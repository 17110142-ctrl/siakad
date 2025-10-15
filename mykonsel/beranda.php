<?php 
$memo = memory_get_usage(true) . "\n"; 
$use = memory_get_usage(false) . "\n";
$df = disk_total_space("/");
$df_c = disk_free_space("/") 
?>
<?php
$kt = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_kategori"));
$skt = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_sub"));
$jbk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_pelanggaran"));
$sp1 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_sp WHERE ket='SP1' AND tapel='$setting[tp]'"));
$sp2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_sp WHERE ket='SP2' AND tapel='$setting[tp]'"));
$sp3 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_sp WHERE ket='SP3' AND tapel='$setting[tp]'"));


?>   
<?php if ($user['level'] == 'admin') : ?>

                 <div class='row'>
				<div class="col-md-3">
					 <div class="card widget widget-info-inline">
                                    <div class="card-header">
                                        <h5 class="card-title">Memory Usage<span class="badge badge-info badge-style-light">Premium</span></h5>
								   </div>
                                    <div class="card-body">
                                        <div class="widget-info-container">
                                            <p class="widget-info-text"><b style="color:red"><?= number_format($use) ?> Mb</b></p>
                                           <p class="widget-info-text">NEW SANDIK</p>
                                            <div class="widget-info-image" style="background: url('../dist/img/speed.svg')"></div>
                                        </div>
                                    </div>
                                </div>
                          
					<div class="card widget widget-info-inline">
                                    <div class="card-header">
                                        <h5 class="card-title">Free Memory<span class="badge badge-info badge-style-light">Premium</span></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-info-container">
                                            <p class="widget-info-text"><b style="color:blue"><?= number_format($memo-$use)  ?> Mb</b></p>
                                            <p class="widget-info-text">NEW SANDIK</p>
                                            <div class="widget-info-image" style="background: url('../dist/img/speed.svg')"></div>
                                        </div>
                                    </div>
                                </div>
                              </div>
					 
					   
				<div class="col-md-3">
                           <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats">
                                                <i class="fa fa-envelope fa-2x"></i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title"><b style="color: blue;">SP 1</b></span>
                                                <span class="widget-stats-amount"> <?= $sp1 ?> </span>
                                        
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                 
                                 <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="fa fa-envelope fa-2x"></i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title"><b style="color: blue;">SP 2</b></span>
                                                 <span class="widget-stats-amount"> <?= $sp2 ?> </span>
                                        
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
							 <div class="card widget widget-info-inline">
                                    <div class="card-header">
                                        <h5 class="card-title">Kategori Pelanggaran<span class="badge badge-danger badge-style-light"><?= $kt ?></span></h5>
								   </div>
                            </div>
						 </div>
                  <div class="col-md-3">  
				 <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                               <img src="../dist/img/guru.png" width="45" class="success" alt="">
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-info">Kepala Sekolah</span>
                                                <span class="widget-stats-info"><?= $setting['kepsek']; ?></span>
                                        
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
				
				                   <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                           <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="fa fa-envelope fa-2x"></i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title"><b style="color: blue;">SP 3</b></span>
                                                <span class="widget-stats-amount"> <?= $sp3 ?> </span>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
									 <div class="card widget widget-info-inline">
                                    <div class="card-header">
                                        <h5 class="card-title">Jenis Pelanggaran<span class="badge badge-info badge-style-light"><?= $jbk ?></span></h5>
								   </div>
                                </div>
				                </div>
				                <div class="col-xl-3">
                                <div class="card widget widget-info">
                                    <div class="card-body">
                                        <div class="widget-info-container">
                                            <div class="widget-info-image" style="background: url('../dist/img/logo65.png')"></div>
                                            <h6 class="widget-info-title">NEW SANDIK</h6>
                                            <p class="widget-info-text"><b>SISTEM APLIKASI PENDIDIK</b><br>Multi Jenjang REGULER & PKBM <small> Created By STBC</small></p>
                                           
                                        </div>
                                    </div>
                                </div>
                
            </div>
        </div>
    </div>
