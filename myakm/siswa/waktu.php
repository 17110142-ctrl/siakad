<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$pel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM ujian"));
?>           

                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
										
										WAKTU PELANGGARAN <?= $pel['pelanggaran'] ?> DETIK
										
										</h5>		
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>NPSN</th>													                                                
                                                    <th>NAMA SEKOLAH</th>
													 <th>KEPALA SEKOLAH</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															 <td>1</td>
														  <td><?= $setting['npsn'] ?></td>
														  <td><?= $setting['sekolah'] ?></td>        
															<td><?= $setting['kepsek'] ?></td>  	 
															</tr>
															</tbody>
															</table>
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
									 
									<button class="btn btn-dark" type="button" disabled>
										 EDIT WAKTU PELANGGARAN
									</button>
											</div>
										<p>
									<form id='formsinkron' class="row g-1">
									
                               <div class="col-md-12">
								<label class="form-label bold">PILIH DURASI</label>
								 <select name="waktu" class="form-select" style="width:100%" required="true">
									  <option value="">Pilih Durasi</option>
									   <option value="1">1 Detik (Default Sistem)</option>
									   <option value="5">5 Detik</option>
									    <option value="10">10 Detik</option>
									   <option value="15">15 Detik</option>
									    <option value="20">20 Detik</option>
										 <option value="25">25 Detik</option>
										  <option value="30">30 Detik</option>
									  </select>
							     </div>
							<p>									  
					<div class="d-grid gap-2"> 
					     <button type="submit" class="btn btn-primary" id="blockui-3">SIMPAN</button>				 
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
    $('#formsinkron').submit(function(e){
    e.preventDefault();
    var data = new FormData(this);
    $.ajax(
    {
        type: 'POST',
        url: 'siswa/twaktu.php',
        enctype: 'multipart/form-data',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
			success: function(data){ 
            setTimeout(function()
            {
                window.location.reload();
            }, 1500);
		  
        }
    });
    return false;
});
</script>	
           