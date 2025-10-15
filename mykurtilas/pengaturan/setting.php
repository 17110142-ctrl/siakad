<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PENGATURAN RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th>SEMESTER</th>                                               
                                                    <th>TAHUN PELAJARAN</th>
                                                    <th>TANGGAL RAPOR</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$query = mysqli_query($koneksi, "SELECT * FROM aplikasi"); 
											  while ($data = mysqli_fetch_array($query)) :
											
											   ?>
                                                <tr>
                                                    <td><?= $data['semester'] ?></td>
                                                     <td><?= $data['tp'] ?></td>
													 <td><?= $data['tanggal_rapor'] ?></td>
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
                                        <h5 class="card-title">PENGATURAN RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?> </span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formrapor' >	
									 <label>Semester</label>
									  <div class="input-group mb-1">
                                       <select name='semester' class='form-select' required='true'>
										<option value="<?= $setting['semester'] ?>"><?= $setting['semester'] ?></option>
											 <option value='1'>1</option>
												   <option value='2'>2</option>
											 </select>
                                        </div>	
										 <label>Tahun Pelajaran</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='tp' value="<?= $setting['tp'] ?>" class='form-control' required='true' />
                                        </div>
										<label>Tanggal Rapor</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='tgl' value="<?= $setting['tanggal_rapor'] ?>" class='form-control' required='true' />
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
    $('#formrapor').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'pengaturan/trapor.php',
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
                        
            