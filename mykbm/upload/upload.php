<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">FILES ADMINISTRASI</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>NAMA GURU</th>													                                                 
                                                    <th>NAMA FILES</th>
													
													 <th>TGL UPLOAD</th>
													  <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM adm ORDER BY tanggal DESC"); 
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM adm WHERE idguru='$user[id_user]' ORDER BY tanggal DESC"); 	
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											 $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$data['idmapel']]);
											  $peg = fetch($koneksi,'users',['id_user'=>$data['idguru']]);
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													 <td><?= $peg['nama'] ?></td>
                                                      <td><?= $data['nama'] ?> <br><?= $mapel['kode'] ?> - <?= $data['kelas'] ?></td>
													    <td><?= $data['tanggal'] ?></td>
													  <td>
													  <?php if($data['pesan'] !=''): ?>
													  <a href="?pg=upload&ac=pesan&id=<?= $data['id'] ?>" > <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="1 Pesan KS"><i class="material-icons">mail</i></button></a>
													  <?php else: ?>
													  <a href="#" > <button class='btn btn-sm btn-secondary' ><i class="material-icons">lock</i></button></a>
													  <?php endif; ?>
													  <?php if($data['file'] !=''): ?>
													  <a href="../mykbm/fileadm/<?= $data['file'] ?>" target="_blank"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak"><i class="material-icons">print</i></button></a>
													  <?php endif; ?>
													  
													   <?php if($data['link'] !=''): ?>
													  <a href="<?= $data['link'] ?>" target="_blank"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Download"><i class="material-icons">download</i></button></a>
													 
													  <?php endif; ?>
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
											   url: 'upload/tupload.php?pg=hapus',
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
														window.location.replace('?pg=upload');
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
                                  
                                    </div>
                                    <div class="card-body">
									 <form id="formupload">
									
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/amplop.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">FILE ADMINISTRASI</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                 </div>
											   </div>
                                            </div>
											<p>
											
									<label class="bold">Nama File</label>                               
									<select name="nama"  class="form-select" style="width: 100%;" required >
									  <option value=''></option>
										<option value='RPP'>RPP</option>
										<option value='Promes'>Promes</option>
										<option value='Prota'>Prota</option>
										
												   </select> 
                                          <p>												   
										<label class="bold">Mata Pelajaran</label> 		   
									<select name="mapel"  class="form-select" style="width: 100%;" required >
									  <option value=''></option>
										 <?php $mpl = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"); ?>
										   <?php while ($mapel = mysqli_fetch_array($mpl)) : ?>
											 <option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
												<?php endwhile ?>
												   </select>  			   
												  <p>												   
										<label class="bold">Kelas / Rombel</label> 		   
									<select name="kelas"  class="form-select" style="width: 100%;" required >
									  <option value=''></option>
										 <?php $kls = mysqli_query($koneksi, "SELECT * FROM siswa GROUP BY kelas"); ?>
										   <?php while ($Q = mysqli_fetch_array($kls)) : ?>
											 <option value="<?= $Q['kelas'] ?>"><?= $Q['kelas'] ?></option>
												<?php endwhile ?>
												   </select>  
												   <p>
						<label class="bold">Nama Guru</label>
                        <select name='guru' class='form-select' required='true'>
						<?php if($user['level']=='admin'){ ?>
                            <option value=''>Pilih Guru</option>
                            <?php
                            $nama = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru'");
                            while ($namaQ = mysqli_fetch_array($nama)) {
                                echo "<option value='$namaQ[id_user]'>$namaQ[nama] </option>";
                            }
                            ?>
                        </select>
						<?php }else{ ?>
						<?php
                            $nama = mysqli_query($koneksi, "SELECT * FROM users WHERE  id_user='$_SESSION[id_user]'");
                            while ($namaQ = mysqli_fetch_array($nama)) {
                                echo "<option value='$namaQ[id_user]'>$namaQ[nama] </option>";
                            }
                            ?>
                        </select>
						<?php } ?>
						<hr>
						<center>PILIH SALAH SATU</center>
						<hr>
						<label class="bold">Upload File</label>
                          <input type="file" class="form-control-file" name="file" placeholder="" aria-describedby="fileHelpId">
                         <br> <br>
                        <label class="bold">Link Google Drive</label>
                         <input type="text" name="link" class="form-control">						 
							<div class="widget-payment-request-info m-t-md">							
										<div class="d-grid gap-2">										
										  <button type="submit"  class="btn btn-primary flex-grow-1 m-l-xxs">SIMPAN</button>
										 </div>
										 </div>
										
											</div>
										</div>
									</div>
								</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
								<script>
						$('#formupload').submit(function(e) {
							e.preventDefault();
							var data = new FormData(this);
						   
							$.ajax({
								type: 'POST',
								url: 'upload/tupload.php?pg=tambah',
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
									if (data = 'OK') {
										setTimeout(function() {
											window.location.replace('?pg=upload');
										}, 2000);
									} else {
									  iziToast.info(
								{
									title: 'GAGAL!',
									message: 'Data sudah ada',
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
					 <?php elseif ($ac == 'pesan') : ?>
				<style>
				.responsive {
				width: 30%;
				height: auto;
				}
				</style>
	  <?php 
		$id = $_GET['id'];
		$data = fetch($koneksi,'adm',['id'=>$id]);
		
		?>
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">FEEDBACK KEPALA SEKOLAH</h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="d-grid gap-2">
										
										<?= $data['pesan'] ?>
										<p>
										<center>
                                               
											
                                                    <img src="../images/user.png" alt="" class="responsive">
												
												</center>
										 </div>
											</div>
										</div>
									</div>
									
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">FEEDBACK KEPALA SEKOLAH</h5>
										
                                    </div>
                                    <div class="card-body">
									<form id='formabsensi' >	
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
												
                                                    <img src="../images/user.png" alt="">
												
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $setting['kepsek'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									
								
									 <label>Nama File</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nis' class='form-control' value="<?= $data['nama'] ?>" readonly />
									   
									   
                                        </div>	
										 <label>Tanggal Upload</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $data['tanggal'] ?>" readonly />
                                        </div>
										
									 </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>		
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					