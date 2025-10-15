<?php
$id_bank = $_GET['id'];
$mapelQ = mysqli_query($koneksi, "SELECT * FROM banksoal where id_bank='$id_bank'");
$mapel = mysqli_fetch_array($mapelQ);
$cekmapel = mysqli_num_rows($mapelQ);
?>

			<div class="row">
             <div class="col-md-6">
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT EXCEL </h5>
			</div>
		 <div class="card-body">
            <form id="formsoalexcel" method='post' enctype='multipart/form-data'>
                      <div class='pull-right '>
                        <button type='submit' name='submit' class='btn btn-primary'><i class='fa fa-upload'></i> Import</button>
                                   
                                </div>
                                <div class='form-group'>
                                    <label>Mata Pelajaran</label>
                                    <input type='hidden' name='id_bank' class='form-control' value="<?= $mapel['id_bank'] ?>" />
                                    <input type='text' name='mapel' class='form-control' value="<?= $mapel['nama'] ?>" disabled />
                                </div>
                                <div class='form-group'>
                                    <label>Pilih File</label>
                                    <input type='file' name='file' class='form-control' required='true' />
                                </div>
                                <p><br>
                                    EXCEL 2007 KE ATAS (XLSX) <br />
                                </p>
                            
                             <div class='form-group'>
                                <a href='sandik_ujian/formatsoalCBT.xlsx'><i class='fa fa-download'></i> Download Format</a>
                            </div>
							</div>
                        </div>
                    </form>
                </div>
                 <div class="col-md-6">
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT EXCEL </h5>
			</div>
		 <div class="card-body">
                    <form id="formsoalword" action='page/word_import/import/index.php/word_import' method='post' enctype='multipart/form-data'>
                         <div class='pull-right '>
                                <button type='submit' name='submit' class='btn btn-primary'><i class='fa fa-upload'></i> Import</button>
                            </div>
                          
                                <div class='form-group'>
                                    <label>Mata Pelajaran</label>
                                    <input type='hidden' name='id_bank' class='form-control' value="<?= $mapel['id_bank'] ?>" />
                                    <input type='text' name='mapel' class='form-control' value="<?= $mapel['nama'] ?>" disabled />
                                </div>
                                <tr>
                                    <td>
                                        <input type='hidden' name='id_bank_soal' value=<?= $_REQUEST['id'] ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <input type='hidden' name='id_lokal' value='<?= $homeurl ?>'></td>
                                </tr>
                                <tr>
                                    <td> <input type='hidden' name='cid' value='1'></td>
                                </tr>
                                <tr>
                                    <td> <input type='hidden' name='lid' value='2'></td>
                                </tr>
                                <tr>
                                    <td> <input type='hidden' name='question_split' value='/Q:[0-9]+\)/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='description_split' value='/FileQ:/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='question_gambar' value='/Gambar:/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='question_video' value='/Video:/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='question_audio' value='/Audio:/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='option_split' value='/[A-Z]:\)/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='option_file' value='/FileO:/'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' name='correct_split' value='/Kunci:/'></td>
                                </tr>
								
                                <div class='form-group'>
                                    <label>Pilih File</label>
                                    <input type='file' name='word_file' class='form-control' required='true' />
                                </div>
                                <p>
                                   Ms. Word (.docx)  <br />
                                </p>
                           
                            <div class='form-group'>
                                <a href='page/word_import/import/sample/sample.docx'><i class='fa fa-download'></i> Download Format</a>
                            </div>
                        </div>
						 </div>
                    </form>
                </div>
                 <div class="col-md-6">
                <div class="card">
            <div class="card-header">
			 <h5 class="card-title">IMPORT FILE GAMBAR </h5>
			</div>
		 <div class="card-body">
               <form id="formfilesoal" method="post" enctype="multipart/form-data">
                               
                                <div class='col-md-12'>
                                    <div class='form-group'>
                                        <input class='form-control' type="file" name="zip_file" />
                                    </div>
                                </div>
                               <div class='kanan'>
                                <button type="submit"  class="btn btn-primary" ><i class='fa fa-upload'></i> Upload File</button>
                               </div>
                            </form>
                            <br />
                            <p>
                                Upload file pendukung soal bertipe zip<br />
                            </p>
                            <?php
                            if (isset($output)) {
                                echo $output;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

     
<script>
   
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