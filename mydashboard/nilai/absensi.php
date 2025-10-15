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
    <input type="hidden" name="pg" value="absensi"> <!-- ini yang penting -->
    <select name="bulan" id="bulan" class="form-control mr-2" onchange="this.form.submit()">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            $value = str_pad($i, 2, '0', STR_PAD_LEFT);
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
    <div class="card">
        <div class="card-header text-center">
            <h5 class="card-title">BULAN <?= strtoupper($bln['ket']); ?> <?= date('Y'); ?></h5>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <?php if ($siswa['foto'] == ''): ?>
                    <img src="../images/user.png" class="rounded-circle img-fluid" width="100" alt="Foto Siswa">
                <?php else: ?>
                    <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" class="rounded-circle img-fluid" width="100" alt="Foto Siswa">
                <?php endif; ?>
                <h6 class="mt-2"><?= $siswa['nama']; ?></h6>
            </div>
            <div class="row text-center">
                <div class="col-6 mb-3">
                    <div class="p-2 bg-success text-white rounded shadow-sm">
                        <div>Hadir</div>
                        <h4><?= $hadir; ?></h4>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="p-2 bg-secondary text-white rounded shadow-sm">
                        <div>Sakit</div>
                        <h4><?= $sakit; ?></h4>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="p-2 bg-primary text-white rounded shadow-sm">
                        <div>Izin</div>
                        <h4><?= $izin; ?></h4>
                    </div>
                </div>
                <div class="col-6 mb-3">
                    <div class="p-2 bg-danger text-white rounded shadow-sm">
                        <div>Alpha</div>
                        <h4><?= $alpha; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

								  
                         	
                            
							
							   