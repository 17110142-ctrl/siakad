<style>
/* Header */
.page h4 {
    font-weight: bold;
    margin-bottom: 20px;
    color: #2c3e50;
}

/* Card style */
.card {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 20px;
}

/* Card Header */
.card-header {
    background-color: #f5f5ff;
    border-bottom: 1px solid #ddd;
}

.card-title h5 {
    margin: 0;
    font-weight: 600;
    color: #34495e;
}

/* Table Styling */
.table {
    border-collapse: collapse !important;
    width: 100%;
}

.table thead th {
    background-color: #e9ecef;
    text-align: center;
    vertical-align: middle;
    font-weight: bold;
    font-size: 13px;
    color: #2c3e50;
}

.table tbody td {
    vertical-align: middle;
    text-align: center;
    font-size: 13px;
    color: #2f3640;
}

/* Dropdown layout */
.filter-section {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end; /* Menyejajarkan item ke bagian bawah */
    gap: 15px;
    margin-bottom: 20px;
}

.filter-group {
    /* Grup untuk setiap label dan input */
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

/* Responsive tweaks */
@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch; /* Membuat item memenuhi lebar pada layar kecil */
    }
}
</style>

<div class="row">
    <div class="col">
        <div class="page">
            <h4>LAPORAN NILAI SISWA</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <?php
        // 0. Dapatkan detail user yang sedang login
        $current_user_id = $_SESSION['id_user'] ?? 0;
        $is_student_logged = isset($_SESSION['id_siswa']) && $_SESSION['id_siswa'] !== '';
        $current_user = [];
        $user_level = 'guru';
        $user_is_walas = false;
        $walas_kelas = '';

        if ($current_user_id > 0) {
            $user_detail_query = mysqli_query($koneksi, "SELECT level, walas FROM users WHERE id_user = '$current_user_id'");
            if ($user_detail_query) {
                $current_user = mysqli_fetch_assoc($user_detail_query) ?: [];
            }
            $user_level = $current_user['level'] ?? 'guru';
            $user_is_walas = !empty($current_user['walas']);
            $walas_kelas = $user_is_walas ? $current_user['walas'] : '';
        }

        if ($is_student_logged) {
            $user_level = 'siswa';
        }

        $student_info = null;
        $student_id = 0;
        if ($is_student_logged) {
            $student_id = (int)$_SESSION['id_siswa'];
            $student_query = mysqli_query($koneksi, "SELECT id_siswa, nis, nisn, nama, kelas FROM siswa WHERE id_siswa='$student_id' LIMIT 1");
            if ($student_query && mysqli_num_rows($student_query) === 1) {
                $student_info = mysqli_fetch_assoc($student_query);
            }
        }

        if ($is_student_logged) {
            $kelas_list = [];
            $kelas_hist_query = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM nilai_sts WHERE idsiswa='$student_id' ORDER BY kelas ASC");
            if ($kelas_hist_query && mysqli_num_rows($kelas_hist_query) > 0) {
                while ($kelas_row = mysqli_fetch_assoc($kelas_hist_query)) {
                    if (!empty($kelas_row['kelas'])) {
                        $kelas_list[] = ['kelas' => $kelas_row['kelas']];
                    }
                }
            }
            if (empty($kelas_list) && $student_info) {
                $kelas_list[] = ['kelas' => $student_info['kelas']];
            }
        }

        // 1. Ambil semua data master untuk filter
        $kelas_list = [];
        $kelas_query = mysqli_query($koneksi, "SELECT kelas FROM kelas ORDER BY kelas ASC");
        if($kelas_query) {
            while ($row = mysqli_fetch_assoc($kelas_query)) {
                $kelas_list[] = $row;
            }
        }

        $tp_list = [];
        $tp_query = mysqli_query($koneksi, "SELECT DISTINCT tp FROM nilai_sts ORDER BY tp DESC");
        if($tp_query) {
            while ($row = mysqli_fetch_assoc($tp_query)) {
                $tp_list[] = $row['tp'];
            }
        }

        $guru_list = [];
        if ($user_level == 'admin') {
            $guru_query = mysqli_query($koneksi, "SELECT id_user, nama FROM users WHERE level='guru' ORDER BY nama ASC");
            if($guru_query) {
                while ($row = mysqli_fetch_assoc($guru_query)) {
                    $guru_list[] = $row;
                }
            }
        }

        // 2. Tentukan filter yang aktif berdasarkan level user
        $default_kelas = $kelas_list[0]['kelas'] ?? '';
        if ($is_student_logged && $student_info) {
            $default_kelas = $student_info['kelas'];
        }
        $active_kelas = !empty($_GET['kelas']) ? $_GET['kelas'] : $default_kelas;
        $active_tp = !empty($_GET['tp']) ? $_GET['tp'] : ($tp_list[0] ?? '');
        $active_semester = $_GET['semester'] ?? '1';

        if ($user_level == 'admin') {
            $active_guru = $_GET['guru'] ?? 'semua';
        } elseif ($is_student_logged) {
            $active_guru = 'semua';
        } else {
            $active_guru = $current_user_id;
        }

        if ($is_student_logged && $student_info) {
            $active_siswa = (string)$student_info['id_siswa'];
        } else {
            $active_siswa = isset($_GET['siswa']) ? trim($_GET['siswa']) : '';
        }

        // 2a. Daftar siswa per kelas untuk kebutuhan biodata / filter opsional
        $students_list = [];
        if ($is_student_logged) {
            if ($student_info) {
                $students_list[] = $student_info;
            }
        } elseif (!empty($active_kelas)) {
            $kelas_esc = mysqli_real_escape_string($koneksi, $active_kelas);
            $students_query = mysqli_query($koneksi, "SELECT id_siswa, nis, nisn, nama FROM siswa WHERE kelas='$kelas_esc' ORDER BY nama ASC");
            if ($students_query) {
                while ($row = mysqli_fetch_assoc($students_query)) {
                    $students_list[] = $row;
                }
            }
        }

        // 3. Bangun Query Utama yang Efisien dengan JOIN
        $query_str = "
            SELECT 
                n.nis, n.nilai_harian, n.nilai_sts, n.nilai_sas,
                s.nama AS nama_siswa, 
                m.nama_mapel, 
                u.nama AS nama_guru 
            FROM 
                nilai_sts n
            LEFT JOIN 
                siswa s ON n.idsiswa = s.id_siswa
            LEFT JOIN 
                mata_pelajaran m ON n.mapel = m.id
            LEFT JOIN 
                users u ON n.guru = u.id_user
            WHERE 
                n.kelas='$active_kelas' 
                AND n.tp='$active_tp' 
                AND n.semester='$active_semester'
        ";

        // Logika filter guru yang lebih spesifik
        if (!$is_student_logged) {
            if ($user_level == 'admin') {
                if ($active_guru != 'semua') {
                    $query_str .= " AND n.guru='$active_guru'";
                }
            } elseif ($user_is_walas) {
                // Jika user adalah wali kelas, cek kelas yang dipilih
                if ($active_kelas != $walas_kelas) {
                    // Jika BUKAN kelasnya, hanya tampilkan nilai yang diinput sendiri
                    $query_str .= " AND n.guru='$current_user_id'";
                }
                // Jika IYA kelasnya, tidak ada filter tambahan, tampilkan semua guru
            } else {
                // Jika user adalah guru biasa, selalu tampilkan nilai yang diinput sendiri
                $query_str .= " AND n.guru='$current_user_id'";
            }
        }

        if ($active_siswa !== '') {
            $siswa_id_esc = mysqli_real_escape_string($koneksi, $active_siswa);
            $query_str .= " AND n.idsiswa='$siswa_id_esc'";
        }

        // Tambahkan pengurutan berdasarkan nama siswa
        $query_str .= " ORDER BY s.nama ASC, m.nama_mapel ASC";
        
        $query = mysqli_query($koneksi, $query_str);

        $selected_student = null;
        $wali_kelas_nama = '';
        $guru_wali_label = '';

        if ($active_siswa !== '') {
            $siswa_id_esc = mysqli_real_escape_string($koneksi, $active_siswa);

            if ($is_student_logged && $student_info && (string)$student_info['id_siswa'] === $active_siswa) {
                $selected_student = $student_info;
            } else {
                $bio_res = mysqli_query($koneksi, "SELECT id_siswa, nama, nis, nisn, kelas FROM siswa WHERE id_siswa='$siswa_id_esc' LIMIT 1");
                if ($bio_res && mysqli_num_rows($bio_res) === 1) {
                    $selected_student = mysqli_fetch_assoc($bio_res);
                }
            }

            if ($selected_student) {
                $kelas_lookup = mysqli_real_escape_string($koneksi, $selected_student['kelas']);
                $wali_res = mysqli_query($koneksi, "SELECT nama FROM users WHERE walas='$kelas_lookup' LIMIT 1");
                if ($wali_res && mysqli_num_rows($wali_res) > 0) {
                    $wali_row = mysqli_fetch_assoc($wali_res);
                    $wali_kelas_nama = $wali_row['nama'] ?? '';
                }

                $gw_res = mysqli_query($koneksi, "SELECT u.nama FROM guru_wali gw JOIN users u ON u.id_user = gw.id_guru WHERE gw.id_siswa='$siswa_id_esc' ORDER BY u.nama ASC");
                $guru_wali_names = [];
                if ($gw_res) {
                    while ($gw_row = mysqli_fetch_assoc($gw_res)) {
                        if (!empty($gw_row['nama'])) {
                            $guru_wali_names[] = $gw_row['nama'];
                        }
                    }
                }
                if (!empty($guru_wali_names)) {
                    $guru_wali_label = implode(', ', $guru_wali_names);
                }
            }
        }

        $semester_label = $active_semester == '1' ? 'Ganjil' : 'Genap';
        $header_title = $selected_student ? ('Daftar Nilai - ' . ($selected_student['nama'] ?? '')) : ('Daftar Nilai Kelas ' . $active_kelas);
        if ($selected_student) {
            $header_subtitle = 'Kelas ' . ($selected_student['kelas'] ?? '-') . ' | Semester ' . $semester_label . ' | Tahun Pelajaran ' . $active_tp;
        } else {
            $header_subtitle = 'Semester ' . $semester_label . ' - Tahun Pelajaran ' . $active_tp;
        }
        ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form method="get">
                    <input type="hidden" name="pg" value="<?= $_GET['pg'] ?>">
                    <div class="filter-section">
                        <div class="filter-group">
                            <label class="form-label">Kelas:</label>
                            <select name="kelas" class="form-select">
                                <?php foreach ($kelas_list as $kelas) : ?>
                                    <option value="<?= $kelas['kelas'] ?>" <?= ($kelas['kelas'] == $active_kelas) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kelas['kelas']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="form-label">Tahun:</label>
                            <select name="tp" class="form-select">
                                <?php foreach ($tp_list as $tahun) : ?>
                                    <option value="<?= $tahun ?>" <?= ($tahun == $active_tp) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tahun) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="form-label">Semester:</label>
                            <select name="semester" class="form-select">
                                <option value="1" <?= $active_semester == '1' ? 'selected' : '' ?>>Ganjil</option>
                                <option value="2" <?= $active_semester == '2' ? 'selected' : '' ?>>Genap</option>
                            </select>
                        </div>
                        
                        <?php if ($user_level == 'admin') : ?>
                        <div class="filter-group">
                            <label class="form-label">Guru:</label>
                            <select name="guru" class="form-select">
                                <option value="semua" <?= ('semua' == $active_guru) ? 'selected' : '' ?>>Semua Guru</option>
                                <?php foreach ($guru_list as $guru) : ?>
                                    <option value="<?= $guru['id_user'] ?>" <?= ($guru['id_user'] == $active_guru) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($guru['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($students_list) && !$is_student_logged) : ?>
                        <div class="filter-group">
                            <label class="form-label">Siswa:</label>
                            <select name="siswa" class="form-select">
                                <option value="" <?= $active_siswa === '' ? 'selected' : '' ?>>Semua Siswa</option>
                                <?php foreach ($students_list as $siswa) : ?>
                                    <option value="<?= $siswa['id_siswa'] ?>" <?= ($siswa['id_siswa'] == $active_siswa) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($siswa['nama']) ?> (<?= htmlspecialchars($siswa['nis']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <?php if ($is_student_logged && $student_info) : ?>
                            <input type="hidden" name="siswa" value="<?= $student_info['id_siswa'] ?>">
                        <?php endif; ?>
                        
                        <div class="filter-group">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_student): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Biodata Siswa</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Nama Lengkap</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($selected_student['nama']); ?></dd>
                            <dt class="col-sm-4">NIS / NISN</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($selected_student['nis']); ?> / <?= htmlspecialchars($selected_student['nisn'] ?? '-'); ?></dd>
                            <dt class="col-sm-4">Kelas</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($selected_student['kelas']); ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Wali Kelas</dt>
                            <dd class="col-sm-8">
                                <?php if ($wali_kelas_nama !== '') : ?>
                                    <?= htmlspecialchars($wali_kelas_nama); ?>
                                <?php else : ?>
                                    <span class="text-muted">Belum diatur</span>
                                <?php endif; ?>
                            </dd>
                            <dt class="col-sm-4">Guru Wali</dt>
                            <dd class="col-sm-8">
                                <?php if ($guru_wali_label !== '') : ?>
                                    <?= htmlspecialchars($guru_wali_label); ?>
                                <?php else : ?>
                                    <span class="text-muted">Belum diatur</span>
                                <?php endif; ?>
                            </dd>
                            <dt class="col-sm-4">Tahun Pelajaran</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($active_tp); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h5><?= htmlspecialchars($header_title) ?></h5>
                    <small><?= htmlspecialchars($header_subtitle) ?></small>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="datatable-nilai-guru">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Mata Pelajaran</th>
                                <th>Nama Guru</th>
                                <th>Nilai Harian</th>
                                <th>STS</th>
                                <th>SAS</th>
                                <th>Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($query && mysqli_num_rows($query) > 0) {
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                                $nilai_harian = floatval($data['nilai_harian']);
                                $nilai_sts = floatval($data['nilai_sts']);
                                $nilai_sas = floatval($data['nilai_sas']);
                                $nilai_akhir = round(($nilai_harian + $nilai_sts + $nilai_sas) / 3);
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['nis']); ?></td>
                                    <td><?= htmlspecialchars($data['nama_siswa'] ?? 'Tidak Ditemukan'); ?></td>
                                    <td><?= htmlspecialchars($data['nama_mapel'] ?? 'Tidak Ditemukan'); ?></td>
                                    <td><?= htmlspecialchars($data['nama_guru'] ?? 'Tidak Ditemukan'); ?></td>
                                    <td><?= $nilai_harian; ?></td>
                                    <td><?= $nilai_sts; ?></td>
                                    <td><?= $nilai_sas; ?></td>
                                    <td><?= $nilai_akhir; ?></td>
                                </tr>
                        <?php  
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">Data tidak ditemukan untuk periode dan kelas yang dipilih.</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi DataTable untuk fungsionalitas pencarian dan sorting
    $(document).ready(function() {
        $('#datatable-nilai-guru').DataTable({
             "order": [] // Menonaktifkan pengurutan default dari DataTable agar pengurutan dari PHP (nama siswa) yang digunakan
        });
    });
</script>
