<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
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
	
    ?>
	                  <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SIKAP <span class="badge badge-primary">SOSIAL</span>
										<?php if($kelas<>''){ ?>
										<span class="badge badge-success"><?= $mpl['kode'] ?></span>
										<span class="badge badge-dark"><?= $kelas ?></span>
										<?php } ?>
										</h5>
										<a href="." class="btn btn-light pull-right">BACK</a>
                                    </div>
                                    <div class="card-body">
									<p>Input Deskripsi adalah hasil pengolahan Nilai Harian sesuai KD dengan posisi Nilai Terendah dan Nilai Tertinggi</p>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>KELAS</th>
                                                      <th>NAMA SISWA</th>														
                                                      <th>SELALU DILAKUKAN</th>
													    <th>MULAI MENINGKAT</th>
														<th width="5%">PRED</th>
													  <th>INPUT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php											
											$no=0;											
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'");
											  while ($data = mysqli_fetch_array($query)) :
											 $des = fetch($koneksi,'sosial',['nis'=>$data['nis'],'mapel'=>$mapel,'smt'=>$semester,'guru'=>$guru]);	
											$sos = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sosial WHERE nis='$data[nis]' and smt='$semester' and tp='$tapel'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                      <td><h5><span class="badge badge-dark"><?= $data['kelas'] ?></span></h5></td>
													 <td><?= $data['nama'] ?></td>
													   <td><?= $des['ket1'] ?></td>
													  <td><?= $des['ket2'] ?></td>
													   	<td><?= $des['pred'] ?></td>									 
													  <td>  
													  <?php if($sos==0): ?>
													<a href="?pg=<?= enkripsi('nsikap2') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($data['id_siswa']) ?>&k=<?= enkripsi($kelas) ?>&m=<?= enkripsi($mapel) ?>&g=<?= enkripsi($guru) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Input Nilai"><i class="material-icons">edit</i> </a>																								
													<?php else : ?>
													<button class="btn btn-sm btn-secondary" disabled><i class="material-icons">lock</i></button>
													<?php endif; ?>
													</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
										</div>
										<div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">                                    
										<h5 class="card-title">NILAI SIKAP SOSIAL</h5>									
									</div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">INPUT DESKRIPSI</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<p>
                                             <div class="widget-payment-request-info-item">	
									<label class="bold">Semester</label>
								<div class="input-group mb-2">
								<select name='smt'  class='form-select' required='true' style="width: 100%">
								    <option value="<?= $setting['semester'] ?>"><?= $setting['semester'] ?></option>									
									 </select>
							     </div>															 
                                 <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select level' required='true' style="width: 100%">
								    <option value="">Pilih Tingkat</option>
                                       <?php
										
											$query = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='1' GROUP BY level"); 	
										  
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
									$query = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE sikap='2'"); 	
									else:
									$query = mysqli_query($koneksi, "SELECT jadwal_mapel.mapel,jadwal_mapel.guru,mata_pelajaran.id,mata_pelajaran.nama_mapel,mata_pelajarn.sikap FROM jadwal_mapel JOIN mata_pelajaran ON mata_pelajaran.id=jadwal_mapel.mapel WHERE jadwal_mapel.guru='$user[id_user]' and mata_pelajaran.sikap='2' GROUP BY jadwal_mapel.mapel"); 
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
											location.replace("?pg=<?= enkripsi('nsikap2') ?>&level=" + level + "&kelas=" + kelas + "&mapel=" + mapel + "&guru=" + guru);
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
      
				 <?php elseif ($ac == enkripsi('edit')): ?>
			 <?php include"nilai/radio.php"; ?>
			<?php 
			$id = dekripsi($_GET['id']); 				
			$siswa = fetch($koneksi,'siswa',['id_siswa'=>$id]);
			$kelas = dekripsi($_GET['k']);
			$mapel = dekripsi($_GET['m']);
			$guru = dekripsi($_GET['g']);
			
			 ?>
		
	                <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SIKAP KI-2 <span class="badge badge-secondary"><?= $siswa['nama'] ?></span></h5>
													
                                    </div>
                                    <div class="card-body">
									
									<form id='formmapel3' >
									<input type="hidden" name="nis" value="<?= $siswa['nis']; ?>" >
									<input type="hidden" name="kelas" value="<?= $kelas; ?>" >
									<input type="hidden" name="mapel" value="<?= $mapel; ?>" >
									<input type="hidden" name="guru" value="<?= $guru; ?>" >
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>   
                                                    <th>SELALU DILAKUKAN</th>											
													<th>MULAI MENINGKAT</th>
													<th>PREDIKAT</th>													
													<th width="5%">SIMPAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
												
											   		
                                                <tr>
												
                                                    <td>
													<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="jujur" /> 
														<span class="check"></span>jujur</label>
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="disiplin" /> 
														<span class="check"></span>disiplin</label>
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="tanggung jawab" /> 
														<span class="check"></span>tanggung jawab</label>
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="santun" /> 
														<span class="check"></span>santun</label>			
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="percaya diri" /> 
														<span class="check"></span>percaya diri</label>
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="peduli" /> 
														<span class="check"></span>peduli</label>	
														<label class="checkbox"><input class='hidden radio-label' type='checkbox' name="ket1[]" value="toleransi" /> 
														<span class="check"></span>toleransi</label>
													</td>                                              
													   					   
													  
													    <td>														
											<select name='ket2'  class='form-select' required='true' style="width:100%;">
											 <option value=''>Pilih Sikap</option>
											  <option value='jujur'>Jujur</option>
										   <option value='disiplin'>Disiplin</option>
										    <option value='tanggung jawab'>Tanggung jawab</option>
											 <option value='santun'>Santun</option>
											  <option value='percaya diri'>Percaya diri</option>
											   <option value='peduli'>Peduli</option>
											    <option value='toleransi'>Toleransi</option>
                                                    </select>
														</td>
														<td>
														<select name='pred' id="pred" class='form-select' required='true' style="width:100%;">
														 <option value=''>Pilih Predikat</option>
														<option value='A'>Sangat Baik</option>
														<option value='B'>Baik</option>
														<option value='C'>Cukup</option>
														</select>
													</td>
													 <td>
													 
													 <button type="submit" class="btn btn-primary"><i class="material-icons">edit</i></button>
													 
													 </td>
													
                                                </tr>
												</tbody>
                                                </table>
												</form>
												 </div>
											</div>
										</div>
									
								</div>
							
							<script>
								$('#formmapel3').submit(function(e){
									e.preventDefault();
									var data = new FormData(this);
									$.ajax(
									{
										type: 'POST',
										 url: 'nilai/tspi.php?pg=tambah_sos',
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
										success: function(data){   		
										if (data == 'OK') {		
										
										setTimeout(function()
										{
										   window.location.replace('?pg=<?= enkripsi(nsikap2) ?>&mapel=<?= $mapel ?>&kelas=<?= $kelas ?>&guru=<?= $guru ?>');
										}, 2000);
										 } else {
										 iziToast.warning(
										{
											title: 'Gagal!',
											message: 'Data Tidak boleh sama',
											titleColor: '#FFFF00',
											messageColor: '#fff',
											backgroundColor: '#8b0000',
											 progressBarColor: '#FFFF00',
											  position: 'topRight'
										});
										setTimeout(function(){
											window.location.reload();
										}, 2000);	
									   }			
									}
								});
								return false;
							});	
											
									</script>

    
					  <?php endif ?>
					