<?php
defined('APK') or exit('No Access');

?>           <?php if ($ac == '') : ?>
	        <div class="alert alert-dark" role="alert">
                  Asesmen Sumatif Akhir Lingkup Materi bertujuan untuk mengevaluasi hasil belajar siswa pada tiap lingkup materi. Hasil Asesmen Sumatif Akhir Lingkup Materi digunakan
                      <b>Untuk Pengolahan Nilai pada Laporan Hasil Belajar Siswa (Rapor).</b> 
				<?php
	if (empty($_GET['level'])) {
        $tingkat = "";
    } else {
        $tingkat = $_GET['level'];
    }			
    if (empty($_GET['kelas'])) {
        $kelas = "";
    } else {
        $kelas = $_GET['kelas'];
    }
     if (empty($_GET['mapel'])) {
        $mapel = "";
    } else {
        $mapel = $_GET['mapel'];
    }
	  if (empty($_GET['guru'])) {
        $guru = "";
    } else {
        $guru = $_GET['guru'];
    }
	$mpl = fetch($koneksi,'mata_pelajaran',['id'=>$mapel]);
	$jumlah = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$mapel' AND level='$tingkat' and guru='$guru' and smt='$semester'"));
    ?>
	
				   </div>	
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SUMATIF <span class="badge badge-primary"><?= $mpl['kode'] ?> <?= $kelas ?></span></h5>
										<div class="pull-right">
										<?php if($kelas<>''): ?>
										
										<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#example"><i class="material-icons">upload</i>Upload</button>
                                    
									<?php endif; ?>
									<a href="." class="btn btn-light">Back</a>
									</div>
									</div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>NIS</th>													                           
                                                    <th>NAMA SISWA</th>
													<?php for($i=1;$i < $jumlah+1; $i++):  ?>
													 <th>LM - <?= $i ?></th>
													 <?php endfor; ?>
													 
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'"); 				
											  while ($data = mysqli_fetch_array($query)) :
											 $sum = fetch($koneksi,'nilai_sumatif',['nis'=>$data['nis']]);
											 
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                     <td><?= $data['nis'] ?></td>
                                                      <td><?= $data['nama'] ?></td>
													  <?php
													$qu = mysqli_query($koneksi, "SELECT * FROM nilai_sumatif WHERE nis='$data[nis]' AND mapel='$mapel' AND khp='SAM' and guru='$guru' and semester='$setting[semester]' and tp='$setting[tp]'"); 				
													  while ($dt = mysqli_fetch_array($qu)){
													    ?>
													   <td><?= $dt['nilai'] ?></td>
													  <?php } ?>
													   
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
												 <div class="modal fade" id="example" tabindex="-1" aria-labelledby="example" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5>NILAI SUMATIF <span class="badge badge-secondary"><?= $kelas ?></span></h5>
                                                                 <a href="nilai/prosessumatif.php?m=<?= $mapel ?>&k=<?= $kelas ?>" class="btn-link"><b>Download Format</b></a>
                                                            </div>
                                                            <div class="modal-body">
													 <div class="alert alert-dark" role="alert">                   
													  Asesmen Sumatif Akhir Lingkup Materi bertujuan untuk mengevaluasi hasil belajar siswa pada tiap lingkup materi. Hasil Asesmen Sumatif Akhir Lingkup Materi digunakan
                                                  <b>Untuk Pengolahan Nilai pada Laporan Hasil Belajar Siswa (Rapor).</b>
													  </div>
											          <form id="form-import" action=''>
								                          <input type="hidden" name="guru" value="<?= $guru ?>" >
													<input type="hidden" name="mapel" value="<?= $mapel ?>" >
														<input type="hidden" name="level" value="<?= $tingkat ?>" >
													<div class='col-md-12'>
														<label>Pilih File</label>
														<input type='file' name='file' class='form-control' required='true' />
														
													</div>
													
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
                                                                <button type="submit" class="btn btn-primary">SIMPAN</button>
                                                            </div>
															 </form>
                                                        </div>
                                                    </div>
                                                </div>
											</div>
										</div>
										
									</div>
									
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                  
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									 <div class="d-grid gap-2">
									<button class="btn btn-primary" type="button" disabled>
										<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
										 INPUT NILAI SUMATIF LM
									</button>
											</div>
								
                               <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select level' required='true' style="width: 100%">
								    <option value="">Pilih Tingkat</option>
                                       <?php
										
											$query = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='2' GROUP BY level"); 	
										  
										while ($tkt = mysqli_fetch_array($query)) {										
										echo "<option value='$tkt[level]'>$tkt[level]</option>";
													}
													?>						
									 </select>
							     </div>	
							    <div class="col-md-12">
								<label class="form-label bold">Pilih Rombel</label>
								<select name='kelas' id='kelas' class='form-select kelas' required='true' style="width: 100%">         
								
								 </select>
							     </div>
							<div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select mapel' required='true' style="width: 100%">
								 <?php
								   if($user['level']=='admin'):
									$query = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"); 	
									else:
									$query = mysqli_query($koneksi, "SELECT jadwal_mapel.mapel,jadwal_mapel.guru,mata_pelajaran.id,mata_pelajaran.nama_mapel FROM jadwal_mapel JOIN mata_pelajaran ON mata_pelajaran.id=jadwal_mapel.mapel WHERE jadwal_mapel.guru='$user[id_user]' GROUP BY jadwal_mapel.mapel"); 
									 endif;
										echo "<option value=''>Pilih Mapel</option>";
										while ($mpl = mysqli_fetch_array($query)) {										
										echo "<option value='$mpl[id]'>$mpl[nama_mapel]</option>";
													}
													?>						
									 </select>
									
							</div>
							
								 <div class="col-md-12">
								<label class="form-label bold">Guru Pengampu</label>
								<select name="guru" class='form-select guru' required='true' style="width: 100%" >
										 <?php
										if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru'"); 
											echo "<option value=''>Pilih Guru</option>";
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru' and id_user='$user[id_user]'"); 	
										  endif;
										while ($guru = mysqli_fetch_array($query)) {										
										echo "<option value='$guru[id_user]'>$guru[nama]</option>";
													}
													?>	
												</select>
							          </div><p>
									  
								           <div class="d-grid gap-2">
                                         <button  class="btn btn-primary flex-grow-1 m-l-xxs" id="cari"> PILIH</button>
                                          <script type="text/javascript">
										$('#cari').click(function() {
											 var level = $('.level').val();
											var kelas = $('.kelas').val();
											var mapel = $('.mapel').val();
											 var guru = $('.guru').val();
											location.replace("?pg=<?= enkripsi('sumlm') ?>&level=" + level + "&kelas=" + kelas + "&mapel=" + mapel + "&guru=" + guru);
										}); 
									</script>
                                            </div>
									
										</div>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
							<script>
						$('#form-import').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'nilai/crud_sumatif.php',
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
								})
								return false;
							});
							</script>
							<script>	
							$("#level").change(function() {
							var level = $(this).val();
							console.log(level);
							$.ajax({
							type: "POST",
							url: "nilai/tnilai.php?pg=kelas", 
							data: "level=" + level, 
							success: function(response) { 
							$("#kelas").html(response);
							
									}
								});
							});
							</script>
							
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					