<?php
header('Content-Type: application/json'); // Set header untuk respons JSON
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

// Mengambil data dari form
$id_tugas = $_POST['id'];
$id_mapel = addslashes($_POST['mapel']);
$tugas_text = addslashes($_POST['isitugas']);
$judul = $_POST['judul'];
$tgl_mulai = $_POST['tgl_mulai'];
$tgl_selesai = $_POST['tgl_selesai'];
$kelas_array = $_POST['kelas'];
$kelas_serialized = serialize($kelas_array);

$data_to_update = [
    'mapel' => $id_mapel,
    'kelas' => $kelas_serialized,
    'judul' => $judul,
    'tugas' => $tugas_text,
    'tgl_mulai' => $tgl_mulai,
    'tgl_selesai' => $tgl_selesai
];

// Logika upload file (jika ada)
if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
    $file = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $ektensi = ['jpg', 'png', 'docx', 'pdf', 'xlsx', 'pptx', 'ppt', 'doc', 'mp4', '3gp'];
    if (in_array(strtolower($ext), $ektensi)) {
        $dest = '../../tugas/';
        $path = $dest . $file;
        if (move_uploaded_file($temp, $path)) {
            $data_to_update['file'] = $file;
        }
    }
}

// Update data tugas di database
$update = update($koneksi, 'tugas', $data_to_update, ['id_tugas' => $id_tugas]);

if ($update) {
    // --- PERBAIKAN LOGIKA NOTIFIKASI WA ---
    
    // 1. Ambil detail tugas LENGKAP dari database, termasuk id_guru yang benar
    $tugas_data = fetch($koneksi, 'tugas', ['id_tugas' => $id_tugas]);
    $id_guru_pembuat = $tugas_data['id_guru']; // Ini adalah ID guru yang benar

    // 2. Ambil detail lain yang diperlukan untuk notifikasi
    $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
    $guru = fetch($koneksi, 'users', ['id_user' => $id_guru_pembuat]); // Ambil nama guru berdasarkan ID pembuat
    $mapel_info = fetch($koneksi, 'mata_pelajaran', ['kode' => $id_mapel]);
    $failed_sends = [];

    if ($setting && !empty($setting['url_api']) && $kelas_array) {
        $kelas_placeholders = implode(',', array_fill(0, count($kelas_array), '?'));
        $kelas_types = str_repeat('s', count($kelas_array));

        $sql_siswa = "SELECT nama, nowa FROM siswa WHERE kelas IN ($kelas_placeholders) AND nowa IS NOT NULL AND nowa != ''";
        $stmt_siswa = mysqli_prepare($koneksi, $sql_siswa);
        mysqli_stmt_bind_param($stmt_siswa, $kelas_types, ...$kelas_array);
        mysqli_stmt_execute($stmt_siswa);
        $result_siswa = mysqli_stmt_get_result($stmt_siswa);

        while ($siswa = mysqli_fetch_assoc($result_siswa)) {
            $pesan = "PEMBERITAHUAN PERUBAHAN TUGAS\n\n" .
                     "Yth. Bapak/Ibu Orang Tua dari ananda " . $siswa['nama'] . ",\n\n" .
                     "Dengan ini kami memberitahukan bahwa ada perubahan pada tugas:\n" .
                     "Mapel: *" . $mapel_info['nama_mapel'] . "*\n" .
                     "Judul: *" . $judul . "*\n" .
                     "Guru: " . $guru['nama'] . "\n" . // Nama guru yang benar sekarang
                     "Batas Waktu: " . date('d-m-Y H:i', strtotime($tgl_selesai)) . "\n\n" .
                     "Mohon untuk memeriksa kembali detail tugas. Terima kasih.\n\n" .
                     "*" . $setting['sekolah'] . "*";

            $curl = curl_init();
            curl_setopt_array($curl, [
              CURLOPT_URL => $setting['url_api'] . '/send-message',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_TIMEOUT => 10,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $siswa['nowa']]
            ]);
            $wa_response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            
            if ($http_code !== 200) {
                $failed_sends[] = ['nama' => $siswa['nama'], 'nowa' => $siswa['nowa']];
            }
            sleep(1);
        }
    }

    if (empty($failed_sends)) {
        echo json_encode(['status' => 'ok', 'message' => 'Tugas berhasil diperbarui.']);
    } else {
        echo json_encode(['status' => 'warning', 'message' => 'Tugas disimpan, tapi beberapa notifikasi gagal.', 'failed' => $failed_sends, 'id_tugas' => $id_tugas]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data tugas di database.']);
}
?>
