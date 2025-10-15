<?php ob_start();

require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}

$nis = $_GET['nis'];
$siswa=fetch($koneksi,'siswa',['nis' => $nis]);

$bl = date('m');
$bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);
$skb=fetch($koneksi,'skkb',['id' =>1]);
$skl=fetch($koneksi,'skl',['id_skl' =>1]);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>SKKB_<?= $siswa['nama'] ?></title>
<link rel="stylesheet" href="../vendor/css/bootstrap.min.css">
</head>
<style>
@page { margin: 40px; }
body { margin: 40px; }
</style>
<body style="font-size: 13px;">
   <?php if ($skl['header'] == '') { ?>
        <h3><?= $setting['nama_sekolah'] ?></h3>
        <p><small> <?= $setting['alamat'] ?></small></p>
    <?php } else { ?>
        <img src="../<?= $skl['header'] ?>" width="100%">
    <?php } ?>
   <br>
  <center>
        <h4><u>SURAT KETERANGAN KELAKUAN BAIK</u></h4>
        No. Surat : <?= $skb['nosurat'] ?>
    </center>
   <br><br>
   <div style="padding-left:10px;margin-right:0px ;">
   <p>Yang bertanda tangan dibawah ini :</p>
   </div>
   <table style="margin-left:50px;margin-right:10px;"  width="100%">
	
			 <tr>
                <td width="130px">Nama</td>
				<td width="10px">:</td>
				<td><?= $setting['kepsek'] ?></td>
            </tr>
			
                <tr>
                <td>NIP</td>
				<td>:</td>
				<td><?= $setting['nip'] ?></td>
            </tr>
			
                <tr>
                <td>Jabatan</td>
				<td>:</td>
				<td>Kepala <?= $setting['sekolah'] ?></td>
            </tr>
			</tbody>
    </table>
	<br/>
	 <div style="padding-left:10px;margin-right:0px ;">
   <p>Menerangkan bahwa :</p>
   </div>
   <table style="margin-left:50px;margin-right:80px"  width="100%">
			 <tr>
                <td width="130px">Nama</td>
				<td width="10px">:</td>
				<td><?= $siswa['nama'] ?></td>
            </tr>
                <tr>
                <td>NIS / NISN</td>
				<td>:</td>
				<td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?></td>
            </tr>
			
                <tr>
                <td>Tempat, Tgl Lahir</td>
				<td>:</td>
				<td><?= $siswa['tempat_lahir'] ?>, <?= $siswa['tgl_lahir'] ?></td>
            </tr>
			
                <tr>
                <td>Jenis Kelamin</td>
				<td>:</td>
				<td><?= $siswa['jenis_kelamin'] ?></td>
            </tr>
			
                <tr>
                <td>Agama</td>
				<td>:</td>
				<td><?= $siswa['agama'] ?></td>
            </tr>
			
                <tr>
                <td>Alamat</td>
				<td>:</td>
				<td><?= $siswa['alamat'] ?></td>
            </tr>
			
                <tr>
                <td>Desa/Kelurahan</td>
				<td>:</td>
				<td><?= $siswa['desa'] ?></td>
            </tr>
			
                <tr>
                <td>Kecamatan</td>
				<td>:</td>
				<td><?= $siswa['kecamatan'] ?></td>
            </tr>
			
                <tr>
                <td>Kabupaten</td>
				<td>:</td>
				<td><?= $siswa['kabupaten'] ?></td>
            </tr>
			</tbody>
    </table>
	<br>
	 <table style="margin-left: 10px;margin-right:0px"  width="100%">
			 <tr>
	<td style="text-align:justify" width="100%"><?= $skb['isi'] ?> </td></tr>
	<tr><td style="text-align:justify" width="100%"><?= $skb['foter'] ?> </td>
	</tr>
	</table>
    <br>
	<table border='0' style="margin-left: 100px;width:850">
					<tr>
					
						<td width='150px'>
							<br/>
							 <br/>
							<br/>
							<br/>
							<br/>
							
							<br/>
							
						</td>
						<td width='200px'></td>
						<td>
							<?= $setting['kecamatan'] ?>, <?= $skl['tgl_surat'] ?><br/>
							Yang Membuat Pernyataan<br/>
							<br/>
							<br/>
							<br/>
							<br/>
							<u><?= $setting['kepsek'] ?></u><br/>
							NIP. <?= $setting['nip'] ?>
							<?php if ($skl['sttd'] == 1) { ?>
                        <img style="z-index: 800;position:absolute;margin-top:-90px;margin-left:-50px" class="img" src="../images/<?= $setting['ttd'] ?>" width="180">
                    <?php } ?>
                    <?php if ($skl['sstempel'] == 1) { ?>
                        <img style="z-index: 920;position:relative;margin-top:-90px;margin-left:-115px;opacity:0.7" class="img" src="../images/<?= $setting['stempel'] ?>" width="150px">
                    <?php } ?>
						</td>
					</tr>
				</table>
</body>

</html>
<?php

$html = ob_get_clean();
require_once '../vendors/autoload.php';;

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('Legal', 'portrait');
$dompdf->render();
$dompdf->stream("SKKB_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>