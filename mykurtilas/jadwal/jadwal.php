<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">JADWAL INPUT RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>MAPEL</th>
                                                    <th>KELAS</th>
													 <th>GURU PENGAMPU</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_rapor ORDER BY id DESC"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $map = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											   $peg = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											   $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kuri']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><h5><span class="badge badge-primary"><?= $map['kode'] ?></span> <span class="badge badge-primary"><?= $kuri['nama_kurikulum'] ?></span></h5></td>
                                                     <td>
													  <?php
														$dataArray = unserialize($data['kelas']);
														foreach ($dataArray as $key => $value) :
															echo $value . " ";
														endforeach;
														?>
													 </td>
													  <td><?= $peg['nama'] ?></td>
													  <td>
											<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Jadwal"><i class="material-icons">delete</i> </button>
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
                                  
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<?php 
											$ids = $_GET['ids'];
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									<form id='formjadwal' class="row g-1">                         
                               <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Tingkat</option>
										<?php
										$lev = mysqli_query($koneksi, "SELECT * FROM level");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[level]'>$level[level]</option>";
										}
										?>
									 </select>
							     </div>	
								 
							             <div class="col-md-12">
								<label class="form-label bold">Rombel</label>
								 <select name='kelas[]' id="kelas" class='form-control select2' style='width:100%' multiple required='true'>
								  
								  </select>		
							     </div>	
								 <div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select kuri' required='true' style="width: 100%">
								   
									 </select>
							</div>
							<div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select' required='true' style="width: 100%">
								   <option value=''>Pilih Mapel</option>
								   <?php
									$mpl = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
									while ($mapel = mysqli_fetch_array($mpl)) { ?>
									<option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
									<?php } ?>
									 </select>
							</div>
								 <div class="col-md-12">
								<label class="form-label bold">Guru Pengampu</label>
								<select name="guru" class='form-select' required='true' style="width: 100%">
										 <?php
													if($user['level']=='admin'){
													$guruku = mysqli_query($koneksi, "SELECT * FROM users where level='guru' order by nama asc");
													}else{
													$guruku = mysqli_query($koneksi, "SELECT * FROM users where id_user='$user[id_user]'");
													}
													echo "<option value=''>Pilih Guru Pengampu</option>";
													while ($guru = mysqli_fetch_array($guruku)) {
														
														echo "<option value='$guru[id_user]'>$guru[nama]</option>";
													}
													?>
												</select>
							          </div><p>
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
							$("#level").change(function() {
							var level = $(this).val();
							console.log(level);
							$.ajax({
							type: "POST",
							url: "jadwal/tmapel.php?pg=kuri", 
							data: "level=" + level, 
							success: function(response) { 
							$("#kuri").html(response);
									}
								});
							});
							</script>
							<script>
						$('#formjadwal').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'jadwal/tjadwal.php?pg=tambah',
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
											window.location.reload();
										}, 1500);
									}
								})
								return false;
							});
							</script>

	
					  <?php endif ?>
					  
	<script>	
		$("#level").change(function() {
		var level = $(this).val();
		console.log(level);
		$.ajax({
		type: "POST",
		url: "jadwal/tjadwal.php?pg=kelas", 
		data: "level=" + level, 
	    success: function(response) { 
		$("#kelas").html(response);
		
				}
			});
		});
		
		$("#pk").change(function() {
		var pk = $(this).val();
		console.log(pk);
		$.ajax({
		type: "POST",
		url: "jadwal/tjadwal.php?pg=mapel", 
		data: "pk=" + pk, 
	    success: function(response) { 
		$("#mapel").html(response);
				}
			});
		});
		</script>				  
					  
					  
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
											   url: 'jadwal/tjadwal.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													  iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil dihapus',
											titleColor: '#FFFF00',
											messageColor: '#fff',
											backgroundColor: 'rgba(0, 0, 0, 0.5)',
											 progressBarColor: '#FFFF00',
											  position: 'topRight'				  
											});
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
					  
					  
					