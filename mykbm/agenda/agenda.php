<?php
defined('APK') or exit('No Access');
$hari = date('D');
$hariDefaultRow = fetch($koneksi, 'm_hari', ['inggris' => $hari]);
$hariDefault = $hariDefaultRow['hari'] ?? '';
?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">AGENDA GURU BULAN <?= strtoupper(bulan_indo($bulan)) ?></h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="alert alert-custom" role="alert">
									<strong>Perhatian ! </strong><br>
										<span>Agenda Guru dapat diisi jika Hari sesuai Jadwal Mengajar</span>
									</div>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                  <th>NO</th>
                                                  <th>HARI</th>													  
												  <th>KELAS</th>													                                                 
                                                  <th>MATA PELAJARAN</th>
												  <th>GURU PENGAMPU</th>
												  <th>AGENDA</th>
												  <th></th>
                                                </tr>
                                            </thead>											
                                            <tbody>	
											<?php
											$no = 0;
											
											$bulan = date('m');
											$tahun = date('Y');
											$orderClause = "ORDER BY FIELD(hari,'Mon','Tue','Wed','Thu','Fri','Sat','Sun'), tingkat, kelas";
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel $orderClause");
											elseif($user['level']=='guru'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where guru='$user[id_user]' $orderClause");
											elseif($user['level']=='kepala'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel $orderClause");
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											  $harix = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
											  $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											  $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											  $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kuri']]);
											  $jumdes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM agenda where jadwal='$data[id_jadwal]' and bulan='$bulan' and tahun='$tahun'"));
											$no++;
											   ?>
											   <tr>
                                                 <td><?= $no; ?></td>
												 <td><?= $harix['hari'] ?></td>
                                                   <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span> <span class="badge badge-primary"> <?= $data['kelas'] ?></span></h5></td>
													<td><?= $mapelx['nama_mapel'] ?> <span class="badge badge-secondary"><?= $kuri['nama_kurikulum'] ?></span></td>
													<td><?= $guru['nama'] ?></td>
													<td><h5><span class="badge badge-success"><?= $jumdes; ?></span></h5></td>
													<td>
													<a href="?pg=<?= enkripsi('agenda') ?>&ac=<?= enkripsi('input') ?>&a=<?= enkripsi($data['id_jadwal']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Agenda"><i class="material-icons">select_all</i> </a>
													<a href="cetak/ctkag.php?j=<?= enkripsi($data['id_jadwal']) ?>" target="_blank" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak Agenda"><i class="material-icons">print</i> </a>
													
													</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
										</div>
									</div>
									<script>
									$(document).ready(function() {
										if (typeof $.fn.DataTable === 'undefined') {
											return;
										}

										var $table = $('#datatable1');
										if (!$table.length) {
											return;
										}

										var dataTable = $.fn.DataTable.isDataTable($table)
											? $table.DataTable()
											: $table.DataTable();

										var $wrapper = $('#datatable1_wrapper .row').first();
										if (!$wrapper.length) {
											return;
										}

										var $lengthDiv = $wrapper.find('div.col-sm-12.col-md-6').first();
										var $filterDiv = $wrapper.find('div.col-sm-12.col-md-6').last();

										$lengthDiv.removeClass('col-md-6').addClass('col-md-4');
										$filterDiv.removeClass('col-md-6').addClass('col-md-4');

										var customFilters = $(
											'<div class="col-sm-12 col-md-4" id="agenda-custom-filters">' +
												'<div class="d-flex flex-column flex-md-row gap-2">' +
													'<div class="flex-fill">' +
														'<label class="form-label mb-1">Hari</label>' +
														'<select id="filterHari" class="form-select form-select-sm">' +
															'<option value="">Semua Hari</option>' +
															'<option value="Senin">Senin</option>' +
															'<option value="Selasa">Selasa</option>' +
															'<option value="Rabu">Rabu</option>' +
															'<option value="Kamis">Kamis</option>' +
															'<option value="Jumat">Jumat</option>' +
															'<option value="Sabtu">Sabtu</option>' +
															'<option value="Minggu">Minggu</option>' +
														'</select>' +
													'</div>' +
												'</div>' +
											'</div>'
										);

										$lengthDiv.after(customFilters);

										var hariColumnIndex = 1; // kolom "HARI"

										function applyHariFilter(hari) {
											var pattern = hari ? '^' + hari + '$' : '';
											dataTable.column(hariColumnIndex).search(pattern, true, false).draw();
										}

										var defaultHari = <?= json_encode($hariDefault) ?>;
										if (defaultHari) {
											$('#filterHari').val(defaultHari);
											applyHariFilter(defaultHari);
										}

										$('#filterHari').on('change', function() {
											applyHariFilter(this.value);
										});
									});
									</script>
									
				<?php elseif ($ac == enkripsi('input')): ?>
                    	<?php
							   $id = dekripsi($_GET['a']);
							   $jadwal = fetch($koneksi,'jadwal_mapel',['id_jadwal'=>$id]);
							   $mapel = $jadwal['mapel'];
							   $tingkat = $jadwal['tingkat'];
							   $guru = $jadwal['guru'];
							   $mpl = fetch($koneksi,'mata_pelajaran',['id'=>$jadwal['mapel']]);
							   $peg = fetch($koneksi,'users',['id_user'=>$guru]);
								?>				 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">AGENDA GURU <?= $mpl['kode'] ?></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="8%">#</th>  	
													<th width="18%">TANGGAL</th>
													<th>KELAS</th>	
                                                    <th>MATERI</th>
                                                    <th>TUJUAN</th>													
													 <th width="18%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM agenda where jadwal='$id' ORDER BY id DESC"); 
											while ($datax = mysqli_fetch_array($query)) :													  
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $datax['tanggal'] ?></td>
													<td><h5><span class="badge badge-primary"><?= $jadwal['kelas'] ?><span> </h5></td>
													<td><?= $datax['materi'] ?></td>
													<td><?= $datax['tujuan'] ?></td>
													  <td>
											<a href="?pg=<?= enkripsi('agenda') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($datax['id']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit LM"><i class="material-icons">edit</i> </a>											
											<button data-id="<?= $datax['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
											</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
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
											   url: 'agenda/tagenda.php?pg=hapus',
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
                                       <h5 class="bold">INPUT AGENDA GURU</h5>
										
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
									<input type="hidden" name="kuri" value="<?= $jadwal['kuri'] ?>" >
									<input type="hidden" name="jadwal" value="<?= $id ?>" >
										 <label class="bold">Tanggal</label>
									  <div class="input-group mb-1">
                                        <input type="text" name="tgl" class="datepicker form-control" required="true" autocomplete="off">
                                        </div>
										<label class="bold">Kelas</label>
									  <div class="input-group mb-1">
									   <select name='kelas' class='form-select' style='width:100%' required>                                         
                                           <option value="<?= $jadwal['kelas'] ?>"><?= $jadwal['kelas'] ?></option>                                            
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
										<?php if($jadwal['kuri']=='1'): ?>			
										<label class="bold">Kompetensi Dasar</label>
									  <div class="input-group mb-1">
                                          <select name='kd'  class='form-select' style='width:100%' required='true'>
                                        <option value="">Pilih KD</option>
							            <?php 
											   $sql=mysqli_query($koneksi,"SELECT * FROM deskripsi where mapel='$mapel' and guru='$guru' and level='$tingkat' and smt='$setting[semester]'");
											   while ($data=mysqli_fetch_array($sql)) {									  
												echo '<option value="'.$data['id'].'">'.$data['kd'].' '.$data['deskripsi'].'</option> ';
											   }
											  ?>
                                          </select>
									   </div>
										<label class="bold">Materi Belajar (maximal 200 karakter)</label>
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
										<?php else: ?>
						
										<label class="bold">Materi</label>
									  <div class="input-group mb-1">
                                          <select name='kd' id="lm" class='form-select' style='width:100%' required='true'>
                                        <option value="">Pilih Materi</option>
							            <?php 
											   $sql=mysqli_query($koneksi,"SELECT * FROM tujuan JOIN lingkup ON lingkup.id=tujuan.idlm where tujuan.mapel='$mapel' and tujuan.guru='$guru' and tujuan.level='$tingkat' and tujuan.smt='$setting[semester]' GROUP BY tujuan.lm");
											   while ($data=mysqli_fetch_array($sql)) {									  
												echo '<option value="'.$data['idlm'].'">'.$data['lm'].' '.$data['materi'].'</option> ';
											   }
											  ?>
                                          </select>
									   </div>
									   <label class="bold">Pilih Tujuan Pembelajaran</label>
									  <div class="input-group mb-1" id="tp">
                                          
									   </div>
									   <script>
									   $("#lm").change(function() {
											var kd = $(this).val();
											console.log(kd);
											$.ajax({
												type: "POST", 
												url: "proto/tdeskrip.php?pg=ambil_tp", 
												data: "kd=" + kd, 
												success: function(response) { 
													$("#tp").html(response);
													console.log(response);
												},
												error: function(xhr, status, error) {
													console.log(error);
												}
											});
										});
										</script>
										<?php endif; ?>
										
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
									 url: 'agenda/tagenda.php?pg=tambah',
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
			 $agenda = fetch($koneksi,'agenda',['id'=>$id]);
			 $jadwal = fetch($koneksi,'jadwal_mapel',['id_jadwal'=>$agenda['jadwal']]);
			  $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$jadwal['mapel']]);
			  $guru = fetch($koneksi,'users',['id_user'=>$jadwal['guru']]);
			 ?>					 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">AGENDA GURU <span class="badge badge-secondary"><?= $mapel['kode'] ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="8%">#</th>  	
													<th width="18%">TANGGAL</th>
													<th>KELAS</th>	
                                                    <th>MATERI</th>													
													 <th>TUJUAN PEMBELAJARAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM agenda where id='$id'"); 
											while ($datax = mysqli_fetch_array($query)) :													  
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $datax['tanggal'] ?></td>
													<td><h5><span class="badge badge-primary"><?= $jadwal['kelas'] ?><span> </h5></td>
													<td><?= $datax['materi'] ?></td>
													 <td><?= $datax['tujuan'] ?></td>
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
                                       <h5 class="bold">EDIT AGENDA <span class="badge badge-secondary"><?= $mapel['kode'] ?></span> <span class="badge badge-primary"><?= $agenda['kelas'] ?></span> </h5>
										
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
									<form id='formedit' >	
									 <label class="bold">Tanggal</label>
									  <div class="input-group mb-1">
                                        <input type="text" name="tgl" value="<?= $agenda['tanggal']; ?>" class="form-control" readonly="true" >
										<input type="hidden" name="id" value="<?= $agenda['id']; ?>" >
										<input type="hidden" name="kuri" value="<?= $jadwal['kuri'] ?>" >
                                        </div>
										 <label class="bold">Semester</label>
									  <div class="input-group mb-1">
                                        <select name='smt' class='form-select' style='width:100%' required>
                                                <option value="<?= $setting['semester'] ?>">Semester <?= $setting['semester'] ?></option>
                                               
												 </select>
                                        </div>
										 <label class="bold">Guru Pengampu</label>
									  <div class="input-group mb-1">
                                        <select name='guru' class='form-select' style='width:100%' required>
                                           <option value="<?= $jadwal['guru'] ?>"><?= $guru['nama'] ?></option>
                                               
												 </select>
                                        </div>
										<label class="bold">Kelas</label>
									  <div class="input-group mb-1">
                                        <select name='kelas' class='form-select' style='width:100%' required>
                                               <option value="<?= $jadwal['kelas'] ?>"><?= $jadwal['kelas'] ?></option>                                       
												 </select>
                                        </div>
										<?php if($jadwal['kuri']=='1'): ?>
										<label class="bold">KD</label>
									  <div class="input-group mb-1">
                                      <select name='kd'  class='form-select'  style='width:100%' required='true'>
									   <option value="<?= $agenda['tujuan'] ?>"><?= $agenda['kd'] ?> <?= $agenda['tujuan'] ?></option>
                                        
                                          </select>
									   </div>
										<label class="bold">Materi (maximal 200 karakter)</label>
									  <div class="input-group mb-1">
                                     <textarea name="materi" class="form-control" rows="5" required="true" maxlength="200" ><?= $agenda['materi'] ?></textarea>							   
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
							<?php else : ?>
							<label class="bold">Materi</label>
									  <div class="input-group mb-1">
                                          <select name='kd' id="lm" class='form-select' style='width:100%' required='true'>
                                       
									   <option value="">Pilih Materi</option>
							            <?php 
											   $sql=mysqli_query($koneksi,"SELECT * FROM tujuan JOIN lingkup ON lingkup.id=tujuan.idlm where tujuan.mapel='$jadwal[mapel]' and tujuan.guru='$jadwal[guru]' and tujuan.level='$jadwal[tingkat]' and tujuan.smt='$setting[semester]' GROUP BY tujuan.lm");
											   while ($data=mysqli_fetch_array($sql)) {									  
												echo '<option value="'.$data['idlm'].'">'.$data['lm'].' '.$data['materi'].'</option> ';
											   }
											  ?>
                                          </select>
									   </div>
									   <label class="bold">Pilih Tujuan Pembelajaran</label>
									  <div class="input-group mb-1" id="tp">
                                          
									   </div>
									   <script>
									   $("#lm").change(function() {
											var kd = $(this).val();
											console.log(kd);
											$.ajax({
												type: "POST", 
												url: "proto/tdeskrip.php?pg=ambil_tp", 
												data: "kd=" + kd, 
												success: function(response) { 
													$("#tp").html(response);
													console.log(response);
												},
												error: function(xhr, status, error) {
													console.log(error);
												}
											});
										});
										</script>
										<?php endif; ?>
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
									 url: 'agenda/tagenda.php?pg=edit',
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
											window.location.replace('?pg=<?= enkripsi(agenda) ?>&ac=<?= enkripsi(input) ?>&a=<?= enkripsi($jadwal[id_jadwal]) ?>');
										}, 2000);
									}
								})
								return false;
							});
							</script>
	
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					
