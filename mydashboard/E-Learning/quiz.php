<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Pastikan parameter materi ada
$id_materi = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_materi <= 0) {
    echo '<div class="alert alert-danger">Parameter materi tidak valid.</div>';
    return;
}

// Ambil data materi
$stmt = $koneksi->prepare("SELECT * FROM materi WHERE id_materi=? LIMIT 1");
$stmt->bind_param('i', $id_materi);
$stmt->execute();
$materi_res = $stmt->get_result();
if ($materi_res->num_rows === 0) {
    echo '<div class="alert alert-warning">Materi tidak ditemukan.</div>';
    return;
}
$materi = $materi_res->fetch_assoc();
$stmt->close();

// Ambil info mapel (id dan nama)
$mapel_id = null;
$nama_mapel = $materi['mapel'];
$stmt = $koneksi->prepare("SELECT id, nama_mapel FROM mata_pelajaran WHERE kode=? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('s', $materi['mapel']);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows) {
        $row = $res->fetch_assoc();
        $mapel_id = (int)$row['id'];
        $nama_mapel = $row['nama_mapel'];
    }
    $stmt->close();
}

// Ambil daftar soal quiz untuk materi ini
$soal = [];
// Pastikan tabel jawaban_quiz tersedia (idempotent)
$__ct_sql_fk = "CREATE TABLE IF NOT EXISTS `jawaban_quiz` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `id_materi` INT NOT NULL,
  `id_quiz` INT NOT NULL,
  `id_siswa` INT NOT NULL,
  `jawaban` LONGTEXT NULL,
  `skor` DECIMAL(6,2) DEFAULT NULL,
  `waktu_submit` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attempt` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jawaban_once` (`id_materi`,`id_quiz`,`id_siswa`,`attempt`),
  KEY `idx_jawaban_siswa` (`id_siswa`),
  KEY `idx_jawaban_materi` (`id_materi`),
  CONSTRAINT `fk_jawaban_quiz_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
$__ct_ok = $koneksi->query($__ct_sql_fk);
if ($__ct_ok === false) {
    error_log('CREATE TABLE jawaban_quiz (FK) gagal: '. $koneksi->error);
    // fallback tanpa FK (untuk kompatibilitas engine)
    $__ct_sql_nofk = "CREATE TABLE IF NOT EXISTS `jawaban_quiz` (
      `id` BIGINT NOT NULL AUTO_INCREMENT,
      `id_materi` INT NOT NULL,
      `id_quiz` INT NOT NULL,
      `id_siswa` INT NOT NULL,
      `jawaban` LONGTEXT NULL,
      `skor` DECIMAL(6,2) DEFAULT NULL,
      `waktu_submit` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `attempt` INT NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_jawaban_once` (`id_materi`,`id_quiz`,`id_siswa`,`attempt`),
      KEY `idx_jawaban_siswa` (`id_siswa`),
      KEY `idx_jawaban_materi` (`id_materi`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $__ct_ok2 = $koneksi->query($__ct_sql_nofk);
    if ($__ct_ok2 === false) { error_log('CREATE TABLE jawaban_quiz (no FK) gagal: '. $koneksi->error); }
}
$q = $koneksi->query("SELECT id, nomor, jenis, pertanyaan, media, opsi, kunci, skor_max FROM quiz WHERE id_materi=".(int)$id_materi." ORDER BY nomor ASC, id ASC");
if ($q) {
    while ($r = $q->fetch_assoc()) {
        $r['media'] = $r['media'] ? json_decode($r['media'], true) : null;
        $r['opsi']  = $r['opsi']  ? json_decode($r['opsi'], true) : null;
        $r['kunci'] = $r['kunci'] ? json_decode($r['kunci'], true) : null;
        $soal[] = $r;
    }
    $q->close();
}

