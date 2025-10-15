<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

function ensure_column_guru(mysqli $db, string $t){ @mysqli_query($db, "ALTER TABLE `$t` ADD COLUMN IF NOT EXISTS `guru` VARCHAR(11) NULL DEFAULT NULL"); }
// Hanya pastikan kolom guru pada jawaban_tugas, tidak pada tabel nilai_*
ensure_column_guru($koneksi,'jawaban_tugas');

function id_guru_autofill($post_id_guru){
  $g = 0;
  if (isset($post_id_guru) && is_numeric($post_id_guru) && (int)$post_id_guru > 0) { $g = (int)$post_id_guru; }
  else if (isset($_SESSION['id_guru']) && (int)$_SESSION['id_guru'] > 0) { $g = (int)$_SESSION['id_guru']; }
  else if (isset($_SESSION['id_user']) && (int)$_SESSION['id_user'] > 0) { $g = (int)$_SESSION['id_user']; }
  return $g;
}

if (isset($_POST['id_jawaban'])) {
  cek_session_guru();
  $id_jawaban = (int)($_POST['id_jawaban'] ?? 0);
  $nilai_in   = isset($_POST['nilai']) ? (string)$_POST['nilai'] : '';
  $nilai      = mysqli_real_escape_string($koneksi, $nilai_in);
  $catatan    = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, (string)$_POST['catatan']) : '';
  $id_guru    = id_guru_autofill($_POST['id_guru'] ?? 0);

  if ($id_jawaban <= 0 || $nilai === '') { ob_clean(); echo json_encode(['status'=>'error','message'=>'Data tidak lengkap (id_jawaban/nilai).']); exit; }

  $q_upd = "UPDATE jawaban_tugas SET nilai='$nilai', catatan_guru='$catatan', status=1, guru=".($id_guru>0?$id_guru:'NULL').", tgl_update=NOW() WHERE id_jawaban=$id_jawaban";
  $ok = mysqli_query($koneksi,$q_upd);
  if (!$ok) { ob_clean(); echo json_encode(['status'=>'error','message'=>'Gagal update jawaban_tugas: '.mysqli_error($koneksi)]); exit; }

  $jt      = fetch($koneksi, 'jawaban_tugas', ['id_jawaban' => $id_jawaban]);
  $setting = fetch($koneksi, 'aplikasi', ['id_aplikasi' => 1]);
  if (!$jt) { ob_clean(); echo json_encode(['status'=>'error','message'=>'Data jawaban_tugas tidak ditemukan']); exit; }
  $sis = fetch($koneksi, 'siswa', ['id_siswa' => (int)$jt['id_siswa']]);
  $tg  = fetch($koneksi, 'tugas', ['id_tugas' => (int)$jt['id_tugas']]);
  if (!$sis || !$tg) { ob_clean(); echo json_encode(['status'=>'error','message'=>'Data siswa/tugas tidak ditemukan']); exit; }
  if ($id_guru<=0 && isset($tg['id_guru'])) { $id_guru = (int)$tg['id_guru']; }

  // Tidak ada sinkronisasi ke nilai_harian atau nilai_sts

  $setting = $setting ?: fetch($koneksi,'aplikasi',['id_aplikasi'=>1]);
  if ($setting && !empty($setting['url_api']) && !empty($sis['nowa'])) {
    $pesan = "INFORMASI NILAI TUGAS - ".$setting['sekolah']."\n\n".
             "Nama Siswa: ".$sis['nama']."\n".
             "Mata Pelajaran: ".($tg['mapel']??'')."\n".
             "Judul Tugas: ".($tg['judul']??'')."\n".
             "Nilai: *".$nilai."*\n\n";
    if (!empty($catatan)) { $pesan .= "Catatan Guru:\n_".$catatan."_\n\n"; }
    $pesan .= "Terima kasih atas perhatian Bapak/Ibu. Pesan ini dikirim otomatis oleh sistem.";
    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $setting['url_api'].'/send-message',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => ['message' => $pesan, 'number' => $sis['nowa']]
    ]);
    curl_exec($curl); curl_close($curl);
  }

  ob_clean(); echo json_encode(['status'=>'success','message'=>'Tersimpan']); exit;
}

// Mode batch di-nonaktifkan: endpoint ini hanya menyimpan ke jawaban_tugas
ob_clean();
echo json_encode(['status'=>'error','message'=>'Batch nilai dinonaktifkan. Gunakan penilaian per jawaban_tugas.']);
?>

