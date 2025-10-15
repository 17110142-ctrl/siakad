<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
session_start();
$user = mysqli_fetch_array(mysqli_query($koneksi, "select * from users WHERE walas='$_GET[k]'"));

$kelas=$_GET['k'];
$kls = fetch ($koneksi,'kelas',['kelas'=>$kelas]);
$level = $kls['level'];
$pk = $kls['pk'];
$bl = date('m');
$bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

 <title>KI-3_<?= $kelas ?></title>
 <link rel="stylesheet" href="../../vendor/css/bootstrap.min.css">
</head>
<?php
    
	if($setting['semester']=='1'){
{$smt="(Satu)";}
}elseif($setting['semester']=='2'){
{$smt="(Dua)";}
}
  ?>
<body style="font-size: 12px;">
    
   
    <center>
        <h5>DAFTAR KUMPULAN NILAI</h5>
		<h5>PENGETAHUAN (KI-3)</h5>
    </center>
    <br>
    
    <div class="col-md-14">
	
        <table style="margin-left: 80px;margin-right:60px"  width="100%" >
            <tr>
                <td width="15%">Satuan Pendidikan</td>
                <td width="1%">:</td>
				<td width="40%"><?= $setting['sekolah'] ?></td>
				<td></td>
				<td width="17%">Kelas</td>
                <td width="1%">:</td>
				<td width="20%"><?= $kelas ?></td>
            </tr>
            <tr>
                <td >Alamat</td>
                <td>:</td>
				<td><?= $ms['alamat'] ?> Kec. <?= $setting['kecamatan'] ?></td>
				<td></td>
				<td>Semester</td>
                <td>:</td>
				<td> <?= $setting['semester'] ?> <?= $smt ?></td>
            </tr>
			<tr>
                <td>Wali Kelas</td>
                <td>:</td>
				<td><?= $user['nama'] ?></td>
				<td></td>
				<td>Tahun Pelajaran</td>
                <td>:</td>
				<td><?= $setting['tp'] ?></td>
            </tr>
			
        </table>
       
        <br>
							 <table style="margin-left: 10px;margin-right:10px"  width="100%" border='1'>
										<tr>
										<td width="3%" style="text-align: center">NO</td>                                               
										<td style="text-align: center" width="10%">NIS</td>
										<td style="text-align: center">NAMA LENGKAP</td>
										<?php																				
											 $queryx = mysqli_query($koneksi,"SELECT * FROM mapel_rapor a join mata_pelajaran b ON b.id=a.mapel where a.tingkat='$level' and a.pk='$pk' order by a.urut asc");  
											 while ($datax = mysqli_fetch_array($queryx)) :
											   ?>
										   <th style="text-align: center" width="6%"><?= $datax['kode'] ?></th>
                                           <?php endwhile; ?>										
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php											
											$no=0;										
											$query = mysqli_query($koneksi,"SELECT * FROM siswa where kelas='$kelas'");                                       
											while ($siswa = mysqli_fetch_array($query)) :									
											$no++;
											?>
                                            <tr>
											 <td style="text-align: center"><?= $no; ?></td>
											 <td style="text-align: center"><?= $siswa['nis'] ?></td>
											 <td>&nbsp;<?= ucwords(strtolower($siswa['nama'])) ?></td>						
											<?php																				
											$querys = mysqli_query($koneksi,"SELECT * FROM mapel_rapor WHERE tingkat='$siswa[level]' and pk='$siswa[jurusan]'");                                       
											while ($datas = mysqli_fetch_array($querys)){
											$nilai = mysqli_fetch_array(mysqli_query($koneksi,"SELECT nilai3,nilai4,nis,mapel,kelas,semester,tp FROM nilai_rapor WHERE nis='$siswa[nis]' AND mapel='$datas[mapel]' and semester='$semester' and tp='$tapel' GROUP BY mapel"));
											?>
											<td style="text-align: center"><?= number_format($nilai['nilai3']) ?></td>
											<?php } ?>
											   </tr>
											<?php endwhile; ?>
			
		</table>	
		<br/>
	<table border='0' style="margin-left: 80px;width:850">
					<tr>
					
						<td>
							Mengetahui, <br/>
							Kepala Sekolah <br/>
							<br/>
							<br/>
							<br/>
							
							<u><?= $setting['kepsek'] ?></u><br/>
							NIP. <?= $setting['nip'] ?>
						</td>
						<td width='400px'></td>
						<td>
							<?= $setting['kecamatan'] ?>, <?php echo date('d'); ?> <?= $bulane['ket'] ?> <?= date('Y') ?><br/>
							Wali Kelas <?= $kelas ?><br/>
							<br/>
							<br/>
							<br/>
							
							<u><?= $user['nama'] ?></u><br/>
							NIP. <?= $user['nip'] ?>
						</td>
					</tr>
				</table>
</body>

</html>
<?php

$html = ob_get_clean();
require_once '../../vendors/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Leger_KI-3.pdf", array("Attachment" => false));
exit(0);
?>