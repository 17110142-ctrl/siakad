<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
      $id = $_POST['kelas'];
	 
	  $ids = $_POST['siswa'];
    $exec = mysqli_query($koneksi, "UPDATE kelas SET ketua='$ids' WHERE id='$id'");
   		
}
if ($pg == 'kelas') {
    $id = $_POST['kelas'];
	$kls = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas WHERE id='$id'"));
	$kelas = $kls['kelas'];
    $sql = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='" . $kelas . "'");
    echo "<option value=''>Pilih Siswa</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[id_siswa]'>$data[nama]</option>";
    }
}

