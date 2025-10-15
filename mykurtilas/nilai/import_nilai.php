<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require("../../config/function.php");
require("../../config/crud.php");
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
        
        for ($i = 3; $i < count($sheetData); $i++) {
            $id_siswa = $sheetData[$i]['0'];
			$nis = $sheetData[$i]['1'];
			$kelas = $sheetData[$i]['4'];
			$kode = $sheetData[$i]['5'];
			$nilai3 = $sheetData[$i]['6'];
			$nilai4 = $sheetData[$i]['7'];
			$idguru = $sheetData[$i]['8'];
			
			$qus = mysqli_query($koneksi, "SELECT nis,mapel,guru FROM nilai_rapor WHERE nis='$nis' AND mapel='$kode' AND guru='$idguru'");
            $cek = mysqli_num_rows($qus);
            if ($cek==0) {	
                $exec = mysqli_query($koneksi, "INSERT nilai_rapor(kelas,nis,mapel,nilai3,nilai4,guru,semester,tp) VALUES('$kelas','$nis','$kode','$nilai3','$nilai4','$idguru','$semester','$tapel')");
			}else{
			 $exec = mysqli_query($koneksi, "UPDATE nilai_rapor SET nilai3='$nilai3',nilai4='$nilai4' WHERE nis='$nis' AND mapel='$kode' AND guru='$idguru' and semester='$semester' and tp='$tapel'");	
			}
        }
       
    } else {
        echo "Pilih file yang bertipe xlsx or xls";
    }
}
