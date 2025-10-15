<?php
defined('APK') or exit('No Access');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">INPUT KOMPETENSI KETERAMPILAN</h5>
										
                                    </div>
                                    <div class="card-body">									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>SMT</th>
													<th>TKT</th>													                                                 
                                                    <th>MATA PELAJARAN</th>
													 <th>GURU PENGAMPU</th>
													 <th>JML KD</th>
													  <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where kuri='1'");
											elseif($user['level']=='guru'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where guru='$user[id_user]' and kuri='1' GROUP BY mapel,tingkat");
											endif;
											while ($data = mysqli_fetch_array($query)) :
											 $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											 $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											 $jumdes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM deskripsi where mapel='$data[mapel]' and level ='$data[tingkat]' and guru='$data[guru]' and ki='KI4' and smt='$setting[semester]'"));
											  $no++; 
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><h5><span class="badge badge-dark"><?= $setting['semester']; ?></span></h5></td>
                                                    <td><?= $data['tingkat'] ?></td>
													<td><?= $mapel['nama_mapel'] ?></td>
													<td><?= $guru['nama'] ?></td>
													<td><h5><span class="badge badge-success"><?= $jumdes; ?></span></h5></td>
													<td><a href="?pg=<?= enkripsi('ki4') ?>&ac=<?= enkripsi('input') ?>&l=<?= enkripsi($data['tingkat']) ?>&m=<?= enkripsi($data['mapel']) ?>&g=<?= enkripsi($data['guru']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Materi"><i class="material-icons">select_all</i> </a></td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										  </div>
										</div>
									</div>
								</div>
					<?php elseif ($ac == enkripsi('input')): ?>
                    	<?php
							   $mapel = dekripsi($_GET['m']);
							   $tingkat = dekripsi($_GET['l']);
							   $guru = dekripsi($_GET['g']);
							   $jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM deskripsi where mapel='$mapel' and level ='$tingkat' and guru='$guru' and ki='KI4' and smt='$setting[semester]'"));
							   $jumlah = $jml + 1;
							   $mpl = fetch($koneksi,'mata_pelajaran',['id'=>$mapel]);
							   $peg = fetch($koneksi,'users',['id_user'=>$guru]);
								?>				 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold"><?= $mpl['kode'] ?></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>  	
													<th width="10%">TKT</th>
													<th width="10%">KD</th>	
                                                    <th>DESKRIPSI</th>													
													 <th width="20%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM deskripsi where guru='$guru' and mapel='$mapel' and level='$tingkat' and smt='$setting[semester]' and ki='KI4'"); 
											while ($data = mysqli_fetch_array($query)) :													  
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $data['level'] ?></td>
													<td><?= $data['kd'] ?></td>
													<td><?= $data['deskripsi'] ?></td>
													  <td>
											<a href="?pg=<?= enkripsi('ki4') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($data['id']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit LM"><i class="material-icons">edit</i> </a>											
											<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
											</td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									<script>
									$('#datatable1').on('click', '.hapus', function() {
									var id = $(this).data('id');
									console.log(id);
									swal({
											  title: 'Yakin hapus data?',
											  text: "You won't be able to revert this!",
											  type: 'warning',
											  showCancelButton: true,
											  confirmButtonColor: '#3085d6',
											  cancelButtonColor: '#d33',
											  confirmButtonText: 'Ya, Hapus!',
											  cancelButtonText: "Batal"				  
									}).then((result) => {
										if (result.value) {
											$.ajax({
											   url: 'kurtilas/tdeskrip.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);
													setTimeout(function() {
														window.location.reload();
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script> 
							
					       <div class="col-md-4">
							  
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                       <h5 class="bold">KOMPETENSI KETERAMPILAN</h5>
										
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
									<form id='formdeskrip' >	
									<input type="hidden" name="jumlah" value="<?= $jumlah ?>" >
										 <label class="bold">Semester</label>
									  <div class="input-group mb-1">
                                        <select name='smt' class='form-select' style='width:100%' required>
                                           <option value="<?= $setting['semester'] ?>">Semester <?= $setting['semester'] ?></option>                               
										</select>
                                        </div>
										<label class="bold">Tahun Pelajaran</label>
									  <div class="input-group mb-1">
                                        <select name='smt' class='form-select' style='width:100%' required>
                                           <option value="<?= $setting['tp'] ?>"><?= $setting['tp'] ?></option>                               
										</select>
                                        </div>
										<label class="bold">Tingkat</label>
									  <div class="input-group mb-1">
									   <select name='level' class='form-select' style='width:100%' required>                                         
                                           <option value="<?= $tingkat ?>"><?= $tingkat ?></option>                                            
                                            </select>
                                        </div>
										<label class="bold">Mata Pelajaran</label>
									  <div class="input-group mb-1">
                                        <select name='mapel' class='form-select' style='width:100%' required>
										<option value="<?= $mapel ?>"><?= $mpl['nama_mapel'] ?></option>                                           
                                            </select>
                                        </div>
										 <label class="bold">Guru Pengampu</label>
									  <div class="input-group mb-1">
                                        <select name='guru' class='form-select' style='width:100%' required>
                                          <option value="<?= $guru ?>"><?= $peg['nama'] ?></option>                                           
                                            </select>
                                        </div>
										<label class="bold">KD</label>
									  <div class="input-group mb-1">
                                         <input type="text" name="kd" value="4.<?= $jumlah; ?>" class="form-control" readonly >
                                        </div>
										<label class="bold">Deskripsi (maximal 200 karakter)</label>
									  <div class="input-group mb-1">
                                     <textarea name="materi" class="form-control" rows="5" required="true" maxlength="200" ></textarea>							   
									   </div>
									   <div id="count">
								<span id="current_count">0</span>
								<span id="maximum_count">/ 200</span>
							</div>
							<script type="text/javascript">
							$('textarea').keyup(function() {    
								var characterCount = $(this).val().length,
									current_count = $('#current_count'),
									maximum_count = $('#maximum_count'),
									count = $('#count');    
									current_count.text(characterCount);        
							});
							</script>
										<div class="widget-payment-request-actions m-t-lg d-flex">

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
                 <script>
						$('#formdeskrip').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'kurtilas/tdeskrip.php?pg=deskrip4',
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

             <?php elseif ($ac == enkripsi('edit')): ?>
			
             <?php
			 $id = dekripsi($_GET['id']);
			 $dataz = fetch($koneksi,'deskripsi',['id'=>$id]);
			  $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$dataz['mapel']]);
			  $guru = fetch($koneksi,'users',['id_user'=>$dataz['guru']]);
			 ?>					 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                      <h5 class="bold">KD <?= $dataz['kd'] ?> <span class="badge badge-secondary"><?= $mapel['kode'] ?></span> <span class="badge badge-primary"><?= $dataz['level'] ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="10%">NO</th>                                               
                                                    <th width="10%">KD</th>
                                                    <th>DESKRIPSI</th>													
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM deskripsi where id='$id'"); 
											  while ($data = mysqli_fetch_array($query)) :
											 
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['kd'] ?></td>
													<td><?= $data['deskripsi'] ?></td>
													  
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
                                      <h5 class="bold">EDIT KD <?= $dataz['kd'] ?> <span class="badge badge-secondary"><?= $mapel['kode'] ?></span> <span class="badge badge-primary"><?= $dataz['level'] ?></span> </h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">LINGKUP MATERI</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formedit' >	
									
										 <label class="bold">Semester</label>
									  <div class="input-group mb-1">
                                        <select name='smt' class='form-select' style='width:100%' required>
                                                <option value="<?= $setting['semester'] ?>">Semester <?= $setting['semester'] ?></option>
                                               
												 </select>
                                        </div>
										 <label class="bold">Guru Pengampu</label>
									  <div class="input-group mb-1">
                                        <select name='guru' class='form-select' style='width:100%' required>
                                           <option value="<?= $dataz['guru'] ?>"><?= $guru['nama'] ?></option>
                                               
												 </select>
                                        </div>
										<label class="bold">Tingkat</label>
									  <div class="input-group mb-1">
                                        <select name='level' class='form-select' style='width:100%' required>
                                               <option value="<?= $dataz['level'] ?>"><?= $dataz['level'] ?></option>                                       
												 </select>
                                        </div>
										<label class="bold">KD</label>
									  <div class="input-group mb-1">
                                     <input type="text" name="kd" value="<?= $dataz['kd']; ?>" class="form-control" readonly >
									  <input type="hidden" name="id" value="<?= $dataz['id']; ?>" class="form-control"  >
									   </div>
										<label class="bold">Deskripsi (maximal 200 karakter)</label>
									  <div class="input-group mb-1">
                                     <textarea name="materi" class="form-control" rows="5" required="true" maxlength="200" ><?= $dataz['deskripsi']; ?></textarea>							   
									   </div>
									   <div id="count">
								<span id="current_count">0</span>
								<span id="maximum_count">/ 200</span>
							</div>
							<script type="text/javascript">
							$('textarea').keyup(function() {    
								var characterCount = $(this).val().length,
									current_count = $('#current_count'),
									maximum_count = $('#maximum_count'),
									count = $('#count');    
									current_count.text(characterCount);        
							});
							</script>
										<div class="widget-payment-request-actions m-t-lg d-flex">

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
                 <script>
						$('#formedit').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'kurtilas/tdeskrip.php?pg=edit_deskrip4',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									beforeSend: function() {
										$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
										$('.progress-bar').animate({
											width: "30%"
										}, 500);
									},
									success: function(data) {
									   
										setTimeout(function() {
											window.location.replace('?pg=<?= enkripsi(ki4) ?>&ac=<?= enkripsi(input) ?>&l=<?= enkripsi($dataz[level]) ?>&m=<?= enkripsi($dataz[mapel]) ?>&g=<?= enkripsi($dataz[guru]) ?>');
										}, 2000);
									}
								})
								return false;
							});
							</script>
	
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					