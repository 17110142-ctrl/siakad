<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

$jmateri = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absen_daringmapel where idsiswa='$id_siswa'"));
$jtugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM jawaban_tugas where id_siswa='$id_siswa'"));
$jnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM jawaban_tugas where id_siswa='$id_siswa' and nilai<>''"));

?>

<?php include"top.php"; ?>
                     
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">select_all</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">MATERI TELAH DI BACA</span>
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
                                                <span class="widget-stats-title">TUGAS DIKERJAKAN</span>
                                                <span class="widget-stats-amount"><?= $jtugas; ?></span>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats" >
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">edit</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill" >
                                                <span class="widget-stats-title">DATA NILAI TUGAS</span>
                                                <span class="widget-stats-amount"><?= $jnil; ?></span>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
						 
                     
						<div class="row">
						<div class="col-xl-8"></div>	
							
							<div class="col-md-4">                                
                             						
                                <div class="card widget widget-list">
								<div class="card-header">
                                    
                                        <h5 class="card-title">PRESENSI E-LEARN</h5>
                                    </div>
                                    <div class="card-body" style="height:290px;">
									<?php
									$query = mysqli_query($koneksi, "SELECT * FROM absen_daringmapel where idsiswa='$id_siswa' ORDER BY id DESC LIMIT 3"); 
									while ($data = mysqli_fetch_array($query)) :
									?>
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
                                            <div class="widget-connection-request-info flex-grow-1">
                                                <span class="widget-connection-request-info-name">
                                                    <?= $data['tanggal'] ?>
                                                </span>
                                                <h5><span class="badge badge-primary"><?= $data['mapel'] ?></span> <span class="badge badge-success"><?= $data['ket'] ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<?php endwhile; ?>
                                    </div>
                                </div>
							</div>
								  
                          </div>	
                            
							
							   