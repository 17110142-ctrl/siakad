<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php if ($ac == '') { ?>
<div class='row'>

        <div class='col-md-7'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'> NILAI PER SEMESTER</h5>
					</div>    
			  
						<div class="card-body">
						<span class="alert-text" style="text-align:justify">
                  Pastikaan Nomor Urut Mapel dan Kelompok Mapel Sudah di isi. Jika belum diisi maka tidak muncul pada Pilih Mapel</b>          
							  </span>
						<form id="formpengaturan" action='proses.php'  method='post' class="form-horizontal" enctype='multipart/form-data'>
                          <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Tingkat</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-select' required='true'>
                                      <option value=''>Pilih Tingkat</option>
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM skl"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['tingkat'] ?>"><?= $m['tingkat'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
									<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Jurusan</label>
							<div class="col-sm-7">
							   <select id='pk' name='pk' class='form-select' required='true'>
                                      <option value=''>Pilih Jurusan</option>
                                       <?php $qu = mysqli_query($koneksi, "SELECT jurusan FROM mapel_ijazah GROUP BY jurusan"); ?>
                                                <?php while ($mj = mysqli_fetch_array($qu)) : ?>
                                                    <option value="<?= $mj['jurusan'] ?>"><?= $mj['jurusan'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelas</label>
							<div class="col-sm-7">
							<select name='kelas' id="kelas" class='form-select'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelompok Mapel</label>
							<div class="col-sm-7">
							   <select id='kelompok' name='kelompok' class='form-select' required='true'>
                                      <option value=''>Pilih Kelompok Mapel</option>	
													<?php if($setting['jenjang']=='SD' OR $setting['jenjang']=='SMP' OR $setting['jenjang']=='PAKET-A' OR $setting['jenjang']=='PAKET-B'): ?>
                                                       
                                                         <option value='A'>A. Umum</option>
														  <option value='B'>B. Muatan Lokal</option>
													  
													   <?php elseif($setting['jenjang']=='SMA' OR $setting['jenjang']=='PAKET-C'): ?>
													    <option value='A'>A. Umum</option>
														  <option value='B'>B. Umum</option>
														   <option value='C'>C. Peminatan</option>
														   <?php elseif($setting['jenjang']=='SMK'): ?>
													    <option value='A'>A. Muatan Nasional</option>
														  <option value='B'>B. Muatan Kewilayahan</option>
														   <option value='C1'>C1. Dasar Bidang Keahlian</option>
														    <option value='C2'>C2. Dasar Program Keahlian</option>
															 <option value='C3'>C3. Kompetensi Keahlian</option>
													<?php endif; ?>
                                                    </select>       
										   </div>
						            </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Pilih Mapel</label>
							<div class="col-sm-7">
							<select name='mapel' id="mapel" class='form-select'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Semester</label>
							<div class="col-sm-9">
							  <select name='semester' class='form-select' required='true'>
                                                   <option value=''>Pilih Semester</option>
                                                    <option value='1'>1</option>
                                                     <option value='2'>2</option>
													  <option value='3'>3</option>
													   <option value='4'>4</option>
													    <option value='5'>5</option>
														 <option value='6'>6</option>
                                                    </select>
													</div>						          	 							
						                        </div>
													 <div class="kanan">
											   <button type='submit'  class='btn btn-success'><i class="material-icons">download</i>Download</button>
											  
                                       </div>
						           
								
									           </form>
								            
											</div>
										</div>
									</div>
							 
                          		 <script>
				  $("#pk").change(function() {
					var pk = $(this).val();
					var level = $('#level').val();
					console.log(pk + level);
					$.ajax({
						type: "POST", 
						url: "crud_skl.php?pg=ambil_kelas", 
						data: "pk=" + pk + '&level=' + level, 
						success: function(response) { 
							$("#kelas").html(response);
						}
					});
				});			
        			   $("#kelompok").change(function() {
					var kelompok = $(this).val();
					var pk = $('#pk').val();
					console.log(kelompok);
					$.ajax({
						type: "POST", 
						url: "crud_skl.php?pg=ambil", 
						data: "kelompok=" + kelompok + '&pk=' + pk, 
						success: function(response) { 
							$("#mapel").html(response);
						}
					});
				});				
			</script>
					 
			<div class="col-md-5">
						 <div class="card">
             <div class="card-header">
  <h5 class='card-title'> UPLOAD NILAI SEMESTER</h5>			 
                    </div>
                     <div class='card-body'>
                        <div class='form-group-sm'>
                            <div class='row'>
                                <form id='formsiswa' enctype='multipart/form-data'>
                                    <div class='col-md-12'>
                                        <label>Pilih File</label>
                                        <input type='file' name='file' class='form-control' required='true' />
                                    </div><p>
                                    <div class='right'>
                                        <a href="?pg=<?= enkripsi('nilai') ?>&ac=view" class='btn btn-primary'><i class='fa fa-eye'></i> View Nilai</a>
                                    
                                        <button type='submit' name='submit' class='btn btn-success'><i class='material-icons'>upload</i> Upload</button>
                                    </div>
									 
									
                                    
                                </form>
                            </div>
                        </div>
						
                    	 </div>
						     </div>	
						         </div>
						            </div>	
									  
						
						 <script>
				  $("#levelQ").change(function() {
					var levelQ = $(this).val();
					console.log(levelQ);
					$.ajax({
						type: "POST", 
						url: "crud_pk.php?pg=ambil_kelasQ", 
						data: "levelQ=" + levelQ, 
						success: function(response) { 
							$("#kelasQ").html(response);
						}
					});
				});						
			</script>
						
						<script>
    $('#formsiswa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'crud_nilai.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
              $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
					$('.progress-bar').animate({
					width: "30%"
					}, 500);
            },
            success: function(response) {
                setTimeout(function() {
                    $('.progress-bar').css({
                        width: "100%"
                    });
                    setTimeout(function() {
                        $('#hasilimport').html(response);
						});
						setTimeout(function() {
                    window.location.reload();
                    }, 2000);
                }, 2000);
            }
        });
    });
