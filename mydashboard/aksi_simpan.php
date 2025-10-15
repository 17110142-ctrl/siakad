<?php
include "../config/koneksi.php";
session_start();

// Set header untuk respon JSON di awal
header('Content-Type: application/json');

// Fungsi bantu untuk mengamankan input
function safe_input($value) {
    return strtoupper(trim($value ?? ''));
}

// Fungsi upload file
function uploadFile($inputName, $identifier, $prefix, $nama_siswa) {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES[$inputName]['tmp_name'];
        $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
        $nama_siswa_bersih = str_replace(' ', '_', $nama_siswa);
        $nama_baru = "{$prefix}_{$identifier}_{$nama_siswa_bersih}." . $ext;
        $folder = "../uploads/kk/";

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if (move_uploaded_file($tmp, $folder . $nama_baru)) {
            return $nama_baru;
        }
    }
    return null;
}

// =================================================================
// [BARU] LOGIKA UNTUK HAPUS FILE KK
// =================================================================
if (isset($_POST['action']) && $_POST['action'] === 'hapus_kk') {
    
    // Validasi dasar untuk aksi hapus
    if (!isset($_SESSION['id_siswa']) || !isset($_POST['id_siswa']) || $_POST['id_siswa'] != $_SESSION['id_siswa']) {
        echo json_encode(["status" => "error", "message" => "Akses tidak sah atau ID tidak cocok."]);
        exit;
    }

    $id_siswa = $_SESSION['id_siswa'];
    $file_to_delete = null;

    // 1. Ambil nama file dari database
    $stmt_select = mysqli_prepare($koneksi, "SELECT kk_ibu FROM siswa WHERE id_siswa = ?");
    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_siswa);
        mysqli_stmt_execute($stmt_select);
        mysqli_stmt_bind_result($stmt_select, $file_to_delete);
        mysqli_stmt_fetch($stmt_select);
        mysqli_stmt_close($stmt_select);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengambil data file."]);
        exit;
    }

    // 2. Hapus file fisik jika ada
    if (!empty($file_to_delete)) {
        $file_path = "../uploads/kk/" . $file_to_delete;
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file dari server
        }
    }

    // 3. Update database, set kolom kk_ibu menjadi NULL
    $stmt_update = mysqli_prepare($koneksi, "UPDATE siswa SET kk_ibu = NULL WHERE id_siswa = ?");
    if ($stmt_update) {
        mysqli_stmt_bind_param($stmt_update, "i", $id_siswa);
        if (mysqli_stmt_execute($stmt_update)) {
            echo json_encode(["status" => "success", "message" => "File Kartu Keluarga berhasil dihapus."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui database."]);
        }
        mysqli_stmt_close($stmt_update);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyiapkan query update."]);
    }
    exit; // Hentikan eksekusi setelah aksi hapus selesai
}


// =================================================================
// LOGIKA UNTUK SIMPAN DATA FORM (Logika yang sudah ada)
// =================================================================

// Cek lock biodata (jika sudah diterima, tolak perubahan)
if (isset($_SESSION['id_siswa'])) {
    $idSess = mysqli_real_escape_string($koneksi, $_SESSION['id_siswa']);

    // Jika kolom validation_status ada pada tabel siswa, gunakan itu sebagai sumber kebenaran
    $qLock2 = mysqli_query($koneksi, "SHOW COLUMNS FROM siswa LIKE 'validation_status'");
    if ($qLock2 && mysqli_num_rows($qLock2) > 0) {
        $qVal = mysqli_query($koneksi, "SELECT validation_status FROM siswa WHERE id_siswa='$idSess' LIMIT 1");
        if ($qVal && mysqli_num_rows($qVal) > 0) {
            $st2 = mysqli_fetch_assoc($qVal);
            if (strtolower($st2['validation_status'] ?? '') === 'validated') {
                echo json_encode(["status" => "error", "message" => "Biodata sudah dikunci (divalidasi). Hubungi operator untuk membuka kunci jika perlu perbaikan."]);
                exit;
            }
        }
    } else {
        // Fallback lama: gunakan tabel biodata_status jika kolom validation_status belum ada
        mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS biodata_status (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_siswa INT NOT NULL UNIQUE,
            status ENUM('accepted','rejected') NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $qLock = mysqli_query($koneksi, "SELECT status FROM biodata_status WHERE id_siswa='$idSess' LIMIT 1");
        if ($qLock && mysqli_num_rows($qLock) > 0) {
            $st = mysqli_fetch_assoc($qLock);
            if (strtolower($st['status']) === 'accepted') {
                echo json_encode(["status" => "error", "message" => "Biodata sudah dikunci (diterima). Hubungi operator untuk membuka kunci jika perlu perbaikan."]);
                exit;
            }
        }
    }
}

// Validasi dasar untuk aksi simpan
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_siswa']) || !isset($_POST['tab'])) {
    die(json_encode(["status" => "error", "message" => "Invalid request."]));
}

$id_siswa = $_POST['id_siswa'];
$tab = $_POST['tab'];

$fields = [];
$update_parts = [];

// Simpan sesuai tab
if ($tab == 'biodata') {
    $fields = [
        'nama', 'nis', 'nisn', 'nokk', 'nik', 't_lahir', 'tgl_lahir', 'jk',
        'agama', 'kewarganegaraan', 'email', 't_badan', 'b_badan', 'l_kepala', 'anakke', 'jumlah_saudara',
        'cita_cita', 'hobi', 'asal_sek', 'thn_lulus', 'beasiswa', 'no_kip', 'no_kks'
    ];
} elseif ($tab == 'alamat') {
    $fields = [
        'rt', 'rw', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'lintang', 'bujur'
    ];
} elseif ($tab == 'ortu') {
    $fields = [
        'nama_ayah', 'status_ayah', 'kewarganegaraan_ayah', 'tempat_lahir_ayah', 'tgl_lahir_ayah',
        'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'no_hp_ayah',
        'nama_ibu', 'status_ibu', 'kewarganegaraan_ibu', 'tempat_lahir_ibu', 'tgl_lahir_ibu',
        'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'no_hp_ibu'
    ];

    // Logika nomor HP utama (nowa)
    if (isset($_POST['use_for_nowa'])) {
        $parent_type = $_POST['use_for_nowa'];
        $parent_hp_field = 'no_hp_' . $parent_type;
        $nomor_hp = safe_input($_POST[$parent_hp_field] ?? '');
        
        if (!empty($nomor_hp)) {
            $update_parts[] = "`nowa` = '" . mysqli_real_escape_string($koneksi, $nomor_hp) . "'";
        } else {
            $update_parts[] = "`nowa` = NULL";
        }
    } else {
        $update_parts[] = "`nowa` = NULL";
    }

    // Logika upload file KK
    if (isset($_FILES['kk_ibu']) && $_FILES['kk_ibu']['error'] === UPLOAD_ERR_OK) {
        $query_data = mysqli_query($koneksi, "SELECT nama, nis FROM siswa WHERE id_siswa = '" . mysqli_real_escape_string($koneksi, $id_siswa) . "'");
        $data_siswa = mysqli_fetch_assoc($query_data);
        $nama_lengkap_siswa = $data_siswa['nama'] ?? 'NAMA_TIDAK_DIKETAHUI';
        $nis_siswa = $data_siswa['nis'] ?? 'NIS_TIDAK_DIKETAHUI';

        if ($kk_file = uploadFile('kk_ibu', $nis_siswa, 'kk', $nama_lengkap_siswa)) {
            $update_parts[] = "`kk_ibu` = '" . mysqli_real_escape_string($koneksi, $kk_file) . "'";
        }
    }
}

// Persiapkan query update untuk field lainnya
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        $val = safe_input($_POST[$field]);
        $esc = mysqli_real_escape_string($koneksi, $val);
        $update_parts[] = "`$field` = '$esc'";
    }
}

// Pastikan ada yang diupdate sebelum eksekusi query
if (empty($update_parts)) {
    echo json_encode(["status" => "info", "message" => "Tidak ada data baru untuk disimpan."]);
    exit;
}

// Eksekusi query
$sql    = "UPDATE `siswa` SET " . implode(", ", $update_parts) . " WHERE `id_siswa` = '" . mysqli_real_escape_string($koneksi, $id_siswa) . "'";
$update = mysqli_query($koneksi, $sql);

if ($update) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil disimpan."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan data: " . mysqli_error($koneksi)
    ]);
}
?>
