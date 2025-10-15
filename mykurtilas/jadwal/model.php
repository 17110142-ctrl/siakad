<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">MODEL RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="10%">NO</th>                                               
                                                    <th>TKT</th>
													 <th>KELAS</th>
                                                    <th>KURIKULUM</th>
													 <th>MODEL RAPOR</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE kurikulum='1'"); 
											  while ($data = mysqli_fetch_array($query)) :
										   $model = fetch($koneksi,'m_rapor',['idr'=>$data['model_rapor']]);
										   $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kurikulum']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><h5><span class="badge badge-dark"><?= $data['level'] ?></span></h5></td>
													 <td><h5><span class="badge badge-success"><?= $data['kelas'] ?></span></h5></td>
													  <td><h5><span class="badge badge-primary"><?= $kuri['nama_kurikulum'] ?></span></h5></td>
													  <td><?= $model['model'] ?></td>
													  
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
                                  
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									<form id='formjadwal' class="row g-1">                         
                               <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Tingkat</option>
										<?php
										$lev = mysqli_query($koneksi, "SELECT level,kurikulum FROM kelas WHERE kurikulum='1' GROUP BY level");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[level]'>$level[level]</option>";
										}
										?>
									 </select>
							     </div>	
							             
							
							<div class="col-md-12">
								<label class="form-label bold">Model Rapor</label>
								<select name='model' id='model' class='form-select' required='true' style="width: 100%">
								</select>
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
				  
							 <script>	
							$("#level").change(function() {
							var level = $(this).val();
							console.log(level);
							$.ajax({
							type: "POST",
							url: "jadwal/tjadwal.php?pg=model", 
							data: "level=" + level, 
							success: function(response) { 
							$("#model").html(response);
									}
								});
							});
							</script>
							<script>
						$('#formjadwal').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'jadwal/tjadwal.php?pg=rapor',
									enctype: 'multipart/form-data',
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
									success: function(data) {
									   
										setTimeout(function() {
											window.location.reload();
										}, 1500);
									}
								})
								return false;
							});
							</script>

	
					  <?php endif ?>
					  
	  
					  
									<script>
									$('#datatable1').on('click', '.hapus', function() {
									var id = $(this).data('id');
									console.log(id);
									swal({
											  title: 'Yakin hapus data?',
											  text: "You won't be able to revert this!",
											  type: 'warning',
											  showCancelButton: true,
											  confirmButtonColor: '#3085d6',
											  cancelButtonColor: '#d33',
											  confirmButtonText: 'Ya, Hapus!',
											  cancelButtonText: "Batal"				  
									}).then((result) => {
										if (result.value) {
											$.ajax({
											   url: 'jadwal/tmapel.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													  iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil dihapus',
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
										}
										return false;
									})

								});

							</script>    		  
					  
<script>

  $(document).ready(function() {
      
    $(function() {

      $('.sikap2').hide();

      $('.kuri').change(function() {

        if ($("option[value='1']").is(":checked")) {

          $('.sikap2').show();

        } else {

          $('.sikap2').hide();

        }

      });

    });
    
  });
  
</script>		