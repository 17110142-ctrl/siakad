<?php
header('Content-Type: application/json');
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
// Tidak perlu helper WA di mode sederhana ini

// Samakan dengan kirim_notif_satu: validasi via helper
cek_session_guru();

// Gunakan util WA bersama (throttle + retry) dari myhome/wa_helpers.php

$kelas     = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
$mapel     = isset($_POST['mapel']) ? (int)$_POST['mapel'] : 0;
$guru      = isset($_POST['guru']) ? (int)$_POST['guru'] : 0;
$tanggal   = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
// Kompatibilitas: izinkan alias 'tgl' seperti pada kode yang berjalan di lingkungan Anda
if (empty($tanggal) && isset($_POST['tgl'])) {
    $tanggal = $_POST['tgl'];
}
$list_only = isset($_POST['list_only']) && $_POST['list_only'] == '1';
$debug     = isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1';

// Optional: kirim hanya untuk subset siswa tertentu (array idsiswa)
$ids_raw = isset($_POST['ids']) ? $_POST['ids'] : null; // bisa JSON atau CSV
$ids_siswa = [];
if (!empty($ids_raw)) {
    if (is_string($ids_raw)) {
        // coba parse json dulu
        $tmp = json_decode($ids_raw, true);
        if (is_array($tmp)) {
            $ids_siswa = array_map('intval', $tmp);
        } else {
            // anggap CSV
            $ids_siswa = array_map('intval', explode(',', $ids_raw));
        }
    } elseif (is_array($ids_raw)) {
        $ids_siswa = array_map('intval', $ids_raw);
    }
}

if (empty($kelas) || $mapel <= 0 || $guru <= 0 || empty($tanggal)) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
    exit();
}

// Pastikan API WA tersedia
if (empty($setting['url_api'])) {
    echo json_encode(['status' => 'error', 'message' => 'URL API WhatsApp belum diatur']);
    exit();
}

// Tidak ada mode kompatibel: pola kirim disamakan dengan kirim_notif_satu.php

// Ambil nama mapel & guru untuk menghindari query berulang
$nama_mapel = '';
$stmt_mapel = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id = ?");
$stmt_mapel->bind_param('i', $mapel);
$stmt_mapel->execute();
if ($r = $stmt_mapel->get_result()->fetch_assoc()) {
    $nama_mapel = $r['nama_mapel'] ?? '';
}
$stmt_mapel->close();

$nama_guru = '';
$stmt_guru = $koneksi->prepare("SELECT nama FROM users WHERE id_user = ?");
$stmt_guru->bind_param('i', $guru);
$stmt_guru->execute();
if ($r = $stmt_guru->get_result()->fetch_assoc()) {
    $nama_guru = $r['nama'] ?? '';
}
$stmt_guru->close();

// Ambil data nilai_harian sesuai scope
$sql = "SELECT nh.* FROM nilai_harian nh WHERE nh.kelas = ? AND nh.mapel = ? AND nh.guru = ? AND nh.tanggal = ?";
if (!empty($ids_siswa)) {
    $in = implode(',', array_map('intval', $ids_siswa));
    $sql .= " AND nh.idsiswa IN ($in)";
}
$sql .= " ORDER BY nh.id ASC";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param('siis', $kelas, $mapel, $guru, $tanggal);
$stmt->execute();
$res = $stmt->get_result();

// Prepared statements untuk melengkapi data
$stmt_siswa = $koneksi->prepare("SELECT nama, nowa FROM siswa WHERE id_siswa = ?");
$stmt_desc_kd = $koneksi->prepare("SELECT deskripsi FROM deskripsi WHERE mapel = ? AND kd = ? LIMIT 1");
$stmt_desc_lm = $koneksi->prepare("SELECT materi FROM lingkup WHERE mapel = ? AND lm = ? LIMIT 1");

// Jika hanya membutuhkan daftar penerima (tanpa mengirim), kembalikan list
if ($list_only) {
    $recipients = [];
    while ($row = $res->fetch_assoc()) {
        $stmt_siswa->bind_param('i', $row['idsiswa']);
        $stmt_siswa->execute();
        $sis = $stmt_siswa->get_result()->fetch_assoc();
        $recipients[] = [
            'idsiswa' => (int)$row['idsiswa'],
            'nama'    => $sis['nama'] ?? '',
            'number'  => $sis['nowa'] ?? ''
        ];
    }
    $stmt->close();
    $stmt_siswa->close();
    $stmt_desc_kd->close();
    $stmt_desc_lm->close();
$payload = [
        'status'     => 'ok',
        'total'      => count($recipients),
        'recipients' => $recipients
    ];
    if ($debug) {
        $payload['api_url'] = rtrim((string)$setting['url_api'], '/') . '/send-message';
        $payload['scope'] = ['kelas'=>$kelas,'mapel'=>$mapel,'guru'=>$guru,'tanggal'=>$tanggal];
    }
    echo json_encode($payload);
    exit();
}

