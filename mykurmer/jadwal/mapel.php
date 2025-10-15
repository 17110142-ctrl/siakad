<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

?>           
	<?php if ($ac == '') : ?>
                   <div class="row">
                          <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">MAPEL RAPOR</h5>
										
                                    </div>
                                    <div class="card-body">
									<div class="d-flex justify-content-between align-items-center mb-2">
									  <p class="mb-0">Gunakan ikon hapus untuk menghapus mapel pada tingkat tertentu.</p>
									  <button type="button" class="btn btn-outline-primary btn-sm" id="btn-generate"><i class="material-icons" style="font-size:16px;vertical-align:middle;">auto_fix_high</i> Generate dari Jadwal</button>
									</div>
                                    <div class="card-box table-responsive">
                                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                                            <thead>
                                                <tr>
                                                    <th width="5%">NO</th>                                               
                                                    <th>TKT</th>
                                                    <th>JURUSAN</th>
													 <th>MAPEL</th>
                                                     <th style="width:90px;">URUT</th>
                                                     <th style="width:110px;">AKSI</th>
													  
                                                </tr>
                                            </thead>
                                            <tbody>
								<?php
								$grouped = [];
								$query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE kurikulum='2' ORDER BY mapel, tingkat, pk");
								while ($data = mysqli_fetch_array($query)) {
								    $map = fetch($koneksi, 'mata_pelajaran', ['id' => $data['mapel']]);
								    $kuri = fetch($koneksi, 'm_kurikulum', ['idk' => $data['kurikulum']]);
								    $key = $data['mapel'];
								    if (!isset($grouped[$key])) {
								        $grouped[$key] = [
								            'mapel_id' => $data['mapel'],
								            'mapel_name' => $map['nama_mapel'] ?? '-',
								            'levels' => [],
								            'jurusan' => [],
								            'items' => []
								        ];
								    }
								    $grouped[$key]['items'][] = [
								        'id' => $data['idm'],
								        'tingkat' => $data['tingkat'],
								        'urut' => (int)$data['urut'],
								        'pk' => $data['pk'],
								        'kurikulum' => $data['kurikulum'],
								        'mapel' => $data['mapel']
								    ];
								    $grouped[$key]['levels'][$data['tingkat']] = true;
								    $grouped[$key]['jurusan'][$data['pk']] = true;
								}

								$no = 0;
								foreach ($grouped as $group) :
								    $no++;
								    $levels = array_keys($group['levels']);
								    sort($levels);
								    $levelsLabel = implode(', ', $levels);
								    $jurusanLabel = implode(', ', array_keys($group['jurusan']));
								    $itemIds = array_column($group['items'], 'id');
								    $uniqueUrutValues = array_unique(array_column($group['items'], 'urut'));
								    $displayUrut = count($uniqueUrutValues) ? reset($uniqueUrutValues) : 0;
								    $encodedIds = htmlspecialchars(json_encode($itemIds), ENT_QUOTES, 'UTF-8');
								    $groupPayload = htmlspecialchars(json_encode($group), ENT_QUOTES, 'UTF-8');
									?>
                                                <tr>
                                                    <td><?= $no; ?></td>
                                                    <td><?= htmlspecialchars($levelsLabel) ?></td>
                                                    <td><?= htmlspecialchars($jurusanLabel) ?></td>
                                                      <td><span class="fw-semibold"><?= htmlspecialchars($group['mapel_name']) ?></span></td>
                                                      <td>
                                                          <div class="mb-1">
                                                            <input type="number" class="form-control form-control-sm text-center urut-input" data-ids='<?= $encodedIds ?>' value="<?= $displayUrut ?>" min="0">
                                                            <?php if (count($uniqueUrutValues) > 1) : ?>
                                                              <small class="text-muted d-block mt-1">Nilai berbeda terdeteksi, perubahan akan menyamakan semuanya.</small>
                                                            <?php endif; ?>
                                                          </div>
                                                      </td>
                                                      <td class="text-center">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-manage-urut" data-mapel='<?= $groupPayload ?>'>
                                                          <i class="material-icons" style="font-size:16px;vertical-align:middle;">edit</i>
                                                        </button>
                                                      </td>
                                                </tr>
									<?php endforeach; ?>
                                                </table>
												 <div id="progressbox" class="mt-2"></div>
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
									<form id='formjadwal' class="row g-1">                         
                               <div class="col-md-6">
								<label class="form-label bold">Tingkat</label>
								<select name='level' id='level' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Tingkat</option>
									<option value='ALL'>Semua</option>
										<?php
										$lev = mysqli_query($koneksi, "SELECT level,kurikulum FROM kelas WHERE kurikulum='2' GROUP BY level");
										while ($level = mysqli_fetch_array($lev)) {
										echo "<option value='$level[level]'>$level[level]</option>";
										}
										?>
									 </select>
							     </div>	
							             <div class="col-md-6">
								<label class="form-label bold">Jurusan</label>
								<select name='pk' class='form-select' required='true' style="width: 100%">
								    <option value=''>Pilih Jurusan</option>
									<option value='semua'>Semua</option>
										<?php
									$jQ = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
									while ($jrs = mysqli_fetch_array($jQ)) :
									echo "<option value='$jrs[jurusan]'>$jrs[jurusan]</option>";
										endwhile;
										?>
										</select>
							</div> 
							<div class="col-md-12">
								<label class="form-label bold">Kurikulum</label>
								<select name='kuri' id='kuri' class='form-select kuri' required='true' style="width: 100%">
								   
									 </select>
							</div>
								 
							<div class="col-md-12">
								<label class="form-label bold">Mata Pelajaran</label>
								<select name='mapel' id='mapel' class='form-select' required='true' style="width: 100%">
								   <option value=''>Pilih Mapel</option>
								<?php
									$mpl = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
									while ($mapel = mysqli_fetch_array($mpl)) { ?>
									<option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
									<?php } ?>
									 </select>
							</div>
								 <div class="col-md-12">
								<label class="form-label bold">No Urut Rapor</label>
								<input type="number" name="urut" class="form-control" required="true" >
							          </div>
									
								   <p>
								           <div class="d-grid gap-2">
                                         <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                                            </div>
										</form>
										</div>
									 </div>
					            </div>
								</div>
							</div>
						</div>
					</div>
				</div>

