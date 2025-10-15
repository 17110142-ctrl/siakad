<?php
session_start();
header('Content-Type: application/json');

// Memastikan skrip hanya diakses melalui metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Akses tidak diizinkan.']);
    exit;
}

// Memuat koneksi database
if (!file_exists("../config/koneksi.php")) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'File koneksi.php tidak ditemukan.']);
    exit;
}
require "../config/koneksi.php";

// ---- BAGIAN BARU: Mengambil URL API dari Database ----
$queryApi = mysqli_query($koneksi, "SELECT url_api FROM aplikasi LIMIT 1");
if (!$queryApi || mysqli_num_rows($queryApi) == 0) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Tidak dapat mengambil url_api dari tabel aplikasi di database.']);
    exit;
}
$dataApi = mysqli_fetch_assoc($queryApi);
$setting['url_api'] = $dataApi['url_api']; // Membuat variabel $setting yang dibutuhkan

// Periksa lagi apakah URL API kosong setelah diambil dari DB
if (empty($setting['url_api'])) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Kolom url_api di tabel aplikasi kosong.']);
    exit;
}

// Validasi data yang dikirim dari AJAX
if (!isset($_POST['id_siswa'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID Siswa tidak ada.']);
    exit;
}

$id_siswa = mysqli_real_escape_string($koneksi, $_POST['id_siswa']);
$querySiswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");

if (mysqli_num_rows($querySiswa) == 0) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Data siswa tidak ditemukan.']);
    exit;
}
$row = mysqli_fetch_assoc($querySiswa);

// Opsi status validasi dari frontend (accepted/rejected/pending)
$status_validasi = isset($_POST['status']) ? strtolower(trim($_POST['status'])) : '';
if (!in_array($status_validasi, ['accepted','rejected','pending',''], true)) {
    $status_validasi = '';
}

// Tentukan nomor tujuan (kolom 'nowa')
$nomorTarget = $row['nowa'];
if (empty($nomorTarget)) {
    echo json_encode(['status' => 'error', 'message' => 'Nomor HP Ortu (kolom nowa) tidak ditemukan di database untuk siswa ini.']);
    exit;
}

// Gunakan pesan custom jika dikirim dari frontend, jika tidak buat otomatis
// Konstruksi pesan akhir
$pesan_custom = isset($_POST['pesan']) ? trim($_POST['pesan']) : '';

// Siapkan data umum
$namaSiswa = htmlspecialchars($row['nama']);
$kelasSiswa = htmlspecialchars($row['kelas']);

if ($status_validasi === 'pending') {
    $pesan = "Yth. Bapak/Ibu Wali Murid dari ananda *$namaSiswa* (Kelas $kelasSiswa),\n\n" .
             "Kami informasikan bahwa status validasi biodata ananda telah DIBATALKAN. Mohon untuk mengisi kembali data biodata pada portal siswa agar dapat divalidasi ulang.\n\n" .
             "Terima kasih atas perhatian dan kerja sama Anda.\n\nSalam,\nWali Kelas";
} elseif ($status_validasi === 'rejected') {
    // Otomatis tambahkan prefix, lalu sambung komentar tambahan dari user (jika ada)
    $pesan = "Yth. Bapak/Ibu Wali Murid dari ananda *$namaSiswa* (Kelas $kelasSiswa),\n\nDengan hormat,\n" .
             "Data belum valid, harap merubah data";
    if ($pesan_custom !== '') {
        $pesan .= ' ' . $pesan_custom;
    }
    $pesan .= "\n\nTerima kasih atas perhatian dan kerja sama Anda.\n\nSalam,\nWali Kelas";
} elseif ($status_validasi === 'accepted') {
    if ($pesan_custom !== '') {
        $pesan = $pesan_custom;
    } else {
        $pesan = "Yth. Bapak/Ibu Wali Murid dari ananda *$namaSiswa* (Kelas $kelasSiswa),\n\n" .
                 "Dengan hormat,\n" .
                 "Data diterima, Terimakasih atas kesungguhan Anda dalam mengisi biodata *$namaSiswa* dengan valid. Data akan terkunci dan tidak bisa diedit. Jika ditemukan data yang tidak sesuai, harap menghubungi operator agar bisa diedit kembali\n\n" .
                 "Terima kasih atas perhatian dan kerja sama Anda.\n\nSalam,\nWali Kelas";
    }
} else {
    // Quick send tanpa status: susun pesan berdasar kelengkapan
    $required = [
        'kelas','nama','nis','nisn','t_lahir','tgl_lahir','jk','nik','nokk','agama','email',
        'anakke','jumlah_saudara','t_badan','b_badan','l_kepala','rt','rw','kelurahan',
        'kecamatan','provinsi','kode_pos','hobi','cita_cita','asal_sek','thn_lulus',
        'beasiswa','nama_ayah','status_ayah','nama_ibu','status_ibu', 'kk_ibu'
    ];
    if ($row['beasiswa'] === 'KIP') $required[] = 'no_kip';
    if ($row['beasiswa'] === 'PKH') $required[] = 'no_kks';
    if (strcasecmp($row['status_ayah'], 'Sudah Meninggal') !== 0) {
        $required = array_merge($required, ['tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','no_hp_ayah']);
    }
    if (strcasecmp($row['status_ibu'], 'Sudah Meninggal') !== 0) {
        $required = array_merge($required, ['tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','no_hp_ibu']);
    }
    $filled = 0; $missingFields = [];
    foreach ($required as $f) {
        if ((isset($row[$f]) && $row[$f] !== '' && $row[$f] !== null) || ($f === 'jumlah_saudara' && $row[$f] === '0')) $filled++; else $missingFields[] = ucwords(str_replace('_', ' ', $f));
    }
    $total = count($required);
    $percent = $total > 0 ? round($filled / $total * 100) : 0;
    $isComplete = ($filled === $total);
    if ($isComplete) {
        $pesan = "Yth. Bapak/Ibu Wali Murid dari ananda *$namaSiswa* (Kelas $kelasSiswa),\n\nKami informasikan bahwa data biodata ananda di sistem sekolah sudah *LENGKAP* (100%).\n\nTerima kasih atas kerja sama Anda.\n\nSalam,\nWali Kelas";
    } else {
        $pesan = "Yth. Bapak/Ibu Wali Murid dari ananda *$namaSiswa* (Kelas $kelasSiswa),\n\nDengan hormat,\nKami informasikan bahwa kelengkapan data biodata ananda baru mencapai *$percent%*. Masih ada beberapa data yang perlu dilengkapi, yaitu:\n\n";
        foreach($missingFields as $field) { $pesan .= "- $field\n"; }
        $pesan .= "\nMohon kesediaannya untuk segera melengkapi data tersebut.\n\nTerima kasih atas perhatian dan kerja sama Anda.\n\nSalam,\nWali Kelas";
    }
}


// Format nomor ke 62 (PENTING untuk API)
$nowa = preg_replace('/[^0-9]/', '', $nomorTarget);
// if (substr($nowa, 0, 1) === '0') {
//     $nowa = '62' . substr($nowa, 1);
// }

// Kirim pesan menggunakan cURL
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $setting['url_api'].'/send-message',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 20,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('message' => $pesan, 'number' => $nowa),
));

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

