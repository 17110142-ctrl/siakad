<?php
defined('APK') or exit('No Access');
$jpes = mysqli_num_rows(mysqli_query($koneksi, "SELECT ruang FROM siswa WHERE ruang<>''"));
?>           
			<?php if ($ac == '') : ?>
			
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PESERTA UJIAN</h5>
										<div class="pull-right">
										<a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('update') ?>" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Peserta"><i class="material-icons">upload</i>UPDATE</a>
										</div>
									</div>
                                    <div class="card-body">
									<p><b style="color:red;">Siahkan Lengkapi Data Peserta Ujian</b><br>Jika tidak mau menggunakan No Peserta, Username dan Password dari Sistem, Silahkan Ubah pada Format Excel Update Peserta</p>
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NO PESERTA</th>
                                                    <th>NAMA PESERTA</th>
                                                    <th>ROMBEL</th>
                                                    <th>USERNAME</th>
                                                    <th>PASSWORD</th>
													 <th>SESI</th>
													  <th>RUANG</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE no_peserta<>''"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['no_peserta'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
                                                    <td><?= $data['kelas'] ?></td>
                                                    <td><?= $data['username'] ?></td>
                                                  <td><?= $data['password'] ?></td>
												  <td><?= $data['sesi'] ?></td>
												   <td><?= $data['ruang'] ?></td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
								</div>		
		 
				</div>	
            <?php elseif($ac == 'login'): ?>
			<?php
			$idu = $_GET['idu'];
			$uji = fetch($koneksi,'ujian',['id_ujian'=>$idu]);
			$sesi = $uji['sesi'];
			$level = $uji['level'];
			$pk = $uji['pk'];
			?>
                   <div class="row">
                          <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PESERTA BELUM LOGIN MAPEL <?= $uji['nama'] ?> SESI <?= $uji['sesi'] ?></h5>
									</div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NO PESERTA</th>
                                                    <th>NAMA PESERTA</th>
                                                    <th>ROMBEL</th>
                                                    <th>USERNAME</th>
                                                    <th>PASSWORD</th>
													 <th>SESI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											if($pk=='semua'):
											$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE NOT EXISTS(SELECT * FROM nilai WHERE nilai.id_siswa=siswa.id_siswa and nilai.id_ujian='$_GET[idu]') and sesi='$sesi' and level='$level'");
											 else:
											 $query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE NOT EXISTS(SELECT * FROM nilai WHERE nilai.id_siswa=siswa.id_siswa and nilai.id_ujian='$_GET[idu]') and sesi='$sesi' and level='$level' and jurusan='$pk'");
											 endif;
											 while ($data = mysqli_fetch_array($query)) :
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['no_peserta'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
                                                    <td><?= $data['kelas'] ?></td>
                                                    <td><?= $data['username'] ?></td>
                                                  <td><?= $data['password'] ?></td>
												  <td><?= $data['sesi'] ?></td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
								</div>		
		 
				</div>	
            <?php elseif($ac == enkripsi('update')): ?>
			<div class="row">
     <div class="col-md-8">  
			 <div class="card">
			<div class="card-header">
				<h5 class="card-title">Update Peserta Ujian</h5>
				
					<a href="siswa/prosessiswa" class="btn btn-sm btn-link pull-right" data-toggle="tooltip" data-placement="top" title="Download Format"><i class="material-icons">download</i> Download Format</a>
					
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
                                                <span class="widget-stats-title">PESERTA UJIAN </span>
                                                <span class="widget-stats-amount"><?= $jpes; ?> PD</span>
                                                <span class="widget-stats-info">dari <?= $jpes ?> PD</span><p>
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
             url: 'siswa/import_siswa.php',
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
						window.location.replace('?pg=<?= enkripsi(peserta) ?>');
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>	
             
                       
			
        <?php endif; ?>		 