									<?php
									require("../config/koneksi.php");
									require("../config/function.php");
									require("../config/crud.php");
									?>           
												   
			
									<?php
											$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE status='1' GROUP BY idsiswa"); 
											  while ($data = mysqli_fetch_array($query)) :
											 $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_siswa,nama FROM siswa  WHERE id_siswa='$data[idsiswa]'"));
											  
											  ?>
                                            <b><?= $siswa['nama'] ?></b> 
											<div class="kanan"><h5><span class="badge badge-primary">BELUM BAYAR</span></h5></div>
                                            <table id="datata" class="table table-bordered" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                   <th width="5%">NO</th>                                               
                                                   <th>NAMA BARANG</th>
												   <th width="5%">JML</th>
												   <th width="15%">HARGA</th>
												    <th width="15%">TOTAL</th>
													   <th width="5%">CANCEL</th>
                                                </tr>
                                            </thead>
											<tbody>
											<?php
											$no=0;
											$queryx = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE status='1' and idsiswa='$data[idsiswa]'"); 
											  while ($datax = mysqli_fetch_array($queryx)) :
											   $produk = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM produk WHERE produk_id='$datax[idproduk]'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $produk['produk_nama'] ?></td>
													 <td><?= $datax['jumlah'] ?></td>
													  <td><?= number_format($datax['harga']) ?></td>
													   <td><?= number_format($datax['total_harga']) ?></td>
													   <td>
													   <button data-id="<?= $datax['id'] ?>"  class="hapus btn btn-sm btn-primary">
				                                       <i class="material-icons">close</i></button>
													   </td>
													</tr>
													<?php endwhile; ?>
													</tbody>
                                                </table>
												<?php  $total = mysqli_fetch_array(mysqli_query($koneksi, "SELECT idsiswa,SUM(total_harga) AS total FROM transaksi  WHERE idsiswa='$data[idsiswa]' AND status='1'")); ?>
												<table id="datata" class="table table-bordered" style="width:100%;font-size:12px">
                                             
                                                <tr>
												<td colspan="4" style="text-align:right;font-weight:bold;">TOTAL</td>
												<td width="20%" style="background-color:yellow;font-weight:bold;"><?= number_format($total['total']) ?></td>
												</tr>
												</table>
												<?php endwhile; ?>
									<script>
									$('#datata').on('click', '.hapus', function() {
									var id = $(this).data('id');
									console.log(id);
									swal({
											  title: 'Yakin akan Cancel Pesanan?',
											  text: "Pesanan Akan di Batalkan",
											  type: 'warning',
											  showCancelButton: true,
											  confirmButtonColor: '#3085d6',
											  cancelButtonColor: '#d33',
											  confirmButtonText: 'Ya, Cancel',
											  cancelButtonText: "Batal"				  
									}).then((result) => {
										if (result.value) {
											$.ajax({
											   url: 'siswa/batal.php',
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
														window.location.reload();
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    