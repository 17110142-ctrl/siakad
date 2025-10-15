<?php
include('../controller/config.php');

// menangkap data yang di kirim dari form
//$id_profil = $_GET['id_profil'];
$nm_sekolah = $_POST['nm_sekolah'];
$nm_aplikasi = $_POST['nm_aplikasi'];
$tahun = $_POST['tahun'];

// update data ke database
mysqli_query($koneksi, "UPDATE tbl_profil SET nm_sekolah='$nm_sekolah', nm_aplikasi='$nm_aplikasi', tahun='$tahun' where id_profil='1'");

// mengalihkan halaman kembali ke index.php
echo "<meta http-equiv='refresh' content='0; url=index.php?page=profil'>";
