<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$nis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis<>''"));
$nisn = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nisn<>''"));
$alamat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE alamat<>''"));
$tempat = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE t_lahir<>''"));
$lahir = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE tgl_lahir<>''"));
?>           
			<?php if ($ac == '') : ?>
			<div class="row">
                          
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">PESERTA DIDIK</h5>
										<div class="pull-right">
										<a href="?pg=<?= enkripsi('siswa') ?>&ac=<?= enkripsi('update') ?>" class="btn btn-primary"><i class="material-icons">upload</i>Update</a>   
                                    
									</div>
									</div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NISN</th>
                                                    <th>NAMA SISWA</th>
                                                    <th>ROMBEL</th>
                                                    <th>ALAMAT</th>
                                                    <th>TEMPAT LAHIR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nisn'] ?></td>
                                                     <td><?= $data['nama'] ?></td>
                                                    <td><?= $data['kelas'] ?></td>
                                                    <td><?= $data['alamat'] ?></td>
                                                  <td><?= $data['t_lahir'] ?></td>
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
								</div>		
		 <?php elseif ($ac == enkripsi('update')) : ?>
		               
					<div class="row">
                        
				<div class='col-md-7'>

					<div class="card">
                        <div class="card-header">
                                        <h5 class="card-title">UPDATE SISWA</h5>
										<div class="pull-right">
										<a href="siswa/proses.php"  class="btn btn-success"><i class="material-icons">download</i>Format</a>   
                                   
									</div>
									</div>
                              <div class="card-body">
							   <form id='formsiswa' >	
                                      <label>Pilih File Excel</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control' required='true' />
									    <span class="input-group-text">
											<button type="submit" id="blockui-3" class="btn btn-primary"><i class="material-icons">upload</i></button>
										</span>
                                        </div>	
								</form>
							</div>		
					     
						</div>
					</div>
					
                            <div class="col-xl-5">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                       
                                    </div>
                                    <div class="card-body edis2">
                                        <span class="text-muted m-b-xs d-block">showing 5 out of 9 in progress tasks.</span>
                                        <ul class="widget-list-content list-unstyled">
                                            <li class="widget-list-item widget-list-item-green">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">article</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        Complete NIS <?= $nis ?> dari <?= $jsiswa ?>
                                                    </a>
                                                    <span class="widget-list-item-description-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= ($nis/$jsiswa)*100 ?>%;" aria-valuenow="<?= ($nis/$jsiswa)*100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-blue">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">verified_user</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                       Complete NISN <?= $nisn ?> dari <?= $jsiswa ?>
                                                    </a>
                                                    <span class="widget-list-item-description-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= ($nisn/$jsiswa)*100 ?>%;" aria-valuenow="<?= ($nisn/$jsiswa)*100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-purple">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">watch_later</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        Complete Alamat <?= $alamat ?> dari <?= $jsiswa ?>
                                                    </a>
                                                    <span class="widget-list-item-description-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= ($alamat/$jsiswa)*100 ?>%;" aria-valuenow="<?= ($alamat/$jsiswa)*100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-yellow">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">extension</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                         Complete Tempat Lahir <?= $tempat ?> dari <?= $jsiswa ?>
                                                    </a>
                                                    <span class="widget-list-item-description-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= ($tempat/$jsiswa)*100 ?>%;" aria-valuenow="79" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-red">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">invert_colors</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                         Complete Tanggal Lahir <?= $lahir ?> dari <?= $jsiswa ?>
                                                    </a>
                                                    <span class="widget-list-item-description-progress">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: <?= ($lahir/$jsiswa)*100 ?>%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </span>
                                                </span>
                                            </li>
                                        </ul>
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
			success: function(data){ 
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			setTimeout(function()
				{
				window.location.replace('?pg=<?= enkripsi(siswa) ?>');
						}, 1500);
									  
						}
					});
				return false;
			});
		</script>	
        <?php endif; ?>		 