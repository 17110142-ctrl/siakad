 <?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulan = date('m');
$blQ = fetch($koneksi,'bulan',['bln'=>$bulan]);
 if (empty($_GET['bln'])) {
        $bln = "";
    } else {
        $bln = $_GET['bln'];
    }
?>


                        <div class="row">
                           <div class="col-xl-8">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">
										BULAN <?= strtoupper($blQ['ket']); ?> <?= date('Y') ?>
										<button class="btn btn-secondary kanan" type="button" disabled>
                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
									<?= buat_tanggal('D, d M Y') ?> </button>
										</h5>
                                    </div>
									
                                    <div class="card-body">
									
									
                                         <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NAMA LENGKAP</th>
                                                    <th>JABATAN</th>
                                                      <th>H &nbsp;</th>
                                                    <th>I &nbsp;</th>
													<th>S &nbsp;</th>
													<th>A &nbsp;</th>
                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0; 
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE tugas<>''"); 
											 while ($data = mysqli_fetch_array($query)) :
				                             
											  $hadir = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE idpeg='$data[id_user]' AND ket='H' AND bulan='$bulan'"));
											$izin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE idpeg='$data[id_user]' AND ket='I' AND bulan='$bulan'"));
											$sakit = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE idpeg='$data[id_user]' AND ket='S' AND bulan='$bulan'"));
											$alpha = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE idpeg='$data[id_user]' AND ket='A' AND bulan='$bulan'"));
											
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nama'] ?></td>
                                                     <td><?= strtoupper($data['tugas']); ?></td>
                                                     <td><?= $hadir; ?></td>
													   <td><?= $izin; ?></td>
													     <td><?= $sakit; ?></td>
														   <td><?= $alpha; ?></td>
                                                    
                                                </tr>
												<?php endwhile; ?>
                                                </table>
												 </div>
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                         
                             <div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">PRESENSI PEMBINA ESKUL</h5>
                                    </div>
                                    <div class="card-body">
									
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $setting['sekolah'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
													 <label class="form-label">BULAN</label>                               
													  <select  class="form-select bln" id="bln" style="width: 100%;" required >
												   <?php $qt = mysqli_query($koneksi, "SELECT * FROM bulan"); ?>
													<option> Pilih Bulan</option>
													<?php while ($mt = mysqli_fetch_array($qt)) : ?>
														<option <?php if ($bln == $mt['bln']) {
														echo "selected";
														} else {
														} ?> value="<?= $mt['bln'] ?>"><?= $mt['ket'] ?> <?= date('Y') ?></option>
													<?php endwhile; ?>
												  </select>   
													</span>                                                  
                                                </div>                                               
                                            </div>
											<script type="text/javascript">
                                $('#bln').change(function() {
                                    var bln = $('.bln').val();
                                  
                                    location.replace("?pg=<?= enkripsi('abpeg2') ?>&bln=" + bln);
                                }); 
                            </script>
											<p>
									        <?php if($bln!=''): ?>
                                           <div class="d-grid gap-2">                                  
                                                <a href="cetak/cetakpeg2.php?bln=<?= $bln ?>" target="_blank" class="btn btn-danger flex-grow-1 m-l-xxs">CETAK REKAP PDF</a>
                                            </div>
											  
											  <?php endif; ?>
                                           </div>
										
                                       </div>
                                    </div>
                            
                                </div>
                            </div>
                        </div>	
					</div>
                  </div>
			  </div>
			