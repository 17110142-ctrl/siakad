<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
cek_session_siswa();

$id_tugas = $_POST['id_tugas'];
$id_siswa = $_SESSION['id_siswa'];
$nama_mapel = isset($_POST['nama_mapel']) ? $_POST['nama_mapel'] : '';
$jawaban = addslashes($_POST['jawaban']);

$datetime = date('Y-m-d');

// Debug: Cek apakah data diterima dari form
if (empty($id_tugas) || empty($id_siswa) || empty($nama_mapel)) {
    echo "Error: Data tidak lengkap!";
    exit;
}    
if ($_FILES['file']['name'] <> '') {	

 $filename = $_FILES['file']['name'];
 $ext = explode('.', $filename);
 $ext = end($ext);
  $file = $id_tugas . '_' . $id_siswa . '.' . $ext;
 if(move_uploaded_file($_FILES["file"]["tmp_name"],'../tugas/'.$file)){

   $datax = array(
                'id_tugas' => $id_tugas,
                'id_siswa' => $id_siswa,
                'jawaban' => $jawaban,
                'file' => $file,
                'nama_mapel' => $nama_mapel
            );
            $where = array(
                'id_siswa' => $id_siswa,
                'id_tugas' => $id_tugas
            );
            $cek = rowcount($koneksi, 'jawaban_tugas', $where);
            if ($cek == 0) {
                insert($koneksi, 'jawaban_tugas', $datax);
				
            } else {
                update($koneksi, 'jawaban_tugas', $datax, $where);
            }
            echo "ok";
            
    }
    

} else {
    $data = array(
        'id_tugas' => $id_tugas,
        'id_siswa' => $id_siswa,
        'jawaban' => $jawaban,
		'tgl_dikerjakan' => $datetime,
		'nama_mapel' => $nama_mapel

    );
    $where = array(
        'id_siswa' => $id_siswa,
        'id_tugas' => $id_tugas,
        
        
    );
    // Debug: Cek apakah nama_mapel ada di data yang akan dimasukkan
if (!isset($data['nama_mapel']) || empty($data['nama_mapel'])) {
    echo "Error: Nama Mapel tidak ditemukan!";
    exit;
}
    $cek = rowcount($koneksi, 'jawaban_tugas', $where);
    if ($cek == 0) {
        insert($koneksi, 'jawaban_tugas', $data);
		
    } else {
        update($koneksi, 'jawaban_tugas', $data, $where);
    }
    echo "ok";
}