<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

?>
								<?php
								$query = mysqli_query($koneksi, "SELECT * FROM reset_andro WHERE sts='0'"); 
								 $cek = mysqli_num_rows($query);
				                if ($cek >= 1) {
								 while ($andro = mysqli_fetch_array($query)) :
								  $siswa = fetch($koneksi,'siswa',['id_siswa'=>$andro['idsiswa']]);

										$no++;
                                   ?>
								   
								   <ul class="list-unstyled timeline">
                    <li>
                      <div class="block">
                        <div class="tags">
                          <a href="" class="tag">
                            <span><?= $siswa['kelas'] ?> <?= $siswa['pk'] ?></span>
                          </a>
                        </div>
                        <div class="block_content">
						 
                          <h2 class="title bold">
						  <a><?= $siswa['nama'] ?>  - <?= $siswa['kelas'] ?></a>
							</h2>
								  <span class="kanan" id="data">	<button data-id="<?= $siswa['id_siswa'] ?>"  class="hapus btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-close"></i> Reset</button></span>
                          <div class="byline">
                            <span><b style="color:red;" ><?= $andro['waktu']; ?></b></span><br>  
							<a><b style="color:blue;" >Browser  APK Android</b>
							 
							</a>
                          </div>						   
                        </div>
                      </div>
                    </li>
                    </ul>
							
						
								<?php endwhile; ?>
								<?php }else{ ?>
	<h4 class='text-center' style="color:blue;">
                            <br /><i class='fa fa-spin fa-circle-o-notch'></i> Loading....<br><small>Belum ada Pelanggaran</small>
                        </h4>
           <?php } ?>
								</tbody>           
							  </table>
							</div>
							<script>
							
						$('#data').on('click', '.hapus', function() {
							var id = $(this).data('id');
							console.log(id);
							swal({
							title: 'Yakin Reset Peserta Ujian?',
							text: "You won't be able to revert this!",
							type: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Ya, Reset!',
							cancelButtonText: "Batal"				  
							}).then((result) => {
							if (result.value) {
							$.ajax({
								url: 'sandik_ujian/tsiswa.php?pg=resetandro',
								method: "POST",
								data: 'id=' + id,
								success: function(data) {
								iziToast.info(
							{
							title: 'Sukses!',
							message: 'Data berhasil direset',
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
							
			