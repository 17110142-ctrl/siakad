<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sesuaikan path ke file koneksi.php Anda
include '../../config/koneksi.php'; 

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak diizinkan.");
}

$id_siswa   = $_POST['id_siswa']   ?? '';
$nis        = $_POST['nis']        ?? '';
$mapel_id   = $_POST['mapel_id']   ?? '';
$mapel_kode = $_POST['mapel_kode'] ?? '';
// Kompatibel dengan nama kolom di DB (mapel adalah ID numerik di nilai_harian/nilai_sts)
if (empty($mapel_id) && !empty($_POST['mapel'])) {
    $mapel_id = $_POST['mapel'];
}
$tp         = $_POST['tp']         ?? '';
$semester   = $_POST['semester']   ?? '';

// Fallback: jika id_siswa kosong tapi ada NIS, lookup id_siswa
if (empty($id_siswa) && !empty($nis)) {
    $res = mysqli_query($koneksi, "SELECT id_siswa FROM siswa WHERE nis='" . mysqli_real_escape_string($koneksi, $nis) . "' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $id_siswa = $row['id_siswa'];
    }
}

// Fallback: jika mapel_id kosong tapi ada mapel_kode, lookup id mapel
if (empty($mapel_id) && !empty($mapel_kode)) {
    $res = mysqli_query($koneksi, "SELECT id FROM mata_pelajaran WHERE kode='" . mysqli_real_escape_string($koneksi, $mapel_kode) . "' LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $mapel_id = $row['id'];
    }
}

if (empty($id_siswa) || empty($mapel_id) || empty($tp) || empty($semester)) {
    die("Parameter tidak lengkap.");
}

// --- AWAL BLOK DEBUG ---
$debug_output = "--- PANEL DEBUGGING ---\n\n";
$debug_output .= "Waktu Eksekusi: " . date('Y-m-d H:i:s') . "\n";
$debug_output .= "Parameter Diterima:\n" . print_r($_POST, true) . "\n";
// --- AKHIR BLOK DEBUG ---

