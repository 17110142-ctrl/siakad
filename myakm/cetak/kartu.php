<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<?php if ($ac == '') : ?>
                   
                   <div class="row"> 
                          <div class="col-md-8">
                                <div class="card">
                                 
				        <div class="card-body">                     
                               <form method="POST" action="?pg=<?= enkripsi('karpes') ?>&ac=<?= enkripsi('kelas') ?>" enctype="multipart/form-data">
								<div class="row mb-2">
                                 <label  class="col-sm-2 control-label bold">Header</label>
								 <div class="col-md-10">
								<textarea id='headerkartu' name="jawab" class='form-control text-center' onchange='kirim_form();' rows='2'><?= $setting['header_kartu'] ?></textarea>
								     </div>
									</div>
									<div class="row mb-2">
                                 <label  class="col-sm-2 control-label bold">Tingkat</label>
								 <div class="col-md-10">
                                        <select name="tingkat" id="tingkat" class="form-select" style="width: 100%;" required >
                              <option value=''></option>
                                 <?php $kls = mysqli_query($koneksi, "SELECT level FROM kelas GROUP BY level"); ?>
                                   <?php while ($Q = mysqli_fetch_array($kls)) : ?>
                                     <option value="<?= $Q['level'] ?>"><?= $Q['level'] ?></option>
                                        <?php endwhile ?>
                                           </select> 
                                    </div>
									</div>
									<div class="row mb-2">
                                   <label  class="col-sm-2 control-label bold">Kelas</label>
								   <div class="col-md-10">
                                        <select name="kelas" id="kelas" class="form-select" style="width: 100%;" required >
                              
                                           </select> 
                                    </div>
									</div>
                                    <div class="row mb-2">
                                       <label  class="col-sm-2 control-label bold"></label>
								   <div class="col-md-10">									
                                       <button type="submit" name="submit" class='btn btn-primary kanan'><i class='material-icons'>check</i> Pilih</button>  
                                    </div>
								</div>
                                </form>
                           
                              
								      </div>
									</div>
								</div>
		<script>
		function kirim_form() {
			
			var jawab = $('#headerkartu').val();
			$.ajax({
				type: 'POST',
				url: 'cetak/tberita.php?pg=header',
				data: 'jawab=' + jawab,
				success: function(response) {
					location.reload();
				}
			});
		}
	</script>
 <?php
$jsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE no_peserta<>''"));
$jlaki = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L' AND no_peserta<>''"));
$jper = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P' AND no_peserta<>''"));

?>	      
		 <div class="col-xl-4">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">Peserta Ujian</h5>
                                    </div>
                                    <div class="card-body">
                                       
                                        <ul class="widget-list-content list-unstyled">
                                            <li class="widget-list-item widget-list-item-green">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">face</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Laki-laki</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= $jlaki ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-blue">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">verified_user</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Perempuan</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= $jper ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                           
                                            <li class="widget-list-item widget-list-item-yellow">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">extension</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Total Peserta</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= number_format($jsis); ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
			 </div>
			 <script>
	  $("#tingkat").change(function() {
        var tingkat = $(this).val();
        console.log(tingkat);
        $.ajax({
            type: "POST", 
            url: "cetak/ambildata.php?pg=ambil_kelas", 
            data: "tingkat=" + tingkat, 
            success: function(response) { 
                $("#kelas").html(response);
            }
        });
    });
	</script>
			 
<?php elseif ($ac == enkripsi('kelas')) : ?>
<?php $kelas=$_POST['kelas'] ?>
                         <div class="row"> 
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">KARTU PESERTA KELAS <?= $kelas ?></h5>
										<div class="pull-right">										
                                   						
                    <button class='btn btn-primary' onclick="frames['frameresult'].print()"><i class='material-icons'>print</i>Print</button>                               
					</div>
						</div>
<div class="card-body">                    
		<div class="table-responsive">
   <table id="datatable1" class="table  table-bordered edis2" style="width:100%;font-size:12px">
        <thead>
            <tr>
            <th width='5%'>NO</th>
			<th>N I S</th>
			 <th>NO PESERTA</th>
			<th>NAMA LENGKAP</th>
			<th style="text-align:center">JK</th>
			<th style="text-align:center">RUANG</th>
            <th style="text-align:center">SESI</th>
			
			
            </tr>
        </thead>
		<tbody>
		 <?php $Q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'");?>
           <?php while ($usr = mysqli_fetch_array($Q)) : ?>
			<?php
                   $no++;
                       ?>
                        <tr>
                         <td style="text-align:center"><?= $no ?></td>
						 <td><?= $usr['nis'] ?></td>
						 <td><?= $usr['no_peserta'] ?></td>
                         <td><?= $usr['nama'] ?></td>
						 <td><?= $usr['jk'] ?></td>
						  <td style="text-align:center"><?= $usr['kelas'] ?></td>
						  <td style="text-align:center"><?= $usr['sesi'] ?></td>
                         								
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</div>
	<iframe id='loadframe' name='frameresult' src='cetak/cetak_kartu.php?kelas=<?= $kelas ?>' style='display:none'></iframe>

		<?php endif; ?>
		
