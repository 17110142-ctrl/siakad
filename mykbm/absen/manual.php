<?php
defined('APK') or exit('No Access');
$hari = date('D');
?>           
	<?php if ($ac == '') : ?>
	<?php

    if (empty($_GET['k'])) {
        $kelasmu = "";
    } else {
        $kelasmu = $_GET['k'];
    }
    if (empty($_GET['g'])) {
        $gurumu = "";
    } else {
        $gurumu = $_GET['g'];
    }
	 if (empty($_GET['m'])) {
        $mapelmu = "";
    } else {
        $mapelmu = $_GET['m'];
    }
 		$map = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran where id='$mapelmu' "));
		$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and kelas='$kelasmu'"));
		$jsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where kelas='$kelasmu'"));
		$jadwalManualId = 0;
		if ($kelasmu && $mapelmu && $gurumu) {
			$jadwalManual = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_jadwal FROM jadwal_mapel WHERE kelas='$kelasmu' AND mapel='$mapelmu' AND guru='$gurumu' LIMIT 1"));
			if ($jadwalManual) {
				$jadwalManualId = $jadwalManual['id_jadwal'];
			}
		}
	?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">MANUAL PRESENSI <span class="badge badge-primary"><?= $kelasmu ?></span> <span class="badge badge-warning"><?= $map['kode'] ?></span> </h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="alert alert-custom" role="alert">
									<strong>Perhatian ! </strong><br>
										<span>Dilakukan sebelum Jam Deteksi Alpha oleh Mesin, Jika Tidak muncul berarti sudah di absen oleh mesin Presensi. Silahkan masuk ke Menu Sinkron Presensi</span>
									</div>
									
									<form id="formabsen">
                                        <table id="datatab" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                  <th width="5%">NO</th>												  
												  <th>NAMA SISWA</th>													                                                 
                                                  <th>KETERANGAN</th>
												 							 
                                                </tr>
                                            </thead>											
                                            <tbody>	
											<?php
											$no = 0;
											$query = mysqli_query($koneksi,"SELECT id_siswa,kelas,nama FROM siswa WHERE NOT EXISTS(SELECT idsiswa,tanggal,kelas FROM absensi WHERE siswa.id_siswa=absensi.idsiswa AND absensi.tanggal='$tanggal')and kelas='$kelasmu'");
											  while ($data = mysqli_fetch_array($query)) :
											 
											$no++;
											   ?>
											   <tr>
                                                 <td><?= $no; ?></td>
                                                  
													<td><?= $data['nama'] ?></td>
													<td>
													<input type="radio" name="absen[]<?php echo $no; ?>" value="A" checked> &nbsp;A  &nbsp;&nbsp;&nbsp;<input type="radio" name="absen[]<?php echo $no; ?>" value="S"> &nbsp;S
													  &nbsp;&nbsp;&nbsp;<input type="radio" name="absen[]<?php echo $no; ?>" value="I"> &nbsp;I
													  <input type="hidden" name="tanggal[]" value="<?= date('Y-m-d') ?>" >
													  <input type="hidden" name="idsiswa[]" value="<?= $data['id_siswa'] ?>" >
													  <input type="hidden" name="kelas[]" value="<?= $data['kelas'] ?>" >
													  <input type="hidden" name="mapel[]" value="<?= $mapelmu ?>" >
													  <input type="hidden" name="guru[]" value="<?= $gurumu ?>" >
													  <input type="hidden" name="jadwal[]" value="<?= $jadwalManualId ?>" >
													   <input type="hidden" name="bulan[]" value="<?= date('m') ?>" >
													   <input type="hidden" name="tahun[]" value="<?= date('Y') ?>" >
													  
													</td>
													
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												<div class="kanan">
												<?php if($jabsis<>$jsis): ?>
												<button type="submit" class="btn btn-primary">SIMPAN</button>
												<?php endif; ?>
												</div>
												</form>
											</div>
										</div>
										</div>
									<div class="col-md-4">
							 
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                       <h5 class="bold">MANUAL PRESENSI MAPEL</h5>
										
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
									<div class="alert alert-custom" role="alert">
									<strong>Perhatian ! </strong><br>
										<span>Presensi Manual dapat diisi jika Hari sesuai Jadwal Mengajar</span>
									</div>
										 <label class="bold">Tanggal</label>
									  <div class="input-group mb-1">
                                        <input type="text" name="tgl" class="form-control" value="<?= date('Y-m-d') ?>" readonly="true" >
                                        </div>
										<label class="bold">Guru Pengampu</label>
									  <div class="input-group mb-1">
                                        <select id="guru" class='form-select guru' style='width:100%' required>
                                          <?php 
										       if($user['level']=='admin'):
											   $sql=mysqli_query($koneksi,"SELECT hari,guru FROM jadwal_mapel WHERE hari='$hari' GROUP BY guru");
											   elseif($user['level']=='guru'):
											   $sql=mysqli_query($koneksi,"SELECT hari,guru FROM jadwal_mapel WHERE hari='$hari' and guru='$user[id_user]' GROUP BY guru");
											   endif;
											   while ($data=mysqli_fetch_array($sql)) {	?>
                                               <?php  $peg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$data[guru]'"));  ?>											   
												<option value="<?= $data['guru'] ?>"><?= $peg['nama'] ?></option>
											 <?php  } ?>
                                          </select>     
                                        </div>
										<label class="bold">Kelas</label>
									  <div class="input-group mb-1">
									   <select  id="kelas" class='form-select kelas' style='width:100%' required>
                                        <option value="">Pilih Kelas</option> 									   
                                          <?php 
										       if($user['level']=='admin'):
											   $sql=mysqli_query($koneksi,"SELECT hari,kelas FROM jadwal_mapel WHERE hari='$hari' GROUP BY kelas");
											   elseif($user['level']=='guru'):
											   $sql=mysqli_query($koneksi,"SELECT hari,kelas,guru FROM jadwal_mapel WHERE hari='$hari' and guru='$user[id_user]' GROUP BY kelas");
											   endif;
											   while ($data=mysqli_fetch_array($sql)) {											  
												echo '<option value="'.$data['kelas'].'">'.$data['kelas'].'</option> ';
											   }
											  ?>
                                          </select>
                                          
                                        </div>
										<label class="bold">Mata Pelajaran</label>
									  <div class="input-group mb-1">
                                        <select id="mapel" class='form-select mapel' style='width:100%' required>
										
                                          </select>                                                    
                                        </div>
										 
										
										<div class="widget-payment-request-actions m-t-lg d-flex">
                                         <button id="pilih" class="btn btn-primary flex-grow-1 m-l-xxs">Pilih Kelas</button>
                             
											</div>
										<script type="text/javascript">
										$('#pilih').click(function() {
										var k = $('.kelas').val();
										var g = $('.guru').val();
										var m = $('.mapel').val();
										location.replace("?pg=<?= enkripsi('manual') ?>&k=" + k + "&g=" + g + "&m=" + m);
										}); 
									</script>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
					<script>
					$("#kelas").change(function() {
						var kelas = $(this).val();
						var guru = $("#guru").val();						
						console.log(kelas + guru);
						$.ajax({
							type: "POST",
							url: "absen/tabsen.php?pg=mapel", 
							data: "kelas=" + kelas + '&guru=' + guru, 
							success: function(response) { 
								$("#mapel").html(response);
								console.log(response);
							},
							error: function(xhr, status, error) {
								console.log(error);
							}
						});
					});
					</script>
				 <script>
						$('#formabsen').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'absen/input.php',
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
										}, 2000);
									}
								})
								return false;
							});
							</script>
									
				
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					
