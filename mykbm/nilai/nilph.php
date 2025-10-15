<?php
defined('APK') or exit('No Access');
$hari = date('D');
?>
<?php if ($ac == '') : ?>
	<?php
	if (empty($_GET['kkm'])) {
		$kkm = "";
	} else {
		$kkm = $_GET['kkm'];
	}
	if (empty($_GET['k'])) {
		$kelasmu = "";
	} else {
		$kelasmu = $_GET['k'];
	}
	if (empty($_GET['g'])) {
		$gurumu = "";
	} else {
		$gurumu = $_GET['g'];
	}
	if (empty($_GET['m'])) {
		$mapelmu = "";
	} else {
		$mapelmu = $_GET['m'];
	}
	$tgl = $_GET['t'] ?? date('Y-m-d'); // Default to today if not set
	$map = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran where id='$mapelmu' "));
	$kuri = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas where kelas='$kelasmu'"));
	?>
	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h5 class="bold">NILAI HARIAN <span class="badge badge-primary"><?= $kelasmu ?></span> <span class="badge badge-warning"><?= $map['kode'] ?? '' ?></span> </h5>
				</div>
				<div class="card-body">
					<form id="formnilai">
						<input type="hidden" name="kuri" value="<?= $kuri['kurikulum'] ?? '' ?>">
						<div class="row">
							<div class="col-md-9">
								<?php if (isset($kuri['kurikulum']) && $kuri['kurikulum'] == '1') : ?>
									<select name="materi" class='form-select' style='width:100%' required>
										<option value="">Pilih Materi</option>
										<?php
										$sql = mysqli_query($koneksi, "SELECT * FROM deskripsi WHERE mapel='$mapelmu' AND level='$kuri[level]'");
										while ($data = mysqli_fetch_array($sql)) {
											echo '<option value="' . $data['kd'] . '">' . $data['deskripsi'] . '</option> ';
										}
										?>
									</select>
								<?php else : ?>
									<select name="materi" class='form-select' style='width:100%' required>
										<option value="">Pilih Materi</option>
										<?php
										$sql = mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$mapelmu' AND level='$kuri[level]'");
										while ($data = mysqli_fetch_array($sql)) {
											echo '<option value="' . $data['lm'] . '">' . $data['materi'] . '</option> ';
										}
										?>
									</select>
								<?php endif; ?>
							</div>
							<div class="col-md-3">
								<?php if ($kelasmu != '') : ?>
									<a href="?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>" class="btn btn-primary"><i class="material-icons">visibility</i>LIHAT INPUT NILAI</a>
								<?php endif; ?>
							</div>
						</div>
						<br>
						<table id="datatab" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
							<thead>
								<tr>
									<th width="5%">NO</th>
									<th>NAMA SISWA</th>
									<th>NILAI</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 0;
								if (!empty($kelasmu)) {
									$query = mysqli_query($koneksi, "SELECT id_siswa,kelas,nama FROM siswa WHERE kelas='$kelasmu'");
									while ($data = mysqli_fetch_array($query)) :
										$no++;
								?>
										<tr>
											<td><?= $no; ?></td>
											<td><?= $data['nama'] ?></td>
											<td>
												<input type="number" name="nilai[]" class="form-control" value="0" required="true">
												<input type="hidden" name="tanggal[]" value="<?= $tgl ?>">
												<input type="hidden" name="idsiswa[]" value="<?= $data['id_siswa'] ?>">
												<input type="hidden" name="kelas[]" value="<?= $data['kelas'] ?>">
												<input type="hidden" name="mapel[]" value="<?= $mapelmu ?>">
												<input type="hidden" name="guru[]" value="<?= $gurumu ?>">
												<input type="hidden" name="kkm[]" value="<?= $kkm ?>">
											</td>
										</tr>
								<?php
									endwhile;
								}
								?>
							</tbody>
						</table>
                        <div class="kanan">
                            <?php if ($kelasmu != '') : ?>
                                <button type="submit" class="btn btn-primary">SIMPAN</button>
                            <?php endif; ?>
                        </div>
                        <div id="progressbox" class="mt-2"></div>
                    </form>
                </div>
            </div>
        </div>
		<div class="col-md-4">
			<div class="card widget widget-payment-request">
				<div class="card-header">
					<h5 class="bold">FILTER NILAI HARIAN</h5>
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
							<div class="alert alert-custom" role="alert">
								<strong>Perhatian ! </strong><br>
								<span>Penilaian Harian dapat dilakukan jika Hari sesuai Jadwal Mengajar</span>
							</div>
							<label class="bold">Tanggal</label>
							<div class="input-group mb-1">
								<input type="text" name="tgl" class="form-control datepicker" value="<?= date('Y-m-d') ?>" required="true">
							</div>
							<label class="bold">Guru Pengampu</label>
							<div class="input-group mb-1">
								<select id="guru" class='form-select guru' style='width:100%' required>
									<?php
									if ($user['level'] == 'admin') :
										$sql = mysqli_query($koneksi, "SELECT hari,guru FROM jadwal_mapel GROUP BY guru");
									elseif ($user['level'] == 'guru') :
										$sql = mysqli_query($koneksi, "SELECT hari,guru FROM jadwal_mapel WHERE guru='$user[id_user]' GROUP BY guru");
									endif;
									while ($data = mysqli_fetch_array($sql)) {
										$peg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$data[guru]'"));
										echo '<option value="' . $data['guru'] . '">' . $peg['nama'] . '</option>';
									}
									?>
								</select>
							</div>
							<label class="bold">Kelas</label>
							<div class="input-group mb-1">
								<select id="kelas" class='form-select kelas' style='width:100%' required>
									<option value="">Pilih Kelas</option>
									<?php
									if ($user['level'] == 'admin') :
										$sql = mysqli_query($koneksi, "SELECT hari,kelas FROM jadwal_mapel GROUP BY kelas");
									elseif ($user['level'] == 'guru') :
										$sql = mysqli_query($koneksi, "SELECT hari,kelas,guru FROM jadwal_mapel WHERE guru='$user[id_user]' GROUP BY kelas");
									endif;
									while ($data = mysqli_fetch_array($sql)) {
										echo '<option value="' . $data['kelas'] . '">' . $data['kelas'] . '</option> ';
									}
									?>
								</select>
							</div>
							<label class="bold">Mata Pelajaran</label>
							<div class="input-group mb-1">
								<select id="mapel" class='form-select mapel' style='width:100%' required>
								</select>
							</div>
							<label class="bold">KKM </label>
							<div class="input-group mb-1">
								<input type="number" id="kkm" class="form-control kkm" value="70" required="true">
							</div>
							<div class="widget-payment-request-actions m-t-lg d-flex">
								<button id="pilih" class="btn btn-primary flex-grow-1 m-l-xxs">Pilih Kelas</button>
							</div>
							<script type="text/javascript">
								$('#pilih').click(function() {
									var k = $('.kelas').val();
									var g = $('.guru').val();
									var m = $('.mapel').val();
									var kkm = $('.kkm').val();
									var tgl = $('.datepicker').val();
									location.replace("?pg=<?= enkripsi('nilai') ?>&k=" + k + "&g=" + g + "&m=" + m + "&kkm=" + kkm + "&t=" + tgl);
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$("#kelas, #guru, .datepicker").change(function() {
			var kelas = $("#kelas").val();
			var guru = $("#guru").val();
			var tgl = $(".datepicker").val();
			$.ajax({
				type: "POST",
				url: "absen/tabsen.php?pg=mapel",
				data: {
					kelas: kelas,
					guru: guru,
					tgl: tgl
				},
				success: function(response) {
					$("#mapel").html(response);
				}
			});
		});
	</script>
    <script>
        // Utility modal khusus WA progress (overlay sederhana, independen dari Bootstrap)
        function renderProgressBox(html){
            // Inject modal wrapper jika belum ada
            if ($('#waModalWrap').length === 0) {
                var tpl = ''
                  + '<div id="waModalWrap" style="position:fixed;inset:0;display:none;z-index:1050;">'
                  +   '<div class="wa-modal-backdrop" style="position:absolute;inset:0;background:rgba(0,0,0,.35);"></div>'
                  +   '<div class="wa-modal" style="position:relative;margin:5vh auto;background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,.2);width:760px;max-width:95vw;max-height:90vh;display:flex;flex-direction:column;">'
                  +     '<div class="wa-modal-header" style="padding:10px 14px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">'
                  +       '<h5 style="margin:0;font-weight:600;">Pengiriman Notifikasi</h5>'
                  +       '<button type="button" class="wa-modal-close btn btn-sm btn-light" aria-label="Close">&times;</button>'
                  +     '</div>'
                  +     '<div id="waModalBody" class="wa-modal-body" style="padding:12px;overflow:auto;"></div>'
                  +     '<div class="wa-modal-footer" style="padding:10px 14px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;">'
                  +       '<button type="button" class="btn btn-secondary wa-modal-close">Tutup</button>'
                  +     '</div>'
                  +   '</div>'
                  + '</div>';
                $('body').append(tpl);
                $(document).off('click.waModalClose', '.wa-modal-close, #waModalWrap .wa-modal-backdrop')
                  .on('click.waModalClose', '.wa-modal-close, #waModalWrap .wa-modal-backdrop', function(){
                      $('#waModalWrap').hide();
                  });
            }
            $('#waModalBody').html('<div id="waModalPanel">'+ html +'</div>');
            $('#waModalWrap').show();
        }

        function sendNotifHarian(scope, attempt, onDone){
            // Ambil daftar penerima terlebih dahulu
            var recips = [];
            var total = 0, sent = 0, fail = 0, idx = 0;
            var failedList = [];
            var apiUrlForDebug = '';
            var WA_CANCELLED = false; // flag pembatalan oleh user

            function pct(){ return total ? Math.round((idx/total)*100) : 0; }
            var SEND_DELAY_MS = 240; // jeda antar kirim agar gateway stabil
            function showWorking(name){
                var percent = pct();
                var html = ''
                    + '<div><label class="sandik" style="color:blue;">Mengirim kepada ' + (name ? ('<b>'+name+'</b> ') : '') + '('+percent+'%)</label> '
                    + '<img src="../images/animasi.gif" style="width:30px;"></div>'
                    + '<div style="height:6px;background:#eee;border-radius:3px;margin-top:6px;overflow:hidden;">'
                    +   '<div style="width:'+percent+'%;height:6px;background:#4caf50;"></div>'
                    + '</div>'
                    + '<div style="margin-top:6px;font-size:12px;color:#555;">Terkirim: '+sent+' | Gagal: '+fail+' | Total: '+total+'</div>'
                    + '<div style="margin-top:10px;display:flex;gap:6px;justify-content:flex-end;">'
                    +   '<button id="waCancelBtn" class="btn btn-sm btn-warning">Batalkan</button>'
                    + '</div>';
                renderProgressBox(html);
            }
            function renderDone(){
                var html = ''
                    + '<div style="margin-bottom:6px;"><b>' + (WA_CANCELLED ? 'Pengiriman dibatalkan' : 'Pengiriman selesai') + '</b> — terkirim '+sent+' dari '+total+'.</div>'
                    + (apiUrlForDebug ? '<div style="font-size:12px;color:#6b7280">API: '+ apiUrlForDebug +'</div>' : '');
                if (failedList.length > 0) {
                    html += '<div style="color:#b45309;margin-bottom:6px;">Gagal terkirim ('+failedList.length+'):</div>';
                    html += '<div id="waFailedList" style="max-height:40vh; overflow:auto; padding-right:4px;">';
                    failedList.forEach(function(f){
                        var extra = '<div style="font-size:12px;color:#6b7280;">HTTP: '+(f.http_code||0)+' — '+(f.error||'')+'</div>';
                        if (f.gateway_resp_snippet) {
                           try {
                             extra += '<pre style="white-space:pre-wrap;background:#f8fafc;border:1px solid #e5e7eb;border-radius:6px;padding:6px;font-size:11px;max-width:100%;overflow:auto;">'
                                   +  $('<div/>').text(f.gateway_resp_snippet).html()
                                   +  '</pre>';
                           } catch(e) {}
                        }
                        html += '<div class="failed-item" data-id="'+f.idsiswa+'" style="display:block;margin:6px 0;padding:6px;border:1px dashed #f59e0b;border-radius:8px;">'
                             +  '<div style="display:flex;align-items:center;gap:6px;justify-content:space-between;">'
                             +    '<div>'+f.nama+' <span style="color:#999">('+ (f.number||'-') +')</span></div>'
                             +    '<div>'
                             +      '<button class="btn btn-sm btn-warning btn-resend-one" data-id="'+f.idsiswa+'">Kirim Ulang</button>'
                             +    '</div>'
                             +  '</div>'
                             +  '<div class="fi-extra">'+ extra +'</div>'
                             +  '<div><span class="fi-status" style="font-size:12px;color:#b91c1c;"></span></div>'
                             +  '</div>';
                    });
                    html += '</div>';
                } else {
                    html += '<div class="text-success">Semua notifikasi berhasil terkirim.</div>';
                }
                html += '<div style="margin-top:8px;display:flex;gap:6px;justify-content:flex-end;">'
                     +  (failedList.length>0 ? '<button id="waResendAll" class="btn btn-sm btn-secondary">Kirim Ulang Semua Gagal</button>' : '')
                     +  '<button id="waOkBtn" class="btn btn-sm btn-primary">OK</button>'
                     +  '</div>';
                renderProgressBox(html);
            }

            function sendOne(r, cb, isRetry){
                // Kirim ke satu penerima
                var payload = $.extend({}, scope, { ids: JSON.stringify([r.idsiswa]), debug: '1' });
                $.ajax({
                    type: 'POST',
                    url: 'nilai/kirim_notif_harian.php',
                    data: payload,
                    dataType: 'json'
                }).done(function(resp){
                    var ok = false;
                    if (resp && resp.results && resp.results.length > 0) {
                        ok = !!resp.results[0].success;
                        var det = resp.results[0] || {};
                        r.http_code = det.http_code || 0;
                        r.error = det.error || '';
                        r.gateway_resp_snippet = det.gateway_resp_snippet || '';
                        if (!apiUrlForDebug && resp.api_url) apiUrlForDebug = resp.api_url;
                    }
                    if (ok) {
                        sent++;
                        if (isRetry) {
                            failedList = failedList.filter(function(x){ return x.idsiswa !== r.idsiswa; });
                            if (fail > 0) fail--;
                        }
                    } else {
                        if (!isRetry) {
                            fail++; failedList.push($.extend({}, r));
                        } else {
                            var exist = failedList.some(function(x){ return x.idsiswa === r.idsiswa; });
                            if (!exist) { failedList.push($.extend({}, r)); }
                            fail = failedList.length;
                        }
                    }
                    cb(ok);
                }).fail(function(){
                    r.http_code = 0; r.error = 'AJAX error / tidak terhubung'; r.gateway_resp_snippet = '';
                    if (!isRetry) {
                        fail++; failedList.push($.extend({}, r));
                    } else {
                        var exist = failedList.some(function(x){ return x.idsiswa === r.idsiswa; });
                        if (!exist) { failedList.push($.extend({}, r)); }
                        fail = failedList.length;
                    }
                    cb(false);
                });
            }

            function loop(){
                if (WA_CANCELLED) { renderDone(); return; }
                if (idx >= total) { renderDone(); return; }
                var r = recips[idx];
                showWorking(r.nama);
                sendOne(r, function(){ idx++; setTimeout(loop, SEND_DELAY_MS); });
            }

            // 1) Ambil list penerima
            $.ajax({
                type: 'POST',
                url: 'nilai/kirim_notif_harian.php',
                data: $.extend({}, scope, { list_only: '1', debug: '1' }),
                dataType: 'json'
            }).done(function(resp){
                if (!resp || resp.status !== 'ok' || !resp.recipients || resp.recipients.length === 0) {
                    renderProgressBox('<div class="text-warning">Tidak ada penerima untuk dikirimi notifikasi.</div>');
                    if (onDone) onDone();
                    return;
                }
                recips = resp.recipients;
                total = recips.length;
                if (resp.api_url) apiUrlForDebug = resp.api_url;
                idx = 0; sent = 0; fail = 0; failedList = [];
                showWorking(recips[0] ? recips[0].nama : '');
                loop();
            }).fail(function(){
                renderProgressBox('<div class="text-danger">Gagal menyiapkan daftar penerima WA.</div>');
                if (onDone) onDone();
            });

            // Handler tombol di dalam modal (delegasi)
            $('#waModalBody')
              .off('click', '.btn-resend-one')
              .on('click', '.btn-resend-one', function(){
                var id = parseInt($(this).data('id'),10);
                var $row = $(this).closest('.failed-item');
                var $btn = $(this);
                $btn.prop('disabled', true).text('Mengirim...');
                var r = (failedList || []).find(function(x){ return x.idsiswa === id; });
                if (!r) { $btn.prop('disabled', false).text('Kirim Ulang'); return; }
                sendOne(r, function(ok){
                    if (ok) {
                        $row.find('.fi-status').text('Sukses');
                        $row.find('.btn-resend-one').remove();
                    } else {
                        $row.find('.fi-status').text('Gagal lagi');
                        $btn.prop('disabled', false).text('Kirim Ulang');
                    }
                }, true);
              })
              .off('click', '#waResendAll')
              .on('click', '#waResendAll', function(){
                var $btnAll = $(this);
                var toResend = failedList.slice();
                $btnAll.prop('disabled', true).text('Mengirim ulang semua...');
                var i = 0;
                function resendLoop(){
                    if (i >= toResend.length) { $btnAll.prop('disabled', false).text('Kirim Ulang Semua Gagal'); return; }
                    var r = toResend[i];
                    var $row = $('#waModalBody .failed-item[data-id="'+r.idsiswa+'"]');
                    var $status = $row.find('.fi-status');
                    $status.text('Mengirim ulang...');
                    sendOne(r, function(ok){
                        if (ok) {
                            $row.find('.btn-resend-one').remove();
                            $status.text('Sukses');
                        } else {
                            $status.text('Gagal lagi');
                        }
                        i++; setTimeout(resendLoop, SEND_DELAY_MS);
                    }, true);
                }
                resendLoop();
              })
              .off('click', '#waCancelBtn')
              .on('click', '#waCancelBtn', function(){
                  WA_CANCELLED = true;
                  var $btn = $(this);
                  $btn.prop('disabled', true).text('Membatalkan...');
              })
              .off('click', '#waOkBtn')
              .on('click', '#waOkBtn', function(){ $('#waModalWrap').hide(); if (onDone) onDone(); });
        }

$('#formnilai').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            data.append('ajax', '1');
            data.append('defer_wa', '1');
            $.ajax({
                type: 'POST',
                url: 'nilai/input.php',
                enctype: 'multipart/form-data',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function(){
                    renderProgressBox('<div><label class="sandik" style="color:blue;">Menyimpan nilai...</label> <img src="../images/animasi.gif" style="width:30px;"></div>');
                },
                success: function(resp) {
                    if (resp && resp.status === 'ok' && resp.scope) {
                        // Kirim WA dengan progress dan retry otomatis
                        sendNotifHarian(resp.scope, 1, function(){
                            setTimeout(function(){
                                window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
                            }, 800);
                        });
                    } else {
                        // Fallback: coba kirim WA berdasarkan konteks form jika backend tidak mengembalikan scope (mis. error parsing JSON)
                        try {
                            var fallbackScope = {
                                kelas: '<?= addslashes($kelasmu) ?>',
                                mapel: <?= (int)($mapelmu ?: 0) ?>,
                                guru: <?= (int)($gurumu ?: 0) ?>,
                                tanggal: '<?= addslashes($tgl) ?>'
                            };
                            if (fallbackScope.kelas && fallbackScope.mapel && fallbackScope.guru && fallbackScope.tanggal) {
                                sendNotifHarian(fallbackScope, 1, function(){
                                    setTimeout(function(){
                                        window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
                                    }, 800);
                                });
                                return;
                            }
                        } catch (e) { /* abaikan */ }
                        // Jika tak bisa fallback, tetap redirect
                        setTimeout(function(){
                            window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
                        }, 800);
                    }
                },
                error: function(){
                    // Jika error simpan (harusnya tidak), fallback ke reload halaman
                    setTimeout(function(){
                        window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
                    }, 1200);
                }
            });
            return false;
        });
    </script>
