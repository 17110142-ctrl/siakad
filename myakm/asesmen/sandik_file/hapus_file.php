<?php
require("../../../config/koneksi.php");
require("../../../config/function.php");
require("../../../config/crud.php");

$foto = glob('../../../files/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}
$exec = mysqli_query($koneksi, "truncate file_pendukung");