<?php
ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// Optional: PhpSpreadsheet for XLSX/template mode
$phpss_ok = false;
if (@file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
    $phpss_ok = class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet');
}

session_start();
if (!isset($_SESSION['id_user'])) { die('Unauthorized'); }

$kelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
$semester = isset($_GET['semester']) ? trim($_GET['semester']) : '';
$tp = isset($_GET['tp']) ? trim($_GET['tp']) : '';
$format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : '';
if ($kelas === '' || $semester === '') { die('Parameter tidak lengkap'); }

// Ambil siswa di kelas (sertakan atribut tambahan untuk kebutuhan laporan)
$students = [];
$stmt = mysqli_prepare($koneksi, "SELECT id_siswa, nis, nisn, nama, kelas, level, jurusan, fase, sakit, izin, alpha FROM siswa WHERE kelas = ? ORDER BY nama");
mysqli_stmt_bind_param($stmt, 's', $kelas);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($res)) { $students[] = $row; }
mysqli_stmt_close($stmt);

// Ambil daftar mapel sesuai konfigurasi mapel_rapor / jadwal
$mapel = [];
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

// Nilai default 0
$nilai_map = [];
if (!empty($mapel)) {
  foreach ($students as $s) {
    $sid = (int)$s['id_siswa'];
    $nilai_map[$sid] = [];
    foreach ($mapel as $m) { $nilai_map[$sid][(int)$m['id']] = 0; }
  }
}

// Isi nilai dari nilai_sts
if (!empty($students) && !empty($mapel)) {
  $in_siswa = implode(',', array_map('intval', array_column($students,'id_siswa')));
  $in_mapel = implode(',', array_map('intval', array_column($mapel,'id')));
  $sem_esc = mysqli_real_escape_string($koneksi, $semester);
  $tp_esc  = mysqli_real_escape_string($koneksi, $tp);
  $sql = "SELECT idsiswa, mapel, nilai_sts FROM nilai_sts WHERE semester='$sem_esc' AND tp='$tp_esc' AND idsiswa IN ($in_siswa) AND mapel IN ($in_mapel)";
  $qn = mysqli_query($koneksi, $sql);
  while ($rn = mysqli_fetch_assoc($qn)) {
    $nilai_map[(int)$rn['idsiswa']][(int)$rn['mapel']] = is_numeric($rn['nilai_sts']) ? (int)$rn['nilai_sts'] : 0;
  }
}

// (dihapus) — definisi ganda fungsi render_table di bawah ini yang dipakai

