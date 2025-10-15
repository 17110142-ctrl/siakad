<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
// Cetak Nilai STS per kelas dan semester (tampilan halaman + tombol export)
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/function.php';

cek_session_guru();

// Ambil TP aktif dari setting aplikasi sebagai default
$tp_aktif = '';
if ($q = mysqli_query($koneksi, "SELECT tp FROM aplikasi WHERE id_aplikasi='1'")) {
    $r = mysqli_fetch_assoc($q);
    $tp_aktif = $r['tp'] ?? '';
}

// Ambil daftar kelas dari tabel siswa
$kelas_list = [];
if ($qk = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa WHERE kelas<>'' ORDER BY kelas")) {
    while ($rk = mysqli_fetch_assoc($qk)) { $kelas_list[] = $rk['kelas']; }
}

$kelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
$semester = isset($_GET['semester']) ? trim($_GET['semester']) : '';
$tp = isset($_GET['tp']) && $_GET['tp'] !== '' ? trim($_GET['tp']) : $tp_aktif;
$do_query = ($kelas !== '' && $semester !== '');

// Siapkan data bila parameter lengkap
$students = [];
$mapel = [];
$nilai_map = [];// [idsiswa][id_mapel] => nilai_sts (default 0)
if ($do_query) {
    // Data siswa di kelas
    $stmt = mysqli_prepare($koneksi, "SELECT id_siswa, nis, nama FROM siswa WHERE kelas = ? ORDER BY nama");
    mysqli_stmt_bind_param($stmt, 's', $kelas);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) { $students[] = $row; }
    mysqli_stmt_close($stmt);

    // Tentukan level & jurusan kelas untuk penarikan mapel
    $class_level   = '';
    $class_jurusan = '';
    if (!empty($students)) {
        $class_level   = $students[0]['level'] ?? '';
        $class_jurusan = $students[0]['jurusan'] ?? '';
    }
    if ($class_level === '' || $class_jurusan === '') {
        $kelasEsc = mysqli_real_escape_string($koneksi, $kelas);
        $kelasRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT level, jurusan FROM kelas WHERE kelas='$kelasEsc' LIMIT 1"));
        if ($kelasRow) {
            if ($class_level === '') {
                $class_level = $kelasRow['level'] ?? '';
            }
            if ($class_jurusan === '') {
                $class_jurusan = $kelasRow['jurusan'] ?? '';
            }
        }
    }
    if ($class_jurusan === '' || $class_jurusan === null) {
        $class_jurusan = 'semua';
    }

    // Ambil mapel dari konfigurasi mapel_rapor terlebih dahulu
    if ($class_level !== '') {
        $levelEsc = mysqli_real_escape_string($koneksi, $class_level);
        $jurEsc   = mysqli_real_escape_string($koneksi, $class_jurusan);
        $sqlMapelRapor = "SELECT mr.mapel AS id, mp.kode, mp.nama_mapel FROM mapel_rapor mr JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.kurikulum='2' AND mr.tingkat='$levelEsc' AND (mr.pk='$jurEsc' OR mr.pk='semua' OR mr.pk='' OR mr.pk IS NULL) ORDER BY mr.urut, mp.nama_mapel";
        $qm = mysqli_query($koneksi, $sqlMapelRapor);
        while ($rm = mysqli_fetch_assoc($qm)) {
            $mapel[] = [
                'id'   => (int)$rm['id'],
                'kode' => $rm['kode'] ?? '',
                'nama_mapel' => $rm['nama_mapel'] ?? ''
            ];
        }
    }

    // Jika belum ada, coba dari jadwal mapel kelas
    if (empty($mapel)) {
        $kelasEsc = mysqli_real_escape_string($koneksi, $kelas);
        $qm = mysqli_query($koneksi, "SELECT jm.mapel AS id, mp.kode, mp.nama_mapel FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id = jm.mapel WHERE jm.kelas = '$kelasEsc' GROUP BY jm.mapel, mp.kode, mp.nama_mapel ORDER BY mp.nama_mapel");
        while ($rm = mysqli_fetch_assoc($qm)) {
            $mapel[] = [
                'id'   => (int)$rm['id'],
                'kode' => $rm['kode'] ?? '',
                'nama_mapel' => $rm['nama_mapel'] ?? ''
            ];
        }
    }

    // Fallback terakhir: seluruh mata pelajaran
    if (empty($mapel)) {
        $qm = mysqli_query($koneksi, "SELECT id, kode, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel");
        while ($rm = mysqli_fetch_assoc($qm)) {
            $mapel[] = [
                'id'   => (int)$rm['id'],
                'kode' => $rm['kode'] ?? '',
                'nama_mapel' => $rm['nama_mapel'] ?? ''
            ];
        }
    }

    // Index siswa dan mapel
    $idsiswa_arr = array_map(function($s){ return (int)$s['id_siswa']; }, $students);
    $mapel_ids = array_map(function($m){ return (int)$m['id']; }, $mapel);

    // Inisialisasi nilai default 0
    if (!empty($mapel_ids)) {
        foreach ($students as $s) {
            $sid = (int)$s['id_siswa'];
            $nilai_map[$sid] = [];
            foreach ($mapel as $m) {
                $mid = (int)$m['id'];
                $nilai_map[$sid][$mid] = 0;
            }
        }
    }

    if (!empty($idsiswa_arr) && !empty($mapel_ids)) {
        $in_siswa = implode(',', array_map('intval', $idsiswa_arr));
        $in_mapel = implode(',', array_map('intval', $mapel_ids));
        $sem_esc = mysqli_real_escape_string($koneksi, $semester);
        $tp_esc  = mysqli_real_escape_string($koneksi, $tp);
        $sql = "SELECT idsiswa, mapel, nilai_sts FROM nilai_sts 
                WHERE semester='$sem_esc' AND tp='$tp_esc' 
                  AND idsiswa IN ($in_siswa) AND mapel IN ($in_mapel)";
        $qn = mysqli_query($koneksi, $sql);
        while ($rn = mysqli_fetch_assoc($qn)) {
            $sid = (int)$rn['idsiswa'];
            $mid = (int)$rn['mapel'];
            $val = $rn['nilai_sts'];
            $nilai_map[$sid][$mid] = is_numeric($val) ? (int)$val : 0;
        }
    }
}

