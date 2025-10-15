<?php
defined('APK') or exit('No accsess');
?>           
<div class="row">
  <div class="col-md-8">
       <div class="card">
          <div class="card-header">
           <h5 class="card-title">MATA PELAJARAN</h5>
				</div>
    <div class="card-body">
	<br>
		<div class="card-box table-responsive">
           <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px;">
               <thead>
                <tr>
                  <th width="5%">NO</th>                                               
                  <th>KODE</th>
                  <th>MATA PELAJARAN</th>
				  <th>#</th>
					</tr>
				</thead>
                  <tbody>
					<?php
					$no=0;
					$query = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY id DESC"); 
					while ($data = mysqli_fetch_assoc($query)) :
					$no++;
					?>
                    <tr>
                    <td><?= $no; ?></td>
                    <td><?= $data['kode'] ?></td>
                    <td><?= $data['nama_mapel'] ?></td>
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
						url: 'master/hapus.php',
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
<div class="col-md-4">
       <div class="card widget widget-payment-request">
           <div class="card-header">
               <h5 class="card-title">UPLOAD DATA</h5>
			   <div class='pull-right '>
			   <a href="master/M_MAPEL.xlsx" class="btn btn-link"><i class="material-icons">download</i>Format</a> 
					</div>
				</div>
       <div class="card-body">
		
		<form id='formmapel' >	
             <label>Pilih File</label>
				<div class="input-group mb-1">
                   <input type='file' name='file' class='form-control' required='true' />
						<span class="input-group-text">
							<button type="submit" class="btn btn-primary"><i class="material-icons">upload</i></button>
							</span>
                              </div>	
					       </form>
					   </div>
					</div>	
<div class="col-md-12">
       <div class="card widget widget-payment-request">
           <div class="card-header">
               <h5 class="card-title">TAMBAH MATA PELAJARAN</h5>
					</div>
       <div class="card-body">
		<form id='formtambah' >	
             <label class="bold">Kode Mapel</label>
				<div class="input-group mb-1">
                   <input type='text' name='kode' class='form-control' required='true' autocomplete="off" />
                              </div>
		<label class="bold">Nama Mapel</label>
				<div class="input-group mb-1">
                   <input type='text' name='nama' class='form-control' required='true' autocomplete="off" />
                              </div>
		<div class="col-md-12">
			<button type="submit" class="btn btn-primary kanan">Simpan</button>
							</div>					  
					       </form>
					   </div>
					</div>
				</div>
			</div>
		</div>
	</div>
					
	<script>
    $('#formmapel').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
            url: 'master/import_mapel.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
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
    $('#formtambah').submit(function(e){
		e.preventDefault();
		var data = new FormData(this);
		$.ajax(
		{
			type: 'POST',
            url: 'master/tambah_mapel.php',
            data: data,
			cache: false,
			contentType: false,
			processData: false,
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
                               