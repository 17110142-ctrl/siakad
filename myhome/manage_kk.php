<?php
require_once '../config/koneksi.php';
require_once '../config/function.php';
require_once '../config/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan.'
    ]);
    exit;
}

$id = isset($_POST['id_siswa']) ? trim($_POST['id_siswa']) : '';
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

if ($id === '' || !ctype_digit($id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID siswa tidak valid.'
    ]);
    exit;
}

$id = (int)$id;
$uploadDir = realpath(__DIR__ . '/../uploads/kk');
if ($uploadDir === false) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Folder penyimpanan KK tidak ditemukan.'
    ]);
    exit;
}

$uploadDir .= DIRECTORY_SEPARATOR;

$current = fetch($koneksi, 'siswa', ['id_siswa' => $id]);
if (!$current) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Data siswa tidak ditemukan.'
    ]);
    exit;
}

$currentFile = '';
if (!empty($current['kk_ibu'])) {
    $currentFile = basename($current['kk_ibu']);
}

function deleteCurrentFile($uploadDir, $filename) {
    if ($filename === '') return;
    $path = $uploadDir . $filename;
    if (is_file($path)) {
        @unlink($path);
    }
}

if ($action === 'delete') {
    if ($currentFile === '') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Tidak ada file KK yang perlu dihapus.'
        ]);
        exit;
    }

    deleteCurrentFile($uploadDir, $currentFile);
    update($koneksi, 'siswa', ['kk_ibu' => ''], ['id_siswa' => $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'File KK berhasil dihapus.'
    ]);
    exit;
}

if ($action === 'upload') {
    if (!isset($_FILES['kk_file']) || $_FILES['kk_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'status' => 'error',
            'message' => 'File KK tidak valid atau gagal diunggah.'
        ]);
        exit;
    }

    $file = $_FILES['kk_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif','bmp','jfif','pdf'];
    if (!in_array($ext, $allowed, true)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.'
        ]);
        exit;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ukuran file melebihi 5 MB.'
        ]);
        exit;
    }

    $newName = 'kk_' . $id . '_' . time() . '_' . mt_rand(1000,9999) . '.' . $ext;
    $destination = $uploadDir . $newName;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal memindahkan file ke server.'
        ]);
        exit;
    }

    // Hapus file lama jika ada
    if ($currentFile !== '') {
        deleteCurrentFile($uploadDir, $currentFile);
    }

    update($koneksi, 'siswa', ['kk_ibu' => $newName], ['id_siswa' => $id]);

    echo json_encode([
        'status' => 'success',
        'message' => 'File KK berhasil diunggah.',
        'filename' => $newName
    ]);
    exit;
}

echo json_encode([
    'status' => 'error',
    'message' => 'Aksi tidak dikenali.'
]);
