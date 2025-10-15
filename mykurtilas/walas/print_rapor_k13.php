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
$nis=$_GET['ids'];

$siswa = fetch($koneksi, 'siswa', ['nis' => $nis]);
$klas = $siswa['kelas'];
$level = $siswa['level'];

$walas = fetch($koneksi, 'users', ['walas' => $klas]);

	if($setting['semester']=='1'){
{$smt="(Satu)";}
}elseif($setting['semester']=='2'){
{$smt="(Dua)";}
}
$ma = fetch($koneksi, 'spiritual', ['nis' => $nis]);	
$ceklis=$ma['pred'];
	if($ceklis=='A'){
{$grades="Sangat Baik";}
}elseif($ceklis=='B'){
{$grades="Baik";}
}elseif($ceklis=='C'){
{$grades="Cukup";}
}elseif($ceklis=='D'){
{$grades="Kurang";}
}
$mas = fetch($koneksi, 'sosial', ['nis' => $nis]);
$cek=$mas['pred'];
if($cek=='A'){
{$gra="Sangat Baik";}
}elseif($cek=='B'){
{$gra="Baik";}
}elseif($cek=='C'){
{$gra="Cukup";}
}elseif($cek=='D'){
{$gra="Kurang";}
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>Raport_<?= $siswa['nama'] ?></title>
<link rel="stylesheet" href="../../vendor/css/bootstrap.min.css">
 
 <style>
    @page { margin: 15px 30px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -20px; right: 0px; height: 30px; background-color: white; }
    #footer .page:after { content: counter(page, upper-roman); }
	.right{
    float: right;
    display: block;
	margin-right:10px;
	}
  </style>
   
</head>
 
<body style="font-size: 11px;">
<center><h4>PENCAPAIAN KOMPETENSI PESERTA DIDIK</h4> </center>  
    <br>
  <div id="footer">
    <p class="page right"> &nbsp;&nbsp;&nbsp;&nbsp;<small><?= strtoupper($siswa['nama']) ?> - <?= strtoupper($siswa['kelas']) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=  strtoupper($setting['sekolah']) ?> - <?= date('Y') ?></small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE </p>
  </div>
  
    <div class="col-md-14">
	
        <table style="margin-left: 10px;margin-right:10px"  width="100%">
            <tr>
                <td width="15%">Nama Sekolah</td>
                <td width="1%">:</td>
				<td width="40%"><?= $setting['sekolah'] ?></td>
				<td></td>
				<td width="17%">Kelas</td>
                <td width="1%">:</td>
				<td width="20%"><?= $siswa['kelas'] ?></td>
            </tr>
            <tr>
                <td >Alamat</td>
                <td>:</td>
				<td><?= $setting['alamat'] ?> </td>
				<td></td>
				<td>Semester</td>
                <td>:</td>
				<td><?= $setting['semester'] ?> <?= $smt ?></td>
            </tr>
			<tr>
                <td>Nama</td>
                <td>:</td>
				<td><?= $siswa['nama'] ?></td>
				<td></td>
				<td>Tahun Pelajaran</td>
                <td>:</td>
				<td><?= $setting['tp'] ?></td>
            </tr>
			<tr>
                <td>N I S</td>
                <td>:</td>
				<td><?= $siswa['nis'] ?></td>
				<td></td>
				<td>N I S N</td>
                <td>:</td>
				<td><?= $siswa['nisn'] ?></td>
            </tr>
        </table>
       
        <br>
 <b>A. SIKAP</b><p></p>
    <b>1. Sikap Spiritual</b>
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th width="20%"><center>Predikat</center></th>
                        <th><center>Deskripsi</center></th>
                       
                    </tr>
                </thead>
                <tbody>
				 <?php if ($ma['nis'] <> '') { ?>
                    <tr>
                        <td height="30px"><center> <?= $ma['pred'] ?> ( <?= $grades ?> )</center></td>
                  <td style="text-align:justify;">Selalu <?= $ma['ket1'] ?> dan sikap <?= $ma['ket2'] ?> mulai berkembang</td>
				  </tr>
				   <?php } ?>
                </tbody>
            </table>
            <b>2. Sikap Sosial</b>
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th width="20%"><center>Predikat</center></th>
                        <th><center>Deskripsi</center></th>
                       
                    </tr>
                </thead>
                <tbody>
				 <?php if ($mas['nis'] <> '') { ?>
                    <tr>
                     <td height="30px"><center> <?= $mas['pred'] ?> ( <?= $gra ?> )</center></td>
                  <td style="text-align:justify;">Selalu menunjukan <?= $mas['ket1'] ?> sedangkan sikap <?= $mas['ket2'] ?> mengalami peningkatan</td>
				  </tr>
				    <?php } ?>
                </tbody>
            </table>
            <br>
			
			 <b>B. PENGETAHUAN</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="2%"><center>No</center></th>
                        <th width="32%"><center>Mata Pelajaran</center></th>
                       <th width="2%"><center>KKM</center></th>
					   <th width="2%"><center>Nilai</center></th>
					   <th width="2%"><center>Pred</center></th>
					   <th width="60%"><center>Deskripsi</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q1 = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$level' AND pk='$siswa[jurusan]' group by kelompok order by kelompok");
                        $no = 0;
                        while ($kelompok = mysqli_fetch_array($q1)) {
                      $query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$level' AND pk='$siswa[jurusan]' AND kelompok='$kelompok[kelompok]' ORDER BY urut ASC");
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
                                <td colspan="6" style="background-color:#DCDCDC">
								<?php if($setting['jenjang']=='SMK'){ ?>
								<b>  <?= $gra ?></b>
								<?php }elseif($setting['jenjang']=='SMA'){ ?>
								<b>  <?= $grade ?></b>
								<?php }else{ ?>
								<b>  <?= $grades ?></b>
								<?php } ?>
								</td>
                            </tr>
                           
							<?php
							while ($mapel = mysqli_fetch_array($query)) {
						  $pelajaran = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran  WHERE id='$mapel[mapel]'"));
			              $nilai3 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_rapor WHERE mapel='$mapel[mapel]' AND nis='$nis'"));
			              $mapelkkm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE mapel='$mapel[mapel]' AND tingkat='$level'"));
						 $rentang=round(100-$mapelkkm['kkm'])/3;		
							$predD=round($mapelkkm['kkm']-1);
						   $nilC1=round($mapelkkm['kkm']);
							$nilC2=round($rentang)+($mapelkkm['kkm']);				 
							$nilB1=round($nilC2+1);
							$nilB2=round($nilC2)+round($rentang);
							$nilA1=round($nilB2+1);
							$nilA2=round($nilB2)+round($rentang);	
		                    $rerata=$nilai3['nilai3'];
							if($rerata<=$predD){
							{$predikat="D";}
							}elseif($rerata>=$nilC1 && $rerata<=$nilC2){
							{$predikat="C";}
							}elseif($rerata>=$nilB1 && $rerata<=$nilB2){
							{$predikat="B";}
							}elseif($rerata>=$nilA1 && $rerata<=$nilA2){
							{$predikat="A";}
							}	
						    if($predikat=='A'){
							{$edis="sangat Baik";}
							}elseif($predikat=='B'){
							{$edis="baik";}
							}elseif($predikat=='C'){
							{$edis="cukup";}
							}elseif($predikat=='D'){
							{$edis="kurang";}
							}		
			
							 $no++;
							 
			  ?>
                                <tr>
                   <td style="text-align:center;"> <?= $no ?></td>
                  <td ><?= $pelajaran['nama_mapel'] ?> </td>
				  <td style="text-align:center;"><?= $mapel['kkm'] ?></td>
				  <td style="text-align:center;">
				  <?php if($nilai3['nilai3']!=0): ?>
				  <?= $nilai3['nilai3'] ?> 
				  <?php endif; ?>
				  </td>
				  <td style="text-align:center;">
				  <?php if($nilai3['nilai3']!=0): ?>
				  <?= $predikat ?>
				   <?php endif; ?>
				  </td>
				  <td height="45px" style="text-align:justify;font-size: 10px;">
				   <?php if($nilai3['desmin3']!=''): ?>
				  <b><i><?= $siswa['nama'] ?> memiliki kemampuan <?= $edis ?> dalam</i></b> <?= $nilai3['desmax3'] ?>, <b><i>perlu dimaksimalkan dalam</i></b> <?= $nilai3['desmin3'] ?>
				  <?php endif; ?>
				  </td>
				  </tr>
				
						<?php } } ?>	
                        
                   
                </tbody>
            </table>
			
        <br>
      
      <b>C. KETERAMPILAN</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="2%"><center>No</center></th>
                        <th width="32%"><center>Mata Pelajaran</center></th>
                       <th width="2%"><center>KKM</center></th>
					   <th width="2%"><center>Nilai</center></th>
					   <th width="2%"><center>Pred</center></th>
					   <th width="60%"><center>Deskripsi</center></th>
                    </tr>
                </thead>
                <tbody>
                     <?php
                        $q1 = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$level' AND pk='$siswa[jurusan]' group by kelompok order by kelompok");
                        $no = 0;
                        while ($kelompok = mysqli_fetch_array($q1)) {
                      $query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$level' AND pk='$siswa[jurusan]' AND kelompok='$kelompok[kelompok]' ORDER BY urut ASC");
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
                                <td colspan="6" style="background-color:#DCDCDC">
								<?php if($setting['jenjang']=='SMK'){ ?>
								<b>  <?= $gra ?></b>
								<?php }elseif($setting['jenjang']=='SMA'){ ?>
								<b>  <?= $grade ?></b>
								<?php }else{ ?>
								<b>  <?= $grades ?></b>
								<?php } ?>
								</td>
                            </tr>
                           
							<?php
							while ($mapel = mysqli_fetch_array($query)) {
						  $pelajaran = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran  WHERE id='$mapel[mapel]'"));
			               $nilai4 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_rapor WHERE mapel='$mapel[mapel]' AND nis='$nis'"));
			               $mapelkkm = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE mapel='$mapel[mapel]' AND tingkat='$level'"));
						 $rentang=round(100-$mapelkkm['kkm'])/3;		
							$predD=round($mapelkkm['kkm']-1);
						   $nilC1=round($mapelkkm['kkm']);
							$nilC2=round($rentang)+($mapelkkm['kkm']);				 
							$nilB1=round($nilC2+1);
							$nilB2=round($nilC2)+round($rentang);
							$nilA1=round($nilB2+1);
							$nilA2=round($nilB2)+round($rentang);	
		                    $rerata=$nilai4['nilai4'];
							if($rerata<=$predD){
							{$predikat="D";}
							}elseif($rerata>=$nilC1 && $rerata<=$nilC2){
							{$predikat="C";}
							}elseif($rerata>=$nilB1 && $rerata<=$nilB2){
							{$predikat="B";}
							}elseif($rerata>=$nilA1 && $rerata<=$nilA2){
							{$predikat="A";}
							}	
						    if($predikat=='A'){
							{$edis="sangat Baik";}
							}elseif($predikat=='B'){
							{$edis="baik";}
							}elseif($predikat=='C'){
							{$edis="cukup";}
							}elseif($predikat=='D'){
							{$edis="kurang";}
							}		
			
			
							 $no++;
							 
			  ?>
                                <tr>
                   <td style="text-align:center;"> <?= $no ?> </td>
                   <td ><?= $pelajaran['nama_mapel'] ?> </td>
				  <td style="text-align:center;"><?= $mapel['kkm'] ?> </td>
				  <td style="text-align:center;">
				  <?php if($nilai4['nilai4']!=0): ?>
				  <?= $nilai4['nilai4'] ?> 
				  <?php endif; ?>
				  </td>
				  <td style="text-align:center;">
				   <?php if($nilai4['nilai4']!=0): ?>
				  <?= $predikat ?>
				  <?php endif; ?>
				  </td>
				  <td height="45px" style="text-align:justify;font-size: 9px;">
				  <?php if($nilai4['desmin4']!=''): ?>
				  <b><i><?= $siswa['nama'] ?> memiliki keterampilan <?= $edis ?> dalam </i></b><?= $nilai4['desmax4'] ?>, perlu dimaksimalkan keterampilan dalam <?= $nilai4['desmin4'] ?>
				  <?php endif; ?>
				  </td>
				  </tr>
				
					<?php } }  ?>	
                </tbody>
            </table>
              <p style="page-break-before: always;"></p>
			<b>D. EKSTRAKURIKULER</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="2%"><center>No</center></th>
                        <th width="36%"><center>Kegiatan Ekstrakurikuler</center></th>
                       <th width="4%"><center>Nilai</center></th>
					   <th width="55%"><center>Keterangan</center></th>
					
                    </tr>
                </thead>
                <tbody>
				<?php
							$no=0;
							 $queryx = mysqli_query($koneksi, "select * from m_eskul ");
                           while ($esk = mysqli_fetch_array($queryx)) {
					$eskuler = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM peskul  WHERE nis='$nis' AND eskul='$esk[eskul]'"));
									 $no++;
									 ?>
				<tr>
				<td style="text-align:center;"><?= $no ?> </td>
				<td>&nbsp;<?= $esk['eskul'] ?> </td>
				<td style="text-align:center;"><?= $eskuler['nilai'] ?></td>
				<td>&nbsp;<?= $eskuler['ket'] ?>  </td>
				</tr>
				<?php } ?>
				</tr>
				
				
	</tbody>
            </table>
         
			<b>E. PRESTASI</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="2%"><center>No</center></th>
                        <th width="42%"><center>Jenis Prestasi</center></th>
                       <th width="56%"><center>Keterangan</center></th>
					  
                    </tr>
                </thead>
                <tbody>
				<?php
							$no=0;
							 $queryx = mysqli_query($koneksi, "select * from siswa WHERE nis='$nis'");
                                 while ($pres = mysqli_fetch_array($queryx)) {
									 $no++;
									 ?>
				<tr>
				<td style="text-align:center;"><?= $no ?> </td>
				<td>&nbsp;<?= $pres['prestasi'] ?> </td>
				<td>
				<?php if($pres['prestasi'] !=''): ?>
				&nbsp;Juara <?= $pres['juara'] ?> Tingkat <?= $pres['tingkat'] ?>
				<?php endif; ?>
				</td>
				
				</tr>
				<?php } ?>
				
	</tbody>
            </table>
            
			<br>
<b>F. KETIDAKHADIRAN</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="70%">
                
				<tr>
				<td width="50%">Sakit </td>
				<td> &nbsp;<?= $siswa['sakit'] ?> hari</td>
				</tr>
				<tr>
				<td>Izin </td>
				<td> &nbsp;<?= $siswa['izin'] ?> hari</td>
				</tr>
				<tr>
				<td>Tanpa Keterangan </td>
				<td> &nbsp;<?= $siswa['alpha'] ?> hari</td>
				</tr>
	
            </table>
            <br>
			 
			<b>G. CATATAN WALI KELAS</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                
				<tr>
				<td height="40">&nbsp;<?= $siswa['catatan'] ?></td>
				
				</tr>
				
            </table>
            <br>
			
			<b>H. TANGGAPAN ORANG TUA / WALI</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                
				<tr>
				<td height="40"></td>
				
				</tr>
				
            </table>
            <br>
			
			<?php if($setting['semester']==2){ ?>
       <table style="margin-left: 10px;margin-right:10px;" border ="1" width="50%">
                
				<tr>
				<td height="30">Berdasarkan pencapaian kompetensi pada semester ke-1<br>
				         dan ke-2, peserta didik ditetapkan *)<br>
						 <?php if($siswa['level']==6 OR $siswa['level']==9 OR $siswa['level']==12): ?>
						&nbsp;<b>LULUS</b>
						<?php else: ?>
						
						 naik ke kelas <b><?= $siswa['level'] + 1 ?></b>
						 <?php endif; ?>
						 <br><s><b>tinggal di kelas  <?= $siswa['level'] ?></b></s><br>*)Coret yang tidak perlu.					
                     </td>
				</tr>
            </table>
			
		
		<?php } ?>
        <table style="margin-left: 50px;" width="100%">
		    <tr>
              <td></td>
			   <td></td>
			   <td>
			  <?= ucwords(strtolower($setting['kecamatan'])) ?>, <?= $setting['tanggal_rapor'] ?>
			  </td>
            </tr>
			<tr>
              <td style="width:35%">Orang Tua/Wali</td>
			   <td style="width:30%">Mengetahui :</td>
			   <td>
			  Wali Kelas <?= $siswa['kelas'] ?>
			  </td>
            </tr>
			<tr>
              <td style="width:35%"></td>
			   <td style="width:30%">
			  
					Kepala Sekolah
				
			   </td>
			   <td></td>
            </tr>
			<tr>
              <td style="width:35%;height:50px"></td>
			   <td></td>
			   <td></td>
            </tr>
			<tr>
              <td style="width:35%">( ______________ )</td>
			   <td><b><?= $setting['kepsek'] ?></b></td>
			   <td><b><?= $walas['nama'] ?></b></td>
            </tr>
			<tr>
              <td style="width:35%"></td>
			   <td><b>NUPTK. <?= $setting['nip'] ?></b></td>
			   <td><b>NIP. <?= $walas['nip'] ?></b></td>
            </tr>
			</table>
			
			
			
    </div>
	

</body>

</html>
<?php

$html = ob_get_clean();
require_once '../../vendor/vendors/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Raport_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>