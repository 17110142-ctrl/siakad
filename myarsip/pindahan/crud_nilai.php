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
        $sukses = $gagal = 0;
       
        for ($i = 5; $i < count($sheetData); $i++) {
          
            $nis = $sheetData[$i]['1'];
            $kelas = $sheetData[$i]['3'];
            $smt = $sheetData[$i]['4'];
          
            $mapel = $sheetData[$i]['5'];
			
			$nilai = $sheetData[$i]['6'];
           
			
            if ($nilai <> '') {
                $exec = mysqli_query($koneksi, "INSERT INTO nilai_skl (nis,kelas,mapel,semester,nilai) 
				VALUES ('$nis','$kelas','$mapel','$smt','$nilai')");
               
				($exec) ? $sukses++ : $gagal++;
				
            }
        }
        echo "Berhasil: $sukses | Gagal: $gagal ";
    } else {
        echo "Pilih file yang bertipe xlsx or xls";
    }
}
