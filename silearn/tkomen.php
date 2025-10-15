<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'users', ['id_user' => $id]);
	if($exec){
		$query = "SELECT * FROM users ORDER BY id_user";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id_user'];
	 $query2 = "UPDATE users SET id_user = $no WHERE id_user = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE users  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}
if ($pg == 'tambah') {
	$id_siswa=$_SESSION['id_siswa'];
	$guru=$_POST['guru'];
	$tgl=date('Y-m-d H:i:s');
	$id_materi=$_POST['id_materi'];
	$komentar=$_POST['komentar'];
	mysqli_query($koneksi,"INSERT INTO komentar(id_user,id_materi,komentar,jenis,tgl,guru) VALUES('$id_siswa','$id_materi','$komentar','1','$tgl','$guru')");
}
if ($pg == 'edit') {
	$id_user = $_POST['iduser'];
    if ($_POST['password'] <> "") {
        $data = [
       'nip'     => $_POST['nip'],
        'username'     => $_POST['username'],
        'nama'   => $_POST['nama'],
		'nowa'   => $_POST['nowa'],
        'level'  => 'staff',
        'password'  => $_POST['password']
        ];
    } else {
        $data = [
       'nip'     => $_POST['nip'],
        'username'     => $_POST['username'],
        'nama'   => $_POST['nama'],
		'nowa'   => $_POST['nowa'],
        'level'  => 'staff'
      
        ];
    }
   
    $exec = update($koneksi, 'users', $data, ['id_user' => $id_user]);
    echo $exec;
	 $ektensi = ['JPG', 'png', 'JPEG', 'jpg', 'jpeg', 'PNG'];
   if ($_FILES['file']['name'] != '') {
   $file = $_FILES['file']['name'];
   $temp = $_FILES['file']['tmp_name'];
   $ext = explode('.', $file);
   $ext = end($ext);
   if (in_array($ext, $ektensi)) {
       $dest = '../../images/fotoguru/';
      $path = $dest . $file;
      $upload = move_uploaded_file($temp, $path);
	if ($upload) {
		$datax = [
		'foto' => $file
		];
		 $exec = update($koneksi, 'users', $datax, ['id_user' => $id_user]);
	}
   }
   }
}
?>