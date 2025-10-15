<?php
// Pastikan koneksi dan variabel lain sudah di-include atau didefinisikan di sini
// contoh: include 'koneksi.php';
// $siswa = ... (data siswa)

$student_id = (int)($_SESSION['id_siswa'] ?? ($siswa['id_siswa'] ?? 0));
if ($student_id <= 0) {
    echo '<div class="alert alert-danger">Data siswa tidak ditemukan. Silakan login kembali.</div>';
    return;
}

$student_query = mysqli_query($koneksi, "SELECT id_siswa, nis, nisn, nama, kelas FROM siswa WHERE id_siswa='$student_id' LIMIT 1");
$student_info = ($student_query && mysqli_num_rows($student_query) === 1) ? mysqli_fetch_assoc($student_query) : null;

if (!$student_info) {
    echo '<div class="alert alert-danger">Data siswa tidak ditemukan. Silakan hubungi administrator.</div>';
    return;
}

// Sinkronkan variabel $siswa yang mungkin dipakai di modul lain
$siswa = $student_info;
?>
<style>
/* Gaya yang sudah ada */
.page h4 {
    font-weight: bold;
    margin-bottom: 20px;
    color: #2c3e50;
}
.card {
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 20px;
}
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
}
.card-title h5 {
    margin: 0;
    font-weight: 600;
    color: #34495e;
}
.table thead th {
    background-color: #e9ecef;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
    font-size: 13px;
    color: #2c3e50;
}
.table tbody td {
    vertical-align: middle;
    text-align: center;
    font-size: 13px;
    color: #2f3640;
}
.table tfoot th {
    background-color: #f8f9fa;
    text-align: right;
    font-weight: bold;
    color: #2c3e50;
}
.filter-section {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}
.filter-section select,
.filter-section button {
    padding: 5px 10px;
    font-size: 13px;
}

/* --- CSS BARU UNTUK TOMBOL DETAIL DAN POPUP --- */

