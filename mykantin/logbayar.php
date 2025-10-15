<style>
.responsive {
  width: auto;
  height: 100px;
}
</style>	
								<?php
									require("../config/koneksi.php");
									require("../config/function.php");
									require("../config/crud.php");
									?>           
												   
			
						
                                            <table id="datata" class="table table-bordered" style="width:100%;font-size:12px">
                                            
											<?php
											$tanggal = date('Y-m-d');
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM saldo WHERE tanggal='$tanggal' and kredit >0 ORDER BY id DESC LIMIT 1"); 
											  while ($data = mysqli_fetch_array($query)) :
											 $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa  WHERE id_siswa='$data[idsiswa]'"));
											  
											$no++;
											   ?>
                                                <tr>
                                                  <td style="text-align:center; vertical-align:center;width:40%">
													<?php if($siswa['foto'] ==''): ?>
														<img src="../images/user.png" class="responsive">												
													<?php else: ?>
												        <img src="../images/foto/<?= $siswa['foto'] ?>" class="responsive">
													<?php endif; ?>													
													</td>
                                                    <td style="text-align:left; vertical-align:center;width:60%;font-weight:bold;">
													<?= $siswa['nama'] ?>
													 <br><?= $siswa['nis'] ?>
													 <h5><span class="badge badge-dark"><?= $siswa['kelas'] ?></span></h5>
													 Waktu Bayar
													  <h5><span class="badge badge-primary"><?= $data['tanggal'] ?></span>  <span class="badge badge-primary"><?= $data['jam'] ?></span></h5>
													 </td>
													</tr>
													<tr>
													 <td style="text-align:center; vertical-align:center;width:40%;font-weight:bold;">
													 PAYMENT CARD
													 <h5><span class="badge badge-primary"><?= $siswa['nokartu'] ?></span></h5>
													 </td>
													 <td style="text-align:left; vertical-align:center;width:60%;font-weight:bold;">
													 TOTAL BAYAR
													 <h5><span class="badge badge-success">RP <?= number_format($data['kredit']) ?></span></h5>
													 </td>
													</tr>
													<?php endwhile; ?>
													
                                                </table>
												