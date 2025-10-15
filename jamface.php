<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");

$jamabsen= date('H:i');
$waktusandik = date('H:i');
$bul = date('m');
$th=date('Y');
$sql = mysqli_query($koneksi, "select * from status_face");
	$data = mysqli_fetch_array($sql);
	$mode_absen = $data['mode'];
	$mode = "";
	if($mode_absen==1){
		echo $waktusandik." >>>> Masuk";	
	}else if($mode_absen==2){
		echo $waktusandik." >>> Pulang";
	}else if($mode_absen==3){
		echo $waktusandik." > Register";
	}

if($jamabsen == $setting['alpha']):

$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE NOT EXISTS(SELECT * FROM absensi WHERE siswa.id_siswa=absensi.idsiswa AND absensi.tanggal='$tanggal')");
while ($sis = mysqli_fetch_array($query)){
 $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$sis[id_siswa]' AND tanggal='$tanggal'"));
    if ($cek == 0) {
	$edis = mysqli_query($koneksi,"INSERT INTO absensi(tanggal,idsiswa,kelas,ket,masuk,pulang,level,bulan,tahun,mesin) VALUES('$tanggal','$sis[id_siswa]','$sis[kelas]','A','$waktu','$waktu','siswa','$bul','$th','RFID')");
   }
}
mysqli_close($koneksi);
endif;
if($waktusandik <= $setting['pulang']):
mysqli_query($koneksi,"UPDATE status set mode='1'");
mysqli_close($koneksi);
endif;
if($waktusandik >= $setting['pulang']):
mysqli_query($koneksi,"UPDATE status set mode='2'");
mysqli_close($koneksi);
endif;


?>