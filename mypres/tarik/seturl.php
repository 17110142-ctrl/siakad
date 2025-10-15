<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

   $server = $_POST['server'];
   $npsn = $_POST['npsn'];
    mysqli_query($koneksi,"UPDATE aplikasi set npsn='$npsn',server='$server' where id_aplikasi='1'");
    

?>