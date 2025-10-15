<?php
header('Content-Type: application/json');
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

// Ambil data POST
$id_tugas = $_POST['id_tugas'];
$nama_siswa = $_POST['nama_siswa'];
$nowa_siswa = $_POST['nowa_siswa'];

// Ambil detail yang diperlukan untuk notifikasi
$tugas_data = fetch($koneksi, 'tugas', ['id_tugas' => $id_tugas]);
$setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
$guru = fetch($koneksi, 'users', ['id_user' => $tugas_data['id_guru']]);
$mapel_info = fetch($koneksi, 'mata_pelajaran', ['kode' => $tugas_data['mapel']]);

if ($setting && !empty($setting['url_api']) && $tugas_data) {
    $pesan = "PEMBERITAHUAN PERUBAHAN TUGAS\n\n" .
             "Yth. Bapak/Ibu Orang Tua dari ananda " . $nama_siswa . ",\n\n" .
             "Dengan ini kami memberitahukan bahwa ada perubahan pada tugas:\n" .
             "Mapel: *" . $mapel_info['nama_mapel'] . "*\n" .
             "Judul: *" . $tugas_data['judul'] . "*\n" .
             "Guru: " . $guru['nama'] . "\n" .
             "Batas Waktu: " . date('d-m-Y H:i', strtotime($tugas_data['tgl_selesai'])) . "\n\n" .
             "Mohon untuk memeriksa kembali detail tugas. Terima kasih.\n\n" .
             "*" . $setting['sekolah'] . "*";

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $setting['url_api'] . '/send-message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $nowa_siswa]
    ]);
    $wa_response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($http_code == 200) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'http_code' => $http_code]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Setting API atau data tugas tidak ditemukan.']);
}
?>
