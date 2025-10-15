<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/wa_helpers.php';

@date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
$waktu   = date('H:i');
$hariEng = date('D');

// Ambil jam masuk & pulang default
$jam_masuk = '07:00';
$jam_pulang = '16:00';
$qWaktu = mysqli_query($koneksi, "SELECT TIME_FORMAT(masuk,'%H:%i') jm, TIME_FORMAT(pulang,'%H:%i') jp FROM waktu WHERE hari='".mysqli_real_escape_string($koneksi,$hariEng)."' LIMIT 1");
if ($qWaktu && ($rW = mysqli_fetch_assoc($qWaktu))) {
    if (!empty($rW['jm'])) $jam_masuk  = $rW['jm'];
    if (!empty($rW['jp'])) $jam_pulang = $rW['jp'];
}

// Ambil mode dari DB
$mode = 1;
$q = mysqli_query($koneksi,"SELECT mode FROM status LIMIT 1");
if ($q && ($r = mysqli_fetch_assoc($q))) $mode = (int)$r['mode'];

// Sinkronkan mode berdasar jam
$now = $waktu;
if ($now < $jam_pulang) $mode_baru = 1; else $mode_baru = 2;
if ($mode !== $mode_baru) {
    mysqli_query($koneksi,"UPDATE status SET mode='$mode_baru' LIMIT 1");
    $mode = $mode_baru;
}

// Ambil template pesan fix
$tplMasuk  = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id=1"));
$tplPulang = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM m_pesan WHERE id=2"));

function buildPesan($tpl,$nama,$tanggal,$ket='',$jam=''){
  if (!$tpl) return '';
  $msg  = ($tpl['pesan1']??'');
  $msg .= ' '.($tpl['pesan2']??'');
  $msg .= ' *'.$nama.'* ';
  $tanggalText = wa_format_tanggal_indonesia($tanggal, true);
  if ($jam !== '') {
    $tanggalText .= ' pukul '.$jam;
  }
  $msg .= ($tpl['pesan3']??'').' '.$tanggalText.' ';
  $msg .= ($tpl['pesan4']??'');
  if ($ket!=='') $msg .= "\n".$ket;
  return trim($msg);
}

function table_has_column($koneksi, $table, $column) {
  static $cache = [];
  $key = strtolower($table).'|'.strtolower($column);
  if (array_key_exists($key, $cache)) {
    return $cache[$key];
  }
  $tableSafe = preg_replace('/[^A-Za-z0-9_]/', '', (string)$table);
  if ($tableSafe === '') {
    $cache[$key] = false;
    return false;
  }
  $columnEsc = mysqli_real_escape_string($koneksi, (string)$column);
  $sql = "SHOW COLUMNS FROM `{$tableSafe}` LIKE '{$columnEsc}'";
  $res = mysqli_query($koneksi, $sql);
  if ($res) {
    $exists = mysqli_num_rows($res) > 0;
    mysqli_free_result($res);
  } else {
    $exists = false;
  }
  $cache[$key] = $exists;
  return $exists;
}

function resolve_card_holder($koneksi, $nokartuEsc) {
  $result = null;
  if ($nokartuEsc === '') {
    return null;
  }

  $qreg = mysqli_query($koneksi, "SELECT id, nokartu, idsiswa, idpeg, level, nama FROM datareg WHERE nokartu='$nokartuEsc' LIMIT 1");
  if ($qreg && ($row = mysqli_fetch_assoc($qreg))) {
    $row['source'] = 'datareg';
    return $row;
  }

  $qsiswa = mysqli_query($koneksi, "SELECT id_siswa, nama, nowa FROM siswa WHERE nokartu='$nokartuEsc' LIMIT 1");
  if ($qsiswa && ($siswa = mysqli_fetch_assoc($qsiswa))) {
    return [
      'source' => 'siswa',
      'level' => 'siswa',
      'idsiswa' => (int)($siswa['id_siswa'] ?? 0),
      'idpeg' => 0,
      'nama' => $siswa['nama'] ?? '',
      'nowa' => $siswa['nowa'] ?? ''
    ];
  }

  if (table_has_column($koneksi, 'users', 'nokartu')) {
    $usersHasNowa = table_has_column($koneksi, 'users', 'nowa');
    $usersHasLevel = table_has_column($koneksi, 'users', 'level');
    $fields = 'id_user, nama';
    if ($usersHasNowa) { $fields .= ', nowa'; }
    if ($usersHasLevel) { $fields .= ', level'; }
    $qpeg = mysqli_query($koneksi, "SELECT $fields FROM users WHERE nokartu='$nokartuEsc' LIMIT 1");
    if ($qpeg && ($peg = mysqli_fetch_assoc($qpeg))) {
      return [
        'source' => 'users',
        'level' => 'pegawai',
        'idsiswa' => 0,
        'idpeg' => (int)($peg['id_user'] ?? 0),
        'nama' => $peg['nama'] ?? '',
        'nowa' => ($usersHasNowa ? ($peg['nowa'] ?? '') : '')
      ];
    }
  }

  return null;
}

function kirimDanLogWA($koneksi,$api,$pesan,$no,$row){
  if ($api==''||$pesan==''||$no=='') return;
  $orig = trim((string)$no);
  $nomor = wa_normalize_number($orig,'62');
  // Kirim sekali saja agar tidak terjadi duplikasi ketika gateway sudah menerima request pertama
  list($ok,$code,$err) = wa_send_with_retry_simple($api, $pesan, $nomor, 3);
  $row['tanggal'] = date('Y-m-d');
  $row['number']  = $nomor;
  $row['message'] = $pesan;
  $row['success'] = $ok?1:0;
  $row['http_code'] = (int)$code;
  $row['error'] = $err;
  wa_log_absen($koneksi,$row);
}

