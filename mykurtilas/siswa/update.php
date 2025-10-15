<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require("../../config/function.php");

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
			$status = $sheetData[$i]['3'];
			$anak = $sheetData[$i]['4'];
			$nisn = $sheetData[$i]['5'];
			$asal = $sheetData[$i]['6'];
			$dikelas = $sheetData[$i]['7'];
			$tanggal = $sheetData[$i]['8'];
			$tlahir = $sheetData[$i]['9'];
			$tgl_lahir = $sheetData[$i]['10'];
			$alamat = $sheetData[$i]['11'];
			$desa = $sheetData[$i]['12'];
			$kec = $sheetData[$i]['13'];
			$kab = $sheetData[$i]['14'];
			$ayah = $sheetData[$i]['15'];
			$pek_ayah = $sheetData[$i]['16'];
			$ibu = $sheetData[$i]['17'];
			$pek_ibu = $sheetData[$i]['18'];
			
			
                $exec = mysqli_query($koneksi, "UPDATE siswa 
				
				SET stskel='$status',
				t_lahir='$tlahir',
				tgl_lahir='$tgl_lahir',
				alamat='$alamat',
				desa='$desa',
				kecamatan='$kec',
				kabupaten='$kab',
				ayah='$ayah',
				pek_ayah='$pek_ayah',
				ibu='$ibu',
				pek_ibu='$pek_ibu',
				anakke='$anak',
				asal_sek='$asal',
				dikelas='$dikelas',
				tgl_terima='$tanggal'
				
				
				WHERE nis='$nis'");
          
        }
       
    } else {
        echo "Pilih file yang bertipe xlsx or xls";
    }
}
