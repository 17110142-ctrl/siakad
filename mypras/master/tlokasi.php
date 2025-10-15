<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
 $smt = $setting['semester'];
if ($pg == 'tambah') {
	 $kode = $_POST['kode'];
     $nama = $_POST['nama'];
      
      $exec = mysqli_query($koneksi, "INSERT INTO s_lokasi (kode,nama) VALUES ('$kode','$nama')");

}

if ($pg == 'hapus') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM s_lokasi WHERE id='$id'");
}

if ($pg == 'edit') {
	 $id = $_POST['id'];
    $nama = $_POST['nama'];
        $exec = mysqli_query($koneksi, "UPDATE s_lokasi SET nama='$nama' WHERE id='$id'");

}
if ($pg == 'kate') {
	 $kode = $_POST['kate'];
      
      $exec = mysqli_query($koneksi, "INSERT INTO s_kategori (kategori) VALUES ('$kode')");
}
if ($pg == 'hapuskate') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM s_kategori WHERE id='$id'");
}

if ($pg == 'editkate') {
	 $id = $_POST['id'];
    $nama = $_POST['kate'];
        $exec = mysqli_query($koneksi, "UPDATE s_kategori SET kategori='$nama' WHERE id='$id'");

}