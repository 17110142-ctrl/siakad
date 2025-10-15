<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
  $idu = $_POST['idu'];
    $idn = $_POST['idn'];
    $query = mysqli_query($koneksi, "select * from nilai where id_nilai='$idn'");
    $cek = mysqli_num_rows($query);
    if ($cek <> 0) {
       $simpan = mysqli_query($koneksi,"INSERT INTO reset(idnilai,idsiswa,idujian) VALUES('$idn','$_SESSION[id_siswa]','$idu')");
	}

?>