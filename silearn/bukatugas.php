							<?php
							defined('APK') or exit('No Access');
							$id = $_GET['id'];
							$tugas = mysqli_fetch_array(mysqli_query($koneksi, "select * from tugas where id_tugas='$id'"));
							$guru = fetch($koneksi, 'users', ['id_user' => $tugas['id_guru']]);
							
							 $where = array(
								'idsiswa' => $_SESSION['id_siswa'],
								'idtugas' => $id
										);
							$datax = array(
								'idtugas' => $id,
								'mapel'=> $tugas['mapel'],
								'idsiswa' => $_SESSION['id_siswa'],
								'tanggal' => date('Y-m-d'),
								
								'bulan'=> date('m'),
								'ket' => 'H',
								'guru'=> $tugas['id_guru'],
								'tahun'=> date('Y')
								  );			
							$cek = rowcount($koneksi, 'absen_daringmapel', $where);
							if ($cek == 0) {
							  insert($koneksi, 'absen_daringmapel', $datax);
									}
								$warna = array('red', 'blue',  'green', 'gray', 'purple', 'black');
							?>

							<div class="row">            
                               <div class="col-xl-6">
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
                                                 <div class="widget-payment-request-info">
                                                
													<?php if($tugas['file']==''): ?>
													<font style="color:red;">Tidak ada File Download</font>
													
													<?php else: ?>
													<br>
													<a href="../tugas/<?= $tugas['file'] ?>" target="_blank" class="btn btn-sm btn-link kanan">Download</a>
													
													<?php endif; ?>
													
                                            </div>
											<br>
                                            <center>
                                    <h3><?= $tugas['judul'] ?></h3>
                                </center>
                                <p><?= $tugas['tugas'] ?></p>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-xl-6">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">KIRIM JAWABAN</h5>
                                    </div>
                                    <div class="card-body">
									<?php if ($tugas['file'] <> null) {
								$pecah=explode('.',$tugas['file']);
						$ekstensi=$pecah[1];
					?>
					<?php if($ekstensi=='mp4'){ ?>
					<video src="<?= $homeurl ?>/tugas/<?= $tugas['file'] ?>" controls autoplay width="100%" height="315"></video>
					 <?php } ?>
					 <?php if($ekstensi=='jpg' OR $ekstensi=='png'){ ?>
					<img src="<?= $homeurl ?>/tugas/<?= $tugas['file'] ?>" controls autoplay width="100%" height="315">
					 <?php } ?>
					 <?php if($ekstensi=='pdf'){ ?>
					<iframe  src="<?= $homeurl ?>/tugas/<?= $tugas['file'] ?>" controls autoplay width="100%" height="315"></iframe>
					 <?php } ?>
                                <?php } ?>
                               
									<?php
									$kondisi = array(
										'id_siswa' => $_SESSION['id_siswa'],
										'id_tugas' => $tugas['id_tugas']
										
										
									);
									$jawab_tugas = fetch($koneksi, 'jawaban_tugas', $kondisi);
									if ($jawab_tugas) {
										$jawaban = $jawab_tugas['jawaban'];
									} else {
										$jawaban = "";
									}
									?>
                            <?php if ($jawab_tugas['nilai'] <> '') { ?>
                               
                                <h4>Nilai Kamu : <?= $jawab_tugas['nilai'] ?></h4>
                            <?php } else { ?>
                                   
                               
									<form id='formjawaban'>
                                    <input type="hidden" name="id_tugas" value="<?= $tugas['id_tugas'] ?>">
                                    <input type="hidden" name="nama_mapel" value="<?= $tugas['mapel'] ?>">
                                    <div class="form-group">
                                        <label class="bold">Lembar Jawaban</label>
                                        <textarea class="form-control" name="jawaban" id="txtjawaban" rows="10"><?= $jawaban ?></textarea>
                                    </div><p>
                                    <?php if ($jawab_tugas['file'] == '') { ?>
                                        <div class="form-group">
                                            <p class="bold">Jika jawaban diupload</p>
											<label class="bold">Upload</label>
                                            <input type="file" class="form-control-file" name="file" aria-describedby="fileHelpId">
                                           <p></p>
                                        </div>
                                    <?php } else { ?>

                                        <div class="alert alert-success" role="alert">
                                            <strong>File jawaban berhasil dikirim</strong>
                                            <a href='<?= $homeurl ?>/tugas/<?= $jawab_tugas['file'] ?>' target="_blank">Lihat file</a>
                                        </div>

                                    <?php  } ?>

                                    <div class="kanan">
                                        <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
                                    </div>
                                </form>
                            <?php  } ?>
									  </div>
									</div>
								</div>
							</div>
	<script>
    $('#formjawaban').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		console.log([...data.entries()]); 
		$.ajax(
		{
			type: 'POST',
             url: 'simpantugas.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
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