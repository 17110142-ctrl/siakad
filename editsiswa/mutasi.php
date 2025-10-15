<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
?>
<script src="siswa/jqury.js"></script>
<?php

    if (empty($_GET['mutasi'])) {
        $mutasi = "";
     
    } else {
        $mutasi = $_GET['mutasi'];
       
    }
    if (empty($_GET['kelas'])) {
        $kelas = "";
    } else {
        $kelas = $_GET['kelas'];
    }
		if (empty($_GET['naik'])) {
        $naik = "";
    } else {
        $naik = $_GET['naik'];
    }
if($mutasi=='naik'){$mutasimu='Naik Kelas';}
if($mutasi=='keluar'){$mutasimu='Keluar / Tamat';}	
    ?>
<div class="row">
    
				<div class="col-xl-8">
                 <div class="card">
			        <div class="card-header">
				       <h5 class="bold">
					   <?php if($mutasi=='naik'): ?>
					   NAIK KE KELAS <span class="badge badge-primary"><?= $naik ?></span>
					   <?php elseif($mutasi=='keluar'): ?>
					   KELUAR / TAMAT
					   <?php endif; ?>
					   </h5>
				
								</div>
                                    <div class="card-body">
									<form id="formsiswa">
									 <input type="hidden" name="mutasi" value="<?= $mutasi ?>" >
									 <input type="hidden" name="kelas" value="<?= $kelas ?>" >
									 <input type="hidden" name="naik" value="<?= $naik ?>" >
									 <div class="kanan">
									 <button type="submit" class="btn btn-primary">Simpan</button>
									 </div>
                                     
									 <table  class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                  
                                                    <th># &nbsp;<input type="checkbox" id="check-all"></th>
                                                    <th>N I S</th>
                                                     <th>NAMA SISWA</th>
													 <th>JK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php
											$no=0;
											 $query = mysqli_query($koneksi,"SELECT * FROM siswa WHERE kelas='$kelas'");
											 while ($data = mysqli_fetch_array($query)) :
											
											$no++;
											   ?>
                                                <tr> 
                                                  <td><?= $no; ?> &nbsp;<input type="checkbox" name="idsiswa[]" id="check<?= $no; ?>" class="checkbox" value="<?= $data['id_siswa'] ?>"></td>
                                                  <td><?= $data['nis'] ?></td>
												   <td><?= $data['nama'] ?></td>
												    <td><?= $data['jk'] ?></td>
                                                </tr>
												
												<?php endwhile; ?>
												</tbody>
                                                </table>
										</form>
                                    </div>
                                </div>
                            </div>
						<div class="col-md-4">  
						<div class="card widget widget-payment-request">
						<div class="card-header">
							<h5 class="bold">Mutasi Siswa</h5>
								</div>
				                <div class="card-body">  
									<div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
									<div class="widget-payment-request-info m-t-md">						 
								    <div class="col-md-12">
								<label class="form-label bold">Alasan Mutasi</label>
						   <select class="form-select mutasi" id="mutasi" required style="width: 100%">
                            <?php if($mutasi !=''): ?>						   
							<option value="<?= $mutasi ?>"><?= $mutasimu ?></option>
							
							<option value="keluar">Keluar / Tamat</option>
							  <option value="naik">Naik Kelas</option>
							  <?php else : ?>
							  <option value="">Pilih Mutasi</option>
							<option value="keluar">Keluar / Tamat</option>
							  <option value="naik">Naik Kelas</option>
							  <?php endif; ?>
							</select>
						</div><p>
						 
						<div class="col-md-12">
								<label class="form-label bold">Kelas</label>
						   <select class="form-select kelas" id="kelas" required style="width: 100%">
							 <?php $level = mysqli_query($koneksi, "SELECT * FROM kelas"); ?>
                                <option value=''> Pilih Kelas</option>
                                <?php while ($kls = mysqli_fetch_array($level)) : ?>
                                    <option <?php if ($kelas == $kls['kelas']) {
                                                echo "selected";
                                            } else {
                                            } ?> value="<?= $kls['kelas'] ?>"><?= $kls['kelas'] ?></option>
                                <?php endwhile; ?>
							</select>
						</div><p>
						<div class="col-md-12" name="untuk" id="untuk" hidden="true" >
								<label class="form-label bold">Naik ke Kelas</label>
						   <select class="form-select naik" id="naik"  style="width: 100%">
							<?php $klas = mysqli_query($koneksi, "SELECT * FROM kelas"); ?>
                                <option value=''> Pilih Kelas</option>
                                <?php while ($k = mysqli_fetch_array($klas)) : ?>
                                    <option <?php if ($naik == $k['kelas']) {
                                                echo "selected";
                                            } else {
                                            } ?> value="<?= $k['kelas'] ?>"><?= $k['kelas'] ?></option>
                                <?php endwhile; ?>
							</select>
						</div><p>
						<div class="col-md-12">
										<button id="cari" class="btn btn-primary kanan">Cari</button>
										 </div>
								<script type="text/javascript">
                                $('#cari').click(function() {
									 var mutasi = $('.mutasi').val();
                                    var kelas = $('.kelas').val();
                                    var naik = $('.naik').val();
                                    location.replace("?pg=<?= enkripsi('mutasi') ?>&mutasi=" + mutasi + "&kelas=" + kelas + "&naik=" + naik);
                                }); 
                            </script>
						</div>
					</div>
				</div>
						</div>
<script type='text/javascript'>
$(window).load(function(){
$("#mutasi").change(function() {
			console.log($("#mutasi option:selected").val());
			if ($("#mutasi option:selected").val() == 'keluar') {
				$('#untuk').prop('hidden', 'true');
			} else {
				$('#untuk').prop('hidden', false);
			}
		});
});
</script>
			
	<script>
    $('#formsiswa').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'siswa/tmutasi.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			 beforeSend: function() {
            $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
            },
			success: function(data){   		
				
					setTimeout(function()
						{
						window.location.reload();
						}, 2000);
									  
						}
					});
				return false;
			});
		</script>	
             
  <script>
$(function(){ 
 $("#check-all").click(function(){
 if ( (this).checked == true ){
 $('.checkbox').prop('checked', true);
 } else {
 $('.checkbox').prop('checked', false);
}
 });
});
</script>	  