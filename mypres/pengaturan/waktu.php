<?php
defined('APK') or exit('No Access');

?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                       <h5 class="card-title">PENGATURAN WAKTU</h5>										
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>HARI</th>
                                                    <th>MASUK</th>
													 <th>ALPHA</th>
													 <th>PULANG</th>
													 <th>MASUK ESKUL</th>
													 <th>PULANG ESKUL</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM waktu"); 
											while ($data = mysqli_fetch_array($query)) :
											$harix = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_hari  WHERE inggris='$data[hari]'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $harix['hari'] ?></td>
                                                    <td><?= $data['masuk'] ?>:00</td>
													<td><?= $data['alpha'] ?></td>
													<td><?= $data['pulang'] ?>:00</td>
													<td>
													<?php if($data['jam_eskul']=='23:00:00'): ?>
													<?php else : ?>
													<?= $data['jam_eskul'] ?>
													<?php endif; ?>
													</td>
													<td>
													<?php if($data['jam_eskul']=='23:00:00'): ?>
													<?php else : ?>
													<?= $data['pulang_eskul'] ?></td>
													<?php endif; ?>
													<td>								
													  <a href="?pg=<?= enkripsi('waktu') ?>&ac=<?= enkripsi('edit') ?>&id=<?= $data['id'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
														<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
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
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">SETTING WAKTU</h5>
										
                                    </div>
                                    <div class="card-body">
                                        
									
									<form id='formwaktu' >	
									 <label class="bold">Hari</label>
									  <div class="input-group mb-1">
                                        <select class="form-select" name="hari" required style="width: 100%">
										  <option value='' >-- Pilih Hari --</option>
										  <?php
											$lev = mysqli_query($koneksi, "SELECT * FROM m_hari");
											while ($level = mysqli_fetch_array($lev)) {
											echo "<option value='$level[inggris]'>$level[hari]</option>";
											} ?>
										</select>
                                        </div>	
										 <label class="bold">Jam Masuk</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='masuk' class='form-control jam1' required='true' autocomplete="off" />
                                        </div>
			                             <label class="bold">Deteksi Alpha</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='alpha' class='form-control jam3' required='true' autocomplete="off" />
                                        </div>
										<label class="bold">Jam Pulang</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='pulang' class='form-control jam2' required='true' autocomplete="off" />
                                        </div>
										<label class="bold">Absen Eskul dimulai(Jika Ada)</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='masuk_eskul' class='form-control jam4'  autocomplete="off" />
                                        <p>harap dikasih jeda minimal 10 menit, Misal masuk pada 14:30 maka isi 14:20</p>
									   </div>
										<label class="bold">Jam Pulang Eskul (Jika Ada)</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='pulang_eskul' class='form-control jam5'  autocomplete="off" />
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
					<script>
    $('#formwaktu').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'pengaturan/twaktu.php?pg=tambah',
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
					 <?php elseif($ac == enkripsi('edit')): ?>	
						 <?php
						 $id = $_GET['id'];
						   $data= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM waktu WHERE id='$id'"));	
                           $harix = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_hari  WHERE inggris='$data[hari]'"));
                            if($data['jam_eskul']=='23:00:00'){
								$datajam = '';
							}else{
								$datajam = $data['jam_eskul'];
							}
							if($data['masuk_eskul']=='23:00:00'){
								$datamasuk = '';
							}else{
								$datamasuk = $data['masuk_eskul'];
							}	

							if($data['pulang_eskul']=='23:00:00'){
								$datapulang = '';
							}else{
								$datapulang = $data['pulang_eskul'];
							}	

                            ?>
						
					 <div class="col-md-4">
                      
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">EDIT WAKTU</h5>
										
                                    </div>
                                    <div class="card-body">
                                        
									<div class="widget-payment-request-info m-t-md">
									<form id='formedit' >
                                     <input type="hidden" name="id" value="<?= $id; ?>" >									
									   <label class="bold">Hari</label>
									  <div class="input-group mb-1">
                                        <select class="form-select" name="hari" required style="width: 100%">
										 <option value="<?= $data['hari'] ?>" ><?= $harix['hari'] ?></option>
										  <option value='' >-- Pilih Hari --</option>
										  <?php
											$lev = mysqli_query($koneksi, "SELECT * FROM m_hari");
											while ($level = mysqli_fetch_array($lev)) {
											echo "<option value='$level[inggris]'>$level[hari]</option>";
											} ?>
										</select>
                                        </div>	
										 <label class="bold">Jam Masuk</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='masuk' class='form-control jam1' value="<?= $data['masuk'] ?>" required='true' autocomplete="off" />
                                        </div>
			                             <label class="bold">Deteksi Alpha</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='alpha' class='form-control jam3' value="<?= date('H:i',strtotime($data['alpha'])) ?>" required='true' autocomplete="off" />
                                        </div>
										<label class="bold">Jam Pulang</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='pulang' class='form-control jam2' value="<?= $data['pulang'] ?>" required='true' autocomplete="off" />
                                        </div>
										<label class="bold">Absen Eskul dimulai(Jika Ada)</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='masuk_eskul' class='form-control jam4' value="<?= $datamasuk ?>" autocomplete="off" />
                                       <p>harap dikasih jeda minimal 10 menit, Misal masuk pada 14:30 maka isi 14:20
									   </div>
										<label class="bold">Jam Pulang Eskul (Jika Ada)</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='pulang_eskul' class='form-control jam5' value="<?= $datapulang ?>" autocomplete="off" />
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
					<script>
    $('#formedit').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'pengaturan/twaktu.php?pg=edit',
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
				window.location.replace('?pg=<?= enkripsi(waktu) ?>');
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>	
				
<?php endif ?>
					
                        
            
                                  
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
											   url: 'pengaturan/twaktu.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);
													setTimeout(function() {
														window.location.replace('?pg=<?= enkripsi(waktu) ?>');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    