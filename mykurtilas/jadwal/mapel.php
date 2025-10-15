<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">MAPEL RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
									<p>Untuk menghapus klik Mapel</p>
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>TKT</th>
                                                    <th>JURUSAN</th>
													 <th>MAPEL</th>
													  <th>URUT</th>
													  
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE kurikulum='1' ORDER BY idm DESC"); 
											  while ($data = mysqli_fetch_array($query)) :
										   $map = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
										   $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kurikulum']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['tingkat'] ?></td>
                                                      <td><?= $data['pk'] ?></td>
													  <td><a href="#" data-id="<?= $data['idm'] ?>" class="hapus link" style="text-decoration:none"><?= $map['nama_mapel'] ?></a></td>
													  
													  <td><?= $data['urut'] ?></td>
													  
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
                               <div class="col-md-6">
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
							             <div class="col-md-6">
								<label class="form-label bold">Jurusan</label>
								<select name='pk' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Jurusan</option>
										<?php
									$jQ = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
									while ($jrs = mysqli_fetch_array($jQ)) :
									echo "<option value='$jrs[jurusan]'>$jrs[jurusan]</option>";
										endwhile;
										?>
										</select>
							</div> 
							<div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select kuri' required='true' style="width: 100%">
								   
									 </select>
							</div>
								 
							<div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select' required='true' style="width: 100%">
								   <option value=''>Pilih Mapel</option>
								<?php
									$mpl = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
									while ($mapel = mysqli_fetch_array($mpl)) { ?>
									<option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
									<?php } ?>
									 </select>
							</div>
								 <div class="col-md-12">
								<label class="form-label bold">No Urut Rapor</label>
								<input type="number" name="urut" class="form-control" required="true" >
							          </div>
									
								   <p>
								          <div class="col-md-12">
								<label class="form-label bold">Kelompok</label>
								<select name='kelompok'  class='form-select' required='true' style="width: 100%">
								   <option value=''>Pilih Kelompok</option>
								   <?php if($setting['jenjang']=='SD' OR $setting['jenjang']=='SMP'): ?>
								     <option value='A'>Kelompok A</option>
									  <option value='B'>Kelompok B</option>
									  <?php endif; ?>
									  
									  <?php if($setting['jenjang']=='SMA'): ?>
								     <option value='A'>A. Umum</option>
									  <option value='B'>B.Umum</option>
									   <option value='C'>C. Peminatan</option>
									  <?php endif; ?>
									   <?php if($setting['jenjang']=='SMK'): ?>
								     <option value='A'>A. Muatan Nasional</option>
									  <option value='B'>B. Muatan Kewilayahan</option>
									   <option value='C1'>C1. Dasar Bidang Keahlian</option>
									    <option value='C2'>C2. Dasar Program Keahlian</option>
										<option value='C3'>C3. Kompetensi Keahlian</option>
									  <?php endif; ?>
									 </select>
							         </div>  
									 <p>
									 <div class="sikap2">
									 <div class="d-grid gap-2">
									  <button class="btn btn-primary" type="button" disabled>
											<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
											Khusus Nilai Sikap dan K-13
										</button>
										</div> 
										
									   <div class="col-md-12">
								<label class="form-label bold">Sikap</label>
								<label>Jika Mapel <b style="color:blue">AGAMA</b> pilih <b style="color:blue">Sikap Spiritual</b><p>Jika Mapel <b style="color:red">PPKN</b> pilih <b style="color:red">Sikap Sosial</b> </label>
								
								<select name='sikap'  class='form-select'  style="width: 100%">
								    <option value=''>Tidak</option>
										<option value='1'>Sikap Spiritual</option>
										<option value='2'>Sikap Sosial</option>
										</select>
							      </div>
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
							url: "jadwal/tmapel.php?pg=kuri", 
							data: "level=" + level, 
							success: function(response) { 
							$("#kuri").html(response);
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
									 url: 'jadwal/tmapel.php?pg=mapel',
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
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);	
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