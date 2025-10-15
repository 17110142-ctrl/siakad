<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<?php if ($ac == '') : ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card">
             <div class="card-header">									
                  
                    <div class='right'>
                        <?php if ($user['level'] == 'admin' OR $user['jabatan']<>'') : ?>                         
                            <a href="?pg=<?= enkripsi('updatesiswa') ?>" class='btn btn-success kanan'><i class='material-icons'>upload</i>Update</a>                         
                        <?php endif ?>
                    </div>
					<h5 class="card-title"> DATA KELULUSAN</h5>
                </div>
			  
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table style="font-size: 12px" id='datatable1' class='table table-bordered table-hover'>
                            <thead>
                                <tr>
                                    <th width='3px'></th>
                                     <th>NIS</th>
                                <th>Nama siswa</th>
                                <th>Tempat</th>
                                <th>Tgl Lahir</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Keterangan</th>
                                <th>SKL  - SKKB</th>
                              
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
									<td><?= $siswa['t_lahir'] ?> </td>
									<td><?= $siswa['tgl_lahir'] ?></td>
									<td><?= $siswa['kelas'] ?></td>
									<td><?= $siswa['jurusan'] ?> </td>
									<td>
                                        <?php if ($siswa['stskel'] == 1) { ?>
                                            <a data-id="<?= $siswa['id_siswa'] ?>" ><b style="color:blue">LULUS</b></a>
                                        <?php } elseif ($siswa['keterangan'] == 2) { ?>
                                            <a data-id="<?= $siswa['id_siswa'] ?>" >LULUS BERSYARAT</a>
                                        <?php } else { ?>
                                            <a data-id="<?= $siswa['id_siswa'] ?>" ><b style="color:red">TIDAK LULUS</b></a>
                                        <?php } ?>
                                    </td>
                                    <td>
									<a href="print_skl.php?nis=<?= $siswa['nis'] ?>" target="_blank" class='btn btn-sm btn-danger'  data-bs-placement="top" data-bs-toggle="tooltip" title="Print SKL"><i class='material-icons'>print</i></a>				
									<a href="cetakskkb.php?nis=<?= $siswa['nis']  ?>" target="_blank" class='btn btn-sm btn-primary'  data-bs-placement="top" data-bs-toggle="tooltip" title="Print SKKB"><i class='material-icons'>print</i></a>				
									
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
   
 
    $('#datatable1').on('click', '.hapus', function() {
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