<?php 
$lulus = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where keterangan='1'"));
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl"));
$tlulus = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where keterangan='0' and level='$skl[tingkat]'"));
 ?>
<div class='row'>
         <div class="col-xl-4">
                                <div class="card widget widget-info">
                                    <div class="card-body">
                                        <div class="widget-info-container">
                                            <div class="widget-info-image" style="background: url('../images/<?= $setting[logo] ?>')"></div>
                                            <h6 class="widget-info-title">NEW SANDIK</h6>
                                            <p class="widget-info-text"><b>SISTEM APLIKASI PENDIDIK</b></p>
                                            
                                        </div>
                                    </div>
                                </div>
								 </div>
						   <div class="col-md-4">  
				            <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                              <i class="material-icons">storage</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Kepala Sekolah</span>
                                                <span class="widget-stats-info"><?= $setting['kepsek']; ?></span>
                                        
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
								</div>
				            <div class="col-md-4">  
				                   <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                               <i class="material-icons">home</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-info">Telegram</span>
                                                <span class="widget-stats-info"><a href="https://t.me/+s4E-i6jIbLQzNzFl" target="_blank" class="btn btn-sm btn-link"><b>Telegram Group</b></a></span>
                                        
                                            </div>
                                            </div>
                                        </div>
                                    </div>
								</div>
								<div class="col-md-4"></div>  
								<div class="col-md-4" style="margin-top:-180px;">  
				            <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                              <i class="material-icons">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">LULUS</span>
                                                <span class="widget-stats-amount"><?= $lulus; ?></span>
                                        
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
								</div>
								<div class="col-md-4" style="margin-top:-180px;">  
				            <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                              <i class="material-icons">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">TIDAK LULUS</span>
                                               <span class="widget-stats-amount"><?= $tlulus; ?></span>
                                        
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
								</div>
							</div>
					
									
                        <div class='col-md-12'>
						   <div class="card">
							 <div class="card-header">	
								  <h5 class='card-title'>
								  <i class="fas fa-trash"></i> RESET DATA SKL</h5></div>   
                                       <div class="card-body">         
												<div class='checkbox'>
												 <form id='formhapusdata' action='' method='post'>
                                                     
													
                                                </div>
                                            </div>
											<div class="card-body">  
                                            <div class="row mb-1">
													<label  class="col-md-4 col-form-label bold">Password Admin</label>
													<div class="col-sm-8">
													<div class="input-group">
                                                    <input type='password' name='password' value="admin##" class='form-control' readonly='true' />
                                                <span class="input-group-btn">
												 <button type='submit' name='submit3' class='btn btn-outline-danger'><i class='fa fa-trash'></i> Reset</button>
												</span>
												</div>
                                               </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#formhapusdata').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'treset.php',
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