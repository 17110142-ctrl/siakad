<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!'); 
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L'"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P'"));
$jmap = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"));
$jguru = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users where level='guru'"));
$jstaff = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users where level='staff'"));
$pesan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM pesan_terkirim  WHERE sender='$id_user' ORDER BY id DESC LIMIT 1"));
$jinfo = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM informasi where untuk='2'"));
?>


<?php include"top.php"; ?>
<?php if($user['level']== 'guru') {
$hari = date('D');
}
?>

               <div class="row">
                <?php           
                $mapelQ = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari' and kelas='$user[kelas]'");
				while ($mapel = mysqli_fetch_array($mapelQ)) : 
				?>
                    <?php
					
                        $guru = fetch($koneksi, 'users', ['id_user' => $mapel['guru']]);
						$pel = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel['mapel']]);
						$absen = fetch($koneksi, 'absensi', ['idpeg' => $mapel['guru'],'tanggal'=>$tanggal]);
						$harix = fetch($koneksi, 'm_hari', ['inggris' => $mapel['hari']]);
                        $warna = array('red', 'blue',  'green', 'gray', 'purple', 'black');
                        ?>
                               <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">JADWAL MAPEL</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container" >
                                            <div class="widget-payment-request-author" >
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/icon/buku.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $pel['nama_mapel'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $mapel['kelas'] ?></span>
                                                </div>
                                            </div>
                                            <div class="widget-payment-request-product" style="background-color: <?= $warna[rand(0, count($warna) - 1)] ?>;">
                                                <div class="widget-payment-request-product-image m-r-sm">
												<?php if($guru['foto']==''): ?>
                                                    <img src="../images/guru.png" class="mt-auto" alt="">
												<?php endif; ?>
                                                </div>
                                                <div class="widget-payment-request-product-info d-flex" >
                                                    <div class="widget-payment-request-product-info-content" >
                                                        <span class="widget-payment-request-product-name" style="color:#fff;">Guru Pengampu</span>
                                                        <span class="widget-payment-request-product-about" style="color:#fff;"><?= $guru['nama'] ?></span>
                                                    </div>
                                                  
                                                </div>
                                            </div>
                                            <div class="widget-payment-request-info m-t-md">
											<div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block bold">
                                                        HARI
                                                    </span>
													<span class="text-muted d-block"><?= $harix['hari']; ?></span>
                                                </div>
											 <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block bold">
                                                        TANGGAL
                                                    </span>									
                                                    <span class="text-muted d-block"><?= $tanggal; ?></span>
                                                </div>
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block bold">
                                                       KEHADIRAN GURU
                                                    </span>
                                                    <span class="text-muted d-block">
													<?php if($absen['ket']=='H'){ ?>
													HADIR
													<?php }elseif($absen['ket']=='S'){ ?>
													SAKIT
													<?php }elseif($absen['ket']=='I'){ ?>
													IZIN
													<?php }elseif($absen['ket']=='A'){ ?>
													ALPHA
													<?php } ?>
													</span>
													
                                                </div>
                                               
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
					
							 
                <?php endwhile; ?>
                </div> 
                
                      <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA PEREMPUAN</span>
                                                <span class="widget-stats-amount"><?= $jpesP; ?> PD</span>
                                                <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA LAKI-LAKI</span>
                                                <span class="widget-stats-amount"><?= $jpesL; ?> PD</span>
                                                <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">TOTAL SISWA</span>
                                                <span class="widget-stats-amount"><?= $jsiswa ?></span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">book</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA MAPEL</span>
                                                <span class="widget-stats-amount"><?= $jmap; ?></span>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-purple">
                                                <i class="material-icons-outlined">people</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA GURU</span>
                                                <span class="widget-stats-amount"><?= $jguru; ?></span>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">people</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA STAFF</span>
                                                <span class="widget-stats-amount"><?= $jstaff; ?></span>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
						 
                     
						<div class="row">
						<div class="col-xl-4">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">KIRIM PESAN</h5>
										<div class="pull-right">
										<a href="?pg=kontakme" class="btn btn-sm btn-primary"><i class="material-icons">add</i>Kontak</a>
										</div>
                                    </div>
                                    <div class="card-body" style="height:410px;">
									
                                       <form id="formpesan" >
									   <input type="hidden" name="sender" value="<?= $id_user ?>" >
                                <div class="col-md-12">
							 <label class="form-label bold"> Kepada</label>
							<select name='nowa' class='form-select' style='width:100%' required>
                             <option value=''>Pilih Kontak</option>
                              <?php $que = mysqli_query($koneksi, "SELECT * FROM kontakme"); ?>
                                <?php while ($k = mysqli_fetch_array($que)) : ?>
                                 <option value="<?= $k['nowa'] ?>"><?= $k['nama_kontak'] ?></option>"
                                <?php endwhile ?>
                               </select>
							 </div>
                                
								<div class="col-md-12">
							 <label class="form-label bold"> Isi Pesan</label>
							 <textarea name='pesan' class='form-control'  rows="7" required="true" /></textarea>
							 </div>
							 <p>
							 <div class="col-md-12">
										<button type="submit" class="btn btn-primary kanan">KIRIM</button>
										 </div>
									    </form>
                                    </div>	
									
									
                                </div>
                            </div>	
							<script>
							   $('#formpesan').submit(function(e) {
									e.preventDefault();
									var data = new FormData(this);
								  
									$.ajax({
										type: 'POST',
										url: 'pengaturan/pesan.php',
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
									});
									return false;
								});
							   
							</script>
							<?php if($user['level']=='admin'): ?>
							<div class="col-xl-4">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">PESAN TERKIRIM</h5>
                                    </div>
                                    <div class="card-body" style="height:410px;">
                                      <ul class="list-group list-group-flush">
                                      <li class="list-group-item">Send To : <?= $pesan['nowa'] ?> </li>
                                      <li class="list-group-item">Time : <?= $pesan['waktu'] ?></li>
                                     <li class="list-group-item" id="datatable">Message :
									 <button data-id="<?= $pesan['id'] ?>"  class="hapus btn btn-sm btn-link" id="optimal" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"> <?= $pesan['isi'] ?></button>
									 </li>
                                    </ul>
                                    </div>
                                </div>
                            </div>	
							
							<div class="col-md-4">                                
                             	
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">LOG ADMIN</h5>
                                    </div>
                                    <div class="card-body" style="height:410px;">
									<?php
									$tgl= date('Y-m-d');
									$query = mysqli_query($koneksi, "SELECT * FROM log WHERE level='admin' ORDER BY id_log DESC LIMIT 4"); 			
									while ($data = mysqli_fetch_array($query)) :
									$pusat = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users  WHERE id_user='$data[id_user]'"));	
									$tgllog = date('Y-m-d',strtotime($data['date']));
									?>
									<?php if($tgl<>$tgllog):?>
									 <?php $exec = mysqli_query($koneksi, "truncate log"); ?>
									  <?php endif; ?>	
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $pusat['nama'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $data['date']; ?></span>
													<p style="color:blue;"><?= timeAgo($data['date']) ?></p>
                                                </div>
                                            </div>
                                           
                                        </div>
										<?php endwhile; ?>
                                    </div>
                                </div>
							</div>
								  
                               </div>	
                            <?php else: ?>
							<div class="col-md-8">                                
                             	
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">INFORMASI</h5>
                                    </div>
                                    <div class="card-body">
									<?php if ($jinfo == 0) { ?>
                                           <p class='text-center'>Tidak ada aktifitas</p>
                                        <?php } ?>
										<hr>
									 <?php 
                                        $logQ = mysqli_query($koneksi, "SELECT * FROM informasi where untuk='2' ORDER BY id DESC");
                                        while ($log = mysqli_fetch_array($logQ)):
											
                                      ?> 
                                        
                                      <span class='badge badge-primary kanan'> <i class='fa fa-calendar'></i><?=  buat_tanggal('d-m-Y', $log['waktu']) ?>  <i class='fa fa-clock-o'></i> <?= buat_tanggal('H:i', $log['waktu']) ?></span>
                                      <h5><?= $log['judul'] ?></h5>
                                     <p><?= $log['isi'] ?></p>
                                       <hr>                
										<?php endwhile; ?>
									</div>
                                </div>
							</div>
							<?php endif; ?>
							</div>
                       <script>
									$('#datatable').on('click', '.hapus', function() {
									var id = $(this).data('id');
									console.log(id);
									swal({
											  title: 'Yakin hapus Pesan',
											  text: "You won't be able to revert this!",
											  type: 'warning',
											  showCancelButton: true,
											  confirmButtonColor: '#3085d6',
											  cancelButtonColor: '#d33',
											  confirmButtonText: 'Ya, Hapus!',
											  cancelButtonText: "Batal"				  
									}).then((result) => {
										if (result.value) {
											$.ajax({
											   url: 'master/tsiswa.php?pg=hps',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
											    $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);
												setTimeout(function() {
												window.location.reload();
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    