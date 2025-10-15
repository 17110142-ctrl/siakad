<?php
header('Content-Type: application/json'); // Penting: Ubah header ke JSON
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

cek_session_guru();

// Mengambil data dari form
$id_mapel = addslashes($_POST['mapel']);
$id_materi = !empty($_POST['id_materi']) ? $_POST['id_materi'] : NULL;
$id_guru = $_SESSION['id_user'];
$tugas = addslashes($_POST['isitugas']);
$judul = addslashes($_POST['judul']);
$tgl_mulai = $_POST['tgl_mulai'];
$tgl_selesai = $_POST['tgl_selesai'];
$kelas_array = $_POST['kelas'];
$kelas_serialized = serialize($kelas_array);

// Setting aktif (semester/tapel) untuk placeholder jawaban
$setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
$current_semester = isset($setting['semester']) ? (string)$setting['semester'] : '';
$current_tapel    = isset($setting['tp']) ? (string)$setting['tp'] : '';

$data_to_insert = [
    'mapel' => $id_mapel,
    'id_materi' => $id_materi,
    'kelas' => $kelas_serialized,
    'id_guru' => $id_guru,
    'judul' => $judul,
    'tugas' => $tugas,
    'tgl_mulai' => $tgl_mulai,
    'tgl_selesai' => $tgl_selesai
];

// Logika upload file
if (isset($_FILES['file']) && $_FILES['file']['name'] != '') {
    $file = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $ektensi = ['docx', 'pdf', 'xlsx', 'pptx', 'ppt', 'doc', 'txt', 'jpg', 'png', 'jpeg', 'gif', 'mp4', '3gp', 'mkv', 'avi', 'mov', 'webm', 'mp3', 'wav', 'm4a', 'ogg'];

    if (in_array(strtolower($ext), $ektensi)) {
        $dest = '../../tugas/';
        $path = $dest . $file;
        if (move_uploaded_file($temp, $path)) {
            $data_to_insert['file'] = $file;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tipe file tidak diizinkan.']);
        exit();
    }
}

// Simpan data tugas ke database
$simpan = insert($koneksi, 'tugas', $data_to_insert);

if ($simpan) {
    $id_tugas_baru = mysqli_insert_id($koneksi);
    
    // Ambil daftar siswa untuk notifikasi dan inisialisasi nilai default (0)
    $students = [];
    if ($kelas_array) {
        $kelas_placeholders = implode(',', array_fill(0, count($kelas_array), '?'));
        $kelas_types = str_repeat('s', count($kelas_array));

        // 1) Ambil list siswa untuk notifikasi (nama + nowa)
        $sql_siswa_notif = "SELECT nama, nowa FROM siswa WHERE kelas IN ($kelas_placeholders) AND nowa IS NOT NULL AND nowa != ''";
        $stmt_siswa_notif = mysqli_prepare($koneksi, $sql_siswa_notif);
        mysqli_stmt_bind_param($stmt_siswa_notif, $kelas_types, ...$kelas_array);
        mysqli_stmt_execute($stmt_siswa_notif);
        $result_siswa_notif = mysqli_stmt_get_result($stmt_siswa_notif);
        while ($s = mysqli_fetch_assoc($result_siswa_notif)) { $students[] = $s; }
        mysqli_stmt_close($stmt_siswa_notif);

        // 2) Inisialisasi baris jawaban_tugas untuk semua siswa terkait dengan nilai awal 0
        $sql_siswa_ids = "SELECT id_siswa FROM siswa WHERE kelas IN ($kelas_placeholders)";
        $stmt_siswa_ids = mysqli_prepare($koneksi, $sql_siswa_ids);
        mysqli_stmt_bind_param($stmt_siswa_ids, $kelas_types, ...$kelas_array);
        mysqli_stmt_execute($stmt_siswa_ids);
        $result_siswa_ids = mysqli_stmt_get_result($stmt_siswa_ids);

        // Siapkan statement cek dan insert untuk efisiensi
        $stmt_cek = mysqli_prepare($koneksi, "SELECT id_jawaban FROM jawaban_tugas WHERE id_tugas = ? AND id_siswa = ? LIMIT 1");
        $stmt_ins = mysqli_prepare($koneksi, "INSERT INTO jawaban_tugas (id_tugas, id_siswa, jawaban, file, nilai, status, nama_mapel, semester, tapel) VALUES (?, ?, '', '', '0', 0, ?, ?, ?)");

        while ($row = mysqli_fetch_assoc($result_siswa_ids)) {
            $ids = (int)$row['id_siswa'];
            // Cek apakah sudah ada baris jawaban
            mysqli_stmt_bind_param($stmt_cek, 'ii', $id_tugas_baru, $ids);
            mysqli_stmt_execute($stmt_cek);
            $has = mysqli_stmt_get_result($stmt_cek);
            if ($has && mysqli_num_rows($has) == 0) {
                // Insert placeholder nilai 0 (belum mengumpulkan)
                mysqli_stmt_bind_param($stmt_ins, 'iisss', $id_tugas_baru, $ids, $id_mapel, $current_semester, $current_tapel);
                mysqli_stmt_execute($stmt_ins);
            }
        }
        if ($stmt_cek) mysqli_stmt_close($stmt_cek);
        if ($stmt_ins) mysqli_stmt_close($stmt_ins);
        mysqli_stmt_close($stmt_siswa_ids);
    }
    // Kembalikan respons JSON dengan status sukses dan daftar siswa
    echo json_encode(['status' => 'ok', 'id_tugas' => $id_tugas_baru, 'students' => $students]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data tugas ke database.']);
}
?>
