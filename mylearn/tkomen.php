<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	$id=$_POST['ids'];
	
	$komentar=$_POST['komentar'];
	mysqli_query($koneksi,"UPDATE komentar SET balasan='$komentar' where id='$id'");
}

?>