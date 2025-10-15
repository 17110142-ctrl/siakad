<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	
  $where = [
				
				'nis'=>$_POST['nis'],
				'p_dimensi'=>$_POST['dimensi']
    ];
    $data = [
				
				'nis' => $_POST['nis'],
				'p_dimensi'=>$_POST['dimensi'],
				 'p_elemen'=>$_POST['elemen'],
				 'p_sub'=>$_POST['sub_elemen']
				
    ];
	
	 $cek = rowcount($koneksi, 'barusikap', $where);
            if ($cek == 0) {
            $exec = insert($koneksi, 'barusikap', $data);
            echo $exec;
        }
}

if ($pg == 'hapusspi') {
    $id = $_POST['id'];
	
    delete($koneksi, 'barusikap', ['idp' => $id]);
	
}
if ($pg == 'ambil_elemen') {
    $id_dimensi = $_POST['dimensi'];
    $sql = mysqli_query($koneksi, "SELECT * FROM m_elemen WHERE iddimensi='" . $id_dimensi . "' ");
    echo "<option value=''>--Pilih Elemen--</option>";
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[id_elemen]'>$data[elemen]</option>";
    }
}
if ($pg == 'ambil_sub_elemen') {
    $id_elemen = $_POST['elemen'];
    $sql = mysqli_query($koneksi, "SELECT * FROM m_sub_elemen WHERE idelemen='" . $id_elemen . "' ");
    echo "<option value=''>--Pilih Sub Elemen--</option>";
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[id_sub]'>$data[sub_elemen]</option>";
    }
}