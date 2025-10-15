<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php if ($ac == '') { ?>
<div class='row'>

        <div class='col-md-7'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'><i class="fas fa-download"></i> Nilai Ujian</h5>
					</div>    
			  <div class='card-body'>
             <div class="alert alert-custom" role="alert">
               <div class="custom-alert-icon icon-dark"><i class="fa fa-check fa-2x"></i></div>
                 <div class="alert-content">
                  <span class="alert-text" style="text-align:justify">
                  Pastikaan Nomor Urut Mapel dan Kelompok Mapel Sudah di isi. Jika belum diisi maka tidak muncul pada Pilih Mapel</b>          
							  </span>
                            </div>
                                </div>				
						<div class="card-body">
						<form id="formpengaturan" action='sandik_skl/prosesujian.php'  method='post' class="form-horizontal" enctype='multipart/form-data'>
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
							<label  class="col-md-3 col-form-label bold">Pilih Ujian</label>
							<div class="col-sm-9">
							  <select name='jenis' class='form-select' required='true'>
                                                   <option value=''>Pilih Ujian</option>
                                                    <option value='TEORI'>TEORI</option>
                                                     <option value='PRAKTEK'>PTAKTEK</option>
													  
                                                    </select>
							                        </div>
                                                </div>
							
													 <div class="right">
											   <button type='submit'  class='btn btn-sm  btn-outline-success'><i class='fa fa-download' ></i>Download</button>
											  
                                       </div>
						           
								
									           </form>
								            
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
						url: "sandik_pk/crud_pk.php?pg=ambil_kelas", 
						data: "level=" + level, 
						success: function(response) { 
							$("#kelas").html(response);
						}
					});
				});			
        			   $("#kelompok").change(function() {
					var kelompok = $(this).val();
					console.log(kelompok);
					$.ajax({
						type: "POST", 
						url: "sandik_skl/crud_skl.php?pg=ambil", 
						data: "kelompok=" + kelompok, 
						success: function(response) { 
							$("#mapel").html(response);
						}
					});
				});				
			</script>
					 
			<div class="col-md-5">
						 <div class="card">
             <div class="card-header">	
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
                                        <a href="?pg=nilaiujian&ac=view" class='btn btn-outline-primary btn-sm'><i class='fa fa-eye'></i> View Nilai</a>
                                    
                                        <button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-upload'></i> Import</button>
                                    </div>
									 
									
                                    
                                </form>
                            </div>
                        </div>
						
						<div id='progressbox'></div>
                        <div id='hasilimport'></div>
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
						url: "sandik_pk/crud_pk.php?pg=ambil_kelasQ", 
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
            url: 'sandik_skl/crud_ujian.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('#progressbox').html('<div><img src="<?= $homeurl ?>/dist/img/animasi1.gif" style="margin-left:100px"></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 100);
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
                  <h5 class='card-title'><i class="fas fa-eye"></i> View Nilai Ujian</h5></div>    
			  
			  <div class='card-body'>
             <div class="alert alert-custom" role="alert">
               <div class="custom-alert-icon icon-dark"><i class="fa fa-check fa-2x"></i></div>
                 <div class="alert-content">
                  <span class="alert-text" style="text-align:justify">
                  Pastikaan Nomor Urut Mapel dan Kelompok Mapel Sudah di isi. Jika belum diisi maka tidak muncul pada Pilih Mapel</b>          
							  </span>
                            </div>
                                </div>				
						<div class="card-body">
						<form action='?pg=viewujian'  method='POST' class="form-horizontal" enctype='multipart/form-data'>
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
							<div class="col-sm-9">
							<select name='mapel' id="mapel" class='form-select'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							
							
													 <div class="right">
											   <button type='submit'  class='btn  btn-outline-success'><i class='fa fa-check' ></i>View Nilai</button>
											  
                                       </div>
						           
								
								
									           </form>
								            
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
						url: "sandik_pk/crud_pk.php?pg=ambil_kelas", 
						data: "level=" + level, 
						success: function(response) { 
							$("#kelas").html(response);
						}
					});
				});			
        			   $("#kelompok").change(function() {
					var kelompok = $(this).val();
					console.log(kelompok);
					$.ajax({
						type: "POST", 
						url: "sandik_skl/crud_skl.php?pg=ambil", 
						data: "kelompok=" + kelompok, 
						success: function(response) { 
							$("#mapel").html(response);
						}
					});
				});				
			</script>
					 
<?php } ?>	