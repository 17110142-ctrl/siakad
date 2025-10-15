<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'm_bayar', ['id' => $id]);
	if($exec){
		$query = "SELECT * FROM m_bayar ORDER BY id";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id'];
	 $query2 = "UPDATE m_bayar SET id = $no WHERE id = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE m_bayar  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}
if ($pg == 'tambah') {
	if($_POST['model']=='1'){
		$jumlah = '1';
		$angsur = $_POST['total']/1;
	}else{
		$jumlah = '12';
		$angsur = $_POST['total']/12;
	}
	 $cek = rowcount($koneksi, 'm_bayar', ['kode' => $_POST['kode']]);
    if ($cek > 0) {
        echo "gagal";
    } else {
   $data = [
	    'kode'     => $_POST['kode'],
        'nama'     => $_POST['nama'],
       'model'     => $_POST['model'],
	   'total'     => $_POST['total'],
	   'jumlah' =>$jumlah,
	   'angsuran'     => $angsur
			];
			$exec = insert($koneksi, 'm_bayar', $data);	                   		
	  }
}
if ($pg == 'edit') {
	$id = $_POST['id'];
    if($_POST['model']=='1'){
		$jumlah = '1';
		$angsur = $_POST['total']/1;
	}else{
		$jumlah = '12';
		$angsur = $_POST['total']/12;
	}
       $data = [
	    'kode'     => $_POST['kode'],
        'nama'     => $_POST['nama'],
       'model'     => $_POST['model'],
	   'total'     => $_POST['total'],
	   'jumlah' =>$jumlah,
	   'angsuran'     => $angsur
			];
   
    $exec = update($koneksi, 'm_bayar', $data, ['id' => $id]);
    echo $exec;
	
}
if ($pg == 'bayar') {
       $data = [
	    'kelas'     => $_POST['kelas'],
        'idb'     => $_POST['idb']    
			];
   
    $exec = update($koneksi, 'k_bayar', $data);
    echo $exec;
	
}

?>