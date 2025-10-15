<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

    $data = [    
        'pelanggaran'         => $_POST['langgar'],
		'kkm'         => $_POST['kkm']
    ];
    $exec = update($koneksi, 'aplikasi', $data, ['id_aplikasi'=>1]);
?>