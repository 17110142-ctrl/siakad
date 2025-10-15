<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// Pastikan Anda telah menginstal PhpSpreadsheet melalui Composer
// Sesuaikan path ini dengan lokasi file autoload.php Anda
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

use PhpOffice\PhpSpreadsheet\IOFactory;

$pg = isset($_GET['pg']) ? $_GET['pg'] : '';

$defaultSmt = (string)$setting['semester'];
$smt = $defaultSmt;

$candidateSmt = null;
if (isset($_POST['smt'])) {
    $candidateSmt = trim((string)$_POST['smt']);
} elseif (isset($_GET['smt'])) {
    $candidateSmt = trim((string)$_GET['smt']);
}

if ($candidateSmt !== null && $candidateSmt !== '') {
    $validSmtValues = ['1', '2'];
    $smt = in_array($candidateSmt, $validSmtValues, true) ? $candidateSmt : $defaultSmt;
}

// --- Fungsi Helper untuk mendapatkan nomor LM yang tersedia ---
function getNextAvailableLm($koneksi, $mapel_id, $level, $semester_val) {
    $lm_used = [];
    $stmt = mysqli_prepare($koneksi, "SELECT lm FROM lingkup WHERE mapel=? AND level=? AND smt=? ORDER BY lm ASC");
    mysqli_stmt_bind_param($stmt, "sss", $mapel_id, $level, $semester_val);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $lm_used[] = (int)$row['lm'];
    }
    mysqli_stmt_close($stmt);

    $n = 1;
    while (in_array($n, $lm_used)) {
        $n++;
    }
    return $n;
}

// =================================================================
// BAGIAN UTAMA: PENANGANAN REQUEST
// =================================================================

// Set header default ke JSON, bisa di-override jika perlu
header('Content-Type: application/json');

if ($pg == 'lingkup') {
    $mapel = $_POST['mapel'];
    $level = $_POST['level'];
    $materiList = $_POST['materi'];

    // 1. Cek duplikasi di database
    $existing = [];
    $stmt_check_existing = mysqli_prepare($koneksi, "SELECT LOWER(materi) as materi FROM lingkup WHERE mapel=? AND level=? AND smt=?");
    mysqli_stmt_bind_param($stmt_check_existing, "sss", $mapel, $level, $smt);
    mysqli_stmt_execute($stmt_check_existing);
    $result_existing = mysqli_stmt_get_result($stmt_check_existing);
    while ($row = mysqli_fetch_assoc($result_existing)) {
        $existing[] = strtolower(trim($row['materi']));
    }
    mysqli_stmt_close($stmt_check_existing);

    foreach ($materiList as $materi) {
        $cleanMateri = strtolower(trim($materi));
        if (in_array($cleanMateri, $existing)) {
            echo json_encode(['status' => 'duplikat', 'materi' => $materi]);
            exit;
        }
    }

    // 2. Jika tidak ada duplikat, lakukan INSERT
    $stmt_insert = mysqli_prepare($koneksi, "INSERT INTO lingkup (mapel, level, materi, smt, lm) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt_insert) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyiapkan statement: ' . mysqli_error($koneksi)]);
        exit;
    }

    foreach ($materiList as $materi) {
        if (empty(trim($materi))) continue; // Lewati jika materi kosong
        
        $lm = getNextAvailableLm($koneksi, $mapel, $level, $smt);
        mysqli_stmt_bind_param($stmt_insert, "ssssi", $mapel, $level, $materi, $smt, $lm);
        
        if (!mysqli_stmt_execute($stmt_insert)) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan materi: ' . mysqli_stmt_error($stmt_insert)]);
            mysqli_stmt_close($stmt_insert);
            exit;
        }
    }

    mysqli_stmt_close($stmt_insert);
    echo json_encode(['status' => 'sukses']);
    exit;
}

