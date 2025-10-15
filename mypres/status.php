<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>      
     
			<?php if ($ac == '') : ?>
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA ABSENSI <?= date('d M Y') ?></h5>
									</div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NAMA LENGKAP</th>
                                                    <th>LEVEL</th>
                                                    <th>ROMBEL</th>
													<th>ABSENSI</th>
                                                    <th>JAM ABSEN</th>
													 <th>JAM PULANG</th>
													 <th>KETERANGAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											   $tgl = date('Y-m-d');
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tgl' order by id desc"); 
											 while ($data = mysqli_fetch_assoc($query)) :
											 $sis = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
											 $peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>                                              
                                                     <td>
													 <?php if($data['level']=='siswa'): ?>
													 <?= $sis['nama'] ?>
													 <?php else : ?>
													  <?= $peg['nama'] ?>
													 <?php endif; ?>
													
													 </td>
                                                    <td><?= ucfirst($data['level']) ?></td>
                                                    <td style="text-align:center;"><?= $data['kelas'] ?></td>
													 <td>
													 <?php if($data['ket']=='H'): ?>
													 HADIR
													 <?php elseif($data['ket']=='S'): ?>
													 <strong style="color:blue;">SAKIT</strong>
													 <?php elseif($data['ket']=='I'): ?>
													 <strong style="color:blue;">IZIN</strong>
													 <?php elseif($data['ket']=='A'): ?>
													 <strong style="color:red;">ALPHA</strong>
													 <?php endif; ?>
													 </td>
                                                  <td style="text-align:center;">
												  
												  <?= $data['masuk'] ?>
												 
												  </td>
												  <td style="text-align:center;">
												  <?php if($data['ket']=='H'): ?>
												  <?= $data['pulang'] ?>
												  <?php endif; ?>
												  </td>
												   <td>
												   <?php if($data['ket']=='H'): ?>
												   <?= $data['keterangan'] ?>
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
							  
							</select>
						</div>
							<div class="col-md-4">
								<label class="form-label bold">AGAMA</label>
						   <select class="form-select" name="agama" required style="width: 100%">
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
						   <select class="form-select" name="jk" required style="width: 100%">
							<option value="<?= $siswa['jk'] ?>"><?= $kel ?></option>
							  <option value='' disabled>-- Pilih JK --</option>
							  <option value='L'>Laki-laki</option>
								  <option value='P'>Perempuan</option>
							</select>
						</div>
						 <div class="col-md-4">
								<label class="form-label bold">JURUSAN</label>
						   <select class="form-select" name="pk" required style="width: 100%">
							<option value="<?= $siswa['jurusan'] ?>"><?= $siswa['jurusan'] ?></option>
							  <option value='' disabled>-- Pilih Jurusan --</option>
							  <?php
										$lev = mysqli_query($koneksi, "SELECT * FROM jurusan");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[kode_jurusan]'>$level[kode_jurusan]</option>";
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