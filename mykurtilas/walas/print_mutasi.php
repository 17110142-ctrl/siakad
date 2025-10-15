<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$ids=$_GET['ids'];
$tp=date('Y');
$tpk=$tp-1;
$siswa = fetch($koneksi, 'siswa', ['nis' => $ids]);
if($setting['jenjang']=='SMK'){
	$sekolah = 'Sekolah Menengah Kejuruan';
}
if($setting['jenjang']=='SMA'){
	$sekolah = 'Sekolah Menengah Atas';
}
if($setting['jenjang']=='SMP'){
	$sekolah = 'Sekolah Menengah Pertama';
}	
if($setting['jenjang']=='SD'){
	$sekolah = 'Sekolah Dasar';
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>LEMBAR MUTASI</title>

  <link rel="stylesheet" href="../../vendor/css/bootstrap.min.css">

 <style>
    h1 {
      font-size: 24px;
      color: slateblue;
    }
	h6 {
      font-size: 18px;
    
    }
    .font-big {
       font-size: 14px;
    }

    .font-small {
      font-size: small;
    }
  </style>

</head>

<body>
   
   <br>
   <center>
        <h6>KETERANGAN PINDAH SEKOLAH</h6>
		<br>
		<h6 class="font-small">Nama Peserta Didik: ........................................</h6>
    </center>
    <br>
    <br>
  
        <table style="margin-left:30px;margin-right:10px"  width="100%" border="1">
		<tr>
		<td colspan="4" style="text-align:center;font-weight:bold">KELUAR</td>
		</tr>
            <tr>
			  <td style="text-align:center;font-weight:bold">Tanggal</td>
                <td style="text-align:center;font-weight:bold">Kelas yang Ditinggalkan</td>
                <td style="text-align:center;font-weight:bold">Sebab-sebab Keluar atau atas Permintaan (Tertulis)</td>
			<td style="text-align:center;font-weight:bold">Tanda Tangan Kepala Sekolah, Stempel Sekolah, dan Tanda Tangan Orang Tua/Wali</td>
            </tr>
            <tr>
			<td></td>
			<td></td>
			<td></td>
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			<br><br>Orang Tua/Wali,<br><br><br>........................................
			</td>
			</tr>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			<br><br>Orang Tua/Wali,<br><br><br>........................................
			</td>
			</tr>
			<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			<br><br>Orang Tua/Wali,<br><br><br>........................................
			</td>
			</tr>
        </table>
       <br>
 	 <div style='page-break-before:always;'></div>
     <br>
   <center>
        <h6>KETERANGAN PINDAH SEKOLAH</h6>
		<br>
		<h6 class="font-small">Nama Peserta Didik: ........................................</h6>
    </center>
    <br>
    <br>
  
        <table style="margin-left:30px;margin-right:10px"  width="100%" border="1">
		
            <tr>
			  <td style="text-align:center;font-weight:bold">No</td>
                <td style="text-align:center;font-weight:bold" colspan="3">MASUK</td>
               
            </tr>
            <tr>
			<td style="text-align:center;">
			1<br>
			2<br>
			3<br>
			4<br>
			<br>
			<br>
			5<br>
			</td>
			<td width="25%">
			&nbsp;Nama Peserta didik<br>
			&nbsp;Nomor Induk<br>
            &nbsp;Nama Sekolah<br>
			&nbsp;Masuk di Sekolah ini:<br>
				&nbsp;a. Tanggal<br>
				&nbsp;b. Di Kelas<br>
				&nbsp;Tahun Pelajaran<br>
             </td>
			<td width="35%">
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			</td>
			
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			</td>
			</tr>
			
			 <tr>
			<td style="text-align:center;">
			1<br>
			2<br>
			3<br>
			4<br>
			<br>
			<br>
			5<br>
			</td>
			<td width="25%">
			&nbsp;Nama Peserta didik<br>
			&nbsp;Nomor Induk<br>
            &nbsp;Nama Sekolah<br>
			&nbsp;Masuk di Sekolah ini:<br>
				&nbsp;a. Tanggal<br>
				&nbsp;b. Di Kelas<br>
				&nbsp;Tahun Pelajaran<br>
             </td>
			<td width="35%">
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			</td>
			
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			</td>
			</tr>
			
			 <tr>
			<td style="text-align:center;">
			1<br>
			2<br>
			3<br>
			4<br>
			<br>
			<br>
			5<br>
			</td>
			<td width="25%">
			&nbsp;Nama Peserta didik<br>
			&nbsp;Nomor Induk<br>
            &nbsp;Nama Sekolah<br>
			&nbsp;Masuk di Sekolah ini:<br>
				&nbsp;a. Tanggal<br>
				&nbsp;b. Di Kelas<br>
				&nbsp;Tahun Pelajaran<br>
             </td>
			<td width="35%">
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			</td>
			
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			</td>
			</tr>
			
			 <tr>
			<td style="text-align:center;">
			1<br>
			2<br>
			3<br>
			4<br>
			<br>
			<br>
			5<br>
			</td>
			<td width="25%">
			&nbsp;Nama Peserta didik<br>
			&nbsp;Nomor Induk<br>
            &nbsp;Nama Sekolah<br>
			&nbsp;Masuk di Sekolah ini:<br>
				&nbsp;a. Tanggal<br>
				&nbsp;b. Di Kelas<br>
				&nbsp;Tahun Pelajaran<br>
             </td>
			<td width="35%">
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			</td>
			
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			</td>
			</tr>
			
			 <tr>
			<td style="text-align:center;" width="5%">
			1<br>
			2<br>
			3<br>
			4<br>
			<br>
			<br>
			5<br>
			</td>
			<td width="25%">
			&nbsp;Nama Peserta didik<br>
			&nbsp;Nomor Induk<br>
            &nbsp;Nama Sekolah<br>
			&nbsp;Masuk di Sekolah ini:<br>
				&nbsp;a. Tanggal<br>
				&nbsp;b. Di Kelas<br>
				&nbsp;Tahun Pelajaran<br>
             </td>
			<td width="35%">
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			.........................................<br>
			</td>
			
			<td>......., ...............................<br>Kepala Sekolah,<br><br><br><br><?= $setting['kepsek'] ?><br>NIP.<?= $setting['nip'] ?>
			</td>
			</tr>
			
        </table>
       <br>
 	 
     </body>
</html>
<?php

$html = ob_get_clean();
require_once '../../vendors/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Lembar Mutasi.pdf", array("Attachment" => false));
exit(0);
?>