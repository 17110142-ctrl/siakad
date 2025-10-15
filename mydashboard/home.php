<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Mengambil data siswa
$query_profil = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'");
$profil_siswa = mysqli_fetch_assoc($query_profil);

// =================================================================
// Pengecekan Kelengkapan Profil (Diringkas untuk kejelasan)
// =================================================================
// Pastikan logika pengecekan profil lengkap Anda ada di sini
// Jika profil tidak lengkap, akan dialihkan seperti kode asli Anda.
// ... (logika pengecekan profil dari kode Anda) ...


// =================================================================
// Perhitungan Statistik
// =================================================================
$jmateri = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absen_daringmapel where idsiswa='$id_siswa'"));
$jtugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM jawaban_tugas where id_siswa='$id_siswa'"));
$jnil = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_siswa FROM jawaban_tugas where id_siswa='$id_siswa' and nilai<>''"));

// =================================================================
// Logika untuk mengambil tugas aktif yang belum dikerjakan
// =================================================================
$tugas_belum_dikerjakan = [];
$sekarang = date('Y-m-d H:i:s');
$kelas_siswa = $profil_siswa['kelas'];

// 1. Ambil semua tugas yang aktif
$tugas_aktif_query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE tgl_mulai <= '$sekarang' AND tgl_selesai >= '$sekarang'");

if ($tugas_aktif_query) {
    while ($tugas = mysqli_fetch_assoc($tugas_aktif_query)) {
        // 2. Cek apakah tugas ini untuk kelas siswa
        $datakelas = unserialize($tugas['kelas']);
        if (is_array($datakelas) && (in_array($kelas_siswa, $datakelas) || in_array('semua', $datakelas))) {
            // 3. Cek apakah siswa sudah mengerjakan tugas ini
            $jawaban_cek = rowcount($koneksi, 'jawaban_tugas', ['id_siswa' => $id_siswa, 'id_tugas' => $tugas['id_tugas']]);
            if ($jawaban_cek == 0) {
                // Jika belum dikerjakan, tambahkan ke daftar
                $tugas_belum_dikerjakan[] = $tugas;
            }
        }
    }
}
?>

<?php include "top.php"; ?>

