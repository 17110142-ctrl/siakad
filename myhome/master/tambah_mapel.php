<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
	$datax = [
       'kode' => $_POST['kode'],
       'nama_mapel'  => $_POST['nama']
        ];
	$simpan = insert($koneksi, 'mata_pelajaran', $datax);
	
?>