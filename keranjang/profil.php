<div class="row">
<div class="col-md-12">
   <div class="card">
       <div class="card-header">
          <a href="." class="btn btn-secondary btn-sm"> <i class="material-icons">west</i><strong class="card-title"> Back</strong></a>       
		<div class="kanan">
		Profile
		</div>
		</div>
         <div class="card-body">
		 <form id="formprofil">
		 <table id="tableprofil" style="text-align:center; width:100%">
		 
             <tr>
             <td style="text-align:left; vertical-align:center;width:40%">
			  <?php if($siswa['foto'] == ""){ ?>
				<img src="images/user.png" class="responsive">
				<?php }else{ ?>
				<img src="images/foto/<?= $siswa['foto'] ?>" class="responsive">
				<?php } ?>
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <b><?= $siswa['nama'] ?></b>
				<br>Card Payment <b style="color:blue;"><?= $siswa['nokartu'] ?></b>			  
			   </td>
			</tr>
			 <tr>
             <td style="text-align:left; vertical-align:top;width:40%">
			  N I S
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <?= $siswa['nis'] ?>	
               <input type="hidden" name="ids" value="<?= $siswa['id_siswa'] ?>" >			  
			   </td>
			</tr>
			<td style="text-align:left; vertical-align:top;width:40%">
			  Kelas
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <?= $siswa['kelas'] ?>	  
			   </td>
			</tr>
			 <tr>
             <td style="text-align:left; vertical-align:top;width:40%">
			  Jenis Kelamin
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <?php if($siswa['jk']=='L'){ ?>
					Laki-Laki
              <?php }else{ ?>
                    Perempuan
			  <?php } ?>			  
			   </td>
			</tr>
			<tr>
			<td style="text-align:left; vertical-align:center;width:40%">
			  Username
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <input type="text" name="username" class="form-control" value="<?= $siswa['username'] ?>" readonly >	  
			   </td>
			</tr>
			<tr>
			<td style="text-align:left; vertical-align:center;width:40%">
			  Password
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <input type="text" name="password" class="form-control" value="<?= $siswa['password'] ?>" required="true">		  
			   </td>
			</tr>
			<tr>
			<td style="text-align:left; vertical-align:center;width:40%">
			  Ganti Foto
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <input type="file" name="file" class="form-control" >		  
			   </td>
			</tr>
			<tr>
			<td style="text-align:left; vertical-align:center;width:40%">
			  
			  </td>
			  <td style="text-align:left; vertical-align:top;width:60%">
			  <br>
			  <button type="submit" class="btn btn-primary">SIMPAN</button>	  
			   </td>
			</tr>
			</table>
			</form>
		 </div>
             </div>
                 </div>
                   </div>
				  
				   <script>
			 
    $('#formprofil').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
             url: 'keranjang/tprofil.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
			$('#progressbox').html('<div><img src="images/animasi1.gif" ></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},			
			success: function(data){  			
			setTimeout(function()
				{
				window.location.reload();
						}, 1000);
									  
						}
					});
				return false;
			});
		</script>	