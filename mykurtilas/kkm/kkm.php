<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-xl-8">
						   <div class="row">
						  <?php
						  $kur = mysqli_query($koneksi, "SELECT * FROM kelas WHERE kurikulum='1'");
							while ($kurik = mysqli_fetch_array($kur)) {
							?>
                                 <div class="col-xl-6">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">KKM TINGKAT <?= $kurik['level'] ?></span>
												 <span class="widget-stats-title">
												<?php if($kurik['model_kkm']==''): ?>
												<h5><span class="badge badge-secondary">BELUM ADA</span></h5>
												<?php else: ?>
												<h5><span class="badge badge-secondary"><?= $kurik['model_kkm']; ?></span></h5>
												<?php if($kurik['model_kkm']=='Multi'){ ?>
                                                <a href="?pg=<?= enkripsi('kkm') ?>&ac=<?= enkripsi('multi') ?>&lv=<?= $kurik['level'] ?>" class="btn btn-sm btn-primary"><i class="material-icons">add</i>INPUT KKM</a>
												<?php }else{ ?>
												  <a href="?pg=<?= enkripsi('kkm') ?>&ac=<?= enkripsi('single') ?>&lv=<?= $kurik['level'] ?>"class="btn btn-sm btn-primary"><i class="material-icons">add</i>INPUT KKM</a>
												<?php } ?>
												<?php endif; ?>
												 </span>
                                                <span class="widget-stats-amount">
												
												</span><p>
                                               
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
								
                            </div>
							
										<?php } ?>
									
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
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?>.</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									<form id='formjadwal' class="row g-1">                         
                               <div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select' required='true' style="width: 100%">
								    <option value="1">K-2013</option>
									 </select>
							     </div>	
								  <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Tingkat</option>
										<?php
										$lev = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='1' GROUP BY level");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[level]'>$level[level]</option>";
										}
										?>
									 </select>
							     </div>	
							             <div class="col-md-12">
								<label class="form-label bold">Model KKM</label>
								 <select name='model' id="model" class='form-select' style='width:100%' required='true'>
								    <option value="">Pilih Model</option>
									  <option value="Single">Single</option>
									  <option value="Multi">Multi</option>
								  </select>		
							     </div>	
						         
							          <p>
								           <div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
										
									 </div>
					               </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

							<script>
						$('#formjadwal').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'kkm/tkkm.php?pg=model',
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
							
							
							
                   <?php elseif($ac == enkripsi('multi')): ?>
				      <?php $tingkat = $_GET['lv'] ?>
				    <div class="row">
                         <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">MUTI KKM TINGKAT <?= $tingkat ?></h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>TKT</th> 
                                                      <th>JURUSAN</th>													
													 <th>MAPEL</th>
													 <th>GROUP</th>
													  <th>URUT</th>
													  <th>KKM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$_GET[lv]' ORDER BY idm ASC"); 
											  while ($data = mysqli_fetch_array($query)) :
										 $map = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['tingkat'] ?></td>
                                                      <td><?= $data['pk'] ?></td>
													  <td><?= $map['kode'] ?></td>
													  <td><?= $data['kelompok'] ?></td>
													  <td><?= $data['urut'] ?></td>
													  <td><?= $data['kkm'] ?></td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
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
                                                    <span class="widget-payment-request-author-name">MULTI KKM</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									<form id='formmulti' class="row g-1">                         
                               <div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select' required='true' style="width: 100%">
								    <option value="1">K-2013</option>
									 </select>
							     </div>	
								  <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								   <option value="<?= $_GET['lv'] ?>"><?= $_GET['lv'] ?></option>
										
									 </select>
							     </div>	
								  <div class="col-md-12">
								<label class="form-label bold">Jurusan</label>
								<select name='pk' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Jurusan</option>
										<?php
									$jQ = mysqli_query($koneksi, "SELECT jurusan FROM siswa  GROUP BY jurusan");
									while ($jrs = mysqli_fetch_array($jQ)) :
									echo "<option value='$jrs[jurusan]'>$jrs[jurusan]</option>";
										endwhile;
										?>
										</select>
							</div> 
								 <div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select' required='true' style="width: 100%">
								   <option value=''>Pilih Mapel</option>
								   <?php
									$mpl = mysqli_query($koneksi, "SELECT * FROM mapel_rapor a JOIN mata_pelajaran b ON b.id=a.mapel where a.kurikulum='1'");
									while ($mapel = mysqli_fetch_array($mpl)) :
									echo "<option value='$mapel[mapel]'>$mapel[nama_mapel]</option>";
										endwhile;
										?>
									 </select>
							</div>
							             <div class="col-md-12">
								<label class="form-label bold">KKM</label>
								 <input type="number" name="kkm" class="form-control" required="true">
							          <p>
								           <div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
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
						$('#formmulti').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'kkm/tkkm.php?pg=multikkm',
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
				   
				   <?php elseif($ac == enkripsi('single')): ?>
				   <?php $tingkat = $_GET['lv'] ?>
	                  <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">SINGLE KKM TINGKAT <?= $tingkat ?></h5>
										
                                    </div>
                                    <div class="card-body">
									<b>Pastikan Mapel Rapor sudah diinput semua</b><p>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>TKT</th>
                                                   <th>JURUSAN</th>
													 <th>MAPEL</th>
													 <th>GROUP</th>
													  <th>URUT</th>
													  <th>KKM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$tingkat' ORDER BY idm ASC"); 
											  while ($data = mysqli_fetch_array($query)) :
										    $map = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['tingkat'] ?></td>
                                                      <td><?= $data['pk'] ?></td>
													  <td><?= $map['kode'] ?></td>
													  <td><?= $data['kelompok'] ?></td>
													  <td><?= $data['urut'] ?></td>
													  <td><?= $data['kkm'] ?></td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
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
                                                    <span class="widget-payment-request-author-name">SINGLE KKM</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									<form id='formsingle' class="row g-1">                         
                               <div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select' required='true' style="width: 100%">
								    <option value="K-2013">K-2013</option>
									 </select>
							     </div>	
								  <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value="<?= $_GET['lv'] ?>"><?= $_GET['lv'] ?></option>
										
									 </select>
							     </div>	
								<div class="col-md-12">
								<label class="form-label bold">Jurusan</label>
								<select name='pk' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Jurusan</option>
										<?php
									$jQ = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
									while ($jrs = mysqli_fetch_array($jQ)) :
									echo "<option value='$jrs[jurusan]'>$jrs[jurusan]</option>";
										endwhile;
										?>
										</select>
							     </div>	
							             <div class="col-md-12">
								<label class="form-label bold">KKM</label>
								 <input type="number" name="kkm" class="form-control" required="true">
							          <p>
								           <div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
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
						$('#formsingle').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'kkm/tkkm.php?pg=kkm',
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
					  <?php endif ?>
					  
		  
					  
					  