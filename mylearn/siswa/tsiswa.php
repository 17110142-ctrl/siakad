<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

  if ($pg == 'optimal') {
	 $tablejawab = 'jawaban_tugas';
	 $tablemateri = 'materi';
	  $tabletugas = 'tugas';
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablemateri."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tabletugas."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablejawab."'");
  }
   
?>