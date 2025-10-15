<?php
defined('APK') or exit('No accsess');
?>   		   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA STAFF</h5>
									<div class="pull-right">
										<a href="?pg=<?= enkripsi('staff') ?>&ac=upload" class="btn btn-sm btn-success"><i class="material-icons">upload</i>IMPOR</a>
										</div>	
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>NIP</th>
                                                    <th>NAMA STAFF</th>
													 <th>USERNAME</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='staff'"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nip'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td><?= $data['username'] ?></td>
													  <td>
											<a href="user/cetak.php?iduser=<?= $data['id_user'] ?>" target="_blank"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak Akun"><i class="material-icons">print</i></button></a>
											  <a href="?pg=<?= enkripsi('staff') ?>&ac=<?= enkripsi('edit') ?>&iduser=<?= enkripsi($data['id_user']) ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
											<button data-id="<?= $data['id_user'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
											</td>
                                                </tr>
												<?php endwhile; ?>
												<tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
						<?php if ($ac == '') : ?>
					       <div class="col-md-4">
                     
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">INPUT STAFF</h5>
										
                                    </div>
                                    <div class="card-body">
									<form id='formguru' >	
									 <label class="bold">N I P</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nip' class='form-control' required='true' />
									   
                                        </div>	
										 <label class="bold">Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' required='true' />
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
					
					 <?php elseif($ac == enkripsi('edit')): ?>	
						 <?php
						 $iduser = dekripsi($_GET['iduser']);
						   $data= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$iduser'"));						
                            ?>
					 <div class="col-md-4">
                      
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">Edit Data Guru</h5>
										
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
									 <label class="bold">N I P</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nip' value="<?= $data['nip'] ?>" class='form-control' required='true' />
									   
                                        </div>	
										 <label class="bold">Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' value="<?= $data['nama'] ?>" class='form-control' required='true' />
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
    <div class="card widget widget-payment-request">
        <div class="card-header">
          <a href="user/M_STAFF.xlsx" class="btn btn-link kanan"><i class="material-icons">download</i>Download Format</a>   
                </div>
      <div class="card-body">                                     
		<form id='formupload' >	
             <label class="bold">Pilih File</label>
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
             url: 'user/tstaff.php?pg=tambah',
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
             url: 'user/import_staff.php',
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
				window.location.replace('?pg=<?= enkripsi(staff) ?>');
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
             url: 'user/tstaff.php?pg=edit',
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
				window.location.replace('?pg=<?= enkripsi(staff) ?>');
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
											   url: 'user/tstaff.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
													$('.progress-bar').animate({
													width: "30%"
													}, 500);
													setTimeout(function() {
														window.location.replace('?pg=<?= enkripsi(staff) ?>');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    