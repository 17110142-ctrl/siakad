
<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>

 <div class="row">
	<div class="col-md-12 col-sm-12 ">
       <div class="x_panel">
           <div class="x_title">
              <h2>Backup Soal </h2>   
                   <div class="clearfix"></div>
                 </div>
<div class="x_content">
     <div class="alert alert-danger alert-dismissible " role="alert">
           <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                    <strong>File Soal Gambar dapat didownload</strong> setelah klik Backup Excel
                  </div>
<div class="table-responsive">
   <table id="datatable1" class="table table-hover table-bordered" style="width:100%">
        <thead>
            <tr>
            <th width='5%'>NO</th>
			<th>KODE</th>
			<th width='5%'>TINGKAT</th>
			<th>KELAS</th>
			<th>JENIS SOAL</th>
			<th>GURU PENGAMPU</th>
			<th>JUMLAH</th>
			<th></th>
            </tr>
        </thead>
		<tbody>
		 <?php 
		 
		 $Q = mysqli_query($koneksi, "SELECT * FROM banksoal");
		 
		 ?>
           <?php while ($bank = mysqli_fetch_array($Q)) : ?>
			<?php
			$kelas = fetch($koneksi,'kelas',['level' => $bank['level']]);
				$datakelas = unserialize($bank['kelas']);
               $guru = fetch($koneksi,'users',['id_user' => $bank['idguru']]);	
                  $jumlahsoal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$bank[id_bank]'"));			   
				  $no++;
                       ?>
                        <tr style="height:30px">
                         <td><?= $no ?></td>
						 <td><?= $bank['kode'] ?></td>
						 <td><?= $bank['level'] ?></td>
                         <td>
						 <?php foreach ($datakelas as $key => $value){
						 echo $value. "  " ;}
							 ?>
						 </td>
						 <td><?= $bank['groupsoal'] ?></td>
						 <td><?= $guru['nama'] ?></td>		
                          <td><?= $jumlahsoal ?> soal</td>
						 <td style="text-align:center">
						 
						 <?php if (file_exists("pengaturan/backup/".$bank['id_bank']."-".$bank['kode'].".zip")) { ?>
						 <a href="pengaturan/backup/<?= $bank['id_bank'] ?>-<?= $bank['kode'] ?>.zip" class="btn-sm btn btn-primary" data-toggle="tooltip" data-placement="top" title="File Soal Gambar"><i class="fa fa-download"></i></a>
						 <?php } ?>
						 <a href="sandik_ujian/bank/proses.php?id=<?= $bank['id_bank'] ?>" class="backup btn-sm btn btn-success" data-toggle="tooltip" data-placement="top" title="Backup Excel"><i class="fa fa-database"></i></a>
						
						</td>								
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				</div>
			</div>
			</div>
				</div>
					</div>
						<script>
				$('#datatable1').on('click', '.backup', function() {
					iziToast.info(
							{
								title: 'Sukses!',
								message: 'Soal berhasil dibackup',
								titleColor: '#FFFF00',
								messageColor: '#fff',
								backgroundColor: 'rgba(0, 0, 0, 0.5)',
								 progressBarColor: '#FFFF00',
								  position: 'topRight'	
									  });
										setTimeout(function() {
											window.location.reload();
										}, 2000);
							});
					</script>
			