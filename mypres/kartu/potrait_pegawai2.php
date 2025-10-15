<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
include "../../vendor/phpqrcode/qrlib.php";
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$mesin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mesin_absen  WHERE id='$setting[mesin]'"));
$id = $_POST['id'];
$ids = $_POST['ids'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>KARTU PEGAWAI</title>
<style>
@page { margin: 10px; }
body { margin: 20px; }

</style>
<style type="text/css">
        body { font-family: Calibri, Helvetica, Arial, sans-serif; }
        h3 { font-family: Cambria,"Times New Roman",serif; }
        #paragraf2 { Calibri, Helvetica, Arial, sans-serif; }
    </style>
</head>

<body>
<table width='100%' align='center' cellpadding='0px' style="margin-top:50px;">
    <tr>
	
	<?php $no=0; ?>
	 <?php $pegawaiQ = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user BETWEEN '$id' AND '$ids'"); ?>
	
        <?php while ($r = mysqli_fetch_array($pegawaiQ)) : ?>
		<?php 
		if($r['jk']=='L'){
			$jkel='Laki-laki';
		}else{
			$jkel='Perempuan';
		} 
		if (strlen($r['nama']) > 26) {
        $namamu= substr($r['nama'], 0, 26) . "...";
         } else {
           $namamu = $r['nama'];
          }	
		?>
		<?php   $no++; ?>

<td width='33%'>
                
                    <table style="text-align:center; width:100%">
                        <tr>
						
                            <td style="text-align:left; vertical-align:top">
                              <img src="../../images/kartu/<?= $mesin['belakang'] ?>" width="208px" height="328px">
							<p style="margin-top:-260px;margin-left:10px;margin-right:50px;font-size:9px;color:#00008B" id="paragraf2">
							Username &nbsp;&nbsp;&nbsp;&nbsp; : <?= $r['Username'] ?><br>
							NIP : <?= strtoupper($r['nip']) ?><br>
							J. KELAMIN : <?= strtoupper($jkel) ?><br>
							AGAMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= strtoupper($r['agama']) ?><br>
							NIS - NISN &nbsp; : <?= $r['nis'] ?> - <?= $r['nisn'] ?><br>
							ALAMAT &nbsp;&nbsp;&nbsp;&nbsp; : <?= $r['alamat'] ?><br>
							</p>
							 
							</td>
							
							</tr>
						
							</table>
							
            </td>
            <?php if (($no % 3) == 0) : ?>
    </tr>
	<tr>
    <?php endif; ?>
<?php endwhile; ?>

    </tr>
</table>
 
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
$dompdf->stream("KARPEG.pdf", array("Attachment" => false));
exit(0);
?>