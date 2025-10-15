<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulanDipilih = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

// Ambil data bulan dari database (opsional, kalau kamu simpan daftar bulan di DB)
$bln = mysqli_fetch_array(mysqli_query($koneksi, "SELECT bln,ket FROM bulan WHERE bln='$bulanDipilih'"));

// Data absensi berdasarkan bulan dipilih
$hadir = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi WHERE idsiswa='$id_siswa' AND ket='H' AND bulan='$bulanDipilih'"));
$sakit = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi WHERE idsiswa='$id_siswa' AND ket='S' AND bulan='$bulanDipilih'"));
$izin  = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi WHERE idsiswa='$id_siswa' AND ket='I' AND bulan='$bulanDipilih'"));
$alpha = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi WHERE idsiswa='$id_siswa' AND ket='A' AND bulan='$bulanDipilih'"));
?>

<?php include"top.php"; ?>
                       <div class="row">
                            <div class="col">
                                <div class="page">
                                    <h4>REKAPITULASI ABSENSI <?= strtoupper($bln['ket']); ?> <?= date('Y'); ?></h4>
                                   
                                </div>
                            </div>
                        </div>
			<div class="row">
				<div class="col-xl-8">
					<div class="card">
						<div class="card-header">
						   <div class="card-title"><h5>Pilih Bulan</h5></div>
						   <!-- Form Pilih Bulan -->
<form method="get" class="form-inline mb-3">
   <select name="bulan" id="bulan" class="form-control mr-2" onchange="this.form.submit()">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $value = str_pad($i, 2, '0', STR_PAD_LEFT); // 01, 02, ..., 12
            $namaBulan = date('F', mktime(0, 0, 0, $i, 10));
            $namaBulanIndo = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            $selected = ($value == $bulanDipilih) ? 'selected' : '';
            echo "<option value='$value' $selected>{$namaBulanIndo[$namaBulan]}</option>";
        }
        ?>
    </select>
</form>
						</div>
						 <div class="card-body">
						<div class="card-box table-responsive">
    <table class="table table-bordered table-hover" style="width:100%;font-size:12px">
        <thead>
            <tr>
                <th width="5%">NO</th>                                               
                <th>TANGGAL</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$id_siswa' AND bulan='$bulanDipilih' ORDER BY id ASC"); 
            while ($data = mysqli_fetch_array($query)) :
                $no++;

                // Format hari dan tanggal
                $tanggal = $data['tanggal'];
                $hariInggris = date('l', strtotime($tanggal));
                $hariIndonesia = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu'
                ];
                $hari = $hariIndonesia[$hariInggris];
                $formatTanggal = date('d-m-Y', strtotime($tanggal));

                // Konversi keterangan + warna
                $keterangan = [
                    'H' => ['label' => 'Hadir', 'badge' => 'success'],
                    'S' => ['label' => 'Sakit', 'badge' => 'secondary'],
                    'I' => ['label' => 'Izin',  'badge' => 'info'],
                    'A' => ['label' => 'Alpha', 'badge' => 'danger']
                ];

                $ket = $data['ket'];
                $ketLabel = isset($keterangan[$ket]) ? $keterangan[$ket]['label'] : $ket;
                $ketBadge = isset($keterangan[$ket]) ? $keterangan[$ket]['badge'] : 'dark';
            ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $hari . ', ' . $formatTanggal; ?></td>
                <td><span class="badge badge-<?= $ketBadge; ?>"><?= $ketLabel; ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

									</div>
								</div>	
							</div>
							<div class="col-md-4">                                
                             						
                                <div class="card widget widget-list">
								<div class="card-header">
                                    
                                        <h5 class="card-title">BULAN <?= strtoupper($bln['ket']); ?> <?= date('Y'); ?></h5>
                                    </div>
                                    <div class="card-body" style="height:290px;">
									
									<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:blue;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    HADIR
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $hadir; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:grey;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    SAKIT
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $sakit; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:purple;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    IZIN
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $izin; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:red;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    ALPHA
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $alpha; ?></span></h5>
                                            </div>
                                        </div>
										<br>
                                    </div>
                                </div>
							</div>
								  
                          </div>	
                            
							
							   