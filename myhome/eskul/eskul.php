<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">EKSTRAKURIKULER</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>EKSTRAKURIKULER</th>
                                                    <th>NAMA PEMBINA</th>
													
													 <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM m_eskul"); 
											  while ($data = mysqli_fetch_array($query)) :
											$guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['eskul'] ?></td>
                                                     <td><?= $guru['nama'] ?></td>
													 
													  <td>
												<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
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
                                        <h5 class="card-title">EKSTRAKURIKULER</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">PEMBINA ESKUL</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formguru' >	
									
										 <label>Nama Eskul</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='eskul' class='form-control' required='true' />
                                        </div>
										<label>Nama Pembina</label>
									  <div class="input-group mb-1">
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
					
					
					  <?php endif ?>
					<script>
						$('#formguru').submit(function(e){
							e.preventDefault();
							var data = new FormData(this);
							$.ajax(
							{
								type: 'POST',
								 url: 'eskul/teskul.php?pg=tambah',
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
											   url: 'eskul/teskul.php?pg=hapus',
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