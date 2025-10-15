 <?php 
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$memo = memory_get_usage(true) . "\n"; 
$use = memory_get_usage(false) . "\n";
$ttl = $use+$memo;
$pakai = ($memo/$ttl)*100;
?>

<?php
$df = disk_free_space("C:");
$dt = disk_total_space("C:");
$du = $dt - $df;
$dp = sprintf('%.2f',($du / $dt) * 100);
$df = formatSize($df);
$du = formatSize($du);
$dt = formatSize($dt);

function formatSize( $bytes )
{
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
                return( round( $bytes, 2 ) . " " . $types[$i] );
}

?>
                       <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">storage</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Free Disk</span>
                                                <span class="widget-stats-amount"><?= ($dt-$du); ?> GB</span>
                                                <span class="widget-stats-info" style="color:red;"><?= ($du); ?> GB Used Disk</span>
                                            </div>
                                           
                                        </div>
										 <div class="progress">
                                            <div class="progress-bar bg-danger"  role="progressbar" style="width: <?= ($du/$dt)*100; ?>%;" aria-valuenow="<?= ($du/$dt)*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                              </div>
                                    </div>
                                </div>
								
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">memory</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Free RAM</span>
                                                <span class="widget-stats-amount"><?= round($use/1024000,2); ?> GB</span>
                                                <span class="widget-stats-info" style="color:blue;"><?= round(($use+$memo+512000)/1024000,2); ?> GB Used</span>
                                            </div>
                                            
                                        </div>
										 <div class="progress">
                                            <div class="progress-bar"  role="progressbar" style="width: <?= $pakai; ?>%;" aria-valuenow="<?= ($du/$dt)*100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <span class="widget-stats-title">PESERTA DIDIK</span>
                                                <span class="widget-stats-amount"><?= $jsiswa ?> PD</span><p>
                                                <span class="widget-stats-info"><?= substr($setting['sekolah'],0,17) ?></span>
                                            </div>
                                            <div class="widget-stats-indicator widget-stats-indicator-positive align-self-start">
                                                <i class="material-icons">keyboard_arrow_up</i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                       
										
											 <h5 class="card-title">RESTORE DATABASE</h5>
                                    </div>
                                    <div class="card-body">
                                    
                                        <ul class="widget-list-content list-unstyled">
										
                                            <li class="widget-list-item widget-list-item-green">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">storage</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                      RESTORE DATABASE
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                    <form id='formrestore'>
                                            <p>Klik Tombol dibawah ini untuk merestore database </p>
                                            <div class='col-md-12'>
                                                <input class='form-control' name='datafile' type='file' required />
                                            </div>
											<div class="kanan">
                                               
                                                <button id="blockui-2" class="btn btn-success">RESTORE</button>
                                            </div>
                                        </form>
                                                    </span>
                                                </span>
                                            </li>
											
                                          
                                        </ul>
										</div>
                                    </div>
                                </div>
                           
							<script>
	
						 $('#formrestore').submit(function(e) {
							e.preventDefault();
							var data = new FormData(this);
							//console.log(data);
							$.ajax({
								type: 'POST',
								url: 'pengaturan/crud_setting.php?pg=setting_restore',
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
                            
                            <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title"><?= $setting['sekolah'] ?></h5>
                                    </div>
                                    <div class="card-body">
									
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">ADMIN</span>
                                                    <span class="widget-payment-request-author-about"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                   
                                            </div>
											
                                           
                                        </div>
										
										<p>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>	
