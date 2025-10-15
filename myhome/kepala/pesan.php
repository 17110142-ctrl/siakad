<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$id = $_POST['id'];
$nama = $_POST['nama'];
$file = $_POST['file'];
$kelas = $_POST['kelas'];
$pesan = $_POST['pesan'];
$nowa = $_POST['nowa'];
$kirim = "Assalamualaikum.wr.wb\n\n Saya selaku Kepala Sekolah ".$setting['sekolah']." menginformasikan bahwa Sdr/i ".$nama." telah Upload Data Administrasi berupa File dengan nama ".$file."-".$kelas." dan saya sampaikan pesan saya yaitu ".$pesan;
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
  CURLOPT_POSTFIELDS => array('message' => $kirim.' *Layanan ini dikirim melalui Server Gateway '.$setting['sekolah'].'*','number' => $nowa),
));

$response = curl_exec($curl);
curl_close($curl);
if($response){
	$datax=[
'pesan'=>$kirim
];
 $exec = update($koneksi, 'adm', $datax, ['id' => $id]);
}


?>
