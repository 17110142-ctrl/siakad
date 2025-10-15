<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$bulan = date('m');
$tahun = date('Y');
$tanggal = date('Y-m-d');
$query = mysqli_query($koneksi, "SELECT * FROM keranjang"); 
while ($data = mysqli_fetch_array($query)) :
$barang = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM produk where produk_id='$data[idproduk]'"));
 $jum = $barang['produk_jumlah'];
 $jumbarang = $jum - $data['jumlah'];
 $simpan = mysqli_query($koneksi,"UPDATE produk SET produk_jumlah='$jumbarang' WHERE produk_id='$data[idproduk]'");
 $simpeun = mysqli_query($koneksi,"INSERT  INTO kantin_bayar(tanggal,idproduk,jumlah,harga,total,bulan,tahun) VALUES('$tanggal','$data[idproduk]','$data[jumlah]','$data[harga]','$data[total]','$bulan','$tahun')");
 if($simpeun){
	 mysqli_query($koneksi,"truncate keranjang");
 }
endwhile;
?>