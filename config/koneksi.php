<?php
session_start();
error_reporting(0);


$host = 'localhost';
$username = 'u852176022_admin1';
$password = 'SMPmuhsuruh@123';
$database = 'u852176022_siakad';

$koneksi = mysqli_connect($host, $username, $password, "");
if ($koneksi) {
	$pilihdb = mysqli_select_db($koneksi, $database);
	if ($pilihdb) {
		$query = mysqli_query($koneksi, "SELECT * FROM aplikasi WHERE id_aplikasi='1'");
		if ($query) {
			$setting = mysqli_fetch_array($query);
			mysqli_set_charset($koneksi, 'utf8');
			date_default_timezone_set($setting['waktu']);
		}
	}
}

$semester = $setting['semester'];
$tapel = $setting['tp'];
$jam = date('H:i:s');
$tanggal = date('Y-m-d');
$datetime = date('Y-m-d H:i:s');

?>