// Jika submit jawaban
$submitted = false;
$hasil_ringkas = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($soal)) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $id_siswa = (int)($_SESSION['id_siswa'] ?? 0);
    if ($id_siswa <= 0) {
        echo '<div class="alert alert-danger">Sesi siswa tidak ditemukan. Silakan login ulang.</div>';
        return;
    }
    // Cari attempt berikutnya
    $next_attempt = 1;
    $rs = $koneksi->query("SELECT COALESCE(MAX(attempt),0) mx FROM jawaban_quiz WHERE id_materi=".(int)$id_materi." AND id_siswa=".$id_siswa);
    if ($rs) { $row = $rs->fetch_assoc(); $next_attempt = ((int)$row['mx']) + 1; $rs->close(); }

    $total_skor = 0.0; $total_maks_otomatis = 0.0; $otomatis_dinilai = 0;

    foreach ($soal as $s) {
        $qid = (int)$s['id'];
        $jenis = $s['jenis'];
        $skor_max = (float)$s['skor_max'];
        $jawab = null; $skor = null;

        if ($jenis === 'pg') {
            $jawab = isset($_POST['q_'.$qid]) ? (int)$_POST['q_'.$qid] : null;
            if ($jawab !== null) {
                $skor = ((string)$jawab === (string)$s['kunci']) ? $skor_max : 0.0;
                $total_skor += $skor; $total_maks_otomatis += $skor_max; $otomatis_dinilai++;
            }
        } elseif ($jenis === 'pgc') {
            $jawab = isset($_POST['q_'.$qid]) ? $_POST['q_'.$qid] : [];
            if (!is_array($jawab)) $jawab = [];
            sort($jawab);
            $k = $s['kunci']; if (!is_array($k)) $k = [];
            $k2 = $k; sort($k2);
            $skor = ($jawab === $k2) ? $skor_max : 0.0;
            $total_skor += $skor; $total_maks_otomatis += $skor_max; $otomatis_dinilai++;
        } elseif ($jenis === 'benar_salah') {
            $jawab = isset($_POST['q_'.$qid]) ? $_POST['q_'.$qid] : null; // 'benar' / 'salah'
            if ($jawab !== null) {
                $skor = ((string)$jawab === (string)$s['kunci']) ? $skor_max : 0.0;
                $total_skor += $skor; $total_maks_otomatis += $skor_max; $otomatis_dinilai++;
            }
        } elseif ($jenis === 'menjodohkan') {
            // Simpan sebagai pasangan {left: right}
            $jawab = isset($_POST['q_'.$qid]) ? $_POST['q_'.$qid] : [];
            if (!is_array($jawab)) $jawab = [];
            // Nilai sederhana: benar jika seluruh pasangan sama persis
            $k = $s['kunci'];
            if (is_array($k)) {
                ksort($jawab); ksort($k);
                $skor = ($jawab === $k) ? $skor_max : 0.0;
                $total_skor += $skor; $total_maks_otomatis += $skor_max; $otomatis_dinilai++;
            } else { $skor = null; }
        } elseif ($jenis === 'isian_singkat') {
            $jawab = isset($_POST['q_'.$qid]) ? trim((string)$_POST['q_'.$qid]) : '';
            // Opsi auto-grade jika kunci ada
            if ($s['kunci'] !== null && $jawab !== '') {
                $skor = (mb_strtolower(trim((string)$s['kunci'])) === mb_strtolower($jawab)) ? $skor_max : 0.0;
                $total_skor += $skor; $total_maks_otomatis += $skor_max; $otomatis_dinilai++;
            }
        } elseif ($jenis === 'uraian') {
            $jawab = isset($_POST['q_'.$qid]) ? trim((string)$_POST['q_'.$qid]) : '';
            $skor = null; // dinilai guru
        }

        // Simpan ke DB (tangani skor NULL secara khusus agar tidak gagal bind)
        $jawab_json = $jawab === null ? null : json_encode($jawab);
        if ($skor === null) {
            $stmt = $koneksi->prepare("INSERT INTO jawaban_quiz (id_materi,id_quiz,id_siswa,jawaban,skor,attempt) VALUES (?,?,?,?,NULL,?)");
            if ($stmt) {
                $stmt->bind_param('iiisi', $id_materi, $qid, $id_siswa, $jawab_json, $next_attempt);
                $ok = $stmt->execute();
                if (!$ok) { error_log('Insert jawaban_quiz NULL skor gagal: '. $stmt->error); }
                $stmt->close();
            } else { error_log('Prepare jawaban_quiz (NULL skor) gagal: '. $koneksi->error); }
        } else {
            $stmt = $koneksi->prepare("INSERT INTO jawaban_quiz (id_materi,id_quiz,id_siswa,jawaban,skor,attempt) VALUES (?,?,?,?,?,?)");
            if ($stmt) {
                $stmt->bind_param('iiisdi', $id_materi, $qid, $id_siswa, $jawab_json, $skor, $next_attempt);
                $ok = $stmt->execute();
                if (!$ok) { error_log('Insert jawaban_quiz gagal: '. $stmt->error); }
                $stmt->close();
            } else { error_log('Prepare jawaban_quiz gagal: '. $koneksi->error); }
        }
    }

    $submitted = true;
    $hasil_ringkas = [
        'otomatis_dinilai' => $otomatis_dinilai,
        'total_skor' => $total_skor,
        'total_maks' => $total_maks_otomatis,
        'attempt' => $next_attempt,
    ];

    // Simpan/akumulasi ke nilai_harian dan sinkronkan ke nilai_sts (rata-rata)
    try {
        // Hanya proses jika ada butir auto-graded
        if ($total_maks_otomatis > 0 && $otomatis_dinilai > 0 && $mapel_id !== null) {
            $nilai_quiz = (int)round(($total_skor / $total_maks_otomatis) * 100);

            // Data umum
            $id_siswa = (int)$id_siswa; // from session above
            // Ambil kelas siswa dari DB
            $kelas_siswa = '';
            $stmt_kls = $koneksi->prepare("SELECT kelas FROM siswa WHERE id_siswa=? LIMIT 1");
            if ($stmt_kls) {
                $stmt_kls->bind_param('i', $id_siswa);
                $stmt_kls->execute();
                $res_kls = $stmt_kls->get_result();
                if ($res_kls && $res_kls->num_rows) {
                    $rowk = $res_kls->fetch_assoc();
                    $kelas_siswa = (string)($rowk['kelas'] ?? '');
                }
                $stmt_kls->close();
            }
            $id_guru = (int)($materi['id_guru'] ?? 0);
            $hari = date('D');
            $tanggal = date('Y-m-d');
            $semester = (string)($setting['semester'] ?? '');
            $tp = (string)($setting['tp'] ?? '');
            $kuri = '2'; // default kurikulum (Merdeka); sesuaikan jika perlu

            // Ambil KKM jika ada (berdasarkan tingkat kelas dan kode mapel)
            $kkm = 0;
            $tingkat = '';
            if (!empty($kelas_siswa)) {
                $parts = preg_split('/\s+/', trim($kelas_siswa));
                $tingkat = $parts[0] ?? '';
            }
            if ($tingkat !== '' && !empty($materi['mapel'])) {
                $stmt_kkm = $koneksi->prepare("SELECT kkm FROM mapel_rapor WHERE tingkat=? AND mapel=? LIMIT 1");
                if ($stmt_kkm) {
                    $stmt_kkm->bind_param('ss', $tingkat, $materi['mapel']);
                    $stmt_kkm->execute();
                    $res_kkm = $stmt_kkm->get_result();
                    if ($res_kkm && $res_kkm->num_rows) {
                        $row_kkm = $res_kkm->fetch_assoc();
                        $kkm = (int)($row_kkm['kkm'] ?? 0);
                    }
                    $stmt_kkm->close();
                }
            }

            // Materi deskripsi ditandai agar mudah dilacak dan dihapus jika perlu
            $materi_desc = 'QUIZ#' . (int)$id_materi . ' - ' . (string)$materi['judul'];

            mysqli_begin_transaction($koneksi);

            // Upsert ke nilai_harian: 1 baris per (idsiswa, mapel, semester, tp, materi=QUIZ#id_materi)
            $existing_id = null;
            $like_key = 'QUIZ#' . (int)$id_materi . '%';
            $stmt_find = $koneksi->prepare("SELECT id FROM nilai_harian WHERE idsiswa=? AND mapel=? AND semester=? AND tapel=? AND materi LIKE ? LIMIT 1");
            if ($stmt_find) {
                $stmt_find->bind_param('iisss', $id_siswa, $mapel_id, $semester, $tp, $like_key);
                $stmt_find->execute();
                $res_find = $stmt_find->get_result();
                if ($res_find && $res_find->num_rows) {
                    $rowf = $res_find->fetch_assoc();
                    $existing_id = (int)$rowf['id'];
                }
                $stmt_find->close();
            }

            if ($existing_id) {
                $stmt_upd = $koneksi->prepare("UPDATE nilai_harian SET nilai=?, hari=?, tanggal=?, kelas=?, kkm=?, kuri=?, guru=?, materi=? WHERE id=?");
                if ($stmt_upd) {
                    $stmt_upd->bind_param('isssisisi', $nilai_quiz, $hari, $tanggal, $kelas_siswa, $kkm, $kuri, $id_guru, $materi_desc, $existing_id);
                    $stmt_upd->execute();
                    $stmt_upd->close();
                }
            } else {
                $stmt_ins = $koneksi->prepare("INSERT INTO nilai_harian (idsiswa, hari, tanggal, kelas, mapel, kkm, kuri, nilai, guru, materi, semester, tapel) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                if ($stmt_ins) {
                    $stmt_ins->bind_param('isssiisiisss', $id_siswa, $hari, $tanggal, $kelas_siswa, $mapel_id, $kkm, $kuri, $nilai_quiz, $id_guru, $materi_desc, $semester, $tp);
                    $stmt_ins->execute();
                    $stmt_ins->close();
                }
            }

            // Hitung ulang rata-rata untuk nilai_sts.nilai_harian
            $total = 0; $jumlah = 0;
            $stmt_rr = $koneksi->prepare("SELECT SUM(nilai) AS total, COUNT(*) AS jumlah FROM nilai_harian WHERE idsiswa=? AND mapel=? AND semester=? AND tapel=?");
            if ($stmt_rr) {
                $stmt_rr->bind_param('iiss', $id_siswa, $mapel_id, $semester, $tp);
                $stmt_rr->execute();
                $res_rr = $stmt_rr->get_result();
                if ($res_rr && $res_rr->num_rows) {
                    $rowrr = $res_rr->fetch_assoc();
                    $total = (float)($rowrr['total'] ?? 0);
                    $jumlah = (int)($rowrr['jumlah'] ?? 0);
                }
                $stmt_rr->close();
            }
            $rata = ($jumlah > 0) ? (int)round($total / $jumlah) : 0;

            // Update/insert nilai_sts
            $sts_id = null;
            $stmt_sts_find = $koneksi->prepare("SELECT id FROM nilai_sts WHERE idsiswa=? AND mapel=? AND semester=? AND tp=? LIMIT 1");
            if ($stmt_sts_find) {
                $stmt_sts_find->bind_param('iiss', $id_siswa, $mapel_id, $semester, $tp);
                $stmt_sts_find->execute();
                $res_sts_find = $stmt_sts_find->get_result();
                if ($res_sts_find && $res_sts_find->num_rows) {
                    $row_sts = $res_sts_find->fetch_assoc();
                    $sts_id = (int)$row_sts['id'];
                }
                $stmt_sts_find->close();
            }

            if ($sts_id) {
                $stmt_sts_upd = $koneksi->prepare("UPDATE nilai_sts SET nilai_harian=? WHERE id=?");
                if ($stmt_sts_upd) {
                    $stmt_sts_upd->bind_param('ii', $rata, $sts_id);
                    $stmt_sts_upd->execute();
                    $stmt_sts_upd->close();
                }
            } else {
                // Perlu NIS untuk insert baru
                $nis = '';
                $stmt_nis = $koneksi->prepare("SELECT nis FROM siswa WHERE id_siswa=? LIMIT 1");
                if ($stmt_nis) {
                    $stmt_nis->bind_param('i', $id_siswa);
                    $stmt_nis->execute();
                    $res_nis = $stmt_nis->get_result();
                    if ($res_nis && $res_nis->num_rows) {
                        $rown = $res_nis->fetch_assoc();
                        $nis = (string)($rown['nis'] ?? '');
                    }
                    $stmt_nis->close();
                }

                $stmt_sts_ins = $koneksi->prepare("INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_harian, guru, semester, tp) VALUES (?,?,?,?,?,?,?,?)");
                if ($stmt_sts_ins) {
                    $stmt_sts_ins->bind_param('isssiiss', $id_siswa, $nis, $kelas_siswa, $mapel_id, $rata, $id_guru, $semester, $tp);
                    $stmt_sts_ins->execute();
                    $stmt_sts_ins->close();
                }
            }

            mysqli_commit($koneksi);
        }
    } catch (Throwable $e) {
        if ($koneksi && $koneksi->errno === 0) {
            @mysqli_rollback($koneksi);
        }
        // Diamkan error untuk user, tapi Anda bisa log jika perlu
    }
}

