<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<style>
							.responsive {
							width: 30%;
							height: auto;
							}
							</style>
							 <div class='row'>
                            <div class='col-md-7'>
                                <div class='box box-solid'>
                                    <div class='box-header with-border'>
                                        <h3 class='box-title'><?= $siswa['nama'] ?></h3>
                                    </div>
                                    <div class='box-body'>
									<center>
                                               
									<?php if($siswa['foto']==''){ ?>
                                    <img src="images/user.png" alt="" class="responsive">
									<?php }else{ ?>
									 <img src="images/fotosiswa/<?= $siswa['foto'] ?>" alt="" class="responsive">
									<?php } ?>
												
									</center>
									<br><br>
									<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM prestasi WHERE idsiswa='$siswa[id_siswa]'"); 
											  while ($data = mysqli_fetch_array($query)) :
											$no++;
											   ?>
											   <?= $no ?>. <?= $data['juara'] ?> Lomba <?= $data['kegiatan'] ?> pada tanggal <?= $data['tanggal'] ?></br>
											   <?php endwhile; ?>
									</div>
								</div>
							</div>
			<div class="col-sm-5" >
			<div class='box box-solid' >
          
			<div class='card' >
		<div class="edis">
		  <div style="font-size:20px"><i class="fas fa-star"></i> Input Prestasi</div>
		  <form id="formprestasi">
		  <input type='hidden' name='ids' value="<?= $siswa['id_siswa'] ?>" class='form-control' />
		  <input type='hidden' name='kelas' value="<?= $siswa['kelas'] ?>" class='form-control' />
				<div class="form-group-sm">
					<label><b>Semester</b></label> 
					<input type="text" name='smt' class="form-control"   value="<?= $setting['semester'] ?>" readonly >
				</div><p>
				<div class="form-group-sm">
					<label><b>Tahun Pelajaran</b></label> 
					<input type="text" name='tp' class="form-control"  value="<?= $setting['tp'] ?>" readonly >
				</div>	<p>		
				<div class="form-group-sm">
					<label><b>Nama Kegiatan</b></label> 
					<input type="text" name='nakeg' class="form-control"  required >
				</div><p>
				<div class="form-group-sm">
					<label><b>Tgl Kegiatan</b></label> 
					<input type="text" name='tgl' class="form-control" required >
				</div><p>
				<div class="form-group-sm">
					<label><b>Penyelenggara</b></label> 
					<input type="text" name='penyelenggara' class="form-control" required >
				</div>
				<p>
				<div class="form-group-sm">
					<label><b>Juara</b></label> 
					<select class="form-control" name="juara" required style="width: 100%">
							
							   <option value='' >-- Pilih Juara --</option>
							      <option value='Juara 1'>Juara 1</option>
								  <option value='Juara 2'>Juara 2</option>
								   <option value='Juara 3'>Juara 3</option>
								  <option value='Harapan 1'>Harapan 1</option>
								   <option value='Harapan 2'>Harapan 2</option>
								  <option value='Harapan 3'>Harapan 3</option>
							</select>
				</div>
				<p>
				<div class="form-group-sm">
					<label><b>Foto Piala / Piagam( wajib )</b></label> 
                   <input type='file' name='file' class='form-control' required />
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
							$('#formprestasi').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								var homeurl = '<?= $homeurl ?>';

								$.ajax({
									type: 'POST',
									url: homeurl + '/simpan_prestasi.php',
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
											window.location.replace('?pg=prestasi');
										}, 2000);
										}
									});
								return false;
							});
						</script>	