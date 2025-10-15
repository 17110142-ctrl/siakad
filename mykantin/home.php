<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$kate = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori"));
if($user['level']=='admin'): 
$prod = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk"));
else:
$prod = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk WHERE produk_toko='$user[idtoko]'"));
endif;
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
?>

<?php include"top.php"; ?>
                      <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">category</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">KATEGORI PRODUK</span>
                                                <span class="widget-stats-amount"><?= $kate; ?> </span>
                                              
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
                                                <i class="material-icons-outlined">inventory</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">DATA PRODUK</span>
                                                <span class="widget-stats-amount"><?= $prod; ?></span>
                                                
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
                                                <span class="widget-stats-title">COSTUMER</span>
                                                <span class="widget-stats-amount"><?= $jsiswa ?></span>
                                               
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
						<div class="row">
						<div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">STOK BARANG</h5>
										
                                    </div>
                                    <div class="card-body">
									
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>NAMA PRODUK</th>
													
													<th>JML</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM produk where produk_jumlah>5"); 
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM produk where produk_jumlah>5 AND produk_toko='$user[idtoko]'"); 
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['produk_nama'] ?></td>
                                                      
													 <td><h5><span class="badge badge-dark"><?= $data['produk_jumlah'] ?></span></h5></td>
                                                </tr>
												<?php endwhile; ?>
												<tbody>
                                              </table>
										</div>
                                    </div>	
                                </div>
                            
							<div class="col-md-6">                                                   	
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">BARANG HABIS</h5>
                                    </div>
                                    <div class="card-body">
									
                                        <table id="datatables" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>NAMA PRODUK</th>
													<th>JML</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM produk where produk_jumlah<6"); 
											 else:
												$query = mysqli_query($koneksi, "SELECT * FROM produk where produk_jumlah<6 AND produk_toko='$user[idtoko]'"); 
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $data['produk_nama'] ?></td>
												<td><h5><span class="badge badge-danger"><?= $data['produk_jumlah'] ?></span></h5></td>
                                                </tr>
												<?php endwhile; ?>
												<tbody>
                                              </table>
                                   
                                </div>
							</div>
						</div>	
                      </div>  
				  </div>
               </div>        
			</div>		  