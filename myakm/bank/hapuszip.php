<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$kode = $_POST['idz'];
$filezip = '../../'.$kode.'.zip';
     unlink($filezip); 
		
		