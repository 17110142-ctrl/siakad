<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

	$id = $_POST['id'];
	
	$hapus = mysqli_query($koneksi,"DELETE FROM transaksi WHERE id='$id'");
?>