elseif ($pg == 'edit_lingkup') {
    $id = $_POST['id'];
    $deskrip = $_POST['deskrip'];
    $stmt = mysqli_prepare($koneksi, "UPDATE lingkup SET materi=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "si", $deskrip, $id);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    if ($exec) {
        echo json_encode(['status' => 'sukses']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
    }
    exit;
}

elseif ($pg == 'hapus') {
    // Untuk hapus lingkup, kita tidak perlu header JSON karena AJAX di client mengharapkan teks biasa ('success'/'error')
    header('Content-Type: text/plain'); 
    $id = $_POST['id'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM lingkup WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $hapus = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo $hapus ? 'success' : 'error';
    exit;
}

// =================================================================
// BLOK UNTUK TUJUAN PEMBELAJARAN
// =================================================================

elseif ($pg == 'tuju') {
    $mapel = $_POST['mapel'];
    $level = $_POST['level'];
    $tujuan_desc = $_POST['tujuan'];
    $guru = $_POST['guru'];
    $tp_number = $_POST['tp'];
    $idlm_from_form = $_POST['lm'];

    // Ambil nomor LM dari tabel lingkup berdasarkan ID nya
    $lingkup = fetch($koneksi, 'lingkup', ['id' => $idlm_from_form]);
    if (!$lingkup) {
        echo json_encode(['status' => 'error', 'message' => 'Lingkup Materi (LM) tidak ditemukan.']);
        exit;
    }
    $lm_number_from_lingkup = $lingkup['lm'];

    $stmt = mysqli_prepare($koneksi, "INSERT INTO tujuan (mapel, level, tujuan, smt, idlm, lm, tp, guru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssiiss",
        $mapel,
        $level,
        $tujuan_desc,
        $smt,
        $idlm_from_form,
        $lm_number_from_lingkup,
        $tp_number,
        $guru
    );
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($exec) {
        echo json_encode(['status' => 'sukses']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data tujuan pembelajaran.']);
    }
    exit;
}

elseif ($pg == 'edit_tp') {
    $id = $_POST['id'];
    $deskrip = $_POST['deskrip'];
    $stmt = mysqli_prepare($koneksi, "UPDATE tujuan SET tujuan=? WHERE idt=?");
    mysqli_stmt_bind_param($stmt, "si", $deskrip, $id);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($exec) {
        echo json_encode(['status' => 'sukses']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
    }
    exit;
}

elseif ($pg == 'hapustp') {
    // Untuk hapus tujuan, kita tidak perlu header JSON karena AJAX di client mengharapkan teks biasa ('success'/'error')
    header('Content-Type: text/plain');
    $id = $_POST['id'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tujuan WHERE idt=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $exec = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo $exec ? 'success' : 'error';
    exit;
}

// =================================================================
// BLOK UNTUK IMPORT TUJUAN PEMBELAJARAN DARI EXCEL
// =================================================================
elseif ($pg == 'import_tp') {
    $response = [];

    if (!empty($_FILES['fileExcel']['name'])) {
        $fileName = basename($_FILES['fileExcel']['name']);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowedType = ['xls', 'xlsx'];
        if (!in_array(strtolower($fileType), $allowedType)) {
            echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak didukung. Hanya file .xls atau .xlsx yang diizinkan.']);
            exit();
        }

        $inputFileName = $_FILES['fileExcel']['tmp_name'];

        try {
            $spreadsheet = IOFactory::load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $mapel_id = $_POST['mapel'];
            $tingkat = $_POST['level'];
            $guru_id = $_POST['guru'];
            
            $imported_tp_count = 0;
            $failed_tp_count = 0;
            $new_lm_count = 0;

            $header = array_map('trim', array_values($sheetData[1]));
            $expected_header = ['LM', 'TP', 'Tujuan Pembelajaran'];

            if ($header !== $expected_header) {
                echo json_encode(['status' => 'error', 'message' => 'Format header file Excel tidak sesuai. Pastikan kolom adalah: LM, TP, Tujuan Pembelajaran.']);
                exit();
            }
            
            for ($i = 2; $i <= count($sheetData); $i++) {
                $row = array_values($sheetData[$i]);
                
                $lm_from_excel = trim($row[0] ?? '');
                $tp_from_excel = trim($row[1] ?? '');
                $tujuan_from_excel = trim($row[2] ?? '');

                if (empty($lm_from_excel) || empty($tp_from_excel) || empty($tujuan_from_excel)) {
                    $failed_tp_count++;
                    continue;
                }

                // Cek atau buat Lingkup Materi (LM)
                $id_lingkup = null;
                $lm_number_from_lingkup = null;

                $stmt_check_lingkup = mysqli_prepare($koneksi, "SELECT id, lm FROM lingkup WHERE materi=? AND mapel=? AND level=? AND smt=?");
                mysqli_stmt_bind_param($stmt_check_lingkup, "ssss", $lm_from_excel, $mapel_id, $tingkat, $smt);
                mysqli_stmt_execute($stmt_check_lingkup);
                $result_check_lingkup = mysqli_stmt_get_result($stmt_check_lingkup);
                $lingkup_data = mysqli_fetch_assoc($result_check_lingkup);
                mysqli_stmt_close($stmt_check_lingkup);

                if ($lingkup_data) {
                    $id_lingkup = $lingkup_data['id'];
                    $lm_number_from_lingkup = $lingkup_data['lm'];
                } else {
                    $new_lm_number = getNextAvailableLm($koneksi, $mapel_id, $tingkat, $smt);
                    
                    $stmt_insert_lingkup = mysqli_prepare($koneksi, "INSERT INTO lingkup (mapel, level, materi, smt, lm) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt_insert_lingkup, "ssssi", $mapel_id, $tingkat, $lm_from_excel, $smt, $new_lm_number);
                    
                    if (mysqli_stmt_execute($stmt_insert_lingkup)) {
                        $id_lingkup = mysqli_insert_id($koneksi);
                        $lm_number_from_lingkup = $new_lm_number;
                        $new_lm_count++;
                    } else {
                        $failed_tp_count++;
                        continue;
                    }
                    mysqli_stmt_close($stmt_insert_lingkup);
                }

                // Masukkan Tujuan Pembelajaran (TP)
                $stmt_insert_tujuan = mysqli_prepare($koneksi, "INSERT INTO tujuan (mapel, level, tujuan, smt, idlm, lm, tp, guru) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt_insert_tujuan, "ssssiiss",
                    $mapel_id, $tingkat, $tujuan_from_excel, $smt, $id_lingkup, $lm_number_from_lingkup, $tp_from_excel, $guru_id
                );

                if (mysqli_stmt_execute($stmt_insert_tujuan)) {
                    $imported_tp_count++;
                } else {
                    $failed_tp_count++;
                }
                mysqli_stmt_close($stmt_insert_tujuan);
            }

            $response_message = "$imported_tp_count data TP berhasil diimpor.";
            if ($new_lm_count > 0) $response_message .= " ($new_lm_count LM baru ditambahkan).";
            if ($failed_tp_count > 0) $response_message .= " $failed_tp_count data gagal diimpor.";
            
            echo json_encode(['status' => 'success', 'message' => $response_message]);

        } catch (\Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang diunggah.']);
    }
    exit();
}

elseif ($pg == 'ambil_tp') {
    header('Content-Type: text/html; charset=utf-8');
    $idlmRaw = $_POST['kd'] ?? '';
    $idlm = (int)$idlmRaw;

    if ($idlm <= 0) {
        echo '<div class="alert alert-warning w-100">Lingkup materi tidak valid.</div>';
        exit;
    }

    $lingkupRow = fetch($koneksi, 'lingkup', ['id' => $idlm]);
    if (!$lingkupRow) {
        echo '<div class="alert alert-warning w-100">Lingkup materi tidak ditemukan.</div>';
        exit;
    }

    $targetSmt = (string)($lingkupRow['smt'] ?? '');
    $stmtTp = null;
    if ($targetSmt !== '') {
        $stmtTp = mysqli_prepare($koneksi, "SELECT tp, tujuan FROM tujuan WHERE idlm = ? AND smt = ? ORDER BY tp ASC");
        if ($stmtTp) {
            mysqli_stmt_bind_param($stmtTp, "is", $idlm, $targetSmt);
        }
    } else {
        $stmtTp = mysqli_prepare($koneksi, "SELECT tp, tujuan FROM tujuan WHERE idlm = ? ORDER BY tp ASC");
        if ($stmtTp) {
            mysqli_stmt_bind_param($stmtTp, "i", $idlm);
        }
    }

    if (!$stmtTp) {
        echo '<div class="alert alert-danger w-100">Gagal menyiapkan data tujuan pembelajaran.</div>';
        exit;
    }

    mysqli_stmt_execute($stmtTp);
    $resultTp = mysqli_stmt_get_result($stmtTp);
    $tujuanRows = [];
    if ($resultTp) {
        while ($row = mysqli_fetch_assoc($resultTp)) {
            $tujuanRows[] = $row;
        }
    }
    mysqli_stmt_close($stmtTp);

    if (empty($tujuanRows)) {
        echo '<div class="alert alert-warning w-100">Belum ada tujuan pembelajaran yang terdaftar untuk materi ini.</div>';
        exit;
    }

    echo '<div class="tp-checkbox-list">';
    foreach ($tujuanRows as $index => $row) {
        $tpCode = trim((string)($row['tp'] ?? ''));
        $tpText = trim((string)($row['tujuan'] ?? ''));
        if ($tpCode === '' && $tpText === '') {
            continue;
        }
        $valueSource = $tpText !== '' ? $tpText : ($tpCode !== '' ? $tpCode : '');
        $value = htmlspecialchars($valueSource, ENT_QUOTES, 'UTF-8');
        $labelParts = [];
        if ($tpCode !== '') {
            $labelParts[] = htmlspecialchars($tpCode, ENT_QUOTES, 'UTF-8');
        }
        if ($tpText !== '') {
            $labelParts[] = htmlspecialchars($tpText, ENT_QUOTES, 'UTF-8');
        }
        $label = $labelParts ? implode(' - ', $labelParts) : htmlspecialchars($valueSource, ENT_QUOTES, 'UTF-8');
        $inputId = 'tp_' . $idlm . '_' . ($index + 1);
        $requiredAttr = $index === 0 ? ' required' : '';
        echo '<div class="form-check">';
        echo '<input class="form-check-input" type="checkbox" name="tp[]" id="' . $inputId . '" value="' . $value . '"' . $requiredAttr . '>';
        echo '<label class="form-check-label" for="' . $inputId . '">' . $label . '</label>';
        echo '</div>';
    }
    echo '</div>';
    echo '<small class="text-muted d-block mt-1">Centang tujuan pembelajaran yang relevan (boleh lebih dari satu).</small>';
    echo '<script>
    (function(){
        var container = document.getElementById("tp");
        if (!container) { return; }
        var checkboxes = container.querySelectorAll(\'input[type="checkbox"][name="tp[]"]\');
        if (!checkboxes.length) { return; }
        function updateRequirement() {
            var anyChecked = Array.prototype.some.call(checkboxes, function(cb){ return cb.checked; });
            checkboxes[0].required = !anyChecked;
        }
        Array.prototype.forEach.call(checkboxes, function(cb){
            cb.addEventListener("change", updateRequirement);
        });
        updateRequirement();
    })();
    </script>';
    exit;
}

else {
    // Jika tidak ada 'pg' yang cocok
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid atau tidak ditemukan.']);
    exit;
}
?>
