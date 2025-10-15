<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<?php if ($ac == '') : ?>
<div class='row'>

        <div class='col-md-8'  >
          <div class="card">
              <div class="card-header">
				 <h5 class="card-title">JENIS ASESMEN</h5>
										
									</div>
     <div class="card-body">                    
   <div class="table-responsive">
   <table id="datatable1" class="table  table-bordered table-hover edis2">
        <thead>
            <tr>
            <th width='5%'>NO</th>
			<th width='12%'>KODE</th>
			 <th>NAMA UJIAN</th>
			<th width='10%'>STATUS</th>			
			<th>ACT</th>
            </tr>
        </thead>
		<tbody>
		 <?php $Q = mysqli_query($koneksi, "SELECT * FROM jenis ORDER BY status");?>
           <?php while ($jenis = mysqli_fetch_array($Q)) : ?>
			<?php
                   $no++;
                       ?>
                        <tr>
                         <td style="text-align:center"><?= $no ?></td>
						 <td style="text-align:center"><?= $jenis['id_jenis'] ?></td>
						 <td><?= $jenis['nama'] ?></td>
                         <td style="text-align:center"><?= $jenis['status'] ?></td>
						
                         <td style="text-align:center">
						 <a href="?pg=<?= enkripsi('jenis') ?>&ac=edit&id=<?= $jenis['id_jenis'] ?>"> <button class='btn btn-sm btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class='material-icons'>edit</i></button></a>
						 <button data-id="<?= $jenis['id_jenis'] ?>" class="hapus btn-sm btn btn-danger"><i class="material-icons">delete</i></button>
						 </td>								
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
       </div>                     
			
        <div class='col-md-4'>
           <div class="card">
              <div class="card-header">
				 <h5 class="card-title">JENIS ASESMEN</h5>
										
									</div>
     <div class="card-body">                  
		       <form id='formjenis' class="form-horizontal" enctype='multipart/form-data'>
								
								    <div class="form-group">
                      <label class="control-label">Kode</label>
                        <input type='text' name='kode' class='form-control' required />
                      </div>
                   				   
							<div class="form-group">
                      <label class="control-label">Nama Ujian</label>
                        <input type='text' name='nama' class='form-control' required />
                      </div>
                       
						<div class="form-group">
                      <label class="control-label">Status</label>
                     
                       <select name='status' class='form-select' required='true'>
                           <option value='aktif'>AKTIF</option>
						   <option value='tidak'>TIDAK</option>
						   </select>
                      </div>
					  <br>
                    <div class="d-grid gap-2">
                     <button type='submit' name='submit' class='btn btn-primary' id="blockui-3">Simpan</button>
                         </div>
                    </form> 
             					
				</div>
			</div>
		</div>
	</div>
	
<?php elseif ($ac == 'edit') : ?>
	<?php $id=$_GET['id']; ?>
<?php $jns=fetch($koneksi,'jenis',['id_jenis'=>$id]); ?>	
<div class='row'>

        <div class='col-md-8'  >
          <div class="card">
              <div class="card-header">
				 <h5 class="card-title">JENIS ASESMEN</h5>
										
		</div>
     <div class="card-body">                                    
         <div class="table-responsive">
   <table id="datatable1" class="table  table-bordered" style="width:100%;font-size:12px">
        <thead>
            <tr>
            <th width='5%'>NO</th>
			<th width='12%'>KODE</th>
			 <th>NAMA UJIAN</th>
			<th width='10%'>STATUS</th>			
			
            </tr>
        </thead>
		<tbody>
		 <?php $Q = mysqli_query($koneksi, "SELECT * FROM jenis ORDER BY status");?>
           <?php while ($jenis = mysqli_fetch_array($Q)) : ?>
			<?php
                   $no++;
                       ?>
                        <tr>
                         <td style="text-align:center"><?= $no ?></td>
						 <td style="text-align:center"><?= $jenis['id_jenis'] ?></td>
						 <td><?= $jenis['nama'] ?></td>
                         <td style="text-align:center"><?= $jenis['status'] ?></td>
												
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
       </div>                     
			
        <div class='col-md-4'>
           <div class="card">
              <div class="card-header">
				 <h5 class="card-title">EDIT JENIS ASESMEN</h5></div>
     <div class="card-body">               
		       <form id='formedit' class="form-horizontal" enctype='multipart/form-data'>
								<input type="hidden" name="id" value="<?= $id ?>" >
								    <div class="form-group">
                      <label class="control-label">Kode</label>
                    
                        <input type='text' name='kode' class='form-control' value="<?= $jns['id_jenis'] ?>" required />
                      </div>
                  				   
							<div class="form-group">
                      <label class="control-label">Nama Ujian</label>
                     
                        <input type='text' name='nama' class='form-control' value="<?= $jns['nama'] ?>" required />
                      </div>
                    
						<div class="form-group">
                      <label class="control-label">Status</label>
                    
                       <select name='status' class='form-select' required='true'>
                           <option value="<?= $jns['status'] ?>"><?= strtoupper($jns['status']) ?></option>
						    <option value='aktif'>AKTIF</option>
						   <option value='tidak'>TIDAK</option>
						   </select>
                      </div>
                    
                  <br>
                    <div class="d-grid gap-2">
                     <button type='submit' name='submit' class='btn btn-primary' id="blockui-3">Simpan</button>
                         </div>
                    </form> 
              					
				</div>
			</div>
		</div>
	</div>
		
				
		<?php endif; ?>
		<script>
    $('#formjenis').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'master/tjenis.php?pg=tambah',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 500);
            },
            success: function(response) {

						setTimeout(function() {
                    window.location.reload();
                    }, 1000);
             
            }
        });
    });
</script>
		 <script>
    $('#formedit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'master/tjenis.php?pg=edit',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('#progressbox').html('<div><label class="sandik" style="color:blue">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="margin-left:100px;"></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 500);
            },
            success: function(response) {

						setTimeout(function() {
                      window.location.replace('?pg=<?= enkripsi(jenis) ?>');
                    }, 1000);
             
            }
        });
    });
</script>
<script>
$('#datatable1').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        swal({
            title: 'Are you sure?',
            text: 'Akan menghapus data ini!',
			type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'master/tjenis.php?pg=hapus',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
                        iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data Mapel berhasil dihapus',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'	
					  });
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    }
                });
            }
            return false;
        })

    });
	</script>