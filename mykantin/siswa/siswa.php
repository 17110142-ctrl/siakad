<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L'"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P'"));

$jum = $jpesL + $jpesP;
?>      
     
			<?php if ($ac == '') : ?>
			<div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">LAKI-LAKI</span>
                                                <span class="widget-stats-amount"><?= $jpesL; ?></span>
                                                <span class="widget-stats-info" style="color:blue;">dari <?= $jsiswa ?> Siswa</span>
                                            </div>
                                           
                                        </div>
										 
                                    </div>
                                </div>
								
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">PEREMPUAN</span>
                                                <span class="widget-stats-amount"><?= $jpesP; ?></span>
                                                <span class="widget-stats-info" style="color:blue;">dari <?= $jsiswa ?> Siswa</span>
                                            </div>
                                            
                                        </div>
										 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">people</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">TOTAL</span>
                                                <span class="widget-stats-amount"><?= $jsiswa ?></span>
                                                <span class="widget-stats-info">dari <?= $jsiswa ?> Siswa</span>
                                            </div>
                                           
                                        </div>
										
                                    </div>
                                </div>
                            </div>
                        </div>
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA KONSUMEN</h5>
										<?php if($user['level']=='admin'): ?>
										<div class="pull-right">
										 <button class='btn btn-dark' onclick="frames['frameresult'].print()"><i class='material-icons'>print</i>Akun</button>                               
										 <a href="?pg=pelanggan&ac=tambah" class='btn btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Siswa"><i class="material-icons">add</i>Tambah</a>
										<a href="?pg=pelanggan&ac=upload" class='btn btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Foto"><i class="material-icons">upload</i>Foto</a>
										
										</div>
										<?php endif; ?>
									</div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NIS</th>
                                                    <th>NAMA KONSUMEN</th>
                                                    <th>KELAS</th>
                                                    <th>USERNAME</th>
                                                    <th>PASSWORD</th>
													 <th>JK</th>
													 <th>PAYMENT CARD</th>
													  <th>SALDO</th>
													 <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa"); 
											  while ($data = mysqli_fetch_assoc($query)) :
											$no++;
											   ?>
                                                <tr>
                                                  <td><?= $no; ?></td>
                                                  <td><?= $data['nis'] ?></td>
                                                  <td><?= $data['nama'] ?></td>
                                                  <td><?= $data['kelas'] ?></td>
                                                  <td><?= $data['username'] ?></td>
                                                  <td><?= $data['password'] ?></td>
												  <td><?= $data['jk'] ?></td>
												   <td><?= $data['nokartu'] ?></td>
												   <td><?= number_format($data['saldo']) ?></td>
												    <td>
													 <a href="?pg=pelanggan&ac=edit&ids=<?= $data['id_siswa'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
												<?php if($user['level']=='admin'): ?>
												<button data-id="<?= $data['id_siswa'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
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
		 
				</div>	
				<iframe id='loadframe' name='frameresult' src='siswa/cetak_akun.php' style='display:none'></iframe>

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
											   url: 'siswa/edit.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													  iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil dihapus',
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
											});
										}
										return false;
									})

								});

							</script>    
            
          <?php elseif($ac == 'edit'): ?>
			<?php
			$ids = $_GET['ids'];
			$siswa = fetch($koneksi,'siswa',['id_siswa'=>$ids]);
			if($siswa['jk']=='L'){
				$kel= 'Laki-laki';
			}else{
				$kel= 'Perempuan';
			}
			?>
			<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">EDIT KONSUMEN</h5>
									</div>
                                    <div class="card-body">
									<p>
			         <form id="formedit" action='' method='post' class="row g-1" enctype='multipart/form-data'>
					 <input type='hidden' name='ids' value="<?= $siswa['id_siswa'] ?>" class='form-control' />
						<div class="col-md-12">
								<label class="form-label bold">NAMA LENGKAP</label>
							<input type='text' name='nama' value="<?= $siswa['nama'] ?>" class='form-control' required="true" />
						</div>	   
							   <div class="col-md-4">
								<label class="form-label bold">NIS</label>
							<input type='text' name='nis' value="<?= $siswa['nis'] ?>" class='form-control' required="true" />
						</div>
						
                    <div class="col-md-4">
								<label class="form-label bold">KELAS</label>
						   <select class="form-select" name="kelas" required style="width: 100%">
							<option value="<?= $siswa['kelas'] ?>"><?= $siswa['kelas'] ?></option>
							  <?php
										$kls = mysqli_query($koneksi, "SELECT kelas FROM siswa GROUP BY kelas");
										while ($kelas = mysqli_fetch_array($kls)) {
										echo "<option value='$kelas[kelas]'>$kelas[kelas]</option>";
										}
										?>
							</select>
						</div>
							   
							<div class="col-md-4">
								<label class="form-label bold">JK</label>
						   <select class="form-select" name="jk" required style="width: 100%">
							<option value="<?= $siswa['jk'] ?>"><?= $kel ?></option>
							  <option value='' disabled>-- Pilih JK --</option>
							  <option value='L'>Laki-laki</option>
								  <option value='P'>Perempuan</option>
							</select>
						</div>
						 <div class="col-md-6">
								<label class="form-label bold">USERNAME</label>
							<input type='text' name='username' value="<?= $siswa['username'] ?>" class='form-control' readonly />
						</div>
                         <div class="col-md-6">
								<label class="form-label bold">PASSWORD</label>
							<input type='text' name='password' value="<?= $siswa['password'] ?>" class='form-control' required="true" />
						</div>	
                        <div class="col-md-12">
								<label class="form-label bold">NO WHATSAPP ( Jika Ada )</label>
                                 <input type='number' name='nowa' value="<?= $siswa['nowa'] ?>" class='form-control' />
						</div>
                         			
                        <div class="col-md-12">
								<label class="form-label bold">FOTO ( Jika Ada )</label>
                                 <input type='file' name='file' class='form-control' />
						</div>	
						
						<div class="col-md-12">
										<button type="submit" class="btn btn-primary kanan">Simpan</button>
										 </div>
											   </form>
			                               </div>
										</div>
									</div>
								
                           <div class="col-md-4">                   
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title"><center><?= strtoupper($siswa['nama']); ?></h5>
										
                                    </div>
                                    <div class="card-body">
                                       <center>
                                               
												<?php if($siswa['foto']==''){ ?>
                                                    <img src="../images/user.png" alt="" class="responsive">
												<?php }else{ ?>
													 <img src="../images/foto/<?= $siswa['foto'] ?>" alt="" class="responsive">
												<?php } ?>
												
												</center>
										</div>	
									</div>	
			                      </div>	
								</div>
               <script>
    $('#formedit').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'siswa/edit.php?pg=edit',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},
								
			success: function(data){   		
			setTimeout(function()
				{
				window.location.reload();
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>
 <?php elseif($ac == 'tambah'): ?>
			
			<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">TAMBAH KONSUMEN</h5>
									</div>
                                    <div class="card-body">
									<p>
			         <form id="formsiswa" action='' method='post' class="row g-1" enctype='multipart/form-data'>
					
						<div class="col-md-12">
								<label class="form-label bold">NAMA LENGKAP</label>
							<input type='text' name='nama'  class='form-control' required="true" />
						</div>	   
							   <div class="col-md-4">
								<label class="form-label bold">NIS</label>
							<input type='text' name='nis'  class='form-control' required="true" />
						</div>
						
                    <div class="col-md-4">
								<label class="form-label bold">KELAS</label>
						   <select class="form-select" name="kelas" required style="width: 100%">
							<option value="">-- Pilih Kelas --</option>
							  <?php
										$kls = mysqli_query($koneksi, "SELECT kelas FROM siswa GROUP BY kelas");
										while ($kelas = mysqli_fetch_array($kls)) {
										echo "<option value='$kelas[kelas]'>$kelas[kelas]</option>";
										}
										?>
							</select>
						</div>
							   
							<div class="col-md-4">
								<label class="form-label bold">JK</label>
						   <select class="form-select" name="jk" required style="width: 100%">
							
							  <option value='' >-- Pilih JK --</option>
							  <option value='L'>Laki-laki</option>
								  <option value='P'>Perempuan</option>
							</select>
						</div>
						 <div class="col-md-6">
								<label class="form-label bold">USERNAME</label>
							<input type='text' name='username'  class='form-control' required="true" />
						</div>
                         <div class="col-md-6">
								<label class="form-label bold">PASSWORD</label>
							<input type='text' name='password'  class='form-control' required="true" />
						</div>	
                        <div class="col-md-12">
								<label class="form-label bold">NO WHATSAPP ( Jika Ada )</label>
                                 <input type='number' name='nowa' class='form-control' />
						</div>
                         			
                        <div class="col-md-12">
								<label class="form-label bold">FOTO ( Jika Ada )</label>
                                 <input type='file' name='file' class='form-control' />
						</div>	
						
						<div class="col-md-12">
										<button type="submit" class="btn btn-primary kanan">Simpan</button>
										 </div>
											   </form>
			                               </div>
										</div>
									</div>
								
                           <div class="col-md-4">                   
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title"><center>FOTO KONSUMEN</h5>
										
                                    </div>
                                    <div class="card-body">
                                       <center>
                                         
                                            <img src="../images/user.png" alt="" class="responsive">
											
												</center>
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
             url: 'siswa/tambah.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},
								
			success: function(data){   		
			setTimeout(function()
				{
				window.location.reload();
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>	

 <?php elseif($ac == 'upload'): ?>
		              <div class="row">
					 
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">UPLOAD FOTO</h5>
									</div>
                                    <div class="card-body">
									<p>
			                        <form id='formfoto' >	
                                      <label>Pilih File Zip</label>
									  <div class="input-group mb-3">
                                       <input type='file' name='file' class='form-control' required='true' />
									    <span class="input-group-text">
											<button type="submit" class="btn btn-success"><i class="material-icons">upload</i></button>
										</span>
                                        </div>	
										</form>
										 
							</div>
						</div>
					</div>		
                  </div>
           <div class='col-md-12'>
            <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">FOTO KONSUMEN</h5>
            
                  </div>
                  <div class="card-body">
				  <div class='row'>
        <?php
        $ektensi = ['jpg', 'png', 'JPG', 'PNG', 'JPEG', 'jpeg'];
        $folder = "../images/foto/"; 
        if (!($buka_folder = opendir($folder))) die("eRorr... Tidak bisa membuka Folder");
        $file_array = array();
        while ($baca_folder = readdir($buka_folder)) :
            $file_array[] = $baca_folder;
        endwhile;
        $jumlah_array = count($file_array);
        for ($i = 2; $i < $jumlah_array; $i++) :
            $nama_file = $file_array;
            $nomor = $i - 1;
            $ext = explode('.', $nama_file[$i]);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) { ?>
               
				<div class="avatar avatar-xxl status status-online">
    <img src="<?= $folder.$nama_file[$i] ?>" alt="">&nbsp;&nbsp;
</div>
                            
          <?php  } ?>
       <?php endfor;
        closedir($buka_folder);
        ?>
    </div>
	  </div>
          </div>
             </div>				  
             </div>		
   <script>
    $('#formfoto').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
            url: 'siswa/tfoto.php',
			enctype: 'multipart/form-data',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},
					
			success: function(data){   		
			setTimeout(function()
				{
				window.location.reload();
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>				 
        <?php endif; ?>		 