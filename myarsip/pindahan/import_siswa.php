<?php
require "../config/koneksi.php";
require "../vendor/autoload.php";
require("../config/function.php");

$file_mimes = array('application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if (isset($_FILES['file']['name'])) {
    $ext = ['xls', 'xlsx'];
    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);
    if (in_array($extension, $ext)) {
        if ('xls' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();
       
        for ($i = 1; $i < count($sheetData); $i++) {
           
            $nis = $sheetData[$i]['1'];
            $t_lahir = $sheetData[$i]['8'];
			$tgl_lahir = $sheetData[$i]['9'];
			$keterangan = $sheetData[$i]['10'];
			
           $cari = mysqli_num_rows(mysqli_query($koneksi,"SELECT nis FROM siswa WHERE nis='$nis'"));
		   if($cari > 0){
                $exec = mysqli_query($koneksi, "UPDATE siswa 
				SET t_lahir='$t_lahir',
				tgl_lahir='$tgl_lahir',keterangan='$keterangan'
				WHERE nis='$nis'");
				
			  
            }
        }
        echo "1";
    } else {
        echo "0";
    }
}
