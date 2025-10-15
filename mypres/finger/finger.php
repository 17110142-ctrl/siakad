<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$query = mysqli_query($koneksi, "SELECT max(idjari) as kodejari FROM datareg");
$data = mysqli_fetch_array($query);
$idjari = $data['kodejari'];
$idjari++;
?>        
 <?php
		$n=10;
		function getName($n) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
		 
			for ($i = 0; $i < $n; $i++) {
				$index = rand(0, strlen($characters) - 1);
				$randomString .= $characters[$index];
			}
		 
			return $randomString;
		}
		$serial = getName($n);
		?>  
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA FINGER PRINT</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>ID</th>
													<th>SERIAL NUMBER</th>
                                                    <th>NAMA LENGKAP</th>
													 <th>STATUS</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM datareg WHERE serial<>'' ORDER BY id DESC"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $siswa = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
											   $peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['idjari'] ?></td>
													 <td><?= $data['serial'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td>
													  <?php if($data['level']=='siswa'): ?>
													  SISWA - <?= $siswa['kelas'] ?>
													  <?php else: ?>
													  PEGAWAI - <?= strtoupper($peg['level']) ?>
													  <?php endif; ?>
													  
													  </td>
													  <td>
											<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus RFID"><i class="material-icons">delete</i> </button>
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
                                       
										<a href="?pg=finger&ac=siswa" class="btn btn-success"><i class="material-icons">crisis_alert</i>Siswa</a>
										<a href="?pg=finger&ac=pegawai" class="btn btn-primary"><i class="material-icons">crisis_alert</i>Pegawai</a>
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
											<?php 
											$ids = $_GET['ids'];
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									
							
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Setelah di Register silahkan <b>Tekan Tombol Registrasi</b> di Mesin, Tempel jari lalu angkat dan tempel sekali lagi...
										</button>
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
											   url: 'finger/tfinger.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);	  
													setTimeout(function() {
														window.location.replace('?pg=finger&ac=temp');
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
                                        <h5 class="card-title">REGISTRASI FINGER PRINT SISWA</h5>
										
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
											
											  <a href="?pg=finger&ac=siswa&ids=<?= $data['id_siswa'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Registrasi Finger"><i class="material-icons">edit</i></button></a>
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
                                        
										<a href="?pg=finger" class="btn btn-primary kanan"><i class="material-icons">crisis_alert</i>Cek Data</a>
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
									<label>ID</label>
									  <div class="input-group mb-1">
                                          <input type='text' name='idjari' class='form-control' value="<?= $idjari ?>" readonly />
                                        </div>
									 <label>Serial Number</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='serial' class='form-control' value="<?= $serial ?>" readonly />
									    <input type='hidden' name='id' class='form-control' value="<?= $siswa['id_siswa'] ?>"   />
									   
                                        </div>	
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $siswa['nama'] ?>"  readonly />
                                        </div>
										<label>Rombel</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' class='form-control' value="<?= $siswa['kelas'] ?>" readonly />
                                        </div>
										<label>Nomor WA</label>
									  <div class="input-group mb-1">
                                       <input type='number' name='nowa' class='form-control' value="<?= $siswa['nowa'] ?>"  />
                                        </div>
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Setelah di Register silahkan <b>Tekan Tombol Registrasi</b> di Mesin, Tempel jari lalu angkat dan tempel sekali lagi...
										</button>
										 </div>
										
										<p>
										<div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Register</button>
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
						  $('#formkartu').submit(function(e) {
								e.preventDefault();
								$.ajax({
									type: 'POST',
									url: 'finger/tfinger.php?pg=siswa',
									data: $(this).serialize(),
									beforeSend: function() {
									$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
									$('.progress-bar').animate({
									width: "30%"
									}, 500);
									},
									success: function(data) {
										console.log(data);
										if (data == 'OK') {
											
											setTimeout(function() {
												window.location.replace('?pg=finger');
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
                                        <h5 class="card-title">REGISTRASI FINER PRINT PEGAWAI</h5>
										
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
											
											  <a href="?pg=finger&ac=pegawai&&ids=<?= $data['id_user'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Registrasi RFID"><i class="material-icons">edit</i></button></a>
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
                                    
										<a href="?pg=finger" class="btn btn-primary kanan"><i class="material-icons">crisis_alert</i>Cek Data</a>
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
									 <label>ID</label>
									  <div class="input-group mb-1">
                                          <input type='text' name='idjari' class='form-control' value="<?= $idjari ?>" readonly />
                                        </div>
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $user['nama'] ?>" readonly />
                                        <input type='hidden' name='id' class='form-control' value="<?= $user['id_user'] ?>"   />
									   
										</div>
										<label>Jabatan</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' class='form-control' value="<?= $user['level'] ?>" readonly />
                                        </div>
										<label>Nomor WA</label>
									  <div class="input-group mb-1">
                                       <input type='number' name='nowa' class='form-control' value="<?= $user['nowa'] ?>"  />
                                        </div>
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Setelah di Register silahkan <b>Tekan Tombol Registrasi</b> di Mesin, Tempel jari lalu angkat dan tempel sekali lagi...
										</button>
										 </div>
										 <label>Serial Number</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='serial' class='form-control' value="<?= $serial ?>" readonly />
									    
                                        </div>	
										<p>
										<div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Register</button>
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
						  $('#formkarpeg').submit(function(e) {
								e.preventDefault();
								$.ajax({
									type: 'POST',
									url: 'finger/tfinger.php?pg=tambah',
									data: $(this).serialize(),
									beforeSend: function() {
									$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
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
					
					
					  <?php elseif ($ac == 'temp') : ?>	
					 <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA TEMP FINGER PRINT</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>ID</th>
													<th>SERIAL NUMBER</th>
                                                    <th>NAMA LENGKAP</th>
													 <th>STATUS</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM temp_finger WHERE serial<>'' ORDER BY id DESC"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $siswa = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
											   $peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['idjari'] ?></td>
													 <td><?= $data['serial'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td>
													  <?php if($data['level']=='siswa'): ?>
													  SISWA - <?= $siswa['kelas'] ?>
													  <?php else: ?>
													  PEGAWAI - <?= strtoupper($peg['level']) ?>
													  <?php endif; ?>
													  
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
                                       
										<a href="?pg=finger&ac=siswa" class="btn btn-success"><i class="material-icons">crisis_alert</i>Siswa</a>
										<a href="?pg=finger&ac=pegawai" class="btn btn-primary"><i class="material-icons">crisis_alert</i>Pegawai</a>
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
										<button class="btn btn-primary" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Tekan Tombol Delete pada Mesin untuk menghapus sidik jari...
										</button>
										 </div>
										
									 </div>
					            </div>
								</div>
							</div>
						</div>
					
					  <?php endif ?>
					