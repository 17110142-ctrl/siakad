     <?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

$has_kurikulum_priv = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
$can_manage_all_classes = ($user['level'] ?? '') === 'admin' || $has_kurikulum_priv;

?>           
<?php if ($ac == '') : ?>
 <?php

    if (empty($_GET['kelas'])) {
        $kelas = "";
    } else {
        $kelas = $_GET['kelas'];
    }
	 
     $lvl = fetch($koneksi,'kelas',['kelas'=>$kelas]);
	 $level = $lvl['level'];
	 $pk = $lvl['pk'];
	
    ?>	
					<div class="row">
                          <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold">LEGER <span class="badge badge-primary"><?= strtoupper($kelas) ?></span> <span class="badge badge-primary"><?= strtoupper($kd) ?></span></h5>
										<div class="pull-right">
										<?php if($kelas !=''): ?>
										<a href="walas/cetakleger.php?k=<?= $kelas ?>" target="_blank" class="btn btn-primary"><i class="material-icons">print</i> SUMATIF</a>
										
										<?php endif; ?>
										</div>
                                    </div>
                                    <div class="card-body">
										
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                 
                                                    <th>NAMA LENGKAP</th>
											   <?php																				
											 $queryx = mysqli_query($koneksi,"SELECT * FROM mapel_rapor a join mata_pelajaran b ON b.id=a.mapel where a.tingkat='$level' and a.pk='$pk' order by a.urut asc");  
											 while ($datax = mysqli_fetch_array($queryx)) :
											   ?>
										   <th><?= $datax['kode'] ?></th>
                                           <?php endwhile; ?>										
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php											
											$no=0;										
											$query = mysqli_query($koneksi,"SELECT * FROM siswa where kelas='$kelas'");                                       
											while ($siswa = mysqli_fetch_array($query)) :									
											$no++;
											?>
                                            <tr>
											 <td style="text-align: center"><?= $no; ?></td>
											 <td>&nbsp;<?= ucwords(strtolower($siswa['nama'])) ?></td>						
											<?php																				
											$querys = mysqli_query($koneksi,"SELECT * FROM mapel_rapor WHERE tingkat='$siswa[level]' and pk='$siswa[jurusan]'");                                       
											while ($datas = mysqli_fetch_array($querys)){
											$nilai = mysqli_fetch_array(mysqli_query($koneksi,"SELECT AVG(nilai) AS rata,nis,mapel,kelas,semester,tp FROM nilai_sumatif WHERE nis='$siswa[nis]' AND mapel='$datas[mapel]' and semester='$semester' and tp='$tapel' GROUP BY mapel"));
											?>
											<td style="text-align: center"><?= number_format($nilai['rata']) ?></td>
											<?php } ?>
											   </tr>
											<?php endwhile; ?>
												</tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
									     
					       <div class="col-md-3">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">                                    
										<h5 class="card-title">LEGER NILAI</h5>									
									</div>
                                    <div class="card-body">
									
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name">LEGER NILAI</span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											<p>
                                             <div class="widget-payment-request-info-item">	
									<label class="bold">Semester</label>
								<div class="input-group mb-2">
								<select name='smt'  class='form-select' required='true' style="width: 100%">
								    <option value="<?= $setting['semester'] ?>"><?= $setting['semester'] ?></option>									
									 </select>
							     </div>															 
                                   									
									<label class="bold">Pilih Kelas</label>
									  <div class="input-group mb-2">
                                     <select id='kelas'  class='form-select kelas' required='true' style="width: 100%">
					    	 <?php 
							 if($can_manage_all_classes){
							 $kls = mysqli_query($koneksi, "SELECT * FROM kelas where kurikulum='2'"); 
							 }else{
							$kls = mysqli_query($koneksi, "SELECT * FROM kelas where kurikulum='2' and kelas='$user[walas]'");
							 } 
							 ?>
										 	<option value=''>Pilih Kelas</option>
													<?php while ($k = mysqli_fetch_array($kls)): ?>
														<option <?php if ($kelas == $k['kelas']) {
                                                echo "selected";
                                            } else {
                                            } ?> value="<?= $k['kelas'] ?>"><?= $k['kelas'] ?></option>
                                           <?php endwhile; ?>							
									 </select>                                    
									   </div>
									  
									   <div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button  id="simpan" class="btn btn-primary flex-grow-1 m-l-xxs">PILIH</button>
                                            </div>
												<script type="text/javascript">
                                $('#simpan').click(function() {
                                    var kelas = $('.kelas').val();
                                    
                                    location.replace("?pg=<?= enkripsi('leger') ?>&kelas=" + kelas);
                                }); 
                            </script>                                             
                                                </div>                                               
                                            </div>
									   </div>
									  
					               </div>								   
								</div>
							</div>
							
						</div>
				
					
                   
					 <?php endif ?> 
					 	
						
