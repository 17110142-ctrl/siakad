<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

$kartu = $_POST['uid'];

$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa  WHERE nokartu='$kartu'"));
$ids = $siswa['id_siswa'];
$tambah = $saldoawal + $besar;
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where nokartu='$kartu'"));
if($jsiswa==0):
echo "GAGAL";
else:
  echo "         IMplus STORE          \n";
  echo "Jl.Raya Kalimulya Cibinong Bogor\n";
  echo "================================\n";
  echo "         CEK SALDO             \n\n";  
  echo "Nama   : ".substr($siswa['nama'],0,22)."\n";
  echo "Saldo  : RP ".number_format($siswa['saldo'])."\n";
  echo "Reff   : ".date('YmdHis')."\n";
  echo "================================\n";
  echo "        TERIMA KASIH            \n";
  echo " Cetak pada ".date('d-m-Y H:i:s')." \n\n";
endif;
mysqli_close($koneksi);
?>