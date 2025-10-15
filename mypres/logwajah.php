
<?php

require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
 (isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
?>  
<?php if ($pg == 'pegawai') : ?>
<?php
$query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' and level='pegawai' and mesin='RFID WAJAH' and ket='H' ORDER BY id DESC LIMIT 1"); 
 
while ($data = mysqli_fetch_array($query)) :
$peg = fetch($koneksi,'users',['id_user'=>$data['idpeg']]);
?> 

<h5><?= $peg['nama'] ?> >>> Masuk Jam : <?= $data['masuk'] ?></h5>
<?php endwhile; ?>
<?php endif; ?>
<?php if ($pg == 'siswa') : ?>
<?php
$queryx = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' and level='siswa' and mesin='RFID WAJAH' and ket='H' ORDER BY id DESC LIMIT 1"); 
 
while ($datax = mysqli_fetch_array($queryx)) :
$siswax = fetch($koneksi,'siswa',['id_siswa'=>$datax['idsiswa']]);
?> 

<h5><?= $siswax['nama'] ?> >>> Masuk Jam : <?= $datax['masuk'] ?></h5>
<?php endwhile; ?>

<?php endif; ?>