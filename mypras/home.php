<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jkate = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM s_kategori"));
$jlok = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM s_lokasi"));
$jdata = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM s_barang"));
$jdata2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM s_barang WHERE idk='1'"));
$jdata3 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM s_barang WHERE idk<>'1'"));

?>

<?php include"top.php"; ?>
                      <div class="row">
							  <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">menu</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">KATEGORI</span>
                                                <span class="widget-stats-amount"><?= $jkate; ?></span>
                                                
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
                                                <i class="material-icons-outlined">home</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">LOKASI</span>
                                                <span class="widget-stats-amount"><?= $jlok; ?></span>
                                                
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
                                                <i class="material-icons-outlined">storage</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">JUMLAH DATA</span>
                                                <span class="widget-stats-amount"><?= $jdata ?> </span>
                                               
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
                                        <h5 class="card-title">DATA SAPRAS</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>KATEGORI</th>
													<th>JUMLAH</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no = 0;
											$query = mysqli_query($koneksi, "SELECT * FROM s_kategori ORDER BY id DESC");
											  while ($data = mysqli_fetch_array($query)) :
											  $datax = mysqli_fetch_array(mysqli_query($koneksi, "SELECT idk,SUM(jumlah) as jml FROM s_barang WHERE idk='$data[id]'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['kategori'] ?></td>													
                                                     <td>
													 <?php if($datax['idk'] !=1): ?>
													 <h5><span class="badge badge-primary"><?= $datax['jml'] ?></span></h5>
													 <?php else : ?>
													 <h5><span class="badge badge-dark"><?= $jdata2 ?></span></h5>
													 <?php endif; ?>
													 </td>
													  
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
                                                <span class="widget-stats-title">BANGUNAN</span>
                                                <span class="widget-stats-amount"><?= $jdata2; ?></span>
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
                                                <span class="widget-stats-title">NON BANGUNAN</span>
                                                <span class="widget-stats-amount"><?= $jdata3; ?> </span>
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