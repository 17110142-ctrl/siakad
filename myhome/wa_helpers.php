<?php
// Utilitas kirim WA + log untuk modul myhome (absensi)
if (!defined('WA_HELPERS_INCLUDED')) {
    define('WA_HELPERS_INCLUDED', true);

    function wa_normalize_number($number, $default_cc = '62') {
        $n = trim((string)$number);
        $n = preg_replace('/[^0-9+]/', '', $n);
        if ($n === '') return '';
        if ($n[0] === '+') $n = substr($n, 1);
        if ($n[0] === '0') {
            $n = $default_cc . substr($n, 1);
        } elseif ($n[0] === '8') {
            $n = $default_cc . $n;
        }
        if (strpos($n, '620') === 0 && isset($n[3]) && $n[3] === '8') {
            $n = $default_cc . substr($n, 3);
        }
        return $n;
    }

    function wa_format_tanggal_indonesia($datetime = 'now', $with_day = true) {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        if ($datetime instanceof DateTimeInterface) {
            $timestamp = $datetime->getTimestamp();
        } elseif (is_numeric($datetime)) {
            $timestamp = (int)$datetime;
        } else {
            $timestamp = strtotime((string)$datetime);
            if ($timestamp === false) {
                $timestamp = time();
            }
        }

        $dayPart = '';
        if ($with_day) {
            $dayIndex = (int)date('N', $timestamp);
            $dayPart = ($days[$dayIndex] ?? date('l', $timestamp)) . ', ';
        }

        $monthIndex = (int)date('n', $timestamp);
        $monthName = $months[$monthIndex] ?? date('F', $timestamp);

        return $dayPart . date('d', $timestamp) . ' ' . $monthName . ' ' . date('Y', $timestamp);
    }

    function wa_is_retryable_http($code, $curl_err = '') {
        if ((int)$code === 0) return true;
        $retryables = [408, 425, 429, 500, 502, 503, 504];
        if (in_array((int)$code, $retryables, true)) return true;
        $e = strtolower((string)$curl_err);
        if ($e && (strpos($e, 'timed out') !== false || strpos($e, 'could not resolve') !== false || strpos($e, 'failed to connect') !== false)) {
            return true;
        }
        return false;
    }

    function wa_throttle_send($min_interval_ms = 250) {
        $lock = __DIR__ . '/wa_throttle.lock';
        $fh = @fopen($lock, 'c+');
        if (!$fh) { usleep($min_interval_ms*1000); return; }
        if (@flock($fh, LOCK_EX)) {
            $last = 0.0;
            $buf = stream_get_contents($fh);
            if ($buf !== false && $buf !== '') { $last = (float)$buf; }
            $now = microtime(true);
            $delta_ms = ($now - $last) * 1000.0;
            if ($delta_ms < $min_interval_ms) {
                usleep((int)(($min_interval_ms - $delta_ms) * 1000));
            }
            ftruncate($fh, 0); rewind($fh); fwrite($fh, (string)microtime(true)); fflush($fh);
            flock($fh, LOCK_UN);
        }
        fclose($fh);
    }

    function wa_send_with_retry_simple($api_base, $message, $number, $max_attempts = 3) {
        $api = rtrim((string)$api_base, '/') . '/send-message';
        $attempt = 0; $last_code = 0; $last_err = ''; $last_resp = '';
        while ($attempt < $max_attempts) {
            $attempt++;
            wa_throttle_send(250); // throttle agar tidak ter-rate-limit gateway
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $api,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 6,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTPHEADER => ['Accept: application/json','Connection: keep-alive','Expect:'],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => ['message' => $message, 'number' => $number],
            ]);
            $resp = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resp === false) $last_err = curl_error($ch);
            curl_close($ch);
            $last_code = (int)$code; $last_resp = (string)$resp;

            // Default oke berdasarkan HTTP code 2xx
            $ok = ($last_code >= 200 && $last_code < 300);
            if ($last_resp) {
                $j = json_decode($last_resp, true);
                if (is_array($j)) {
                    // Jika gateway mengembalikan field boolean 'success'
                    if (array_key_exists('success', $j)) {
                        $ok = (bool)$j['success'];
                    }
                    // Interpretasi umum field 'status'
                    if (array_key_exists('status', $j)) {
                        $st = $j['status'];
                        // Normalisasi ke string lower untuk perbandingan
                        $st_str = is_bool($st) ? ($st ? 'true' : 'false') : strtolower((string)$st);
                        // Nilai yang dianggap sukses
                        $successVals = ['ok','success','true','1','sent'];
                        $failVals    = ['error','fail','failed','false','0'];
                        if (in_array($st_str, $successVals, true)) {
                            $ok = true;
                        } elseif (in_array($st_str, $failVals, true)) {
                            $ok = false;
                            $last_err = isset($j['message']) ? (string)$j['message'] : 'Gateway returned error status';
                        } // jika status tidak dikenal, biarkan berdasarkan HTTP code
                    }
                }
            }
            if ($ok) {
                return [true, $last_code, ''];
            }
            if ($attempt < $max_attempts && wa_is_retryable_http($last_code, $last_err)) {
                $base = 400; // ms
                $delay_ms = (int)($base * pow(2, $attempt - 1) + random_int(40, 180));
                usleep($delay_ms * 1000);
                continue;
            }
            break;
        }
        return [false, $last_code, $last_err ?: 'Unknown error'];
    }

    function wa_ensure_log_table($koneksi) {
        $sql = "CREATE TABLE IF NOT EXISTS wa_absen_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            tanggal DATE NOT NULL,
            jenis ENUM('masuk','pulang','lain') DEFAULT 'lain',
            level ENUM('siswa','pegawai') DEFAULT 'siswa',
            idsiswa INT NULL,
            idpeg INT NULL,
            nama VARCHAR(100) NULL,
            number VARCHAR(32) NULL,
            message TEXT NULL,
            success TINYINT(1) NOT NULL DEFAULT 0,
            http_code INT NOT NULL DEFAULT 0,
            error TEXT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($koneksi, $sql);
    }

    function wa_metrics_file_path() {
        return __DIR__ . '/temp/wa_absen_metrics.json';
    }

    function wa_metrics_append($entry) {
        $path = wa_metrics_file_path();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $fh = @fopen($path, 'c+');
        if (!$fh) {
            return;
        }

        if (!@flock($fh, LOCK_EX)) {
            fclose($fh);
            return;
        }

        $size = 0;
        $stat = fstat($fh);
        if ($stat && isset($stat['size'])) {
            $size = (int)$stat['size'];
        }

        $raw = '';
        if ($size > 0) {
            $raw = fread($fh, $size);
        }

        $data = [];
        if ($raw !== '') {
            $tmp = json_decode($raw, true);
            if (is_array($tmp)) {
                $data = $tmp;
            }
        }

        $tanggal = $entry['tanggal'] ?? date('Y-m-d');
        $tanggal = (string)$tanggal;
        $waktu = $entry['waktu'] ?? date('H:i:s');
        $record = [
            'timestamp' => isset($entry['timestamp']) ? (string)$entry['timestamp'] : (date('Y-m-d') . ' ' . $waktu),
            'waktu'     => (string)$waktu,
            'nama'      => (string)($entry['nama'] ?? ''),
            'number'    => (string)($entry['number'] ?? ''),
            'jenis'     => (string)($entry['jenis'] ?? 'lain'),
            'level'     => (string)($entry['level'] ?? 'siswa'),
            'success'   => !empty($entry['success']) ? 1 : 0,
            'error'     => (string)($entry['error'] ?? ''),
            'log_id'    => isset($entry['log_id']) ? (string)$entry['log_id'] : '',
            'http_code' => isset($entry['http_code']) ? (int)$entry['http_code'] : 0,
            'message'   => (string)($entry['message'] ?? '')
        ];
        if ($record['log_id'] === '') {
            $record['log_id'] = (string) round(microtime(true) * 1000);
        }
        $record['id'] = $record['log_id'];

        if (!isset($data[$tanggal])) {
            $data[$tanggal] = [
                'summary' => ['total' => 0, 'sent' => 0, 'failed' => 0],
                'events' => []
            ];
        }

        $data[$tanggal]['summary']['total']++;
        if ($record['success'] === 1) {
            $data[$tanggal]['summary']['sent']++;
        } else {
            $data[$tanggal]['summary']['failed']++;
        }

        $data[$tanggal]['events'][] = $record;

        $maxEventsPerDay = 200;
        $eventCount = count($data[$tanggal]['events']);
        if ($eventCount > $maxEventsPerDay) {
            $data[$tanggal]['events'] = array_slice($data[$tanggal]['events'], $eventCount - $maxEventsPerDay);
        }

        $maxDays = 7;
        $allDates = array_keys($data);
        if (count($allDates) > $maxDays) {
            sort($allDates);
            while (count($allDates) > $maxDays) {
                $old = array_shift($allDates);
                unset($data[$old]);
            }
        }

        rewind($fh);
        ftruncate($fh, 0);
        fwrite($fh, json_encode($data, JSON_UNESCAPED_UNICODE));
        fflush($fh);
        flock($fh, LOCK_UN);
        fclose($fh);
    }

    function wa_metrics_get_day($date = null) {
        $date = $date ?: date('Y-m-d');
        $path = wa_metrics_file_path();
        if (!is_file($path)) {
            return ['summary' => ['total' => 0, 'sent' => 0, 'failed' => 0], 'events' => []];
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return ['summary' => ['total' => 0, 'sent' => 0, 'failed' => 0], 'events' => []];
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return ['summary' => ['total' => 0, 'sent' => 0, 'failed' => 0], 'events' => []];
        }
        if (!isset($data[$date]) || !is_array($data[$date])) {
            return ['summary' => ['total' => 0, 'sent' => 0, 'failed' => 0], 'events' => []];
        }
        $day = $data[$date];
        if (!isset($day['summary']) || !is_array($day['summary'])) {
            $day['summary'] = ['total' => 0, 'sent' => 0, 'failed' => 0];
        }
        if (!isset($day['events']) || !is_array($day['events'])) {
            $day['events'] = [];
        }
        return [
            'summary' => array_merge(['total' => 0, 'sent' => 0, 'failed' => 0], $day['summary']),
            'events' => $day['events']
        ];
    }

    function wa_metrics_get_event($logId) {
        $logId = (string)$logId;
        $path = wa_metrics_file_path();
        if ($logId === '' || !is_file($path)) {
            return null;
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }
        $data = json_decode($raw, true);
        if (!is_array($data)) {
            return null;
        }
        foreach ($data as $date => $day) {
            if (!isset($day['events']) || !is_array($day['events'])) {
                continue;
            }
            foreach ($day['events'] as $event) {
                if ((string)($event['log_id'] ?? '') === $logId) {
                    $event['date'] = $date;
                    return $event;
                }
            }
        }
        return null;
    }

    function wa_metrics_update_log($logId, $success, $error = '', $httpCode = 0) {
        $logId = (string)$logId;
        $path = wa_metrics_file_path();
        if ($logId === '' || !is_file($path)) {
            return;
        }

        $fh = @fopen($path, 'c+');
        if (!$fh) {
            return;
        }
        if (!@flock($fh, LOCK_EX)) {
            fclose($fh);
            return;
        }

        $size = 0;
        $stat = fstat($fh);
        if ($stat && isset($stat['size'])) {
            $size = (int)$stat['size'];
        }

        $raw = '';
        if ($size > 0) {
            $raw = fread($fh, $size);
        }

        $data = [];
        if ($raw !== '') {
            $tmp = json_decode($raw, true);
            if (is_array($tmp)) {
                $data = $tmp;
            }
        }

        $updated = false;
        foreach ($data as $date => &$day) {
            if (!isset($day['events']) || !is_array($day['events'])) {
                continue;
            }
            foreach ($day['events'] as &$event) {
                if ((string)($event['log_id'] ?? '') === $logId) {
                    $prevSuccess = !empty($event['success']);
                    $newSuccess = !empty($success);
                    if ($prevSuccess !== $newSuccess) {
                        if (!isset($day['summary']) || !is_array($day['summary'])) {
                            $day['summary'] = ['total' => 0, 'sent' => 0, 'failed' => 0];
                        }
                        if ($prevSuccess) {
                            $day['summary']['sent'] = max(0, (int)$day['summary']['sent'] - 1);
                            $day['summary']['failed'] = (int)$day['summary']['failed'] + 1;
                        } else {
                            $day['summary']['failed'] = max(0, (int)$day['summary']['failed'] - 1);
                            $day['summary']['sent'] = (int)$day['summary']['sent'] + 1;
                        }
                    }
                    $event['success'] = $newSuccess ? 1 : 0;
                    $event['error'] = (string)$error;
                    if ($httpCode !== null) {
                        $event['http_code'] = (int)$httpCode;
                    }
                    $event['timestamp'] = date('Y-m-d H:i:s');
                    $event['waktu'] = date('H:i:s');
                    $updated = true;
                    break 2;
                }
            }
        }
        unset($day, $event);

        if ($updated) {
            rewind($fh);
            ftruncate($fh, 0);
            fwrite($fh, json_encode($data, JSON_UNESCAPED_UNICODE));
            fflush($fh);
        }

        flock($fh, LOCK_UN);
        fclose($fh);
    }

    function wa_guard_table_name() {
        return 'wa_absen_guard';
    }

    function wa_guard_ensure_table($koneksi) {
        static $ensured = false;
        if ($ensured) {
            return;
        }
        $table = wa_guard_table_name();
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            absensi_id INT NOT NULL,
            jenis ENUM('masuk','pulang','lain') NOT NULL DEFAULT 'lain',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(absensi_id, jenis)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($koneksi, $sql);
        $ensured = true;
    }

    function wa_guard_mark_sent($koneksi, $absensiId, $jenis) {
        $absensiId = (int)$absensiId;
        if ($absensiId <= 0) {
            return;
        }
        $jenis = strtolower((string)$jenis);
        if (!in_array($jenis, ['masuk','pulang','lain'], true)) {
            $jenis = 'lain';
        }
        wa_guard_ensure_table($koneksi);
        $table = wa_guard_table_name();
        $jenisEsc = mysqli_real_escape_string($koneksi, $jenis);
        mysqli_query($koneksi, "INSERT IGNORE INTO `$table` (absensi_id, jenis) VALUES (".(int)$absensiId.",'".$jenisEsc."')");
    }

    function wa_guard_is_sent($koneksi, $absensiId, $jenis) {
        $absensiId = (int)$absensiId;
        if ($absensiId <= 0) {
            return false;
        }
        $jenis = strtolower((string)$jenis);
        if (!in_array($jenis, ['masuk','pulang','lain'], true)) {
            $jenis = 'lain';
        }
        wa_guard_ensure_table($koneksi);
        $table = wa_guard_table_name();
        $jenisEsc = mysqli_real_escape_string($koneksi, $jenis);
        $res = mysqli_query($koneksi, "SELECT 1 FROM `$table` WHERE absensi_id=".(int)$absensiId." AND jenis='".$jenisEsc."' LIMIT 1");
        if (!$res) {
            return false;
        }
        $row = mysqli_fetch_row($res);
        return $row ? true : false;
    }

    function wa_log_absen($koneksi, $row) {
        $cols = [
            'tanggal','jenis','level','idsiswa','idpeg','nama','number','message','success','http_code','error'
        ];
        $data = [];
        foreach ($cols as $c) { $data[$c] = $row[$c] ?? null; }

        $base = (int) round(microtime(true) * 1000);
        $logId = ($base * 1000) + random_int(0, 999);

        wa_metrics_append([
            'tanggal'  => $data['tanggal'] ?? date('Y-m-d'),
            'waktu'    => date('H:i:s'),
            'nama'     => $row['nama'] ?? '',
            'number'   => $row['number'] ?? '',
            'jenis'    => $row['jenis'] ?? 'lain',
            'level'    => $row['level'] ?? 'siswa',
            'success'  => $row['success'] ?? 0,
            'error'    => $row['error'] ?? '',
            'http_code'=> $row['http_code'] ?? 0,
            'message'  => $row['message'] ?? '',
            'log_id'   => (string)$logId
        ]);

        $absensiId = isset($row['absensi_id']) ? (int)$row['absensi_id'] : 0;
        $jenis = $row['jenis'] ?? 'lain';
        $successFlag = !empty($row['success']);
        if ($absensiId > 0 && $successFlag) {
            wa_guard_mark_sent($koneksi, $absensiId, $jenis);
        }

        return $logId;
    }
}
?>
