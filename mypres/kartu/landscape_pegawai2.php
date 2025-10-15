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
$absQ = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user BETWEEN '$id' AND '$ids'");
		
while ($peg = mysqli_fetch_array($absQ)) : 
		  
$tempdir = "../../temp/"; 
if (!file_exists($tempdir)) 
    mkdir($tempdir);


$codeContents = $peg['username'];

QRcode::png($codeContents, $tempdir . $peg['username'] . '.png', QR_ECLEVEL_M, 4);
endwhile;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>

    <title>KARPEL</title>
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
                            <td style="text-align:center; vertical-align:top">							
                              <img src="../../images/kartu/<?= $mesin['belakang'] ?>" width="328px" height="208px">
							<?php if($setting['mesin']=='2'){ ?>
							 
							<?php }else{ ?>
							<?php if($r['foto'] !=''): ?>
                              <center><img src="../../images/fotoguru/<?= $r['foto'] ?>" style="margin-top:-300px;width:100px;height:120px;"></center></p> 
                            <?php else: ?>
							
							<?php endif; ?>
							<?php } ?>
							<p style="margin-top:-90px;font-size:12px;color:#fff;" id="paragraf2"><b>
							 
							 <?= strtoupper($namamu) ?>
							
							</td>
							</tr>
						
							</table>
							
            </td>
            <?php if (($no % 2) == 0) : ?>
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