<?php
// File: siswa/tambah_kelas.php
// Digunakan untuk menambah dan menghapus data kelas dalam satu endpoint

require "../../config/koneksi.php";
require "../../config/function.php";
require "../../config/crud.php";

header('Content-Type: application/json');

// Tangkap parameter aksi (add/delete)
$action = isset($_POST['action']) ? $_POST['action'] : 'add';

if ($action === 'delete') {
    // Hapus kelas berdasarkan id_kelas
    $id = intval($_POST['id_kelas']);
    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID kelas tidak valid']);
        exit;
    }

    // Query delete
    $query = "DELETE FROM kelas WHERE id = $id";
    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data: ' . mysqli_error($koneksi)]);
    }
    exit;
}

// Default: aksi add (tambah kelas)
if (!isset($_POST['level']) || !isset($_POST['nama_kelas'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

$data = [
    'level' => $_POST['level'],
    'kelas' => $_POST['nama_kelas'],
];

if (insert($koneksi, 'kelas', $data)) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal insert: ' . mysqli_error($koneksi)]);
}