<?php elseif ($ac == enkripsi('lihat')) : ?>
	<?php
	$kelas = dekripsi($_GET['k']);
	$mapel = dekripsi($_GET['m']);
	$guru = dekripsi($_GET['g']);
	$mpl = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel]);
	$peg = fetch($koneksi, 'users', ['id_user' => $guru]);

	$pertemuan_query = mysqli_query($koneksi, "SELECT DISTINCT tanggal FROM nilai_harian WHERE kelas='$kelas' AND mapel='$mapel' AND guru='$guru' ORDER BY tanggal ASC");
	$pertemuan_dates = [];
	while ($row = mysqli_fetch_assoc($pertemuan_query)) {
		$pertemuan_dates[] = $row['tanggal'];
	}

	$tanggal_filter = '';
	if (isset($_GET['p_tgl']) && in_array($_GET['p_tgl'], $pertemuan_dates)) {
		$tanggal_filter = $_GET['p_tgl'];
	} elseif (!empty($pertemuan_dates)) {
		$tanggal_filter = end($pertemuan_dates);
	}
	?>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between">
					<h5 class="bold">NILAI HARIAN <?= $mpl['kode'] ?> KELAS <?= $kelas ?></h5>
					<a href="?pg=<?= enkripsi('nilai') ?>" class="btn btn-sm btn-danger">Kembali Ke Input Nilai</a>
				</div>
				<div class="card-body">

					<div class="row mb-3">
						<div class="col-md-6">
							<form method="GET" action="">
								<input type="hidden" name="pg" value="<?= $_GET['pg'] ?>">
								<input type="hidden" name="ac" value="<?= $_GET['ac'] ?>">
								<input type="hidden" name="k" value="<?= $_GET['k'] ?>">
								<input type="hidden" name="m" value="<?= $_GET['m'] ?>">
								<input type="hidden" name="g" value="<?= $_GET['g'] ?>">

								<div class="input-group">
									<label class="input-group-text" for="pertemuanFilter">Pilih Pertemuan</label>
									<select name="p_tgl" id="pertemuanFilter" class="form-select">
										<option value="">-- Semua Pertemuan --</option>
										<?php
										if (!empty($pertemuan_dates)) {
											$pertemuan_ke = 1;
											foreach ($pertemuan_dates as $date) {
												$selected = ($date == $tanggal_filter) ? 'selected' : '';
												echo "<option value='{$date}' {$selected}>Pertemuan ke-{$pertemuan_ke} (" . date('d-m-Y', strtotime($date)) . ")</option>";
												$pertemuan_ke++;
											}
										} else {
											echo "<option value=''>Belum ada data nilai</option>";
										}
										?>
									</select>
									<button class="btn btn-primary" type="submit">Filter</button>
								</div>
							</form>
						</div>
					</div>
					
                    <div class="card-box table-responsive">
                        <!-- AWAL MODIFIKASI: Form untuk proses update nilai -->
                        <form id="formUpdateNilai" action="nilai/input.php" method="POST">
                            <!-- Input hidden untuk redirect kembali ke halaman yang benar setelah update -->
                            <input type="hidden" name="k" value="<?= $_GET['k'] ?>">
                            <input type="hidden" name="m" value="<?= $_GET['m'] ?>">
                            <input type="hidden" name="g" value="<?= $_GET['g'] ?>">
                            <input type="hidden" name="p_tgl" value="<?= $tanggal_filter ?>">

							<table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
								<thead>
									<tr>
										<th width="5%">#</th>
										<th>TANGGAL</th>
										<th>KELAS</th>
										<th>NAMA SISWA</th>
										<th>K-MAT</th>
										<th width="15%">NILAI</th> <!-- Beri lebar agar input muat -->
										<th>KET</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 0;
									// PENTING: Modifikasi query untuk mengambil primary key (bernama 'id')
									$query_sql = "SELECT * FROM nilai_harian WHERE kelas='$kelas' AND mapel='$mapel' AND guru='$guru'";
									if (!empty($tanggal_filter)) {
										$query_sql .= " AND tanggal='$tanggal_filter'";
									}
									$query_sql .= " ORDER BY id ASC"; // Tambahkan order untuk konsistensi
									$query = mysqli_query($koneksi, $query_sql);

									if (mysqli_num_rows($query) > 0) {
										while ($datax = mysqli_fetch_array($query)) :
											$siswa = fetch($koneksi, 'siswa', ['id_siswa' => $datax['idsiswa']]);
											if ($datax['nilai'] >= $datax['kkm']) {
												$ket = '<span class="badge badge-success">Tuntas</span>';
											} else {
												$ket = '<span class="badge badge-danger">Tidak Tuntas</span>';
											}
											$no++;
									?>
											<tr>
												<td><?= $no; ?></td>
												<td><?= date('d-m-Y', strtotime($datax['tanggal'])) ?></td>
												<td><?= $datax['kelas'] ?></td>
												<td><?= $siswa['nama'] ?></td>
												<td>
													<?php if ($datax['kuri'] == '1') : ?>
														KD <?= $datax['materi'] ?>
													<?php else : ?>
														LM <?= $datax['materi'] ?>
													<?php endif; ?>
												</td>
												<td>
													<!-- Jadikan nilai sebagai input yang bisa diedit -->
                                                <input type="number" class="form-control form-control-sm" name="nilai[<?= $datax['id'] ?>]" value="<?= $datax['nilai'] ?>" data-initial="<?= $datax['nilai'] ?>" required>
                                                <!-- Kirim juga idsiswa untuk proses rekapitulasi nilai STS -->
                                                <input type="hidden" name="idsiswa[<?= $datax['id'] ?>]" value="<?= $datax['idsiswa'] ?>">
												</td>
												<td><?= $ket ?></td>
											</tr>
									<?php
										endwhile;
									} else {
										echo '<tr><td colspan="7" class="text-center">Tidak ada data nilai pada pertemuan ini.</td></tr>';
									}
									?>
								</tbody>
							</table>

                        <?php if (mysqli_num_rows($query) > 0) : ?>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success"><i class="material-icons">save</i> Simpan Perubahan</button>
                            </div>
                        <?php endif; ?>
                        <div id="progressbox" class="mt-2"></div>
                        </form>
                        <!-- AKHIR MODIFIKASI -->
                    </div>

				</div>
			</div>
		</div>
    </div>
