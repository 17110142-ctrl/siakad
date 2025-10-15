<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
if (empty($_GET['idtoko'])) {
        $idtoko = "";
        
    } else {
        $idtoko = $_GET['idtoko'];
       
    }
	$toko = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM toko  WHERE idt='$idtoko'"));
?>           
			   
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">TRANSAKSI TOKO <?= $toko['nama_toko'] ?> <small>HARI INI</small></h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>NAMA PRODUK</th>
											         <th>JUMLAH</th>
													 <th>TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM transaksi JOIN produk ON produk.produk_id=transaksi.idproduk WHERE produk.produk_toko='$idtoko' AND transaksi.status='2' AND transaksi.tanggal='$tanggal' GROUP BY transaksi.idproduk"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $trx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(jumlah) AS jml,SUM(total_harga) AS total FROM transaksi  WHERE idproduk='$data[idproduk]' AND tanggal='$tanggal'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                     <td><?= $data['produk_nama'] ?></td>
													 <td><?= $trx['jml'] ?></td>
													 <td>RP <?= number_format($trx['total']) ?></td>
													 
                                                </tr>
												<?php endwhile; ?>
												<tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
					
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">CETAK TRANSAKSI</h5>
										
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= $user['nama'] ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">
									
										<label class="bold">TOKO</label>
									  <div class="input-group mb-1">
                                      <select class="form-select idtoko" required style="width: 100%">
									  <?php $level = mysqli_query($koneksi, "select * from toko"); ?>
										<option value=''>Pilih toko</option>
										 <?php while ($kls = mysqli_fetch_array($level)) : ?>
										<option <?php if ($idtoko == $kls['idt']) {
                                                echo "selected";
                                            } else {
                                            } ?> value="<?= $kls['idt'] ?>"><?= $kls['nama_toko'] ?></option>
                                         <?php endwhile; ?>
										</select> 
                                        </div>
										<label class="bold">Tanggal Transaksi</label>
									  <div class="input-group mb-1">
                                       <input type='text' name='tgl' class='form-control' value="<?= $tanggal ?>" readonly />
                                        </div>
										
										<div class="widget-payment-request-actions m-t-lg d-flex">
                                         <button id="cari" class="btn btn-success flex-grow-1 m-l-xxs">CARI</button>
										 <?php if($idtoko==''): ?>
										 <button class="btn btn-secondary flex-grow-1 m-l-xxs" disabled>CETAK</button>
										 <?php else : ?>
                                         <button  class="btn btn-primary flex-grow-1 m-l-xxs" onclick="frames['frameresult'].print()">CETAK</button>
                                            <?php endif; ?>
											</div>
										
									 </div>
					            </div>
								</div>
							</div>
						</div>
					<script type="text/javascript">
                                $('#cari').click(function() {
                                    var idtoko = $('.idtoko').val();
                                   
                                    location.replace("?pg=trxtoko&idtoko=" + idtoko);
                                }); 
                            </script>
					<iframe id='loadframe' name='frameresult' src='toko/cetaktrxtoko.php?tgl=<?= $tanggal ?>&idtoko=<?= $idtoko ?>' style='display:none'></iframe>