/* Tombol Lihat Detail */
.btn-detail {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 5px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.btn-detail:hover {
    background-color: #2980b9;
}

/* Modal (Popup) Styling */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 1050;
    display: none; /* Sembunyi secara default */
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
.modal-overlay.show {
    display: flex;
    opacity: 1;
}
.modal-content {
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    transform: scale(0.9);
    transition: transform 0.3s ease-in-out;
}
.modal-overlay.show .modal-content {
    transform: scale(1);
}
.modal-header {
    padding: 15px 25px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h5 {
    margin: 0;
    font-size: 1.1rem;
    color: #333;
    font-weight: 600;
}
.modal-close-btn {
    background: none;
    border: none;
    font-size: 1.8rem;
    font-weight: 300;
    color: #888;
    cursor: pointer;
    line-height: 1;
    padding: 0;
}
.modal-close-btn:hover {
    color: #000;
}
.modal-body {
    padding: 20px 25px;
}
.modal-body .loader {
    text-align: center;
    padding: 30px;
    font-size: 16px;
    color: #555;
}

@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<div class="row">
    <div class="col">
        <div class="page">
            <h4>KARTU HASIL STUDI (SISWA)</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <?php
        $nis = $siswa['nis'] ?? '';
        // PASTIKAN ID SISWA JUGA DIAMBIL
        $id_siswa = $siswa['id_siswa'] ?? $student_id; 

        $tp_list = [];
        // Ambil daftar TP memakai nis ATAU idsiswa agar baris yang nis-nya NULL tetap terbaca
        $cond_ident = !empty($nis) ? "(nis='".$nis."' OR idsiswa='".$id_siswa."')" : "idsiswa='".$id_siswa."'";
        $tp_query = mysqli_query($koneksi, "SELECT DISTINCT tp FROM nilai_sts WHERE $cond_ident ORDER BY tp DESC");
        if($tp_query) {
            while ($row = mysqli_fetch_assoc($tp_query)) {
                $tp_list[] = $row['tp'];
            }
        }

        $latest_period = null;
        $latest_query = mysqli_query($koneksi, "SELECT tp, semester FROM nilai_sts WHERE $cond_ident ORDER BY tp DESC, semester DESC LIMIT 1");
        if ($latest_query && mysqli_num_rows($latest_query) > 0) {
            $latest_period = mysqli_fetch_assoc($latest_query);
        }

        $active_tp = $_GET['tp'] ?? ($latest_period['tp'] ?? '');
        $active_semester = $_GET['semester'] ?? ($latest_period['semester'] ?? '1');

        $kelas_display = $student_info['kelas'] ?? '';
        $kelas_lookup_row = mysqli_query($koneksi, "SELECT kelas FROM nilai_sts WHERE $cond_ident AND tp='$active_tp' AND semester='$active_semester' LIMIT 1");
        if ($kelas_lookup_row && mysqli_num_rows($kelas_lookup_row) > 0) {
            $kelas_fetch = mysqli_fetch_assoc($kelas_lookup_row);
            if (!empty($kelas_fetch['kelas'])) {
                $kelas_display = $kelas_fetch['kelas'];
            }
        }

        $wali_kelas_nama = '';
        if ($kelas_display !== '') {
            $kelas_esc = mysqli_real_escape_string($koneksi, $kelas_display);
            $wali_res = mysqli_query($koneksi, "SELECT nama FROM users WHERE walas='$kelas_esc' LIMIT 1");
            if ($wali_res && mysqli_num_rows($wali_res) > 0) {
                $wali_row = mysqli_fetch_assoc($wali_res);
                $wali_kelas_nama = $wali_row['nama'] ?? '';
            }
        }

        $guru_wali_label = '';
        $gw_res = mysqli_query($koneksi, "SELECT u.nama FROM guru_wali gw JOIN users u ON u.id_user = gw.id_guru WHERE gw.id_siswa='$id_siswa' ORDER BY u.nama ASC");
        if ($gw_res) {
            $names = [];
            while ($row = mysqli_fetch_assoc($gw_res)) {
                if (!empty($row['nama'])) {
                    $names[] = $row['nama'];
                }
            }
            if (!empty($names)) {
                $guru_wali_label = implode(', ', $names);
            }
        }
        ?>

        <div class="filter-section">
            <form method="get">
                <input type="hidden" name="pg" value="<?= $_GET['pg'] ?? 'khs' ?>">
                
                <label>Tahun:</label>
                <select name="tp" onchange="this.form.submit()">
                    <?php if (empty($tp_list)) : ?>
                        <option>Belum ada data</option>
                    <?php else : ?>
                        <?php foreach ($tp_list as $tahun) : ?>
                            <option value="<?= $tahun ?>" <?= ($tahun == $active_tp) ? 'selected' : '' ?>>
                                <?= $tahun ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <label>Semester:</label>
                <select name="semester" onchange="this.form.submit()">
                    <option value="1" <?= $active_semester == '1' ? 'selected' : '' ?>>Ganjil</option>
                    <option value="2" <?= $active_semester == '2' ? 'selected' : '' ?>>Genap</option>
                </select>

                <button type="button" class="btn btn-secondary btn-sm" disabled>🔒 Fitur Tidak Aktif</button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Biodata Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Nama Lengkap</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($student_info['nama']); ?></dd>
                            <dt class="col-sm-4">NIS / NISN</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($student_info['nis']); ?> / <?= htmlspecialchars($student_info['nisn'] ?? '-'); ?></dd>
                            <dt class="col-sm-4">Kelas</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($kelas_display); ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Wali Kelas</dt>
                            <dd class="col-sm-8">
                                <?php if ($wali_kelas_nama !== '') : ?>
                                    <?= htmlspecialchars($wali_kelas_nama); ?>
                                <?php else : ?>
                                    <span class="text-muted">Belum diatur</span>
                                <?php endif; ?>
                            </dd>
                            <dt class="col-sm-4">Guru Wali</dt>
                            <dd class="col-sm-8">
                                <?php if ($guru_wali_label !== '') : ?>
                                    <?= htmlspecialchars($guru_wali_label); ?>
                                <?php else : ?>
                                    <span class="text-muted">Belum diatur</span>
                                <?php endif; ?>
                            </dd>
                            <dt class="col-sm-4">Tahun Pelajaran</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($active_tp); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Tampilkan baris KHS dengan identitas nis/id siswa yang tersedia
        $query_str = "SELECT * FROM nilai_sts WHERE $cond_ident AND tp='$active_tp' AND semester='$active_semester' ORDER BY id ASC";
        $query = mysqli_query($koneksi, $query_str);

        $no = 1;
        $total_nilai = 0;
        $jumlah_mapel = 0;
        ?>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h5>Daftar Nilai - <?= htmlspecialchars($student_info['nama']); ?></h5>
                    <small>Kelas <?= htmlspecialchars($kelas_display); ?> • Semester <?= $active_semester == '1' ? 'Ganjil' : 'Genap' ?> • Tahun Pelajaran <?= htmlspecialchars($active_tp); ?></small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai Harian</th>
                                <th>STS</th>
                                <th>SAS</th>
                                <th>Nilai Akhir (Angka)</th>
                                <th>Nilai Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                $mapel_id = $data['mapel'];
                                $mapel_query = mysqli_query($koneksi, "SELECT nama_mapel FROM mata_pelajaran WHERE id='$mapel_id'");
                                $mapel = mysqli_fetch_assoc($mapel_query);
                                $guru_name = '';
                                if (!empty($data['guru'])) {
                                    $guru_res = mysqli_query($koneksi, "SELECT nama FROM users WHERE id_user='" . mysqli_real_escape_string($koneksi, $data['guru']) . "'");
                                    if ($guru_res && mysqli_num_rows($guru_res) > 0) {
                                        $guru_row = mysqli_fetch_assoc($guru_res);
                                        $guru_name = $guru_row['nama'] ?? '';
                                    }
                                }

                                if ($guru_name === '') {
                                    $kelas_lookup_fallback = $data['kelas'] ?? $kelas_display;
                                    $kelas_lookup_fallback = $kelas_lookup_fallback ?: $kelas_display;
                                    if ($kelas_lookup_fallback !== '') {
                                        $kelas_esc_fb = mysqli_real_escape_string($koneksi, $kelas_lookup_fallback);
                                        $mapel_esc_fb = mysqli_real_escape_string($koneksi, $mapel_id);
                                        $guru_fb_query = mysqli_query($koneksi, "SELECT u.nama FROM jadwal_mapel jm JOIN users u ON u.id_user = jm.guru WHERE jm.kelas='$kelas_esc_fb' AND jm.mapel='$mapel_esc_fb' AND jm.semester='" . mysqli_real_escape_string($koneksi, $active_semester) . "' AND jm.tp='" . mysqli_real_escape_string($koneksi, $active_tp) . "' LIMIT 1");
                                        if ($guru_fb_query && mysqli_num_rows($guru_fb_query) > 0) {
                                            $guru_fb_row = mysqli_fetch_assoc($guru_fb_query);
                                            $guru_name = $guru_fb_row['nama'] ?? '';
                                        }
                                    }
                                }

                                $nilai_harian = floatval($data['nilai_harian']);
                                $nilai_sts = floatval($data['nilai_sts']);
                                $nilai_sas = floatval($data['nilai_sas']);

                                $nilai_akhir = round(($nilai_harian + $nilai_sts + $nilai_sas) / 3);
                                $total_nilai += $nilai_akhir;
                                $jumlah_mapel++;

                                if ($nilai_akhir >= 90) $huruf = "A";
                                elseif ($nilai_akhir >= 80) $huruf = "B+";
                                elseif ($nilai_akhir >= 70) $huruf = "B";
                                elseif ($nilai_akhir >= 60) $huruf = "C";
                                else $huruf = "D";
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $guru_name !== '' ? htmlspecialchars($guru_name) : 'Tidak Diketahui'; ?></td>
                                    <td><?= $mapel['nama_mapel'] ?? 'Tidak Diketahui'; ?></td>
                                    <td>
                                        <?= $nilai_harian; ?>
                                        <!-- FIXED: Mengirim id_siswa, bukan nis -->
                                        <button class="btn-detail" onclick="openDetailModal(
                                            '<?= $id_siswa ?>', 
                                            '<?= $mapel_id ?>', 
                                            '<?= htmlspecialchars($mapel['nama_mapel'] ?? 'Mapel') ?>',
                                            '<?= $active_tp ?>',
                                            '<?= $active_semester ?>'
                                        )">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    <td><?= $nilai_sts; ?></td>
                                    <td><?= $nilai_sas; ?></td>
                                    <td><?= $nilai_akhir; ?></td>
                                    <td><?= $huruf; ?></td>
                                </tr>
                        <?php  
                            }
                        } else {
                            echo '<tr><td colspan="8" class="text-center">Data tidak ditemukan untuk periode ini.</td></tr>';
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" style="text-align:right">Rata-rata Nilai</th>
                                <th colspan="5"><?= $jumlah_mapel > 0 ? round($total_nilai / $jumlah_mapel) : '0'; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- --- HTML BARU UNTUK MODAL/POPUP --- -->
<div id="detailModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h5 id="modalTitle">Detail Nilai</h5>
            <button class="modal-close-btn" onclick="closeDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="loader">Memuat data...</div>
        </div>
    </div>
</div>


<!-- --- JAVASCRIPT BARU UNTUK FUNGSI POPUP --- -->
<script>
const modalOverlay = document.getElementById('detailModal');
const modalTitle = document.getElementById('modalTitle');
const modalBody = document.getElementById('modalBody');

/**
 * FIXED: Parameter pertama sekarang adalah id_siswa
 * @param {string} idSiswa - ID siswa
 * @param {string} mapelId - ID mata pelajaran
 * @param {string} mapelNama - Nama mata pelajaran
 * @param {string} tp - Tahun Pelajaran
 * @param {string} semester - Semester
 */
function openDetailModal(idSiswa, mapelId, mapelNama, tp, semester) {
    modalOverlay.classList.add('show');
    modalTitle.innerHTML = `Detail Nilai: ${mapelNama}`;
    modalBody.innerHTML = '<div class="loader">Memuat data...</div>';

    const formData = new FormData();
    formData.append('id_siswa', idSiswa); // FIXED: Mengirim id_siswa
    formData.append('mapel_id', mapelId); // kompatibel lama
    formData.append('mapel', mapelId);    // nama field sesuai kolom DB
    formData.append('tp', tp);
    formData.append('semester', semester);

    fetch('nilai/get_detail_nilai.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        modalBody.innerHTML = data;
    })
    .catch(error => {
        console.error('Error fetching details:', error);
        modalBody.innerHTML = '<div class="loader" style="color: red;">Gagal memuat data. Silakan coba lagi.</div>';
    });
}

function closeDetailModal() {
    modalOverlay.classList.remove('show');
}

modalOverlay.addEventListener('click', function(event) {
    if (event.target === modalOverlay) {
        closeDetailModal();
    }
});
</script>
