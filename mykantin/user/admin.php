<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Data Admin</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>NIP</th>
                                                    <th>NAMA ADMIN</th>
													 <th>LEVEL</th>
													 <th width="20%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='admin'"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nip'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td><?= $data['username'] ?></td>
													  <td>
											
											  <a href="?pg=admin&ac=edit&iduser=<?= $data['id_user'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
												<button data-id="<?= $data['id_user'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
											</td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									 <?php if ($ac == '') : ?>
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">Input Data Admin</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Data Admin</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formguru' >	
									
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' required='true' />
                                        </div>
										<label>Username</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='username' class='form-control' required='true' />
                                        </div>
										<label>Password</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='password' class='form-control' required='true' />
                                        </div>
										
										
                                      <label>Foto Jika Ada</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control'/>
                                        </div>	
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs" id="blockui-2">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					
					 <?php elseif($ac == 'edit'): ?>	
						 <?php
						 $iduser = $_GET['iduser'];
						   $data= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$iduser'"));						
                            ?>
					 <div class="col-md-4">
                      
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">Edit Data Admin</h5>
										
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
										
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' value="<?= $data['nama'] ?>" class='form-control' required='true' />
                                        </div>
										<label>Username</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='username' value="<?= $data['username'] ?>" class='form-control' readonly />
                                        </div>
										<label>Password</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='password'  class='form-control' />
                                        </div>
										
                                      <label>Foto Jika Ada</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control'/>
                                        </div>	
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs" id="blockui-3">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
				
					
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
             url: 'user/tadmin.php?pg=tambah',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
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
    $('#formedit').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'user/tadmin.php?pg=edit',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){   		
			setTimeout(function()
				{
				window.location.replace('?pg=admin');
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
											   url: 'user/tadmin.php?pg=hapus',
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
														window.location.replace('?pg=admin');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    