<?php endif ?>

<script>
// Definisikan util & sender bila belum ada (agar aman saat mode 'lihat')
if (typeof renderProgressBox === 'undefined') {
    function renderProgressBox(html){
        if ($('#waModalWrap').length === 0) {
            var tpl = ''
              + '<div id="waModalWrap" style="position:fixed;inset:0;display:none;z-index:1050;">'
              +   '<div class="wa-modal-backdrop" style="position:absolute;inset:0;background:rgba(0,0,0,.35);"></div>'
              +   '<div class="wa-modal" style="position:relative;margin:5vh auto;background:#fff;border-radius:10px;box-shadow:0 12px 30px rgba(0,0,0,.2);width:760px;max-width:95vw;max-height:90vh;display:flex;flex-direction:column;">'
              +     '<div class="wa-modal-header" style="padding:10px 14px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">'
              +       '<h5 style="margin:0;font-weight:600;">Pengiriman Notifikasi</h5>'
              +       '<button type="button" class="wa-modal-close btn btn-sm btn-light" aria-label="Close">&times;</button>'
              +     '</div>'
              +     '<div id="waModalBody" class="wa-modal-body" style="padding:12px;overflow:auto;"></div>'
              +     '<div class="wa-modal-footer" style="padding:10px 14px;border-top:1px solid #e5e7eb;display:flex;justify-content:flex-end;gap:8px;">'
              +       '<button type="button" class="btn btn-secondary wa-modal-close">Tutup</button>'
              +     '</div>'
              +   '</div>'
              + '</div>';
            $('body').append(tpl);
            $(document).off('click.waModalClose', '.wa-modal-close, #waModalWrap .wa-modal-backdrop')
              .on('click.waModalClose', '.wa-modal-close, #waModalWrap .wa-modal-backdrop', function(){
                  $('#waModalWrap').hide();
              });
        }
        $('#waModalBody').html('<div id="waModalPanel">'+ html +'</div>');
        $('#waModalWrap').show();
    }
}
if (typeof sendNotifHarian === 'undefined') {
    function sendNotifHarian(scope, attempt, onDone){
        // Versi sequential dengan progres per siswa
        var recips = [];
        var total = 0, sent = 0, fail = 0, idx = 0;
        var failedList = [];
        var apiUrlForDebug = '';
        var SEND_DELAY_MS = 240; // jeda antar kirim agar gateway stabil

        function pct(){ return total ? Math.round((idx/total)*100) : 0; }
        function showWorking(name){
            var percent = pct();
            var html = ''
                + '<div><label class="sandik" style="color:blue;">Mengirim kepada ' + (name ? ('<b>'+name+'</b> ') : '') + '('+percent+'%)</label> '
                + '<img src="../images/animasi.gif" style="width:30px;"></div>'
                + '<div style="height:6px;background:#eee;border-radius:3px;margin-top:6px;overflow:hidden;">'
                +   '<div style="width:'+percent+'%;height:6px;background:#4caf50;"></div>'
                + '</div>'
                + '<div style="margin-top:6px;font-size:12px;color:#555;">Terkirim: '+sent+' | Gagal: '+fail+' | Total: '+total+'</div>';
                renderProgressBox(html);
                try {
                  if (apiUrlForDebug) {
                    $('#waModalBody').prepend('<div style="font-size:12px;color:#6b7280;margin-bottom:4px;">API: '+ apiUrlForDebug +'</div>');
                  }
                  // inject detail error per baris
                  if (failedList && failedList.length) {
                    var $items = $('#waModalBody .failed-item');
                    for (var i=0;i<failedList.length;i++){
                      var f = failedList[i];
                      var $row = $items.eq(i);
                      if (!$row.length) continue;
                      var extra = '<div style="font-size:12px;color:#6b7280;">HTTP: '+(f.http_code||0)+' — '+(f.error||'')+'</div>';
                      if (f.gateway_resp_snippet) {
                        extra += '<pre style="white-space:pre-wrap;background:#f8fafc;border:1px solid #e5e7eb;border-radius:6px;padding:6px;font-size:11px;max-width:100%;overflow:auto;">'
                              +  $('<div/>').text(f.gateway_resp_snippet).html()
                              +  '</pre>';
                      }
                      $row.append('<div class="fi-extra">'+extra+'</div>');
                    }
                  }
                } catch(e){}
            }
        function renderDone(){
            var html = ''
                + '<div style="margin-bottom:6px;"><b>Pengiriman selesai</b> — terkirim '+sent+' dari '+total+'.</div>'
                + (apiUrlForDebug ? '<div style="font-size:12px;color:#6b7280">API: '+ apiUrlForDebug +'</div>' : '');
            if (failedList.length > 0) {
                html += '<div style="color:#b45309;margin-bottom:6px;">Gagal terkirim ('+failedList.length+'):</div>';
                html += '<div id="waFailedList" style="max-height:40vh; overflow:auto; padding-right:4px;">';
                failedList.forEach(function(f){
                    var extra = '<div style="font-size:12px;color:#6b7280;">HTTP: '+(f.http_code||0)+' — '+(f.error||'')+'</div>';
                    if (f.gateway_resp_snippet) {
                       try {
                         extra += '<pre style="white-space:pre-wrap;background:#f8fafc;border:1px solid #e5e7eb;border-radius:6px;padding:6px;font-size:11px;max-width:100%;overflow:auto;">'
                               +  $('<div/>').text(f.gateway_resp_snippet).html()
                               +  '</pre>';
                       } catch(e) {}
                    }
                    html += '<div class="failed-item" data-id="'+f.idsiswa+'" style="display:block;margin:6px 0;padding:6px;border:1px dashed #f59e0b;border-radius:8px;">'
                         +  '<div style="display:flex;align-items:center;gap:6px;justify-content:space-between;">'
                         +    '<div>'+f.nama+' <span style="color:#999">('+ (f.number||'-') +')</span></div>'
                         +    '<div>'
                         +      '<button class="btn btn-sm btn-warning btn-resend-one" data-id="'+f.idsiswa+'">Kirim Ulang</button>'
                         +    '</div>'
                         +  '</div>'
                         +  '<div class="fi-extra">'+ extra +'</div>'
                         +  '<div><span class="fi-status" style="font-size:12px;color:#b91c1c;"></span></div>'
                         +  '</div>';
                });
                html += '</div>';
            } else {
                html += '<div class="text-success">Semua notifikasi berhasil terkirim.</div>';
            }
            html += '<div style="margin-top:8px;display:flex;gap:6px;justify-content:flex-end;">'
                 +  (failedList.length>0 ? '<button id="waResendAll" class="btn btn-sm btn-secondary">Kirim Ulang Semua Gagal</button>' : '')
                 +  '<button id="waOkBtn" class="btn btn-sm btn-primary">OK</button>'
                 +  '</div>';
            renderProgressBox(html);
        }
        function sendOne(r, cb, isRetry){
            var payload = $.extend({}, scope, { ids: JSON.stringify([r.idsiswa]), debug: '1' });
            $.ajax({
                type: 'POST',
                url: 'nilai/kirim_notif_harian.php',
                data: payload,
                dataType: 'json'
            }).done(function(resp){
                var ok = false;
                if (resp && resp.results && resp.results.length > 0) {
                    ok = !!resp.results[0].success;
                    var det = resp.results[0] || {};
                    r.http_code = det.http_code || 0;
                    r.error = det.error || '';
                    r.gateway_resp_snippet = det.gateway_resp_snippet || '';
                    if (!apiUrlForDebug && resp.api_url) apiUrlForDebug = resp.api_url;
                }
                if (ok) {
                    sent++;
                    if (isRetry) {
                        failedList = failedList.filter(function(x){ return x.idsiswa !== r.idsiswa; });
                        if (fail > 0) fail--;
                    }
                } else {
                    if (!isRetry) {
                        fail++;
                        failedList.push($.extend({}, r));
                    } else {
                        // Jika kirim ulang masih gagal, pertahankan pada daftar gagal
                        var exist = failedList.some(function(x){ return x.idsiswa === r.idsiswa; });
                        if (!exist) { failedList.push($.extend({}, r)); }
                        fail = failedList.length;
                    }
                }
                cb(ok);
            }).fail(function(){
                r.http_code = 0; r.error = 'AJAX error / tidak terhubung'; r.gateway_resp_snippet = '';
                if (!isRetry) {
                    fail++;
                    failedList.push($.extend({}, r));
                } else {
                    var exist = failedList.some(function(x){ return x.idsiswa === r.idsiswa; });
                    if (!exist) { failedList.push($.extend({}, r)); }
                    fail = failedList.length;
                }
                cb(false);
            });
        }
        function loop(){
            if (idx >= total) { renderDone(); return; }
            var r = recips[idx];
            showWorking(r.nama);
            sendOne(r, function(){ idx++; setTimeout(loop, SEND_DELAY_MS); }, false);
        }
        // Load recipients
        $.ajax({
            type: 'POST',
            url: 'nilai/kirim_notif_harian.php',
            data: $.extend({}, scope, { list_only: '1', debug: '1' }),
            dataType: 'json'
        }).done(function(resp){
            if (!resp || resp.status !== 'ok' || !resp.recipients || resp.recipients.length === 0) {
                renderProgressBox('<div class="text-warning">Tidak ada penerima untuk dikirimi notifikasi.</div>');
                if (onDone) onDone();
                return;
            }
            recips = resp.recipients; total = recips.length; idx = 0; sent = 0; fail = 0; failedList = [];
            if (resp.api_url) apiUrlForDebug = resp.api_url;
            showWorking(recips[0] ? recips[0].nama : '');
            loop();
        }).fail(function(){
            renderProgressBox('<div class="text-danger">Gagal menyiapkan daftar penerima WA.</div>');
            if (onDone) onDone();
        });

        // Delegated handlers
        $('#waModalBody')
          .off('click', '.btn-resend-one')
          .on('click', '.btn-resend-one', function(){
            var id = parseInt($(this).data('id'),10);
            var $row = $(this).closest('.failed-item');
            var $btn = $(this);
            $btn.prop('disabled', true).text('Mengirim...');
            var r = (failedList || []).find(function(x){ return x.idsiswa === id; });
            if (!r) { $btn.prop('disabled', false).text('Kirim Ulang'); return; }
            sendOne(r, function(ok){
              if (ok) {
                $row.find('.fi-status').text('Sukses');
                $row.find('.btn-resend-one').remove();
              } else {
                $row.find('.fi-status').text('Gagal lagi');
                $btn.prop('disabled', false).text('Kirim Ulang');
              }
            }, true);
          })
          .off('click', '#waResendAll')
          .on('click', '#waResendAll', function(){
            var $btnAll = $(this);
            var toResend = failedList.slice();
            $btnAll.prop('disabled', true).text('Mengirim ulang semua...');
            var i = 0;
            function resendLoop(){
              if (i >= toResend.length) { $btnAll.prop('disabled', false).text('Kirim Ulang Semua Gagal'); return; }
              var r = toResend[i];
              var $row = $('#waModalBody .failed-item[data-id="'+r.idsiswa+'"]');
              var $status = $row.find('.fi-status');
              $status.text('Mengirim ulang...');
                sendOne(r, function(ok){
                    if (ok) {
                        $row.find('.btn-resend-one').remove();
                        $status.text('Sukses');
                    } else {
                        $status.text('Gagal lagi');
                    }
                    i++; setTimeout(resendLoop, SEND_DELAY_MS);
                }, true);
            }
            resendLoop();
          })
          .off('click', '#waOkBtn')
          .on('click', '#waOkBtn', function(){ $('#waModalWrap').hide(); if (onDone) onDone(); });
    }
}