// Hitung rentang tanggal tahun pelajaran (tp) untuk fallback filter ketika semester/tapel pada jawaban_tugas kosong
$tp_start = '';
$tp_end   = '';
if (strpos($tp, '/') !== false) {
    $tp_parts = explode('/', $tp);
    $y0 = (int)$tp_parts[0];
    $y1 = $y0 + 1;
    $tp_start = sprintf('%04d-07-01', $y0); // 1 Juli tahun awal
    $tp_end   = sprintf('%04d-06-30', $y1); // 30 Juni tahun berikutnya
}
?>
<style>
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .detail-table th, .detail-table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    .detail-table th {
        background-color: #f2f2f2;
        font-weight: 600;
        text-align: center;
    }
    .detail-table td:nth-child(1),
    .detail-table td:nth-child(3) {
        text-align: center;
    }
    .detail-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .no-data {
        text-align: center;
        padding: 20px;
        color: #777;
        font-style: italic;
    }
    .badge {
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 12px;
        color: white;
        font-weight: 500;
    }
    .badge-harian { background-color: #3498db; }
    .badge-tugas { background-color: #9b59b6; }
    .badge-elearning { background-color: #2ecc71; }
</style>

<div class="table-responsive">
    <table class="detail-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th width="15%">Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $semua_nilai = [];
            
            // --- Membuat Peta Pertemuan untuk Nilai Harian ---
            $pertemuan_map = [];
            $sql_pertemuan = "SELECT DISTINCT tanggal FROM nilai_harian WHERE idsiswa='$id_siswa' AND mapel='$mapel_id' AND semester='$semester' AND tapel='$tp' ORDER BY tanggal ASC";
            $query_pertemuan = mysqli_query($koneksi, $sql_pertemuan);
            if ($query_pertemuan) {
                $pertemuan_ke = 1;
                while ($pertemuan_data = mysqli_fetch_assoc($query_pertemuan)) {
                    $pertemuan_map[$pertemuan_data['tanggal']] = $pertemuan_ke;
                    $pertemuan_ke++;
                }
            }

            // --- REVISED: Membuat Peta Pertemuan untuk TUGAS ---
            $pertemuan_tugas_map = [];
            $cond_sem = "(jt.semester='$semester' OR jt.semester='' OR jt.semester IS NULL)";
            $cond_tp  = "(jt.tapel='$tp' OR jt.tapel='' OR jt.tapel IS NULL)";
            $cond_dtr = ($tp_start && $tp_end) ? "AND (jt.tgl_update IS NOT NULL AND DATE(jt.tgl_update) BETWEEN '$tp_start' AND '$tp_end')" : '';
            $sql_pertemuan_tugas = "SELECT DISTINCT DATE(jt.tgl_update) as tanggal_tugas FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa='$id_siswa' AND mp.id='$mapel_id' AND $cond_sem AND $cond_tp $cond_dtr ORDER BY tanggal_tugas ASC";
            $query_pertemuan_tugas = mysqli_query($koneksi, $sql_pertemuan_tugas);
            if ($query_pertemuan_tugas) {
                $pertemuan_ke_tugas = 1;
                while ($pertemuan_data_tugas = mysqli_fetch_assoc($query_pertemuan_tugas)) {
                    $pertemuan_tugas_map[$pertemuan_data_tugas['tanggal_tugas']] = $pertemuan_ke_tugas;
                    $pertemuan_ke_tugas++;
                }
            }

            // 1. Ambil data dari tabel 'nilai_harian'
            $sql_harian = "SELECT tanggal, nilai FROM nilai_harian WHERE idsiswa='$id_siswa' AND mapel='$mapel_id' AND semester='$semester' AND tapel='$tp'";
            $debug_output .= "\n--- Query Nilai Harian ---\n" . $sql_harian . "\n";
            $query_harian = mysqli_query($koneksi, $sql_harian);
            if (!$query_harian) {
                $debug_output .= "Error: " . mysqli_error($koneksi) . "\n";
            } else {
                $debug_output .= "Hasil: " . mysqli_num_rows($query_harian) . " baris ditemukan.\n";
                while ($data = mysqli_fetch_assoc($query_harian)) {
                    if (!empty($data['tanggal']) && $data['tanggal'] !== '0000-00-00') {
                        $nomor_pertemuan = $pertemuan_map[$data['tanggal']] ?? '?'; 
                        $semua_nilai[] = [
                            'tanggal' => strtotime($data['tanggal']),
                            'nilai' => $data['nilai'],
                            'keterangan' => '<span class="badge badge-harian">Nilai Harian : Pertemuan ke-' . $nomor_pertemuan . '</span>'
                        ];
                    }
                }
            }

            // 2. Ambil data dari 'jawaban_tugas'
            // REVISED: Mengubah format keterangan tugas
            // Perkuat pencocokan mapel: cocokkan kode ATAU nama_mapel (dengan TRIM)
            $sql_tugas = "SELECT jt.tgl_update, jt.nilai, mp.nama_mapel FROM jawaban_tugas jt JOIN mata_pelajaran mp ON (TRIM(jt.nama_mapel) = mp.kode OR TRIM(jt.nama_mapel) = mp.nama_mapel) WHERE jt.id_siswa='$id_siswa' AND mp.id='$mapel_id' AND $cond_sem AND $cond_tp $cond_dtr";
            $debug_output .= "\n--- Query Jawaban Tugas ---\n" . $sql_tugas . "\n";
            $query_tugas = mysqli_query($koneksi, $sql_tugas);
            if (!$query_tugas) {
                $debug_output .= "Error: " . mysqli_error($koneksi) . "\n";
            } else {
                $debug_output .= "Hasil: " . mysqli_num_rows($query_tugas) . " baris ditemukan.\n";
                while ($data = mysqli_fetch_assoc($query_tugas)) {
                    if (!empty($data['tgl_update']) && strpos($data['tgl_update'], '0000-00-00') === false) {
                        $tanggal_tugas_saja = date('Y-m-d', strtotime($data['tgl_update']));
                        $nomor_pertemuan_tugas = $pertemuan_tugas_map[$tanggal_tugas_saja] ?? '?';
                        $keterangan_tugas = htmlspecialchars($data['nama_mapel']) . ' : Tugas - ' . $nomor_pertemuan_tugas;
                        // Fallback: jika nilai tugas NULL, pakai rata-rata gabungan dari nilai_sts
                        $nilai_tugas = $data['nilai'];
                        if ($nilai_tugas === null || $nilai_tugas === '') {
                            $sem_esc = mysqli_real_escape_string($koneksi, $semester);
                            $tp_esc  = mysqli_real_escape_string($koneksi, $tp);
                            $q_fb = "SELECT nilai_harian FROM nilai_sts WHERE idsiswa='$id_siswa' AND mapel='$mapel_id' AND semester='$sem_esc' AND tp='$tp_esc' LIMIT 1";
                            $r_fb = mysqli_query($koneksi, $q_fb);
                            if ($r_fb && mysqli_num_rows($r_fb) > 0) {
                                $row_fb = mysqli_fetch_assoc($r_fb);
                                $nilai_tugas = $row_fb['nilai_harian'];
                            }
                        }

                        $semua_nilai[] = [
                            'tanggal' => strtotime($data['tgl_update']),
                            'nilai' => ($nilai_tugas === null || $nilai_tugas === '' ? '<i>Belum dinilai</i>' : $nilai_tugas),
                            'keterangan' => '<span class="badge badge-tugas">' . $keterangan_tugas . '</span>'
                        ];
                    }
                }
            }

            // 3. Ambil data dari 'nilai' (E-Learning)
            // Toleransi: kode mapel boleh tidak di awal string kode_nama
            $sql_elearning = "SELECT n.ujian_selesai, n.nilai, mp.nama_mapel, mp.id AS mapel_ujian FROM nilai n INNER JOIN ujian u ON n.id_ujian = u.id_ujian INNER JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) >= 1 WHERE n.id_siswa='$id_siswa' AND n.semester='$semester' AND n.tp='$tp' AND n.nilai IS NOT NULL";
            $debug_output .= "\n--- Query E-Asessmen ---\n" . $sql_elearning . "\n";
            $query_elearning = mysqli_query($koneksi, $sql_elearning);
            if (!$query_elearning) {
                $debug_output .= "Error: " . mysqli_error($koneksi) . "\n";
            } else {
                $debug_output .= "Hasil: " . mysqli_num_rows($query_elearning) . " baris ditemukan (sebelum difilter oleh PHP).\n";
                while ($data = mysqli_fetch_assoc($query_elearning)) {
                    if ($data['mapel_ujian'] == $mapel_id) {
                        if (!empty($data['ujian_selesai']) && strpos($data['ujian_selesai'], '0000-00-00') === false) {
                            $semua_nilai[] = [
                                'tanggal' => strtotime($data['ujian_selesai']),
                                'nilai' => $data['nilai'],
                                'keterangan' => '<span class="badge badge-elearning">E-Asessmen: ' . htmlspecialchars($data['nama_mapel']) . '</span>'
                            ];
                        }
                    }
                }
            }

            $debug_output .= "\n--- Total Nilai Terkumpul ---\n";
            $debug_output .= "Jumlah item sebelum diurutkan: " . count($semua_nilai) . "\n";

            // Urutkan semua data berdasarkan tanggal (terbaru dulu)
            if (!empty($semua_nilai)) {
                usort($semua_nilai, function($a, $b) {
                    return ($a['tanggal'] && $b['tanggal']) ? $b['tanggal'] <=> $a['tanggal'] : 0;
                });
            }

            // Array untuk terjemahan bulan
            $bulan_indonesia = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];

            // Tampilkan data yang sudah diurutkan
            if (empty($semua_nilai)) {
                echo '<tr><td colspan="4" class="no-data">Tidak ada rincian nilai yang ditemukan untuk mata pelajaran ini.</td></tr>';
            } else {
                $no = 1;
                foreach ($semua_nilai as $nilai) {
                    if ($nilai['tanggal'] !== false) {
                        $tanggal_inggris = date('d F Y', $nilai['tanggal']);
                        $nama_bulan_inggris = date('F', $nilai['tanggal']);
                        $nama_bulan_indonesia = $bulan_indonesia[$nama_bulan_inggris] ?? $nama_bulan_inggris;
                        $tanggal_indonesia = str_replace($nama_bulan_inggris, $nama_bulan_indonesia, $tanggal_inggris);
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $tanggal_indonesia; ?></td>
                        <td><?= $nilai['nilai']; ?></td>
                        <td><?= $nilai['keterangan']; ?></td>
                    </tr>
            <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    // --- CETAK SEMUA OUTPUT DEBUG DI DALAM KOMENTAR HTML ---
    echo "\n<!--\n" . htmlspecialchars($debug_output, ENT_QUOTES, 'UTF-8') . "\n-->\n";
    ?>
</div>
