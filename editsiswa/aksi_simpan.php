<?php
include '../config/koneksi.php'; // pastikan file koneksi database sudah benar

session_start(); // pastikan session aktif

$aksi = $_GET['aksi'];

if($aksi == 'siswa'){
    $nama = $_POST['nama'];
$nisn = $_POST['nisn'];
$kelas = $_POST['kelas'];

$nama_ayah = $_POST['nama_ayah'];
$nama_ibu = $_POST['nama_ibu'];

$alamat = $_POST['alamat'];
$kota = $_POST['kota'];

$hobi = $_POST['hobi'];
$cita_cita = $_POST['cita_cita'];

// Contoh simpan ke database (DISARANKAN pakai PDO / mysqli prepared statement)
// mysqli_query($koneksi, "INSERT INTO siswa (...) VALUES (...)");

// Untuk sekarang, kita tes aja:
echo "Data berhasil disimpan:
<br>Nama: $nama
<br>NISN: $nisn
<br>Kelas: $kelas
<br>Nama Ayah: $nama_ayah
<br>Nama Ibu: $nama_ibu
<br>Alamat: $alamat
<br>Kota: $kota
<br>Hobi: $hobi
<br>Cita-cita: $cita_cita";
?>