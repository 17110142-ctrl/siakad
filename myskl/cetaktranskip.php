<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$tahun=date('Y');
$ids=$_GET['nis'];

$siswa = fetch($koneksi, 'siswa', ['nis' => $ids]);
$kelas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas  WHERE id_kelas='$siswa[id_kelas]'"));
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>Transkip_<?= $siswa['nama'] ?></title>
<link rel="stylesheet" href="../../plugins/bootstrap/dist/css/bootstrap.min.css">
</head>
<style>
@page { margin: 20px; }
body { margin: 20px; }
</style>
<body style="font-size: 11px;">
    
   
    <center>
        <h5><?= strtoupper($setting['sekolah']); ?><br>TRANSKRIP NILAI</h5>
    </center>
    <br>
    
    <div class="col-md-14">
	<?php if($setting['jenjang']=='SMK'): ?>
        <table style="margin-left: 20px;margin-right:10px;font-size:11px"  width="100%">
            <tr>
                <td width="18%">Nama Siswa</td>
                <td width="1%">:</td>
				<td><?= $siswa['nama'] ?></td>
				<td width="7%"></td>
				<td width="18%">Tahun Pelajaran</td>
                <td width="1%">:</td>
				<td><?= $setting['tp'] ?></td>
            </tr>
            <tr>
                <td >NIS / NISN</td>
                <td>:</td>
				<td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?> </td>
				<td></td>
				<td>Program Study</td>
                <td>:</td>
				<td><?= $kelas['program_study'] ?></td>
            </tr>
			<tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
				<td><?= $siswa['tempat_lahir'] ?>, <?= $siswa['tgl_lahir'] ?></td>
				<td></td>
				<td>Paket Keahlian</td>
                <td>:</td>
				<td><?= $kelas['nama_pk'] ?></td>
            </tr>
			
        </table>
       <?php else: ?>
	    <table style="margin-left: 20px;margin-right:10px;font-size:11px"  width="100%">
            <tr>
                <td width="18%">Nama Siswa</td>
                <td width="1%">:</td>
				<td><?= $siswa['nama'] ?></td>
				<td width="7%"></td>
				<td width="18%">Tahun Pelajaran</td>
                <td width="1%">:</td>
				<td><?= $setting['tp'] ?></td>
            </tr>
            <tr>
                <td >NIS / NISN</td>
                <td>:</td>
				<td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?> </td>
				<td></td>
				 <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
				<td><?= $siswa['tempat_lahir'] ?>, <?= $siswa['tgl_lahir'] ?></td>
            </tr>
			
			
        </table>
	   
	   <?php endif; ?>
        <br>
 
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th width="5%" style="text-align:center" rowspan="2">NO</th>
                        <th style="text-align:center" rowspan="2" colspan="2">MATA DIKLAT</th>
                       <th style="text-align:center" colspan="6">PEROLEHAN NILAI</th>
					     <th style="text-align:center" colspan="2">UJIAN SEKOLAH</th>
						   <th style="text-align:center;width:5%" rowspan="2">UJIAN NASIONAL</th>
						   </tr>
						   <tr>
						    <th style="text-align:center;width:5%">I</th>
						   <th style="text-align:center;width:5%">II</th>
						    <th style="text-align:center;width:5%">III</th>
							 <th style="text-align:center;width:5%">IV</th>
							  <th style="text-align:center;width:5%">V</th>
							   <th style="text-align:center;width:5%">VI</th>
							    <th style="text-align:center;width:6%">Teori</th>
								 <th style="text-align:center;width:6%">Praktek</th>
							   
                    </tr>
                </thead>
                <tbody>
			    
                    
                        <?php
                        $q1 = mysqli_query($koneksi, "SELECT * FROM mapel_ijazah  group by kelompok order by kelompok");
                        $no = 0;
                        while ($kelompok = mysqli_fetch_array($q1)) {
							
                       if($kelompok['kelompok']=='A'){
						{$gra="A. Muatan Nasional";}
						}elseif($kelompok['kelompok']=='B'){
						{$gra="B. Muatan Kewilayahan";}
						}elseif($kelompok['kelompok']=='C1'){
						{$gra="C1. Dasar Bidang Keahlian";}
						}elseif($kelompok['kelompok']=='C2'){
						{$gra="C2. Dasar Program Keahlian";}
						}elseif($kelompok['kelompok']=='C3'){
						{$gra="C3. Kompetensi Keahlian";}
						
						}
						
						 if($kelompok['kelompok']=='A'){
						{$grade="A. Umum";}
						}elseif($kelompok['kelompok']=='B'){
						{$grade="B. Umum";}
						}elseif($kelompok['kelompok']=='C1'){
						{$grade="C. Peminatan";}
						
						}
					
						 if($kelompok['kelompok']=='A'){
						{$grades="A. Umum";}
						}elseif($kelompok['kelompok']=='B'){
						{$grades="B. Muatan Lokal";}
						}
						
                        ?>
                            <tr>
                                <td colspan="12">
								<?php if($setting['jenjang']=='SMK'){ ?>
								<b>  <?= $gra ?></b>
								<?php }elseif($setting['jenjang']=='SMA' OR $setting['jenjang']=='PAKET-C'){ ?>
								<b>  <?= $grade ?></b>
								<?php }else{ ?>
								<b>  <?= $grades ?></b>
								<?php } ?>
								</td>
                            </tr>
                           
							<?php
							 $query = mysqli_query($koneksi, "SELECT * FROM mapel_ijazah where kelompok='$kelompok[kelompok]' GROUP BY kode order by urut ");
							while ($mapel = mysqli_fetch_array($query)) {
								
							$smt1P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='1'"));
							$smt1K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='1'"));
							$smt2P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='2'"));
							$smt2K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='2'"));
							$smt3P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='3'"));
							$smt3K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='3'"));
							$smt4P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='4'"));
							$smt4K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='4'"));
							$smt5P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='5'"));
							$smt5K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='5'"));
							$smt6P = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI3' and semester='6'"));
							$smt6K = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and ki='KI4' and semester='6'"));
							$usP = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and jenis='PRAKTEK'"));
							$usT = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE nis='$siswa[nis]' and mapel='$mapel[kode]' and jenis='TEORI'"));
									
								
							 $no++;
		                  ?>
		                  <tr>
						  <td style="text-align:center;" rowspan="2"><?= $no ?></td>
						  <td  rowspan="2"> &nbsp;<?= $mapel['namamapel'] ?></td>
						 
						  
						  <td width="8%">&nbsp;Pengetahuan</td>
						  <td style="text-align:center;" >
						    <?php if($smt1P['nilai']<>0){ ?>
						  <?= number_format($smt1P['nilai'],2) ?> 
						  <?php } ?>
						  </td>
                           <td style="text-align:center;" >
						   <?php if($smt2P['nilai']<>0){ ?>
						   <?= number_format($smt2P['nilai'],2) ?> 
						   <?php } ?>
						   </td>
						   <td style="text-align:center;" >
						     <?php if($smt3P['nilai']<>0){ ?>
						   <?= number_format($smt3P['nilai'],2) ?> 
						   <?php } ?>
						   </td>
						   <td style="text-align:center;" >
						     <?php if($smt4P['nilai']<>0){ ?>
						   <?= number_format($smt4P['nilai'],2) ?> 
						   <?php } ?>
						   </td>
						   <td style="text-align:center;" >
						     <?php if($smt5P['nilai']<>0){ ?>
						   <?= number_format($smt5P['nilai'],2) ?>
						   <?php } ?>
						   </td>
						   <td style="text-align:center;" > 
						     <?php if($smt6P['nilai']<>0){ ?>
						   <?= number_format($smt6P['nilai'],2) ?>
						   <?php } ?>
						   </td>
						   <td rowspan="2" style="text-align:center;" > 
						   <?= number_format($usT['nilai'],2) ?>
						   </td>
						   <td rowspan="2" style="text-align:center;" >
						   <?= number_format($usP['nilai'],2) ?>
						   </td>
						   <td rowspan="2" style="text-align:center;" > </td>
						  </tr>
						  <tr>
						  <td>&nbsp;Keterampilan</td>
						  <td style="text-align:center;" > <?= number_format($smt1K['nilai'],2) ?></td>
						   <td style="text-align:center;" ><?= number_format($smt2K['nilai'],2) ?> </td>
						   <td style="text-align:center;" > <?= number_format($smt3K['nilai'],2) ?></td>
						   <td style="text-align:center;" ><?= number_format($smt4K['nilai'],2) ?> </td>
						   <td style="text-align:center;" ><?= number_format($smt5K['nilai'],2) ?> </td>
						   <td style="text-align:center;" ><?= number_format($smt6K['nilai'],2) ?> </td>
						    
				              </tr>
							  
						<?php }} ?>
                        
             
			    <?php $jm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(nilai) AS jumlah FROM nilai_skl  WHERE nis='$ids' AND semester BETWEEN 1 AND 6")); ?>
				 <?php $rt = mysqli_fetch_array(mysqli_query($koneksi, "SELECT AVG(nilai) AS rata FROM nilai_skl  WHERE nis='$ids' AND semester BETWEEN 1 AND 6")); ?>
				<?php $praktek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(nilai) AS jumlah FROM nilai_skl  WHERE nis='$ids' AND jenis='PRAKTEK'")); ?>
				<?php $teori = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(nilai) AS jumlah FROM nilai_skl  WHERE nis='$ids' AND jenis='TEORI'")); ?>
				<?php $praktekUS = mysqli_fetch_array(mysqli_query($koneksi, "SELECT AVG(nilai) AS rata FROM nilai_skl  WHERE nis='$ids' AND jenis='PRAKTEK'")); ?>
				<?php $teoriUS = mysqli_fetch_array(mysqli_query($koneksi, "SELECT AVG(nilai) AS rata FROM nilai_skl  WHERE nis='$ids' AND jenis='TEORI'")); ?>
							
					 							
                        <tr>
						<th style="text-align:center;" colspan="3" > Jumlah Nilai </th>
						  <th style="text-align:center;width:5%"><?= number_format($jm['jumlah'],2) ?></th>
						    <th style="text-align:center;width:5%"></th>
							  <th style="text-align:center;width:5%"></th>
							    <th style="text-align:center;width:5%"></th>
								  <th style="text-align:center;width:5%"></th>
								    <th style="text-align:center;width:5%"></th>
									 <th style="text-align:center;width:6%"><?= $teori['jumlah'] ?></th>
									  <th style="text-align:center;width:6%"><?= $praktek['jumlah'] ?></th>
									   <th style="text-align:center;"></th>
						      </tr>
                    <tr>
						<th style="text-align:center;" colspan="3" > Rata-rata Nilai </th>
						  <th style="text-align:center;width:5%"><?= number_format($rt['rata'],2) ?></th>
						    <th style="text-align:center;width:5%"></th>
							  <th style="text-align:center;width:5%"></th>
							    <th style="text-align:center;width:5%"></th>
								  <th style="text-align:center;width:5%"></th>
								    <th style="text-align:center;width:5%"></th>
									 <th style="text-align:center;width:6%"><?= number_format($teoriUS['rata'],2) ?></th>
									  <th style="text-align:center;width:6%"><?= number_format($praktekUS['rata'],2) ?></th>
									   <th style="text-align:center;"></th>
						     
							 </tr>
			   </tbody>
            </table>
           <br>
		   <?php if($setting['jenjang']=='SMK'): ?>
		  <center> <b>PRAKTEK KERJA LAPANGAN</b></center>
			  <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th width="5%" style="text-align:center">NO</th>
                        <th style="text-align:center">NAMA MITRA DU / DI</th>
                       <th style="text-align:center;width:20%">LOKASI </th>
					     <th style="text-align:center;width:10%">LAMA</th>
						   <th style="text-align:center;">KETERANGAN</th>
						   </tr>
						  
                </thead>
                <tbody>
				<?php
				$no=0;
				$queryQ = mysqli_query($koneksi, "SELECT * FROM pkl WHERE nis='$ids' ");
				while ($pkl = mysqli_fetch_array($queryQ)) {
					$no++;
					?>
					<tr>
					 <td style="text-align:center;width:5%" ><?= $no ?> </td>
						 <td style="text-align:center;" ><?= $pkl['mitra'] ?> </td>
						  <td style="text-align:center;" ><?= $pkl['lokasi'] ?> </td>
						   <td style="text-align:center;" ><?= $pkl['lama'] ?> </td>
						   <td style="text-align:center;" ><?= $pkl['ket'] ?> </td>
					</tr>
				<?php } ?>
				<tr>
					 <td style="text-align:center;width:5%" >2 </td>
						 <td style="text-align:center;" > </td>
						  <td style="text-align:center;" > </td>
						   <td style="text-align:center;" > </td>
						   <td style="text-align:center;" > </td>
					</tr>
			     </tbody>
            </table> 
			<?php endif; ?>
			 <br><br>
			 <table style="margin-left: 80px;" width="90%">
		<tr>
               <td style="text-align: center" width="33.3%"></td>
                 <td style="text-align: center" width="33.3%"></td>
                <td width="33.3%"><?= $setting['kecamatan'] ?>, <?= $skl['tgl_surat'] ?></td>
            </tr>
			</table>
			<table style="margin-left: 80px;" width="90%">
            <tr>
                <td></td>
			<td></td>
				 <td>
				<?php if($setting['jenis']=='REGULER'): ?>
                    <p>Kepala Sekolah,</p>
					<?php else: ?>
					<p>Kepala PKBM,</p>
					<?php endif; ?>
				 
				<br><br><br>
				 <b><u><?= $setting['kepsek'] ?></u> </b>
				 <p>NIP. <?= $setting['nip'] ?></p>
				 </td>
            </tr>
			
		<tr>
               <td width="33.3%"></td>
                 <td  width="33.3%"> </td>
                <td  width="33.3%"></td>
            </tr>
			</table>
    </div>
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
$dompdf->stream("Transkip_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>