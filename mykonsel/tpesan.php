<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

    $id = $_POST['id'];
    delete($koneksi, 'bk_pesan', ['id' => $id]);
	
?>