<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">REGISTRASI RFID SISWA</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>N I S</th>
                                                    <th>NAMA SISWA</th>
													 <th>ROMBEL</th>
													 <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nokartu is NULL"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nis'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
													  <td><?= $data['kelas'] ?></td>
													  <td>
											
											  <a href="?pg=register&ids=<?= $data['id_siswa'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Registrasi RFID"><i class="material-icons">edit</i></button></a>
											</td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">INPUT KARTU RFID</h5>
										<a href="?pg=pelanggan" class="btn btn-primary"><i class="material-icons">crisis_alert</i>Cek Data</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">Data Siswa</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<?php 
											$ids = $_GET['ids'];
											$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
											?>
									<div class="widget-payment-request-info m-t-md">
									<form id='formkartu' >	
									 <label>N I S</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nis' class='form-control' value="<?= $siswa['nis'] ?>" required="true"/>
									    <input type='hidden' name='ids' class='form-control' value="<?= $siswa['id_siswa'] ?>"   />
									   
                                        </div>	
										 <label>Nama Lengkap</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' class='form-control' value="<?= $siswa['nama'] ?>" required="true" />
                                        </div>
										<label>Rombel</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' class='form-control' value="<?= $siswa['kelas'] ?>" required="true" />
                                        </div>
										<div class="d-grid gap-2">
										<button class="btn btn-dark" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Loading...
										</button>
										 </div>
										<label>No Kartu</label>
									  <div class="input-group mb-1" id="norfid">
                                       
                                        </div>
										<p>
										<div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
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
					<script type="text/javascript">
						$(document).ready(function(){
							setInterval(function(){
								$("#norfid").load('rfid/nokartu.php')
							}, 1000);  
						});
					</script>
					
					   <script>
    $('#formkartu').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'rfid/trfid.php',
            data: data,
			cache: false,
			contentType: false,
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
				window.location.replace('?pg=register');
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>		
		     