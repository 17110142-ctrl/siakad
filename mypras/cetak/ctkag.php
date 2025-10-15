<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
	$idj= dekripsi($_GET['j']);
	
	$bl= date('m');
	$jadwal = fetch ($koneksi, 'jadwal_mapel', ['id_jadwal' =>$idj]);
	$mapel= $jadwal['mapel'];
	$kelas= $jadwal['kelas'];
	$guru= $jadwal['guru'];
	$kuri= $jadwal['kuri'];
    $bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);
	$map = fetch ($koneksi, 'mata_pelajaran', ['id' =>$mapel]);
	$usr = fetch ($koneksi, 'users', ['id_user' =>$guru]);
	?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>AGENDA GURU</title>
<link rel='stylesheet' href='../../vendor/css/cetak.min.css'>

</head>
<style>
@page { margin: 80px; }
body { margin: 20px; }
</style>
<body style="font-size: 14px;">	


<div style='background:#fff; width:97%; margin:0 auto; height:90%;'>
            <table width='100%'>
                <tr>
                    <td width='100'><img src='../../images/<?= $setting['logo'] ?>' width='70'></td>
                    <td style="text-align:center">
                        <strong class='f12'>
                          <?= strtoupper($setting['header']) ?><br>
                     <?= strtoupper($setting['sekolah']) ?><br>
					 <small>Alamat :  <?= $setting['alamat'] ?> Kec. <?= $setting['kecamatan'] ?> Kab.  <?= $setting['kabupaten'] ?> Email :  <?= $setting['email'] ?></small>
                        </strong>
                    </td>
                    
                </tr>
            </table>
			 <hr style="margin:1px">
		 <hr style="margin:2px">
   <br>
		
		<center><h4>AGENDA GURU BULAN <?= strtoupper($bulane['ket']) ?> <?= date('Y') ?><br><?= strtoupper($map['nama_mapel']) ?></h4></center>
		<br>
 
    <table width="100%">
	
            <tr>
			<td width="10%"></td>
                 <td width='100px'>Kelas</td>
                <td width='10px'>:</td>
                <td><?= $kelas ?></td>
				<td width="70%"></td>
				<td width='100px'>Bulan</td>
                <td width='10px'>:</td>
                <td><?= $bulane['ket'] ?> <?= date('Y') ?></td>
            </tr>
			
                <tr>
				<td width="10%"></td>
                <td width='100px'>Semester</td>
                <td width='10px'>:</td>
                <td><?= $setting['semester'] ?></td>
				<td ></td>
				 <td width='100px'>Tahun Pelajaran</td>
                <td width='10px'>:</td>
                <td><?= $setting['tp'] ?></td>
				</tr>
				
			
    </table>

     <br>
	 <?php if($kuri=='1'):?>
		 <table class='it-grid' width='100%' style="font-size:12px;">      
              <tr>
                <th width="3%" height="40px">NO</th>
                <th width="8%" style="text-align:center">TANGGAL</th>			
				<th style="text-align:center">MATERI</th>
				<th width="5%" style="text-align:center">KD</th>
				<th style="text-align:center">INDIKATOR</th>
               <th width="10%" style="text-align:center">PENCAPAIAN</th>
                <th width="10%" style="text-align:center">KEHADIRAN</th>
                 </tr>
				 <?php
				 $no=0;
                $query = mysqli_query($koneksi, "select * from agenda WHERE kelas='$kelas' and mapel='$mapel' and guru='$guru' ORDER BY id ASC");				 			 
				while ($data = mysqli_fetch_array($query)) {
				$hari = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
					if($data['hadir']<50){
						$capai ="Tidak Tercapai";
					}else{
						$capai ="Tercapai";
					}	
               $no++;
                ?>					
					
					<tr>
					<td><?= $no; ?></td>
					<td style="text-align:center"><?= $hari['hari'] ?><br><?= date('d-m-Y',strtotime($data['tanggal'])); ?></td>
					<td><?= $data['materi'] ?></td>
					<td style="text-align:center"><?= $data['kd'] ?></td>
					<td><?= $data['tujuan'] ?></td>
					<td style="text-align:center"><?= $capai ?></td>
					<td style="text-align:center"><?= $data['hadir'] ?>%</td>
					</tr>
				<?php } ?>
            </table>
			<?php else : ?>
			<table class='it-grid' width='100%' style="font-size:12px;">       
              <tr>
                <th width="3%" height="40px">NO</th>
                <th width="8%" style="text-align:center">TANGGAL</th>
				<th style="text-align:center">MATERI</th>
				<th style="text-align:center">TUJUAN PEMBELAJARAN</th>
               <th width="10%" style="text-align:center">PENCAPAIAN</th>
                <th width="10%" style="text-align:center">KEHADIRAN</th>
                 </tr>
				 <?php
				 $no=0;
                $query = mysqli_query($koneksi, "select * from agenda WHERE kelas='$kelas' and mapel='$mapel' and guru='$guru' ORDER BY id ASC");				 
				while ($data = mysqli_fetch_array($query)) {
					$hari = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
					if($data['hadir']<50){
						$capai ="Tidak Tercapai";
					}else{
						$capai ="Tercapai";
					}
               $no++;
                ?>					
					
					<tr>
					<td><?= $no; ?></td>
					<td style="text-align:center"><?= $hari['hari'] ?><br><?= date('d-m-Y',strtotime($data['tanggal'])); ?></td>
					
					<td><?= $data['materi'] ?></td>
					<td><?= $data['tujuan'] ?></td>
					<td style="text-align:center"><?= $capai ?></td>
					<td style="text-align:center"><?= $data['hadir'] ?>%</td>
					</tr>
				<?php } ?>
            </table>
			
			<?php endif; ?>
    <br>
	<table width='100%'>
					<tr>
					<td width="5%"></td>
					<td width='50px'></td>
						<td>
							Mengetahui,<br>Kepala Sekolah
				
					<br/>
							<br/>
							<br/>
							<br/>
							
								<u><?= $setting['kepsek'] ?></u><br/>
							NIP. <?= $setting['nip'] ?>
							<img style="z-index: 920;position:relative;margin-top:-30px;margin-left:-70px;opacity:0.7" class="img" src="../../images/<?= $setting['ttd'] ?>" width="130px">
							
						</td>
						 <img style="z-index: 920;position:relative;margin-top:-130px;margin-left:10px;opacity:0.7" class="img" src="../../images/<?= $setting['stempel'] ?>" width="140px">
							
						<td width='40%'></td>
						<td width="5%"></td>
						<td>
						<?= ucwords(strtolower($setting['kecamatan'])); ?>, <?php echo  date("t",time()); ?> <?= $bulane['ket'] ?> <?= date('Y') ?><br/>
							Guru Pengampu
					<br/>
							<br/>
							<br/>
							<br/>
							
								<u><?= $usr['nama'] ?></u><br/>
							NIP. <?= $usr['nip'] ?>
							
						</td>
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
$dompdf->setPaper('A4', 'Landscape');
$dompdf->render();
$dompdf->stream("Agenda Guru Bulan ". $bl . ".pdf", array("Attachment" => false));
exit(0);
?>