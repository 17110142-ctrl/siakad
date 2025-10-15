<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$hari = date('D');
$waktusandik = date('H:i');
$waktu = date('H:i:s');
$bul = date('m');
$th=date('Y');
$sql = mysqli_query($koneksi, "select * from status");
	$data = mysqli_fetch_array($sql);
	$mode_absen = $data['mode'];
	$mode = "";
	if($mode_absen==1){
		echo $waktusandik." >>>> Masuk";	
	}else if($mode_absen==2){
		echo $waktusandik." >>> Pulang";
	}else if($mode_absen==3){
		echo $waktusandik." Masuk Les ";
	}else if($mode_absen==4){
		echo $waktusandik." Pulang Les ";
	}

$query = mysqli_query($koneksi,"SELECT * FROM waktu WHERE hari='$hari'");
while ($data = mysqli_fetch_array($query)) :
if($data['masuk']<>'' && $waktusandik <= $data['masuk']){
mysqli_query($koneksi,"UPDATE status set mode='1'");
}
if($data['pulang']<>'' && $waktusandik >= $data['pulang'] && $waktusandik < $data['masuk_eskul'] ){
mysqli_query($koneksi,"UPDATE status set mode='2'");
}
if($data['masuk_eskul']<>'' && $waktusandik >= $data['masuk_eskul'] && $waktusandik < $data['jam_eskul']){
mysqli_query($koneksi,"UPDATE status set mode='3'");
}
if($data['pulang_eskul']<>'' && $waktusandik >= $data['pulang_eskul']){
mysqli_query($koneksi,"UPDATE status set mode='4'");
}

endwhile;


$query = mysqli_query($koneksi,"SELECT * FROM waktu WHERE hari='$hari'");
while ($data = mysqli_fetch_array($query)) :

if($waktu == $data['alpha']):
$query = mysqli_query($koneksi,"SELECT id_siswa,kelas FROM siswa WHERE NOT EXISTS(SELECT idsiswa,tanggal,kelas FROM absensi WHERE siswa.id_siswa=absensi.idsiswa AND absensi.tanggal='$tanggal')");
while ($sis = mysqli_fetch_array($query)){
 $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa,tanggal FROM absensi WHERE idsiswa='$sis[id_siswa]' AND tanggal='$tanggal'"));
    if ($cek == 0) {
		$edis = mysqli_query($koneksi,"INSERT INTO absensi(tanggal,idsiswa,kelas,ket,masuk,pulang,level,bulan,tahun,mesin) VALUES('$tanggal','$sis[id_siswa]','$sis[kelas]','A','$waktu','$waktu','siswa','$bul','$th','RFID')");
   }
}

endif;
endwhile;


?>