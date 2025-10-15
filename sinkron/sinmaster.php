<?php
require("../config/koneksi.php");
require("../config/function.php");
$token = isset($_GET['token']) ? $_GET['token'] : 'false';
$querys = mysqli_query($koneksi, "select token_api from aplikasi where token_api='$token'");
$cektoken = mysqli_num_rows($querys);
if ($cektoken <> 0) {
  $querysiswa = mysqli_query($koneksi, "select * from siswa");
  $array_siswa = array();
  while ($siswa = mysqli_fetch_assoc($querysiswa)) {
    $array_siswa[] = $siswa;
  }
  $querypel = mysqli_query($koneksi, "select * from mata_pelajaran");
  $array_pel = array();
  while ($pel = mysqli_fetch_assoc($querypel)) {
    $array_pel[] = $pel;
  }
  $queryabsen = mysqli_query($koneksi, "select * from absensi");
  $array_absen = array();
  while ($absen = mysqli_fetch_assoc($queryabsen)) {
    $array_absen[] = $absen;
  }
  $queryreg = mysqli_query($koneksi, "select * from datareg");
  $array_reg = array();
  while ($reg = mysqli_fetch_assoc($queryreg)) {
    $array_reg[] = $reg;
  }
  $queryuser = mysqli_query($koneksi, "select * from users");
  $array_user = array();
  while ($user = mysqli_fetch_assoc($queryuser)) {
    $array_user[] = $user;
  }
   $querylanggar = mysqli_query($koneksi, "select * from bk_siswa");
  $array_langgar = array();
  while ($langgar = mysqli_fetch_assoc($querylanggar)) {
    $array_langgar[] = $langgar;
  }
   $querysurat = mysqli_query($koneksi, "select * from bk_surat");
  $array_surat = array();
  while ($surat = mysqli_fetch_assoc($querysurat)) {
    $array_surat[] = $surat;
  }
  echo json_encode(
    [
      "siswa" => $array_siswa,
      "pel" => $array_pel,
	  "absensi" => $array_absen,
	  "register" => $array_reg,
	  "pegawai" => $array_user,
	   "langgar" => $array_langgar,
	  "surat" => $array_surat
    ]
  );
} else {
  echo "<script>location.href='.'</script>";
}
