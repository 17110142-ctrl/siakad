<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once("../../PHPExcel/PHPExcel.php");
session_start();

$guru = $_POST['guru'];
$mapel = $_POST['mapel'];
$tingkat = $_POST['level'];
$jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup where mapel='$mapel' and level='$tingkat' and guru and smt='$semester'"));
 $nama_file_baru = 'data.xlsx';
	
	if(is_file('../../temp/'.$nama_file_baru)) 
	unlink('../../temp/'.$nama_file_baru); 
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION); 
	$tmp_file = $_FILES['file']['tmp_name'];
	if($ext == "xlsx"){
	move_uploaded_file($tmp_file, '../../temp/'.$nama_file_baru);
     
	$excelreader = new PHPExcel_Reader_Excel2007();
	$loadexcel = $excelreader->load('../../temp/'.$nama_file_baru); 
	$sheet = $loadexcel->getActiveSheet()->toArray();
			
	
	   $nama_file_baru = 'data.xlsx';
       
        for ($a = 6; $a < (6+$jml); $a++) {
            $kode = $sheet['2'][$a];
            
            for ($i = 3; $i < count($sheet); $i++) {
				
                 $khp = $sheet[$i]['0'];
                $nis = $sheet[$i]['1'];
				$mapel = $sheet[$i]['5'];
				$kelas = $sheet[$i]['4'];
                $kode = $kode;
                $nilai = $sheet[$i][$a];
				$khp ='SAM';
              $idguru = $_POST['guru'];
				
	
$query = "INSERT INTO nilai_sumatif(nis,kelas,mapel,nilai,ket,khp,guru,semester,tp) VALUES('".$nis."','".$kelas."','".$mapel."','".$nilai."','".$kode."','".$khp."','".$idguru."','".$semester."','".$tapel."')";

		
			mysqli_query($koneksi, $query);
			
            }
        }
}

