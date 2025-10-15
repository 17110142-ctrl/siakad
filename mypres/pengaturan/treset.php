<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$query = mysqli_query($koneksi, "SELECT * FROM datareg"); 
	while ($data = mysqli_fetch_array($query)){
		$gambar = glob('../../data/'.$data['folder'].'/*'); 
  foreach ($gambar as $filex) {
    if (is_file($filex))
        unlink($filex); 
    } 
	rmdir('../../data/'.$data['folder']);
	}
$exec = mysqli_query($koneksi, "update siswa set sts='0',idjari=NULL");
$exec = mysqli_query($koneksi, "update users set sts='0',idjari=NULL");
$exec = mysqli_query($koneksi, "truncate absensi");
$exec = mysqli_query($koneksi, "truncate datareg");
$exec = mysqli_query($koneksi, "truncate absensi_les");
$exec = mysqli_query($koneksi, "truncate waktu");
$exec = mysqli_query($koneksi, "truncate temp_finger");
unlink('../../neural.json');
$from = '../../json/neural.json'; 
$to = '../../neural.json'; 
$kopi = copy($from,$to) ; 
	
?>