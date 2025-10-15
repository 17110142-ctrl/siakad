<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require("../../config/function.php");
cek_session_admin();
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
		
        $exec = mysqli_query($koneksi, "truncate sekolah");
		$exec = mysqli_query($koneksi, "truncate prosek");
		
        for ($i = 1; $i < count($sheetData); $i++) {
            $id_sek = $sheetData[$i]['0'];
            $npsn = $sheetData[$i]['1'];
            $nama = $sheetData[$i]['2'];
            $nama = addslashes($nama);
            $status = $sheetData[$i]['3'];
			$kepsek = $sheetData[$i]['4'];
			$kepsek = addslashes($kepsek);
			$nip = $sheetData[$i]['5'];
           
			$server = $sheetData[$i]['6'];
            $proktor = $sheetData[$i]['7'];
			$nowa = $sheetData[$i]['8'];
			$alamat = $sheetData[$i]['9'];
			$desa = $sheetData[$i]['10'];
			$kec = $sheetData[$i]['11'];
			$kab = $sheetData[$i]['12'];
			$prop = $sheetData[$i]['13'];
			$token = $setting['token_api'];
			$url = $setting['url_host'];
			
            $qnpsn = mysqli_query($koneksi, "SELECT npsn FROM prosek WHERE npsn='$npsn'");
            $ceknpsn = mysqli_num_rows($qnpsn);
            if ($ceknpsn == 0) {
                
             $exec = mysqli_query($koneksi, "INSERT INTO sekolah (npsn,nama_sekolah,status,alamat,desa,kecamatan,kabupaten,provinsi,kepsek,nip,id_server,proktor,token_api,url_sinkron) 
			 VALUES ('$npsn','$nama','$status','$alamat','$desa','$kec','$kab','$prop','$kepsek','$nip','$server','$proktor','$token','$url')");
             $exec = mysqli_query($koneksi, "INSERT INTO prosek (npsn,nama,level,username,password,nowa) 
				VALUES ('$npsn','$proktor','sekolah','$npsn','$npsn','$nowa')");
			}
            
        }
        
    } else {
        echo "gagal";
    }
}
