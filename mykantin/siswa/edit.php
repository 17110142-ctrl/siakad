<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'siswa', ['id_siswa' => $id]);
	if($exec){
		$query = "SELECT * FROM siswa ORDER BY id_siswa";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id_siswa'];
	 $query2 = "UPDATE siswa SET id_siswa = $no WHERE id_siswa = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE siswa  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}

if ($pg == 'edit') {
	$ids = $_POST['ids'];
   
        $data = [
       'nis'     => $_POST['nis'],
        'nama'   => $_POST['nama'],
       'kelas'  => $_POST['kelas'],
	   'jk'  => $_POST['jk'],
	   'password'  => $_POST['password'],
	   'nowa'  => $_POST['nowa']
        ];
    
   
    $exec = update($koneksi, 'siswa', $data, ['id_siswa' => $ids]);
    
	 $ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/foto/';
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
}

?>