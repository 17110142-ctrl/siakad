<?php
header('Content-Type: application/json');
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

if (isset($_POST['id_tugas']) && isset($_POST['numbers'])) {
    $id_tugas = intval($_POST['id_tugas']);
    $numbers_to_resend = is_array($_POST['numbers']) ? $_POST['numbers'] : [];
    $type = isset($_POST['type']) ? $_POST['type'] : 'baru';
    $failed_sends = [];
    if ($id_tugas > 0 && !empty($numbers_to_resend)) {
        $tugas = fetch($koneksi, 'tugas', ['id_tugas' => $id_tugas]);
        $mapel_info = fetch($koneksi, 'mata_pelajaran', ['kode' => $tugas['mapel']]);
        $guru = fetch($koneksi, 'users', ['id_user' => $tugas['id_guru']]);
        $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
        if ($tugas && $mapel_info && $guru && $setting && !empty($setting['url_api'])) {
            $judul_notif = ($type === 'perubahan') ? "PEMBERITAHUAN PERUBAHAN TUGAS" : "PEMBERITAHUAN TUGAS BARU";
            $kalimat_ajakan = ($type === 'perubahan') ? "Mohon untuk memeriksa kembali detail tugas." : "Mohon untuk mengingatkan ananda agar mengerjakan tepat waktu.";
            foreach ($numbers_to_resend as $nowa) {
                $nowa_clean = mysqli_real_escape_string($koneksi, $nowa);
                $siswa = fetch($koneksi, 'siswa', ['nowa' => $nowa_clean]);
                if ($siswa) {
                    $pesan = "$judul_notif\n\n" .
                             "Yth. Bapak/Ibu Orang Tua dari ananda " . $siswa['nama'] . ",\n\n" .
                             "Dengan ini kami memberitahukan bahwa ada tugas:\n" .
                             "Mapel: *" . $mapel_info['nama_mapel'] . "*\n" .
                             "Judul: *" . $tugas['judul'] . "*\n" .
                             "Guru: " . $guru['nama'] . "\n" .
                             "Batas Waktu: " . date('d-m-Y H:i', strtotime($tugas['tgl_selesai'])) . "\n\n" .
                             "$kalimat_ajakan Terima kasih.\n\n" .
                             "*" . $setting['sekolah'] . "*";
                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => $setting['url_api'] . '/send-message',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 10,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $nowa]
                    ]);
                    $response_wa = curl_exec($curl);
                    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);
                    if ($http_code !== 200) {
                        $failed_sends[] = ['nama' => $siswa['nama'], 'nowa' => $nowa];
                    }
                    sleep(1);
                }
            }
            if (empty($failed_sends)) {
                echo json_encode(['status' => 'ok', 'message' => 'Semua notifikasi yang gagal berhasil dikirim ulang.']);
            } else {
                echo json_encode(['status' => 'warning', 'message' => 'Beberapa notifikasi masih gagal dikirim.', 'failed' => $failed_sends]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil detail tugas atau setting.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    }
} else {
    $id_tugas = $_POST['id'];
    $id_mapel = addslashes($_POST['mapel']);
    $id_materi = isset($_POST['id_materi']) ? $_POST['id_materi'] : '';
    $tugas_text = addslashes($_POST['isitugas']);
    $judul = $_POST['judul'];
    $tgl_mulai = $_POST['tgl_mulai'];
    $tgl_selesai = $_POST['tgl_selesai'];
    $kelas_array = $_POST['kelas'];
    $kelas_serialized = serialize($kelas_array);
    $data_to_update = [
        'mapel' => $id_mapel,
        'id_materi' => $id_materi,
        'kelas' => $kelas_serialized,
        'judul' => $judul,
        'tugas' => $tugas_text,
        'tgl_mulai' => $tgl_mulai,
        'tgl_selesai' => $tgl_selesai
    ];
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
    $update = update($koneksi, 'tugas', $data_to_update, ['id_tugas' => $id_tugas]);
    if ($update) {
        $students = [];
        if ($kelas_array) {
            $kelas_placeholders = implode(',', array_fill(0, count($kelas_array), '?'));
            $kelas_types = str_repeat('s', count($kelas_array));
            $sql_siswa = "SELECT nama, nowa FROM siswa WHERE kelas IN ($kelas_placeholders) AND nowa IS NOT NULL AND nowa != ''";
            $stmt_siswa = mysqli_prepare($koneksi, $sql_siswa);
            mysqli_stmt_bind_param($stmt_siswa, $kelas_types, ...$kelas_array);
            mysqli_stmt_execute($stmt_siswa);
            $result_siswa = mysqli_stmt_get_result($stmt_siswa);
            while ($siswa = mysqli_fetch_assoc($result_siswa)) {
                $students[] = $siswa;
            }
            mysqli_stmt_close($stmt_siswa);

            // Inisialisasi jawaban_tugas placeholder (nilai 0) untuk siswa yang belum punya baris
            $sql_ids = "SELECT id_siswa FROM siswa WHERE kelas IN ($kelas_placeholders)";
            $stmt_ids = mysqli_prepare($koneksi, $sql_ids);
            mysqli_stmt_bind_param($stmt_ids, $kelas_types, ...$kelas_array);
            mysqli_stmt_execute($stmt_ids);
            $res_ids = mysqli_stmt_get_result($stmt_ids);
            $stmt_cek = mysqli_prepare($koneksi, "SELECT id_jawaban FROM jawaban_tugas WHERE id_tugas = ? AND id_siswa = ? LIMIT 1");
            // Siapkan info semester/tapel + mapel untuk placeholder
            $tugas_row = fetch($koneksi, 'tugas', ['id_tugas' => $id_tugas]);
            $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
            $curr_semester = isset($setting['semester']) ? (string)$setting['semester'] : '';
            $curr_tapel    = isset($setting['tp']) ? (string)$setting['tp'] : '';
            $mapel_kode    = isset($tugas_row['mapel']) ? (string)$tugas_row['mapel'] : '';
            $stmt_ins = mysqli_prepare($koneksi, "INSERT INTO jawaban_tugas (id_tugas, id_siswa, jawaban, file, nilai, status, nama_mapel, semester, tapel) VALUES (?, ?, '', '', '0', 0, ?, ?, ?)");
            while ($r = mysqli_fetch_assoc($res_ids)) {
                $ids = (int)$r['id_siswa'];
                mysqli_stmt_bind_param($stmt_cek, 'ii', $id_tugas, $ids);
                mysqli_stmt_execute($stmt_cek);
                $has = mysqli_stmt_get_result($stmt_cek);
                if ($has && mysqli_num_rows($has) == 0) {
                    mysqli_stmt_bind_param($stmt_ins, 'iisss', $id_tugas, $ids, $mapel_kode, $curr_semester, $curr_tapel);
                    mysqli_stmt_execute($stmt_ins);
                }
            }
            if ($stmt_cek) mysqli_stmt_close($stmt_cek);
            if ($stmt_ins) mysqli_stmt_close($stmt_ins);
            mysqli_stmt_close($stmt_ids);
        }
        echo json_encode(['status' => 'ok', 'id_tugas' => $id_tugas, 'students' => $students]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data tugas di database.']);
    }
}
?>
