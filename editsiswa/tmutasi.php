<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
    $tahun = date('Y');
    $mutasi = $_POST['mutasi'];
	$kelas = $_POST['kelas'];
	$naik = $_POST['naik'];
   $ids = $_POST['idsiswa'];
if($mutasi=='keluar'):	
$count = count($_POST['idsiswa']);

for( $i=0; $i < $count; $i++ ){
$user = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa  WHERE id_siswa='$ids[$i]'"));
$nama = addslashes($user['nama']);
$simpan = mysqli_query($koneksi,"INSERT INTO alumni(id_siswa,nis,nisn,nama,kelas,jurusan,jk,tgl_mutasi,tahun_lulus) VALUES('$ids[$i]','$user[nis]','$user[nisn]','$nama','$user[kelas]','$user[jurusan]','$user[jk]','$tanggal','$tahun')");
if($simpan){
	mysqli_query($koneksi,"DELETE FROM siswa where id_siswa='$ids[$i]'");
}
}

else:
$count = count($_POST['idsiswa']);
for( $i=0; $i < $count; $i++ ){

$simpan = mysqli_query($koneksi,"UPDATE siswa SET kelas='$naik' where id_siswa='$ids[$i]'");
}
endif;
?>