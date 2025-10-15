<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl"));
?>
<?php if ($ac == '') { ?>
<div class='row'>

        <div class='col-md-5'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'>INPUT MAPEL IJAZAH</h5>
					</div>    
			  
			 
						<div class="card-body">
						<form id="formmapel" action='sandik_pk/proses.php'  method='post' class="form-horizontal" enctype='multipart/form-data'>
                          
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">TINGKAT</label>
							<div class="col-sm-7">
							   <select id='level' name='level' class='form-control' required='true'>
                                     
                                       <?php $quem = mysqli_query($koneksi, "SELECT * FROM skl"); ?>
                                                <?php while ($m = mysqli_fetch_array($quem)) : ?>
                                                    <option value="<?= $m['tingkat'] ?>"><?= $m['tingkat'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
									
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">JURUSAN</label>
							<div class="col-sm-7">
							 <select id='jurusan' name='jurusan' class='form-select' required>
							 <option value=''>Pilih Jurusan</option>
							  <?php $query = mysqli_query($koneksi, "SELECT level,pk FROM kelas WHERE level='$skl[tingkat]' GROUP BY pk"); ?>
                                                <?php while ($j = mysqli_fetch_array($query)) : ?>
                                                    <option value="<?= $j['pk'] ?>"><?= $j['pk'] ?></option>
                                                <?php endwhile ?>
                                                    </select>    
                            
                                            </div>
                                        </div>
									
										
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">KODE</label>
							<div class="col-sm-7">
							   <select id='kodemap' name='kodemap' class='form-select' required='true'>
                                      <option value=''>Pilih Kode Mapel</option>
                                       <?php $q = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"); ?>
                                                <?php while ($mp = mysqli_fetch_array($q)) : ?>
                                                    <option value="<?= $mp['kode'] ?>"><?= $mp['kode'] ?></option>
                                                <?php endwhile ?>
                                                    </select>            
										   </div>
						            </div>
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">MAPEL</label>
							<div class="col-sm-9">
							<select name='mapel' id="mapel" class='form-select'  required='true'>
                            
                                                </select>
                                            </div>
                                        </div>
							<div class="row mb-2">
							<label  class="col-md-3 col-form-label bold">GROUP</label>
							<div class="col-sm-9">
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
							<label  class="col-md-3 col-form-label bold">NO URUT</label>
							<div class="col-sm-9">
							 		<input type="number" name="urut" class="form-control" required="true" >          	 							
						                      </div>
									  
                                       </div>
									    <div class="kanan">
											   <button type='submit'  class='btn btn-success'>Simpan</button>                                           
						                      </div>	
								
									           </form>
								            
											</div>
										</div>
									</div>
								
                          		 <script>
				 
        			   $("#kodemap").change(function() {
					var kodemap = $(this).val();
					console.log(kodemap);
					$.ajax({
						type: "POST", 
						url: "crud_skl.php?pg=ambil_mapel", 
						data: "kodemap=" + kodemap, 
						success: function(response) { 
							$("#mapel").html(response);
						}
					});
				});				
			</script>
			                          <script>
                                        $('#formmapel').submit(function(e) {
                                            e.preventDefault();
                                            var data = new FormData(this);
                                            $.ajax({
                                                type: 'POST',
                                                url: 'edit_mapel.php',
                                                 enctype: 'multipart/form-data',
												data: data,
												cache: false,
												contentType: false,
												processData: false,
												success: function(data) {
													console.log(data);
								                if (data == 'OK') {
												 $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
													$('.progress-bar').animate({
													width: "30%"
													}, 500);  
													setTimeout(function() {
														window.location.reload();
													}, 2000);

												} else {
									 iziToast.info(
									{
										title: 'GAGAL!',
										message: 'Kode Mapel sudah ada',
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

							}
						});
						return false;
					});
                                    </script>		 
			<div class="col-md-7">
			<div class="card">
             <div class="card-header"></div>	
                  <div class="card-body">
                    <div class='table-responsive'>
                        <table style="font-size: 12px" id='datatable1' class='table'>
                            <thead>
                                <tr>
                                    <th width='3px'>No</th>
									<th width='5%'>Tingkat</th>
									  <th >PK</th>
                                    <th>Kode Mapel</th>  
                                     <th width='5%'>No Urut</th> 	
									 <th>Kelompok</th>
                                     
                                </tr>
                            </thead>
							<tbody>
							 <?php
							 $no=0;
							  $query = mysqli_query($koneksi, "select * from mapel_ijazah ORDER BY idmapel DESC");
							  while ($mapel = mysqli_fetch_array($query)) {
								  $no++;
							 ?>
							 <tr>
							  <td><?= $no; ?></td>
								<td><?= $mapel['tingkat'] ?></td>
								<td><?= $mapel['jurusan'] ?></td>
							        <td><?= $mapel['kode'] ?></td>
									<td><?= $mapel['urut'] ?></td>
									<td><?= $mapel['kelompok'] ?> &nbsp;&nbsp;&nbsp; <button data-id="<?= $mapel['idmapel'] ?>" class="hapus btn-sm btn btn-danger"><i class="material-icons">delete</i></button></td>
							</tr>
                            <?php } ?>
                        </tbody>
                        </table>
                    	 </div>
						     </div>	
						         </div>
						            </div>	
									  
                                    
						<script>
		$('#datatable1').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        swal({
            title: 'Are you sure?',
            text: 'Akan menghapus data ini!',
			type:'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'iya, hapus'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'crud_skl.php?pg=hapusmapel',
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
						
						 
						
		
		 

					 
<?php } ?>	