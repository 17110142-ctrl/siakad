<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

	$id = $_POST['id'];
	
	$jumlah = $_POST['jumlah'];
	
	$simpan = mysqli_query($koneksi,"UPDATE keranjang SET jumlah='$jumlah' WHERE id='$id'");
mysqli_close($koneksi);
?>