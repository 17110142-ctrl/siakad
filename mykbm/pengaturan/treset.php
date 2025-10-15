<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$exec = mysqli_query($koneksi, "truncate agenda");
$exec = mysqli_query($koneksi, "truncate nilai_harian");
$exec = mysqli_query($koneksi, "truncate absensi_harian");
$exec = mysqli_query($koneksi, "truncate lingkup");
$exec = mysqli_query($koneksi, "truncate tujuan");
$exec = mysqli_query($koneksi, "truncate deskripsi");
$exec = mysqli_query($koneksi, "truncate jadwal_mapel");
?>
