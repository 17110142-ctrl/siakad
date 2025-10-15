<?php
require "../config/koneksi.php";
require "../vendor/autoload.php";
require "../config/function.php";
require "../config/crud.php";

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
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], 
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], 
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] 
    ]
];
$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER 
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], 
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], 
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] 
    ]
];

$sheet->setCellValue('A1', "NO"); 
$sheet->setCellValue('B1', "NIS"); 
$sheet->setCellValue('C1', "NISN"); 
$sheet->setCellValue('D1', "NO PESERTA");
$sheet->setCellValue('E1', "KELAS");
$sheet->setCellValue('F1', "JURUSAN");
$sheet->setCellValue('G1', "NAMA SISWA");
$sheet->setCellValue('H1', "JK");
$sheet->setCellValue('I1', "TEMPAT LAHIR");
$sheet->setCellValue('J1', "TANGGAL LAHIR");
$sheet->setCellValue('K1', "KETERANGAN");
$sheet->getStyle('A1')->applyFromArray($style_col);
$sheet->getStyle('B1')->applyFromArray($style_col);
$sheet->getStyle('C1')->applyFromArray($style_col);
$sheet->getStyle('D1')->applyFromArray($style_col);
$sheet->getStyle('E1')->applyFromArray($style_col);
$sheet->getStyle('F1')->applyFromArray($style_col);
$sheet->getStyle('G1')->applyFromArray($style_col);
$sheet->getStyle('H1')->applyFromArray($style_col);
$sheet->getStyle('I1')->applyFromArray($style_col);
$sheet->getStyle('J1')->applyFromArray($style_col);
$sheet->getStyle('K1')->applyFromArray($style_col);

$sheet->getRowDimension('1')->setRowHeight(20);
$sheet->getRowDimension('2')->setRowHeight(20);
$sheet->getRowDimension('3')->setRowHeight(20);

$level= $_GET['level'];
$row=2; 
$no=1; 
$sql = mysqli_query($koneksi, "SELECT * FROM siswa WHERE level='$level'");

while($data = mysqli_fetch_array($sql)){ 
$sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nis']);
    $sheet->setCellValue('C' . $row, $data['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('D' . $row, $data['no_peserta']);
	$sheet->setCellValue('E' . $row, $data['kelas']);
	$sheet->setCellValue('F' . $row, $data['jurusan']);
	$sheet->setCellValue('G' . $row, $data['nama']);
    $sheet->setCellValue('H' . $row, $data['jk']);
	$sheet->setCellValue('I' . $row, $data['t_lahir']);
	$sheet->setCellValue('J' . $row, $data['tgl_lahir']);
	$sheet->setCellValue('K' . $row, '');
	
	
    $sheet->getStyle('A' . $row)->applyFromArray($style_row);
    $sheet->getStyle('B' . $row)->applyFromArray($style_row);
    $sheet->getStyle('C' . $row)->applyFromArray($style_row);
    $sheet->getStyle('D' . $row)->applyFromArray($style_row);
    $sheet->getStyle('E' . $row)->applyFromArray($style_row);
    $sheet->getStyle('F' . $row)->applyFromArray($style_row);
    $sheet->getStyle('G' . $row)->applyFromArray($style_row);
	$sheet->getStyle('H' . $row)->applyFromArray($style_row);
	$sheet->getStyle('I' . $row)->applyFromArray($style_row);
    $sheet->getStyle('J' . $row)->applyFromArray($style_row);
	$sheet->getStyle('K' . $row)->applyFromArray($style_row);
	
	$sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
	$sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
	$sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
	$sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
    $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 

    $sheet->getRowDimension($row)->setRowHeight(20); 

    $no++; 
    $row++; 
}

$sheet->setCellValue('M2', "KETERANGAN DIISI DENGAN ANGKA"); 
$sheet->setCellValue('M3', "0 TIDAK LULUS"); 
$sheet->setCellValue('M4', "1 LULUS"); 
$sheet->setCellValue('M5', "2 LULUS BERSYARAT"); 


$sheet->getColumnDimension('A')->setAutoSize(true); 
$sheet->getColumnDimension('B')->setAutoSize(true); 
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true); 
$sheet->getColumnDimension('F')->setAutoSize(true); 
$sheet->getColumnDimension('G')->setAutoSize(true); 
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true); 
$sheet->getColumnDimension('J')->setAutoSize(true); 
$sheet->getColumnDimension('K')->setAutoSize(true); 
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


$sheet->setTitle("Data Siswa");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=UPDATE SISWA SKL.xlsx"); 

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>
