<?php ob_start();
error_reporting(0);
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$id=$_GET['id'];
$surat=fetch($koneksi,'bk_surat',['idsp'=>$id]);
$siswa=fetch($koneksi,'siswa',['nis'=>$surat['nis']]);
$bl=date('m');
$bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>SP3</title>
<link rel='stylesheet' href='../vendor/css/bootstrap.min.css' />
</head>
<style>
@page { margin: 30px; }
body { margin: 30px; }
</style>
<body style="font-size: 13px;">
 <div class="col-md-12">
<center><h4><?= strtoupper($setting['header']) ?><br><?= strtoupper($setting['sekolah']) ?></h4></center>
	   <center> Alamat : <?= $setting['alamat']; ?> Kec. <?= $setting['kecamatan']; ?> Kab. <?= $setting['kabupaten']; ?></center>
	    <center> Email : <?= $setting['email']; ?></center>
	 				<hr>
   
     <img src="../images/<?= $setting['logo'] ?>" style="margin-left:20px ;margin-top:-90px ;width: 80px;">
 
   
    <table>
	<tbody>
     <tr>
       <td width='100px'>Nomor Surat</td>
        <td width='10px'>:</td>
         <td><?= $surat['nosurat'] ?></td>
	    </tr>
		<tr>
		<td>Lampiran</td>	
         <td>:</td>   
		<td>-</td>
		</tr>
		<tr>
		<td>Perihal</td>	
         <td>:</td>   
		<td>Peringatan</td>
		</tr>
			</tbody>
    </table>
	 <table>
	<tbody>
     <tr>
	  <td width='450px'></td>
       <td width='200px'>
	   Kepada Yth.<br>
	   Bapak/Ibu Orang Tua Siswa dari<br>
	   <b><?= $siswa['nama'] ?></b><br>
		di<br>
		Tempat
	   </td>        
		</tr>
			</tbody>
    </table>
	
	 <table>
	<tbody>
     <tr>
	  <td width='650px'>
       Dengan hormat,<br><br>
	   <p style="text-align:justify; text-indent:0.5in;">Bersamaan surat ini kami sampaikan bahwa ananda <b><?= $siswa['nama'] ?></b> sudah melakukan dan mengulangi pelanggaran aturan sekolah <?= $setting['sekolah'] ?>, yakni tidak mengikuti aturan tata tertib yang berlaku diantaranya:</p>
	   </td>        
		</tr>
		
		 <?php
		 $no=0;
          $query = mysqli_query($koneksi, "select * from bk_siswa WHERE nis='$siswa[nis]' AND sts='SP3'");                          
           while ($bk = mysqli_fetch_array($query)) {
			$no++;   
			   ?>
			   <tr>
			   <td style="text-align:justify; text-indent:0.5in;">
			   <?= $no ?>. <?= $bk['ket'] ?>
			   </td>
			   </tr>
		   <?php } ?>
		
		<tr>
		<td>
		<br>
		 <p style="text-align:justify; text-indent:0.5in;">
		 Kami memberikan kesempatan pada <b><?= $siswa['nama'] ?></b> untuk tidak mengulangi hal tersebut lagi sejak surat ini diterbitkan. Apabila terbukti melanggar aturan tersebut kembali maka teguran SP 1, SP 2, SP 3 akan kami lanjutkan dengan DO dari sekolah ini.
		 </p>
		 <p style="text-align:justify; text-indent:0.5in;">
		 Untuk itu kami memberikan sanksi berupa : <?= $surat['sanksi'] ?>
		 </p>
		  <p style="text-align:justify; text-indent:0.5in;">
		 Demikian Surat Peringatan terakhir kami sampaikan kepada Bapak/Ibu Orang Tua Siswa. Mohon perhatian dan pendampingannya dari Bapak/Ibu. Terima kasih.
		  </p>
		</td>
		</tr>
			</tbody>
    </table>
	
	 <table>
	<tbody>
     <tr>
						<td>
							 <br/>
							 <br/>
							<br/>
							<br/>
							<br/>
							
							<u></u><br/>
							 
						</td>
						<td width='450px'></td>
						<td>
							<?= $setting['kecamatan'] ?>, <?= date('d') ?> <?= $bulane['ket'] ?> <?= date('Y') ?><br/>
							Kepala Sekolah<br/>
							<br/>
							<br/>
							<br/>
							
							<u><b><?= $setting['kepsek'] ?></b></u><br/>
							NIP. <?= $setting['nip'] ?>
						</td>
					</tr>
				</table>
	</div>
	
</body>
</html>
<?php

$html = ob_get_clean();
require_once '../vendor/vendors/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'potrait');
$dompdf->render();
$dompdf->stream("SP3 $siswa[nama].pdf", array("Attachment" => false));

exit(0);
?>