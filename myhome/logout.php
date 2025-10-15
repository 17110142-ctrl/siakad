<?php
// Memulai sesi di baris paling atas adalah praktik terbaik
session_start();

// Memuat file konfigurasi
if (file_exists("../config/koneksi.php")) {
    require("../config/koneksi.php");
}

$user_id = null;
$user_type = null;

// Logika untuk menentukan user yang sedang logout
if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];
    $user_type = 'users';
    $id_column = 'id_user';
} elseif (isset($_SESSION['id_siswa'])) {
    $user_id = $_SESSION['id_siswa'];
    $user_type = 'siswa';
    $id_column = 'id_siswa';
}

// Jika user teridentifikasi dan koneksi database ada, bersihkan statusnya
if ($user_id !== null && isset($koneksi) && $koneksi instanceof mysqli) {
    // Menggunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("UPDATE {$user_type} SET online = '0', session_token = NULL WHERE {$id_column} = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Hancurkan semua data sesi
session_unset();
session_destroy();

// Arahkan kembali ke halaman login utama
header("Location: /myhome/mulai.php?status=logout");
exit(); // Hentikan eksekusi setelah redirect
