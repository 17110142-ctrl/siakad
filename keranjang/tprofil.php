<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

	$ids = $_POST['ids'];
    
        $data = [
       'password'     => $_POST['password']
      
        ];
    
    $exec = update($koneksi, 'siswa', $data, ['id_siswa' => $ids]);

$ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../images/foto/';
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