<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

// Opsi respons JSON (meniru pola kirim_notif_harian.php)
$want_json = false;
$defer_wa = (isset($_POST['defer_wa']) && $_POST['defer_wa'] == '1');
try {
    if ((isset($_REQUEST['json']) && $_REQUEST['json'] == '1') ||
        (isset($_REQUEST['format']) && strtolower((string)$_REQUEST['format']) === 'json') ||
        (isset($_SERVER['HTTP_ACCEPT']) && stripos((string)$_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
        $want_json = true;
        header('Content-Type: application/json');
    }
} catch (Throwable $e) {}

$id = $_POST['id'];
$id_mapel = addslashes($_POST['mapel']);
$id_guru = $_SESSION['id_user'];
$materi = addslashes($_POST['isimateri']);
$judul = $_POST['judul'];
$tgl_mulai = $_POST['tgl_mulai'];
$tgl_selesai = $_POST['tgl_selesai'];
$youtube = $_POST['youtube'];
$kelas_array = $_POST['kelas']; // Ambil sebagai array
$kelas_serialized = serialize($kelas_array);

$data = [
    'mapel' => $id_mapel,
    'kelas' => $kelas_serialized,
    'judul' => $judul,
    'materi' => $materi,
    'tgl_mulai' => $tgl_mulai,
    'tgl_selesai' => $tgl_selesai,
    'youtube' => $youtube
];

$ektensi = ['jpg', 'png', 'docx', 'pdf', 'xlsx'];
if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
    $file = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    if (in_array(strtolower($ext), $ektensi)) {
        $dest = '../../materi/';
        $path = $dest . $file;
        if (move_uploaded_file($temp, $path)) {
            $data['file'] = $file;
        } else {
            if ($want_json) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload file baru.']);
            } else {
                echo "Gagal mengupload file baru.";
            }
            exit();
        }
    } else {
        if ($want_json) {
            echo json_encode(['status' => 'error', 'message' => 'Ekstensi file tidak diizinkan.']);
        } else {
            echo "Ekstensi file tidak diizinkan.";
        }
        exit();
    }
}

// Update data materi di database
$update = update($koneksi, 'materi', $data, ['id_materi' => $id]);

if ($update) {
    // --- PENAMBAHAN FITUR NOTIFIKASI WA ---
    $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
    $guru = fetch($koneksi, 'users', ['id_user' => $id_guru]);
    $mapel_info = fetch($koneksi, 'mata_pelajaran', ['kode' => $id_mapel]);

    if (!$defer_wa && $setting && !empty($setting['url_api']) && $kelas_array) {
        $kelas_placeholders = implode(',', array_fill(0, count($kelas_array), '?'));
        $kelas_types = str_repeat('s', count($kelas_array));

        $sql_siswa = "SELECT nama, nowa FROM siswa WHERE kelas IN ($kelas_placeholders) AND nowa IS NOT NULL AND nowa != ''";
        $stmt_siswa = mysqli_prepare($koneksi, $sql_siswa);
        mysqli_stmt_bind_param($stmt_siswa, $kelas_types, ...$kelas_array);
        mysqli_stmt_execute($stmt_siswa);
        $result_siswa = mysqli_stmt_get_result($stmt_siswa);

        while ($siswa = mysqli_fetch_assoc($result_siswa)) {
            $pesan = "*PEMBERITAHUAN PERUBAHAN MATERI*\n\n" .
                     "Yth. Bapak/Ibu Orang Tua dari ananda " . $siswa['nama'] . ",\n\n" .
                     "Dengan ini kami memberitahukan bahwa ada perubahan pada materi:\n" .
                     "Mapel: *" . $mapel_info['nama_mapel'] . "*\n" .
                     "Judul: *" . $judul . "*\n" .
                     "Guru: " . $guru['nama'] . "\n\n" .
                     "Mohon untuk memeriksa kembali detail materi. Terima kasih.\n\n" .
                     "*" . $setting['sekolah'] . "*";

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
    }
    // --- AKHIR FITUR NOTIFIKASI ---

    if ($want_json) {
        echo json_encode(['status' => 'ok']);
    } else {
        echo "OK";
    }
} else {
    if ($want_json) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data materi.']);
    } else {
        echo "Gagal memperbarui data materi.";
    }
}
?>
