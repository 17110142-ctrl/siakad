<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">INPUT DATA TIDAK HADIR</h5>
            </div>
            <div class="card-body">
                <div id="progressbox" class="mb-3" style="display:none"></div>
                <?php if (isset($user) && strtolower($user['level']) == 'admin'): ?>
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-sm btn-success" id="btnHadirSemua">
                        <i class="fa fa-check-circle" style="margin-right:6px;"></i> Hadir Semua
                    </button>
                </div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover" style="font-size:12px">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>ROMBEL</th>
                                <th>STATUS</th>
                                <th class="text-center">INPUT</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $tanggal = date('Y-m-d');
                        $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE NOT EXISTS (
                            SELECT * FROM absensi WHERE siswa.id_siswa=absensi.idsiswa AND absensi.tanggal='$tanggal')");
                        while ($data = mysqli_fetch_array($query)) :
                            $no++;
                        ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['kelas'] ?></td>
                                <td><span class="badge bg-secondary">Belum Absen</span></td>
                                <td>
                                    <select class="form-select input-absen" data-id="<?= $data['id_siswa'] ?>" data-nama="<?= $data['nama'] ?>" data-nis="<?= $data['nis'] ?>" data-kelas="<?= $data['kelas'] ?>">
                                        <option value="">Pilih</option>
                                        <option value="S">Sakit</option>
                                        <option value="I">Izin</option>
                                        <option value="A">Alpa</option>
                                        <option value="H">Hadir</option>
                                    </select>
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

