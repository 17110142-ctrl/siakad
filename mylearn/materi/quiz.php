<?php
if (session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/koneksi.php';
header('X-Content-Type-Options: nosniff');
$mode = $_REQUEST['mode'] ?? '';

function ensure_tables(mysqli $db){
  $db->query("CREATE TABLE IF NOT EXISTS `quiz` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `id_materi` INT NOT NULL,
    `nomor` INT NOT NULL,
    `jenis` VARCHAR(32) NOT NULL,
    `pertanyaan` LONGTEXT NULL,
    `media` LONGTEXT NULL,
    `opsi` LONGTEXT NULL,
    `kunci` LONGTEXT NULL,
    `skor_max` DECIMAL(6,2) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_quiz_materi` (`id_materi`),
    KEY `idx_quiz_nomor` (`nomor`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

  $db->query("CREATE TABLE IF NOT EXISTS `jawaban_quiz` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `id_materi` INT NOT NULL,
    `id_quiz` INT NOT NULL,
    `id_siswa` INT NOT NULL,
    `jawaban` LONGTEXT NULL,
    `skor` DECIMAL(6,2) DEFAULT NULL,
    `waktu_submit` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `attempt` INT NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_jawaban_once` (`id_materi`,`id_quiz`,`id_siswa`,`attempt`),
    KEY `idx_jawaban_siswa` (`id_siswa`),
    KEY `idx_jawaban_materi` (`id_materi`),
    CONSTRAINT `fk_jawaban_quiz_quiz` FOREIGN KEY (`id_quiz`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function media_root(){
  $root = realpath(__DIR__ . '/../../images');
  if ($root===false) return false;
  $dir = $root . DIRECTORY_SEPARATOR . 'quiz_media' . DIRECTORY_SEPARATOR;
  if (!is_dir($dir)) @mkdir($dir,0775,true);
  return $dir;
}

function delete_if_unreferenced(mysqli $db, int $id_materi, int $exclude_id, string $url){
  // url bentuknya ../../images/quiz_media/xxx.ext atau /images/quiz_media/xxx.ext
  $fname = basename(parse_url($url, PHP_URL_PATH));
  if(!$fname) return;
  $res = $db->query("SELECT COUNT(*) c FROM quiz WHERE id_materi=".(int)$id_materi." AND id<>".(int)$exclude_id." AND (
      (media LIKE '%".$db->real_escape_string($fname)."%') OR (opsi LIKE '%".$db->real_escape_string($fname)."%')
    )");
  $need = 0; if($res){ $row=$res->fetch_assoc(); $need=(int)$row['c']; $res->close(); }
  if($need>0) return;
  $dir = media_root(); if(!$dir) return;
  $path = $dir.$fname;
  if (is_file($path)) @unlink($path);
}

function validate_question(array $q, int $index){
  $no = 'Soal #'.($index+1);
  $type = $q['type'] ?? '';
  $text = trim($q['text'] ?? '');
  $score = (float)($q['score'] ?? 0);
  if ($text==='') return "$no: pertanyaan wajib diisi";
  if (!($score>0)) return "$no: skor harus > 0";
  if ($type==='pg'){
    $opts = $q['options'] ?? [];
    if (!is_array($opts) || count($opts)<2) return "$no: minimal 2 opsi";
    foreach($opts as $o){ if(trim($o['text']??'')==='') return "$no: semua opsi harus diisi"; }
    $key = $q['key'] ?? null;
    if (!is_numeric($key) || $key<0 || $key>=count($opts)) return "$no: kunci tidak valid";
  } elseif ($type==='pgc'){
    $opts = $q['options'] ?? [];
    if (!is_array($opts) || count($opts)<2) return "$no: minimal 2 opsi";
    foreach($opts as $o){ if(trim($o['text']??'')==='') return "$no: semua opsi harus diisi"; }
    $keys = $q['keys'] ?? [];
    if (!is_array($keys) || count($keys)<1) return "$no: kunci minimal 1 opsi";
  } elseif ($type==='menjodohkan'){
    $pairs = $q['pairs'] ?? [];
    if (!is_array($pairs) || count($pairs)<1) return "$no: minimal 1 pasangan";
    foreach($pairs as $p){ if(trim($p['left']??'')==='' || trim($p['right']??'')==='') return "$no: isi kiri dan kanan"; }
  } elseif ($type==='benar_salah'){
    $key = $q['key'] ?? $q['key_bool'] ?? '';
    if ($key!=='benar' && $key!=='salah') return "$no: kunci tidak valid";
  } elseif ($type==='isian_singkat' || $type==='uraian'){
  } else {
    return "$no: jenis soal tidak dikenal";
  }
  return null;
}

ensure_tables($koneksi);

if ($mode==='upload' && $_SERVER['REQUEST_METHOD']==='POST'){
  header('Content-Type: application/json');
  $dir = media_root();
  if (!$dir){ echo json_encode(['status'=>'error','message'=>'dir']); exit; }
  $f = $_FILES['file'] ?? null;
  if (!$f || ($f['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK){ echo json_encode(['status'=>'error','message'=>'nofile']); exit; }
  $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
  $allow = ['jpg','jpeg','png','gif','webp'];
  if (!in_array($ext,$allow)){ echo json_encode(['status'=>'error','message'=>'ext']); exit; }
  try { $rand = bin2hex(random_bytes(4)); } catch(Throwable $e) { $rand = uniqid(); }
  $name = date('YmdHis').'_'.$rand.'.'.$ext;
  $path = $dir.$name;
  if (!move_uploaded_file($f['tmp_name'],$path)){ echo json_encode(['status'=>'error','message'=>'save']); exit; }
  $url  = '../../images/quiz_media/'.$name;
  echo json_encode(['status'=>'ok','url'=>$url]); exit;
}

if ($mode==='save' && $_SERVER['REQUEST_METHOD']==='POST'){
  header('Content-Type: application/json');
  $id_materi = intval($_POST['id_materi'] ?? 0);
  $questions = json_decode($_POST['questions'] ?? '[]', true);
  if ($id_materi<=0 || !is_array($questions)){ echo json_encode(['status'=>'error','message'=>'invalid']); exit; }

  foreach($questions as $i=>$q){
    $err = validate_question($q,$i);
    if($err){ echo json_encode(['status'=>'error','message'=>$err]); exit; }
  }

  $res = $koneksi->query("SELECT COALESCE(MAX(nomor),0) mx FROM quiz WHERE id_materi=".(int)$id_materi);
  $base = 0; if ($res){ $r=$res->fetch_assoc(); $base=(int)$r['mx']; $res->close(); }

  $sql = "INSERT INTO quiz (id_materi,nomor,jenis,pertanyaan,media,opsi,kunci,skor_max) VALUES (?,?,?,?,?,?,?,?)";
  mysqli_begin_transaction($koneksi);
  try{
    $stmt = $koneksi->prepare($sql);
    if(!$stmt) throw new Exception($koneksi->error);
    foreach($questions as $i=>$q){
      $nomor = $base + $i + 1;
      $jenis = substr((string)($q['type'] ?? ''),0,32);
      $pertanyaan = $q['text'] ?? '';
      $media = json_encode($q['media'] ?? new stdClass());
      if ($jenis==='pg' || $jenis==='pgc'){
        $opsi = json_encode($q['options'] ?? []);
      } elseif ($jenis==='menjodohkan'){
        $opsi = json_encode($q['pairs'] ?? []);
      } else {
        $opsi = null;
      }
      if (array_key_exists('key',$q)) { $kunci_val = $q['key']; }
      elseif (array_key_exists('keys',$q)) { $kunci_val = $q['keys']; }
      elseif (array_key_exists('key_bool',$q)) { $kunci_val = $q['key_bool']; }
      else { $kunci_val = null; }
      $kunci = $kunci_val===null ? null : json_encode($kunci_val);
      $skor  = (float)($q['score'] ?? 1);

      $stmt->bind_param('iisssssd', $id_materi, $nomor, $jenis, $pertanyaan, $media, $opsi, $kunci, $skor);
      if(!$stmt->execute()) throw new Exception($stmt->error);
    }
    $stmt->close();
    mysqli_commit($koneksi);
    echo json_encode(['status'=>'ok']);
  } catch(Throwable $e){
    mysqli_rollback($koneksi);
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
  }
  exit;
}

if ($mode==='list_json' && isset($_GET['id_materi'])){
  header('Content-Type: application/json');
  $id_materi = intval($_GET['id_materi']);
  $q = $koneksi->query("SELECT id,id_materi,nomor,jenis,pertanyaan,media,opsi,kunci,skor_max FROM quiz WHERE id_materi=$id_materi ORDER BY nomor ASC,id ASC");
  $out=[];
  if($q){
    while($r=$q->fetch_assoc()){
      $r['media'] = $r['media'] ? json_decode($r['media'],true) : null;
      $r['opsi']  = $r['opsi']  ? json_decode($r['opsi'],true) : null;
      $r['kunci'] = $r['kunci'] ? json_decode($r['kunci'],true) : null;
      $out[] = $r;
    }
    $q->close();
  }
  echo json_encode(['status'=>'ok','data'=>$out]); exit;
}

if ($mode==='delete' && $_SERVER['REQUEST_METHOD']==='POST'){
  header('Content-Type: application/json');
  $id = intval($_POST['id'] ?? 0);
  if ($id<=0){ echo json_encode(['status'=>'error','message'=>'invalid id']); exit; }
  $q = $koneksi->query("SELECT id_materi, media, opsi FROM quiz WHERE id=$id");
  if(!$q || !$q->num_rows){ echo json_encode(['status'=>'error','message'=>'not found']); exit; }
  $row = $q->fetch_assoc(); $q->close();
  $id_materi = (int)$row['id_materi'];

  $urls = [];
  $m = $row['media'] ? json_decode($row['media'], true) : null;
  if (is_array($m) && !empty($m['url'])) $urls[] = $m['url'];
  $ops = $row['opsi'] ? json_decode($row['opsi'], true) : null;
  if (is_array($ops)) foreach($ops as $o){ if (is_array($o) && !empty($o['img'])) $urls[] = $o['img']; }

  $ok = $koneksi->query("DELETE FROM quiz WHERE id=$id");
  if($ok){
    foreach(array_unique($urls) as $u){ delete_if_unreferenced($koneksi,$id_materi,$id,$u); }
    echo json_encode(['status'=>'ok']);
  } else {
    echo json_encode(['status'=>'error','message'=>$koneksi->error]);
  }
  exit;
}

if ($mode==='update' && $_SERVER['REQUEST_METHOD']==='POST'){
  header('Content-Type: application/json');
  $payload = json_decode($_POST['data'] ?? '{}', true);
  $id = intval($payload['id'] ?? 0);
  if ($id<=0){ echo json_encode(['status'=>'error','message'=>'invalid id']); exit; }

  $q = $koneksi->query("SELECT id_materi, media, opsi, jenis FROM quiz WHERE id=$id");
  if(!$q || !$q->num_rows){ echo json_encode(['status'=>'error','message'=>'not found']); exit; }
  $old = $q->fetch_assoc(); $q->close();
  $id_materi = (int)$old['id_materi'];
  $jenis = $old['jenis'];

  $tmp = ['type'=>$jenis,'text'=>$payload['text'] ?? '','score'=>$payload['skor'] ?? 0];
  if ($jenis==='pg' || $jenis==='pgc') $tmp['options'] = $payload['options'] ?? [];
  if ($jenis==='pg') $tmp['key'] = $payload['key'] ?? null;
  if ($jenis==='pgc') $tmp['keys'] = $payload['keys'] ?? [];
  if ($jenis==='menjodohkan') $tmp['pairs'] = $payload['pairs'] ?? [];
  if ($jenis==='benar_salah') $tmp['key_bool'] = $payload['key_bool'] ?? '';

  $err = validate_question($tmp,0);
  if($err){ echo json_encode(['status'=>'error','message'=>$err]); exit; }

  // siapkan field
  $pertanyaan = $payload['text'] ?? '';
  $media = json_encode($payload['media'] ?? new stdClass());
  if ($jenis==='pg' || $jenis==='pgc'){
    $opsi = json_encode($payload['options'] ?? []);
  } elseif ($jenis==='menjodohkan'){
    $opsi = json_encode($payload['pairs'] ?? []);
  } else {
    $opsi = null;
  }
  if ($jenis==='pg')      { $kunci_val = $payload['key'] ?? null; }
  elseif ($jenis==='pgc') { $kunci_val = $payload['keys'] ?? []; }
  elseif ($jenis==='benar_salah') { $kunci_val = $payload['key_bool'] ?? ''; }
  else { $kunci_val = null; }
  $kunci = $kunci_val===null ? null : json_encode($kunci_val);
  $skor  = (float)($payload['skor'] ?? 1);

  $stmt = $koneksi->prepare("UPDATE quiz SET pertanyaan=?, media=?, opsi=?, kunci=?, skor_max=?, updated_at=NOW() WHERE id=?");
  if(!$stmt){ echo json_encode(['status'=>'error','message'=>$koneksi->error]); exit; }
  $stmt->bind_param('ssssdi', $pertanyaan, $media, $opsi, $kunci, $skor, $id);
  $ok = $stmt->execute();
  $stmt->close();

  if($ok){
    // Bersihkan file lama yang tidak lagi direferensikan
    $old_urls = [];
    $om = $old['media'] ? json_decode($old['media'], true) : null;
    if (is_array($om) && !empty($om['url'])) $old_urls[] = $om['url'];
    $oo = $old['opsi'] ? json_decode($old['opsi'], true) : null;
    if (is_array($oo)) foreach($oo as $o){ if (is_array($o) && !empty($o['img'])) $old_urls[] = $o['img']; }

    $new_urls = [];
    $nm = $payload['media'] ?? null;
    if (is_array($nm) && !empty($nm['url'])) $new_urls[] = $nm['url'];
    if ($jenis==='pg' || $jenis==='pgc'){
      $no = $payload['options'] ?? [];
      if (is_array($no)) foreach($no as $o){ if (is_array($o) && !empty($o['img'])) $new_urls[] = $o['img']; }
    }

    foreach(array_diff(array_unique($old_urls), array_unique($new_urls)) as $u){
      if($u) delete_if_unreferenced($koneksi,$id_materi,$id,$u);
    }

    echo json_encode(['status'=>'ok']);
  } else {
    echo json_encode(['status'=>'error','message'=>'update failed']);
  }
  exit;
}

http_response_code(400);
header('Content-Type: application/json');
echo json_encode(['status'=>'error','message'=>'bad request']);
