 <?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulan = date('m');
$blQ = fetch($koneksi,'bulan',['bln'=>$bulan]);
?>


                        <div class="row">
                           <div class="col-xl-12">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">
										PELANGGARAN SISWA KELAS <?= $user['walas'] ?>
										</h5>
                                    </div>
									
                                    <div class="card-body">
									
									
                                         <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th >TANGGAL</th>
                                                    <th >NIS</th>
													<th >NAMA SISWA</th>
													<th >KETERANGAN</th>
													<th >POIN</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0; 
											
											$query = mysqli_query($koneksi, "SELECT * FROM bk_siswa WHERE kelas='$user[walas]'"); 
											 while ($data = mysqli_fetch_array($query)) :
				                            $siswa = fetch($koneksi,'siswa',['nis'=>$data['nis']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                   
                                                    <td><?= $data['tanggal'] ?></td>
													<td><?= $siswa['nis'] ?></td>
													   <td><?= $siswa['nama'] ?></td>
													<td><?= $data['ket'] ?></td>
													<td><?= $data['poin'] ?></td>
													
											   </tr>
												<?php endwhile; ?>
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
											   url: 'siswa/edit.php?pg=hapusprestasi',
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
														window.location.replace('?pg=pelanggaran');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    