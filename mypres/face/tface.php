<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

   (isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'hapus') {
    $id = $_POST['id'];
    $reg = fetch($koneksi,'datareg',['id'=>$id]);
	if($reg['level']=='siswa'){
		mysqli_query($koneksi, "update siswa SET sts='0' WHERE id_siswa='$reg[idsiswa]'");
	}
	if($reg['level']=='pegawai'){
		mysqli_query($koneksi, "update users SET sts='0' WHERE id_user='$reg[idpeg]'");
	}
	
	$gambar = glob('../../data/'.$reg['folder'].'/*'); 
  foreach ($gambar as $filex) {
    if (is_file($filex))
        unlink($filex); 
    } 
	rmdir('../../data/'.$reg['folder']);
	
	
	 delete($koneksi, 'datareg', ['id' => $id]);
}


?>
