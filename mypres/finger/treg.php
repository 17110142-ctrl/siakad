<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
 $id = $_POST['id'];
  $reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM datafinger  WHERE id='$id'"));
  $data = [
		'idjari' => $reg['idjari'],
		'nama' => $reg['nama'],
		'serial' => $reg['serialnumber']
    ];
	$exec = insert($koneksi, 'temp_finger', $data);
	
	if($reg['level']=='pegawai'):				          
    mysqli_query($koneksi, "update users SET sts_jari='0',idjari=NULL WHERE idjari='$reg[idjari]'");
	elseif($reg['level']=='siswa'):
	 mysqli_query($koneksi, "update siswa SET sts_jari='0',idjari=NULL WHERE idjari='$reg[idjari]'");
	endif;
    delete($koneksi, 'datafinger', ['id' => $id]);
	
}	
if ($pg == 'tambah') {
	$id = $_POST['id'];
	$jari = $_POST['nokartu'];
   $where = [
  'idjari' => $_POST['nokartu'],		
    ];
    
	$data = [
		'idjari' => $_POST['nokartu'],
		'nama' => $_POST['nama'],
		'serial' => $_POST['serial'],
		'level' =>'siswa'
    ];
	
 $cek = rowcount($koneksi, 'datafinger', $where);
 if ($cek == 0) {
 $exec = insert($koneksi, 'datafinger', $data);
 mysqli_query($koneksi, "UPDATE siswa SET sts_jari='1', idjari='$jari' WHERE id_siswa='$id'");
		
	}

}	
if ($pg == 'tambahpeg') {
	$id = $_POST['id'];
	$jari = $_POST['nokartu'];
   $where = [
  'idjari' => $_POST['nokartu'],		
    ];
    
	$data = [
		'idjari' => $_POST['nokartu'],
		'nama' => $_POST['nama'],
		'serialnumber' => $_POST['serial'],
		'level' =>'pegawai'
    ];
	
 $cek = rowcount($koneksi, 'datafinger', $where);
 if ($cek == 0) {
 $exec = insert($koneksi, 'datafinger', $data);
 mysqli_query($koneksi, "UPDATE users SET sts_jari='1', idjari='$idjari' WHERE id_user='$id'");
		 
	}

}	

?>