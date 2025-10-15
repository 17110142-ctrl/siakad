<?php
require("config/koneksi.php");
require("config/function.php");
require("config/apk.php");
$username = mysqli_escape_string($koneksi, $_POST['username']);
$password = mysqli_escape_string($koneksi, $_POST['password']);
$siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username='$username'");
if ($username <> "" and $password <> "") {
	if (mysqli_num_rows($siswaQ) == 0) {
		echo "td";
	} else {
		$siswa = mysqli_fetch_array($siswaQ);
		if ($password <> $siswa['password']) {
			echo "nopass";
		} else {			
			$_SESSION['id_siswa'] = $siswa['id_siswa'];
			
			echo "ok";
			
		}
	}
}