<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require "../../config/function.php";
require "../../config/crud.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Throwable;

function json_exit(string $status, string $message, array $extra = []): void {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

function fetch_siswa_by_nis(mysqli $db, string $nis): ?array {
    $stmt = $db->prepare("SELECT id_siswa, kelas FROM siswa WHERE nis = ? LIMIT 1");
    $stmt->bind_param('s', $nis);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

$level_pengguna = $_SESSION['level'] ?? '';
$id_pengguna    = $_SESSION['id_user'] ?? 0;

$semester = $_POST['semester'] ?? '';
$tapel    = $_POST['tp'] ?? '';

if ($semester === '' || $tapel === '') {
    $setting_row = fetch($koneksi, 'setting', ['id_setting' => 1]);
    if ($setting_row) {
        $semester = $semester !== '' ? $semester : ($setting_row['semester'] ?? '');
        $tapel    = $tapel    !== '' ? $tapel    : ($setting_row['tp'] ?? '');
    }
}

$semester = trim((string)$semester);
$tapel    = trim((string)$tapel);

if (isset($_POST['nilai_sts']) && is_array($_POST['nilai_sts'])) {
    $mapel   = isset($_POST['mapel']) ? (int)$_POST['mapel'] : 0;
    $kelas   = $_POST['kelas'] ?? '';
    $guru    = isset($_POST['guru']) ? (int)$_POST['guru'] : 0;
    $nilai   = $_POST['nilai_sts'];
    $tpTercapaiRaw = $_POST['tp_tercapai'] ?? [];
    $tpKurangRaw   = $_POST['tp_kurang'] ?? [];
    $ket     = 'LMAS';
    $khp     = 'STS';

    if ($mapel === 0) {
        json_exit('error', 'Mapel belum dipilih.');
    }

    $stmtCek = $koneksi->prepare("SELECT id, khp FROM nilai_sts WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tp = ? LIMIT 1");
    $stmtInsert = $koneksi->prepare("INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_sts, ket, khp, guru, semester, tp) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmtUpdate = $koneksi->prepare("UPDATE nilai_sts SET idsiswa = ?, nis = ?, kelas = ?, nilai_sts = ?, ket = ?, khp = ?, guru = ? WHERE id = ?");
    $stmtDescDelete = $koneksi->prepare("DELETE FROM nilai_formatif WHERE nis = ? AND mapel = ? AND kelas = ?");
    $stmtDescInsert = $koneksi->prepare("INSERT INTO nilai_formatif (nis, kelas, mapel, tinggi, rendah) VALUES (?,?,?,?,?)");
    $mapelStr = (string)$mapel;
    $descUpdateCount = 0;

    $inserted = 0;
    $updated  = 0;
    $skipped  = [];

    $normalizeChoices = function ($value): string {
        if (is_array($value)) {
            $clean = [];
            foreach ($value as $item) {
                $item = trim((string)$item);
                if ($item !== '') {
                    $clean[] = $item;
                }
            }
            return $clean ? implode('||', $clean) : '';
        }
        $value = trim((string)$value);
        return $value;
    };

    $descDeleteReady = $stmtDescDelete instanceof mysqli_stmt;
    $descInsertReady = $stmtDescInsert instanceof mysqli_stmt;

    if ($descDeleteReady) {
        $descDelNis = $descDelMapel = $descDelKelas = '';
        $stmtDescDelete->bind_param('sss', $descDelNis, $descDelMapel, $descDelKelas);
    }
    if ($descInsertReady) {
        $descInsNis = $descInsKelas = $descInsMapel = $descInsTinggi = $descInsRendah = '';
        $stmtDescInsert->bind_param('sssss', $descInsNis, $descInsKelas, $descInsMapel, $descInsTinggi, $descInsRendah);
    }

    foreach ($nilai as $nis => $nilai_input) {
        $nis = trim((string)$nis);
        if ($nis === '') {
            continue;
        }

        if (!is_numeric($nilai_input)) {
            $skipped[] = $nis . ' (nilai tidak valid)';
            continue;
        }
        $nilai_bulat = (int)round($nilai_input);
        $nilai_bulat = max(0, min(100, $nilai_bulat));

        $siswa = fetch_siswa_by_nis($koneksi, $nis);
        if (!$siswa) {
            $skipped[] = $nis . ' (siswa tidak ditemukan)';
            continue;
        }

        $kelas_siswa = $kelas !== '' ? $kelas : ($siswa['kelas'] ?? '');
        $idsiswa     = (int)$siswa['id_siswa'];

        $kelas_desc = $kelas_siswa !== '' ? $kelas_siswa : ($siswa['kelas'] ?? '');
        $deskripsi_tinggi = $normalizeChoices($tpTercapaiRaw[$nis] ?? '');
        $deskripsi_rendah = $normalizeChoices($tpKurangRaw[$nis] ?? '');

        if ($descDeleteReady && $kelas_desc !== '') {
            $descDelNis = $nis;
            $descDelMapel = $mapelStr;
            $descDelKelas = $kelas_desc;
            if (!$stmtDescDelete->execute()) {
                $skipped[] = $nis . ' (gagal menghapus deskripsi lama: ' . $stmtDescDelete->error . ')';
            }
        } elseif (($deskripsi_tinggi !== '' || $deskripsi_rendah !== '') && !$descDeleteReady) {
            $skipped[] = $nis . ' (gagal menyiapkan penghapusan deskripsi lama)';
        } elseif ($kelas_desc === '' && ($deskripsi_tinggi !== '' || $deskripsi_rendah !== '')) {
            $skipped[] = $nis . ' (kelas siswa tidak ditemukan saat menghapus deskripsi lama)';
        }

        if ($descInsertReady && $kelas_desc !== '' && ($deskripsi_tinggi !== '' || $deskripsi_rendah !== '')) {
            $descInsNis = $nis;
            $descInsKelas = $kelas_desc;
            $descInsMapel = $mapelStr;
            $descInsTinggi = $deskripsi_tinggi;
            $descInsRendah = $deskripsi_rendah;
            if ($stmtDescInsert->execute()) {
                $descUpdateCount++;
            } else {
                $skipped[] = $nis . ' (gagal menyimpan deskripsi: ' . $stmtDescInsert->error . ')';
            }
        } elseif (($deskripsi_tinggi !== '' || $deskripsi_rendah !== '') && !$descInsertReady) {
            $skipped[] = $nis . ' (gagal menyiapkan penyimpanan deskripsi)';
        } elseif ($kelas_desc === '' && ($deskripsi_tinggi !== '' || $deskripsi_rendah !== '')) {
            $skipped[] = $nis . ' (kelas siswa tidak ditemukan untuk menyimpan deskripsi)';
        }

        $stmtCek->bind_param('iiss', $idsiswa, $mapel, $semester, $tapel);
        $stmtCek->execute();
        $stmtCek->store_result();

        if ($stmtCek->num_rows > 0) {
            $stmtCek->bind_result($id_existing, $khp_existing);
            $stmtCek->fetch();
            $khp_update = $khp_existing === 'STS' ? $khp_existing : $khp;
            $stmtUpdate->bind_param('ississii', $idsiswa, $nis, $kelas_siswa, $nilai_bulat, $ket, $khp_update, $guru, $id_existing);
            if ($stmtUpdate->execute()) {
                $updated++;
            } else {
                $skipped[] = $nis . ' (gagal memperbarui: ' . $stmtUpdate->error . ')';
            }
        } else {
            $stmtInsert->bind_param('issiississ', $idsiswa, $nis, $kelas_siswa, $mapel, $nilai_bulat, $ket, $khp, $guru, $semester, $tapel);
            if ($stmtInsert->execute()) {
                $inserted++;
            } else {
                $skipped[] = $nis . ' (gagal menyimpan: ' . $stmtInsert->error . ')';
            }
        }
        $stmtCek->free_result();
    }

    $stmtCek->close();
    $stmtInsert->close();
    $stmtUpdate->close();
    if ($descDeleteReady) {
        $stmtDescDelete->close();
    }
    if ($descInsertReady) {
        $stmtDescInsert->close();
    }

    $message = 'Nilai STS berhasil disimpan.';
    $summary = ['inserted' => $inserted, 'updated' => $updated];
    if ($descUpdateCount > 0) {
        $summary['deskripsi'] = $descUpdateCount;
    }
    if (!empty($skipped)) {
        $summary['skipped'] = $skipped;
    }

    json_exit('ok', $message, $summary);
}

if (!empty($_FILES['file']['name'])) {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        json_exit('error', 'Terjadi kesalahan saat mengunggah file.');
    }

    $extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, ['xls', 'xlsx'], true)) {
        json_exit('error', 'Format file tidak didukung. Unggah file XLS atau XLSX.');
    }

    $scope  = $_POST['import_scope'] ?? 'per_mapel';
    $mapel  = isset($_POST['mapel']) ? (int)$_POST['mapel'] : 0;
    $kelas  = trim((string)($_POST['kelas'] ?? ''));
    $guru   = isset($_POST['guru']) ? (int)$_POST['guru'] : 0;
    $ket    = 'LMAS';
    $khp    = 'STS';

    if ($level_pengguna !== 'admin' && $level_pengguna !== 'kurikulum') {
        $scope = 'per_mapel';
    }

    if ($scope === 'per_mapel' && $mapel === 0) {
        json_exit('error', 'Pilih mapel sebelum melakukan import.');
    }

    if ($scope === 'semua_mapel' && $kelas === '') {
        json_exit('error', 'Pilih kelas sebelum melakukan import semua mapel.');
    }

    $tingkat_import = trim((string)($_POST['level'] ?? ''));
    if ($tingkat_import === '' && $kelas !== '') {
        $kelasLevelEsc = mysqli_real_escape_string($koneksi, $kelas);
        $kelasInfo = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT level FROM kelas WHERE kelas='$kelasLevelEsc' LIMIT 1"));
        if ($kelasInfo && !empty($kelasInfo['level'])) {
            $tingkat_import = $kelasInfo['level'];
        }
    }

    if ($level_pengguna === 'guru' && $mapel !== 0) {
        $stmtValidasiMapel = $koneksi->prepare("SELECT 1 FROM jadwal_mapel WHERE guru = ? AND mapel = ? LIMIT 1");
        $stmtValidasiMapel->bind_param('ii', $id_pengguna, $mapel);
        $stmtValidasiMapel->execute();
        $stmtValidasiMapel->store_result();
        if ($stmtValidasiMapel->num_rows === 0) {
            $stmtValidasiMapel->close();
            json_exit('error', 'Anda tidak memiliki hak untuk mengimpor nilai pada mapel ini.');
        }
        $stmtValidasiMapel->close();
    }

    $reader = $extension === 'xls' ? new Xls() : new Xlsx();
    try {
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
    } catch (Throwable $th) {
        json_exit('error', 'Gagal membaca file: ' . $th->getMessage());
    }

    $sheet = $spreadsheet->getActiveSheet();
    $highestColumnIndex = Coordinate::columnIndexFromString($sheet->getHighestColumn());
    $highestRow = $sheet->getHighestRow();

    $mapelColumns = [];
    $mapelColumnWarnings = [];
    $columnLetters = [];
    $tpColumns = [];
    $validationColumns = [];
    $tpMapelSet = [];
    $stmtMapelLookup = $koneksi->prepare("SELECT id FROM mata_pelajaran WHERE UPPER(kode)=? OR UPPER(nama_mapel)=? LIMIT 1");
    for ($col = 4; $col <= $highestColumnIndex; $col++) {
        $colLetter = Coordinate::stringFromColumnIndex($col);
        $metaCellRaw = $sheet->getCell($colLetter . '4')->getValue();
        $mapelIdCell = trim((string)$metaCellRaw);
        if ($mapelIdCell !== '') {
            if (stripos($mapelIdCell, 'TP|') === 0) {
                $parts = explode('|', $mapelIdCell);
                if (count($parts) >= 3) {
                    $mapelIdTp = (int)$parts[1];
                    $tpCode = $parts[2];
                    $tpColumns[$colLetter] = [
                        'mapel' => $mapelIdTp,
                        'code'  => $tpCode
                    ];
                    if ($mapelIdTp > 0) {
                        $tpMapelSet[$mapelIdTp] = true;
                    }
                    continue;
                }
            }
            if (strtoupper($mapelIdCell) === 'VALIDASI') {
                $validationColumns[] = $colLetter;
                continue;
            }
        }
        $columnLetters[] = $colLetter;
        if ($mapelIdCell === '') {
            $mapelColumns[$colLetter] = null;
            continue;
        }
        if (is_numeric($mapelIdCell)) {
            $mapelId = (int)$mapelIdCell;
            if ($mapelId > 0) {
                $mapelColumns[$colLetter] = $mapelId;
            } else {
                $mapelColumnWarnings[] = "Kolom $colLetter memiliki ID mapel tidak valid ($mapelIdCell)";
                $mapelColumns[$colLetter] = null;
            }
        } else {
            $upper = strtoupper($mapelIdCell);
            $stmtMapelLookup->bind_param('ss', $upper, $upper);
            $stmtMapelLookup->execute();
            $resLookup = $stmtMapelLookup->get_result();
            $rowLookup = $resLookup ? $resLookup->fetch_assoc() : null;
            if ($resLookup) { $resLookup->free(); }
            if ($rowLookup && !empty($rowLookup['id'])) {
                $mapelColumns[$colLetter] = (int)$rowLookup['id'];
            } else {
                $mapelColumnWarnings[] = "Kolom $colLetter menggunakan nilai non-numerik ($mapelIdCell)";
                $mapelColumns[$colLetter] = null;
            }
        }
    }

    // Fallback: isi kolom kosong mengikuti urutan mapel rapor kelas
    $raporMapels = [];
    if (!empty($kelas)) {
        $kelasEsc = mysqli_real_escape_string($koneksi, $kelas);
        $kelasRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT level, jurusan FROM kelas WHERE kelas='$kelasEsc' LIMIT 1"));
        $kelasLevel = $kelasRow['level'] ?? '';
        $kelasJur   = $kelasRow['jurusan'] ?? '';
        if ($kelasJur === '' || $kelasJur === null) {
            $kelasJur = 'semua';
        }
        if ($kelasLevel !== '') {
            $levelEsc = mysqli_real_escape_string($koneksi, $kelasLevel);
            $jurEsc   = mysqli_real_escape_string($koneksi, $kelasJur);
            $sqlOrder = "SELECT mapel FROM mapel_rapor WHERE kurikulum='2' AND tingkat='$levelEsc' AND (pk='$jurEsc' OR pk='semua' OR pk='' OR pk IS NULL) ORDER BY urut";
            $resOrder = mysqli_query($koneksi, $sqlOrder);
            while ($row = mysqli_fetch_assoc($resOrder)) {
                $raporMapels[] = (int)$row['mapel'];
            }
        }
    }

    if (empty($raporMapels) && !empty($kelas)) {
        $kelasEsc = mysqli_real_escape_string($koneksi, $kelas);
        $resOrder = mysqli_query($koneksi, "SELECT jm.mapel FROM jadwal_mapel jm WHERE jm.kelas='$kelasEsc' GROUP BY jm.mapel ORDER BY jm.mapel");
        while ($row = mysqli_fetch_assoc($resOrder)) {
            $raporMapels[] = (int)$row['mapel'];
        }
    }

    if ($scope === 'per_mapel' && $mapel > 0) {
        $raporMapels = [$mapel];
    }

    if (!empty($raporMapels)) {
        $recognized = array_filter($mapelColumns);
        $missing = array_values(array_diff($raporMapels, $recognized));
        $missingIndex = 0;
        foreach ($columnLetters as $letter) {
            if (empty($mapelColumns[$letter])) {
                if ($scope === 'per_mapel' && $mapel > 0) {
                    $mapelColumns[$letter] = $mapel;
                } elseif ($missingIndex < count($missing)) {
                    $mapelColumns[$letter] = $missing[$missingIndex++];
                }
            }
        }
    }

    $tpDetailsByMapel = [];
    if (!empty($tpMapelSet)) {
        foreach (array_keys($tpMapelSet) as $tpMapelId) {
            if (!$tpMapelId) {
                continue;
            }
            $mapelEscTp = mysqli_real_escape_string($koneksi, (string)$tpMapelId);
            $sqlTpLookup = "SELECT tp, tujuan FROM tujuan WHERE mapel='$mapelEscTp'";
            if ($tingkat_import !== '') {
                $levelEscTp = mysqli_real_escape_string($koneksi, (string)$tingkat_import);
                $sqlTpLookup .= " AND level='$levelEscTp'";
            }
            if ($semester !== '') {
                $semesterEscTp = mysqli_real_escape_string($koneksi, (string)$semester);
                $sqlTpLookup .= " AND smt='$semesterEscTp'";
            }
            $sqlTpLookup .= " ORDER BY tp ASC";
            $tpDetailsByMapel[$tpMapelId] = [];
            if ($tpLookupRes = mysqli_query($koneksi, $sqlTpLookup)) {
                while ($tpRow = mysqli_fetch_assoc($tpLookupRes)) {
                    $code = trim((string)($tpRow['tp'] ?? ''));
                    if ($code === '') {
                        continue;
                    }
                    $tpDetailsByMapel[$tpMapelId][$code] = trim((string)($tpRow['tujuan'] ?? ''));
                }
                mysqli_free_result($tpLookupRes);
            }
            if (empty($tpDetailsByMapel[$tpMapelId])) {
                $fallbackRes = mysqli_query($koneksi, "SELECT tp, tujuan FROM tujuan WHERE mapel='$mapelEscTp' ORDER BY tp ASC");
                if ($fallbackRes) {
                    while ($tpRow = mysqli_fetch_assoc($fallbackRes)) {
                        $code = trim((string)($tpRow['tp'] ?? ''));
                        if ($code === '') {
                            continue;
                        }
                        if (!isset($tpDetailsByMapel[$tpMapelId][$code]) || $tpDetailsByMapel[$tpMapelId][$code] === '') {
                            $tpDetailsByMapel[$tpMapelId][$code] = trim((string)($tpRow['tujuan'] ?? ''));
                        }
                    }
                    mysqli_free_result($fallbackRes);
                }
            }
        }
    }

    if (empty($mapelColumns)) {
        $message = 'Kolom mapel tidak ditemukan pada file.';
        if (!empty($mapelColumnWarnings)) {
            $message .= ' ' . implode(' ', $mapelColumnWarnings);
        }
        json_exit('error', $message);
    }

    $stmtCek   = $koneksi->prepare("SELECT id, khp FROM nilai_sts WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tp = ? LIMIT 1");
    $stmtInsert = $koneksi->prepare("INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_sts, ket, khp, guru, semester, tp) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmtUpdate = $koneksi->prepare("UPDATE nilai_sts SET idsiswa = ?, nis = ?, kelas = ?, nilai_sts = ?, ket = ?, khp = ?, guru = ? WHERE id = ?");
    $stmtGuru   = $koneksi->prepare("SELECT guru FROM jadwal_mapel WHERE mapel = ? AND kelas = ? LIMIT 1");

    $stmtDescDelete = $koneksi->prepare("DELETE FROM nilai_formatif WHERE nis = ? AND mapel = ? AND kelas = ?");
    $stmtDescInsert = $koneksi->prepare("INSERT INTO nilai_formatif (nis, kelas, mapel, tinggi, rendah) VALUES (?,?,?,?,?)");
    $descDeleteReady = $stmtDescDelete instanceof mysqli_stmt;
    $descInsertReady = $stmtDescInsert instanceof mysqli_stmt;
    if ($descDeleteReady) {
        $descDelNis = $descDelMapel = $descDelKelas = '';
        $stmtDescDelete->bind_param('sss', $descDelNis, $descDelMapel, $descDelKelas);
    }
    if ($descInsertReady) {
        $descInsNis = $descInsKelas = $descInsMapel = $descInsTinggi = $descInsRendah = '';
        $stmtDescInsert->bind_param('sssss', $descInsNis, $descInsKelas, $descInsMapel, $descInsTinggi, $descInsRendah);
    }
    $descUpdateCount = 0;

    $inserted = 0;
    $updated  = 0;
    $skipped  = [];

    for ($row = 5; $row <= $highestRow; $row++) {
        $nis = trim((string)$sheet->getCell('B' . $row)->getValue());
        if ($nis === '') {
            continue;
        }

        $siswa = fetch_siswa_by_nis($koneksi, $nis);
        if (!$siswa) {
            $skipped[] = $nis . ' (siswa tidak ditemukan)';
            continue;
        }
        $idsiswa = (int)$siswa['id_siswa'];
        $kelasRow = $kelas !== '' ? $kelas : ($siswa['kelas'] ?? '');
        $tpStatusByMapel = [];

        foreach ($mapelColumns as $colLetter => $mapelId) {
            if (empty($mapelId)) {
                continue;
            }
            if ($scope === 'per_mapel' && $mapel !== 0 && $mapelId !== $mapel) {
                continue;
            }

            $nilaiRaw = $sheet->getCell($colLetter . $row)->getCalculatedValue();
            if ($nilaiRaw === null || $nilaiRaw === '') {
                continue;
            }
            if (!is_numeric($nilaiRaw)) {
                $skipped[] = $nis . ' (nilai tidak valid)';
                continue;
            }
            $nilaiBulat = (int)round($nilaiRaw);
            $nilaiBulat = max(0, min(100, $nilaiBulat));

            $guruRow = $guru;
            if ($scope === 'semua_mapel' || $guruRow === 0) {
                $kelasGuru = $kelasRow !== '' ? $kelasRow : ($siswa['kelas'] ?? '');
                if ($kelasGuru !== '') {
                    $stmtGuru->bind_param('is', $mapelId, $kelasGuru);
                    $stmtGuru->execute();
                    $resGuru = $stmtGuru->get_result();
                    if ($resGuru) {
                        $rowGuru = $resGuru->fetch_assoc();
                        if ($rowGuru && !empty($rowGuru['guru'])) {
                            $guruRow = (int)$rowGuru['guru'];
                        }
                        $resGuru->free();
                    }
                }
            }

            $stmtCek->bind_param('iiss', $idsiswa, $mapelId, $semester, $tapel);
            $stmtCek->execute();
            $stmtCek->store_result();

            if ($stmtCek->num_rows > 0) {
                $stmtCek->bind_result($idExisting, $khpExisting);
                $stmtCek->fetch();
                $khpUpdate = $khpExisting === 'STS' ? $khpExisting : $khp;
                $stmtUpdate->bind_param('ississii', $idsiswa, $nis, $kelasRow, $nilaiBulat, $ket, $khpUpdate, $guruRow, $idExisting);
                if ($stmtUpdate->execute()) {
                    $updated++;
                } else {
                    $skipped[] = $nis . ' - ' . $mapelId . ' (gagal memperbarui: ' . $stmtUpdate->error . ')';
                }
            } else {
                $stmtInsert->bind_param('issiississ', $idsiswa, $nis, $kelasRow, $mapelId, $nilaiBulat, $ket, $khp, $guruRow, $semester, $tapel);
                if ($stmtInsert->execute()) {
                    $inserted++;
                } else {
                    $skipped[] = $nis . ' - ' . $mapelId . ' (gagal menyimpan: ' . $stmtInsert->error . ')';
                }
            }
            $stmtCek->free_result();
        }

        if (!empty($tpColumns)) {
            foreach ($tpColumns as $colLetter => $tpMeta) {
                $mapelIdTp = (int)($tpMeta['mapel'] ?? 0);
                if ($mapelIdTp <= 0) {
                    continue;
                }
                if ($scope === 'per_mapel' && $mapel !== 0 && $mapelIdTp !== $mapel) {
                    continue;
                }
                $tpCode = (string)($tpMeta['code'] ?? '');
                $cellVal = trim((string)$sheet->getCell($colLetter . $row)->getValue());
                if (!isset($tpStatusByMapel[$mapelIdTp])) {
                    $tpStatusByMapel[$mapelIdTp] = [
                        'true' => [],
                        'false' => [],
                        'missing' => [],
                        'invalid' => []
                    ];
                }
                if ($cellVal === '') {
                    $tpStatusByMapel[$mapelIdTp]['missing'][] = $tpCode;
                    continue;
                }
                $normalized = strtoupper($cellVal);
                if ($normalized === 'TRUE') {
                    $tpStatusByMapel[$mapelIdTp]['true'][] = $tpCode;
                } elseif ($normalized === 'FALSE') {
                    $tpStatusByMapel[$mapelIdTp]['false'][] = $tpCode;
                } else {
                    $tpStatusByMapel[$mapelIdTp]['invalid'][] = $tpCode;
                }
            }
        }

        if (!empty($tpStatusByMapel)) {
            foreach ($tpStatusByMapel as $mapelIdTp => $statusSet) {
                if (!$descDeleteReady) {
                    $skipped[] = $nis . ' (gagal menyiapkan penghapusan deskripsi mapel ' . $mapelIdTp . ')';
                    continue;
                }
                if ($kelasRow === '') {
                    $skipped[] = $nis . ' (kelas siswa tidak ditemukan untuk deskripsi mapel ' . $mapelIdTp . ')';
                    continue;
                }
                $descDelNis = $nis;
                $descDelMapel = (string)$mapelIdTp;
                $descDelKelas = $kelasRow;
                if (!$stmtDescDelete->execute()) {
                    $skipped[] = $nis . ' (gagal menghapus deskripsi lama mapel ' . $mapelIdTp . ': ' . $stmtDescDelete->error . ')';
                    continue;
                }
                $tpLookup = $tpDetailsByMapel[$mapelIdTp] ?? [];
                $trueTexts = [];
                foreach ($statusSet['true'] as $tpCode) {
                    $trueTexts[] = $tpLookup[$tpCode] ?? $tpCode;
                }
                $falseTexts = [];
                foreach ($statusSet['false'] as $tpCode) {
                    $falseTexts[] = $tpLookup[$tpCode] ?? $tpCode;
                }
                $trueTexts = array_values(array_filter($trueTexts, static function($item){
                    return trim((string)$item) !== '';
                }));
                $falseTexts = array_values(array_filter($falseTexts, static function($item){
                    return trim((string)$item) !== '';
                }));
                if (!empty($trueTexts) || !empty($falseTexts)) {
                    if ($descInsertReady) {
                        $descInsNis = $nis;
                        $descInsKelas = $kelasRow;
                        $descInsMapel = (string)$mapelIdTp;
                        $descInsTinggi = implode('||', $trueTexts);
                        $descInsRendah = implode('||', $falseTexts);
                        if ($stmtDescInsert->execute()) {
                            $descUpdateCount++;
                        } else {
                            $skipped[] = $nis . ' (gagal menyimpan deskripsi mapel ' . $mapelIdTp . ': ' . $stmtDescInsert->error . ')';
                        }
                    } else {
                        $skipped[] = $nis . ' (gagal menyiapkan penyimpanan deskripsi mapel ' . $mapelIdTp . ')';
                    }
                }
                if (!empty($statusSet['missing']) || !empty($statusSet['invalid'])) {
                    $issues = [];
                    if (!empty($statusSet['missing'])) {
                        $issues[] = 'TP ' . implode(', ', $statusSet['missing']) . ' kosong';
                    }
                    if (!empty($statusSet['invalid'])) {
                        $issues[] = 'TP ' . implode(', ', $statusSet['invalid']) . ' tidak valid';
                    }
                    if (!empty($issues)) {
                        $skipped[] = $nis . ' (mapel ' . $mapelIdTp . ': ' . implode('; ', $issues) . ')';
                    }
                }
            }
        }
    }

    $stmtCek->close();
    $stmtInsert->close();
    $stmtUpdate->close();
    if ($descDeleteReady) {
        $stmtDescDelete->close();
    }
    if ($descInsertReady) {
        $stmtDescInsert->close();
    }
    $stmtGuru->close();

    $message = 'Import nilai STS selesai.';
    $summary = ['inserted' => $inserted, 'updated' => $updated];
    if ($descUpdateCount > 0) {
        $summary['deskripsi'] = $descUpdateCount;
    }
    if (!empty($skipped)) {
        $summary['skipped'] = $skipped;
    }
    if (!empty($mapelColumnWarnings)) {
        $summary['warnings'] = $mapelColumnWarnings;
    }

    if (isset($stmtMapelLookup)) {
        $stmtMapelLookup->close();
    }

    json_exit('ok', $message, $summary);
}

json_exit('error', 'Permintaan tidak valid.');
