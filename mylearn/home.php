<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jpes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L'"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jtugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tugas"));
$jmateri = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM materi"));
$jnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jawaban_tugas"));
?>

<?php include"top.php"; ?>
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
                                                <span class="widget-stats-title">TOTAL PESERTA</span>
                                                <span class="widget-stats-amount"><?= $jpes ?> PD</span>
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
                                                <i class="material-icons-outlined">select_all</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA MATERI</span>
                                                <span class="widget-stats-amount"><?= $jmateri; ?></span>
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
                                                <i class="material-icons-outlined">support</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA TUGAS</span>
                                                <span class="widget-stats-amount"><?= $jtugas; ?></span>
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
                                                <i class="material-icons-outlined">edit</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA NILAI TUGAS</span>
                                                <span class="widget-stats-amount"><?= $jnil; ?></span>
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
                                        <h5 class="card-title">LOG SISWA</h5>
                                    </div>
                                    <div class="card-body" style="height:410px;">
                                        
                                        <ul class="widget-list-content list-unstyled">
                                            <?php									
									$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE online='1' ORDER BY id_siswa DESC LIMIT 5"); 			
									while ($data = mysqli_fetch_array($query)) :
									$sis = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$data[id_siswa]'"));	
									?>
											<li class="widget-list-item widget-list-item-blue">
                                                <span class="widget-list-item-icon">
                                                    <i class="material-icons-outlined">face</i>
                                                </span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                       <?= $sis['nama'] ?>
                                                    </a>
                                                    <span class="widget-list-item-description-date">
                                                       Online
                                                    </span>
                                                </span>
                                                <span class="widget-list-item-transaction-amount-positive"></span>
                                            </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>	
							<div class="col-xl-4">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">LOG GURU</h5>
                                    </div>
                                    <div class="card-body" style="height:410px;">
                                        
                                        <ul class="widget-list-content list-unstyled">
										<?php
									
									$query = mysqli_query($koneksi, "SELECT * FROM log WHERE level='pegawai' ORDER BY id_log DESC LIMIT 4"); 			
									while ($data = mysqli_fetch_array($query)) :
									$pusat = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users  WHERE id_user='$data[id_user]'"));	
								
									?>
                                            <li class="widget-list-item widget-list-item-yellow">
                                               <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <?= $pusat['nama'] ?> 
                                                    </a>
                                                    <span class="widget-list-item-description-date">
                                                       <?= $data['date'] ?>
                                                    </span>
                                                </span>
                                                <span class="widget-list-item-transaction-amount-positive"> <?= timeAgo($data['date']) ?></span>
                                            </li>
                                           <?php endwhile; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>	
							
							<div class="col-md-4">                                
                             						
                                <div class="card widget widget-list">
								<div class="card-header">
                                        <h5 class="card-title">OPTIMIZE DATABASE</h5>
                                    </div>
									 <div class="card-body">
									
									<div class="d-grid gap-2">
										<button class="btn btn-primary" type="button" id="optimal">OPTIMIZE</button>
									</div>
									</div>									
                                  </div>
								  <div class="col-xl-12">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">LOG ADMIN</h5>
                                    </div>
                                    <div class="card-body" style="height:270px;">
									<?php
									$tgl= date('Y-m-d');
									$query = mysqli_query($koneksi, "SELECT * FROM log WHERE level='admin' ORDER BY id_log DESC LIMIT 2"); 			
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
                            
							</div>
                       
					  
								
							  
	<script>
				$("#optimal").click(function(){
		    	Swal.fire({
				  title: 'Optimize Database',
				  text: "Informasi : Optimize Table Jawaban dan Soal",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, Optimize !'
				}).then((result) => {
				  if (result.value) {
					$.ajax({
					url: 'siswa/tsiswa.php?pg=optimal',
					success: function(data) {
						 Swal.fire(
				      'Success!',
				      'Your file has been Optimize.',
				      'success'
				    )
				   setTimeout(function() {
					window.location.reload();
					}, 1000);
						}
					});
					}
					return false;
						})

						});
					</script>	
	  <?php include('bottomcache.php'); ?>