<?php
session_start();
include "../config/koneksi.php";
include "../config/user.php"; // misal di sini isi $user dan $_SESSION

// Pastikan hanya user yang berhak yang bisa mengakses
if (!isset($_SESSION['level'])) {
    // Atau redirect ke halaman login
    die("Anda tidak memiliki akses.");
}

// --- LOGIKA UNTUK NAMA FILE EXCEL ---
$namaFile = "Data_Siswa";
$kelasFilterUntukNamaFile = ""; // Variabel untuk menyimpan nama kelas yang difilter

// Siapkan SQL berdasarkan role dan filter
$kelasWali = isset($user['walas']) ? mysqli_real_escape_string($koneksi, $user['walas']) : null;
$sql = "SELECT * FROM siswa ";
$whereClauses = [];

if ($_SESSION['level'] === 'guru' && $kelasWali) {
    $whereClauses[] = "kelas = '$kelasWali'";
    $kelasFilterUntukNamaFile = "_Kelas_" . str_replace(" ", "_", $kelasWali); // Format untuk nama file
} else {
    // Tambahkan filter kelas jika ada dari request GET
    if (isset($_GET['filterKelas']) && !empty($_GET['filterKelas'])) {
        $filterKelas = mysqli_real_escape_string($koneksi, $_GET['filterKelas']);
        $whereClauses[] = "kelas = '$filterKelas'";
        $kelasFilterUntukNamaFile = "_Kelas_" . str_replace(" ", "_", $filterKelas); // Format untuk nama file
    } else {
        // Jika tidak ada filter kelas (atau "Semua Kelas" dipilih), tambahkan "Semua_Kelas"
        $kelasFilterUntukNamaFile = "_Semua_Kelas";
    }
}

if (!empty($whereClauses)) {
    $sql .= " WHERE " . implode(" AND ", $whereClauses);
}

$sql .= " ORDER BY kelas, nama";

$result = mysqli_query($koneksi, $sql);

