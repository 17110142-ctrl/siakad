							<?php
							defined('APK') or exit('No Access');
							$id = $_GET['id'];
							$materi = mysqli_fetch_array(mysqli_query($koneksi, "select * from materi where id_materi='$id'"));
							$guru = fetch($koneksi, 'users', ['id_user' => $materi['id_guru']]);
							function youtube($url)
							{
								$link = str_replace('http://www.youtube.com/watch?v=', '', $url);
								$link = str_replace('https://www.youtube.com/watch?v=', '', $link);
								$data = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $link . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
								return $data;
							}
							 $where = array(
								'idsiswa' => $_SESSION['id_siswa'],
								'idmateri' => $id
										);
							$datax = array(
								'idmateri' => $id,
								'mapel'=> $materi['mapel'],
								'idsiswa' => $_SESSION['id_siswa'],
								'tanggal' => date('Y-m-d'),
								'jam' => date('H:i:s'),
								'bulan'=> date('m'),
								'ket' => 'H',
								'guru'=> $materi['id_guru'],
								'tahun'=> date('Y')
								  );			
							$cek = rowcount($koneksi, 'absen_daringmapel', $where);
							if ($cek == 0) {
							  insert($koneksi, 'absen_daringmapel', $datax);
									}
								$warna = array('red', 'blue',  'green', 'gray', 'purple', 'black');
							?>

							<div class="row">            
                               <div class="col-xl-7">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">MATERI BELAJAR</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container" >
                                            <div class="widget-payment-request-author" >
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/icon/buku.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $materi['mapel'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $materi['judul'] ?></span>
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
                                                 <div class="widget-payment-request-info">
                                                
													<?php if($materi['file']==''): ?>
													<font style="color:red;">Tidak ada File Download</font>
													
													<?php else: ?>
													<br>
													<a href="../materi/<?= $materi['file'] ?>" target="_blank" class="btn btn-sm btn-link kanan">Download</a>
													
													<?php endif; ?>
													
                                            </div>
                                           <?php 
									if(!empty($materi['file'])){
										$pecah=explode('.',$materi['file']);
										$ekstensi=$pecah[1];
									?>
									<?php if($ekstensi=='mp4'){ ?>
									<video src="<?= $homeurl ?>/materi/<?= $materi['file'] ?>" controls autoplay width="100%" height="315"></video>
									 <?php } ?>
									 <?php if($ekstensi=='jpg' OR $ekstensi=='png'){ ?>
									<img src="<?= $homeurl ?>/materi/<?= $materi['file'] ?>" controls autoplay width="100%" height="315">
									 <?php } ?>
									 <?php if($ekstensi=='pdf'){ ?>
									<iframe  src="<?= $homeurl ?>/materi/<?= $materi['file'] ?>" controls autoplay width="100%" height="315"></iframe>
									<?php } ?>
									 <?php if($ekstensi=='docx'){ ?>
									<iframe src="http://docs.google.com/viewer?url=berkas/<?= $materi['file'] ?> 
									&embedded=true"  width="100%" height="315" controls autoplay style="border: none;"></iframe>

									<?php } ?>
									  <?php } ?>
									<center>
										<div class="callout">
											<strong>
												<h3><?= $materi['judul'] ?></h3>
											</strong>
										</div>
									</center>
									<?php if ($materi['youtube'] <> null) {  ?>
										<div class="col-md-3"></div>
										<div class="callout col-md-6">
											<?= youtube($materi['youtube']) ?>
										</div>
										<div class="col-md-3"></div>
									<?php } ?>
									<div class="col-md-12">
										<?= $materi['materi'] ?>
									</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-xl-5">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">KOMENTAR</h5>
                                    </div>
                                    <div class="card-body">
									<p>Jika ada materi yang tidak paham silahkan kirim pertanyaan</p>
									<form id="formpesan">								
									<input type='hidden' name='id_materi' value="<?= $materi['id_materi'] ?>" >
									<input type='hidden' name='guru' value="<?= $materi['id_guru'] ?>" >
									<textarea id='editor2' name='komentar' class='editor1' rows='5' cols='80' style='width:100%;' required="true"></textarea>
									<div class="kanan">
									  <button type="submit" name="submit" class="btn btn-primary">Kirim</button>
									</div>
								</form>
								</div>
								 <div class="card-body">
								        <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM komentar where id_materi='$materi[id_materi]' and id_user='$id_siswa' and jenis='1'"); 
											while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
								<div class="widget-connection-request-container d-flex" >
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-xs m-r-xs">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:green;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    <?= $data['komentar'] ?>
                                                </span>
                                                <br><small style="color:#fff;margin-left:10px;"><?= $data['tgl'] ?></small>
                                            </div>
                                        </div>
											<br>
											<?php if($data['balasan'] !=''): ?>
								         <div class="widget-connection-request-container d-flex" >
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-xs m-r-xs">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:yellow;">
                                                <span class="widget-connection-request-info-name" style="margin-left:5px;">
                                                    <?= $data['balasan'] ?>
                                                </span>
                                                <br><small style="margin-left:10px;"><?= $guru['nama'] ?></small>
                                            </div>
                                        </div>
										<?php endif; ?>
											<?php endwhile; ?>
									</div>
									</div>
								</div>
							</div>
	<script>
    $('#formpesan').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'tkomen.php?pg=tambah',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><label class="sandik" style="color:white;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},			
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