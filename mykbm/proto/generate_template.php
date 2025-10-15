<?php
// Pastikan Anda telah menginstal PhpSpreadsheet melalui Composer
require __DIR__ . '/../../vendor/autoload.php'; // Path ini sudah Anda pastikan benar sebelumnya

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Worksheet\Protection; // Tambahkan ini untuk kelas Protection

// --- KONFIGURASI DATABASE dan FUNGSI ---
// Pastikan path ke file koneksi, function, dan crud Anda sudah benar
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/function.php';
require_once __DIR__ . '/../../config/crud.php';

global $setting, $koneksi;

// --- Ambil Parameter mapel dan level dari URL ---
$mapel_id_param_encrypted = isset($_GET['m']) ? $_GET['m'] : '';
$level_id_param_encrypted = isset($_GET['l']) ? $_GET['l'] : '';

$mapel_id_param = '';
$level_id_param = '';

if (function_exists('dekripsi')) {
    if (!empty($mapel_id_param_encrypted)) {
        $mapel_id_param = dekripsi($mapel_id_param_encrypted);
    }
    if (!empty($level_id_param_encrypted)) {
        $level_id_param = dekripsi($level_id_param_encrypted);
    }
} else {
    error_log("Fungsi dekripsi tidak ditemukan di generate_template.php. Pastikan 'config/function.php' dimuat.");
    $mapel_id_param = $mapel_id_param_encrypted;
    $level_id_param = $level_id_param_encrypted;
}

// --- Ambil Nama Mapel dan Jenjang dari Database (Untuk Header Template) ---
$mapel_name = "Mata Pelajaran (Tidak Ditemukan)";
$jenjang_name = !empty($level_id_param) ? $level_id_param : "Jenjang (Tidak Ditemukan)";

if (!empty($mapel_id_param) && function_exists('fetch')) {
    $mapel_data = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel_id_param]);
    if ($mapel_data && isset($mapel_data['nama_mapel'])) {
        $mapel_name = $mapel_data['nama_mapel'];
    }
}

// --- Ambil Data Lingkup Materi dari Database ---
$existing_lms = [];
$smt = isset($setting['semester']) ? $setting['semester'] : null;

if (!empty($mapel_id_param) && !empty($level_id_param) && !empty($smt)) {
    $stmt_lingkup = mysqli_prepare($koneksi, "SELECT materi FROM lingkup WHERE mapel=? AND level=? AND smt=? ORDER BY materi ASC");
    if ($stmt_lingkup) {
        mysqli_stmt_bind_param($stmt_lingkup, "sss", $mapel_id_param, $level_id_param, $smt);
        mysqli_stmt_execute($stmt_lingkup);
        $result_lingkup = mysqli_stmt_get_result($stmt_lingkup);
        while ($row = mysqli_fetch_assoc($result_lingkup)) {
            $existing_lms[] = $row['materi'];
        }
        mysqli_stmt_close($stmt_lingkup);
    } else {
        error_log("Failed to prepare lingkup statement: " . mysqli_error($koneksi));
    }
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Template TP');

// --- Bagian untuk Header Deskriptif ---
$sheet->setCellValue('A1', "Format Import TP $mapel_name Kelas $jenjang_name");
$sheet->mergeCells('A1:C1');
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', '');
$sheet->mergeCells('A2:C2');

// --- Bagian untuk Header Data ---
$sheet->setCellValue('A3', 'LM');
$sheet->setCellValue('B3', 'TP');
$sheet->setCellValue('C3', 'Tujuan Pembelajaran');

$sheet->getStyle('A3:C3')->getFont()->setBold(true);
$sheet->getStyle('A3:C3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFF2F2F2');

// Atur lebar kolom
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(60);

// --- Buat Sheet Tersembunyi untuk Daftar LM (Data Validation Source) ---
$lmListSheet = $spreadsheet->createSheet();
$lmListSheet->setTitle('LM_Options');
$lmListSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// Masukkan pilihan LM ke sheet tersembunyi
$rowNum = 1;
foreach ($existing_lms as $lm_item) {
    $lmListSheet->setCellValue('A' . $rowNum, $lm_item);
    $rowNum++;
}

// --- TERAPKAN PROTEKSI SHEET ---

// 1. Set semua sel menjadi unlocked secara default (penting agar hanya yang diinginkan terkunci nanti)
// Atau, lebih baik, biarkan semua sel locked secara default, lalu unlock hanya range data input
// Secara default, sel-sel di PhpSpreadsheet memiliki properti `locked` = true.
// Jadi kita hanya perlu meng-unlock range sel yang bisa diedit.

// Range sel yang bisa diedit (dimulai dari baris 4 ke bawah)
// Kolom A, B, dan C, dimulai dari baris 4 hingga baris 1000 (atau batas yang Anda inginkan)
$editableRange = 'A4:C30'; // Sesuaikan batas akhir baris jika perlu

// Set sel-sel dalam rentang yang dapat diedit menjadi 'unlocked'
$sheet->getStyle($editableRange)->getProtection()->setLocked(Protection::PROTECTION_UNLOCKED);

// 2. Aktifkan proteksi sheet
// Anda bisa menambahkan password jika ingin, atau biarkan kosong untuk tanpa password
$sheet->getProtection()->setSheet(true); // Mengunci seluruh sheet
// Opsional: set password (ganti 'password' dengan password yang Anda inginkan)
// $sheet->getProtection()->setPassword('your_password_here'); 

// Opsional: Izinkan pengguna untuk melakukan tindakan tertentu meskipun sheet terkunci
// Contoh: Mengizinkan seleksi sel terkunci dan tidak terkunci
$sheet->getProtection()->setSelectLockedCells(true);
$sheet->getProtection()->setSelectUnlockedCells(true);
// Anda juga bisa mengatur opsi lain seperti setFormatCells, setFormatColumns, setInsertRows, dll.

// --- Terapkan Data Validation (Dropdown) pada Kolom LM di Sheet Utama ---
// Penting: Data validation harus diterapkan SETELAH setting protection,
// atau setidaknya pastikan properti protection untuk sel yang divalidasi sudah benar.

$validation = $sheet->getCell('A4')->getDataValidation();
$validation->setType(DataValidation::TYPE_LIST);
$validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
$validation->setAllowBlank(true);
$validation->setShowInputMessage(true);
$validation->setShowErrorMessage(true);
$validation->setShowDropDown(true);
$validation->setErrorTitle('Input Error');
$validation->setError('Pilihan tidak valid. Silakan pilih dari daftar.');
$validation->setPromptTitle('Pilih Lingkup Materi');
$validation->setPrompt('Silakan pilih Lingkup Materi dari daftar atau ketik baru.');

$dataRange = 'LM_Options!$A$1:$A$' . (count($existing_lms) > 0 ? count($existing_lms) : 1);
$validation->setFormula1($dataRange);

// Terapkan validasi data ke seluruh kolom LM (misalnya, A4 hingga A1000)
for ($row = 4; $row <= 1000; $row++) {
    $sheet->getCell('A' . $row)->setDataValidation(clone $validation);
}


// Set sheet aktif kembali ke sheet utama
$spreadsheet->setActiveSheetIndex(0);

// --- Set Header untuk Pengunduhan File ---
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_' . $mapel_name . '_kelas' . $jenjang_name . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;