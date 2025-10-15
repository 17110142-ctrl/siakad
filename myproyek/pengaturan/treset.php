<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate m_proyek");
$exec = mysqli_query($koneksi, "truncate nilai_proses");
$exec = mysqli_query($koneksi, "truncate nilai_proyek");
$exec = mysqli_query($koneksi, "truncate proyek");

?>