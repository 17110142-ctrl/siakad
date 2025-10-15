<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate transaksi");
$exec = mysqli_query($koneksi, "truncate tmpbuku");
$exec = mysqli_query($koneksi, "truncate tmpsis");
$exec = mysqli_query($koneksi, "truncate m_buku");
$exec = mysqli_query($koneksi, "truncate buku");
$gambar = glob('../../temp/perpus/*'); 
foreach ($gambar as $filex) {
    if (is_file($filex))
        unlink($filex); 
}
?>