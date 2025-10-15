<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'ubah') {
    $sttd = (isset($_POST['sttd'])) ? 1 : 0;
    $sstempel = (isset($_POST['sstempel'])) ? 1 : 0;
    $nilai = (isset($_POST['nilai'])) ? 1 : 0;
    $kelompok = (isset($_POST['kelompok'])) ? 1 : 0;
    $data = [
	    'tingkat' => $_POST['tingkat'],
        'nama_surat' => $_POST['nama'],
        'no_surat' => $_POST['no_surat'],
        'tgl_surat' => $_POST['tgl_surat'],
        'pembuka' => $_POST['pembuka'],
        'isi_surat' => $_POST['isi'],
        'penutup' => $_POST['penutup'],
       'dibuka' => $_POST['dibuka'],
	   'ditutup' => $_POST['ditutup'],
        'sstempel' => $sstempel,
        'sttd' => $sttd,
        'nilai' => $nilai,
        'kelompok' => $kelompok

    ];
    $where = [
        'id_skl' => 1
    ];
    $exec = update($koneksi, 'skl', $data, $where);
    echo mysqli_error($koneksi);
    if ($exec) {
        $ektensi = ['jpg', 'png', 'JPG', 'PNG'];
        if ($_FILES['header']['name'] <> '') {
            $header = $_FILES['header']['name'];
            $temp = $_FILES['header']['tmp_name'];
            $ext = explode('.', $header);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
                $dest = 'images/header' . rand(1, 1000) . '.' . $ext;
                $upload = move_uploaded_file($temp, '../' . $dest);
                if ($upload) {
                    $data2 = [
                        'header' => $dest
                    ];
					$data3 = [
                        'file' => $dest
                    ];
                    $exec = update($koneksi, 'skl', $data2, $where);
					 $exec = update($koneksi, 'skkb', $data3);
                } else {
                    echo "gagal";
                }
            }
        }
        
        
    } else {
        echo "Gagal menyimpan";
    }
}
if ($pg == 'ambil_mapel') {
    $klp = $_POST['kodemap'];
    $sql = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE kode='" . $klp . "' ");
   
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[nama_mapel]'>$data[nama_mapel]</option>";
    }
}
if ($pg == 'ambil_kelas') {
    $level = $_POST['level'];
	$pk = $_POST['pk'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='$level' and pk='$pk' ");
   
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}
if ($pg == 'hapusmapel') {
   $id = $_POST['id'];
    delete($koneksi, 'mapel_ijazah', ['idmapel' => $id]);
}
if ($pg == 'ambil') {
    $klp = $_POST['kelompok'];
	$pk = $_POST['pk'];
    $sql = mysqli_query($koneksi, "SELECT * FROM mapel_ijazah WHERE kelompok='$klp' and jurusan='$pk'");
    echo "<option value=''>--Pilih Mapel--</option>";
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kode]'>$data[namamapel]</option>";
    }
}