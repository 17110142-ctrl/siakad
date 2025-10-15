<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA REGISTRASI BARCODE</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>BARKODE</th>
                                                    <th>NAMA LENGKAP</th>
													 <th>STATUS</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM datareg WHERE nokartu<>'' ORDER BY id DESC"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $siswa = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
											   $peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['barkode'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td>
													  <?php if($data['level']=='siswa'): ?>
													  SISWA - <?= $siswa['kelas'] ?>
													  <?php else: ?>
													  PEGAWAI - <?= strtoupper($peg['level']) ?>
													  <?php endif; ?>
													  
													  </td>
													  <td>
											<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus barkode"><i class="material-icons">delete</i> </button>
											</td>
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
                                       
										<a href="?pg=barkode&ac=siswa" class="btn btn-success"><i class="material-icons">crisis_alert</i>Siswa</a>
										<a href="?pg=barkode&ac=pegawai" class="btn btn-primary"><i class="material-icons">crisis_alert</i>Pegawai</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Data Siswa</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<?php 
											$ids = $_GET['ids'];
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									
							
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Loading...
										</button>
										 </div>
										
									 </div>
					            </div>
								</div>
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
											   url: 'barkode/tbarkode.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												beforeSend: function() {
												$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
												$('.progress-bar').animate({
													width: "30%"
												}, 500);
											},
												success: function(data) {
													 
													setTimeout(function() {
														window.location.replace('?pg=barkode');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    
					
      <?php elseif ($ac == 'siswa') : ?>
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">REGISTRASI BARKODE SISWA</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>N I S</th>
                                                    <th>NAMA SISWA</th>
													 <th>ROMBEL</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE sts='0'"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nis'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td><?= $data['kelas'] ?></td>
													  <td>
											
											  <a href="?pg=barkode&ac=siswa&ids=<?= $data['id_siswa'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Registrasi barkode"><i class="material-icons">edit</i></button></a>
											</td>
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
                                       
										<a href="?pg=barkode" class="btn btn-primary kanan"><i class="material-icons">crisis_alert</i>Cek Data</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Data Siswa</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<?php 
											$ids = $_GET['ids'];
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									<form id='formkartu' >	
									 <label>N I S</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nis' class='form-control' value="<?= $siswa['nis'] ?>" readonly />
									    <input type='hidden' name='id' class='form-control' value="<?= $siswa['id_siswa'] ?>"   />
									   
                                        </div>	
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $siswa['nama'] ?>" readonly />
                                        </div>
										<label>Rombel</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' class='form-control' value="<?= $siswa['kelas'] ?>" readonly />
                                        </div>
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Loading...
										</button>
										 </div>
										<label>No Kartu</label>
									  <div class="input-group mb-1" id="nobarkode">
                                       
                                        </div>
										<p>
										<div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs" id="blockui-2">Simpan</button>
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
					<script type="text/javascript">
						$(document).ready(function(){
							setInterval(function(){
								$("#nobarkode").load('barkode/nokartu.php')
							}, 5000);  
						});
					</script>
					
					   <script>
						  $('#formkartu').submit(function(e) {
								e.preventDefault();
								$.ajax({
									type: 'POST',
									url: 'barkode/tbarkode.php?pg=siswa',
									data: $(this).serialize(),
									beforeSend: function() {
												$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
												$('.progress-bar').animate({
													width: "30%"
												}, 500);
											},
									success: function(data) {
										console.log(data);
										if (data == 'OK') {
											
											setTimeout(function() {
												window.location.reload();
											}, 2000);
										} else {
										   iziToast.info(
									{
										title: 'Gagal!',
										message: 'Data Siswa Belum dipilih',
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

									}
								});
								return false;
							});
						   
						</script>
					
		      <?php elseif ($ac == 'pegawai') : ?>			
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">REGISTRASI BARCODE PEGAWAI</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>N I P</th>
                                                    <th>NAMA GURU</th>
													 <th>JABATAN</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE sts='0' AND level<>'admin'"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nip'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td><?= $data['level'] ?></td>
													  <td>
											
											  <a href="?pg=barkode&ac=pegawai&&ids=<?= $data['id_user'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Registrasi barkode"><i class="material-icons">edit</i></button></a>
											</td>
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
                                        
										<a href="?pg=barkode" class="btn btn-primary kanan"><i class="material-icons">crisis_alert</i>Cek Data</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Data user</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<?php 
											$ids = $_GET['ids'];
											$user = fetch($koneksi,'users',['id_user'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									<form id='formkarpeg' >	
									 <label>N I P</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nip' class='form-control' value="<?= $user['nip'] ?>" readonly />
									    <input type='hidden' name='id' class='form-control' value="<?= $user['id_user'] ?>"  />
									   
                                        </div>	
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $user['nama'] ?>" readonly />
                                        </div>
										<label>Jabatan</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' class='form-control' value="<?= $user['level'] ?>" readonly />
                                        </div>
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Loading...
										</button>
										 </div>
										<label>No Kartu</label>
									  <div class="input-group mb-1" id="nobarkode">
                                       
                                        </div>
										<p>
										<div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs" id="blockui-2">Simpan</button>
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
					<script type="text/javascript">
						$(document).ready(function(){
							setInterval(function(){
								$("#nobarkode").load('barkode/nokartu.php')
							}, 5000);  
						});
					</script>
					
					   <script>
						  $('#formkarpeg').submit(function(e) {
								e.preventDefault();
								$.ajax({
									type: 'POST',
									url: 'barkode/tbarkode.php?pg=tambah',
									data: $(this).serialize(),
									beforeSend: function() {
												$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
												$('.progress-bar').animate({
													width: "30%"
												}, 500);
											},
									success: function(data) {
										console.log(data);
										if (data == 'OK') {
											
											setTimeout(function() {
												window.location.reload();
											}, 2000);
										} else {
										   iziToast.info(
									{
										title: 'Gagal!',
										message: 'Data Pegawai Belum di Pilih',
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

									}
								});
								return false;
							});
						   
						</script>
					
					
					
					
					  <?php endif ?>
					