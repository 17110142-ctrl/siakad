<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';


if ($pg == 'model') {
  
	$exec = mysqli_query($koneksi, "UPDATE kelas set model_kkm='$_POST[model]' WHERE level='$_POST[level]'");
	$exec = mysqli_query($koneksi, "UPDATE level set model_kkm='$_POST[model]' WHERE level='$_POST[level]'");
}

if ($pg == 'kkm') {
	$pk = $_POST['pk'];
     $tingkat = $_POST['level'];
	  $kkm = $_POST['kkm'];
    $exec = mysqli_query($koneksi, "UPDATE mapel_rapor SET kkm='$kkm' WHERE tingkat='$tingkat' and pk='$pk'");
      
			
}

if ($pg == 'multikkm') {
	 $mapel = $_POST['mapel'];
      $tingkat = $_POST['level'];
	  $kkm = $_POST['kkm'];
	   $pk = $_POST['pk'];
    $exec = mysqli_query($koneksi, "UPDATE mapel_rapor SET kkm='$kkm' WHERE tingkat='$tingkat' AND mapel='$mapel' and pk='$pk'");
       
			
}