$results = [];
$sent = 0;
$failed = 0;

while ($row = $res->fetch_assoc()) {
    // Data siswa
    $stmt_siswa->bind_param('i', $row['idsiswa']);
    $stmt_siswa->execute();
    $sis = $stmt_siswa->get_result()->fetch_assoc();
    $nama_siswa = $sis['nama'] ?? '';
    $no_wa = $sis['nowa'] ?? '';

    // Deskripsi materi
    $materi_desc = '';
    if ($row['kuri'] == '1') {
        $stmt_desc_kd->bind_param('is', $row['mapel'], $row['materi']);
        $stmt_desc_kd->execute();
        if ($d = $stmt_desc_kd->get_result()->fetch_assoc()) {
            $materi_desc = $d['deskripsi'] ?? '';
        }
    } else {
        $stmt_desc_lm->bind_param('is', $row['mapel'], $row['materi']);
        $stmt_desc_lm->execute();
        if ($d = $stmt_desc_lm->get_result()->fetch_assoc()) {
            $materi_desc = $d['materi'] ?? '';
        }
    }

    $tgl_kirim = date('d-m-Y', strtotime($row['tanggal']));
    $status_tuntas = ((int)$row['nilai'] >= (int)$row['kkm']) ? 'Tuntas' : 'Tidak Tuntas';
    $label_materi = ($row['kuri'] == '1' ? 'KD ' : 'LM ') . $row['materi'];
    $materi_line = $label_materi . (!empty($materi_desc) ? ' - ' . $materi_desc : '');

    $ok = false; $http_code = 0; $err = '';
    if (!empty($no_wa)) {
        // Samakan pola pengiriman dengan kirim_notif_satu.php
        $pesan = "INFORMASI NILAI HARIAN - " . ($setting['sekolah'] ?? '') . "\n\n"
               . "Nama Siswa: " . $nama_siswa . "\n"
               . "Kelas: " . $row['kelas'] . "\n"
               . "Mata Pelajaran: " . $nama_mapel . "\n"
               . "Tanggal: " . $tgl_kirim . "\n"
               . "Materi: " . $materi_line . "\n"
               . "Nilai: *" . $row['nilai'] . "* (KKM: " . $row['kkm'] . ")\n"
               . "Keterangan: " . $status_tuntas . "\n\n"
               . "Guru: " . $nama_guru . "\n"
               . "Pesan ini dikirim otomatis oleh sistem.";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => rtrim((string)$setting['url_api'], '/') . '/send-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $no_wa]
        ]);
        $body = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($body === false) { $err = curl_error($curl); }
        curl_close($curl);
        $ok = ($http_code == 200);
        $attempts = 1;
        $used_url = rtrim((string)$setting['url_api'], '/') . '/send-message';
        $used_ctype = 'multipart';
    } else {
        $err = 'Nomor WA kosong';
        $http_code = 0;
        $body = '';
        $attempts = 0;
        $used_url = '';
        $used_ctype = '';
    }

    if ($ok) $sent++; else $failed++;
    $results[] = [
        'idsiswa' => (int)$row['idsiswa'],
        'nama'    => $nama_siswa,
        'number'  => $no_wa,
        'success' => $ok,
        'http_code' => $http_code,
        'error'   => $err,
        'gateway_resp_snippet' => substr((string)$body, 0, 300),
        'raw_number' => $no_wa ?? '',
        'attempts' => (int)$attempts,
        'used_url' => $used_url,
        'payload_type' => $used_ctype
    ];
}

$stmt->close();
$stmt_siswa->close();
$stmt_desc_kd->close();
$stmt_desc_lm->close();

echo json_encode([
    'status' => 'ok',
    'total'  => count($results),
    'sent'   => $sent,
    'failed' => $failed,
    'results'=> $results,
    'api_url'=> rtrim((string)$setting['url_api'], '/') . '/send-message'
]);
?>
