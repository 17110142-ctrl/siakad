<?php
// logabsen.php — header persis seperti snippet user + filter log sesuai mode
// PHP 7 compatible, tanpa debug output

require_once __DIR__ . '/../config/koneksi.php';
@include_once __DIR__ . '/../config/function.php';
@include_once __DIR__ . '/../config/crud.php';

@date_default_timezone_set('Asia/Jakarta');
$tanggal        = date('Y-m-d');
$waktu_sekarang = date('H:i');
$hariEng        = date('D');

// Ambil jam masuk/pulang per hari
$masuk  = '07:00';
$pulang = '16:00';
$qw = mysqli_query($koneksi, "SELECT TIME_FORMAT(masuk,'%H:%i') jm, TIME_FORMAT(pulang,'%H:%i') jp FROM waktu WHERE hari='" . mysqli_real_escape_string($koneksi,$hariEng) . "' LIMIT 1");
if ($qw && ($rw = mysqli_fetch_assoc($qw))) {
    if (!empty($rw['jm'])) $masuk  = $rw['jm'];
    if (!empty($rw['jp'])) $pulang = $rw['jp'];
}

// Mode dari tabel status + autosync berdasar jam
$current_mode = 1;
$qs = mysqli_query($koneksi, "SELECT mode FROM status LIMIT 1");
if ($qs && ($rs = mysqli_fetch_assoc($qs)) && isset($rs['mode'])) $current_mode = (int)$rs['mode'];
if ($waktu_sekarang < $masuk)      $mode_baru = 1;
elseif ($waktu_sekarang < $pulang) $mode_baru = 1;
else                                $mode_baru = 2;
if ($current_mode !== $mode_baru) {
    mysqli_query($koneksi, "UPDATE status SET mode='".(int)$mode_baru."' LIMIT 1");
    $current_mode = $mode_baru;
}

// Label mode
$mode_text = 'MODE TIDAK DIKENAL';
switch ($current_mode) {
    case 1: $mode_text = 'ABSEN MASUK'; break;
    case 2: $mode_text = 'ABSEN PULANG'; break;
    case 3: $mode_text = 'MASUK ESKUL';  break;
    case 4: $mode_text = 'PULANG ESKUL'; break;
}
?>

<!-- ================================================== -->
<!-- Informasi Debug (hanya terlihat di source code)  -->
<!-- ================================================== -->
<!-- <?= $debug_message ?> -->

<!-- ================================================== -->
<!-- Panel Status untuk Header (Bagian 1 dari output) -->
<!-- ================================================== -->
<span style="font-weight: 500; font-size: 0.9rem;">
    Waktu Sekarang: <b><?= $waktu_sekarang ?></b> | Masuk: <b><?= $masuk ?></b> | Pulang: <b><?= $pulang ?></b>
</span>
<span style="font-size: 1rem; padding: 8px 15px; margin-left: 15px; background-color: rgba(0,0,0,0.3); border-radius: 8px; font-weight: bold; border: 1px solid rgba(255,255,255,0.2);">
    <?= $mode_text ?>
</span>

<--SPLIT-->

<!-- ================================================== -->
<!-- Log Presensi sesuai mode (masuk/pulang saja)        -->
<!-- ================================================== -->
<div class="card" style="background-color:rgba(255,255,255,0.1);border:none;color:white;">
  <div class="card-body">
    <h5 class="card-title text-center" style="font-weight:bold;text-shadow:1px 1px 2px #000;">LOG PRESENSI HARI INI</h5>
    <ul class="list-unstyled">
      <?php
      if ($current_mode == 1) {
          $sqlLog = "SELECT * FROM absensi WHERE tanggal='" . mysqli_real_escape_string($koneksi,$tanggal) . "' AND masuk IS NOT NULL ORDER BY masuk DESC LIMIT 8";
      } else {
          $sqlLog = "SELECT * FROM absensi WHERE tanggal='" . mysqli_real_escape_string($koneksi,$tanggal) . "' AND pulang IS NOT NULL ORDER BY pulang DESC LIMIT 8";
      }
      $qlog = mysqli_query($koneksi, $sqlLog);
      if ($qlog && mysqli_num_rows($qlog) > 0) {
          while ($row = mysqli_fetch_assoc($qlog)) {
              $nama = '';
              $ket  = '';
              if ($row['level'] === 'siswa') {
                  $qsis = mysqli_query($koneksi, "SELECT nama FROM siswa WHERE id_siswa='" . mysqli_real_escape_string($koneksi,$row['idsiswa']) . "' LIMIT 1");
                  $s    = $qsis ? mysqli_fetch_assoc($qsis) : null;
                  $nama = ($s && !empty($s['nama'])) ? $s['nama'] : 'Siswa Tidak Ditemukan';
              } else {
                  $qusr = mysqli_query($koneksi, "SELECT nama FROM users WHERE id_user='" . mysqli_real_escape_string($koneksi,$row['idpeg']) . "' LIMIT 1");
                  $u    = $qusr ? mysqli_fetch_assoc($qusr) : null;
                  $nama = ($u && !empty($u['nama'])) ? $u['nama'] : 'Pegawai Tidak Ditemukan';
              }
              if ($current_mode == 1 && !empty($row['masuk'])) {
                  $jam = date('H:i', strtotime($row['masuk']));
                  $ket = 'Telah melakukan presensi masuk pada jam ' . $jam;
              } elseif ($current_mode == 2 && !empty($row['pulang'])) {
                  $jam = date('H:i', strtotime($row['pulang']));
                  $ket = 'Telah melakukan presensi pulang pada jam ' . $jam;
              }
              if ($ket !== '') {
                  echo '<li style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,.2);animation:fadeIn 1s;">'
                     . '<strong>' . htmlspecialchars($nama) . '</strong> - '
                     . '<small>' . htmlspecialchars($ket) . '</small>'
                     . '</li>';
              }
          }
      } else {
          echo '<li class="text-center">Belum ada data presensi hari ini.</li>';
      }
      ?>
    </ul>
  </div>
</div>

<style>
@keyframes fadeIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
</style>