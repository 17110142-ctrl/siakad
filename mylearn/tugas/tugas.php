<?php defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!'); ?>

<?php if ($ac == '') { ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card">
                <div class="card-header">
                    <h5 class='card-title'>TUGAS BELAJAR</h5>
                    <div class='pull-right'>
                        <a href="?pg=<?= enkripsi('inputtugas') ?>" class='btn btn-primary'><i class="material-icons">add</i> Tambah Tugas</a>
                        <a href="." class='btn btn-outline-danger'>Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id='tablemateri' class='table-responsive'>
                        <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                            <thead>
                                <tr>
                                    <th width='5px'>no.</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Judul Tugas</th>
                                    <th>Tgl Mulai</th>
                                    <th>Tgl Selesai</th>
                                    <th>Kelas</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($user['level'] == 'guru') {
                                    $tugasQ = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_guru='$_SESSION[id_user]' ORDER BY tgl_mulai DESC");
                                } else {
                                    $tugasQ = mysqli_query($koneksi, "SELECT * FROM tugas ORDER BY tgl_mulai DESC");
                                }
                                $no = 0;
                                while ($tugas = mysqli_fetch_array($tugasQ)) :
                                    $no++;
                                    $guru = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$tugas[id_guru]'"));
                                    $mpl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE kode='$tugas[mapel]'"));
                                ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $mpl['nama_mapel'] ?></td>
                                        <td><?= $tugas['judul'] ?></td>
                                        <td style="text-align:center"><?= date('d-m-Y H:i', strtotime($tugas['tgl_mulai'])) ?></td>
                                        <td style="text-align:center"><?= date('d-m-Y H:i', strtotime($tugas['tgl_selesai'])) ?></td>
                                        <td style="text-align:center">
                                            <?php
                                            $kelas_tugas = unserialize($tugas['kelas']);
                                            if ($kelas_tugas) {
                                                foreach ($kelas_tugas as $kelas) {
                                                    echo "<span class='badge bg-primary me-1'>$kelas</span>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td style="text-align:center">
                                            <?php if ($tugas['file']) : ?>
                                                <a href="../tugas/<?= $tugas['file'] ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align:center">
                                            <div class='btn-group'>
                                                <a href='?pg=<?= enkripsi('tugas') ?>&ac=<?= enkripsi('jawaban') ?>&id=<?= $tugas['id_tugas'] ?>' class='btn btn-sm btn-success'><i class='material-icons'>check</i> Nilai</a>
                                                <a href='?pg=<?= enkripsi('tugas') ?>&ac=<?= enkripsi('edit') ?>&id=<?= $tugas['id_tugas'] ?>' class='btn btn-primary btn-sm'>
                                                    <i class="material-icons">edit</i> Edit
                                                </a>
                                                <button data-id='<?= $tugas['id_tugas'] ?>' class="hapus btn btn-danger btn-sm"><i class="material-icons">delete</i></button>
                                            </div>
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
<?php } elseif ($ac == enkripsi('jawaban')) {
    $id_tugas = $_GET['id'];
?>
    <div class='row'>
        <div class='col-md-12'>
            <div class="card">
                <div class="card-header">
                    <h5 class='card-title'>DAFTAR JAWABAN SISWA</h5>
                    <div class='pull-right'>
                        <button class='btn btn-primary' onclick="frames['frameresult'].print()"><i class='material-icons'>print</i> Cetak Nilai</button>
                        <a href="?pg=<?= enkripsi('tugas') ?>" class='btn btn-outline-danger'>Kembali ke Daftar Tugas</a>
                    </div>
                </div>
                <div class='card-body' id="tablejawaban">
                    <div class='table-responsive'>
                        <table id="datatable-jawaban" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Siswa</th>
                                    <th width="10%">Kelas</th>
                                    <th>Jawaban Teks</th>
                                    <th width="10%">File Jawaban</th>
                                    <th width="5%">Nilai</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Tampilkan semua siswa pada kelas tugas (left join jawaban_tugas)
                                $tugas_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='" . mysqli_real_escape_string($koneksi, $id_tugas) . "'"));
                                // Robust parse kelas: serialized, JSON, CSV
                                $kelas_list = [];
                                $raw_kelas = $tugas_row['kelas'] ?? '';
                                if (is_string($raw_kelas) && $raw_kelas !== '') {
                                    $tmp = @unserialize($raw_kelas);
                                    if (is_array($tmp)) {
                                        $kelas_list = array_values(array_filter($tmp));
                                    } else {
                                        $tmp = json_decode($raw_kelas, true);
                                        if (is_array($tmp)) {
                                            $kelas_list = array_values(array_filter($tmp));
                                        } else {
                                            $kelas_list = array_values(array_filter(array_map('trim', explode(',', $raw_kelas))));
                                        }
                                    }
                                }
                                $no = 0;
                                if (!empty($kelas_list)) {
                                    // Build IN list safely
                                    $kelas_esc = array_map(function($v) use ($koneksi){ return "'" . mysqli_real_escape_string($koneksi, $v) . "'"; }, $kelas_list);
                                    $in_kelas = implode(',', $kelas_esc);
                                    $id_tugas_i = (int)$id_tugas;
                                    // Group order: 0=submitted belum dinilai, 1=submitted dinilai, 2=belum submit
                                    $sql = "SELECT s.id_siswa, s.nama, s.kelas, jt.id_jawaban, jt.jawaban, jt.file, COALESCE(jt.nilai, 0) AS nilai,
                                                    CASE 
                                                      WHEN jt.id_jawaban IS NOT NULL AND (COALESCE(jt.jawaban,'')<>'' OR COALESCE(jt.file,'')<>'') AND (jt.nilai IS NULL OR jt.nilai='' OR jt.nilai='0' OR jt.nilai=0) THEN 0
                                                      WHEN jt.id_jawaban IS NOT NULL AND (COALESCE(jt.jawaban,'')<>'' OR COALESCE(jt.file,'')<>'') THEN 1
                                                      ELSE 2
                                                    END AS grp
                                            FROM siswa s
                                            LEFT JOIN jawaban_tugas jt 
                                              ON jt.id_jawaban = (
                                                  SELECT MAX(jt2.id_jawaban) 
                                                  FROM jawaban_tugas jt2 
                                                  WHERE jt2.id_tugas = $id_tugas_i AND jt2.id_siswa = s.id_siswa
                                              )
                                            WHERE s.kelas IN ($in_kelas)
                                            ORDER BY grp ASC, s.nama ASC";
                                    $res = mysqli_query($koneksi, $sql);
                                    while ($row = ($res instanceof mysqli_result) ? mysqli_fetch_assoc($res) : null) {
                                        $no++;
                                        $id_jawaban = $row['id_jawaban'] ?? null;
                                        $nilai = (int)($row['nilai'] ?? 0);
                                        $jaw_text = trim((string)($row['jawaban'] ?? ''));
                                        $file_jwb = trim((string)($row['file'] ?? ''));
                                ?>
                                    <tr id="jawaban-row-<?= (int)($id_jawaban ?? 0) ?>">
                                        <td><?= $no ?></td>
                                        <td><?= htmlspecialchars($row['nama']) ?></td>
                                        <td><?= htmlspecialchars($row['kelas']) ?></td>
                                        <td><?= $jaw_text !== '' ? nl2br(htmlspecialchars($jaw_text)) : '<span class="text-muted">Belum mengumpulkan</span>' ?></td>
                                        <td class="text-center">
                                            <?php if ($file_jwb !== '') : ?>
                                                <a href="<?= $homeurl ?>/tugas/<?= htmlspecialchars($file_jwb) ?>" class="btn btn-sm btn-success" target="_blank"><i class="material-icons">search</i> Lihat</a>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge nilai-badge bg-<?= ($nilai > 0) ? 'success' : 'warning'; ?>"><?= $nilai ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($id_jawaban && ($jaw_text !== '' || $file_jwb !== '')) : ?>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm btn-nilai" data-bs-toggle="modal" data-bs-target="#modalnilai<?= $id_jawaban ?>">
                                                    <i class="material-icons">add</i> Nilai
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalHapusJawaban<?= $id_jawaban ?>">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>
                                            <?php else: ?>
                                                <span class="text-muted">Belum mengumpulkan tugas</span>
                                            <?php endif; ?>
                                         </td>
                                    </tr>
                                    <?php if ($id_jawaban): ?>
                                    <div class="modal fade" id="modalnilai<?= $id_jawaban ?>" tabindex="-1" role="dialog" aria-labelledby="modalNilaiLabel<?= $id_jawaban ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Input Nilai: <?= htmlspecialchars($row['nama']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="form-nilai-tugas">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_jawaban" value="<?= $id_jawaban ?>">

                                                        <?php
                                                        // NEW: Preview area for uploaded file (image/pdf/other) with zoom & rotate controls
                                                        if (!empty($file_jwb)) {
                                                            $file_url = htmlspecialchars($homeurl . '/tugas/' . $file_jwb);
                                                            $ext = strtolower(pathinfo($file_jwb, PATHINFO_EXTENSION));

                                                            // Build controls dynamically: rotate left/right + zoom + reset + buka penuh
                                                            $controls = '<div class="zoom-controls mb-2">'
                                                                      . '<button type="button" class="btn btn-sm btn-outline-secondary rotate-left" title="Putar Kiri">⟲</button> '
                                                                      . '<button type="button" class="btn btn-sm btn-outline-secondary rotate-right" title="Putar Kanan">⟳</button> '
                                                                      . '<button type="button" class="btn btn-sm btn-outline-secondary zoom-out" title="Zoom Out">-</button> '
                                                                      . '<button type="button" class="btn btn-sm btn-outline-secondary zoom-in" title="Zoom In">+</button> '
                                                                      . '<button type="button" class="btn btn-sm btn-outline-secondary zoom-reset" title="Reset">Reset</button> '
                                                                      . '<a href="' . $file_url . '" target="_blank" class="btn btn-sm btn-outline-primary ms-2" title="Buka penuh">Buka penuh</a>'
                                                                      . '</div>';

                                                            // initialize scale, rotate and translate attributes
                                                            $wrapper_start = '<div class="mb-3 file-preview text-center" data-scale="1" data-rotate="0" data-tx="0" data-ty="0" style="--scale:1;">' . $controls . '<div class="zoom-content" style="overflow:auto;position:relative;">';
                                                            $wrapper_end = '</div></div>';

                                                            // Image types -> wrap media inside .zoom-inner for transforms
                                                            if (in_array($ext, ['jpg','jpeg','png','gif','webp','bmp'])) {
                                                                echo $wrapper_start;
                                                                echo '<div class="zoom-inner" style="display:inline-block;"><img src="' . $file_url . '" alt="Preview ' . htmlspecialchars($file_jwb) . '" class="zoom-img" style="max-width:100%;max-height:320px;display:block;border:1px solid #ddd;"></div>';
                                                                echo $wrapper_end;
                                                            }
                                                            // PDF -> iframe inside .zoom-inner
                                                            elseif ($ext === 'pdf') {
                                                                echo $wrapper_start;
                                                                echo '<div class="zoom-inner" style="display:inline-block;"><iframe src="' . $file_url . '" class="zoom-frame" style="width:100%;height:420px;border:1px solid #ddd;"></iframe></div>';
                                                                echo $wrapper_end;
                                                            }
                                                            // Other files: provide a download/open link (no zoom/rotate)
                                                            else {
                                                                echo '<div class="mb-3 file-preview">';
                                                                echo '<a href="' . $file_url . '" target="_blank" class="btn btn-sm btn-outline-primary">Download / Buka file: ' . htmlspecialchars($file_jwb) . '</a>';
                                                                echo '</div>';
                                                            }
                                                        }
                                                        ?>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Nilai (0-100)</label>
                                                            <input type="number" class="form-control" name="nilai" required min="0" max="100" value="<?= $nilai ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Catatan Guru (Opsional)</label>
                                                            <textarea class="form-control" name="catatan" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type='submit' class='btn btn-primary'>Simpan Nilai</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="modalHapusJawaban<?= $id_jawaban ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel<?= $id_jawaban ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Jawaban: <?= htmlspecialchars($row['nama']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="form-hapus-jawaban">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id" value="<?= $id_jawaban ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Catatan / Alasan Penghapusan</label>
                                                            <textarea class="form-control" name="catatan" rows="3" required placeholder="Contoh: Jawaban tidak sesuai, silakan unggah ulang file yang benar."></textarea>
                                                            <small class="form-text text-muted">Catatan ini akan dikirimkan ke siswa melalui WhatsApp.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type='submit' class='btn btn-danger'>Hapus dan Kirim Notifikasi</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php }
                                    } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <iframe id='loadframe' name='frameresult' src='tugas/print_jawaban.php?id=<?= $id_tugas ?>' style='display:none'></iframe>
<?php } elseif ($ac == enkripsi('edit')) {
    $id_tugas = $_GET['id'];
    $tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='$id_tugas'"));
    if ($tugas) {
        $kelas_terpilih = unserialize($tugas['kelas']);
?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">EDIT TUGAS BELAJAR</h5>
                     <div class='pull-right'>
                        <a href="?pg=<?= enkripsi('tugas') ?>" class='btn btn-outline-danger'>Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="formEditTugas" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $tugas['id_tugas'] ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran</label>
                            <select name='mapel' id='mapel_select_edit' class='form-select' required>
                                <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC"); ?>
                                <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                    <option value="<?= $mapel['kode'] ?>" <?= ($tugas['mapel'] == $mapel['kode']) ? 'selected' : '' ?>>
                                        <?= $mapel['nama_mapel'] ?>
                                    </option>
                                <?php endwhile ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tautkan pada Materi (Opsional)</label>
                            <select name='id_materi' id='materi_select_edit' class='form-select'>
                                <option value=''>-- Tidak Ditautkan --</option>
                                <?php
                                $materi_query = mysqli_query($koneksi, "SELECT * FROM materi WHERE mapel = '" . $tugas['mapel'] . "' ORDER BY judul ASC");
                                while ($materi = mysqli_fetch_array($materi_query)) :
                                    $selected = ($tugas['id_materi'] == $materi['id_materi']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $materi['id_materi'] ?>" <?= $selected ?>><?= $materi['judul'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Tugas</label>
                            <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Isi Tugas</label>
                            <textarea name='isitugas' id='isitugas-editor' class='editor1' rows='10'><?= htmlspecialchars($tugas['tugas']) ?></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class='col-md-4'>
                                <label class="form-label fw-bold">Pilih Kelas</label>
                                <select name='kelas[]' class='form-select select2' multiple='multiple' style='width:100%' required>
                                    <?php $lev = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC"); ?>
                                    <?php while ($kelas = mysqli_fetch_array($lev)) : ?>
                                        <option value="<?= $kelas['kelas'] ?>" <?= (is_array($kelas_terpilih) && in_array($kelas['kelas'], $kelas_terpilih)) ? 'selected' : '' ?>>
                                            <?= $kelas['kelas'] ?>
                                        </option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                            <div class='col-md-4'>
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type='datetime-local' name='tgl_mulai' class='form-control' value="<?= date('Y-m-d\TH:i', strtotime($tugas['tgl_mulai'])) ?>" required />
                            </div>
                            <div class='col-md-4'>
                                <label class="form-label fw-bold">Tanggal Selesai</label>
                                <input type='datetime-local' name='tgl_selesai' class='form-control' value="<?= date('Y-m-d\TH:i', strtotime($tugas['tgl_selesai'])) ?>" required />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">File Pendukung (Opsional)</label>
                             <?php if (!empty($tugas['file'])) : ?>
                                <p class="form-text text-muted small">File saat ini: <a href="../tugas/<?= $tugas['file'] ?>" target="_blank"><?= $tugas['file'] ?></a></p>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="file">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file.</small>
                        </div>
                        <div class='card-footer'>
                            <button type='submit' class='btn btn-primary'>Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } else { echo "<div class='alert alert-danger'>Tugas tidak ditemukan atau Anda tidak memiliki hak akses.</div>"; }
} ?>

<div class="modal fade" id="notif-progress-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Pengiriman Notifikasi</h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="../../images/animasi.gif" style="width:50px;">
                </div>
                <p id="notif-status-text" class="text-center mb-2">Mempersiapkan pengiriman...</p>
                <div class="progress" style="height: 20px;">
                    <div id="notif-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div id="failed-section" class="mt-3" style="display:none; max-height:220px; overflow:auto;">
                    <h6>Gagal Terkirim:</h6>
                    <ul id="failed-notif-list" class="list-group"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <form id="resend-form" class="me-2">
                    <input type="hidden" name="failed_base64" id="failed_base64">
                    <input type="hidden" name="id_tugas" id="resend_id_tugas" value="<?= $id_tugas ?? '' ?>">
                    <input type="hidden" name="type" id="resend_type" value="perubahan">
                    <button type="button" class="btn btn-primary" id="btn-resend">Kirimkan Ulang</button>
                </form>
                <button type="button" class="btn btn-primary" id="finish-notif-button" style="display:none;">Selesai</button>
            </div>
        </div>
    </div>
</div>

<?php if ($ac == enkripsi('edit')) : ?>
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap-5' });
        tinymce.init({
            selector: '#isitugas-editor',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar: 'undo redo | bold italic underline | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor emoticons | code preview',
            paste_data_images: true,
            image_advtab: true,
            height: 300,
            setup: function(editor) { editor.on('change', function() { tinymce.triggerSave(); }); }
        });
        $('#mapel_select_edit').change(function() {
            var mapel_kode = $(this).val();
            var materi_select = $('#materi_select_edit');
            materi_select.html('<option value="">Memuat...</option>');
            if (mapel_kode !== '') {
                $.ajax({ url: 'tugas/get_materi.php', type: 'POST', data: { mapel_kode: mapel_kode }, success: function(r) { materi_select.html(r); }, error: function() { materi_select.html('<option value="">Gagal memuat materi</option>'); } });
            } else {
                materi_select.html('<option value="">Pilih Mata Pelajaran Terlebih Dahulu</option>');
            }
        });
        $('#formEditTugas').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.set('isitugas', tinymce.get('isitugas-editor').getContent());
            var submitButton = $(this).find('button[type="submit"]');
            $.ajax({
                type: 'POST', url: 'tugas/simpan_dan_get_siswa.php', data: formData, cache: false, contentType: false, processData: false, dataType: 'json',
                beforeSend: function() { submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'); },
                success: function(response) {
                    if (response.status === 'ok') {
                        if (response.students && response.students.length > 0) {
                            $('#notif-progress-modal').modal('show');
                            sendNotificationsSequentially(response.students, response.id_tugas, 'perubahan');
                        } else {
                            swal({ title: 'Berhasil!', text: 'Tugas berhasil diperbarui (Tidak ada siswa untuk dinotifikasi).', type: 'success', timer: 2000, showConfirmButton: false }).then(function(){ window.location.replace('?pg=<?= enkripsi('tugas') ?>'); });
                        }
                    } else {
                        swal('Gagal!', response.message || 'Gagal menyimpan data tugas.', 'error');
                        submitButton.prop('disabled', false).html('Simpan Perubahan');
                    }
                },
                error: function() { swal('Error!', 'Terjadi kesalahan koneksi saat menyimpan data.', 'error'); submitButton.prop('disabled', false).html('Simpan Perubahan'); }
            });
        });
        function parseFailed(){ try{ return JSON.parse(decodeURIComponent(escape(atob($('#failed_base64').val())))); }catch(e){ return []; } }
        function setFailed(list){ var b64=btoa(unescape(encodeURIComponent(JSON.stringify(list)))); $('#failed_base64').val(b64); }
        function renderFailed(list){ $('#failed-section').show(); $('#failed-notif-list').empty(); list.forEach(function(s){ var nama=s.nama||'-'; var nohp=s.nohp||s.nowa||'-'; $('#failed-notif-list').append('<li class="list-group-item list-group-item-danger">'+nama+' ('+nohp+')</li>'); }); }
        function sendNotificationsSequentially(students, id_tugas, type) {
            let index = 0; let totalSiswa = students.length; let failedSends = [];
            function sendNext() {
                if (index >= totalSiswa) {
                    $('#notif-status-text').text('Semua notifikasi telah diproses!');
                    if (failedSends.length === 0) { $('#failed-section').hide(); $('#btn-resend').hide(); }
                    else { var payload = failedSends.map(function(s){ return { nama: s.nama, nohp: s.nowa, id_tugas: id_tugas, type: type }; }); setFailed(payload); renderFailed(payload); $('#btn-resend').show(); }
                    $('#finish-notif-button').show();
                    return;
                }
                let student = students[index];
                let percent = Math.round(((index + 1) / totalSiswa) * 100);
                $('#notif-status-text').html('Mengirim ke: <span class="fw-bold">' + student.nama + '</span>');
                $('#notif-progress-bar').css('width', percent + '%').text(percent + '%');
                $.ajax({ url: 'tugas/kirim_notif_satu.php', type: 'POST', data: { id_tugas: id_tugas, nama_siswa: student.nama, nowa_siswa: student.nowa, type: type }, dataType: 'json',
                    success: function(r){ if (!r || r.status !== 'ok') { failedSends.push(student); } },
                    error: function(){ failedSends.push(student); },
                    complete: function(){ index++; setTimeout(sendNext, 150); }
                });
            }
            sendNext();
        }
        $(document).on('click','#btn-resend',function(){
            var list = parseFailed(); if(!list.length) return; var id_tugas=$('#resend_id_tugas').val(); var type=$('#resend_type').val(); var remaining=[]; var i=0;
            function sendNext(){
                if(i>=list.length){ setFailed(remaining); if(remaining.length>0){ renderFailed(remaining); $('#btn-resend').prop('disabled',false).text('Kirimkan Ulang'); $('#finish-notif-button').show(); } else { $('#btn-resend').prop('disabled',false).text('Kirimkan Ulang'); $('#finish-notif-button').show(); swal({title:'Selesai',text:'Semua pesan terkirim.',type:'success'}); } return; }
                var s=list[i]; var nama=s.nama||''; var nohp=(s.nohp||s.nowa||'').replace(/\D+/g,''); $('#btn-resend').prop('disabled',true).text('Mengirim '+(i+1)+'/'+list.length+'...');
                $.ajax({ url:'tugas/kirim_notif_satu.php', type:'POST', data:{id_tugas:id_tugas,nama_siswa:nama,nowa_siswa:nohp,type:type}, dataType:'json',
                    success:function(r){ if(!(r && r.status==='ok')) remaining.push({nama:nama,nohp:nohp,id_tugas:id_tugas,type:type}); },
                    error:function(){ remaining.push({nama:nama,nohp:nohp,id_tugas:id_tugas,type:type}); },
                    complete:function(){ i++; setTimeout(sendNext,120); }
                });
            }
            sendNext();
        });
        $('#finish-notif-button').on('click', function() { $('#notif-progress-modal').modal('hide'); window.location.replace('?pg=<?= enkripsi('tugas') ?>'); });
    });
