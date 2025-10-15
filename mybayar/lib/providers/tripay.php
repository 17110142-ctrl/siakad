<?php

// Integrasi sederhana Tripay untuk QRIS/VA.
// Catatan: Anda harus mengisi credential pada payment_channels.payload_template dengan JSON:
{
   "provider":"tripay",
   "api_key":"xxxx",
   "private_key":"ytf6ooi2gmlNPfpchd94jDOk8hRWOu",
   "merchant_code":"T0000",
   "method":"QRIS",           // atau kode VA Tripay, misal: "BRIVA" / "BNIVA" / "MANDIRIVA" dst
   "callback_url":"https://domain-anda/mybayar/api/webhook_tripay.php"
}

if (!function_exists('tripay_request')) {
    function tripay_request($endpoint, $apiKey, $payload = [], $method = 'POST')
    {
        $url = 'https://tripay.co.id/api/' . ltrim($endpoint, '/');
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [ 'Authorization: Bearer ' . $apiKey ],
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        }
        $res = curl_exec($ch);
        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => $err];
        }
        curl_close($ch);
        $json = json_decode($res, true);
        if (!is_array($json)) {
            return ['success' => false, 'error' => 'Invalid response'];
        }
        return $json;
    }
}

if (!function_exists('tripay_create_transaction')) {
    function tripay_create_transaction($koneksi, $config, $orderNo, $amount, $student, $bayar)
    {
        $apiKey      = isset($config['api_key']) ? $config['api_key'] : '';
        $privateKey  = isset($config['private_key']) ? $config['private_key'] : '';
        $merchant    = isset($config['merchant_code']) ? $config['merchant_code'] : '';
        $method      = isset($config['method']) ? $config['method'] : 'QRIS';
        $callbackUrl = isset($config['callback_url']) ? $config['callback_url'] : '';

        if ($apiKey === '' || $privateKey === '' || $merchant === '') {
            return ['ok' => false, 'message' => 'Konfigurasi Tripay tidak lengkap'];
        }

        $payload = [
            'method'        => $method,
            'merchant_ref'  => $orderNo,
            'amount'        => (int)$amount,
            'customer_name' => isset($student['nama']) ? $student['nama'] : 'Siswa',
            'customer_email'=> 'no-reply@example.com',
            'customer_phone'=> isset($student['nowa']) ? $student['nowa'] : '0800000000',
            'order_items'   => json_encode([
                [
                    'sku'         => isset($bayar['kode']) ? $bayar['kode'] : 'TAGIHAN',
                    'name'        => isset($bayar['nama']) ? $bayar['nama'] : 'Pembayaran Sekolah',
                    'price'       => (int)$amount,
                    'quantity'    => 1,
                ],
            ]),
            'return_url'    => '',
            'expired_time'  => time() + (24 * 60 * 60),
            'signature'     => hash_hmac('sha256', $merchant.$orderNo.$amount, $privateKey),
            'callback_url'  => $callbackUrl,
        ];

        $resp = tripay_request('transaction/create', $apiKey, $payload, 'POST');

        if (!is_array($resp) || (isset($resp['success']) && $resp['success'] !== true)) {
            $msg = isset($resp['message']) ? $resp['message'] : 'Gagal membuat transaksi Tripay';
            return ['ok' => false, 'message' => $msg, 'raw' => $resp];
        }

        $data = isset($resp['data']) ? $resp['data'] : [];
        $qrUrl = isset($data['qr_url']) ? $data['qr_url'] : null;
        $qrString = isset($data['qr_string']) ? $data['qr_string'] : null;
        $payCode = isset($data['pay_code']) ? $data['pay_code'] : null; // untuk VA
        $instructions = '';
        if (isset($data['instructions']) && is_array($data['instructions'])) {
            foreach ($data['instructions'] as $inst) {
                if (isset($inst['steps']) && is_array($inst['steps'])) {
                    foreach ($inst['steps'] as $s) {
                        $instructions .= "- " . $s . "\n";
                    }
                }
            }
        }

        return [
            'ok'          => true,
            'qr_string'   => $qrString,
            'qr_url'      => $qrUrl,
            'pay_code'    => $payCode,
            'instructions'=> trim($instructions),
            'raw'         => $data,
        ];
    }
}

