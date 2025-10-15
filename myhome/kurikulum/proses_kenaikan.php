<?php
require('../../config/koneksi.php');

$id_siswa = $_POST['id_siswa'] ?? '';
$jenis     = $_POST['jenis'] ?? '';  // "level" atau "kelas"
$nilai     = $_POST['nilai'] ?? '';

if (!$id_siswa || !$jenis || !$nilai) {
    http_response_code(400);
    exit("Data tidak lengkap.");
}

$id_siswa = intval($id_siswa);

// Validasi kolom yang boleh diupdate
$kolom = '';
if ($jenis == 'level') {
    $kolom = 'level';
} elseif ($jenis == 'kelas') {
    $kolom = 'kelas';
} else {
    http_response_code(400);
    exit("Kolom tidak valid.");
}

$nilai = mysqli_real_escape_string($koneksi, $nilai);
$update = mysqli_query($koneksi, "UPDATE siswa SET $kolom = '$nilai' WHERE id_siswa = $id_siswa");

if ($update) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Gagal menyimpan perubahan.";
}
