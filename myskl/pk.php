<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<?php if ($ac == '') { ?>
<div class='row'>
        <div class='col-md-5'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'><i class="fas fa-tools"></i> Program Keahlian</h5>
					</div>    
			
						<div class="card-body">
                    <form id='menu' >
									<div class="box-body box-pane">
					         <div class="form-group">
							<label class="bold">Kode Program Keahlian</label>
						   <select name='idpk'  class='form-select' required='true'>
                                      <option value="<?= $_GET['idpk'] ?>"><?= $_GET['idpk'] ?></option>
                                  
                                  </select>
							</div>
		                   
					      <div class="form-group">
							<label class="bold">Nama Program Keahlian</label>
							<input type="text" name="nama" class="form-control" required="true" >
							</div>
							<div class="form-group">
							<label class="bold">Nama Program Study</label>
							<input type="text" name="study" class="form-control" required="true" >
							</div>
							<p>
							<div class="right">
					   <button type='submit' name='submit' class='btn btn-sm  btn-success'><i class='fa fa-check-circle' ></i>Simpan</button>                        
					    </div>
					  </form>
                        </div>
					 </div>
                   </div>
			    </div>
				<div class='col-md-6'>
					<div class="card">
             <div class="card-header">
			  <h5 class='card-title'>Program Keahlian</h5>
			 </div>	
                    <div class="card-body">
						<div class='table-responsive'>
                    <table id='datatable' style="font-size: 12px" class='table'>
                        <thead>
                            <tr>
                                <th width='5px'>#</th>
                               
                                <th>Kode PK</th>
                                <th>Nama Proram Keahlian</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php $lev = mysqli_query($koneksi, "SELECT * FROM kelas GROUP BY idpk"); ?>
                            <?php while ($k = mysqli_fetch_array($lev)) : ?>
                                <?php $no++; ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $k['idpk'] ?></td>
                                    <td><?= $k['nama_pk'] ?></td>
                                   
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
             </div>
			  </div>
             </div>
						  <script>
				  $('#menu').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
      
        $.ajax({
            type: 'POST',
            url: 'sandik_pk/crud_pk.php?pg=ubahpk',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
               iziToast.info({
					title: 'Sukses!',
					message: 'Data berhasil disimpan',
					titleColor: '#FFFF00',
					messageColor: '#fff',
					backgroundColor: 'rgba(0, 0, 0, 0.5)',
					progressBarColor: '#FFFF00',
					position: 'topRight'			  
                });
                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            }
        });
        return false;
    });
</script>
<?php } elseif ($ac == 'produktif') { ?>

<div class='row'>
        <div class='col-md-5'>
           <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'>Standar Kompetensi Utama ( <?= $_GET['idpk'] ?> )</h5>
					</div>    
			         <div class="card-body">
                    <form id='formprod' >
					         <div class="form-group">
							<label class="bold">Kode Program Keahlian</label>
						   <select name='idpk'  class='form-select' required='true'>
                                      <option value="<?= $_GET['idpk'] ?>"><?= $_GET['idpk'] ?></option>
                                  
                                  </select>
							</div>
		                   <div class="form-group">
							<label class="bold">Kode Produktif</label>
							<input type="text" name="kode" class="form-control"  required="true" >
							</div>
					      <div class="form-group">
							<label class="bold">Standar Kompetensi Utama</label>
							<textarea name="sku" class="form-control" rows="3" required="true" ></textarea>
							</div>
							<div class="form-group">
							<label class="bold">Jumlah Jam Dalam 3 Tahun</label>
							<input type="number" name="jjm" class="form-control"  required="true" >
							</div>
							<p>
							<div class="right">
						   <button type='submit' name='submit' class='btn btn-sm  btn-success'><i class='fa fa-check-circle' ></i>Simpan</button>
                         
					    </div>
					  </form>
                        </div>
					 </div>
                   </div>
			   
				<div class='col-md-7'>
						 <div class="card">
             <div class="card-header"></div>
						<div class="card-body">
						<div class='table-responsive'>
                    <table id='datatable1' style="font-size: 12px" class='table'>
                        <thead>
                            <tr>
                                <th width='5px'>#</th>
								<th>Kode</th>
                                <th>Standar Kompetensi Utama</th>
                                <th>JJm</th>
								 <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $lev = mysqli_query($koneksi, "SELECT * FROM m_produktif WHERE pk='$_GET[idpk]'"); ?>
                            <?php while ($k = mysqli_fetch_array($lev)) : ?>
                                <?php $no++; ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $k['kode'] ?></td>
									<td><?= $k['sku'] ?></td>
                                    <td><?= $k['jjm'] ?></td>
									<td>
									 <button data-id="<?= $k['idt'] ?>" class="hapus btn-sm btn btn-outline-danger"><i class="fas fa-times-circle"></i></button>
									 </td>
                                </tr>
                            <?php endwhile ?>
                        </tbody>
                    </table>
                </div>
             </div>
			  </div>
             </div>
						  <script>
		$('#formprod').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'sandik_pk/crud_pk.php?pg=tambahsku',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
				console.log(data);
			if (data == 'OK') {
               iziToast.info({
					title: 'Sukses!',
					message: 'Data berhasil disimpan',
					titleColor: '#FFFF00',
					messageColor: '#fff',
					backgroundColor: 'rgba(0, 0, 0, 0.5)',
					progressBarColor: '#FFFF00',
					position: 'topRight'			  
                });
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
                 } else {
									 iziToast.info(
									{
										title: 'Gagal!',
										message: 'KODE sudah ada',
										titleColor: '#FFFF00',
										messageColor: '#fff',
										backgroundColor: 'rgba(0, 0, 0, 0.5)',
										 progressBarColor: '#FFFF00',
										  position: 'topRight'					  
									});
									setTimeout(function() {
									window.location.reload();
								}, 2000);
								}

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
            title: 'Are you sure?',
            text: 'Akan menghapus data ini!',
			type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'iya, hapus'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'sandik_pk/crud_pk.php?pg=hapus',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
                   iziToast.info(
            {
                title: 'Sukses!',
                message: 'Jadwal berhasil dihapus',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'	
                    });
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


<?php } ?>