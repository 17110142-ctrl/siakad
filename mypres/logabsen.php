<?php 
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

$tanggal = date('Y-m-d');

// Statistik Absensi
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND level='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND level='pegawai'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal'"));
$sql = mysqli_query($koneksi, "SELECT * FROM status");
$sts = mysqli_fetch_assoc($sql);
?>

<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">face</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">ABSENSI SISWA HARI INI</span>
                        <span class="widget-stats-amount"><?= $jabsis; ?> PD</span>
                        <span class="widget-stats-info">dari <?= $jsiswa ?> PD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-warning">
                        <i class="material-icons-outlined">face</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">ABSENSI PEGAWAI HARI INI</span>
                        <span class="widget-stats-amount"><?= $jabpeg; ?> PTK</span>
                        <span class="widget-stats-info">dari <?= $jpeg ?> PTK</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-danger">
                        <i class="material-icons-outlined">school</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">TOTAL ABSENSI</span>
                        <span class="widget-stats-amount"><?= $jtot ?></span>
                        <span class="widget-stats-info">Sekolah</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div>
                <?php if ($sts['mode'] == '1'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN MASUK</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' ORDER BY id DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                            $peg = fetch($koneksi, 'users', ['id_user' => $data['idpeg']]);
                            $info = ($data['ket'] == 'H') ? 'Hadir' : (($data['ket'] == 'S') ? 'Sakit' : (($data['ket'] == 'I') ? 'Izin' : 'Alpha'));
                        ?>
                        <li data-animation="animated fadeInDownBig" data-delay="1s">
                            <?= $data['level'] == 'siswa' ? $siswa['nama'] . " <small style='color:yellow'>( $info )</small>" : $peg['nama']; ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                <?php elseif ($sts['mode'] == '2'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN PULANG</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND pulang<>'' ORDER BY pulang DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                            $peg = fetch($koneksi, 'users', ['id_user' => $data['idpeg']]);
                        ?>
                        <li data-animation="animated fadeInDownBig" data-delay="1s">
                            <?= $data['level'] == 'siswa' ? $siswa['nama'] : $peg['nama']; ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
