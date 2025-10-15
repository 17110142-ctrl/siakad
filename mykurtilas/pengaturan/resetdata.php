<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

                        <div class="row">
                            <div class="col-xl-8">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                       
										<div class="kanan">
                                               
                                                <button id="confirm" class="btn btn-danger">RESET</button>
                                            </div>
											 <h5 class="card-title">RESET DATA E-RAPOR KURTILAS</h5>
                                    </div>
                                    <div class="card-body">
                                    
                                        <ul class="widget-list-content list-unstyled">
										
                                            <li class="widget-list-item widget-list-item-red">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">storage</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                      RESET DATABASE E-RAPOR KURTILAS
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                     Database E-RAPOR KURTILAS dikosongkan kembali ke awal
                                                    </span>
                                                </span>
                                            </li>
											
                                          
                                        </ul>
										
                                    </div>
                                </div>
                            </div>
                             <script>
				$("#confirm").click(function(){
		    	Swal.fire({
				  title: 'RESET E-KBM',
				  text: "Database E-KBM akan dikosongkan !",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, Reset!'
				}).then((result) => {
				  if (result.value) {
					$.ajax({
					url: 'pengaturan/treset.php',
					success: function(data) {
						 Swal.fire(
				      'Deleted!',
				      'Your file has been deleted.',
				      'success'
				    )
				   setTimeout(function() {
					window.location.reload();
					}, 1000);
						}
					});
					}
					return false;
						})

						});
					</script>	
	
                            <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title"><?= $setting['sekolah'] ?></h5>
                                    </div>
                                    <div class="card-body">
									
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">ADMIN</span>
                                                    <span class="widget-payment-request-author-about"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                   
                                            </div>
											
                                           
                                        </div>
										
										<p>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>	

						
