<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate nilai");
$exec = mysqli_query($koneksi, "truncate banksoal");
$exec = mysqli_query($koneksi, "truncate soal");
$exec = mysqli_query($koneksi, "truncate ujian");
$exec = mysqli_query($koneksi, "truncate jawaban");
$exec = mysqli_query($koneksi, "truncate jawaban_dup");
$exec = mysqli_query($koneksi, "truncate jawaban_soal");
$exec = mysqli_query($koneksi, "truncate jodoh");
$exec = mysqli_query($koneksi, "truncate kunci_soal");

$foto = glob('../../files/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}

?>