<?php
// --- LOGIKA PAGINASI DAN FILTER ---
$per_page_options = [20, 50, 100, 150];
$per_page = isset($_GET['per_page']) && in_array($_GET['per_page'], $per_page_options) ? (int)$_GET['per_page'] : 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

$selected_tahun = $_GET['tahun_lulus'] ?? '';
$pg_param = $_GET['pg'] ?? '';

$count_sql = "SELECT COUNT(*) as total FROM alumni";
$where_clauses = [];
if (!empty($selected_tahun)) {
    $where_clauses[] = "alumni.tahun_lulus = '" . mysqli_real_escape_string($koneksi, $selected_tahun) . "'";
}
if (!empty($where_clauses)) {
    $count_sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$count_result = mysqli_query($koneksi, $count_sql);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $per_page);
$offset = ($current_page - 1) * $per_page;

// Ambil kolom 'transkrip' juga
$sql = "
    SELECT 
        alumni.nis, 
        alumni.nama, 
        alumni.ijazah, 
        alumni.transkrip, 
        alumni.tahun_lulus
    FROM 
        alumni
";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY alumni.nama ASC LIMIT {$per_page} OFFSET {$offset}";
$query = mysqli_query($koneksi, $sql);
?>

<div class="row">
    <!-- BAGIAN KIRI: DAFTAR DOKUMEN -->
    <div class='col-md-8'>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">DAFTAR DOKUMEN ALUMNI</h5>
                <div class="d-flex align-items-center">
                    <form action="" method="GET" class="d-flex align-items-center me-3">
                        <input type="hidden" name="pg" value="<?= htmlspecialchars($pg_param); ?>">
                        <input type="hidden" name="tahun_lulus" value="<?= htmlspecialchars($selected_tahun); ?>">
                        <label for="per_page" class="form-label me-2 mb-0">Data:</label>
                        <select name="per_page" id="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <?php foreach ($per_page_options as $option): ?>
                                <option value="<?= $option; ?>" <?= $per_page == $option ? 'selected' : ''; ?>><?= $option; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                    <form action="" method="GET" class="d-flex align-items-center">
                        <input type="hidden" name="pg" value="<?= htmlspecialchars($pg_param); ?>">
                        <select name="tahun_lulus" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            <?php
                            $query_tahun = mysqli_query($koneksi, "SELECT DISTINCT tahun_lulus FROM alumni WHERE tahun_lulus IS NOT NULL AND tahun_lulus != '' ORDER BY tahun_lulus DESC");
                            while ($tahun_data = mysqli_fetch_assoc($query_tahun)) {
                                $tahun = htmlspecialchars($tahun_data['tahun_lulus']);
                                $selected = ($tahun == $selected_tahun) ? 'selected' : '';
                                echo "<option value='{$tahun}' {$selected}>{$tahun}</option>";
                            }
                            ?>
                        </select>
                        <?php if (!empty($selected_tahun)): ?>
                            <a href="?pg=<?= htmlspecialchars($pg_param); ?>" class="btn btn-sm btn-secondary">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class='table-responsive'>
                    <table class='table table-striped'>
                        <thead>
                            <tr>
                                <th>NO.</th>
                                <th>NAMA LENGKAP</th>
                                <th>TAHUN LULUS</th>
                                <!-- MODIFIKASI: Kolom Aksi dipecah -->
                                <th class="text-center">IJAZAH</th>
                                <th class="text-center">TRANSKRIP NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $folder_path_server = "../images/ijazahsiswa/";
                            $transkrip_path_server = "../images/transkripsiswa/";
                            $folder_path_browser = "../images/ijazahsiswa/";
                            $transkrip_path_browser = "../images/transkripsiswa/";
                            $no = $offset + 1;

                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) :
                                    $ijazah_exists = !empty($data['ijazah']) && file_exists($folder_path_server . $data['ijazah']);
                                    $transkrip_exists = !empty($data['transkrip']) && file_exists($transkrip_path_server . $data['transkrip']);
                            ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($data['nama']); ?></td>
                                        <td><?= htmlspecialchars($data['tahun_lulus'] ?? 'Data Kosong'); ?></td>
                                        
                                        <!-- KOLOM AKSI IJAZAH -->
                                        <td class="text-center">
                                            <?php if ($ijazah_exists): ?>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-info view-btn" data-src="<?= $folder_path_browser . rawurlencode($data['ijazah']); ?>" data-nama="<?= htmlspecialchars($data['nama']); ?>" title="Lihat Ijazah"><i class="material-icons" style="font-size:16px; vertical-align:middle;">visibility</i></button>
                                                    <button type="button" class="btn btn-danger delete-btn" data-nis="<?= htmlspecialchars($data['nis']); ?>" data-nama="<?= htmlspecialchars($data['nama']); ?>" data-type="ijazah" title="Hapus Ijazah"><i class="material-icons" style="font-size:16px; vertical-align:middle;">delete</i></button>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum Ada</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- KOLOM AKSI TRANSKRIP -->
                                        <td class="text-center">
                                            <?php if ($transkrip_exists): ?>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-info view-btn" data-src="<?= $transkrip_path_browser . rawurlencode($data['transkrip']); ?>" data-nama="<?= htmlspecialchars($data['nama']); ?>" title="Lihat Transkrip"><i class="material-icons" style="font-size:16px; vertical-align:middle;">visibility</i></button>
                                                    <button type="button" class="btn btn-danger delete-btn" data-nis="<?= htmlspecialchars($data['nis']); ?>" data-nama="<?= htmlspecialchars($data['nama']); ?>" data-type="transkrip" title="Hapus Transkrip"><i class="material-icons" style="font-size:16px; vertical-align:middle;">delete</i></button>
                                                </div>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum Ada</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            } else {
                                echo '<tr><td colspan="5" class="text-center">Tidak ada data alumni yang cocok dengan filter yang dipilih.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end align-items-center mt-3">
                    <nav>
                        <ul class="pagination mb-0">
                            <?php
                            if ($current_page > 1) echo "<li class='page-item'><a class='page-link' href='?pg={$pg_param}&tahun_lulus={$selected_tahun}&per_page={$per_page}&page=" . ($current_page - 1) . "'>&laquo;</a></li>";
                            for ($i = 1; $i <= $total_pages; $i++) echo "<li class='page-item " . ($i == $current_page ? 'active' : '') . "'><a class='page-link' href='?pg={$pg_param}&tahun_lulus={$selected_tahun}&per_page={$per_page}&page={$i}'>{$i}</a></li>";
                            if ($current_page < $total_pages) echo "<li class='page-item'><a class='page-link' href='?pg={$pg_param}&tahun_lulus={$selected_tahun}&per_page={$per_page}&page=" . ($current_page + 1) . "'>&raquo;</a></li>";
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- BAGIAN KANAN: FORM UPLOAD -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">UPLOAD DOKUMEN</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Pastikan nama file adalah <strong>NAMA LENGKAP</strong> alumni.
                    Contoh: <code>AJI BAGASKORO.pdf</code>
                </p>
                <hr>
                <form id='formDokumen' enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="doc_type" class="form-label">Jenis Dokumen</label>
                        <select name="doc_type" id="doc_type" class="form-select" required>
                            <option value="ijazah">Ijazah</option>
                            <option value="transkrip">Transkrip Nilai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file-upload" class="form-label">Pilih File</label>
                        <input type='file' name='files[]' id="file-upload" class='form-control' required='true' multiple accept=".pdf,.jpg,.jpeg,.png" />
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="material-icons" style="font-size: 18px; vertical-align: bottom;">upload</i> Upload
                        </button>
                    </div>
                </form>
                <div id="progressbox" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UNTUK MELIHAT DOKUMEN -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Tampilan Dokumen</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="pdf-container"></div>
       <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus <strong id="delete-doc-type"></strong> milik <strong id="delete-nama-alumni"></strong>?
        <input type="hidden" id="nis-to-delete" value="">
        <input type="hidden" id="type-to-delete" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-btn">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Fungsi untuk submit form upload
    $('#formDokumen').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        data.append('action', 'upload'); 
        
        $.ajax({
            type: 'POST',
            url: 'ijazah/tijazah.php',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        $('#progressbox').html(
                            '<div class="progress">' +
                            '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: ' + percentComplete + '%;" aria-valuenow="' + percentComplete + '" aria-valuemin="0" aria-valuemax="100">' + percentComplete + '%</div>' +
                            '</div>' +
                            '<p class="text-center mt-1">Sedang mengunggah file...</p>'
                        );
                    }
                }, false);
                return xhr;
            },
            beforeSend: function() {
                $('#progressbox').html('<div class="alert alert-info">Mempersiapkan unggahan...</div>');
            },
            success: function(response) {
                var resultMessage = response.replace(/\n/g, '<br>');
                var alertClass = response.includes('ERROR:') ? 'alert-danger' : 'alert-success';
                $('#progressbox').html('<div class="alert ' + alertClass + '">' + resultMessage + '</div>');
                setTimeout(function() { window.location.reload(); }, 5000);
            },
            error: function() {
                $('#progressbox').html('<div class="alert alert-danger">Gagal! Terjadi kesalahan koneksi.</div>');
                setTimeout(function() { window.location.reload(); }, 3000);
            }
        });
    });

    // --- LOGIKA LIHAT DOKUMEN ---
    $('.view-btn').on('click', function() {
        var pdf_url = $(this).data('src');
        var nama_siswa = $(this).data('nama');
        $('#viewModalLabel').text('Dokumen - ' + nama_siswa);
        var pdfObject = $('<object>').attr('data', pdf_url).attr('type', 'application/pdf').css({'width': '100%','height': '75vh'});
        pdfObject.append('<p>Browser tidak mendukung pratinjau PDF.</p>');
        $('#pdf-container').html(pdfObject);
        $('#viewModal').modal('show');
    });

    // --- LOGIKA HAPUS DOKUMEN ---
    $('.delete-btn').on('click', function() {
        var nis = $(this).data('nis');
        var nama = $(this).data('nama');
        var type = $(this).data('type');
        $('#nis-to-delete').val(nis);
        $('#type-to-delete').val(type);
        $('#delete-nama-alumni').text(nama);
        $('#delete-doc-type').text(type === 'ijazah' ? 'Ijazah' : 'Transkrip Nilai');
        $('#deleteModal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        var nis = $('#nis-to-delete').val();
        var type = $('#type-to-delete').val();
        
        $.ajax({
            type: 'POST',
            url: 'ijazah/tijazah.php',
            data: { 
                action: 'delete',
                nis: nis,
                doc_type: type
            },
            dataType: 'json',
            beforeSend: function() {
                $('#confirm-delete-btn').prop('disabled', true).text('Menghapus...');
            },
            success: function(response) {
                if(response.status == 'success') {
                    $('#deleteModal').modal('hide');
                    alert('Dokumen berhasil dihapus.');
                    window.location.reload();
                } else {
                    alert('Gagal menghapus: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan koneksi.');
            },
            complete: function() {
                $('#confirm-delete-btn').prop('disabled', false).text('Ya, Hapus');
            }
        });
    });
});
</script>
