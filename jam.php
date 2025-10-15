<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$hari = date('D');
$waktusandik = date('H:i');
$waktu = date('H:i:s');
$sql = mysqli_query($koneksi, "select * from status");
	$data = mysqli_fetch_array($sql);
	$mode_absen = $data['mode'];
	$mode = "";
	if($mode_absen==1){
		echo $waktusandik." >>>> Masuk";	
	}else if($mode_absen==2){
		echo $waktusandik." >>> Pulang";
	}else if($mode_absen==3){
		echo $waktusandik." Masuk Les ";
	}else if($mode_absen==4){
		echo $waktusandik." Pulang Les ";
	}


$query = mysqli_query($koneksi,"SELECT * FROM waktu WHERE hari='$hari'");
while ($data = mysqli_fetch_array($query)) :
if($data['masuk']<>'' && $waktusandik <= $data['masuk']){
mysqli_query($koneksi,"UPDATE status set mode='1'");
}
if($data['pulang']<>'' && $waktusandik >= $data['pulang'] && $waktusandik < $data['masuk_eskul'] ){
mysqli_query($koneksi,"UPDATE status set mode='2'");
}
if($data['masuk_eskul']<>'' && $waktusandik >= $data['masuk_eskul'] && $waktusandik < $data['jam_eskul']){
mysqli_query($koneksi,"UPDATE status set mode='3'");
}
if($data['pulang_eskul']<>'' && $waktusandik >= $data['pulang_eskul']){
mysqli_query($koneksi,"UPDATE status set mode='4'");
}

endwhile;



?>