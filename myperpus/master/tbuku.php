<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
include "../../vendor/phpqrcode/qrlib.php";
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'buku', ['id' => $id]);
	 $exec = delete($koneksi, 'transaksi', ['idbuku' => $id]);
	 $exec = mysqli_query($koneksi, "truncate tmpsis");
	 $exec = mysqli_query($koneksi, "truncate tmpbuku");
	if($exec){
		$query = "SELECT * FROM buku ORDER BY id";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id'];
	 $query2 = "UPDATE buku SET id = $no WHERE id = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE buku  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}
if ($pg == 'tambah') {
   $data = [
	    'idkategori'     => $_POST['kate'],
        'judul'     => $_POST['judul'],
		'pengarang'     => $_POST['pengarang'],
		'penerbit'     => $_POST['penerbit'],
        'jumlah'     => $_POST['jumlah'],
		'barkode'     => $_POST['barkode']
			];
	$exec = insert($koneksi, 'buku', $data);
	$tempdir = "../../temp/perpus/"; 
if (!file_exists($tempdir)) 
    mkdir($tempdir);
$codeContents = $_POST['barkode'];
QRcode::png($codeContents, $tempdir . $_POST['barkode'] . '.png', QR_ECLEVEL_M, 4);
	if($exec){
		mysqli_query($koneksi, "TRUNCATE tmpbuku");	
	}
}
if ($pg == 'edit') {
	$id = $_POST['id'];
    
        $data = [
       'judul'     => $_POST['judul'],
        'jumlah'     => $_POST['jumlah']
        ];
    
   
    $exec = update($koneksi, 'buku', $data, ['id' => $id]);
    echo $exec;
	
}
?>