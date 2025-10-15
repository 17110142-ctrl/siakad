<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
?>
<div class="row">
     <div class="col-md-8">  
			 <div class="card">
			<div class="card-header">
				<h5 class="card-title">Import Data Peserta Didik Baru</h5>
				
					<a href="pdb/M_SISWA_BARU.xlsx" class="btn btn-sm btn-link pull-right" data-toggle="tooltip" data-placement="top" title="Download Format"><i class="fa fa-download"></i> Download Format</a>
					
								</div>
				                <div class="card-body">  
								<form id='formsiswa' >								 
								    <div class='col-md-12'>
                                      <label>Pilih File</label>
									  <div class="input-group">
                                       <input type='file' name='file' class='form-control' required='true' />
									   <span class="input-group-btn">
											<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
										</span>
                                    </div>
								</form>
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">storage</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA </span>
                                                <span class="widget-stats-amount"><?= $jsiswa; ?> PD</span>
                                                <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span><p>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
				
			
	<script>
    $('#formsiswa').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'pdb/import_siswa.php',
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
             
                       