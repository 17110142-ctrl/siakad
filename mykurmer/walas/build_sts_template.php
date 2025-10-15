<?php
// Generator template XLSX untuk Cetak Nilai STS (Kurmer)
// Hasil disimpan ke assets/sts_template.xlsx dan dapat langsung diunduh
ob_start();
error_reporting(0);
session_start();

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/function.php';
require_once __DIR__ . '/../../config/crud.php';

if (!isset($_SESSION['id_user'])) { die('Unauthorized'); }

$assetsDir = realpath(__DIR__ . '/../../assets');
if (!$assetsDir) { die('Folder assets tidak ditemukan'); }
$outFile   = $assetsDir . DIRECTORY_SEPARATOR . 'sts_template.xlsx';

// Pastikan library ada
if (!file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    die('PhpSpreadsheet belum terpasang.');
}
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Buat workbook
$ss = new Spreadsheet();
$sheet = $ss->getActiveSheet();
$sheet->setTitle('Nilai STS');

// Judul
$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', 'DAFTAR NILAI STS');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Info kelas/semester/tp dengan placeholder
$sheet->setCellValue('A3', 'Kelas');
$sheet->setCellValue('B3', '{{KELAS}}');
$sheet->setCellValue('A4', 'Semester');
$sheet->setCellValue('B4', '{{SEMESTER}}');
$sheet->setCellValue('A5', 'Tahun Pelajaran');
$sheet->setCellValue('B5', '{{TP}}');

// Header tabel
$hdrRow = 7;
$sheet->setCellValue('A'.$hdrRow, 'No');
$sheet->setCellValue('B'.$hdrRow, 'NIS');
$sheet->setCellValue('C'.$hdrRow, 'Nama');
$sheet->setCellValue('D'.$hdrRow, '[[MAPEL_START]]'); // penanda judul mapel dimulai di sini (ke kanan)

$sheet->getStyle('A'.$hdrRow.':H'.$hdrRow)->getFont()->setBold(true);
$sheet->getStyle('A'.$hdrRow.':H'.$hdrRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A'.$hdrRow.':H'.$hdrRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E2F0D9');
$sheet->getStyle('A'.$hdrRow.':H'.$hdrRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Baris contoh data (akan digandakan). Wajib ada penanda [[DATA_ROW]] pada salah satu sel di baris ini.
$dataRow = $hdrRow + 1; // 8
$sheet->setCellValue('A'.$dataRow, '[[DATA_ROW]]');
$sheet->setCellValue('B'.$dataRow, '123456'); // contoh
$sheet->setCellValue('C'.$dataRow, 'Nama Siswa');
$sheet->setCellValue('D'.$dataRow, ''); // nilai mapel akan diisi dinamis ke kanan

$sheet->getStyle('A'.$dataRow.':H'.$dataRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet->getStyle('A'.$dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('B'.$dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
$sheet->getStyle('C'.$dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

// Lebar kolom
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(14);
$sheet->getColumnDimension('C')->setWidth(38);
for ($i = 'D'; $i <= 'H'; $i++) { $sheet->getColumnDimension($i)->setWidth(12); }

// Simpan ke assets
@unlink($outFile);
$writer = new Xlsx($ss);
$writer->save($outFile);

// Jika diminta unduh
if (isset($_GET['download']) && $_GET['download'] == '1') {
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="sts_template.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
}

echo 'Template dibuat: ' . basename($outFile);
?>

