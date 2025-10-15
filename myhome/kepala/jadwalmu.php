<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>           
			   
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">JADWAL KBM</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>HARI</th>
													<th>TKT - KELAS</th>
                                                    <th>MATA PELAJARAN</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $harix = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
											  $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
											  $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
											  $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kuri']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $harix['hari'] ?></td>
                                                     <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span> <span class="badge badge-primary"> <?= $data['kelas'] ?></span></h5></td>
													  <td><?= $mapelx['nama_mapel'] ?><br><span class="badge badge-secondary"><?= $guru['nama'] ?></span> <span class="badge badge-info"><?= $kuri['nama_kurikulum'] ?></span></td>
													  
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									 
					</div>
				</div>
				