<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'input') {
	 $idk = $_POST['idk'];
     $idb = $_POST['idb'];
     $kondisi = $_POST['kondisi'];
	 $atap = $_POST['atap'];
	 $lantai = $_POST['lantai'];
	 $dinding = $_POST['dinding'];
	 $pintu = $_POST['pintu'];
	 $jendela = $_POST['jendela'];
	 $idl = $_POST['idl'];
	 $nama = $_POST['namabarang'];
	 $baik = $_POST['baik'];
	 $rb = $_POST['rb'];
	 $rs = $_POST['rs'];
	 $rr = $_POST['rr'];
	 $jumlah = $baik + $rb + $rs + $rr;
	 if($idk==1):
    $koneksi->query(" INSERT INTO s_barang (idk,idl,kondisi,atap,lantai,dinding,pintu,jendela) VALUES ('$idk','$idb','$kondisi','$atap','$lantai','$dinding','$pintu','$jendela')");
	$id = $koneksi->insert_id;
	else :
	$koneksi->query(" INSERT INTO s_barang (idk,idl,nama,jumlah,baik,rb,rs,rr) VALUES ('$idk','$idl','$nama','$jumlah','$baik','$rb','$rs','$rr')");
	$id = $koneksi->insert_id;
	endif;
	$ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto' => $file
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   if ($_FILES['file1']['name'] != '') {
   $file1 = $_FILES['file1']['name'];
   $temp = $_FILES['file1']['tmp_name'];
   $ext = explode('.', $file1);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file1;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rb' => $file1
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   
   if ($_FILES['file2']['name'] != '') {
   $file2 = $_FILES['file2']['name'];
   $temp = $_FILES['file2']['tmp_name'];
   $ext = explode('.', $file2);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file2;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rs' => $file2
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   
   if ($_FILES['file3']['name'] != '') {
   $file3 = $_FILES['file3']['name'];
   $temp = $_FILES['file3']['tmp_name'];
   $ext = explode('.', $file3);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file3;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rr' => $file3
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
}

if ($pg == 'hapus') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM s_barang WHERE id='$id'");
}

if ($pg == 'edit') {
	 $id = $_POST['id'];
    $idk = $_POST['idk'];
     $kondisi = $_POST['kondisi'];
	 $atap = $_POST['atap'];
	 $lantai = $_POST['lantai'];
	 $dinding = $_POST['dinding'];
	 $pintu = $_POST['pintu'];
	 $jendela = $_POST['jendela'];
	
	 $nama = $_POST['namabarang'];
	 $baik = $_POST['baik'];
	 $rb = $_POST['rb'];
	 $rs = $_POST['rs'];
	 $rr = $_POST['rr'];
	 $jumlah = $baik + $rb + $rs + $rr;
	 if($idk==1):
        $exec = mysqli_query($koneksi, "UPDATE s_barang SET kondisi='$kondisi',atap='$atap',lantai='$lantai',dinding='$dinding',pintu='$pintu',jendela='$jendela' WHERE id='$id'");
     else:
	 $exec = mysqli_query($koneksi, "UPDATE s_barang SET nama='$nama',jumlah='$jumlah',baik='$baik',rb='$rb',rs='$rs',rr='$rr' WHERE id='$id'");
	 endif;
	 $ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto' => $file
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   
   if ($_FILES['file1']['name'] != '') {
   $file1 = $_FILES['file1']['name'];
   $temp = $_FILES['file1']['tmp_name'];
   $ext = explode('.', $file1);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file1;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rb' => $file1
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   
   if ($_FILES['file2']['name'] != '') {
   $file2 = $_FILES['file2']['name'];
   $temp = $_FILES['file2']['tmp_name'];
   $ext = explode('.', $file2);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file2;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rs' => $file2
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
   
   if ($_FILES['file3']['name'] != '') {
   $file3 = $_FILES['file3']['name'];
   $temp = $_FILES['file3']['tmp_name'];
   $ext = explode('.', $file3);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/sapras/';
      $path = $dest . $file3;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto_rr' => $file3
		];
		 $exec = update($koneksi, 's_barang', $datax, ['id' => $id]);
	}
   }
   }
}
