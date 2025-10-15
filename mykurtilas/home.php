<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$tanggal = date('Y-m-d');
$bulan = date('m');
$tahun = date('Y');

$ki3 = mysqli_num_rows(mysqli_query($koneksi, "SELECT nilai3 FROM nilai_rapor"));
$ki4 = mysqli_num_rows(mysqli_query($koneksi, "SELECT nilai4 FROM nilai_rapor"));
$sos = mysqli_num_rows(mysqli_query($koneksi, "SELECT nis FROM sosial"));
$spi = mysqli_num_rows(mysqli_query($koneksi, "SELECT nis FROM spiritual"));
$sikap = $sos + $spi;
$eskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM m_eskul"));
$peskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peskul"));
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
                                                <span class="widget-stats-title">NILAI PENGETAHUAN</span>
                                                <span class="widget-stats-amount"><?= $ki3; ?> </span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
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
                                                <span class="widget-stats-title">NILAI KETERAMPILAN</span>
                                                <span class="widget-stats-amount"><?= $ki4; ?></span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
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
                                                <span class="widget-stats-title">NILAI SIKAP</span>
                                                <span class="widget-stats-amount"><?= $sikap ?> </span>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,19) ?></span>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PROGRES RAPOR KURTILAS</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                             <th width="5%">NO</th>                                               
                                             <th>TKT</th>
                                             <th>MATA PELAJARAN</th>
											<?php																				
											 $queryx = mysqli_query($koneksi,"SELECT * FROM kelas WHERE kurikulum='1'");  
											 while ($datax = mysqli_fetch_array($queryx)) :
											   ?>
										   <th><?= $datax['kelas'] ?></th>
                                           <?php endwhile; ?>		
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor where kurikulum='1' ORDER BY tingkat,urut ASC");
											while ($data = mysqli_fetch_array($query)) :
											  $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											 
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													 <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span></h5></td>                                                 
													  <td><?= $mapelx['nama_mapel'] ?><br><span class="badge badge-secondary"><?= $guru['nama'] ?></span> <span class="badge badge-info"><?= $kuri['nama_kurikulum'] ?></span></td>
													<?php																				
											 $queryx = mysqli_query($koneksi,"SELECT * FROM kelas WHERE kurikulum='1'");  
											 while ($datax = mysqli_fetch_array($queryx)) :
											 $jnr = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_rapor where kelas='$datax[kelas]' and mapel='$data[mapel]' and semester='$semester' and tp='$tapel'"));
											
											 $jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT kelas FROM siswa WHERE kelas='$datax[kelas]'"));
											 ?>
										   <td>
										   <?php if($jnr<>0){ ?>
										   <h5><span class="badge badge-success">100%</span></h5> 
										   <?php }else{ ?><h5><span class="badge badge-danger">0%</span></h5>  <?php } ?>
								
										   </td>
                                           <?php endwhile; ?>	 
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
                              
							  <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">select_all</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">EKSTRAKURIKULER</span>
                                                <span class="widget-stats-amount"><?= $eskul; ?></span>
                                                <span class="widget-stats-info"></span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">select_all</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">PESERTA ESKUL</span>
                                                <span class="widget-stats-amount"><?= $peskul; ?> </span>
                                                <span class="widget-stats-info"></span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>	
                      </div>	
                               
	                  <script>

							var autoRefresh = setInterval(
								function() {
									$('#logabs').load('logabsen.php');
									$('#logabsen').load('logsis.php');
									$('#logabsenpeg').load('logpeg.php');
									$('#logpesan').load('logpesan.php');
								}, 1000
							);
						</script>