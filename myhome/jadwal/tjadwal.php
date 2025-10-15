<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

session_start();

// Asumsikan variabel $setting diambil dari database atau file konfigurasi
// Contoh: $setting = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM setting WHERE id_setting='1'"));

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'jadwal_mapel', ['id_jadwal' => $id]);
    
    if ($exec) {
        $query = "SELECT * FROM jadwal_mapel ORDER BY id_jadwal";
        $hasil = mysqli_query($koneksi, $query);
        $no = 1;

        while ($data = mysqli_fetch_array($hasil)) {
            $id_jadwal = $data['id_jadwal'];
            $query2 = "UPDATE jadwal_mapel SET id_jadwal = $no WHERE id_jadwal = '$id_jadwal'";
            mysqli_query($koneksi, $query2);
            $no++;
        }

        $query = "ALTER TABLE jadwal_mapel AUTO_INCREMENT = $no";
        mysqli_query($koneksi, $query);
    }
}

if ($pg == 'tambah') {
    // Jika login sebagai guru, paksa field guru menggunakan ID sesi
    if ($_SESSION['level'] == 'guru') {
        $_POST['guru'] = $_SESSION['id_user'];
    }

    $level = $_POST['level'];
    $kuri = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='$level'"));

    // Data yang akan dimasukkan ke tabel jadwal_mapel
    $data = [
        'tingkat'   => $_POST['level'],
        'kelas'     => $_POST['kelas'],
        'mapel'     => $_POST['mapel'],
        'guru'      => $_POST['guru'],
        'hari'      => $_POST['hari'],
        'kuri'      => $kuri['kurikulum'],
        'semester'  => $setting['semester'], // Penambahan kolom semester
        'tp'        => $setting['tp']        // Penambahan kolom tp
    ];

    $exec = insert($koneksi, 'jadwal_mapel', $data);
    echo "OK";
}

if ($pg == 'kelas') {
    $id_level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='$id_level'");

    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}
?>