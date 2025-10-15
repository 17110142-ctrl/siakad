<?php
// Pastikan file ini berada di dalam folder 'tugas'
require("../../config/koneksi.php");

// Periksa apakah ada data 'mapel_kode' yang dikirim melalui POST
if(isset($_POST['mapel_kode'])){
    $mapel_kode = mysqli_real_escape_string($koneksi, $_POST['mapel_kode']);
    $id_guru = $_SESSION['id_user'];
    
    // --- PERUBAHAN LOGIKA DI SINI ---
    // Menambahkan kondisi untuk admin (id_guru = 1)
    $filter_guru_sql = "AND id_guru = '$id_guru'"; // Filter default untuk guru biasa
    if ($id_guru == 1) {
        $filter_guru_sql = ""; // Jika admin, hapus filter by id_guru
    }

    // Query diubah untuk memeriksa rentang tanggal aktif dan kondisi admin.
    $query = "
        SELECT id_materi, judul 
        FROM materi 
        WHERE 
            mapel = '$mapel_kode' 
            $filter_guru_sql
            AND DATE(tgl_mulai) <= CURDATE() 
            AND (DATE(tgl_selesai) >= CURDATE() OR tgl_selesai IS NULL OR DATE(tgl_selesai) = '0000-00-00')
        ORDER BY judul ASC
    ";
    
    $result = mysqli_query($koneksi, $query);
    
    // Periksa apakah ada materi yang ditemukan
    if(mysqli_num_rows($result) > 0){
        echo '<option value="">Pilih Materi (Opsional)</option>';
        while($row = mysqli_fetch_assoc($result)){
            echo '<option value="'.$row['id_materi'].'">'.htmlspecialchars($row['judul']).'</option>';
        }
    } else {
        echo '<option value="">Tidak ada materi aktif untuk mata pelajaran ini</option>';
    }
} else {
    // Respon default jika tidak ada kode mapel yang dikirim
    echo '<option value="">Pilih Mata Pelajaran Terlebih Dahulu</option>';
}
?>
