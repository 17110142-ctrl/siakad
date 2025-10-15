<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulan = date('m');
$blQ = fetch($koneksi,'bulan',['bln'=>$bulan]);

$isKurikulumTask = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
$kelasList = [];
if ($isKurikulumTask) {
    $kelasRes = mysqli_query($koneksi, "SELECT kelas FROM kelas ORDER BY kelas ASC");
    if ($kelasRes) {
        while ($row = mysqli_fetch_assoc($kelasRes)) {
            $kelasName = trim($row['kelas'] ?? '');
            if ($kelasName !== '' && !in_array($kelasName, $kelasList, true)) {
                $kelasList[] = $kelasName;
            }
        }
        mysqli_free_result($kelasRes);
    }
}

$selectedKelas = trim($user['walas'] ?? '');
if ($isKurikulumTask) {
    $selectedKelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
    if ($selectedKelas === '' && !empty($kelasList)) {
        $selectedKelas = $kelasList[0];
    }
}

$classOptions = [];
if ($isKurikulumTask) {
    $classOptions = $kelasList;
} elseif ($selectedKelas !== '') {
    $classOptions[] = $selectedKelas;
}
?>


                        <div class="row">
                           <div class="col-xl-8">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">
										BULAN <?= strtoupper($blQ['ket']); ?> <?= date('Y') ?>
										<button class="btn btn-secondary kanan" type="button" disabled>
                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									<?= buat_tanggal('D, d M Y') ?> </button>
										</h5>
                                    </div>
									
                                    <div class="card-body">

<?php if ($isKurikulumTask && !empty($kelasList)) : ?>
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="pg" value="absensiswa">
        <div class="col-md-6 col-lg-4">
            <label for="kelasFilter" class="form-label">Pilih Kelas</label>
            <select id="kelasFilter" name="kelas" class="form-select" onchange="this.form.submit()">
                <?php foreach ($kelasList as $kelasItem) : ?>
                    <option value="<?= htmlspecialchars($kelasItem, ENT_QUOTES) ?>" <?= $kelasItem === $selectedKelas ? 'selected' : '' ?>>Kelas <?= htmlspecialchars($kelasItem) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
<?php elseif ($isKurikulumTask && empty($kelasList)) : ?>
    <div class="alert alert-warning">Belum ada data kelas yang dapat ditampilkan.</div>
<?php endif; ?>

<?php if ($selectedKelas === '' && !$isKurikulumTask) : ?>
    <div class="alert alert-info">Anda belum ditetapkan sebagai wali kelas.</div>
<?php endif; ?>

<?php if ($selectedKelas !== '') : ?>
                                         <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th >NAMA LENGKAP</th>
                                                    <th>ROMBEL</th>
                                                    <th>H &nbsp;</th>
                                                    <th>I &nbsp;</th>
													<th>S &nbsp;</th>
													<th>A &nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0; 
											$kelasEsc = mysqli_real_escape_string($koneksi, $selectedKelas);
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelasEsc'"); 
											 while ($data = mysqli_fetch_array($query)) :
				                             $hadir = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$data[id_siswa]' AND ket='H' AND bulan='$bulan'"));
											$izin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$data[id_siswa]' AND ket='I' AND bulan='$bulan'"));
											$sakit = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$data[id_siswa]' AND ket='S' AND bulan='$bulan'"));
											$alpha = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$data[id_siswa]' AND ket='A' AND bulan='$bulan'"));
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                   
                                                     <td><?= $data['nama'] ?></td>
													   <td><?= $data['kelas'] ?></td>
                                                    <td><?= $hadir; ?></td>
													   <td><?= $izin; ?></td>
													     <td><?= $sakit; ?></td>
														   <td><?= $alpha; ?></td>
                                                </tr>
 											<?php endwhile; ?>
												</table>
												 </div>
<?php endif; ?>
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                         
                             <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">CETAK PRESENSI SISWA</h5>
                                    </div>
                                    <div class="card-body">
									 <form id="formabsen" method="POST" action="siswa/cetakkelas.php" target="_blank" enctype="multipart/form-data">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $setting['sekolah'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
													<label class="form-label">ROMBEL</label>                               
                                  <select name="kelas"  class="form-select" style="width: 100%;" required >
                                     <?php if (empty($classOptions)) : ?>
                                         <option value="" disabled selected>Tidak ada kelas tersedia</option>
                                     <?php else : ?>
                                         <?php foreach ($classOptions as $kelasOption) : ?>
                                             <option value="<?= htmlspecialchars($kelasOption, ENT_QUOTES) ?>" <?= $kelasOption === $selectedKelas ? 'selected' : '' ?>><?= htmlspecialchars($kelasOption) ?></option>
                                         <?php endforeach; ?>
                                     <?php endif; ?>
                                           </select>  
										   <br>
													<label>BULAN</label>
                                                   <select name="bulan"  class="form-select" style="width: 100%;" required >
										  <option value=''></option>
											 <?php $qt = mysqli_query($koneksi, "SELECT * FROM bulan"); ?>
											   <?php while ($mt = mysqli_fetch_array($qt)) : ?>
												 <option value="<?= $mt['bln'] ?>"><?= $mt['ket'] ?> <?= date('Y') ?></option>
													<?php endwhile ?>
													   </select>   
															
													</span>                                                  
                                                </div>                                               
                                            </div>
											<p>
                                           <div class="d-grid gap-2">
                                             
                                                <button type="submit"  class="btn btn-primary flex-grow-1 m-l-xxs">CETAK REKAP</button>
                                            </div>
                                        </div>
										</form>
										<p>
                                    </div>
                                </div>
                            </div>
                        </div>
                             	
					</div>
                     	
