<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           <?php if ($ac == '') : ?>
	        <div class="alert alert-dark" role="alert">
                  Asesmen Sumatif Akhir Semester <b>tidak wajib</b> untuk dilakukan, kewenangan diserahkan pada sekolah. 
                 Jika dilaksanakan , silahkan isi nilai dibawah ini	
				<?php
	if (empty($_GET['level'])) {
        $tingkat = "";
    } else {
        $tingkat = $_GET['level'];
    }			
    if (empty($_GET['kelas'])) {
    $kelas = "";
} else {
    $kelas = $_GET['kelas'];
}
 if (empty($_GET['mapel'])) {
        $mapel = "";
    } else {
        $mapel = $_GET['mapel'];
    }
	  if (empty($_GET['guru'])) {
        $guru = "";
    } else {
        $guru = $_GET['guru'];
    }
	$is_admin_like = in_array($user['level'], ['admin','kurikulum']);
	$mpl = $mapel !== '' ? fetch($koneksi,'mata_pelajaran',['id'=>$mapel]) : ['kode' => '', 'nama_mapel' => ''];
	$kelas_all_options = [];
	if ($is_admin_like) {
		$qAllClass = mysqli_query($koneksi, "SELECT kelas FROM kelas WHERE kelas <> '' ORDER BY kelas");
		while ($rowClass = mysqli_fetch_assoc($qAllClass)) {
			$kelas_all_options[] = $rowClass['kelas'];
		}
	}

	$templateRoot = 'nilai/prosessas.php';
	if (!empty($homeurl ?? '')) {
		$templateRoot = rtrim($homeurl, '/') . '/mykurmer/nilai/prosessas.php';
	}

	$perMapelHref = '#';
	$perMapelClass = 'btn-link';
	if (!($mapel !== '' && $kelas !== '')) {
		$perMapelClass .= ' disabled';
		if ($is_admin_like) {
			$perMapelClass .= ' d-none';
		}
	} else {
		$perParams = ['m' => $mapel, 'k' => $kelas];
		if ($semester !== '') {
			$perParams['s'] = $semester;
		}
		if ($tapel !== '') {
			$perParams['tp'] = $tapel;
		}
		if ($guru !== '') {
			$perParams['g'] = $guru;
		}
		$perMapelHref = $templateRoot . '?' . http_build_query($perParams);
	}

	$allMapelHref = '#';
	$allMapelClass = 'btn-link';
	if ($mapel !== '' && $kelas !== '') {
		$allMapelClass .= ' d-none';
	}
	if ($kelas === '') {
		$allMapelClass .= ' disabled';
	} else {
		$allParams = ['mode' => 'all', 'k' => $kelas];
		if ($semester !== '') {
			$allParams['s'] = $semester;
		}
		if ($tapel !== '') {
			$allParams['tp'] = $tapel;
		}
		$allMapelHref = $templateRoot . '?' . http_build_query($allParams);
	}
    ?>
	 <?php include"sandik_rapor/radio.php"; ?>
				   </div>	
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="bold"> SUMATIF AKHIR SEMESTER <span class="badge badge-secondary"><?= $mpl['kode'] ?> <?= $kelas ?></span></h5>
										<div class="pull-right">
									<?php if($is_admin_like || $kelas<>''): ?>
										
										<button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#example"><i class="material-icons">upload</i>Upload</button>
                                    
									<?php endif; ?>
									
									</div>
									</div>
                                    <div class="card-body">
									<form id="form-nilai-sas" method="POST" action="nilai/crud_sas.php">
    <input type="hidden" name="idsiswa" value="<?= $data['id_siswa'] ?>" >
    <input type="hidden" name="kelas" id="import-kelas-hidden" value="<?= $kelas ?>">
    <input type="hidden" name="mapel" id="import-mapel-hidden" value="<?= $mapel ?>">
    <input type="hidden" name="guru" id="import-guru-hidden" value="<?= $guru ?>">
    <input type="hidden" name="semester" value="<?= $semester ?>">
    <input type="hidden" name="tp" value="<?= $tapel ?>">
    
    <div class="card-box table-responsive">
        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th>NIS</th>
                    <th>NAMA SISWA</th>
                    <th>SAS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'"); 				
                while ($data = mysqli_fetch_array($query)) :
                    $sas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_sts WHERE nis='$data[nis]' AND mapel='$mapel' AND khp='SAS' and guru='$guru' and semester='$semester' and tp='$tapel'"));
                    $no++;
                ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $data['nis'] ?></td>
                        <td><?= $data['nama'] ?></td>
                        <td>
                            <input type="number" name="nilai_sas[<?= $data['nis'] ?>]" class="form-control form-control-sm" style="width: 80px;" value="<?= $sas['nilai_sas'] ?>">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <div class="text-end">
        <button type="submit" class="btn btn-success">SIMPAN NILAI</button>
    </div>
