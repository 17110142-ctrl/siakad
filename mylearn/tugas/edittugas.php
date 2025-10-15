<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Mengambil ID tugas dari URL dan datanya dari database
$id_tugas = $_GET['id'];
$tugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas='$id_tugas'"));
$kelas_terpilih = $tugas ? unserialize($tugas['kelas']) : [];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">EDIT TUGAS BELAJAR</h5>
            </div>
            <div class="card-body">
                <form id="formEditTugas">
                    <input type="hidden" name="id" value="<?= $tugas['id_tugas'] ?>">
                    
                    <div class="row mb-1">
                        <label class="col-md-3 col-form-label bold">Mata Pelajaran</label>
                        <div class="col-sm-7">
                            <select name='mapel' class='form-select' style='width:100%' required>
                                <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC"); ?>
                                <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                    <option value="<?= $mapel['kode'] ?>" <?= ($tugas['mapel'] == $mapel['kode']) ? 'selected' : '' ?>>
                                        <?= $mapel['nama_mapel'] ?>
                                    </option>
                                <?php endwhile ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-md-3 col-form-label bold">Judul Tugas</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="judul" value="<?= htmlspecialchars($tugas['judul']) ?>" required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-md-3 col-form-label bold">Tugas Belajar</label>
                        <div class="col-sm-9">
                            <textarea name='isitugas' id='isitugas-editor' class='editor1' rows='10' cols='80' style='width:100%;'><?= htmlspecialchars($tugas['tugas']) ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-md-3 col-form-label bold">Kelas</label>
                        <div class="col-md-9">
                            <select name='kelas[]' id='soalkelas' class='form-control form-control-sm select2' multiple='multiple' style='width:100%' required='true'>
                                <?php $lev = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY kelas ASC"); ?>
                                <?php while ($kelas = mysqli_fetch_array($lev)) : ?>
                                    <option value="<?= $kelas['kelas'] ?>" <?= (is_array($kelas_terpilih) && in_array($kelas['kelas'], $kelas_terpilih)) ? 'selected' : '' ?>>
                                        <?= $kelas['kelas'] ?>
                                    </option>
                                <?php endwhile ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-md-3 col-form-label bold">Mulai</label>
                        <div class="col-sm-3">
                            <input type='text' name='tgl_mulai' value="<?= $tugas['tgl_mulai'] ?>" class='tgl form-control' autocomplete='off' required='true' />
                        </div>
                        <label class="col-md-1 col-form-label bold">Selesai</label>
                        <div class="col-sm-3">
                            <input type='text' name='tgl_selesai' value="<?= $tugas['tgl_selesai'] ?>" class='tgl form-control' autocomplete='off' required='true' />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-md-3 col-form-label bold">File Pendukung</label>
                        <div class="col-sm-9">
                            <?php if (!empty($tugas['file'])) : ?>
                                <p class="form-text text-muted">File saat ini: <a href="../../tugas/<?= $tugas['file'] ?>" target="_blank"><?= $tugas['file'] ?></a></p>
                            <?php endif; ?>
                            <input type="file" class="form-control-file" name="file" aria-describedby="fileHelpId">
                            <small id="fileHelpId" class="form-text text-muted">Unggah file baru untuk mengganti.</small>
                        </div>
                    </div>
                    <br>
                    <div class='modal-footer'>
                        <div class='box-tools pull-right btn-group'>
                            <button type='submit' class='btn btn-primary'>Simpan Perubahan</button>
                        </div>
                    </div>
                    <div id="progressbox" class="mt-2" style="display:none; text-align:center;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- REVISI: Modal baru untuk proses pengiriman notifikasi -->
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
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.select2').select2();
        
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
            images_upload_handler: function(blobInfo, success, failure) {
                success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
            },
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });
            }
        });
    });

    // REVISI TOTAL: Logika submit form dan pengiriman notifikasi
    $('#formEditTugas').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('isitugas', tinymce.get('isitugas-editor').getContent());
        
        var submitButton = $(this).find('button[type="submit"]');

        // Langkah 1: Simpan data tugas ke database
        $.ajax({
            type: 'POST',
            url: 'tugas/simpan_dan_get_siswa.php', // Menggunakan script baru
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
                    // Jika penyimpanan berhasil dan ada daftar siswa, mulai proses notifikasi
                    if (response.students && response.students.length > 0) {
                        $('#notif-progress-modal').modal('show');
                        sendNotificationsSequentially(response.students, response.id_tugas);
                    } else {
                        swal({ title: 'Berhasil!', text: 'Tugas berhasil diperbarui (Tidak ada siswa untuk dinotifikasi).', type: 'success', timer: 2000, showConfirmButton: false })
                        .then(() => { window.location.replace('?pg=tugas'); });
                    }
                } else {
                    swal('Gagal!', response.message || 'Gagal menyimpan data tugas.', 'error');
                    submitButton.prop('disabled', false).html('Simpan Perubahan');
                }
            },
            error: function() {
                swal('Error!', 'Terjadi kesalahan koneksi saat menyimpan data.', 'error');
                submitButton.prop('disabled', false).html('Simpan Perubahan');
            }
        });
    });

    // Langkah 2: Fungsi untuk mengirim notifikasi satu per satu
    function sendNotificationsSequentially(students, id_tugas) {
        let index = 0;
        let totalSiswa = students.length;
        let failedSends = [];

        function sendNext() {
            if (index >= totalSiswa) {
                // Proses Selesai
                $('#notif-status-text').text('Semua notifikasi telah diproses!');
                if (failedSends.length === 0) {
                    $('#failed-section').hide();
                }
                $('#finish-notif-button').show();
                return;
            }

            let student = students[index];
            let percent = Math.round(((index + 1) / totalSiswa) * 100);

            // Update UI Modal
            $('#notif-status-text').html('Mengirim ke: <span class="fw-bold">' + student.nama + '</span>');
            $('#notif-progress-bar').css('width', percent + '%').text(percent + '%');
            
            // AJAX untuk mengirim satu notifikasi
            $.ajax({
                url: 'tugas/kirim_notif_satu.php', // Menggunakan script baru
                type: 'POST',
                data: {
                    id_tugas: id_tugas,
                    nama_siswa: student.nama,
                    nowa_siswa: student.nowa
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
                    // Jeda singkat sebelum mengirim berikutnya agar animasi terlihat lebih mulus
                    setTimeout(sendNext, 200); 
                }
            });
        }

        sendNext(); // Mulai proses pengiriman
    }

    // Handler untuk tombol Selesai di modal notifikasi
    $('#finish-notif-button').on('click', function() {
        $('#notif-progress-modal').modal('hide');
        swal({ title: 'Selesai!', text: 'Proses pengiriman notifikasi telah selesai.', type: 'info', timer: 1500, showConfirmButton: false })
        .then(() => { window.location.replace('?pg=tugas'); });
    });
</script>
