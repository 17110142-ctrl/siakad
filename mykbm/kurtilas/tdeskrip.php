<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
 $smt = $setting['semester'];
if ($pg == 'deskrip3') {
	 $mapel = $_POST['mapel'];
     $level = $_POST['level'];
      $deskrip = $_POST['materi'];
	  $guru = $_POST['guru'];
	  $kd = $_POST['kd'];
	  
      $exec = mysqli_query($koneksi, "INSERT INTO deskripsi (mapel,level,deskripsi,smt,ki,kd,guru) VALUES ('$mapel','$level','$deskrip','$smt','KI3','$kd','$guru')");

}
if ($pg == 'edit_deskrip3') {
	 $id = $_POST['id'];
     $deskrip = $_POST['materi'];
        $exec = mysqli_query($koneksi, "UPDATE deskripsi SET deskripsi='$deskrip' WHERE id='$id'");

}
if ($pg == 'hapus') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM deskripsi WHERE id='$id'");
}
if ($pg == 'deskrip4') {
	$mapel = $_POST['mapel'];
     $level = $_POST['level'];
      $deskrip = $_POST['materi'];
	  $guru = $_POST['guru'];
	  $kd = $_POST['kd'];
         $exec = mysqli_query($koneksi, "INSERT INTO deskripsi (mapel,level,deskripsi,smt,ki,kd,guru) VALUES ('$mapel','$level','$deskrip','$smt','KI4','$kd','$guru')");

}
if ($pg == 'edit_deskrip4') {
	 $id = $_POST['id'];
    $deskrip = $_POST['materi'];
        $exec = mysqli_query($koneksi, "UPDATE deskripsi SET deskripsi='$deskrip' WHERE id='$id'");

}
if ($pg == 'hapus4') {
	 $id = $_POST['id'];
   
        $exec = mysqli_query($koneksi, "DELETE FROM deskripsi WHERE id='$id'");

}

