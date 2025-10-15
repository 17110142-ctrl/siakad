<?php
defined('APK') or exit('No Access');
// Mengambil data mesin absen
$mesin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mesin_absen WHERE id='$setting[mesin]'"));
// Menghitung jumlah siswa yang datanya lengkap
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where t_lahir<>''"));

// =================================================================
// DIPERBAIKI: Mengurutkan berdasarkan kolom 'kelas' yang benar
// =================================================================
$semua_siswa = [];
// Memastikan urutan menggunakan kolom 'kelas' sesuai struktur tabel Anda
$query_siswa = mysqli_query($koneksi, "
    SELECT s.nis, s.nama
    FROM siswa s
    ORDER BY s.kelas, s.nama ASC
");
if ($query_siswa) {
    while ($data_siswa = mysqli_fetch_assoc($query_siswa)) {
        $semua_siswa[] = $data_siswa;
    }
}
?>

<!-- =================================================================
BARU: Menambahkan Style untuk membuat dropdown bisa di-scroll
================================================================= -->
<style>
.select2-results__options {
    max-height: 250px; /* Atur tinggi maksimal dropdown, sesuaikan jika perlu */
    overflow-y: auto;
}
</style>

<?php if ($ac == '') : ?>
<div class="row">
    <!-- Kolom Pengaturan Kartu -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">KARTU SISWA</h5>
                <div class="pull-right">
                    <a href="?pg=<?= enkripsi('cetak') ?>&ac=<?= enkripsi('update') ?>" class='btn btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Update Siswa"><i class="material-icons">upload</i>Update Data Siswa</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="formpengaturan">
                            <input type="hidden" name="id" value="<?= $setting['mesin'] ?>">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label fw-bold">Model Kartu</label>
                                <div class="col-sm-9">
                                    <select name='model' class='form-select' required='true'>
                                        <option value="<?= $mesin['model'] ?>"><?= $mesin['model'] ?></option>
                                        <option value="">Pilih Model</option>
                                        <option value="Potrait">Potrait</option>
                                        <option value="Landscape">Landscape</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label fw-bold">Bg Depan</label>
                                <div class="col-sm-5">
                                    <input type='file' name='depan' class='form-control' />
                                </div>
                                <div class="col-sm-4">
                                    <?php if ($mesin['depan'] != '') : ?>
                                        <img src="<?= $homeurl ?>/images/kartu/<?= $mesin['depan'] ?>" height='100px' class="img-thumbnail" />
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label fw-bold">Bg Belakang</label>
                                <div class="col-sm-5">
                                    <input type='file' name='belakang' class='form-control' />
                                </div>
                                <div class="col-sm-4">
                                    <?php if ($mesin['belakang'] != '') : ?>
                                        <img src="<?= $homeurl ?>/images/kartu/<?= $mesin['belakang'] ?>" height='100px' class="img-thumbnail" />
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-sm-12 text-end">
                                    <?php if ($jsiswa == 0) : ?>
                                        <button class='btn btn-secondary' disabled> Simpan</button>
                                    <?php else : ?>
                                        <button type='submit' class='btn btn-primary'> Simpan</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Cetak -->
    <div class="col-md-4">
        <!-- Cetak Depan -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">CETAK DEPAN</h5>
            </div>
            <div class="card-body">
                <form action="<?= ($mesin['model'] == 'Potrait') ? 'kartu/potrait.php' : 'kartu/landscape.php'; ?>" target="_blank" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dari Siswa</label>
                        <select name="id" class="form-select select2" required="required" style="width: 100%;">
                            <option value="">Pilih Siswa...</option>
                            <?php foreach ($semua_siswa as $siswa) : ?>
                                <option value="<?= $siswa['nis'] ?>">(<?= $siswa['nis'] ?>) <?= htmlspecialchars($siswa['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sampai Siswa</label>
                        <select name="ids" class="form-select select2" required="required" style="width: 100%;">
                            <option value="">Pilih Siswa...</option>
                            <?php foreach ($semua_siswa as $siswa) : ?>
                                <option value="<?= $siswa['nis'] ?>">(<?= $siswa['nis'] ?>) <?= htmlspecialchars($siswa['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="form-text">
                        <?php if ($mesin['model'] == 'Potrait') : ?>
                            Maximal 6 Orang per lembar
                        <?php else : ?>
                            Maximal 8 Orang per lembar
                        <?php endif; ?>
                    </p>
                    <div class="d-grid">
                        <?php if ($jsiswa == 0) : ?>
                            <button class='btn btn-secondary' disabled> <i class="material-icons">print</i> Cetak</button>
                        <?php else : ?>
                            <button type="submit" class="btn btn-primary"><i class="material-icons">print</i> Cetak</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cetak Belakang -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">CETAK BELAKANG</h5>
            </div>
            <div class="card-body">
                <form action="<?= ($mesin['model'] == 'Potrait') ? 'kartu/potrait2.php' : 'kartu/landscape2.php'; ?>" target="_blank" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dari Siswa</label>
                        <select name="id" class="form-select select2" required="required" style="width: 100%;">
                            <option value="">Pilih Siswa...</option>
                            <?php foreach ($semua_siswa as $siswa) : ?>
                                <option value="<?= $siswa['nis'] ?>">(<?= $siswa['nis'] ?>) <?= htmlspecialchars($siswa['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sampai Siswa</label>
                        <select name="ids" class="form-select select2" required="required" style="width: 100%;">
                            <option value="">Pilih Siswa...</option>
                            <?php foreach ($semua_siswa as $siswa) : ?>
                                <option value="<?= $siswa['nis'] ?>">(<?= $siswa['nis'] ?>) <?= htmlspecialchars($siswa['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="form-text">
                        <?php if ($mesin['model'] == 'Potrait') : ?>
                            Maximal 6 Orang per lembar
                        <?php else : ?>
                            Maximal 8 Orang per lembar
                        <?php endif; ?>
                    </p>
                    <div class="d-grid">
                        <?php if ($jsiswa == 0) : ?>
                            <button class='btn btn-secondary' disabled> <i class="material-icons">print</i> Cetak</button>
                        <?php else : ?>
                            <button type="submit" class="btn btn-primary"><i class="material-icons">print</i> Cetak</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =================================================================
SCRIPT UNTUK FORM DAN SELECT2
================================================================= -->
<script>
    // Script AJAX untuk form pengaturan (sudah ada sebelumnya)
    $('#formpengaturan').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'kartu/tsetting.php',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                // Tampilkan loading indicator jika ada
                console.log('Mengirim data pengaturan...');
            },
            success: function(data) {
                console.log('Pengaturan berhasil disimpan!');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan: " + error);
            }
        });
        return false;
    });

    // Inisialisasi Select2 pada dropdown
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5', // Gunakan tema Bootstrap 5
            placeholder: 'Cari dan Pilih Siswa...',
            allowClear: true
        });
    });
</script>

<?php elseif ($ac == enkripsi('update')) : ?>
    <?php
    $jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE alamat<>''"));
    ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">UPDATE DATA SISWA</h5>
                    <a href="kartu/proses.php" class="btn btn-sm btn-link pull-right" data-toggle="tooltip" data-placement="top" title="Download Format"><i class="fa fa-download"></i> Download Format</a>
                </div>
                <div class="card-body">
                    <form id='formsiswa'>
                        <div class='col-md-12'>
                            <label>Pilih File Excel</label>
                            <div class="input-group">
                                <input type='file' name='file' class='form-control' required='true' accept=".xls,.xlsx" />
                                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">storage</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">SISWA </span>
                            <span class="widget-stats-amount"><?= $jsiswa; ?> PD</span>
                            <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#formsiswa').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'kartu/import_siswa.php',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    console.log('Mengimpor data siswa...');
                },
                success: function(data) {
                    console.log('Import berhasil!');
                    setTimeout(function() {
                        window.location.replace('?pg=<?= enkripsi("cetak") ?>');
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan import: " + error);
                }
            });
            return false;
        });
    </script>
<?php endif; ?>
