<?php

if (!function_exists('mybayar_random_hex')) {
    function mybayar_random_hex($length)
    {
        $length = (int)$length;
        $bytesLength = max(1, (int)ceil($length / 2));
        if (function_exists('random_bytes')) {
            $bytes = random_bytes($bytesLength);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($bytesLength);
        } else {
            $bytes = hash('sha256', uniqid((string)mt_rand(), true), true);
        }

        return substr(bin2hex($bytes), 0, $length);
    }
 

if (!function_exists('mybayar_ensure_payment_infrastructure')) {
    function mybayar_ensure_payment_infrastructure($koneksi)
    {
        $queries = [
            "CREATE TABLE IF NOT EXISTS `payment_channels` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `code` VARCHAR(32) NOT NULL,
                `name` VARCHAR(100) NOT NULL,
                `channel_type` VARCHAR(10) NOT NULL DEFAULT 'QRIS',
                `payload_template` TEXT DEFAULT NULL,
                `payment_code_prefix` VARCHAR(32) DEFAULT NULL,
                `instructions` TEXT DEFAULT NULL,
                `logo_path` VARCHAR(255) DEFAULT NULL,
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `payment_channels_code_unique` (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            "CREATE TABLE IF NOT EXISTS `payment_orders` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `order_no` VARCHAR(40) NOT NULL,
                `idsiswa` INT(11) NOT NULL,
                `idbayar` INT(11) NOT NULL,
                `amount` INT(11) NOT NULL,
                `method_code` VARCHAR(32) NOT NULL,
                `status` VARCHAR(15) NOT NULL DEFAULT 'pending',
                `payment_code` VARCHAR(64) DEFAULT NULL,
                `payload` TEXT DEFAULT NULL,
                `instructions` TEXT DEFAULT NULL,
                `qr_path` VARCHAR(255) DEFAULT NULL,
                `installment_no` INT(11) NOT NULL DEFAULT 1,
                `target_blth` VARCHAR(6) DEFAULT NULL,
                `periode_label` VARCHAR(32) DEFAULT NULL,
                `proof_path` VARCHAR(255) DEFAULT NULL,
                `expired_at` DATETIME DEFAULT NULL,
                `paid_at` DATETIME DEFAULT NULL,
                `created_at` DATETIME DEFAULT NULL,
                `updated_at` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `payment_orders_order_no_unique` (`order_no`),
                KEY `payment_orders_student_idx` (`idsiswa`),
                KEY `payment_orders_bayar_idx` (`idbayar`),
                KEY `payment_orders_method_idx` (`method_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];

        foreach ($queries as $sql) {
            if (!mysqli_query($koneksi, $sql)) {
                $message = 'Gagal menyiapkan tabel pembayaran: ' . mysqli_error($koneksi);
                die($message);
            }
        }

        $check = mysqli_query($koneksi, "SHOW TABLES LIKE 'payment_channels'");
        if ($check && mysqli_num_rows($check) > 0) {
            $exists = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM payment_channels");
            $row = $exists ? mysqli_fetch_assoc($exists) : null;
            if (!$row || (int)$row['total'] === 0) {
                $now = date('Y-m-d H:i:s');
                $defaultName = 'QRIS Utama';
                $defaultCode = 'QRIS_DEFAULT';
                $instructions = "1. Buka aplikasi keuangan / mobile banking\n2. Pilih menu Scan QRIS\n3. Arahkan kamera ke kode yang tampil di sistem\n4. Pastikan nominal sesuai sebelum konfirmasi pembayaran.";
                $payload = json_encode(['message' => 'Ganti payload QRIS dengan format resmi dari mitra pembayaran Anda']);
                mysqli_query($koneksi, "INSERT INTO payment_channels(code, name, channel_type, payload_template, instructions, is_active, created_at) VALUES ('" . mysqli_real_escape_string($koneksi, $defaultCode) . "', '" . mysqli_real_escape_string($koneksi, $defaultName) . "', 'QRIS', '" . mysqli_real_escape_string($koneksi, $payload) . "', '" . mysqli_real_escape_string($koneksi, $instructions) . "', 1, '$now')");
            }
        }
    }
}

if (!function_exists('mybayar_fetch_payment_channels')) {
    function mybayar_fetch_payment_channels($koneksi, $onlyActive = false)
    {
        $onlyActive = (bool)$onlyActive;
        $sql = "SELECT * FROM payment_channels";
        if ($onlyActive) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY name";
        $result = mysqli_query($koneksi, $sql);
        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
        }
        return $data;
    }
}

if (!function_exists('mybayar_find_channel')) {
    function mybayar_find_channel($koneksi, $code)
    {
        $codeEsc = mysqli_real_escape_string($koneksi, $code);
        $res = mysqli_query($koneksi, "SELECT * FROM payment_channels WHERE code='" . $codeEsc . "' LIMIT 1");
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            mysqli_free_result($res);
            return $row ?: null;
        }
        return null;
    }
}

if (!function_exists('mybayar_generate_order_no')) {
    function mybayar_generate_order_no($koneksi, $prefix = 'PAY')
    {
        do {
            $candidate = $prefix . date('YmdHis') . strtoupper(mybayar_random_hex(4));
            $res = mysqli_query($koneksi, "SELECT 1 FROM payment_orders WHERE order_no='" . mysqli_real_escape_string($koneksi, $candidate) . "' LIMIT 1");
            $exists = $res && mysqli_fetch_assoc($res);
            if ($res) {
                mysqli_free_result($res);
            }
        } while ($exists);

        return $candidate;
    }
}

if (!function_exists('mybayar_format_blth')) {
    function mybayar_format_blth($blth)
    {
        if (!$blth || strlen($blth) !== 6) {
            return '';
        }
        $month = substr($blth, 0, 2);
        $year = substr($blth, 2, 4);
        $date = DateTime::createFromFormat('!m Y', $month . ' ' . $year);
        return $date ? $date->format('F Y') : '';
    }
}

if (!function_exists('mybayar_increment_blth')) {
    function mybayar_increment_blth($blth)
    {
        $date = $blth && strlen($blth) === 6
            ? DateTime::createFromFormat('!mY', $blth)
            : new DateTime('first day of this month');
        if (!$date) {
            $date = new DateTime('first day of this month');
        }
        $date->modify('+1 month');
        return $date->format('mY');
    }
}

if (!function_exists('mybayar_latest_blth')) {
    function mybayar_latest_blth($koneksi, $idsiswa, $idbayar)
    {
        $idsiswa = (int)$idsiswa;
        $idbayar = (int)$idbayar;
        $latest = null;
        $res = mysqli_query($koneksi, "SELECT target_blth FROM payment_orders WHERE idsiswa = " . (int)$idsiswa . " AND idbayar = " . (int)$idbayar . " ORDER BY created_at DESC LIMIT 1");
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ($row && $row['target_blth']) {
                $latest = $row['target_blth'];
            }
            mysqli_free_result($res);
        }
        if ($latest) {
            return $latest;
        }

        $res2 = mysqli_query($koneksi, "SELECT blth FROM trx_bayar WHERE idsiswa = " . (int)$idsiswa . " AND idbayar = " . (int)$idbayar . " ORDER BY tanggal DESC LIMIT 1");
        if ($res2) {
            $row2 = mysqli_fetch_assoc($res2);
            if ($row2 && $row2['blth']) {
                $latest = $row2['blth'];
            }
            mysqli_free_result($res2);
        }

        return $latest;
    }
}

if (!function_exists('mybayar_order_summary')) {
    function mybayar_order_summary($koneksi, $idsiswa, $bayar)
    {
        $idsiswa = (int)$idsiswa;
        $idbayar = isset($bayar['id']) ? (int)$bayar['id'] : 0;
        $jumlah = isset($bayar['jumlah']) ? (int)$bayar['jumlah'] : 0;
        $angsuran = isset($bayar['angsuran']) ? (int)$bayar['angsuran'] : 0;
        $total = isset($bayar['total']) ? (int)$bayar['total'] : 0;

        $countPaid = 0;
        $sumPaid = 0;
        $resPaid = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt, COALESCE(SUM(bayar),0) AS total FROM trx_bayar WHERE idsiswa = " . (int)$idsiswa . " AND idbayar = " . (int)$idbayar);
        if ($resPaid) {
            $row = mysqli_fetch_assoc($resPaid);
            if ($row) {
                $countPaid = (int)$row['cnt'];
                $sumPaid = (int)$row['total'];
            }
            mysqli_free_result($resPaid);
        }

        $countPending = 0;
        $resPending = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM payment_orders WHERE idsiswa = " . (int)$idsiswa . " AND idbayar = " . (int)$idbayar . " AND status = 'pending'");
        if ($resPending) {
            $row = mysqli_fetch_assoc($resPending);
            if ($row) {
                $countPending = (int)$row['cnt'];
            }
            mysqli_free_result($resPending);
        }

        $isMonthly = !(isset($bayar['model']) && (int)$bayar['model'] === 1);
        $expectedCount = $isMonthly ? $jumlah : 1;
        $outstanding = max($total - $sumPaid, 0);
        $hasRoom = ($countPaid + $countPending) < $expectedCount || $expectedCount === 0;

        $nextInstallment = $countPaid + $countPending + 1;
        if (!$hasRoom) {
            $nextInstallment = null;
        }

        $lastBlth = mybayar_latest_blth($koneksi, $idsiswa, $idbayar);
        $targetBlth = $lastBlth ? mybayar_increment_blth($lastBlth) : date('mY');

        if (!$isMonthly) {
            $targetBlth = null;
        }

        $amountDue = $isMonthly ? ($angsuran > 0 ? min($angsuran, $outstanding) : $outstanding) : $outstanding;
        if ($amountDue <= 0 && $outstanding > 0) {
            $amountDue = $outstanding;
        }

        return [
            'count_paid'      => $countPaid,
            'count_pending'   => $countPending,
            'expected_count'  => $expectedCount,
            'total_paid'      => $sumPaid,
            'total_expected'  => $total,
            'outstanding'     => $outstanding,
            'is_monthly'      => $isMonthly,
            'can_create'      => $hasRoom && $outstanding > 0,
            'next_installment'=> $nextInstallment,
            'target_blth'     => $isMonthly ? $targetBlth : null,
            'periode_label'   => $isMonthly && $targetBlth ? mybayar_format_blth($targetBlth) : 'Pembayaran',
            'amount_due'      => $amountDue,
        ];
    }
}

if (!function_exists('mybayar_render_template')) {
    function mybayar_render_template($template, $data)
    {
        $placeholders = [];
        foreach ($data as $key => $value) {
            $placeholders['{' . $key . '}'] = $value;
        }
        return strtr($template, $placeholders);
    }
}

if (!function_exists('mybayar_generate_payment_code')) {
    function mybayar_generate_payment_code($channel, $student, $orderContext)
    {
        $prefix = trim(isset($channel['payment_code_prefix']) ? $channel['payment_code_prefix'] : 'PAY');
        $nis = '';
        if (isset($student['nis'])) {
            $nis = $student['nis'];
        } elseif (isset($student['nisn'])) {
            $nis = $student['nisn'];
        } elseif (isset($student['id_siswa'])) {
            $nis = $student['id_siswa'];
        }
        $suffix = substr($orderContext['order_no'], -6);
        return strtoupper($prefix) . $nis . $suffix;
    }
}

if (!function_exists('mybayar_prepare_payload')) {
    function mybayar_prepare_payload($channel, $templateData)
    {
        $raw = isset($channel['payload_template']) ? $channel['payload_template'] : '';
        if ($raw === '' || $raw === null) {
            return null;
        }
        return mybayar_render_template($raw, $templateData);
    }
}

if (!function_exists('mybayar_store_qr_image')) {
    function mybayar_store_qr_image($payload, $orderNo)
    {
        if ($payload === '') {
            return null;
        }
        $root = dirname(__DIR__);
        $targetDir = $root . '/../files/payment_qr';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }
        if (!is_dir($targetDir) || !is_writable($targetDir)) {
            return null;
        }

        if (!class_exists('QRcode')) {
            $qrLibPath = __DIR__ . '/../../vendor/phpqrcode/qrlib.php';
            if (!file_exists($qrLibPath)) {
                return null;
            }
            require_once $qrLibPath;
        }

        $filename = $orderNo . '.png';
        $fullPath = $targetDir . '/' . $filename;
        QRcode::png($payload, $fullPath, QR_ECLEVEL_M, 6);
        return 'files/payment_qr/' . $filename;
    }
}

if (!function_exists('mybayar_create_order')) {
    function mybayar_create_order($koneksi, $student, $bayar, $summary, $channel)
    {
        $idsiswa = isset($student['id_siswa']) ? (int)$student['id_siswa'] : 0;
        $idbayar = isset($bayar['id']) ? (int)$bayar['id'] : 0;
        $amount = isset($summary['amount_due']) ? (int)$summary['amount_due'] : 0;
        $installmentNo = isset($summary['next_installment']) ? (int)$summary['next_installment'] : 1;
        $targetBlth = isset($summary['target_blth']) ? $summary['target_blth'] : null;
        $periodeLabel = isset($summary['periode_label']) ? $summary['periode_label'] : 'Pembayaran';

        if ($amount <= 0) {
            return null;
        }

        $orderNo = mybayar_generate_order_no($koneksi);
        $templateData = [
            'amount'       => $amount,
            'order_no'     => $orderNo,
            'student_id'   => $idsiswa,
            'student_nis'  => isset($student['nis']) ? $student['nis'] : '',
            'student_name' => isset($student['nama']) ? $student['nama'] : '',
            'kelas'        => isset($student['kelas']) ? $student['kelas'] : '',
            'kode_bayar'   => isset($bayar['kode']) ? $bayar['kode'] : '',
        ];

        $payload = mybayar_prepare_payload($channel, $templateData);
        $paymentCode = null;
        $qrPath = null;
        $instructions = isset($channel['instructions']) ? $channel['instructions'] : '';

        $channelType = isset($channel['channel_type']) ? $channel['channel_type'] : 'QRIS';

        // Jika payload_template berformat JSON dan berisi provider Tripay,
        // delegasikan pembuatan kode bayar/QR ke Tripay.
        $maybeJson = isset($channel['payload_template']) ? $channel['payload_template'] : '';
        $config = null;
        if ($maybeJson && substr(trim($maybeJson), 0, 1) === '{') {
            $decoded = json_decode($maybeJson, true);
            if (is_array($decoded) && isset($decoded['provider']) && strtolower($decoded['provider']) === 'tripay') {
                require_once __DIR__ . '/providers/tripay.php';
                $res = tripay_create_transaction($koneksi, $decoded, $orderNo, $amount, $student, $bayar);
                if (isset($res['ok']) && $res['ok'] === true) {
                    if (!empty($res['qr_string'])) {
                        $payload = $res['qr_string'];
                        $qrPath = mybayar_store_qr_image($payload, $orderNo);
                    }
                    if (!empty($res['pay_code'])) {
                        $paymentCode = $res['pay_code'];
                    }
                    if (!empty($res['instructions'])) {
                        $instructions = $res['instructions'];
                    }
                }
            }
        }

        if ($paymentCode === null && $qrPath === null) {
            if ($channelType === 'QRIS') {
                if (!$payload) {
                    $payload = json_encode([
                        'order' => $orderNo,
                        'amount' => $amount,
                    ]);
                }
                $qrPath = mybayar_store_qr_image($payload, $orderNo);
            } else {
                $paymentCode = mybayar_generate_payment_code($channel, $student, ['order_no' => $orderNo]);
                $templateData['payment_code'] = $paymentCode;
                if ($instructions) {
                    $instructions = mybayar_render_template($instructions, $templateData);
                }
            }
        }

        $now = date('Y-m-d H:i:s');
        $methodCode = isset($channel['code']) ? $channel['code'] : '';
        $orderEsc = mysqli_real_escape_string($koneksi, $orderNo);
        $methodEsc = mysqli_real_escape_string($koneksi, (string)$methodCode);
        $paymentCodeSql = $paymentCode !== null ? "'" . mysqli_real_escape_string($koneksi, (string)$paymentCode) . "'" : 'NULL';
        $payloadSql = $payload !== null ? "'" . mysqli_real_escape_string($koneksi, (string)$payload) . "'" : 'NULL';
        $instructionsSql = $instructions !== '' ? "'" . mysqli_real_escape_string($koneksi, $instructions) . "'" : 'NULL';
        $qrPathSql = $qrPath !== null ? "'" . mysqli_real_escape_string($koneksi, $qrPath) . "'" : 'NULL';
        $targetBlthSql = $targetBlth !== null ? "'" . mysqli_real_escape_string($koneksi, $targetBlth) . "'" : 'NULL';
        $periodeLabelSql = $periodeLabel !== null ? "'" . mysqli_real_escape_string($koneksi, $periodeLabel) . "'" : 'NULL';
        $createdSql = "'" . mysqli_real_escape_string($koneksi, $now) . "'";
        $sql = sprintf(
            "INSERT INTO payment_orders(order_no, idsiswa, idbayar, amount, method_code, status, payment_code, payload, instructions, qr_path, installment_no, target_blth, periode_label, created_at, updated_at) VALUES('%s', %d, %d, %d, '%s', 'pending', %s, %s, %s, %s, %d, %s, %s, %s, %s)",
            $orderEsc,
            $idsiswa,
            $idbayar,
            $amount,
            $methodEsc,
            $paymentCodeSql,
            $payloadSql,
            $instructionsSql,
            $qrPathSql,
            $installmentNo,
            $targetBlthSql,
            $periodeLabelSql,
            $createdSql,
            $createdSql
        );
        if (!mysqli_query($koneksi, $sql)) {
            return null;
        }

        return [
            'order_no'      => $orderNo,
            'amount'        => $amount,
            'method_code'   => $methodCode,
            'status'        => 'pending',
            'payment_code'  => $paymentCode,
            'payload'       => $payload,
            'qr_path'       => $qrPath,
            'instructions'  => $instructions,
            'installment_no'=> $installmentNo,
            'target_blth'   => $targetBlth,
            'periode_label' => $periodeLabel,
            'created_at'    => $now,
        ];
    }
}

if (!function_exists('mybayar_order_payload_for_display')) {
    function mybayar_order_payload_for_display($order)
    {
        return [
            'order_no'      => isset($order['order_no']) ? $order['order_no'] : '',
            'amount'        => isset($order['amount']) ? (int)$order['amount'] : 0,
            'method_code'   => isset($order['method_code']) ? $order['method_code'] : '',
            'status'        => isset($order['status']) ? $order['status'] : 'pending',
            'payment_code'  => isset($order['payment_code']) ? $order['payment_code'] : null,
            'payload'       => isset($order['payload']) ? $order['payload'] : null,
            'qr_path'       => isset($order['qr_path']) ? $order['qr_path'] : null,
            'instructions'  => isset($order['instructions']) ? $order['instructions'] : '',
            'installment_no'=> isset($order['installment_no']) ? (int)$order['installment_no'] : 1,
            'target_blth'   => isset($order['target_blth']) ? $order['target_blth'] : null,
            'periode_label' => isset($order['periode_label']) ? $order['periode_label'] : '',
            'created_at'    => isset($order['created_at']) ? $order['created_at'] : null,
            'paid_at'       => isset($order['paid_at']) ? $order['paid_at'] : null,
        ];
    }
}

if (!function_exists('mybayar_fetch_orders_by_student')) {
    function mybayar_fetch_orders_by_student($koneksi, $idsiswa, $idbayar = null)
    {
        $sql = "SELECT * FROM payment_orders WHERE idsiswa = " . (int)$idsiswa;
        if ($idbayar) {
            $sql .= " AND idbayar = " . (int)$idbayar;
        }
        $sql .= " ORDER BY created_at DESC";
        $res = mysqli_query($koneksi, $sql);
        $rows = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rows[] = $row;
            }
            mysqli_free_result($res);
        }
        return $rows;
    }
}

if (!function_exists('mybayar_mark_order')) {
    function mybayar_mark_order($koneksi, $orderNo, $status, $proofPath = null)
    {
        $allowed = ['pending', 'paid', 'cancelled', 'expired'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        $now = date('Y-m-d H:i:s');
        $orderEsc = mysqli_real_escape_string($koneksi, $orderNo);
        $proofSql = $proofPath !== null ? "'" . mysqli_real_escape_string($koneksi, $proofPath) . "'" : 'proof_path';
        $paidAtSql = ($status === 'paid') ? "'$now'" : 'paid_at';
        $sql = "UPDATE payment_orders SET status = '" . mysqli_real_escape_string($koneksi, $status) . "', updated_at = '$now', paid_at = $paidAtSql, proof_path = $proofSql WHERE order_no = '$orderEsc'";
        mysqli_query($koneksi, $sql);
        return mysqli_affected_rows($koneksi) > 0;
    }
}

if (!function_exists('mybayar_insert_trx_from_order')) {
    function mybayar_insert_trx_from_order($koneksi, $order, $student, $bayar, $setting)
    {
        $idsiswa = isset($order['idsiswa']) ? (int)$order['idsiswa'] : 0;
        $idbayar = isset($order['idbayar']) ? (int)$order['idbayar'] : 0;
        $kelas = isset($student['kelas']) ? $student['kelas'] : '';
        $blth = isset($order['target_blth']) ? $order['target_blth'] : date('mY');
        $tanggal = date('Y-m-d');
        $bayarAmount = isset($order['amount']) ? (int)$order['amount'] : 0;
        $ke = isset($order['installment_no']) ? (int)$order['installment_no'] : 1;
        $bukti = isset($order['order_no']) ? $order['order_no'] : '';

        $sql = sprintf(
            "INSERT INTO trx_bayar(tanggal, blth, idsiswa, kelas, idbayar, bayar, ke, bukti) VALUES('%s','%s',%d,'%s',%d,%d,%d,'%s')",
            mysqli_real_escape_string($koneksi, $tanggal),
            mysqli_real_escape_string($koneksi, $blth),
            $idsiswa,
            mysqli_real_escape_string($koneksi, $kelas),
            $idbayar,
            $bayarAmount,
            $ke,
            mysqli_real_escape_string($koneksi, $bukti)
        );
        $ok = mysqli_query($koneksi, $sql);
        if (!$ok) {
            return false;
        }

        if (!empty($setting['url_api']) && !empty($student['nowa'])) {
            $pesan = "STRUK PEMBAYARAN" . "\n" .
                "Nama : " . (isset($student['nama']) ? $student['nama'] : '') . "\n" .
                "Kelas : " . (isset($student['kelas']) ? $student['kelas'] : '') . "\n" .
                "Jenis : TRX " . (isset($bayar['kode']) ? $bayar['kode'] : '') . "\n" .
                "Nominal : RP. " . number_format($bayarAmount) . "\n" .
                "Tgl : " . date('d-m-Y H:i') . "\n" .
                "Ref : " . $bukti;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => rtrim($setting['url_api'], '/') . '/send-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'message' => $pesan,
                    'number' => $student['nowa'],
                ],
                CURLOPT_TIMEOUT => 10,
            ]);
            curl_exec($curl);
            curl_close($curl);
        }

        return true;
    }
}
