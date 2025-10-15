<?php
defined('APK') or exit('No Access');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L'"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P'"));
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
                                                <span class="widget-stats-title">SISWA LAKI-LAKI</span>
                                                <span class="widget-stats-amount"><?= $jpesL; ?></span>
                                               
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
                                                <span class="widget-stats-title">SISWA PEREMPUAN</span>
                                                <span class="widget-stats-amount"><?= $jpesP; ?></span>
                                               
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
                                                <span class="widget-stats-title">TOTAL SISWA</span>
                                                <span class="widget-stats-amount"><?= $jsiswa ?></span>
                                               
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
                                        <h5 class="card-title">DATA SISWA</h5>
										<?php if($user['level']=='siswa'): ?>
										<div class="pull-right">
										 <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('tambah') ?>" class='btn btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Siswa"><i class="material-icons">add</i>Tambah</a>
										<a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('upload') ?>" class='btn btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Foto"><i class="material-icons">upload</i>Foto</a>
										
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
                                                    <th>NAMA SISWA</th>
                                                    <th>ROMBEL</th>
                                                    <th>USERNAME</th>
                                                    <th>PASSWORD</th>
													 <th>JK</th>
													 <th>AGAMA</th>
													 <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username<>''"); 
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
												  <td><?= $data['agama'] ?></td>
												    <td>
													 <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('edit') ?>&ids=<?= enkripsi($data['id_siswa']) ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
												<?php if($user['level']=='siswa'): ?>
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
            <?php elseif($ac == 'login'): ?>
			<?php
			$idu = $_GET['idu'];
			$uji = fetch($koneksi,'ujian',['id_ujian'=>$idu]);
			$sesi = $uji['sesi'];
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
											$query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE NOT EXISTS(SELECT * FROM nilai WHERE nilai.id_siswa=siswa.id_siswa and nilai.id_ujian='$_GET[idu]') and sesi='$sesi'");
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
				           
          <?php elseif($ac == enkripsi('edit')): ?>
			<?php
			$ids = dekripsi($_GET['ids']);
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
                                        <h5 class="card-title">EDIT DATA</h5>
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
								<label class="form-label bold">NISN</label>
							<input type='text' name='nisn' value="<?= $siswa['nisn'] ?>" class='form-control' required="true" />
						</div>
						<div class="col-md-4">
								<label class="form-label bold">TINGKAT</label>
						   <select class="form-select" name="level" required style="width: 100%">
							<option value="<?= $siswa['level'] ?>"><?= $siswa['level'] ?></option>
							  
							</select>
						</div>
                    <div class="col-md-4">
								<label class="form-label bold">ROMBEL</label>
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
								<label class="form-label bold">AGAMA</label>
						   <select class="form-select" name="agama" required="true" style="width: 100%">
							<option value="<?= $siswa['agama'] ?>"><?= $siswa['agama'] ?></option>
							   <option value='' disabled>-- Pilih Agama --</option>
							      <option value='Islam'>Islam</option>
								  <option value='Kristen'>Kristen</option>
								   <option value='Katholik'>Katholik</option>
								  <option value='Hindu'>Hindu</option>
								   <option value='Budha'>Budha</option>
								  <option value='Konghucu'>Konghucu</option>
							</select>
						</div>		           
							<div class="col-md-4">
								<label class="form-label bold">JK</label>
						   <select class="form-select" name="jk" required="true" style="width: 100%">
							<option value="<?= $siswa['jk'] ?>"><?= $kel ?></option>
							  <option value='' disabled>-- Pilih JK --</option>
							  <option value='L'>Laki-laki</option>
								  <option value='P'>Perempuan</option>
							</select>
						</div>
						 <div class="col-md-4">
								<label class="form-label bold">JURUSAN</label>
						   <select class="form-select" name="pk" style="width: 100%" required="true">
							<option value="<?= $siswa['jurusan'] ?>"><?= $siswa['jurusan'] ?></option>
							 
							  <?php
										$lev = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[jurusan]'>$level[jurusan]</option>";
										}
										?>
							</select>
						</div>		
									<div class="col-md-4">
								<label class="form-label bold">USERNAME</label>
							<input type='text' name='username' value="<?= $siswa['username'] ?>" class='form-control' readonly />
						</div>
                         <div class="col-md-4">
								<label class="form-label bold">PASSWORD</label>
							<input type='text' name='password' value="<?= $siswa['password'] ?>" class='form-control' required="true" />
						</div>	
                        <div class="col-md-6">
								<label class="form-label bold">NO WHATSAPP ( Jika Ada )</label>
                                 <input type='number' name='nowa' value="<?= $siswa['nowa'] ?>" class='form-control' />
						</div>
                          <div class="col-md-6">
								<label class="form-label bold">Tempat Lahir</label>
                                 <input type='text' name='tlahir' value="<?= $siswa['t_lahir'] ?>" class='form-control' />
						</div>
                      <div class="col-md-6">
								<label class="form-label bold">Tgl Lahir ( contoh: 21 Agustus 2007 )</label>
                                 <input type='text' name='tgllahir' value="<?= $siswa['tgl_lahir'] ?>" class='form-control' />
						</div>							
                        <div class="col-md-6">
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
													 <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="" class="responsive">
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
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
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
 <?php elseif($ac == enkripsi('tambah')): ?>
			<?php
			$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_siswa, nis FROM siswa ORDER BY id_siswa DESC LIMIT 1"));
			$id_siswa = $siswa['id_siswa'] + 1;
			$npsn = $setting['npsn'];
			$nis = $siswa['nis'];
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$serial = substr(str_shuffle($characters), 0,3);
            
			if($id_siswa >=1 AND $id_siswa<=9){
				$nomor = '00'.$id_siswa;
			}elseif($id_siswa >=10 AND $id_siswa<=99){
				$nomor = '0'.$id_siswa;
			}else{
				$nomor = $id_siswa;
			}
			$username = $nis;
			$password = $nis."-".$serial;
			?>
			<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">TAMBAH SISWA</h5>
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
								<label class="form-label bold">NISN</label>
							<input type='text' name='nisn'  class='form-control' required="true" />
						</div>
						<div class="col-md-4">
								<label class="form-label bold">TINGKAT</label>
						   <select class="form-select" name="level"  style="width: 100%" required="true">
							  <option value='' selected>-- Pilih Tingkat --</option>
							  <?php
										$lev = mysqli_query($koneksi, "SELECT level FROM siswa GROUP BY level");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[level]'>$level[level]</option>";
										}
										?>
							</select>
						</div>
                    <div class="col-md-4">
								<label class="form-label bold">ROMBEL</label>
						   <select class="form-select" name="kelas" required style="width: 100%">
							  <option value='' selected>-- Pilih Rombel --</option>
							  <?php
										$kls = mysqli_query($koneksi, "SELECT kelas FROM kelas");
										while ($k = mysqli_fetch_array($kls)) {
										echo "<option value='$k[kelas]'>$k[kelas]</option>";
										}
										?>
							</select>
						</div>
							<div class="col-md-4">
								<label class="form-label bold">AGAMA</label>
						   <select class="form-select" name="agama" required style="width: 100%">
							
							   <option value='' selected>-- Pilih Agama --</option>
							      <option value='Islam'>Islam</option>
								  <option value='Kristen'>Kristen</option>
								   <option value='Katholik'>Katholik</option>
								  <option value='Hindu'>Hindu</option>
								   <option value='Budha'>Budha</option>
								  <option value='Konghucu'>Konghucu</option>
							</select>
						</div>		           
							<div class="col-md-4">
								<label class="form-label bold">JK</label>
						   <select class="form-select" name="jk" required style="width: 100%">
							
							  <option value='' selected>-- Pilih JK --</option>
							  <option value='L'>Laki-laki</option>
								  <option value='P'>Perempuan</option>
							</select>
						</div>
						 <div class="col-md-4">
								<label class="form-label bold">JURUSAN</label>
						   <select class="form-select" name="pk" required style="width: 100%">
							
							  <option value='' selected>-- Pilih Jurusan --</option>
							  <?php
										$jur = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
										while ($pk = mysqli_fetch_array($jur)) {
										echo "<option value='$pk[jurusan]'>$pk[jurusan]</option>";
										}
										?>
							</select>
						</div>		
									<div class="col-md-4">
								<label class="form-label bold">USERNAME</label>
							<input type='text' name='username' value="<?= $username; ?>" class='form-control' readonly />
						</div>
                         <div class="col-md-4">
								<label class="form-label bold">PASSWORD</label>
							<input type='text' name='password' value="<?= $password; ?>" class='form-control' readonly />
						</div>	
                        <div class="col-md-6">
								<label class="form-label bold">NO WHATSAPP ( Jika Ada )</label>
                                 <input type='number' name='nowa' class='form-control' />
						</div>							
                        <div class="col-md-6">
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
                                        <h5 class="card-title"><center>FOTO SISWA</h5>
										
                                    </div>
                                    <div class="card-body">
                                       <center>
                                         
                                            <img src="../images/user.png" alt="" class="responsive">
											
												</center>
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
             url: 'tambahsiswa.php',
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

 <?php elseif($ac == enkripsi('upload')): ?>
		  <div class="row">					  
           <div class='col-md-8'>
            <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">FOTO SISWA</h5>
            
                  </div>
                  <div class="card-body">
				  <div class='row'>
        <?php
        $ektensi = ['jpg', 'png', 'JPG', 'PNG', 'JPEG', 'jpeg'];
        $folder = "../images/fotosiswa/"; 
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

<div class="col-md-4">
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
			$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
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