<script>
$(document).ready(function () {
    if ($.fn.dataTable.isDataTable('#datatable1')) {
        $('#datatable1').DataTable().destroy();
    }

    $('#datatable1').DataTable({
        "paging": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "lengthChange": false,
        "order": [[2, 'asc']],
    });

    function submitAbsensi(el, ket, options) {
        var opts = $.extend({
            showAlert: true,
            showSpinner: true,
            fadeOutRow: true,
            onResult: null,
            onAlways: null,
            extraData: null
        }, options || {});

        var spinnerEl = null;

        var payload = {
            id: el.data('id'),
            nis: el.data('nis'),
            nama: el.data('nama'),
            kelas: el.data('kelas'),
            ket: ket
        };

        if (opts.extraData && typeof opts.extraData === 'object') {
            $.extend(payload, opts.extraData);
        }

        var request = $.ajax({
            type: 'POST',
            url: 'absen/tabsen.php?pg=siswa',
            data: payload,
            dataType: 'json',
            beforeSend: function () {
                el.prop('disabled', true);
                if (opts.showSpinner) {
                    spinnerEl = $('<span class="spinner-border spinner-border-sm text-primary ms-2" role="status"></span>');
                    el.after(spinnerEl);
                }
            }
        });

        request.done(function (res) {
            var debugMessages = Array.isArray(res.debug) ? res.debug.join('\n') : '';

            if (res.status === 'OK') {
                if (opts.showAlert) {
                    alert('Absensi berhasil disimpan!\n\n--- LOG DEBUG ---\n' + debugMessages);
                }
                if (opts.fadeOutRow) {
                    el.closest('tr').fadeOut(500, function () { $(this).remove(); });
                }
                if (typeof opts.onResult === 'function') {
                    opts.onResult(true, res);
                }
            } else {
                if (opts.showAlert) {
                    alert('TERJADI ERROR!\n\nPesan: ' + res.message + '\n\n--- LOG DEBUG ---\n' + debugMessages);
                }
                el.prop('disabled', false);
                if (typeof opts.onResult === 'function') {
                    opts.onResult(false, res);
                }
            }
        });

        request.fail(function (jqXHR, textStatus, errorThrown) {
            if (opts.showAlert) {
                alert('Koneksi ke Server Gagal!\n\nCek file absen/tabsen.php.\nError: ' + textStatus + ' - ' + errorThrown);
            }
            el.prop('disabled', false);
            if (typeof opts.onResult === 'function') {
                opts.onResult(false, { status: 'ERROR', message: textStatus, errorThrown: errorThrown });
            }
        });

        request.always(function () {
            if (spinnerEl) {
                spinnerEl.remove();
            }
            if (typeof opts.onAlways === 'function') {
                opts.onAlways();
            }
        });

        return request;
    }

    $('.input-absen').on('change', function () {
        var el = $(this);
        var ket = el.val();
        if (ket === '') return;

        submitAbsensi(el, ket, { showAlert: true, showSpinner: true });
    });

    // Modal untuk progress bulk hadir
    var bulkModalHtml = `
    <div class="modal fade" id="bulkHadirModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Proses Hadir Semua</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="bulkCloseX" style="display:none;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div id="bulk-progress-box">
              <div class="d-flex align-items-center">
                <div class="spinner-border text-primary mr-2" role="status" id="bulkSpinner" style="width:1.5rem;height:1.5rem;"></div>
                <div id="bulk-progress-text" class="small text-muted flex-grow-1">Menyiapkan proses...</div>
              </div>
              <div class="progress mt-2" style="height:8px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="bulkProgressBar" role="progressbar" style="width:0%"></div>
              </div>
              <div id="bulk-failed-wrap" class="mt-2" style="display:none;">
                <div class="small text-danger">Tidak terkirim:</div>
                <div id="bulk-failed-list" class="small text-danger"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-warning" id="bulkResendBtn" style="display:none;">Kirim Ulang Gagal</button>
            <button type="button" class="btn btn-secondary" id="bulkDoneBtn" data-dismiss="modal" disabled>Selesai</button>
          </div>
        </div>
      </div>
    </div>`;

    if ($('#bulkHadirModal').length === 0) {
        $('body').append(bulkModalHtml);
    }
    var canUseModal = (typeof $.fn.modal === 'function');

    $('#btnHadirSemua').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var button = $(this);
        var selectable = $('.input-absen').filter(function () {
            return !$(this).prop('disabled') && $(this).is(':visible');
        }).toArray();

        if (selectable.length === 0) {
            // Tampilkan modal info singkat
            if (canUseModal && $('#bulkHadirModal').length) {
                $('#bulk-progress-text').text('Tidak ada siswa yang perlu diproses.');
                $('#bulkSpinner').hide();
                $('#bulkProgressBar').css('width', '100%').removeClass('progress-bar-animated');
                $('#bulkDoneBtn').prop('disabled', false);
                $('#bulkHadirModal').modal('show');
            } else {
                $('#progressbox').show().html('<div class="alert alert-info mb-0">Tidak ada siswa yang perlu diproses.</div>');
            }
            return;
        }

        if (!confirm('Yakin menandai semua siswa sebagai hadir?')) {
            return;
        }

        button.prop('disabled', true);

        // Reset modal UI
        if (canUseModal && $('#bulkHadirModal').length) {
            $('#bulkSpinner').show();
            $('#bulkProgressBar').addClass('progress-bar-animated').css('width', '0%');
            $('#bulk-progress-text').text('Menyiapkan proses...');
            $('#bulk-failed-wrap').hide();
            $('#bulk-failed-list').empty();
            $('#bulkDoneBtn').prop('disabled', true);
            $('#bulkHadirModal').modal({backdrop: 'static', keyboard: false}).modal('show');
        } else {
            $('#progressbox').show().html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" style="width:50px;"></div><div id="bulk-progress-text" class="mt-2 small text-muted"></div>');
        }

        var total = selectable.length;
        var processed = 0;
        var successCount = 0;
        var failedList = [];
        var failedLogIds = [];

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function updateProgress() {
            var text = 'Memproses ' + processed + ' dari ' + total + ' siswa. Berhasil: ' + successCount + ', Gagal: ' + failedList.length + '.';
            if (failedList.length > 0) {
                var failedNames = failedList.map(escapeHtml).join(', ');
                text += '<br><span class="text-danger">Tidak terkirim: ' + failedNames + '</span>';
            }
            $('#bulk-progress-text').html(text);
            var percent = Math.round((processed / total) * 100);
            $('#bulkProgressBar').css('width', percent + '%');
            if (failedList.length > 0) {
                $('#bulk-failed-wrap').show();
                $('#bulk-failed-list').text(failedList.join(', '));
            }
        }

        updateProgress();

        function processNext(index) {
            if (index >= total) {
                var summary = 'Selesai memproses ' + total + ' siswa. Berhasil: ' + successCount + ', Gagal: ' + failedList.length + '.';
                $('#bulk-progress-text').html(summary);
                $('#bulkSpinner').hide();
                $('#bulkProgressBar').removeClass('progress-bar-animated').css('width', '100%');
                $('#bulkDoneBtn').prop('disabled', false);
                if (failedLogIds.length > 0) {
                    $('#bulkResendBtn').show().prop('disabled', false).text('Kirim Ulang Gagal');
                } else {
                    $('#bulkResendBtn').hide();
                }
                button.prop('disabled', false);
                return;
            }

            var el = $(selectable[index]);
            el.val('H');

            submitAbsensi(el, 'H', {
                showAlert: false,
                showSpinner: false,
                onResult: function (success, res) {
                    processed++;
                    var nama = el.data('nama');
                    if (success) {
                        successCount++;
                        if (res && res.wa && !res.wa.ok) {
                            failedList.push(nama);
                            if (res.wa.log_id) { failedLogIds.push(res.wa.log_id.toString()); }
                        }
                    } else {
                        if (!nama) { nama = 'ID ' + el.data('id'); }
                        failedList.push(nama);
                        if (res && res.wa && res.wa.log_id) { failedLogIds.push(res.wa.log_id.toString()); }
                    }
                    updateProgress();
                },
                onAlways: function () {
                    processNext(index + 1);
                },
                extraData: { auto: 1 }
            });
        }

        processNext(0);
    });

    // Resend WA gagal (bulk)
    $(document).off('click', '#bulkResendBtn').on('click', '#bulkResendBtn', function(){
        var $btn = $(this);
        if ($btn.prop('disabled')) return;
        $btn.prop('disabled', true).text('Mengirim ulang...');
        $.ajax({
            type: 'POST',
            url: '../myhome/wa_absen_api.php',
            data: { action: 'resend_ids', ids: failedLogIds },
            traditional: true,
            dataType: 'json',
            timeout: 20000
        }).done(function(resp){
            if (!resp || resp.status !== 'ok') {
                $('#bulk-progress-text').append('<div class="text-danger mt-2">Gagal memproses ulang (respon tidak valid).</div>');
                $btn.prop('disabled', false).text('Kirim Ulang');
                return;
            }

            if ((resp.failed || 0) === 0) {
                $('#bulk-progress-text').append('<div class="text-success mt-2">Semua notifikasi gagal berhasil dikirim ulang.</div>');
                $btn.hide();
            } else {
                var sent = resp.sent || 0;
                var failed = resp.failed || 0;
                $('#bulk-progress-text').append('<div class="text-warning mt-2">Kirim ulang selesai. Berhasil: '+sent+', Gagal: '+failed+'.</div>');
                $('#bulk-progress-text').append('<div class="text-muted">Silakan coba lagi atau tekan Selesai untuk memuat ulang halaman.</div>');
                $btn.prop('disabled', false).text('Kirim Ulang');
            }
        }).fail(function(){
            $('#bulk-progress-text').append('<div class="text-danger mt-2">Gagal terhubung ke server saat kirim ulang.</div>');
            $('#bulk-progress-text').append('<div class="text-muted">Periksa koneksi, lalu klik Kirim Ulang atau tekan Selesai untuk memuat ulang.</div>');
            $btn.prop('disabled', false).text('Kirim Ulang');
        });
    });

    $(document).off('click', '#bulkDoneBtn').on('click', '#bulkDoneBtn', function(){
        location.reload();
    });
});
</script>
