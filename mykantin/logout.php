<?php
	require("../config/koneksi.php");
	require("../config/dis.php");
	(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
	session_destroy();
	echo "<script>location.href = '.';</script>";
