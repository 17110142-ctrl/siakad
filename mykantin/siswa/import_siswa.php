<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require("../../config/function.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
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
		 $exec = mysqli_query($koneksi, "truncate siswa");
		 $exec = mysqli_query($koneksi, "truncate saldo");
		 $exec = mysqli_query($koneksi, "truncate transaksi");
		 $exec = mysqli_query($koneksi, "truncate keranjang");
        for ($i = 4; $i < count($sheetData); $i++) {
            $id_siswa = $sheetData[$i]['0'];
			$nis = $sheetData[$i]['1'];
			$kelas = $sheetData[$i]['2'];
			$nama = $sheetData[$i]['3'];
			$nama = addslashes($nama);
			$jk = $sheetData[$i]['4'];
			$username = $sheetData[$i]['5'];
			$password = $sheetData[$i]['6'];
			$foto = $sheetData[$i]['7'];
			$nowa = $sheetData[$i]['8'];
			
           $qus = mysqli_query($koneksi, "SELECT username FROM siswa WHERE username='$username'");
            $cekuser = mysqli_num_rows($qus);
            if ($cekuser == 0) {
			
                $exec = mysqli_query($koneksi, "INSERT INTO siswa (nis,kelas,nama,jk,username,password,foto,nowa) 
				VALUES ('$nis','$kelas','$nama','$jk','$username','$password','$foto','$nowa')");
             
				
            }
        }
        echo "1";
    } else {
        echo "0";
    }
}
