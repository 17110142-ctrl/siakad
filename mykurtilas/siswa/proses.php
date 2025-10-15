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
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];


$style_row = [
    'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
    ],
    'borders' => [
        'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
        'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
        'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
        'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
    ]
];

$sheet->setCellValue('A1', "UPDATE DATA SISWA ".$setting['sekolah']); 
$sheet->mergeCells('A1:S1'); 
$sheet->getStyle('A1')->getFont()->setBold(true); 
$sheet->getStyle('A1')->getFont()->setSize(15); 
 $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
 
$sheet->setCellValue('A3', "NO");
$sheet->setCellValue('B3', "NIS"); 
$sheet->setCellValue('C3', "NAMA LENGKAP"); 
$sheet->setCellValue('D3', "STATUS DALAM KELUARGA");
$sheet->setCellValue('E3', "ANAK KE");  
$sheet->setCellValue('F3', "NISN"); 
$sheet->setCellValue('G3', "SEKOLAH ASAL");
$sheet->setCellValue('H3', "DITERIMA KELAS");  
$sheet->setCellValue('I3', "PADA TANGGAL"); 
$sheet->setCellValue('J3', "TEMPAT LAHIR"); 
$sheet->setCellValue('K3', "TANGGAL LAHIR");
$sheet->setCellValue('L3', "ALAMAT"); 
$sheet->setCellValue('M3', "DESA"); 
$sheet->setCellValue('N3', "KECAMATAN"); 
$sheet->setCellValue('O3', "KABUPATEN"); 
$sheet->setCellValue('P3', "NAMA AYAH"); 
$sheet->setCellValue('Q3', "PEKERJAAN"); 
$sheet->setCellValue('R3', "NAMA IBU"); 
$sheet->setCellValue('S3', "PEKERJAAN"); 



$sheet->getStyle('A3')->applyFromArray($style_col);
$sheet->getStyle('B3')->applyFromArray($style_col);
$sheet->getStyle('C3')->applyFromArray($style_col);
$sheet->getStyle('D3')->applyFromArray($style_col);
$sheet->getStyle('E3')->applyFromArray($style_col);
$sheet->getStyle('F3')->applyFromArray($style_col);
$sheet->getStyle('G3')->applyFromArray($style_col);
$sheet->getStyle('H3')->applyFromArray($style_col);
$sheet->getStyle('I3')->applyFromArray($style_col);
$sheet->getStyle('J3')->applyFromArray($style_col);
$sheet->getStyle('K3')->applyFromArray($style_col);
$sheet->getStyle('L3')->applyFromArray($style_col);
$sheet->getStyle('M3')->applyFromArray($style_col);
$sheet->getStyle('N3')->applyFromArray($style_col);
$sheet->getStyle('O3')->applyFromArray($style_col);
$sheet->getStyle('P3')->applyFromArray($style_col);
$sheet->getStyle('Q3')->applyFromArray($style_col);
$sheet->getStyle('R3')->applyFromArray($style_col);
$sheet->getStyle('S3')->applyFromArray($style_col);

$sheet->getRowDimension('1')->setRowHeight(20);
$sheet->getRowDimension('2')->setRowHeight(20);
$sheet->getRowDimension('3')->setRowHeight(20);


$i=4; 
$no=1; 
$sql = mysqli_query($koneksi, "SELECT * FROM siswa");
while($data = mysqli_fetch_array($sql)){ 

    $sheet->setCellValue('A' . $i, $no);
    $sheet->setCellValue('B' . $i, $data['nis']);
    $sheet->setCellValue('C' . $i, $data['nama']);
    $sheet->setCellValue('D' . $i, '');
    $sheet->setCellValue('E' . $i, '');
	$sheet->setCellValueExplicit('F' . $i, $data['nisn'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('G' . $i, '');
	$sheet->setCellValue('H' . $i, '');
	$sheet->setCellValue('I' . $i, '12 Agustus 2000', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('J' . $i, '');
    $sheet->setCellValue('K' . $i, '12 Agustus 2000', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
	$sheet->setCellValue('L' . $i, '');
	$sheet->setCellValue('M' . $i, '');
	$sheet->setCellValue('N' . $i, '');
    $sheet->setCellValue('O' . $i, '');
	$sheet->setCellValue('P' . $i, '');
	$sheet->setCellValue('Q' . $i, '');
	$sheet->setCellValue('R' . $i, '');
	$sheet->setCellValue('S' . $i, '');
	   
	 
	 
	 
	 
    $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
	 $sheet->getStyle('B' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
     $sheet->getStyle('D' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
   $sheet->getRowDimension($i)->setRowHeight(20); 

   $i++; $no++;
}


$sheet->getColumnDimension('A')->setAutoSize(true); 
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getColumnDimension('J')->setAutoSize(true);
$sheet->getColumnDimension('K')->setAutoSize(true); 
$sheet->getColumnDimension('L')->setAutoSize(true);
$sheet->getColumnDimension('M')->setAutoSize(true);
$sheet->getColumnDimension('N')->setAutoSize(true);
$sheet->getColumnDimension('O')->setAutoSize(true);
$sheet->getColumnDimension('P')->setAutoSize(true);
$sheet->getColumnDimension('Q')->setAutoSize(true);
$sheet->getColumnDimension('R')->setAutoSize(true);
$sheet->getColumnDimension('S')->setAutoSize(true);

$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle("BIODATA");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="UPDATE DATA RAPOR.xlsx"'); 
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>