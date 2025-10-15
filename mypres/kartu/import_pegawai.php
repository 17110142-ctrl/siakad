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
        
        for ($i = 4; $i < count($sheetData); $i++) {
            $id_user = $sheetData[$i][0];
            $username = $sheetData[$i][1];
            $nip = $sheetData[$i][3];
            $nama = $sheetData[$i][4];
            $level = $sheetData[$i][5];
            
            $query = "INSERT INTO users (id_user, username, nip, nama, level) 
                      VALUES ('$id_user', '$username', '$nip', '$nama', '$level') 
                      ON DUPLICATE KEY UPDATE 
                      username='$username', nama='$nama', level='$level'";
            
            $exec = mysqli_query($koneksi, $query);
        }
        echo "1";
    } else {
        echo "0";
    }
}
