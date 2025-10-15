<?php
defined('APK') or exit('No accsess');
$is_admin = ($user['level'] ?? '') === 'admin';
$is_kurikulum_task = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
?> 		   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header warna">
                                        <h5 class="card-title">DATA GURU</h5>
										<div class="pull-right" style="display:flex;gap:6px;flex-wrap:wrap">
										<?php if ($is_admin): ?>
										<a href="?pg=<?= enkripsi('guru') ?>&ac=upload" class="btn btn-sm btn-success"><i class="material-icons">upload</i>IMPOR</a>
										<?php endif; ?>
										<?php if ($is_admin || $is_kurikulum_task): ?>
										<a href="?pg=<?= enkripsi('guruwali') ?>" class="btn btn-sm btn-primary"><i class="material-icons">supervisor_account</i>Guru Wali</a>
										<?php endif; ?>
										</div>
                                    </div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                        <thead>
                                         <tr>
                                          <th width="5%">NO</th>                                               
                                          <th><?= $setting['no_guru'] ?></th>
                                          <th>NAMA GURU</th>
										  <th>STATUS</th>
										  <th>WALAS</th>
										  <th>JABATAN</th>
										  <th></th>
                                          </tr>
                                          </thead>
                                          <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru'"); 
											while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                            <tr>
                                             <td><?= $no; ?></td>
                                             <td><?= $data['nip'] ?></td>
                                             <td><?= $data['nama'] ?></td>
											 <td><?= $data['jenis'] ?></td>
											 <td><?= $data['walas'] ?></td>
											 <td><?= $data['tugas'] ?></td>
											<td>
											<a href="user/cetak.php?iduser=<?= $data['id_user'] ?>" target="_blank"><button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak Akun"><i class="material-icons">print</i></button></a>
											<?php if ($is_admin): ?>
											<a href="?pg=<?= enkripsi('guru') ?>&ac=<?= enkripsi('edit') ?>&iduser=<?= enkripsi($data['id_user']) ?>"><button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
											<button data-id="<?= $data['id_user'] ?>" class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i></button>
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
									
					<?php if ($ac == '' && $is_admin) : ?>
					       <div class="col-md-4">                   
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">INPUT GURU</h5>										
                                    </div>
                                    <div class="card-body">
									<form id='formguru' >	
									 <label class="bold"><?= $setting['no_guru'] ?></label>
									 <div class="input-group mb-1">
                                       <input type='text' name='nip' class='form-control' required='true' />
									 </div>	
									<label class="bold">Nama Lengkap</label>
									<div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' required='true' />
                                    </div>
								    <label class="bold">Status Guru</label>
									<div class="input-group mb-1">
                                      <select name="jenis" class="form-select" style="width:100%" required >
									  <option value="">Pilih Status</option>
									  <option value="kepala">Kepala Sekolah</option>
									  <option value="Guru Mapel">Guru Mapel</option>
									  <option value="Guru BK">Guru BK</option>
									  <option value="Guru Mapel Umum">Guru Mapel Umum</option>									 
									  </select>
                                    </div>
								    <label class="bold">Username</label>
									<div class="input-group mb-1">
                                       <input type='text' name='username' class='form-control' required='true' />
                                     </div>
									<label class="bold">Password</label>
									<div class="input-group mb-1">
                                       <input type='text' name='password' class='form-control' required='true' />
                                     </div>
									<label class="bold">Nomor WA</label>
									<div class="input-group mb-1">
                                       <input type='number' name='nowa' class='form-control' required='true' />
                                    </div>
								    <label class="bold">Wali Kelas</label>
									<div class="input-group mb-1">
                                      <select name="walas" class="form-select" style="width:100%" >
									  <option value="">Bukan Wali Kelas</option>
									   <?php $q = mysqli_query($koneksi, "select * from kelas");
                                         while ($data = mysqli_fetch_array($q)) { ?>
                                         <option value="<?= $data['kelas'] ?>"><?= $data['kelas'] ?></option>
                                        <?php } ?>
									   </select>
                                    </div>
                                    <label class="bold">Jabatan</label>
									<div class="input-group mb-1">
                                      <select name="tugas" class="form-select" style="width:100%" >
									  <option value="">Tidak Ada</option>
									  <option value="admin">Admin</option>
									  <option value="kurikulum">Kurikulum</option>
									  <option value="bendahara">Bendahara</option>
									  <option value="kesiswaan">Kesiswaan</option>
									  <option value="sarpras">Sarpras</option>
									  <option value="perpustakaan">Perpustakaan</option>
									   </select>
                                    </div>
                                    <label class="bold">Foto Jika Ada</label>
									<div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control'/>
                                    </div>	
									<div class="widget-payment-request-actions m-t-lg d-flex">
											<button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
									
					               </div>
								</div>
							</div>
						</div>
					
	<?php elseif($ac == enkripsi('edit') && $is_admin): ?>	
		<?php
			$iduser = dekripsi($_GET['iduser']);
		    $data= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$iduser'"));						
              ?>
					 <div class="col-md-4">                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">EDIT DATA GURU</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
												<?php if($data['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
                                               <?php else : ?>
											    <img src="../images/fotoguru/<?= $data['foto'] ?>" alt="">
												<?php endif; ?>

											   </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $data['nama'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formedit' >	
									   <input type="hidden" class="form-control" name="iduser" value="<?= $iduser ?>" readonly>
									 <label class="bold"><?= $setting['no_guru'] ?></label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nip' value="<?= $data['nip'] ?>" class='form-control' required='true' />
									   
                                        </div>	
										 <label class="bold">Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' value="<?= $data['nama'] ?>" class='form-control' required='true' />
                                        </div>
										<label class="bold">Status Guru</label>
									  <div class="input-group mb-1">
                                      <select name="jenis" class="form-select" style="width:100%" required >
									   <option value="<?= $data['jenis'] ?>"><?= $data['jenis'] ?></option>
									   <option value="">Pilih Status</option>
									   <option value="kepala">Kepala Sekolah</option>
									 <option value="Guru Mapel">Guru Mapel</option>
									  <option value="Guru BK">Guru BK</option>
									  <option value="Guru Mapel Umum">Guru Mapel Umum</option>
									 
									  </select>
                                        </div>
										<label class="bold">Username</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='username' value="<?= $data['username'] ?>" class='form-control' readonly />
                                        </div>
										<label class="bold">Password</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='password' value="<?= $data['password'] ?>" class='form-control' required='true' />
                                        </div>
							          <label class="bold">Nomor WA</label>
									  <div class="input-group mb-1">
                                       <input type='number' name='nowa' value="<?= $data['nowa'] ?>" class='form-control' required='true' />
                                        </div>
										<label class="bold">Wali Kelas</label>
									  <div class="input-group mb-1">
                                      <select name="walas" class="form-select" style="width:100%" >
									  <option value="<?= $data['walas'] ?>"><?= $data['walas'] ?></option>
									   <option value="">Bukan Wali Kelas</option>
									   <?php $q = mysqli_query($koneksi, "select * from kelas");
											while ($data = mysqli_fetch_array($q)) { ?>
												<option value="<?= $data['kelas'] ?>"><?= $data['kelas'] ?></option>
											<?php } ?>
									  </select>
                                        </div>
										<label class="bold">Jabatan</label>
									<div class="input-group mb-1">
                                      <select name="tugas" class="form-select" style="width:100%" >
									  <option value="">Tidak Ada</option>
									  <option value="admin">Admin</option>
									  <option value="kurikulum">Kurikulum</option>
									  <option value="bendahara">Bendahara</option>
									  <option value="kesiswaan">Kesiswaan</option>
									  <option value="sarpras">Sarpras</option>
									  <option value="perpustakaan">Perpustakaan</option>
									   </select>
                                    </div>
                                      <label class="bold">Foto Jika Ada</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control'/>
                                        </div>	
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
			</div>
<?php elseif($ac == 'upload'): ?>	
	<div class="col-md-4">                     
       <div class="card">
          <div class="card-header">
           <a href="user/M_GURU.xlsx" class="btn btn-link kanan"><i class="material-icons">download</i>Download Format</a>   
			</div>
           <div class="card-body">
		<form id='formupload' >	
             <label>Pilih File</label>
				<div class="input-group mb-1">
                   <input type='file' name='file' class='form-control' required='true' />
						<span class="input-group-text">
							<button type="submit" class="btn btn-primary"><i class="material-icons">upload</i></button>
							</span>
                              </div>	
					       </form>
						</div>
					  </div>
								
<?php endif ?>
	<script>
    $('#formguru').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'user/tguru.php?pg=tambah',
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
			setTimeout(function()
				{
				window.location.reload();
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>	
      <script>
    $('#formupload').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'user/import_guru.php',
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
			setTimeout(function()
				{
				window.location.replace('?pg=<?= enkripsi(guru) ?>');
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>                  
    <script>
    $('#formedit').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'user/tguru.php?pg=edit',
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
			setTimeout(function()
				{
				window.location.replace('?pg=<?= enkripsi(guru) ?>');
						}, 2000);
									  
						}
					});
				return false;
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
											   url: 'user/tguru.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
											    $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);
												setTimeout(function() {
												window.location.replace('?pg=<?= enkripsi(guru) ?>');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    
