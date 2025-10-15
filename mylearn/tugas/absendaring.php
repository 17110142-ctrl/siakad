<?php defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!'); ?>
<?php $rapor = fetch($koneksi, 'setting_rapor', ['id' => 1]); ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class='box box-solid'>
                <div class='box-header with-border '>
                    <h3 class='box-title'> Daftar Absen Daring</h3>
                    <div class='box-tools pull-right '>
                     <button type="button" class="btn btn-icon icon-left btn-danger" data-toggle="modal" data-target="#cetakdata">
        <i class="fa fa-print"></i> Cetak
    </button>
                    </div>
                </div><!-- /.box-header -->
                <div class='box-body'>
                    <!-- Button trigger modal -->
                    <div class="form-group">
                        
                    </div>
                  
                   
                   <div class='table-responsive'>
                        <table style="font-size: 11px" id='tabelmapel' class='table table-bordered table-striped'>
                            <thead>
                                <tr>
                                    <th width='5px'>#</th>
                                    <th>Nama</th>
                          
                                    <th>Kelas</th>
									 <th>Tanggal Absen</th>
                                    <th  width='20px'>Photo</th>
                                    <th width='10px'></th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php
								$no = 0;
								 $query = mysqli_query($koneksi, "select * from absen_daring");
                            while ($absen = mysqli_fetch_array($query)) {
								
								$siswa=fetch($koneksi,'siswa',['id_siswa' => $absen['idsiswa']]);
                                $no++;
                            ?>
							 <tr>
                                    <td><?= $no; ?></td>
									 <td><?= $siswa['nama'] ?></td>
                                    <td><?= $siswa['id_kelas'] ?></td>
							        <td><?= $absen['tanggal'] ?> <?= $absen['jam'] ?></td>
									<td><img src="../tugas/<?= $absen['gambar'] ?>" width="50"></td>
									<td>
                                        <button data-id="<?= $absen['id'] ?>" class="hapus btn-sm btn btn-danger"><i class="fas fa-trash    "></i></button>
										</td>	
                                        </tr>
							<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>


<script>
  
    $('#tabelmapel').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        swal({
            title: 'Apa anda yakin?',
            text: "akan menghapus tugas ini!",

            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'mod_tugas/crud_tugas.php?pg=hapusabsen',
                    method: "POST",
                    data: 'id=' + id,
                    success: function(data) {
                        toastr.success('absen berhasil dihapus');
                        $("#tabelmapel").load(window.location + " #tabelmapel");
                    }
                });
            }
        })

    });
 
</script>
 <div class="modal fade" id="cetakdata" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form name="form-cetak" method="POST" action="mod_elearn/tugas/cetakdaring.php" target="_blank">
                   <input type="hidden" name="guru" value='<?= $_SESSION['id_pengawas'] ?>' class="form-control" required>
                  
				 <div class='modal-body'>
                        <div class="row">
                            
                    <div class="col-md-12">
                       <div class='form-group'>
                        <label>Kelas</label>
                         <select class="form-control" style="width: 100%" name="kelas" id="kelas" >
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE guru='$_SESSION[id_pengawas]' GROUP BY kelas");
                            while ($kelas = mysqli_fetch_array($query)) {
                            ?>
                                <option value="<?= $kelas['kelas'] ?>"><?= $kelas['kelas'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
					</div>
					
					 <div class="col-md-6">
                       <div class='form-group'>
                        <label>Semester</label>
                        <input type="text" name="smt" value="<?= $rapor['semester'] ?>" class="form-control" autocomplete='off' disabled>
                    </div>
					</div>
					 <div class="col-md-6">
                       <div class='form-group'>
                        <label>Tahun Pelajaran</label>
                       <input type="text" name="tp" value="<?= $rapor['tp'] ?>" class="form-control" autocomplete='off' disabled>
                   
					</div>
						</div>
                    </div>
						</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Cetak</button>
                </div>
            </form>
        </div>
    </div>
</div>