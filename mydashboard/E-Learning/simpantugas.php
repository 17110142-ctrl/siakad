<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
cek_session_siswa();

$id_tugas = isset($_POST['id_tugas']) ? $_POST['id_tugas'] : '';
$id_siswa = isset($_SESSION['id_siswa']) ? $_SESSION['id_siswa'] : '';
$nama_mapel = isset($_POST['nama_mapel']) ? $_POST['nama_mapel'] : '';
$jawaban = isset($_POST['jawaban']) ? addslashes($_POST['jawaban']) : '';

$datetime = date('Y-m-d');

// Validasi data dasar
if (empty($id_tugas) || empty($id_siswa) || empty($nama_mapel)) {
    echo "Error: Data tidak lengkap!";
    exit;
}

// Ambil data tugas untuk validasi batas waktu
$tugas = fetch($koneksi, 'tugas', ['id_tugas' => $id_tugas]);
if (!$tugas) {
    echo "Error: Tugas tidak ditemukan!";
    exit;
}

// Cek apakah sudah melewati batas waktu pengerjaan (server-side enforcement)
$now = date('Y-m-d H:i:s');
if (!empty($tugas['tgl_selesai']) && $now > $tugas['tgl_selesai']) {
    echo "Error: Batas waktu pengerjaan sudah lewat.";
    exit;
}

// Proses dengan/ tanpa file
if (isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
    $filename = $_FILES['file']['name'];
    $extParts = explode('.', $filename);
    $ext = strtolower(end($extParts));
    $file = $id_tugas . '_' . $id_siswa . '.' . $ext;

    if (move_uploaded_file($_FILES['file']['tmp_name'], '../../tugas/' . $file)) {
        $datax = [
            'id_tugas'   => $id_tugas,
            'id_siswa'   => $id_siswa,
            'jawaban'    => $jawaban,
            'file'       => $file,
            'nama_mapel' => $nama_mapel
        ];
        $where = [
            'id_siswa' => $id_siswa,
            'id_tugas' => $id_tugas
        ];
        $cek = rowcount($koneksi, 'jawaban_tugas', $where);
        if ($cek == 0) {
            insert($koneksi, 'jawaban_tugas', $datax);
        } else {
            update($koneksi, 'jawaban_tugas', $datax, $where);
        }
        echo "ok";
    } else {
        echo "Error: Gagal mengunggah file.";
    }
} else {
    $data = [
        'id_tugas'       => $id_tugas,
        'id_siswa'       => $id_siswa,
        'jawaban'        => $jawaban,
        'tgl_dikerjakan' => $datetime,
        'nama_mapel'     => $nama_mapel
    ];
    $where = [
        'id_siswa' => $id_siswa,
        'id_tugas' => $id_tugas
    ];

    if (!isset($data['nama_mapel']) || empty($data['nama_mapel'])) {
        echo "Error: Nama Mapel tidak ditemukan!";
        exit;
    }

    $cek = rowcount($koneksi, 'jawaban_tugas', $where);
    if ($cek == 0) {
        insert($koneksi, 'jawaban_tugas', $data);
    } else {
        update($koneksi, 'jawaban_tugas', $data, $where);
    }
    echo "ok";
}
