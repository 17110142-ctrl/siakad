<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
cek_session_siswa();
$ids = $_POST['ids'];
		$data= [
	   't_lahir'  => $_POST['tlahir'],
	   'tgl_lahir'  => $_POST['tgllahir'],
       'agama'   => $_POST['agama'],
	   'alamat'   => $_POST['alamat'],
	   'username'  => $_POST['username'],
	   'password'  => $_POST['password']
	   
        ];
		$exec = update($koneksi, 'siswa', $data, ['id_siswa' => $ids]);
	
 $ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = 'images/fotosiswa/';
      $path = $dest . $file;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto' => $file
		];
		 $exec = update($koneksi, 'siswa', $datax, ['id_siswa' => $ids]);
	}
   }
   }
   ?>