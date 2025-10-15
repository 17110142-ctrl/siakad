                        <style>
							.responsive {
							width: 50%;
							height: auto;
							}
							</style>
							 <div class='row'>
                            <div class='col-md-7'>
                                <div class='box box-solid'>
                                    <div class='box-header with-border'>
                                        <h3 class='box-title'>FOTO  PROFILE</h3>
                                    </div>
                                    <div class='box-body'>
									<center>
                                               
									<?php if($siswa['foto']==''){ ?>
                                    <img src="images/user.png" alt="" class="responsive">
									<?php }else{ ?>
									 <img src="images/fotosiswa/<?= $siswa['foto'] ?>" alt="" class="responsive">
									<?php } ?>
												
									</center>
									</div>
								</div>
							</div>
			<div class="col-sm-5" >
			<div class='box box-solid' >
          
			<div class='box-body' >
		<div class="edis">
		  <div style="font-size:20px"><i class="fas fa-user-plus"></i> Edit Profil</div>
		  <form id="formprofil">
		  <input type='hidden' name='ids' value="<?= $siswa['id_siswa'] ?>" class='form-control' />
				<div class="form-group-sm">
					<label><b>Username</b></label> 
					<input type="text" name='username' class="form-control"   value="<?= $siswa['username'] ?>" readonly >
				</div>
				<div class="form-group-sm">
					<label><b>Password</b></label> 
					<input type="text" name='password' class="form-control"  value="<?= $siswa['password'] ?>" required >
				</div>			
				<div class="form-group-sm">
					<label><b>Tempat Lahir</b></label> 
					<input type="text" name='tlahir' class="form-control" value="<?= $siswa['t_lahir'] ?>" required >
				</div>
				<div class="form-group-sm">
					<label><b>Tgl Lahir ( contoh: 21 Agustus 2007 )</b></label> 
					<input type="text" name='tgllahir' class="form-control" value="<?= $siswa['tgl_lahir'] ?>" required >
				</div>
				<div class="form-group-sm">
					<label><b>Agama</b></label> 
					<select class="form-control" name="agama" required style="width: 100%">
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
				<div class="form-group-sm">
					<label><b>Alamat</b></label> 
					<input type="text" name='alamat' class="form-control" value="<?= $siswa['alamat'] ?>" required >
				</div>
				<div class="form-group-sm">
					<label><b>FOTO ( Jika Ada )</b></label> 
                   <input type='file' name='file' class='form-control' />
				   </div>
				<br>
				
			  <button type="submit" class="btn btn-primary btn-round form-control mt-5"  name="btnsubmit">Simpan</button>
			</form> 
			<br><br>
		</div>
    </div>
	 
  </div> 
</div>
						<script>
							$('#formprofil').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								var homeurl = '<?= $homeurl ?>';

								$.ajax({
									type: 'POST',
									url: homeurl + '/simpanprofil.php',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									success: function(data) {
									iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil disimpan',
											titleColor: '#FFFF00',
											messageColor: '#fff',
											backgroundColor: 'rgba(0, 0, 0, 0.5)',
											 progressBarColor: '#FFFF00',
											  position: 'topRight'				  
											});
											setTimeout(function() {
											window.location.replace('?pg=profil');
										}, 2000);
										}
									});
								return false;
							});
						</script>	 
						 