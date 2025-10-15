<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
$tanggal = date('Y-m-d');
$jam = date('H:i:s');
$bulan = date('m');
$tahun    = date('Y');
$tglabsen = date('d M Y H:i:s');

	$nokartu = $_POST['uid'];
	$nokartu = str_replace("\r", '', $nokartu);

$status = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM status"));	
$absen = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND nokartu='$nokartu' ORDER BY id DESC LIMIT 1"));
$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$absen[idsiswa]'"));
$nowa = $data['nowa'];
$peg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$absen[idpeg]'"));
$pesan1 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='1'"));
$pesan2 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='2'"));
$pesan3 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='3'"));
$pesan4 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='4'"));

$notif_masuk_siswa = $pesan1['pesan1']." ".$pesan1['pesan2']." *".$data['nama']."* ".$pesan1['pesan3']." ".$tglabsen." ".$pesan1['pesan4'];
$notif_pulang_siswa = $pesan2['pesan1']." ".$pesan2['pesan2']." *".$data['nama']."* ".$pesan2['pesan3']." ".$tglabsen." ".$pesan2['pesan4'];
$notif_masuk_peg = $pesan3['pesan1']." ".$pesan3['pesan2']." *".$peg['nama']."* ".$pesan3['pesan3']." ".$tglabsen." ".$pesan3['pesan4'];
$notif_pulang_peg = $pesan4['pesan1']." ".$pesan4['pesan2']." *".$peg['nama']."* ".$pesan4['pesan3']." ".$tglabsen." ".$pesan4['pesan4'];


$query = mysqli_query($koneksi, "select * from datareg where nokartu='$nokartu'");
$cek = mysqli_num_rows($query);

	
if($status['mode']=='1' AND $cek<>0):
if($absen['level']=='pegawai'){

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
		  CURLOPT_POSTFIELDS => array('message' => $notif_masuk_peg,'number' => $setting['nowa'])
		));
		 curl_exec($curl);
		curl_close($curl);
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
		  CURLOPT_POSTFIELDS => array('message' => $notif_masuk_peg,'number' => $peg['nowa'])
		));
		 curl_exec($curl);
		curl_close($curl);
  }elseif($absen['level']=='siswa'){
 
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
		  CURLOPT_POSTFIELDS => array('message' =>$notif_masuk_siswa,'number' => $nowa)
		));
		curl_exec($curl);
		curl_close($curl);
	}
endif;


if($status['mode']=='2' AND $cek<>0):

if($absen['level']=='pegawai'){
	
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
		  CURLOPT_POSTFIELDS => array('message' => $notif_pulang_peg,'number' => $setting['nowa'])
		));
		curl_exec($curl);
		curl_close($curl);
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
		  CURLOPT_POSTFIELDS => array('message' => $notif_pulang_peg,'number' => $peg['nowa'])
		));
		curl_exec($curl);
		curl_close($curl);
	}elseif($absen['level']=='siswa'){

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
		  CURLOPT_POSTFIELDS => array('message' =>$notif_pulang_siswa,'number' => $nowa)
		));
		curl_exec($curl);
		curl_close($curl);	
	}
endif;

			?>