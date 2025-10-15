<?php
require("../../../config/koneksi.php");
require("../../../config/function.php");
require("../../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

 $smt = $setting['semester'];


if ($pg == 'lingkup') {
	 $tingkat = $_POST['level'];
     $kode = $_POST['mapel'];
	 $guru = $_POST['guru'];
	 $materi = $_POST['materi'];
	 $no = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(lm) AS nomor FROM lingkup  WHERE kodemap='$kode' AND tingkat='$tingkat'"));
 $urutan = $no['nomor'];
 $urutan++;
       $dataQ =[
	   'tingkat' =>$tingkat,
	   'lm' =>$urutan,
	   'kodemap' =>$kode,
	   'materi' =>$materi,
	   'idguru' =>$guru,
	   'smt'=>$smt
	   ];
$simpan = insert($koneksi,'lingkup',$dataQ);
echo $simpan;
}
if ($pg == 'edit') {
	  $idl = $_POST['idl'];
	 $materi = $_POST['materi'];
	
       $dataQ =[
	   'materi' =>$materi
	   ];
$simpan = update($koneksi,'lingkup',$dataQ,['idl'=>$idl]);
echo $simpan;
}
if ($pg == 'tujuan') {
	 $tingkat = $_POST['level'];
     $kode = $_POST['mapel'];
	 $guru = $_POST['guru'];
	 $materi = $_POST['materi'];
	 $no = mysqli_fetch_array(mysqli_query($koneksi, "SELECT MAX(tp) AS nomor FROM tujuan  WHERE  kodemap='$kode' AND tingkat='$tingkat'"));
 $urutan = $no['nomor'];
 $urutan++;
       $dataQ =[
	   'tingkat' =>$tingkat,
	   'tp' =>$urutan,
	   'kodemap' =>$kode,
	   'pembelajaran' =>$materi,
	   'idguru' =>$guru,
	   'smt'=>$smt
	   ];
$simpan = insert($koneksi,'tujuan',$dataQ);
echo $simpan;
}
if ($pg == 'edittujuan') {
	  $idt = $_POST['idt'];
	 $materi = $_POST['materi'];
	
       $dataQ =[
	   'pembelajaran' =>$materi
	   ];
$simpan = update($koneksi,'tujuan',$dataQ,['idt'=>$idt]);
echo $simpan;
}