<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
 $nokartu = $_POST['nokartu'];
$idface = $_POST['idface'];
$tanggal = date('Y-m-d');
$jamabsen = date('H:i:s');
$bulan = date('m');
$tahun    = date('Y');
$jam_masuk  = strtotime($setting['masuk']);
$jam_datang = strtotime($waktu);
												
$selisih  = $jam_datang - $jam_masuk;
 
if($selisih > 0){
	$jam   = floor($selisih / (60 * 60));
	$menit = $selisih - ( $jam * (60 * 60) );
	$detik = $selisih % 60;
	$ket =  'Terlambat '.$jam .  ' jam, ' . floor( $menit / 60 ) . ' menit, ' . $detik . ' detik';
}else{
$ket = "Tepat Waktu";	
}		

$status = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM status"));	

$query = mysqli_query($koneksi, "select * from datareg where nokartu='$nokartu' and folder='$idface'");
$cek = mysqli_num_rows($query);
$data = mysqli_fetch_array($query);
$nama = $data['nama'];

if ($cek ==0) {
	echo "TIDAK TERDAFTAR";
	
	mysqli_close($koneksi);
		}else{
	
		echo $nama;	

		$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$data[idsiswa]'"));
		$kelas = $siswa['kelas'];
		$nowa = $siswa['nowa'];

		$cari_absen = mysqli_query($koneksi, "select * from absensi where nokartu='$nokartu' and tanggal='$tanggal'");
		$jumlah_absen = mysqli_num_rows($cari_absen);
			
		if($status['mode']=='1' AND $jumlah_absen==0):
			if($data['level']=='pegawai'){
				
			mysqli_query($koneksi, "insert into absensi(nokartu,tanggal,idpeg, masuk, ket, bulan,tahun,level,keterangan,mesin)values('$nokartu','$tanggal','$data[idpeg]', '$jamabsen','H', '$bulan','$tahun','pegawai','$ket','RFID WAJAH')");		
			mysqli_query($koneksi, "insert into pesan_terkirim(idpeg,waktu,ket)values('$data[idpeg]','$datetime','1')");		
			mysqli_query($koneksi, "TRUNCATE tmpreg");
			}else{
				 $koneksi->query("INSERT INTO  absensi(nokartu,tanggal,idsiswa,kelas,masuk,ket,bulan,tahun,level,keterangan,mesin)values('$nokartu','$tanggal', '$data[idsiswa]', '$kelas', '$jamabsen','H', '$bulan','$tahun','siswa','$ket','RFID WAJAH')");			
				mysqli_query($koneksi, "insert into pesan_terkirim(idsiswa,waktu,ket)values('$data[idsiswa]','$datetime','1')");		
			mysqli_query($koneksi, "TRUNCATE tmpreg");
			}
		endif;
		if($status['mode']=='2'):
			mysqli_query($koneksi, "update absensi set pulang='$jamabsen' where nokartu='$nokartu' and tanggal='$tanggal'");
			if($data['level']=='pegawai'){
			mysqli_query($koneksi, "insert into pesan_terkirim(idpeg,waktu,ket)values('$data[idpeg]','$datetime','2')");		
			mysqli_query($koneksi, "TRUNCATE tmpreg");
			}else{
				mysqli_query($koneksi, "insert into pesan_terkirim(idsiswa,waktu,ket)values('$data[idsiswa]','$datetime','2')");		
			mysqli_query($koneksi, "TRUNCATE tmpreg");
			}
			endif;

}
	
?>