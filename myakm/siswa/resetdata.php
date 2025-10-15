<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jumsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
?>           

                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
										
										RESET DATA SINKRON
										
										</h5>		
                                    </div>
                                    <div class="card-body">
									<div class="alert alert-light" role="alert">
											Reset Data Sinkron merupakan tindakan yang akan mengembalikan data pada setingan awal
											<b>Namun data yang sudah dikirim ke Pusat tidak dapat dikirim ulang.</b> Untuk menarik data kembali lakukan Langkah sinkron seperti semula
										</div>
										      </div>	
											</div>
										</div>
										
									
					       <div class="col-md-4" id="blockui-card-1">
                     
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
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									 <div class="d-grid gap-2">
									
									<button class="btn btn-danger" type="button" disabled>
												<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
												&nbsp;RESET DATA SINKRON
											</button>
											
											</div>
										<p>
										
                               <div class="col-md-12">
								<label class="form-label bold">NPSN</label>
								<input type="text" name="npsn" class="form-control" value="<?= $setting['npsn'] ?>" readonly >
							     </div>
							<p>									  
					<div class="d-grid gap-2"> 
					    
							<button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs" id="konfirmasi"><i class="material-icons">auto_mode</i>RESET DATA</button>
                                							 
								 </div>
									
										</div>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
					 <script>
				$("#konfirmasi").click(function(){
		    	Swal.fire({
				  title: 'Reset Data Sinkron',
				  text: "Data akan kembali ke seting awal !",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, Reset!'
				}).then((result) => {
				  if (result.value) {
					$.ajax({
					url: 'siswa/tsiswa.php?pg=settingawal',
					success: function(data) {
						 Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
				   setTimeout(function() {
					window.location.replace('.');
					}, 1000);
						}
					});
					}
					return false;
						})

						});
					</script>			