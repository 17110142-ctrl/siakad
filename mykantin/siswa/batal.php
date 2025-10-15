<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$id = $_POST['id'];
	
	$hapus = mysqli_query($koneksi,"UPDATE transaksi SET status='3' WHERE id='$id'");
?>