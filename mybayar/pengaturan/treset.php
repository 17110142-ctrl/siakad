<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate trx_bayar");
$exec = mysqli_query($koneksi, "truncate m_bayar");
$exec = mysqli_query($koneksi, "truncate tmpbayar");
?>