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
	$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT kelas FROM siswa where kelas='$kelas'"));
	$jumnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_harian where kelas='$kelas' and mapel='$mapel' and guru='$guru' and semester='$setting[semester]' and katrol<>''"));
	$jml = $jumnil/$jsiswa;
	?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>PENILAIAN HARIAN</title>
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
  
		<center><h3>REKAPITULASI PENILAIAN HARIAN</h3></center>
		<br>
 
    <table width="100%">
	
            <tr>
			<td width="10%"></td>
                 <td width='100px'>Kelas</td>
                <td width='10px'>:</td>
                <td><?= $kelas ?></td>
				<td width="70%"></td>
				<td width='100px'>Semester</td>
                <td width='10px'>:</td>
                <td><?= $setting['semester'] ?></td>
            </tr>
			
                <tr>
				<td width="10%"></td>
                <td width='100px'>Mata Pelajaran</td>
                <td width='10px'>:</td>
                <td><?= $map['nama_mapel'] ?></td>
				<td ></td>
				 <td width='100px'>Tahun Pelajaran</td>
                <td width='10px'>:</td>
                <td><?= $setting['tp'] ?></td>
				</tr>
				
			
    </table>

     <br>
	 
		 <table class='it-grid' width='100%' style="font-size:14px;">        
              <tr>
                <th width="5%" height="40px">NO</th>
				<th  width="15%" style="text-align:center">N I S</th>
				<th width="35%" style="text-align:center">NAMA SISWA</th>
				<th  width="5%" style="text-align:center">JK</th>
				<?php for( $i=0; $i < $jml; $i++ ): ?>
				 <th  style="text-align:center">PH <?= ($i+1); ?></th>
				 <?php endfor; ?>
               
                 </tr>
				 <?php
				 $no=0;
                $query = mysqli_query($koneksi, "select id_siswa,nama,nis,jk from siswa WHERE kelas='$kelas'");				 
				while ($siswa = mysqli_fetch_array($query)) {					
               $no++;
                ?>					
					
					<tr>
					<td height="25px" style="text-align:center"><?= $no; ?></td>
					<td style="text-align:center"><?= $siswa['nis'] ?></td>
					
					<td><?= ucwords(strtolower($siswa['nama'])) ?></td>
					<td style="text-align:center"><?= $siswa['jk'] ?></td>
					 <?php
				
                $queryx = mysqli_query($koneksi, "select * from nilai_harian WHERE idsiswa='$siswa[id_siswa]' and kelas='$kelas' and guru='$guru' and mapel='$mapel' and katrol<>'' GROUP BY tanggal");				 
				while ($datax = mysqli_fetch_array($queryx)) {
				
                ?>	
					<td style="text-align:center"><?= $datax['katrol'] ?></td>
					<?php } ?>
					</tr>
				<?php } ?>
            </table>
			
    <br>
	<table width='100%'>
					<tr>
					<td width="5%"></td>
					<td width='50px'></td>
						<td>
						
				<br/>
					<br/>
							<br/>
							<br/>
							<br/>
							<br/>
								
						</td>
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
$dompdf->setPaper('A4', 'Potrait');
$dompdf->render();
$dompdf->stream("KATROL PH ". $kelas . ".pdf", array("Attachment" => false));
exit(0);
?>