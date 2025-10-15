<?php
// Memulai sesi di baris paling atas adalah praktik terbaik
session_start();

// Memuat file-file konfigurasi
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
require("../config/apk.php");

// =================================================================
// <<< PERBAIKAN UTAMA: Panggil Pengecek Sesi dengan Path yang Andal >>>
// Kode ini akan memeriksa apakah sesi ini masih valid atau sudah digantikan.
$check_session_path = __DIR__ . '/../myhome/check_session.php'; // Membuat path yang lebih aman
if (file_exists($check_session_path)) {
    require_once($check_session_path);
} else {
    // Jika file tidak ditemukan, hentikan eksekusi dan berikan pesan error yang jelas.
    // Ini akan mencegah HTTP Error 500 dan membantu debugging.
    die("Kesalahan Fatal: File 'check_session.php' tidak ditemukan. Pastikan file tersebut ada di folder utama (satu level di atas folder dashboard ini).");
}
// =================================================================

// Logika untuk memeriksa apakah siswa sudah login
(isset($_SESSION['id_siswa'])) ? $id_siswa = $_SESSION['id_siswa'] : $id_siswa = 0;
($id_siswa == 0) ? header("Location:mulai") : null;

$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';


// =================================================================
// BLOK PENJAGA GERBANG DENGAN SWEETALERT LANGSUNG
// =================================================================

// Definisikan kolom yang wajib diisi
$kolom_wajib_gate = [
    "nama" => "Nama Lengkap", "nisn" => "NISN", "nokk" => "NO. KK", "nik" => "NIK",
    "t_lahir" => "Tempat Lahir", "tgl_lahir" => "Tanggal Lahir", "jk" => "Jenis Kelamin",
    "agama" => "Agama", "kewarganegaraan" => "Kewarganegaraan", "email" => 'Email Siswa',
    "t_badan" => "Tinggi Badan", "b_badan" => "Berat Badan", "l_kepala" => "Lingkar Kepala",
    "anakke" => "Anak Ke", "jumlah_saudara" => "Jumlah Saudara", "cita_cita" => "Cita-Cita",
    "hobi" => "Hobi", "asal_sek" => "Asal Sekolah", 'thn_lulus' => "Tahun Lulus",
    'beasiswa' => "Beasiswa", 'rt' => "RT", 'rw' => "RW", 'kelurahan' => "Desa/Kelurahan",
    'kecamatan' => "Kecamatan", 'kabupaten' => "Kabupaten", 'provinsi' => "Provinsi",
    'kode_pos' => "Kode Pos", 'nama_ayah' => "Nama Ayah", 'status_ayah' => "Status Ayah",
    'nama_ibu' => "Nama Ibu", 'status_ibu' => "Status Ibu", 'kk_ibu' => "Upload KK",
];

$kolom_kosong_gate = [];
if ($siswa) {
    foreach ($kolom_wajib_gate as $kolom_db => $nama_tampilan) {
        if (empty($siswa[$kolom_db])) {
            $kolom_kosong_gate[] = $nama_tampilan;
        }
    }
} else {
    // Jika data siswa tidak ditemukan, anggap semua wajib diisi
    $kolom_kosong_gate = array_values($kolom_wajib_gate);
}

// Jika ada kolom yang kosong, lakukan pengecekan halaman
if (!empty($kolom_kosong_gate)) {
    $halaman_diizinkan = ['profil_siswa', 'edit_profil', 'logout'];
    $halaman_sekarang = ($pg == '') ? 'home' : $pg;

    if (!in_array($halaman_sekarang, $halaman_diizinkan)) {
        // Alih-alih redirect, kita tampilkan SweetAlert di sini & hentikan sisa halaman
        include "top.php"; // Muat <head> agar SweetAlert punya dasar HTML

        $pesan_error_html = "<ul>";
        foreach ($kolom_kosong_gate as $pesan) {
            $pesan_error_html .= "<li style='text-align: left; margin-left: 20px;'>$pesan</li>";
        }
        $pesan_error_html .= "</ul>";

        echo "
        <body>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Profil Anda Belum Lengkap!',
                        html: '<p>Untuk dapat mengakses fitur ini, Anda harus melengkapi data berikut terlebih dahulu:</p>" . addslashes($pesan_error_html) . "',
                        confirmButtonText: 'Lengkapi Profil Sekarang',
                        confirmButtonColor: '#3085d6',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '?pg=profil_siswa';
                        }
                    });
                });
            </script>
        </body>
        </html>
        ";
        
        exit(); // Hentikan eksekusi seluruh sisa halaman
    }
}
// =================================================================
// BLOK PENJAGA GERBANG - Selesai
// =================================================================

?>

<?php include "top.php"; ?>
<body>
<?php if ($sidebar == ''): ?>
    <div class="app align-content-stretch d-flex flex-wrap">
<?php else: ?>
    <div class="app menu-off-canvas align-content-stretch d-flex flex-wrap">
<?php endif; ?>
    
    <div class="app-sidebar">
        <div class="logo">
            <img src="<?= $homeurl ?>/images/<?= $setting['logo'] ?>" style="no-repeat;max-width:40px">
            <span class="logo-text hidden-on-mobile" style="font-size:12px;font-weight:bold;color:black;"><?= $setting['sekolah'] ?></span>
             <div class="sidebar-user-switcher user-activity-online">
                
             </div>
        </div>
        <?php include "menu.php"; ?>
    </div>

    <div class="app-container">
        <?php include "nav.php"; ?>
        <div class="app-content">
            <?php include "pages.php"; ?>
        </div>
    </div>

</div>
<?php include "footer.php"; ?>
</body>
</html>
