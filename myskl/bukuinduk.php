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
										<?php if($user['level']=='admin'): ?>
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
                                                    <th>No</th>
                        <th>Kelas</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>TTL</th>
                        <th>JK</th>
                        <th>NIK</th>
                        <th>No. KK</th>
                        <th>Agama</th>
                        <th>Email</th>
                        <th>Anak Ke / Jml Saudara</th>
                        <th>Tinggi Badan</th>
                        <th>Berat Badan</th>
                        <th>Lingkar Kepala</th>
                        <th>RT/RW</th>
                        <th>Kel/Kec</th>
                        <th>Provinsi</th>
                        <th>Kode Pos</th>
                        <th>Hobi</th>
                        <th>Cita-cita</th>
                        <th>Sekolah Asal / Thn Lulus</th>
                        <th>Beasiswa</th>
                        <th>No KIP</th>
                        <th>No KKS</th>
                        <th>Nama Ayah</th>
                        <th>Status Ayah</th>
                        <th>TTL Ayah</th>
                        <th>No. HP Ayah</th>
                        <th>Pendidikan Ayah</th>
                        <th>Penghasilan Ayah</th>
                        <th>Pekerjaan Ayah</th>
                        <th>Nama Ibu</th>
                        <th>Status Ibu</th>
                        <th>TTL Ibu</th>
                        <th>No. HP Ibu</th>
                        <th>Pendidikan Ibu</th>
                        <th>Penghasilan Ibu</th>
                        <th>Pekerjaan Ibu</th>
                        <th>File KK</th>
                        <th>Status Kelengkapan</th>
                        <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM siswa "); 
											  while ($data = mysqli_fetch_assoc($query)) :
											$no++;
											   ?>
											   
											   <?php
                        // Daftar field yang wajib diisi
                        $required = [
                            'kelas','nama','nis','nisn','t_lahir','tgl_lahir','jk',
                            'nik','nokk','agama','email','anakke','jumlah_saudara',
                            't_badan','b_badan','l_kepala','rt','rw','kelurahan',
                            'kecamatan','provinsi','kode_pos','hobi','cita_cita',
                            'asal_sek','thn_lulus','beasiswa',
                            'nama_ayah','status_ayah','nama_ibu','status_ibu'
                        ];

                        // Tambahkan file KK sebagai field wajib
                        $required[] = 'kk_ibu';

                        // Logika untuk field kondisional
                        if ($data['beasiswa'] === 'KIP') {
                            $required[] = 'no_kip';
                        } elseif ($data['beasiswa'] === 'PKH') {
                            $required[] = 'no_kks';
                        }

                        $fatherFields = ['tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','no_hp_ayah'];
                        $motherFields = ['tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','no_hp_ibu'];

                        if (strcasecmp($data['status_ayah'], 'Sudah Meninggal') !== 0) {
                            $required = array_merge($required, $fatherFields);
                        }
                        if (strcasecmp($data['status_ibu'], 'Sudah Meninggal') !== 0) {
                            $required = array_merge($required, $motherFields);
                        }

                        $total = count($required);
                        $filled = 0;
                        $missingFields = []; // Array untuk menyimpan nama field yang kosong

                        foreach ($required as $f) {
                            $isFieldFilled = false;
                            if ($f === 'jumlah_saudara') {
                                if (isset($data[$f]) && ($data[$f] === '0' || $data[$f] === '-' || $data[$f] !== '')) {
                                    $isFieldFilled = true;
                                }
                            } elseif ($f === 'kk_ibu') {
                                if (!empty($data['kk_ibu'])) {
                                    $isFieldFilled = true;
                                }
                            } else {
                                if (!empty($data[$f])) {
                                    $isFieldFilled = true;
                                }
                            }
                            
                            if($isFieldFilled) {
                                $filled++;
                            } else {
                                // Jika field kosong, tambahkan ke daftar yang kurang
                                $missingFields[] = ucwords(str_replace('_', ' ', $f));
                            }
                        }

                        $percent = round($filled / $total * 100);
                        $isComplete = ($filled === $total);
                        ?>
                                                <tr>
                                                  <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($data['kelas']) ?></td>
                            <td><?= htmlspecialchars($data['nama']) ?></td>
                            <td><?= htmlspecialchars($data['nis']) ?></td>
                            <td><?= htmlspecialchars($data['nisn']) ?></td>
                            <td><?= htmlspecialchars($data['t_lahir']) ?>, <?= htmlspecialchars($data['tgl_lahir']) ?></td>
                            <td><?= $data['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= htmlspecialchars($data['nik']) ?></td>
                            <td><?= htmlspecialchars($data['nokk']) ?></td>
                            <td><?= htmlspecialchars($data['agama']) ?></td>
                            <td><?= htmlspecialchars($data['email']) ?></td>
                            <td><?= htmlspecialchars($data['anakke']) ?> / <?= htmlspecialchars($data['jumlah_saudara']) ?></td>
                            <td><?= htmlspecialchars($data['t_badan']) ?></td>
                            <td><?= htmlspecialchars($data['b_badan']) ?></td>
                            <td><?= htmlspecialchars($data['l_kepala']) ?></td>
                            <td><?= htmlspecialchars($data['rt']) ?> / <?= htmlspecialchars($data['rw']) ?></td>
                            <td><?= htmlspecialchars($data['kelurahan']) ?>/<?= htmlspecialchars($data['kecamatan']) ?></td>
                            <td><?= htmlspecialchars($data['provinsi']) ?></td>
                            <td><?= htmlspecialchars($data['kode_pos']) ?></td>
                            <td><?= htmlspecialchars($data['hobi']) ?></td>
                            <td><?= htmlspecialchars($data['cita_cita']) ?></td>
                            <td><?= htmlspecialchars($data['asal_sek']) ?> / <?= htmlspecialchars($data['thn_lulus']) ?></td>
                            <td><?= htmlspecialchars($data['beasiswa']) ?></td>
                            <td><?= htmlspecialchars($data['no_kip']) ?></td>
                            <td><?= htmlspecialchars($data['no_kks']) ?></td>
                            <td><?= htmlspecialchars($data['nama_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['status_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['tempat_lahir_ayah']) ?> / <?= htmlspecialchars($data['tgl_lahir_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['no_hp_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['pendidikan_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['penghasilan_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['pekerjaan_ayah']) ?></td>
                            <td><?= htmlspecialchars($data['nama_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['status_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['tempat_lahir_ibu']) ?> / <?= htmlspecialchars($data['tgl_lahir_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['no_hp_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['pendidikan_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['penghasilan_ibu']) ?></td>
                            <td><?= htmlspecialchars($data['pekerjaan_ibu']) ?></td>
                            <td>
                                <?php if (!empty($data['kk_ibu'])): ?>
                                    <a href="../uploads/kk/<?= htmlspecialchars($data['kk_ibu']) ?>" target="_blank" class="btn btn-sm btn-secondary">Lihat KK</a>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($isComplete): ?>
                                    <span class="badge bg-success">Lengkap (100%)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Lengkap (<?= $percent ?>%)</span>
                                    <button class="btn btn-link btn-sm p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $data['id_siswa'] ?>">
                                        Lihat Detail
                                    </button>
                                    <div class="collapse" id="collapse-<?= $data['id_siswa'] ?>">
                                        <ul class="mb-0 ps-3 small">
                                            <?php foreach ($missingFieldsList as $field): ?>
                                                <li><?= htmlspecialchars($field) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
													 <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('edit') ?>&ids=<?= enkripsi($data['id_siswa']) ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
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
													 <img src="../images/fotosiswa/<?= $siswa['foto'].jpg ?>" alt="" class="responsive">
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
$username = '';
$password = '';
?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">TAMBAH SISWA</h5>
            </div>
            <div class="card-body">
                <form id="formsiswa" action='' method='post' class="row g-1" enctype='multipart/form-data'>

                    <div class="col-md-12">
                        <label class="form-label bold">NAMA LENGKAP</label>
                        <input type='text' name='nama' class='form-control' required />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label bold">NIS</label>
                        <input type='text' name='nis' class='form-control' required />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label bold">NISN</label>
                        <input type='text' name='nisn' class='form-control' required />
                    </div>

                    <div class="col-md-4">
                        <label class="form-label bold">TINGKAT</label>
                        <select class="form-select" name="level" required style="width: 100%">
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
                        <label class="form-label bold">NO WHATSAPP (Jika Ada)</label>
                        <input type='number' name='nowa' class='form-control' />
                    </div>

                    <div class="col-md-6">
                        <label class="form-label bold">FOTO (Jika Ada)</label>
                        <input type='file' name='file' class='form-control' />
                    </div>

                    <div class="col-md-12">
                        <button type="submit" id="btnSubmit" class="btn btn-primary kanan" disabled>Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">
                    <center>FOTO SISWA</center>
                </h5>
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
// Submit Form
$('#formsiswa').submit(function(e) {
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        type: 'POST',
        url: 'siswa/tambah.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang diproses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
            $('.progress-bar').animate({
                width: "30%"
            }, 500);
        },
        success: function(data) {
            setTimeout(function() {
                window.location.reload();
            }, 2000);
        }
    });
    return false;
});

// Fungsi untuk generate serial random
function generateSerial() {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var serial = '';
    for (var i = 0; i < 3; i++) {
        serial += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return serial;
}

// Update Username dan Password saat NIS diisi
$('input[name="nis"]').on('input', function() {
    var nis = $(this).val();
    if (nis !== '') {
        var serial = generateSerial();
        $('input[name="username"]').val(nis);
        $('input[name="password"]').val(nis + '-' + serial);
        $('#btnSubmit').prop('disabled', false); // Aktifkan tombol
    } else {
        $('input[name="username"]').val('');
        $('input[name="password"]').val('');
        $('#btnSubmit').prop('disabled', true); // Disable tombol
    }
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