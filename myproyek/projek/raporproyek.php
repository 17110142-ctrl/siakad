                         
<?php
 if (empty($_GET['kelas'])) {
        $kelas = "";
    } else {
        $kelas = $_GET['kelas'];
    }
	
	?>




						 <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        
                                    </div>
                                    <div class="card-body">
                                        
                                            <div class="example-content">
                                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
                                                    </li>
                                                   
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                     <div class="row">
									<div class="col-md-8">
									<div class="card">
										<div class="card-header">
											<h5 class="card-title">CETAK RAPOR PROYEK <?= $kelas ?></h5>
											
										</div>
										<div class="card-body">
										
										<div class="card-box table-responsive">
											<table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
												<thead>
													<tr>
														<th width="5%">NO</th>                                               
														<th>NIS</th>
														<th>NAMA SISWA</th>
														 
														 <th></th>
													</tr>
												</thead>
												<tbody>
												<?php
												
												$no=0;
												$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'"); 
												  while ($data = mysqli_fetch_array($query)) :												 
												   $peg = fetch($koneksi,'users',['id_user'=>$data['idguru']]);
												 $no++;
												   ?>
													<tr>
												<td><?= $no; ?></td>
												<td><?= $data['nis'] ?></td>                                                    
												<td><?= $data['nama'] ?></td>
												<td>
												
												<a href="projek/print_raport.php?nis=<?= $data['nis'] ?>" target="_blank" class="btn btn-sm btn-primary"><i class="material-icons">print</i></a>
												
												 </td>
													</tr>
													
													<?php endwhile; ?>
													</tbody>
													</table>
													 </div>
												</div>
											</div>
										</div>
									
													   <div class="col-md-4">
												 
															<div class="card widget widget-payment-request">
																<div class="card-header">
															  
																</div>
																<div class="card-body">
																	<div class="widget-payment-request-container">
																		<div class="widget-payment-request-author">
																			<div class="avatar m-r-sm">
																				<img src="../images/guru.png" alt="">
																			</div>
																			<div class="widget-payment-request-author-info">
																				<span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
																				<span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
																		   
																		   </div>
																		</div>
																		<div class="widget-payment-request-info m-t-md">
																		 <div class="d-grid gap-2">
															         <button class="btn btn-secondary" type="button" disabled>
																	<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
																	Rapor Kelas <?= $kelas ?>
																</button>
																</div><p>
																<div class="col-md-12">
																	<label class="form-label bold">Rombel</label>
																	<select class="form-select kelas">
															<?php $k = mysqli_query($koneksi, "select * from kelas WHERE kurikulum='2'"); ?>
															<option value=''> Pilih Kelas</option>
															<?php while ($kls = mysqli_fetch_array($k)) : ?>
																<option <?php if ($kelas == $kls['kelas']) {
																			echo "selected";
																		} else {
																		} ?> value="<?= $kls['kelas'] ?>"><?= $kls['kelas'] ?></option>
															<?php endwhile; ?>
														</select>
															 </div>	
															 
															 <p>
																		  <div class="d-grid gap-2">
																	 <button id="cari_kelas" class="btn btn-primary flex-grow-1 m-l-xxs">CARI ROMBEL</button>
																		</div>
																		 <script type="text/javascript">
															$('#cari_kelas').click(function() {
																var kelas = $('.kelas').val();
																
																location.replace("?pg=<?= enkripsi('raporproyek') ?>&kelas=" + kelas );
															}); 
														</script>
																		   </div>
																
																	</div>
																 </div>
															</div>
															</div>
														</div>										
												   </div>
                                                   
                                           </div>