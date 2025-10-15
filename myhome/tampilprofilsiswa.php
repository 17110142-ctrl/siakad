<?php
// --- Data Fetching Logic ---
$level = $_SESSION['level'] ?? '';
$kelasWali = $user['walas'] ?? null;
// FIXED: Mengambil 'tugas' dari array $user, bukan dari $_SESSION
$tugas = $user['tugas'] ?? ''; 

// --- Penentuan Hak Akses ---
$canViewAll = false; // Default: tidak bisa melihat semua
$isLockedToWalasClass = false; // Default: tidak terkunci pada kelas walas

if ($level === 'admin' || $tugas === 'bendahara') {
    // Admin dan Bendahara bisa melihat semua data siswa dan menggunakan filter
    $canViewAll = true;
} elseif ($level === 'guru' && !empty($kelasWali)) {
    // Jika bukan admin/bendahara, tapi guru dan punya kelas walas, maka terkunci
    $isLockedToWalasClass = true;
} else {
    // Guru biasa (tanpa kelas walas) juga bisa memfilter semua kelas
    $canViewAll = true;
}

// --- Cek ketersediaan referensi Dapodik (tabel ref_dapodik) ---
$refDapoAvailable = false;
$__tblCheck2 = mysqli_query($koneksi, "SHOW TABLES LIKE 'ref_dapodik'");
if ($__tblCheck2 && mysqli_num_rows($__tblCheck2) > 0) {
    $refDapoAvailable = true;
}

// --- Pembangunan Query SQL ---
$filterKelas = isset($_GET['filterKelas']) ? mysqli_real_escape_string($koneksi, $_GET['filterKelas']) : '';

// Gunakan LEFT JOIN ke ref_dapodik sebagai referensi utama padanan
if ($refDapoAvailable) {
    $selectBase = "SELECT s.*, d.nisn AS d_nisn, d.nis AS d_nis, d.nik AS d_nik, d.no_kk AS d_no_kk, d.nama AS d_nama, d.tempat_lahir AS d_tempat_lahir, d.tgl_lahir AS d_tgl_lahir, d.jk AS d_jk, d.agama AS d_agama, d.email AS d_email, d.rt AS d_rt, d.rw AS d_rw, d.kelurahan AS d_kelurahan, d.kecamatan AS d_kecamatan, d.provinsi AS d_provinsi, d.kode_pos AS d_kode_pos, d.alamat AS d_alamat FROM siswa s LEFT JOIN ref_dapodik d ON d.nisn = s.nisn";
    $orderBy = " ORDER BY s.kelas, s.nama";
    $whereTpl = " WHERE s.kelas = '%s'";
} else {
    $selectBase = "SELECT * FROM siswa";
    $orderBy = " ORDER BY kelas, nama";
    $whereTpl = " WHERE kelas = '%s'";
}

$sql = $selectBase . $orderBy; // Default query

if ($isLockedToWalasClass) {
    $kelasWali_safe = mysqli_real_escape_string($koneksi, $kelasWali);
    $sql = $selectBase . sprintf($whereTpl, $kelasWali_safe) . ($refDapoAvailable ? " ORDER BY s.nama" : " ORDER BY nama");
} elseif ($canViewAll && !empty($filterKelas)) {
    // Jika bisa melihat semua DAN ada filter yang diterapkan
    $sql = $selectBase . sprintf($whereTpl, $filterKelas) . ($refDapoAvailable ? " ORDER BY s.nama" : " ORDER BY nama");
}
// Jika $canViewAll true tapi tidak ada filter, query default akan digunakan.

$result = mysqli_query($koneksi, $sql);
if (!$result) {
    die("Query Gagal: " . mysqli_error($koneksi));
}

$kelasQuery = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM siswa WHERE kelas IS NOT NULL AND kelas != '' ORDER BY kelas");
if (!$kelasQuery) {
    die("Query Kelas Gagal: " . mysqli_error($koneksi));
}
$kelasList = mysqli_fetch_all($kelasQuery, MYSQLI_ASSOC);
// Samakan opsi dengan mydashboard/edit_profil.php
$agamaOptions = ['Islam','Kristen','Katholik','Hindu','Budha','Konghucu'];
$statusOrtuOptions = ['MASIH HIDUP','SUDAH MENINGGAL'];
$beasiswaOptions = ['TIDAK ADA','KIP','PKH'];
// Opsi orang tua mengikuti mydashboard/edit_profil.php
$parentEducationOptions = ['TIDAK SEKOLAH','SD','SMP','SMA/SMK','D1','D2','D3','S1','S2','S3'];
$parentOccupationOptions = ['TIDAK BEKERJA','PNS','TNI/POLRI','GURU','PETANI','NELAYAN','PEDAGANG','WIRASWASTA','BURUH','KARYAWAN SWASTA'];
$parentIncomeOptions = ['< RP 500.000','RP 500.000 - RP 999.999','RP 1.000.000 - RP 1.999.999','RP 2.000.000 - RP 4.999.999','>= RP 5.000.000'];

$guruWaliStudents = [];
if (($level === 'guru') && !empty($user['id_user'])) {
    $idGuru = (int)$user['id_user'];
    $sqlGuruWali = "SELECT s.id_siswa, s.nis, s.nisn, s.nama, s.kelas, s.nowa, s.alamat FROM guru_wali gw JOIN siswa s ON s.id_siswa = gw.id_siswa WHERE gw.id_guru = $idGuru ORDER BY s.kelas, s.nama";
    if ($gwResult = mysqli_query($koneksi, $sqlGuruWali)) {
        while ($row = mysqli_fetch_assoc($gwResult)) {
            $guruWaliStudents[] = $row;
        }
        mysqli_free_result($gwResult);
    }
}
?>

