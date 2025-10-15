<?php
defined('APK') or exit('No accsess');
?>		   
<div class="row">
      <div class="col-md-7">
          <div class="card">
             <div class="card-header">
                  <h5 class="card-title">DATA INFORMASI</h5>
		    </div>
  <div class="card-body">
	<div class="card-box table-responsive">
        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
              <thead>
                 <tr>                                                            
                 
		         <th>UNTUK</th>
				 <th>ISI INFORMASI</th> 
				 <th width="8%"></th>
                 </tr>
                 </thead>
                 <tbody>
					<?php				   
					$query = mysqli_query($koneksi, "SELECT * FROM informasi ORDER BY id DESC"); 
					while ($data = mysqli_fetch_array($query)) :
					
					?>
                  <tr>
                  <td>
				  <?php if($data['untuk']=='1'): ?>
				  Siswa
				  <?php else: ?>
				  Guru
				  <?php endif; ?>
				  </td>
                  <td><?= $data['isi'] ?></td>
				 
				  <td>
					
					<button data-id="<?= $data['id'] ?>"  class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
				  </td>
                  </tr>
				<?php endwhile; ?>
                </tbody>
				</table>
					</div>
				</div>
			</div>
		</div>
	<?php if ($ac == '') : ?>
<div class="col-md-5">
    <div class="card widget widget-payment-request">
         <div class="card-header">
             <h5 class="card-title">TAMBAH INFORMASI</h5>
		</div>
    <div class="card-body">
          <div class="widget-payment-request-container">
               <div class="widget-payment-request-author">
                   <div class="avatar m-r-sm">
                       <img src="../images/guru.png" alt="">
                     </div>
           <div class="widget-payment-request-author-info">
             <span class="widget-payment-request-author-name">Data Admin</span>
             <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                   </div>
                 </div>
	             <div class="widget-payment-request-info m-t-md">
				<form id='formguru' >	
				<label class="bold">Untuk</label>
				<div class="input-group mb-1">
                    <select name="untuk" class="form-select" style="width:100%" required >
					
					<option value="1">Siswa</option>
					<option value="2">Guru</option>
													 
					</select>
                </div>
				<label class="bold">Judul</label>
				<div class="input-group mb-1">
                    <input type='text' name='judul'  class='form-control' required="true" />
                </div>
			    <label class="bold">Isi Informasi</label>
		        <div class="input-group mb-1">
                    <textarea id='editor2' name='isi' class='editor1' rows='10' cols='80' style='width:100%;' required><?= $soal['soal'] ?></textarea>
					
                </div>
				
		<div class="widget-payment-request-actions m-t-md d-flex">
          <button type="submit" onclick="tinyMCE.triggerSave(true,true);" class="btn btn-primary flex-grow-1 m-l-xxs" >Simpan</button>
               </div>
			</form>
				</div>
			</div>
		</div>
	</div>
</div>
		
 <?php endif ?>
	<script>
    $('#formguru').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
            url: 'info/tinfo.php?pg=tambah',
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
		$('#datatable1').on('click', '.hapus', function() {
			var id = $(this).data('id');
			console.log(id);
			swal({
				title: 'Yakin hapus data?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: "Batal"				  
			}).then((result) => {
			if (result.value) {
				$.ajax({
				url: 'info/tinfo.php?pg=hapus',
				method: "POST",
				data: 'id=' + id,
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
				}
				return false;
			})

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
