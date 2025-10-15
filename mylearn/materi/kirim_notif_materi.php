<?php
header('Content-Type: application/json');
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// Validasi session guru (seperti kirim_notif_harian)
cek_session_guru();

// Ambil setting API
$setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
if (empty($setting['url_api'])) {
    echo json_encode(['status' => 'error', 'message' => 'URL API WhatsApp belum diatur']);
    exit();
}

// Ambil parameter
$kelas_raw = $_POST['kelas'] ?? null; // JSON array atau CSV atau array
$mapel_kode = isset($_POST['mapel_kode']) ? trim($_POST['mapel_kode']) : '';
$guru_id = isset($_POST['guru']) ? (int)$_POST['guru'] : 0;
$judul = isset($_POST['judul']) ? trim($_POST['judul']) : '';
$jenis = isset($_POST['jenis']) ? trim($_POST['jenis']) : 'baru'; // 'baru' | 'ubah'
$debug = isset($_REQUEST['debug']) && $_REQUEST['debug'] == '1';
$list_only = isset($_POST['list_only']) && $_POST['list_only'] == '1';

// Normalisasi kelas menjadi array string
$kelas_list = [];
if (!empty($kelas_raw)) {
    if (is_string($kelas_raw)) {
        $tmp = json_decode($kelas_raw, true);
        if (is_array($tmp)) {
            $kelas_list = array_values(array_filter(array_map('strval', $tmp)));
        } else {
            $kelas_list = array_values(array_filter(array_map('trim', explode(',', $kelas_raw))));
        }
    } elseif (is_array($kelas_raw)) {
        $kelas_list = array_values(array_filter(array_map('strval', $kelas_raw)));
    }
}

if (empty($kelas_list) || empty($mapel_kode) || $guru_id <= 0 || empty($judul)) {
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
    exit();
}

// Ambil nama mapel dan guru
$nama_mapel = '';
$stmt_map = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE kode = ? LIMIT 1");
if ($stmt_map) {
    $stmt_map->bind_param('s', $mapel_kode);
    $stmt_map->execute();
    $r = $stmt_map->get_result()->fetch_assoc();
    $nama_mapel = $r['nama_mapel'] ?? '';
    $stmt_map->close();
}

$nama_guru = '';
$stmt_guru = $koneksi->prepare("SELECT nama FROM users WHERE id_user = ? LIMIT 1");
if ($stmt_guru) {
    $stmt_guru->bind_param('i', $guru_id);
    $stmt_guru->execute();
    $r = $stmt_guru->get_result()->fetch_assoc();
    $nama_guru = $r['nama'] ?? '';
    $stmt_guru->close();
}

// Ambil daftar siswa sesuai kelas
$recipients = [];
if (!empty($kelas_list)) {
    $placeholders = implode(',', array_fill(0, count($kelas_list), '?'));
    $types = str_repeat('s', count($kelas_list));
    $sql = "SELECT id_siswa, nama, nowa FROM siswa WHERE kelas IN ($placeholders) AND nowa IS NOT NULL AND nowa != '' ORDER BY nama ASC";
    $stmt = $koneksi->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$kelas_list);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $recipients[] = [
                'idsiswa' => (int)$row['id_siswa'],
                'nama'    => (string)$row['nama'],
                'number'  => (string)$row['nowa']
            ];
        }
        $stmt->close();
    }
}

if ($list_only) {
    $payload = [
        'status'     => 'ok',
        'total'      => count($recipients),
        'recipients' => $recipients
    ];
    if ($debug) {
        $payload['api_url'] = rtrim((string)$setting['url_api'], '/') . '/send-message';
        $payload['scope'] = [
            'kelas' => $kelas_list,
            'mapel_kode' => $mapel_kode,
            'guru' => $guru_id,
            'judul' => $judul,
            'jenis' => $jenis
        ];
    }
    echo json_encode($payload);
    exit();
}

// Kirim ke subset jika diberikan
$ids_raw = $_POST['ids'] ?? null;
$ids_filter = [];
if (!empty($ids_raw)) {
    if (is_string($ids_raw)) {
        $tmp = json_decode($ids_raw, true);
        if (is_array($tmp)) {
            $ids_filter = array_map('intval', $tmp);
        } else {
            $ids_filter = array_map('intval', explode(',', $ids_raw));
        }
    } elseif (is_array($ids_raw)) {
        $ids_filter = array_map('intval', $ids_raw);
    }
}

if (!empty($ids_filter)) {
    $recipients = array_values(array_filter($recipients, function($r) use ($ids_filter){
        return in_array((int)$r['idsiswa'], $ids_filter, true);
    }));
}

// Compose message template
$judul_header = ($jenis === 'ubah') ? '*PEMBERITAHUAN PERUBAHAN MATERI*' : '*PEMBERITAHUAN MATERI BARU*';
$sekolah = (string)($setting['sekolah'] ?? '');

$results = [];
$sent = 0;
$failed = 0;

foreach ($recipients as $r) {
    $nama_siswa = $r['nama'] ?? '';
    $no_wa = $r['number'] ?? '';
    $ok = false; $http_code = 0; $err = ''; $body = '';
    if (!empty($no_wa)) {
        $pesan = $judul_header . "\n\n"
               . "Yth. Bapak/Ibu Orang Tua dari ananda " . $nama_siswa . ",\n\n"
               . "Terdapat materi pada mata pelajaran: *" . $nama_mapel . "*\n"
               . "Judul: *" . $judul . "*\n"
               . "Guru: " . $nama_guru . "\n\n"
               . "Mohon untuk mengingatkan ananda agar mempelajarinya. Terima kasih.\n\n"
               . "*" . $sekolah . "*";

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
        'idsiswa' => (int)$r['idsiswa'],
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

echo json_encode([
    'status' => 'ok',
    'total'  => count($results),
    'sent'   => $sent,
    'failed' => $failed,
    'results'=> $results,
    'api_url'=> rtrim((string)$setting['url_api'], '/') . '/send-message'
]);
?>

