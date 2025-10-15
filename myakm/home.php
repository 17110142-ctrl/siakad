<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jpes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE no_peserta<>''"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L' and no_peserta<>''"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P' and no_peserta<>''"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jbank = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM banksoal"));
$jsoal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM soal"));
$jnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai"));
?>

<?php include "top.php"; ?>



<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">face</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">PESERTA PEREMPUAN</span>
                        <span class="widget-stats-amount"><?= $jpesP; ?> PD</span>
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
                        <span class="widget-stats-title">PESERTA LAKI-LAKI</span>
                        <span class="widget-stats-amount"><?= $jpesL; ?> PD</span>
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
                    <div class="widget-stats-icon widget-stats-icon-danger">
                        <i class="material-icons-outlined">school</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">TOTAL PESERTA</span>
                        <span class="widget-stats-amount"><?= $jpes ?> PD</span>
                        <span class="widget-stats-info"><?= substr($setting['sekolah'], 0, 19) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-success">
                        <i class="material-icons-outlined">select_all</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">DATA BANK SOAL</span>
                        <span class="widget-stats-amount"><?= $jbank; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-purple">
                        <i class="material-icons-outlined">support</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">DATA SOAL</span>
                        <span class="widget-stats-amount"><?= $jsoal; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">visibility</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">DATA NILAI</span>
                        <span class="widget-stats-amount"><?= $jnil; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('bottomcache.php'); ?>
