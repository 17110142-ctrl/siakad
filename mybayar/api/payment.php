<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once __DIR__ . '/../lib/payment.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_siswa'])) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan login sebagai siswa']);
    exit;
}

$id_siswa = (int)$_SESSION['id_siswa'];

mybayar_ensure_payment_infrastructure($koneksi);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function payment_response($status, $message = '', $extra = [])
{
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

$student = fetch($koneksi, 'siswa', ['id_siswa' => $id_siswa]);
if (!$student) {
    payment_response('error', 'Data siswa tidak ditemukan');
}

if ($action === 'create_order') {
    $idbayar = (int)($_POST['idbayar'] ?? 0);
    $methodCode = $_POST['method_code'] ?? '';

    if ($idbayar <= 0 || $methodCode === '') {
        payment_response('error', 'Parameter tidak lengkap');
    }

    $bayar = fetch($koneksi, 'm_bayar', ['id' => $idbayar]);
    if (!$bayar) {
        payment_response('error', 'Jenis pembayaran tidak ditemukan');
    }

    $channel = mybayar_find_channel($koneksi, $methodCode);
    if (!$channel || (int)$channel['is_active'] !== 1) {
        payment_response('error', 'Channel pembayaran tidak tersedia');
    }

    $summary = mybayar_order_summary($koneksi, $id_siswa, $bayar);
    if (!$summary['can_create']) {
        payment_response('error', 'Tidak ada tagihan yang perlu dibayar atau masih menunggu verifikasi sebelumnya');
    }

    $order = mybayar_create_order($koneksi, $student, $bayar, $summary, $channel);
    if (!$order) {
        payment_response('error', 'Gagal membuat order');
    }

    payment_response('ok', 'Order berhasil dibuat', ['order' => $order]);
}

if ($action === 'list_orders') {
    $orders = mybayar_fetch_orders_by_student($koneksi, $id_siswa);
    $payload = array_map('mybayar_order_payload_for_display', $orders);
    payment_response('ok', 'Data order', ['orders' => $payload]);
}

if ($action === 'cancel_order') {
    $orderNo = $_POST['order_no'] ?? '';
    if ($orderNo === '') {
        payment_response('error', 'Order tidak ditemukan');
    }
    $orders = mybayar_fetch_orders_by_student($koneksi, $id_siswa);
    $order = null;
    foreach ($orders as $row) {
        if ($row['order_no'] === $orderNo) {
            $order = $row;
            break;
        }
    }
    if (!$order) {
        payment_response('error', 'Order tidak ditemukan');
    }
    if ($order['status'] !== 'pending') {
        payment_response('error', 'Order tidak dapat dibatalkan');
    }
    $ok = mybayar_mark_order($koneksi, $orderNo, 'cancelled');
    if (!$ok) {
        payment_response('error', 'Gagal membatalkan order');
    }
    payment_response('ok', 'Order dibatalkan');
}

if ($action === 'summary') {
    $mBayar = [];
    $result = mysqli_query($koneksi, "SELECT * FROM m_bayar ORDER BY nama");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $summary = mybayar_order_summary($koneksi, $id_siswa, $row);
            $mBayar[] = [
                'data'    => $row,
                'summary' => $summary,
            ];
        }
        mysqli_free_result($result);
    }
    $orders = mybayar_fetch_orders_by_student($koneksi, $id_siswa);
    payment_response('ok', 'Ringkasan', [
        'payments' => $mBayar,
        'orders'   => array_map('mybayar_order_payload_for_display', $orders)
    ]);
}

payment_response('error', 'Aksi tidak dikenali');
