<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require "../../vendor/autoload.php";
require("../../config/crud.php");
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

$bl= $_GET['bln'];
$bulane = fetch ($koneksi, 'bulan', ['bln' =>$bl]);

$sheet->setCellValue('A1', "REKAP PROSENTASE"); 
$sheet->mergeCells('A1:I1'); 
$sheet->getStyle('A1')->getFont()->setBold(true); 
$sheet->getStyle('A1')->getFont()->setSize(13); 
$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 

$sheet->setCellValue('A3', "NO"); 
$sheet->setCellValue('B3', "NAMA PEGAWAI"); 
$sheet->setCellValue('C3', "JABATAN"); 
$sheet->setCellValue('D3', "H");
$sheet->setCellValue('E3', "S");  
$sheet->setCellValue('F3', "I");
$sheet->setCellValue('G3', "A");
$sheet->setCellValue('H3', "TEPAT (%)");
$sheet->setCellValue('I3', "TERLAMBAT (%)");

$sheet->getStyle('A3')->applyFromArray($style_col);
$sheet->getStyle('B3')->applyFromArray($style_col);
$sheet->getStyle('C3')->applyFromArray($style_col);
$sheet->getStyle('D3')->applyFromArray($style_col);
$sheet->getStyle('E3')->applyFromArray($style_col);
$sheet->getStyle('F3')->applyFromArray($style_col);
$sheet->getStyle('G3')->applyFromArray($style_col);
$sheet->getStyle('H3')->applyFromArray($style_col);
$sheet->getStyle('I3')->applyFromArray($style_col);

$sheet->getRowDimension('1')->setRowHeight(20);
$sheet->getRowDimension('2')->setRowHeight(20);
$sheet->getRowDimension('3')->setRowHeight(20);


$i=4; 
$no=1; 
$bulan= $bl;
$tahun=date('Y');
	
$query = mysqli_query($koneksi,"select id_user,level,nama from users where level<>'admin' GROUP BY id_user");          
while ($peg = mysqli_fetch_array($query)) {
$hadir= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun FROM absensi WHERE idpeg='$peg[id_user]' AND ket='H' AND bulan='$bulan' AND tahun='$tahun' "));
$sakit= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun FROM absensi WHERE idpeg='$peg[id_user]' AND ket='S' AND bulan='$bulan' AND tahun='$tahun' "));
$izin= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun FROM absensi WHERE idpeg='$peg[id_user]' AND ket='I' AND bulan='$bulan' AND tahun='$tahun' "));
$alpha= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun FROM absensi WHERE idpeg='$peg[id_user]' AND ket='A' AND bulan='$bulan' AND tahun='$tahun' "));
$tepat= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun,keterangan FROM absensi WHERE idpeg='$peg[id_user]' AND ket='H' AND bulan='$bulan' AND tahun='$tahun' AND keterangan='Tepat Waktu' "));
$telat= mysqli_num_rows(mysqli_query($koneksi, "SELECT idpeg,ket,bulan,tahun,keterangan FROM absensi WHERE idpeg='$peg[id_user]' AND ket='H' AND bulan='$bulan' AND tahun='$tahun' AND keterangan !='Tepat Waktu' "));
	
    $sheet->setCellValue('A' . $i, $no);
    $sheet->setCellValue('B' . $i, ucwords(strtolower($peg['nama'])));
    $sheet->setCellValue('C' . $i, ucwords(strtolower($peg['level'])));
    $sheet->setCellValue('D' . $i, $hadir);
    $sheet->setCellValue('E' . $i, $sakit);
	$sheet->setCellValue('F' . $i, $izin);
    $sheet->setCellValue('G' . $i, $alpha);
	if($hadir >0):
	$sheet->setCellValue('H' . $i, round(($tepat/$hadir)*100));
	$sheet->setCellValue('I' . $i, round(($telat/$hadir)*100));
	endif;
    $sheet->getStyle('A' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
	$sheet->getStyle('B' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT); 
    $sheet->getStyle('E' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('F' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('G' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('H' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle('I' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	
	$sheet->getStyle('A' . $i)->applyFromArray($style_row);
	$sheet->getStyle('B' . $i)->applyFromArray($style_row);
	$sheet->getStyle('C' . $i)->applyFromArray($style_row);
	$sheet->getStyle('D' . $i)->applyFromArray($style_row);
	$sheet->getStyle('E' . $i)->applyFromArray($style_row);
	$sheet->getStyle('F' . $i)->applyFromArray($style_row);
	$sheet->getStyle('G' . $i)->applyFromArray($style_row);
	$sheet->getStyle('H' . $i)->applyFromArray($style_row);
	$sheet->getStyle('I' . $i)->applyFromArray($style_row);
    $sheet->getRowDimension($i)->setRowHeight(20); 
 
   $i++; $no++;

}
 

$sheet->getColumnDimension('A')->setWidth(3); 
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);
$sheet->getColumnDimension('H')->setAutoSize(true);
$sheet->getColumnDimension('I')->setAutoSize(true);
$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle("DATA PROSENTASE");

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=PROSENTASE BULAN ".$bl.".xlsx"); 
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>