 <?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jfile = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM file_pendukung"));
?>

 <div class='row'>
        <div class='col-md-8'>
            <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">FILE SOAL</h5>
            
                  </div>
                  <div class="card-body">
				  <div class='row'>
        <?php
        $ektensi = ['jpg', 'png', 'JPG', 'PNG', 'JPEG', 'jpeg'];
        $folder = "../files/"; 
        if (!($buka_folder = opendir($folder))) die("eRorr... Tidak bisa membuka Folder");
        $file_array = array();
        while ($baca_folder = readdir($buka_folder)) :
            $file_array[] = $baca_folder;
        endwhile;
        $jumlah_array = count($file_array);
        for ($i = 2; $i < $jumlah_array; $i++) :
            $nama_file = $file_array;
            $nomor = $i - 1;
            $ext = explode('.', $nama_file[$i]);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) { ?>
               
				<div class="avatar avatar-xxl status status-online">
    <img src="<?= $folder.$nama_file[$i] ?>" alt="">&nbsp;&nbsp;
</div>
                            
          <?php  } ?>
       <?php endfor;
        closedir($buka_folder);
        ?>
    </div>
	  </div>
          </div>
             </div>
	<div class="col-xl-4">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">File Soal Gambar</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/user.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']); ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                                </div>
                                            </div>
                                           
                                            <div class="widget-payment-request-info m-t-md">
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
                                                        Description
                                                    </span>
                                                    <span class="text-muted d-block">Digunakan Untuk Sinkron Data Lokal</span>
                                                </div>
                                                <div class="widget-payment-request-info-item">
                                                    <span class="widget-payment-request-info-title d-block">
                                                        Due Date
                                                    </span>
                                                    <span class="text-muted d-block"><?= date('d M Y') ?></span>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2" id="data">
                                                <button  class="hapus btn btn-danger"> Hapus File</button> 
											</div>	<p>
												<form id="formzip">
												<div class="d-grid gap-2" id="data">
                                               <button name="submit3" type="submit"  class="btn btn-success"> Buat Zip</button>  
                                            </div>
											</form>
											
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>				 
		
 <script>
 
    $('#formzip').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?= $homeurl ?>/zip.php',
            data: $(this).serialize(),
			beforeSend: function() {
                $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 500);
            },
            success: function(data) {
               
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                

            }
        });
        return false;
    });
	
        
</script>
<script>
 $("#data").on('click', '.hapus', function() {
        var id = $(this).data('id');
        swal({
            title: 'Konfirmasi ',
            text: 'Apakah kamu yakin akan menghapus soal ??',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'asesmen/sandik_file/hapus_file.php',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
                 iziToast.info({
			title: 'Sukses!',
			message: 'File berhasil dihapus',
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

