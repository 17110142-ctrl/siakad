<?php 
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
	$mode = mysqli_query($koneksi, "select * from statustrx");
	$data_mode = mysqli_fetch_array($mode);
	$mode_absen = $data_mode['mode'];

	
	$mode_absen = $mode_absen + 1;
	if($mode_absen > 3){
		$mode_absen = 1 ;
	}
	$simpan = mysqli_query($koneksi, "update statustrx set mode='$mode_absen'");
	
?>