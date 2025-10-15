<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");


mysqli_query($koneksi, "TRUNCATE tmpreg");
$status = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM status_face"));	
	
	$nokartu = $_GET['nokartu'];
	

$query = mysqli_query($koneksi, "select * from datareg where nokartu='$nokartu'");
$cek = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);
$nama = $data['nama'];
if ($status['mode'] == 3) {
if ($cek ==0) {
	echo "TIDAK TERDAFTAR";
	$exec = mysqli_query($koneksi,"INSERT INTO tmpreg(nokartu) VALUES('$nokartu')");
	mysqli_close($koneksi);
		}
}else{
	if ($cek ==1) {
		echo $nama;
	$exec = mysqli_query($koneksi,"INSERT INTO tmpreg(nokartu) VALUES('$nokartu')");
	mysqli_close($koneksi);
}
}
			?>