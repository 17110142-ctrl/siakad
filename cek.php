<?php
require "config/koneksi.php";
require "config/function.php";
require "config/crud.php";
if ($koneksi) {
    $idn= $_POST['idn'];
	 $idu= $_POST['idu'];
	  $ids= $_POST['ids'];
   
	$query = "SELECT * FROM reset WHERE idnilai='$idn' AND idsiswa='$ids' AND idujian='$idu'";
$data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
$ResultData = mysqli_num_rows($data);

if ($ResultData == 0) {
					
	echo"OK";
					
}else{
	echo"gagal";
}
}
?>