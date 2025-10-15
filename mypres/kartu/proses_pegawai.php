<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require "../../vendor/autoload.php";

session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$style_col = [
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
    ]
];

$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
    ]
];

$sheet->setCellValue('A1', "DATA PEGAWAI");
$sheet->mergeCells('A1:E1');
$sheet->getStyle('A1')->applyFromArray($style_col);
$sheet->getStyle('A1')->getFont()->setSize(15);

$sheet->setCellValue('A3', "NO");
$sheet->setCellValue('B3', "ID PEGAWAI");
$sheet->setCellValue('C3', "NAMA PEGAWAI");
$sheet->setCellValue('D3', "JABATAN");
$sheet->setCellValue('E3', "LEVEL");

$sheet->getStyle('A3:E3')->applyFromArray($style_col);

$i = 4;
$no = 1;
$sql = mysqli_query($koneksi, "SELECT * FROM users");
while ($data = mysqli_fetch_array($sql)) {
    $sheet->setCellValue('A' . $i, $no);
    $sheet->setCellValue('B' . $i, $data['id_user']);
    $sheet->setCellValue('C' . $i, $data['nama']);
    $sheet->setCellValue('D' . $i, $data['jabatan']);
    $sheet->setCellValue('E' . $i, $data['level']);
    
    $sheet->getStyle("A$i:E$i")->applyFromArray($style_row);
    $i++;
    $no++;
}

foreach (range('A', 'E') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle("DATA PEGAWAI");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=DATA_PEGAWAI.xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
