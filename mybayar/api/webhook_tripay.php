<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once __DIR__ . '/../lib/payment.php';

// Tripay mengirim callback JSON + header X-Callback-Signature
$raw = file_get_contents('php://input');
$signature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';
$event = isset($_SERVER['HTTP_X_CALLBACK_EVENT']) ? $_SERVER['HTTP_X_CALLBACK_EVENT'] : '';

// Cari private_key dari channel Tripay (ambil satu yang aktif)
$q = mysqli_query($koneksi, "SELECT payload_template FROM payment_channels WHERE is_active=1");
$privateKey = '';
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
        $tpl = isset($r['payload_template']) ? $r['payload_template'] : '';
        if ($tpl && substr(trim($tpl), 0, 1) === '{') {
            $cfg = json_decode($tpl, true);
            if (is_array($cfg) && isset($cfg['provider']) && strtolower($cfg['provider']) === 'tripay') {
                $privateKey = isset($cfg['private_key']) ? $cfg['private_key'] : '';
                if ($privateKey) break;
            }
        }
    }
}

http_response_code(200);

if (!$privateKey) {
    exit('NO KEY');
}

$calc = hash_hmac('sha256', $raw, $privateKey);
if ($signature !== $calc) {
    exit('INVALID SIGNATURE');
}

$data = json_decode($raw, true);
if (!is_array($data)) {
    exit('INVALID PAYLOAD');
}

// Tripay mengirim status pada event payment_status
if (strtolower($event) === 'payment_status') {
    $status = isset($data['status']) ? strtoupper($data['status']) : '';
    $merchantRef = isset($data['merchant_ref']) ? $data['merchant_ref'] : '';

    if ($merchantRef !== '') {
        if ($status === 'PAID' || $status === 'SUCCESS') {
            // tandai paid + masukkan ke trx_bayar
            $order = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM payment_orders WHERE order_no='" . mysqli_real_escape_string($koneksi, $merchantRef) . "' LIMIT 1"));
            if ($order) {
                $student = fetch($koneksi, 'siswa', ['id_siswa' => $order['idsiswa']]);
                $byr = fetch($koneksi, 'm_bayar', ['id' => $order['idbayar']]);
                mybayar_mark_order($koneksi, $merchantRef, 'paid');
                global $setting;
                mybayar_insert_trx_from_order($koneksi, $order, $student, $byr, $setting);
            }
        } elseif ($status === 'EXPIRED' || $status === 'FAILED') {
            mybayar_mark_order($koneksi, $merchantRef, strtolower($status));
        }
    }
}

echo 'OK';
?>