// Set header untuk file Excel dengan nama yang dinamis
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$namaFile}{$kelasFilterUntukNamaFile}_" . date('Ymd_His') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Mulai membuat tabel HTML yang akan dibaca sebagai Excel
echo '<table border="1">';
echo '<thead>';
echo '<tr>';
echo '<th>No</th>';
echo '<th>Kelas</th>';
echo '<th>Nama</th>';
echo '<th>NIS</th>';
echo '<th>NISN</th>';
echo '<th>Tempat Lahir</th>';
echo '<th>Tanggal Lahir</th>';
echo '<th>Jenis Kelamin</th>';
echo '<th>NIK</th>';
echo '<th>No. KK</th>';
echo '<th>Agama</th>';
echo '<th>Email</th>';
echo '<th>Anak Ke</th>';
echo '<th>Jumlah Saudara</th>';
echo '<th>Tinggi Badan</th>';
echo '<th>Berat Badan</th>';
echo '<th>Lingkar Kepala</th>';
echo '<th>RT</th>';
echo '<th>RW</th>';
echo '<th>Kelurahan</th>';
echo '<th>Kecamatan</th>';
echo '<th>Provinsi</th>';
echo '<th>Kode Pos</th>';
echo '<th>Hobi</th>';
echo '<th>Cita-cita</th>';
echo '<th>Asal Sekolah</th>';
echo '<th>Tahun Lulus</th>';
echo '<th>Beasiswa</th>';
echo '<th>No KIP</th>';
echo '<th>No KKS</th>';
echo '<th>Nama Ayah</th>';
echo '<th>Status Ayah</th>';
echo '<th>Tempat Lahir Ayah</th>';
echo '<th>Tanggal Lahir Ayah</th>';
echo '<th>No. HP Ayah</th>';
echo '<th>Pendidikan Ayah</th>';
echo '<th>Penghasilan Ayah</th>';
echo '<th>Pekerjaan Ayah</th>';
echo '<th>Nama Ibu</th>';
echo '<th>Status Ibu</th>';
echo '<th>Tempat Lahir Ibu</th>';
echo '<th>Tanggal Lahir Ibu</th>';
echo '<th>No. HP Ibu</th>';
echo '<th>Pendidikan Ibu</th>';
echo '<th>Penghasilan Ibu</th>';
echo '<th>Pekerjaan Ibu</th>';
echo '<th>File KK</th>';
echo '<th>Status Kelengkapan (%)</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    // Logika perhitungan kelengkapan data (sama seperti di siswa.php)
    $required = [
        'kelas','nama','nis','nisn','t_lahir','tgl_lahir','jk',
        'nik','nokk','agama','email','anakke','jumlah_saudara',
        't_badan','b_badan','l_kepala','rt','rw','kelurahan',
        'kecamatan','provinsi','kode_pos','hobi','cita_cita',
        'asal_sek','thn_lulus','beasiswa',
        'nama_ayah','status_ayah','nama_ibu','status_ibu'
    ];
    $required[] = 'kk_ibu';

    if ($row['beasiswa'] === 'KIP') {
        $required[] = 'no_kip';
    } elseif ($row['beasiswa'] === 'PKH') {
        $required[] = 'no_kks';
    }

    $fatherFields = ['tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah','no_hp_ayah'];
    $motherFields = ['tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu','no_hp_ibu'];

    if (strcasecmp($row['status_ayah'], 'Sudah Meninggal') !== 0) {
        $required = array_merge($required, $fatherFields);
    }
    if (strcasecmp($row['status_ibu'], 'Sudah Meninggal') !== 0) {
        $required = array_merge($required, $motherFields);
    }

    $total = count($required);
    $filled = 0;

    foreach ($required as $f) {
        if ($f === 'jumlah_saudara') {
            if (isset($row[$f]) && ($row[$f] === '0' || $row[$f] === '-' || $row[$f] !== '')) {
                $filled++;
            }
        } elseif ($f === 'kk_ibu') {
            if (!empty($row['kk_ibu'])) {
                $filled++;
            }
        } else {
            if (!empty($row[$f])) {
                $filled++;
            }
        }
    }
    $percent = round($filled / $total * 100);
    // Akhir logika perhitungan kelengkapan data

    echo '<tr>';
    echo '<td>' . $no++ . '</td>';
    echo '<td>' . htmlspecialchars($row['kelas']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nama']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nis']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nisn']) . '</td>';
    echo '<td>' . htmlspecialchars($row['t_lahir']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tgl_lahir']) . '</td>';
    echo '<td>' . ($row['jk'] == 'L' ? 'Laki-laki' : 'Perempuan') . '</td>';
    echo '<td>' . htmlspecialchars($row['nik']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nokk']) . '</td>';
    echo '<td>' . htmlspecialchars($row['agama']) . '</td>';
    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
    echo '<td>' . htmlspecialchars($row['anakke']) . '</td>';
    echo '<td>' . htmlspecialchars($row['jumlah_saudara']) . '</td>';
    echo '<td>' . htmlspecialchars($row['t_badan']) . '</td>';
    echo '<td>' . htmlspecialchars($row['b_badan']) . '</td>';
    echo '<td>' . htmlspecialchars($row['l_kepala']) . '</td>';
    echo '<td>' . htmlspecialchars($row['rt']) . '</td>';
    echo '<td>' . htmlspecialchars($row['rw']) . '</td>';
    echo '<td>' . htmlspecialchars($row['kelurahan']) . '</td>';
    echo '<td>' . htmlspecialchars($row['kecamatan']) . '</td>';
    echo '<td>' . htmlspecialchars($row['provinsi']) . '</td>';
    echo '<td>' . htmlspecialchars($row['kode_pos']) . '</td>';
    echo '<td>' . htmlspecialchars($row['hobi']) . '</td>';
    echo '<td>' . htmlspecialchars($row['cita_cita']) . '</td>';
    echo '<td>' . htmlspecialchars($row['asal_sek']) . '</td>';
    echo '<td>' . htmlspecialchars($row['thn_lulus']) . '</td>';
    echo '<td>' . htmlspecialchars($row['beasiswa']) . '</td>';
    echo '<td>' . htmlspecialchars($row['no_kip']) . '</td>';
    echo '<td>' . htmlspecialchars($row['no_kks']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nama_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tempat_lahir_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tgl_lahir_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['no_hp_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['pendidikan_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['penghasilan_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['pekerjaan_ayah']) . '</td>';
    echo '<td>' . htmlspecialchars($row['nama_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tempat_lahir_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['tgl_lahir_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['no_hp_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['pendidikan_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['penghasilan_ibu']) . '</td>';
    echo '<td>' . htmlspecialchars($row['pekerjaan_ibu']) . '</td>';
    echo '<td>' . (!empty($row['kk_ibu']) ? 'Ada' : 'Tidak Ada') . '</td>';
    echo '<td>' . $percent . '%</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

mysqli_close($koneksi);
?>