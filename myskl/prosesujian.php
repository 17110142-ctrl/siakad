<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require "../../config/function.php";
require "../../config/crud.php";

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
$kelas = $_POST['kelas'];
$mapel = $_POST['mapel'];
$jenis = $_POST['jenis'];


$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A2', "   FORMAT UPLOAD TRANSKIP NILAI UJIAN ".$jenis." ".$mapel); 
$sheet->mergeCells('A2:G2');
$sheet->getStyle('A2')->applyFromArray($style_col);

$sheet->mergeCells('A3:G3');

$sheet->setCellValue('A5', "NO"); 
$sheet->setCellValue('B5', "NIS"); 
$sheet->setCellValue('C5', "NAMA LENGKAP"); 
$sheet->setCellValue('D5', "KELAS");
$sheet->setCellValue('E5', "JENIS");
$sheet->setCellValue('F5', "KD MAPEL");
$sheet->setCellValue('G5', "NILAI");  


$sheet->getStyle('A5')->applyFromArray($style_col);
$sheet->getStyle('B5')->applyFromArray($style_col);
$sheet->getStyle('C5')->applyFromArray($style_col);
$sheet->getStyle('D5')->applyFromArray($style_col);
$sheet->getStyle('E5')->applyFromArray($style_col);
$sheet->getStyle('F5')->applyFromArray($style_col);
$sheet->getStyle('G5')->applyFromArray($style_col);

$sheet->getRowDimension('1')->setRowHeight(20);
$sheet->getRowDimension('2')->setRowHeight(20);
$sheet->getRowDimension('3')->setRowHeight(20);


$no = 1; 
$row=6;  
$sql = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_kelas='$kelas'");
while($data = mysqli_fetch_array($sql)){ 

    $sheet->setCellValue('A' . $row, $no);
    $sheet->setCellValue('B' . $row, $data['nis']);
    $sheet->setCellValue('C' . $row, $data['nama']);
    $sheet->setCellValue('D' . $row, $kelas);
	$sheet->setCellValue('E' . $row, $jenis);
	$sheet->setCellValue('F' . $row, $mapel);
	$sheet->setCellValue('G' . $row, '');
   
    $sheet->getStyle('A' . $row)->applyFromArray($style_row);
    $sheet->getStyle('B' . $row)->applyFromArray($style_row);
    $sheet->getStyle('C' . $row)->applyFromArray($style_row);
    $sheet->getStyle('D' . $row)->applyFromArray($style_row);
    $sheet->getStyle('E' . $row)->applyFromArray($style_row);
    $sheet->getStyle('F' . $row)->applyFromArray($style_row);
   $sheet->getStyle('G' . $row)->applyFromArray($style_row);
   
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom No
    $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); // Set text left untuk kolom NIS

    $sheet->getRowDimension($row)->setRowHeight(20); 

    $no++; 
    $row++; 
}

// Set width kolom
$sheet->getColumnDimension('A')->setWidth(5); 
$sheet->getColumnDimension('B')->setWidth(15); 
$sheet->getColumnDimension('C')->setWidth(40); 
$sheet->getColumnDimension('D')->setWidth(10); 
$sheet->getColumnDimension('E')->setWidth(10); 
$sheet->getColumnDimension('F')->setWidth(15); 
$sheet->getColumnDimension('G')->setWidth(10); 
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


$sheet->setTitle("Data Nilai");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=Format Upload Nilai Ujian - $mapel - $jenis.xlsx"); 

header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

?>
