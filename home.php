           
			<?php if($setting['pelanggaran']==1): ?>
             <?php if($nilsis >=1){ ?>
				<script>
				$(function() {
				$("#myModal").modal();
				});
				</script>

			  <div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog">
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">INFORMASI</h4>
					</div>
					<div class="modal-body">
					  <p>Anda terdeteksi keluar dari aplikasi, Untuk melanjutkan Ujian silahkan Hubungi Proktor / Admin</p>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
			  </div>

				<?php } ?>
             <?php endif; ?>

			<div class='col-md-7'>		
			 <div class='box box-solid'>
				<div class='box-body'>
			  <div> 
				 TOKEN </i>  <a href="#" class="btn btn-sm btn-default"><?= $token['token'] ?></a>
				 <?php if($setting['pelanggaran']==1): ?>
				 <?php if($nilsis >= 1): ?>
				 <?php $reset = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' AND ujian_selesai<>'' AND browser='0' ORDER BY id_nilai DESC LIMIT 1"));?> 
					  <a href="?pg=mintareset&idn=<?= $reset['id_nilai'] ?>" class="btn btn-danger btn-round btn-sm pull-right">Minta Reset</a>
					<?php endif; ?>
					   <?php endif; ?>
					</div>
				</div>
			</div>
			<div class='box box-solid'>
                <div class='box-header with-border'>
                     <h3 class='box-title'><i class="fas fa-bell"></i> Informasi</h3>
                         </div>
                 <div class='box-body'>
                    <div id='pengumuman'>
                      <?php $logC = 0;
                                        echo "<ul class='timeline'><br>";
                                        $logQ = mysqli_query($koneksi, "SELECT * FROM informasi where untuk='1' ORDER BY id DESC");

                                        while ($log = mysqli_fetch_array($logQ)) {
                                            $logC++;
                                           
                                            if ($log['untuk'] == '2') {
                                                $bg = 'bg-green';
                                                $color = 'text-green';
                                            } else {
                                                $bg = 'bg-blue';
                                                $color = 'text-blue';
                                            }
                                            echo "
                                                        
                                                        
                                                        <!-- timeline time label -->
                                                        
                                                        <li><i class='fa fa-envelope $bg'></i>
                                                        <div class='timeline-item'>
                                                        <span class='time'> <i class='fa fa-calendar'></i> " . buat_tanggal('d-m-Y', $log['waktu']) . " <i class='fa fa-clock-o'></i> " . buat_tanggal('H:i', $log['waktu']) . "</span>
                                                        <h3 class='timeline-header' style='background-color:#f9f0d5'><a href='#'>$log[judul]</a></h3>
                                                        <div class='timeline-body'>
                                                        " . ucfirst($log['isi']) . "	
                                                        </div>
                                                        
                                                        </div>
                                                        </li>
                                            
                                                        
                                                    ";
                                        }
                                        if ($logC == 0) {
                                            echo "<p class='text-center'>Tidak ada aktifitas.</p>";
                                        }
                                        echo "</ul>";?>
							 </div>
                         </div>
                        </div>
					</div>

	<div class="col-sm-5" >
	
	 <div class='box box-solid' >
       <div class='box-body' >
		<div class="edis">
		  <div style="font-size:20px"><i class="fas fa-graduation-cap"></i> Konfirmasi data Peserta</div>
		  <form id="konfir">
				<div class="form-group-sm">
					<label><b>Kode Peserta</b></label> 
					<input type="text" class="form-control"   value="<?= $siswa['no_peserta'] ?>" required="true" >
				</div>
				<div class="form-group-sm">
					<label><b>Nama Peserta</b></label> 
					<input type="text" class="form-control"  value="<?= $siswa['nama'] ?>" required="true" >
				</div>			
				<div class="form-group-sm">
					<label><b>Mata Ujian</b></label> 
					<input type="text" class="form-control" value="Literasi | Numerasi" required="true">
				</div>
				<div class="form-group-sm">
					<label><b>Token</b></label> 
					<input type="text" class="form-control" name="token"  value="<?= $token['token'] ?>" name="token" required="true">
				</div>
				<div class="form-group-sm">
					<label><b>Jenis Kelamin</b></label> 
					<select class="form-control" name="jenis_kelamin" required="true">
					        <option value=""></option>
							<option value="L">Laki-Laki</option>
							<option value="P">Perempuan</option>
						</select>
				</div>
				<div id='progressbox'></div> 
				<br>				
			  <button type="submit" class="btn btn-primary btn-round form-control mt-5"  name="btnsubmit">Submit</button>
			</form> 
			<br>
		</div>
    </div>
	 
  </div> 
</div>
 <script>
    $('#konfir').submit(function(e){
    e.preventDefault();

    $.ajax({
        url: 'tkonfir.php',
        method: 'POST',
        data: new FormData(this),
        cache: false,
        contentType: false,
        processData: false,
        beforeSend() {
            $('#progressbox').html('<label>Data sedang diproses…</label>');
        },
        success(response) {
            if (response.trim() === 'OK') {
                setTimeout(()=> window.location.replace('?pg=jadwal'), 2000);
            } else {
                setTimeout(()=> window.location.reload(), 2000);
            }
        },
        error(xhr) {
            if (xhr.status === 403) {
                // redirect ke forbidden.php jika UA tidak valid
                window.location.href = 'forbidden.php';
            } else {
                alert('Error: ' + xhr.status);
            }
        }
    });

    return false;
});
		</script>