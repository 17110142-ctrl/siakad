
<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
$sql = mysqli_query($koneksi, "select * from status");
	$sts = mysqli_fetch_assoc($sql);
?>  
<?php if($sts['mode']=='1'): ?>
 <h5 class="card-title" style="color:blue;"><small>ABSEN MASUK</small></h5>
  <button class="btn btn-sm btn-primary pull-right" type="button" disabled>
	<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
	<?= $setting['mesin'] ?>
	</button>
											
<?php
	$query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' and level='pegawai' and ket='H' ORDER BY id DESC LIMIT 1"); 
				$cek = mysqli_num_rows($query);
				if ($cek >= 1) {
				while ($data = mysqli_fetch_array($query)) :
				 $peg = fetch($koneksi,'pegawai',['id_user'=>$data['idpeg']]);
				 if($data['ket']=='H'){$info='Hadir';} if($data['ket']=='S'){$info='Sakit';} 
				 if($data['ket']=='I'){$info='Izin';} if($data['ket']=='A'){$info='Alpha';}
				$no++;
                ?>

							   <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
											<center>
                                               
												<?php if($peg['foto']==''){ ?>
                                                    <img src="../images/user.png" alt="" class="responsive">
												<?php }else{ ?>
													 <img src="../images/fotoguru/<?= $peg['foto'] ?>" alt="" class="responsive">
												<?php } ?>
												
												</center>
                                                <div class="widget-payment-request-author-info">
												  <center>
												  <h3><span class="badge badge-light"><?= ucwords(strtolower($peg['nama'])); ?></span></h3>                                                
													<span>JAM ABSEN <?= $data['masuk']; ?></span>
													</center>
                                               
												</div>
											 </div>
                                            </div>
											
											 <center>
                                        <h5><span class="badge badge-secondary"><?= $data['keterangan']; ?></span></h5>
										</center>
												<?php endwhile; ?>
				                               
												 <?php } ?>
												 
												 
						<?php else: ?>		
               <h5 class="card-title" style="color:red;"><small>ABSEN PULANG</small></h5>
			   
						<?php
	         $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND pulang<>'' AND ket='H' and level='pegawai' ORDER BY pulang DESC LIMIT 1"); 		
				while ($data = mysqli_fetch_array($query)) :
				$guru = fetch($koneksi,'pegawai',['id_user'=>$data['idpeg']]);
				
				$no++;
                ?>

							   <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                              <center>
												<?php if($guru['foto']==''){ ?>
                                                    <img src="../images/user.png" alt="" class="responsive">
												<?php }else{ ?>
													 <img src="../images/fotoguru/<?= $guru['foto'] ?>" alt="" class="responsive">
												<?php } ?>
												</center>
												
                                                </div>
                                                <div class="widget-payment-request-author-info">
												 <center>
												  <h3><span class="badge badge-light"><?= $guru['nama']; ?></span></h3>                                                
													<span>JAM ABSEN <?= $data['pulang']; ?></span>
													<p>
													<b style="color:blue">Hati hati dalam perjalanan, Sampai jumpa esok hari</b>
													</center>
												</div>
											 </div>
                                           
									<?php endwhile; ?>
				                 			 
						  <?php endif; ?>