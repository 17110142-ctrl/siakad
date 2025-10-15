<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

    $server = $_POST['id'];
	if($server=='lokal'){
   mysqli_query($koneksi,"UPDATE aplikasi set server='pusat' where id_aplikasi='1'");
	}else{
		mysqli_query($koneksi,"UPDATE aplikasi set server='lokal' where id_aplikasi='1'");
	}
?>