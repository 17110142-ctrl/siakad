 <?php
defined('APK') or exit('No Access'); 

$pesan7 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='7'"));
$pesan8 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='8'"));

?>

                        <div class="row">
                           <div class="col-xl-6">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                      <h5 class="card-title" style="color:blue;">NOTIF MASUK PEMBINA ESKUL</h5>
										<button class="btn btn-secondary kanan" type="button" disabled>
                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									<?= buat_tanggal('D, d M Y') ?> </button>
										</h5>
                                    </div>
                                    <div class="card-body">
									 
                                       <form id="formsiswa" class="row g-1"> 
									    <div class="col-md-12">
										<label class="bold">Pesan Pembuka</label>									   
                                          <input type="text" class="form-control" name="pesan1" value="<?= $pesan7['pesan1'] ?>" >
										  </div>
                                        <div class="col-md-8">
										<label class="bold">Isi Pesan</label>									   
                                          <textarea class="form-control" name="pesan2" spellcheck="false" rows="4"><?= $pesan7['pesan2'] ?></textarea>
										  </div>
										  <div class="col-md-4">
										<label class="bold"></label>									   
                                          <textarea class="form-control" rows="4" readonly>Nama Pegawai</textarea>
										  </div>
										 
										  <div class="col-md-8">
										<label class="bold"></label>									   
                                          <textarea class="form-control" name="pesan3" spellcheck="false" rows="4"><?= $pesan7['pesan3'] ?></textarea>
										  </div>
										   <div class="col-md-4">
										<label class="bold"></label>									   
                                          <textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea>
										  </div>
										  <div class="col-md-12">
										<label class="bold">Pesan Penutup</label>									   
                                          <textarea class="form-control" name="pesan4" spellcheck="false" rows="4"><?= $pesan7['pesan4'] ?></textarea>
										  </div><p>
										  <div class="d-grid gap-2">
											<button type="submit" class="btn btn-primary">Simpan Notif Masuk</button>
										   
										</div>
                                       </form>										  
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
							 url: 'pesan/tsetting.php?pg=masukekpeg',
							data: data,
							cache: false,
							contentType: false,
							processData: false,
							beforeSend: function() {
												$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
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
						 
						 
						 
                              <div class="col-xl-6">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                      <h5 class="card-title" style="color:red;">NOTIF PULANG PEMBINA ESKUL</h5>
										<button class="btn btn-secondary kanan" type="button" disabled>
                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									<?= buat_tanggal('D, d M Y') ?> </button>
										</h5>
                                    </div>
                                    <div class="card-body">
									   <form id="formsiswa2" class="row g-1"> 
									    <div class="col-md-12">
										<label class="bold">Pesan Pembuka</label>									   
                                          <input type="text" class="form-control" name="pesan1" value="<?= $pesan8['pesan1'] ?>" >
										  </div>
                                        <div class="col-md-8">
										<label class="bold">Isi Pesan</label>									   
                                          <textarea class="form-control" name="pesan2" spellcheck="false" rows="4"><?= $pesan8['pesan2'] ?></textarea>
										  </div>
										  <div class="col-md-4">
										<label class="bold"></label>									   
                                          <textarea class="form-control" rows="4" readonly>Nama Pegawai</textarea>
										  </div>
										 
										  <div class="col-md-8">
										<label class="bold"></label>									   
                                          <textarea class="form-control" name="pesan3" spellcheck="false" rows="4"><?= $pesan8['pesan3'] ?></textarea>
										  </div>
										   <div class="col-md-4">
										<label class="bold"></label>									   
                                          <textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea>
										  </div>
										  <div class="col-md-12">
										<label class="bold">Pesan Penutup</label>									   
                                          <textarea class="form-control" name="pesan4" spellcheck="false" rows="4"><?= $pesan8['pesan4'] ?></textarea>
										  </div><p>
										  <div class="d-grid gap-2">
										 <button type="submit" class="btn btn-primary">Simpan Notif Pulang</button>
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
					$('#formsiswa2').submit(function(e){
						e.preventDefault();
						var data = new FormData(this);
						$.ajax(
						{
							type: 'POST',
							 url: 'pesan/tsetting.php?pg=pulangekpeg',
							data: data,
							cache: false,
							contentType: false,
							processData: false,						
							beforeSend: function() {
												$('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
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