<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'ambil_kelas') {
    $id_level = $_POST['tingkat'];
    $sql = mysqli_query($koneksi, "SELECT level,kelas FROM siswa WHERE level='" . $id_level . "' GROUP BY kelas");
    echo "<option value=''>Pilih Kelas</option>";
   while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}
if ($pg == 'ambil_ruang') {
    $sql = mysqli_query($koneksi, "SELECT ruang FROM siswa  GROUP BY ruang");
    echo "<option value=''>Pilih Ruang</option>";
    while ($ruang = mysqli_fetch_array($sql)) {
        echo "<option value='$ruang[ruang]'>$ruang[ruang]</option>";
    }
}
if ($pg == 'ambilkelas') {
    $id_bank = $_POST['mapel_id'];
    $ruang = $_POST['ruang'];
    $sesi = $_POST['sesi'];
     $data = mysqli_query($koneksi, "SELECT siswa.sesi,siswa.ruang,siswa.kelas,siswa.level,banksoal.id_bank,banksoal.level FROM siswa JOIN banksoal ON banksoal.level=siswa.level where siswa.ruang='$ruang' and siswa.sesi='$sesi' and banksoal.id_bank='$id_bank' group by siswa.kelas");
            
            echo "<option value=''>Pilih Kelas</option>";
            while ($kelas = mysqli_fetch_array($data)) {
                echo "<option value='$kelas[kelas]'>$kelas[kelas]</option>";
            }
        
}
if ($pg == 'ambil_sesi') {
    $ruang = $_POST['ruang'];
    $sql = mysqli_query($koneksi, "SELECT sesi,ruang FROM siswa WHERE ruang ='$ruang' GROUP BY sesi");
    echo "<option value=''>Pilih Sesi</option>";
    while ($sesi = mysqli_fetch_array($sql)) {
        echo "<option value='$sesi[sesi]'>$sesi[sesi]</option>";
    }
}