<div class="modal fade" id="modalGenerate" tabindex="-1" aria-labelledby="modalGenerateLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form id="form-generate" class="needs-validation" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="modalGenerateLabel">Generate Mapel dari Jadwal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-md-4">
              <label class="form-label bold">Tingkat</label>
              <select id="gen-level" class="form-select" required>
                <option value="">Pilih Tingkat</option>
                <option value="ALL">Semua</option>
                <?php
                $levGen = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='2' GROUP BY level");
                while ($lvl = mysqli_fetch_assoc($levGen)) {
                    echo "<option value='{$lvl['level']}'>{$lvl['level']}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label bold">Jurusan</label>
              <select id="gen-pk" class="form-select" required>
                <option value="">Pilih Jurusan</option>
                <option value="semua">Semua</option>
                <?php
                $jrGen = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
                while ($jr = mysqli_fetch_assoc($jrGen)) {
                    $namaJur = $jr['jurusan'];
                    echo "<option value='{$namaJur}'>{$namaJur}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label bold">Kurikulum</label>
              <select id="gen-kuri" class="form-select" required>
                <option value="">Pilih Kurikulum</option>
              </select>
            </div>
            <div class="col-12 text-end">
              <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-load-mapel">Muat Mapel</button>
            </div>
          </div>
          <div class="table-responsive" style="max-height:400px;overflow:auto;">
            <table class="table table-bordered table-sm align-middle" id="table-generate">
              <thead class="table-light">
                <tr>
                  <th style="width:40px;" class="text-center"><input type="checkbox" id="check-all-mapel"></th>
                  <th>Kode</th>
                  <th>Mata Pelajaran</th>
                  <th style="width:120px;">No Urut Rapor</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="4" class="text-center text-muted">Silakan pilih tingkat/jurusan lalu klik &quot;Muat Mapel&quot;.</td></tr>
              </tbody>
            </table>
          </div>
          <div id="generate-alert" class="mt-2"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Mapel</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="modalUrut" tabindex="-1" aria-labelledby="modalUrutLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUrutLabel">Kelola Data Mapel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive" style="max-height:380px;overflow:auto;">
          <table class="table table-bordered table-sm align-middle" id="table-urut-detail">
            <thead class="table-light">
              <tr>
                <th style="width:120px;">Tingkat</th>
                <th style="width:140px;">Jurusan</th>
                <th style="width:120px;">No Urut</th>
                <th style="width:160px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>
            </tbody>
          </table>
        </div>
        <div id="urut-alert" class="mt-2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEditMapel" tabindex="-1" aria-labelledby="modalEditMapelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-edit-mapel" class="needs-validation" novalidate>
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditMapelLabel">Edit Mapel Rapor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit-id">
          <div class="mb-2">
            <label class="form-label bold">Mata Pelajaran</label>
            <select name="mapel" id="edit-mapel" class="form-select" required style="width:100%">
              <option value="">Pilih Mapel</option>
              <?php
              $mplEdit = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran");
              while ($mapelEdit = mysqli_fetch_array($mplEdit)) { ?>
                  <option value="<?= $mapelEdit['id'] ?>"><?= $mapelEdit['nama_mapel'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">Tingkat</label>
            <select name="level" id="edit-level" class="form-select" required style="width:100%">
              <option value="">Pilih Tingkat</option>
              <?php
              $levEdit = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='2' GROUP BY level");
              while ($levelEdit = mysqli_fetch_array($levEdit)) { ?>
                  <option value="<?= $levelEdit['level'] ?>"><?= $levelEdit['level'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">Jurusan</label>
            <select name="pk" id="edit-pk" class="form-select" style="width:100%">
              <option value="">Tanpa Jurusan</option>
              <option value="semua">Semua</option>
              <?php
              $pkEdit = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
              while ($pkRow = mysqli_fetch_array($pkEdit)) { ?>
                  <option value="<?= $pkRow['jurusan'] ?>"><?= $pkRow['jurusan'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">Kurikulum</label>
            <select name="kuri" id="edit-kuri" class="form-select" required style="width:100%">
              <option value="">Pilih Kurikulum</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">No Urut Rapor</label>
            <input type="number" name="urut" id="edit-urut" class="form-control" min="0" required>
          </div>
          <div id="edit-alert" class="mt-2"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

				  <script>	
							$("#level").change(function() {
							var level = $(this).val();
        if (level === 'ALL') {
						$("#kuri").html("<option value='ALL'>Semua</option>").val('ALL').prop('disabled', false);
        } else if (level) {
								$("#kuri").prop('disabled', false);
								$.ajax({
									type: "POST",
									url: "jadwal/tmapel.php?pg=kuri", 
									data: "level=" + level, 
									success: function(response) { 
										$("#kuri").html(response);
									}
								});
							} else {
								$("#kuri").prop('disabled', false).html("<option value=''>Pilih Kurikulum</option>");
							}
							});
							</script>

<script>
$(document).on('change', '.urut-input', function(){
    var ids = $(this).data('ids');
    var urut = parseInt($(this).val(), 10);
    var $feedbackBox = $(this).closest('.modal').length ? $('#urut-alert') : $('#progressbox');
    if (isNaN(urut)) {
        urut = 0;
        $(this).val(urut);
    }
    if (!Array.isArray(ids)) {
        if (typeof ids === 'string' && ids.length) {
            ids = ids.split(',').map(function(item){
                return item.trim();
            }).filter(Boolean);
        } else if (ids !== undefined && ids !== null) {
            ids = [ids];
        } else {
            ids = [];
        }
    }
    ids = ids.map(function(item){
        var val = parseInt(item, 10);
        return isNaN(val) ? 0 : val;
    }).filter(function(val){
        return val > 0;
    });
    if (ids.length === 0) {
        $feedbackBox.html('<div class="alert alert-danger py-1 mb-0">ID mapel tidak ditemukan.</div>');
        setTimeout(function(){ $feedbackBox.html(''); }, 3000);
        return;
    }
    $.post('jadwal/tmapel.php?pg=update_urut', {ids: ids, urut: urut}, function(res){
        if (res && res.status === 'ok') {
            $feedbackBox.html('<div class="alert alert-success py-1 mb-0">Nomor urut diperbarui.</div>');
        } else {
            var message = (res && res.message) ? res.message : 'Gagal memperbarui nomor urut.';
            $feedbackBox.html('<div class="alert alert-danger py-1 mb-0">'+ message +'</div>');
        }
        setTimeout(function(){ $feedbackBox.html(''); }, 3000);
    }, 'json');
});

var modalGenerate = $('#modalGenerate');
var modalUrut = $('#modalUrut');
var modalEditMapel = $('#modalEditMapel');
var editForm = $('#form-edit-mapel');
var editAlert = $('#edit-alert');
var urutAlert = $('#urut-alert');
var currentEditEntry = null;

$('#btn-generate').on('click', function(){
    $('#form-generate')[0].reset();
    $('#gen-kuri').html('<option value="">Pilih Kurikulum</option>');
    $('#table-generate tbody').html('<tr><td colspan="4" class="text-center text-muted">Silakan pilih tingkat/jurusan lalu klik "Muat Mapel".</td></tr>');
    $('#generate-alert').html('');
    modalGenerate.modal('show');
});

$('.btn-manage-urut').on('click', function(){
    var payload = $(this).data('mapel');
    if (typeof payload === 'string') {
        try { payload = JSON.parse(payload); } catch (e) { payload = null; }
    }
    if (!payload || !payload.items) {
        $('#table-urut-detail tbody').html('<tr><td colspan="4" class="text-center text-muted">Data tidak tersedia.</td></tr>');
        modalUrut.modal('show');
        return;
    }
    $('#modalUrutLabel').text('Kelola Nomor Urut - ' + (payload.mapel_name || 'Mapel'));
    var rows = '';
    payload.items.forEach(function(item){
        var itemIdsAttr = JSON.stringify([item.id]);
        var entryAttr = encodeURIComponent(JSON.stringify({
            id: item.id,
            mapel: item.mapel,
            mapel_name: payload.mapel_name || '',
            tingkat: item.tingkat,
            pk: item.pk,
            kurikulum: item.kurikulum,
            urut: item.urut
        }));
        var pkLabel = item.pk && item.pk.length ? item.pk : '-';
        rows += '<tr>'
              + '<td>'+ item.tingkat +'</td>'
              + '<td>'+ pkLabel +'</td>'
              + '<td><input type="number" class="form-control form-control-sm text-center urut-input" data-ids=\''+ itemIdsAttr +'\' value="'+item.urut+'" min="0"></td>'
              + '<td class="text-center">'
              +   '<button type="button" class="btn btn-sm btn-outline-primary me-2 btn-edit-mapel" data-entry=\''+ entryAttr +'\'><i class="material-icons" style="font-size:16px;vertical-align:middle;">edit</i></button>'
              +   '<button type="button" class="btn btn-link text-danger p-0 btn-delete-mapel" data-id="'+item.id+'"><i class="material-icons" style="font-size:16px;">delete</i></button>'
              + '</td>'
              + '</tr>';
    });
    if (!rows) {
        rows = '<tr><td colspan="4" class="text-center text-muted">Tidak ada data.</td></tr>';
    }
    $('#table-urut-detail tbody').html(rows);
    urutAlert.html('');
    modalUrut.modal('show');
});

function ensureOption($select, value, label) {
    var val = value === null || value === undefined ? '' : String(value);
    var display = label !== undefined && label !== null && String(label).length ? label : (val.length ? val : '-');
    if (!$select.find('option').filter(function(){ return $(this).val() === val; }).length) {
        var option = $('<option/>').val(val).text(display);
        $select.append(option);
    }
}

function loadEditKurikulum(level, selectedValue) {
    var $select = $('#edit-kuri');
    if (!level || level === 'ALL') {
        $select.html('<option value="">Pilih Kurikulum</option>');
        if (selectedValue) {
            ensureOption($select, selectedValue, selectedValue);
            $select.val(String(selectedValue));
        }
        return;
    }
    $select.prop('disabled', true).html('<option value="">Memuat...</option>');
    $.post('jadwal/tmapel.php?pg=kuri', {level: level}, function(res){
        var html = res && res.length ? res : '<option value="">Pilih Kurikulum</option>';
        $select.html(html);
        if (selectedValue !== undefined && selectedValue !== null) {
            ensureOption($select, selectedValue, selectedValue);
            $select.val(String(selectedValue));
        }
        $select.prop('disabled', false);
    }).fail(function(){
        $select.html('<option value="">Pilih Kurikulum</option>').prop('disabled', false);
        if (selectedValue) {
            ensureOption($select, selectedValue, selectedValue);
            $select.val(String(selectedValue));
        }
    });
}

$(document).on('click', '.btn-edit-mapel', function(){
    var raw = $(this).attr('data-entry') || '';
    var entry = null;
    try { raw = decodeURIComponent(raw); } catch (e) {
        raw = '';
    }
    try { entry = JSON.parse(raw); } catch (e) {
        entry = null;
    }
    if (!entry) {
        urutAlert.html('<div class="alert alert-danger py-1 mb-0">Data mapel tidak valid untuk diedit.</div>');
        setTimeout(function(){ urutAlert.html(''); }, 3000);
        return;
    }
    currentEditEntry = entry;
    editForm[0].reset();
    editAlert.html('');
    $('#edit-id').val(entry.id);
    ensureOption($('#edit-mapel'), entry.mapel, entry.mapel_name || 'Mapel');
    $('#edit-mapel').val(String(entry.mapel));
    ensureOption($('#edit-level'), entry.tingkat, entry.tingkat);
    $('#edit-level').val(entry.tingkat);
    ensureOption($('#edit-pk'), entry.pk || '', entry.pk && entry.pk.length ? entry.pk : '-');
    $('#edit-pk').val(entry.pk || '');
    $('#edit-urut').val(entry.urut);
    loadEditKurikulum(entry.tingkat, entry.kurikulum);
    modalEditMapel.modal('show');
});

$('#edit-level').on('change', function(){
    loadEditKurikulum($(this).val(), '');
});

editForm.on('submit', function(e){
    e.preventDefault();
    var formData = editForm.serialize();
    editAlert.html('<div class="alert alert-info py-1 mb-0">Menyimpan perubahan...</div>');
    $.ajax({
        url: 'jadwal/tmapel.php?pg=edit_mapel',
        method: 'POST',
        dataType: 'json',
        data: formData,
        success: function(res){
            if (res && res.status === 'ok') {
                editAlert.html('<div class="alert alert-success py-1 mb-0">'+ res.message +'</div>');
                setTimeout(function(){ window.location.reload(); }, 1200);
            } else {
                var message = (res && res.message) ? res.message : 'Gagal memperbarui data.';
                editAlert.html('<div class="alert alert-danger py-1 mb-0">'+ message +'</div>');
            }
        },
        error: function(){
            editAlert.html('<div class="alert alert-danger py-1 mb-0">Terjadi kesalahan saat menyimpan.</div>');
        }
    });
});

$('#gen-level').on('change', function(){
    var level = $(this).val();
    if (level === 'ALL') {
        $('#gen-kuri').html('<option value="ALL">Semua</option>').prop('disabled', false);
    } else if (level) {
        $.post('jadwal/tmapel.php?pg=kuri', {level: level}, function(res){
            $('#gen-kuri').html(res).prop('disabled', false);
        });
    } else {
        $('#gen-kuri').html('<option value="">Pilih Kurikulum</option>').prop('disabled', false);
    }
});

$('#btn-load-mapel').on('click', function(){
    var level = $('#gen-level').val();
    var pk    = $('#gen-pk').val();
    if (!level || !pk) {
        $('#generate-alert').html('<div class="alert alert-warning py-1">Pilih tingkat dan jurusan terlebih dahulu.</div>');
        return;
    }
    $('#generate-alert').html('');
    $('#table-generate tbody').html('<tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>');
    $.ajax({
        url: 'jadwal/tmapel.php?pg=list_jadwal',
        method: 'POST',
        dataType: 'json',
        data: {level: level, pk: pk, kuri: $('#gen-kuri').val()},
        success: function(res){
            if (!res || res.status !== 'ok') {
                $('#table-generate tbody').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>');
                return;
            }
            var rows = '';
            if (!res.data || res.data.length === 0) {
                rows = '<tr><td colspan="4" class="text-center text-muted">Tidak ada data mapel dari jadwal.</td></tr>';
            } else {
                var start = parseInt(res.start, 10) || 1;
                res.data.forEach(function(item, idx){
                    var urut = start + idx;
                    var disabled = item.exists ? 'disabled' : '';
                    var checked = item.exists ? '' : 'checked';
                    var note = item.exists ? '<small class="text-muted">Sudah terdaftar</small>' : '';
                    rows += '<tr data-id="'+item.id+'">'
                        + '<td class="text-center"><input type="checkbox" class="form-check-input chk-mapel" '+checked+' '+disabled+'></td>'
                        + '<td>'+ (item.kode || '') +'</td>'
                        + '<td>'+ item.nama +'<br>'+note+'</td>'
                        + '<td><input type="number" class="form-control form-control-sm text-center input-urut" value="'+urut+'" min="0" '+disabled+'></td>'
                        + '</tr>';
                });
            }
            $('#table-generate tbody').html(rows);
            $('#check-all-mapel').prop('checked', true);
        },
        error: function(){
            $('#table-generate tbody').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data.</td></tr>');
        }
    });
});

$('#check-all-mapel').on('change', function(){
    var checked = $(this).is(':checked');
    $('#table-generate tbody .chk-mapel').prop('checked', checked);
});

$('#form-generate').on('submit', function(e){
    e.preventDefault();
    var level = $('#gen-level').val();
    var pk    = $('#gen-pk').val();
    var kuri  = $('#gen-kuri').val();
    if (!level || !pk || !kuri) {
        $('#generate-alert').html('<div class="alert alert-warning py-1">Lengkapi pilihan tingkat, jurusan, dan kurikulum.</div>');
        return;
    }
    var mapels = [];
    $('#table-generate tbody tr').each(function(){
        var chk = $(this).find('.chk-mapel');
        if (chk.length && chk.is(':checked')) {
            var id = $(this).data('id');
            var urut = parseInt($(this).find('.input-urut').val(), 10) || 0;
            mapels.push({id: id, urut: urut});
        }
    });
    if (mapels.length === 0) {
        $('#generate-alert').html('<div class="alert alert-warning py-1">Tidak ada mapel yang dipilih.</div>');
        return;
    }
    $('#generate-alert').html('<div class="alert alert-info py-1">Menyimpan...</div>');
    $.ajax({
        url: 'jadwal/tmapel.php?pg=generate_bulk',
        method: 'POST',
        dataType: 'json',
        data: {payload: JSON.stringify({level: level, pk: pk, kuri: kuri, mapels: mapels})},
        success: function(res){
            if (res && res.status === 'ok') {
                $('#generate-alert').html('<div class="alert alert-success py-1">'+ res.message +' (ditambah: '+ res.inserted +')</div>');
                setTimeout(function(){ window.location.reload(); }, 1200);
            } else {
                $('#generate-alert').html('<div class="alert alert-danger py-1">'+ (res && res.message ? res.message : 'Gagal menyimpan.') +'</div>');
            }
        },
        error: function(){
            $('#generate-alert').html('<div class="alert alert-danger py-1">Terjadi kesalahan saat menyimpan.</div>');
        }
    });
});
</script>
							<script>
						$('#formjadwal').submit(function(e) {
								e.preventDefault();
								var data = new FormData(this);
								$.ajax({
									type: 'POST',
									 url: 'jadwal/tmapel.php?pg=mapel',
									enctype: 'multipart/form-data',
									data: data,
									cache: false,
									contentType: false,
									processData: false,
									beforeSend: function() {
									$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
									$('.progress-bar').animate({
									width: "30%"
									}, 500);
									},
									success: function(data) {
									   
										setTimeout(function() {
											window.location.reload();
										}, 1500);
									}
								})
								return false;
							});
							</script>

	
					  <?php endif ?>
					  
	  
					  
									<script>
$(document).on('click', '.btn-delete-mapel', function() {
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
											   url: 'jadwal/tmapel.php?pg=hapus',
												method: "POST",
												data: 'id=' + id,
												success: function(data) {
												$('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
												$('.progress-bar').animate({
												width: "30%"
												}, 500);	
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
					  
<script>

  $(document).ready(function() {
      
    $(function() {

      $('.sikap2').hide();

      $('.kuri').change(function() {

        if ($("option[value='1']").is(":checked")) {

          $('.sikap2').show();

        } else {

          $('.sikap2').hide();

        }

      });

    });
    
  });
  
</script>		
