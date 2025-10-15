<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$id_siswa = $_POST['id_siswa'];
$id_bank = $_POST['id_bank'];
$id_ujian = $_POST['id_ujian'];
$id_soal = $_POST['id_soal'];
$jenis = '5';

   $exec = mysqli_query($koneksi, "UPDATE jawaban SET jawaban=NULL,jawaburut=NULL WHERE id_siswa='$id_siswa' AND id_ujian='$id_ujian' AND id_soal='$id_soal' AND jenis='$jenis'");
   $hapus = mysqli_query($koneksi, "DELETE FROM jodoh WHERE id_siswa='$id_siswa' AND id_ujian='$id_ujian' AND id_soal='$id_soal' AND jenis='$jenis'");

	

?>