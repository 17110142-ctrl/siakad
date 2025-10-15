 <?php 
 defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jumguru = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE nowa<>'' AND level='guru'"));
$jumstaff = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users WHERE nowa<>'' AND level='staff'"));
$jumsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nowa<>''"));


?>


                       <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">GURU PENGGUNA WA</span>
                                                <span class="widget-stats-amount"><?= $jumguru ?></span><p>
                                                <span class="widget-stats-info"><?= $setting['sekolah'] ?></span>
                                            </div>
                                            <div class="widget-stats-indicator widget-stats-indicator-positive align-self-start">
                                                <i class="material-icons">keyboard_arrow_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            <div class="col-xl-4">
                                 <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">STAFF PENGGUNA WA</span>
                                                <span class="widget-stats-amount"><?= $jumstaff ?></span><p>
                                                <span class="widget-stats-info"><?= $setting['sekolah'] ?></span>
                                            </div>
                                            <div class="widget-stats-indicator widget-stats-indicator-positive align-self-start">
                                                <i class="material-icons">keyboard_arrow_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA PENGGUNA WA</span>
                                                <span class="widget-stats-amount"><?= $jumsiswa ?></span><p>
                                                <span class="widget-stats-info"><?= $setting['sekolah'] ?></span>
                                            </div>
                                            <div class="widget-stats-indicator widget-stats-indicator-negative align-self-start">
                                                <i class="material-icons">keyboard_arrow_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-xl-8">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">NOTIF WHATS APP SISWA</h5>
                                    </div>
                                    <div class="card-body">
                                         <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>WHATS APP</th>
                                                    <th>NAMA SISWA</th>
                                                    <th>ROMBEL</th>
                                                    <th>JK</th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nowa<>''"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nowa'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
                                                    <td><?= $data['kelas'] ?></td>
                                                    <td><?= $data['jk'] ?></td>
                                                 
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                          
                             <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">UPDATE WHATS APP</h5>
                                    </div>
                                    <div class="card-body">
									 <form id="formsiswa">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">UPDATE WA</span>
                                                    <span class="widget-payment-request-author-about"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
													<label>File Excel</label>
                                                  <input type='file' name='file'  class='form-control' required="true" />									
                                                    </span>                                                  
                                                </div>                                               
                                            </div>
											
                                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                                <a href="sandik_absen/proses.php" class="btn btn-success flex-grow-1 m-r-xxs">Download Format</a>
                                                <button type="submit" id="blockui-3" class="btn btn-primary flex-grow-1 m-l-xxs">Upload</button>
                                            </div>
                                        </div>
										</form>
										<p>
                                    </div>
                                </div>
                            </div>
                        </div>
                             	
					</div>
                     	<script>
					$('#formsiswa').submit(function(e){
						e.preventDefault();
						var data = new FormData(this);
						$.ajax(
						{
							type: 'POST',
							 url: 'sandik_absen/import_siswa.php',
							data: data,
							cache: false,
							contentType: false,
							processData: false,
							success: function(data){   		
							setTimeout(function()
								{
								window.location.reload();
										}, 2000);
													  
										}
									});
								return false;
							});
						</script>