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
		 
		
        for ($i = 4; $i < count($sheetData); $i++) {
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$serial = substr(str_shuffle($characters), 0,3);
            $id_siswa = $sheetData[$i]['0'];
			if($id_siswa >=1 AND $id_siswa<=9){
				$nomor = '00'.$id_siswa;
			}elseif($id_siswa >=10 AND $id_siswa<=99){
				$nomor = '0'.$id_siswa;
			}else{
				$nomor = $id_siswa;
			}
            $nisn = $sheetData[$i]['1'];
			$nis = $sheetData[$i]['2'];
            $level = $sheetData[$i]['3'];
			if($level=="1" OR $level=="2"){
				$fase="A";
			}elseif($level=="3" OR $level=="4"){
				$fase="B";
			}elseif($level=="5" OR $level=="6"){
				$fase="C";
			}elseif($level=="7" OR $level=="8" OR $level=="9"){
				$fase="D";
			}elseif($level=="10"){
				$fase="E";
			}elseif($level=="11" OR $level=="12"){
				$fase="F";
			}
			$kelas = $sheetData[$i]['4'];
			$jurusan = $sheetData[$i]['5'];
			If($jurusan==''){
				$jurusan ='semua';
			}else{
			$jurusan = $jurusan;	
			}
			$nama = $sheetData[$i]['6'];
			$nama = addslashes($nama);
			$jk = $sheetData[$i]['7'];
			$agama = $sheetData[$i]['8'];
			$foto = $sheetData[$i]['9'];
			$nowa = $sheetData[$i]['10'];
			$npsn = $setting['npsn'];
			$server = $setting['id_server'];
			$username = $nis;
			$password = $nis."-".$serial;
			$nopes = "PDB-".$npsn."-".$nomor;
			$slevel = mysqli_query($koneksi, "SELECT id_level FROM level WHERE id_level='$level'");
            $ceklevel = mysqli_num_rows($slevel);
            if ($ceklevel == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO level (id_level,level)VALUES('$level','$level')");
            }
			$skelas = mysqli_query($koneksi, "SELECT kelas FROM kelas WHERE kelas='$kelas'");
            $cekkelas = mysqli_num_rows($skelas);
            if ($cekkelas == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO kelas (level,kelas,pk)VALUES('$level','$kelas','$jurusan')");
            }
			$sjurusan = mysqli_query($koneksi, "SELECT kode_jurusan FROM jurusan WHERE kode_jurusan='$jurusan'");
            $cekjurusan = mysqli_num_rows($sjurusan);
            if ($cekjurusan == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO jurusan (kode_jurusan)VALUES('$jurusan')");
            }
			$qser = mysqli_query($koneksi, "SELECT kode_server FROM server WHERE kode_server='$server'");
            $cekser = mysqli_num_rows($qser);
            if ($cekser == 0) {
                $exec = mysqli_query($koneksi, "INSERT INTO server (kode_server)VALUES('$server')");
            }
           $qus = mysqli_query($koneksi, "SELECT username FROM siswa WHERE username='$username'");
            $cekuser = mysqli_num_rows($qus);
            if ($cekuser == 0) {
			
                $exec = mysqli_query($koneksi, "INSERT INTO siswa (no_peserta,nisn,nis,level,kelas,jurusan,fase,nama,jk,agama,username,password,server,foto,nowa) 
				VALUES ('$nopes','$nisn','$nis','$level','$kelas','$jurusan','$fase','$nama','$jk','$agama','$username','$password','$server','$foto','$nowa')");
             
				
            }
        }
        echo "1";
    } else {
        echo "0";
    }
}
