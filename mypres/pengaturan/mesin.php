<?php
defined('APK') or exit('No Access');

// Handle Aksi Form Hari Libur (Tambah)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi_libur'])) {
    if ($_POST['aksi_libur'] == 'tambah') {
        $tanggal_libur = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
        $keterangan_libur = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
        $level_kelas = mysqli_real_escape_string($koneksi, $_POST['level']); // Data kelas baru
        
        $query_tambah_libur = "INSERT INTO hari_libur (tanggal, keterangan, level) VALUES ('$tanggal_libur', '$keterangan_libur', '$level_kelas')";
        mysqli_query($koneksi, $query_tambah_libur);
    }
    // Untuk request AJAX, kita hentikan script di sini. JS akan handle reload.
    exit();
}

// Handle Hapus Hari Libur (via GET dari AJAX)
if (isset($_GET['hapus_libur'])) {
    $id_libur = (int)$_GET['hapus_libur'];
    
    $query_hapus_libur = "DELETE FROM hari_libur WHERE id = $id_libur";
    mysqli_query($koneksi, $query_hapus_libur);

    // Hentikan script setelah selesai, JS akan handle reload
    exit();
}


// Fetch data awal
$mesin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mesin_absen WHERE id='$setting[mesin]'"));
$libur_nasional_query = mysqli_query($koneksi, "SELECT * FROM hari_libur ORDER BY tanggal DESC");
?>
<div class="row">
    <!-- Kolom Kiri: Menampilkan Data -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">DATA MASTER PENGATURAN</h5>
            </div>
            <div class="card-body">
                <!-- Tabel Setting Mesin -->
                <h6 class="card-subtitle mb-2 text-muted">Setting Mesin Aktif</h6>
                <div class="card-box table-responsive">
                    <table class="table table-bordered table-hover" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th>NAMA MESIN</th>
                                <th>API WA</th>
                                <th>HARI SEKOLAH</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $mesin['mesin'] ?? 'Belum diatur' ?></td>
                                <td><?= $setting['url_api'] ?? 'Belum diatur' ?></td>
                                <td><?= $setting['hari_sekolah'] ?? '6' ?> Hari</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>

                <!-- Tabel Daftar Hari Libur -->
                <h6 class="card-subtitle mb-2 text-muted">Daftar Hari Libur</h6>
                <div class="card-box table-responsive">
                    <table id="datatable-libur" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no_libur = 0;
                            while ($data_libur = mysqli_fetch_array($libur_nasional_query)) :
                                $no_libur++;
                            ?>
                                <tr>
                                    <td><?= $no_libur ?></td>
                                    <td><?= date('d-m-Y', strtotime($data_libur['tanggal'])) ?></td>
                                    <td><?= $data_libur['keterangan'] ?></td>
                                    <td><?= $data_libur['level'] ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm hapus-libur" data-id="<?= $data_libur['id'] ?>">Hapus</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Form Input -->
    <div class="col-md-4">
        <!-- Form Setting Mesin -->
        <div class="card widget widget-payment-request mb-4">
            <div class="card-header">
                <h5 class="card-title">SETTING MESIN</h5>
            </div>
            <div class="card-body">
                <form id='formmesin'>
                    <label class="bold">Mesin Presensi</label>
                    <div class="input-group mb-3">
                        <select class="form-select" name="mesin" required style="width: 100%">
                            <option value="<?= $setting['mesin'] ?>"><?= $mesin['mesin'] ?></option>
                            <option value=''>-- Pilih Mesin --</option>
                            <?php
                            $lev_query = mysqli_query($koneksi, "SELECT * FROM mesin_absen");
                            while ($msn = mysqli_fetch_array($lev_query)) {
                                echo "<option value='$msn[id]'>$msn[mesin]</option>";
                            } ?>
                        </select>
                    </div>
                    <label class="bold">URL API WA</label>
                    <div class="input-group mb-3">
                        <input type="text" name="api" value="<?= $setting['url_api'] ?>" class="form-control">
                    </div>
                    <label class="bold">Jumlah Hari Sekolah</label>
                    <div class="input-group mb-3">
                        <select class="form-select" name="hari_sekolah" required>
                            <option value="6" <?= ($setting['hari_sekolah'] ?? 6) == 6 ? 'selected' : '' ?>>6 Hari (Senin - Sabtu)</option>
                            <option value="5" <?= ($setting['hari_sekolah'] ?? 6) == 5 ? 'selected' : '' ?>>5 Hari (Senin - Jumat)</option>
                        </select>
                    </div>
                    <div class="widget-payment-request-actions mt-lg-3 d-flex">
                        <button type="submit" class="btn btn-primary flex-grow-1">Simpan Mesin</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Tambah Hari Libur -->
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">TAMBAH HARI LIBUR</h5>
            </div>
            <div class="card-body">
                <form id="form-tambah-libur" method="POST">
                    <input type="hidden" name="aksi_libur" value="tambah">
                    <label class="bold">Tanggal Libur</label>
                    <div class="input-group mb-3">
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <label class="bold">Keterangan</label>
                    <div class="input-group mb-3">
                        <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Hari Kemerdekaan" required>
                    </div>
                    <!-- PERUBAHAN: Menambahkan pilihan kelas -->
                    <label class="bold">Berlaku Untuk Kelas</label>
                    <div class="input-group mb-3">
                        <select class="form-select" name="level" required>
                            <option value="Semua">Semua Kelas</option>
                            <?php
                            $query_kelas = mysqli_query($koneksi, "SELECT DISTINCT level FROM kelas ORDER BY level ASC");
                            while ($kelas_data = mysqli_fetch_array($query_kelas)) {
                                echo "<option value='$kelas_data[level]'>Kelas $kelas_data[level]</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="widget-payment-request-actions mt-lg-3 d-flex">
                        <button type="submit" class="btn btn-success flex-grow-1">Tambah Libur</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables untuk tabel
        $('#datatable-libur').DataTable();
        
        // Script untuk form mesin
        $('#formmesin').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'pengaturan/tmesin.php',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                    $('.progress-bar').animate({
                        width: "30%"
                    }, 500);
                },
                success: function(data) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                }
            });
        });

        // Script untuk form tambah hari libur dengan popup gaya lama
        $('#form-tambah-libur').submit(function(e) {
            e.preventDefault(); 
            var data = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '', 
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                    $('.progress-bar').animate({
                        width: "30%"
                    }, 500);
                },
                success: function(response) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#progressbox').html('<div style="color:red;">Terjadi kesalahan!</div>');
                }
            });
        });

        // Script untuk hapus hari libur dengan popup gaya lama tanpa konfirmasi
        $('#datatable-libur').on('click', '.hapus-libur', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '?pg=<?= $_GET['pg'] ?>&hapus_libur=' + id,
                beforeSend: function() {
                    $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                    $('.progress-bar').animate({
                        width: "30%"
                    }, 500);
                },
                success: function(response) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#progressbox').html('<div style="color:red;">Terjadi kesalahan!</div>');
                }
            });
        });
    });
</script>
