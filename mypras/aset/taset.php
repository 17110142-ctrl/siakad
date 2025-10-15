<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'input') {
	 $tgl = $_POST['tgl'];
     $barang = $_POST['barang'];
     $toko = $_POST['toko'];
	 $alamat = $_POST['alamat'];
	 $jumlah = $_POST['jumlah'];
	 $harga = $_POST['harga'];
	 $dana = $_POST['dana'];
	 $total= $jumlah * $harga;
	 $bulan = date('m',strtotime($tgl));
	 $tahun = date('Y',strtotime($tgl));
    $koneksi->query(" INSERT INTO aset (tanggal,nama_barang,jumlah,harga,total,toko,alamat,dana,bulan,tahun) VALUES ('$tgl','$barang','$jumlah','$harga','$total','$toko','$alamat','$dana','$bulan','$tahun')");
	
}

if ($pg == 'hapus') {
	 $id = $_POST['id'];
     $exec = mysqli_query($koneksi, "DELETE FROM aset WHERE id='$id'");
}

if ($pg == 'edit') {
	 $id = $_POST['id'];
    $tgl = $_POST['tgl'];
     $barang = $_POST['barang'];
     $toko = $_POST['toko'];
	 $alamat = $_POST['alamat'];
	 $jumlah = $_POST['jumlah'];
	 $harga = $_POST['harga'];
	 $dana = $_POST['dana'];
	 $total= $jumlah * $harga;
	 $bulan = date('m',strtotime($tgl));
	 $tahun = date('Y',strtotime($tgl));
     $simpan = mysqli_query($koneksi, "UPDATE aset SET tanggal='$tanggal',nama_barang='$barang',jumlah='$jumlah',harga='$harga',
	 total='$total',toko='$toko',alamat='$alamat',dana='$dana',bulan='$bulan',tahun='$tahun' WHERE id='$id'");
     
}
