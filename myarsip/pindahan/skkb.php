<?php
defined('APK') or exit('No Access');
$skkb=fetch($koneksi,'skkb',['id'=>1]);
?>
    <div class='row'>
        <div class='col-md-12'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'><i class="fas fa-envelope"></i> SURAT KETERANGAN KELAKUAN BAIK</h5>
					</div>    
			 
                <div class='card-body'>
                   <form id="formskkb">
				   <div class='pull-right'>
                        <button type='submit' name='submit' class='btn btn-primary' onclick="tinyMCE.triggerSave(true,true);"> Simpan</button>
                    </div>
                   <p>
				    
                    
                     <div class="col-md-12">
                       <center> <img src="<?= $homeurl ?>/<?= $skkb['file'] ?>"></center>
                    </div>
					<p>
					<div class="col-md-12">
                                <label class="bold">No Surat</label>
                                <input type="text" class="form-control" name="nosurat" value="<?= $skkb['nosurat'] ?>" >
                            </div>
                      
                        <p>
                            <div class="col-md-12">
                                    <label class="bold">Header</label>
										<textarea id='editor2' name='header' class='form-control' rows='1' cols='80' style='width:100%;'><?= $skkb['header'] ?></textarea>
							
                            </div><p>
							<div class="col-md-12">
                               
                                    <label class="bold">Isi</label>
                                    
										<textarea id='editor2' name='isi' class='editor1' rows='5' cols='80' style='width:100%;'><?= $skkb['isi'] ?></textarea>
						
                            </div><p>
                            
								 <div class="col-md-12">
								 <label class="bold">Foter</label>
                                
										<textarea id='editor1' name='foter' class='editor1' rows='5' cols='80' style='width:100%;'><?= $skkb['foter'] ?></textarea>
									
                                    </div>
								 
                    </div>                   
                </form>
					</div>
						</div>
							</div>
								</div>
								   </div>
								     </div>
		<script>
    $('#formskkb').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'tskkb.php',
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