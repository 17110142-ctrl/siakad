<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'login') {
    $id = $_POST['id'];
	
    $exec = mysqli_query($koneksi,"UPDATE siswa set online='0' where id_siswa='$id'");
  }
if ($pg == 'hapus') {
    $id = $_POST['id'];
	$nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM  nilai WHERE id_nilai='$id'"));
	$idsiswa = $nilai['id_siswa'];
	$idujian = $nilai['id_ujian'];
	 $busek = mysqli_query($koneksi, "DELETE FROM reset WHERE idsiswa='$idsiswa' AND idnilai='$id' AND idujian='$idujian'");
    $exec = delete($koneksi, 'nilai', ['id_nilai' => $id]);
  }
  if ($pg == 'ulang') {
    $id = $_POST['id'];
	
    $exec = delete($koneksi, 'nilai', ['id_nilai' => $id]);
  }
  if ($pg == 'reset') {
    $id = $_POST['id'];
	$nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM  nilai WHERE id_nilai='$id'"));
	$idbank = $nilai['id_bank'];
	$idsiswa = $nilai['id_siswa'];
	$idujian = $nilai['id_ujian'];
	$hapus = mysqli_query($koneksi, "DELETE FROM jawaban WHERE id_siswa='$idsiswa' AND id_bank='$idbank' AND id_ujian='$idujian'");
    $busek = mysqli_query($koneksi, "DELETE FROM reset WHERE idsiswa='$idsiswa' AND idnilai='$id' AND idujian='$idujian'");
    $hapuse = mysqli_query($koneksi, "DELETE FROM jawaban_soal WHERE id_siswa='$idsiswa' AND id_bank='$idbank' AND id_ujian='$idujian'");
	$hapusnya = mysqli_query($koneksi, "DELETE FROM jodoh WHERE id_siswa='$idsiswa' AND id_bank='$idbank' AND id_ujian='$idujian'");
	$exec = delete($koneksi, 'nilai', ['id_nilai' => $id]);
  }
  if ($pg == 'selesai') {
    $id = $_POST['idp'];
	 
	$nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM  nilai WHERE id_nilai='$id'"));
	$idbank = $nilai['id_bank'];
	$idsiswa = $nilai['id_siswa'];
	$idujian = $nilai['id_ujian'];
	$total = round($nilai['skor']+$nilai['skor_esai']+$nilai['skor_multi']+$nilai['skor_bs']+$nilai['skor_urut']);
  $simpan = mysqli_query($koneksi,"UPDATE nilai SET ujian_selesai='$datetime', browser='1', online='0',total='$total' WHERE id_nilai='$id'");
	$hapus = mysqli_query($koneksi, "DELETE FROM jawaban WHERE id_siswa='$idsiswa' AND id_bank='$idbank' AND id_ujian='$idujian'");
	 $busek = mysqli_query($koneksi, "DELETE FROM reset WHERE idsiswa='$idsiswa' AND idnilai='$id' AND idujian='$idujian'");
 
  }
  if ($pg == 'hapus_nilai') {
    
	 $busek = mysqli_query($koneksi, "DELETE FROM nilai WHERE status='1'");
   
  }
   if ($pg == 'hapus_nilai_belum') {
    
	 $busek = mysqli_query($koneksi, "DELETE FROM nilai WHERE status is null");
   
  }
   if ($pg == 'settingawal') {
    
   $busek = mysqli_query($koneksi, "UPDATE aplikasi SET sekolah='MKKS KAB. MALANG',npsn=null,kepsek=null,url_host='https://newpat.mkkskabmalang.com' WHERE id_aplikasi='1'");
   $exec = mysqli_query($koneksi, "truncate banksoal");
   $exec = mysqli_query($koneksi, "truncate soal");
   $exec = mysqli_query($koneksi, "truncate siswa");
   $exec = mysqli_query($koneksi, "truncate ujian");
   $exec = mysqli_query($koneksi, "truncate mata_pelajaran");
   $exec = mysqli_query($koneksi, "truncate informasi");
   $exec = mysqli_query($koneksi, "truncate jodoh");
   $exec = mysqli_query($koneksi, "truncate jawaban");
   $exec = mysqli_query($koneksi, "truncate kelas");
   $exec = mysqli_query($koneksi, "truncate jenis");
   $exec = mysqli_query($koneksi, "truncate reset");
   $exec = mysqli_query($koneksi, "truncate file_pendukung"); 
   $exec = mysqli_query($koneksi, "truncate jawaban_soal");
   $exec = mysqli_query($koneksi, "truncate kunci_soal");
	
$gambar = glob('../../temp/*'); 
foreach ($gambar as $filex) {
    if (is_file($filex))
        unlink($filex); 
}
   $foto = glob('../../files/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}
    $filezip = '../../files.zip';
     unlink($filezip);
  }
  if ($pg == 'optimal') {
	 $tablejawab = 'jawaban';
	 $tablesoal = 'soal';
	  $tablenilai = 'nilai';
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablejawab."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablesoal."'");
	   $exec = mysqli_query($koneksi, "OPTIMIZE TABLE '".$tablenilai."'");
  }
  
 if ($pg == 'selesaikan') {
    $query = mysqli_query($koneksi, "SELECT * FROM nilai WHERE ujian_selesai is null"); 
	while ($data = mysqli_fetch_array($query)) :
	
	$idm = $data['id_bank'];
    $ids = $data['id_siswa'];
    $idu = $data['id_ujian'];

	$totalsoal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$idm'"));
	$totaljawaban = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jawaban WHERE id_bank='$idm' AND id_siswa='$ids'"));
	$where = array(
		'id_bank' => $idm,
		'id_siswa' => $ids,
		'id_ujian' => $idu
	);

$benar = $salah = 0;
$benarm = $salahm = 0;
$benarb = $salahb = 0;
$benaru = $salahu = 0;
$benari = $salahi = 0;
$mapel = fetch($koneksi, 'banksoal', array('id_bank' => $idm));
$siswa = fetch($koneksi, 'siswa', array('id_siswa' => $ids));
$ceksoal = select($koneksi, 'soal', array('id_bank' => $idm, 'jenis' => 1));
$ceksoalesai = select($koneksi, 'soal', array('id_bank' => $idm, 'jenis' => 2));
$cekmulti = select($koneksi, 'soal', array('id_bank' => $idm, 'jenis' => 3));
$cekbs = select($koneksi, 'soal', array('id_bank' => $idm, 'jenis' => 4));
$cekurut = select($koneksi, 'soal', array('id_bank' => $idm, 'jenis' => 5));

$arrayjawabesai = array();
foreach ($ceksoalesai as $getsoalesai) {
    $w2 = array(
        'id_siswa' => $ids,
        'id_bank' => $idm,
        'id_soal' => $getsoalesai['id_soal'],
        'jenis' => 2
    );
   
    $getjwb2 = fetch($koneksi, 'jawaban', $w2);
    if ($getjwb2) {
        $jawabxx = str_replace("'", "`", $getjwb2['esai']);
        $jawabxx = str_replace("#", ">>", $jawabxx);
        $jawabxx = preg_replace('/[^A-Za-z0-9\@\<\>\$\_\&\-\+\(\)\/\?\!\;\:\`\"\[\]\*\{\}\=\%\~\`\÷\× ]/', '', $jawabxx);
        $arrayjawabesai[$getsoalesai['id_soal']] = $jawabxx;
    } else {
        $arrayjawabesai[$getsoalesai['id_soal']] = 'Tidak Diisi';
    }
	 ($getjwb2['esai'] == $getsoalesai['jawaban']) ? $benari++ : $salahi++;
}
$arrayjawab = array();
foreach ($ceksoal as $getsoal) {
    $w = array(
        'id_siswa' => $ids,
        'id_bank' => $idm,
        'id_soal' => $getsoal['id_soal'],
        'jenis' => 1
    );
    $getjwb = fetch($koneksi, 'jawaban', $w);
    if ($getjwb) {
        $arrayjawab[$getsoal['id_soal']] = $getjwb['jawaban'];
    } else {
        $arrayjawab[$getsoal['id_soal']] = 'X';
    }
    ($getjwb['jawaban'] == $getsoal['jawaban']) ? $benar++ : $salah++;
}

$arraymulti = array();
foreach ($cekmulti as $getmulti) {
    $m = array(
        'id_siswa' => $ids,
        'id_bank' => $idm,
        'id_soal' => $getmulti['id_soal'],
        'jenis' => 3
    );
    $getmt = fetch($koneksi, 'jawaban', $m);
    if ($getmulti) {
        $arraymulti[$getmulti['id_soal']] = $getmt['jawabmulti'];
    } else {
        $arraymulti[$getmulti['id_soal']] = 'X';
    }
    ($getmt['jawabmulti'] == $getmulti['jawaban']) ? $benarm++ : $salahm++;
}

$arraybs = array();
foreach ($cekbs as $getbs) {
    $b = array(
        'id_siswa' => $ids,
        'id_bank' => $idm,
        'id_soal' => $getbs['id_soal'],
        'jenis' => 4
    );
    $getb = fetch($koneksi, 'jawaban', $b);
    if ($getbs) {
        $arraybs[$getbs['id_soal']] = $getb['jawabbs'];
    } else {
        $arraybs[$getbs['id_soal']] = 'X';
    }
    ($getb['jawabbs'] == $getbs['jawaban']) ? $benarb++ : $salahb++;
}
$arrayurut = array();
foreach ($cekurut as $geturut) {
    $u = array(
        'id_siswa' => $ids,
        'id_bank' => $idm,
        'id_soal' => $geturut['id_soal'],
        'jenis' => 5
    );
    $getut = fetch($koneksi, 'jawaban', $u);
    if ($geturut) {
        $arrayurut[$geturut['id_soal']] = $getut['jawaburut'];
    } else {
        $arrayurut[$geturut['id_soal']] = 'X';
    }
    ($getut['jawaburut'] == $geturut['jawaban']) ? $benaru++ : $salahu++;
}


$data = array(
    'ujian_selesai' => $datetime,
    'online' => 0,
	'jml_benar' => $benar,
	'benar_esai' => $benari,
	'benar_multi' => $benarm,
	'benar_bs' => $benarb,
	'benar_urut' => $benaru,
	'jawaban_pg' => serialize($arrayjawab),
    'jawaban_esai' => serialize($arrayjawabesai),
	'jawaban_multi' => serialize($arraymulti),
	'jawaban_bs' => serialize($arraybs),
	'jawaban_urut' => serialize($arrayurut),
	'server' =>$setting['id_server'],
	'jumjawab' =>$totaljawaban,
	'jumsoal' =>$totalsoal,
	'browser'=>1
);

   $exec = update($koneksi, 'nilai', $data, $where);
	$exec = mysqli_query($koneksi, "DELETE FROM jodoh WHERE id_ujian='$idu' AND id_siswa='$ids'");
	$exec = mysqli_query($koneksi, "DELETE FROM jawaban WHERE id_ujian='$idu' AND id_siswa='$ids'");
	

	
	endwhile;
 	
  } 
  
   if ($pg == 'resetall') { 
	 $id = $_POST['id'];
$hapus = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_ujian='$id' and browser='0'");
echo $hapus;		
  }
  if ($pg == 'paksaall') {
    $id = $_POST['id'];
	
  $simpan = mysqli_query($koneksi,"UPDATE nilai SET ujian_selesai='$datetime', browser='1', online='0' WHERE id_ujian='$id' and browser='0'");
	$hapus = mysqli_query($koneksi, "DELETE FROM jawaban WHERE id_ujian='$id'");
	 $busek = mysqli_query($koneksi, "DELETE FROM reset WHERE idujian='$id'");
 
  }
   if ($pg == 'database') {
    
  
   $exec = mysqli_query($koneksi, "truncate banksoal");
   $exec = mysqli_query($koneksi, "truncate soal");
   $exec = mysqli_query($koneksi, "truncate siswa");
   $exec = mysqli_query($koneksi, "truncate ujian");
   $exec = mysqli_query($koneksi, "truncate mata_pelajaran");
   $exec = mysqli_query($koneksi, "truncate informasi");
   $exec = mysqli_query($koneksi, "truncate jodoh");
   $exec = mysqli_query($koneksi, "truncate jawaban");
   $exec = mysqli_query($koneksi, "truncate kelas");
   $exec = mysqli_query($koneksi, "truncate jenis");
   $exec = mysqli_query($koneksi, "truncate reset");
   $exec = mysqli_query($koneksi, "truncate file_pendukung"); 
   $exec = mysqli_query($koneksi, "truncate jawaban_soal");
   $exec = mysqli_query($koneksi, "truncate kunci_soal");
   $exec = mysqli_query($koneksi, "truncate nilai");
   $exec = mysqli_query($koneksi, "truncate nilai2");
   $foto = glob('../../files/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}
   }
?>