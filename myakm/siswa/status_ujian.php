<?php
$nilaiq = mysqli_query($koneksi, "SELECT nilai.id_nilai,nilai.id_ujian,nilai.id_bank,nilai.id_siswa,nilai.ipaddress,nilai.jumjawab,nilai.browser,
nilai.ujian_mulai,nilai.ujian_selesai,nilai.ujian_berlangsung,nilai.nilai,
ujian.id_ujian,ujian.pelanggaran ,ujian.status FROM nilai LEFT JOIN ujian ON nilai.id_ujian=ujian.id_ujian  where ujian.status='1' and nilai.id_siswa<>'' and nilai.id_ujian='$_GET[id]' ORDER BY nilai.id_nilai DESC");

$uj = fetch($koneksi,'ujian',['id_ujian'=>$_GET['id']]);
 ?>

    <div class='row'>
	<?php if($uj['pelanggaran']==0): ?>
    <div class='col-md-12'>
	<?php else : ?>
	 <div class='col-md-9'>
	<?php endif; ?>
            <div class="card">
                  <div class="card-header">
				
                    <h5 class="bold"><span class="badge badge-primary"><?= $uj['nama'] ?></span> <span class="badge badge-dark">SESI <?= $uj['sesi'] ?></span> <span class="badge badge-success"> TINGKAT <?= $uj['level'] ?></span> </h5>
					<div class="pull-right" id="ulg">
					<button  class="paksa btn btn-secondary"><i class="material-icons">select_all</i>SELESAI</button>
					  <a href="." class="btn btn-light">BACK</a>
					  <?php if($uj['pelanggaran']>=1): ?>
					  <button data-id="<?= $_GET['id'] ?>"  class="paksa btn  btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Selesai Paksa"><i class="material-icons">sync</i>END ALL </button>
					  <button data-id="<?= $_GET['id'] ?>"  class="hapus btn  btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset"><i class="material-icons">delete</i>RESET ALL </button>
					  <?php endif; ?>
					  </div>
					<br>
					<?php if($uj['pelanggaran']==0): ?>
					
					<?php else : ?>
                     <span class="badge badge-secondary kanan">RESET BROWSER</span>
						<?php endif; ?>
					</div>
                <div class='card-body'>
				
                    <div id='logstatus'>


 
			</div>
		</div>
	</div>
</div>
<?php if($uj['pelanggaran']==0): ?>
<?php else : ?>
		 <div class='col-md-3'>
            <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">PERMINTAAN RESET BROWSER</h5>
					</div>
                <div class='card-body'>
				<div class="d-grid gap-2">
				<button class="btn btn-primary" type="button" disabled>
					<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
					INFORMASI...
				</button>
				<div class="alert alert-light" role="alert" style="text-align:justify">
                 Jika dalam status muncul <b style="color:red">Selesai - tidak terjawab semua</b> dan dibawahnya tidak muncul tulisan <b style="color:red">Minta Reset</b>, maka sebaiknya
				 Proktor menayakan ke Peserta mau lanjut atau tidak. Jika lanjut, Siswa boleh menekan tombol Minta Reset, atau Proktor Bisa Langsung klik Reset,
				 Tetapi jika Siswa tidak mau melanjutkan maka klik <b style="color:red">Selesai Paksa</b>
				 Jika dalam status muncul <b style="color:red">Error</b> maka Proktor langsung tekan <b style="color:red">Reset</b> dan Periksa jaringan koneksi siswa, kemungkinan tidak mendapat IP
					</div>
				  </div>
				</div>
              </div>
           </div>
			<?php endif; ?>	
		</div>	
  <script>
    var autoRefresh = setInterval(
        function() {
            <?php if (isset($_GET['id'])) { ?>
                $('#logstatus').load("siswa/semua_status.php?idu=<?= $_GET['id'] ?>");
				
            <?php } ?>
        }, 1000
    );

   
</script>

 