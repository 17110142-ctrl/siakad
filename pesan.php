<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
require_once(__DIR__ . '/myhome/wa_helpers.php');

function kirimPesan($nokartu, $mode)
{
    global $koneksi, $setting;

    $tanggal = date('Y-m-d');
    $tglabsen = date('d M Y H:i:s');

    $status = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM status LIMIT 1"));
    if ($status && isset($status['mode'])) {
        $mode = (int)$status['mode'];
    }

    $absen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' AND nokartu='" . mysqli_real_escape_string($koneksi, $nokartu) . "' ORDER BY id DESC LIMIT 1"));
    if (!$absen) {
        return [
            'status' => 'skip',
            'message' => 'Data absensi tidak ditemukan',
            'results' => []
        ];
    }

    $jenis = ($mode == 1 ? 'masuk' : 'pulang');
    $results = [];

    $absensiId = isset($absen['id']) ? (int)$absen['id'] : 0;
    $forceSend = !empty($_REQUEST['force']);
    if (!$forceSend && $absensiId > 0 && wa_guard_is_sent($koneksi, $absensiId, $jenis)) {
        return [
            'status' => 'skip',
            'message' => 'Notifikasi sudah dikirim via proses utama',
            'results' => []
        ];
    }

    $pesan1 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='1'"));
    $pesan2 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='2'"));
    $pesan3 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='3'"));
    $pesan4 = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='4'"));

    $siswa = null;
    if (!empty($absen['idsiswa'])) {
        $siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='" . (int)$absen['idsiswa'] . "'"));
    }

    $pegawai = null;
    if (!empty($absen['idpeg'])) {
        $pegawai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='" . (int)$absen['idpeg'] . "'"));
    }

    $notif_masuk_siswa = $pesan1['pesan1'] . " " . $pesan1['pesan2'] . " *" . ($siswa['nama'] ?? '-') . "* " . $pesan1['pesan3'] . " " . $tglabsen . " " . $pesan1['pesan4'];
    $notif_pulang_siswa = $pesan2['pesan1'] . " " . $pesan2['pesan2'] . " *" . ($siswa['nama'] ?? '-') . "* " . $pesan2['pesan3'] . " " . $tglabsen . " " . $pesan2['pesan4'];
    $notif_masuk_peg   = $pesan3['pesan1'] . " " . $pesan3['pesan2'] . " *" . ($pegawai['nama'] ?? '-') . "* " . $pesan3['pesan3'] . " " . $tglabsen . " " . $pesan3['pesan4'];
    $notif_pulang_peg  = $pesan4['pesan1'] . " " . $pesan4['pesan2'] . " *" . ($pegawai['nama'] ?? '-') . "* " . $pesan4['pesan3'] . " " . $tglabsen . " " . $pesan4['pesan4'];

    $datareg = mysqli_query($koneksi, "SELECT 1 FROM datareg WHERE nokartu='" . mysqli_real_escape_string($koneksi, $nokartu) . "' LIMIT 1");
    $cek = $datareg ? mysqli_num_rows($datareg) : 0;

    $apiUrl = $setting['url_api'] ?? '';

    $addResult = function (&$results, $params) {
        $results[] = [
            'id' => $params['id'] ?? null,
            'level' => $params['level'] ?? 'siswa',
            'jenis' => $params['jenis'] ?? 'lain',
            'nama' => $params['nama'] ?? '-',
            'number' => $params['number'] ?? '',
            'success' => !empty($params['success']) ? 1 : 0,
            'error' => $params['error'] ?? ''
        ];
    };

    if ($cek != 0) {
        if ($mode == 1) {
            if ($absen['level'] == 'pegawai') {
                // kirim ke admin
                $numAdmin = wa_normalize_number($setting['nowa'] ?? '', '62');
                if ($numAdmin !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_masuk_peg, $numAdmin, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'pegawai',
                        'idpeg' => (int)($absen['idpeg'] ?? 0),
                        'nama' => $pegawai['nama'] ?? '',
                        'number' => $numAdmin,
                        'message' => $notif_masuk_peg,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => $numAdmin,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA admin kosong'
                    ]);
                }

                // kirim ke pegawai
                $numPegawai = wa_normalize_number($pegawai['nowa'] ?? '', '62');
                if ($numPegawai !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_masuk_peg, $numPegawai, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'pegawai',
                        'idpeg' => (int)($absen['idpeg'] ?? 0),
                        'nama' => $pegawai['nama'] ?? '',
                        'number' => $numPegawai,
                        'message' => $notif_masuk_peg,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => $numPegawai,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA pegawai kosong'
                    ]);
                }
            } elseif ($absen['level'] == 'siswa') {
                $num = wa_normalize_number($siswa['nowa'] ?? '', '62');
                if ($num !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_masuk_siswa, $num, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'siswa',
                        'idsiswa' => (int)($absen['idsiswa'] ?? 0),
                        'nama' => $siswa['nama'] ?? '',
                        'number' => $num,
                        'message' => $notif_masuk_siswa,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'siswa',
                        'jenis' => $jenis,
                        'nama' => $siswa['nama'] ?? '-',
                        'number' => $num,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'siswa',
                        'jenis' => $jenis,
                        'nama' => $siswa['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA orangtua kosong'
                    ]);
                }
            }
        } elseif ($mode == 2) {
            if ($absen['level'] == 'pegawai') {
                $numAdmin = wa_normalize_number($setting['nowa'] ?? '', '62');
                if ($numAdmin !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_pulang_peg, $numAdmin, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'pegawai',
                        'idpeg' => (int)($absen['idpeg'] ?? 0),
                        'nama' => $pegawai['nama'] ?? '',
                        'number' => $numAdmin,
                        'message' => $notif_pulang_peg,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => $numAdmin,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA admin kosong'
                    ]);
                }

                $numPegawai = wa_normalize_number($pegawai['nowa'] ?? '', '62');
                if ($numPegawai !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_pulang_peg, $numPegawai, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'pegawai',
                        'idpeg' => (int)($absen['idpeg'] ?? 0),
                        'nama' => $pegawai['nama'] ?? '',
                        'number' => $numPegawai,
                        'message' => $notif_pulang_peg,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => $numPegawai,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'pegawai',
                        'jenis' => $jenis,
                        'nama' => $pegawai['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA pegawai kosong'
                    ]);
                }
            } elseif ($absen['level'] == 'siswa') {
                $num = wa_normalize_number($siswa['nowa'] ?? '', '62');
                if ($num !== '') {
                    list($ok, $code, $err) = wa_send_with_retry_simple($apiUrl, $notif_pulang_siswa, $num, 3);
                    $logId = wa_log_absen($koneksi, [
                        'tanggal' => $tanggal,
                        'jenis' => $jenis,
                        'level' => 'siswa',
                        'idsiswa' => (int)($absen['idsiswa'] ?? 0),
                        'nama' => $siswa['nama'] ?? '',
                        'number' => $num,
                        'message' => $notif_pulang_siswa,
                        'success' => $ok ? 1 : 0,
                        'http_code' => $code,
                        'error' => $err,
                        'absensi_id' => $absensiId
                    ]);
                    $addResult($results, [
                        'id' => $logId,
                        'level' => 'siswa',
                        'jenis' => $jenis,
                        'nama' => $siswa['nama'] ?? '-',
                        'number' => $num,
                        'success' => $ok,
                        'error' => $err
                    ]);
                } else {
                    $addResult($results, [
                        'level' => 'siswa',
                        'jenis' => $jenis,
                        'nama' => $siswa['nama'] ?? '-',
                        'number' => '',
                        'success' => 0,
                        'error' => 'Nomor WA orangtua kosong'
                    ]);
                }
            }
        }
    }

    $total = count($results);
    $failed = array_filter($results, function ($row) {
        return (int)($row['success'] ?? 0) === 0;
    });

    return [
        'status' => ($total === 0 ? 'empty' : (count($failed) === 0 ? 'ok' : (count($failed) === $total ? 'fail' : 'partial'))),
        'results' => $results,
        'success_count' => $total - count($failed),
        'failed_count' => count($failed)
    ];
}

if (isset($_GET['nokartu']) && isset($_GET['mode'])) {
    $response = kirimPesan($_GET['nokartu'], $_GET['mode']);
    $format = $_GET['format'] ?? $_POST['format'] ?? '';
    if ($format === 'json' || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'json') !== false)) {
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
