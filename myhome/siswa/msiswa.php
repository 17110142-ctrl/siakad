<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$kelas_result = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC");
?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap JS (pastikan juga ini ada kalau plugin Anda butuh bootstrap JS) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- Wysihtml5 JS dan CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysihtml5-bower/0.3.3/bootstrap3-wysihtml5.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysihtml5-bower/0.3.3/bootstrap3-wysihtml5.all.min.js"></script>


<div class="row">
    <!-- Form Input Kelas -->
    <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">MASTER KELAS</h5>
										
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
									<form id='formkelas' >	
									 <label>Kelas</label>
									  <div class="input-group mb-1">
                                      <select id="level" name="level" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="VII">VII</option>
                            <option value="VIII">VIII</option>
                            <option value="IX">IX</option>
                        </select>
                                        </div>
										 
										<label>Nama Kelas</label>
									  <div class="input-group mb-1">
                                      <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: VII-A atau IX-B" required>
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

    <!-- Daftar Kelas -->
    <div class="col-md-6 mt-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daftar Kelas</h5>
            </div>
            <div class="card-body">
                <ul id="listKelas" class="list-group">
                    <?php while($row = mysqli_fetch_assoc($kelas_result)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($row['kelas']) ?>
                            <button class="btn btn-sm btn-danger btn-hapus-kelas" data-id="<?= $row['id'] ?>">
    <span class="material-icons-two-tone" style="font-size:18px;">delete</span>
</button>

                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function(){
    // submit tambah kelas
    $('#formkelas').submit(function(e){
      e.preventDefault();
      let data = new FormData(this);
      $.ajax({
        type: 'POST',
        url: 'siswa/tambah_kelas.php',      // ← path diperbaiki
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
          $('#progressbox').html(
            '<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang diproses</label>' +
            '&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>'
          );
          $('.progress-bar').animate({ width: "30%" }, 500);
        },
        success: function(res){
          // optional: cek response
          // console.log(res);
          setTimeout(function(){
            window.location.reload();
          }, 2000);
        },
        error: function(xhr, status, err){
          alert('AJAX Error: ' + err);
        }
      });
    });

  // Hapus kelas
  $(document).on('click', '.btn-hapus-kelas', function(){
    if(!confirm('Yakin ingin menghapus kelas ini?')) return;

    let btn = $(this),
        id  = btn.data('id');

    $.post('siswa/tambah_kelas.php', { action:'delete', id_kelas: id }, function(res){
      if(res.status === 'ok'){
        btn.closest('li').fadeOut(300, function(){ $(this).remove(); });
      } else {
        alert('Error: ' + res.message);
      }
    }, 'json');
  });
});
</script>


<div class="row">
    <div class="col-md-8">  
			 <div class="card">
			<div class="card-header">
				<h5 class="card-title">Import Data Siswa</h5>
				
					<a href="siswa/M_SISWA.xlsx" class="btn btn-link pull-right" data-bs-toggle="tooltip" data-bs-placement="top" title="Download Format"><i class="material-icons">download</i>Format</a>
					
								</div>
				                <div class="card-body">  
								<form id='formsiswa' >								 
								    <div class='col-md-12'>
                                      <label>Pilih File</label>
									  <div class="input-group">
                                       <input type='file' name='file' class='form-control' required='true' />
									   <span class="input-group-btn">
											<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
										</span>
                                    </div>
								</form>
							</div>
							
						</div>
					</div>
				</div>
				<div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">storage</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA </span>
                                                <span class="widget-stats-amount"><?= $jsiswa; ?> PD</span>
                                                <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span><p>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
				
			
	<script>
    $('#formsiswa').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'siswa/import_siswa.php',
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
             