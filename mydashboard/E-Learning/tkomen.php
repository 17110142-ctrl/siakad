<?php
// Memulai output buffering untuk menangkap output yang tidak diinginkan
ob_start();

// Selalu mulai sesi di file yang menggunakan $_SESSION
session_start();

// Path file diubah menjadi format yang benar dan lebih aman
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/function.php';

// Inisialisasi array untuk response JSON
$response = ['status' => 'error', 'message' => 'Aksi tidak dikenal.'];

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
    // Validasi input dasar
    if (!isset($_SESSION['id_siswa']) || empty($_POST['id_materi']) || empty($_POST['komentar']) || empty($_POST['guru'])) {
        $response['message'] = 'Data yang dikirim tidak lengkap.';
    } else {
        $id_siswa = $_SESSION['id_siswa'];
        $guru = $_POST['guru'];
        $tgl = date('Y-m-d H:i:s');
        $id_materi = $_POST['id_materi'];
        $komentar = $_POST['komentar'];

        // Menggunakan prepared statement untuk keamanan
        $stmt = $koneksi->prepare("INSERT INTO komentar(id_user, id_materi, komentar, jenis, tgl, guru) VALUES (?, ?, ?, '1', ?, ?)");
        
        if ($stmt) {
            // PERBAIKAN KRUSIAL: Tipe data untuk komentar diubah menjadi 's' (string)
            // Format sebelumnya: "isisi" -> Salah
            // Format yang benar: "iissi"
            // i = integer, s = string
            $stmt->bind_param("iissi", $id_siswa, $id_materi, $komentar, $tgl, $guru);
            
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Komentar berhasil ditambahkan.';
            } else {
                $response['message'] = 'Gagal menyimpan komentar ke database: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Gagal mempersiapkan query database: ' . $koneksi->error;
        }
    }
}

// Membersihkan buffer output sebelum mengirim JSON
ob_clean();

// Mengatur header untuk output JSON
header('Content-Type: application/json');

// Mengirim response dalam format JSON
echo json_encode($response);

// Menghentikan eksekusi skrip
exit();
