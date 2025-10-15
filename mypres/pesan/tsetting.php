<?php
require("../../config/koneksi.php");


(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';


function simpan_pesan($koneksi, $id) {
    $pesan1 = $_POST['pesan1'] ?? '';
    $pesan2 = $_POST['pesan2'] ?? '';
    $pesan3 = $_POST['pesan3'] ?? '';
    $pesan4 = $_POST['pesan4'] ?? '';

    // Langkah 1: Cek dulu apakah data dengan ID tersebut ada
    $stmt_cek = mysqli_prepare($koneksi, "SELECT id FROM m_pesan WHERE id = ?");
    mysqli_stmt_bind_param($stmt_cek, "i", $id);
    mysqli_stmt_execute($stmt_cek);
    $result = mysqli_stmt_get_result($stmt_cek);
    mysqli_stmt_close($stmt_cek);
    
    if (mysqli_num_rows($result) > 0) {
        // Langkah 2a: Jika ada, UPDATE data yang ada
        $stmt = mysqli_prepare($koneksi, "UPDATE m_pesan SET pesan1 = ?, pesan2 = ?, pesan3 = ?, pesan4 = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $pesan1, $pesan2, $pesan3, $pesan4, $id);
    } else {
        // Langkah 2b: Jika tidak ada, INSERT data baru
        $stmt = mysqli_prepare($koneksi, "INSERT INTO m_pesan (id, pesan1, pesan2, pesan3, pesan4) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issss", $id, $pesan1, $pesan2, $pesan3, $pesan4);
    }

    // Langkah 3: Eksekusi query dan tutup statement
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Menentukan ID yang akan diupdate berdasarkan nilai 'pg' dari pesansis.php
$target_id = null;
if ($pg == 'hadir') $target_id = 1;
if ($pg == 'pulang') $target_id = 2;
if ($pg == 'izin') $target_id = 9;
if ($pg == 'sakit') $target_id = 10;
if ($pg == 'alpa') $target_id = 11;

if ($target_id !== null) {
    simpan_pesan($koneksi, $target_id);
} 
elseif ($pg == 'hps') {
    mysqli_query($koneksi, "TRUNCATE TABLE pesan_terkirim");
}

?>