</form>

												 <div class="modal fade" id="example" tabindex="-1" aria-labelledby="example" aria-hidden="true">
                                                     <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header justify-content-between align-items-center">
                                                                <h5 class="mb-0">NILAI SAS <span class="badge badge-secondary"><?= $mapel !== '' ? (($mpl['kode'] ?? '') . ' ' . $kelas) : 'SEMUA MAPEL' ?></span></h5>
                                                                <div class="d-flex gap-2">
													<a href="<?= htmlspecialchars($perMapelHref, ENT_QUOTES, 'UTF-8') ?>" id="download-template-mapel-sas" data-root="<?= htmlspecialchars($templateRoot, ENT_QUOTES, 'UTF-8') ?>" data-template="<?= htmlspecialchars($perMapelHref, ENT_QUOTES, 'UTF-8') ?>" class="<?= $perMapelClass ?>" target="_blank" rel="noopener noreferrer"><b>Download Format (Per Mapel)</b></a>
													<?php if ($is_admin_like): ?>
													<a href="<?= htmlspecialchars($allMapelHref, ENT_QUOTES, 'UTF-8') ?>" id="download-template-semua-sas" data-root="<?= htmlspecialchars($templateRoot, ENT_QUOTES, 'UTF-8') ?>" data-template="<?= htmlspecialchars($allMapelHref, ENT_QUOTES, 'UTF-8') ?>" class="<?= $allMapelClass ?>" target="_blank" rel="noopener noreferrer"><b>Download Format (Semua Mapel)</b></a>
													<?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body">
													 <div class="alert alert-dark" role="alert">                   
													  Asesmen Sumatif Akhir Semester <b>tidak wajib</b> untuk dilakukan, kewenangan diserahkan pada sekolah. 
														Jika dilaksanakan , silahkan isi nilai dibawah ini	
													  </div>
							          <form id="form-import-sas" method="POST" action="nilai/crud_sas.php" enctype="multipart/form-data">
								                     <input type="hidden" name="guru" id="import-guru-field" value="<?= $guru ?>" >
													<input type="hidden" name="mapel" id="import-mapel-field" value="<?= $mapel ?>" >
													<input type="hidden" name="kelas" id="import-kelas-field" value="<?= $kelas ?>" >
													<input type="hidden" name="semester" value="<?= $semester ?>" >
													<input type="hidden" name="tp" value="<?= $tapel ?>" >
													<input type="hidden" name="level" value="<?= $tingkat ?>" >
													<?php $can_multi_import = in_array($user['level'], ['admin','kurikulum']); ?>
													<?php if ($can_multi_import) : ?>
													<div class="mb-3">
														<label class="form-label bold d-block">Mode Import</label>
														<div class="form-check">
															<input class="form-check-input" type="radio" name="import_scope" id="scope-mapel" value="per_mapel" <?= $mapel ? 'checked' : '' ?>>
															<label class="form-check-label" for="scope-mapel">Import per Mata Pelajaran (menggunakan mapel terpilih)</label>
														</div>
														<div class="form-check">
															<input class="form-check-input" type="radio" name="import_scope" id="scope-semua" value="semua_mapel" <?= $mapel ? '' : 'checked' ?>>
															<label class="form-check-label" for="scope-semua">Import semua mapel dari file (gunakan kolom MAPEL pada template)</label>
														</div>
														<small class="text-muted" id="import-scope-note">Pada mode semua mapel, guru akan ditentukan otomatis dari jadwal mengajar.</small>
													</div>
	<?php $show_kelas_modal = ($can_multi_import && $kelas === ''); ?>
	<div class="mb-3" id="import-kelas-wrapper"<?= $show_kelas_modal ? '' : ' style="display:none;"' ?>>
		<label class="form-label">Pilih Kelas</label>
		<select class="form-select" id="import-kelas-select">
			<option value="">Pilih Kelas</option>
			<?php foreach ($kelas_all_options as $kelasOpt): ?>
			<option value="<?= htmlspecialchars($kelasOpt) ?>" <?= $kelasOpt === $kelas ? 'selected' : '' ?>><?= htmlspecialchars($kelasOpt) ?></option>
															<?php endforeach; ?>
														</select>
														<small class="text-muted">Digunakan untuk menentukan kelas saat import semua mapel.</small>
													</div>
													<?php else : ?>
														<input type="hidden" name="import_scope" value="per_mapel">
													<?php endif; ?>
													<div class="mb-3">
														<label class="form-label">Pilih File (xls/xlsx)</label>
													<input type='file' name='file' id="import-file" class='form-control' accept=".xls,.xlsx" required />
													<div id="progressbox-sas" class="mt-2"></div>
												</div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">BATAL</button>
                                                                <button type="submit" class="btn btn-primary">SIMPAN</button>
                                                            </div>
															 </form>
                                                        </div>
                                                    </div>
                                                </div>
											</div>
										</div>
										
									</div>
									
					       <div class="col-md-4">
                     
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                  
                                    </div>
                                    <div class="card-body">
                                        <div class="widget-payment-request-container">
                                            <div class="widget-payment-request-author">
                                                <div class="avatar m-r-sm">
                                                    <img src="../images/guru.png" alt="">
                                                </div>
                                                <div class="widget-payment-request-author-info">
                                                    <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span>
                                                    <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                                               
											   </div>
                                            </div>
											
									<div class="widget-payment-request-info m-t-md">
									 <div class="d-grid gap-2">
									<button class="btn btn-primary" type="button" disabled>
										<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
										 SUMATIF AKHIR SEMESTER
									</button>
											</div>
								
                               <div class="col-md-12">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select level' required='true' style="width: 100%">
								    <option value="">Pilih Tingkat</option>
                                        <?php
										
											$query = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='2' GROUP BY level"); 	
										  
										while ($tkt = mysqli_fetch_array($query)) {										
										echo "<option value='$tkt[level]'>$tkt[level]</option>";
													}
													?>						
									 </select>
							     </div>	
							    <div class="col-md-12">
								<label class="form-label bold">Pilih Rombel</label>
								<select name='kelas' id='kelas' class='form-select kelas' required='true' style="width: 100%">         
								
								 </select>
							     </div>
							<div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select mapel' data-selected="<?= htmlspecialchars($mapel, ENT_QUOTES, 'UTF-8') ?>" <?= $is_admin_like ? '' : 'required' ?> style="width: 100%">
								   <option value=''>Pilih Mapel (pilih tingkat & rombel dulu)</option>
								 </select>
									
							</div>
							
								 <div class="col-md-12">
								<label class="form-label bold">Guru Pengampu</label>
								<select name="guru" class='form-select guru' <?= $is_admin_like ? '' : 'required' ?> style="width: 100%" id="guru-select">
										  <?php
										if(in_array($user['level'], ['admin','kurikulum'])):
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru'"); 
											echo "<option value=''>Pilih Guru</option>";
											else:
											$query = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru' and id_user='$user[id_user]'"); 	
										  endif;
										while ($guru = mysqli_fetch_array($query)) {										
										echo "<option value='$guru[id_user]'>$guru[nama]</option>";
													}
													?>	
												</select>
							          </div><p>
									  
								           <div class="d-grid gap-2">
                                         <button  class="btn btn-primary flex-grow-1 m-l-xxs" id="cari"> PILIH</button>
                                          <script type="text/javascript">
                                $('#cari').click(function() {
									 var level = $('.level').val();
                                    var kelas = $('.kelas').val();
                                    var mapel = $('.mapel').val();
									 var guru = $('.guru').val();
                                    location.replace("?pg=<?= enkripsi('sas') ?>&level=" + level + "&kelas=" + kelas + "&mapel=" + mapel + "&guru=" + guru);
                                }); 
                            </script>
                                            </div>
									
										</div>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>
							<script>