?>

<style>
.quiz-header {background:#fff;border:1px solid #e9ecef;border-radius:12px;padding:18px;margin-bottom:18px;display:flex;justify-content:space-between;align-items:center}
.quiz-card {background:#fff;border:1px solid #e9ecef;border-radius:12px;margin-bottom:16px;overflow:hidden}
.quiz-card .q-title {padding:14px 18px;font-weight:600;border-bottom:1px solid #f1f3f4}
.quiz-card .q-body {padding:16px 18px}
.quiz-actions {display:flex;gap:10px;justify-content:flex-end;margin-top:8px}
.badge-soft {background:#eef5ff;color:#356ac3;padding:4px 10px;border-radius:12px;font-size:.8rem}
.opt {display:flex;align-items:center;gap:10px;margin-bottom:10px}
.opt img {max-height:60px;border-radius:6px;border:1px solid #eee}
.subtle {color:#6c757d;font-size:.9rem}
.btn-primary {border-radius:10px}
</style>

<div class="quiz-header">
  <div>
    <div class="subtle">Mata Pelajaran</div>
    <h5 class="mb-0"><?= htmlspecialchars($nama_mapel) ?></h5>
    <div class="subtle">Tema: <?= htmlspecialchars($materi['judul']) ?></div>
  </div>
  <a href="?pg=<?= enkripsi('materi') ?>&mapel=<?= urlencode($materi['mapel']) ?>" class="btn btn-light"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php if (empty($soal)) : ?>
  <div class="alert alert-info">Belum ada soal quiz untuk materi ini.</div>
<?php elseif ($submitted) : ?>
  <div class="alert alert-success">
    <div class="d-flex align-items-center"><i class="fas fa-check-circle mr-2"></i>
      <strong>Jawaban tersimpan.</strong>
    </div>
    <?php if ($hasil_ringkas && $hasil_ringkas['total_maks'] > 0): ?>
      <div class="mt-2">Skor otomatis: <?= number_format($hasil_ringkas['total_skor'],2) ?> / <?= number_format($hasil_ringkas['total_maks'],2) ?> (Attempt <?= (int)$hasil_ringkas['attempt'] ?>)</div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (!empty($soal) && !$submitted): ?>
<form method="post">
  <?php foreach ($soal as $idx => $s): ?>
    <div class="quiz-card">
      <div class="q-title">Soal <?= (int)$s['nomor'] ?> <span class="badge-soft ml-2">Skor Maks: <?= (float)$s['skor_max'] ?></span></div>
      <div class="q-body">
        <div class="mb-2"><?= $s['pertanyaan'] ?></div>
        <?php if (is_array($s['media']) && !empty($s['media']['url'])): ?>
          <div class="mb-2"><img src="<?= htmlspecialchars($s['media']['url']) ?>" style="max-width:100%;border-radius:8px;border:1px solid #eee" alt="media"></div>
        <?php endif; ?>

        <?php if ($s['jenis'] === 'pg'): ?>
            <?php foreach ((array)$s['opsi'] as $i => $op): ?>
              <label class="opt">
                <input type="radio" name="q_<?= (int)$s['id'] ?>" value="<?= (int)$i ?>" required>
                <span><?= htmlspecialchars($op['text'] ?? (string)$op) ?></span>
                <?php if (is_array($op) && !empty($op['img'])): ?>
                  <img src="<?= htmlspecialchars($op['img']) ?>" alt="">
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
        <?php elseif ($s['jenis'] === 'pgc'): ?>
            <?php foreach ((array)$s['opsi'] as $i => $op): ?>
              <label class="opt">
                <input type="checkbox" name="q_<?= (int)$s['id'] ?>[]" value="<?= (int)$i ?>">
                <span><?= htmlspecialchars($op['text'] ?? (string)$op) ?></span>
                <?php if (is_array($op) && !empty($op['img'])): ?>
                  <img src="<?= htmlspecialchars($op['img']) ?>" alt="">
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
        <?php elseif ($s['jenis'] === 'benar_salah'): ?>
            <label class="opt"><input type="radio" name="q_<?= (int)$s['id'] ?>" value="benar" required> <span>Benar</span></label>
            <label class="opt"><input type="radio" name="q_<?= (int)$s['id'] ?>" value="salah" required> <span>Salah</span></label>
        <?php elseif ($s['jenis'] === 'isian_singkat'): ?>
            <input type="text" class="form-control" name="q_<?= (int)$s['id'] ?>" placeholder="Ketik jawaban singkat...">
        <?php elseif ($s['jenis'] === 'uraian'): ?>
            <textarea class="form-control" name="q_<?= (int)$s['id'] ?>" rows="5" placeholder="Tulis jawaban uraian..."></textarea>
        <?php elseif ($s['jenis'] === 'menjodohkan'): ?>
            <?php
              // Tampilkan sebagai tabel left-right, jawaban berupa mapping kiri->kanan
              $pairs = is_array($s['opsi']) ? $s['opsi'] : [];
              $right_options = array_map(function($p){ return $p['right'] ?? ''; }, $pairs);
            ?>
            <?php foreach ($pairs as $i => $p): ?>
              <div class="row mb-2">
                <div class="col-sm-6"><div class="p-2 border rounded bg-light"><?= htmlspecialchars($p['left'] ?? '') ?></div></div>
                <div class="col-sm-6">
                  <select name="q_<?= (int)$s['id'] ?>[<?= (int)$i ?>]" class="form-control">
                    <option value="">Pilih pasangan...</option>
                    <?php foreach ($right_options as $opt): ?>
                      <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="quiz-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Jawaban</button>
  </div>
</form>
<?php endif; ?>
