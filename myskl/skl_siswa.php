<?php ob_start();

require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
include "../vendor/phpqrcode/qrlib.php";
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}

$nis = $_GET['nis'];
$siswa = fetch($koneksi, 'siswa', ['nis' => $nis]);
$pk = $siswa['jurusan'];
$skl = fetch($koneksi, 'skl', ['id_skl' => 1]);
$tempdir = "../temp/"; 
if (!file_exists($tempdir)) 
    mkdir($tempdir);

$codeContents = $siswa['nis'] . '-' . $siswa['nama'];

QRcode::png($codeContents, $tempdir . $siswa['nis'] . '.png', QR_ECLEVEL_M, 4);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>SKL_<?= $siswa['nama'] ?></title>
<link rel="stylesheet" href="../vendor/css/bootstrap.min.css">
</head>
<style>
@page { margin: 20px; }
body { margin: 20px; }
</style>
<body style="font-size: 12px;">
    <?php if ($skl['header'] == '') { ?>
        <h3><?= $setting['nama_sekolah'] ?></h3>
        <p><small> <?= $setting['alamat'] ?></small></p>
    <?php } else { ?>
        <img src="../<?= $skl['header'] ?>" width="100%">
    <?php } ?>
   
    <center>
        <h4><u><?= $skl['nama_surat'] ?></u></h4>
        No. Surat : <?= sprintf("432.1-%03d", $siswa['id_siswa']); ?><?= $skl['no_surat'] ?><?= date('Y') ?>
    </center>
 
      <div style='width:100%; margin:10px;text-align:justify'>
	  <?= $skl['pembuka'] ?>
	  </div>
		
		
        <table style="margin-left: 60px;margin-right:60px" border="1" width="100%">
            <tr>
                <td style="width:200px">&nbsp;Nama</td>
                <td>&nbsp;<?= $siswa['nama'] ?></td>
            </tr>
            <tr>
                <td>&nbsp;Tempat, Tgl Lahir</td>
                <td>&nbsp;<?= $siswa['tempat_lahir'] ?>, <?= $siswa['tgl_lahir'] ?></td>
            </tr>
            <tr>
                <td>&nbsp;NIS / NISN</td>
                <td>&nbsp;<?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?> </td>
            </tr>
            <?php if($setting['jenjang']=='SMK' OR $setting['jenjang']=='SMA'){ ?>
                <tr>
                    <td>&nbsp;Kompetensi</td>
                    <td>&nbsp;<?= $siswa['jurusan'] ?></td>
                </tr>
            <?php } ?>
        </table>
		
		 <div style='width:100%; margin:10px;text-align:justify'>
       <?= $skl['isi_surat'] ?> 
	   </div>
        <center>
            <?php if ($siswa['keterangan'] == 1) { ?>
                <h4><b>LULUS</b></h4>
            <?php } elseif ($siswa['keterangan'] == 2) { ?>
                <h4><b>LULUS BERSYARAT</b></h4>
            <?php } else { ?>
                <h4><b>TIDAK LULUS</b></h4>
            <?php } ?>
        </center>

        <?php if ($skl['nilai'] == 1) { ?>
            <table style="margin-left: 30px;margin-right:30px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th style="height:40px;width:30px;text-align:center;">No</th>
                        <th style="text-align:center;">Mata Pelajaran</th>
                        <th style="width: 60px;text-align:center;"> Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($skl['kelompok'] == 1) { ?>
                        <?php
                        $q1 = mysqli_query($koneksi, "SELECT * FROM mapel_ijazah WHERE jurusan='$pk' group by kelompok order by kelompok ASC");
                        $total = 0;
                        $no = 0;
                        while ($kelompok = mysqli_fetch_array($q1)) {

                            $query = mysqli_query($koneksi, "SELECT * FROM  mapel_ijazah WHERE jurusan='$kelompok[jurusan]' AND kelompok='$kelompok[kelompok]' group by kode  order by urut ");
                          $ceklis=$kelompok['kelompok'];
						  if($setting['jenjang']=='SMK'){
							  if($ceklis=='A'){
							{$grade="A. Muatan Nasional";}
							}elseif($ceklis=='B'){
							{$grade="B. Muatan Kewilayahan";}
							}elseif($ceklis=='C1'){
							{$grade="C1. Dasar Bidang Keahlian";}
							}elseif($ceklis=='C2'){
							{$grade="C2. Dasar Program Keahlian";}
							}elseif($ceklis=='C3'){
							{$grade="C3. Kompetensi Keahlian";}
							}
						  }
						  if($setting['jenjang']=='SMA' OR $setting['jenjang']=='PAKET-C'){
							  if($ceklis=='A'){
							{$grades="A. (Umum)";}
							}elseif($ceklis=='B'){
							{$grades="B. (Umum)";}
							}elseif($ceklis=='C'){
							{$grades="C. (Peminatan)";}
							}
						  }
						  if($setting['jenjang']=='SD' OR $setting['jenjang']=='SMP' OR $setting['jenjang']=='PAKET-A' OR $setting['jenjang']=='PAKET-C'){
							  if($ceklis=='A'){
							{$gra="A. (Umum)";}
							}elseif($ceklis=='B'){
							{$gra="B. (Muatan Lokal)";}
							
							}
						  }
                        ?>
                            <tr>
							<?php if($setting['jenjang']=='SMK'){ ?>
                                <td colspan="3">&nbsp;&nbsp;<b><?= $grade ?></b></td>
							<?php }elseif($setting['jenjang']=='SMA'){ ?>
							<td colspan="3">&nbsp;&nbsp;<b>Kelompok <?= $grades ?></b></td>
							<?php }else{ ?>
							<td colspan="3">&nbsp;&nbsp;<b>Kelompok <?= $gra ?></b></td>
							<?php } ?>
                            </tr>
                            <?php
                            while ($mapel = mysqli_fetch_array($query)) {
                           $nilai =  mysqli_fetch_array(mysqli_query($koneksi, "SELECT AVG(nilai) AS rata,nis,mapel,nilai FROM nilai_skl  WHERE mapel='$mapel[kode]' AND nis='$siswa[nis]' AND semester<>''"));
                            $no++;
                            $total = $total + floatval($nilai['rata']);
                            ?>
                                <tr>
                                    <td style="width:30px;height:20px;text-align:center"><?= $no ?></td>
                                    <td>&nbsp;&nbsp;<?= $mapel['namamapel'] ?></td>
                                    <td style="text-align:center;"><?= round($nilai['rata']) ?></td>
                                </tr>
                        <?php }
                        } ?>
                        <tr>
                            <td colspan="2" style="height:30px;text-align:center"><b>NILAI RATA RATA </b></td>
                            <td style="height:30px;text-align:center"> <?= number_format($total / $no,2)  ?></td>

                        </tr>
                    <?php } else { ?>

                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM mapel_ijazah WHERE jurusan='$iswa[idpk]'   order by urut ");
                        $no = 0;
                        $total = 0;
                        while ($mapel = mysqli_fetch_array($query)) {
                           $nilai =  mysqli_fetch_array(mysqli_query($koneksi, "SELECT AVG(nilai) AS rata,nis,mapel,nilai FROM nilai_skl  WHERE mapel='$mapel[kode]' AND nis='$siswa[nis]' AND semester<>''"));
                            $no++;
                            $total = $total + floatval($nilai['rata']);

                        ?>
                            <tr>
                                <td style="width:30px;height:20px;text-align:center"><?= $no ?></td>
                                <td>&nbsp;&nbsp;<?= $mapel['namamapel'] ?></td>
                                <td style="width: 50px;text-align:center"><?= round($nilai['rata']) ?></td>
                            </tr>
                        <?php }
                        ?>
                        <tr>
                            <td colspan="2" style="height:30px;text-align:center"><b>NILAI RATA RATA </b></td>
                            <td style="height:30px;text-align:center"> <b><?= number_format($total / $no,2)  ?></b></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
           
        <?php } ?>
		<div style='width:100%; margin:10px;text-align:justify'>
        <?= $skl['penutup'] ?>
        </div>
        <table width="100%">
            <tr>
                <td style="text-align: center">
                    <img class="img" src="../temp/<?= $siswa['nis'] ?>.png" width="150">
                </td>
				 <td style="text-align: center">
				 <?php if($siswa['foto']<>''){ ?>
                    <img class="img" src="../images/fotosiswa/<?= $siswa['foto'] ?>" width="100">
				 <?php }else{ ?>
				   <img class="img" src="../images/polos.png" width="100">
				  <?php } ?>
				
				  <p style="margin-top:-30px;"><?= $siswa['nama'] ?></p>
				  </td>
                <td></td>
                <td style="text-align: center">
                    <?= $setting['kecamatan'] ?>, <?= $skl['tgl_surat'] ?>
                    <p>Kepala Sekolah,</p>					
                    <br><br><br><br><br>
                    <b><u><?= $setting['kepsek'] ?></u></b>
                    <p>NIP. <?= $setting['nip'] ?></p>
                    <?php if ($skl['sttd'] == 1) { ?>
                        <img style="z-index: 800;position:absolute;margin-top:-130px;margin-left:60px" class="img" src="../images/<?= $setting['ttd'] ?>" width="200">
                    <?php } ?>
                    <?php if ($skl['sstempel'] == 1) { ?>
                        <img style="z-index: 920;position:relative;margin-top:-115px;margin-left:-115px;opacity:0.7" class="img" src="../images/<?= $setting['stempel'] ?>" width="160px">
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
	</body>

</html>
<?php

$html = ob_get_clean();
require_once '../vendors/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('Legal', 'portrait');
$dompdf->render();
$dompdf->stream("SKL_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>