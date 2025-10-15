<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Mengambil nilai 'pg' saat ini untuk memastikan semua link benar
$current_pg = isset($_GET['pg']) ? htmlspecialchars($_GET['pg']) : 'materi';
$kelas_siswa = $siswa['kelas']; // Mengambil kelas siswa dari sesi
$sekarang = date('Y-m-d H:i:s'); // Waktu saat ini untuk perbandingan
// Mode urut dropdown judul materi
$sort_mode = (isset($_GET['sort']) && strtolower($_GET['sort']) === 'abjad') ? 'abjad' : 'baru';

// PENAMBAHAN: Fungsi untuk mengubah tanggal ke format Indonesia
function format_tanggal_indonesia($tanggal_waktu) {
    if (is_null($tanggal_waktu) || $tanggal_waktu == '0000-00-00 00:00:00') {
        return 'Tidak terbatas';
    }
    $bulan_indonesia = [
        'January'   => 'Januari', 'February'  => 'Februari', 'March'     => 'Maret',
        'April'     => 'April',   'May'       => 'Mei',      'June'      => 'Juni',
        'July'      => 'Juli',    'August'    => 'Agustus',  'September' => 'September',
        'October'   => 'Oktober', 'November'  => 'November', 'December'  => 'Desember'
    ];
    $timestamp = strtotime($tanggal_waktu);
    $tanggal_inggris = date('d F Y, H:i', $timestamp);
    $nama_bulan_inggris = date('F', $timestamp);
    $nama_bulan_indonesia = $bulan_indonesia[$nama_bulan_inggris];
    return str_replace($nama_bulan_inggris, $nama_bulan_indonesia, $tanggal_inggris);
}

// Hitung ringkasan nilai quiz siswa untuk sebuah materi (attempt terakhir)
function ringkas_nilai_quiz(mysqli $db, int $id_materi, int $id_siswa): ?array {
    // Cari attempt terakhir siswa untuk materi ini
    $attempt = 0; $res = $db->query("SELECT COALESCE(MAX(attempt),0) a FROM jawaban_quiz WHERE id_materi=".(int)$id_materi." AND id_siswa=".(int)$id_siswa);
    if ($res) { $row = $res->fetch_assoc(); $attempt = (int)($row['a'] ?? 0); $res->close(); }
    if ($attempt <= 0) {
        // Belum ada jawaban
        $res2 = $db->query("SELECT SUM(COALESCE(skor_max,0)) m FROM quiz WHERE id_materi=".(int)$id_materi);
        $maks = 0; if ($res2) { $r2 = $res2->fetch_assoc(); $maks = (float)($r2['m'] ?? 0); $res2->close(); }
        return ['total'=>null, 'maks'=>$maks, 'attempt'=>0];
    }
    $total = 0; $res3 = $db->query("SELECT SUM(COALESCE(skor,0)) t FROM jawaban_quiz WHERE id_materi=".(int)$id_materi." AND id_siswa=".(int)$id_siswa." AND attempt=".(int)$attempt);
    if ($res3) { $r3 = $res3->fetch_assoc(); $total = (float)($r3['t'] ?? 0); $res3->close(); }
    $maks = 0; $res4 = $db->query("SELECT SUM(COALESCE(skor_max,0)) m FROM quiz WHERE id_materi=".(int)$id_materi);
    if ($res4) { $r4 = $res4->fetch_assoc(); $maks = (float)($r4['m'] ?? 0); $res4->close(); }
    return ['total'=>$total, 'maks'=>$maks, 'attempt'=>$attempt];
}

