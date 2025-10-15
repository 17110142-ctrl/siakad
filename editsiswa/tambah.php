<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

	 $ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != ''){
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/fotosiswa/';
      $path = $dest . $file;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
       'nis'     => $_POST['nis'],
        'nisn'     => $_POST['nisn'],
        'nama'   => addslashes($_POST['nama']),
		'agama'   => $_POST['agama'],
        'level'  => $_POST['level'],
       'kelas'  => $_POST['kelas'],
	   'jk'  => $_POST['jk'],
	   'jurusan'  => $_POST['pk'],
	   'username'  => $_POST['username'],
	   'password'  => $_POST['password'],
	   'foto'    => $file,
	   'nowa'  => $_POST['nowa']
        ];
		 $exec = insert($koneksi, 'siswa', $datax);
	}
   }
   
   }else{
     $data = [
       'nis'     => $_POST['nis'],
        'nisn'     => $_POST['nisn'],
        'nama'   => addslashes($_POST['nama']),
		'agama'   => $_POST['agama'],
        'level'  => $_POST['level'],
       'kelas'  => $_POST['kelas'],
	   'jk'  => $_POST['jk'],
	   'jurusan'  => $_POST['pk'],
	    'username'  => $_POST['username'],
	   'password'  => $_POST['password'],
	   'nowa'  => $_POST['nowa']
        ];
    
   
    $exec = insert($koneksi, 'siswa', $data);
   
   }
?>