<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'kelas') {
    $level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='" . $level . "'");
    echo "<option value=''>Pilih Rombel</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}

if ($pg == 'mapel') {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }

    $kelas = trim($_POST['kelas'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $guru  = trim($_POST['guru'] ?? '');

    if ($kelas === '') {
        echo json_encode(['status' => 'error', 'message' => 'Pilih rombel terlebih dahulu.', 'data' => []]);
        exit;
    }

    $kelasEsc = mysqli_real_escape_string($koneksi, $kelas);
    $kelasRow = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT level, jurusan FROM kelas WHERE kelas='$kelasEsc' LIMIT 1"));
    if ($kelasRow) {
        if ($level === '') {
            $level = $kelasRow['level'] ?? '';
        }
        $jurusan = $kelasRow['jurusan'] ?? '';
    } else {
        $jurusan = '';
    }

    if ($level === '') {
        echo json_encode(['status' => 'error', 'message' => 'Pilih tingkat terlebih dahulu.', 'data' => []]);
        exit;
    }

    $levelEsc = mysqli_real_escape_string($koneksi, $level);
    $jurusan = $jurusan === '' || $jurusan === null ? 'semua' : $jurusan;
    $jurEsc = mysqli_real_escape_string($koneksi, $jurusan);

    $guruFilterSql = '';
    if ($guru !== '' && $guru !== '0') {
        $guruEsc = mysqli_real_escape_string($koneksi, $guru);
        $guruFilterSql = " AND EXISTS (SELECT 1 FROM jadwal_mapel jm WHERE jm.mapel = mr.mapel AND jm.kelas = '$kelasEsc' AND jm.guru = '$guruEsc')";
    }

    $sql = "SELECT DISTINCT mr.mapel AS id, mp.kode, mp.nama_mapel FROM mapel_rapor mr JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.kurikulum='2' AND mr.tingkat='$levelEsc' AND (mr.pk='$jurEsc' OR mr.pk='semua' OR mr.pk='' OR mr.pk IS NULL) $guruFilterSql ORDER BY mr.urut, mp.nama_mapel";
    $res = mysqli_query($koneksi, $sql);

    $data = [];
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = [
                'id'   => (int)$row['id'],
                'kode' => $row['kode'] ?? '',
                'nama' => $row['nama_mapel'] ?? ''
            ];
        }
    }

    if (empty($data)) {
        echo json_encode(['status' => 'error', 'message' => 'Mapel rapor belum diatur untuk kelas ini.', 'data' => []]);
    } else {
        echo json_encode(['status' => 'ok', 'data' => $data]);
    }
    exit;
}