?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="bold" style="margin:0">Cetak Nilai STS</h5>
        <?php if ($do_query && !empty($students)): ?>
        <div>
          <a target="_blank" href="walas/export_nilai_sts.php?format=excel&kelas=<?= urlencode($kelas) ?>&semester=<?= urlencode($semester) ?>&tp=<?= urlencode($tp) ?>" class="btn btn-sm btn-success"><i class="material-icons">table_view</i> Export Excel</a>
          <a target="_blank" href="walas/export_nilai_sts.php?format=xlsx&kelas=<?= urlencode($kelas) ?>&semester=<?= urlencode($semester) ?>&tp=<?= urlencode($tp) ?>" class="btn btn-sm btn-info"><i class="material-icons">grid_on</i> Export XLSX (Template)</a>
          <a target="_blank" href="walas/export_nilai_sts.php?format=print&kelas=<?= urlencode($kelas) ?>&semester=<?= urlencode($semester) ?>&tp=<?= urlencode($tp) ?>" class="btn btn-sm btn-primary"><i class="material-icons">picture_as_pdf</i> Export PDF</a>
          <a target="_blank" href="walas/export_nilai_sts.php?format=pts_pdf&kelas=<?= urlencode($kelas) ?>&semester=<?= urlencode($semester) ?>&tp=<?= urlencode($tp) ?>" class="btn btn-sm btn-dark"><i class="material-icons">print</i> Raport PTS (PDF)</a>
        </div>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <form class="row g-3" method="get" action="?pg=<?= enkripsi('cetak_sts') ?>">
          <input type="hidden" name="pg" value="<?= enkripsi('cetak_sts') ?>" />
          <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <select name="kelas" class="form-select" required>
              <option value="">-- Pilih Kelas --</option>
              <?php foreach ($kelas_list as $k): ?>
                <option value="<?= htmlspecialchars($k) ?>" <?= $k===$kelas?'selected':'' ?>><?= htmlspecialchars($k) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" required>
              <option value="">-- Pilih --</option>
              <option value="1" <?= $semester==='1'?'selected':'' ?>>1</option>
              <option value="2" <?= $semester==='2'?'selected':'' ?>>2</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tahun Pelajaran</label>
            <input type="text" class="form-control" name="tp" value="<?= htmlspecialchars($tp) ?>" placeholder="contoh: 2025/2026" />
          </div>
          <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-dark w-100"><i class="material-icons">search</i> Tampilkan</button>
          </div>
        </form>

        <?php if ($do_query): ?>
          <hr/>
          <div class="alert alert-secondary" style="font-size:12px">
            Tip: Untuk hasil sesuai desain, letakkan file template Excel di <code>assets/sts_template.xlsx</code> atau <a href="walas/build_sts_template.php?download=1" target="_blank">unduh template bawaan</a>, lalu gunakan tombol “Export XLSX (Template)”.
            Gunakan placeholder berikut di template: <code>{{KELAS}}</code>, <code>{{SEMESTER}}</code>, <code>{{TP}}</code>,
            <code>[[MAPEL_START]]</code> (sel awal judul mapel ke kanan), dan <code>[[DATA_ROW]]</code> (baris contoh data dengan urutan kolom: No, NIS, Nama, lalu nilai mapel).
          </div>
          <div class="mb-2 text-muted">Kelas: <strong><?= htmlspecialchars($kelas) ?></strong> | Semester: <strong><?= htmlspecialchars($semester) ?></strong> | TP: <strong><?= htmlspecialchars($tp) ?></strong></div>
          <?php if (empty($students)): ?>
            <div class="alert alert-info">Tidak ada siswa pada kelas ini.</div>
          <?php elseif (empty($mapel)): ?>
            <div class="alert alert-warning">Daftar mata pelajaran belum diatur untuk kelas <?= htmlspecialchars($kelas) ?>.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped" style="font-size:12px">
                <thead>
                  <tr>
                    <th rowspan="2" class="text-center align-middle" style="width:48px">No</th>
                    <th rowspan="2" class="text-center align-middle" style="min-width:100px">NIS</th>
                    <th rowspan="2" class="align-middle" style="min-width:220px;text-align:left">Nama</th>
                    <th colspan="<?= count($mapel) ?>" class="text-center">Nilai STS</th>
                  </tr>
                  <tr>
                    <?php foreach ($mapel as $m): ?>
                      <th class="text-center"><?= htmlspecialchars($m['kode'] ?: $m['nama_mapel']) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php $no=1; foreach ($students as $s): $sid=(int)$s['id_siswa']; ?>
                    <tr>
                      <td class="text-center"><?= $no++ ?></td>
                      <td class="text-center"><?= htmlspecialchars($s['nis']) ?></td>
                      <td>&nbsp;<?= htmlspecialchars($s['nama']) ?></td>
                      <?php foreach ($mapel as $m): $mid=(int)$m['id']; $v=$nilai_map[$sid][$mid] ?? 0; ?>
                        <td class="text-center"><?= (int)$v ?></td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
