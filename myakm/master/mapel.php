<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jmap = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"));

?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">MATA PELAJARAN</h5>
										
                                    </div>
                                    <div class="card-body">
									<br>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>KODE</th>
                                                   <th>MATA PELAJARAN</th>
												   
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"); 
											  while ($data = mysqli_fetch_assoc($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['kode'] ?></td>
                                                    <td><?= $data['nama_mapel'] ?></td>
													
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									
					       <div class="col-md-4">
                       <div class="card" id="blockui-card-1">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">UPLOAD MATA PELAJARAN</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/buku.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Mata Pelajaran</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<br>
									<a href="master/M_MAPEL.xlsx" class="btn btn-link pull-right"><i class="material-icons">download</i>Download Format</a>   
									<br>
									<form id='formmapel' >	
                                      <label>Pilih File</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control' required='true' />
									    <span class="input-group-text">
											<button type="submit" class="btn btn-primary"><i class="material-icons">upload</i></button>
										</span>
                                        </div>	
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					
					<script>
    $('#formmapel').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'master/import_mapel.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			processData: false,
			beforeSend: function() {
                $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 500);
            },
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
                        
                               