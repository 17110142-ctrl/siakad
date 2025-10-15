                               <?php
							require("../config/koneksi.php");
							require("../config/function.php");
							require("../config/crud.php");
							$sql = mysqli_query($koneksi, "select * from status");
								$sts = mysqli_fetch_assoc($sql);
							?>  
                        
							   <div class="card widget widget-list">                        
                                    <div class="card-body">                                      
                                        <ul class="widget-list-content list-unstyled">
										<?php
										$query = mysqli_query($koneksi, "SELECT * FROM pesan_terkirim WHERE ket<>'' ORDER BY id DESC LIMIT 4"); 
										while ($data = mysqli_fetch_array($query)) :
										 $peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
										 $sis = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
										$no++;
										?>
										
                                            <li class="widget-list-item widget-list-item-green">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">wifi</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
													<?php if($data['idpeg']==$peg['id_user']): ?>
                                                        <?= $peg['nama'] ?>
														<?php endif; ?>
														<?php if($data['idsiswa']==$sis['id_siswa']): ?>
                                                        <?= $sis['nama'] ?>
														<?php endif; ?>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= $data['waktu'] ?>
                                                    </span>
													<span class="badge badge-primary kanan">
                                                      <?php if($data['ket']==1): ?>
													  Absen Masuk
													  <?php else: ?>
													  Absen Pulang
													  <?php endif; ?>
                                                    </span>
                                                </span>
                                            </li>
											
											
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>
                                </div>
								