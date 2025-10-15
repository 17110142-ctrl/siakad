<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>      
     
			<?php if ($ac == '') : ?>
			<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">ABSENSI SISWA <?= date('d M Y'); ?></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>KELAS</th>													                                                 
                                                    <th>MATA PELAJARAN</th>
													 <th>GURU PENGAMPU</th>
													 <th>H</th>
													  <th>I</th>
													   <th>S</th>
													    <th>A</th>
													  <th>VIEW</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$harix = date('D');
											$no=0;
											if($user['level']=='admin'):
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE kuri='1' and hari='$harix' GROUP BY tingkat,mapel,guru"); 
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE kuri='1' AND guru='$user[id_user]' AND hari='$harix' GROUP BY tingkat,mapel"); 	
											endif;
											while ($data = mysqli_fetch_array($query)) :
											$mapel = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);			  
											$peg = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											$jumh = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas ='$data[kelas]' and tanggal='$tanggal' and ket='H'"));
											$jums = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas ='$data[kelas]' and tanggal='$tanggal' and ket='S'"));
											$jumi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas ='$data[kelas]' and tanggal='$tanggal' and ket='I'"));
											$juma = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where kelas ='$data[kelas]' and tanggal='$tanggal' and ket='A'"));
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                      <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span></h5></td>
													   <td><?= $mapel['nama_mapel'] ?></td>
													  <td><?= $peg['nama'] ?></td>
													  <td><h5><span class="badge badge-success"><?= $jumh; ?></span></h5></td>
													 <td><h5><span class="badge badge-primary"><?= $jumi; ?></span></h5></td>
													 <td><h5><span class="badge badge-warning"><?= $jums; ?></span></h5></td>
													 <td><h5><span class="badge badge-danger"><?= $juma; ?></span></h5></td>
													 
													 <td>											
											<a href="?pg=status&ac=lihat&k=<?= $data['kelas'] ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Absen"><i class="material-icons">crisis_alert</i> </a>
											
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
				<?php elseif($ac == 'lihat'): ?>
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
													 <th>KETERANGAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											   $tgl = date('Y-m-d');
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM absensi where kelas='$_GET[k]' and tanggal='$tgl'"); 
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
                                                    <td style="text-align:center;"> <h5><span class="badge badge-dark"><?= strtoupper($data['level']) ?></span></h5></td>
                                                    <td style="text-align:center;"><h5><span class="badge badge-info"><?= $data['kelas'] ?></span></h5></td>
													 <td style="text-align:center;">
													 <?php if($data['ket']=='H'): ?>
													 <h5><span class="badge badge-success">HADIR</span></h5>
													 <?php elseif($data['ket']=='S'): ?>
													 <h5><span class="badge badge-primary">SAKIT</span></h5>
													 <?php elseif($data['ket']=='I'): ?>
													 <h5><span class="badge badge-warning">IZIN</span></h5>
													 <?php elseif($data['ket']=='A'): ?>
													 <h5><span class="badge badge-danger">ALPHA</span></h5>
													 <?php endif; ?>
													 </td>
                                                  <td style="text-align:center;">
												  
												  <?= $data['masuk'] ?>
												 
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
           					
        <?php endif; ?>		 