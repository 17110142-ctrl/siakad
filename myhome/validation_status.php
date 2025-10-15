<?php
session_start();
header('Content-Type: application/json');

require_once "../config/koneksi.php";

// Hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
    exit;
}

// Pastikan hanya admin/bendahara yang dapat mengubah status
$level = $_SESSION['level'] ?? '';
if (!in_array($level, ['admin','bendahara'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak']);
    exit;
}

$id_siswa = isset($_POST['id_siswa']) ? (int) $_POST['id_siswa'] : 0;
$status = isset($_POST['status']) ? strtolower(trim($_POST['status'])) : '';

$allowed = ['pending','validated','rejected'];
if ($id_siswa <= 0 || !in_array($status, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Parameter tidak valid']);
    exit;
}

// Pastikan kolom validation_status ada
$col = mysqli_query($koneksi, "SHOW COLUMNS FROM siswa LIKE 'validation_status'");
if ($col && mysqli_num_rows($col) == 0) {
    // Tambahkan kolom jika belum ada
    @mysqli_query($koneksi, "ALTER TABLE siswa ADD COLUMN validation_status ENUM('pending','validated','rejected') NOT NULL DEFAULT 'pending'");
}

$id_safe = mysqli_real_escape_string($koneksi, (string)$id_siswa);
$status_safe = mysqli_real_escape_string($koneksi, $status);

$ok = mysqli_query($koneksi, "UPDATE siswa SET validation_status='$status_safe' WHERE id_siswa='$id_safe'");
if ($ok) {
    // Opsional: sinkronkan tabel lama biodata_status untuk kompatibilitas
    if ($status === 'pending') {
        // Hapus kunci lama agar tidak mengunci edit
        @mysqli_query($koneksi, "DELETE FROM biodata_status WHERE id_siswa='$id_safe'");
    } elseif ($status === 'validated') {
        @mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS biodata_status (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_siswa INT NOT NULL UNIQUE,
            status ENUM('accepted','rejected') NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        @mysqli_query($koneksi, "INSERT INTO biodata_status(id_siswa, status) VALUES('$id_safe','accepted') ON DUPLICATE KEY UPDATE status=VALUES(status), updated_at=CURRENT_TIMESTAMP");
    } elseif ($status === 'rejected') {
        @mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS biodata_status (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_siswa INT NOT NULL UNIQUE,
            status ENUM('accepted','rejected') NOT NULL,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        @mysqli_query($koneksi, "INSERT INTO biodata_status(id_siswa, status) VALUES('$id_safe','rejected') ON DUPLICATE KEY UPDATE status=VALUES(status), updated_at=CURRENT_TIMESTAMP");
    }
    echo json_encode(['status' => 'success', 'message' => 'Status validasi diperbarui']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status: ' . mysqli_error($koneksi)]);
}
?>

