<?php
// Letakkan file ini di direktori utama Anda, sejajar dengan folder config.
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");

date_default_timezone_set('Asia/Jakarta');
$tanggal_sekarang = date('Y-m-d');
$waktu_sekarang = date('H:i:s');

// 1. Ambil waktu batas alpa dari kolom 'alpha' di database
$waktu_setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT alpha FROM waktu LIMIT 1"));
if (!$waktu_setting || empty($waktu_setting['alpha'])) {
    die("Pengaturan 'alpha' tidak ditemukan di tabel 'waktu'. Proses dihentikan.");
}
$batas_waktu_alpa = $waktu_setting['alpha'];

// 2. Cek apakah waktu saat ini sudah melewati batas waktu alpa
// Jika belum, hentikan script.
if (strtotime($waktu_sekarang) < strtotime($batas_waktu_alpa)) {
    echo "Belum waktunya menjalankan pengecekan alpa. Batas waktu: $batas_waktu_alpa.";
    exit;
}

// 3. Ambil semua siswa yang belum memiliki entri di tabel absensi untuk hari ini
$query_siswa_alpa = "
    SELECT id_siswa, nama, kelas, nowa 
    FROM siswa 
    WHERE id_siswa NOT IN (
        SELECT idsiswa 
        FROM absensi 
        WHERE tanggal = '$tanggal_sekarang' AND idsiswa IS NOT NULL
    )
";

$result_siswa_alpa = mysqli_query($koneksi, $query_siswa_alpa);

if (mysqli_num_rows($result_siswa_alpa) == 0) {
    echo "Tidak ada siswa yang perlu ditandai alpa hari ini.";
    exit;
}

// 4. Siapkan data untuk notifikasi (diambil sekali saja)
$setting_notif = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT url_api FROM setting LIMIT 1"));
$pesan_template_alpa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='11'"));

echo "Memproses " . mysqli_num_rows($result_siswa_alpa) . " siswa untuk ditandai ALPA...\n";

// 5. Loop melalui setiap siswa yang alpa, input absensi, dan kirim notifikasi
while ($siswa = mysqli_fetch_assoc($result_siswa_alpa)) {
    $data_alpa = [
        'tanggal' => $tanggal_sekarang,
        'idsiswa' => $siswa['id_siswa'],
        'kelas' => $siswa['kelas'],
        'ket' => 'A',
        'keterangan' => 'Alpa (Otomatis oleh Sistem)',
        'mesin' => 'SISTEM',
        'level' => 'siswa',
        'bulan' => date('m'),
        'tahun' => date('Y')
    ];

    // Masukkan data alpa ke tabel absensi
    if (insert($koneksi, 'absensi', $data_alpa)) {
        echo "Siswa " . $siswa['nama'] . " berhasil ditandai ALPA.\n";

        // Kirim notifikasi WA jika template dan nomor WA ada
        if ($pesan_template_alpa && !empty($siswa['nowa'])) {
            $pesan_wa = $pesan_template_alpa['pesan1'] . " " . 
                        $pesan_template_alpa['pesan2'] . " *" . $siswa['nama'] . "* " . 
                        $pesan_template_alpa['pesan3'] . " " . date('l, d F Y') . ". " .
                        "Dengan keterangan: *Alpa (Tidak ada keterangan)*. " .
                        $pesan_template_alpa['pesan4'];
            
            kirimNotifikasiOtomatis($koneksi, $setting_notif, $pesan_wa, $siswa['nowa']);
        }
    } else {
        echo "Gagal menandai ALPA untuk siswa " . $siswa['nama'] . ".\n";
    }
}

echo "Proses selesai.";

// Fungsi kirim notifikasi (duplikat dari tabsen.php untuk memastikan file ini bisa berdiri sendiri)
function kirimNotifikasiOtomatis($koneksi, $setting, $pesan, $nomorTujuan) {
    $pesan_log = mysqli_real_escape_string($koneksi, $pesan);
    if (empty($nomorTujuan) || empty($setting['url_api'])) {
        mysqli_query($koneksi, "INSERT INTO pesan_terkirim (hp, pesan, status, waktu) VALUES ('$nomorTujuan', '$pesan_log', 'Gagal: No WA/URL API kosong', NOW())");
        return;
    }
    $nomorFormatted = preg_replace('/[^0-9]/', '', $nomorTujuan);
    if (substr($nomorFormatted, 0, 2) === '62') {
        $nomorFormatted = '0' . substr($nomorFormatted, 2);
    }
    if (substr($nomorFormatted, 0, 1) !== '0') {
        mysqli_query($koneksi, "INSERT INTO pesan_terkirim (hp, pesan, status, waktu) VALUES ('$nomorTujuan', '$pesan_log', 'Gagal: Format nomor salah', NOW())");
        return;
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $setting['url_api'] . '/send-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array('message' => $pesan, 'number' => $nomorFormatted),
        CURLOPT_TIMEOUT => 15,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    ));
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    $status_kirim = 'Gagal';
    if ($error) {
        $status_kirim = 'Gagal: cURL Error - ' . $error;
    } else {
        $response_data = json_decode($response, true);
        if ($response_data && isset($response_data['status']) && $response_data['status'] == true) {
            $status_kirim = 'Terkirim';
        } else {
            $api_message = $response_data['response'] ?? 'API merespon gagal.';
            $status_kirim = 'Gagal: API - ' . $api_message;
        }
    }
    mysqli_query($koneksi, "INSERT INTO pesan_terkirim (hp, pesan, status, waktu) VALUES ('$nomorTujuan', '$pesan_log', '$status_kirim', NOW())");
}
?>
