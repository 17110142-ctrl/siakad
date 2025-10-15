<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Materi</title>
    
    <!-- Link Font untuk Ikon Chatbot -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- CSS untuk Chatbot -->
    <style>
        .chatbot-toggler {
            position: fixed;
            right: 35px;
            bottom: 30px;
            height: 50px;
            width: 50px;
            background: #673AB7;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            z-index: 1050;
            transition: all 0.2s ease;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        body.show-chatbot .chatbot-toggler {
            transform: rotate(90deg);
        }
        .chatbot-toggler span {
            position: absolute;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }
        .chatbot-toggler span:last-child {
            opacity: 0;
            transform: rotate(-90deg);
        }
        body.show-chatbot .chatbot-toggler span:first-child {
            opacity: 0;
            transform: rotate(90deg);
        }
        body.show-chatbot .chatbot-toggler span:last-child {
            opacity: 1;
            transform: rotate(0deg);
        }
        .chatbot {
            position: fixed;
            right: 35px;
            bottom: 100px;
            width: 420px;
            max-width: 90%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 128px 0 rgba(0,0,0,0.1), 0 32px 64px -48px rgba(0,0,0,0.5);
            transform: scale(0.5);
            opacity: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1040;
            transition: all 0.2s ease;
            transform-origin: bottom right;
        }
        body.show-chatbot .chatbot {
            transform: scale(1);
            opacity: 1;
            pointer-events: auto;
        }
        .chatbot .chatbox-header {
            background: #673AB7;
            padding: 16px 0;
            text-align: center;
            color: #fff;
            position: relative;
        }
        .chatbot .chatbox-header h2 {
            font-size: 1.4rem;
            margin: 0;
        }
        .chatbot .chatbox {
            height: 400px;
            overflow-y: auto;
            padding: 30px 20px 100px;
            background-color: #f1f1f1;
            list-style: none;
            margin: 0;
        }
        .chatbox .chat {
            display: flex;
            margin-bottom: 15px;
        }
        .chatbox .chat p {
            max-width: 75%;
            font-size: 0.95rem;
            padding: 12px 16px;
            border-radius: 18px;
            background: #007bff;
            color: #fff;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .chatbox .incoming p {
            background: #e9e9e9;
            color: #000;
            border-radius: 18px 18px 18px 0;
        }
        .chatbox .chat.outgoing {
            justify-content: flex-end;
        }
        .chatbox .outgoing p {
             border-radius: 18px 18px 0 18px;
        }
        .chat-input {
            position: absolute;
            bottom: 0;
            width: 100%;
            display: flex;
            gap: 5px;
            background: #fff;
            padding: 8px 20px;
            border-top: 1px solid #ccc;
        }
        .chat-input textarea {
            height: 55px;
            width: 100%;
            border: none;
            outline: none;
            font-size: 0.95rem;
            resize: none;
            padding: 16px 15px 16px 0;
        }
        .chat-input span {
            font-size: 1.75rem;
            color: #673AB7;
            cursor: pointer;
            align-self: center;
            line-height: 55px;
            visibility: hidden;
        }
        .chat-input textarea:valid ~ span {
            visibility: visible;
        }
    </style>
</head>
<body>

<?php if ($ac == '') { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">TAMBAH MATERI BELAJAR</h5>
                </div>
                <div class="card-body">
                    <form id="formmateri" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Mata Pelajaran</label>
                            <div class="col-sm-7">
                                <select name='mapel' class='form-select' style='width:100%' required>
                                    <option value=''>Pilih Mata Pelajaran</option>
                                    <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC"); ?>
                                    <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                        <option value="<?= $mapel['kode'] ?>"><?= $mapel['nama_mapel'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Judul Materi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="judul" placeholder="Judul materi" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Materi Belajar</label>
                            <div class="col-sm-9">
                                <textarea name='isimateri' class='editor1' id='editor-tambah' rows='10' cols='80' style='width:100%;'></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Kelas</label>
                            <div class="col-md-9">
                                <select name='kelas[]' class='form-control select2' multiple='multiple' style='width:100%' required='true'>
                                    <?php $lev = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC"); ?>
                                    <?php while ($kelas = mysqli_fetch_array($lev)) : ?>
                                        <option value="<?= $kelas['kelas'] ?>"><?= $kelas['kelas'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Jadwal</label>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type='datetime-local' name='tgl_mulai' class='form-control' autocomplete='off' required='true' />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type='datetime-local' name='tgl_selesai' class='form-control' autocomplete='off' required='true' />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Link Youtube</label>
                            <div class="col-sm-9">
                                <input type='text' name='youtube' class='form-control youtube-link-input' autocomplete='off' />
                                <small class="form-text text-muted">Contoh Link: <b>https://youtu.be/42cqGZY9VTc</b>. Cukup masukkan kodenya saja: <b>42cqGZY9VTc</b></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">File Pendukung</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="file" aria-describedby="fileHelpId">
                                <small id="fileHelpId" class="form-text text-muted">Format: doc, docx, xls, xlsx, pdf, ppt, pptx, jpg, png, mp4, mp3</small>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='submit' class='btn btn-primary'>Simpan Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } elseif ($ac == enkripsi('edit')) { ?>
    <?php
    $id = $_GET['id'];
    $materi = fetch($koneksi, 'materi', ['id_materi' => $id]);
    $map = fetch($koneksi, 'mata_pelajaran', ['kode' => $materi['mapel']]);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">EDIT MATERI BELAJAR</h5>
                </div>
                <div class="card-body">
                    <form id="formedit" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name="id" value="<?= $id ?>">
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Mata Pelajaran</label>
                            <div class="col-sm-7">
                                <select name='mapel' class='form-select' style='width:100%' required>
                                    <option value="<?= $materi['mapel'] ?>"><?= $map['nama_mapel'] ?></option>
                                    <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE kode <> '" . mysqli_real_escape_string($koneksi, $materi['mapel']) . "' ORDER BY nama_mapel ASC"); ?>
                                    <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                        <option value="<?= $mapel['kode'] ?>"><?= $mapel['nama_mapel'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Judul Materi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($materi['judul']) ?>" required="true">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Materi Belajar</label>
                            <div class="col-sm-9">
                                <textarea name='isimateri' class='editor1' id='editor-edit' rows='10' cols='80' style='width:100%;'><?= htmlspecialchars($materi['materi']) ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Kelas</label>
                            <div class="col-md-9">
                                <select name='kelas[]' class='form-control select2' multiple='multiple' style='width:100%' required='true'>
                                    <?php
                                    $lev = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC");
                                    $kelas_terpilih = @unserialize($materi['kelas']);
                                    while ($kelas = mysqli_fetch_array($lev)) :
                                        $selected = (is_array($kelas_terpilih) && in_array($kelas['kelas'], $kelas_terpilih)) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $kelas['kelas'] ?>" <?= $selected ?>><?= $kelas['kelas'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Jadwal</label>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type='datetime-local' name='tgl_mulai' value="<?= date('Y-m-d\TH:i', strtotime($materi['tgl_mulai'])) ?>" class='form-control' autocomplete='off' required='true' />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type='datetime-local' name='tgl_selesai' value="<?= ($materi['tgl_selesai']) ? date('Y-m-d\TH:i', strtotime($materi['tgl_selesai'])) : '' ?>" class='form-control' autocomplete='off' required='true' />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">Link Youtube</label>
                            <div class="col-sm-9">
                                <input type='text' name='youtube' value="<?= htmlspecialchars($materi['youtube']) ?>" class='form-control youtube-link-input' autocomplete='off' />
                                <small class="form-text text-muted">Contoh Link: <b>https://youtu.be/42cqGZY9VTc</b>. Cukup masukkan kodenya saja: <b>42cqGZY9VTc</b></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label fw-bold">File Pendukung</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="file" aria-describedby="fileHelpId">
                                <small id="fileHelpId" class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah file. File saat ini: <?= $materi['file'] ? htmlspecialchars($materi['file']) : 'Tidak ada' ?></small>
                            </div>
                        </div>
                        <div class='modal-footer' style="display:flex; gap:8px; justify-content:flex-end;">
                            <button type='button' id='btnNotifMateri' class='btn btn-secondary'>Kirim Notifikasi Materi</button>
                            <button type='submit' class='btn btn-primary'>Update Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Modal for WA notification progress -->
<div class="modal fade" id="waProgressModal" tabindex="-1" role="dialog" aria-labelledby="waProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="waProgressModalLabel">Proses Pengiriman Notifikasi</h5>
            </div>
            <div class="modal-body">
                <div id="wa-initial-progress" class="text-center">
                    <p id="wa-status-text" class="mb-2">Mempersiapkan pengiriman...</p>
                    <img src="../../images/animasi.gif" style="width:50px;">
                </div>
                <div id="wa-sending-progress" style="display:none;">
                    <p id="wa-sending-to" class="mb-1">Mengirim ke...</p>
                    <div class="progress" style="height: 20px;">
                        <div id="wa-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small>Terkirim: <span id="wa-sent-count">0</span></small>
                        <small>Gagal: <span id="wa-fail-count">0</span></small>
                        <small>Total: <span id="wa-total-count">0</span></small>
                    </div>
                </div>
                <div id="wa-result-section" class="mt-3" style="display:none;">
                    <h6 id="wa-result-title"></h6>
                    <div id="wa-failed-list" style="max-height: 300px; overflow-y: auto;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="wa-cancel-button">Batalkan</button>
                <button type="button" class="btn btn-secondary" id="wa-resend-all-button" style="display:none;">Kirim Ulang Semua Gagal</button>
                <button type="button" class="btn btn-primary" id="wa-finish-button" style="display:none;">Selesai</button>
            </div>
        </div>
    </div>
</div>

<button class="chatbot-toggler">
    <span class="material-symbols-outlined">smart_toy</span>
    <span class="material-symbols-outlined">close</span>
</button>
<div class="chatbot">
    <div class="chatbox-header">
        <h2>Asisten AI 🤖</h2>
    </div>
    <ul class="chatbox">
        <li class="chat incoming">
            <p>Halo! 👋<br>Ada yang bisa saya bantu cari di sistem ini?</p>
        </li>
    </ul>
    <div class="chat-input">
        <textarea placeholder="Ketik pertanyaan Anda..." required></textarea>
        <span id="send-btn" class="material-symbols-outlined">send</span>
    </div>
</div>

<script>
$(document).ready(function() {
    // --- Setup ---
    var CURRENT_GURU_ID = <?= (int)($_SESSION['id_user'] ?? ($user['id_user'] ?? 0)) ?>;
    var waModal = new bootstrap.Modal(document.getElementById('waProgressModal'));
    var WA_CANCELLED = false;
    var failedList = [];
    var onWaDone = function() {};

    // --- Core Notification Logic ---

    // Sends a single WA notification
    function sendOne(recipient, scope, callback, isRetry) {
        var payload = $.extend({}, scope, { ids: JSON.stringify([recipient.idsiswa]), debug: '1' });
        $.ajax({
            type: 'POST',
            url: 'materi/kirim_notif_materi.php',
            data: payload,
            dataType: 'json'
        }).done(function(resp) {
            var result = resp && resp.results && resp.results[0] ? resp.results[0] : {};
            if (result.success) {
                // On success, if it was a retry, remove from failed list
                if (isRetry) {
                    failedList = failedList.filter(function(x) { return x.idsiswa !== recipient.idsiswa; });
                }
                callback(true);
            } else {
                // On failure, add to failed list if it's not already there
                recipient.error = result.error || 'Gagal dari server';
                if (!isRetry) {
                    var exists = failedList.some(function(x) { return x.idsiswa === recipient.idsiswa; });
                    if (!exists) failedList.push(recipient);
                }
                callback(false);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            recipient.error = 'Error: ' + textStatus;
            if (!isRetry) {
                var exists = failedList.some(function(x) { return x.idsiswa === recipient.idsiswa; });
                if (!exists) failedList.push(recipient);
            }
            callback(false);
        });
    }

    // Manages the whole notification sending process
    function sendNotifMateri(scope) {
        var recips = [], total = 0, sent = 0, idx = 0;
        var SEND_DELAY_MS = 250;
        failedList = [];
        WA_CANCELLED = false;

        // Store scope in a hidden div for the resend buttons to use
        $('#waProgressModal .modal-body').find('#wa-scope-data').remove(); // Clear previous
        $('#waProgressModal .modal-body').append('<div id="wa-scope-data" style="display:none;">' + JSON.stringify(scope) + '</div>');

        function resetModal() {
            $('#wa-initial-progress').show();
            $('#wa-sending-progress').hide();
            $('#wa-result-section').hide();
            $('#wa-status-text').text('Mempersiapkan pengiriman...');
            $('#wa-progress-bar').css('width', '0%').text('0%');
            $('#wa-cancel-button').show().prop('disabled', false).text('Batalkan');
            $('#wa-resend-all-button').hide();
            $('#wa-finish-button').show();
            $('#wa-failed-list').empty();
        }

        function updateProgress(name) {
            var percent = total > 0 ? Math.round(((idx + 1) / total) * 100) : 0;
            $('#wa-initial-progress').hide();
            $('#wa-sending-progress').show();
            $('#wa-sending-to').html('Mengirim ke: <span class="fw-bold">' + name + '</span>');
            $('#wa-progress-bar').css('width', percent + '%').text(percent + '%');
            $('#wa-sent-count').text(sent);
            $('#wa-fail-count').text(failedList.length);
            $('#wa-total-count').text(total);
        }

        function renderDone() {
            $('#wa-sending-progress').hide();
            $('#wa-result-section').show();
            $('#wa-cancel-button').hide();
            $('#wa-finish-button').show();

            var resultTitle = WA_CANCELLED ? 'Pengiriman Dibatalkan' : 'Pengiriman Selesai';
            $('#wa-result-title').text(resultTitle + ' (Terkirim: ' + sent + '/' + total + ')');

            $('#wa-failed-list').empty();
            if (failedList.length > 0) {
                $('#wa-resend-all-button').show();
                failedList.forEach(function(f) {
                    var item = '<div class="card card-body p-2 mb-2" id="failed-' + f.idsiswa + '">' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                        '<div>' + f.nama + ' <small class="text-muted">(' + (f.number || '-') + ')</small></div>' +
                        '<button class="btn btn-sm btn-warning btn-resend-one" data-id="' + f.idsiswa + '">Kirim Ulang</button>' +
                        '</div>' +
                        '<small class="text-danger" id="status-' + f.idsiswa + '">Gagal: ' + (f.error || 'Unknown error') + '</small>' +
                        '</div>';
                    $('#wa-failed-list').append(item);
                });
            } else {
                $('#wa-failed-list').html('<div class="alert alert-success">Semua notifikasi berhasil terkirim.</div>');
                $('#wa-resend-all-button').hide();
            }
        }

        function loop() {
            if (WA_CANCELLED || idx >= total) {
                renderDone();
                return;
            }
            var r = recips[idx];
            updateProgress(r.nama);
            sendOne(r, scope, function(success) {
                if (success) sent++;
                idx++;
                setTimeout(loop, SEND_DELAY_MS);
            }, false); // isRetry = false
        }

        resetModal();
        waModal.show();

        // Get recipient list
        $.ajax({
            type: 'POST',
            url: 'materi/kirim_notif_materi.php',
            data: $.extend({}, scope, { list_only: '1' }),
            dataType: 'json'
        }).done(function(resp) {
            if (resp && resp.status === 'ok' && resp.recipients && resp.recipients.length > 0) {
                recips = resp.recipients;
                total = recips.length;
                loop();
            } else {
                $('#wa-status-text').text(resp.message || 'Tidak ada siswa penerima notifikasi.');
                $('#wa-cancel-button').hide();
                $('#wa-finish-button').show();
            }
        }).fail(function() {
            $('#wa-status-text').text('Gagal memuat daftar penerima.');
            $('#wa-cancel-button').hide();
            $('#wa-finish-button').show();
        });
    }

    // --- Event Handlers ---

    // Modal buttons
    $('#wa-cancel-button').on('click', function() {
        WA_CANCELLED = true;
        $(this).prop('disabled', true).text('Membatalkan...');
    });

    $('#wa-finish-button').on('click', function() {
        waModal.hide();
        if (typeof onWaDone === 'function') {
            onWaDone();
        }
    });

    // Resend one
    $('#wa-failed-list').on('click', '.btn-resend-one', function() {
        var btn = $(this);
        var id = btn.data('id');
        var recipient = failedList.find(function(x) { return x.idsiswa == id; });
        if (recipient) {
            btn.prop('disabled', true).text('Mengirim...');
            var scope = JSON.parse($('#wa-scope-data').text() || '{}');
            sendOne(recipient, scope, function(success) {
                if (success) {
                    $('#failed-' + id).remove();
                    if (failedList.length === 0) {
                       $('#wa-failed-list').html('<div class="alert alert-success">Semua notifikasi gagal berhasil dikirim ulang.</div>');
                       $('#wa-resend-all-button').hide();
                    }
                } else {
                    btn.prop('disabled', false).text('Kirim Ulang');
                    $('#status-' + id).text('Gagal lagi: ' + (recipient.error || ''));
                }
            }, true); // isRetry = true
        }
    });

    // Resend all
    $('#wa-resend-all-button').on('click', function() {
        $(this).prop('disabled', true);
        var resendButtons = $('#wa-failed-list .btn-resend-one').get();
        var i = 0;
        function resendLoop() {
            if (i >= resendButtons.length) {
                $('#wa-resend-all-button').prop('disabled', false);
                return;
            }
            $(resendButtons[i]).trigger('click');
            i++;
            setTimeout(resendLoop, 350); // a bit slower to avoid race conditions
        }
        resendLoop();
    });

    // Form submission
    function handleFormSubmit(formId, url) {
        $(formId).submit(function(e) {
            e.preventDefault();
            tinymce.triggerSave();
            var data = new FormData(this);
            data.append('ajax', '1');
            data.append('defer_wa', '1');
            var submitBtn = $(this).find('button[type=submit]');

            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                enctype: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
                },
                success: function(data) {
                    var j = {};
                    try { j = typeof data === 'string' ? JSON.parse(data) : data; } catch(e) {}
                    
                    if (j.status === 'ok' || (typeof data === 'string' && data.trim() === 'OK')) {
                        iziToast.success({ title: 'Berhasil!', message: 'Data materi berhasil disimpan.', position: 'topRight' });
                        var $form = $(formId);
                        var scope = {
                            kelas: JSON.stringify($form.find("select[name='kelas[]']").val() || []),
                            mapel_kode: $form.find("select[name='mapel']").val() || '',
                            guru: CURRENT_GURU_ID,
                            judul: $form.find("input[name='judul']").val() || '',
                            jenis: (formId === '#formedit' ? 'ubah' : 'baru')
                        };
                        onWaDone = function() { window.location.replace('?pg=<?= enkripsi("materi") ?>'); };
                        sendNotifMateri(scope);
                    } else {
                        iziToast.error({ title: 'GAGAL!', message: (j.message || data), position: 'topRight' });
                        submitBtn.prop('disabled', false).text(formId === '#formedit' ? 'Update Materi' : 'Simpan Materi');
                    }
                },
                error: function() {
                    iziToast.error({ title: 'Error!', message: 'Terjadi kesalahan server.', position: 'topRight' });
                    submitBtn.prop('disabled', false).text(formId === '#formedit' ? 'Update Materi' : 'Simpan Materi');
                }
            });
        });
    }

    handleFormSubmit('#formmateri', 'materi/buat_materi.php');
    handleFormSubmit('#formedit', 'materi/edit.php');

    // Manual trigger button
    $(document).on('click', '#btnNotifMateri', function(e) {
        e.preventDefault();
        var $form = $('#formedit');
        var scope = {
            kelas: JSON.stringify($form.find("select[name='kelas[]']").val() || []),
            mapel_kode: $form.find("select[name='mapel']").val() || '',
            guru: CURRENT_GURU_ID,
            judul: $form.find("input[name='judul']").val() || '',
            jenis: 'ubah'
        };
        if (!scope.kelas || scope.kelas === '[]' || !scope.mapel_kode || !scope.judul) {
            iziToast.error({ title: 'Kurang Lengkap', message: 'Pastikan kelas, mapel, dan judul terisi.', position: 'topRight' });
            return;
        }
        onWaDone = function() { iziToast.info({ title: 'Info', message: 'Proses notifikasi selesai.', position: 'topRight' }); };
        sendNotifMateri(scope);
    });

    // --- Initializations ---

    // TinyMCE
    tinymce.init({
        selector: '.editor1',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist backcolor forecolor | code | link image media',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        paste_data_images: true,
        images_upload_handler: function(blobInfo, success, failure) {
            success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
        },
        setup: function(editor) {
            editor.on('change', function() {
                tinymce.triggerSave();
            });
        }
    });

    // Chatbot
    const chatbotToggler = document.querySelector(".chatbot-toggler");
    const chatInput = document.querySelector(".chat-input textarea");
    const sendChatBtn = document.querySelector(".chat-input #send-btn");
    const chatbox = document.querySelector(".chatbox");
    const body = document.querySelector("body");
    let userMessage;
    const API_URL = "materi/ai_search.php";
    const createChatLi = (message, className) => {
        const chatLi = document.createElement("li");
        chatLi.classList.add("chat", className);
        let chatContent = `<p>${message.replace(/\n/g, '<br>')}</p>`;
        chatLi.innerHTML = chatContent;
        return chatLi;
    }
    const generateResponse = (incomingChatLi) => {
        const messageElement = incomingChatLi.querySelector("p");
        const formData = new FormData();
        formData.append('query', userMessage);
        fetch(API_URL, { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                messageElement.innerHTML = (data.message || "Maaf, saya tidak mengerti.").replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            }).catch(() => {
                messageElement.textContent = "Oops! Terjadi kesalahan. Silakan coba lagi nanti.";
            }).finally(() => chatbox.scrollTo(0, chatbox.scrollHeight));
    }
    const handleChat = () => {
        userMessage = chatInput.value.trim();
        if (!userMessage) return;
        chatInput.value = "";
        chatInput.style.height = "55px";
        chatbox.appendChild(createChatLi(userMessage, "outgoing"));
        chatbox.scrollTo(0, chatbox.scrollHeight);
        setTimeout(() => {
            const incomingChatLi = createChatLi("Sedang mencari...", "incoming");
            chatbox.appendChild(incomingChatLi);
            chatbox.scrollTo(0, chatbox.scrollHeight);
            generateResponse(incomingChatLi);
        }, 600);
    }
    chatInput.addEventListener("input", () => {
        chatInput.style.height = "auto";
        chatInput.style.height = `${chatInput.scrollHeight}px`;
    });
    sendChatBtn.addEventListener("click", handleChat);
    chatbotToggler.addEventListener("click", () => body.classList.toggle("show-chatbot"));
    chatInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleChat();
        }
    });
});
</script>

</body>
</html>
