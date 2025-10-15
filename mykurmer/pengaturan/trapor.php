<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$smt = $_POST['semester'];
$tp = $_POST['tp'];
$tgl = $_POST['tgl'];
$simpan = mysqli_query($koneksi,"UPDATE aplikasi SET semester='$smt',tp='$tp',tanggal_rapor='$tgl' where id_aplikasi='1'");
?>