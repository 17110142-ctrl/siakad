<?php
require('../config/koneksi.php');

// Ambil semua siswa yang belum naik
// Ambil semua siswa, tanpa filter sts
$query_siswa = mysqli_query($koneksi, "
    SELECT * FROM siswa 
    ORDER BY level, kelas, nama
");


$data_kelas = []; // untuk menampung data per kelas
while ($row = mysqli_fetch_assoc($query_siswa)) {
    $key = trim($row['kelas']);
    $data_kelas[$key][] = $row;
}

// Pilihan level dan kelas baru
$pilihan_level = ['VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
$pilihan_kelas = ['A', 'B', 'C', 'D'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title">KENAIKAN KELAS SISWA</h5></div>
            <div class="card-body">

                <?php foreach ($data_kelas as $kelasKey => $siswaList): 
                    list($level, $kelasNama) = explode('-', $kelasKey);
                ?>
                <div class="mb-4">
                    <h6><strong>Kelas <?= $level . ' ' . $kelasNama ?></strong></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" style="font-size: 12px;">
                            <thead class="table-light">
                                <tr>
                                    <th width="30">NO</th>
                                    <th>NAMA</th>
                                    <th>LEVEL BARU</th>
                                    <th>KELAS BARU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($siswaList as $siswa): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $siswa['nama'] ?></td>
                                        <td>
                                            <select class="form-select form-select-sm input-kenaikan" 
                                                    data-id="<?= $siswa['id_siswa'] ?>"
                                                    data-jenis="level">
                                                <option value="">Pilih</option>
                                                <?php foreach ($pilihan_level as $lvl): ?>
                                                    <option value="<?= $lvl ?>"><?= $lvl ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm input-kenaikan" 
                                                    data-id="<?= $siswa['id_siswa'] ?>"
                                                    data-jenis="kelas">
                                                <option value="">Pilih</option>
                                                <?php foreach ($pilihan_kelas as $kls): ?>
                                                    <option value="<?= $kls ?>"><?= $kls ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.input-kenaikan').on('change', function () {
        var el = $(this);
        var id_siswa = el.data('id');
        var nilai = el.val();
        var jenis = el.data('jenis');

        if (nilai === '') return;

        $.ajax({
            type: 'POST',
            url: 'ajax/proses_kenaikan.php',
            data: {
                id_siswa: id_siswa,
                jenis: jenis,
                nilai: nilai
            },
            beforeSend: function () {
                el.prop('disabled', true);
                el.after('<span class="spinner-border spinner-border-sm text-primary ms-2" role="status"></span>');
            },
            success: function (res) {
                el.next('.spinner-border').remove();
                el.prop('disabled', false);

                if (res.trim() !== 'OK') {
                    iziToast.error({
                        title: 'Error',
                        message: 'Gagal update: ' + res,
                        position: 'topRight'
                    });
                }
            },
            error: function () {
                iziToast.error({
                    title: 'Error',
                    message: 'Koneksi ke server gagal',
                    position: 'topRight'
                });
                el.prop('disabled', false);
                el.next('.spinner-border').remove();
            }
        });
    });
});
</script>
					<div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">DATA KETUA KELAS</h5>
										
                                    </div>
                                    <div class="card-body">
									
									<div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>  
													<th width="15%">KELAS</th>													
                                                    <th>NIS</th>
                                                    <th>NAMA KETUA KELAS</th>						
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											$no=0;
											$query = mysqli_query($koneksi, "SELECT * FROM kelas"); 
											  while ($data = mysqli_fetch_array($query)) :
											  $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$data[ketua]'"));
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
													 <td><?= $data['kelas'] ?></td>
                                                    <td><?= $siswa['nis'] ?></td>
                                                     <td><?= $siswa['nama'] ?></td>
													
                                                </tr>
												<?php endwhile; ?>
												<tbody>
                                                </table>
												 </div>
											</div>
										</div>
									</div>
						<?php if ($ac == '') : ?>
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">UPDATE KETUA KELAS</h5>
										
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
									<form id='formketua' >	
									 <label>Kelas</label>
									  <div class="input-group mb-1">
                                      <select name="kelas" id="kelas" class="form-select" style="width:100%" >
									  <option value="">Pilih Kelas</option>
									   <?php $q = mysqli_query($koneksi, "select * from kelas");
                                     while ($data = mysqli_fetch_array($q)) { ?>
                                    <option value="<?= $data['id'] ?>"><?= $data['kelas'] ?></option>
										<?php } ?>
									  </select>
                                        </div>
										 
										<label>Nama Ketua Kelas</label>
									  <div class="input-group mb-1">
                                      <select name="siswa" id="siswa" class="form-select" style="width:100%" required >
									   
									  </select>
                                        </div>
										<div class="widget-payment-request-actions m-t-lg d-flex">

                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					
					<script>	
							$("#kelas").change(function() {
							var kelas = $(this).val();
							console.log(kelas);
							$.ajax({
							type: "POST",
							url: "siswa/tketua.php?pg=kelas", 
							data: "kelas=" + kelas, 
							success: function(response) { 
							$("#siswa").html(response);
									}
								});
							});
							</script>
							<script>
    $('#formketua').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'siswa/tketua.php?pg=tambah',
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
                        
          
<?php endif ?>
					