<?php
defined('APK') or exit('No Access');
?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA KURIKULUM</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th style="text-align:center;" width="5%">TINGKAT</th>
                                                    <th>KURIKULUM</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT level,kurikulum FROM kelas GROUP BY level"); 
											  while ($data = mysqli_fetch_array($query)) :
											$kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kurikulum']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td style="text-align:center;"><h5><span class="badge badge-primary"><?= $data['level'] ?></span></h5></td>
                                                     <td><?= $kuri['nama_kurikulum'] ?></td>
													
													 
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
                                        <h5 class="card-title">INPUT KURIKULUM</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/<?= $setting['logo'] ?>" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">KURIKULUM</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formguru' >	
									 <label>Tingkat</label>
									  <div class="input-group mb-1">
                                      <select name='level' class='form-select' style='width:100%' required>
                                                <option value=''>Pilih Tingkat</option>
                                                <?php $que = mysqli_query($koneksi, "SELECT level FROM kelas GROUP BY level"); ?>
                                                <?php while ($tkt = mysqli_fetch_array($que)) : ?>

                                                    <option value="<?= $tkt['level'] ?>"><?= $tkt['level'] ?></option>"

                                                <?php endwhile ?>
												</select>
                                        </div>	
										 <label>Kurikulum</label>
									  <div class="input-group mb-1">
                                       <select name='kuri' class='form-select' style='width:100%' required>
                                                <option value=''>Pilih Kurikulum</option>
                                                <?php $query = mysqli_query($koneksi, "SELECT * FROM m_kurikulum"); ?>
                                                <?php while ($kuri = mysqli_fetch_array($query)) : ?>
                                                    <option value="<?= $kuri['idk'] ?>"><?= $kuri['nama_kurikulum'] ?></option>"
                                                <?php endwhile ?>
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
             url: 'kurikulum/tkuri.php',
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
                        
            