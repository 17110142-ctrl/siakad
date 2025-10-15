<?php defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">INPUT TUGAS BELAJAR</h5>
            </div>
            <div class="card-body">
                <form id="formtugas" enctype="multipart/form-data">
                    <!-- Mata Pelajaran -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">Mata Pelajaran</label>
                        <div class="col-md-9">
                            <select name='mapel' id='mapel_select' class='form-select' required>
                                <option value=''>Pilih Mata Pelajaran</option>
                                <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC"); ?>
                                <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                    <option value="<?= $mapel['kode'] ?>"><?= $mapel['nama_mapel'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                    </div>
                    <!-- Tautkan Materi -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">Tautkan pada Materi (Opsional)</label>
                        <div class="col-md-9">
                            <select name='id_materi' id='materi_select' class='form-select'>
                                <option value=''>Pilih Mata Pelajaran Terlebih Dahulu</option>
                            </select>
                        </div>
                    </div>
                    <!-- Judul Tugas -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">Judul Tugas</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="judul" placeholder="Judul Tugas" required>
                        </div>
                    </div>
                    <!-- Isi Tugas -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">Tugas Belajar</label>
                        <div class="col-md-9">
                            <textarea name='isitugas' id='isitugas-editor' class='editor1' rows='10'></textarea>
                        </div>
                    </div>
                    <!-- Kelas -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">Kelas</label>
                        <div class="col-md-9">
                            <select name='kelas[]' class='form-control form-control-sm select2' multiple='multiple' style='width:100%' required='true'>
                                <option value="">Pilih Kelas</option>
                                <?php $lev = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC"); ?>
                                <?php while ($kelas = mysqli_fetch_array($lev)) : ?>
                                    <option value="<?= $kelas['kelas'] ?>"><?= $kelas['kelas'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                    </div>
                    <!-- Waktu Pengerjaan -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-md-3 col-form-label bold">Waktu Pengerjaan</label>
                        <div class="col-md-4">
                            <input type='datetime-local' name='tgl_mulai' class='form-control' required='true' />
                        </div>
                        <div class="col-md-1 text-center">s/d</div>
                        <div class="col-md-4">
                            <input type='datetime-local' name='tgl_selesai' class='form-control' required='true' />
                        </div>
                    </div>
                    <!-- File Pendukung -->
                    <div class="row mb-3">
                        <label class="col-md-3 col-form-label bold">File Pendukung</label>
                        <div class="col-md-9">
                            <input type="file" class="form-control-file" name="file">
                            <small class="form-text text-muted">format file (doc/docx/xls/xlsx/pdf)</small>
                        </div>
                    </div>
                    <br>
                    <div class='modal-footer'>
                        <button type='submit' class='btn btn-primary'>Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal baru untuk proses pengiriman notifikasi -->
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
                <div id="failed-section" class="mt-3" style="display:none;">
                    <h6>Gagal Terkirim:</h6>
                    <ul id="failed-notif-list" class="list-group"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="finish-notif-button" style="display:none;">Selesai</button>
                <button type="button" class="btn btn-warning" id="resend-notif-button" style="display:none;">Kirimkan Ulang</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#mapel_select').change(function() {
            var mapel_kode = $(this).val();
            if (mapel_kode !== '') {
                $.ajax({
                    url: 'tugas/get_materi.php',
                    type: 'POST',
                    data: { mapel_kode: mapel_kode },
                    success: function(response) {
                        $('#materi_select').html(response);
                    }
                });
            } else {
                $('#materi_select').html('<option value="">Pilih Mata Pelajaran Terlebih Dahulu</option>');
            }
        });

        tinymce.init({
            selector: '.editor1',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste formula'
            ],
            toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | formula code | imagetools link image paste ',
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            paste_data_images: true,
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });
            }
        });

        $('#formtugas').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('isitugas', tinymce.get('isitugas-editor').getContent());
            
            var submitButton = $(this).find('button[type="submit"]');

            $.ajax({
                type: 'POST',
                url: 'tugas/buat_tugas.php',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                },
                success: function(response) {
                    if (response.status === 'ok') {
                        if (response.students && response.students.length > 0) {
                            $('#notif-progress-modal').modal('show');
                            sendNotificationsSequentially(response.students, response.id_tugas);
                        } else {
                            swal({ title: 'Berhasil!', text: 'Tugas berhasil disimpan (Tidak ada siswa untuk dinotifikasi).', type: 'success', timer: 2000, showConfirmButton: false })
                            .then(() => { window.location.replace('?pg=<?= enkripsi("tugas") ?>'); });
                        }
                    } else {
                        swal('Gagal!', response.message || 'Gagal menyimpan data tugas.', 'error');
                        submitButton.prop('disabled', false).html('Simpan');
                    }
                },
                error: function() {
                    swal('Error!', 'Terjadi kesalahan koneksi saat menyimpan data.', 'error');
                    submitButton.prop('disabled', false).html('Simpan');
                }
            });
        });

        let currentTugasId = null;
        let lastFailedSendsB64 = '';

        function sendNotificationsSequentially(students, id_tugas) {
            let index = 0;
            let totalSiswa = students.length;
            let failedSends = [];
            currentTugasId = id_tugas;

            function sendNext() {
                if (index >= totalSiswa) {
                    $('#notif-status-text').text('Semua notifikasi telah diproses!');
                    if (failedSends.length === 0) {
                        $('#failed-section').hide();
                        $('#resend-notif-button').hide();
                    }
                    $('#finish-notif-button').show();
                    if (failedSends.length > 0) {
                        try {
                            var json = JSON.stringify(failedSends);
                            lastFailedSendsB64 = btoa(unescape(encodeURIComponent(json)));
                        } catch(e) { lastFailedSendsB64 = ''; }
                        $('#resend-notif-button').show();
                    }
                    return;
                }

                let student = students[index];
                let percent = Math.round(((index + 1) / totalSiswa) * 100);

                $('#notif-status-text').html('Mengirim ke: <span class="fw-bold">' + student.nama + '</span>');
                $('#notif-progress-bar').css('width', percent + '%').text(percent + '%');
                
                $.ajax({
                    url: 'tugas/kirim_notif_satu.php',
                    type: 'POST',
                    data: {
                        id_tugas: id_tugas,
                        nama_siswa: student.nama,
                        nowa_siswa: student.nowa,
                        type: 'baru'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status !== 'ok') {
                            failedSends.push(student);
                            $('#failed-section').show();
                            $('#failed-notif-list').append('<li class="list-group-item list-group-item-danger">' + student.nama + ' (' + student.nowa + ')</li>');
                        }
                    },
                    error: function() {
                        failedSends.push(student);
                        $('#failed-section').show();
                        $('#failed-notif-list').append('<li class="list-group-item list-group-item-danger">' + student.nama + ' (' + student.nowa + ') - Gagal Koneksi</li>');
                    },
                    complete: function() {
                        index++;
                        setTimeout(sendNext, 200); 
                    }
                });
            }
            sendNext();
        }

        $('#finish-notif-button').on('click', function() {
            $('#notif-progress-modal').modal('hide');
            window.location.replace('?pg=<?= enkripsi("tugas") ?>');
        });

        // Kirim ulang notifikasi untuk daftar gagal
        $('#resend-notif-button').on('click', function(){
            var btn = $(this);
            btn.prop('disabled', true).text('Memproses...');
            var postData = {
                ajax: '1',
                id_tugas: currentTugasId,
                // type tidak dikirim agar default 'perubahan' dipakai oleh endpoint
            };
            if (lastFailedSendsB64) {
                postData.failed_base64 = lastFailedSendsB64;
            }
            $.ajax({
                url: 'tugas/resend_notif.php',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(resp){
                    if (resp && resp.status === 'ok') {
                        // Update daftar gagal jika masih ada yang tersisa
                        $('#failed-notif-list').empty();
                        if (resp.failed > 0) {
                            $('#failed-section').show();
                            resp.remaining.forEach(function(s){
                                var nama = s.nama || '-';
                                var nohp = s.nohp || s.nowa || '-';
                                $('#failed-notif-list').append('<li class="list-group-item list-group-item-danger">'+nama+' ('+nohp+')</li>');
                            });
                            try {
                                var json = JSON.stringify(resp.remaining);
                                lastFailedSendsB64 = btoa(unescape(encodeURIComponent(json)));
                            } catch(e) { lastFailedSendsB64 = ''; }
                            swal({ title: 'Sebagian gagal', text: 'Berhasil: '+resp.succeeded+'\nGagal: '+resp.failed, type: 'warning' });
                            btn.prop('disabled', false).text('Kirimkan Ulang');
                        } else {
                            $('#failed-section').hide();
                            swal({ title: 'Selesai', text: 'Semua pesan terkirim.', type: 'success' })
                            .then(function(){ window.location.replace('?pg=<?= enkripsi("tugas") ?>'); });
                        }
                    } else {
                        swal('Gagal', 'Resend gagal diproses.', 'error');
                        btn.prop('disabled', false).text('Kirimkan Ulang');
                    }
                },
                error: function(){
                    swal('Error', 'Koneksi gagal.', 'error');
                    btn.prop('disabled', false).text('Kirimkan Ulang');
                }
            });
        });
    });
</script>
