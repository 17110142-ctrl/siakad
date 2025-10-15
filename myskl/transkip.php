<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<?php if ($ac == '') : ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card">
             <div class="card-header">									
                  
					<h5 class="card-title"><i class="fas fa-user-friends fa-fw"></i> Transkip Nilai</h5>
                </div>
			  
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table style="font-size: 11px" id='example1' class='table'>
                            <thead>
                                <tr>
                                    <th width='3px'></th>
                                     <th>NIS</th>
                                <th>Nama siswa</th>
                                <th>Tempat</th>
                                <th>Tgl Lahir</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                   
                                <th>Cetak</th>
                              
                                </tr>
                            </thead>
							 <tbody>
                            <?php
							$no = 0;
                            $query = mysqli_query($koneksi, "select * from siswa WHERE level='$skl[tingkat]' order by id_siswa DESC");
                            while ($siswa = mysqli_fetch_array($query)) {							
                                $no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $siswa['nis'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>
									<td><?= $siswa['tempat_lahir'] ?> </td>
									<td><?= $siswa['tgl_lahir'] ?></td>
									<td><?= $siswa['id_kelas'] ?></td>
									<td><?= $siswa['idpk'] ?> </td>
									
                                    <td>
									<a href="sandik_skl/cetaktranskip.php?nis=<?= $siswa['nis'] ?>" target="_blank" class='btn btn-sm btn-outline-danger' data-bs-placement="top" data-bs-toggle="tooltip" title="Print Transkip"><i class='fa fa-print'></i></button>				
									 
									</td>
                                   
							       <?php } ?>  
                                </tr>
                        </tbody>
                        </table>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
	

<?php endif ?>

<script>
   
 
    $('#example1').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'	
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'sandik_ujian/crud_siswa.php?pg=hapus',
                    method: "POST",
                    data: 'id_siswa=' + id,
                    success: function(data) {
                  iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berasil dihapus',
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