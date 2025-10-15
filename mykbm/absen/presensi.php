<?php
defined('APK') or exit('No Access');
$createAbsensiHarianTable = "
CREATE TABLE IF NOT EXISTS `absensi_harian` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `idsiswa` INT(11) NOT NULL,
  `kelas` VARCHAR(50) NOT NULL,
  `mapel` INT(11) NOT NULL,
  `guru` INT(11) NOT NULL,
  `ket` VARCHAR(1) NOT NULL DEFAULT 'H',
  `bulan` VARCHAR(2) NOT NULL,
  `tahun` VARCHAR(4) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jadwal_tanggal` (`id_jadwal`,`tanggal`),
  KEY `idx_idsiswa_tanggal` (`idsiswa`,`tanggal`),
  UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
mysqli_query($koneksi, $createAbsensiHarianTable);
@mysqli_query($koneksi, "ALTER TABLE absensi_harian ADD UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)");
$hari = date('D');
?>
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PRESENSI HARI INI</h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="alert alert-custom" role="alert">
									<strong>Perhatian ! </strong><br>
										<span>Sinkron Presensi Guru Mapel dapat dilakukan jika Hari sesuai Jadwal Mengajar</span>
									</div>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                  <th>NO</th>
                                                  <th>HARI</th>													  
												  <th>KELAS</th>													                                                 
                                                  <th>MAPEL</th>
												  <th>GURU PENGAMPU</th>
												  <th></th>
                                                </tr>
                                            </thead>											
                                            <tbody>	
											<?php
											$no = 0;
											
											$bulan = date('m');
											$tahun = date('Y');
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari'");
											elseif($user['level']=='guru'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari' and guru='$user[id_user]'");

											endif;
											  while ($data = mysqli_fetch_array($query)) :
											  $harix = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
											  $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											  $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											  $jabmap = mysqli_num_rows(mysqli_query($koneksi, "SELECT 1 FROM absensi_harian WHERE tanggal='$tanggal' AND id_jadwal='{$data['id_jadwal']}'"));
											$no++;
											   ?>
											   <tr>
                                                 <td><?= $no; ?></td>
												 <td><?= $harix['hari'] ?></td>
                                                   <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span> <span class="badge badge-primary"> <?= $data['kelas'] ?></span> <span class="badge badge-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Data Sinkron"> <?= $jabmap ?></span></h5></td>
													<td><?= $mapelx['kode'] ?></td>
													<td><?= $guru['nama'] ?></td>
													<td>
													<a href="?pg=<?= enkripsi('absensi') ?>&ac=<?= enkripsi('lihat') ?>&a=<?= enkripsi($data['id_jadwal']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Presensi"><i class="material-icons">visibility</i> </a>
													<a href="?pg=<?= enkripsi('absensi') ?>&a=<?= enkripsi($data['id_jadwal']) ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Ambil Presensi"><i class="material-icons">sync</i> </a>
													</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
										</div>
									<div class="col-md-4">
							  <?php
							  $idj = isset($_GET['a']) ? (int) dekripsi($_GET['a']) : 0;
							  $jadwal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE id_jadwal='$idj'")); 
							  $mpl = $jadwal ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE id='{$jadwal['mapel']}'")) : null; 
							  $peg = $jadwal ? mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='{$jadwal['guru']}'")) : null; 
							  
							  ?>
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                       <h5 class="bold">SINKRON PRESENSI MAPEL</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">									
									<form id='formsinkron' >	
									<input type="hidden" name="kuri" value="<?= $jadwal['kuri'] ?? '' ?>" >
									<input type="hidden" name="jadwal" value="<?= $idj ?>" >
										 <label class="bold">Tanggal</label>
									  <div class="input-group mb-1">
                                        <input type="text" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" readonly="true" >
                                        </div>
										<label class="bold">Kelas</label>
									  <div class="input-group mb-1">
									   <select name='kelas' class='form-select' style='width:100%' required>                                         
                                           <option value="<?= $jadwal['kelas'] ?? '' ?>"><?= $jadwal['kelas'] ?? 'Pilih Kelas' ?></option>
                                            </select>
                                        </div>
										<label class="bold">Mata Pelajaran</label>
									  <div class="input-group mb-1">
                                        <select name='mapel' class='form-select' style='width:100%' required>
										<option value="<?= $jadwal['mapel'] ?? '' ?>"><?= $mpl['nama_mapel'] ?? 'Pilih Mapel' ?></option>                                           
                                            </select>
                                        </div>
										 <label class="bold">Guru Pengampu</label>
									  <div class="input-group mb-1">
                                        <select name='guru' class='form-select' style='width:100%' required>
                                          <option value="<?= $jadwal['guru'] ?? '' ?>"><?= $peg['nama'] ?? 'Pilih Guru' ?></option>                                           
                                            </select>
                                        </div>
										
										<div class="widget-payment-request-actions m-t-lg d-flex">
											<?php if($idj<>''): ?>
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Sinkron Presensi</button>
                                            
											<?php endif; ?>
											</div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				 <script>
						$('#formsinkron').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'absen/tabsen.php?pg=sinkron',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									beforeSend: function() {
									$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
									$('.progress-bar').animate({
									width: "30%"
									}, 500);
									},
									success: function(data) {
									   
										setTimeout(function() {
											window.location.reload();
										}, 2000);
									}
								})
								return false;
							});
							</script>
									
				<?php elseif ($ac == enkripsi('lihat')): ?>
                    	<?php
							   $id = (int) dekripsi($_GET['a']);
							   $jadwal = fetch($koneksi,'jadwal_mapel',['id_jadwal'=>$id]);
							   $kelas = $jadwal['kelas'];
							   $mapel = $jadwal['mapel'];
							   $guru = $jadwal['guru'];
							   $mpl = fetch($koneksi,'mata_pelajaran',['id'=>$jadwal['mapel']]);
							   $peg = fetch($koneksi,'users',['id_user'=>$guru]);
								?>				 
                      <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">PRESENSI MAPEL <?= $mpl['kode'] ?></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="8%">#</th>  	
													<th width="18%">TANGGAL</th>
													<th>KELAS</th>	
                                                    <th>NAMASISWA</th>
                                                    <th>KET</th>													
													 <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM absensi_harian WHERE id_jadwal='$id' AND tanggal='$tanggal'"); 
											while ($datax = mysqli_fetch_array($query)) :
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$datax['idsiswa']]);
											if($datax['ket']=='H'){
												$absensi ='HADIR';
											}elseif($datax['ket']=='A'){
												$absensi ='ALPHA';
											}elseif($datax['ket']=='S'){
												$absensi ='SAKIT';
											}elseif($datax['ket']=='I'){
												$absensi ='IZIN';
											}
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $datax['tanggal'] ?></td>
													<td><h5><span class="badge badge-primary"><?= $jadwal['kelas'] ?><span> </h5></td>
													<td><?= $siswa['nama'] ?></td>
													<td><?= $absensi ?></td>
													  <td>
											<a href="?pg=<?= enkripsi('absensi') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($datax['id']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit LM"><i class="material-icons">edit</i> </a>											
											
											</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>	
								</div>
							</div>
				
                
             <?php elseif ($ac == enkripsi('edit')): ?>
			
             <?php
			$id = (int) dekripsi($_GET['id']);
			 $absen = fetch($koneksi,'absensi_harian',['id'=>$id]);
			 $sis = fetch($koneksi,'siswa',['id_siswa'=>$absen['idsiswa']]);
			 ?>					 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">EDIT PRESENSI</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="8%">#</th>  	
													<th width="18%">TANGGAL</th>
													<th>KELAS</th>	
                                                    <th>NAMA SISWA</th>													
													 <th>KET</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM absensi_harian WHERE id='$id'"); 
											while ($datax = mysqli_fetch_array($query)) :
                                            $siswa = fetch($koneksi,'siswa',['id_siswa'=>$datax['idsiswa']]);
											if($datax['ket']=='H'){
												$absensi ='HADIR';
											}elseif($datax['ket']=='A'){
												$absensi ='ALPHA';
											}elseif($datax['ket']=='S'){
												$absensi ='SAKIT';
											}elseif($datax['ket']=='I'){
												$absensi ='IZIN';
											}											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $datax['tanggal'] ?></td>
													<td><h5><span class="badge badge-primary"><?= $datax['kelas'] ?><span> </h5></td>
													<td><?= $siswa['nama'] ?></td>
													 <td><?= $absensi ?></td>
                                                </tr>
												<?php endwhile; ?>
                                               
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                       <h5 class="bold">EDIT PRESENSI</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formedit' >	
									 <label class="bold">Tanggal</label>
									  <div class="input-group mb-1">
                                        <input type="text" name="tgl" value="<?= $absen['tanggal']; ?>" class="form-control" readonly="true" >
										<input type="hidden" name="id" value="<?= $absen['id']; ?>" >
									   
                                        </div>
										 <label class="bold">Nama Siswa</label>
									  <div class="input-group mb-1">
                                        <select name='ids' class='form-select' style='width:100%' required>
                                               <option value="<?= $sis['id_siswa'] ?>"><?= $sis['nama'] ?></option>                                       
												 </select>
                                        </div>
										<label class="bold">Kelas</label>
									  <div class="input-group mb-1">
                                        <select name='kelas' class='form-select' style='width:100%' required>
                                               <option value="<?= $absen['kelas'] ?>"><?= $absen['kelas'] ?></option>                                       
												 </select>
                                        </div>
										
										<label class="bold">Keterangan</label>
									  <div class="input-group mb-1">
                                        <select name='ket' class='form-select' style='width:100%' required>
                                               <option value="">Pilih Keterangan</option>
											   <option value="I">IZIN</option>
											   <option value="S">SAKIT</option>
											   <option value="A">ALPHA</option>
												 </select>
                                        </div>
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
                 <script>
						$('#formedit').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'absen/tabsen.php?pg=edit',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									beforeSend: function() {
									$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
									$('.progress-bar').animate({
									width: "30%"
									}, 500);
									},
									success: function(data) {
									   
										setTimeout(function() {
											window.location.replace('?pg=<?= enkripsi(absensi) ?>');
										}, 2000);
									}
								})
								return false;
							});
							</script>
	
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					
