<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
$tgl =  date('Y-m-d');
$waktu = date('H:i:s');
$kartu = $_POST['uid'];

$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa  WHERE nokartu='$kartu'"));
$ids = $siswa['id_siswa'];
$saldo = $siswa['saldo'];
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa where nokartu='$kartu'"));
if($jsiswa==0):
echo "GAGAL";
else:
$jumtrx = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa,status FROM transaksi_kantin where idsiswa='$ids' AND status='1'"));
  if($jumtrx==0){
	  echo "KOSONG";
  }else{
	$trx = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(total_harga) AS total,idsiswa,status FROM transaksi_kantin  WHERE idsiswa='$ids' AND status='1'"));
    if($saldo > $trx['total']){
	$saldoakhir = $saldo - $trx['total'];
	$simpeun = mysqli_query($koneksi,"INSERT INTO saldo(tanggal,jam,idsiswa,debet,kredit) VALUES('$tgl','$waktu','$ids','0','$trx[total]')");
	$simpan = mysqli_query($koneksi,"UPDATE siswa SET saldo='$saldoakhir' WHERE id_siswa='$ids'");
	
		  echo "         IMplus STORE          \n";
          echo "Jl.Raya Kalimulya Cibinong Bogor\n";
		  echo "================================\n";
		  echo "        STRUK TRANSAKSI         \n\n";  
		  echo "Nama   : ".substr($siswa['nama'],0,22)."\n";
		  echo "Waktu  : ".date('d-m-Y H:i:s')." \n\n";
		$no=0;
		$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE idsiswa='$ids' AND status='1'"); 
		while ($data = mysqli_fetch_array($query)) :
		$idp = $data['idproduk'];		
		$prod = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM produk  WHERE produk_id='$data[idproduk]'"));
		$stok = $prod['produk_jumlah']-$data['jumlah'];
		mysqli_query($koneksi,"UPDATE produk SET produk_jumlah='$stok' WHERE produk_id='$idp'");
		$no++;
		 echo $data['jumlah']." ".substr($prod['produk_nama'],0,22)."\n";  
		 echo "                    RP ".number_format($data['total_harga'])."\n";
		
		endwhile;
		 echo "================================\n";
		 echo "TOTAL               RP ".number_format($trx['total'])."\n\n";
		if($simpan){ 
		mysqli_query($koneksi,"UPDATE transaksi SET status='2' WHERE idsiswa='$ids' AND status='1'");
	}
	}else{
	echo "TIDAK";
	}
  }
endif;
mysqli_close($koneksi);
?>