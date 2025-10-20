<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

require_once __DIR__ . '/dapodik_helpers.php';

$config = dapodik_get_config($koneksi);
$semesterInfo = dapodik_resolve_semester_info($config, $setting);
$config = array_merge($config, $semesterInfo);

$baseUrl = htmlspecialchars(dapodik_normalize_base_url($config['base_url'] ?? ''), ENT_QUOTES, 'UTF-8');
$token = htmlspecialchars($config['token'] ?? '', ENT_QUOTES, 'UTF-8');
$npsn = htmlspecialchars($config['npsn'] ?? '', ENT_QUOTES, 'UTF-8');
$semesterId = htmlspecialchars($config['semester_id'] ?? '', ENT_QUOTES, 'UTF-8');
$semesterLabel = htmlspecialchars($config['semester_label'] ?? '', ENT_QUOTES, 'UTF-8');
$lastTestStatus = htmlspecialchars($config['last_test_status'] ?? '', ENT_QUOTES, 'UTF-8');
$lastTestAt = htmlspecialchars($config['last_test_at'] ?? '', ENT_QUOTES, 'UTF-8');
$lastTestMessage = trim((string)($config['last_test_message'] ?? ''));
?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between">
                <h5 class="card-title mb-0">Pengaturan Web Service Dapodik</h5>
                <span class="badge bg-primary fw-normal">Beta</span>
            </div>
            <div class="card-body">
                <form id="form-dapodik-config">
                    <div class="mb-3">
                        <label for="base_url" class="form-label">URL Dapodik</label>
                        <input type="url" class="form-control" id="base_url" name="base_url" value="<?= $baseUrl ?>" placeholder="http://localhost:5774" required>
                        <div class="form-text">
                            Gunakan alamat yang sama seperti di menu Pengaturan &gt; Web Service Dapodik. Sertakan protokol (http/https).
                            <br>Untuk simulasi tanpa koneksi web service, isi dengan <code>mock://default</code> atau nama skenario mock lain.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="token" class="form-label">Token / Key</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="token" name="token" value="<?= $token ?>" placeholder="Masukkan token webservice" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggle-token">
                                <i class="material-icons-two-tone">visibility</i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="npsn" class="form-label">NPSN</label>
                                <input type="text" class="form-control" id="npsn" name="npsn" value="<?= $npsn ?>" placeholder="8 digit NPSN" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="semester_id" class="form-label">Semester ID</label>
                                <input type="text" class="form-control" id="semester_id" name="semester_id" value="<?= $semesterId ?>" placeholder="contoh: 20251" required>
                                <div class="form-text">Format Dapodik: tahun awal + (1=Ganjil / 2=Genap).</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="semester_label" class="form-label">Label Semester</label>
                        <input type="text" class="form-control" id="semester_label" name="semester_label" value="<?= $semesterLabel ?>" placeholder="contoh: 2025/2026 Ganjil">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons-two-tone align-middle me-1">save</i> Simpan Pengaturan
                        </button>
                        <button type="button" id="btn-reset-config" class="btn btn-light border">
                            <i class="material-icons-two-tone align-middle me-1">restart_alt</i> Reset Form
                        </button>
                    </div>
                </form>
                <?php if ($lastTestStatus !== '') : ?>
                    <div class="alert alert-secondary mt-3 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Status uji koneksi terakhir: <strong><?= $lastTestStatus ?></strong></span>
                            <?php if ($lastTestAt !== '') : ?>
                                <small class="text-muted"><?= $lastTestAt ?></small>
                            <?php endif; ?>
                        </div>
                        <?php if ($lastTestMessage !== '') : ?>
                            <div class="small text-muted mt-2"><?= htmlspecialchars($lastTestMessage, ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Sinkronisasi Nilai</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Gunakan tombol di bawah ini setelah data nilai diinput lengkap. Sistem akan mencoba memadankan rombel, siswa, dan mapel dengan data Dapodik berdasarkan nama kelas, NISN, serta nama mata pelajaran.
                </p>
                <div class="btn-group mb-3 w-100" role="group">
                    <button type="button" class="btn btn-outline-primary" id="btn-test-koneksi">
                        <i class="material-icons-two-tone align-middle me-1">network_check</i> Tes Koneksi
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-preview-nilai">
                        <i class="material-icons-two-tone align-middle me-1">preview</i> Preview Nilai
                    </button>
                    <button type="button" class="btn btn-warning" id="btn-kirim-matev">
                        <i class="material-icons-two-tone align-middle me-1">playlist_add</i> Kirim MATEV
                    </button>
                    <button type="button" class="btn btn-success" id="btn-kirim-nilai">
                        <i class="material-icons-two-tone align-middle me-1">cloud_upload</i> Kirim Nilai
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="strict-mode" checked>
                        <label class="form-check-label" for="strict-mode">Mode Ketat</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="debug-mode">
                        <label class="form-check-label" for="debug-mode">Debug Detail</label>
                    </div>
                </div>

                <div class="border rounded p-3 bg-light" style="min-height:160px;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="material-icons-two-tone text-primary me-2">list_alt</i>
                        <strong>Log Aktivitas</strong>
                        <button type="button" class="btn btn-sm btn-link ms-auto" id="btn-clear-log">Bersihkan</button>
                    </div>
                    <div id="dapodik-log" class="small text-monospace" style="max-height:220px; overflow:auto;">
                        <div class="text-muted">Menunggu aksi...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Preview Data yang Akan Dikirim</h6>
            </div>
            <div class="card-body">
                <div id="dapodik-preview" class="small text-muted">Belum ada data. Klik <strong>Preview Nilai</strong> untuk melihat ringkasannya.</div>
            </div>
        </div>
    </div>
</div>

<script>
    (function($){
        const apiUrl = 'dapodik/api.php';
        const $form = $('#form-dapodik-config');
        const $log = $('#dapodik-log');
        const $preview = $('#dapodik-preview');

        function appendLog(message, type) {
            const timestamp = new Date().toLocaleTimeString();
            const cls = type === 'error'
                ? 'text-danger'
                : (type === 'success'
                    ? 'text-success'
                    : (type === 'warning' ? 'text-warning' : 'text-muted'));
            const row = $('<div>').addClass(cls).text(`[${timestamp}] ${message}`);
            $log.append(row);
            $log.scrollTop($log.prop('scrollHeight'));
        }

        function setPreviewContent(html) {
            $preview.html(html);
        }

        function ajaxAction(action, payload = {}) {
            const data = $.extend({action: action}, payload);
            return $.ajax({
                url: apiUrl,
                method: 'POST',
                dataType: 'json',
                data: data
            });
        }

        function getFlags(){
            return {
                strict: $('#strict-mode').is(':checked') ? 1 : 0,
                debug: $('#debug-mode').is(':checked') ? 1 : 0,
            };
        }

        $('#toggle-token').on('click', function () {
            const $input = $('#token');
            const type = $input.attr('type') === 'password' ? 'text' : 'password';
            $input.attr('type', type);
            $(this).find('i').text(type === 'password' ? 'visibility' : 'visibility_off');
        });

        $form.on('submit', function(e){
            e.preventDefault();
            const formData = $form.serialize();
            const $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true).addClass('disabled');
            appendLog('Menyimpan pengaturan...', 'info');
            $.ajax({
                url: apiUrl,
                method: 'POST',
                data: formData + '&action=save_config',
                dataType: 'json'
            }).done(function(res){
                if (res.success) {
                    appendLog(res.message || 'Pengaturan tersimpan.', 'success');
                    if (res.data && res.data.semester_label) {
                        $('#semester_label').val(res.data.semester_label);
                    }
                    if (res.data && res.data.semester_id) {
                        $('#semester_id').val(res.data.semester_id);
                    }
                } else {
                    appendLog(res.message || 'Gagal menyimpan pengaturan.', 'error');
                }
            }).fail(function(xhr){
                appendLog('Gagal menyimpan pengaturan: ' + (xhr.responseText || xhr.statusText), 'error');
            }).always(function(){
                $btn.prop('disabled', false).removeClass('disabled');
            });
        });

        $('#btn-reset-config').on('click', function(){
            $form[0].reset();
            appendLog('Form dikembalikan ke nilai awal.', 'info');
        });

        $('#btn-test-koneksi').on('click', function(){
            const $btn = $(this);
            $btn.prop('disabled', true).addClass('disabled');
            appendLog('Mengirim permintaan tes koneksi...', 'info');
            ajaxAction('test_connection')
                .done(function(res){
                    if (res.success) {
                        appendLog(res.message || 'Koneksi berhasil.', 'success');
                        if (res.data && res.data.sekolah) {
                            appendLog('Sekolah: ' + res.data.sekolah.nama, 'success');
                        }
                        if (res.data && res.data.mode) {
                            appendLog('Mode: ' + (res.data.mode === 'simulation' ? 'Simulasi (mock)' : 'Live'), 'info');
                        }
                    } else {
                        appendLog(res.message || 'Tes koneksi gagal.', 'error');
                    }
                })
                .fail(function(xhr){
                    appendLog('Tes koneksi gagal: ' + (xhr.responseText || xhr.statusText), 'error');
                })
                .always(function(){
                    $btn.prop('disabled', false).removeClass('disabled');
                });
        });

        $('#btn-preview-nilai').on('click', function(){
            const $btn = $(this);
            $btn.prop('disabled', true).addClass('disabled');
            appendLog('Mengambil ringkasan nilai...', 'info');
            setPreviewContent('<div class="text-muted">Memuat data...</div>');
            ajaxAction('preview_nilai')
                .done(function(res){
                    if (res.success) {
                        appendLog(res.message || 'Preview nilai berhasil.', 'success');
                        if (res.data && res.data.mode) {
                            appendLog('Mode: ' + (res.data.mode === 'simulation' ? 'Simulasi (mock)' : 'Live'), 'info');
                        }
                        if (res.data && res.data.data_source) {
                            appendLog('Sumber nilai: ' + res.data.data_source, 'info');
                        }
                        if (res.data && res.data.html) {
                            setPreviewContent(res.data.html);
                        } else {
                            setPreviewContent('<div class="text-muted">Tidak ada data nilai pada semester terpilih.</div>');
                        }
                    } else {
                        const msg = res.message || 'Preview nilai gagal.';
                        appendLog(msg, 'error');
                        setPreviewContent('<div class="text-danger">' + msg + '</div>');
                    }
                })
                .fail(function(xhr){
                    const msg = xhr.responseText || xhr.statusText;
                    appendLog('Preview nilai gagal: ' + msg, 'error');
                    setPreviewContent('<div class="text-danger">' + msg + '</div>');
                })
                .always(function(){
                    $btn.prop('disabled', false).removeClass('disabled');
                });
        });

        $('#btn-kirim-nilai').on('click', function(){
            if (!confirm('Pastikan data sudah benar. Lanjutkan kirim nilai ke Dapodik?')) {
                return;
            }
            const $btn = $(this);
            $btn.prop('disabled', true).addClass('disabled');
            appendLog('Mengirim data nilai ke Dapodik...', 'info');
            ajaxAction('kirim_nilai', getFlags())
                .done(function(res){
                    if (res.success) {
                        appendLog(res.message || 'Kirim nilai berhasil.', 'success');
                        if (res.data && res.data.mode) {
                            appendLog('Mode: ' + (res.data.mode === 'simulation' ? 'Simulasi (mock)' : 'Live'), 'info');
                        }
                        if (res.data && res.data.data_source) {
                            appendLog('Sumber nilai: ' + res.data.data_source, 'info');
                        }
                        if (res.data && res.data.stats) {
                            appendLog(
                                'Statistik pencocokan: total ' + (res.data.stats.total_rows || 0) +
                                ', cocok ' + (res.data.stats.matched || 0) +
                                ', belum cocok ' + (res.data.stats.unmatched || 0),
                                'info'
                            );
                        }
                        if (res.data && res.data.unmatched) {
                            Object.keys(res.data.unmatched).forEach(function(key){
                                const list = res.data.unmatched[key];
                                if (Array.isArray(list) && list.length) {
                                    const labelMap = {
                                        kelas: 'Rombel tidak ditemukan',
                                        mapel: 'Mapel belum terpadan',
                                        nisn: 'NISN tidak terdaftar di Dapodik',
                                        anggota_rombel: 'Anggota rombel tidak ditemukan'
                                    };
                                    const label = labelMap[key] || key;
                                    appendLog(label + ': ' + list.length + ' entri.', 'warning');
                                }
                            });
                        }
                        if (res.data && Array.isArray(res.data.responses)) {
                            res.data.responses.forEach(function(resp){
                                if (Array.isArray(resp.messages) && resp.messages.length) {
                                    const context = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    const lastMsg = resp.messages[resp.messages.length - 1];
                                    const line = (context ? context + ' - ' : '') + lastMsg;
                                    appendLog(line, resp.status === 'ok' ? 'success' : 'error');
                                }
                            });
                        }
                        if (res.data && res.data.summary_html) {
                            setPreviewContent(res.data.summary_html);
                        }
                    } else {
                        appendLog(res.message || 'Kirim nilai gagal.', 'error');
                        if (res.data && Array.isArray(res.data.responses)) {
                            res.data.responses.forEach(function(resp){
                                if (Array.isArray(resp.messages) && resp.messages.length) {
                                    const context = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    const lastMsg = resp.messages[resp.messages.length - 1];
                                    const line = (context ? context + ' - ' : '') + lastMsg;
                                    appendLog(line, resp.status === 'ok' ? 'success' : 'error');
                                } else if (resp.error) {
                                    const context = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    const line = (context ? context + ' - ' : '') + resp.error;
                                    appendLog(line, 'error');
                                }
                            });
                        }
                        if (res.data && res.data.data_source) {
                            appendLog('Sumber nilai: ' + res.data.data_source, 'info');
                        }
                        if (res.data && res.data.stats) {
                            appendLog(
                                'Statistik pencocokan: total ' + (res.data.stats.total_rows || 0) +
                                ', cocok ' + (res.data.stats.matched || 0) +
                                ', belum cocok ' + (res.data.stats.unmatched || 0),
                                'info'
                            );
                        }
                        if (res.data && res.data.unmatched) {
                            Object.keys(res.data.unmatched).forEach(function(key){
                                const list = res.data.unmatched[key];
                                if (Array.isArray(list) && list.length) {
                                    const labelMap = {
                                        kelas: 'Rombel tidak ditemukan',
                                        mapel: 'Mapel belum terpadan',
                                        nisn: 'NISN tidak terdaftar di Dapodik',
                                        anggota_rombel: 'Anggota rombel tidak ditemukan'
                                    };
                                    const label = labelMap[key] || key;
                                    appendLog(label + ': ' + list.length + ' entri.', 'error');
                                }
                            });
                        }
                        if (res.data && res.data.summary_html) {
                            setPreviewContent(res.data.summary_html);
                        }
                    }
                })
                .fail(function(xhr){
                    var raw = (xhr.responseText || xhr.statusText || '').toString();
                    appendLog('Kirim nilai gagal: ' + raw, 'error');
                })
                .always(function(){
                    $btn.prop('disabled', false).removeClass('disabled');
                });
        });

        $('#btn-kirim-matev').on('click', function(){
            const $btn = $(this);
            if (!confirm('Ini akan memicu pembentukan Mata Evaluasi Rapor (MATEV) pada Dapodik. Lanjutkan?')) {
                return;
            }
            $btn.prop('disabled', true).addClass('disabled');
            appendLog('Memicu pembentukan MATEV di Dapodik...', 'info');
            ajaxAction('kirim_matev', getFlags())
                .done(function(res){
                    if (res.success) {
                        appendLog(res.message || 'Kirim MATEV berhasil.', 'success');
                        if (res.data && Array.isArray(res.data.responses)) {
                            res.data.responses.forEach(function(resp){
                                if (Array.isArray(resp.messages) && resp.messages.length) {
                                    const ctx = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    const last = resp.messages[resp.messages.length - 1];
                                    appendLog((ctx ? ctx + ' - ' : '') + last, resp.status === 'ok' ? 'success' : 'error');
                                } else if (resp.error) {
                                    const ctx = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    appendLog((ctx ? ctx + ' - ' : '') + resp.error, 'error');
                                }
                            });
                        }
                        if (res.data && res.data.summary_html) {
                            setPreviewContent(res.data.summary_html);
                        }
                    } else {
                        appendLog(res.message || 'Kirim MATEV gagal.', 'error');
                        if (res.data && Array.isArray(res.data.responses)) {
                            res.data.responses.forEach(function(resp){
                                if (Array.isArray(resp.messages) && resp.messages.length) {
                                    const ctx = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    const last = resp.messages[resp.messages.length - 1];
                                    appendLog((ctx ? ctx + ' - ' : '') + last, resp.status === 'ok' ? 'success' : 'error');
                                } else if (resp.error) {
                                    const ctx = [resp.kelas || '', resp.mapel || ''].filter(Boolean).join(' / ');
                                    appendLog((ctx ? ctx + ' - ' : '') + resp.error, 'error');
                                }
                            });
                        }
                    }
                })
                .fail(function(xhr){
                    appendLog('Kirim MATEV gagal: ' + (xhr.responseText || xhr.statusText), 'error');
                })
                .always(function(){
                    $btn.prop('disabled', false).removeClass('disabled');
                });
        });

        $('#btn-clear-log').on('click', function(){
            $log.empty().append('<div class="text-muted">Log dibersihkan.</div>');
        });

    })(jQuery);
</script>
