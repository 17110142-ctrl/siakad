<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate nilai_rapor");
$exec = mysqli_query($koneksi, "truncate spiritual");
$exec = mysqli_query($koneksi, "truncate sosial");
$exec = mysqli_query($koneksi, "truncate peskul");
$exec = mysqli_query($koneksi, "truncate mapel_rapor");
?>