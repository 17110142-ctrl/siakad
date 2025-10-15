<?php 
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require("../../config/dis.php");
(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
($id_user == 0) ? header('location:login.php') : null;
echo "<style> .str{ mso-number-format:\@; } </style>";

$bl= $_GET['bln'];
$bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);

$file = "REKAP ABSENSI BULAN ".$bulane['ket'];
header("Content-type: application/octet-stream");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Disposition: attachment; filename=" . $file . ".xls");
?>
<center><h4>REKAPITULASI ABSENSI PEGAWAI</h4></center>
<table border='1'>
	<thead>
		<tr>
                <th width="2%" height="40px">No</th>
                <th>Nama Pegawai</th>
				<th width="8%" style="text-align:center;">Jabatan</th>
                <?php
				$bulan= $bl;
				$tahun=date('Y');
				
                	$tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                    for ($i = 1; $i < $tanggal + 1; $i++) { ?>
                    <?php
					$date1 = date("D",strtotime("$tahun-$bulan-$i"));
					?>
                    <th width="2%">
                    <?php if($date1=='Sun')	{ ?>				
					<b style="color:red"><?= $i ?></b>
					<?php }else{ ?>
					<?= $i ?>
					<?php } ?>
					</th>
                <?php } ?>
                <th width="1%">H</th>
                <th width="1%">S</th>
                <th width="1%">I</th>
                <th width="1%">A</th>
            </tr>
                  <?php
			$query = mysqli_query($koneksi,"select id_user,level,nama from users where level<>'admin' GROUP BY id_user");
             $no = 0;
              while ($peg = mysqli_fetch_array($query)) {
		$hadir= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idpeg='$peg[id_user]' AND ket='H' AND bulan='$bulan' AND tahun='$tahun' "));
         $sakit= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idpeg='$peg[id_user]' AND ket='S' AND bulan='$bulan' AND tahun='$tahun' "));
		 $izin= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idpeg='$peg[id_user]' AND ket='I' AND bulan='$bulan' AND tahun='$tahun' "));
         $alpha= mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idpeg='$peg[id_user]' AND ket='A' AND bulan='$bulan' AND tahun='$tahun' "));
			  $no++;
			?>
			
							<tr>
                                    <td class="text-center"><?= $no; ?></td>
                                    <td>&nbsp;&nbsp;<?= ucwords(strtolower($peg['nama'])) ?></td>
									 <td>&nbsp;&nbsp;<?= ucwords(strtolower($peg['level'])) ?></td>
				<?php 
				
				$tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                    for ($i = 1; $i < $tanggal + 1; $i++) { ?>
                        <?php $tanggalbaru = date('Y-m-d', mktime(0, 0, 0, $bulan, $i, $tahun));
						$date2 = date("D",strtotime("$tahun-$bulan-$i"));
                        $cekabsen = fetch($koneksi, 'absensi', ['tanggal' => $tanggalbaru, 'idpeg' => $peg['id_user']]);
                       if ($cekabsen) { ?>
					 
                            <td class="text-center"><b><?= $cekabsen['ket'] ?></b></td>
                        <?php } else { ?>
						 <?php if($date2=='Sun'): ?>
                            <td style="color:white;background-color:red" class="text-center">X</td>
							<?php else: ?>
							<td></td>
							<?php endif; ?>
                        <?php } ?>
                    <?php } ?>
					
							  <td class="text-center"><?= $hadir; ?></td>
							  <td class="text-center"><?= $sakit; ?></td>
							 <td class="text-center"><?= $izin; ?></td>
							  <td class="text-center"><?= $alpha; ?></td>
							  
									 </tr>   
			  <?php } ?>
                    
								
            </table>