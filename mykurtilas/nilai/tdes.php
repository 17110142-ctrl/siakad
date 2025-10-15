<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';


if ($pg == 'tambah') {
	
     $nis = $_POST['id'];
	  $desmin = $_POST['desmin'];
	  $desmax = $_POST['desmax'];
	  if($desmin<>$desmax):
    $exec = mysqli_query($koneksi, "UPDATE nilai_rapor SET desmin3='$desmin',desmax3='$desmax' WHERE id='$nis' ");
      echo "OK";
	endif;		
}
if ($pg == 'tambah4') {
	
     $nis = $_POST['id'];
	  $desmin = $_POST['desmin'];
	  $desmax = $_POST['desmax'];
	  if($desmin<>$desmax):
    $exec = mysqli_query($koneksi, "UPDATE nilai_rapor SET desmin4='$desmin',desmax4='$desmax' WHERE id='$nis' ");
      echo "OK";
	endif;		
}

