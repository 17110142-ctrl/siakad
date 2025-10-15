<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

  if ($pg == 'optimal') {
	 $tablejawab = 'jawaban';
	 $tablesoal = 'soal';
	  $tablenilai = 'nilai';
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablejawab."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablesoal."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablenilai."'");
  }
   
?>