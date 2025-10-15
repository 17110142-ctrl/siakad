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
                                                   <th>DETAIL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0; 
											$query = mysqli_query($koneksi, "SELECT * FROM users where level<>'admin'"); 
											 while ($data = mysqli_fetch_array($query)) :
				                             
											$id_user = $data['id_user'];

$sql = "SELECT 
            SUM(ket = 'H') AS hadir, 
            SUM(ket = 'I') AS izin, 
            SUM(ket = 'S') AS sakit, 
            SUM(ket = 'A') AS alpha 
        FROM absensi 
        WHERE idpeg = '$id_user' AND bulan = '$bulan'";

$result = mysqli_query($koneksi, $sql);
$absen = mysqli_fetch_assoc($result);

$hadir = $absen['hadir'] ?? 0;
$izin = $absen['izin'] ?? 0;
$sakit = $absen['sakit'] ?? 0;
$alpha = $absen['alpha'] ?? 0;

											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= $data['nama'] ?></td>
                                                     <td><?= ucfirst($data['level']); ?></td>
                                                     <td><?= $hadir; ?></td>
													   <td><?= $izin; ?></td>
													     <td><?= $sakit; ?></td>
														   <td><?= $alpha; ?></td>
                                                    <td>
													<a href="?pg=<?= enkripsi('detail') ?>&ids=<?= $data['id_user'] ?>" class="btn btn-sm btn-primary">Detail</a>
													</td>
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
                                        <h5 class="card-title">DATA PRESENSI PEGAWAI</h5>
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
                                  
                                    location.replace("?pg=<?= enkripsi('abpeg') ?>&bln=" + bln);
                                }); 
                            </script>
											<p>
									        <?php if($bln!=''): ?>
                                           <div class="d-grid gap-2">                                  
                                                <a href="cetak/cetakpeg.php?bln=<?= $bln ?>" target="_blank" class="btn btn-danger flex-grow-1 m-l-xxs">CETAK REKAP PDF</a>
                                            </div><p>
											 <div class="d-grid gap-2">                                  
                                                <a href="cetak/prosesexcel.php?bln=<?= $bln ?>" target="_blank" class="btn btn-success flex-grow-1 m-l-xxs">CETAK REKAP EXCEL</a>
                                            </div><p>
											<div class="d-grid gap-2">                                  
                                                <a href="cetak/cetakprosen.php?bln=<?= $bln ?>" target="_blank" class="btn btn-dark flex-grow-1 m-l-xxs">REKAP PROSENTASE PDF</a>
                                              </div>
											  <p>
											<div class="d-grid gap-2">                                  
                                                <a href="cetak/excel.php?bln=<?= $bln ?>" target="_blank" class="btn btn-success flex-grow-1 m-l-xxs">REKAP PROSENTASE EXCEL</a>
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
			