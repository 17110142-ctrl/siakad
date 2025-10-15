<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
cek_session_admin();
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';



if ($pg == 'hapus') {
    $id = $_POST['id'];
    delete($koneksi, 'jenis', ['id_jenis' => $id]);
}
if ($pg == 'tambah') {
    $data = [
	'id_jenis' => $_POST['kode'],
	'nama' => $_POST['nama'],
	'status' => $_POST['status']
	];
   $simpan = insert($koneksi,'jenis',$data);
   echo $simpan;
}


if ($pg == 'edit') {
	$id = $_POST['id'];
	 $data = [
	'id_jenis' => $_POST['kode'],
	'nama' => $_POST['nama'],
	'status' => $_POST['status']
	];
	$datax=[
	'nama_ujian' => $_POST['nama']
	];
   $simpan = update($koneksi,'jenis',$data,['id_jenis' =>$id]);
   $exec = update($koneksi,'aplikasi',$datax,['id_aplikasi' =>1]);
   echo $simpan;
	
}