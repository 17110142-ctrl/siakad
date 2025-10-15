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
if($siswa['jk']=='L'){
$kelamin = 'Laki-laki';
}else{
$kelamin= 'Perempuan';
}	

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>Cover_Rapor_<?= $siswa['nama'] ?></title>

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
    <br><br><br><br>
   <center>
        <h6>LAPORAN</h6>
		<h6>HASIL CAPAIAN KOMPETENSI PESERTA DIDIK</h6>
		<?php if($setting['jenjang']=='SMA'): ?>
		<h6>SEKOLAH MENENGAH ATAS (SMA)</h6>
		<?php elseif($setting['jenjang']=='SMK'): ?>
		<h6>SEKOLAH MENENGAH KEJURUAN (SMK)</h6>
		<?php elseif($setting['jenjang']=='SMP'): ?>
		<h6>SEKOLAH MENENGAH PERTAMA (SMP)</h6>
		<?php elseif($setting['jenjang']=='SD'): ?>
		<h6>SEKOLAH DASAR (SD)</h6>
		<?php endif; ?>
		
    </center>
    <br>
    <br>
        
    <div class="col-md-12">
	
               <center><img src="../../images/kemdikbud.png" width="30%"></center>
         
        <br><br><br>
 <center class="font-big">Nama Peserta Didik</center>
    <br>
            <table style="margin-left: 70px;margin-right:50px;" border ="1" width="100%">
                    <tr style="text-align: center;font-size: 18px;">
                        <td><b><?php echo ucfirst($siswa['nama']); ?></b></td>
                    </tr>
					</table>
					<br><br><br>
 <center>No. Induk / NISN</center>
    <br>
	<table style="margin-left: 70px;margin-right:50px;" border ="1" width="100%">
                    <tr style="text-align: center;font-size: 14px;">
                        <td><?php echo $siswa['nis']; ?> / <?php echo $siswa['nisn']; ?></td>
                    </tr>
					</table>
					<br><br><br><br><br><br><br><br>
   <center>
        <h6>KEMENTRIAN PENDIDIKAN DAN KEBUDAYAAN</h6>
		<h6>REPUBLIK INDONESIA</h6>
		
    </center>
	<div style='page-break-before:always;'></div>

        <br><br>
 <center><h6>R A P O R</h6></center>
 <center>
 <?php if($setting['jenjang']=='SMA'): ?>
		<h6>SEKOLAH MENENGAH ATAS</h6>
		<h6>(SMA)</h6>
		<?php elseif($setting['jenjang']=='SMK'): ?>
		<h6>SEKOLAH MENENGAH KEJURUAN </h6>
		<h6>(SMK)</h6>
		<?php elseif($setting['jenjang']=='SMP'): ?>
		<h6>SEKOLAH MENENGAH PERTAMA</h6>
		<h6>(SMP)</h6>
		<?php elseif($setting['jenjang']=='SD'): ?>
		<h6>SEKOLAH DASAR</h6>
		<h6>(SD)</h6>
		<?php endif; ?>
 </center>
 <center><h6><?= $grades ?></h6></center>
    <br><br><br><br><br>
            <table style="margin-left: 70px;margin-right:50px;"  width="100%">
                    <tr style="font-size: 14px;">
                        <td>Nama Sekolah</td>
						<td>:</td>
						<td><?= $setting['sekolah'] ?></td>
                    </tr>
					<tr style="font-size: 14px;">
                        <td>N P S N</td>
						<td>:</td>
						<td><?= $setting['npsn'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Kelurahan/Desa</td>
						<td>:</td>
						<td><?= $setting['desa'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Kecamatan</td>
						<td>:</td>
						<td><?= $setting['kecamatan'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Kabupaten/Kota</td>
						<td>:</td>
						<td><?= $setting['kabupaten'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Provinsi</td>
						<td>:</td>
						<td><?= $setting['propinsi'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Website</td>
						<td>:</td>
						<td><?= $setting['web'] ?></td>
					</tr>
					<tr style="font-size: 14px;">
                        <td>Email</td>
						<td>:</td>
						<td><?= $setting['email'] ?></td>
					</tr>
					</table>
					
 <div style='page-break-before:always;'></div>
     <br><br>
   <center>
        <h6>IDENTITAS PESERTA DIDIK</h6>
		
    </center>
    <br>
    <br>
    <br>
    <div class="col-md-12">
	
        <table style="margin-left:30px;margin-right:10px"  width="100%">
            <tr>
			  <td width="1%">1.</td>
                <td width="20%">Nama Peserta Didik</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['nama'] ?></td>
            </tr>
            <tr>
			  <td width="1%">2.</td>
                <td >Nomor Induk</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['nis'] ?></td>
            </tr>
			 <tr>
			  <td width="1%">3.</td>
                <td >Tempat, Tanggal Lahir</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['t_lahir'] ?>, <?= $siswa['tgl_lahir'] ?> </td>
            </tr>
			 <tr>
			  <td width="1%">4.</td>
                <td >Jenis Kelamin</td>
                <td width="1%">:</td>
			<td width="40%"><?= $kelamin ?></td>
            </tr>
			 <tr>
			  <td width="1%">5.</td>
                <td >Agama</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['agama'] ?></td>
            </tr>
			 <tr>
			  <td width="1%">6.</td>
                <td >Pendidikan Sebelumnya</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['asal_sek'] ?></td>
            </tr>
			<tr>
			  <td width="1%">7.</td>
                <td >Alamat Peserta Didik</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['alamat'] ?> Desa <?= $siswa['desa'] ?> Kec. <?= $siswa['kecamatan'] ?> Kab. <?= $siswa['kabupaten'] ?></td>
            </tr>
			<tr>
			  <td width="1%">8.</td>
                <td >Nama Orang Tua</td>
                <td width="1%"></td>
			<td width="40%"></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">a. Ayah</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['ayah'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">b. Ibu</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['ibu'] ?></td>
            </tr>
			<tr>
			  <td width="1%">9.</td>
                <td >Pekerjaan Orang Tua</td>
                <td width="1%"></td>
			<td width="40%"></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">a. Ayah</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['pek_ayah'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">b. Ibu</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['pek_ibu'] ?></td>
            </tr>
			<tr>
			  <td width="1%">10.</td>
                <td >Alamat Orang Tua</td>
                <td width="1%"></td>
			<td width="40%"></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                <td width="12%">Jalan</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['alamat'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                <td width="12%">Kelurahan/Desa</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['desa'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                <td width="12%">Kecamatan</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['kecamatan'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                <td width="12%">Kabupaten</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['kabupaten'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
                <td width="12%">Propinsi</td>
                <td width="1%">:</td>
			<td width="40%"><?= $setting['propinsi'] ?></td>
            </tr>
			<tr>
			  <td width="1%">11.</td>
                <td >Wali Peserta Didik</td>
                <td width="1%"></td>
			<td width="40%"></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">a. Nama</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['wali'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">b. Pekerjaan</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['pek_wali'] ?></td>
            </tr>
			<tr>
			  <td width="4%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td width="12%">c. Alamat</td>
                <td width="1%">:</td>
			<td width="40%"><?= $siswa['alamat_wali'] ?></td>
            </tr>
			
        </table>
        <table width="100%">
            <tr>
               <td style="text-align: center;width:50px"></td>
                <td style="text-align: center;width:180px">
				<br>
                    <?php if ($siswa['foto'] <>'') { ?>
                        <img width="90" class="img" src="../../images/fotosiswa/<?= $siswa['foto'] ?>" width="80" >
                    <?php } ?>
                   <?php if ($siswa['foto'] =='') { ?>
                        <img width="90" class="img" src="../../images/polos.png">
                    <?php } ?>
                </td>

                <td style="text-align: center;width:180px">
                    
                </td>
                <td style="text-align: justify">
                    <?= $setting['kecamatan'] ?>, <?= $setting['tanggal_rapor'] ?><br>
                    Kepala Sekolah,

                   
                    
                        <br><br><br><br>
                    

                    <u><b><?= $setting['kepsek'] ?></b></u>
                    <br>
                    NUPTK. <?= $setting['nip'] ?>
                    
                        <br>
                        
                   
                </td>

            </tr>
        </table>
 	 <div style='page-break-before:always;'></div>
     <br><br>
   <center>
        <h6>PETUNJUK PENGISIAN</h6>	
    </center>
    <br>
    <br>
    <br>
	<p style="text-align:justify">Rapor merupakan ringkasan hasil penilaian terhadap seluruh aktivitas pembelajaran yang dilakukan peserta didik dalam satu semester. Rapor dipergunakan selama peserta didik mengikuti seluruh program pembelajaran di <?= $sekolah ?> yang bersangkutan.
Berikut petunjuk pengisian rapor.</p>
<br>

<p>1. Identitas Sekolah diisi dengan data yang sesuai dengan keberadaan <?= $sekolah ?>.</p>
<p>2. Keterangan tentang diri Peserta didik diisi lengkap.</p>
<p>3. Rapor harus dilengkapi dengan pas foto berwarna (3 x 4).</p>
<p style="text-align:justify">4. Sikap spiritual dan sikap sosial diisi dengan predikat (Sangat Baik, Baik, Cukup, atau Kurang) dan &nbsp;&nbsp;&nbsp;&nbsp;dilengkapi dengan deskripsi berdasarkan rangkuman hasil penilaian sikap dari semua guru mata &nbsp;&nbsp;&nbsp;&nbsp;pelajaran guru BK, dan wali kelas.</p>
<p style="text-align:justify">5. Deskripsi sikap spiritual dan sikap sosial ditulis menggunakan kalimat positif yang memotivasi untuk &nbsp;&nbsp;&nbsp;&nbsp;butir-butir nilai sikap yang sangat baik dan/atau kurang baik.</p>
<p>6. Kolom KKM diisi dengan KKM mata pelajaran untuk pengetahuan dan keterampilan.</p>
<p style="text-align:justify">7. Kolom nilai pada pengetahuan dan keterampilan ditulis dalam bentuk bilangan bulat pada skala 0- &nbsp;&nbsp;&nbsp;&nbsp;100</p>
<p style="text-align:justify">8. Kolom predikat pada pengetahuan dan keterampilan diisi berdasarkan interval predikat (D – A) yang &nbsp;&nbsp;&nbsp;&nbsp;ditetapkan satuan pendidikan.</p>
<p style="text-align:justify">9. Kolom deskripsi pada pengetahuan dan keterampilan ditulis dengan singkat menggunakan kalimat &nbsp;&nbsp;&nbsp;&nbsp;positif untuk capaian tertinggi dan kalimat yang memotivasi untuk capaian terendah.</p>
<p style="text-align:justify">10. Kolom predikat pada ekstrakurikuler diisi dengan sangat baik, baik, cukup, kurang, yang kriterianya &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ditetapkan oleh satuan pendidikan. Kolom deskripsi diisi dengan penjelasan sikap dan kecakapan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;yang dicapai.</p>
<p style="text-align:justify">11. Kolom jenis kegiatandiisi dengan kegiatan yang diikuti oleh peserta didik dalam bidang akademik &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dan non akademik pada kegiatan yang berkaitan dengan satuan pendidikan pada semester &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;berjalan. Contoh: Olimpiade Biologi, Paduan Suara,Paskibra.</p>
<p style="text-align:justify">12. Kolom keterangan pada prestasi diisi dengan tingkat wilayah. Contoh: Juara II Tingkat Kabupaten, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Juara I Tingkat Provinsi, Anggota Pasukan Pengibar Bendera tingkat Nasional.</p>
<p style="text-align:justify">13. Ketidakhadiran diisi dengan data akumulasi ketidakhadiran peserta didik karena sakit, izin, atau &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tanpa keterangan selama satu semester.</p>
<p style="text-align:justify">14. Catatan wali kelas diisi dengan saran-saran bagi peserta didik dan orang tua untuk diperhatikan.</p>
<p style="text-align:justify">15. Tanggapan orang tua/wali adalah komentar atas pencapaian hasil belajar peserta didik.</p>
	
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
$dompdf->stream("Raport_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>