</script>
<?php else : ?>
<script>
    $(document).ready(function() {
        $('#datatable1').DataTable();
        $('#datatable-jawaban').DataTable();
        $('#tablejawaban').on('submit', '.form-nilai-tugas', function(e) {
            e.preventDefault();
            var form = $(this);
            var id_jawaban = form.find('input[name="id_jawaban"]').val();
            $.ajax({ type: "POST", url: "tugas/simpan_nilai.php", data: form.serialize(), dataType: 'json', beforeSend: function() { form.find('button[type="submit"]').prop('disabled', true).text('Menyimpan...'); }, success: function(response) { $('#modalnilai' + id_jawaban).modal('hide'); if (response.status === 'success' || response.status === 'success_no_wa') { swal({ title: 'Berhasil!', text: response.message, type: 'success', timer: 2000, showConfirmButton: false }).then(function(){ window.location.reload(); }); } else { swal('Gagal!', response.message, 'error'); } }, error: function() { swal('Gagal!', 'Terjadi kesalahan saat terhubung ke server.', 'error'); }, complete: function() { form.find('button[type="submit"]').prop('disabled', false).text('Simpan Nilai'); } });
        });
        $('#tablejawaban').on('submit', '.form-hapus-jawaban', function(e) {
            e.preventDefault();
            var form = $(this);
            var id_jawaban = form.find('input[name="id"]').val();
            $.ajax({ type: "POST", url: "tugas/hapus_nilai.php", data: form.serialize(), dataType: 'json', beforeSend: function() { form.find('button[type="submit"]').prop('disabled', true).text('Menghapus...'); }, success: function(response) { $('#modalHapusJawaban' + id_jawaban).modal('hide'); if (response.status === 'success') { swal('Terhapus!', response.message, 'success').then(function(){ window.location.reload(); }); } else { swal('Gagal!', response.message, 'error'); } }, error: function() { swal('Gagal!', 'Terjadi kesalahan koneksi.', 'error'); }, complete: function() {  form.find('button[type="submit"]').prop('disabled', false).text('Hapus dan Kirim Notifikasi'); } });
        });
        $('#datatable1').on('click', '.hapus', function() {
            var id = $(this).data('id');
            swal({ title: 'Anda Yakin?', text: "Tugas ini akan dihapus secara permanen!", type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal' }).then(function(result){ if (result.value) { $.ajax({ url: 'tugas/hapus_tugas.php', method: "POST", data: { id: id }, success: function(data) { swal('Terhapus!', 'Tugas telah berhasil dihapus.', 'success').then(function(){ window.location.reload(); }); } }); } });
        });
    });
</script>
<?php endif; ?>

<!-- NEW: Styles + improved zoom/rotate/pan handlers -->
<style>
/* ensure predictable behavior for zoom + pan */
.file-preview .zoom-content {
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:auto;
    position:relative;
    cursor: grab;
    user-select: none;
    -webkit-user-drag: none;
    touch-action: none; /* we'll handle touch events */
}
.file-preview .zoom-inner {
    display:inline-block;
    will-change: transform;
    transition: transform .15s ease;
    transform-origin: center center;
}
.file-preview .zoom-controls { display:inline-flex; gap:.35rem; flex-wrap:wrap; align-items:center; }
.file-preview .zoom-controls .btn { padding:.25rem .5rem; font-size:.85rem; }
.file-preview .zoom-inner img,
.file-preview .zoom-inner iframe { display:block; pointer-events: auto; max-width:none; max-height:80vh; }
.file-preview .zoom-content.grabbing { cursor: grabbing; }
</style>

<script>
(function($){
    $(function(){
        function parseFloatAttr($el, name, def) {
            var v = parseFloat($el.attr(name));
            return isNaN(v) ? def : v;
        }

        // Ensure we cache natural size for images (defensive)
        function ensureNaturalSize($wrapper) {
            var $img = $wrapper.find('.zoom-img').first();
            if ($img && $img.length) {
                try {
                    var el = $img[0];
                    var natW = (el && el.naturalWidth && el.naturalWidth>0) ? el.naturalWidth : el.width;
                    $img.data('naturalWidth', natW || $img.width());
                } catch (e) {
                    // defensive: if anything unexpected, skip caching
                    console && console.warn && console.warn('ensureNaturalSize error', e);
                }
            }
        }

        // Apply translate/rotate/scale to .zoom-inner
        // For images we scale by setting image width (so scroll area changes).
        function applyTransform($wrapper, scale, rotate, tx, ty) {
            try {
                scale = Math.max(0.25, Math.min(4, parseFloat(scale) || 1));
                // normalize rotation to 0..359 to avoid huge numbers
                rotate = parseFloat(rotate) || 0;
                rotate = ((Math.round(rotate) % 360) + 360) % 360;
                tx = parseFloat(tx) || 0;
                ty = parseFloat(ty) || 0;
                $wrapper.attr('data-scale', scale);
                $wrapper.attr('data-rotate', rotate);
                $wrapper.attr('data-tx', tx);
                $wrapper.attr('data-ty', ty);

                var $inner = $wrapper.find('.zoom-inner').first();
                if (!$inner || !$inner.length) return;

                var $img = $inner.find('.zoom-img').first();
                if ($img && $img.length) {
                    ensureNaturalSize($wrapper);
                    var natW = parseFloat($img.data('naturalWidth') || ($img[0] && $img[0].naturalWidth) || $img.width());
                    if (!isFinite(natW) || natW <= 0) natW = $img.width() || 1;
                    // set image width to naturalWidth * scale so scrollable area grows
                    $img.css({ width: Math.round(natW * scale) + 'px', height: 'auto', 'max-width': 'none' });
                    // Use rotation on the inner wrapper (no scale in transform to preserve layout)
                    $inner.css('transform', 'translate(' + tx + 'px,' + ty + 'px) rotate(' + rotate + 'deg)');
                } else {
                    // For non-image content (iframe/pdf), use transform including scale
                    $inner.css('transform', 'translate(' + tx + 'px,' + ty + 'px) rotate(' + rotate + 'deg) scale(' + scale + ')');
                    // fallback zoom for some browsers
                    $inner.css('zoom', scale);
                }
            } catch (err) {
                // Don't let transform errors break the page; log for debug
                console && console.error && console.error('applyTransform error', err);
            }
        }

        // Delegated button handlers with rotation normalization and tx/ty reset on rotate
        $(document).on('click', '.file-preview .zoom-in, .file-preview .zoom-out, .file-preview .zoom-reset, .file-preview .rotate-left, .file-preview .rotate-right', function(e){
            e.preventDefault();
            var $btn = $(this);
            var $wrapper = $btn.closest('.file-preview');
            if (!$wrapper.length) return;
            var scale = parseFloatAttr($wrapper, 'data-scale', 1);
            var rotate = parseFloatAttr($wrapper, 'data-rotate', 0);
            var tx = parseFloatAttr($wrapper, 'data-tx', 0);
            var ty = parseFloatAttr($wrapper, 'data-ty', 0);

            if ($btn.hasClass('zoom-in')) {
                scale = +(scale + 0.15).toFixed(2);
            } else if ($btn.hasClass('zoom-out')) {
                scale = +(scale - 0.15).toFixed(2);
            } else if ($btn.hasClass('zoom-reset')) {
                scale = 1; rotate = 0; tx = 0; ty = 0;
                // reset scroll immediately
                var $content = $wrapper.find('.zoom-content').first();
                if ($content && $content.length) { $content.scrollLeft(0); $content.scrollTop(0); }
                // apply after a short delay to allow scroll reset to take effect
                setTimeout(function(){ applyTransform($wrapper, scale, rotate, tx, ty); }, 10);
                return;
            } else if ($btn.hasClass('rotate-left')) {
                // rotate by -90, normalize result and reset translation to avoid layout issues
                rotate = rotate - 90;
                rotate = ((Math.round(rotate) % 360) + 360) % 360;
                tx = 0; ty = 0;
            } else if ($btn.hasClass('rotate-right')) {
                rotate = rotate + 90;
                rotate = ((Math.round(rotate) % 360) + 360) % 360;
                tx = 0; ty = 0;
            }
            applyTransform($wrapper, scale, rotate, tx, ty);
        });

        // Panning (mouse) - use scroll when possible (image and no rotation)
        $(document).on('mousedown', '.file-preview .zoom-content', function(e){
            if (e.which !== 1) return; // left button only
            var $content = $(this);
            var $wrapper = $content.closest('.file-preview');
            var startX = e.pageX, startY = e.pageY;
            var startTx = parseFloatAttr($wrapper, 'data-tx', 0), startTy = parseFloatAttr($wrapper, 'data-ty', 0);
            var scale = parseFloatAttr($wrapper, 'data-scale', 1);
            var rotate = parseFloatAttr($wrapper, 'data-rotate', 0);
            var $inner = $wrapper.find('.zoom-inner').first();
            var isImage = ($inner && $inner.find('.zoom-img').length > 0);

            $content.addClass('grabbing');

            // only use scroll-based panning when rotation is exactly 0 (normalized)
            if (isImage && (((Math.round(rotate) % 360) + 360) % 360) === 0) {
                var startScrollLeft = $content.scrollLeft();
                var startScrollTop = $content.scrollTop();
                function onMove(ev) {
                    ev.preventDefault();
                    var dx = ev.pageX - startX;
                    var dy = ev.pageY - startY;
                    $content.scrollLeft(startScrollLeft - dx);
                    $content.scrollTop(startScrollTop - dy);
                }
                function onUp() {
                    $(document).off('mousemove', onMove);
                    $(document).off('mouseup', onUp);
                    $content.removeClass('grabbing');
                }
                $(document).on('mousemove', onMove);
                $(document).on('mouseup', onUp);
            } else {
                // Fallback: translate-based panning
                function onMove(ev) {
                    ev.preventDefault();
                    var dx = ev.pageX - startX;
                    var dy = ev.pageY - startY;
                    applyTransform($wrapper, scale, rotate, startTx + dx, startTy + dy);
                }
                function onUp() {
                    $(document).off('mousemove', onMove);
                    $(document).off('mouseup', onUp);
                    $content.removeClass('grabbing');
                }
                $(document).on('mousemove', onMove);
                $(document).on('mouseup', onUp);
            }
        });

        // Panning (touch) - similar approach
        $(document).on('touchstart', '.file-preview .zoom-content', function(e){
            if (!e.touches || e.touches.length !== 1) return;
            var touch = e.touches[0];
            var $content = $(this);
            var $wrapper = $content.closest('.file-preview');
            var startX = touch.pageX, startY = touch.pageY;
            var startTx = parseFloatAttr($wrapper, 'data-tx', 0), startTy = parseFloatAttr($wrapper, 'data-ty', 0);
            var scale = parseFloatAttr($wrapper, 'data-scale', 1);
            var rotate = parseFloatAttr($wrapper, 'data-rotate', 0);
            var $inner = $wrapper.find('.zoom-inner').first();
            var isImage = $inner.find('.zoom-img').length > 0;

            $content.addClass('grabbing');

            if (isImage && (Math.round(rotate) % 360) === 0) {
                var startScrollLeft = $content.scrollLeft();
                var startScrollTop = $content.scrollTop();
                function onMove(ev) {
                    if (!ev.touches || ev.touches.length !== 1) return;
                    var t = ev.touches[0];
                    var dx = t.pageX - startX;
                    var dy = t.pageY - startY;
                    $content.scrollLeft(startScrollLeft - dx);
                    $content.scrollTop(startScrollTop - dy);
                    ev.preventDefault();
                }
                function onEnd() {
                    document.removeEventListener('touchmove', onMove);
                    document.removeEventListener('touchend', onEnd);
                    $content.removeClass('grabbing');
                }
                document.addEventListener('touchmove', onMove, { passive: false });
                document.addEventListener('touchend', onEnd);
            } else {
                function onMove(ev) {
                    if (!ev.touches || ev.touches.length !== 1) return;
                    var t = ev.touches[0];
                    var dx = t.pageX - startX;
                    var dy = t.pageY - startY;
                    applyTransform($wrapper, scale, rotate, startTx + dx, startTy + dy);
                    ev.preventDefault();
                }
                function onEnd() {
                    document.removeEventListener('touchmove', onMove);
                    document.removeEventListener('touchend', onEnd);
                    $content.removeClass('grabbing');
                }
                document.addEventListener('touchmove', onMove, { passive: false });
                document.addEventListener('touchend', onEnd);
            }
        });

        // Reset when modal closes
        $(document).on('hidden.bs.modal', '.modal', function(){
            $(this).find('.file-preview').each(function(){
                var $w = $(this);
                var $inner = $w.find('.zoom-inner').first();
                var $img = ($inner && $inner.find) ? $inner.find('.zoom-img').first() : $();
                if ($img && $img.length) {
                    $img.css({ width: '', 'max-width': '', height: '' });
                }
                applyTransform($w, 1, 0, 0, 0);
                var $content = $w.find('.zoom-content').first();
                if ($content.length) { $content.scrollLeft(0); $content.scrollTop(0); }
            });
        });

        // Initialize when modal shown
        $(document).on('shown.bs.modal', '.modal', function(){
            $(this).find('.file-preview').each(function(){
                var $w = $(this);
                if (!$w.attr('data-scale')) $w.attr('data-scale', '1');
                if (!$w.attr('data-rotate')) $w.attr('data-rotate', '0');
                if (!$w.attr('data-tx')) $w.attr('data-tx', '0');
                if (!$w.attr('data-ty')) $w.attr('data-ty', '0');
                ensureNaturalSize($w);
            });
        });
    });
})(jQuery);
</script>

