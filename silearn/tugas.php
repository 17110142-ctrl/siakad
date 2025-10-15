<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>

               <div class="row">
                <?php           
                $tugasQ = mysqli_query($koneksi, "SELECT * FROM tugas");
				while ($tugas = mysqli_fetch_array($tugasQ)) : ?>
                    <?php
                    if ($tugas['tgl_selesai'] > date('Y-m-d H:i:s')) {
                        $datakelas = unserialize($tugas['kelas']);
                        $guru = fetch($koneksi, 'users', ['id_user' => $tugas['id_guru']]);
                    ?>
                        <?php if (in_array($siswa['kelas'], $datakelas) or in_array('semua', $datakelas)) :

                            $warna = array('red', 'blue',  'green', 'gray', 'purple', 'black');

                        ?>
                               <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">TUGAS BELAJAR</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container" >
                                            <div class="widget-payment-request-author" >
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/icon/buku.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $tugas['mapel'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $tugas['judul'] ?></span>
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
                                                    <span class="widget-payment-request-info-title d-block">
                                                        MATERI TUGAS
                                                    </span>
                                                    <span class="text-muted d-block"><?= substr($tugas['tugas'],0 , 30) ?>....</span>
													
                                                </div>
                                                <div class="widget-payment-request-info-item">                              
                                                    <span class="text-muted d-block">MULAI &nbsp;&nbsp;&nbsp;&nbsp;<?= $tugas['tgl_mulai'] ?></span>													
                                                    <span class="text-muted d-block">SELESAI <?= $tugas['tgl_selesai'] ?></span>
                                                </div>
                                            </div>
                                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                                <a href="#" class="btn btn-light flex-grow-1 m-r-xxs">Reject</a>
												<?php if ($tugas['tgl_mulai'] > date('Y-m-d H:i:s') and $tugas['tgl_selesai'] > date('Y-m-d H:i:s')) { ?>
                                                TUGAS BELUM MULAI
                                            <?php } elseif ($tugas['tgl_mulai'] < date('Y-m-d H:i:s') and $tugas['tgl_selesai'] > date('Y-m-d H:i:s')) { ?>
                                                <a href="?pg=bukatugas&id=<?= $tugas['id_tugas'] ?>" class="btn btn-primary flex-grow-1 m-l-xxs">BUKA TUGAS</a>
                                             <?php } ?>
											</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<?php else: ?>
							<div class="alert alert-light" role="alert">
                                    Tidak ada Tugas pada hari ini
                                    </div>
						<?php endif; ?>
						<?php }else{ ?>
							<div class="alert alert-light" role="alert">
                                    Tidak ada Tugas pada hari ini
                                    </div>
						<?php } ?>
                <?php endwhile; ?>
                </div> 