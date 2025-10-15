<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require "../../config/function.php";

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

 
  $level=$_GET['level'];
  $kode=$_GET['kode'];


$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->setCreator('Edi Sukarna')
->setLastModifiedBy('Edi Sukarna')
->setTitle('Office 2007 XLSX')
->setSubject('Office 2007 XLSX')
->setDescription('Test document for Office 2007 XLSX.')
->setKeywords('office 2007 openxml php')
->setCategory('Test result file');




$spreadsheet->setActiveSheetIndex(0)
->setCellValue('A1', "No")
->setCellValue('B1', "NIS")
->setCellValue('C1', "NAMA")
->setCellValue('D1', $kode)

; 

$i=2; 
$no=1; 
$sql = mysqli_query($koneksi, "SELECT * FROM siswa WHERE level='$level'");

while($row = mysqli_fetch_array($sql)){ 
$spreadsheet->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $no)
	->setCellValue('B'.$i, $row['nis'])
	->setCellValue('C'.$i, $row['nama'])
	->setCellValue('D'.$i, "")
	
	;
	
	$i++; $no++;
}

; 
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5); 
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12); 
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(30); 
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10); 


$spreadsheet->getActiveSheet()->setTitle('DATA_SISWA');

$spreadsheet->setActiveSheetIndex(0);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

header("Content-Disposition: attachment; filename=NILAI SKL $kode.xlsx");
header('Cache-Control: max-age=0');

header('Cache-Control: max-age=1');


header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: cache, must-revalidate'); 
header('Pragma: public');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

?>
