<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once("../../myhome/wa_helpers.php");

// Atur header untuk output JSON
header('Content-Type: application/json');

date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
$waktu = date('H:i:s');
$pg = $_GET['pg'] ?? '';

// Array untuk menyimpan log debug
$debug_log = [];
$isAuto = !empty($_POST['auto']);

// Kirim notifikasi WA dengan retry + log, mengembalikan ringkasan
function kirimNotifikasi($koneksi, $setting, $pesan, $nomorTujuan, $idsiswa, $namaSiswa, $tanggal, &$debug_log) {
    wa_ensure_log_table($koneksi);

    $url_api = $setting['url_api'] ?? '';
    $nomor = wa_normalize_number($nomorTujuan, '62');

    if (empty($nomor) || empty($url_api)) {
        $debug_log[] = "❌ GAGAL: Nomor WA atau URL API kosong.";
        return [ 'ok' => false, 'http_code' => 0, 'error' => 'No WA/URL API kosong', 'log_id' => null, 'number' => $nomor ];
    }

    $debug_log[] = "📞 Mencoba mengirim ke API: " . $url_api;
    list($ok, $code, $err) = wa_send_with_retry_simple($url_api, $pesan, $nomor, 3);

    $log_id = wa_log_absen($koneksi, [
        'tanggal' => $tanggal,
        'jenis'   => 'lain',
        'level'   => 'siswa',
        'idsiswa' => (int)$idsiswa,
        'nama'    => (string)$namaSiswa,
        'number'  => (string)$nomor,
        'message' => (string)$pesan,
        'success' => $ok ? 1 : 0,
        'http_code'=> (int)$code,
        'error'   => (string)$err
    ]);

    if ($ok) {
        $debug_log[] = "✅ SUKSES: Notifikasi berhasil dikirim via API.";
    } else {
        $debug_log[] = "❌ GAGAL: API/Network error - " . $err;
    }

    return [ 'ok' => (bool)$ok, 'http_code' => (int)$code, 'error' => (string)$err, 'log_id' => (string)$log_id, 'number' => $nomor ];
}

function hitungKeterlambatan($jam_masuk_str, $jam_absen_str) {
    $jam_masuk = strtotime($jam_masuk_str);
    $jam_absen = strtotime($jam_absen_str);
    $selisih = $jam_absen - $jam_masuk;
    if ($selisih > 0) {
        $jam = floor($selisih / 3600);
        $menit = floor(($selisih % 3600) / 60);
        return 'Terlambat ' . $jam . ' jam, ' . $menit . ' menit';
    } else {
        return 'Tepat Waktu';
    }
}

if ($pg == 'siswa') {
    $id = $_POST['id'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $ket = $_POST['ket'] ?? '';

    $debug_log[] = "🚀 Proses dimulai untuk siswa ID: $id, Keterangan: $ket";

    if (empty($id) || empty($kelas) || empty($ket)) {
        echo json_encode(['status' => 'ERROR', 'message' => 'Data tidak lengkap', 'debug' => $debug_log]);
        exit;
    }

    $jam_masuk_setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT masuk FROM waktu LIMIT 1"))['masuk'];
    if ($ket == 'H') $keterangan = 'Absen Otomatis dari Sistem';
    elseif ($ket == 'S') $keterangan = 'Sakit';
    elseif ($ket == 'I') $keterangan = 'Izin';
    else $keterangan = 'Alpa';
    
    $data = ['tanggal' => $tanggal, 'idsiswa' => $id, 'kelas' => $kelas, 'ket' => $ket, 'keterangan' => $keterangan, 'masuk' => ($ket == 'H' ? $waktu : null), 'mesin' => 'MANUAL', 'level' => 'siswa', 'bulan' => date('m'), 'tahun' => date('Y')];

    if (!insert($koneksi, 'absensi', $data)) {
        $debug_log[] = "❌ FATAL: Gagal menyimpan data absensi ke database.";
        echo json_encode(['status' => 'ERROR', 'message' => 'Gagal simpan absensi', 'debug' => $debug_log]);
        exit;
    }

    $debug_log[] = "✔️ Absensi berhasil disimpan ke database.";
    
    $setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT url_api FROM aplikasi LIMIT 1"));
    $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama, nowa FROM siswa WHERE id_siswa='".(int)$id."'"));
    
    if (!$siswa) {
        $debug_log[] = "⚠️ PERINGATAN: Data siswa dengan ID $id tidak ditemukan. Notifikasi tidak dikirim.";
        echo json_encode(['status' => 'OK', 'message' => 'Absen OK, siswa tidak ditemukan', 'debug' => $debug_log]);
        exit;
    }
    
    $debug_log[] = "✔️ Data siswa ditemukan: " . $siswa['nama'];

    if (empty($siswa['nowa'])) {
        $debug_log[] = "⚠️ PERINGATAN: Siswa tidak memiliki nomor WA. Notifikasi tidak dikirim.";
        echo json_encode(['status' => 'OK', 'message' => 'Absen OK, No WA kosong', 'debug' => $debug_log, 'wa' => ['ok'=>false,'log_id'=>null,'number'=>'','error'=>'No WA']]);
        exit;
    }

    $debug_log[] = "✔️ Nomor WA ditemukan: " . $siswa['nowa'];

    $template_id = null;
    if($ket == 'H') $template_id = 1;
    if($ket == 'I') $template_id = 9;
    if($ket == 'S') $template_id = 10;
    if($ket == 'A') $template_id = 11;

    $debug_log[] = "ℹ️ Mencari template pesan dengan ID: $template_id";

    $wa_result = null;
    if($template_id) {
        $pesan_template = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id='$template_id'"));
        if($pesan_template) {
            $debug_log[] = "✔️ Template pesan ditemukan.";
            $tanggal_id = wa_format_tanggal_indonesia($tanggal, true);
            $pesan_wa = $pesan_template['pesan1'] . " "
                . $pesan_template['pesan2'] . " *" . $siswa['nama'] . "* "
                . $pesan_template['pesan3'] . " " . $tanggal_id . ". "
                . "Dengan keterangan: *" . $keterangan . "*. "
                . $pesan_template['pesan4'];
            if ($isAuto) {
                $pesan_wa .= " Absensi dilakukan secara otomatis melalui fitur Hadir Semua.";
            }
            $wa_result = kirimNotifikasi($koneksi, $setting, $pesan_wa, $siswa['nowa'], $id, $siswa['nama'], $tanggal, $debug_log);
        } else {
            $debug_log[] = "⚠️ PERINGATAN: Template pesan dengan ID $template_id tidak ditemukan di database. Notifikasi tidak dikirim.";
        }
    }

    echo json_encode([
        'status' => 'OK',
        'message' => 'Proses selesai',
        'debug' => $debug_log,
        'wa' => $wa_result ?: ['ok'=>false,'log_id'=>null,'number'=>'','error'=>'Tidak ada pesan dikirim']
    ]);
}
?>
