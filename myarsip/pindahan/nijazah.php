<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<?php if ($ac == '') { ?>
<div class='row'>

        <div class='col-md-12'>
            <div class='panel panel-default'>
               <div class="panel-heading" style="height:45px">
                  <h4 class='box-title'><i class="fas fa-print"></i> CETAK STANDAR KOMPETENSI UTAMA</h4></div>    
			  
			  <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" >Transkip Nilai</a></li>
					</ul>					
					<div class="tab-content">
                        <div class="tab-pane active" id="tab_1" >
						<br>
						 <div class="col-md-8">
						<div class="panel panel-default">
                       					
						<div class="box-body">
						<form id="formpengaturan" action='?pg=nijazah&ac=siswa'  method='post' class="form-horizontal" enctype='multipart/form-data'>
                          
							<div class="box-body box-pane">
							<label class="col-xs-12 control-label"></label>                  						
						     <div class="form-group-sm">
							<label class="col-sm-3 control-label">Pilih Tingkat</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-control' required='true'>
                                      <option value=''>--Pilih Tingkat--</option>
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM skl"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['tingkat'] ?>"><?= $m['tingkat'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							<label class="col-xs-12 control-label"></label> 
							<div class="form-group-sm">
							<label class="col-sm-3 control-label">Kelas</label>
							<div class="col-sm-7">
							<select name='kelas' id="kelas" class='form-control'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							
						
							<label class="col-xs-12 control-label"></label> 
								<div class="form-group-sm">
							<label class="col-sm-9 control-label"></label>
							<div class="col-sm-3">
							
											   <button type='submit'  class='btn btn-sm  btn-default'><i class='fa fa-print' ></i><span> Print</span></button>
											
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
						
			</script>
			
                    
			<div class="col-md-4">
						<div class="panel panel-default">
                     <div class='box-body'>
                        <div class='form-group-sm'>
                            <div class='row'>
                                <form id='formsiswa' enctype='multipart/form-data'>
                                    <div class='col-md-8'>
                                        <label>UPDATE SISWA (NO. IJAZAH)<br>Pilih File</label>
                                        <input type='file' name='file' class='form-control' required='true' />
                                    </div>
                                    <div class='col-md-4'>
                                        <label>&nbsp;</label><br>
                                        <button type='submit' name='submit' class='btn-sm btn-success'><i class='fa fa-upload'></i> Import</button>
                                    </div>	
						<div class='col-md-12'>
						<br>
						 <a href="sandik_pk/ekspor2.php?level=<?= $skl['tingkat'] ?>" ><i class='fa fa-download'></i> <b>Download Format</b></a>
						<br><hr>
						</div>
						
                                </form>
                            </div>
                        </div>
                                </form>
                            </div>
                        </div>
						
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
            url: 'sandik_pk/import_siswa2.php',
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
			
						
	<?php } elseif ($ac == 'siswa') { ?>
<?php $kelas = $_POST['kelas']; ?>
<div class='row'>

        <div class='col-md-12'>
            <div class='panel panel-default'>
               <div class="panel-heading" style="height:45px">
                  <h4 class='box-title'><i class="fas fa-print"></i> CETAK STANDAR KOMPETENSI UTAMA</h4></div>    
			  
			  <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" >Transkip Nilai</a></li>
					</ul>					
					<div class="tab-content">
                        <div class="tab-pane active" id="tab_1" >
						<br>					
						<div class="panel panel-default">	
                <div class='box-body'>
                <div class="table-responsive">
                    <table style="font-size: 12px" class="table table-bordered table-hover" id="example1">
                        <thead>
                            <tr>
                                 <th class="text-center" width="3%" >
                                    #
                                </th>
                              <th width="10%" >Kelas</th>
                                <th >N I S</th>
								 <th >N I S N</th>
								 <th >Nama Siswa</th>
								<th ></th>
							</tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from siswa WHERE id_kelas='$kelas' ");
                            $no = 0;
                            while ($siswa = mysqli_fetch_array($query)) {								
                                $no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                   <td><?= $siswa['id_kelas'] ?></td>
								   <td><?= $siswa['nis'] ?></td>
								   <td><?= $siswa['nisn'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>
                                  <td>
								  <a href="sandik_pk/cetaksku.php?nis=<?= $siswa['nis'] ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fa fa-print"></i> Cetak </a>
								  </td>
                                </tr>

                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

					 
<?php } ?>	