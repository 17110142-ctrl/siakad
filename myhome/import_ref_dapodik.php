<?php
require_once "../config/koneksi.php";
require_once "../vendor/autoload.php";

// Hanya admin/bendahara
$level = $_SESSION['level'] ?? '';
$tugas = $_SESSION['tugas'] ?? ($_SESSION['user']['tugas'] ?? '');
if (!in_array($level, ['admin']) && $tugas !== 'bendahara') {
    http_response_code(403);
    echo "Akses ditolak.";
    exit;
}

// Buat tabel ref_dapodik jika belum ada
$createSql = "CREATE TABLE IF NOT EXISTS ref_dapodik (
    nisn VARCHAR(10) NOT NULL PRIMARY KEY,
    nis VARCHAR(32) NULL,
    nik VARCHAR(32) NULL,
    no_kk VARCHAR(32) NULL,
    nama VARCHAR(150) NOT NULL,
    tempat_lahir VARCHAR(100) NULL,
    tgl_lahir DATE NULL,
    jk VARCHAR(10) NULL,
    agama VARCHAR(50) NULL,
    email VARCHAR(100) NULL,
    rt VARCHAR(10) NULL,
    rw VARCHAR(10) NULL,
    kelurahan VARCHAR(100) NULL,
    kecamatan VARCHAR(100) NULL,
    provinsi VARCHAR(100) NULL,
    kode_pos VARCHAR(10) NULL,
    alamat VARCHAR(255) NULL,
    sumber VARCHAR(50) NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($koneksi, $createSql);

$msg = '';
$stat = null;
$summary = ['inserted'=>0,'updated'=>0,'skipped'=>0];

