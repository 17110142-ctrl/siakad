<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$sesi = $_POST['sesi'];
$dari = $_POST['dari'];
$sampai = $_POST['sampai'];
mysqli_query($koneksi,"UPDATE siswa set sesi='$sesi' WHERE   id_siswa BETWEEN '$dari' AND '$sampai'");
?>