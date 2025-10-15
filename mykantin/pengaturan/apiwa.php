<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<div class='row'>

       <div class='col-md-8'>
          <div class="card">
			<div class="card-header">
				<h5 class="card-title">Pengaturan Server WA</h5>
					
						</div>
				            <div class="card-body">            
						 	<form id="formpengaturan" action='' method='post' enctype='multipart/form-data'>
                   
								<div class="row mb-1">
								<label  class="col-sm-2 col-form-label">URL API</label>
								<div class="col-sm-10">
									<input type='text' name='urlapi' value="<?= $setting['url_api'] ?>" class='form-control' />
																	</div>
																  <p>
                                   <div class="col-sm-12">		
                                      								 
                                                <button type='submit' id="blockui-3" class='btn btn-primary kanan' >Simpan</button>
														
                                            </div>
						               
									           </form>
								            	</div> 
											</div>
										</div>
									</div>
									<script>
								   $('#formpengaturan').submit(function(e) {
										e.preventDefault();
										var data = new FormData(this);
									  
										$.ajax({
											type: 'POST',
											url: 'pengaturan/crud_setting.php?pg=apiwa',
											enctype: 'multipart/form-data',
											data: data,
											cache: false,
											contentType: false,
											processData: false,
											success: function(data) {
											 
												setTimeout(function() {
													window.location.reload();
												}, 2000);

											}
										});
										return false;
									});
								   
								</script>
									
						 <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">Message Request</h5>
                                    </div>
                                    <div class="card-body">
									 <form id="formpesan">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                   <img src="../images/user.png" class="mt-auto" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">EDI SUKARNA</span>
                                                    <span class="widget-payment-request-author-about">Sistem Aplikasi Pendidik</span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
                                                    
									               <input type='hidden' name='nowa' value="081380774602" class='form-control' readonly />
												<label>Pesan</label>
								              <input type='text' name='pesan' value="Tes Server....." class='form-control' readonly />
												
                                                    </span>
                                                    <span class="text-muted d-block">Tes Pesan dikirim ke Pengembang Sandik</span>
                                                </div>
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
                                                        Due Date
                                                    </span>
                                                    <span class="text-muted d-block"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                                <a href="#" class="btn btn-light flex-grow-1 m-r-xxs" disabled>Reject</a>
                                                <button type="submit" class="btn btn-success flex-grow-1 m-l-xxs">Kirim</button>
                                            </div>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
        
                          	</div> 
								 
<script>
   $('#formpesan').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
      
        $.ajax({
            type: 'POST',
            url: 'pengaturan/pesan.php',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
              iziToast.info(
            {
                title: 'Sukses!',
                message: 'Pesan berhasil dikirim',
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
        });
        return false;
    });
   
</script>
