<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
?>
	 <?php
$folder = "../../photo";
$gambar = scandir($folder);

foreach($gambar as $img);
    
?>			

 <img src="../photo/<?= $img ?>" alt="" style="width:100%">
