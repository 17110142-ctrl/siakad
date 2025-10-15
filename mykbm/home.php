<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$tanggal = date('Y-m-d');
$bulan = date('m');
$tahun = date('Y');
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='siswa'"));
$jagenda = mysqli_num_rows(mysqli_query($koneksi, "SELECT bulan,tahun FROM agenda where bulan='$bulan' and tahun='$tahun'"));
$jjurnal = mysqli_num_rows(mysqli_query($koneksi, "SELECT bulan,tahun,hambatan FROM agenda where hambatan<>'' and bulan='$bulan' and tahun='$tahun'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM siswa"));
$ki3 = mysqli_num_rows(mysqli_query($koneksi, "SELECT kuri FROM nilai_harian where kuri='1'"));
$ki4 = mysqli_num_rows(mysqli_query($koneksi, "SELECT kuri FROM nilai_harian where kuri='2'"));
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
                                                <span class="widget-stats-title">ABSENSI SISWA HARI INI</span>
                                                <span class="widget-stats-amount"><?= $jabsis; ?> PD</span>
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
                                                <span class="widget-stats-title">AGENDA GURU BULAN INI</span>
                                                <span class="widget-stats-amount"><?= $jagenda; ?></span>
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
                                                <span class="widget-stats-title">JURNAL GURU BULAN INI</span>
                                                <span class="widget-stats-amount"><?= $jjurnal ?> </span>
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
                                        <h5 class="card-title">JADWAL KBM HARI INI</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>HARI</th>
													<th>TKT - KELAS</th>
                                                    <th>MATA PELAJARAN</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no = 0;
											$hari = date('D');
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari'");
											elseif($user['level']=='guru'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari' and guru='$user[id_user]'");
											elseif($user['level']=='kepala'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari'");
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											  $harix = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
											  $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											  $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											  $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kuri']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $harix['hari'] ?></td>
                                                     <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span> <span class="badge badge-primary"> <?= $data['kelas'] ?></span></h5></td>
													  <td><?= $mapelx['nama_mapel'] ?><br><span class="badge badge-secondary"><?= $guru['nama'] ?></span> <span class="badge badge-info"><?= $kuri['nama_kurikulum'] ?></span></td>
													 
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
                                                <span class="widget-stats-title">NILAI HARIAN (K-13)</span>
                                                <span class="widget-stats-amount"><?= $ki3; ?></span>
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
                                                <span class="widget-stats-title">NILAI HARIAN (K-MERDEKA)</span>
                                                <span class="widget-stats-amount"><?= $ki4; ?> </span>
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