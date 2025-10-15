<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulan = date('m');
$blQ = fetch($koneksi,'bulan',['bln'=>$bulan]);

$isKurikulumTask = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
$kelasList = [];
if ($isKurikulumTask) {
    $kelasRes = mysqli_query($koneksi, "SELECT kelas FROM kelas ORDER BY kelas ASC");
    if ($kelasRes) {
        while ($row = mysqli_fetch_assoc($kelasRes)) {
            $kelasName = trim($row['kelas'] ?? '');
            if ($kelasName !== '' && !in_array($kelasName, $kelasList, true)) {
                $kelasList[] = $kelasName;
            }
        }
        mysqli_free_result($kelasRes);
    }
}

$selectedKelas = trim($user['walas'] ?? '');
if ($isKurikulumTask) {
    $selectedKelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
    if ($selectedKelas === '' && !empty($kelasList)) {
        $selectedKelas = $kelasList[0];
    }
}

$classOptions = [];
if ($isKurikulumTask) {
    $classOptions = $kelasList;
} elseif ($selectedKelas !== '') {
    $classOptions[] = $selectedKelas;
}
?>


                        <div class="row">
                           <div class="col-xl-12">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">
										PELANGGARAN SISWA KELAS <?= $selectedKelas !== '' ? htmlspecialchars($selectedKelas) : '-' ?>
										</h5>
                                    </div>
									
                                    <div class="card-body">

<?php if ($isKurikulumTask && !empty($kelasList)) : ?>
    <form method="get" class="row g-2 mb-3">
        <input type="hidden" name="pg" value="pelanggaran">
        <div class="col-md-6 col-lg-4">
            <label for="kelasFilterPelanggaran" class="form-label">Pilih Kelas</label>
            <select id="kelasFilterPelanggaran" name="kelas" class="form-select" onchange="this.form.submit()">
                <?php foreach ($kelasList as $kelasItem) : ?>
                    <option value="<?= htmlspecialchars($kelasItem, ENT_QUOTES) ?>" <?= $kelasItem === $selectedKelas ? 'selected' : '' ?>>Kelas <?= htmlspecialchars($kelasItem) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
<?php elseif ($isKurikulumTask && empty($kelasList)) : ?>
    <div class="alert alert-warning">Belum ada data kelas yang dapat ditampilkan.</div>
<?php endif; ?>

<?php if ($selectedKelas === '' && !$isKurikulumTask) : ?>
    <div class="alert alert-info">Anda belum ditetapkan sebagai wali kelas.</div>
<?php endif; ?>

<?php if ($selectedKelas !== '') : ?>
                                         <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th >TANGGAL</th>
                                                    <th >NIS</th>
													<th >NAMA SISWA</th>
													<th >KETERANGAN</th>
													<th >POIN</th>
													
                                                </tr>
                                            </thead>
                                            <tbody>
											<?php
											
											$no=0; 
											$kelasEsc = mysqli_real_escape_string($koneksi, $selectedKelas);
											$query = mysqli_query($koneksi, "SELECT * FROM bk_siswa WHERE kelas='$kelasEsc'"); 
											 while ($data = mysqli_fetch_array($query)) :
				                            $siswa = fetch($koneksi,'siswa',['nis'=>$data['nis']]);
											$no++;
											   ?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                   
                                                    <td><?= $data['tanggal'] ?></td>
													<td><?= $siswa['nis'] ?></td>
													   <td><?= $siswa['nama'] ?></td>
													<td><?= $data['ket'] ?></td>
													<td><?= $data['poin'] ?></td>
													
											   </tr>
											<?php endwhile; ?>
												</table>
												 </div>
<?php endif; ?>
                                            </div>
                                           
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
											   url: 'siswa/edit.php?pg=hapusprestasi',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
													  iziToast.info(
										{
											 title: 'Sukses!',
											message: 'Data berhasil dihapus',
											titleColor: '#FFFF00',
											messageColor: '#fff',
											backgroundColor: 'rgba(0, 0, 0, 0.5)',
											 progressBarColor: '#FFFF00',
											  position: 'topRight'				  
											});
													setTimeout(function() {
														window.location.replace('?pg=pelanggaran');
													}, 2000);
												}
											});
										}
										return false;
									})

								});

							</script>    
