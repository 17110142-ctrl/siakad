<?php
defined('APK') or exit('No Access');
// Ambil semua data pesan yang diperlukan dengan ID yang benar
$pesan1 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='1'")); // Hadir
$pesan2 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='2'")); // Pulang
$pesan9 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='9'")); // Izin
$pesan10 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='10'")); // Sakit
$pesan11 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='11'")); // Alpa
?>

<div class="row">
    <!-- BARIS PERTAMA: NOTIF HADIR & PULANG -->
    <div class="col-xl-6">
        <div class="card widget">
            <div class="card-header"><h5 class="card-title" style="color:blue;">NOTIF HADIR SISWA</h5></div>
            <div class="card-body">
                <form id="form_hadir" class="row g-2">
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Pembuka</label><input type="text" class="form-control" name="pesan1" value="<?= htmlspecialchars($pesan1['pesan1'] ?? '') ?>"></div>
                    <div class="col-md-8"><label class="form-label fw-bold">Isi Pesan</label><textarea class="form-control" name="pesan2" rows="4"><?= htmlspecialchars($pesan1['pesan2'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly>Nama Siswa</textarea></div>
                    <div class="col-md-8"><textarea class="form-control" name="pesan3" rows="4"><?= htmlspecialchars($pesan1['pesan3'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea></div>
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Penutup</label><textarea class="form-control" name="pesan4" rows="4"><?= htmlspecialchars($pesan1['pesan4'] ?? '') ?></textarea></div>
                    <div class="d-grid gap-2 mt-3"><button type="submit" class="btn btn-primary">Simpan Notif Hadir</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card widget">
            <div class="card-header"><h5 class="card-title" style="color:red;">NOTIF PULANG SISWA</h5></div>
            <div class="card-body">
                <form id="form_pulang" class="row g-2">
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Pembuka</label><input type="text" class="form-control" name="pesan1" value="<?= htmlspecialchars($pesan2['pesan1'] ?? '') ?>"></div>
                    <div class="col-md-8"><label class="form-label fw-bold">Isi Pesan</label><textarea class="form-control" name="pesan2" rows="4"><?= htmlspecialchars($pesan2['pesan2'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly>Nama Siswa</textarea></div>
                    <div class="col-md-8"><textarea class="form-control" name="pesan3" rows="4"><?= htmlspecialchars($pesan2['pesan3'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea></div>
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Penutup</label><textarea class="form-control" name="pesan4" rows="4"><?= htmlspecialchars($pesan2['pesan4'] ?? '') ?></textarea></div>
                    <div class="d-grid gap-2 mt-3"><button type="submit" class="btn btn-danger">Simpan Notif Pulang</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- BARIS KEDUA: NOTIF IZIN & SAKIT -->
    <div class="col-xl-6">
        <div class="card widget">
            <div class="card-header"><h5 class="card-title" style="color:#ffc107;">NOTIF IZIN SISWA</h5></div>
            <div class="card-body">
                <form id="form_izin" class="row g-2">
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Pembuka</label><input type="text" class="form-control" name="pesan1" value="<?= htmlspecialchars($pesan9['pesan1'] ?? '') ?>"></div>
                    <div class="col-md-8"><label class="form-label fw-bold">Isi Pesan</label><textarea class="form-control" name="pesan2" rows="4"><?= htmlspecialchars($pesan9['pesan2'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly>Nama Siswa</textarea></div>
                    <div class="col-md-8"><textarea class="form-control" name="pesan3" rows="4"><?= htmlspecialchars($pesan9['pesan3'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea></div>
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Penutup</label><textarea class="form-control" name="pesan4" rows="4"><?= htmlspecialchars($pesan9['pesan4'] ?? '') ?></textarea></div>
                    <div class="d-grid gap-2 mt-3"><button type="submit" class="btn btn-warning">Simpan Notif Izin</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card widget">
            <div class="card-header"><h5 class="card-title" style="color:#0dcaf0;">NOTIF SAKIT SISWA</h5></div>
            <div class="card-body">
                <form id="form_sakit" class="row g-2">
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Pembuka</label><input type="text" class="form-control" name="pesan1" value="<?= htmlspecialchars($pesan10['pesan1'] ?? '') ?>"></div>
                    <div class="col-md-8"><label class="form-label fw-bold">Isi Pesan</label><textarea class="form-control" name="pesan2" rows="4"><?= htmlspecialchars($pesan10['pesan2'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly>Nama Siswa</textarea></div>
                    <div class="col-md-8"><textarea class="form-control" name="pesan3" rows="4"><?= htmlspecialchars($pesan10['pesan3'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea></div>
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Penutup</label><textarea class="form-control" name="pesan4" rows="4"><?= htmlspecialchars($pesan10['pesan4'] ?? '') ?></textarea></div>
                    <div class="d-grid gap-2 mt-3"><button type="submit" class="btn btn-info">Simpan Notif Sakit</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- BARIS KETIGA: NOTIF ALPA -->
    <div class="col-xl-6">
        <div class="card widget">
            <div class="card-header"><h5 class="card-title" style="color:#6c757d;">NOTIF ALPA SISWA</h5></div>
            <div class="card-body">
                <form id="form_alpa" class="row g-2">
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Pembuka</label><input type="text" class="form-control" name="pesan1" value="<?= htmlspecialchars($pesan11['pesan1'] ?? '') ?>"></div>
                    <div class="col-md-8"><label class="form-label fw-bold">Isi Pesan</label><textarea class="form-control" name="pesan2" rows="4"><?= htmlspecialchars($pesan11['pesan2'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly>Nama Siswa</textarea></div>
                    <div class="col-md-8"><textarea class="form-control" name="pesan3" rows="4"><?= htmlspecialchars($pesan11['pesan3'] ?? '') ?></textarea></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Variabel</label><textarea class="form-control" rows="4" readonly><?= date('d M Y H:i') ?></textarea></div>
                    <div class="col-md-12"><label class="form-label fw-bold">Pesan Penutup</label><textarea class="form-control" name="pesan4" rows="4"><?= htmlspecialchars($pesan11['pesan4'] ?? '') ?></textarea></div>
                    <div class="d-grid gap-2 mt-3"><button type="submit" class="btn btn-secondary">Simpan Notif Alpa</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function handleFormSubmit(formId, url) {
        $(formId).submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Template notifikasi berhasil disimpan!');
                    window.location.reload();
                },
                error: function() {
                    alert('Gagal menyimpan data!');
                }
            });
        });
    }

    handleFormSubmit('#form_hadir', 'pesan/tsetting.php?pg=hadir');
    handleFormSubmit('#form_pulang', 'pesan/tsetting.php?pg=pulang');
    handleFormSubmit('#form_izin', 'pesan/tsetting.php?pg=izin');
    handleFormSubmit('#form_sakit', 'pesan/tsetting.php?pg=sakit');
    handleFormSubmit('#form_alpa', 'pesan/tsetting.php?pg=alpa');
});
</script>