if ($error) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghubungi API (cURL Error): ' . $error]);
    exit;
}

$response_data = json_decode($response, true);

if ($response_data && isset($response_data['status']) && $response_data['status'] == true) {
    $waktu = date('Y-m-d H:i:s');
    // Pastikan session id_user ada, jika tidak berikan nilai default
    $sender = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 'system'; 
    mysqli_query($koneksi, "INSERT INTO pesan_terkirim(waktu, nowa, isi, sender) VALUES('$waktu', '$nowa', '$pesan', '$sender')");

    // Catat status validasi (lock) jika ada
    if (!empty($status_validasi)) {
        // Buat tabel status biodata jika belum ada
        $create = "CREATE TABLE IF NOT EXISTS biodata_status (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_siswa INT NOT NULL UNIQUE,
            status ENUM('accepted','rejected') NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        mysqli_query($koneksi, $create);

        $id_safe = mysqli_real_escape_string($koneksi, $id_siswa);
        if ($status_validasi === 'pending') {
            // Hapus kunci lama dan set siswa ke pending
            mysqli_query($koneksi, "DELETE FROM biodata_status WHERE id_siswa='$id_safe'");
            $col = mysqli_query($koneksi, "SHOW COLUMNS FROM siswa LIKE 'validation_status'");
            if ($col && mysqli_num_rows($col) == 0) {
                @mysqli_query($koneksi, "ALTER TABLE siswa ADD COLUMN validation_status ENUM('pending','validated','rejected') NOT NULL DEFAULT 'pending'");
            }
            mysqli_query($koneksi, "UPDATE siswa SET validation_status='pending' WHERE id_siswa='" . mysqli_real_escape_string($koneksi, $id_siswa) . "'");
        } else {
            $status_safe = mysqli_real_escape_string($koneksi, $status_validasi);
            mysqli_query($koneksi, "INSERT INTO biodata_status(id_siswa, status) VALUES('$id_safe', '$status_safe') ON DUPLICATE KEY UPDATE status=VALUES(status), updated_at=CURRENT_TIMESTAMP");

            // Tambahan: sinkronkan ke kolom validation_status pada tabel siswa
            $col = mysqli_query($koneksi, "SHOW COLUMNS FROM siswa LIKE 'validation_status'");
            if ($col && mysqli_num_rows($col) == 0) {
                @mysqli_query($koneksi, "ALTER TABLE siswa ADD COLUMN validation_status ENUM('pending','validated','rejected') NOT NULL DEFAULT 'pending'");
            }
            $val_status = ($status_validasi === 'accepted') ? 'validated' : 'rejected';
            mysqli_query($koneksi, "UPDATE siswa SET validation_status='" . mysqli_real_escape_string($koneksi, $val_status) . "' WHERE id_siswa='" . mysqli_real_escape_string($koneksi, $id_siswa) . "'");
        }
    }
    echo json_encode(['status' => 'success', 'message' => 'Pesan berhasil dikirim.']);
} else {
    $api_message = isset($response_data['message']) ? $response_data['message'] : 'Tidak ada pesan error dari API.';
    echo json_encode(['status' => 'error', 'message' => 'API merespons dengan status gagal: ' . $api_message, 'api_response' => $response_data]);
}
?>
