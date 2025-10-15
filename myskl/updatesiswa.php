<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<div class='row'>
    <div class='col-md-12'>
        <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'> UPDATE DATA SISWA</h5>
					</div>   
                 		
            <div class='card-body'>

                                <form id='formsiswa' enctype='multipart/form-data'>
                                    <div class='col-md-8'>
                                        <label>Pilih File</label>
                                        <input type='file' name='file' class='form-control' required='true' />
                                    </div>
                                    <div class='col-md-8'>
                                        <label>&nbsp;</label><br>
                                        <button type='submit' name='submit' class='btn btn-primary kanan'><i class='material-icons'>upload</i> Import</button>
                                    </div>
                                </form>
                           
                       
						<br>
						
						 <a href="ekspor.php?level=<?= $skl['tingkat'] ?>"> <button class="btn btn-link"><i class='material-icons'>download</i>Format</button></a>
						
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
<script>
    $('#formsiswa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'import_siswa.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
               $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
					$('.progress-bar').animate({
					width: "30%"
					}, 500);
            },
            success: function(response) {
                setTimeout(function() {
                    $('.progress-bar').css({
                        width: "100%"
                    });
                    
						setTimeout(function() {
                    window.location.replace('?pg=<?= enkripsi(siswa) ?>');
                    }, 2000);
                }, 2000);
            }
        });
    });
</script>