<?php
// Siapkan query export berdasarkan kondisi filter/walas
$exportFilter = $isLockedToWalasClass ? ($kelasWali ?? '') : ($filterKelas ?? '');
$exportQuery = !empty($exportFilter) ? ('?filterKelas=' . urlencode($exportFilter)) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        #tableSiswa th, #tableSiswa td { vertical-align: middle !important; white-space: nowrap; font-size: 14px; }
        #tableSiswa td:nth-child(14) { white-space: normal !important; }
        #tableSiswa td { max-height: 60px; overflow: hidden; text-overflow: ellipsis; }
        .dataTables_scrollHeadInner, #tableSiswa { width: 100% !important; }
        div.dataTables_wrapper { width: 100%; }
        #tableSiswa td:last-child .btn { margin-right: 4px; }
        #tableSiswa td:last-child { white-space: nowrap; }
        /* Hide default DataTables controls, we provide custom toolbar */
        .dataTables_filter, .dataTables_length { display: none !important; }
        /* Sticky Nama column */
        .table-responsive { position: relative; }
        #tableSiswa th.sticky-col-header { position: sticky; left: 0; z-index: 3; background-color: #f8f9fa; box-shadow: 2px 0 0 rgba(0,0,0,0.05); }
        #tableSiswa td.sticky-col { position: sticky; left: 0; z-index: 2; background-color: #ffffff; box-shadow: 2px 0 0 rgba(0,0,0,0.05); }
        .kk-thumb-wrapper { display: flex; align-items: center; gap: 8px; }
        .kk-thumb { max-height: 60px; border-radius: 4px; border: 1px solid #dee2e6; }
        .kk-file-wrapper { display: flex; flex-direction: column; gap: 4px; }
        /* Highlight for fields that have residu */
        .residu-highlight.is-invalid,
        .residu-highlight { border-color: #dc3545 !important; box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25); }
        /* Pastikan KK Viewer selalu paling depan */
        #kkViewerModal { z-index: 2000 !important; }
        .modal-backdrop.kk-viewer-backdrop { z-index: 1999 !important; }
    </style>
</head>
<body>
<div class="container mt-4">

    <?php if ($isLockedToWalasClass): ?>
    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-info mb-0">
                Anda adalah wali kelas <strong><?= htmlspecialchars($user['walas']) ?></strong>. Data di bawah ini telah disaring secara otomatis.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($guruWaliStudents)): ?>
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="bi bi-people-fill me-1"></i>Data Siswa Binaan Guru Wali</h5>
            <span class="badge bg-primary">Total: <?= count($guruWaliStudents) ?></span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">No</th>
                            <th>Nama Siswa</th>
                            <th>NIS / NISN</th>
                            <th>Kelas</th>
                            <th>No. WA</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $noBina=1; foreach ($guruWaliStudents as $gs): ?>
                        <tr>
                            <td><?= $noBina++; ?></td>
                            <td><?= htmlspecialchars($gs['nama']); ?></td>
                            <td><?= htmlspecialchars($gs['nis'] ?: '-'); ?> / <?= htmlspecialchars($gs['nisn'] ?: '-'); ?></td>
                            <td><?= htmlspecialchars($gs['kelas'] ?: '-'); ?></td>
                            <td><?= htmlspecialchars($gs['nowa'] ?: '-'); ?></td>
                            <td><?= htmlspecialchars($gs['alamat'] ?: '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                DAFTAR BIODATA SISWA
                <?php if ($isLockedToWalasClass): ?>
                    KELAS <?= htmlspecialchars($user['walas']) ?>
                <?php elseif (!empty($filterKelas)): ?>
                    KELAS <?= htmlspecialchars($filterKelas) ?>
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!$refDapoAvailable): ?>
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Residu & validasi padanan belum aktif karena referensi Dapodik belum diimpor.
                    </div>
                    <?php if ($level === 'admin' || $tugas === 'bendahara'): ?>
                        <a class="btn btn-sm btn-outline-primary" href="import_ref_dapodik.php">
                            <i class="bi bi-upload me-1"></i> Import Referensi Dapodik
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <!-- Toolbar: Search, Page Length, Kelas Filter -->
            <div class="row g-2 align-items-center mb-3">
                <div class="col-12 col-md">
                    <div class="input-group">
                        <input id="toolbar-search" type="text" class="form-control" placeholder="Cari Nama/NIS/NISN...">
                        <button id="toolbar-search-btn" class="btn btn-primary" type="button" title="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center">
                    <label for="toolbar-length" class="me-2">Data:</label>
                    <select id="toolbar-length" class="form-select">
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <?php if (!$isLockedToWalasClass): ?>
                <div class="col-auto d-flex align-items-center">
                    <label for="toolbar-kelas" class="me-2">Kelas:</label>
                    <select id="toolbar-kelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php foreach($kelasList as $k): ?>
                            <option value="<?= htmlspecialchars($k['kelas']) ?>" <?= (!empty($filterKelas) && $filterKelas === $k['kelas']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($k['kelas']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="col-auto">
                    <span class="badge bg-info text-dark">Kelas <?= htmlspecialchars($user['walas']) ?></span>
                </div>
                <?php endif; ?>
                <div class="col-12 col-md-auto d-flex gap-2">
                    <a href="export_status.php<?= $exportQuery ?>" id="exportPdfBtn" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF Status
                    </a>
                    <a href="export_excel.php<?= $exportQuery ?>" id="exportExcelBtn" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel Data Siswa
                    </a>
                    <?php if ($level === 'admin' || $tugas === 'bendahara'): ?>
                    <a href="import_ref_dapodik.php" class="btn btn-outline-secondary">
                        <i class="bi bi-upload"></i> Import Referensi Dapodik
                    </a>
                    <?php endif; ?>
                    <?php if ($refDapoAvailable): ?>
                    <?php
                        // Placeholder badge, akan diisi via PHP di bawah saat loop
                    ?>
                    <button type="button" id="btn-residu" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#residuModal">
                        <i class="bi bi-bug"></i> Residu Data <span class="badge bg-warning text-dark" id="residu-count">0</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tableSiswa" class="table table-striped table-bordered table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kelas</th>
                            <th class="sticky-col-header">Nama Siswa</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>No. WA</th>
                            <th>TTL</th>
                            <th>JK</th>
                            <th>NIK</th>
                            <th>No. KK</th>
                            <th>Agama</th>
                            <th>Email</th>
                            <th>Anak Ke / Jml Saudara</th>
                            <th>Tinggi Badan</th>
                            <th>Berat Badan</th>
                            <th>Lingkar Kepala</th>
                            <th>RT/RW</th>
                            <th>Kel/Kec</th>
                            <th>Provinsi</th>
                            <th>Kode Pos</th>
                            <th>Hobi</th>
                            <th>Cita-cita</th>
                            <th>Sekolah Asal / Thn Lulus</th>
                            <th>Beasiswa</th>
                            <th>No KIP</th>
                            <th>No KKS</th>
                            <th>Nama Ayah</th>
                            <th>Status Ayah</th>
                            <th>TTL Ayah</th>
                            <th>No. HP Ayah</th>
                            <th>Pendidikan Ayah</th>
                            <th>Penghasilan Ayah</th>
                            <th>Pekerjaan Ayah</th>
                            <th>Nama Ibu</th>
                            <th>Status Ibu</th>
                            <th>TTL Ibu</th>
                            <th>No. HP Ibu</th>
                            <th>Pendidikan Ibu</th>
                            <th>Penghasilan Ibu</th>
                            <th>Pekerjaan Ibu</th>
                            <th>File KK</th>
                            <th>Status Kelengkapan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $no = 1; 
                    $residuEntries = [];
                    $residuTotal = 0;
                    $studentDataForJs = [];

                    $normalizeBase = function($value) {
                        $value = (string)($value ?? '');
                        if ($value === '') return '';
                        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
                        if ($converted !== false) {
                            $value = $converted;
                        }
                        $value = trim($value);
                        if ($value === '') return '';
                        return preg_replace('/\s+/u', ' ', $value);
                    };
                    $normalizeName = function($value) use ($normalizeBase) {
                        $value = strtoupper($normalizeBase($value));
                        if ($value === '') return '';
                        $value = preg_replace('/[^A-Z0-9]/', '', $value);
                        return $value;
                    };
                    $normalizeText = function($value) use ($normalizeBase) {
                        $value = strtoupper($normalizeBase($value));
                        if ($value === '') return '';
                        $value = preg_replace('/[^A-Z0-9\s]/', ' ', $value);
                        $value = preg_replace('/\s+/', ' ', $value);
                        return trim($value);
                    };
                    $normalizeDigits = function($value, $preserveLeadingZeros = true) {
                        $digits = preg_replace('/\D+/', '', (string)($value ?? ''));
                        if ($digits === '') return '';
                        if ($preserveLeadingZeros) {
                            return $digits;
                        }
                        $trimmed = ltrim($digits, '0');
                        return $trimmed === '' ? '0' : $trimmed;
                    };
                    $normalizeEmail = function($value) {
                        return strtolower(trim((string)($value ?? '')));
                    };
                    $normalizeDate = function($value) {
                        if (!$value) return '';
                        $ts = @strtotime((string)$value);
                        return $ts ? date('Y-m-d', $ts) : '';
                    };
                    $normalizeJK = function($value) {
                        $v = strtoupper(trim((string)($value ?? '')));
                        if ($v === '') return '';
                        $char = substr($v, 0, 1);
                        return ($char === 'L' || $char === 'P') ? $char : '';
                    };
                    $normalizeLocation = function($value) use ($normalizeText) {
                        $v = $normalizeText($value);
                        if ($v === '') return '';
                        $v = preg_replace('/^(KABUPATEN|KAB|KOTA MADYA|KOTAMADYA|KOTA|KECAMATAN|KEC)\s+/u', '', $v);
                        return trim($v);
                    };
                    $formatResiduValue = function($value) {
                        $val = trim((string)($value ?? ''));
                        return $val === '' ? 'kosong' : $val;
                    };
                    $compareValue = function($type, $appValue, $refValue) use ($normalizeDigits, $normalizeName, $normalizeText, $normalizeDate, $normalizeJK, $normalizeEmail, $normalizeLocation) {
                        switch ($type) {
                            case 'nisn':
                                $app = $normalizeDigits($appValue, true);
                                $ref = $normalizeDigits($refValue, true);
                                if ($ref === '') return false;
                                // toleransi: jika salah 1 digit tapi nama & tanggal lahir cocok, terima
                                if ($app === $ref) return true;
                                if (strlen($app) === 10 && strlen($ref) === 10) {
                                    $diff = 0;
                                    for ($i = 0; $i < 10; $i++) {
                                        if ($app[$i] !== $ref[$i]) $diff++;
                                        if ($diff > 1) break;
                                    }
                                    if ($diff === 1) {
                                        return true;
                                    }
                                }
                                return false;
                            case 'digits-strict':
                                return $normalizeDigits($appValue, true) === $normalizeDigits($refValue, true);
                            case 'digits':
                                return $normalizeDigits($appValue, false) === $normalizeDigits($refValue, false);
                            case 'date':
                                return $normalizeDate($appValue) === $normalizeDate($refValue);
                            case 'jk':
                                return $normalizeJK($appValue) === $normalizeJK($refValue);
                            case 'email':
                                return $normalizeEmail($appValue) === $normalizeEmail($refValue);
                            case 'location':
                                return $normalizeLocation($appValue) === $normalizeLocation($refValue);
                            case 'name':
                                $na = $normalizeName($appValue);
                                $nb = $normalizeName($refValue);
                                if ($na === $nb) return true;
                                if ($na === '' || $nb === '') return $na === $nb;
                                $lev = levenshtein($na, $nb);
                                if ($lev <= 2) return true;
                                $len = max(strlen($na), strlen($nb));
                                if ($len > 0 && ($lev / $len) <= 0.05) return true;
                                $similar = 0.0;
                                similar_text($na, $nb, $similar);
                                if ($similar >= 95) return true;
                                return false;
                            default:
                                return $normalizeText($appValue) === $normalizeText($refValue);
                        }
                    };

                    while($row = mysqli_fetch_assoc($result)): ?>
                        <?php
                        // Daftar field yang wajib diisi
                        $required = [ 'kelas','nama','nis','nisn','t_lahir','tgl_lahir','jk','nik','nokk','agama','email','anakke','jumlah_saudara','t_badan','b_badan','l_kepala','rt','rw','kelurahan','kabupaten','kecamatan','provinsi','kode_pos','hobi','cita_cita','asal_sek','thn_lulus','beasiswa','nama_ayah','status_ayah','nama_ibu','status_ibu', 'kk_ibu' ];

                        // Logika untuk field kondisional
                        if ($row['beasiswa'] === 'KIP') $required[] = 'no_kip';
                        elseif ($row['beasiswa'] === 'PKH') $required[] = 'no_kks';

                        $fatherFields = ['tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','no_hp_ayah'];
                        $motherFields = ['tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','no_hp_ibu'];

                        if (strcasecmp($row['status_ayah'], 'Sudah Meninggal') !== 0) {
                            $required = array_merge($required, $fatherFields);
                        }
                        if (strcasecmp($row['status_ibu'], 'Sudah Meninggal') !== 0) {
                            $required = array_merge($required, $motherFields);
                        }

                        $total = count($required);
                        $filled = 0;
                        $missingFields = [];

                        foreach ($required as $f) {
                            $isFieldFilled = false;
                            if ($f === 'jumlah_saudara' && isset($row[$f]) && ($row[$f] === '0' || $row[$f] === '-' || $row[$f] !== '')) {
                                $isFieldFilled = true;
                            } elseif ($f === 'kk_ibu' && !empty($row['kk_ibu'])) {
                                $isFieldFilled = true;
                            } elseif (isset($row[$f]) && !empty($row[$f])) {
                                $isFieldFilled = true;
                            }
                            
                            if($isFieldFilled) {
                                $filled++;
                            } else {
                                $missingFields[] = ucwords(str_replace('_', ' ', $f));
                            }
                        }

                        $percent = $total > 0 ? round($filled / $total * 100) : 0;
                        $isComplete = ($filled === $total);

                        // --- HITUNG RESIDU berdasar referensi Dapodik (jika tersedia & ada pasangan)
                        $residu = [];
                        $rowResidu = [];
                        if ($refDapoAvailable && !empty($row['d_nisn'])) {
                            $checks = [
                                ['label'=>'NISN','app'=>$row['nisn']??'','ref'=>$row['d_nisn']??'','type'=>'nisn'],
                                ['label'=>'Nama','app'=>$row['nama']??'','ref'=>$row['d_nama']??'','type'=>'name'],
                                ['label'=>'Tempat Lahir','app'=>$row['t_lahir']??'','ref'=>$row['d_tempat_lahir']??'','type'=>'location'],
                                ['label'=>'Tanggal Lahir','app'=>$row['tgl_lahir']??'','ref'=>$row['d_tgl_lahir']??'','type'=>'date'],
                                ['label'=>'JK','app'=>$row['jk']??'','ref'=>$row['d_jk']??'','type'=>'jk'],
                                ['label'=>'NIK','app'=>$row['nik']??'','ref'=>$row['d_nik']??'','type'=>'digits-strict'],
                                ['label'=>'No KK','app'=>$row['nokk']??'','ref'=>$row['d_no_kk']??'','type'=>'digits-strict'],
                                ['label'=>'Agama','app'=>$row['agama']??'','ref'=>$row['d_agama']??'','type'=>'text'],
                                ['label'=>'Email','app'=>$row['email']??'','ref'=>$row['d_email']??'','type'=>'email'],
                                ['label'=>'RT','app'=>$row['rt']??'','ref'=>$row['d_rt']??'','type'=>'digits'],
                                ['label'=>'RW','app'=>$row['rw']??'','ref'=>$row['d_rw']??'','type'=>'digits'],
                                ['label'=>'Kelurahan','app'=>$row['kelurahan']??'','ref'=>$row['d_kelurahan']??'','type'=>'location'],
                                ['label'=>'Kecamatan','app'=>$row['kecamatan']??'','ref'=>$row['d_kecamatan']??'','type'=>'location'],
                                ['label'=>'Provinsi','app'=>$row['provinsi']??'','ref'=>$row['d_provinsi']??'','type'=>'location'],
                                ['label'=>'Kode Pos','app'=>$row['kode_pos']??'','ref'=>$row['d_kode_pos']??'','type'=>'digits-strict'],
                            ];
                            foreach ($checks as $c) {
                                $a = $c['app'];
                                $b = $c['ref'];
                                if ($b === null || $b === '') continue; // referensi kosong: abaikan
                                if (!$compareValue($c['type'], $a, $b)) {
                                    $residu[] = [
                                        'label' => $c['label'],
                                        'app' => $formatResiduValue($a),
                                        'dapodik' => $formatResiduValue($b)
                                    ];
                                }
                            }
                            if (!empty($residu)) {
                                $residuEntries[] = [
                                    'id' => $row['id_siswa'] ?? '',
                                    'nama' => $row['nama'] ?? '',
                                    'kelas' => $row['kelas'] ?? '',
                                    'residu' => $residu
                                ];
                                $residuTotal += 1;
                            }
                            $rowResidu = $residu;
                        } elseif ($refDapoAvailable) {
                            $residuEntries[] = [
                                'id' => $row['id_siswa'] ?? '',
                                'nama' => $row['nama'] ?? '',
                                'kelas' => $row['kelas'] ?? '',
                                'residu' => [
                                    [
                                        'label' => 'NISN',
                                        'app' => $formatResiduValue($row['nisn'] ?? ''),
                                        'dapodik' => 'Tidak ditemukan di referensi'
                                    ]
                                ]
                            ];
                            $residuTotal += 1;
                            $rowResidu = [
                                [
                                    'label' => 'NISN',
                                    'app' => $formatResiduValue($row['nisn'] ?? ''),
                                    'dapodik' => 'Tidak ditemukan di referensi'
                                ]
                            ];
                        }
                        
                        // --- PERSIAPAN PESAN WHATSAPP ---
                        $namaSiswa = htmlspecialchars($row['nama']);
                        $kelasSiswa = htmlspecialchars($row['kelas']);
                        $pesanWA = "";
                        if ($isComplete) {
                            $pesanWA = "Yth. Bapak/Ibu Wali Murid dari ananda $namaSiswa (Kelas $kelasSiswa),\n\nKami informasikan bahwa data biodata ananda di sistem sekolah sudah LENGKAP (100%).\n\nTerima kasih atas kerja sama Anda.\n\nSalam,\nWali Kelas";
                        } else {
                            $pesanWA = "Yth. Bapak/Ibu Wali Murid dari ananda $namaSiswa (Kelas $kelasSiswa),\n\nDengan hormat,\nKami informasikan bahwa kelengkapan data biodata ananda baru mencapai $percent%. Masih ada beberapa data yang perlu dilengkapi, yaitu:\n\n";
                            foreach($missingFields as $field) {
                                $pesanWA .= "- $field\n";
                            }
                            $pesanWA .= "\nMohon kesediaannya untuk segera melengkapi data tersebut melalui portal siswa atau menghubungi pihak sekolah.\n\nTerima kasih atas perhatian dan kerja sama Anda.\n\nSalam,\nWali Kelas";
                        }
                        
                        // --- FORMAT NOMOR TELEPON ---
                        $nomorTarget = !empty($row['nowa']) ? $row['nowa'] : ($row['no_hp_ibu'] ?? ($row['no_hp_ayah'] ?? ''));
                        $nomorWA = '';
                        if (!empty($nomorTarget)) {
                            $nomorWA = preg_replace('/[^0-9]/', '', $nomorTarget);
                            if (substr($nomorWA, 0, 1) === '0') {
                                $nomorWA = '62' . substr($nomorWA, 1);
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                            <td class="sticky-col">
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <span><?= htmlspecialchars($row['nama']) ?></span>
                                    <button type="button"
                                            class="btn btn-link btn-sm text-primary btn-edit-siswa"
                                            data-id="<?= htmlspecialchars($row['id_siswa']) ?>"
                                            title="Edit biodata">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['nis']) ?></td>
                            <td><?= htmlspecialchars($row['nisn']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['password']) ?></td>
                            <td>
                                <?php if (!empty($row['nowa'])): ?>
                                    <?= htmlspecialchars($row['nowa']) ?>
                                <?php else: ?>
                                    <span class="text-muted">kosong</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['t_lahir']) ?>, <?= htmlspecialchars($row['tgl_lahir']) ?></td>
                            <td><?= $row['jk'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            <td><?= htmlspecialchars($row['nik']) ?></td>
                            <td><?= htmlspecialchars($row['nokk']) ?></td>
                            <td><?= htmlspecialchars($row['agama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['anakke']) ?> / <?= htmlspecialchars($row['jumlah_saudara']) ?></td>
                            <td><?= htmlspecialchars($row['t_badan']) ?></td>
                            <td><?= htmlspecialchars($row['b_badan']) ?></td>
                            <td><?= htmlspecialchars($row['l_kepala']) ?></td>
                            <td><?= htmlspecialchars($row['rt']) ?> / <?= htmlspecialchars($row['rw']) ?></td>
                            <td><?= htmlspecialchars($row['kelurahan']) ?>/<?= htmlspecialchars($row['kecamatan']) ?></td>
                            <td><?= htmlspecialchars($row['provinsi']) ?></td>
                            <td><?= htmlspecialchars($row['kode_pos']) ?></td>
                            <td><?= htmlspecialchars($row['hobi']) ?></td>
                            <td><?= htmlspecialchars($row['cita_cita']) ?></td>
                            <td><?= htmlspecialchars($row['asal_sek']) ?> / <?= htmlspecialchars($row['thn_lulus']) ?></td>
                            <td><?= htmlspecialchars($row['beasiswa']) ?></td>
                            <td><?= htmlspecialchars($row['no_kip']) ?></td>
                            <td><?= htmlspecialchars($row['no_kks']) ?></td>
                            <td><?= htmlspecialchars($row['nama_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['status_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['tempat_lahir_ayah']) ?> / <?= htmlspecialchars($row['tgl_lahir_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['no_hp_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['pendidikan_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['penghasilan_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['pekerjaan_ayah']) ?></td>
                            <td><?= htmlspecialchars($row['nama_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['status_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['tempat_lahir_ibu']) ?> / <?= htmlspecialchars($row['tgl_lahir_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['no_hp_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['pendidikan_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['penghasilan_ibu']) ?></td>
                            <td><?= htmlspecialchars($row['pekerjaan_ibu']) ?></td>
                            <td>
                                <?php if (!empty($row['kk_ibu'])): ?>
                                    <a href="../uploads/kk/<?= htmlspecialchars($row['kk_ibu']) ?>" target="_blank" class="btn btn-sm btn-secondary">Lihat KK</a>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($isComplete): ?>
                                    <span class="badge bg-success">Lengkap (100%)</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Lengkap (<?= $percent ?>%)</span>
                                    <button class="btn btn-link btn-sm p-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $row['id_siswa'] ?>">
                                        Lihat Detail
                                    </button>
                                    <div class="collapse" id="collapse-<?= $row['id_siswa'] ?>">
                                        <ul class="mb-0 ps-3 small">
                                            <?php foreach ($missingFields as $field): ?>
                                                <li><?= htmlspecialchars($field) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $missingJson = htmlspecialchars(json_encode($missingFields), ENT_QUOTES, 'UTF-8');
                                $isCompleteFlag = $isComplete ? '1' : '0';
                                ?>
                                <div class="d-grid gap-1">
                                    <?php 
                                    $vstatus = strtolower(trim($row['validation_status'] ?? ''));
                                    $badgeHtml = '';
                                    if ($refDapoAvailable && !empty($row['d_nisn'])) {
                                        if (empty($rowResidu)) {
                                            $badgeHtml = '<span class="badge bg-success align-self-start"><i class="bi bi-check-circle me-1"></i>Padan</span>';
                                        } else {
                                            $labels = array_map(function($item){ return $item['label']; }, $rowResidu);
                                            $badgeText = 'Tidak padan (' . implode(', ', $labels) . ')';
                                            $badgeHtml = '<span class="badge bg-danger align-self-start"><i class="bi bi-exclamation-triangle me-1"></i>' . htmlspecialchars($badgeText) . '</span>';
                                        }
                                    } elseif ($refDapoAvailable) {
                                        $badgeHtml = '<span class="badge bg-warning text-dark align-self-start"><i class="bi bi-question-circle me-1"></i>NISN tidak ditemukan</span>';
                                    }
                                    ?>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        <?php if (!empty($badgeHtml)) echo $badgeHtml; ?>
                                        <?php if ($vstatus === 'validated'): ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-success btn-unvalidate"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#unvalidateModal"
                                                    data-id="<?= htmlspecialchars($row['id_siswa']) ?>"
                                                    data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                                    data-nowa="<?= htmlspecialchars($row['nowa']) ?>">
                                                <i class="bi bi-check-circle"></i> Tervalidasi
                                            </button>
                                        <?php else: ?>
                                            <?php if ($isComplete): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-primary btn-open-validation"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#validationModal"
                                                        data-id="<?= htmlspecialchars($row['id_siswa']) ?>"
                                                        data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                                        data-kelas="<?= htmlspecialchars($row['kelas']) ?>"
                                                        data-percent="<?= $percent ?>"
                                                        data-complete="<?= $isCompleteFlag ?>"
                                                        data-missing='<?= $missingJson ?>'
                                                        data-nowa="<?= htmlspecialchars($row['nowa']) ?>">
                                                    <i class="bi bi-clipboard-check"></i> Validasi
                                                </button>
                                            <?php else: ?>
                                                <?php if (!empty($row['nowa'])): ?>
                                                    <button type="button"
                                                            class="btn btn-sm btn-success btn-send-wa"
                                                            data-id="<?= htmlspecialchars($row['id_siswa']) ?>"
                                                            data-nowa="<?= htmlspecialchars($row['nowa']) ?>">
                                                        <i class="bi bi-whatsapp"></i> WA Ortu
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        <i class="bi bi-whatsapp"></i> WA Ortu
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                            // Build data for JS map. Use kk_ibu from DB; previous code overwrote it with
                            // undefined $kkExists/$kkFile so it always became empty.
                            $rowForJs = $row;
                            $rowForJs['kk_ibu'] = $row['kk_ibu'] ?? '';
                            $studentDataForJs[$row['id_siswa']] = $rowForJs;
                            $studentDataForJs[$row['id_siswa']]['residu'] = $rowResidu;
                        ?>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
  </div>
  </div>
  </div>

<script id="students-json" type="application/json"><?php echo json_encode($studentDataForJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>

<!-- Modal Kelola KK -->
<div class="modal fade" id="kkModal" tabindex="-1" aria-labelledby="kkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="kkModalLabel">Kelola File KK</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="kk-alert" class="alert d-none" role="alert"></div>
        <div id="kk-preview-container" class="mb-3 text-center">
            <img id="kk-preview-img" src="" alt="Pratinjau KK" class="img-fluid rounded d-none" style="max-height:280px;">
            <div id="kk-preview-file" class="alert alert-info d-none"></div>
            <div id="kk-preview-empty" class="text-muted">Belum ada file KK yang diunggah.</div>
        </div>
        <form id="kk-form" class="row g-3" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="id_siswa" id="kk-id">
            <div class="col-12">
                <label class="form-label">Upload File KK</label>
                <input type="file" class="form-control" name="kk_file" id="kk-file" accept=".jpg,.jpeg,.png,.webp,.gif,.bmp,.pdf">
                <div class="form-text">Format yang didukung: JPG, PNG, PDF. Ukuran maksimal 5 MB.</div>
            </div>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-outline-danger d-none" id="kk-delete-btn">
            <i class="bi bi-trash"></i> Hapus File
        </button>
        <div class="ms-auto d-flex gap-2">
            <button type="button" class="btn btn-primary" id="kk-upload-btn">
                <span class="spinner-border spinner-border-sm me-2 d-none" id="kk-spinner" role="status" aria-hidden="true"></span>
                Simpan
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Viewer KK (fullscreen) -->
<div class="modal fade" id="kkViewerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-dark">
      <div class="modal-header border-0">
        <h6 class="modal-title text-white">Pratinjau Kartu Keluarga</h6>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close">Tutup</button>
      </div>
      <div class="modal-body d-flex justify-content-center align-items-center p-0">
        <img id="kk-viewer-img" src="" alt="Kartu Keluarga" class="img-fluid" style="max-height:100vh; object-fit: contain;">
      </div>
    </div>
  </div>
  </div>

<!-- Modal Batalkan Validasi -->
<div class="modal fade" id="unvalidateModal" tabindex="-1" aria-labelledby="unvalidateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="unvalidateModalLabel">Batalkan Validasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="unv-text" class="mb-2"></p>
        <div class="small text-muted">Pengguna akan menerima pemberitahuan WhatsApp bahwa validasi dibatalkan dan diminta mengisi ulang data.</div>
        <div id="unv-alert" class="mt-3" style="display:none;"></div>
        <input type="hidden" id="unv-id-siswa" value="">
        <input type="hidden" id="unv-nama" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tidak</button>
        <button type="button" class="btn btn-danger" id="unv-send-btn">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="unv-spinner" role="status" aria-hidden="true"></span>
            Ya, Batalkan
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<?php if ($refDapoAvailable): ?>
<!-- Modal Residu Data -->
<div class="modal fade" id="residuModal" tabindex="-1" aria-labelledby="residuModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="residuModalLabel">Residu Data (Perbedaan dengan Dapodik)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="residu-info" class="small text-muted mb-2"></div>
        <div id="residu-content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script id="residu-json" type="application/json"><?php echo json_encode($residuEntries, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?></script>
<script>
// Set badge count from PHP
document.addEventListener('DOMContentLoaded', function(){
  var badge = document.getElementById('residu-count');
  if (badge) badge.textContent = <?php echo (int)$residuTotal; ?>;
});
</script>
<?php endif; ?>

<!-- Modal Edit Biodata -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Biodata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="edit-alert" class="alert d-none" role="alert"></div>
        <div id="edit-residu" class="mb-3"></div>
        <form id="edit-form" class="row g-3" novalidate>
            <input type="hidden" name="id_siswa" id="edit-id">

            <div class="col-12">
                <h6 class="text-uppercase text-muted small mb-1">Data Siswa</h6>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">NIS</label>
                <input type="text" class="form-control" name="nis">
            </div>
            <div class="col-md-3">
                <label class="form-label">NISN</label>
                <input type="text" class="form-control" name="nisn">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelas</label>
                <select class="form-select" name="kelas" id="edit-kelas">
                    <option value="">Pilih kelas</option>
                    <?php foreach ($kelasList as $k): ?>
                        <option value="<?= htmlspecialchars($k['kelas']) ?>"><?= htmlspecialchars($k['kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tingkat</label>
                <input type="text" class="form-control" name="level">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jurusan</label>
                <input type="text" class="form-control" name="jurusan">
            </div>
            <div class="col-md-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" name="password">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Kelamin</label>
                <select class="form-select" name="jk" id="edit-jk">
                    <option value="">Pilih JK</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Agama</label>
                <select class="form-select" name="agama" id="edit-agama">
                    <option value="">Pilih Agama</option>
                    <?php foreach ($agamaOptions as $opt): ?>
                        <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" name="t_lahir">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="text" class="form-control" name="tgl_lahir">
            </div>
            <div class="col-md-3">
                <label class="form-label">No. WhatsApp</label>
                <input type="text" class="form-control" name="nowa">
            </div>
            <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hobi</label>
                <input type="text" class="form-control" name="hobi">
            </div>
            <div class="col-md-3">
                <label class="form-label">Cita-cita</label>
                <input type="text" class="form-control" name="cita_cita">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sekolah Asal</label>
                <input type="text" class="form-control" name="asal_sek">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tahun Lulus</label>
                <input type="text" class="form-control" name="thn_lulus">
            </div>
            <div class="col-md-3">
                <label class="form-label">Beasiswa</label>
                <input type="text" class="form-control" name="beasiswa" id="edit-beasiswa" list="datalist-beasiswa">
            </div>
            <div class="col-md-3">
                <label class="form-label">No. KIP</label>
                <input type="text" class="form-control" name="no_kip">
            </div>
            <div class="col-md-3">
                <label class="form-label">No. KKS</label>
                <input type="text" class="form-control" name="no_kks">
            </div>
            <div class="col-md-3">
                <label class="form-label">Anak Ke</label>
                <input type="text" class="form-control" name="anakke">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jumlah Saudara</label>
                <input type="text" class="form-control" name="jumlah_saudara">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tinggi Badan (cm)</label>
                <input type="text" class="form-control" name="t_badan">
            </div>
            <div class="col-md-3">
                <label class="form-label">Berat Badan (kg)</label>
                <input type="text" class="form-control" name="b_badan">
            </div>
            <div class="col-md-3">
                <label class="form-label">Lingkar Kepala (cm)</label>
                <input type="text" class="form-control" name="l_kepala">
            </div>

            <div class="col-12">
                <h6 class="text-uppercase text-muted small mb-1 mt-3">Dokumen &amp; Alamat</h6>
            </div>
            <div class="col-md-3">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" name="nik">
            </div>
            <div class="col-md-3">
                <label class="form-label">No. KK</label>
                <input type="text" class="form-control" name="nokk">
            </div>
            <div class="col-12">
                <label class="form-label">File KK</label>
                <div id="edit-kk-alert" class="alert d-none" role="alert"></div>
                <div class="mb-2 text-center">
                    <img id="edit-kk-preview-img" src="" alt="Pratinjau KK" class="img-fluid rounded d-none kk-zoomable" style="max-height:240px; cursor: zoom-in;">
                    <div id="edit-kk-preview-file" class="alert alert-info d-none mt-2 mb-0"></div>
                    <div id="edit-kk-preview-empty" class="text-muted">Belum ada file KK yang diunggah.</div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <input type="file" class="form-control" id="edit-kk-file" accept=".jpg,.jpeg,.png,.webp,.gif,.bmp,.pdf">
                    <button type="button" class="btn btn-primary" id="edit-kk-upload-btn">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="edit-kk-spinner" role="status" aria-hidden="true"></span>
                        Upload
                    </button>
                    <button type="button" class="btn btn-outline-danger d-none" id="edit-kk-delete-btn">Hapus</button>
                </div>
                <div class="form-text">Format yang didukung: JPG, PNG, PDF. Ukuran maksimal 5 MB.</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">RT</label>
                <input type="text" class="form-control" name="rt">
            </div>
            <div class="col-md-3">
                <label class="form-label">RW</label>
                <input type="text" class="form-control" name="rw">
            </div>
            <div class="col-md-3">
                <label class="form-label">Provinsi</label>
                <input type="text" class="form-control" name="provinsi" id="edit-provinsi" list="datalist-provinsi" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kabupaten/Kota</label>
                <input type="text" class="form-control" name="kabupaten" id="edit-kabupaten" list="datalist-kabupaten" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kecamatan</label>
                <input type="text" class="form-control" name="kecamatan" id="edit-kecamatan" list="datalist-kecamatan" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kelurahan/Desa</label>
                <input type="text" class="form-control" name="kelurahan" id="edit-kelurahan" list="datalist-kelurahan" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">Kode Pos</label>
                <input type="text" class="form-control" name="kode_pos" id="edit-kode-pos" autocomplete="off">
            </div>

            <div class="col-12">
                <h6 class="text-uppercase text-muted small mb-1 mt-3">Data Ayah</h6>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama Ayah</label>
                <input type="text" class="form-control" name="nama_ayah">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status Ayah</label>
                <select class="form-select" name="status_ayah" id="edit-status-ayah">
                    <option value="">Pilih Status</option>
                    <?php foreach ($statusOrtuOptions as $opt): ?>
                        <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">No. HP Ayah</label>
                <input type="text" class="form-control" name="no_hp_ayah">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tempat Lahir Ayah</label>
                <input type="text" class="form-control" name="tempat_lahir_ayah">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Lahir Ayah</label>
                <input type="text" class="form-control" name="tgl_lahir_ayah">
            </div>
            <div class="col-md-4">
                <label class="form-label">Pendidikan Ayah</label>
                <input type="text" class="form-control" name="pendidikan_ayah" id="edit-pendidikan-ayah" list="datalist-parent-education">
            </div>
            <div class="col-md-4">
                <label class="form-label">Pekerjaan Ayah</label>
                <input type="text" class="form-control" name="pekerjaan_ayah" id="edit-pekerjaan-ayah" list="datalist-parent-occupation">
            </div>
            <div class="col-md-4">
                <label class="form-label">Penghasilan Ayah</label>
                <input type="text" class="form-control" name="penghasilan_ayah" id="edit-penghasilan-ayah" list="datalist-parent-income">
            </div>

            <div class="col-12">
                <h6 class="text-uppercase text-muted small mb-1 mt-3">Data Ibu</h6>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nama Ibu</label>
                <input type="text" class="form-control" name="nama_ibu">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status Ibu</label>
                <select class="form-select" name="status_ibu" id="edit-status-ibu">
                    <option value="">Pilih Status</option>
                    <?php foreach ($statusOrtuOptions as $opt): ?>
                        <option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">No. HP Ibu</label>
                <input type="text" class="form-control" name="no_hp_ibu">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tempat Lahir Ibu</label>
                <input type="text" class="form-control" name="tempat_lahir_ibu">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Lahir Ibu</label>
                <input type="text" class="form-control" name="tgl_lahir_ibu">
            </div>
            <div class="col-md-4">
                <label class="form-label">Pendidikan Ibu</label>
                <input type="text" class="form-control" name="pendidikan_ibu" id="edit-pendidikan-ibu" list="datalist-parent-education">
            </div>
            <div class="col-md-4">
                <label class="form-label">Pekerjaan Ibu</label>
                <input type="text" class="form-control" name="pekerjaan_ibu" id="edit-pekerjaan-ibu" list="datalist-parent-occupation">
            </div>
            <div class="col-md-4">
                <label class="form-label">Penghasilan Ibu</label>
                <input type="text" class="form-control" name="penghasilan_ibu" id="edit-penghasilan-ibu" list="datalist-parent-income">
            </div>
        </form>
        <datalist id="datalist-beasiswa">
            <?php foreach ($beasiswaOptions as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <datalist id="datalist-parent-education">
            <?php foreach ($parentEducationOptions as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <datalist id="datalist-parent-occupation">
            <?php foreach ($parentOccupationOptions as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <datalist id="datalist-parent-income">
            <?php foreach ($parentIncomeOptions as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>"></option>
            <?php endforeach; ?>
        </datalist>
        <datalist id="datalist-provinsi"></datalist>
        <datalist id="datalist-kabupaten"></datalist>
        <datalist id="datalist-kecamatan"></datalist>
        <datalist id="datalist-kelurahan"></datalist>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" form="edit-form" class="btn btn-primary" id="edit-save-btn">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="edit-spinner" role="status" aria-hidden="true"></span>
            Simpan Perubahan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Validasi -->
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="validationModalLabel">Validasi Biodata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2 small text-muted" id="vm-info"></div>
        <div id="vm-content" class="mb-3"></div>
        <div class="mb-3" id="vm-options-box">
            <label class="form-label mb-1">Opsi Validasi</label>
            <div class="btn-group" role="group" aria-label="Opsi Validasi">
                <input type="radio" class="btn-check" name="vm-status" id="vm-status-accepted" autocomplete="off">
                <label class="btn btn-outline-success btn-sm" for="vm-status-accepted">Data diterima</label>
                <input type="radio" class="btn-check" name="vm-status" id="vm-status-rejected" autocomplete="off">
                <label class="btn btn-outline-danger btn-sm" for="vm-status-rejected">Data tidak diterima</label>
            </div>
        </div>
        <div class="mb-3">
            <label for="vm-message" class="form-label">Pesan WhatsApp (boleh diedit)</label>
            <div id="vm-reject-prefix" class="form-text text-danger mb-2" style="display:none;">Data belum valid, harap merubah data :</div>
            <textarea id="vm-message" class="form-control" rows="8" placeholder="Tulis pesan yang akan dikirim..."></textarea>
            <div class="form-text">Pesan ini akan dikirim ke orang tua melalui WhatsApp API.</div>
        </div>
        <div id="vm-alert" class="mt-3" style="display:none;"></div>
        <input type="hidden" id="vm-id-siswa" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="vm-send-btn">
            <span class="spinner-border spinner-border-sm me-2 d-none" id="vm-spinner" role="status" aria-hidden="true"></span>
            Kirim
        </button>
      </div>
    </div>
  </div>
  </div>

<script>
$(document).ready(function () {
    const table = $('#tableSiswa').DataTable({
        pageLength: 25,
        dom: 'rtip'
    });
    // Menyesuaikan ulang kolom tabel setelah inisialisasi dan saat window resize
    setTimeout(function() { table.columns.adjust().draw(); }, 200);
    $(window).on('resize', function () {
        setTimeout(function() { table.columns.adjust().draw(); }, 50);
    });

    // Toolbar behaviors
    const searchInput = document.getElementById('toolbar-search');
    const searchBtn = document.getElementById('toolbar-search-btn');
    const lengthSelect = document.getElementById('toolbar-length');
    const kelasSelect = document.getElementById('toolbar-kelas');

    // Initialize length select with current page length
    if (lengthSelect) {
        try { lengthSelect.value = table.page.len().toString(); } catch(e) {}
        lengthSelect.addEventListener('change', function(){
            const val = parseInt(this.value || '25', 10);
            table.page.len(val).draw();
        });
    }

    // Custom multi-token, case-insensitive search across all columns
    if (!$.fn.dataTable.ext._customMultiTokenSearch) {
        $.fn.dataTable.ext._customMultiTokenSearch = true;
        $.fn.dataTable.ext.search.push(function(settings, data){
            // Batasi filter hanya untuk tabel ini
            if (!settings || !settings.nTable || settings.nTable.id !== 'tableSiswa') return true;
            const input = document.getElementById('toolbar-search');
            if (!input) return true;
            const q = (input.value || '').trim().toLowerCase();
            if (!q) return true;
            const tokens = q.split(/\s+/).filter(Boolean);
            const rowText = (data || []).join(' ').toLowerCase();
            return tokens.every(t => rowText.indexOf(t) !== -1);
        });
    }

    function doSearch(){
        // Pastikan pencarian global DataTables kosong agar hanya filter kustom yang berlaku
        try { table.search(''); } catch(e) {}
        table.draw();
    }
    if (searchBtn) searchBtn.addEventListener('click', function(e){ e.preventDefault(); doSearch(); });
    if (searchInput) {
        // Cari saat mengetik (real-time) dan saat Enter
        searchInput.addEventListener('input', doSearch);
        searchInput.addEventListener('keyup', function(e){ if (e.key === 'Enter') doSearch(); });
    }

    if (kelasSelect) {
        kelasSelect.addEventListener('change', function(){
            const selected = this.value;
            const url = new URL(window.location.href);
            if (selected) url.searchParams.set('filterKelas', selected); else url.searchParams.delete('filterKelas');
            window.location.href = url.toString();
        });
    }

    // ========== Modal Validasi & Kirim WA ==========
    const validationModal = document.getElementById('validationModal');
    const vmTitle = document.getElementById('validationModalLabel');
    const vmInfo = document.getElementById('vm-info');
    const vmContent = document.getElementById('vm-content');
    const vmAlert = document.getElementById('vm-alert');
    const vmIdSiswa = document.getElementById('vm-id-siswa');
    const vmSendBtn = document.getElementById('vm-send-btn');
    const vmSpinner = document.getElementById('vm-spinner');
    const vmMessage = document.getElementById('vm-message');
    const vmStatusAccepted = document.getElementById('vm-status-accepted');
    const vmStatusRejected = document.getElementById('vm-status-rejected');
    const vmOptionsBox = document.getElementById('vm-options-box');
    const vmRejectPrefix = document.getElementById('vm-reject-prefix');
    let vmCurrNama = '';
    let vmAutoMsgIncomplete = '';

    // Reset modal state each time it is shown
    validationModal.addEventListener('show.bs.modal', function (event) {
        vmAlert.style.display = 'none';
        vmAlert.className = '';
        vmAlert.innerHTML = '';
        vmSendBtn.disabled = false;
        vmSpinner.classList.add('d-none');
    });

    // (Toggle Validasi quick dihapus — digantikan alur Validasi/Unvalidate)
    $(document).on('click', '.btn-open-validation', function () {
        const btn = this;
        const id = btn.getAttribute('data-id');
        const nama = btn.getAttribute('data-nama') || '-';
        const kelas = btn.getAttribute('data-kelas') || '-';
        const percent = btn.getAttribute('data-percent') || '0';
        const isComplete = btn.getAttribute('data-complete') === '1';
        const nowa = btn.getAttribute('data-nowa') || '';
        let missing = [];
        try {
            const raw = btn.getAttribute('data-missing') || '[]';
            missing = JSON.parse(raw);
        } catch (e) {
            missing = [];
        }

        // Isi judul dan info
        vmTitle.textContent = 'Validasi Biodata - ' + nama;
        vmInfo.textContent = 'Kelas: ' + kelas + ' • Kelengkapan: ' + percent + '%';

        // Isi konten validasi
        if (isComplete) {
            vmContent.innerHTML = '<div class="alert alert-success mb-0">Data biodata sudah lengkap (100%).</div>';
        } else {
            let list = '<ul class="mb-0 ps-3">';
            missing.forEach(function (f) { list += '<li>' + $('<div>').text(f).html() + '</li>'; });
            list += '</ul>';
            vmContent.innerHTML = '<div class="alert alert-warning"><strong>Data belum lengkap.</strong><br>Mohon cek dan lengkapi beberapa isian berikut:</div>' + list;
        }

        // Susun pesan default untuk dapat diedit
        let msg = '';
        if (isComplete) {
            msg += 'Yth. Bapak/Ibu Wali Murid dari ananda ' + nama + ' (Kelas ' + kelas + '),\n\n';
            msg += 'Dengan hormat,\n';
            msg += 'Data diterima, Terimakasih atas kesungguhan Anda dalam mengisi biodata ' + nama + ' dengan valid. Data akan terkunci dan tidak bisa diedit. Jika ditemukan data yang tidak sesuai, harap menghubungi operator agar bisa diedit kembali\n\n';
            msg += 'Terima kasih atas perhatian dan kerja sama Anda.\n\n';
            msg += 'Salam,\nWali Kelas';
        } else {
            msg += 'Yth. Bapak/Ibu Wali Murid dari ananda ' + nama + ' (Kelas ' + kelas + '),\n\n';
            msg += 'Dengan hormat,\n';
            msg += 'Kami informasikan bahwa kelengkapan data biodata ananda baru mencapai ' + percent + '%. '; 
            msg += 'Masih ada beberapa data yang perlu dilengkapi, yaitu:\n\n';
            missing.forEach(function (f) { msg += '- ' + f + '\n'; });
            msg += '\nMohon kesediaannya untuk segera melengkapi data tersebut melalui portal siswa atau menghubungi pihak sekolah.\n\n';
            msg += 'Terima kasih atas perhatian dan kerja sama Anda.\n\n';
            msg += 'Salam,\nWali Kelas';
        }

        vmMessage.value = msg;
        vmAutoMsgIncomplete = isComplete ? '' : msg;

        // Simpan id siswa untuk dikirim
        vmIdSiswa.value = id;
        vmCurrNama = nama;

        // Tampilkan opsi hanya jika lengkap 100%
        if (isComplete) {
            if (vmOptionsBox) vmOptionsBox.style.display = 'block';
            vmStatusAccepted.checked = true;
            vmStatusRejected.checked = false;
            vmMessage.value = 'Data diterima, Terimakasih atas kesungguhan Anda dalam mengisi biodata ' + vmCurrNama + ' dengan valid. Data akan terkunci dan tidak bisa diedit. Jika ditemukan data yang tidak sesuai, harap menghubungi operator agar bisa diedit kembali';
            if (vmRejectPrefix) vmRejectPrefix.style.display = 'none';
        } else {
            if (vmOptionsBox) vmOptionsBox.style.display = 'none';
            vmStatusAccepted.checked = false;
            vmStatusRejected.checked = true;
            vmMessage.value = vmAutoMsgIncomplete;
            if (vmRejectPrefix) vmRejectPrefix.style.display = 'block';
        }

        // Atur tombol kirim berdasarkan ketersediaan nomor WA (kolom nowa)
        if (!nowa) {
            vmSendBtn.disabled = true;
            vmAlert.className = 'alert alert-danger';
            vmAlert.style.display = 'block';
            vmAlert.innerHTML = 'Nomor WhatsApp orang tua tidak tersedia (kolom nowa kosong).';
        }
    });

    // Toggle pesan berdasarkan opsi validasi
    function refreshMessageByStatus(){
        if (vmStatusAccepted.checked) {
            vmMessage.value = 'Yth. Bapak/Ibu Wali Murid dari ananda ' + vmCurrNama + ',\n\n' +
                              'Dengan hormat,\n' +
                              'Data diterima, Terimakasih atas kesungguhan Anda dalam mengisi biodata ' + vmCurrNama + ' dengan valid. Data akan terkunci dan tidak bisa diedit. Jika ditemukan data yang tidak sesuai, harap menghubungi operator agar bisa diedit kembali\n\n' +
                              'Terima kasih atas perhatian dan kerja sama Anda.\n\n' +
                              'Salam,\nWali Kelas';
            if (vmRejectPrefix) vmRejectPrefix.style.display = 'none';
        } else if (vmStatusRejected.checked) {
            // Kosongkan kolom: sistem akan menambahkan prefix otomatis di backend
            vmMessage.value = '';
            vmMessage.focus();
            if (vmRejectPrefix) vmRejectPrefix.style.display = 'block';
        }
    }
    vmStatusAccepted.addEventListener('change', refreshMessageByStatus);
    vmStatusRejected.addEventListener('change', refreshMessageByStatus);

    vmSendBtn.addEventListener('click', function () {
        const id = vmIdSiswa.value;
        if (!id) return;

        // UI: disable + spinner
        vmSendBtn.disabled = true;
        vmSpinner.classList.remove('d-none');
        vmAlert.style.display = 'none';
        vmAlert.className = '';
        vmAlert.innerHTML = '';

        // Kirim via AJAX POST ke kirim_wa_biodata.php
        const formData = new FormData();
        formData.append('id_siswa', id);
        formData.append('pesan', vmMessage.value || '');
        if (vmStatusAccepted.checked) formData.append('status', 'accepted');
        else if (vmStatusRejected.checked) formData.append('status', 'rejected');

        fetch('kirim_wa_biodata.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        }).then(async (res) => {
            let data = null;
            try { data = await res.json(); } catch (e) {}
            if (!res.ok) {
                throw new Error((data && data.message) ? data.message : ('HTTP ' + res.status));
            }
            if (data && data.status === 'success') {
                // Tutup modal dan kembali ke tampilan data (reload)
                try {
                    const modal = bootstrap.Modal.getInstance(validationModal) || new bootstrap.Modal(validationModal);
                    modal.hide();
                } catch (e) {}
                window.location.reload();
            } else {
                throw new Error(data && data.message ? data.message : 'Gagal mengirim pesan.');
            }
        }).catch((err) => {
            vmAlert.className = 'alert alert-danger';
            vmAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + (err && err.message ? err.message : 'Terjadi kesalahan.');
            vmAlert.style.display = 'block';
        }).finally(() => {
            vmSpinner.classList.add('d-none');
            vmSendBtn.disabled = false; // re-enable to allow retry
        });
    });

    const studentsDataEl = document.getElementById('students-json');
    let studentsMap = {};
    if (studentsDataEl) {
        try {
            const parsed = JSON.parse(studentsDataEl.textContent || '{}');
            studentsMap = (parsed && typeof parsed === 'object' && !Array.isArray(parsed)) ? parsed : {};
        } catch (err) {
            studentsMap = {};
        }
    }

    const editModalEl = document.getElementById('editModal');
    const editForm = document.getElementById('edit-form');
    const editAlert = document.getElementById('edit-alert');
    const editResiduBox = document.getElementById('edit-residu');
    const editSaveBtn = document.getElementById('edit-save-btn');
    const editSpinner = document.getElementById('edit-spinner');
    const editModalLabel = document.getElementById('editModalLabel');
    const editModalInstance = editModalEl ? new bootstrap.Modal(editModalEl) : null;

    const kkModalEl = document.getElementById('kkModal');
    const kkModalInstance = kkModalEl ? new bootstrap.Modal(kkModalEl) : null;
    const kkModalLabel = document.getElementById('kkModalLabel');
    const kkForm = document.getElementById('kk-form');
    const kkAlert = document.getElementById('kk-alert');
    const kkPreviewImg = document.getElementById('kk-preview-img');
    const kkPreviewFile = document.getElementById('kk-preview-file');
    const kkPreviewEmpty = document.getElementById('kk-preview-empty');
    const kkFileInput = document.getElementById('kk-file');
    const kkUploadBtn = document.getElementById('kk-upload-btn');
    const kkSpinner = document.getElementById('kk-spinner');
    const kkDeleteBtn = document.getElementById('kk-delete-btn');
    const kkIdField = document.getElementById('kk-id');
    const KK_IMAGE_EXTS = ['jpg','jpeg','png','gif','webp','bmp','jfif'];

    // Elements inside Edit Modal for KK management
    const eKkAlert = document.getElementById('edit-kk-alert');
    const eKkPreviewImg = document.getElementById('edit-kk-preview-img');
    const eKkPreviewFile = document.getElementById('edit-kk-preview-file');
    const eKkPreviewEmpty = document.getElementById('edit-kk-preview-empty');
    const eKkFileInput = document.getElementById('edit-kk-file');
    const eKkUploadBtn = document.getElementById('edit-kk-upload-btn');
    const eKkSpinner = document.getElementById('edit-kk-spinner');
    const eKkDeleteBtn = document.getElementById('edit-kk-delete-btn');
    // KK viewer elements
    const kkViewerModalEl = document.getElementById('kkViewerModal');
    const kkViewerImg = document.getElementById('kk-viewer-img');
    const kkViewerModal = kkViewerModalEl ? new bootstrap.Modal(kkViewerModalEl) : null;

    const editFieldNames = [
        'nama','nis','nisn','kelas','level','jurusan','username','password','jk','agama','t_lahir','tgl_lahir','nowa','email','hobi','cita_cita','asal_sek','thn_lulus','beasiswa','no_kip','no_kks','anakke','jumlah_saudara','t_badan','b_badan','l_kepala','nik','nokk','kk_ibu','rt','rw','kelurahan','kecamatan','provinsi','kode_pos','nama_ayah','status_ayah','no_hp_ayah','tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','nama_ibu','status_ibu','no_hp_ibu','tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu'
    ];

    function escapeHtml(value) {
        return String(value ?? '').replace(/&/g, '&amp;')
                                  .replace(/</g, '&lt;')
                                  .replace(/>/g, '&gt;')
                                  .replace(/"/g, '&quot;')
                                  .replace(/'/g, '&#39;');
    }

    function setSelectValue(select, value) {
        if (!select) return;
        const normalized = value == null ? '' : String(value);
        let found = false;
        Array.from(select.options).forEach(function(option){
            if (option.value === normalized) {
                found = true;
            }
        });
        if (!found && normalized !== '') {
            const opt = new Option(normalized, normalized, true, true);
            select.add(opt);
        }
        select.value = normalized;
    }

    const WILAYAH_API = '../mydashboard/get_wilayah.php';
    const $provInput = $('#edit-provinsi');
    const $kabInput = $('#edit-kabupaten');
    const $kecInput = $('#edit-kecamatan');
    const $kelInput = $('#edit-kelurahan');
    const $kodePosInput = $('#edit-kode-pos');

    const wilayahCache = {
        provinsi: null,
        kabupaten: {},
        kecamatan: {},
        kelurahan: {}
    };

    function normalizeWilayah(value) {
        return (value || '').toUpperCase().trim();
    }

    function populateDatalistElement(id, items) {
        const dl = document.getElementById(id);
        if (!dl) return;
        dl.innerHTML = '';
        (items || []).forEach(function(item){
            if (!item) return;
            const option = document.createElement('option');
            option.value = normalizeWilayah(item);
            dl.appendChild(option);
        });
    }

    function fetchWilayah(action, params) {
        const qs = new URLSearchParams({ action });
        if (params) {
            Object.keys(params).forEach(function(key){
                if (params[key] !== undefined && params[key] !== null && params[key] !== '') {
                    qs.append(key, params[key]);
                }
            });
        }
        return fetch(`${WILAYAH_API}?${qs.toString()}`, { cache: 'no-store' })
            .then(function(res){ return res.json(); })
            .catch(function(){ return { error: 'Gagal terhubung ke layanan wilayah.' }; });
    }

    function loadProvinsi() {
        if (wilayahCache.provinsi) {
            populateDatalistElement('datalist-provinsi', wilayahCache.provinsi);
            return Promise.resolve(wilayahCache.provinsi);
        }
        return fetchWilayah('provinsi').then(function(res){
            if (Array.isArray(res)) {
                const list = res.map(normalizeWilayah);
                wilayahCache.provinsi = list;
                populateDatalistElement('datalist-provinsi', list);
                return list;
            }
            if (res && res.error) alert(res.error);
            return [];
        });
    }

    function loadKabupaten(prov) {
        const key = normalizeWilayah(prov);
        if (!key) return Promise.resolve([]);
        if (wilayahCache.kabupaten[key]) {
            populateDatalistElement('datalist-kabupaten', wilayahCache.kabupaten[key]);
            return Promise.resolve(wilayahCache.kabupaten[key]);
        }
        return fetchWilayah('kabupaten', { provinsi: key }).then(function(res){
            if (Array.isArray(res)) {
                const list = res.map(normalizeWilayah);
                wilayahCache.kabupaten[key] = list;
                populateDatalistElement('datalist-kabupaten', list);
                return list;
            }
            if (res && res.error) alert(res.error);
            return [];
        });
    }

    function loadKecamatan(prov, kab) {
        const provKey = normalizeWilayah(prov);
        const kabKey = normalizeWilayah(kab);
        if (!provKey || !kabKey) return Promise.resolve([]);
        const cacheKey = `${provKey}|${kabKey}`;
        if (wilayahCache.kecamatan[cacheKey]) {
            populateDatalistElement('datalist-kecamatan', wilayahCache.kecamatan[cacheKey]);
            return Promise.resolve(wilayahCache.kecamatan[cacheKey]);
        }
        return fetchWilayah('kecamatan', { provinsi: provKey, kabupaten: kabKey }).then(function(res){
            if (Array.isArray(res)) {
                const list = res.map(normalizeWilayah);
                wilayahCache.kecamatan[cacheKey] = list;
                populateDatalistElement('datalist-kecamatan', list);
                return list;
            }
            if (res && res.error) alert(res.error);
            return [];
        });
    }

    function loadKelurahan(prov, kab, kec) {
        const provKey = normalizeWilayah(prov);
        const kabKey = normalizeWilayah(kab);
        const kecKey = normalizeWilayah(kec);
        if (!provKey || !kabKey || !kecKey) return Promise.resolve([]);
        const cacheKey = `${provKey}|${kabKey}|${kecKey}`;
        if (wilayahCache.kelurahan[cacheKey]) {
            populateDatalistElement('datalist-kelurahan', wilayahCache.kelurahan[cacheKey]);
            return Promise.resolve(wilayahCache.kelurahan[cacheKey]);
        }
        return fetchWilayah('kelurahan', { provinsi: provKey, kabupaten: kabKey, kecamatan: kecKey }).then(function(res){
            if (Array.isArray(res)) {
                const list = res.map(normalizeWilayah);
                wilayahCache.kelurahan[cacheKey] = list;
                populateDatalistElement('datalist-kelurahan', list);
                return list;
            }
            if (res && res.error) alert(res.error);
            return [];
        });
    }

    function fetchDetailAndSetKodePos(prov, kab, kec, kel) {
        const provKey = normalizeWilayah(prov);
        const kabKey = normalizeWilayah(kab);
        const kecKey = normalizeWilayah(kec);
        const kelKey = normalizeWilayah(kel);
        if (!provKey || !kabKey || !kecKey || !kelKey) return;
        fetchWilayah('detail', {
            provinsi: provKey,
            kabupaten: kabKey,
            kecamatan: kecKey,
            kelurahan: kelKey
        }).then(function(res){
            if (res && !res.error && res.kodepos) {
                if (!$kodePosInput.val()) {
                    $kodePosInput.val(res.kodepos);
                }
            }
        });
    }

    function clearWilayahAfter(level) {
        if (level === 'provinsi') {
            $kabInput.val('');
            $kecInput.val('');
            $kelInput.val('');
            populateDatalistElement('datalist-kabupaten', []);
            populateDatalistElement('datalist-kecamatan', []);
            populateDatalistElement('datalist-kelurahan', []);
            wilayahCache.kabupaten = {};
            wilayahCache.kecamatan = {};
            wilayahCache.kelurahan = {};
            $kodePosInput.val('');
        }
        if (level === 'kabupaten') {
            $kecInput.val('');
            $kelInput.val('');
            populateDatalistElement('datalist-kecamatan', []);
            populateDatalistElement('datalist-kelurahan', []);
            wilayahCache.kecamatan = {};
            wilayahCache.kelurahan = {};
            $kodePosInput.val('');
        }
        if (level === 'kecamatan') {
            $kelInput.val('');
            populateDatalistElement('datalist-kelurahan', []);
            wilayahCache.kelurahan = {};
            $kodePosInput.val('');
        }
    }

    function prefillWilayahLists(student) {
        const prov = normalizeWilayah(student.provinsi || '');
        const kab = normalizeWilayah(student.kabupaten || '');
        const kec = normalizeWilayah(student.kecamatan || '');
        const kel = normalizeWilayah(student.kelurahan || '');
        if (!prov) return;
        loadProvinsi().then(function(){
            if (!prov) return;
            loadKabupaten(prov).then(function(){
                if (!kab) return;
                loadKecamatan(prov, kab).then(function(){
                    if (!kec) return;
                    loadKelurahan(prov, kab, kec).then(function(){
                        if (kel) {
                            fetchDetailAndSetKodePos(prov, kab, kec, kel);
                        }
                    });
                });
            });
        });
    }

    function populateEditForm(student) {
        if (!editForm) return;
        const hiddenId = editForm.elements['id_siswa'];
        if (hiddenId) hiddenId.value = student.id_siswa || '';

        editFieldNames.forEach(function(name){
            const input = editForm.elements[name];
            if (!input) return;
            let value = student[name];
            if (value === null || value === undefined) value = '';
            if (name === 'jk') {
                value = value ? String(value).toUpperCase() : '';
            } else if (name === 'status_ayah' || name === 'status_ibu' || name === 'beasiswa' ||
                       name === 'pendidikan_ayah' || name === 'pekerjaan_ayah' || name === 'penghasilan_ayah' ||
                       name === 'pendidikan_ibu' || name === 'pekerjaan_ibu' || name === 'penghasilan_ibu') {
                value = value ? String(value).toUpperCase() : '';
                if (name === 'beasiswa' && value === '') {
                    value = 'TIDAK ADA';
                }
            } else if (name === 'provinsi' || name === 'kabupaten' || name === 'kecamatan' || name === 'kelurahan') {
                value = value ? String(value).toUpperCase() : '';
            }
            if (input.tagName === 'SELECT') {
                setSelectValue(input, value);
            } else {
                input.value = value;
            }
        });

        if (editResiduBox) {
            const residuList = Array.isArray(student.residu) ? student.residu : [];
            if (residuList.length) {
                let html = '<div class="alert alert-warning small"><strong>Perbedaan dengan Dapodik:</strong><ul class="mb-0">';
                residuList.forEach(function(item){
                    const label = escapeHtml(item.label || '');
                    const app = escapeHtml(item.app || 'kosong');
                    const dapodik = escapeHtml(item.dapodik || 'kosong');
                    html += `<li>${label}: Aplikasi = <span class="text-danger">${app}</span>, Dapodik = <span class="text-success">${dapodik}</span></li>`;
                });
                html += '</ul></div>';
                editResiduBox.innerHTML = html;
            } else {
                editResiduBox.innerHTML = '';
            }
        }

        // Tandai field residu dengan highlight merah dan hilangkan saat diedit
        (function(){
            const residuList = Array.isArray(student.residu) ? student.residu : [];
            if (!residuList.length) return;
            const labelToField = {
                'NISN':'nisn',
                'Nama':'nama',
                'Tempat Lahir':'t_lahir',
                'Tanggal Lahir':'tgl_lahir',
                'JK':'jk',
                'NIK':'nik',
                'No KK':'nokk',
                'Agama':'agama',
                'Email':'email',
                'RT':'rt',
                'RW':'rw',
                'Kabupaten':'kabupaten',
                'Kelurahan':'kelurahan',
                'Kecamatan':'kecamatan',
                'Provinsi':'provinsi',
                'Kode Pos':'kode_pos'
            };
            const markField = function(name){
                const el = editForm && editForm.elements[name];
                if (!el) return;
                el.classList.add('is-invalid','residu-highlight');
                const clear = function(){ el.classList.remove('is-invalid','residu-highlight'); };
                el.addEventListener('input', clear, { once: true });
                el.addEventListener('change', clear, { once: true });
            };
            residuList.forEach(function(r){
                const field = labelToField[(r.label||'').trim()];
                if (field) markField(field);
            });
        })();

        // Update Edit-Modal KK preview
        if (eKkAlert) { eKkAlert.className = 'alert d-none'; eKkAlert.textContent=''; }
        if (eKkFileInput) eKkFileInput.value='';
        if (eKkDeleteBtn) eKkDeleteBtn.classList.add('d-none');
        if (eKkSpinner) eKkSpinner.classList.add('d-none');
        (function(){
            const filename = student && student.kk_ibu ? String(student.kk_ibu) : '';
            const hasFile = filename !== '';
            if (eKkPreviewImg) eKkPreviewImg.classList.add('d-none');
            if (eKkPreviewFile) { eKkPreviewFile.classList.add('d-none'); eKkPreviewFile.innerHTML=''; }
            if (eKkPreviewEmpty) eKkPreviewEmpty.classList.add('d-none');
            if (!hasFile) {
                if (eKkPreviewEmpty) eKkPreviewEmpty.classList.remove('d-none');
                return;
            }
            const ext = filename.split('.').pop().toLowerCase();
            const url = '../uploads/kk/' + encodeURIComponent(filename);
            if (KK_IMAGE_EXTS.includes(ext)) {
                if (eKkPreviewImg) { eKkPreviewImg.src = url + '?t=' + Date.now(); eKkPreviewImg.classList.remove('d-none'); }
            } else {
                if (eKkPreviewFile) { eKkPreviewFile.innerHTML = '<i class="bi bi-file-earmark-text me-1"></i><a href="' + url + '" target="_blank">Lihat file KK</a>'; eKkPreviewFile.classList.remove('d-none'); }
            }
            if (eKkDeleteBtn) eKkDeleteBtn.classList.remove('d-none');
        })();

        prefillWilayahLists(student);

        // Terapkan disable field ortu jika status = SUDAH MENINGGAL
        try { toggleParentFields('ayah'); } catch(_e) {}
        try { toggleParentFields('ibu'); } catch(_e) {}
    }

    function resetKkAlerts() {
        if (kkAlert) {
            kkAlert.className = 'alert d-none';
            kkAlert.textContent = '';
        }
    }

    function showKkAlert(type, message) {
        if (!kkAlert) return;
        kkAlert.className = 'alert alert-' + type;
        kkAlert.textContent = message;
    }

    function updateKkPreview(student) {
        if (!kkPreviewImg || !kkPreviewFile || !kkPreviewEmpty) return;
        const filename = (student && student.kk_ibu) ? String(student.kk_ibu) : '';
        const hasFile = filename !== '';

        kkPreviewImg.classList.add('d-none');
        kkPreviewFile.classList.add('d-none');
        kkPreviewFile.innerHTML = '';
        kkPreviewEmpty.classList.add('d-none');

        if (!hasFile) {
            kkPreviewEmpty.classList.remove('d-none');
            if (kkDeleteBtn) kkDeleteBtn.classList.add('d-none');
            return;
        }

        const ext = filename.split('.').pop().toLowerCase();
        const fileUrl = '../uploads/kk/' + encodeURIComponent(filename);
        if (KK_IMAGE_EXTS.includes(ext)) {
            kkPreviewImg.src = fileUrl + '?t=' + Date.now();
            kkPreviewImg.classList.remove('d-none');
            kkPreviewEmpty.classList.add('d-none');
            kkPreviewFile.classList.add('d-none');
        } else {
            kkPreviewFile.innerHTML = '<i class="bi bi-file-earmark-text me-1"></i><a href="' + fileUrl + '" target="_blank">Lihat file KK</a>';
            kkPreviewFile.classList.remove('d-none');
        }
        if (kkDeleteBtn) kkDeleteBtn.classList.remove('d-none');
    }

    $(document).on('click', '.btn-edit-siswa', function(){
        if (!editModalInstance || !editForm) return;
        const id = this.getAttribute('data-id');
        const student = id ? studentsMap[id] : null;
        if (!student) {
            alert('Data siswa tidak ditemukan.');
            return;
        }
        editForm.reset();
        populateEditForm(student);
        if (editAlert) {
            editAlert.className = 'alert d-none';
            editAlert.textContent = '';
        }
        if (editModalLabel) {
            editModalLabel.textContent = 'Edit Biodata - ' + (student.nama || '');
        }
        if (editSpinner) editSpinner.classList.add('d-none');
        if (editSaveBtn) editSaveBtn.disabled = false;
        editModalInstance.show();
    });

    $(document).on('click', '.btn-manage-kk', function(){
        if (!kkModalInstance || !kkForm) return;
        const id = this.getAttribute('data-id');
        const student = id ? studentsMap[id] : null;
        if (!student) {
            alert('Data siswa tidak ditemukan.');
            return;
        }
        if (kkIdField) kkIdField.value = student.id_siswa || '';
        if (kkFileInput) kkFileInput.value = '';
        resetKkAlerts();
        updateKkPreview(student);
        if (kkDeleteBtn) kkDeleteBtn.disabled = false;
        if (kkDeleteBtn) {
            if (student.kk_ibu && student.kk_ibu !== '') {
                kkDeleteBtn.classList.remove('d-none');
            } else {
                kkDeleteBtn.classList.add('d-none');
            }
        }
        if (kkSpinner) kkSpinner.classList.add('d-none');
        if (kkUploadBtn) kkUploadBtn.disabled = false;
        if (kkModalLabel) kkModalLabel.textContent = 'Kelola File KK - ' + (student.nama || '');
        kkModalInstance.show();
    });

    if (editForm) {
        editForm.addEventListener('submit', function(e){
            e.preventDefault();
            if (!editSaveBtn) return;
            if (editAlert) {
                editAlert.className = 'alert d-none';
                editAlert.textContent = '';
            }
            editSaveBtn.disabled = true;
            if (editSpinner) editSpinner.classList.remove('d-none');
            const formData = new FormData(editForm);
            fetch('update_siswa_biodata.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).then(async (res) => {
                let data;
                try {
                    data = await res.json();
                } catch (err) {
                    throw new Error('Respon server tidak valid.');
                }
                if (!res.ok || !data || data.status !== 'success') {
                    throw new Error(data && data.message ? data.message : 'Gagal menyimpan data.');
                }
                if (editAlert) {
                    editAlert.className = 'alert alert-success';
                    editAlert.textContent = data.message || 'Data berhasil diperbarui.';
                }
                setTimeout(function(){ window.location.reload(); }, 1200);
            }).catch((err) => {
                if (editAlert) {
                    editAlert.className = 'alert alert-danger';
                    editAlert.textContent = err && err.message ? err.message : 'Terjadi kesalahan saat menyimpan.';
                }
            }).finally(() => {
                if (editSpinner) editSpinner.classList.add('d-none');
                if (editSaveBtn) editSaveBtn.disabled = false;
            });
        });
    }

    if (kkUploadBtn && kkForm) {
        kkUploadBtn.addEventListener('click', function(){
            if (!kkIdField || !kkIdField.value) {
                showKkAlert('danger', 'ID siswa tidak ditemukan.');
                return;
            }
            if (!kkFileInput || !kkFileInput.files || kkFileInput.files.length === 0) {
                showKkAlert('warning', 'Pilih file KK terlebih dahulu.');
                return;
            }
            resetKkAlerts();
            kkUploadBtn.disabled = true;
            if (kkSpinner) kkSpinner.classList.remove('d-none');

            const formData = new FormData();
            formData.append('id_siswa', kkIdField.value);
            formData.append('action', 'upload');
            formData.append('kk_file', kkFileInput.files[0]);

            fetch('manage_kk.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).then(async (res) => {
                let data;
                try {
                    data = await res.json();
                } catch (err) {
                    throw new Error('Respon server tidak valid.');
                }
                if (!res.ok || !data || data.status !== 'success') {
                    throw new Error(data && data.message ? data.message : 'Gagal mengunggah file.');
                }
                showKkAlert('success', data.message || 'File KK berhasil diperbarui.');
                const student = studentsMap[kkIdField.value];
                if (student) {
                    student.kk_ibu = data.filename || '';
                }
                setTimeout(function(){ window.location.reload(); }, 1000);
            }).catch((err) => {
                showKkAlert('danger', err && err.message ? err.message : 'Terjadi kesalahan saat mengunggah.');
            }).finally(() => {
                if (kkSpinner) kkSpinner.classList.add('d-none');
                kkUploadBtn.disabled = false;
            });
        });
    }

    if (kkFileInput) {
        kkFileInput.addEventListener('change', function(){
            resetKkAlerts();
        });
    }

    if (kkDeleteBtn) {
        kkDeleteBtn.addEventListener('click', function(){
            if (!kkIdField || !kkIdField.value) {
                showKkAlert('danger', 'ID siswa tidak ditemukan.');
                return;
            }
            if (!confirm('Hapus file KK saat ini?')) {
                return;
            }
            resetKkAlerts();
            kkDeleteBtn.disabled = true;

            const formData = new FormData();
            formData.append('id_siswa', kkIdField.value);
            formData.append('action', 'delete');

            fetch('manage_kk.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).then(async (res) => {
                let data;
                try {
                    data = await res.json();
                } catch (err) {
                    throw new Error('Respon server tidak valid.');
                }
                if (!res.ok || !data || data.status !== 'success') {
                    throw new Error(data && data.message ? data.message : 'Gagal menghapus file KK.');
                }
                showKkAlert('success', data.message || 'File KK berhasil dihapus.');
                const student = studentsMap[kkIdField.value];
                if (student) {
                    student.kk_ibu = '';
                }
                setTimeout(function(){ window.location.reload(); }, 1000);
            }).catch((err) => {
                showKkAlert('danger', err && err.message ? err.message : 'Terjadi kesalahan saat menghapus.');
            }).finally(() => {
                kkDeleteBtn.disabled = false;
            });
        });
    }

    // Edit-Modal KK actions
    function eShowKkAlert(type, message){ if(!eKkAlert) return; eKkAlert.className='alert alert-'+type; eKkAlert.textContent=message; }
    function eResetKkAlert(){ if(eKkAlert){ eKkAlert.className='alert d-none'; eKkAlert.textContent=''; } }

    if (eKkUploadBtn) {
        eKkUploadBtn.addEventListener('click', function(){
            const idInput = editForm ? editForm.elements['id_siswa'] : null;
            const id = idInput ? idInput.value : '';
            if (!id) { eShowKkAlert('danger','ID siswa tidak ditemukan.'); return; }
            if (!eKkFileInput || !eKkFileInput.files || eKkFileInput.files.length === 0) {
                eShowKkAlert('warning','Pilih file KK terlebih dahulu.'); return; }
            eResetKkAlert();
            eKkUploadBtn.disabled = true; if (eKkSpinner) eKkSpinner.classList.remove('d-none');
            const fd = new FormData(); fd.append('id_siswa', id); fd.append('action','upload'); fd.append('kk_file', eKkFileInput.files[0]);
            fetch('manage_kk.php', { method:'POST', body: fd, credentials:'same-origin' })
              .then(async (res)=>{ let data; try{ data=await res.json(); }catch(e){ throw new Error('Respon server tidak valid.'); }
                     if(!res.ok || !data || data.status!=='success') throw new Error(data && data.message ? data.message : 'Gagal mengunggah file.');
                     eShowKkAlert('success', data.message || 'File KK berhasil diperbarui.');
                     if (studentsMap[id]) studentsMap[id].kk_ibu = data.filename || '';
                     setTimeout(()=>window.location.reload(), 1000);
              }).catch(err=>{ eShowKkAlert('danger', err && err.message ? err.message : 'Terjadi kesalahan saat mengunggah.'); })
              .finally(()=>{ if(eKkSpinner) eKkSpinner.classList.add('d-none'); eKkUploadBtn.disabled=false; });
        });
    }

    if (eKkFileInput) {
        eKkFileInput.addEventListener('change', function(){ eResetKkAlert(); });
    }

    if (eKkDeleteBtn) {
        eKkDeleteBtn.addEventListener('click', function(){
            const idInput = editForm ? editForm.elements['id_siswa'] : null;
            const id = idInput ? idInput.value : '';
            if (!id) { eShowKkAlert('danger','ID siswa tidak ditemukan.'); return; }
            if (!confirm('Hapus file KK saat ini?')) return;
            eResetKkAlert(); eKkDeleteBtn.disabled = true;
            const fd = new FormData(); fd.append('id_siswa', id); fd.append('action','delete');
            fetch('manage_kk.php', { method:'POST', body: fd, credentials:'same-origin' })
              .then(async (res)=>{ let data; try{ data=await res.json(); }catch(e){ throw new Error('Respon server tidak valid.'); }
                  if(!res.ok || !data || data.status!=='success') throw new Error(data && data.message ? data.message : 'Gagal menghapus file KK.');
                  eShowKkAlert('success', data.message || 'File KK berhasil dihapus.');
                  if (studentsMap[id]) studentsMap[id].kk_ibu = '';
                  setTimeout(()=>window.location.reload(), 900);
              }).catch(err=>{ eShowKkAlert('danger', err && err.message ? err.message : 'Terjadi kesalahan saat menghapus.'); })
              .finally(()=>{ eKkDeleteBtn.disabled = false; });
        });
    }

    // Klik gambar pratinjau KK di modal Edit untuk tampil fullscreen
    if (eKkPreviewImg && kkViewerModal) {
        eKkPreviewImg.addEventListener('click', function(){
            const src = this.getAttribute('src') || '';
            if (!src || this.classList.contains('d-none')) return;
            kkViewerImg.src = src;
            kkViewerModal.show();
        });
        kkViewerModalEl.addEventListener('shown.bs.modal', function(){
            // Angkat backdrop milik viewer agar berada tepat di bawah modal viewer
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const last = backdrops[backdrops.length - 1];
            if (last) last.classList.add('kk-viewer-backdrop');
        });
    }

    // ===== Toggle bidang orang tua bila meninggal (sesuai dashboard) =====
    function toggleParentFields(parentType){
        const $status = $(parentType === 'ayah' ? '#edit-status-ayah' : '#edit-status-ibu');
        if (!$status.length) return;
        const isDeceased = String(($status.val() || '')).toUpperCase() === 'SUDAH MENINGGAL';
        const fields = ['tempat_lahir','tgl_lahir','pendidikan','pekerjaan','penghasilan','no_hp'];
        fields.forEach(function (f){
            const $el = $(`[name="${f}_${parentType}"]`);
            if (!$el.length) return;
            $el.prop('disabled', isDeceased).prop('required', !isDeceased);
            if (isDeceased) { $el.val(''); }
        });
    }

    $('#edit-status-ayah').on('change', function(){ toggleParentFields('ayah'); });
    $('#edit-status-ibu').on('change', function(){ toggleParentFields('ibu'); });

    [$provInput, $kabInput, $kecInput, $kelInput].forEach(function($el){
        if (!$el || !$el.length) return;
        $el.on('focusin', function(){ $(this).data('before', normalizeWilayah(this.value)); });
        $el.on('blur', function(){ this.value = normalizeWilayah(this.value); });
    });

    $provInput.on('focus', function(){ loadProvinsi(); });
    $provInput.on('input', function(){ loadProvinsi(); });
    $provInput.on('change', function(){
        const current = normalizeWilayah(this.value);
        const before = $(this).data('before') || '';
        this.value = current;
        if (current === before) {
            return;
        }
        clearWilayahAfter('provinsi');
        if (current) {
            loadKabupaten(current);
        }
    });

    $kabInput.on('focus', function(){
        const prov = normalizeWilayah($provInput.val());
        if (!prov) return;
        loadKabupaten(prov);
    });
    $kabInput.on('change', function(){
        const current = normalizeWilayah(this.value);
        const before = $(this).data('before') || '';
        this.value = current;
        if (current === before) {
            return;
        }
        clearWilayahAfter('kabupaten');
        const prov = normalizeWilayah($provInput.val());
        if (prov && current) {
            loadKecamatan(prov, current);
        }
    });

    $kecInput.on('focus', function(){
        const prov = normalizeWilayah($provInput.val());
        const kab = normalizeWilayah($kabInput.val());
        if (!prov || !kab) return;
        loadKecamatan(prov, kab);
    });
    $kecInput.on('change', function(){
        const current = normalizeWilayah(this.value);
        const before = $(this).data('before') || '';
        this.value = current;
        if (current === before) {
            return;
        }
        clearWilayahAfter('kecamatan');
        const prov = normalizeWilayah($provInput.val());
        const kab = normalizeWilayah($kabInput.val());
        if (prov && kab && current) {
            loadKelurahan(prov, kab, current);
        }
    });

    $kelInput.on('focus', function(){
        const prov = normalizeWilayah($provInput.val());
        const kab = normalizeWilayah($kabInput.val());
        const kec = normalizeWilayah($kecInput.val());
        if (!prov || !kab || !kec) return;
        loadKelurahan(prov, kab, kec);
    });
    $kelInput.on('change', function(){
        this.value = normalizeWilayah(this.value);
        const prov = normalizeWilayah($provInput.val());
        const kab = normalizeWilayah($kabInput.val());
        const kec = normalizeWilayah($kecInput.val());
        fetchDetailAndSetKodePos(prov, kab, kec, this.value);
    });

    loadProvinsi();

    // ========== Quick Send WA (incomplete data) ==========
    $(document).on('click', '.btn-send-wa', function(){
        const btn = this;
        const id = btn.getAttribute('data-id');
        if (!id) return;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mengirim...';

        const formData = new FormData();
        formData.append('id_siswa', id);
        // Tidak mengirim pesan custom: biarkan server buat pesan detail otomatis

        fetch('kirim_wa_biodata.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        }).then(async (res) => {
            let data = null;
            try { data = await res.json(); } catch (e) {}
            if (!res.ok) {
                throw new Error((data && data.message) ? data.message : ('HTTP ' + res.status));
            }
            if (data && data.status === 'success') {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Terkirim';
                // tetap disabled setelah sukses
            } else {
                throw new Error(data && data.message ? data.message : 'Gagal mengirim');
            }
        }).catch((err) => {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-danger');
            btn.innerHTML = '<i class="bi bi-x-circle me-1"></i> Gagal, coba lagi';
            btn.disabled = false;
            // Opsional: tooltip/alert dapat ditambahkan jika diperlukan
        });
    });

    // ========== Batalkan Validasi ==========
    const unvModal = document.getElementById('unvalidateModal');
    const unvText = document.getElementById('unv-text');
    const unvAlert = document.getElementById('unv-alert');
    const unvId = document.getElementById('unv-id-siswa');
    const unvNama = document.getElementById('unv-nama');
    const unvSendBtn = document.getElementById('unv-send-btn');
    const unvSpinner = document.getElementById('unv-spinner');

    if (unvModal) {
        unvModal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            const id = btn.getAttribute('data-id');
            const nama = btn.getAttribute('data-nama') || '-';
            unvId.value = id;
            unvNama.value = nama;
            unvAlert.style.display = 'none';
            unvAlert.className = '';
            unvAlert.innerHTML = '';
            unvSendBtn.disabled = false;
            unvSpinner.classList.add('d-none');
            unvText.textContent = `Apakah Anda yakin membatalkan validasi data dari ${nama}?`;
        });

        unvSendBtn.addEventListener('click', function(){
            const id = unvId.value;
            if (!id) return;
            unvSendBtn.disabled = true;
            unvSpinner.classList.remove('d-none');
            unvAlert.style.display = 'none';
            unvAlert.className = '';
            unvAlert.innerHTML = '';

            const formData = new FormData();
            formData.append('id_siswa', id);
            formData.append('status', 'pending');
            // Biarkan server membuat pesan default pembatalan

            fetch('kirim_wa_biodata.php', { method: 'POST', body: formData, credentials: 'same-origin' })
                .then(async (res) => {
                    let data = null; try { data = await res.json(); } catch(e) {}
                    if (!res.ok) throw new Error((data && data.message) ? data.message : ('HTTP ' + res.status));
                    // sukses, reload untuk update tombol
                    window.location.reload();
                })
                .catch((err) => {
                    unvAlert.className = 'alert alert-danger';
                    unvAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i> ' + (err && err.message ? err.message : 'Terjadi kesalahan.');
                    unvAlert.style.display = 'block';
                })
                .finally(() => {
                    unvSpinner.classList.add('d-none');
                    unvSendBtn.disabled = false;
                });
        });
    }
});
</script>

<?php if ($refDapoAvailable): ?>
<script>
$(function(){
    const residuBtn = document.getElementById('btn-residu');
    if (!residuBtn) return;
    const residuContent = document.getElementById('residu-content');
    const residuInfo = document.getElementById('residu-info');
    const jsonEl = document.getElementById('residu-json');
    let data = [];
    try { data = JSON.parse(jsonEl.textContent || '[]'); } catch (e) { data = []; }

    $('#residuModal').on('show.bs.modal', function(){
        // Render list
        const total = data.length;
        residuInfo.textContent = total > 0 ? (total + ' siswa memiliki residu data.') : 'Tidak ada residu.';
        if (total === 0) {
            residuContent.innerHTML = '<div class="alert alert-success mb-0"><i class="bi bi-check-circle me-1"></i> Semua data sesuai dengan referensi Dapodik.</div>';
            return;
        }
        let html = '<div class="list-group">';
        data.forEach((item, idx) => {
            const rid = 'residu-item-' + idx;
            const header = $('<div>').text((item.nama || '-') + ' • ' + (item.kelas || '-')).html();
            html += '<div class="list-group-item">';
            html += '<div class="d-flex justify-content-between align-items-center">';
            html += '<div><strong>' + header + '</strong></div>';
            html += '<button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#'+rid+'">Lihat residu</button>';
            html += '</div>';
            html += '<div class="collapse mt-2" id="'+rid+'">';
            // Tombol Edit Biodata akan muncul setelah panel residu dibuka
            html += '<div class="mb-2">'
                 +   '<button type="button" class="btn btn-primary btn-sm btn-edit-siswa" data-id="' + (item.id || '') + '">'
                 +     '<i class="bi bi-pencil-square"></i> Edit biodata'
                 +   '</button>'
                 + '</div>';
            if (item.residu && item.residu.length) {
                html += '<ul class="mb-0">';
                item.residu.forEach(r => {
                    const lbl = $('<div>').text(r.label || '-').html();
                    const app = $('<div>').text(r.app || '').html();
                    const ref = $('<div>').text(r.dapodik || '').html();
                    html += '<li><strong>' + lbl + ':</strong> Aplikasi = <span class="text-danger">' + app + '</span>, Dapodik = <span class="text-success">' + ref + '</span></li>';
                });
                html += '</ul>';
            } else {
                html += '<div class="text-muted">Tidak ada detail residu.</div>';
            }
            html += '</div>';
            html += '</div>';
        });
        html += '</div>';
        residuContent.innerHTML = html;

        // Toggle teks tombol antara "Lihat residu" <-> "Tutup" saat panel dibuka/ditutup
        const $modal = $('#residuModal');
        const $collapses = $modal.find('.collapse');
        $collapses.off('shown.bs.collapse.residu hidden.bs.collapse.residu');
        $collapses.on('shown.bs.collapse.residu', function(){
            const selector = '#' + this.id;
            const $btn = $modal.find('button[data-bs-target="' + selector + '"]');
            $btn.text('Tutup').removeClass('btn-outline-primary').addClass('btn-primary');
        });
        $collapses.on('hidden.bs.collapse.residu', function(){
            const selector = '#' + this.id;
            const $btn = $modal.find('button[data-bs-target="' + selector + '"]');
            $btn.text('Lihat residu').removeClass('btn-primary').addClass('btn-outline-primary');
        });
    });
});
</script>
<?php endif; ?>

</body>
</html>