$('#form-nilai-sas').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize(); 
    $.ajax({
        type: 'POST',
        url: 'nilai/crud_sas.php',
        data: data,
        dataType: 'json',
        success: function(response) {
            var msg = (response && response.message) ? response.message : "Nilai berhasil disimpan!";
            if (response) {
                var detail = [];
                if (typeof response.inserted !== 'undefined') {
                    detail.push(response.inserted + ' baru');
                }
                if (typeof response.updated !== 'undefined') {
                    detail.push(response.updated + ' diperbarui');
                }
                if (Array.isArray(response.skipped) && response.skipped.length > 0) {
                    detail.push(response.skipped.length + ' dilewati');
                }
                if (Array.isArray(response.warnings) && response.warnings.length > 0) {
                    detail.push(response.warnings.length + ' peringatan');
                    console.warn('Peringatan:', response.warnings);
                }
                if (detail.length) {
                    msg += '\n(' + detail.join(', ') + ')';
                }
            }
            alert(msg);
            window.location.reload();
        },
        error: function(xhr) {
            var msg = 'Gagal menyimpan nilai.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            alert(msg);
        }
    });
});
</script>

                        <script>
$('#form-import-sas').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'nilai/crud_sas.php',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									dataType: 'json',
									beforeSend: function() {
									$('#progressbox-sas').html('<div><label class="sandik" style="color:blue;">Data sedang diproses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
									},
									success: function(res) {
										var msg = (res && res.message) ? res.message : 'Import selesai.';
										var detail = [];
										if (res) {
											if (typeof res.inserted !== 'undefined') {
												detail.push(res.inserted + ' baru');
											}
											if (typeof res.updated !== 'undefined') {
												detail.push(res.updated + ' diperbarui');
											}
											if (Array.isArray(res.skipped) && res.skipped.length > 0) {
												detail.push(res.skipped.length + ' dilewati');
												console.log('Baris dilewati:', res.skipped);
											}
											if (Array.isArray(res.warnings) && res.warnings.length > 0) {
												detail.push(res.warnings.length + ' peringatan');
												console.warn('Peringatan kolom:', res.warnings);
											}
										}
										if (detail.length) {
											msg += '<br><small>' + detail.join(', ') + '</small>';
										}
										$('#progressbox-sas').html('<div class="alert alert-success">'+ msg +'</div>');
										setTimeout(function() {
											window.location.reload();
										}, 1500);
									},
									error: function(xhr) {
										var msg = 'Gagal mengimpor nilai.';
										if (xhr.responseJSON && xhr.responseJSON.message) {
											msg = xhr.responseJSON.message;
										}
										$('#progressbox-sas').html('<div class="alert alert-danger">'+ msg +'</div>');
									}
								})
								return false;
							});


