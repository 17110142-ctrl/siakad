<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'kontakme', ['id' => $id]);
	if($exec){
		$query = "SELECT * FROM kontakme ORDER BY id";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id'];
	 $query2 = "UPDATE kontakme SET id = $no WHERE id = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE kontakme  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}
if ($pg == 'tambah') {
	
	 $cekuser = rowcount($koneksi, 'kontakme', ['nowa' => $_POST['nowa']]);
    if ($cekuser > 0) {
        echo "gagal";
    } else {
  
     $data = [
	    'nowa'     => $_POST['nowa'],
        'pemilik'     => $_POST['pemilik'],
        'nama_kontak'   => $_POST['nama']
		
			];
			$exec = insert($koneksi, 'kontakme', $data);
			echo "OK";                   		
	  }
        
}
  
if ($pg == 'edit') {
	$id = $_POST['id'];
   
        $data = [
       'nowa'     => $_POST['nowa'],
        'nama_kontak'   => $_POST['nama']
        ];
    
   
    $exec = update($koneksi, 'kontakme', $data, ['id' => $id]);
    
}
?>