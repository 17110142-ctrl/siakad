<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$kode = $_POST['id'];

$exec = mysqli_query($koneksi, "DELETE FROM komentar WHERE id='$kode'");
