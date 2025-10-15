<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$tanggal = date('Y-m-d');
$tahun   = date('Y');

// Deteksi jenis request (AJAX naik kelas atau form massal)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // =========================
    // === Mutasi Naik Kelas ===
    // =========================
    if (isset($_POST['id_siswa']) && isset($_POST['kelas_baru'])) {
        $id_siswa   = $_POST['id_siswa'];
        $kelas_baru = $_POST['kelas_baru'];

        // Ambil data siswa
        $q = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
        if (!$q || mysqli_num_rows($q) === 0) {
            exit("Data siswa tidak ditemukan");
        }

        $siswa = mysqli_fetch_assoc($q);
        $level_baru = (int)$siswa['level'] + 1;

        // Update kelas dan level
        $u = mysqli_query($koneksi, 
            "UPDATE siswa 
               SET kelas = '$kelas_baru', 
                   level = '$level_baru' 
             WHERE id_siswa = '$id_siswa'"
        );

        if ($u) {
            echo "OK";
        } else {
            echo "Gagal update";
        }
        exit;
    }

    // ================================
    // === Mutasi Tamat / Keluar ===
    // ================================
    if (isset($_POST['aksi']) && isset($_POST['selected'])) {
        $aksi       = $_POST['aksi']; // 'tamat' atau 'keluar'
        $selected   = $_POST['selected']; // array id_siswa
        $kelas_asal = $_POST['kelas_asal'] ?? '';

        foreach ($selected as $id) {
            $result = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id'");
            $user = mysqli_fetch_assoc($result);
            if (!$user) continue;

            // Daftar kolom yang ingin dimasukkan ke alumni
            $fields = [
                'id_siswa','nis','nisn','nama','kelas','jurusan','jk',
                'email','agama','foto','nowa','prestasi','tingkat','juara',
                't_lahir','tgl_lahir','alamat','anakke','asal_sek','thn_lulus',
                'nokk','nik','kewarganegaraan','jumlah_saudara','cita_cita','hobi',
                'beasiswa','no_kip','no_kks','rt','rw','kelurahan','kecamatan',
                'kabupaten','provinsi','kode_pos','lintang','bujur','nama_ayah',
                'status_ayah','kewarganegaraan_ayah','tempat_lahir_ayah','tgl_lahir_ayah',
                'pendidikan_ayah','penghasilan_ayah','pekerjaan_ayah','no_hp_ayah',
                'nama_ibu','status_ibu','kewarganegaraan_ibu','tempat_lahir_ibu',
                'tgl_lahir_ibu','pendidikan_ibu','penghasilan_ibu','no_hp_ibu','kk_ibu',
                't_badan','b_badan','l_kepala','tgl_mutasi','tahun_lulus'
            ];

            $values = [];
            foreach ($fields as $col) {
                if ($col === 'tgl_mutasi') {
                    $values[] = $tanggal;
                } elseif ($col === 'tahun_lulus') {
                    $values[] = $tahun;
                } else {
                    $values[] = addslashes($user[$col] ?? '');
                }
            }

            // Susun dan jalankan query INSERT
            $sql = "
                INSERT INTO alumni (" . implode(',', $fields) . ")
                VALUES ('" . implode("','", $values) . "')
            ";
            $insert = mysqli_query($koneksi, $sql);

            // ================================================================= //
            // === PERUBAHAN DI SINI ===
            // Jika aksi adalah 'tamat' ATAU 'keluar', dan data berhasil dimasukkan ke tabel alumni,
            // maka hapus data dari tabel siswa.
            // ================================================================= //
            if (($aksi === 'tamat' || $aksi === 'keluar') && $insert) {
                mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa='$id'");
            }
        }

        
        // Tambahkan path dari root domain Anda, yaitu /myhome/
        $pg_kembali_enkripsi = enkripsi('mutasi'); 
        
        // Gunakan path absolut dari root domain
        $redirect_url = "/myhome/?pg={$pg_kembali_enkripsi}&mutasi={$aksi}&kelas={$kelas_asal}";
        
        echo "<script>location.href='{$redirect_url}';</script>";
        exit;
    }
}
?>