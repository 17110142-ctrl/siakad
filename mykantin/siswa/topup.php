<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>      
     
	
					<div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA DEBET</h5>
										
									</div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                         <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>
                                                    <th>TANGGAL</th>
													<th>JAM</th>
                                                    <th>NAMA KONSUMEN</th>
                                                    <th>DEBET</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM saldo WHERE debet >0 ORDER BY tanggal DESC"); 
											  while ($data = mysqli_fetch_assoc($query)) :
											  $siswa = fetch($koneksi,'siswa',['id_siswa'=>$data['idsiswa']]);
											$no++;
											   ?>
                                                <tr>
                                                  <td><?= $no; ?></td>
                                                  <td><?= $data['tanggal'] ?></td>
												   <td><?= $data['jam'] ?></td>
                                                  <td><?= $siswa['nama'] ?></td>
                                                  <td><?= number_format($data['debet']) ?></td>
                                                 
                                                </tr>
												<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
								</div>		
		 
		
				