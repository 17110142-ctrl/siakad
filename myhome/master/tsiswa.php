<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'hps') {
	$id = $_POST['id'];
	$exec = mysqli_query($koneksi, "delete from pesan_terkirim where id='$id'");
}