</script>

		
		 
<?php } elseif ($ac == 'view') { ?>
<div class='row'>

        <div class='col-md-12'>
             <div class="card">
             <div class="card-header">	
                  <h5 class='card-title'> View Nilai Semester</h5>
				  </div>    
			  			
						<div class="card-body">
						<span class="alert-text" style="text-align:justify">
                  Pastikaan Nomor Urut Mapel dan Kelompok Mapel Sudah di isi. Jika belum diisi maka tidak muncul pada Pilih Mapel</b>          
							  </span>
						<form action='?pg=viewsemester'  method='POST' class="form-horizontal" enctype='multipart/form-data'>
                          <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Tingkat</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-select' required='true'>
                                      <option value=''>Pilih Tingkat</option>
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM skl"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['tingkat'] ?>"><?= $m['tingkat'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
									<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Jurusan</label>
							<div class="col-sm-7">
							   <select id='pk' name='pk' class='form-select' required='true'>
                                      <option value=''>Pilih Jurusan</option>
                                       <?php $qu = mysqli_query($koneksi, "SELECT jurusan FROM mapel_ijazah GROUP BY jurusan"); ?>
                                                <?php while ($mj = mysqli_fetch_array($qu)) : ?>
                                                    <option value="<?= $mj['jurusan'] ?>"><?= $mj['jurusan'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelas</label>
							<div class="col-sm-7">
							<select name='kelas' id="kelas" class='form-select'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelompok Mapel</label>
							<div class="col-sm-7">
							   <select id='kelompok' name='kelompok' class='form-control' required='true'>
													<option value=''>Pilih Kelompok Mapel</option>
													<?php if($setting['jenjang']=='SD' OR $setting['jenjang']=='SMP'): ?>
                                                       
                                                         <option value='A'>A. Umum</option>
														  <option value='B'>B. Muatan Lokal</option>
													  
													   <?php elseif($setting['jenjang']=='SMA'): ?>
													    <option value='A'>A. Umum</option>
														  <option value='B'>B. Umum</option>
														   <option value='C'>C. Peminatan</option>
														   <?php elseif($setting['jenjang']=='SMK'): ?>
													    <option value='A'>A. Muatan Nasional</option>
														  <option value='B'>B. Muatan Kewilayahan</option>
														   <option value='C'>C. Muatan Peminatan Kejuruan</option>
														   
													<?php endif; ?>
                                                    </select>       
										   </div>
						            </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Pilih Mapel</label>
							<div class="col-sm-7">
							<select name='mapel' id="mapel" class='form-control'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Semester</label>
							<div class="col-sm-9">
							<div class="input-group">
							  <select name='semester' class='form-control' required='true'>
                                                   <option value=''>Pilih Semester</option>
                                                    <option value='1'>1</option>
                                                     <option value='2'>2</option>
													  <option value='3'>3</option>
													   <option value='4'>4</option>
													    <option value='5'>5</option>
														 <option value='6'>6</option>
                                                    </select>
													 <span class="input-group-btn">
											   <button type='submit'  class='btn  btn-primary'><i class='fa fa-eye' ></i><span> View Nilai</span></button>
											  </span>
                                            </div>						          	 							
						            </div>
									  
                                       </div>
						            </div>	
								
									           </form>
								            
											</div>
										</div>
									</div>
								</div> 
               <script>
				  $("#pk").change(function() {
					var pk = $(this).val();
					var level = $('#level').val();
					console.log(pk + level);
					$.ajax({
						type: "POST", 
						url: "crud_skl.php?pg=ambil_kelas", 
						data: "pk=" + pk + '&level=' + level, 
						success: function(response) { 
							$("#kelas").html(response);
						}
					});
				});			
        			 $("#kelompok").change(function() {
					var kelompok = $(this).val();
					var pk = $('#pk').val();
					console.log(kelompok);
					$.ajax({
						type: "POST", 
						url: "crud_skl.php?pg=ambil", 
						data: "kelompok=" + kelompok + '&pk=' + pk, 
						success: function(response) { 
							$("#mapel").html(response);
						}
					});
				});				
			</script>
					 
<?php } ?>	