// Cek apakah parameter 'mapel' ada di URL
if (isset($_GET['mapel']) && !empty($_GET['mapel'])) {
    // =================================================================
    // TAMPILAN DAFTAR MATERI
    // =================================================================
    $kode_mapel = mysqli_real_escape_string($koneksi, urldecode($_GET['mapel']));
    
    // Ambil nama mapel lengkap
    $nama_mapel_query = mysqli_query($koneksi, "SELECT nama_mapel FROM mata_pelajaran WHERE kode = '$kode_mapel'");
    $nama_mapel_data = mysqli_fetch_assoc($nama_mapel_query);
    $nama_mapel_lengkap = $nama_mapel_data['nama_mapel'] ?? $kode_mapel;

    // Ambil semua tugas untuk mapel ini yang relevan dengan kelas siswa
    $tugas_list = [];
    $tugas_aktif_count = 0;
    $tugas_selesai_count = 0;

    $tugas_query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE mapel = '$kode_mapel' ORDER BY tgl_selesai DESC");
    if ($tugas_query) {
        while($tugas = mysqli_fetch_assoc($tugas_query)) {
            $datakelas = unserialize($tugas['kelas']);
            if (in_array($siswa['kelas'], $datakelas) || in_array('semua', $datakelas)) {
                $jawaban = fetch($koneksi, 'jawaban_tugas', ['id_siswa' => $siswa['id_siswa'], 'id_tugas' => $tugas['id_tugas']]);
                $tugas['nilai'] = $jawaban['nilai'] ?? 0;

                if ($tugas['tgl_selesai'] < $sekarang) {
                    $tugas['status'] = 'DITUTUP';
                    $tugas_selesai_count++;
                } else {
                    $tugas['status'] = 'AKTIF';
                    $tugas_aktif_count++;
                }
                $tugas_list[] = $tugas;
            }
        }
    }
?>
    <!-- CSS untuk Halaman dan Popup Kustom -->
    <style>
        .task-summary-box {
            background-color: #fff; border: 1px solid #e9ecef; border-radius: 10px;
            padding: 20px; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;
        }
        .task-summary-box:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-3px);
        }
        .task-summary-box h5 { margin-bottom: 10px; font-weight: 600; }
        .task-summary-box p { margin-bottom: 0; color: #6c757d; }
        .task-summary-box .badge { font-size: 0.8rem; }

        /* --- CSS UNTUK POPUP TUGAS --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); z-index: 1050;
            display: none; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.3s ease-in-out;
        }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-content-custom {
            background: white; border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 90%;
            max-width: 600px; max-height: 90vh;
            display: flex; flex-direction: column;
            transform: scale(0.9);
            transition: transform 0.3s ease-in-out;
        }
        .modal-overlay.show .modal-content-custom { transform: scale(1); }
        .modal-header {
            padding: 15px 25px; border-bottom: 1px solid #e9ecef;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h5 { margin: 0; font-size: 1.1rem; color: #333; font-weight: 600; }
        .modal-close-btn {
            background: none; border: none; font-size: 1.8rem;
            font-weight: 300; color: #888; cursor: pointer;
            line-height: 1; padding: 0;
        }
        .modal-body { padding: 0; overflow-y: auto; }
        .task-list { list-style: none; padding: 0; margin: 0; }
        .task-list-item {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 25px; border-bottom: 1px solid #f0f0f0;
            cursor: pointer; transition: background-color 0.2s ease;
            text-decoration: none; color: inherit;
        }
        .task-list-item:last-child { border-bottom: none; }
        .task-list-item:hover { background-color: #f8f9fa; }
        .task-info .status {
            font-size: 0.7rem; font-weight: bold;
            padding: 2px 6px; border-radius: 4px; color: #fff;
            margin-right: 8px;
        }
        .task-info .status.aktif { background-color: #28a745; }
        .task-info .status.ditutup { background-color: #dc3545; }
        .task-info .title { font-weight: 500; }
        .task-nilai { font-size: 0.9rem; color: #888; }
    </style>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items:center">
                    <h4 class="card-title mb-0">Daftar Materi: <?= htmlspecialchars($nama_mapel_lengkap) ?></h4>
                    <div class="d-flex" style="gap:8px;">
                        <a href="?pg=<?= enkripsi('tugas') ?>" class="btn btn-primary">
                            <i class="fas fa-list-check"></i> Lihat Semua Tugas
                        </a>
                        <a href="?pg=<?= $current_pg ?>" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="task-summary-box" onclick="openTugasModal()">
                <h5>Daftar Tugas</h5>
                <p>
                    <span class="badge bg-success"><?= $tugas_aktif_count ?> tugas aktif</span>, 
                    <span class="badge bg-secondary"><?= $tugas_selesai_count ?> tugas tidak aktif</span>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <?php 
        // Query diubah untuk memfilter materi berdasarkan waktu aktif
        $materi_query_by_class = mysqli_query($koneksi, "SELECT * FROM materi WHERE mapel = '$kode_mapel' AND tgl_mulai <= '$sekarang' AND (tgl_selesai IS NULL OR tgl_selesai >= '$sekarang') ORDER BY id_materi DESC");
        
        $materi_ditemukan_untuk_kelas = false;
        if ($materi_query_by_class):
            while($materi = mysqli_fetch_assoc($materi_query_by_class)): 
                // Filter materi berdasarkan kelas siswa
                $datakelas_materi = unserialize($materi['kelas']);
                if (in_array($siswa['kelas'], $datakelas_materi) || in_array('semua', $datakelas_materi)):
                    $materi_ditemukan_untuk_kelas = true;
                    $guru = fetch($koneksi, 'users', ['id_user' => $materi['id_guru']]);
                    // Warna banner konsisten per mapel
                    $palette = ['#001BFF','#0d6efd','#20c997','#6f42c1','#198754','#dc3545','#fd7e14','#0dcaf0'];
                    $hashColor = crc32((string)$materi['mapel']);
                    $subject_color = $palette[$hashColor % count($palette)];
        ?>
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="card-title mb-0">MATERI BELAJAR</h5></div>
                <div class="card-body d-flex flex-column">
                    <div>
                        <div class="text-muted" style="margin-bottom:8px;">(<?= htmlspecialchars($nama_mapel_lengkap) ?>)</div>
                        <?php $foto_g = !empty($guru['foto']) ? '../images/'.htmlspecialchars($guru['foto']) : '../images/guru.png'; ?>
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;background: <?= !empty($subject_color) ? $subject_color : '#6f42c1' ?>; color:#fff;">
                            <img src="<?= $foto_g ?>" alt="Guru" style="width:48px;height:48px;border-radius:50%;background:#fff;padding:2px;object-fit:cover;">
                            <div>
                                <div style="opacity:.9;font-size:.9rem;">Guru Pengampu</div>
                                <div style="font-weight:600;"><?= htmlspecialchars($guru['nama'] ?? '-') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="widget-payment-request-info m-t-md mt-3">
                        <div class="widget-payment-request-info-item">
                            <span class="widget-payment-request-info-title d-block">RINGKASAN</span>
                            <span class="text-muted d-block"><?= substr(strip_tags($materi['materi']), 0, 30) ?>...</span>
                        </div>
                        <!-- PERBAIKAN: Menampilkan tanggal mulai dan selesai -->
                            <div class="widget-payment-request-info-item">
                                <span class="widget-payment-request-info-title d-block">WAKTU PELAKSANAAN</span>
                                <span class="text-muted d-block" style="font-size: 0.8rem;">
                                    Mulai: <?= format_tanggal_indonesia($materi['tgl_mulai']) ?>
                                </span>
                                <span class="text-muted d-block" style="font-size: 0.8rem;">
                                    Selesai: <?= format_tanggal_indonesia($materi['tgl_selesai']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php
                        // Daftar gabungan: Materi, Quiz, Tugas (berdasarkan mapel)
                        $kode_mapel_for_dd = $materi['mapel'];
                        $combined = [];

                        // Kumpulkan materi yang sesuai kelas siswa (urut tgl_mulai naik)
                        $order_clause = 'ORDER BY tgl_mulai ASC, id_materi ASC';
                        $q_m = mysqli_query($koneksi, "SELECT id_materi, judul, kelas, tgl_mulai, tgl_selesai, tgl FROM materi WHERE mapel='".mysqli_real_escape_string($koneksi,$kode_mapel_for_dd)."' $order_clause");
                        if ($q_m) {
                            while ($m = mysqli_fetch_assoc($q_m)) {
                                $kelas_m = @unserialize($m['kelas']); if (!is_array($kelas_m)) $kelas_m = [];
                                if (in_array($kelas_siswa, $kelas_m) || in_array('semua', $kelas_m)) {
                                    // Status baca materi berdasarkan absen_daringmapel
                                    $sudah_baca = rowcount($koneksi, 'absen_daringmapel', ['idmateri' => (int)$m['id_materi'], 'idsiswa' => (int)$siswa['id_siswa']]) > 0;
                                    $combined[] = [
                                        'tipe' => 'MATERI',
                                        'label' => '(MATERI) '.($m['judul'] ?? ''),
                                        'judul' => $m['judul'] ?? '',
                                        'selesai' => $m['tgl_selesai'] ?? null,
                                        'mulai' => $m['tgl_mulai'] ?? null,
                                        'uploaded' => $m['tgl_mulai'] ?? null,
                                        'nilai_txt' => null,
                                        'status_txt' => $sudah_baca ? 'Sudah dibaca' : 'Belum dibaca',
                                        'status_done' => $sudah_baca,
                                        'href' => '?pg=bukamateri&id='.(int)$m['id_materi'],
                                        'sort_key' => $m['judul'] ?? ''
                                    ];
                                    // Cek apakah materi ini memiliki quiz
                                    $has_quiz = 0; $rq = mysqli_query($koneksi, "SELECT COUNT(*) c FROM quiz WHERE id_materi=".(int)$m['id_materi']);
                                    if ($rq) { $rw = mysqli_fetch_assoc($rq); $has_quiz = (int)$rw['c']; }
                                    if ($has_quiz > 0) {
                                        $ringkas = ringkas_nilai_quiz($koneksi, (int)$m['id_materi'], (int)$siswa['id_siswa']);
                                        $nilai_txt = '';
                                        if ($ringkas) {
                                            if ($ringkas['total'] === null) {
                                                $nilai_txt = ($ringkas['maks'] > 0) ? 'Nilai: - / '.(float)$ringkas['maks'] : 'Nilai: -';
                                            } else {
                                                $nilai_txt = 'Nilai: '.(float)$ringkas['total'].' / '.(float)$ringkas['maks'];
                                            }
                                        }
                                        $combined[] = [
                                            'tipe' => 'QUIZ',
                                            'label' => '(QUIZ)',
                                            'judul' => $m['judul'] ?? '',
                                            'selesai' => $m['tgl_selesai'] ?? null,
                                            'mulai' => $m['tgl_mulai'] ?? null,
                                            'uploaded' => $m['tgl_mulai'] ?? null,
                                            'nilai_txt' => $nilai_txt,
                                            'status_txt' => ($ringkas && (int)$ringkas['attempt']>0) ? 'Sudah dikerjakan' : 'Belum dikerjakan',
                                            'status_done' => ($ringkas && (int)$ringkas['attempt']>0),
                                            'href' => '?pg=' . enkripsi('quiz') . '&id=' . (int)$m['id_materi'],
                                            'sort_key' => 'QUIZ '.($m['judul'] ?? '')
                                        ];
                                    }
                                }
                            }
                        }

                        // Kumpulkan tugas pada mapel ini
                        $q_t = mysqli_query($koneksi, "SELECT id_tugas, judul, kelas, tgl_mulai, tgl_selesai, tgl FROM tugas WHERE mapel='".mysqli_real_escape_string($koneksi,$kode_mapel_for_dd)."' ORDER BY tgl_mulai DESC, id_tugas DESC");
                        if ($q_t) {
                            while ($t = mysqli_fetch_assoc($q_t)) {
                                $kelas_t = @unserialize($t['kelas']); if (!is_array($kelas_t)) $kelas_t = [];
                                if (in_array($kelas_siswa, $kelas_t) || in_array('semua', $kelas_t)) {
                                    $jawaban = fetch($koneksi, 'jawaban_tugas', ['id_siswa' => $siswa['id_siswa'], 'id_tugas' => $t['id_tugas']]);
                                    $nilai_tugas = ($jawaban && $jawaban['nilai']!=='') ? $jawaban['nilai'] : null;
                                    $combined[] = [
                                        'tipe' => 'TUGAS',
                                        'label' => '(TUGAS) '.($t['judul'] ?? ''),
                                        'judul' => $t['judul'] ?? '',
                                        'selesai' => $t['tgl_selesai'] ?? null,
                                        'mulai' => $t['tgl_mulai'] ?? null,
                                        'uploaded' => $t['tgl_mulai'] ?? null,
                                        'nilai_txt' => ($nilai_tugas!==null ? ('Nilai: '.$nilai_tugas) : ''),
                                        'status_txt' => $jawaban ? 'Sudah dikerjakan' : 'Belum dikerjakan',
                                        'status_done' => $jawaban ? true : false,
                                        'href' => '?pg=bukatugas&id='.(int)$t['id_tugas'],
                                        'sort_key' => $t['judul'] ?? ''
                                    ];
                                }
                            }
                        }

                        // Urutkan hanya berdasarkan tgl_mulai (terlama dulu -> paling atas)
                        usort($combined, function($a,$b){
                            $ta = strtotime($a['uploaded'] ?? '1970-01-01 00:00:00');
                            $tb = strtotime($b['uploaded'] ?? '1970-01-01 00:00:00');
                            return $ta <=> $tb;
                        });
                        $count_dd = count($combined);
                        $base_url = '?pg='.$current_pg.'&mapel='.urlencode($kode_mapel_for_dd);
                        $ddid = 'dd_konten_'.(int)$materi['id_materi'];
                    ?>
                    <style>
                        .learn-sublist {list-style:none;padding:0;margin:16px 0 0 0}
                        .learn-sublist li {display:flex;align-items:center;justify-content:space-between;border:1px solid #eef0f2;border-radius:10px;padding:10px 12px;margin-bottom:8px;background:#fafbfc}
                        .learn-sublist .meta {display:flex;align-items:center;gap:10px}
                        .learn-sublist li a{display:flex;align-items:center;justify-content:space-between;width:100%;text-decoration:none;color:inherit}
                        .badge-status {display:inline-block;padding:3px 8px;border-radius:12px;font-size:.7rem;margin-bottom:4px}
                        .badge-status.done {background:#e7f5ec;color:#198754}
                        .badge-status.notyet {background:#f8f9fa;color:#6c757d}
                    </style>
                    <ul class="learn-sublist mt-auto">
                        <?php if (empty($combined)): ?>
                            <li><span class="text-muted d-block p-2">Tidak ada konten</span></li>
                        <?php else: foreach ($combined as $it): ?>
                            <li>
                                <a href="<?= htmlspecialchars($it['href']) ?>">
                                    <div class="meta">
                                        <?php if ($it['tipe']==='MATERI'): ?>
                                            <i class="fas fa-book"></i>
                                        <?php elseif ($it['tipe']==='QUIZ'): ?>
                                            <i class="fas fa-question-circle"></i>
                                        <?php else: ?>
                                            <i class="fas fa-tasks"></i>
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($it['label']) ?></strong>
                                    </div>
                                    <div class="text-end" style="font-size:.8rem;">
                                        <span class="badge-status <?= !empty($it['status_done']) ? 'done' : 'notyet' ?>"><?= htmlspecialchars($it['status_txt'] ?? '') ?></span>
                                        <div class="text-muted">Selesai: <?= format_tanggal_indonesia($it['selesai']) ?><?= !empty($it['nilai_txt']) ? ' • '.htmlspecialchars($it['nilai_txt']) : '' ?></div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php 
                endif; // Tutup if pengecekan kelas
            endwhile; 
        endif;

        if (!$materi_ditemukan_untuk_kelas) {
            echo '<div class="col-12"><div class="alert alert-info">Belum ada materi aktif untuk mata pelajaran ini yang sesuai dengan kelas Anda.</div></div>';
        }
        ?>
    </div>

    <!-- POPUP UNTUK DAFTAR TUGAS -->
    <div id="tugasModal" class="modal-overlay">
        <div class="modal-content-custom">
            <div class="modal-header">
                <h5 id="modalTitle">Daftar Tugas: <?= htmlspecialchars($nama_mapel_lengkap) ?></h5>
                <button class="modal-close-btn" onclick="closeTugasModal()">&times;</button>
            </div>
            <div class="modal-body">
                <ul class="task-list">
                    <?php if (empty($tugas_list)): ?>
                        <li class="task-list-item" style="justify-content: center;">Tidak ada tugas untuk mata pelajaran ini.</li>
                    <?php else: ?>
                        <?php foreach($tugas_list as $tugas): ?>
                            <a href="?pg=bukatugas&id=<?= $tugas['id_tugas'] ?>" class="task-list-item">
                                <div class="task-info">
                                    <span class="status <?= strtolower($tugas['status']) ?>"><?= $tugas['status'] ?></span>
                                    <span class="title"><?= htmlspecialchars($tugas['judul']) ?></span>
                                </div>
                                <div class="task-nilai">
                                    Nilai: <?= $tugas['nilai'] ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        const tugasModal = document.getElementById('tugasModal');
        function openTugasModal() { tugasModal.classList.add('show'); }
        function closeTugasModal() { tugasModal.classList.remove('show'); }
        tugasModal.addEventListener('click', function(event) {
            if (event.target === tugasModal) { closeTugasModal(); }
        });
        // Dropdown materi kini dibuka saat hover via CSS (.dd:hover .dd-menu)
    </script>

<?php
} else {
    // =================================================================
    // TAMPILAN "PILIH MATA PELAJARAN" DIUBAH MENJADI DAFTAR MATERI AKTIF
    // Untuk memindahkan tampilan kartu materi ke halaman awal
    // =================================================================
?>
    <!-- CSS untuk ringkasan tugas dan popup (reuse dari tampilan materi per mapel) -->
    <style>
        .task-summary-box {
            background-color: #fff; border: 1px solid #e9ecef; border-radius: 10px;
            padding: 20px; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;
        }
        .task-summary-box:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); transform: translateY(-3px); }
        .task-summary-box h5 { margin-bottom: 10px; font-weight: 600; }
        .task-summary-box p { margin-bottom: 0; color: #6c757d; }
        .task-summary-box .badge { font-size: 0.8rem; }

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 1050; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease-in-out; }
        .modal-overlay.show { display: flex; opacity: 1; }
        .modal-content-custom { background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 90%; max-width: 600px; max-height: 90vh; display: flex; flex-direction: column; transform: scale(0.9); transition: transform 0.3s ease-in-out; }
        .modal-overlay.show .modal-content-custom { transform: scale(1); }
        .modal-header { padding: 15px 25px; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h5 { margin: 0; font-size: 1.1rem; color: #333; font-weight: 600; }
        .modal-close-btn { background: none; border: none; font-size: 1.8rem; font-weight: 300; color: #888; cursor: pointer; line-height: 1; padding: 0; }
        .modal-body { padding: 0; overflow-y: auto; }
        .task-list { list-style: none; padding: 0; margin: 0; }
        .task-list-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; border-bottom: 1px solid #eee; color: inherit; text-decoration: none; }
        .task-list-item:hover { background: #fafafa; }
        .task-info { display: flex; align-items: center; gap: 10px; }
        .task-info .status { font-size: .75rem; text-transform: uppercase; font-weight: 700; padding: 4px 8px; border-radius: 8px; }
        .task-info .status.aktif { background: #e7f5ec; color: #198754; }
        .task-info .status.ditutup { background: #f8f9fa; color: #6c757d; }
        .task-nilai { color: #6c757d; font-size: .9rem; }
    </style>

    <?php
    // Hitung ringkasan tugas untuk SEMUA mapel yang relevan dengan kelas siswa
    $tugas_list = [];
    $tugas_aktif_count = 0;
    $tugas_selesai_count = 0;
    $q_all_tugas = mysqli_query($koneksi, "SELECT * FROM tugas ORDER BY tgl_selesai DESC");
    if ($q_all_tugas) {
        while ($t = mysqli_fetch_assoc($q_all_tugas)) {
            $dk = @unserialize($t['kelas']);
            if (!is_array($dk)) $dk = [];
            if (in_array($kelas_siswa, $dk) || in_array('semua', $dk)) {
                $jawaban = fetch($koneksi, 'jawaban_tugas', ['id_siswa' => $siswa['id_siswa'], 'id_tugas' => $t['id_tugas']]);
                $t['nilai'] = $jawaban['nilai'] ?? 0;
                if ($t['tgl_selesai'] < $sekarang) { $t['status'] = 'DITUTUP'; $tugas_selesai_count++; }
                else { $t['status'] = 'AKTIF'; $tugas_aktif_count++; }
                $tugas_list[] = $t;
            }
        }
    }
    ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">Materi Belajar</h4>
                    <div class="d-flex" style="gap:8px;">
                        <a href="?pg=<?= enkripsi('tugas') ?>" class="btn btn-primary">
                            <i class="fas fa-list-check"></i> Lihat Semua Tugas
                        </a>
                        <span class="text-muted d-none d-md-inline">Semua mata pelajaran (aktif)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan tugas -->
    <div class="row">
        <div class="col-12">
            <div class="task-summary-box" onclick="openTugasModal()">
                <h5>Daftar Tugas</h5>
                <p>
                    <span class="badge bg-success"><?= $tugas_aktif_count ?> tugas aktif</span>,
                    <span class="badge bg-secondary"><?= $tugas_selesai_count ?> tugas tidak aktif</span>
                </p>
            </div>
        </div>
    </div>

    <div class="row">
        <?php
        // Ambil semua materi aktif untuk kelas siswa dari semua mapel
        $materi_query_all = mysqli_query($koneksi, "SELECT * FROM materi WHERE tgl_mulai <= '$sekarang' AND (tgl_selesai IS NULL OR tgl_selesai >= '$sekarang') ORDER BY id_materi DESC");
        $ada_materi = false;
        $printed_mapel = [];
        if ($materi_query_all) :
            while ($materi = mysqli_fetch_assoc($materi_query_all)) :
                $datakelas_materi = @unserialize($materi['kelas']);
                if (!is_array($datakelas_materi)) $datakelas_materi = [];
                if (in_array($kelas_siswa, $datakelas_materi) || in_array('semua', $datakelas_materi)) :
                    // Cetak 1 panel per mapel saja
                    if (isset($printed_mapel[$materi['mapel']])) { continue; }
                    $printed_mapel[$materi['mapel']] = true;
                    $ada_materi = true;
                    // Ambil nama mapel dari kode
                    $mp = fetch($koneksi, 'mata_pelajaran', ['kode' => $materi['mapel']]);
                    $nama_mapel_kartu = $mp['nama_mapel'] ?? $materi['mapel'];
                    $panel_id = 'sb_' . preg_replace('/[^A-Za-z0-9_]/', '_', $materi['mapel']);
                    $warna = array('red', 'blue', 'green', 'gray', 'purple', 'black');
        ?>
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card widget widget-payment-request h-100">
                <div class="card-header subject-header" onclick="toggleSubject('<?= $panel_id ?>')">
                    <h5 class="card-title mb-0">(<?= htmlspecialchars($nama_mapel_kartu) ?>)</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <style>
                        .subject-header{cursor:pointer; background:#f8f9fa}
                        .subject-body{display:none}
                        .subject-body.show{display:block}
                    </style>
                    <?php
                        // Sub-list: Materi / Quiz / Tugas untuk tema ini
                        $quiz_count = 0; $id_tugas_rel = 0;
                        $rq = mysqli_query($koneksi, "SELECT COUNT(*) c FROM quiz WHERE id_materi=".(int)$materi['id_materi']);
                        if ($rq) { $rw = mysqli_fetch_assoc($rq); $quiz_count = (int)$rw['c']; }
                        $rt = mysqli_query($koneksi, "SELECT id_tugas FROM tugas WHERE id_materi=".(int)$materi['id_materi']." LIMIT 1");
                        if ($rt && mysqli_num_rows($rt)>0) { $rw = mysqli_fetch_assoc($rt); $id_tugas_rel = (int)$rw['id_tugas']; }
                    ?>
                    <style>
                        .learn-sublist {list-style:none;padding:0;margin:16px 0 0 0}
                        .learn-sublist li {display:flex;align-items:center;justify-content:space-between;border:1px solid #eef0f2;border-radius:10px;padding:10px 12px;margin-bottom:8px;background:#fafbfc}
                        .learn-sublist .meta {display:flex;align-items:center;gap:10px}
                        .learn-sublist .badge-soft {background:#eef5ff;color:#356ac3;padding:3px 8px;border-radius:12px;font-size:.75rem}
                        .dd {position:relative;display:inline-block}
                        .dd-menu {display:none;position:absolute;right:0;top:100%;z-index:1060;background:#fff;border:1px solid #e9ecef;border-radius:8px;min-width:260px;max-height:260px;overflow:auto;box-shadow:0 10px 20px rgba(0,0,0,.08)}
                        .dd:hover .dd-menu{display:block}
                        .dd-menu a {display:block;padding:8px 12px;color:#212529;text-decoration:none}
                        .dd-menu a:hover {background:#f6f7f9}
                        .dd-menu .dd-head{position:sticky;top:0;background:#fff;border-bottom:1px solid #eee;padding:6px 8px;font-size:.8rem;color:#6c757d}
                        .dd-menu .dd-head a{color:#0d6efd;margin-left:6px}
                    </style>
                    <?php
                        // Daftar gabungan untuk tampilan semua mapel (berdasarkan mapel dari kartu ini)
                        $kode_mapel_for_dd = $materi['mapel'];
                        $combined_all = [];

                        // Ambil guru pengampu terakhir untuk mapel ini (dari materi terbaru)
                        $guru_panel = null;
                        $rg = mysqli_query($koneksi, "SELECT id_guru FROM materi WHERE mapel='".mysqli_real_escape_string($koneksi,$kode_mapel_for_dd)."' ORDER BY tgl_mulai DESC, id_materi DESC LIMIT 1");
                        if ($rg && mysqli_num_rows($rg)>0) {
                            $rgg = mysqli_fetch_assoc($rg);
                            $guru_panel = fetch($koneksi, 'users', ['id_user' => (int)$rgg['id_guru']]);
                        }
                        // Warna konsisten per mapel (palette deterministik)
                        $palette = ['#001BFF','#0d6efd','#20c997','#6f42c1','#198754','#dc3545','#fd7e14','#0dcaf0'];
                        $hashColor = crc32((string)$kode_mapel_for_dd);
                        $subject_color_mapel = $palette[$hashColor % count($palette)];

                        // Materi (urut tgl_mulai naik)
                        $order_clause_all = 'ORDER BY tgl_mulai ASC, id_materi ASC';
                        $q_m_all = mysqli_query($koneksi, "SELECT id_materi, judul, kelas, tgl_mulai, tgl_selesai, tgl FROM materi WHERE mapel='".mysqli_real_escape_string($koneksi,$kode_mapel_for_dd)."' $order_clause_all");
                        if ($q_m_all) {
                            while ($m = mysqli_fetch_assoc($q_m_all)) {
                                $km = @unserialize($m['kelas']); if (!is_array($km)) $km = [];
                                if (in_array($kelas_siswa, $km) || in_array('semua', $km)) {
                                    $sudah_baca_all = rowcount($koneksi, 'absen_daringmapel', ['idmateri' => (int)$m['id_materi'], 'idsiswa' => (int)$siswa['id_siswa']]) > 0;
                                    $combined_all[] = [
                                        'tipe' => 'MATERI',
                                        'label' => '(MATERI) '.($m['judul'] ?? ''),
                                        'judul' => $m['judul'] ?? '',
                                        'selesai' => $m['tgl_selesai'] ?? null,
                                        'mulai' => $m['tgl_mulai'] ?? null,
                                        'uploaded' => $m['tgl_mulai'] ?? null,
                                        'nilai_txt' => null,
                                        'status_txt' => $sudah_baca_all ? 'Sudah dibaca' : 'Belum dibaca',
                                        'status_done' => $sudah_baca_all,
                                        'href' => '?pg=bukamateri&id='.(int)$m['id_materi'],
                                        'sort_key' => $m['judul'] ?? ''
                                    ];
                                    // Tambahkan item QUIZ bila ada
                                    $has_quiz = 0; $rq = mysqli_query($koneksi, "SELECT COUNT(*) c FROM quiz WHERE id_materi=".(int)$m['id_materi']);
                                    if ($rq) { $rw = mysqli_fetch_assoc($rq); $has_quiz = (int)$rw['c']; }
                                    if ($has_quiz>0) {
                                        $ringkas = ringkas_nilai_quiz($koneksi, (int)$m['id_materi'], (int)$siswa['id_siswa']);
                                        $nilai_txt = '';
                                        if ($ringkas && $ringkas['attempt']>0 && $ringkas['maks']>0) {
                                            $nilai_txt = 'Nilai: '.number_format((float)$ringkas['total'],2).' / '.number_format((float)$ringkas['maks'],2);
                                        }
                                        $combined_all[] = [
                                            'tipe' => 'QUIZ',
                                            'label' => '(QUIZ)',
                                            'judul' => '',
                                            'selesai' => $m['tgl_selesai'] ?? null,
                                            'mulai' => $m['tgl_mulai'] ?? null,
                                            'uploaded' => $m['tgl_mulai'] ?? null,
                                            'nilai_txt' => $nilai_txt,
                                            'status_txt' => ($ringkas && (int)$ringkas['attempt']>0) ? 'Sudah dikerjakan' : 'Belum dikerjakan',
                                            'status_done' => ($ringkas && (int)$ringkas['attempt']>0),
                                            'href' => '?pg=' . enkripsi('quiz') . '&id='.(int)$m['id_materi'],
                                            'sort_key' => 'QUIZ '.($m['judul'] ?? '')
                                        ];
                                    }
                                }
                            }
                            mysqli_free_result($q_m_all);
                        }

                        // Tugas
                        $q_t_all = mysqli_query($koneksi, "SELECT id_tugas, judul, kelas, tgl_mulai, tgl_selesai, tgl FROM tugas WHERE mapel='".mysqli_real_escape_string($koneksi,$kode_mapel_for_dd)."'");
                        if ($q_t_all) {
                            while ($t = mysqli_fetch_assoc($q_t_all)) {
                                $kt = @unserialize($t['kelas']); if (!is_array($kt)) $kt = [];
                                if (in_array($kelas_siswa, $kt) || in_array('semua', $kt)) {
                                    $jawaban = fetch($koneksi, 'jawaban_tugas', ['id_siswa'=>$siswa['id_siswa'], 'id_tugas'=>$t['id_tugas']]);
                                    $nilai_tugas = isset($jawaban['nilai']) && $jawaban['nilai']!=='' ? $jawaban['nilai'] : null;
                                    $combined_all[] = [
                                        'tipe' => 'TUGAS',
                                        'label' => '(TUGAS) '.($t['judul'] ?? ''),
                                        'judul' => $t['judul'] ?? '',
                                        'selesai' => $t['tgl_selesai'] ?? null,
                                        'mulai' => $t['tgl_mulai'] ?? null,
                                        'uploaded' => $t['tgl_mulai'] ?? null,
                                        'nilai_txt' => $nilai_tugas!==null ? ('Nilai: '.htmlspecialchars($nilai_tugas)) : '',
                                        'status_txt' => $jawaban ? 'Sudah dikerjakan' : 'Belum dikerjakan',
                                        'status_done' => $jawaban ? true : false,
                                        'href' => '?pg=bukatugas&id='.(int)$t['id_tugas'],
                                        'sort_key' => $t['judul'] ?? ''
                                    ];
                                }
                            }
                            mysqli_free_result($q_t_all);
                        }

                        // Urutkan hanya berdasarkan tgl_mulai (terlama dulu -> paling atas)
                        usort($combined_all, function($a,$b){
                            $ta = strtotime($a['uploaded'] ?? '1970-01-01 00:00:00');
                            $tb = strtotime($b['uploaded'] ?? '1970-01-01 00:00:00');
                            return $ta <=> $tb;
                        });

                        $ddid = 'dd_konten_'.(int)$materi['id_materi'].'_all';
                        $count_dd = count($combined_all);
                        $base_query = '?pg='.$current_pg; // tidak ada parameter mapel di halaman semua mapel
                    ?>
                    <style>
                        .learn-sublist {list-style:none;padding:0;margin:16px 0 0 0}
                        .learn-sublist li {display:flex;align-items:center;justify-content:space-between;border:1px solid #eef0f2;border-radius:10px;padding:10px 12px;margin-bottom:8px;background:#fafbfc}
                        .learn-sublist .meta {display:flex;align-items:center;gap:10px}
                        .learn-sublist li a{display:flex;align-items:center;justify-content:space-between;width:100%;text-decoration:none;color:inherit}
                        .badge-status {display:inline-block;padding:3px 8px;border-radius:12px;font-size:.7rem;margin-bottom:4px}
                        .badge-status.done {background:#e7f5ec;color:#198754}
                        .badge-status.notyet {background:#f8f9fa;color:#6c757d}
                        .guru-banner{background:#001BFF;color:#fff;border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:12px;margin-bottom:12px}
                        .guru-banner .guru-avatar{width:48px;height:48px;border-radius:50%;background:#fff;padding:2px;object-fit:cover}
                        .guru-banner .guru-title{opacity:.9;font-size:.9rem}
                        .guru-banner .guru-name{font-weight:600}
                    </style>
                    <div class="guru-banner" style="background: <?= $subject_color_mapel ?>; cursor:pointer;" onclick="toggleSubject('<?= $panel_id ?>')">
                        <?php $foto = !empty($guru_panel['foto']) ? '../images/'.htmlspecialchars($guru_panel['foto']) : '../images/guru.png'; ?>
                        <img src="<?= $foto ?>" class="guru-avatar" alt="Guru">
                        <div>
                            <div class="guru-title">Guru Pengampu</div>
                            <div class="guru-name"><?= htmlspecialchars($guru_panel['nama'] ?? '-') ?></div>
                        </div>
                    </div>
                    <div id="<?= $panel_id ?>" class="subject-body">
                    <ul class="learn-sublist mt-2">
                        <?php if (empty($combined_all)): ?>
                            <li><span class="text-muted d-block p-2">Tidak ada konten</span></li>
                        <?php else: foreach ($combined_all as $it): ?>
                            <li>
                                <a href="<?= htmlspecialchars($it['href']) ?>">
                                    <div class="meta">
                                        <?php if ($it['tipe']==='MATERI'): ?>
                                            <i class="fas fa-book"></i>
                                        <?php elseif ($it['tipe']==='QUIZ'): ?>
                                            <i class="fas fa-question-circle"></i>
                                        <?php else: ?>
                                            <i class="fas fa-tasks"></i>
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($it['label']) ?></strong>
                                    </div>
                                    <div class="text-end" style="font-size:.8rem;">
                                        <span class="badge-status <?= !empty($it['status_done']) ? 'done' : 'notyet' ?>"><?= htmlspecialchars($it['status_txt'] ?? '') ?></span>
                                        <div class="text-muted">Selesai: <?= format_tanggal_indonesia($it['selesai']) ?><?= !empty($it['nilai_txt']) ? ' • '.htmlspecialchars($it['nilai_txt']) : '' ?></div>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; endif; ?>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
                endif; // end cek kelas
            endwhile;
        endif;

        if (!$ada_materi) {
            echo '<div class="col-12"><div class="alert alert-info">Belum ada materi aktif untuk kelas Anda.</div></div>';
        }
        ?>
    </div>

    <!-- POPUP UNTUK DAFTAR TUGAS (semua mapel) -->
    <div id="tugasModal" class="modal-overlay">
        <div class="modal-content-custom">
            <div class="modal-header">
                <h5 id="modalTitle">Daftar Tugas</h5>
                <button class="modal-close-btn" onclick="closeTugasModal()">&times;</button>
            </div>
            <div class="modal-body">
                <ul class="task-list">
                    <?php if (empty($tugas_list)): ?>
                        <li class="task-list-item" style="justify-content: center;">Tidak ada tugas untuk Anda saat ini.</li>
                    <?php else: ?>
                        <?php foreach($tugas_list as $tugas): ?>
                            <a href="?pg=bukatugas&id=<?= $tugas['id_tugas'] ?>" class="task-list-item">
                                <div class="task-info">
                                    <span class="status <?= strtolower($tugas['status']) ?>"><?= $tugas['status'] ?></span>
                                    <span class="title"><?= htmlspecialchars($tugas['judul']) ?></span>
                                </div>
                                <div class="task-nilai">Nilai: <?= $tugas['nilai'] ?></div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        const tugasModal = document.getElementById('tugasModal');
        function openTugasModal() { tugasModal.classList.add('show'); }
        function closeTugasModal() { tugasModal.classList.remove('show'); }
        tugasModal.addEventListener('click', function(event) { if (event.target === tugasModal) { closeTugasModal(); } });
        // Dropdown materi dibuka saat hover via CSS (.dd:hover .dd-menu)
        function toggleSubject(id){
            var el = document.getElementById(id);
            if(!el) return;
            if(el.classList.contains('show')){ el.classList.remove('show'); }
            else { el.classList.add('show'); }
        }
    </script>
<?php
} // Tutup dari else
?>
