<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

	$ids = $_POST['ids'];
	$kartu = $_POST['nokartu'];
	$simpan = mysqli_query($koneksi,"UPDATE siswa SET nokartu='$kartu' WHERE id_siswa='$ids'");
	mysqli_query($koneksi, "TRUNCATE tmpreg");
  mysqli_close($koneksi);
?>