// AJAX submit untuk form update nilai (halaman Lihat Nilai)
$('#formUpdateNilai').on('submit', function(e){
    e.preventDefault();
    var $form = $(this);
    // 1) Deteksi siswa yang nilainya berubah
    var changedIds = [];
    $form.find('input[name^="nilai["]').each(function(){
        var $in = $(this);
        var name = $in.attr('name'); // bentuk: nilai[123]
        var match = name && name.match(/nilai\[(\d+)\]/);
        if (!match) return;
        var recId = match[1];
        var initial = parseFloat($in.data('initial'));
        var current = parseFloat($in.val());
        if (!isFinite(initial)) initial = 0;
        if (!isFinite(current)) current = 0;
        if (current !== initial) {
            var h = $form.find('input[name="idsiswa['+recId+']"]');
            var sid = parseInt(h.val(),10);
            if (sid && changedIds.indexOf(sid) === -1) changedIds.push(sid);
        }
    });

    var data = new FormData(this);
    data.append('ajax', '1');
    data.append('defer_wa', '1');
    $.ajax({
        type: 'POST',
        url: 'nilai/input.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function(){
            renderProgressBox('<div><label class="sandik" style="color:blue;">Menyimpan perubahan nilai...</label> <img src="../images/animasi.gif" style="width:30px;"></div>');
        },
        success: function(resp){
            var redirectLater = function(){
                setTimeout(function(){
                    var url = '?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= $_GET['k'] ?>&m=<?= $_GET['m'] ?>&g=<?= $_GET['g'] ?>';
                    <?php if (!empty($tanggal_filter)) : ?>
                    url += '&p_tgl=<?= $tanggal_filter ?>';
                    <?php endif; ?>
                    window.location.replace(url);
                }, 800);
            };

            if (changedIds.length === 0) { redirectLater(); return; }

            if (resp && resp.status === 'ok' && resp.scope) {
                var sc = $.extend({}, resp.scope, { ids: JSON.stringify(changedIds) });
                sendNotifHarian(sc, 1, redirectLater);
            } else {
                redirectLater();
            }
        },
        error: function(){
            setTimeout(function(){
                var url = '?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= $_GET['k'] ?>&m=<?= $_GET['m'] ?>&g=<?= $_GET['g'] ?>';
                <?php if (!empty($tanggal_filter)) : ?>
                url += '&p_tgl=<?= $tanggal_filter ?>';
                <?php endif; ?>
                window.location.replace(url);
            }, 1200);
        }
    });
});
</script>
<script>
// Failsafe handler untuk form input nilai agar tidak submit GET jika ada error JS sebelumnya
(function(){
  if (!window.jQuery) return;
  $(document).off('submit.fixFormNilai', '#formnilai');
  $(document).on('submit.fixFormNilai', '#formnilai', function(e){
    e.preventDefault();
    try {
      var data = new FormData(this);
      data.append('ajax','1');
      data.append('defer_wa','1');
      $.ajax({
        type: 'POST',
        url: 'nilai/input.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        beforeSend: function(){
          if (typeof renderProgressBox === 'function') {
            renderProgressBox('<div><label class="sandik" style="color:blue;">Menyimpan nilai...</label> <img src="../images/animasi.gif" style="width:30px;"></div>');
          }
        },
        success: function(resp){
          if (resp && resp.status === 'ok' && resp.scope && typeof sendNotifHarian === 'function') {
            sendNotifHarian(resp.scope, 1, function(){
              setTimeout(function(){
                window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
              }, 800);
            });
          } else {
            setTimeout(function(){
              window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
            }, 800);
          }
        },
        error: function(){
          setTimeout(function(){
            window.location.replace('?pg=<?= enkripsi('nilai') ?>&ac=<?= enkripsi('lihat') ?>&k=<?= enkripsi($kelasmu) ?>&m=<?= enkripsi($mapelmu) ?>&g=<?= enkripsi($gurumu) ?>');
          }, 1200);
        }
      });
    } catch (err) {
      // Jika terjadi error tidak terduga, tetap cegah submit GET
      console.error('Form nilai submit error:', err);
    }
    return false;
  });
})();
</script>
