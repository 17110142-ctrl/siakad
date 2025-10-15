<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$tgl = date('d');
$blth = date('mY');
$jamabsen= date('H:i');
$waktusandik = date('H:i');
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
	}

if($tgl == $setting['tgltrx']):
if (date('H:i:s')==$setting['jamkirim']){
$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE NOT EXISTS(SELECT * FROM trx_bayar WHERE siswa.id_siswa=trx_bayar.idsiswa AND trx_bayar.blth='$blth')");
while ($sis = mysqli_fetch_array($query)){

 $byr = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_bayar WHERE id='$setting[idbayar]'"));
 $pesan = "Assalamualaikum wr.wb\n\n Kamiinformasikan bahwa Ananda ".$sis['nama']." pada bulan ini belum melakukan iuran pembayaran ".$byr['nama']."\n\n Demikian informasi ini kami sampaikan. Harap menjadi perhatian Orang Tua siswa\n\n Wassalamualaikum wr.wb\n\n";
 sleep(1);


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $setting['url_api'].'/send-message',  
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('message' => $pesan.' *Dikirim melalui Server Gateway '.$setting['sekolah'].'*','number' => $sis['nowa']),
));

$response = curl_exec($curl);
curl_close($curl);
}
sleep(1);
mysqli_close($koneksi);
}
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