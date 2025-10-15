<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$level = $_POST['level'];
$kuri = $_POST['kuri'];
$simpan = mysqli_query($koneksi,"UPDATE kelas set kurikulum='$kuri' where level='$level'");
?>