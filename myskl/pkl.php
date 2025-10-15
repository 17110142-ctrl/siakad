<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php if ($ac == '') { ?>
<div class='row'>

        <div class='col-md-7'>
           <div class="card">
             <div class="card-header">
                  <h5 class='box-title'><i class="fas fa-download"></i> Praktek Kerja Lapangan</h5>
				  </div>    
			
						<div class="card-body">
						<form id="formpengaturan" action='sandik_pk/prosespkl.php'  method='post' class="form-horizontal" enctype='multipart/form-data'>
                          <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Pilih Tingkat</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-select' required='true'>
                                      <option value=''>Pilih Tingkat</option>
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM siswa GROUP BY level"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['level'] ?>"><?= $m['level'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelas</label>
							<div class="col-sm-9">
							<select name='kelas' id="kelas" class='form-control'  required='true'>
                            
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
				 $("#kelas").change(function() {
					var kelas = $(this).val();
					console.log(kelas);
					$.ajax({
						type: "POST", 
						url: "sandik_pk/crud_pk.php?pg=ambil_sku", 
						data: "kelas=" + kelas, 
						success: function(response) { 
							$("#sku").html(response);
						}
					});
				});				
			</script>
			<div class="col-md-5">
				 <div class="card">
             <div class="card-header"></div>
                     <div class='card-body'>
                                <form id='formsiswa' enctype='multipart/form-data'>
                                    <div class='col-md-12'>
                                        <label class="bold">Pilih File</label>
                                        <input type='file' name='file' class='form-control' required='true' />
                                    </div><p>
                                    <div class='right'>
                                        <button type='submit' name='submit' class='btn btn-sm btn-success'><i class='fa fa-upload'></i> Import</button>
                                    <a href="?pg=pkl&ac=view" class='btn btn-outline-primary btn-sm'><i class='fa fa-eye'></i> View Nilai</a>
                                    </div>
                                </form>
                      
						<div id='progressbox'></div>
                        <div id='hasilimport'></div>
                    </div>
                </div>
			</div>			
						
						
						
						
						
						<script>
    $('#formsiswa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'sandik_pk/crud_pkl.php',
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

        <div class='col-md-7'>
             <div class="card">
             <div class="card-header">
                  <h5 class='box-title'><i class="fas fa-eye"></i> View Praktek Kerja Lapangan</h5>
				  </div>    
			  
			  
						<div class="card-body">
						<form action='?pg=viewpkl'  method='POST' class="form-horizontal" enctype='multipart/form-data'>
                          <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Pilih Tingkat</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-select' required='true'>
                                      <option value=''>Pilih Tingkat</option>
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM siswa GROUP BY level"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['level'] ?>"><?= $m['level'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							 <div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">Kelas</label>
							<div class="col-sm-9">
							<select name='kelas' id="kelas" class='form-control'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							
							<div class="right">
							<button type='submit'  class='btn btn-sm  btn-primary'><i class='fa fa-eye' ></i><span> View PKL</span></button>
											
                                            </div>						          	 							
						         
									           </form>
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
				 $("#kelas").change(function() {
					var kelas = $(this).val();
					console.log(kelas);
					$.ajax({
						type: "POST", 
						url: "sandik_pk/crud_pk.php?pg=ambil_sku", 
						data: "kelas=" + kelas, 
						success: function(response) { 
							$("#sku").html(response);
						}
					});
				});				
			</script>
					 
<?php } ?>	