<!-- CSS untuk panel peringatan dan statistik yang dirapikan -->
<style>
    .task-warning-card {
        background: linear-gradient(135deg, #fff3f3, #ffeaea);
        border: 1px solid #ffb8b8;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(217, 83, 79, 0.1);
    }
    .task-warning-header {
        font-size: 1.8rem;
        font-weight: 700;
        color: #d9534f;
    }
    .task-item {
        border-top: 1px solid #ffe0e0;
        padding: 15px 0;
    }
    .task-item:first-child {
        border-top: none;
        padding-top: 0;
    }
    .task-title {
        font-weight: 600;
        color: #333;
    }
    .task-mapel {
        font-size: 0.9rem;
        color: #777;
    }
    .welcome-card {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 40px;
        text-align: center;
    }
    .widget-stats {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 15px;
    }
    .widget-stats .card-body {
        padding: 15px;
    }
    .widget-stats-container {
        align-items: center;
    }
    .widget-stats-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .widget-stats-icon i {
        font-size: 22px;
        color: #fff;
    }
    .widget-stats-icon-success { background-color: #d1e7dd; }
    .widget-stats-icon-success i { color: #0f5132; }
    .widget-stats-icon-purple { background-color: #e2d9f3; }
    .widget-stats-icon-purple i { color: #4c228c; }
    .widget-stats-icon-primary { background-color: #cfe2ff; }
    .widget-stats-icon-primary i { color: #084298; }

    .widget-stats-title {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
        line-height: 1.3;
    }
    .widget-stats-amount {
        font-size: 1.5rem;
        font-weight: 700;
    }
    /* PERBAIKAN: Gaya baru untuk kartu jadwal */
    .schedule-header {
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .schedule-header h4 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .schedule-header p {
        color: #6c757d;
        margin-bottom: 0;
    }
    .schedule-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #555;
    }
    .schedule-item i {
        margin-right: 10px;
        color: #888;
    }
    .status-hadir { color: #28a745; font-weight: 500; }
    .status-absen { color: #dc3545; font-weight: 500; }
</style>

<div class="row">
    <!-- Kolom Kiri: Peringatan Tugas atau Pesan Selamat Datang -->
    <div class="col-lg-8">
        <?php if (!empty($tugas_belum_dikerjakan)): ?>
            <div class="card task-warning-card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="material-icons-outlined" style="font-size: 48px; color: #d9534f;">warning</i>
                        <h2 class="task-warning-header ml-3">PERINGATAN TUGAS</h2>
                    </div>
                    <p class="text-muted">Anda memiliki beberapa tugas aktif yang belum dikerjakan. Segera selesaikan sebelum batas waktu berakhir!</p>
                    
                    <div class="mt-4">
                        <?php foreach ($tugas_belum_dikerjakan as $tugas): ?>
                            <?php
                                // Ambil nama mapel lengkap
                                $mapel_info = fetch($koneksi, 'mata_pelajaran', ['kode' => $tugas['mapel']]);
                                $nama_mapel = $mapel_info['nama_mapel'] ?? $tugas['mapel'];
                            ?>
                            <div class="task-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="task-title"><?= htmlspecialchars($tugas['judul']) ?></div>
                                    <div class="task-mapel"><?= htmlspecialchars($nama_mapel) ?></div>
                                </div>
                                <a href="?pg=bukatugas&id=<?= $tugas['id_tugas'] ?>" class="btn btn-danger">Kerjakan Sekarang</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Tampilkan gambar atau pesan selamat datang jika tidak ada tugas -->
            <div class="card">
                 <div class="card-body welcome-card">
                     <img src="../images/icon/selamat.png" alt="Selamat Datang" style="max-width: 200px;">
                     <h4 class="mt-3">Kerja Bagus!</h4>
                     <p class="text-muted">Anda tidak memiliki tugas aktif yang belum dikerjakan. Tetap semangat belajar!</p>
                 </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Kolom Kanan: Statistik yang Dirapikan -->
    <div class="col-lg-4">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-success">
                        <i class="material-icons-outlined">select_all</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">MATERI TELAH DI BACA</span>
                        <span class="widget-stats-amount"><?= $jmateri; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-purple">
                        <i class="material-icons-outlined">support</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">TUGAS DIKERJAKAN</span>
                        <span class="widget-stats-amount"><?= $jtugas; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">edit</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">DATA NILAI TUGAS</span>
                        <span class="widget-stats-amount"><?= $jnil; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$hari = date('D');
$hari_ini_info = fetch($koneksi, 'm_hari', ['inggris' => $hari]);
?>

<!-- PERBAIKAN: Header Jadwal Terpusat -->
<div class="row">
    <div class="col-12">
        <div class="schedule-header text-center">
            <h4>Jadwal Mata Pelajaran (<?= htmlspecialchars($profil_siswa['kelas']) ?>)</h4>
            <p><?= htmlspecialchars($hari_ini_info['hari']) ?>, <?= htmlspecialchars($tanggal) ?></p>
        </div>
    </div>
</div>

<div class="row">
    <?php
    $mapelQ = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel where hari='$hari' and kelas='$kelas_siswa'");
    while ($mapel = mysqli_fetch_array($mapelQ)):
        $guru = fetch($koneksi, 'users', ['id_user' => $mapel['guru']]);
        $pel = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel['mapel']]);
        $absen = fetch($koneksi, 'absensi', ['idpeg' => $mapel['guru'], 'tanggal' => $tanggal]);
        $warna = array('#6f42c1', '#007bff', '#28a745', '#6c757d', '#17a2b8', '#fd7e14');
    ?>
        <div class="col-xl-4">
            <!-- PERBAIKAN: Menggabungkan desain lama dan baru -->
            <div class="card widget widget-payment-request">
                <div class="card-body">
                    <div class="widget-payment-request-container">
                        <div class="widget-payment-request-author">
                            <div class="avatar m-r-sm">
                                <img src="../images/icon/buku.png" alt="">
                            </div>
                            <div class="widget-payment-request-author-info">
                                <span class="widget-payment-request-author-name"><?= $pel['nama_mapel'] ?? 'N/A' ?></span>
                                <!-- Kelas dihapus dari sini karena sudah ada di header -->
                            </div>
                        </div>
                        <div class="widget-payment-request-product" style="background-color: <?= $warna[array_rand($warna)] ?>;">
                            <div class="widget-payment-request-product-image m-r-sm">
                                <img src="../images/guru.png" class="mt-auto" alt="">
                            </div>
                            <div class="widget-payment-request-product-info d-flex">
                                <div class="widget-payment-request-product-info-content">
                                    <span class="widget-payment-request-product-name" style="color:#fff;">Guru Pengampu</span>
                                    <span class="widget-payment-request-product-about" style="color:#fff;"><?= $guru['nama'] ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="widget-payment-request-info m-t-md">
                             <div class="schedule-item">
                                <i class="material-icons-outlined">
                                    <?php
                                        if (isset($absen['ket']) && $absen['ket'] == 'H') { echo "check_circle"; } 
                                        else { echo "cancel"; }
                                    ?>
                                </i>
                                <span>
                                    <?php
                                    if (isset($absen['ket'])) {
                                        if ($absen['ket'] == 'H') { echo "<span class='status-hadir'>HADIR</span>"; } 
                                        elseif ($absen['ket'] == 'S') { echo "<span class='status-absen'>SAKIT</span>"; } 
                                        elseif ($absen['ket'] == 'I') { echo "<span class='status-absen'>IZIN</span>"; } 
                                        else { echo "<span class='status-absen'>ALPHA</span>"; }
                                    } else {
                                        echo "BELUM ABSEN";
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
