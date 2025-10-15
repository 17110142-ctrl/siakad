<?php

require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// JIKA ada POST nilai_raport (array) → simpan/update ke nilai_formatif
if (isset($_POST['nilai_raport']) && is_array($_POST['nilai_raport'])) {
    $kelas    = $_POST['kelas'] ?? '';
    $mapel    = $_POST['mapel'] ?? '';
    $guru     = $_POST['id_user'] ?? '';
    $semester = $setting['semester'];
    $tapel    = $setting['tp'];

    foreach ($_POST['nilai_raport'] as $id_siswa => $nilai) {
        if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
            echo "Nilai tidak valid untuk ID siswa $id_siswa"; exit;
        }

        // Ambil NIS siswa
        $q = mysqli_query($koneksi, "SELECT nis FROM siswa WHERE id_siswa='" . mysqli_real_escape_string($koneksi, $id_siswa) . "'");
        $siswa = mysqli_fetch_assoc($q);
        if (!$siswa) {
            echo "Siswa tidak ditemukan (ID: $id_siswa)"; exit;
        }
        $nis = $siswa['nis'];

        // Cek apakah data nilai_formatif sudah ada
        $cek = mysqli_query($koneksi, 
            "SELECT 1 FROM nilai_rapor
             WHERE nis='$nis' AND semester='$semester' AND tp='$tapel' 
             AND guru='$guru' AND mapel='$mapel'"
        );

        if (mysqli_num_rows($cek) > 0) {
            // Update nilai_raport
            mysqli_query($koneksi, 
                "UPDATE nilai_rapor 
                 SET nilai='$nilai' 
                 WHERE nis='$nis' AND semester='$semester' AND tp='$tapel' 
                 AND guru='$guru' AND mapel='$mapel'"
            );
        } else {
            // Insert data baru
            mysqli_query($koneksi, 
                "INSERT INTO nilai_rapor 
                 (nis, kelas, mapel, guru, nilai, semester, tp) 
                 VALUES 
                 ('$nis', '$kelas', '$mapel', '$guru', '$nilai', '$semester', '$tapel')"
            );
        }
    }

    echo 'OK';
    exit;
}

// … kode lama untuk satuan insert nilai_formatif (tinggi/rendah TP) tetap disimpan di bawah ini …

$nis    = $_POST['nis']   ?? '';
$kelas  = $_POST['kelas'] ?? '';
$kode   = $_POST['mapel'] ?? '';
$guru   = $_POST['guru']  ?? '';
$tinggi = isset($_POST['tinggi']) ? implode(', ', $_POST['tinggi']) : '';
$rendah = isset($_POST['rendah']) ? implode(', ', $_POST['rendah']) : '';

$cek2 = mysqli_query($koneksi,
    "SELECT 1 FROM nilai_formatif 
     WHERE nis='$nis' AND mapel='$kode' AND guru='$guru'"
);
if (mysqli_num_rows($cek2) > 0) {
    echo 'gagal'; exit;
}
if ($tinggi === $rendah) {
    echo 'Pilihan TP tercapai dan kurang tidak boleh sama.'; exit;
}

$smt = $setting['semester'];
$tp  = $setting['tahun']; // memastikan ini terdefinisi
$sql2 = "
  INSERT INTO nilai_rapor
    (nis, kelas, mapel, tinggi, rendah, semester, tp, guru)
  VALUES
    ('$nis','$kelas','$kode','$tinggi','$rendah','$smt','$tp','$guru')
";
if (mysqli_query($koneksi, $sql2)) {
    echo 'OK';
} else {
    echo 'Error: ' . mysqli_error($koneksi);
}
