<?php
// AMBIL DATA DARI DATABASE
include "../config/koneksi.php"; // Pastikan path ini benar
session_start();

// Cek apakah sesi siswa ada
if (!isset($_SESSION['id_siswa'])) {
    die("Sesi siswa tidak ditemukan. Silakan login terlebih dahulu.");
}

$id_siswa = $_SESSION['id_siswa'];
$query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
if (!$query || mysqli_num_rows($query) == 0) {
    die("Data siswa dengan ID " . htmlspecialchars($id_siswa) . " tidak ditemukan.");
}
$data = mysqli_fetch_assoc($query);

// ===================================================================
//                 HELPER & DEFINISI FIELD TERPUSAT
// ===================================================================

// Helper function untuk menampilkan baris data di ringkasan
function displayRow($label, $value)
{
    // Logika ini akan menampilkan nilai 0, dan hanya menampilkan "- Belum diisi -"
    // jika nilainya adalah null atau string kosong.
    $display_value = ($value !== null && trim((string)$value) !== '') ? htmlspecialchars($value) : '<span class="text-muted fst-italic">- Belum diisi -</span>';

    echo "<div class='row mb-2'>
            <div class='col-sm-5 col-md-4 col-lg-3'><strong>" . htmlspecialchars($label) . "</strong></div>
            <div class='col-sm-7 col-md-8 col-lg-9'>: {$display_value}</div>
          </div>";
}

// Mapping untuk data yang butuh terjemahan (contoh: Jenis Kelamin)
$jk_map = ['L' => 'LAKI-LAKI', 'P' => 'PEREMPUAN'];

// Definisi semua field untuk Biodata, Alamat, dan Ortu
$fields_biodata_labels = [
    "nama" => "Nama Lengkap", "nisn" => "NISN", "nokk" => "NO. KK", "nik" => "NIK", "t_lahir" => "Tempat Lahir",
    "tgl_lahir" => "Tanggal Lahir", "jk" => "Jenis Kelamin", "agama" => "Agama", "kewarganegaraan" => "Kewarganegaraan",
    "email" => 'Email Siswa', "t_badan" => "Tinggi Badan (cm)", "b_badan" => "Berat Badan (kg)", "l_kepala" => "Lingkar Kepala (cm)",
    "anakke" => "Anak Ke", "jumlah_saudara" => "Jumlah Saudara Kandung", "cita_cita" => "Cita-Cita", "hobi" => "Hobi",
    "asal_sek" => "Asal Sekolah", 'thn_lulus' => "Tahun Lulus", 'beasiswa' => 'Beasiswa', 'no_kip' => 'No. KIP', 'no_kks' => 'No. KKS'
];
$fields_alamat_labels = [
    'kode_pos' => 'Kode Pos', 'rt' => 'RT', 'rw' => 'RW', 'kelurahan' => 'Kelurahan/Desa', 'kecamatan' => 'Kecamatan',
    'kabupaten' => 'Kabupaten/Kota', 'provinsi' => 'Provinsi', 'lintang' => 'Lintang', 'bujur' => 'Bujur'
];
$fields_ortu_labels = [
    'ayah' => [
        'nama_ayah' => 'Nama Ayah', 'status_ayah' => 'Status Ayah', 'kewarganegaraan_ayah' => 'Kewarganegaraan',
        'tempat_lahir_ayah' => 'Tempat Lahir', 'tgl_lahir_ayah' => 'Tanggal Lahir', 'pendidikan_ayah' => 'Pendidikan',
        'pekerjaan_ayah' => 'Pekerjaan', 'penghasilan_ayah' => 'Penghasilan', 'no_hp_ayah' => 'No. HP'
    ],
    'ibu' => [
        'nama_ibu' => 'Nama Ibu', 'status_ibu' => 'Status Ibu', 'kewarganegaraan_ibu' => 'Kewarganegaraan',
        'tempat_lahir_ibu' => 'Tempat Lahir', 'tgl_lahir_ibu' => 'Tanggal Lahir', 'pendidikan_ibu' => 'Pendidikan',
        'pekerjaan_ibu' => 'Pekerjaan', 'penghasilan_ibu' => 'Penghasilan', 'no_hp_ibu' => 'No. HP'
    ]
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Biodata Siswa - <?= htmlspecialchars($data['nama'] ?? 'Siswa') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; }
        .section-title {
            border-bottom: 2px solid #198754;
            padding-bottom: 8px;
            margin-bottom: 20px;
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container my-5">

        <!-- =================================================================== -->
        <!--                   BAGIAN RINGKASAN DATA SAJA                     -->
        <!-- =================================================================== -->
        <div id="ringkasan-container">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0"><i class="bi bi-person-lines-fill"></i> Ringkasan Status Isian Biodata</h3>
                </div>
                <div class="card-body p-4">
                    <p class="card-text mb-4">Berikut adalah data yang telah Anda input. Silakan periksa kembali kelengkapan dan kebenaran data. (Untuk kolom lintang dan bujur tidak masalah jika kosong, karena data diambil secara otomatis ketika memilih desa). Jika ada yang perlu diubah, klik tombol "Edit Biodata" di bagian bawah.</p>
                    
                    <h4 class="section-title">A. BIODATA SISWA</h4>
                    <?php
                    foreach ($fields_biodata_labels as $field => $label) {
                        $value = $data[$field] ?? '';
                        if ($field === 'jk') $value = $jk_map[$value] ?? $value;
                        if (($field === 'no_kip' && ($data['beasiswa'] ?? '') !== 'KIP') || ($field === 'no_kks' && ($data['beasiswa'] ?? '') !== 'PKH')) continue;
                        displayRow(strtoupper($label), $value);
                    }
                    ?>
                    
                    <h4 class="section-title mt-5">B. ALAMAT</h4>
                    <?php
                    foreach ($fields_alamat_labels as $field => $label) {
                        displayRow(strtoupper($label), $data[$field] ?? '');
                    }
                    ?>

                    <h4 class="section-title mt-5">C. DATA ORANG TUA</h4>
                    <h5>DATA AYAH</h5>
                    <?php
                    foreach ($fields_ortu_labels['ayah'] as $field => $label) {
                        displayRow(strtoupper($label), $data[$field] ?? '');
                    }
                    ?>
                    <h5 class="mt-4">DATA IBU</h5>
                    <?php
                    foreach ($fields_ortu_labels['ibu'] as $field => $label) {
                        displayRow(strtoupper($label), $data[$field] ?? '');
                    }
                    ?>
                    <div class='row mb-2'>
                        <div class='col-sm-5 col-md-4 col-lg-3'><strong>UPLOAD KK</strong></div>
                        <div class='col-sm-7 col-md-8 col-lg-9'>:
                            <?php if (!empty($data['kk_ibu'])) : ?>
                                <a href="../uploads/kk/<?= htmlspecialchars($data['kk_ibu']) ?>" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-file-earmark-text"></i> Lihat File KK</a>
                            <?php else : ?>
                                <span class="text-muted fst-italic">- Belum diupload -</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end bg-light">
                    <!-- Tombol diubah menjadi link (tag <a>) yang mengarah ke halaman edit_profil.php -->
                    <a href="?pg=edit_profil" class="btn btn-primary btn-lg">
                        <i class="bi bi-pencil-square"></i> Edit Biodata
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- PENTING: jQuery dan Bootstrap JS tetap dimuat jika ada komponen Bootstrap lain yang membutuhkannya -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
