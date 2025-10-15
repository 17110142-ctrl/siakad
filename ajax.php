<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';


    $nokartu = $_POST['nokartu'];
	$query = mysqli_query($koneksi, "select * from datareg where nokartu='$nokartu'");
$cek = mysqli_num_rows($query);

if ($cek ==0) {
	if ($pg == 'pegawai') {
	
	$path = $_POST['path'];
	$img = $_POST['image'];
    $idpeg = $_POST['idpeg'];
  
	$nama = $_POST['nama'];
	$peg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$nama'"));
	mkdir('data/' . $path, 0777, true);

	define('orig_dir', 'data/' . $path . '/');
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	
	$fi = new FilesystemIterator(orig_dir, FilesystemIterator::SKIP_DOTS);
	$file = orig_dir . 'image' . iterator_count($fi). '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file in image directory.';
	
	$data= [
        'folder' => $path,
		'idpeg' => $idpeg,
		'nama' => $peg['nama'],
		'level' => 'pegawai',
		'sts' =>1,
		'nokartu' => $nokartu
		];
		$dataQ=[
	'sts' => 1
	];	
		$exec = insert($koneksi,'datareg', $data);
		if($exec){
			update($koneksi,'users',$dataQ,['id_user'=>$idpeg]);
			mysqli_query($koneksi, "TRUNCATE tmpreg");
		}
	}
if ($pg == 'siswa') {
	
	$path = $_POST['path'];
	$img = $_POST['image'];
    $idsiswa = $_POST['idpeg'];
  
	$nama = $_POST['nama'];
	$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$idsiswa'"));
	mkdir('data/' . $path, 0777, true);

	define('orig_dir', 'data/' . $path . '/');
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	
	$fi = new FilesystemIterator(orig_dir, FilesystemIterator::SKIP_DOTS);
	$file = orig_dir . 'image' . iterator_count($fi). '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file in image directory.';
	
	$data= [
        'folder' => $path,
		'idsiswa' => $idsiswa,
		'nama' => $siswa['nama'],
		'level' => 'siswa',
		'sts' =>1,
		'nokartu' => $nokartu
		];
		$dataQ=[
	'sts' => 1
	];	
		$exec = insert($koneksi,'datareg', $data);
		if($exec){
			update($koneksi,'siswa',$dataQ,['id_siswa'=>$idsiswa]);
			mysqli_query($koneksi, "TRUNCATE tmpreg");
		}
	}		
}
?>