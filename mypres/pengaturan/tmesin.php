<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$mesin = mysqli_real_escape_string($koneksi, $_POST['mesin']);
$api = mysqli_real_escape_string($koneksi, $_POST['api']);
$hari_sekolah = (int)$_POST['hari_sekolah']; // Ambil data baru

// Update query dengan kolom baru
$query = "UPDATE aplikasi SET mesin='$mesin', url_api='$api', hari_sekolah='$hari_sekolah'";
mysqli_query($koneksi, $query);
?>