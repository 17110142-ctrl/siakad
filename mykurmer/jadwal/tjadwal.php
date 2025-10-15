<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	 $kode = $_POST['mapel'];
     $tingkat = $_POST['level'];
    $kelas = serialize($_POST['kelas']);
	  $guru = $_POST['guru'];
	  $kuri = $_POST['kuri'];
    $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jadwal_rapor WHERE  mapel='$kode' AND guru='$guru' AND level='$tingkat'"));
        if ($cek > 0) :
            echo "GAGAL";
        else :
		
        $exec = mysqli_query($koneksi, "INSERT INTO jadwal_rapor (level,kelas,guru,mapel,kuri) VALUES ('$tingkat','$kelas','$guru','$kode','$kuri')");
			if ($exec) {
                echo "OK";
            } else {
                echo mysqli_error($koneksi);
            }
       endif;
}
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'jadwal_rapor', ['id' => $id]);
    echo $exec;
}

if ($pg == 'kkm') {
	
     $tingkat = $_POST['level'];
	  $kkm = $_POST['kkm'];
    $exec = mysqli_query($koneksi, "UPDATE mapel_rapor SET kkm='$kkm' WHERE tingkat='$tingkat'");
      
			
}

if ($pg == 'multikkm') {
	 $kode = $_POST['mapel'];
      $tingkat = $_POST['level'];
	  $kkm = $_POST['kkm'];
    $exec = mysqli_query($koneksi, "UPDATE mapel_rapor SET kkm='$kkm' WHERE tingkat='$tingkat' AND kode='$kode' ");
       
			
}

if ($pg == 'rapor') {
	  $model = $_POST['model'];
      $level = $_POST['level'];
	
       $exec = mysqli_query($koneksi, "UPDATE kelas SET model_rapor='$model' WHERE level='$level'"); 			
}


if ($pg == 'kelas') {
    $id_level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='" . $id_level . "'");
   
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}
if ($pg == 'mapel') {
    $pk = $_POST['pk'];
    $sql = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE pk='" . $pk . "'");
   
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kode]'>$data[nama_mapel]</option>";
    }
}
if ($pg == 'model') {
	 $level = $_POST['level'];
	 $kelas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas  WHERE level='$level'"));
	 $kuri = $kelas['kurikulum'];
    $sql = mysqli_query($koneksi, "SELECT * FROM m_rapor WHERE kuri='$kuri'");
    echo"<option value=''>Pilih Model</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[idr]'>$data[model]</option>";
    }
}
if ($pg == 'ambilkuri') {
    $level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='" . $level . "' GROUP BY level");
   echo"<option value=''>Pilih Kurikulum</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kurikulum]'>$data[kurikulum]</option>";
    }
}
if ($pg == 'rombel') {
    $id_level = $_POST['tingkat'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='" . $id_level . "'");
   
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}