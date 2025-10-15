<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$exec = mysqli_query($koneksi, "truncate s_lokasi");
$exec = mysqli_query($koneksi, "truncate s_barang");

?>