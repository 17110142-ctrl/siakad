<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once __DIR__ . '/../lib/payment.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Tidak terautentikasi']);
    exit;
}

mybayar_ensure_payment_infrastructure($koneksi);

$action = $_POST['action'] ?? '';

function respond($status, $message = '', $extra = [])
{
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

if ($action === 'save_channel') {
    $originalCode = trim($_POST['original_code'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $channelType = $_POST['channel_type'] ?? 'QRIS';
    $prefix = trim($_POST['payment_code_prefix'] ?? '');
    $payload = $_POST['payload_template'] ?? '';
    $instructions = $_POST['instructions'] ?? '';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '' || $code === '') {
        respond('error', 'Nama dan kode wajib diisi');
    }

    if (!in_array($channelType, ['QRIS', 'PAYCODE'], true)) {
        respond('error', 'Tipe channel tidak valid');
    }

    if ($originalCode && $originalCode !== $code) {
        $cek = mybayar_find_channel($koneksi, $code);
        if ($cek) {
            respond('error', 'Kode channel sudah dipakai');
        }
    }

    $now = date('Y-m-d H:i:s');
    if ($originalCode) {
        $stmt = mysqli_prepare($koneksi, "UPDATE payment_channels SET name=?, channel_type=?, payment_code_prefix=?, payload_template=?, instructions=?, is_active=?, updated_at=? WHERE code=?");
        if (!$stmt) {
            respond('error', 'Gagal mempersiapkan query');
        }
        mysqli_stmt_bind_param($stmt, 'sssssiis', $name, $channelType, $prefix, $payload, $instructions, $isActive, $now, $originalCode);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$ok) {
            respond('error', 'Gagal memperbarui channel');
        }
        if ($originalCode !== $code) {
            $stmt2 = mysqli_prepare($koneksi, "UPDATE payment_channels SET code=? WHERE code=?");
            if ($stmt2) {
                mysqli_stmt_bind_param($stmt2, 'ss', $code, $originalCode);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
            }
        }
    } else {
        $cek = mybayar_find_channel($koneksi, $code);
        if ($cek) {
            respond('error', 'Kode channel sudah dipakai');
        }
        $stmt = mysqli_prepare($koneksi, "INSERT INTO payment_channels(code, name, channel_type, payment_code_prefix, payload_template, instructions, is_active, created_at) VALUES(?,?,?,?,?,?,?,?)");
        if (!$stmt) {
            respond('error', 'Gagal mempersiapkan query');
        }
        mysqli_stmt_bind_param($stmt, 'ssssssis', $code, $name, $channelType, $prefix, $payload, $instructions, $isActive, $now);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$ok) {
            respond('error', 'Gagal menyimpan channel');
        }
    }

    respond('ok', 'Channel tersimpan');
}

if ($action === 'toggle_channel') {
    $code = $_POST['code'] ?? '';
    $toActive = isset($_POST['to_active']) && $_POST['to_active'] === '1' ? 1 : 0;
    if ($code === '') {
        respond('error', 'Kode channel tidak ditemukan');
    }
    $stmt = mysqli_prepare($koneksi, "UPDATE payment_channels SET is_active = ?, updated_at = ? WHERE code = ?");
    if (!$stmt) {
        respond('error', 'Gagal mempersiapkan query');
    }
    $now = date('Y-m-d H:i:s');
    mysqli_stmt_bind_param($stmt, 'iss', $toActive, $now, $code);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$ok) {
        respond('error', 'Gagal memperbarui status');
    }
    respond('ok', 'Status diperbarui');
}

if ($action === 'delete_channel') {
    $code = $_POST['code'] ?? '';
    if ($code === '') {
        respond('error', 'Kode channel tidak ditemukan');
    }
    $stmt = mysqli_prepare($koneksi, "DELETE FROM payment_channels WHERE code = ?");
    if (!$stmt) {
        respond('error', 'Gagal mempersiapkan query');
    }
    mysqli_stmt_bind_param($stmt, 's', $code);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if (!$ok) {
        respond('error', 'Gagal menghapus channel');
    }
    respond('ok', 'Channel terhapus');
}

function payment_admin_fetch_order(mysqli $koneksi, string $orderNo): ?array
{
    $orderEsc = mysqli_real_escape_string($koneksi, $orderNo);
    $res = mysqli_query($koneksi, "SELECT * FROM payment_orders WHERE order_no = '" . $orderEsc . "' LIMIT 1");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        mysqli_free_result($res);
        return $row ?: null;
    }
    return null;
}

if ($action === 'mark_paid') {
    $orderNo = $_POST['order_no'] ?? '';
    if ($orderNo === '') {
        respond('error', 'Order tidak ditemukan');
    }
    $order = payment_admin_fetch_order($koneksi, $orderNo);
    if (!$order) {
        respond('error', 'Order tidak ditemukan');
    }
    if ($order['status'] !== 'pending') {
        respond('error', 'Status order tidak valid untuk konfirmasi');
    }

    $student = fetch($koneksi, 'siswa', ['id_siswa' => $order['idsiswa']]);
    $bayar = fetch($koneksi, 'm_bayar', ['id' => $order['idbayar']]);
    if (!$student || !$bayar) {
        respond('error', 'Data siswa atau pembayaran tidak ditemukan');
    }

    $setting = $GLOBALS['setting'] ?? [];

    $ok = mybayar_mark_order($koneksi, $orderNo, 'paid');
    if (!$ok) {
        respond('error', 'Gagal memperbarui status order');
    }
    $order = payment_admin_fetch_order($koneksi, $orderNo);
    if (!$order) {
        respond('error', 'Order tidak ditemukan setelah pembaruan');
    }

    mybayar_insert_trx_from_order($koneksi, $order, $student, $bayar, $setting);

    respond('ok', 'Order dikonfirmasi');
}

if ($action === 'cancel_order') {
    $orderNo = $_POST['order_no'] ?? '';
    if ($orderNo === '') {
        respond('error', 'Order tidak ditemukan');
    }
    $order = payment_admin_fetch_order($koneksi, $orderNo);
    if (!$order) {
        respond('error', 'Order tidak ditemukan');
    }
    if ($order['status'] !== 'pending') {
        respond('error', 'Order tidak dapat dibatalkan');
    }
    $ok = mybayar_mark_order($koneksi, $orderNo, 'cancelled');
    if (!$ok) {
        respond('error', 'Gagal memperbarui status');
    }
    respond('ok', 'Order dibatalkan');
}

respond('error', 'Aksi tidak dikenali');