(function(){
    const canMulti = <?= $is_admin_like ? 'true' : 'false' ?>;
    const mapelHiddenMain = $('#import-mapel-hidden');
    const mapelHiddenModal = $('#import-mapel-field');
    const guruHidden = $('#import-guru-field');
    const kelasHidden = $('#import-kelas-field');
    const levelHidden = $('input[name="level"][type="hidden"]');
    const mapelSelect = $('#mapel');
    const guruSelect = $('select.guru');
    const kelasSelectMain = $('#kelas');
    const scopeRadios = $('input[name="import_scope"]');
    const scopePerMapel = $('#scope-mapel');
    const scopeSemua = $('#scope-semua');
    const kelasWrapper = $('#import-kelas-wrapper');
    const kelasSelectModal = $('#import-kelas-select');
    const linkMapel = $('#download-template-mapel-sas');
    const linkSemua = $('#download-template-semua-sas');
	const mapelTemplateRootSas = linkMapel.data('root') || 'mykurmer/nilai/prosessas.php';
    const semuaTemplateRootSas = linkSemua.length ? (linkSemua.data('root') || mapelTemplateRootSas) : mapelTemplateRootSas;
    const semesterVal = <?= json_encode($semester) ?>;
    const tapelVal = <?= json_encode($tapel) ?>;

    function buildTemplateUrl(root, paramsObj) {
        const params = new URLSearchParams();
        Object.keys(paramsObj).forEach(function(key){
            const value = paramsObj[key];
            if (value !== undefined && value !== null && String(value) !== '') {
                params.set(key, value);
            }
        });
        const query = params.toString();
        return query ? (root + '?' + query) : root;
    }

    function updateTemplateLinks() {
        const kelasVal = kelasHidden.val();
        const mapelVal = mapelHiddenModal.val();
        const scopeVal = canMulti ? (scopeRadios.filter(':checked').val() || 'semua_mapel') : 'per_mapel';
        const mapelSelected = !!mapelVal;
        const kelasSelected = !!kelasVal;

        const shouldShowMapel = mapelSelected && kelasSelected && (!canMulti || scopeVal === 'per_mapel');
        const shouldShowSemua = canMulti && (!mapelSelected || scopeVal === 'semua_mapel');

        if (linkMapel.length) {
            if (shouldShowMapel) {
                const guruVal = guruHidden.val();
                const url = buildTemplateUrl(mapelTemplateRootSas, {
                    m: mapelVal,
                    k: kelasVal,
                    s: semesterVal,
                    tp: tapelVal,
                    g: guruVal
                });
                linkMapel.removeClass('disabled d-none')
                    .attr('href', url)
                    .attr('data-template', url)
                    .attr('target', '_blank')
                    .attr('rel', 'noopener noreferrer');
            } else {
                linkMapel.attr('href', '#')
                    .attr('data-template', '')
                    .removeAttr('target')
                    .removeAttr('rel')
                    .addClass('disabled');
                if (canMulti) {
                    linkMapel.addClass('d-none');
                }
            }
        }

        if (linkSemua.length) {
            if (shouldShowSemua) {
                linkSemua.removeClass('d-none');
                if (kelasSelected) {
                    const urlAll = buildTemplateUrl(semuaTemplateRootSas, {
                        mode: 'all',
                        k: kelasVal,
                        s: semesterVal,
                        tp: tapelVal
                    });
                    linkSemua.removeClass('disabled')
                        .attr('href', urlAll)
                        .attr('data-template', urlAll)
                        .attr('target', '_blank')
                        .attr('rel', 'noopener noreferrer');
                } else {
                    linkSemua.addClass('disabled')
                        .attr('href', '#')
                        .attr('data-template', '')
                        .removeAttr('target')
                        .removeAttr('rel');
                }
            } else {
                linkSemua.attr('href', '#')
                    .attr('data-template', '')
                    .removeAttr('target')
                    .removeAttr('rel');
                if (canMulti) {
                    linkSemua.addClass('d-none');
                }
            }
        }
    }

    function toggleKelasWrapper() {
        if (!canMulti) return;
        const scope = scopeRadios.filter(':checked').val();
        const kelasVal = kelasHidden.val();
        if (scope === 'semua_mapel' && !kelasVal) {
            kelasWrapper.show();
        } else {
            kelasWrapper.hide();
        }
        updateTemplateLinks();
    }

    function refreshScopeAvailability() {
        if (!canMulti) return;
        const mapelVal = mapelSelect.val() || mapelHiddenMain.val();
        if (scopePerMapel.length) {
            if (mapelVal) {
                scopePerMapel.prop('disabled', false);
                if (!scopeRadios.filter(':checked').length) {
                    scopePerMapel.prop('checked', true);
                }
            } else {
                scopePerMapel.prop('disabled', true).prop('checked', false);
                scopeSemua.prop('checked', true);
            }
        }
        toggleKelasWrapper();
    }

    function fetchMapelOptions(selectedId) {
        const kelasVal = kelasSelectMain.val() || kelasHidden.val() || (kelasSelectModal.length ? kelasSelectModal.val() : '') || '';
        const levelVal = $('.level').val() || (levelHidden.length ? levelHidden.val() : '') || '';
        const guruVal  = $('#guru-select').val() || guruHidden.val() || '';

        if (!kelasVal) {
            mapelSelect.html('<option value="">Pilih rombel terlebih dahulu</option>').prop('disabled', true);
            mapelHiddenMain.val('');
            mapelHiddenModal.val('');
            mapelSelect.data('selected', '');
            refreshScopeAvailability();
            updateTemplateLinks();
            return;
        }

        mapelSelect.prop('disabled', true).html('<option value="">Memuat daftar mapel...</option>');

        $.ajax({
            url: 'nilai/tnilai.php?pg=mapel',
            method: 'POST',
            dataType: 'json',
            data: {
                kelas: kelasVal,
                level: levelVal,
                guru: guruVal
            },
            success: function(res){
                let optionsHtml = '<option value="">Pilih Mapel</option>';
                if (res && res.status === 'ok' && Array.isArray(res.data) && res.data.length) {
                    const targetSelected = selectedId !== undefined && selectedId !== null && selectedId !== ''
                        ? String(selectedId)
                        : String(mapelSelect.data('selected') || '');
                    res.data.forEach(function(item){
                        const val = String(item.id);
                        const text = item.kode ? (item.kode + ' - ' + item.nama) : item.nama;
                        const selectedAttr = targetSelected && targetSelected === val ? ' selected' : '';
                        optionsHtml += '<option value="'+ val +'"'+ selectedAttr +'>'+ text +'</option>';
                    });
                    mapelSelect.html(optionsHtml).prop('disabled', false);
                } else {
                    const message = (res && res.message) ? res.message : 'Mapel belum diatur.';
                    mapelSelect.html('<option value="">'+ message +'</option>').prop('disabled', true);
                }
                const currentVal = mapelSelect.val() || '';
                mapelHiddenMain.val(currentVal);
                mapelHiddenModal.val(currentVal);
                mapelSelect.data('selected', currentVal);
                refreshScopeAvailability();
                updateTemplateLinks();
            },
            error: function(){
                mapelSelect.html('<option value="">Gagal memuat mapel.</option>').prop('disabled', true);
                mapelHiddenMain.val('');
                mapelHiddenModal.val('');
                mapelSelect.data('selected', '');
                refreshScopeAvailability();
                updateTemplateLinks();
            }
        });
    }

    function syncHidden() {
        const currentMapelVal = mapelSelect.val() || mapelHiddenMain.val() || '';
        if (currentMapelVal !== mapelHiddenMain.val()) {
            mapelHiddenMain.val(currentMapelVal);
        }
        if (currentMapelVal !== mapelHiddenModal.val()) {
            mapelHiddenModal.val(currentMapelVal);
        }

        const guruVal = guruSelect.val() || guruHidden.val() || '';
        if (guruVal !== guruHidden.val()) {
            guruHidden.val(guruVal);
        }

        const kelasVal = kelasSelectMain.val() || kelasHidden.val() || (kelasSelectModal.length ? kelasSelectModal.val() : '') || '';
        if (kelasVal) {
            if (kelasVal !== kelasHidden.val()) {
                kelasHidden.val(kelasVal);
            }
            if (kelasSelectModal.length && kelasVal !== kelasSelectModal.val()) {
                kelasSelectModal.val(kelasVal);
            }
        }
        updateTemplateLinks();
    }

    mapelSelect.on('change', function(){
        mapelHiddenMain.val(this.value);
        mapelHiddenModal.val(this.value);
        mapelSelect.data('selected', this.value || '');
        if (canMulti) {
            if (this.value) {
                scopePerMapel.prop('checked', true);
            } else {
                scopeSemua.prop('checked', true);
            }
        }
        refreshScopeAvailability();
        updateTemplateLinks();
    });

    guruSelect.on('change', function(){
        guruHidden.val(this.value);
        const current = mapelSelect.data('selected') || mapelSelect.val() || '';
        fetchMapelOptions(current);
    });

    kelasSelectMain.on('change', function(){
        kelasHidden.val(this.value);
        if (kelasSelectModal.length) {
            kelasSelectModal.val(this.value);
        }
        mapelSelect.data('selected', '');
        updateTemplateLinks();
        fetchMapelOptions('');
    });

    if (kelasSelectModal.length) {
        kelasSelectModal.on('change', function(){
            kelasHidden.val(this.value);
            updateTemplateLinks();
        });
    }

    scopeRadios.on('change', function(){
        if (!canMulti) return;
        if (this.value === 'semua_mapel') {
            mapelHiddenMain.val('');
            mapelHiddenModal.val('');
            guruHidden.val('');
        } else {
            const currentVal = mapelSelect.val();
            mapelHiddenMain.val(currentVal);
            mapelHiddenModal.val(currentVal);
            guruHidden.val(guruSelect.val());
        }
        toggleKelasWrapper();
    });

    $('#example').on('show.bs.modal', function(){
        syncHidden();
        refreshScopeAvailability();
    });

    $(document).on('click', 'a.disabled', function(e){
        e.preventDefault();
        const targetId = this.id || '';
        if (targetId === 'download-template-semua-sas') {
            alert('Silakan pilih kelas terlebih dahulu sebelum mengunduh format semua mapel.');
        }
    });

    syncHidden();
    refreshScopeAvailability();
    fetchMapelOptions(mapelSelect.data('selected') || '');
})();
							</script>
							<script>	
						$("#level").change(function() {
							var level = $(this).val();
							console.log(level);
							$.ajax({
							type: "POST",
							url: "nilai/tnilai.php?pg=kelas", 
							data: "level=" + level, 
							success: function(response) { 
							$("#kelas").html(response).trigger('change');
							}
							});
							$('#mapel').html('<option value="">Pilih rombel terlebih dahulu</option>').prop('disabled', true).data('selected', '');
							$('#import-mapel-field').val('');
							$('#import-mapel-hidden').val('');
						});
						</script>
					  <?php endif ?>
					  
