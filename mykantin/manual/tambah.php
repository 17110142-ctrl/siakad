<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

	$ids = $_POST['ids'];
	$idp = $_POST['idp'];
	$jumlah = $_POST['jumlah'];
	$harga = $_POST['harga'];
	
	$cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM keranjang  WHERE idsiswa='$ids' AND idproduk='$idp'"));
    if ($cek == 0) {
		$tot = $jumlah * $harga;
		$simpan = mysqli_query($koneksi,"INSERT INTO keranjang(idsiswa,idproduk,jumlah,harga,total) VALUES('$ids','$idp','$jumlah','$harga','$tot')");
		
	}else{
		$produk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM keranjang  WHERE idsiswa='$ids' AND idproduk='$idp'"));
	$total = $produk['jumlah'] + $jumlah;
	$tot = $total * $harga;
	$simpan = mysqli_query($koneksi,"UPDATE keranjang SET jumlah='$total',total='$tot' WHERE idsiswa='$ids' AND idproduk='$idp'");

	}
	mysqli_close($koneksi);
?>