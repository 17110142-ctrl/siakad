<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'katrol') {
	$tanggal = $_POST['tanggal'];
	$mapel = $_POST['mapel'];
	$kelas = $_POST['kelas'];
    $guru = $_POST['guru'];
    $rendah = $_POST['rendah'];
	$tinggi = $_POST['tinggi'];
	$sql = mysqli_fetch_array(mysqli_query($koneksi, "SELECT tanggal,idsiswa,mapel,kelas,guru,nilai,MIN(nilai) AS kecil, MAX(nilai) as besar FROM nilai_harian where tanggal='$tanggal' and mapel='$mapel' and kelas='$kelas' and guru='$guru'"));
	$kecil = $sql['kecil'];
	$besar = $sql['besar'];
	
	$query = mysqli_query($koneksi, "SELECT * FROM nilai_harian where tanggal='$tanggal' and mapel='$mapel' and kelas='$kelas' and guru='$guru'");
	while ($data = mysqli_fetch_array($query)) :
	
	$nilai = $data['nilai'];
	$katrol = $rendah+($nilai - $kecil)/($besar-$kecil) * ($tinggi-$rendah);
	$katrol = round($katrol);
	mysqli_query($koneksi,"UPDATE nilai_harian set katrol='$katrol' where id='$data[id]'");
	endwhile;
}