// Helper render tabel sebagai HTML string (legacy .xls)
function render_table($kelas, $semester, $tp, $students, $mapel, $nilai_map) {
  ob_start();
  echo "<table border='1' cellpadding='4' cellspacing='0'>";
  echo "<tr><th colspan='".(3+count($mapel))."'>Daftar Nilai STS Kelas ".htmlspecialchars($kelas)." | Semester ".htmlspecialchars($semester)." | TP ".htmlspecialchars($tp)."</th></tr>";
  echo "<tr><th>No</th><th>NIS</th><th>Nama</th>";
  foreach ($mapel as $m) { echo "<th>".htmlspecialchars($m['kode'] ?: $m['nama_mapel'])."</th>"; }
  echo "</tr>";
  $no=1;
  foreach ($students as $s) {
    echo "<tr>";
    echo "<td>".$no++ ."</td>";
    echo "<td>".htmlspecialchars($s['nis'])."</td>";
    echo "<td>".htmlspecialchars($s['nama'])."</td>";
    foreach ($mapel as $m) {
      $mid = (int)$m['id']; $sid=(int)$s['id_siswa']; $v=$nilai_map[$sid][$mid] ?? 0;
      echo "<td>".(int)$v."</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
  return ob_get_clean();
}

function nilai_predikat_pts($nilai) {
  if (!is_numeric($nilai)) return '';
  $n = (float)$nilai;
  if ($n >= 90) return 'A';
  if ($n >= 80) return 'B';
  if ($n >= 70) return 'C';
  if ($n > 0) return 'D';
  return '';
}

// 1) New mode: xlsx using template if available
if ($format === 'xlsx') {
  if (!$phpss_ok) {
    // Fallback jika library belum tersedia
    $format = 'excel';
  } else {
    // Generate XLSX: gunakan template jika ada
    $template = __DIR__ . '/../../assets/sts_template.xlsx';
    $use_template = is_file($template);
    try {
        if ($use_template) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($template);
        } else {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        }
        $sheet = $spreadsheet->getActiveSheet();

        // Jika tidak menggunakan template, buat header sederhana
        if (!$use_template) {
            $sheet->setTitle('Nilai STS');
            $sheet->setCellValue('A1', 'Daftar Nilai STS');
            $sheet->setCellValue('A2', 'Kelas');
            $sheet->setCellValue('B2', $kelas);
            $sheet->setCellValue('A3', 'Semester');
            $sheet->setCellValue('B3', $semester);
            $sheet->setCellValue('A4', 'Tahun Pelajaran');
            $sheet->setCellValue('B4', $tp);
            // Header tabel mulai baris 6
            $hdrRow = 6; $col = 1;
            $sheet->setCellValueByColumnAndRow($col++, $hdrRow, 'No');
            $sheet->setCellValueByColumnAndRow($col++, $hdrRow, 'NIS');
            $sheet->setCellValueByColumnAndRow($col++, $hdrRow, 'Nama');
            foreach ($mapel as $m) {
                $sheet->setCellValueByColumnAndRow($col++, $hdrRow, ($m['kode'] ?: $m['nama_mapel']));
            }
            // Bold header
            $sheet->getStyle("A{$hdrRow}:".\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col-1).$hdrRow)
                  ->getFont()->setBold(true);

            // Tulis data
            $r = $hdrRow + 1; $no=1;
            foreach ($students as $s) {
                $c=1;
                $sheet->setCellValueByColumnAndRow($c++, $r, $no++);
                $sheet->setCellValueByColumnAndRow($c++, $r, $s['nis']);
                $sheet->setCellValueByColumnAndRow($c++, $r, $s['nama']);
                foreach ($mapel as $m) {
                    $mid=(int)$m['id']; $sid=(int)$s['id_siswa']; $v=$nilai_map[$sid][$mid] ?? 0;
                    $sheet->setCellValueByColumnAndRow($c++, $r, (int)$v);
                }
                $r++;
            }

            // Auto width sederhana
            foreach (range(1, 3+count($mapel)) as $i) {
                $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
            }
        } else {
            // Mode TEMPLATE: dukung placeholder berikut pada template
            // {{KELAS}}, {{SEMESTER}}, {{TP}}  -> akan diganti nilai sebenarnya
            // [[MAPEL_START]]  -> sel awal untuk menulis judul mapel ke kanan
            // [[DATA_ROW]]     -> baris contoh untuk menggandakan data siswa, mulai kolom A: No, B: NIS, C: Nama, D.. mapel
            $highestRow = $sheet->getHighestRow();
            $highestCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
            $posMapel = null; $dataRow = null;

            for ($r=1; $r <= $highestRow; $r++) {
                for ($c=1; $c <= $highestCol; $c++) {
                    $v = (string)$sheet->getCellByColumnAndRow($c,$r)->getValue();
                    if ($v === '{{KELAS}}') $sheet->setCellValueByColumnAndRow($c,$r,$kelas);
                    if ($v === '{{SEMESTER}}') $sheet->setCellValueByColumnAndRow($c,$r,$semester);
                    if ($v === '{{TP}}') $sheet->setCellValueByColumnAndRow($c,$r,$tp);
                    if ($v === '[[MAPEL_START]]') { $posMapel = [$r,$c]; $sheet->setCellValueByColumnAndRow($c,$r,''); }
                    if ($v === '[[DATA_ROW]]') { $dataRow = $r; $sheet->setCellValueByColumnAndRow($c,$r,''); }
                }
            }

            // Tulis header mapel jika penanda ditemukan
            if ($posMapel) {
                [$hr,$hc] = $posMapel; $cc=$hc;
                foreach ($mapel as $m) {
                    $sheet->setCellValueByColumnAndRow($cc++, $hr, ($m['kode'] ?: $m['nama_mapel']));
                }
            }

            // Gandakan baris data jika ada penanda [[DATA_ROW]]
            if ($dataRow) {
                $startRow = $dataRow; $no=1; $r = $startRow;
                // Ambil style baris contoh untuk disalin
                $templateRange = 'A'.$startRow.':'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3+max(1,count($mapel))).$startRow;
                foreach ($students as $s) {
                    if ($r > $startRow) { $sheet->insertNewRowBefore($r,1); }
                    // Salin style dari baris template jika ada
                    try { $sheet->duplicateStyle($sheet->getStyle($templateRange), 'A'.$r.':'.\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3+count($mapel)).$r); } catch (\Throwable $e) {}
                    $c=1;
                    $sheet->setCellValueByColumnAndRow($c++, $r, $no++);
                    $sheet->setCellValueByColumnAndRow($c++, $r, $s['nis']);
                    $sheet->setCellValueByColumnAndRow($c++, $r, $s['nama']);
                    foreach ($mapel as $m) {
                        $mid=(int)$m['id']; $sid=(int)$s['id_siswa']; $v=$nilai_map[$sid][$mid] ?? 0;
                        $sheet->setCellValueByColumnAndRow($c++, $r, (int)$v);
                    }
                    $r++;
                }
                // Hapus baris template sisa jika perlu (tidak perlu karena kita menimpa)
            }
        }

        $fname = 'nilai_sts_' . preg_replace('/[^A-Za-z0-9_-]+/','_', $kelas) . '_S' . $semester . '_' . preg_replace('/[^A-Za-z0-9_-]+/','_', $tp) . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$fname.'"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    } catch (\Throwable $e) {
        // Jika terjadi error apapun, fallback ke HTML excel agar pengguna tetap dapat mengunduh
        $format = 'excel';
    }
  }
}

