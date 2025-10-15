<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// ===================================================================================
// KODE INI MENENTUKAN APAKAH PROSESNYA ADALAH INPUT BARU ATAU UPDATE
// Input baru mengirim 'materi', sedangkan update tidak.
// ===================================================================================

if (isset($_POST['nilai']) && is_array($_POST['nilai']) && !isset($_POST['materi'])) {
    // ===============================================================================
    // PROSES UPDATE NILAI YANG SUDAH ADA (DARI HALAMAN LIHAT NILAI)
    // ===============================================================================

    // Ambil data dari form update
    $nilai_arr = $_POST['nilai'];
    $idsiswa_arr = $_POST['idsiswa'];

    // Data untuk redirect kembali
    $k = $_POST['k'];
    $m = $_POST['m']; // Ini adalah ID Mapel yang terenkripsi
    $g = $_POST['g'];
    $p_tgl = $_POST['p_tgl'];

    // --- PERBAIKAN DIMULAI DI SINI ---
    // Langsung dekripsi ID Mapel dari form, ini lebih aman daripada query ulang.
    $mapel_id = dekripsi($m);
    // --- PERBAIKAN SELESAI ---

    // Pengaturan Umum
    $smt = $setting['semester'];
    $tapel = $setting['tp'];

    // Siapkan statement di luar loop untuk efisiensi (disesuaikan dengan kolom 'id')
    $stmt_update_harian = $koneksi->prepare("UPDATE nilai_harian SET nilai = ? WHERE id = ?");
    $stmt_rata_rata = $koneksi->prepare("SELECT SUM(nilai) AS total, COUNT(*) AS jumlah FROM nilai_harian WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tapel = ?");
    $stmt_update_sts = $koneksi->prepare("UPDATE nilai_sts SET nilai_harian = ? WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tp = ?");

    $siswa_diproses = []; // Untuk memastikan rekapitulasi hanya sekali per siswa

    // Mulai transaksi database untuk keamanan data
    $koneksi->begin_transaction();

    try {
        // Loop per nilai yang diupdate (disesuaikan dengan 'id')
        foreach ($nilai_arr as $id => $nilai_baru) {
            // 1. Update nilai di tabel nilai_harian
            $stmt_update_harian->bind_param("ii", $nilai_baru, $id);
            $stmt_update_harian->execute();

            // Ambil id siswa yang bersangkutan dengan nilai ini
            $idsiswa = $idsiswa_arr[$id];

            // Tandai siswa ini untuk dihitung ulang rata-ratanya
            if (!in_array($idsiswa, $siswa_diproses)) {
                $siswa_diproses[] = $idsiswa;
            }
        }

        // 2. Hitung ulang rata-rata untuk setiap siswa yang nilainya diubah
        foreach ($siswa_diproses as $id_siswa_update) {
            // --- PERBAIKAN: Gunakan $mapel_id yang sudah didekripsi ---
            // Hitung ulang nilai rata-rata harian
            $stmt_rata_rata->bind_param("isss", $id_siswa_update, $mapel_id, $smt, $tapel);
            $stmt_rata_rata->execute();
            $d = $stmt_rata_rata->get_result()->fetch_assoc();
            $rata = ($d && $d['jumlah'] > 0) ? round($d['total'] / $d['jumlah']) : 0;

            // Update nilai_harian di tabel nilai_sts
            $stmt_update_sts->bind_param("issss", $rata, $id_siswa_update, $mapel_id, $smt, $tapel);
            $stmt_update_sts->execute();
        }

        // Jika semua berhasil, commit transaksi
        $koneksi->commit();
    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, batalkan semua perubahan
        $koneksi->rollback();
        echo "Error: " . $exception->getMessage();
        exit();
    }

    // Tutup semua statement
    $stmt_update_harian->close();
    $stmt_rata_rata->close();
    $stmt_update_sts->close();

    // Kirim notifikasi WA setelah UPDATE jika tidak ditunda
    $defer_wa = isset($_REQUEST['defer_wa']) && $_REQUEST['defer_wa'] == '1';
    $is_ajax  = isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == '1';

    if (!$defer_wa && !empty($setting['url_api'])) {
        // Ambil semua baris yang baru diupdate untuk mengirim notifikasi
        $ids_harian = array_keys($nilai_arr);
        if (!empty($ids_harian)) {
            $in = implode(',', array_map('intval', $ids_harian));
            $sql_rows = "SELECT nh.*, s.nama AS nama_siswa, s.nowa AS no_wa
                         FROM nilai_harian nh
                         LEFT JOIN siswa s ON s.id_siswa = nh.idsiswa
                         WHERE nh.id IN ($in)";
            $stmt_rows = $koneksi->prepare($sql_rows);
            $stmt_rows->execute();
            $res_rows = $stmt_rows->get_result();

            // Statement untuk ambil nama mapel/guru dan deskripsi materi
            $stmt_mapel_nm = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id = ?");
            $stmt_guru_nm  = $koneksi->prepare("SELECT nama FROM users WHERE id_user = ?");
            $stmt_desc_kd  = $koneksi->prepare("SELECT deskripsi FROM deskripsi WHERE mapel = ? AND kd = ? LIMIT 1");
            $stmt_desc_lm  = $koneksi->prepare("SELECT materi FROM lingkup WHERE mapel = ? AND lm = ? LIMIT 1");

            while ($row = $res_rows->fetch_assoc()) {
                $nama_mapel = '';
                $stmt_mapel_nm->bind_param('i', $row['mapel']);
                $stmt_mapel_nm->execute();
                if ($r = $stmt_mapel_nm->get_result()->fetch_assoc()) {
                    $nama_mapel = $r['nama_mapel'] ?? '';
                }

                $nama_guru = '';
                $stmt_guru_nm->bind_param('i', $row['guru']);
                $stmt_guru_nm->execute();
                if ($r = $stmt_guru_nm->get_result()->fetch_assoc()) {
                    $nama_guru = $r['nama'] ?? '';
                }

                // Deskripsi materi
                $materi_desc = '';
                if ($row['kuri'] == '1') {
                    $stmt_desc_kd->bind_param('is', $row['mapel'], $row['materi']);
                    $stmt_desc_kd->execute();
                    if ($d = $stmt_desc_kd->get_result()->fetch_assoc()) {
                        $materi_desc = $d['deskripsi'] ?? '';
                    }
                } else {
                    $stmt_desc_lm->bind_param('is', $row['mapel'], $row['materi']);
                    $stmt_desc_lm->execute();
                    if ($d = $stmt_desc_lm->get_result()->fetch_assoc()) {
                        $materi_desc = $d['materi'] ?? '';
                    }
                }

                $tgl_kirim = date('d-m-Y', strtotime($row['tanggal']));
                $status_tuntas = ((int)$row['nilai'] >= (int)$row['kkm']) ? 'Tuntas' : 'Tidak Tuntas';
                $label_materi = ($row['kuri'] == '1' ? 'KD ' : 'LM ') . $row['materi'];
                $materi_line = $label_materi . (!empty($materi_desc) ? ' - ' . $materi_desc : '');

                if (!empty($row['no_wa'])) {
                    $pesan = "INFORMASI NILAI HARIAN - " . ($setting['sekolah'] ?? '') . "\n\n"
                           . "Nama Siswa: " . ($row['nama'] ?? $row['nama_siswa'] ?? '') . "\n"
                           . "Kelas: " . $row['kelas'] . "\n"
                           . "Mata Pelajaran: " . $nama_mapel . "\n"
                           . "Tanggal: " . $tgl_kirim . "\n"
                           . "Materi: " . $materi_line . "\n"
                           . "Nilai: *" . $row['nilai'] . "* (KKM: " . $row['kkm'] . ")\n"
                           . "Keterangan: " . $status_tuntas . "\n\n"
                           . "Guru: " . $nama_guru . "\n"
                           . "Pesan ini dikirim otomatis oleh sistem.";

                    $curl = curl_init();
                    curl_setopt_array($curl, [
                        CURLOPT_URL => rtrim($setting['url_api'], '/') . '/send-message',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 10,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => [
                            'message' => $pesan,
                            'number'  => $row['no_wa']
                        ]
                    ]);
                    curl_exec($curl);
                    curl_close($curl);
                }
            }

            $stmt_rows->close();
            $stmt_mapel_nm->close();
            $stmt_guru_nm->close();
            $stmt_desc_kd->close();
            $stmt_desc_lm->close();
        }
    }

    if ($is_ajax) {
        // Balas JSON agar frontend bisa trigger progressbox kirim WA
        $kelas_plain = dekripsi($k);
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        if (function_exists('ob_get_length')) {
            while (ob_get_length()) { ob_end_clean(); }
        }
        echo json_encode([
            'status' => 'ok',
            'op' => 'update',
            'scope' => [
                'kelas'   => $kelas_plain,
                'mapel'   => $mapel_id,
                'guru'    => dekripsi($g),
                'tanggal' => $p_tgl
            ]
        ]);
        exit();
    } else {
        // Redirect kembali ke halaman lihat nilai dengan filter yang sama
        $redirect_url = "../?pg=" . enkripsi('nilai') . "&ac=" . enkripsi('lihat') . "&k=" . $k . "&m=" . $m . "&g=" . $g;
        if (!empty($p_tgl)) {
            $redirect_url .= "&p_tgl=" . $p_tgl;
        }
        header("Location: " . $redirect_url);
        exit();
    }

} else {
    // ===============================================================================
    // PROSES INPUT NILAI BARU (KODE ASLI DARI input.php)
    // ===============================================================================

    // Ambil nilai yang sama untuk semua siswa
    $materi_pilihan = $_POST['materi'];
    $kuri_pilihan   = $_POST['kuri'];
    $smt            = $setting['semester'];
    $tapel          = $setting['tp'];

    // Ambil nilai yang berupa array untuk setiap siswa
    $tanggal_arr = $_POST['tanggal'];
    $idsiswa_arr = $_POST['idsiswa'];
    $kelas_arr   = $_POST['kelas'];
    $mapel_arr   = $_POST['mapel'];
    $guru_arr    = $_POST['guru'];
    $kkm_arr     = $_POST['kkm'];
    $nilai_arr   = $_POST['nilai'];

    $count = count($idsiswa_arr);

    // Siapkan statement di luar loop untuk efisiensi
    $stmt_harian = $koneksi->prepare("
        INSERT INTO nilai_harian
        (idsiswa, hari, tanggal, kelas, mapel, kkm, kuri, nilai, guru, materi, semester, tapel)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt_cek_sts = $koneksi->prepare("SELECT * FROM nilai_sts WHERE idsiswa=? AND mapel=? AND semester=? AND tp=?");
    $stmt_insert_sts = $koneksi->prepare("
        INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_harian, guru, semester, tp)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt_update_sts = $koneksi->prepare("UPDATE nilai_sts SET nilai_harian = ? WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tp = ?");
    $stmt_get_nis = $koneksi->prepare("SELECT nis FROM siswa WHERE id_siswa = ?");
    $stmt_rata_rata = $koneksi->prepare("
        SELECT SUM(nilai) AS total, COUNT(*) AS jumlah
        FROM nilai_harian
        WHERE idsiswa = ? AND mapel = ? AND semester = ? AND tapel = ?
    ");

    // Siapkan statement tambahan untuk kebutuhan notifikasi WA
    $stmt_get_siswa_wa = $koneksi->prepare("SELECT nama, nowa FROM siswa WHERE id_siswa = ?");
    $stmt_get_mapel_nm = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id = ?");
    $stmt_get_guru_nm  = $koneksi->prepare("SELECT nama FROM users WHERE id_user = ?");

    // Ambil materi deskripsi sekali untuk semua siswa (berdasarkan pilihan materi & mapel pertama)
    $materi_desc_text = '';
    $mapel_first = isset($mapel_arr[0]) ? (int)$mapel_arr[0] : 0;
    if (!empty($materi_pilihan) && $mapel_first > 0) {
        if ($kuri_pilihan == '1') {
            $stmt = $koneksi->prepare("SELECT deskripsi FROM deskripsi WHERE mapel = ? AND kd = ? LIMIT 1");
            $stmt->bind_param('is', $mapel_first, $materi_pilihan);
            $stmt->execute();
            if ($r = $stmt->get_result()->fetch_assoc()) {
                $materi_desc_text = $r['deskripsi'] ?? '';
            }
            $stmt->close();
        } else {
            $stmt = $koneksi->prepare("SELECT materi FROM lingkup WHERE mapel = ? AND lm = ? LIMIT 1");
            $stmt->bind_param('is', $mapel_first, $materi_pilihan);
            $stmt->execute();
            if ($r = $stmt->get_result()->fetch_assoc()) {
                $materi_desc_text = $r['materi'] ?? '';
            }
            $stmt->close();
        }
    }

    // Loop per siswa untuk insert/update data
    for ($i = 0; $i < $count; $i++) {
        $id       = $idsiswa_arr[$i];
        $mp       = $mapel_arr[$i];
        $tgl      = $tanggal_arr[$i];
        $hari     = date('D', strtotime($tgl));
        $kls      = $kelas_arr[$i];
        $kkmVal   = $kkm_arr[$i];
        $guruid   = $guru_arr[$i];
        $nilaiVal = $nilai_arr[$i];

        // 1. Insert ke nilai_harian (menggunakan nilai materi yang sama untuk semua)
        $stmt_harian->bind_param(
            "isssisiisiss",
            $id, $hari, $tgl, $kls, $mp, $kkmVal, $kuri_pilihan, $nilaiVal, $guruid, $materi_pilihan, $smt, $tapel
        );
        $stmt_harian->execute();

        // 2. Cek apakah sudah ada di nilai_sts
        $stmt_cek_sts->bind_param("isss", $id, $mp, $smt, $tapel);
        $stmt_cek_sts->execute();
        $result_cek = $stmt_cek_sts->get_result();

        if ($result_cek->num_rows == 0) {
            // Belum ada, dapatkan NIS
            $stmt_get_nis->bind_param("i", $id);
            $stmt_get_nis->execute();
            $nisData = $stmt_get_nis->get_result()->fetch_assoc();
            $nis = $nisData ? $nisData['nis'] : '';

            // Masukkan data baru ke nilai_sts
            $stmt_insert_sts->bind_param(
                "isssisss",
                $id, $nis, $kls, $mp, $nilaiVal, $guruid, $smt, $tapel
            );
            $stmt_insert_sts->execute();
        } else {
            // Sudah ada, hitung ulang nilai rata-rata harian
            $stmt_rata_rata->bind_param("isss", $id, $mp, $smt, $tapel);
            $stmt_rata_rata->execute();
            $d = $stmt_rata_rata->get_result()->fetch_assoc();
            $rata = ($d && $d['jumlah'] > 0) ? round($d['total'] / $d['jumlah']) : 0;

            // Update nilai_harian di nilai_sts
            $stmt_update_sts->bind_param("issss", $rata, $id, $mp, $smt, $tapel);
            $stmt_update_sts->execute();
        }

        // 3. Kirim Notifikasi WA ke Ortu (jika tidak ditunda dan API diset & nomor tersedia)
        //    Pesan dikirim per siswa setelah data tersimpan.
        $defer_wa = isset($_REQUEST['defer_wa']) && $_REQUEST['defer_wa'] == '1';
        if (!$defer_wa && !empty($setting['url_api'])) {
            // Ambil data siswa (nama & nomor WA ortu)
            $stmt_get_siswa_wa->bind_param("i", $id);
            $stmt_get_siswa_wa->execute();
            $info_siswa = $stmt_get_siswa_wa->get_result()->fetch_assoc();

            // Ambil nama mapel
            $nama_mapel = '';
            $stmt_get_mapel_nm->bind_param("i", $mp);
            $stmt_get_mapel_nm->execute();
            if ($row_mp = $stmt_get_mapel_nm->get_result()->fetch_assoc()) {
                $nama_mapel = $row_mp['nama_mapel'] ?? '';
            }

            // Ambil nama guru
            $nama_guru = '';
            $stmt_get_guru_nm->bind_param("i", $guruid);
            $stmt_get_guru_nm->execute();
            if ($row_gr = $stmt_get_guru_nm->get_result()->fetch_assoc()) {
                $nama_guru = $row_gr['nama'] ?? '';
            }

            $no_wa = $info_siswa['nowa'] ?? '';
            if (!empty($no_wa)) {
                $tgl_kirim = date('d-m-Y', strtotime($tgl));
                $status_tuntas = ($nilaiVal >= $kkmVal) ? 'Tuntas' : 'Tidak Tuntas';
                $label_materi = ($kuri_pilihan == '1' ? 'KD ' : 'LM ') . $materi_pilihan;
                $materi_line = $label_materi . (!empty($materi_desc_text) ? ' - ' . $materi_desc_text : '');

                // Susun pesan
                $pesan = "INFORMASI NILAI HARIAN - " . ($setting['sekolah'] ?? '') . "\n\n"
                       . "Nama Siswa: " . ($info_siswa['nama'] ?? '') . "\n"
                       . "Kelas: " . $kls . "\n"
                       . "Mata Pelajaran: " . $nama_mapel . "\n"
                       . "Tanggal: " . $tgl_kirim . "\n"
                       . "Materi: " . $materi_line . "\n"
                       . "Nilai: *" . $nilaiVal . "* (KKM: " . $kkmVal . ")\n"
                       . "Keterangan: " . $status_tuntas . "\n\n"
                       . "Guru: " . $nama_guru . "\n"
                       . "Pesan ini dikirim otomatis oleh sistem.";

                // Kirim ke API WhatsApp Gateway
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => rtrim($setting['url_api'], '/') . '/send-message',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => [
                        'message' => $pesan,
                        'number'  => $no_wa
                    ]
                ]);
                curl_exec($curl);
                curl_close($curl);
            }
        }
    }

    // Tutup semua statement
    $stmt_harian->close();
    $stmt_cek_sts->close();
    $stmt_insert_sts->close();
    $stmt_update_sts->close();
    $stmt_get_nis->close();
    $stmt_rata_rata->close();
    $stmt_get_siswa_wa->close();
    $stmt_get_mapel_nm->close();
    $stmt_get_guru_nm->close();

    // Jika AJAX diminta, balas JSON berisi scope untuk proses kirim WA dengan progressbox
    $is_ajax = isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == '1';
    if ($is_ajax) {
        $scope = [
            'kelas'   => $kelas_arr[0] ?? '',
            'mapel'   => (int)($mapel_arr[0] ?? 0),
            'guru'    => (int)($guru_arr[0] ?? 0),
            'tanggal' => $tanggal_arr[0] ?? ''
        ];
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        if (function_exists('ob_get_length')) {
            while (ob_get_length()) { ob_end_clean(); }
        }
        echo json_encode(['status' => 'ok', 'op' => 'insert', 'scope' => $scope]);
        exit();
    }
    // Redirect akan ditangani oleh AJAX di halaman input nilai jika bukan AJAX
}
?>