function norm_header2($s){ $s = strtolower(trim($s)); $s = preg_replace('/\s+/', ' ', $s); return $s; }
function parse_date_generic2($v){ if($v===null||$v==='')return null; if(is_numeric($v)&&$v>1000){ try{ $ts=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($v); return date('Y-m-d',$ts);}catch(\Throwable $e){}} $ts=strtotime((string)$v); return $ts?date('Y-m-d',$ts):null; }

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['file'])){
    $f=$_FILES['file'];
    if($f['error']!==UPLOAD_ERR_OK){ $msg='Upload gagal ('.$f['error'].').'; $stat='danger'; }
    else{
        $ext=strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        try{
            if($ext==='csv'){ $reader=new \PhpOffice\PhpSpreadsheet\Reader\Csv(); $reader->setDelimiter(','); $reader->setEnclosure('"'); }
            elseif($ext==='xls'){ $reader=new \PhpOffice\PhpSpreadsheet\Reader\Xls(); }
            elseif($ext==='xlsx'){ $reader=new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); }
            else{ throw new Exception('Format file tidak didukung. Gunakan CSV/XLS/XLSX.'); }
            $sheet=$reader->load($f['tmp_name'])->getActiveSheet();
            $rows=$sheet->toArray(null,true,true,true);
            if(!$rows||count($rows)===0) throw new Exception('File kosong.');

            // deteksi header baris yang mengandung NISN dan NAMA
            $headerIdx=null; foreach($rows as $i=>$cols){ $joined=strtoupper(implode(' ', array_values($cols))); if(strpos($joined,'NISN')!==false && strpos($joined,'NAMA')!==false){ $headerIdx=$i; break; } }
            if($headerIdx===null) $headerIdx=array_key_first($rows);
            $headers=$rows[$headerIdx];
            $map=[]; foreach($headers as $k=>$h){ $h0=norm_header2((string)$h);
                if(strpos($h0,'nisn')!==false) $map['nisn']=$k;
                if($h0==='nis'||strpos($h0,'nis ')!==false) $map['nis']=$k;
                if($h0==='nik'||strpos($h0,'nik')!==false) $map['nik']=$k;
                if(strpos($h0,'kk')!==false) $map['no_kk']=$k;
                if($h0==='nama'||strpos($h0,'nama')!==false) $map['nama']=$k;
                if(strpos($h0,'tempat')!==false && strpos($h0,'lahir')!==false) $map['tempat_lahir']=$k;
                if((strpos($h0,'tanggal')!==false||strpos($h0,'tgl')!==false)&&strpos($h0,'lahir')!==false) $map['tgl_lahir']=$k;
                if($h0==='jk'||strpos($h0,'jenis kelamin')!==false) $map['jk']=$k;
                if(strpos($h0,'agama')!==false) $map['agama']=$k;
                if(strpos($h0,'email')!==false) $map['email']=$k;
                if($h0==='rt') $map['rt']=$k; if($h0==='rw') $map['rw']=$k;
                if(strpos($h0,'kel')!==false) $map['kelurahan']=$k;
                if(strpos($h0,'kec')!==false && strpos($h0,'kecamatan')!==false) $map['kecamatan']=$k; elseif($h0==='kecamatan') $map['kecamatan']=$k;
                if(strpos($h0,'prov')!==false) $map['provinsi']=$k;
                if(strpos($h0,'kode')!==false && strpos($h0,'pos')!==false) $map['kode_pos']=$k;
                if(strpos($h0,'alamat')!==false) $map['alamat']=$k;
            }
            if(!isset($map['nisn']) || !isset($map['nama'])) throw new Exception('Header minimal harus memuat kolom NISN dan NAMA.');

            foreach($rows as $idx=>$cols){ if($idx<=$headerIdx) continue; 
                $get=function($key) use($map,$cols){ if(!isset($map[$key])) return ''; $k=$map[$key]; return isset($cols[$k])?trim((string)$cols[$k]):''; };
                $nisn=preg_replace('/\D+/','',$get('nisn')); if($nisn===''){ $summary['skipped']++; continue; }
                $nis = $get('nis');
                $nik = $get('nik');
                $no_kk = $get('no_kk');
                $nama=$get('nama');
                $tempat=$get('tempat_lahir');
                $tgl=parse_date_generic2($get('tgl_lahir'));
                $jk=$get('jk');
                $agama=$get('agama');
                $email=$get('email');
                $rt=$get('rt'); $rw=$get('rw');
                $kel=$get('kelurahan'); $kec=$get('kecamatan'); $prov=$get('provinsi');
                $kode=$get('kode_pos'); $alamat=$get('alamat');

                $nesc=function($v) use($koneksi){ return "'".mysqli_real_escape_string($koneksi,(string)$v)."'"; };
                $nescn=function($v) use($koneksi){ return $v!==null?"'".mysqli_real_escape_string($koneksi,(string)$v)."'":"NULL"; };
                $sql="INSERT INTO ref_dapodik (nisn, nis, nik, no_kk, nama, tempat_lahir, tgl_lahir, jk, agama, email, rt, rw, kelurahan, kecamatan, provinsi, kode_pos, alamat, sumber)
                      VALUES (".$nesc($nisn).", ".($nis!==''?$nesc($nis):'NULL').", ".($nik!==''?$nesc($nik):'NULL').", ".($no_kk!==''?$nesc($no_kk):'NULL').", ".$nesc($nama).", ".($tempat!==''?$nesc($tempat):'NULL').", ".($tgl?$nesc($tgl):'NULL').", ".($jk!==''?$nesc($jk):'NULL').", ".($agama!==''?$nesc($agama):'NULL').", ".($email!==''?$nesc($email):'NULL').", ".($rt!==''?$nesc($rt):'NULL').", ".($rw!==''?$nesc($rw):'NULL').", ".($kel!==''?$nesc($kel):'NULL').", ".($kec!==''?$nesc($kec):'NULL').", ".($prov!==''?$nesc($prov):'NULL').", ".($kode!==''?$nesc($kode):'NULL').", ".($alamat!==''?$nesc($alamat):'NULL').", 'import')
                      ON DUPLICATE KEY UPDATE nis=VALUES(nis), nik=VALUES(nik), no_kk=VALUES(no_kk), nama=VALUES(nama), tempat_lahir=VALUES(tempat_lahir), tgl_lahir=VALUES(tgl_lahir), jk=VALUES(jk), agama=VALUES(agama), email=VALUES(email), rt=VALUES(rt), rw=VALUES(rw), kelurahan=VALUES(kelurahan), kecamatan=VALUES(kecamatan), provinsi=VALUES(provinsi), kode_pos=VALUES(kode_pos), alamat=VALUES(alamat), sumber='import'";
                $ok=mysqli_query($koneksi,$sql);
                if($ok){ if(mysqli_affected_rows($koneksi)===1) $summary['inserted']++; else $summary['updated']++; }
                else { $summary['skipped']++; }
            }
            $msg='Import selesai. Insert: '.$summary['inserted'].', Update: '.$summary['updated'].', Skip: '.$summary['skipped'];
            $stat='success';
        }catch(Exception $e){ $msg='Gagal mengimpor: '.$e->getMessage(); $stat='danger'; }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Referensi Dapodik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Import Referensi Dapodik</h5>
        <a href="tampilprofilsiswa.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if($msg): ?><div class="alert alert-<?= htmlspecialchars($stat?:'info') ?>"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <p class="text-muted">Unggah file CSV/XLS/XLSX dari Dapodik (atau ekspor resmi) yang memuat kolom: NISN, NIK, No KK, Nama, Tempat Lahir, Tanggal Lahir, JK, Agama, Email, RT, RW, Kelurahan, Kecamatan, Provinsi, Kode Pos, Alamat. Kolom yang tidak ada akan diabaikan.</p>
            <form method="post" enctype="multipart/form-data" class="row g-2 align-items-end">
                <div class="col-12 col-md-6">
                    <label class="form-label">File Referensi</label>
                    <input type="file" name="file" class="form-control" accept=".csv,.xls,.xlsx" required>
                </div>
                <div class="col-12 col-md-auto">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

