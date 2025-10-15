<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
if($setting['pelanggaran']==1){
	$langgar='Menggunakan';
}else{
	$langgar = 'Tidak Menggunakan';
}
?>
<div class='row'>
<div class='col-md-3'> </div>	
       <div class='col-md-6'>
          <div class="card">
			<div class="card-header">
				<h5 class="card-title">Pengaturan Ujian</h5>				
						</div>
						
				            <div class="card-body">            
						 	<form id="formpengaturan" class="row g-1" enctype='multipart/form-data'>
                             <div class="col-md-12">
								<label class="form-label bold"> Pelanggaran</label>
								<select class="form-select" name="langgar"  required style="width: 100%">
								 <option value="<?= $setting['pelanggaran'] ?>"><?= $langgar ?></option>
								   <option value='0'>Tdak Menggunakan</option>
								  </select>
							     </div>	
		                  <div class="col-md-12">
								<label class="form-label bold"> Acak Soal</label>
								<select class="form-select" name="acak"  required style="width: 100%">								
								   <option value='1'>Ya Acak</option>								  
								  </select>
							     </div>	
								 <div class="col-md-12">
								<label class="form-label bold"> K K M</label>
								<input type="number" name="kkm" value="<?= $setting['kkm'] ?>" class="form-control" required="true" >
						                <div class="col-md-12">
										<button type="submit" class="btn btn-primary kanan">Simpan</button>
										 </div>
									           </form>
								            	</div> 
											</div>
										</div>
									</div>
									 
<script>
   $('#formpengaturan').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
      
        $.ajax({
            type: 'POST',
            url: 'asesmen/tsetting.php',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
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
