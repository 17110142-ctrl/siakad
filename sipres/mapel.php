<?php 
defined('APK') or exit('No Access');
$hari = date('D');
?>

               <div class="row">
                <?php           
                $mapelQ = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari' and kelas='$siswa[kelas]'");
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