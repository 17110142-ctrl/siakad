 <?php
	$idmapel=$_GET['id'];
	$nom = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nomor) AS nomer FROM soal WHERE id_bank='$idmapel' "));
	$mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM banksoal WHERE id_bank='$idmapel' "));
			?>
<?php if($mapel['model']=='1'): ?>
 <div class="row">
     <div class="col-md-6">
	 
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT EXCEL MODEL 2 (AKM)</h5>
			</div>
		 <div class="card-body">
			  <form id="form-import2" action='' method="POST" enctype="multipart/form-data">
                <div class='form-group'>
                    <label>File xlsx</label>
                    <input type="file" class="form-control" name="file"  placeholder="" aria-describedby="helpfile"  required>
                     <small id="helpfile" class="form-text text-muted">File harus .xlsx</small>
                       </div>
							<input type="hidden" name="idmapel" value="<?= $idmapel ?>" >
                           
                            <input type="hidden" name="nomer" value="<?= $nom['nomer'] ?>" >							
                       
                               <div class="form-group kanan">  
							   <a href="?pg=banksoal&ac=lihat&id=<?= $idmapel ?>" class="btn btn-dark"><i class="material-icons">arrow_back</i></a>		
                                  <a href="bank/FORMAT_SOAL_2.xlsx" class="btn  btn-success" data-bs-placement="top" data-bs-toggle="tooltip" title="Download Format soal"><i class='material-icons' >download</i> Format</a>
								<button type="submit"  class="btn btn-primary" ><i class='material-icons'>upload</i> Upload</button>
                                   </div>
								   </form>
								
                        </div>
                    </div>
                </div>
           <div class="col-md-6">
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT EXCEL MODEL 1 (AKM)</h5>
			</div>
		 <div class="card-body">
			  <form id="form-import" action='' method="POST" enctype="multipart/form-data">
                <div class='form-group'>
                    <label>File xlsx</label>
                    <input type="file" class="form-control" name="file"  placeholder="" aria-describedby="helpfile" style="width:60%" required>
                     <small id="helpfile" class="form-text text-muted">File harus .xls</small>
                       </div>
							<input type="hidden" name="idmapel" value="<?= $idmapel ?>" >
                           
                            <input type="hidden" name="nomer" value="<?= $nom['nomer'] ?>" >							
                       
                               <div class="form-group">  
							   <a href="?pg=banksoal&ac=lihat&id=<?= $idmapel ?>" class="btn  btn-dark"><i class="material-icons">arrow_back</i></a>		
                                  <a href="bank/FORMAT_SOAL_1.xls" class="btn btn-success" data-bs-placement="top" data-bs-toggle="tooltip" title="Download Format soal"><i class='material-icons'>download</i> Format</a>
								<button type="submit"  class="btn btn-primary" ><i class='material-icons'>upload</i> Upload</button>
                                   </div>
								   </form>
                        </div>
                    </div>
                </div>
             
             </div>
       <script>
    
    $('#form-import').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'bank/timpor.php',
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
            success: function(data) {
                setTimeout(function() {
                    window.location.replace('?pg=<?= enkripsi(banksoal) ?>&ac=lihat&id=<?= $idmapel ?>');
                }, 1500);


            }
        });
    });
   
</script>

	<script>
    
    $('#form-import2').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'bank/timpor2.php',
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
            success: function(data) {

                setTimeout(function() {
                    window.location.replace('?pg=<?= enkripsi(banksoal) ?>&ac=lihat&id=<?= $idmapel ?>');
                }, 2000);


            }
        });
    });
   
</script>
<?php else : ?>
<div class="row">
            
                 <div class="col-md-12">
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT WORD (NON AKM) </h5>
			 
			</div>
		 <div class="card-body">
                    <form id="formsoalword" action='word/word_import/index_tabel.php' method='post' enctype='multipart/form-data'>
                         <div class='kanan'>
                                <button type='submit' name='submit' class='btn btn-primary'><i class='material-icons'>upload</i> Import</button>
                            </div>
                              <br><br>
                                <input type='hidden' name='id_bank' value="<?= $mapel['id_bank'] ?>" />
                    <div class='form-group'>
                        <label>Mata Pelajaran</label>
                        <input type='text' class='form-control' value="<?= $mapel['nama'] ?>" disabled />
                    </div>
                    <div class='form-group'>
                        <label>Pilih File Word (.docx)</label>
                        <input type='file' name='word_file' class='form-control' required />
                    </div>
                    <p>Gunakan format tabel seperti berikut:</p>
                    <ul>
                        <li>Jenis: 1 = Pilihan Ganda, 2 = Uraian</li>
                        <li>Kolom: No | Jenis | Soal | pilA - pilE | Kunci | Skor</li>
                    </ul>
                    <a href='bank/FORMAT_WORD_TABEL.docx' class="btn btn-sm btn-success"><i class='material-icons'>download</i> Download Format</a>
                    <button type='submit' name='submit' class='btn btn-primary mt-2'><i class='material-icons'>upload</i> Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

     
<script>
   $('#formsoalword').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'bank/timpor_word.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
           cache: false,
            beforeSend: function() {
                $('.loader').css('display', 'block');
            },
            success: function(response) {
               iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berhasil disimpan',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'
                    });
                    setTimeout(function() {
                        window.location.replace('?pg=banksoal');
                    }, 2000);
            }
        });
    });
   
   
    $('#formfilesoal').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'sandik_ujian/crud_soal.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
           cache: false,
            beforeSend: function() {
                $('.loader').css('display', 'block');
            },
            success: function(response) {
               iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berhasil disimpan',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'
                    });
                    setTimeout(function() {
                        window.location.replace('?pg=banksoal');
                    }, 2000);
            }
        });
    });


   
    $('#formsoalexcel').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'sandik_ujian/imporsoalcbt.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('.loader').css('display', 'block');
            },
            success: function(response) {
               iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berhasil disimpan',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'
                    });
                    setTimeout(function() {
                        window.location.replace('?pg=banksoal');
                    }, 2000);
            }
        });
    });

    
</script>	
<?php endif; ?>
