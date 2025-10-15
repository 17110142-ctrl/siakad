<?php
require_once '../config/koneksi.php';
require_once '../config/function.php';
require_once '../config/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan.'
    ]);
    exit;
}

$id = $_POST['id_siswa'] ?? '';
$id = trim($id);
if ($id === '' || !ctype_digit($id)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID siswa tidak valid.'
    ]);
    exit;
}

$allowedFields = [
    'nama','nis','nisn','kelas','level','jurusan','username','password','jk','agama','t_lahir','tgl_lahir',
    'nowa','email','hobi','cita_cita','asal_sek','thn_lulus','beasiswa','no_kip','no_kks','anakke','jumlah_saudara',
    't_badan','b_badan','l_kepala','nik','nokk','kk_ibu','rt','rw','kelurahan','kabupaten','kecamatan','provinsi','kode_pos',
    'nama_ayah','status_ayah','no_hp_ayah','tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah',
    'penghasilan_ayah','nama_ibu','status_ibu','no_hp_ibu','tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu',
    'pekerjaan_ibu','penghasilan_ibu'
];

$data = [];
foreach ($allowedFields as $field) {
    if (isset($_POST[$field])) {
        $value = trim($_POST[$field]);
        $data[$field] = $value;
    }
}

if (empty($data)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tidak ada data yang diubah.'
    ]);
    exit;
}

$updated = update($koneksi, 'siswa', $data, ['id_siswa' => $id]);

if ($updated) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Data siswa berhasil diperbarui.'
    ]);
} else {
    $error = mysqli_error($koneksi);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal memperbarui data. ' . ($error ?: '')
    ]);
}
