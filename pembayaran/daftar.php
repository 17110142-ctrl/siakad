<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
$kartu = $_POST['uid'];
mysqli_query($koneksi, "TRUNCATE tmpreg");
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM datareg where nokartu='$kartu'"));
if($jsiswa<>0):
echo "GAGAL";
else:
	$exec = mysqli_query($koneksi,"INSERT INTO tmpreg(nokartu) VALUES('$kartu')");
	echo $kartu."          ";
endif;
?>