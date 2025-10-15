<?php
// Set header agar outputnya berupa JSON
header('Content-Type: application/json');

// Cobalah untuk menyertakan file koneksi
@include '../config/koneksi.php';

// <<< Perubahan 1: Cek apakah koneksi berhasil dibuat
if (!isset($koneksi) || !$koneksi) {
    // Jika file koneksi tidak ada atau variabel $koneksi gagal dibuat
    echo json_encode([
        'status' => 'db_error',
        'message' => 'Tidak bisa menyambung ke database. Periksa file konfigurasi koneksi Anda.'
    ]);
    exit; // Hentikan eksekusi
}

// Cek apakah input POST ada
if (isset($_POST['nama']) && isset($_POST['kelas'])) {
    $nama = trim($_POST['nama']);
    $kelas = $_POST['kelas'];

    // <<< Perubahan 2: Validasi input tidak boleh kosong
    if (empty($nama) || empty($kelas)) {
        echo json_encode([
            'status' => 'input_error',
            'message' => 'Nama dan Kelas tidak boleh kosong. Mohon lengkapi data Anda.'
        ]);
        exit;
    }

    // Gunakan prepared statements untuk keamanan maksimal melawan SQL Injection
    $query = "SELECT username, password FROM siswa WHERE TRIM(nama) = ? AND kelas = ?";
    
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ss", $nama, $kelas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data_siswa = $result->fetch_assoc();
        $response = [
            'status' => 'success',
            'username' => $data_siswa['username'],
            'password' => $data_siswa['password'],
            'message' => "Hai! Berikut adalah data login kamu:<br><strong>Username:</strong> " . htmlspecialchars($data_siswa['username']) . "<br><strong>Password:</strong> " . htmlspecialchars($data_siswa['password'])
        ];
    } else {
        // <<< Perubahan 3: Respons spesifik jika data tidak ditemukan
        $response = [
            'status' => 'not_found',
            'message' => "Maaf, data untuk nama '" . htmlspecialchars($nama) . "' di kelas " . htmlspecialchars($kelas) . " tidak ditemukan. Pastikan penulisan sudah benar."
        ];
    }
    $stmt->close();
} else {
    $response = [
        'status' => 'input_error',
        'message' => 'Permintaan tidak valid. Harap isi semua kolom.'
    ];
}

$koneksi->close();
echo json_encode($response);