<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require("../../config/function.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$user = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM prosek  WHERE id_user='$_SESSION[id_user]'"));
$sek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM sekolah WHERE npsn='$user[npsn]'"));
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
		 $exec = mysqli_query($koneksi, "truncate ruang");
		 $exec = mysqli_query($koneksi, "truncate sesi");
        for ($i = 4; $i < count($sheetData); $i++) {
			
            $nopes = $sheetData[$i]['1'];
			$nis = $sheetData[$i]['2']; 
			$username = $sheetData[$i]['8']; 
			$password = $sheetData[$i]['9']; 			
			$sesi = $sheetData[$i]['10'];
			$ruang = $sheetData[$i]['11'];
			
			$sruang = mysqli_query($koneksi, "SELECT kode_ruang FROM ruang WHERE kode_ruang='$ruang'");
            $cekruang = mysqli_num_rows($sruang);
            if ($cekruang == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO ruang (kode_ruang,keterangan)VALUES('$ruang','$ruang')");
            }
			$ssesi = mysqli_query($koneksi, "SELECT kode_sesi FROM sesi WHERE kode_sesi='$sesi'");
            $ceksesi = mysqli_num_rows($ssesi);
            if ($ceksesi == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO sesi (kode_sesi,nama_sesi)VALUES('$sesi','$sesi')");
            }
                $simpan = mysqli_query($koneksi, "UPDATE siswa SET no_peserta='$nopes',username='$username',password='$password',sesi='$sesi',ruang='$ruang' WHERE nis='$nis'");
             
			
        }
        echo "1";
    } else {
        echo "0";
    }
}
