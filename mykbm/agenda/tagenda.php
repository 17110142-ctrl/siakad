<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
 
if ($pg == 'tambah') {
	$tgl = $_POST['tgl'];
	 $guru = $_POST['guru'];
	$hari = date('D',strtotime($tgl));
	$bulan = date('m',strtotime($tgl));
	$tahun = date('Y',strtotime($tgl));
	$jadwal = $_POST['jadwal'];
	$mapel = $_POST['mapel'];
	  $kuri = $_POST['kuri'];
      $kd = $_POST['kd'];
	  $kelas = $_POST['kelas'];
	  $jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where kelas='$kelas'"));
	  $jabs = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas='$kelas' and tanggal='$tgl' and ket='H'"));
	  $hadir = round(($jabs/$jsiswa)*100);
	   if($kuri=='2'):
	    $tujuan=implode(', ',$_POST['tp']);
	  $lingkup = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM lingkup  WHERE id='$kd'"));
	  $materi = $lingkup['materi'];
      $exec = mysqli_query($koneksi, "INSERT INTO agenda (jadwal,hari,tanggal,kelas,mapel,materi,tujuan,bulan,tahun,hadir,guru) VALUES ('$jadwal','$hari','$tgl','$kelas','$mapel','$materi','$tujuan','$bulan','$tahun','$hadir','$guru')");
      else:
	   $materi = $_POST['materi'];
	   $desk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM deskripsi  WHERE id='$kd'"));
	   $kade = $desk['kd'];
	  $deskripsi = $desk['deskripsi'];
      $exec = mysqli_query($koneksi, "INSERT INTO agenda (jadwal,hari,tanggal,kelas,mapel,kd,materi,tujuan,bulan,tahun,hadir,guru) VALUES ('$jadwal','$hari','$tgl','$kelas','$mapel','$kade','$materi','$deskripsi','$bulan','$tahun','$hadir','$guru')");
	  endif;
}
if ($pg == 'edit') {
	 $id = $_POST['id'];
	 $tgl = $_POST['tgl'];
     $kelas = $_POST['kelas'];
      $materi = $_POST['materi'];
      $kuri = $_POST['kuri'];
	  $kd = $_POST['kd'];
	 
	  $jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where kelas='$kelas'"));
	  $jabs = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas='$kelas' and tanggal='$tgl' and ket='H'"));
	  $hadir = round(($jabs/$jsiswa)*100);
	   if($kuri=='2'):
	    $tujuan=implode(', ',$_POST['tp']);
	  $lingkup = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM lingkup  WHERE id='$kd'"));
	  $materi = $lingkup['materi'];
      $exec = mysqli_query($koneksi, "UPDATE agenda SET materi='$materi',tujuan='$tujuan',hadir='$hadir' WHERE id='$id'");
      else:
	   $materi = $_POST['materi'];
	   
      $exec = mysqli_query($koneksi, "UPDATE agenda SET materi='$materi',hadir='$hadir' WHERE id='$id'");
	  endif;
}
if ($pg == 'hapus') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM agenda WHERE id='$id'");
}

if ($pg == 'jurnal') {
	 $id = $_POST['id'];
     $hambat = $_POST['hambatan'];
	 $pecah = $_POST['pemecahan'];
        $exec = mysqli_query($koneksi, "UPDATE agenda SET hambatan='$hambat',pemecahan='$pecah' WHERE id='$id'");

}