</div>
                
 <div class='row'>
 <div class="col-md-8">
       <div class="card">
            <div class="card-header">                         
                <div class="right">
              <a href="?pg=inputbk" class="btn btn-sm btn-dark"><i class="fas fa-plus fa-fw"></i> Tambah</a>		
			   </div>
			   <h5 class='card-title'>Daftar Konseling</h5>
                
            </div>
            <div class='card-body'>
               <div class='table-responsive'>
                        <table style="font-size: 12px" id='example1' class='table  table-hover'>
                            <thead>
                            <tr>                              
                                    <th width='5%'>No</th>					
                                    <th width="10%">Tanggal</th>									                                                                
                                    <th width="20%">Nama Siswa</th>
									 <th>Keterangan</th> 
									 <th width="5%">Poin</th>
									 <th width="20%">Action</th>
									 </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from bk_siswa WHERE tapel='$setting[tp]' ORDER BY tanggal DESC LIMIT 5");
                            $no = 0;
                            while ($bk = mysqli_fetch_array($query)) {
							$siswa=fetch($koneksi,'siswa',['nis'=>$bk['nis']]);
							   $no++;
                              
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
									<td><?= date('d-m-Y',strtotime($bk['tanggal'])) ?>								                                
									<td><b style="color:blue"><?= $siswa['nama'] ?></b></td>
									 <td><?= $bk['ket'] ?></td>
									  <td><?= $bk['poin'] ?></td>
									<td>	
									<a href="?pg=inputbk&ac=edit&id=<?= $bk['id'] ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-edit"></i></a>								   
									 <button data-id="<?= $bk['id'] ?>" class="hapus btn-sm btn btn-outline-danger"><i class="fas fa-trash"></i></button>
									</td>
									</tr>
									<?php } ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
  </div>      
     <script>
	 $('#example1').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        swal({
            title: 'Maaf Dilarang Hapus Data',
            text: 'silahkan gunakan menu Reset',
			type:'warning',
			showConfirmButton: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'iya, hapus'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                   url: 'bk/crud_bk.php?pg=hapus_siswa',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
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
                            window.location.reload();
                        }, 2000);
                    }
                });
            }
            return false;
        })

    });
	</script>	
	
	  <div class='animated flipInX col-md-4' >
            <div class='box box-solid direct-chat direct-chat-warning'>
                <div class='card'>
				 <div class="card-header">	
                    <h5 class='card-title'>Informasi Pelanggaran</h5>
                   
                </div>
                <div class='box-body'>
                    <?php
                            $query = mysqli_query($koneksi, "select * from bk_kategori");                          
                            while ($bk = mysqli_fetch_array($query)) {
							
                            $jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bk_pelanggaran
							JOIN bk_siswa ON bk_siswa.idpel=bk_pelanggaran.id
							WHERE bk_siswa.idkat='$bk[id]'"));  
							 
                              
                            ?>
                        <li class="list-group-item">
                            <a href="?pg=inputbk&ac=detail&id=<?= $bk['id'] ?>" class="btn btn-outline-primary" data-bs-placement="top" data-bs-toggle="tooltip" title="Total Pelanggaan <?= $bk['kategori'] ?>">
                                <i class="fa fa-users-cog"></i> <?= $bk['kategori'] ?>
                            </a>
							
							<div class="right">
							<a href="?pg=inputbk&ac=rincian&id=<?= $bk['id'] ?>" > <label class="btn btn-outline-danger" data-bs-placement="top" data-bs-toggle="tooltip" title="Lihat Detail"><b><?= $jml ?></b></label></a>
							</div>
							 </li>
							<?php } ?>
                   
                </div>
            </div>
        </div>
	
        <?php
	$query = mysqli_query($koneksi, "select * from bk_tindakan");
while ($tdk = mysqli_fetch_array($query)):
$min=$tdk['minpoin'];
$max=$tdk['maxpoin'];
$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis,SUM(poin) AS total FROM bk_siswa "));
$where =[
          'nis' => $siswa['nis'],     
           'ket' => $tdk['tindakan']    
         ];
      $cek = rowcount($koneksi, 'bk_sp', $where);
            if ($cek == 0) {
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP1'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP2'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP3'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
}
endwhile;
?>
	

<?php endif ?>

     
				