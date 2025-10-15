<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	 $kode = $_POST['eskul'];
	  $guru = $_POST['guru'];
    $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM m_eskul WHERE eskul='$kode' AND guru='$guru'"));
        if ($cek > 0) :
            echo "GAGAL";
        else :
		
        $exec = mysqli_query($koneksi, "INSERT INTO m_eskul (eskul,guru) VALUES ('$kode','$guru')");
			if ($exec) {
			$simpan = mysqli_query($koneksi, "UPDATE users set tugas='Pembina' where id_user='$guru'");	
                echo "OK";
            } else {
                echo mysqli_error($koneksi);
            }
       endif;
}
if ($pg == 'hapus') {
    $id = $_POST['id'];
	$user = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_eskul  WHERE id='$id'"));
	mysqli_query($koneksi,"UPDATE users set tugas=NULL WHERE id_user='$user[guru]'");
    $exec = delete($koneksi, 'm_eskul', ['id' => $id]);
    echo $exec;
}
