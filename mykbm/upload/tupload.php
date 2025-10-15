<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
$nama = $_POST['nama'];
$guru = $_POST['guru'];
$mapel = $_POST['mapel'];
$kelas = $_POST['kelas'];
$tgl = date('Y-m-d');
$link = $_POST['link'];

       if ($_FILES['file']['name'] <> '') {
            $file = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $upload = move_uploaded_file($temp, '../mykbm/fileadm/' . $file);
                if ($upload) {
					$data = [
					'kelas' =>$kelas,
					'idmapel' =>$mapel,
					'idguru' =>$guru,
					'nama' =>$nama,
					'tanggal' =>$tgl,
					'file'=>$file
					];
					insert($koneksi, 'adm', $data);
                 echo "OK";
                } else {
                    echo "gagal";
                }
            }else{
				$data = [
					'kelas' =>$kelas,
					'idmapel' =>$mapel,
					'idguru' =>$guru,
					'nama' =>$nama,
					'tanggal' =>$tgl,
					'link'=>$link
					];
					insert($koneksi, 'adm', $data);
                 echo "OK";
			}
       
}
if ($pg == 'hapus') {
	$kode = $_POST['id'];

$query = "SELECT * FROM adm WHERE id='".$kode."'";
		$sql = mysqli_query($koneksi, $query); 
		$data = mysqli_fetch_array($sql);

		if(is_file("../mykbm/fileadm/".$data['file'])) 
			unlink("../mykbm/fileadm/".$data['file']); 
$exec = mysqli_query($koneksi, "DELETE FROM adm WHERE id='$kode'");
}