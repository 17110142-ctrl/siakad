<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$exec = mysqli_query($koneksi, "truncate tugas");
$exec = mysqli_query($koneksi, "truncate materi");
$exec = mysqli_query($koneksi, "truncate jawaban_tugas");

$foto = glob('../../tugas/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}
$gambar = glob('../../materi/*'); 
foreach ($gambar as $file) {
    if (is_file($file))
        unlink($file); 
}
?>