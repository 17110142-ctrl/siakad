<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate nilai_sumatif");
$exec = mysqli_query($koneksi, "truncate nilai_formatif");
$exec = mysqli_query($koneksi, "truncate peskul");
?>