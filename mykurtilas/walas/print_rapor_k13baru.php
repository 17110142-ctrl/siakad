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
$klas=$siswa['kelas'];
$level=$siswa['level'];
$walas = fetch($koneksi, 'users', ['walas' => $klas]);

	if($setting['semester']=='1'){
{$smt="(Satu)";}
}elseif($setting['semester']=='2'){
{$smt="(Dua)";}
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>Raport_<?= $siswa['nama'] ?></title>
<link rel="stylesheet" href="../../vendor/css/bootstrap.min.css">
<style>
    @page { margin: 50px 40px; }
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
    
   
    <center>
        <h5>PENCAPAIAN KOMPETENSI PESERTA DIDIK</h5>
    </center>
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
 &nbsp;&nbsp;&nbsp;<b>A. SIKAP</b><p></p>
   
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                        <th width="30%"><center>Dimensi</center></th>
                        <th><center>Deskripsi</center></th>
                       
                    </tr>
                </thead>
                <tbody>
			              <?php							
                            $query = mysqli_query($koneksi, "select * from barusikap WHERE nis='$nis' ORDER BY p_dimensi ASC");
                            $no = 0;
                            while ($sikap = mysqli_fetch_array($query)) {
							$dm=fetch($koneksi,'m_dimensi',['id_dimensi'=>$sikap['p_dimensi']]);
							$des=fetch($koneksi,'m_sub_elemen',['id_sub'=>$sikap['p_sub']]);
							$fase=$siswa['fase'];							
                                $no++;
                            ?>
							 <tr>
                                   
                                    <td><?= $dm['dimensi'] ?></td>
									<td style="text-align:justify;font-size:10px">
									<?php if($fase=='A'){ ?>
									<?= $siswa['nama']?> <?= strtolower($des['A']) ?>
									<?php }elseif($fase=='B'){ ?>
									<?= $siswa['nama']?> <?= strtolower($des['B']) ?>
									<?php }elseif($fase=='C'){ ?>
									<?= $siswa['nama']?> <?= strtolower($des['C']) ?>
									<?php }elseif($fase=='D'){ ?>
									<?= $siswa['nama']?> <?= strtolower($des['D']) ?>
									<?php }elseif($fase=='E'){ ?>
									<?= $siswa['nama']?> <?= strtolower($des['E']) ?>
									<?php } ?>
								     </td>
								  </tr>
								   <?php } ?>
								</tbody>
							</table>
            
            <br>
			
			 &nbsp;&nbsp;&nbsp;<b>B. PENGETAHUAN DAN KETERAMPILAN</b>
			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="3%"><center>No</center></th>
                        <th width="32%"><center>Mata Pelajaran</center></th>
                     
					   <th width="5%"><center>Nilai Akhir</center></th>
					  
					   <th width="60%"><center>Capaian Kompetensi</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $q1 = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$level' AND pk='$siswa[jurusan]' group by kelompok order by kelompok");
                        $no = 0;
                        while ($kelompok = mysqli_fetch_array($q1)) {
                      $query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$siswa[level]' AND pk='$siswa[jurusan]' AND kelompok='$kelompok[kelompok]' ORDER BY urut ASC");
                           
                          
                        ?>
                            <tr>
                                <td colspan="4" style="background-color:#DCDCDC"> <b> Kelompok <?= $kelompok['kelompok'] ?></b></td>
                            </tr>
                           
							<?php
							while ($mapel = mysqli_fetch_array($query)) {
						 $pelajaran = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran  WHERE id='$mapel[mapel]'"));
			              $nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_rapor WHERE mapel='$mapel[mapel]' AND nis='$nis'"));
						
							 $no++;
		?>
		
						
                                <tr>
                   <td style="text-align:center;" rowspan="2"> <?= $no ?></td>
                  <td  rowspan="2"><?= $pelajaran['nama_mapel'] ?> </td>
				  
				  <td style="text-align:center;" rowspan="2">
				 <?php if($nilai['nilai3']!=0): ?>
				 <?= $nilai['nilai3'] ?> 
				  <?php endif; ?>
				  </td>
				  <td height="auto" style="text-align:justify;font-size:10px;">
				 <?php if($nilai['desmin3']!=''): ?>
				  Menunjukan kemampuan <?= $nilai['desmax3'] ?> dan <?= $nilai['desmax4'] ?></td>
				    <?php endif; ?>
				  </tr>
				  <tr>
				  <td height="auto" style="text-align:justify;font-size:10px;">
				  <?php if($nilai['desmin3']!=''): ?>
				 Perlu bimbingan <?= $nilai['desmin3'] ?> dan <?= $nilai['desmin4'] ?></td>
				 <?php endif; ?>
				  </tr>
				
						<?php } } ?>	
                        
                   
                </tbody>
            </table>
             <br>
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="100%">
                <thead>
                    <tr>
                       <th width="3%"><center>No</center></th>
                        <th width="37%"><center>Ekstrakurikuler</center></th>
					   <th width="60%"><center>Keterangan</center></th>
					
                    </tr>
                </thead>
                <tbody>
				<?php
							$no=0;
							 $queryx = mysqli_query($koneksi, "select * from m_eskul ");
                                 while ($esk = mysqli_fetch_array($queryx)) {
									 $eskuler=fetch($koneksi,'siswa_eskul',['eskul'=>$esk['ekstra']]);
									 $no++;
									 ?>
				<tr>
				<td style="text-align:center;"><?= $no ?> </td>
				<td><?= $esk['ekstra'] ?> </td>
				<td><?= $eskuler['ket'] ?>  </td>
				</tr>
				<?php } ?>
				</tr>
				
				
	       </tbody>
            </table>
            <br>

			
            <table style="margin-left: 10px;margin-right:10px;" border ="1" width="50%">
                <tr>
				<td class="text-center" colspan="2"><b>Ketidakhadian</b></td>
				</tr>
				<tr>
				<td width="70%">Sakit </td>
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
			 
		
            <br>
			<?php if($setting['semester']==2){ ?>
       <table style="margin-left: 10px;margin-right:10px;" border ="1" width="50%">
                
				<tr>
				<td height="30" style="text-align:justify">Berdasarkan pencapaian kompetensi pada semester ke-1<br>
				         dan ke-2, peserta didik ditetapkan *)
						 <?php if($siswa['level']==6 OR $siswa['level']==9 OR $siswa['level']==12): ?>
						&nbsp;<b>Lulus</b>
						<?php else: ?>
						 <br>
						 naik ke kelas <b><?= $siswa['level'] + 1 ?></b>
						 <?php endif; ?>
						 <br><s><b>tinggal di kelas  <?= $siswa['level'] ?></b></s><br>*)Coret yang tidak perlu.					
                     </td>
				</tr>
            </table>
			
		<br>
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
			    <?php if($setting['jenjang'] !='SEMUA'){ ?>
					Kepala Sekolah
					<?php }else{ ?>
					Kepala PKBM
					<?php } ?>
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
			   <td><b>NIP. <?= $setting['nip'] ?></b></td>
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