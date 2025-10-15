<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$wkt = $_POST['waktu'];
mysqli_query($koneksi,"UPDATE ujian set pelanggaran='$wkt'");
?>