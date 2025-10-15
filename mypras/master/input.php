<?php
defined('APK') or exit('No Access');

?>           
	
		 
                      <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">SAPRAS</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>  	
													<th>KATEGORI</th>
													<th>LOKASI / RUANG</th>	
													<th>NAMA BARANG</th>													
													 <th width="30%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM s_barang ORDER BY id DESC"); 
											while ($data = mysqli_fetch_array($query)) :
											$kate = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM s_kategori  WHERE id='$data[idk]'"));
											$lok = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM s_lokasi  WHERE id='$data[idl]'"));											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													<td><?= $kate['kategori'] ?></td>
													<td><?= $lok['nama'] ?></td>
													<td>
													<?php if($data['idk']==1): ?>
													<?= $lok['nama'] ?>
													<?php else: ?>
													<?= $data['nama'] ?>
													<?php endif; ?>
													</td>
													  <td>
											<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#example<?= $data['id'] ?>"><i class="material-icons">download</i>Detail</button>
											<a href="?pg=<?= enkripsi('barang') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($data['id']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i> </a>											
											<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
											</td>
											<div class="modal fade" id="example<?= $data['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLiveLabel"><?= $kate['kategori'] ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php if($data['idk']==1): ?>
																<label class="bold">Nama Bangunan</label>
															  <div class="input-group mb-1">
															 <select name='idb' class='form-select' style="width: 100%">
															<option value="<?= $data['idb'] ?>"><?= $lok['nama'] ?></option>
															</select>
															 </div>
																<label class="bold">Kondisi</label>
															  <div class="input-group mb-1">
															 <select name='kondisi' class='form-select' required='true' style="width: 100%">
														   <option value="<?= $data['kondisi'] ?>"><?= $data['kondisi'] ?></option>
															 </select>
															 </div>
																<label class="bold">Kerusakan Atap (%)</label>
															  <div class="input-group mb-1">
															 <input type="number" name="atap" class="form-control" value="<?= $data['atap'] ?>" required="true" >
																</div>
																<label class="bold">Kerusakan Lantai (%)</label>
															  <div class="input-group mb-1">
															 <input type="number" name="lantai" class="form-control" value="<?= $data['lantai'] ?>" required="true" >
																</div>
																<label class="bold">Kerusakan Dinding (%)</label>
															  <div class="input-group mb-1">
															 <input type="number" name="dinding" class="form-control" value="<?= $data['dinding'] ?>" required="true" >
																</div>
																<label class="bold">Kerusakan Pintu (%)</label>
															  <div class="input-group mb-1">
															 <input type="number" name="pintu" class="form-control" value="<?= $data['pintu'] ?>" required="true" >
																</div>
																<label class="bold">Kerusakan Jendela (%)</label>
															  <div class="input-group mb-1">
															 <input type="number" name="jendela" class="form-control" value="<?= $data['jendela'] ?>" required="true" >
																</div>
																<?php else: ?>
																<label class="bold">Lokasi</label>
															  <div class="input-group mb-1">
															 <select name='idl' class='form-select'  style="width: 100%">
															<option value="<?= $data['idl'] ?>"><?= $lok['nama'] ?></option>
															</select>
															 </div>
															 <label class="bold">Nama Barang</label>
															  <div class="input-group mb-1">
															 <input type="text"  value="<?= $data['nama'] ?>" class="form-control" >
																</div>
																<div class="row">
																<div class="col-md-6">
																<label class="bold">Jumlah Baik</label>															  
															 <input type="number" class="form-control" value="<?= $data['baik'] ?>" required="true" >
																</div>
																<div class="col-md-6">
																<label class="bold">Jumlah Rusak Berat</label>
															 <input type="number"  class="form-control" value="<?= $data['rb'] ?>" required="true" >
																</div>
																</div>
																<div class="row">
																<div class="col-md-6">
																<label class="bold">Jumlah Rusak Sedang</label>
															 <input type="number"  class="form-control" value="<?= $data['rs'] ?>" required="true" >
																</div>
																<div class="col-md-6">
																<label class="bold">Jumlah Rusak Ringan</label>
															 <input type="number" class="form-control" value="<?= $data['rr'] ?>" required="true" >
																</div>
																</div>
																<?php endif; ?>
																
																<div class="row">
																<div class="col-md-6">
																<label class="bold">Foto Barang Baik</label>
															     <?php if($data['foto'] !=''): ?>
																 <br>
																 <img src="../images/sapras/<?= $data['foto'] ?>" style="max-width:150px;">
																<?php else: ?>
																
																<?php endif; ?>
																</div>
																<div class="col-md-6">
																<label class="bold">Foto Barang Rusak Berat</label>
															     <?php if($data['foto_rb'] !=''): ?>
																 <br>
																 <img src="../images/sapras/<?= $data['foto_rb'] ?>" style="max-width:150px;">
																<?php else: ?>
																
																<?php endif; ?>
																</div>
																</div>
																
																<div class="row">
																<div class="col-md-6">
																<label class="bold">Foto Barang Rusak Sedang</label>
															     <?php if($data['foto_rs'] !=''): ?>
																 <br>
																 <img src="../images/sapras/<?= $data['foto_rs'] ?>" style="max-width:150px;">
																<?php else: ?>
																
																<?php endif; ?>
																</div>
																<div class="col-md-6">
																<label class="bold">Foto Barang Rusak Ringan</label>
															     <?php if($data['foto_rr'] !=''): ?>
																 <br>
																 <img src="../images/sapras/<?= $data['foto_rr'] ?>" style="max-width:150px;">
																<?php else: ?>
																
																<?php endif; ?>
																</div>
																</div>
																
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                              
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           
											
											
											
											
                                                </tr>
												<?php endwhile; ?>
                                                </table>
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
											   url: 'master/tbarang.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);
													setTimeout(function() {
														window.location.replace('?pg=<?= enkripsi(barang) ?>');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script> 
							<?php if ($ac == '') : ?>
					       <div class="col-md-4">
							  
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                       <h5 class="bold">INPUT</h5>
										
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
									<form id='formbarang' >	
										<label class="bold">Kategori Barang</label>
									  <div class="input-group mb-1">
									 <select name="idk" id="idk" class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Kategori</option>
										<?php
										$kat = mysqli_query($koneksi, "SELECT * FROM s_kategori");
										while ($kate = mysqli_fetch_array($kat)) {
										echo "<option value='$kate[id]'>$kate[kategori]</option>";
										}
										?>
									 </select>
                                        </div>
										
                                        
										<div id="tutup" style="display: none;">
										<label class="bold">Lokasi</label>
									  <div class="input-group mb-1">
									 <select name='idl' class='form-select'  style="width: 100%">
								    <option value=''>Pilih Lokasi</option>
										<?php
										$lok = mysqli_query($koneksi, "SELECT * FROM s_lokasi");
										while ($loka = mysqli_fetch_array($lok)) {
										echo "<option value='$loka[id]'>$loka[nama]</option>";
										}
										?>
									 </select>
									 </div>
									 <label class="bold">Nama Barang</label>
									  <div class="input-group mb-1">
									 <input type="text" name="namabarang" class="form-control"  >
                                        </div>
										<label class="bold">Jumlah Baik</label>
									  <div class="input-group mb-1">
									 <input type="number" name="baik" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Berat</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rb" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Sedang</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rs" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Ringan</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rr" class="form-control" value="0" required="true" >
                                        </div>
										</div>
										
										<div id="buka" style="display: none;">
										<label class="bold">Nama Bangunan</label>
									  <div class="input-group mb-1">
									 <select name='idb' class='form-select' style="width: 100%">
								    <option value=''>Pilih Bangunan</option>
										<?php
										$lok = mysqli_query($koneksi, "SELECT * FROM s_lokasi");
										while ($loka = mysqli_fetch_array($lok)) {
										echo "<option value='$loka[id]'>$loka[nama]</option>";
										}
										?>
									 </select>
									 </div>
										<label class="bold">Kondisi</label>
									  <div class="input-group mb-1">
									 <select name='kondisi' class='form-select' required='true' style="width: 100%">
								    <option value='Baik'>Baik</option>
									<option value='Rusak Ringan'>Rusak Ringan</option>
									<option value='Rusak Sedang'>Rusak Sedang</option>
									<option value='Rusak Berat'>Rusak Berat</option>
									 </select>
									 </div>
										<label class="bold">Kerusakan Atap (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="atap" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Kerusakan Lantai (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="lantai" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Kerusakan Dinding (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="dinding" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Kerusakan Pintu (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="pintu" class="form-control" value="0" required="true" >
                                        </div>
										<label class="bold">Kerusakan Jendela (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="jendela" class="form-control" value="0" required="true" >
                                        </div>
										</div>
										
										<label class="bold">Foto Barang Baik (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Rusak Berat (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file1' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Sedang (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file2' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Rusak Ringan (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file3' class='form-control' />
										 </div>
										 
										<div class="widget-payment-request-actions m-t-lg d-flex">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<script type='text/javascript'>
				document.getElementById('idk').addEventListener('change', function () {
					var style = this.value != 1 ? 'block' : 'none';
					document.getElementById('tutup').style.display = style;
					var style = this.value == 1 ? 'block' : 'none';
					document.getElementById('buka').style.display = style;
				});
				</script>
                 <script>
						$('#formbarang').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'master/tbarang.php?pg=input',
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
									success: function(data) {
									   
										setTimeout(function() {
											window.location.reload();
										}, 2000);
									}
								})
								return false;
							});
							</script>

             <?php elseif ($ac == enkripsi('edit')): ?>
			
             <?php
			 $id = dekripsi($_GET['id']);
			 $dataz = fetch($koneksi,'s_barang',['id'=>$id]);
			 $loka = fetch($koneksi,'s_lokasi',['id'=>$dataz['idl']]);
			 ?>					 
                      
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                      <h5 class="bold">EDIT SAPRAS</h5>
										
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
									<form id='formedit' >	
									<input type="hidden" name="id" value="<?= $dataz['id'] ?>" >
									<input type="hidden" name="idk" value="<?= $dataz['idk'] ?>" >
									<?php if($dataz['idk']==1): ?>
										<label class="bold">Nama Bangunan</label>
									  <div class="input-group mb-1">
									 <select name='idb' class='form-select' style="width: 100%">
								    <option value="<?= $dataz['idb'] ?>"><?= $loka['nama'] ?></option>
									</select>
									 </div>
										<label class="bold">Kondisi</label>
									  <div class="input-group mb-1">
									 <select name='kondisi' class='form-select' required='true' style="width: 100%">
								   <option value="<?= $dataz['kondisi'] ?>"><?= $dataz['kondisi'] ?></option>
								   <option value='Baik'>Baik</option>
									<option value='Rusak Ringan'>Rusak Ringan</option>
									<option value='Rusak Sedang'>Rusak Sedang</option>
									<option value='Rusak Berat'>Rusak Berat</option>
									 </select>
									 </div>
										<label class="bold">Kerusakan Atap (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="atap" class="form-control" value="<?= $dataz['atap'] ?>" required="true" >
                                        </div>
										<label class="bold">Kerusakan Lantai (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="lantai" class="form-control" value="<?= $dataz['lantai'] ?>" required="true" >
                                        </div>
										<label class="bold">Kerusakan Dinding (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="dinding" class="form-control" value="<?= $dataz['dinding'] ?>" required="true" >
                                        </div>
										<label class="bold">Kerusakan Pintu (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="pintu" class="form-control" value="<?= $dataz['pintu'] ?>" required="true" >
                                        </div>
										<label class="bold">Kerusakan Jendela (%)</label>
									  <div class="input-group mb-1">
									 <input type="number" name="jendela" class="form-control" value="<?= $dataz['jendela'] ?>" required="true" >
                                        </div>
										<?php else: ?>
										<label class="bold">Lokasi</label>
									  <div class="input-group mb-1">
									 <select name='idl' class='form-select'  style="width: 100%">
								    <option value="<?= $dataz['idl'] ?>"><?= $loka['nama'] ?></option>
									</select>
									 </div>
									 <label class="bold">Nama Barang</label>
									  <div class="input-group mb-1">
									 <input type="text" name="namabarang" value="<?= $dataz['nama'] ?>" class="form-control" >
                                        </div>
										<label class="bold">Jumlah Baik</label>
									  <div class="input-group mb-1">
									 <input type="number" name="baik" class="form-control" value="<?= $dataz['baik'] ?>" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Berat</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rb" class="form-control" value="<?= $dataz['rb'] ?>" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Sedang</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rs" class="form-control" value="<?= $dataz['rs'] ?>" required="true" >
                                        </div>
										<label class="bold">Jumlah Rusak Ringan</label>
									  <div class="input-group mb-1">
									 <input type="number" name="rr" class="form-control" value="<?= $dataz['rr'] ?>" required="true" >
                                        </div>
										<?php endif; ?>
										<label class="bold">Foto Barang Baik (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Rusak Berat (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file1' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Sedang (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file2' class='form-control' />
										 </div>
										 <label class="bold">Foto Barang Rusak Ringan (Jika Ada)</label>
										<div class="input-group mb-1">
										<input type='file' name='file3' class='form-control' />
										 </div>
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
                 <script>
						$('#formedit').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'master/tbarang.php?pg=edit',
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
									success: function(data) {
									   
										setTimeout(function() {
											window.location.replace('?pg=<?= enkripsi(barang) ?>');
										}, 2000);
									}
								})
								return false;
							});
							</script>
	
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					