// 2) Legacy mode: HTML table -> .xls (tetap dipertahankan)
if ($format === 'excel') {
  $fname = 'nilai_sts_' . preg_replace('/[^A-Za-z0-9_-]+/','_', $kelas) . '_S' . $semester . '_' . preg_replace('/[^A-Za-z0-9_-]+/','_', $tp) . '.xls';
  header('Content-Type: application/vnd.ms-excel; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.$fname.'"');
  header('Cache-Control: max-age=0');
  echo render_table($kelas, $semester, $tp, $students, $mapel, $nilai_map);
  exit;
}

// 3) Raport PTS (PDF via print) — digabungkan di endpoint ini
if ($format === 'pdf' || $format === 'pts' || $format === 'pts_pdf') {
  $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi'=>1]);
  $sekolah = $setting['sekolah'] ?? 'SEKOLAH';
  $alamat  = $setting['alamat'] ?? '';
  $kota    = $setting['kota'] ?? '';
  $kepsek  = $setting['kepsek'] ?? '';
  $nip     = $setting['nip'] ?? '';
  $telp    = $setting['telp'] ?? ($setting['telepon'] ?? '');
  $tgl_cetak = function_exists('tgl_indo') ? tgl_indo(date('Y-m-d')) : date('j F Y');

  $walas_nama = '';
  if ($kelas !== '') {
    $esc = mysqli_real_escape_string($koneksi, $kelas);
    if ($qw = mysqli_query($koneksi, "SELECT nama FROM users WHERE walas='$esc' LIMIT 1")) {
      $rw = mysqli_fetch_assoc($qw); $walas_nama = trim($rw['nama'] ?? '');
    }
  }

  if (!function_exists('__pts_roman_to_int')) {
    function __pts_roman_to_int(string $roman): ?int {
      $map = ['I'=>1,'II'=>2,'III'=>3,'IV'=>4,'V'=>5,'VI'=>6,'VII'=>7,'VIII'=>8,'IX'=>9,'X'=>10];
      $roman = strtoupper(trim($roman));
      return $map[$roman] ?? null;
    }
  }
  if (!function_exists('__pts_int_to_roman')) {
    function __pts_int_to_roman(int $num): ?string {
      $map = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X'];
      return $map[$num] ?? null;
    }
  }
  if (!function_exists('__pts_next_class')) {
    function __pts_next_class(string $kelas): string {
      $kelas = trim($kelas);
      if ($kelas === '') return '';
      $grade = strtoupper(strtok($kelas, ' -'));
      $current = __pts_roman_to_int($grade);
      if ($current === null) return '';
      if ($current >= 9) return 'Lulus';
      $next = __pts_int_to_roman($current + 1);
      return $next ?: '';
    }
  }

  $semester_label = ($semester === '1') ? 'I (Gasal)' : (($semester === '2') ? 'II (Genap)' : $semester);
  $tanggal_ttd = $setting['tanggal_rapor'] ?? $tgl_cetak;

  // Siapkan logo untuk kop surat (opsional)
  $logo_html = '';
  if (!empty($setting['logo'])) {
    $logo_path = __DIR__ . '/../../images/' . $setting['logo'];
    if (@is_file($logo_path) && is_readable($logo_path)) {
      $mime = 'image/png';
      if (function_exists('mime_content_type')) {
        $detected = @mime_content_type($logo_path);
        if ($detected) { $mime = $detected; }
      }
      $data = @file_get_contents($logo_path);
      if ($data !== false) {
        $logo_html = '<img src="data:' . $mime . ';base64,' . base64_encode($data) . '" style="width:70px;height:auto;" alt="Logo" />';
      }
    }
  }

  $address_line = trim($alamat . ($kota ? ', '.$kota : ''));
  $label_kelompok = [
    'A' => 'Kelompok A (Umum)',
    'B' => 'Kelompok B (Umum)',
    'C' => 'Kelompok C (Peminatan)',
    'D' => 'Kelompok D'
  ];
  $semester_teks = ($semester === '1') ? 'Ganjil' : (($semester === '2') ? 'Genap' : trim($semester));
  $judul_laporan = 'Laporan Hasil Penilaian Sumatif Tengah Semester'.($semester_teks !== '' ? ' '.ucfirst($semester_teks) : '');
  $laporan_belajar_title = 'Laporan Hasil Belajar Sumatif Tengah Semester'.($semester_teks !== '' ? ' ('.ucfirst($semester_teks).')' : '');

  if (!function_exists('__sts_render_identity_block')) {
    function __sts_render_identity_block(array $ctx): string {
      ob_start();
      ?>
      <table class="identity-table">
        <tr>
          <td class="identity-col">
            <table class="identity-inner">
              <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td class="value"><strong><?= htmlspecialchars($ctx['nama'] ?? '-') ?></strong></td>
              </tr>
              <tr>
                <td class="label">NIS/NISN</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['nis'] ?? '-') ?> / <?= htmlspecialchars($ctx['nisn'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="label">Nama Sekolah</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['sekolah'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['alamat'] ?? '-') ?></td>
              </tr>
            </table>
          </td>
          <td class="identity-col">
            <table class="identity-inner">
              <tr>
                <td class="label">Kelas</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['kelas'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="label">Fase</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['fase'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="label">Semester</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['semester'] ?? '-') ?></td>
              </tr>
              <tr>
                <td class="label">Tahun Pelajaran</td>
                <td class="separator">:</td>
                <td class="value"><?= htmlspecialchars($ctx['tp'] ?? '-') ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <?php
      return ob_get_clean();
    }
  }

  ob_start();
  ?>
  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="utf-8" />
    <title>Raport PTS - Kelas <?= htmlspecialchars($kelas) ?> (S<?= htmlspecialchars($semester) ?>, <?= htmlspecialchars($tp) ?>)</title>
    <style>
      @page { margin: 22mm 20mm 20mm 20mm; }
      body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color:#111; }
      .page-wrap { position:relative; min-height:257mm; page-break-inside:avoid; }
      .page-content { padding-bottom:32mm; }
      .identity-table { width:100%; border-collapse:collapse; margin-bottom:8px; }
      .identity-col { width:50%; vertical-align:top; }
      .identity-inner { width:100%; border-collapse:collapse; }
      .identity-inner td { padding:2px 0; font-size:12px; vertical-align:top; }
      .identity-inner td.label { width:38%; font-weight:600; }
      .identity-inner td.separator { width:6%; }
      .identity-inner td.value { font-weight:400; }
      .divider { border-top:1px solid #000; margin:6px 0 12px; }
      .report-title { text-align:center; font-weight:700; font-size:14px; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:10px; }
      .mapel-table { width:100%; border-collapse:collapse; font-size:12px; table-layout:fixed; }
      .mapel-table th { border:1px solid #000; padding:6px 8px; background:#f2f2f2; text-transform:uppercase; font-size:11px; }
      .mapel-table td { border:1px solid #000; padding:6px 8px; vertical-align:top; }
      .mapel-table td.no { width:7%; text-align:center; }
      .mapel-table td.mapel { width:30%; }
      .mapel-table td.nilai { width:10%; text-align:center; }
      .mapel-table tr.kelompok-row td { font-weight:600; background:#f8f8f8; text-transform:uppercase; }
      .section-heading { font-weight:600; text-transform:uppercase; margin:20px 0 8px; font-size:12px; }
      .eks-table { width:100%; border-collapse:collapse; font-size:12px; }
      .eks-table th { border:1px solid #000; background:#f2f2f2; padding:6px; text-transform:uppercase; font-size:11px; }
      .eks-table td { border:1px solid #000; padding:6px; vertical-align:top; }
      .eks-table td.no { width:7%; text-align:center; }
      .eks-table td.predikat { width:14%; text-align:center; }
      .att-table { width:65%; border-collapse:collapse; font-size:12px; margin-top:6px; }
      .att-table td { border:1px solid #000; padding:6px 10px; }
      .note-box { border:1px solid #000; min-height:60px; padding:10px; font-size:12px; }
      .promotion-box { border:1px solid #000; padding:8px 12px; font-weight:600; text-align:center; margin-top:10px; }
      .signature-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:20px; }
      .signature-table td { padding:0 12px; vertical-align:top; }
      .signature-table td.left { width:35%; }
      .signature-table td.center { width:30%; text-align:center; }
      .signature-table td.right { width:35%; text-align:right; }
      .signature-gap { height:68px; }
      .signature-placeholder { display:inline-block; width:170px; border-bottom:1px dotted #000; height:0; margin-top:44px; }
      .signature-name { font-weight:600; text-decoration:underline; }
      .signature-nip { font-size:11px; margin-top:4px; }
      .page-footer { position:absolute; left:0; right:0; bottom:0; font-size:10px; color:#000; }
      .page-footer table { width:100%; border-collapse:collapse; border-top:1px solid #000; }
      .page-footer td { padding-top:4px; border:none; font-size:10px; }
      .page-break { page-break-before: always; }
    </style>
  </head>
  <body>
  <?php
    $total_students = count($students);
    $student_counter = 0;
    foreach ($students as $s):
      $student_counter++;
      $sid = (int)$s['id_siswa'];
      $fase = trim((string)($s['fase'] ?? ''));
      $fase = $fase !== '' ? $fase : '-';
      $tingkat = (int)($s['level'] ?? 0);
      if ($tingkat === 0 && !empty($s['kelas'])) {
        $kelas_lookup = mysqli_real_escape_string($koneksi, $s['kelas']);
        if ($qk = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kelas='$kelas_lookup' LIMIT 1")) {
          $rk = mysqli_fetch_assoc($qk);
          if ($rk && isset($rk['level'])) { $tingkat = (int)$rk['level']; }
        }
      }
      $pk = trim($s['jurusan'] ?? 'semua');
      $pk_esc = mysqli_real_escape_string($koneksi, $pk);
      $mapel_rows = [];
      $sql_mapel = "SELECT mr.mapel, mr.kelompok, mr.urut, mr.kkm, mp.nama_mapel FROM mapel_rapor mr JOIN mata_pelajaran mp ON mp.id=mr.mapel WHERE mr.tingkat='".$tingkat."' AND mr.pk='".$pk_esc."' ORDER BY mr.urut ASC";
      if ($qm = mysqli_query($koneksi, $sql_mapel)) {
        while ($rm = mysqli_fetch_assoc($qm)) { $mapel_rows[] = $rm; }
      }
      if (empty($mapel_rows)) {
        foreach ($mapel as $m_all) {
          $mapel_rows[] = ['mapel'=>$m_all['id'], 'nama_mapel'=>$m_all['nama_mapel'], 'kelompok'=>'', 'kkm'=>0];
        }
      }

      $nis_esc = mysqli_real_escape_string($koneksi, $s['nis']);
      $kelas_induk = mysqli_real_escape_string($koneksi, $s['kelas']);
      $deskripsi_map = [];
      if ($qd = mysqli_query($koneksi, "SELECT mapel, tinggi, rendah FROM nilai_formatif WHERE nis='$nis_esc' AND kelas='$kelas_induk'")) {
        while ($rd = mysqli_fetch_assoc($qd)) { $deskripsi_map[(int)$rd['mapel']] = $rd; }
      }

      $eskul_rows = [];
      if ($sem_esc && $tp_esc) {
        if ($qe = mysqli_query($koneksi, "SELECT eskul, nilai, ket FROM peskul WHERE nis='$nis_esc' AND semester='$sem_esc' AND tp='$tp_esc'")) {
          while ($re = mysqli_fetch_assoc($qe)) { $eskul_rows[] = $re; }
        }
      }

      $catatan_wali = '';
      if (isset($s['catatan_wali']) && $s['catatan_wali'] !== '') { $catatan_wali = $s['catatan_wali']; }
      $promotion_text = '&nbsp;';
      if ((string)$semester === '2') {
        $next = __pts_next_class($s['kelas']);
        if ($next === 'Lulus') { $promotion_text = 'Lulus'; }
        elseif ($next !== '') { $promotion_text = 'Naik ke kelas '.$next; }
      }

      $identity_context = [
        'nama' => $s['nama'] ?? '',
        'nis' => $s['nis'] ?? '',
        'nisn' => ($s['nisn'] ?? '') !== '' ? $s['nisn'] : '-',
        'sekolah' => $sekolah,
        'alamat' => $address_line,
        'kelas' => $s['kelas'] ?? '',
        'fase' => $fase,
        'semester' => $semester,
        'tp' => $tp
      ];

      $total_pages = 3;
      $page_no = 1;

  ?>
    <div class="page-wrap">
      <div class="page-content">
        <?= __sts_render_identity_block($identity_context); ?>
        <div class="divider"></div>
        <div class="report-title">LAPORAN HASIL BELAJAR</div>
        <table class="mapel-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Mata Pelajaran</th>
              <th>Nilai Akhir</th>
              <th>Capaian Kompetensi</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $last_kelompok = null;
            $mapel_number = 1;
            foreach ($mapel_rows as $mr) {
              $mid = (int)$mr['mapel'];
              $nilai_pts = $nilai_map[$sid][$mid] ?? 0;
              $kel = $mr['kelompok'] ?? '';
              if ($kel !== '' && $kel !== $last_kelompok) {
                $last_kelompok = $kel;
                $label = $label_kelompok[$kel] ?? ('Kelompok '.strtoupper($kel));
                ?>
                <tr class="kelompok-row"><td colspan="4"><?= htmlspecialchars($label) ?></td></tr>
                <?php
              }
              $desc = '&nbsp;';
              if (!empty($deskripsi_map[$mid])) {
                $tinggi = trim((string)$deskripsi_map[$mid]['tinggi']);
                $rendah = trim((string)$deskripsi_map[$mid]['rendah']);
                $parts = [];
                if ($tinggi !== '') {
                  $parts[] = 'Mencapai Kompetensi dengan sangat baik dalam hal '.htmlspecialchars(rtrim($tinggi, '.'));
                }
                if ($rendah !== '') {
                  $parts[] = 'Perlu peningkatan dalam hal '.htmlspecialchars(rtrim($rendah, '.'));
                }
                if (!empty($parts)) {
                  $desc = implode('. ', $parts);
                  if (substr($desc, -1) !== '.') { $desc .= '.'; }
                }
              }
              ?>
              <tr>
                <td class="no"><?= $mapel_number ?></td>
                <td class="mapel"><?= htmlspecialchars($mr['nama_mapel'] ?? '') ?></td>
                <td class="nilai"><?= is_numeric($nilai_pts) ? (int)$nilai_pts : '' ?></td>
                <td><?= $desc ?></td>
              </tr>
              <?php
              $mapel_number++;
            }
            $rendered_rows = $mapel_number - 1;
            $min_rows = 11;
            if ($rendered_rows < $min_rows) {
              for ($i = $rendered_rows; $i < $min_rows; $i++) {
                ?>
                <tr>
                  <td class="no">&nbsp;</td>
                  <td class="mapel">&nbsp;</td>
                  <td class="nilai">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <?php
              }
            }
          ?>
          </tbody>
        </table>
      </div>
      <div class="page-footer">
        <table>
          <tr>
            <td><?= htmlspecialchars($s['kelas']) ?> | <?= htmlspecialchars($s['nama']) ?> | -</td>
            <td style="text-align:right;">Halaman : <?= $page_no ?></td>
          </tr>
        </table>
      </div>
    </div>
  <?php
        if ($page_no < $total_pages) { echo '<div class="page-break"></div>'; }
        $page_no++;
  ?>
    <div class="page-wrap">
      <div class="page-content">
        <?= __sts_render_identity_block($identity_context); ?>
        <div class="divider"></div>
        <div class="section-heading">Kegiatan Ekstrakurikuler</div>
        <table class="eks-table">
          <thead>
            <tr>
              <th>No</th>
              <th>Kegiatan Ekstrakurikuler</th>
              <th>Predikat</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $row_index = 0;
              foreach ($eskul_rows as $er) {
                if ($row_index >= 3) { break; }
                $row_index++;
                ?>
                <tr>
                  <td class="no"><?= $row_index ?></td>
                  <td><?= htmlspecialchars($er['eskul'] ?? '') ?></td>
                  <td class="predikat"><?= htmlspecialchars($er['nilai'] ?? '') ?></td>
                  <td><?= htmlspecialchars($er['ket'] ?? '') ?></td>
                </tr>
                <?php
              }
              for ($i = $row_index + 1; $i <= 3; $i++) {
                ?>
                <tr>
                  <td class="no"><?= $i ?></td>
                  <td>&nbsp;</td>
                  <td class="predikat">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <?php
              }
            ?>
          </tbody>
        </table>

        <div class="section-heading">Ketidakhadiran</div>
        <table class="att-table">
          <tr><td>Sakit</td><td>: <?= (int)($s['sakit'] ?? 0) ?> hari</td></tr>
          <tr><td>Izin</td><td>: <?= (int)($s['izin'] ?? 0) ?> hari</td></tr>
          <tr><td>Tanpa Keterangan</td><td>: <?= (int)($s['alpha'] ?? 0) ?> hari</td></tr>
        </table>

        <div class="section-heading">Catatan Wali Kelas</div>
        <div class="note-box"><?= $catatan_wali !== '' ? htmlspecialchars($catatan_wali) : '&nbsp;' ?></div>

        <div class="section-heading">Keterangan Kenaikan Kelas</div>
        <div class="promotion-box"><?= $promotion_text === '&nbsp;' ? '&nbsp;' : htmlspecialchars($promotion_text) ?></div>
      </div>
      <div class="page-footer">
        <table>
          <tr>
            <td><?= htmlspecialchars($s['kelas']) ?> | <?= htmlspecialchars($s['nama']) ?> | -</td>
            <td style="text-align:right;">Halaman : <?= $page_no ?></td>
          </tr>
        </table>
      </div>
    </div>
  <?php
        if ($page_no < $total_pages) { echo '<div class="page-break"></div>'; }
        $page_no++;
  ?>
    <div class="page-wrap">
      <div class="page-content">
        <?= __sts_render_identity_block($identity_context); ?>
        <div class="divider"></div>
        <table class="signature-table">
          <tr>
            <td class="left">
              Mengetahui<br>Orang Tua/Wali,
              <div class="signature-gap"></div>
              <span class="signature-placeholder"></span>
            </td>
            <td class="center">
              Mengetahui
              <div class="signature-gap"></div>
              <span class="signature-name"><?= $kepsek !== '' ? htmlspecialchars($kepsek) : '.............................' ?></span>
              <?php if ($nip !== ''): ?>
                <div class="signature-nip">NIP. <?= htmlspecialchars($nip) ?></div>
              <?php endif; ?>
            </td>
            <td class="right">
              <?= htmlspecialchars($kota ?: '................') ?>, <?= htmlspecialchars($tanggal_ttd) ?><br>Wali Kelas,
              <div class="signature-gap"></div>
              <span class="signature-name"><?= $walas_nama !== '' ? htmlspecialchars($walas_nama) : '.............................' ?></span>
            </td>
          </tr>
        </table>
      </div>
      <div class="page-footer">
        <table>
          <tr>
            <td><?= htmlspecialchars($s['kelas']) ?> | <?= htmlspecialchars($s['nama']) ?> | -</td>
            <td style="text-align:right;">Halaman : <?= $page_no ?></td>
          </tr>
        </table>
      </div>
    </div>
  <?php
      if ($student_counter < $total_students) { echo '<div class="page-break"></div>'; }
    endforeach;
  ?>
  </body>
  </html>
  <?php
  $html = ob_get_clean();
  $rendered = false;
  if (@file_exists(__DIR__ . '/../../vendor/vendors/autoload.php')) {
    require_once __DIR__ . '/../../vendor/vendors/autoload.php';
    if (class_exists('\Dompdf\Dompdf')) {
      try {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fname = 'Raport_PTS_'.preg_replace('/[^A-Za-z0-9_-]+/','_',$kelas).'_S'.$semester.'_'.$tp.'.pdf';
        $dompdf->stream($fname, ["Attachment" => false]);
        $rendered = true;
      } catch (\Throwable $e) {
        $rendered = false;
      }
    }
  }
  if (!$rendered) {
    header('Content-Type: text/html; charset=utf-8');
    echo $html;
  }
  exit;
}

// Default: tampilan cetak (PDF via print-to-PDF)
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Cetak Nilai STS - <?= htmlspecialchars($kelas) ?> (S<?= htmlspecialchars($semester) ?>, <?= htmlspecialchars($tp) ?>)</title>
  <style>
    body{font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#000}
    h3{margin:0 0 6px 0}
    .meta{margin:0 0 10px 0;color:#444}
    table{width:100%;border-collapse:collapse}
    th,td{border:1px solid #000;padding:6px;text-align:center}
    th{text-transform:uppercase}
    td.text-left{text-align:left}
    @media print{@page{size:landscape;margin:12mm}}
  </style>
</head>
<body onload="window.print()">
  <h3>Daftar Nilai STS - Kelas <?= htmlspecialchars($kelas) ?></h3>
  <div class="meta">Semester: <strong><?= htmlspecialchars($semester) ?></strong> | TP: <strong><?= htmlspecialchars($tp) ?></strong></div>
  <?= render_table($kelas, $semester, $tp, $students, $mapel, $nilai_map) ?>
</body>
</html>
