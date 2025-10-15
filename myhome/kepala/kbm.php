<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">FILES ADMINISTRASI</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th> 
													<th>NAMA GURU</th>													                                                 
                                                    <th>NAMA FILES</th>
													
													 <th>TGL UPLOAD</th>
													  <th width="15%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											
											$query = mysqli_query($koneksi, "SELECT * FROM adm ORDER BY tanggal DESC"); 
											
											  while ($data = mysqli_fetch_array($query)) :
											 $mapel = fetch($koneksi,'mata_pelajaran',['id'=>$data['idmapel']]);
											  $peg = fetch($koneksi,'users',['id_user'=>$data['idguru']]);
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													 <td><?= $peg['nama'] ?></td>
                                                      <td><?= $data['nama'] ?> <br><?= $mapel['kode'] ?> - <?= $data['kelas'] ?></td>
													    <td><?= $data['tanggal'] ?></td>
													  <td>
													  <?php if($data['file'] !=''): ?>
													  <a href="../adm/<?= $data['file'] ?>" target="_blank"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Cetak"><i class="material-icons">print</i></button></a>
													  <?php endif; ?>
													  
													   <?php if($data['link'] !=''): ?>
													  <a href="<?= $data['link'] ?>" target="_blank"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Download"><i class="material-icons">download</i></button></a>
													 
													  <?php endif; ?>
											           <a href="?pg=kbm&ac=pesan&id=<?= $data['id'] ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Kirim Pesan"><i class="material-icons">mail</i></button></a>
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
											   url: 'upload/tupload.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													  iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil dihapus',
											titleColor: '#FFFF00',
											messageColor: '#fff',
											backgroundColor: 'rgba(0, 0, 0, 0.5)',
											 progressBarColor: '#FFFF00',
											  position: 'topRight'				  
											});
													setTimeout(function() {
														window.location.replace('?pg=upload');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    
					      
					<?php elseif($ac == 'pesan'): ?>	
						 <?php
						 $id = $_GET['id'];
						   $data= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM adm WHERE id='$id'"));						
                            $guru= mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$data[idguru]'"));
							?>
							<div class="row">
							<div class="col-md-3"></div>
                              <div class="col-md-6">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">KIRIM PESAN</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
												<?php if($user['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
                                               <?php else : ?>
											    <img src="../images/fotoguru/<?= $user['foto'] ?>" alt="">
												<?php endif; ?>

											   </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $user['nama'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									<form id='formpesan' >	
									  <input type="hidden" name="id" value="<?= $data['id'] ?>" >
										 <label>Untuk</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='nama' value="<?= $guru['nama'] ?>" class='form-control' required='true' />
                                        </div>
										<label>Nama File Upload</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='file' value="<?= $data['nama'] ?>" class='form-control' readonly />
                                        </div>
										<label>Kelas</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='kelas' value="<?= $data['kelas'] ?>" class='form-control' required='true' />
                                        </div>
										<label>Nomor WA</label>
									  <div class="input-group mb-1">
                                       <input type='number' name='nowa' value="<?= $guru['nowa'] ?>" class='form-control' readonly />
                                        </div>
										
                                      <label>Pesan Yang disampaikan</label>
									  <div class="input-group mb-3">
                                       <textarea name="pesan" class='form-control' rows="5"/></textarea>
                                        </div>	
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">KIRIM</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>				
					</div>
				<script>
$('#formpesan').submit(function(e)
{
    e.preventDefault();
    var data = new FormData(this);
    
    $.ajax(
    {
        type: 'POST',
        url: 'kepala/pesan.php',
        enctype: 'multipart/form-data',
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
                window.location.replace('?pg=kbm');
            }, 2000);
            
        }
    });
    return false;
});
</script>

					
						
					 
					  <?php endif ?>
					  
					  
					  
					  	  
					  
					  
					