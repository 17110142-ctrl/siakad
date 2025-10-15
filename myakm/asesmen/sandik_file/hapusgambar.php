<?php
require "../../../config/koneksi.php";
require "../../../config/function.php";
$files = glob('../../../files/*'); 
foreach ($files as $file) {
    if (is_file($file))
        unlink($file); 
}
$exec = mysqli_query($koneksi, "truncate file_pendukung");
$filezip = '../../../files.zip';
     unlink($filezip); 
?>