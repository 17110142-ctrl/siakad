<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$createTable = "
CREATE TABLE IF NOT EXISTS `absensi_harian` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `idsiswa` INT(11) NOT NULL,
  `kelas` VARCHAR(50) NOT NULL,
  `mapel` INT(11) NOT NULL,
  `guru` INT(11) NOT NULL,
  `ket` VARCHAR(1) NOT NULL DEFAULT 'H',
  `bulan` VARCHAR(2) NOT NULL,
  `tahun` VARCHAR(4) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jadwal_tanggal` (`id_jadwal`,`tanggal`),
  KEY `idx_idsiswa_tanggal` (`idsiswa`,`tanggal`),
  UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
mysqli_query($koneksi, $createTable);
@mysqli_query($koneksi, "ALTER TABLE absensi_harian ADD UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)");
$tanggalmu = date('Y-m-d');
$guru = $_POST['guru'];
$mapel = $_POST['mapel'];
$tanggal = $_POST['tanggal'];
$bulan = $_POST['bulan'];	
$tahun = $_POST['tahun'];	
$ket = $_POST['absen'];	
$ids = $_POST['idsiswa'];
$kelas = $_POST['kelas'];	
$jadwal = isset($_POST['jadwal']) ? $_POST['jadwal'] : [];
	
$count = count($_POST['kelas']);
$sql   = "INSERT INTO absensi(idsiswa,tanggal,kelas,level,ket,bulan,tahun) VALUES ";
for( $i=0; $i < $count; $i++ )
	
{
$sql .= "('{$ids[$i]}','{$tanggal[$i]}','{$kelas[$i]}','siswa','{$ket[$i]}','{$bulan[$i]}','{$tahun[$i]}')";
$sql .= ",";
}
$sql = rtrim($sql,",");
$exec = $koneksi->query($sql);

if($exec){
	$sql   = "INSERT INTO absensi_harian(id_jadwal,tanggal,idsiswa,kelas,mapel,guru,ket,bulan,tahun) VALUES ";
for( $i=0; $i < $count; $i++ )
	
{
$curJadwal = isset($jadwal[$i]) ? $jadwal[$i] : 0;
$sql .= "('{$curJadwal}','{$tanggal[$i]}','{$ids[$i]}','{$kelas[$i]}','{$mapel[$i]}','{$guru[$i]}','{$ket[$i]}','{$bulan[$i]}','{$tahun[$i]}')";
$sql .= ",";
}
$sql = rtrim($sql,",");
$exec = $koneksi->query($sql);
}
?>