// API WA
$api='';
$qa=mysqli_query($koneksi,"SELECT url_api FROM aplikasi LIMIT 1");
if($qa&&($ra=mysqli_fetch_assoc($qa))) $api=$ra['url_api'];

if (!isset($_POST['kode_qr'])){echo "Tidak ada data QR.";exit;}
$nokartu=mysqli_real_escape_string($koneksi,$_POST['kode_qr']);
if ($nokartu===''){echo "QR kosong.";exit;}

$holder = resolve_card_holder($koneksi, $nokartu);
if (!$holder) {
  echo "QR tidak valid.";
  exit;
}

$level = strtolower($holder['level'] ?? '');
$level = ($level === 'siswa') ? 'siswa' : 'pegawai';
$nama  = $holder['nama'] ?? '';
$nowa  = $holder['nowa'] ?? '';
$source = $holder['source'] ?? 'datareg';

if ($level === 'siswa') {
  $id_user = (int)($holder['idsiswa'] ?? 0);
  if ($id_user <= 0) {
    echo "QR tidak valid.";
    exit;
  }
  $idcol = 'idsiswa';
  if ($source !== 'siswa') {
    $s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama,nowa FROM siswa WHERE id_siswa='$id_user'"));
    if ($s) {
      $nama = $s['nama'];
      $nowa = $s['nowa'];
    }
  }
} else {
  $id_user = (int)($holder['idpeg'] ?? 0);
  if ($id_user <= 0) {
    echo "QR tidak valid.";
    exit;
  }
  $idcol = 'idpeg';
  $usersHasNowa = table_has_column($koneksi, 'users', 'nowa');
  $fields = $usersHasNowa ? 'nama,nowa' : 'nama';
  if ($source !== 'users') {
    $u = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT $fields FROM users WHERE id_user='$id_user'"));
    if ($u) {
      $nama = $u['nama'];
      if ($usersHasNowa && isset($u['nowa'])) {
        $nowa = $u['nowa'];
      }
    }
  }
  if (!$usersHasNowa) {
    $nowa = '';
  }
}

$qabs=mysqli_query($koneksi,"SELECT * FROM absensi WHERE tanggal='$tanggal' AND nokartu='$nokartu' LIMIT 1");
$abs=$qabs?mysqli_fetch_assoc($qabs):null;
$masuk_sdh=$abs&&!empty($abs['masuk']);
$pulang_sdh=$abs&&!empty($abs['pulang']);

if($mode==1){
  if($masuk_sdh){echo "Sudah absen masuk.";}else{
    $sql="INSERT INTO absensi(tanggal,nokartu,$idcol,level,masuk,bulan,tahun,ket,mesin) VALUES('$tanggal','$nokartu','$id_user','$level','$waktu',LPAD(MONTH(NOW()),2,'0'),YEAR(NOW()),'H','QR')";
    if(mysqli_query($koneksi,$sql)){
      $absensi_id = mysqli_insert_id($koneksi);
      echo "Absensi masuk berhasil: $nama $waktu.";
      if($level==='siswa'&&!empty($nowa)){
        $msg=buildPesan($tplMasuk,$nama,$tanggal,'*Keterangan:* Tepat Waktu',$waktu);
        kirimDanLogWA($koneksi,$api,$msg,$nowa,[
          'jenis'=>'masuk','level'=>'siswa','idsiswa'=>$id_user,'idpeg'=>0,'nama'=>$nama,'absensi_id'=>$absensi_id
        ]);
      }
    }else echo "Gagal simpan masuk.";
  }
}elseif($mode==2){
  if($pulang_sdh){echo "Sudah absen pulang.";}elseif($masuk_sdh){
    if(mysqli_query($koneksi,"UPDATE absensi SET pulang='$waktu' WHERE tanggal='$tanggal' AND nokartu='$nokartu'")){
      echo "Absensi pulang berhasil: $nama $waktu.";
      if($level==='siswa'&&!empty($nowa)){
        $msg=buildPesan($tplPulang,$nama,$tanggal,'',$waktu);
        kirimDanLogWA($koneksi,$api,$msg,$nowa,[
          'jenis'=>'pulang','level'=>'siswa','idsiswa'=>$id_user,'idpeg'=>0,'nama'=>$nama,'absensi_id'=> (int)($abs['id'] ?? 0)
        ]);
      }
    }else echo "Gagal update pulang.";
  }else{
    $sql="INSERT INTO absensi(tanggal,nokartu,$idcol,level,pulang,bulan,tahun,ket,mesin) VALUES('$tanggal','$nokartu','$id_user','$level','$waktu',LPAD(MONTH(NOW()),2,'0'),YEAR(NOW()),'H','QR')";
    if(mysqli_query($koneksi,$sql)){
      $absensi_id = mysqli_insert_id($koneksi);
      echo "Absensi pulang berhasil tanpa masuk: $nama $waktu.";
      if($level==='siswa'&&!empty($nowa)){
        $msg=buildPesan($tplPulang,$nama,$tanggal,'',$waktu);
        kirimDanLogWA($koneksi,$api,$msg,$nowa,[
          'jenis'=>'pulang','level'=>'siswa','idsiswa'=>$id_user,'idpeg'=>0,'nama'=>$nama,'absensi_id'=>$absensi_id
        ]);
      }
    }else echo "Gagal simpan pulang.";
  }
}else{
  echo "Mode tidak valid.";
}
