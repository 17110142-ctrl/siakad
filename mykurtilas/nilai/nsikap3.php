<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
	
	<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">INPUT NILAI SIKAP <span class="badge badge-primary">K-2013</span></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>TINGKAT</th>
                                                      <th>ROMBEL</th>														
                                                    <th>MATA PELAJARAN</th>
													 <th>GURU PENGAMPU</th>
													 <th>JML</th>
													  <th>INPUT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_rapor a join mapel_rapor b ON b.mapel=a.mapel WHERE a.kuri='1' and b.sikap='1' GROUP BY a.level,a.mapel,a.guru"); 
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_rapor a join mapel_rapor b ON b.mapel=a.mapel WHERE a.kuri='1' and b.sikap='1' and guru='$user[id_user]' GROUP BY a.level,a.mapel"); 	
											endif;
											  while ($data = mysqli_fetch_array($query)) :
											 $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											 $peg = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											  $model = fetch($koneksi,'level',['level'=>$data['level']]);
											$jnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barusikap where mapel='$data[mapel]' and guru='$data[guru]'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                      <td><h5><span class="badge badge-dark"><?= $data['level'] ?></span></h5></td>
													  <td>
													  <?php
														$dataArray = unserialize($data['kelas']);
														foreach ($dataArray as $key => $value) :
															echo $value . " ";
														endforeach;
														?>
														</td>
													   <td><?= $mapel['nama_mapel'] ?></td>
													  <td><?= $peg['nama'] ?></td>
													    <td><h5><span class="badge badge-danger"><?= $jnil; ?></span></h5></td>
													 											 
													  <td>
                                                       <?php if($model['model_rapor']=='2'): ?>													  
													<a href="?pg=nsikap3&ac=lihat&idj=<?= $data['id'] ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Input Nilai"><i class="material-icons">edit</i> </a>											
													<?php else : ?>
													<button class="btn btn-sm btn-light" disabled><i class="material-icons">lock</i></button>
													<?php endif; ?>
													</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
										</div>
									</div>
				<?php elseif($ac == 'lihat'): ?>
	            <?php 
				$idj = $_GET['idj'];
				$jadwal = fetch($koneksi,'jadwal_rapor',['id'=>$idj]);
				$guru = fetch($koneksi,'users',['id_user'=>$jadwal['guru']]);				
				$map = fetch($koneksi,'mata_pelajaran',['id'=>$jadwal['mapel']]);
				?>
				
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SIKAP <span class="badge badge-primary"><?= $map['kode'] ?></span> <span class="badge badge-primary"><?= $jadwal['level'] ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                   <th>NIS</th>
                                                    <th>NAMA LENGKAP</th>
													 <th>ROMBEL</th>
													 <th>PRED</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php											
											$no=0;
											$query = mysqli_query($koneksi,"SELECT * FROM sosial WHERE mapel='$map[id]' and guru='$jadwal[guru]'");											
											  while ($data = mysqli_fetch_array($query)) :
											  $siswa = fetch($koneksi,'siswa',['nis'=>$data['nis']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>                           
                                                     <td><?= $data['nis'] ?></td>
													 <td><?= ucwords(strtolower($siswa['nama'])); ?></td>
													 <td><?= $data['kelas'] ?></td>
													 <td><h5 class="bold"><span class="badge badge-primary"><?= $data['pred'] ?></span></h5></td>
													
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
										<h5 class="card-title">NILAI SIKAP</h5>									
									</div>
                                    <div class="card-body">
									<form id="formnilai" action="?pg=nsikap3&ac=input" method='post' enctype='multipart/form-data'>
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">INPUT NILAI</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<p>
                                             <div class="widget-payment-request-info-item">	
									<label class="bold">Semester</label>
								<div class="input-group mb-2">
								<select name='smt'  class='form-select' required='true' style="width: 100%">
								    <option value="<?= $setting['semester'] ?>"><?= $setting['semester'] ?></option>									
									 </select>
							     </div>															 
                                   
								<label class="bold">Tingkat</label>
								<div class="input-group mb-2">
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value="<?= $jadwal['level'] ?>"><?= $jadwal['level'] ?></option>									
									 </select>
							     </div>												
									<label class="bold">Pilih Kelas</label>
									  <div class="input-group mb-2">
                                     <select name='kelas' id='kelas' class='form-select' required='true' style="width: 100%">
								     <option value=''>Pilih Rombel</option>
								                <?php
										$dataArray = unserialize($jadwal['kelas']);
										foreach ($dataArray as $key => $value) :
										echo $value . " ";
										echo "<option value='$value'>$value</option>";	
										endforeach;
										?>									
									 </select>                                    
									   </div>
									   <label class="bold">Mapel</label>
								<div class="input-group mb-2">
								<select name="mapel" class='form-select' required='true' style="width: 100%">
								    <option value="<?= $jadwal['mapel'] ?>"><?= $map['nama_mapel'] ?></option>									
									 </select>
							     </div>
								<label class="bold">Guru Pengampu</label>
								<div class="input-group mb-2">
								<select name="guru" class='form-select' required='true' style="width: 100%">
								    <option value="<?= $jadwal['guru'] ?>"><?= $guru['nama'] ?></option>									
									 </select>
							     </div>									 
									   <div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
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
					
					
					
      <?php elseif ($ac == 'input') : ?>
	  <?php 
		$kelas = $_POST['kelas'];
		$mapel = $_POST['mapel'];
		$guru = $_POST['guru'];
		$map = fetch($koneksi,'mata_pelajaran',['id'=>$mapel]);
		
		?>
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SIKAP <span class="badge badge-primary"><?= $map['kode'] ?></span> <span class="badge badge-primary"><?= $kelas; ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
									<thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>N I S</th>
                                                    <th>NAMA SISWA</th>
													   <th>DIMENSI</th>
													    <th>KETERANGAN</th>
														<th width="5%"></th>
                                                </tr>
                                            </thead>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'"); 	
											  while ($siswa = mysqli_fetch_array($query)) :
											$niss = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barusikap WHERE nis='$siswa[nis]'"));
											$spi =fetch($koneksi,'barusikap',['nis'=>$siswa['nis']]);
											$no++;
											   ?>
											   	
                                                <tr>
                                                   <td><?= $no; ?></td> 
                                                   <td><?= $siswa['nis'] ?></td>													
													<td><?= $siswa['nama'] ?></td>																											   
													 <td>
													 
													 <button class="btn btn-primary" type="button" disabled>
														<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
														<?= $niss ?> dimensi
													</button>
													 
													 </td>	
													<td>
														<?php if($niss==6): ?>
														<b>Lengkap</b>
														<?php else: ?>
														<h5> <span class="badge badge-secondary">Kurang <?= 6 - $niss ?> dimensi</span></h5>
														<?php endif; ?>
													</td>
													
													 <td>
													 <?php if($niss==6): ?>
													 <button class="btn btn-sm btn-light" disabled><i class="material-icons">lock</i></button>
													 <?php else: ?>
													 <a href="?pg=nsikap3&ac=inputbaru&ids=<?= $siswa['id_siswa'] ?>"  class="btn btn-sm btn-primary"><i class="material-icons">edit</i></a>
												<?php endif; ?>
												</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
					       
				</div>	
				 <?php elseif ($ac == 'input2') : ?>
		 <?php 
		$kelas = $_GET['k'];
		
		?>
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">NILAI SIKAP <span class="badge badge-primary"><?= $map['kode'] ?></span> <span class="badge badge-primary"><?= $kelas; ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
									<thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>N I S</th>
                                                    <th>NAMA SISWA</th>
													   <th>DIMENSI</th>
													    <th>KETERANGAN</th>
														<th width="5%"></th>
                                                </tr>
                                            </thead>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'"); 	
											  while ($siswa = mysqli_fetch_array($query)) :
											$niss = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barusikap WHERE nis='$siswa[nis]'"));
											$spi =fetch($koneksi,'barusikap',['nis'=>$siswa['nis']]);
											$no++;
											   ?>
											   	
                                                <tr>
                                                   <td><?= $no; ?></td> 
                                                   <td><?= $siswa['nis'] ?></td>													
													<td><?= $siswa['nama'] ?></td>																											   
													 <td>
													 
													 <button class="btn btn-primary" type="button" disabled>
														<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
														<?= $niss ?> dimensi
													</button>
													 
													 </td>	
													<td>
														<?php if($niss==6): ?>
														<b>Lengkap</b>
														<?php else: ?>
														<h5> <span class="badge badge-secondary">Kurang <?= 6 - $niss ?> dimensi</span></h5>
														<?php endif; ?>
													</td>
													
													 <td>
													 <?php if($niss==6): ?>
													 <button class="btn btn-sm btn-light" disabled><i class="material-icons">lock</i></button>
													 <?php else: ?>
													 <a href="?pg=nsikap3&ac=inputbaru&ids=<?= $siswa['id_siswa'] ?>"  class="btn btn-sm btn-primary"><i class="material-icons">edit</i></a>
												<?php endif; ?>
												</td>
                                                </tr>
												<?php endwhile; ?>
												</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
					       
				</div>	
				 <?php elseif ($ac == 'inputbaru'): ?>	
				<?php 
				$siswa=fetch($koneksi,'siswa',['id_siswa'=>$_GET['ids']]); 
				$kelas=$siswa['kelas'];
				?>					 
							 <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
			              <h5 class="bold">NILAI SIKAP <span class="badge badge-secondary"> <?= $kelas ?></span></h5>
										
                                    </div>
                                    <div class="card-body">
                    <form id='menu'  class="form-horizontal">
					 <input type="hidden" name="id" value="<?= $siswa['nis'] ?>"> 
									<div class="form-group">
							<label  class="col-md-3">Semester</label>
							<div class="col-sm-5">
						   <select name="smt" id="smt" class="form-control" style="width: 100%;" readonly>
                               <option value="<?= $setting['semester']  ?>">Semester <?= $setting['semester']  ?></option>
							      
                                  </select>
							</div>
		                    </div>
							<div class="form-group">
							<label  class="col-md-3">N I S</label>
							<div class="col-sm-5">
						   <input type="text" name="nis" value="<?= $siswa['nis']  ?>" class="form-control" readonly>
							</div>
		                    </div>
							<div class="form-group">
							<label  class="col-md-3">Nama Siswa</label>
							<div class="col-sm-9">
						   <input type="text" value="<?= $siswa['nama']  ?>" class="form-control" readonly>
							</div>
		                    </div>
							<div class="form-group">
							<label  class="col-md-3">Dimensi</label>
							<div class="col-sm-9">
							 <select name='dimensi' id="dimensi" class='form-control' required='true' style="width:100%;">
							 <option value=''>--Pilih Dimensi--</option>
							  
                                 <?php $quem = mysqli_query($koneksi, "SELECT * FROM m_dimensi"); ?>
                                     <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                         <option value="<?= $m['id_dimensi'] ?>"><?= $m['dimensi'] ?></option>
                                                <?php endwhile ?>
                                                    </select>
                                                    
                                       </div>
						            </div>
								<div class="form-group">
							<label  class="col-md-3">Elemen</label>
							<div class="col-sm-9">
							 <select name='elemen' id="elemen" class='form-control' required='true' style="width:100%;">
							 <option value=''>--Pilih Elemen--</option>
							  
                                </select>
								  
                                       </div>
						            </div>
								<div class="form-group">
							<label  class="col-md-3">Sub Elemen</label>
							<div class="col-sm-9">
							 <select name='sub_elemen' id="sub_elemen" class='form-control' required='true' style="width:100%;">
							 <option value=''>--Pilih Sub Elemen--</option>
							  
                                </select>
                                       </div>
						            </div>
								
							<div class="pull-right">
					   <button type='submit' name='submit' class='btn btn-primary'> Simpan</button>                    
					 </div>
					  </form>
                        </div>
					 </div>
                        </div>
						</div>
						  <script>
				  $("#dimensi").change(function() {
					var dimensi = $(this).val();
					console.log(dimensi);
					$.ajax({
						type: "POST", 
						url: "nilai/crud_sikapbaru.php?pg=ambil_elemen", 
						data: "dimensi=" + dimensi, 
						success: function(response) { 
							$("#elemen").html(response);
						}
					});
				});						
			</script>	
           <script>
				  $("#elemen").change(function() {
					var elemen = $(this).val();
					console.log(elemen);
					$.ajax({
						type: "POST", 
						url: "nilai/crud_sikapbaru.php?pg=ambil_sub_elemen", 
						data: "elemen=" + elemen, 
						success: function(response) { 
							$("#sub_elemen").html(response);
						}
					});
				});						
			</script>		
			
			 <script>
		 $('#menu').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'nilai/crud_sikapbaru.php?pg=tambah',
            data: $(this).serialize(),
            beforeSend: function() {
                $('form button').on("click", function(e) {
                    e.preventDefault();
                });
            },
            success: function(data) {
                console.log(data);
                if (data == 'OK') {
                    iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berasil disimpan',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'			  
                });
                    setTimeout(function() {
                         window.location.replace('?pg=nsikap3&ac=input2&k=<?= $kelas?>');
                    }, 2000);
                } else {
                    iziToast.error({
                        title: 'Gagal!',
                        message: 'Dimensi Sudah tercatat',
                        position: 'topRight'
                    });
					setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                }

            }
        });
        return false;
    });
</script>
							
							
							
				            
					  <?php endif ?>
					