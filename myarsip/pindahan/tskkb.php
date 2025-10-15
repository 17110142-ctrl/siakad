<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

$header = $_POST['header'];
$isi = $_POST['isi'];
$foter = $_POST['foter'];
$surat = $_POST['nosurat'];
$simpan = mysqli_Query($koneksi,"UPDATE skkb SET header='$header',isi='$isi',foter='$foter',nosurat='$surat'")

?>