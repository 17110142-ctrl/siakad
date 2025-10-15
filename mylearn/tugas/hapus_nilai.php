<?php
header('Content-Type: application/json');
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

$id_jawaban = $_POST['id'];
$catatan = addslashes($_POST['catatan']);

// 1. Ambil data yang diperlukan SEBELUM dihapus
$jawaban = fetch($koneksi, 'jawaban_tugas', ['id_jawaban' => $id_jawaban]);
if (!$jawaban) {
    echo json_encode(['status' => 'error', 'message' => 'Jawaban tidak ditemukan.']);
    exit();
}

$siswa = fetch($koneksi, 'siswa', ['id_siswa' => $jawaban['id_siswa']]);
$tugas = fetch($koneksi, 'tugas', ['id_tugas' => $jawaban['id_tugas']]);
$mapel = fetch($koneksi, 'mata_pelajaran', ['kode' => $tugas['mapel']]);
$setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);

// 2. Kirim notifikasi penolakan terlebih dahulu
if ($setting && !empty($setting['url_api']) && !empty($siswa['nowa'])) {
    $pesan = "PEMBERITAHUAN JAWABAN DITOLAK\n\n" .
             "Yth. Bapak/Ibu Orang Tua dari ananda " . $siswa['nama'] . ",\n\n" .
             "Jawaban tugas ananda telah ditolak oleh guru dengan alasan berikut:\n\n" .
             "Mapel: *" . $mapel['nama_mapel'] . "*\n" .
             "Judul: *" . $tugas['judul'] . "*\n\n" .
             "Catatan Guru:\n_" . $catatan . "_\n\n" .
             "Silakan perbaiki dan kirimkan ulang jawaban Anda melalui SIAKAD. Terima kasih.\n\n" .
             "*" . $setting['sekolah'] . "*";

    // Kirim WA
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $setting['url_api'] . '/send-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $siswa['nowa']]
    ]);
    curl_exec($curl);
    curl_close($curl);
}

// 3. Hapus file jika ada
if (!empty($jawaban['file']) && is_file("../../../tugas/" . $jawaban['file'])) {
    unlink("../../../tugas/" . $jawaban['file']);
}

// 4. Hapus jawaban dari database
$delete = delete($koneksi, 'jawaban_tugas', ['id_jawaban' => $id_jawaban]);

if ($delete) {
    echo json_encode(['status' => 'success', 'message' => 'Jawaban siswa telah dihapus dan notifikasi penolakan terkirim.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus jawaban dari database.']);
}
?>
