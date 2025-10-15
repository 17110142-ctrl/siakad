<?php
require("../../config/koneksi.php");

$id = $_GET['id'];
$get = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_path FROM e_arsip WHERE id = '$id'"));

$file_path = '../../arsip/' . $get['file_path'];
if (file_exists($file_path)) {
    unlink($file_path); // Hapus file fisik
}

mysqli_query($koneksi, "DELETE FROM e_arsip WHERE id = '$id'");
echo "<script>alert('Arsip berhasil dihapus');window.location='list_arsip.php';</script>";
?>
