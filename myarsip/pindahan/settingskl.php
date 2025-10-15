<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<?php
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<?php if ($ac == '') { ?>
<div class='row'>
        <div class='col-md-12'>
            <div class="card">
             <div class="card-header">									
                  <h5 class="card-title"><i class="fas fa-tools"></i> Pengaturan SKL</h5></div>    
			  
						<div class="card-body">
                    <form id='menu'   class="form-horizontal" >
									
									 <div class="row">
						 <div class="col-md-6">
                                <label class="bold">Pengumuman dibuka</label>
                                <input type="text" class="form-control datepicker" name="dibuka" value="<?= $skl['dibuka'] ?>" autocomplete="off" required="true">
                            </div>
                       
                        <div class="col-md-6">
                                <label class="bold">Pengumuman ditutup</label>
                                <input type="text" class="form-control datepicker" name="ditutup" value="<?= $skl['ditutup'] ?>" autocomplete="off" required="true">
                            </div>
							<p>
									<div class="col-md-12">
                                <label class="bold">Pilih Siswa Tingkat</label>
                                <select name='tingkat' class='form-select'  required='true'>
                             <option value="<?= $skl['tingkat'] ?>"><?= $skl['tingkat'] ?></option>
                               <?php
                                $lev = mysqli_query($koneksi, "SELECT * FROM siswa group by level");
                                        while ($level = mysqli_fetch_array($lev)) :
                                            echo "<option value='" . $level['level'] . "' $s>$level[level]</option>";
                                        endwhile;
                                 ?>
                                </select>
                            </div>
                       <p>
					       
                        <div class="col-md-4">
                                <label class="bold">Nama Surat</label>
                                <input type="text" class="form-control" name="nama" value="<?= $skl['nama_surat'] ?>" aria-describedby="helpId">
                            </div>
                       
                        <div class="col-md-4">
                                <label class="bold">No Surat</label>
                                <input type="text" class="form-control" name="no_surat" value="<?= $skl['no_surat'] ?>" aria-describedby="helpId">
                            </div>
                      
                        <div class="col-md-4">
                                <label class="bold">Tanggal Surat</label>
                                <input type="text" class="form-control" name="tgl_surat" value="<?= $skl['tgl_surat'] ?>" aria-describedby="helpId">
                            </div>
                        <p>
                    <div class="col-md-4">
                        <label class="bold">File Header</label>
                        <input type="file" class="form-control-file" name="header" id="header" aria-describedby="fileHelpId">
                    </div>
                     <div class="col-md-12">
                       <center> <img src="<?= $homeurl ?>/<?= $skl['header'] ?>"></center>
                    </div>
					<p>
                    <div class="form-group">
                        <label class="bold">Dasar Surat</label>
                        <textarea name="pembuka"  class='editor1'><?= $skl['pembuka'] ?></textarea>
                    </div><p>
                    <div class="form-group">
                        <label class="bold">Isi Surat</label>
                        <textarea name="isi"  class='editor1'><?= $skl['isi_surat'] ?></textarea>
                    </div><p>
                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-check">
                                    <label class="bold">Menggunakan Nilai</label><br>
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="nilai" value="checkedValue" <?php if ($skl['nilai'] == 1) {
                                                                                                                                echo "checked";
                                                                                                                            } ?>>
                                        Pakai / Tidak
                                    </label>
                                </div>
                            </div>
                        
                        <div class="col-md-6">
                                <div class="form-check">
                                    <label class="bold">Kelompok Mapel</label><br>
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="kelompok" value="checkedValue" <?php if ($skl['kelompok'] == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                                        Pakai / Tidak
                                    </label>
                                </div>
                            </div>
                        </div>
                   <p>
                    <div class="form-group-sm">
                        <label class="bold">Pentutup Surat</label>
                        <textarea name="penutup" class='editor1'><?= $skl['penutup'] ?></textarea>
                    </div><p>
                    <div class="row">
                                <div class="col-md-6">
                                        <label class="bold">Stempel</label><br>
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="sstempel" value="checkedValue" <?php if ($skl['sstempel'] == 1) {
                                                                                                                                        echo "checked";
                                                                                                                                    } ?>>
                                            Pakai / Tidak
                                        </label>
                                    </div>
                                
                                <div class="col-md-6">
                                        <label class="bold">TTD Kep Sek</label><br>
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="sttd" value="checkedValue" <?php if ($skl['sttd'] == 1) {
                                                                                                                                    echo "checked";
                                                                                                                                } ?>>
                                            Pakai / Tidak
                                        </label>
                                    </div>
                                </div>
                            </div>

                          <p>
					       <div class="kanan">
                        <button type="submit" class="btn btn-success" onclick="tinyMCE.triggerSave(true,true);" > Simpan</button>
                    </div>
					
					  </form>
                        </div>
					 </div>
                        </div>
						  <script>
				  $('#menu').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
      
        $.ajax({
            type: 'POST',
            url: 'crud_skl.php?pg=ubah',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
            $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }
        });
        return false;
    });
</script>
						
                       
        </div>
    </div>
</div>

<script>
	tinymce.init({
		selector: '.editor1',
		
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste formula'
		],

		toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | formula code | imagetools link image paste ',
		fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
		paste_data_images: true,

		images_upload_handler: function(blobInfo, success, failure) {
			success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
		},
		image_class_list: [{
			title: 'Responsive',
			value: 'img-responsive'
		}],
	});
</script>

<?php } ?>