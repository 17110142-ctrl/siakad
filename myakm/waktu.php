<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

if (isset($_GET['pg'])) {
	$pg = $_GET['pg'];
	if ($pg == 'waktu') {
		echo $waktu;
	}
}