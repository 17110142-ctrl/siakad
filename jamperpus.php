<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$waktusandik = date('H:i');

$sql = mysqli_query($koneksi, "select * from statustrx");
	$data = mysqli_fetch_array($sql);
	$mode_perpus = $data['mode'];
	
	if($mode_perpus==1){
		echo $waktusandik." >>> PINJAM";	
	}else if($mode_perpus==2){
		echo $waktusandik." >> KEMBALI";
	}else if($mode_perpus==3){
		echo $waktusandik." >>>> INPUT";
	}
?>