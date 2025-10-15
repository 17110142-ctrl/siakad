<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

    $data = [    
	
		 'tgltrx'         => $_POST['tgl'],
		  'idbayar'         => $_POST['idbayar'],
		   'jamkirim'         => $_POST['jamkirim']
    ];
    $exec = update($koneksi, 'aplikasi', $data, ['id_aplikasi'=>1]);
?>