
/*---------------------------------------------------------------
  SQL DB BACKUP 19.07.2024 21:20 
  HOST: localhost
  DATABASE: u8752035_siandi
  TABLES: *
  ---------------------------------------------------------------*/

/*---------------------------------------------------------------
  TABLE: `absen_daringmapel`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `absen_daringmapel`;
CREATE TABLE `absen_daringmapel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idmateri` int(11) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  `idsiswa` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam` varchar(50) DEFAULT NULL,
  `bulan` varchar(5) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  `tahun` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `absensi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `absensi`;
CREATE TABLE `absensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `nokartu` varchar(50) DEFAULT NULL,
  `idsiswa` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `idpeg` int(11) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `masuk` varchar(50) DEFAULT NULL,
  `pulang` varchar(50) DEFAULT NULL,
  `ket` varchar(5) DEFAULT NULL,
  `bulan` varchar(50) DEFAULT NULL,
  `tahun` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `mesin` varchar(50) DEFAULT NULL,
  `jjm` varchar(50) DEFAULT NULL,
  `honor` varchar(50) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `daring` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `absensi` VALUES   ('1','2024-07-15',NULL,'66','VII DIGITAL 2',NULL,'siswa','12:38:51','12:38:51','I','07','2024','Terlambat 478060 jam, 38 menit, 51 detik','MANUAL',NULL,NULL,NULL,'0');

/*---------------------------------------------------------------
  TABLE: `agenda`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `agenda`;
CREATE TABLE `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mapel` int(11) NOT NULL,
  `kuri` varchar(50) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `kd` varchar(50) DEFAULT NULL,
  `materi` text DEFAULT NULL,
  `kehadiran` int(11) DEFAULT NULL,
  `bulan` varchar(10) DEFAULT NULL,
  `tahun` varchar(10) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  `hambatan` text DEFAULT NULL,
  `pemecahan` text DEFAULT NULL,
  `lm` varchar(20) DEFAULT NULL,
  `tp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `agenda` VALUES   ('1','2','2','2024-07-16','VIII SAINS',NULL,'pancasila','0','07','2024','3','ribut','berikan teguran','1',NULL);
INSERT INTO `agenda` VALUES ('2','2','2','2024-07-19','VIII SAINS',NULL,'hukum bacaan','0','07','2024','3',NULL,NULL,'2',NULL);
INSERT INTO `agenda` VALUES ('3','2','2','2024-07-20','VIII SAINS',NULL,'Stratifikasi sosial','0','07','2024','3',NULL,NULL,'3',NULL);
INSERT INTO `agenda` VALUES ('4','2','2','2024-07-18','VIII SAINS',NULL,'Stratifikasi sosial','0','07','2024','3',NULL,NULL,'3',NULL);

/*---------------------------------------------------------------
  TABLE: `aplikasi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `aplikasi`;
CREATE TABLE `aplikasi` (
  `id_aplikasi` int(11) NOT NULL AUTO_INCREMENT,
  `aplikasi` varchar(50) DEFAULT NULL,
  `sekolah` varchar(50) DEFAULT NULL,
  `kode_sekolah` varchar(50) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `jenjang` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `semester` int(11) NOT NULL DEFAULT 1,
  `tp` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `desa` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `propinsi` varchar(50) DEFAULT NULL,
  `kepsek` varchar(50) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nowa` varchar(12) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `web` varchar(50) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `waktu` varchar(50) DEFAULT NULL,
  `url_host` varchar(50) DEFAULT NULL,
  `token_api` text DEFAULT NULL,
  `id_server` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `logo` text DEFAULT NULL,
  `header_kartu` text DEFAULT NULL,
  `header` text DEFAULT NULL,
  `server` varchar(20) DEFAULT NULL,
  `proktor` varchar(50) DEFAULT NULL,
  `tekhnisi` text DEFAULT NULL,
  `stempel` text DEFAULT NULL,
  `nama_ujian` varchar(50) DEFAULT NULL,
  `mesin` varchar(20) DEFAULT NULL,
  `url_api` varchar(100) DEFAULT NULL,
  `masuk` varchar(50) DEFAULT NULL,
  `pulang` varchar(50) DEFAULT NULL,
  `alpha` varchar(50) DEFAULT NULL,
  `pelanggaran` int(11) NOT NULL DEFAULT 0,
  `tanggal_rapor` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `aplikasi` VALUES   ('1','SIANDI','MADRASAH TSANAWIYAH NEGERI 1 BONE','P01','REGULER','SMP','40320078','1','2024/2025','Jl. Letjend Soekawati Watampone','Manurunge','Tanete Riattang ','Bone','Sulawesi Selatan ','H. Ambo Asse S.Pd., M.Pd.','196611071993031005 ','085241648359','mtsn1bone.official@gmail.com','https://siandi.mtsn1bone.sch.id/mypanel','081380774602','-','Asia/Makassar','https://ujian.mkkskabmalang.com','M4L4N9KJ9vUTCuZwEdis','192.168.99.18','0','logo14.png','KARTU PESERTA','KEMENTERIAN AGAMA REPUBLIK INDONESIA<br/>\r\nKEMENTERIAN AGAMA KABUPATEN BONE','pusat','PROKTOR',NULL,'stempel86.png','SUMATIF AKHIR TAHUN','BARCODE','https://server.mtsn1bone.sch.id','07:00','16:00','07:15','1','26 Juni 2024');

/*---------------------------------------------------------------
  TABLE: `banksoal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `banksoal`;
CREATE TABLE `banksoal` (
  `id_bank` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) DEFAULT NULL,
  `idguru` varchar(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `pk` text DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `soal_agama` varchar(50) DEFAULT NULL,
  `model` int(11) DEFAULT 0,
  `groupsoal` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_bank`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `barusikap`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `barusikap`;
CREATE TABLE `barusikap` (
  `idp` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) DEFAULT NULL,
  `p_dimensi` int(11) NOT NULL,
  `p_elemen` int(11) NOT NULL,
  `p_sub` int(11) NOT NULL,
  PRIMARY KEY (`idp`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `barusikap` VALUES   ('1','20221041','1','1','1');
INSERT INTO `barusikap` VALUES ('2','20221041','2','6','12');
INSERT INTO `barusikap` VALUES ('3','20221041','3','10','25');
INSERT INTO `barusikap` VALUES ('4','20221041','4','13','29');
INSERT INTO `barusikap` VALUES ('5','20221041','5','16','38');
INSERT INTO `barusikap` VALUES ('6','20221041','6','19','41');

/*---------------------------------------------------------------
  TABLE: `berita`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id_berita` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) NOT NULL,
  `sesi` varchar(10) NOT NULL,
  `ruang` varchar(20) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `ikut` varchar(10) DEFAULT NULL,
  `susulan` varchar(10) DEFAULT NULL,
  `no_susulan` text DEFAULT NULL,
  `mulai` varchar(10) DEFAULT NULL,
  `selesai` varchar(10) DEFAULT NULL,
  `nama_proktor` varchar(50) DEFAULT NULL,
  `nip_proktor` varchar(50) DEFAULT NULL,
  `nama_pengawas` varchar(50) DEFAULT NULL,
  `nip_pengawas` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tgl_ujian` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_berita`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `bulan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `bulan`;
CREATE TABLE `bulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bln` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ket` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `bulan` VALUES   ('1','01','Januari');
INSERT INTO `bulan` VALUES ('2','02','Februari');
INSERT INTO `bulan` VALUES ('3','03','Maret');
INSERT INTO `bulan` VALUES ('4','04','April');
INSERT INTO `bulan` VALUES ('5','05','Mei');
INSERT INTO `bulan` VALUES ('6','06','Juni');
INSERT INTO `bulan` VALUES ('7','07','Juli');
INSERT INTO `bulan` VALUES ('8','08','Agustus');
INSERT INTO `bulan` VALUES ('9','09','September');
INSERT INTO `bulan` VALUES ('10','10','Oktober');
INSERT INTO `bulan` VALUES ('11','11','Nopember');
INSERT INTO `bulan` VALUES ('12','12','Desember');

/*---------------------------------------------------------------
  TABLE: `datareg`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `datareg`;
CREATE TABLE `datareg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nokartu` varchar(50) DEFAULT NULL,
  `idsiswa` int(11) DEFAULT NULL,
  `idpeg` int(11) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `idjari` int(11) DEFAULT NULL,
  `serial` varchar(50) NOT NULL,
  `sts` int(11) NOT NULL DEFAULT 0,
  `no_wa` varchar(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nokartu` (`nokartu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `deskripsi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `deskripsi`;
CREATE TABLE `deskripsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mapel` varchar(100) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `smt` int(11) DEFAULT NULL,
  `ki` varchar(11) DEFAULT NULL,
  `kd` varchar(20) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `download`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `download`;
CREATE TABLE `download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `npsn` varchar(50) DEFAULT NULL,
  `idbank` int(11) DEFAULT NULL,
  `ket` int(11) NOT NULL DEFAULT 1,
  `waktu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `file_pendukung`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `file_pendukung`;
CREATE TABLE `file_pendukung` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT 0,
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*---------------------------------------------------------------
  TABLE: `informasi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `informasi`;
CREATE TABLE `informasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(50) DEFAULT NULL,
  `untuk` varchar(50) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `waktu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jadwal_mapel`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jadwal_mapel`;
CREATE TABLE `jadwal_mapel` (
  `id_jadwal` int(11) NOT NULL AUTO_INCREMENT,
  `tingkat` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` varchar(100) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  `hari` varchar(50) DEFAULT NULL,
  `kuri` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_jadwal`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `jadwal_mapel` VALUES   ('1','8','VIII SAINS','2','3','Tue','2');
INSERT INTO `jadwal_mapel` VALUES ('2','8','VIII SAINS','6','8','Mon','2');
INSERT INTO `jadwal_mapel` VALUES ('3','8','VIII DIGITAL 1','6','8','Tue','2');
INSERT INTO `jadwal_mapel` VALUES ('4','8','VIII SAINS','6','8','Thu','2');
INSERT INTO `jadwal_mapel` VALUES ('5','8','VII DIGITAL 2','6','8','Thu','2');

/*---------------------------------------------------------------
  TABLE: `jadwal_rapor`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jadwal_rapor`;
CREATE TABLE `jadwal_rapor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `kelas` varchar(100) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  `kuri` varchar(50) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jawaban`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban`;
CREATE TABLE `jawaban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `id_bank` int(11) NOT NULL DEFAULT 0,
  `id_soal` int(11) NOT NULL DEFAULT 0,
  `id_ujian` int(11) NOT NULL DEFAULT 0,
  `jawaban` varchar(50) DEFAULT NULL,
  `jawabx` varchar(50) DEFAULT NULL,
  `jenis` int(11) NOT NULL,
  `esai` text DEFAULT NULL,
  `jawabmulti` text DEFAULT NULL,
  `jawabbs` text DEFAULT NULL,
  `jawaburut` text DEFAULT NULL,
  `bs1` varchar(5) DEFAULT NULL,
  `bs2` varchar(5) DEFAULT NULL,
  `bs3` varchar(5) DEFAULT NULL,
  `bs4` varchar(5) DEFAULT NULL,
  `bs5` varchar(5) DEFAULT NULL,
  `urut1` text DEFAULT NULL,
  `urut2` text DEFAULT NULL,
  `urut3` text DEFAULT NULL,
  `urut4` text DEFAULT NULL,
  `urut5` text DEFAULT NULL,
  `nilai_esai` int(11) NOT NULL DEFAULT 0,
  `ragu` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jawaban_dup`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban_dup`;
CREATE TABLE `jawaban_dup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `id_bank` int(11) NOT NULL DEFAULT 0,
  `id_soal` int(11) NOT NULL DEFAULT 0,
  `id_ujian` int(11) NOT NULL DEFAULT 0,
  `jawaban` varchar(50) DEFAULT NULL,
  `jawabx` varchar(50) DEFAULT NULL,
  `jenis` int(11) NOT NULL,
  `esai` text DEFAULT NULL,
  `jawabmulti` text DEFAULT NULL,
  `jawabbs` text DEFAULT NULL,
  `jawaburut` text DEFAULT NULL,
  `bs1` varchar(5) DEFAULT NULL,
  `bs2` varchar(5) DEFAULT NULL,
  `bs3` varchar(5) DEFAULT NULL,
  `bs4` varchar(5) DEFAULT NULL,
  `bs5` varchar(5) DEFAULT NULL,
  `urut1` text DEFAULT NULL,
  `urut2` text DEFAULT NULL,
  `urut3` text DEFAULT NULL,
  `urut4` text DEFAULT NULL,
  `urut5` text DEFAULT NULL,
  `nilai_esai` int(11) NOT NULL DEFAULT 0,
  `ragu` int(11) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jawaban_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban_soal`;
CREATE TABLE `jawaban_soal` (
  `id_jawaban` int(11) NOT NULL AUTO_INCREMENT,
  `id_soal` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `id_bank` int(11) DEFAULT NULL,
  `id_ujian` int(11) DEFAULT NULL,
  `idjawab` varchar(50) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `jawab` text DEFAULT NULL,
  `skor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jawaban_tugas`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban_tugas`;
CREATE TABLE `jawaban_tugas` (
  `id_jawaban` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `id_tugas` int(11) DEFAULT NULL,
  `jawaban` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `tgl_dikerjakan` datetime DEFAULT NULL,
  `tgl_update` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nilai` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jenis`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jenis`;
CREATE TABLE `jenis` (
  `id_jenis` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `jenis` VALUES   ('PAT','Penilaian Akhir Tahun','tidak');
INSERT INTO `jenis` VALUES ('SAT','SUMATIF AKHIR TAHUN','aktif');

/*---------------------------------------------------------------
  TABLE: `jenis_sp`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jenis_sp`;
CREATE TABLE `jenis_sp` (
  `id_sp` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) DEFAULT NULL,
  `jenjang` varchar(50) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_sp`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `jenis_sp` VALUES   ('1','REGULER','SD','SD/MI');
INSERT INTO `jenis_sp` VALUES ('2','REGULER','SMP','SMP/MTs');
INSERT INTO `jenis_sp` VALUES ('3','REGULER','SMA','SMA/MA');
INSERT INTO `jenis_sp` VALUES ('4','REGULER','SMK','SMK');
INSERT INTO `jenis_sp` VALUES ('5','PKBM','PAKET-A','KESETARAAN SD');
INSERT INTO `jenis_sp` VALUES ('6','PKBM','PAKET-B','KESETARAAN SMP');
INSERT INTO `jenis_sp` VALUES ('7','PKBM','PAKET-C','KESETARAAN SMA');

/*---------------------------------------------------------------
  TABLE: `jodoh`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jodoh`;
CREATE TABLE `jodoh` (
  `id_jawaban` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `id_bank` int(11) NOT NULL DEFAULT 0,
  `id_soal` int(11) NOT NULL DEFAULT 0,
  `id_ujian` int(11) NOT NULL DEFAULT 0,
  `jenis` varchar(50) DEFAULT NULL,
  `jawaburut` text DEFAULT NULL,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `jurusan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jurusan`;
CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_jurusan` varchar(50) DEFAULT NULL,
  `nama_jurusan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `jurusan` VALUES   ('1','semua',NULL);

/*---------------------------------------------------------------
  TABLE: `kelas`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `pk` varchar(100) DEFAULT NULL,
  `kuri` varchar(11) DEFAULT NULL,
  `model_kkm` varchar(50) DEFAULT NULL,
  `model_rapor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `komentar`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `komentar`;
CREATE TABLE `komentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_materi` int(11) DEFAULT NULL,
  `komentar` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `jenis` tinyint(2) DEFAULT NULL,
  `tgl` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `kontakme`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kontakme`;
CREATE TABLE `kontakme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nowa` varchar(13) DEFAULT NULL,
  `nama_kontak` varchar(50) DEFAULT NULL,
  `pemilik` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `kontakme` VALUES   ('1','081380774602','Edi Sukarna','1');
INSERT INTO `kontakme` VALUES ('2','085179786825','tes','1');
INSERT INTO `kontakme` VALUES ('3','085232733677','asmar','1');

/*---------------------------------------------------------------
  TABLE: `kunci_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kunci_soal`;
CREATE TABLE `kunci_soal` (
  `id_bank` int(11) DEFAULT NULL,
  `id_soal` int(11) DEFAULT NULL,
  `id_jawab` varchar(50) DEFAULT NULL,
  `jawaban` text DEFAULT NULL,
  `skor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `level`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `id_level` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `kurikulum` varchar(5) DEFAULT NULL,
  `model_kkm` varchar(50) DEFAULT NULL,
  `model_rapor` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `lingkup`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `lingkup`;
CREATE TABLE `lingkup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mapel` varchar(11) DEFAULT NULL,
  `level` varchar(11) DEFAULT NULL,
  `materi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `smt` varchar(11) DEFAULT NULL,
  `lm` int(11) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `lingkup` VALUES   ('1','2','8','pancasila','1','1','3');
INSERT INTO `lingkup` VALUES ('2','2','8','hukum bacaan','1','2','3');
INSERT INTO `lingkup` VALUES ('3','2','8','Stratifikasi sosial','1','3','3');

/*---------------------------------------------------------------
  TABLE: `log`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `text` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `log` VALUES   ('1','3','login','Masuk','2024-07-19 07:50:29','pegawai');
INSERT INTO `log` VALUES ('2','3','login','Masuk','2024-07-19 07:57:19','pegawai');
INSERT INTO `log` VALUES ('3','8','login','Masuk','2024-07-19 08:24:34','pegawai');
INSERT INTO `log` VALUES ('4','1','login','Masuk','2024-07-19 08:41:13','admin');
INSERT INTO `log` VALUES ('5','101','login','Masuk','2024-07-19 12:52:22','pegawai');
INSERT INTO `log` VALUES ('6','1','login','Masuk','2024-07-19 21:18:14','admin');
INSERT INTO `log` VALUES ('7','1','login','Masuk','2024-07-19 21:18:42','admin');

/*---------------------------------------------------------------
  TABLE: `m_dimensi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_dimensi`;
CREATE TABLE `m_dimensi` (
  `id_dimensi` int(11) NOT NULL AUTO_INCREMENT,
  `dimensi` text DEFAULT NULL,
  PRIMARY KEY (`id_dimensi`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_dimensi` VALUES   ('1','Beriman, Bertakwa Kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia');
INSERT INTO `m_dimensi` VALUES ('2','Berkebhinekaan Global');
INSERT INTO `m_dimensi` VALUES ('3','Bergotong-Royong');
INSERT INTO `m_dimensi` VALUES ('4','Mandiri');
INSERT INTO `m_dimensi` VALUES ('5','Bernalar Kritis');
INSERT INTO `m_dimensi` VALUES ('6','Kreatif');

/*---------------------------------------------------------------
  TABLE: `m_elemen`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_elemen`;
CREATE TABLE `m_elemen` (
  `id_elemen` int(11) NOT NULL AUTO_INCREMENT,
  `iddimensi` int(11) NOT NULL,
  `elemen` varchar(100) NOT NULL,
  PRIMARY KEY (`id_elemen`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_elemen` VALUES   ('1','1','Akhlak Beragama');
INSERT INTO `m_elemen` VALUES ('2','1','Akhlak Pribadi');
INSERT INTO `m_elemen` VALUES ('3','1','Akhlak Kepada Manusia');
INSERT INTO `m_elemen` VALUES ('4','1','Akhlak Kepada Alam');
INSERT INTO `m_elemen` VALUES ('5','1','Akhlak Bernegara');
INSERT INTO `m_elemen` VALUES ('6','2','Mengenal dan menghargai budaya');
INSERT INTO `m_elemen` VALUES ('7','2','Komunikasi dan interaksi antar budaya');
INSERT INTO `m_elemen` VALUES ('8','2','Refleksi dan bertanggung jawab terhadap pengalaman kebinekaan');
INSERT INTO `m_elemen` VALUES ('9','2','Berkeadilan Sosial');
INSERT INTO `m_elemen` VALUES ('10','3','Kolaborasi');
INSERT INTO `m_elemen` VALUES ('11','3','Kepedulian');
INSERT INTO `m_elemen` VALUES ('12','3','Berbagi');
INSERT INTO `m_elemen` VALUES ('13','4','Pemahaman diri dan situasi yang dihadap');
INSERT INTO `m_elemen` VALUES ('14','4','Regulasi Diri');
INSERT INTO `m_elemen` VALUES ('15','5','Memperoleh dan memproses informasi dan gagasan');
INSERT INTO `m_elemen` VALUES ('16','5','Menganalisis dan mengevaluasi penalaran dan prosedurnya');
INSERT INTO `m_elemen` VALUES ('17','5','Refleksi pemikiran dan proses berpikir');
INSERT INTO `m_elemen` VALUES ('18','6','Menghasilkan gagasan yang orisinal');
INSERT INTO `m_elemen` VALUES ('19','6','Menghasilkan karya dan tindakan yang orisinal');
INSERT INTO `m_elemen` VALUES ('20','6','Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan');

/*---------------------------------------------------------------
  TABLE: `m_eskul`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_eskul`;
CREATE TABLE `m_eskul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eskul` varchar(100) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_eskul` VALUES   ('1','Marching Band Iqra','105');

/*---------------------------------------------------------------
  TABLE: `m_hari`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_hari`;
CREATE TABLE `m_hari` (
  `idh` int(11) NOT NULL AUTO_INCREMENT,
  `hari` varchar(50) NOT NULL,
  `inggris` varchar(50) NOT NULL,
  PRIMARY KEY (`idh`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_hari` VALUES   ('1','Senin','Mon');
INSERT INTO `m_hari` VALUES ('2','Selasa','Tue');
INSERT INTO `m_hari` VALUES ('3','Rabu','Wed');
INSERT INTO `m_hari` VALUES ('4','Kamis','Thu');
INSERT INTO `m_hari` VALUES ('5','Jumat','Fri');
INSERT INTO `m_hari` VALUES ('6','Sabtu','Sat');
INSERT INTO `m_hari` VALUES ('7','Minggu','mon');

/*---------------------------------------------------------------
  TABLE: `m_kurikulum`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_kurikulum`;
CREATE TABLE `m_kurikulum` (
  `idk` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kurikulum` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_kurikulum` VALUES   ('1','K-13');
INSERT INTO `m_kurikulum` VALUES ('2','K-Merdeka');

/*---------------------------------------------------------------
  TABLE: `m_nilai_proyek`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_nilai_proyek`;
CREATE TABLE `m_nilai_proyek` (
  `nilai` varchar(3) NOT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nilai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_nilai_proyek` VALUES   ('BB','Belum Berkembang');
INSERT INTO `m_nilai_proyek` VALUES ('BSH','Berkembang Sesuai Harapan');
INSERT INTO `m_nilai_proyek` VALUES ('MB','Mulai Berkembang');
INSERT INTO `m_nilai_proyek` VALUES ('SB','Sangat Berkembang');

/*---------------------------------------------------------------
  TABLE: `m_pesan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_pesan`;
CREATE TABLE `m_pesan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pesan1` text DEFAULT NULL,
  `pesan2` text DEFAULT NULL,
  `pesan3` text DEFAULT NULL,
  `pesan4` text DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_pesan` VALUES   ('1','Assalamualaikum Warahmatullahi Wabarakatuh','Kami informasikan Bahwa :','Telah hadir di MTsN 1 BONE menggunakan Absesi Digital BARCODE pada :','.\r\nDemikian Informasi kami sampaikan untuk menjadi Sarana Monitoring Orang Tua Siswa terhadap putra putrinya. Terima kasih dan salam hangat dari Kami.\r\n\r\nPesan ini tidak perlu dibalas dikirim via *SERVER WA GATEWAY MTsN 1 BONE*',NULL);
INSERT INTO `m_pesan` VALUES ('2','Assalamualaikum Warahmatullahi Wabarakatuh','Kami informasikan Bahwa :','*Telah selesai melaksanakan Proses Pembelajaran di MTsN 1 BONE* ','.\r\nDemikian Informasi kami sampaikan untuk menjadi Sarana Monitoring Orang Tua Siswa terhadap putra putrinya. Terima kasih dan salam hangat dari Kami.\r\n\r\nPesan ini tidak perlu dibalas dikirim via *SERVER WA GATEWAY MTsN 1 BONE*',NULL);
INSERT INTO `m_pesan` VALUES ('3','Assalamualaikum Warahmatullahi Wabarakatuh','Kami informasikan Bahwa ','Telah hadir di MTsN 1 BONE menggunakan Absesi Digital *Barcode Scan* pada ','.\r\nDemikian Informasi ini kami sampaikan untuk menjadi Sarana Monitoring Kepala Madrasah terhadap para pegawai. Terima kasih dan salam hangat dari Kami.\r\n\r\nPesan ini tidak perlu dibalas.\r\n\r\ndikirim via *SERVER WA GATEWAY MTsN 1 BONE*',NULL);
INSERT INTO `m_pesan` VALUES ('4','Assalamualaikum Warahmatullahi Wabarakatuh','Kami informasikan bahwa','Telah selesai melaksanakan KBM di MTsN 1 BONE pada','.\r\nDemikian Informasi ini kami sampaikan untuk menjadi Sarana Monitoring Kepala Madrasah terhadap para guru dan pegawai. Terima kasih dan salam hangat dari Kami.\r\n\r\nPesan ini tidak perlu dibalas.\r\n\r\ndikirim via *SERVER WA GATEWAY MTsN 1 BONE*',NULL);

/*---------------------------------------------------------------
  TABLE: `m_proyek`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_proyek`;
CREATE TABLE `m_proyek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ke` varchar(50) NOT NULL,
  `tingkat` varchar(50) DEFAULT NULL,
  `fase` varchar(1) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `m_rapor`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_rapor`;
CREATE TABLE `m_rapor` (
  `idr` int(11) NOT NULL AUTO_INCREMENT,
  `kuri` int(11) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idr`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_rapor` VALUES   ('1','1','Model 2016');
INSERT INTO `m_rapor` VALUES ('2','1','Model 2023');
INSERT INTO `m_rapor` VALUES ('3','2','Model Kurmer');

/*---------------------------------------------------------------
  TABLE: `m_spiritual`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_spiritual`;
CREATE TABLE `m_spiritual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ket` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_spiritual` VALUES   ('1','berdoa sebelum dan sesudah melakukan kegiatan');
INSERT INTO `m_spiritual` VALUES ('2','menjalankan ibadah sesuai dengan agamanya');
INSERT INTO `m_spiritual` VALUES ('3','memberi salam pada saat awal dan akhir kegiatan');
INSERT INTO `m_spiritual` VALUES ('4','bersyukur atas nikmat dan karunia Tuhan Yang Maha Esa');
INSERT INTO `m_spiritual` VALUES ('5','bersyukur ketika berhasil mengerjakan sesuatu');
INSERT INTO `m_spiritual` VALUES ('6','berserah diri (tawakal) kepada Tuhan setelah berikhtiar atau melakukan usaha');
INSERT INTO `m_spiritual` VALUES ('7','memelihara hubungan baik dengan sesama umat');

/*---------------------------------------------------------------
  TABLE: `m_sub_elemen`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `m_sub_elemen`;
CREATE TABLE `m_sub_elemen` (
  `id_sub` int(11) NOT NULL AUTO_INCREMENT,
  `iddimensi` int(11) NOT NULL,
  `idelemen` int(11) NOT NULL,
  `sub_elemen` varchar(100) NOT NULL,
  `A` text DEFAULT NULL,
  `B` text DEFAULT NULL,
  `C` text DEFAULT NULL,
  `D` text DEFAULT NULL,
  `E` text DEFAULT NULL,
  PRIMARY KEY (`id_sub`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `m_sub_elemen` VALUES   ('1','1','1','Mengenal dan Mencintai Tuhan Yang Maha Esa','Mengenali sifat-sifat utama Tuhan bahwa Ia Maha Esa dan Ia adalah Sang Pencipta yang Maha Pengasih dan Maha Penyayang dan mengenali kebaikan dirinya sebagai cerminan sifat Tuhan','Memahami sifat-sifat Tuhan utama lainnya dan mengaitkan sifatsifat tersebut dengan konsep dirinya dan ciptaan-Nya','Memahami berbagai kualitas atau sifat-sifat Tuhan yang diutarakan dalam kitab suci agama masing-masing dan menghubungkan kualitas-kualitas positif Tuhan dengan sikap pribadinya, serta meyakini firman Tuhan sebagai kebenaran','Memahami kehadiran Tuhan dalam kehidupan sehari-hari serta mengaitkan pemahamannya tentang kualitas atau sifat-sifat Tuhan dengan konsep peran manusia di bumi sebagai makhluk Tuhan yang bertanggung jawab','Menerapkan pemahamannya tentang kualitas atau sifat-sifat Tuhan dalam ritual ibadahnya baik ibadah yang bersifat personal maupun sosial.');
INSERT INTO `m_sub_elemen` VALUES ('2','1','1','Pemahaman Agama/Kepercayaan','Mengenali unsur-unsur utama agama/kepercayaan (ajaran, ritual keagamaan, kitab suci, dan orang suci/ utusan Tuhan YME).','Mengenali unsur-unsur utama agama/kepercayaan (simbol-simbol keagamaan dan sejarah agama/ kepercayaan)','Memahami berbagai kualitas atau sifat-sifat Tuhan yang diutarakan dalam kitab suci agama masing-masing dan menghubungkan kualitas-kualitas positif Tuhan dengan sikap pribadinya, serta meyakini firman Tuhan sebagai kebenaran','Memahami kehadiran Tuhan dalam kehidupan sehari-hari serta mengaitkan pemahamannya tentang kualitas atau sifat-sifat Tuhan dengan konsep peran manusia di bumi sebagai makhluk Tuhan yang bertanggung jawab','Memahami struktur organisasi, unsur-unsur utama agama /kepercayaan dalam konteks Indonesia, memahami kontribusi agama/kepercayaan terhadap peradaban dunia.');
INSERT INTO `m_sub_elemen` VALUES ('3','1','1','Pelaksanaan Ritual Ibadah','Terbiasa melaksanakan ibadah sesuai ajaran agama/kepercayaannya','Terbiasa melaksanakan ibadah wajib sesuai tuntunan agama/kepercayaannya','Melaksanakan ibadah secara rutin sesuai dengan tuntunan agama/kepercayaan, berdoa mandiri, merayakan, dan memahami makna hari-hari besarnya','Melaksanakan ibadah secara rutin dan mandiri sesuai dengan tuntunan agama/kepercayaan, serta berpartisipasi pada perayaan hari-hari besarnya\r\n','Melaksanakan ibadah secara rutin dan mandiri serta menyadari arti penting ibadah tersebut dan berpartisipasi aktif pada kegiatan keagamaan atau kepercayaan');
INSERT INTO `m_sub_elemen` VALUES ('4','1','2','Integritas','Membiasakan bersikap jujur terhadap diri sendiri dan orang lain dan berani menyampaikan kebenaran atau fakta','Membiasakan melakukan refleksi tentang pentingnya bersikap jujur dan berani menyampaikan kebenaran atau fakta','Berani dan konsisten menyampaikan kebenaran atau fakta serta memahami konsekuensi-konsekuensinya untuk diri sendiri','Berani dan konsisten menyampaikan kebenaran atau fakta serta memahami konsekuensi-konsekuensinya untuk diri sendiri dan orang lain','Menyadari bahwa aturan agama dan sosial merupakan aturan yang baik dan menjadi bagian dari diri sehingga bisa menerapkannya secara bijak dan kontekstual');
INSERT INTO `m_sub_elemen` VALUES ('5','1','2','Merawat Diri secara Fisik, Mental dan Spiritual','Memiliki rutinitas sederhana yang diatur secara mandiri dan dijalankan sehari-hari serta menjaga kesehatan dan keselamatan/keamanan diri dalam semua aktivitas kesehariannya.\r\n','Mulai membiasakan diri untuk disiplin, rapi, membersihkan dan merawat tubuh, menjaga tingkah laku dan perkataan dalam semua aktivitas kesehariannya','Memperhatikan kesehatan jasmani, mental, dan rohani dengan melakukan aktivitas fisik, sosial, dan ibadah','Mengidentifikasi pentingnya menjaga keseimbangan kesehatan jasmani, mental, dan rohani serta berupaya menyeimbangkan aktivitas fisik, sosial dan ibadah','Melakukan aktivitas fisik, sosial, dan ibadah secara seimbang.');
INSERT INTO `m_sub_elemen` VALUES ('6','1','3','Mengutamakan persamaan dengan orang lain dan menghargai perbedaan','Mengenali hal-hal yang sama dan berbeda yang dimiliki diri dan temannya dalam berbagai hal, serta memberikan respon secara positif.','Terbiasa mengidentifikasi hal-hal yang sama dan berbeda yang dimiliki diri dan temannya dalam berbagai hal serta memberikan respon secara positif.','Mengidentifikasi kesamaan dengan orang lain sebagai perekat hubungan sosial dan mewujudkannya dalam aktivitas kelompok. Mulai mengenal berbagai kemungkinan interpretasi dan cara pandang yang berbeda ketika dihadapkan dengan dilema.','Mengenal perspektif dan emosi/perasaan dari sudut pandang orang atau kelompok lain yang tidak pernah dijumpai atau dikenalnya. Mengutamakan persamaan dan menghargai perbedaan sebagai alat pemersatu dalam keadaan konflik atau perdebatan.','Mengidentifikasi hal yang menjadi permasalahan bersama, memberikan alternatif solusi untuk menjembatani perbedaan dengan mengutamakan kemanusiaan.');
INSERT INTO `m_sub_elemen` VALUES ('7','1','3','Berempati kepada orang lain','Mengidentifikasi emosi, minat dan kebutuhan orang-orang terdekat dan meresponnya secara positif.','Terbiasa memberikan apresiasi di lingkungan sekolah dan masyarakat','Mulai memandang sesuatu dari perspektif orang lain serta mengidentifikasi kebaikan dan kelebihan orang sekitarnya.','Memahami perasaan dan sudut pandang orang dan/atau kelompok lain yang tidak pernah dikenalnya','Memahami dan menghargai perasaan dan sudut pandang orang dan/atau kelompok lain.');
INSERT INTO `m_sub_elemen` VALUES ('8','1','4','Memahami Keterhubungan Ekosistem Bumi','Mengidentifikasi berbagai ciptaan Tuhan','Memahami keterhubungan antara satu ciptaan dengan ciptaan Tuhan yang lainnya','Memahami konsep harmoni dan mengidentifikasi adanya saling ketergantungan antara berbagai ciptaan Tuhan','Memahami konsep sebab-akibat di antara berbagai ciptaan Tuhan dan mengidentifikasi berbagai sebab yang mempunyai dampak baik atau buruk, langsung maupun tidak langsung, terhadap alam semesta.\r\n','Mengidentifikasi masalah lingkungan hidup di tempat ia tinggal dan melakukan langkah-langkah konkrit yang bisa dilakukan untuk menghindari kerusakan dan menjaga keharmonisan ekosistem yang ada di lingkungannya.');
INSERT INTO `m_sub_elemen` VALUES ('9','1','4','Menjaga Lingkungan Alam Sekitar','Membiasakan bersyukur atas lingkungan alam sekitar dan berlatih untuk menjaganya\r\n','Terbiasa memahami tindakan-tindakan yang ramah dan tidak ramah lingkungan serta membiasakan diri untuk berperilaku ramah lingkungan\r\n','Mewujudkan rasa syukur dengan terbiasa berperilaku ramah lingkungan dan memahami akibat perbuatan tidak ramah lingkungan dalam lingkup kecil maupun besar.\r\n','Mewujudkan rasa syukur dengan berinisiatif untuk menyelesaikan permasalahan lingkungan alam sekitarnya dengan mengajukan alternatif solusi dan mulai menerapkan solusi tersebut.\r\n','Mewujudkan rasa syukur dengan membangun kesadaran peduli lingkungan alam dengan menciptakan dan mengimplementasikan solusi dari permasalahan lingkungan yang ada.');
INSERT INTO `m_sub_elemen` VALUES ('10','1','5','Melaksanakan Hak dan Kewajiban sebagai Warga Negara Indonesia','Mengidentifikasi hak dan tanggung jawabnya di rumah, sekolah, dan lingkungan sekitar serta kaitannya dengan keimanan kepada Tuhan YME.\r\n\r\n','Mengidentifikasi hak dan tanggung jawab orang-orang di sekitarnya serta kaitannya dengan keimanan kepada Tuhan YME.\r\n','Mengidentifikasi dan memahami peran, hak, dan kewajiban dasar sebagai warga negara serta kaitannya dengan keimanan kepada Tuhan YME dan secara sadar mempraktikkannya dalam kehidupan sehari-hari.\r\n','Menganalisa peran, hak, dan kewajiban sebagai warga negara, memahami perlunya mengutamakan kepentingan umum di atas kepentingan pribadi sebagai wujud dari keimanannya kepada Tuhan YME.\r\n','Memperoleh hak dan melaksanakan kewajiban kewarganegaraan dan terbiasa mendahulukan kepentingan umum di atas kepentingan pribadi sebagai wujud dari keimanannya kepada Tuhan YME.');
INSERT INTO `m_sub_elemen` VALUES ('11','2','6','Mendalami budaya dan identitas budaya','Mengidentifikasi dan mendeskripsikan ide-ide tentang dirinya dan beberapa macam kelompok di lingkungan sekitarnya\r\n','Mengidentifikasi dan mendeskripsikan ide-ide tentang dirinya dan berbagai macam kelompok di lingkungan sekitarnya, serta cara orang lain berperilaku dan berkomunikasi dengannya.\r\n','Mengidentifikasi dan mendeskripsikan keragaman budaya di sekitarnya; serta menjelaskan peran budaya dan Bahasa dalam membentuk identitas dirinya.\r\n','Menjelaskan perubahan budaya seiring waktu dan sesuai konteks, baik dalam skala lokal, regional, dan nasional. Menjelaskan identitas diri yang terbentuk dari budaya bangsa.\r\n','Menganalisis pengaruh keanggotaan kelompok lokal, regional, nasional, dan global terhadap pembentukan identitas, termasuk identitas dirinya. Mulai menginternalisasi identitas diri sebagai bagian dari budaya bangsa.');
INSERT INTO `m_sub_elemen` VALUES ('12','2','6','Mengeksplorasi dan membandingkan pengetahuan budaya, kepercayaan, serta praktiknya','Mengidentifikasi dan mendeskripsikan praktik keseharian diri dan budayanya\r\n','Mengidentifikasi dan membandingkan praktik keseharian diri dan budayanya dengan orang lain di tempat dan waktu/era yang berbeda.\r\n','Mendeskripsikan dan membandingkan pengetahuan, kepercayaan, dan praktik dari berbagai kelompok budaya.\r\n','Memahami dinamika budaya yang mencakup pemahaman, kepercayaan, dan praktik keseharian dalam konteks personal dan sosial.\r\n','Menganalisis dinamika budaya yang mencakup pemahaman, kepercayaan, dan praktik keseharian dalam rentang waktu yang panjang dan konteks yang luas.');
INSERT INTO `m_sub_elemen` VALUES ('13','2','6','Menumbuhkan rasa menghormati terhadap keanekaragaman budaya','Mendeskripsikan pengalaman dan pemahaman hidup bersama-sama dalam kemajemukan.\r\n','Memahami bahwa kemajemukan dapat memberikan kesempatan untuk mendapatkan pengalaman dan pemahaman yang baru.\r\n','Mengidentifikasi peluang dan tantangan yang muncul dari keragaman budaya di Indonesia.\r\n','Memahami pentingnya melestarikan dan merayakan tradisi budaya untuk mengembangkan identitas pribadi, sosial, dan bangsa Indonesia serta mulai berupaya melestarikan budaya dalam kehidupan sehari-hari.\r\n','Memahami pentingnya saling menghormati dalam mempromosikan pertukaran budaya dan kolaborasi dalam dunia yang saling terhubung serta menunjukkannya dalam perilaku.');
INSERT INTO `m_sub_elemen` VALUES ('14','2','7','Berkomunikasi antar budaya','Mengenali bahwa diri dan orang lain menggunakan kata, gambar, dan bahasa tubuh yang dapat memiliki makna yang berbeda di lingkungan sekitarnya\r\n','Mendeskripsikan penggunaan kata, tulisan dan bahasa tubuh yang memiliki makna yang berbeda di lingkungan sekitarnya dan dalam suatu budaya tertentu.\r\n','Memahami persamaan dan perbedaan cara komunikasi baik di dalam maupun antar kelompok budaya.\r\n','Mengeksplorasi pengaruh budaya terhadap penggunaan bahasa serta dapat mengenali risiko dalam berkomunikasi antar budaya.\r\n','Menganalisis hubungan antara bahasa, pikiran, dan konteks untuk memahami dan meningkatkan komunikasi antar budaya yang berbeda-beda.');
INSERT INTO `m_sub_elemen` VALUES ('15','2','7','Mempertimbangkan dan menumbuhkan berbagai perspektif','Mengekspresikan pandangannya terhadap topik yang umum dan mendengarkan sudut pandang orang lain yang berbeda dari dirinya dalam lingkungan keluarga dan sekolah\r\n','Mengekspresikan pandangannya terhadap topik yang umum dan dapat mengidentifikasi sudut pandang orang lain. Mendengarkan dan membayangkan sudut pandang orang lain yang berbeda dari dirinya pada situasi di ranah sekolah, keluarga, dan lingkungan sekitar.\r\n','Membandingkan beragam perspektif untuk memahami permasalahan sehari-hari. Membayangkan dan mendeskripsikan situasi komunitas yang berbeda dengan dirinya ke dalam situasi dirinya dalam konteks lokal dan regional.\r\n','Menjelaskan asumsi-asumsi yang mendasari perspektif tertentu. Membayangkan dan mendeskripsikan perasaan serta motivasi komunitas yang berbeda dengan dirinya yang berada dalam situasi yang sulit.\r\n','Menyajikan pandangan yang seimbang mengenai permasalahan yang dapat menimbulkan pertentangan pendapat. Memperlakukan orang lain dan budaya yang berbeda darinya dalam posisi setara dengan diri dan budayanya, serta bersedia memberikan pertolongan ketika orang lain berada dalam situasi sulit.');
INSERT INTO `m_sub_elemen` VALUES ('16','2','8','Refleksi terhadap pengalaman kebinekaan','Menyebutkan apa yang telah dipelajari tentang orang lain dari interaksinya dengan kemajemukan budaya di lingkungan sekolah dan rumah\r\n','Menyebutkan apa yang telah dipelajari tentang orang lain dari interaksinya dengan kemajemukan budaya di lingkungan sekitar.\r\n','Menjelaskan apa yang telah dipelajari dari interaksi dan pengalaman dirinya dalam lingkungan yang beragam.\r\n','Merefleksikan secara kritis gambaran berbagai kelompok budaya yang ditemui dan cara meresponnya.\r\n','Merefleksikan secara kritis dampak dari pengalaman hidup di lingkungan yang beragam terkait dengan perilaku, kepercayaan serta tindakannya terhadap orang lain.');
INSERT INTO `m_sub_elemen` VALUES ('17','2','8','Menghilangkan stereotip dan prasangka','Mengenali perbedaan tiap orang atau kelompok dan menganggapnya sebagai kewajaran\r\n','Mengkonfirmasi dan mengklarifikasi stereotip dan prasangka yang dimilikinya tentang orang atau kelompokdi sekitarnya untuk mendapatkan pemahaman yang lebih baik\r\n','Mengkonfirmasi dan mengklarifikasi stereotip dan prasangka yang dimilikinya tentang orang atau kelompok di sekitarnya untuk mendapatkan pemahaman yang lebih baik serta mengidentifikasi pengaruhnya terhadap individu dan kelompok di lingkungan sekitarnya\r\n','Mengkonfirmasi, mengklarifikasi dan menunjukkan sikapmenolak stereotip serta prasangka tentang gambaran identitas kelompok dan suku bangsa.\r\n','Mengkritik dan menolak stereotip serta prasangka tentang gambaran identitas kelompok dan suku bangsa serta berinisiatif mengajak orang lain untuk menolak stereotip dan prasangka.');
INSERT INTO `m_sub_elemen` VALUES ('18','2','8','Menyelaraskan perbedaan budaya','Mengidentifikasi perbedaan-perbedaan budaya yang konkrit di lingkungan sekitar\r\n','Mengenali bahwa perbedaan budaya mempengaruhi pemahaman antarindividu.\r\n','Mencari titik temu nilai budaya yang beragam untuk menyelesaikan permasalahan bersama.\r\n','Mengkonfirmasi, mengklarifikasi dan menunjukkan sikapmenolak stereotip serta prasangka tentang gambaran identitas kelompok dan suku bangsa.\r\n','Mengetahui tantangan dan keuntungan hidup dalam lingkungan dengan budaya yang beragam, serta memahami pentingnya kerukunan antar budaya dalam kehidupan bersama yang harmonis.');
INSERT INTO `m_sub_elemen` VALUES ('19','2','9','Aktif membangun masyarakat yang inklusif, adil, dan berkelanjutan\r\n','Menjalin pertemanan tanpa memandang perbedaan agama, suku, ras, jenis kelamin, dan perbedaan lainnya, dan mengenal masalah-masalah sosial, ekonomi, dan lingkungan di lingkungan sekitarnya\r\n','Mengidentifikasi cara berkontribusi terhadap lingkungan sekolah, rumah dan lingkungan sekitarnya yang inklusif, adil dan berkelanjutan\r\n','Membandingkan beberapa tindakan dan praktik perbaikan lingkungan sekolah yang inklusif, adil, dan berkelanjutan, dengan mempertimbangkan dampaknya secara jangka panjang terhadap manusia, alam, dan masyarakat\r\n','Mengidentifikasi masalah yang ada di sekitarnya sebagai akibat dari pilihan yang dilakukan oleh manusia, serta dampak masalah tersebut terhadap sistem ekonomi, sosial dan lingkungan, serta mencari solusi yang memperhatikan prinsip-prinsip keadilan terhadap manusia, alam dan masyarakat\r\n','Berinisiatif melakukan suatu tindakan berdasarkan identifikasi masalah untuk mempromosikan keadilan, keamanan ekonomi, menopang ekologi dan demokrasi sambil menghindari kerugian jangka panjang terhadap manusia, alam ataupun masyarakat.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('20','2','9','Berpartisipasi dalam proses pengambilan keputusan bersama','Mengidentifikasi pilihan-pilihan berdasarkan kebutuhan dirinya dan orang lain ketika membuat keputusan\r\n','Berpartisipasi menentukan beberapa pilihan untuk keperluan bersama berdasarkan kriteria sederhana\r\n','Berpartisipasi dalam menentukan kriteria yang disepakati bersama untuk menentukan pilihan dan keputusan untuk kepentingan bersama\r\n','Berpartisipasi dalam menentukan kriteria dan metode yang disepakati bersama untuk menentukan pilihan dan keputusan untuk kepentingan bersama melalui proses bertukar pikiran secara cermat dan terbuka dengan panduan pendidik\r\n','Berpartisipasi menentukan pilihan dan keputusan untuk kepentingan bersama melalui proses bertukar pikiran secara cermat dan terbuka secara mandiri\r\n');
INSERT INTO `m_sub_elemen` VALUES ('21','2','9','Memahami peran individu dalam demokrasi','Mengidentifikasi peran, hak dan kewajiban warga dalam masyarakat demokratis\r\n','Memahami konsep hak dan kewajiban, serta implikasinya terhadap perilakunya.\r\n','Memahami konsep hak dan kewajiban, serta implikasinya terhadap perilakunya. Menggunakan konsep ini untuk menjelaskan perilaku diri dan orang sekitarnya\r\n','Memahami konsep hak dan kewajiban serta implikasinya terhadap ekspresi dan perilakunya. Mulai aktif mengambil sikap dan langkah untuk melindungi hak orang/kelompok lain.\r\n','Memahami konsep hak dan kewajiban, serta implikasinya terhadap ekspresi dan perilakunya. Mulai mencari solusi untuk dilema terkait konsep hak dan kewajibannya.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('22','3','10','Kerja sama','Menerima dan melaksanakan tugas serta peran yang diberikan kelompok dalam sebuah kegiatan bersama.\r\n','Menampilkan tindakan yang sesuai dengan harapan dan tujuan kelompok.\r\n','Menunjukkan ekspektasi (harapan) positif kepada orang lain dalam rangka mencapai tujuan kelompok di lingkungan sekitar (sekolah dan rumah).\r\n','Menyelaraskan tindakan sendiri dengan tindakan orang lain untuk melaksanakan kegiatan dan mencapai tujuan kelompok di lingkungan sekitar, serta memberi semangat kepada orang lain untuk bekerja efektif dan mencapai tujuan bersama.\r\n','Membangun tim dan mengelola kerjasama untuk mencapai tujuan bersama sesuai dengan target yang sudah ditentukan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('23','3','10','Komunikasi untuk mencapai tujuan bersama','Memahami informasi sederhana dari orang lain dan menyampaikan informasi sederhana kepada orang lain menggunakan kata-katanya sendiri.\r\n','Memahami informasi yang disampaikan (ungkapan pikiran, perasaan, dan keprihatinan) orang lain dan menyampaikan informasi secara akurat menggunakan berbagai simbol dan media\r\n','Memahami informasi dari berbagai sumber dan menyampaikan pesan menggunakan berbagai simbol dan media secara efektif kepada orang lain untuk mencapai tujuan bersama\r\n','Memahami informasi, gagasan, emosi, keterampilan dan keprihatinan yang diungkapkan oleh orang lain menggunakan berbagai simbol dan media secara efektif, serta memanfaatkannya untuk meningkatkan kualitas hubungan interpersonal guna mencapai tujuan bersama.\r\n','Aktif menyimak untuk memahami dan menganalisis informasi, gagasan, emosi, keterampilan dan keprihatinan yang disampaikan oleh orang lain dan kelompok menggunakan berbagai simbol dan media secara efektif, serta menggunakan berbagai strategi komunikasi untuk menyelesaikan masalah guna mencapai berbagai tujuan bersama.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('24','3','10','Saling-ketergantungan positif','Mengenali kebutuhan-kebutuhan diri sendiri yang memerlukan orang lain dalam pemenuhannya.\r\n','Menyadari bahwa setiap orang membutuhkan orang lain dalam memenuhi kebutuhannya dan perlunya saling membantu\r\n','Menyadari bahwa meskipun setiap orang memiliki otonominya masing-masing, setiap orang membutuhkan orang lain dalam memenuhi kebutuhannya.\r\n','Mendemonstrasikan kegiatan kelompok yang menunjukkan bahwa anggota kelompok dengan kelebihan dan kekurangannya masing-masing perlu dan dapat saling membantu memenuhi kebutuhan.\r\n','Menyelaraskan kapasitas kelompok agar para anggota kelompok dapat saling membantu satu sama lain memenuhi kebutuhan mereka baik secara individual maupun kolektif.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('25','3','10','Koordinasi Sosial','Melaksanakan aktivitas kelompok sesuai dengan kesepakatan bersama dengan bimbingan, dan saling mengingatkan adanya kesepakatan tersebut.\r\n','Menyadari bahwa dirinya memiliki peran yang berbeda dengan orang lain/temannya, serta mengetahui konsekuensi perannya terhadap ketercapaian tujuan.\r\n','Menyelaraskan tindakannya sesuai dengan perannya dan mempertimbangkan peran orang lain untuk mencapai tujuan bersama.\r\n','Membagi peran dan menyelaraskan tindakan dalam kelompok serta menjaga tindakan agar selaras untuk mencapai tujuan bersama.\r\n','Menyelaraskan dan menjaga tindakan diri dan anggota kelompok agar sesuai antara satu dengan lainnya serta menerima konsekuensi tindakannya dalam rangka mencapai tujuan bersama.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('26','3','11','Tanggap terhadap lingkungan Sosial','Peka dan mengapresiasi orang-orang di lingkungan sekitar, kemudian melakukan tindakan sederhana untuk mengungkapkannya.\r\n','Peka dan mengapresiasi orang-orang di lingkungan sekitar, kemudian melakukan tindakan untuk menjaga keselarasan dalam berelasi dengan orang lain.\r\n','Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan menjaga keselarasan dalam berelasi dengan orang lain.\r\n','Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan berkontribusi sesuai dengan kebutuhan masyarakat.\r\n','Tanggap terhadap lingkungan sosial sesuai dengan tuntutan peran sosialnya dan berkontribusi sesuai dengan kebutuhan masyarakat untuk menghasilkan keadaan yang lebih baik.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('27','3','11','Persepsi sosial','Mengenali berbagai reaksi orang lain di lingkungan sekitar dan penyebabnya.\r\n','Memahami berbagai alasan orang lain menampilkan respon tertentu\r\n','Menerapkan pengetahuan mengenai berbagai reaksi orang lain dan penyebabnya dalam konteks keluarga, sekolah, serta pertemanan dengan sebaya.\r\n','Menggunakan pengetahuan tentang sebab dan alasan orang lain menampilkan reaksi tertentu untuk menentukan tindakan yang tepat agar orang lain menampilkan respon yang diharapkan.\r\n','Melakukan tindakan yang tepat agar orang lain merespon sesuai dengan yang diharapkan dalam rangka penyelesaian pekerjaan dan pencapaian tujuan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('28','3','12','Berbagi','Memberi dan menerima hal yang dianggap berharga dan penting kepada/dari orang-orang di lingkungan sekitar.\r\n','\"\r\nMemberi dan menerima hal yang dianggap penting dan berharga kepada/dari orang-orang di lingkungan sekitar baik yang dikenal maupun tidak dikenal.\"\r\n','Memberi dan menerima hal yang dianggap penting dan berharga kepada/dari orang-orang di lingkungan luas/masyarakat baik yang dikenal maupun tidak dikenal.\r\n','\"\r\nMengupayakan memberi hal yang dianggap penting dan berharga kepada masyarakat yang membutuhkan bantuan di sekitar tempat tinggal\"\r\n','Mengupayakan memberi hal yang dianggap penting dan berharga kepada orang-orang yang membutuhkan di masyarakat yang lebih luas (negara, dunia).\r\n');
INSERT INTO `m_sub_elemen` VALUES ('29','4','13','Mengenali kualitas dan minat diri serta tantangan yang dihadapi','Mengidentifikasi dan menggambarkan kemampuan, prestasi, dan ketertarikannya secara subjektif\r\n','Mengidentifikasi kemampuan, prestasi, dan ketertarikannya serta tantangan yang dihadapi berdasarkan kejadian-kejadian yang dialaminya dalam kehidupan sehari-hari.\r\n','Menggambarkan pengaruh kualitas dirinya terhadap pelaksanaan dan hasil belajar; serta mengidentifikasi kemampuan yang ingin dikembangkan dengan mempertimbangkan tantangan yang dihadapinya dan umpan balik dari orang dewasa\r\n','Membuat penilaian yang realistis terhadap kemampuan dan minat , serta prioritas pengembangan diri berdasarkan pengalaman belajar dan aktivitas lain yang dilakukannya.\r\n','Mengidentifikasi kekuatan dan tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya di masa depan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('30','4','13','Mengembangkan refleksi diri','Melakukan refleksi untuk mengidentifikasi kekuatan dan kelemahan, serta prestasi dirinya.\r\n','Melakukan refleksi untuk mengidentifikasi kekuatan, kelemahan, dan prestasi dirinya, serta situasi yang dapat mendukung dan menghambat pembelajaran dan pengembangan dirinya\r\n','Melakukan refleksi untuk mengidentifikasi faktor-faktor di dalam maupun di luar dirinya yang dapat mendukung/menghambatnya dalam belajar dan mengembangkan diri; serta mengidentifikasi cara-cara untuk mengatasi kekurangannya.\r\n','Memonitor kemajuan belajar yang dicapai serta memprediksi tantangan pribadi dan akademik yang akan muncul berlandaskan pada pengalamannya untuk mempertimbangkan strategi belajar yang sesuai.\r\n','Melakukan refleksi terhadap umpan balik dari teman, guru, dan orang dewasa lainnya, serta informasi-informasi karir yang akan dipilihnya untuk menganalisis karakteristik dan keterampilan yang dibutuhkan dalam menunjang atau menghambat karirnya di masa depan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('31','4','14','Regulasi emosi','Mengidentifikasi perbedaan emosi yang dirasakannya dan situasi-situasi yang menyebabkan-nya; serta mengekspresi-kan secara wajar\r\n','Mengetahui adanya pengaruh orang lain, situasi, dan peristiwa yang terjadi terhadap emosi yang dirasakannya; serta berupaya untuk mengekspresikan emosi secara tepat dengan mempertimbangkan perasaan dan kebutuhan orang lain disekitarnya\r\n','Memahami perbedaan emosi yang dirasakan dan dampaknya terhadap proses belajar dan interaksinya dengan orang lain; serta mencoba cara-cara yang sesuai untuk mengelola emosi agar dapat menunjang aktivitas belajar dan interaksinya dengan orang lain.\r\n','Memahami dan memprediksi konsekuensi dari emosi dan pengekspresiannya dan menyusun langkah-langkah untuk mengelola emosinya dalam pelaksanaan belajar dan berinteraksi dengan orang lain.\r\n','Mengendalikan dan menyesuaikan emosi yang dirasakannya secara tepat ketika menghadapi situasi yang menantang dan menekan pada konteks belajar, relasi, dan pekerjaan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('32','4','14','Penetapan tujuan belajar, prestasi, dan pengembangan diri serta rencana strategis untuk mencapainya','Menetapkan target belajar dan merencanakan waktu dan tindakan belajar yang akan dilakukannya.\r\n','Menjelaskan pentingnya memiliki tujuan dan berkomitmen dalam mencapainya serta mengeksplorasi langkah-langkah yang sesuai untuk mencapainya\r\n','Menilai faktor-faktor (kekuatan dan kelemahan) yang ada pada dirinya dalam upaya mencapai tujuan belajar, prestasi, dan pengembangan dirinya serta mencoba berbagai strategi untuk mencapainya.\r\n','Merancang strategi yang sesuai untuk menunjang pencapaian tujuan belajar, prestasi, dan pengembangan diri dengan mempertimbangkan kekuatan dan kelemahan dirinya, serta situasi yang dihadapi.\r\n','Mengevaluasi efektivitas strategi pembelajaran digunakannya, serta menetapkan tujuan belajar, prestasi, dan pengembangan diri secara spesifik dan merancang strategi yang sesuai untuk menghadapi tantangan-tantangan yang akan dihadapi pada konteks pembelajaran, sosial dan pekerjaan yang akan dipilihnya di masa depan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('33','4','14','Menunjukkan inisiatif dan bekerja secara mandiri','Berinisiatif untuk mengerjakan tugas-tugas rutin secara mandiri dibawah pengawasan dan dukungan orang dewasa\r\n','Mempertimbangkan, memilih dan mengadopsi berbagai strategi dan mengidentifikasi sumber bantuan yang diperlukan serta berinisiatif menjalankannya untuk mendapatkan hasil belajar yang diinginkan.\r\n','Memahami arti penting bekerja secara mandiri serta inisiatif untuk melakukannya dalam menunjang pembelajaran dan pengembangan dirinya\r\n','Mengkritisi efektivitas dirinya dalam bekerja secara mandiri dengan mengidentifikasi hal-hal yang menunjang maupun menghambat dalam mencapai tujuan.\r\n','Menentukan prioritas pribadi, berinisiatif mencari dan mengembangkan pengetahuan dan keterampilan yang spesifik sesuai tujuan di masa depan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('34','4','14','Mengembangkan pengendalian dan disiplin diri','Melaksanakan kegiatan belajar di kelas dan menyelesaikan tugas-tugas dalam waktu yang telah disepakati.\r\n','Menjelaskan pentingnya mengatur diri secara mandiri dan mulai menjalankan kegiatan dan tugas yang telah sepakati secara mandiri\r\n','Mengidentifikasi faktor-faktor yang dapat mempengaruhi kemampuan dalam mengelola diri dalam pelaksanaan aktivitas belajar dan pengembangan dirinya.\r\n','Berkomitmen dan menjaga konsistensi pencapaian tujuan yang telah direncanakannya untuk mencapai tujuan belajar dan pengembangan diri yang diharapkannya\r\n','Melakukan tindakan-tindakan secara konsisten guna mencapai tujuan karir dan pengembangan dirinya di masa depan, serta berusaha mencari dan melakukan alternatif tindakan lain yang dapat dilakukan ketika menemui hambatan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('35','4','14','Percaya diri, tangguh (resilient), dan adaptif','Berani mencoba dan adaptif menghadapi situasi baru serta bertahan mengerjakan tugas-tugas yang disepakati hingga tuntas\r\n','Tetap bertahan mengerjakan tugas ketika dihadapkan dengan tantangan dan berusaha menyesuaikan strateginya ketika upaya sebelumnya tidak berhasil.\r\n','Menyusun, menyesuaikan, dan mengujicobakan berbagai strategi dan cara kerjanya untuk membantu dirinya dalam penyelesaian tugas yang menantang\r\n','Membuat rencana baru dengan mengadaptasi, dan memodifikasi strategi yang sudah dibuat ketika upaya sebelumnya tidak berhasil, serta menjalankan kembali tugasnya dengan keyakinan baru.\r\n','Menyesuaikan dan mulai menjalankan rencana dan strategi pengembangan dirinya dengan mempertimbangkan minat dan tuntutan pada konteks belajar maupun pekerjaan yang akan dijalaninya di masa depan, serta berusaha untuk mengatasi tantangan-tantangan yang ditemui.\r\n\r\n\r\nProfil Pelajar Pancasila\r\nPilih Fase\r\n\r\n\r\nFase E\r\n\r\nDimensi & Elemen\r\nBeriman, Bertakwa Kepada Tuhan Yang Maha Esa, dan Berakhlak Mulia\r\nBerkebinekaan Global\r\nBergotong-Royong\r\nMandiri\r\nElemen\r\n\r\n\r\nPemahaman diri dan situasi yang dihadapi\r\n\r\n\r\nRegulasi Diri\r\n\r\nBernalar Kritis\r\nKreatif\"\r\n');
INSERT INTO `m_sub_elemen` VALUES ('36','5','15','Mengajukan pertanyaan','Mengajukan pertanyaan untuk menjawab keingintahuannya dan untuk mengidentifikasi suatu permasalahan mengenai dirinya dan lingkungan sekitarnya.\r\n','Mengajukan pertanyaan untuk mengidentifikasi suatu permasalahan dan mengkonfirmasi pemahaman terhadap suatu permasalahan mengenai dirinya dan lingkungan sekitarnya.\r\n','Mengajukan pertanyaan untuk membandingkan berbagai informasi dan untuk menambah pengetahuannya.\r\n','Mengajukan pertanyaan untuk klarifikasi dan interpretasi informasi, serta mencari tahu penyebab dan konsekuensi dari informasi tersebut.\r\n','Mengajukan pertanyaan untuk menganalisis secara kritis permasalahan yang kompleks dan abstrak.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('37','5','15','Mengidentifikasi, mengklarifikasi, dan mengolah informasi dan gagasan','Mengidentifikasi dan mengolah informasi dan gagasan\r\n','Mengumpulkan, mengklasifikasikan, membandingkan dan memilih informasi dan gagasan dari berbagai sumber.\r\n','Mengumpulkan, mengklasifikasikan, membandingkan, dan memilih informasi dari berbagai sumber, serta memperjelas informasi dengan bimbingan orang dewasa.\r\n','Mengidentifikasi, mengklarifikasi, dan menganalisis informasi yang relevan serta memprioritaskan beberapa gagasan tertentu.\r\n','Secara kritis mengklarifikasi serta menganalisis gagasan dan informasi yang kompleks dan abstrak dari berbagai sumber. Memprioritaskan suatu gagasan yang paling relevan dari hasil klarifikasi dan analisis.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('38','5','16','Menganalisis dan mengevaluasi penalaran dan prosedurnya','Melakukan penalaran konkrit dan memberikan alasan dalam menyelesaikan masalah dan mengambil keputusan\r\n','Menjelaskan alasan yang relevan dalam penyelesaian masalah dan pengambilan keputusan\r\n','Menjelaskan alasan yang relevan dan akurat dalam penyelesaian masalah dan pengambilan keputusan\r\n','Membuktikan penalaran dengan berbagai argumen dalam mengambil suatu simpulan atau keputusan.\r\n','Menganalisis dan mengevaluasi penalaran yang digunakannya dalam menemukan dan mencari solusi serta mengambil keputusan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('39','5','17','Merefleksi dan mengevaluasi pemikirannya sendiri','Menyampaikan apa yang sedang dipikirkan secara terperinci\r\n','Menyampaikan apa yang sedang dipikirkan dan menjelaskan alasan dari hal yang dipikirkan\r\n','Memberikan alasan dari hal yang dipikirkan, serta menyadari kemungkinan adanya bias pada pemikirannya sendiri\r\n\r\nProfil Pelajar Pancasila\r\nPilih Fase\r\n\r\n\r\nFase C\r\n\r\nDimensi & Elemen\"\r\n','Menjelaskan asumsi yang digunakan, menyadari kecenderungan dan konsekuensi bias pada pemikirannya, serta berusaha mempertimbangkan perspektif yang berbeda.\r\n','Menjelaskan alasan untuk mendukung pemikirannya dan memikirkan pandangan yang mungkin berlawanan dengan pemikirannya dan mengubah pemikirannya jika diperlukan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('40','6','18','Menghasilkan gagasan yang orisinal','Menggabungkan beberapa gagasan menjadi ide atau gagasan imajinatif yang bermakna untuk mengekspresikan pikiran dan/atau perasaannya.\r\n','Memunculkan gagasan imajinatif baru yang bermakna dari beberapa gagasan yang berbeda sebagai ekspresi pikiran dan/atau perasaannya.\r\n','Mengembangkan gagasan yang ia miliki untuk membuat kombinasi hal yang baru dan imajinatif untuk mengekspresikan pikiran dan/atau perasaannya.\r\n','Menghubungkan gagasan yang ia miliki dengan informasi atau gagasan baru untuk menghasilkan kombinasi gagasan baru dan imajinatif untuk mengekspresikan pikiran dan/atau perasaannya.\r\n','Menghasilkan gagasan yang beragam untuk mengekspresikan pikiran dan/atau perasaannya, menilai gagasannya, serta memikirkan segala risikonya dengan mempertimbangkan banyak perspektif seperti etika dan nilai kemanusiaan ketika gagasannya direalisasikan.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('41','6','19','Menghasilkan karya dan tindakan yang orisinal','Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/atau tindakan serta mengapresiasi karya dan tindakan yang dihasilkan\r\n','Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya sesuai dengan minat dan kesukaannya dalam bentuk karya dan/atau tindakan serta mengapresiasi karya dan tindakan yang dihasilkan\r\n','Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya sesuai dengan minat dan kesukaannya dalam bentuk karya dan/atau tindakan serta mengapresiasi dan mengkritik karya dan tindakan yang dihasilkan\r\n','Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/atau tindakan, serta mengevaluasinya dan mempertimbangkan dampaknya bagi orang lain\r\n','Mengeksplorasi dan mengekspresikan pikiran dan/atau perasaannya dalam bentuk karya dan/atau tindakan, serta mengevaluasinya dan mempertimbangkan dampak dan risikonya bagi diri dan lingkungannya dengan menggunakan berbagai perspektif.\r\n');
INSERT INTO `m_sub_elemen` VALUES ('42','6','20','Memiliki keluwesan berpikir dalam mencari alternatif solusi permasalahan','Mengidentifikasi gagasan-gagasan kreatif untuk menghadapi situasi dan permasalahan.\r\n','Membandingkan gagasan-gagasan kreatif untuk menghadapi situasi dan permasalahan.\r\n','berupaya mencari solusi alternatif saat pendekatan yang diambil tidak berhasil berdasarkan identifikasi terhadap situasi\r\n','Menghasilkan solusi alternatif dengan mengadaptasi berbagai gagasan dan umpan balik untuk menghadapi situasi dan permasalahan\r\n','Bereksperimen dengan berbagai pilihan secara kreatif untuk memodifikasi gagasan sesuai dengan perubahan situasi.\r\n');

/*---------------------------------------------------------------
  TABLE: `mapel_rapor`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `mapel_rapor`;
CREATE TABLE `mapel_rapor` (
  `idm` int(11) NOT NULL AUTO_INCREMENT,
  `urut` int(11) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  `tingkat` int(11) DEFAULT NULL,
  `pk` varchar(50) DEFAULT NULL,
  `kelompok` varchar(2) DEFAULT NULL,
  `kkm` int(11) DEFAULT NULL,
  `sikap` varchar(11) DEFAULT NULL,
  `kurikulum` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idm`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `mata_pelajaran`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `mata_pelajaran`;
CREATE TABLE `mata_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nama_mapel` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `materi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `materi`;
CREATE TABLE `materi` (
  `id_materi` int(255) NOT NULL AUTO_INCREMENT,
  `id_guru` int(255) DEFAULT NULL,
  `kelas` text DEFAULT NULL,
  `mapel` varchar(255) DEFAULT NULL,
  `judul` text DEFAULT NULL,
  `materi` longtext DEFAULT NULL,
  `quiz` varchar(50) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `tgl_mulai` datetime NOT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `tgl` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_materi`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
INSERT INTO `materi` VALUES   ('1','3','a:1:{i:0;s:10:\"VIII SAINS\";}','PP','perumusan pancasila','<p>perumusan pancasila sidang BPUPK</p>',NULL,NULL,'2024-07-13 17:27:00','','2024-07-13 16:27:51',NULL);
INSERT INTO `materi` VALUES ('2','1','a:3:{i:0;s:10:\"VIII SAINS\";i:1;s:14:\"VIII DIGITAL 1\";i:2;s:13:\"VII DIGITAL 2\";}','PP','xfdg','<p>sdsffghgjghjhnmbnmb</p>',NULL,'data kepsek.docx','2024-07-15 08:25:00','','2024-07-15 07:25:54',NULL);

/*---------------------------------------------------------------
  TABLE: `nilai`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `id_ujian` int(11) DEFAULT NULL,
  `id_bank` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `ujian_mulai` varchar(20) DEFAULT NULL,
  `ujian_berlangsung` varchar(20) DEFAULT NULL,
  `ujian_selesai` varchar(20) DEFAULT NULL,
  `jml_benar` int(11) DEFAULT NULL,
  `benar_esai` int(11) NOT NULL DEFAULT 0,
  `benar_multi` int(11) NOT NULL DEFAULT 0,
  `benar_bs` int(11) NOT NULL DEFAULT 0,
  `benar_urut` int(11) NOT NULL DEFAULT 0,
  `skor` varchar(255) DEFAULT NULL,
  `skor_esai` varchar(255) DEFAULT NULL,
  `skor_multi` varchar(255) DEFAULT NULL,
  `skor_bs` varchar(255) DEFAULT NULL,
  `skor_urut` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `makskor` int(11) DEFAULT NULL,
  `nilai` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `ipaddress` varchar(20) DEFAULT NULL,
  `hasil` int(11) DEFAULT NULL,
  `jawaban_pg` text DEFAULT NULL,
  `jawaban_esai` longtext DEFAULT NULL,
  `jawaban_multi` text DEFAULT NULL,
  `jawaban_bs` text DEFAULT NULL,
  `jawaban_urut` text DEFAULT NULL,
  `nilai_esai` int(11) DEFAULT NULL,
  `nilai_esai2` text DEFAULT NULL,
  `online` int(11) NOT NULL DEFAULT 0,
  `id_soal` longtext DEFAULT NULL,
  `id_opsi` longtext DEFAULT NULL,
  `id_esai` text DEFAULT NULL,
  `blok` int(11) NOT NULL DEFAULT 0,
  `server` varchar(50) DEFAULT NULL,
  `browser` int(11) DEFAULT 0,
  `jenis_browser` varchar(50) DEFAULT NULL,
  `jumjawab` varchar(11) DEFAULT NULL,
  `jumsoal` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `nilai_formatif`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_formatif`;
CREATE TABLE `nilai_formatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  `tinggi` longtext DEFAULT NULL,
  `rendah` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `nilai_harian`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_harian`;
CREATE TABLE `nilai_harian` (
  `idn` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `idsiswa` varchar(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` int(11) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL,
  `kd` varchar(14) DEFAULT NULL,
  `ki` varchar(11) DEFAULT NULL,
  `kuri` varchar(12) DEFAULT NULL,
  `guru` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idn`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `nilai_harian` VALUES   ('1','2024-07-15','1','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('2','2024-07-15','2','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('3','2024-07-15','3','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('4','2024-07-15','4','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('5','2024-07-15','5','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('6','2024-07-15','6','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('7','2024-07-15','7','VIII SAINS','2','99','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('8','2024-07-15','8','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('9','2024-07-15','9','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('10','2024-07-15','10','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('11','2024-07-15','11','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('12','2024-07-15','12','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('13','2024-07-15','13','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('14','2024-07-15','14','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('15','2024-07-15','15','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('16','2024-07-15','16','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('17','2024-07-15','17','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('18','2024-07-15','18','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('19','2024-07-15','19','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('20','2024-07-15','20','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('21','2024-07-15','21','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('22','2024-07-15','22','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('23','2024-07-15','23','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('24','2024-07-15','24','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('25','2024-07-15','25','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('26','2024-07-15','26','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('27','2024-07-15','27','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('28','2024-07-15','28','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('29','2024-07-15','29','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('30','2024-07-15','30','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('31','2024-07-15','31','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('32','2024-07-15','32','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('33','2024-07-16','1','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('34','2024-07-16','2','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('35','2024-07-16','3','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('36','2024-07-16','4','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('37','2024-07-16','5','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('38','2024-07-16','6','VIII SAINS','2','90','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('39','2024-07-16','7','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('40','2024-07-16','8','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('41','2024-07-16','9','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('42','2024-07-16','10','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('43','2024-07-16','11','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('44','2024-07-16','12','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('45','2024-07-16','13','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('46','2024-07-16','14','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('47','2024-07-16','15','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('48','2024-07-16','16','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('49','2024-07-16','17','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('50','2024-07-16','18','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('51','2024-07-16','19','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('52','2024-07-16','20','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('53','2024-07-16','21','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('54','2024-07-16','22','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('55','2024-07-16','23','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('56','2024-07-16','24','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('57','2024-07-16','25','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('58','2024-07-16','26','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('59','2024-07-16','27','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('60','2024-07-16','28','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('61','2024-07-16','29','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('62','2024-07-16','30','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('63','2024-07-16','31','VIII SAINS','2','0','1','M','2','3');
INSERT INTO `nilai_harian` VALUES ('64','2024-07-16','32','VIII SAINS','2','0','1','M','2','3');

/*---------------------------------------------------------------
  TABLE: `nilai_proses`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_proses`;
CREATE TABLE `nilai_proses` (
  `idpros` int(11) NOT NULL AUTO_INCREMENT,
  `idsiswa` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `proyek_ke` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpros`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `nilai_proyek`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_proyek`;
CREATE TABLE `nilai_proyek` (
  `idn` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  `idsiswa` int(11) DEFAULT NULL,
  `idproyek` int(11) DEFAULT NULL,
  `proyek` int(11) DEFAULT NULL,
  `nilai` varchar(5) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  PRIMARY KEY (`idn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `nilai_rapor`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_rapor`;
CREATE TABLE `nilai_rapor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  `nilai3` int(11) DEFAULT NULL,
  `desmin3` text DEFAULT NULL,
  `desmax3` text DEFAULT NULL,
  `nilai4` int(11) DEFAULT NULL,
  `desmin4` text DEFAULT NULL,
  `desmax4` text DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `nilai_sumatif`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai_sumatif`;
CREATE TABLE `nilai_sumatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` varchar(50) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `khp` varchar(50) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `pesan_terkirim`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `pesan_terkirim`;
CREATE TABLE `pesan_terkirim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idsiswa` varchar(11) DEFAULT NULL,
  `idpeg` varchar(11) DEFAULT NULL,
  `waktu` varchar(50) DEFAULT NULL,
  `ket` varchar(5) DEFAULT NULL,
  `nowa` varchar(14) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `sender` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `peskul`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `peskul`;
CREATE TABLE `peskul` (
  `idp` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `eskul` varchar(50) DEFAULT NULL,
  `guru` varchar(16) DEFAULT NULL,
  `nilai` varchar(50) DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `smt` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`idp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `proyek`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `proyek`;
CREATE TABLE `proyek` (
  `idp` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  `p_proyek` int(11) NOT NULL,
  `p_dimensi` int(11) NOT NULL,
  `p_elemen` int(11) NOT NULL,
  `p_sub` int(11) NOT NULL,
  `semester` int(11) DEFAULT NULL,
  PRIMARY KEY (`idp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `reset`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `reset`;
CREATE TABLE `reset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idsiswa` int(11) DEFAULT NULL,
  `idnilai` int(11) DEFAULT NULL,
  `idujian` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `ruang`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ruang`;
CREATE TABLE `ruang` (
  `kode_ruang` varchar(10) NOT NULL,
  `keterangan` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_ruang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `server`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `server`;
CREATE TABLE `server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_server` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `server` VALUES   ('1','192.168.99.18');

/*---------------------------------------------------------------
  TABLE: `sesi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sesi`;
CREATE TABLE `sesi` (
  `kode_sesi` varchar(10) NOT NULL,
  `nama_sesi` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_sesi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `sesi` VALUES   ('1','1');

/*---------------------------------------------------------------
  TABLE: `sinkron`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sinkron`;
CREATE TABLE `sinkron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `npsn` varchar(50) DEFAULT NULL,
  `waktu` varchar(50) DEFAULT NULL,
  `sts` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*---------------------------------------------------------------
  TABLE: `siswa`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `siswa`;
CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL AUTO_INCREMENT,
  `no_peserta` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `fase` varchar(5) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `jk` varchar(50) DEFAULT NULL,
  `server` varchar(50) DEFAULT NULL,
  `sesi` int(11) DEFAULT 1,
  `ruang` varchar(50) DEFAULT NULL,
  `online` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(100) DEFAULT NULL,
  `nowa` varchar(14) DEFAULT NULL,
  `sts` int(11) NOT NULL DEFAULT 0,
  `idjari` varchar(11) DEFAULT NULL,
  `sakit` int(11) NOT NULL DEFAULT 0,
  `izin` int(11) NOT NULL DEFAULT 0,
  `alpha` int(11) NOT NULL DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `lulus` int(11) NOT NULL DEFAULT 0,
  `prestasi` text DEFAULT NULL,
  `tingkat` text DEFAULT NULL,
  `juara` text DEFAULT NULL,
  `t_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `desa` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `ayah` varchar(50) DEFAULT NULL,
  `pek_ayah` varchar(50) DEFAULT NULL,
  `ibu` varchar(50) DEFAULT NULL,
  `pek_ibu` varchar(50) DEFAULT NULL,
  `stskel` varchar(50) DEFAULT NULL,
  `anakke` varchar(11) DEFAULT NULL,
  `asal_sek` varchar(50) DEFAULT NULL,
  `dikelas` varchar(50) DEFAULT NULL,
  `tgl_terima` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_siswa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `soal`;
CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT NULL,
  `nomor` int(11) DEFAULT NULL,
  `soal` longtext DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `opsi` int(11) DEFAULT NULL,
  `pilA` longtext DEFAULT NULL,
  `pilB` longtext DEFAULT NULL,
  `pilC` longtext DEFAULT NULL,
  `pilD` longtext DEFAULT NULL,
  `pilE` longtext DEFAULT NULL,
  `perA` text DEFAULT NULL,
  `perB` text DEFAULT NULL,
  `perC` text DEFAULT NULL,
  `perD` text DEFAULT NULL,
  `perE` text DEFAULT NULL,
  `jawaban` text DEFAULT NULL,
  `file` longtext DEFAULT NULL,
  `file1` mediumtext DEFAULT NULL,
  `fileA` mediumtext DEFAULT NULL,
  `fileB` mediumtext DEFAULT NULL,
  `fileC` mediumtext DEFAULT NULL,
  `fileD` mediumtext DEFAULT NULL,
  `fileE` mediumtext DEFAULT NULL,
  `ket` text DEFAULT NULL,
  `sts` int(11) DEFAULT 0,
  `max_skor` int(11) DEFAULT 1,
  `jenisjawab` varchar(50) DEFAULT NULL,
  `panjang` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_soal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*---------------------------------------------------------------
  TABLE: `sosial`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sosial`;
CREATE TABLE `sosial` (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` varchar(11) DEFAULT NULL,
  `guru` varchar(11) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `ket1` text DEFAULT NULL,
  `ket2` text DEFAULT NULL,
  `pred` varchar(5) DEFAULT NULL,
  `smt` int(11) DEFAULT NULL,
  `tahun` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`ids`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `spiritual`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `spiritual`;
CREATE TABLE `spiritual` (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  `mapel` varchar(11) DEFAULT NULL,
  `guru` varchar(11) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `ket1` text DEFAULT NULL,
  `ket2` text DEFAULT NULL,
  `pred` varchar(5) DEFAULT NULL,
  `smt` int(11) DEFAULT NULL,
  `tahun` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`ids`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `status`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `status` VALUES   ('1','1');

/*---------------------------------------------------------------
  TABLE: `temp_file`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_file`;
CREATE TABLE `temp_file` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT 0,
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*---------------------------------------------------------------
  TABLE: `temp_finger`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_finger`;
CREATE TABLE `temp_finger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(50) DEFAULT NULL,
  `idjari` int(11) DEFAULT NULL,
  `serial` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `temp_pil`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_pil`;
CREATE TABLE `temp_pil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbank` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `temp_pil` VALUES   ('1','29','1');
INSERT INTO `temp_pil` VALUES ('2','29','2');
INSERT INTO `temp_pil` VALUES ('3','29','3');
INSERT INTO `temp_pil` VALUES ('4','29','4');
INSERT INTO `temp_pil` VALUES ('5','29','5');
INSERT INTO `temp_pil` VALUES ('6','29','6');
INSERT INTO `temp_pil` VALUES ('7','29','7');
INSERT INTO `temp_pil` VALUES ('8','29','8');
INSERT INTO `temp_pil` VALUES ('9','29','9');
INSERT INTO `temp_pil` VALUES ('10','29','10');
INSERT INTO `temp_pil` VALUES ('11','29','11');
INSERT INTO `temp_pil` VALUES ('12','29','12');
INSERT INTO `temp_pil` VALUES ('13','29','13');
INSERT INTO `temp_pil` VALUES ('14','29','14');
INSERT INTO `temp_pil` VALUES ('15','29','15');
INSERT INTO `temp_pil` VALUES ('16','29','16');
INSERT INTO `temp_pil` VALUES ('17','29','17');
INSERT INTO `temp_pil` VALUES ('18','31','1');
INSERT INTO `temp_pil` VALUES ('19','31','2');
INSERT INTO `temp_pil` VALUES ('20','31','3');
INSERT INTO `temp_pil` VALUES ('21','31','4');
INSERT INTO `temp_pil` VALUES ('22','31','5');
INSERT INTO `temp_pil` VALUES ('23','31','6');
INSERT INTO `temp_pil` VALUES ('24','31','7');
INSERT INTO `temp_pil` VALUES ('25','31','8');
INSERT INTO `temp_pil` VALUES ('26','31','9');
INSERT INTO `temp_pil` VALUES ('27','31','10');
INSERT INTO `temp_pil` VALUES ('28','31','11');
INSERT INTO `temp_pil` VALUES ('29','31','12');
INSERT INTO `temp_pil` VALUES ('30','31','13');
INSERT INTO `temp_pil` VALUES ('31','31','14');
INSERT INTO `temp_pil` VALUES ('32','31','15');
INSERT INTO `temp_pil` VALUES ('33','31','16');
INSERT INTO `temp_pil` VALUES ('34','31','17');
INSERT INTO `temp_pil` VALUES ('35','31','18');
INSERT INTO `temp_pil` VALUES ('36','31','19');
INSERT INTO `temp_pil` VALUES ('37','31','20');
INSERT INTO `temp_pil` VALUES ('38','31','21');
INSERT INTO `temp_pil` VALUES ('39','31','22');
INSERT INTO `temp_pil` VALUES ('40','32','1');
INSERT INTO `temp_pil` VALUES ('41','32','2');
INSERT INTO `temp_pil` VALUES ('42','32','3');
INSERT INTO `temp_pil` VALUES ('43','32','4');
INSERT INTO `temp_pil` VALUES ('44','32','5');
INSERT INTO `temp_pil` VALUES ('45','32','6');
INSERT INTO `temp_pil` VALUES ('46','32','7');
INSERT INTO `temp_pil` VALUES ('47','32','8');
INSERT INTO `temp_pil` VALUES ('48','32','9');
INSERT INTO `temp_pil` VALUES ('49','32','10');
INSERT INTO `temp_pil` VALUES ('50','32','11');
INSERT INTO `temp_pil` VALUES ('51','32','12');
INSERT INTO `temp_pil` VALUES ('52','32','13');
INSERT INTO `temp_pil` VALUES ('53','32','14');
INSERT INTO `temp_pil` VALUES ('54','32','15');
INSERT INTO `temp_pil` VALUES ('55','32','1');
INSERT INTO `temp_pil` VALUES ('56','32','2');
INSERT INTO `temp_pil` VALUES ('57','32','3');
INSERT INTO `temp_pil` VALUES ('58','32','4');
INSERT INTO `temp_pil` VALUES ('59','32','5');
INSERT INTO `temp_pil` VALUES ('60','32','6');
INSERT INTO `temp_pil` VALUES ('61','32','7');
INSERT INTO `temp_pil` VALUES ('62','32','8');
INSERT INTO `temp_pil` VALUES ('63','32','9');
INSERT INTO `temp_pil` VALUES ('64','32','10');
INSERT INTO `temp_pil` VALUES ('65','32','11');
INSERT INTO `temp_pil` VALUES ('66','32','12');
INSERT INTO `temp_pil` VALUES ('67','32','13');
INSERT INTO `temp_pil` VALUES ('68','32','14');
INSERT INTO `temp_pil` VALUES ('69','32','15');
INSERT INTO `temp_pil` VALUES ('70','32','1');
INSERT INTO `temp_pil` VALUES ('71','32','2');
INSERT INTO `temp_pil` VALUES ('72','32','3');
INSERT INTO `temp_pil` VALUES ('73','32','4');
INSERT INTO `temp_pil` VALUES ('74','32','5');
INSERT INTO `temp_pil` VALUES ('75','32','6');
INSERT INTO `temp_pil` VALUES ('76','32','7');
INSERT INTO `temp_pil` VALUES ('77','32','8');
INSERT INTO `temp_pil` VALUES ('78','32','9');
INSERT INTO `temp_pil` VALUES ('79','32','10');
INSERT INTO `temp_pil` VALUES ('80','32','11');
INSERT INTO `temp_pil` VALUES ('81','32','12');
INSERT INTO `temp_pil` VALUES ('82','32','13');
INSERT INTO `temp_pil` VALUES ('83','32','14');
INSERT INTO `temp_pil` VALUES ('84','32','15');
INSERT INTO `temp_pil` VALUES ('85','32','1');
INSERT INTO `temp_pil` VALUES ('86','32','2');
INSERT INTO `temp_pil` VALUES ('87','32','3');
INSERT INTO `temp_pil` VALUES ('88','32','4');
INSERT INTO `temp_pil` VALUES ('89','32','5');
INSERT INTO `temp_pil` VALUES ('90','32','6');
INSERT INTO `temp_pil` VALUES ('91','32','7');
INSERT INTO `temp_pil` VALUES ('92','32','8');
INSERT INTO `temp_pil` VALUES ('93','32','9');
INSERT INTO `temp_pil` VALUES ('94','32','10');
INSERT INTO `temp_pil` VALUES ('95','32','11');
INSERT INTO `temp_pil` VALUES ('96','32','12');
INSERT INTO `temp_pil` VALUES ('97','32','13');
INSERT INTO `temp_pil` VALUES ('98','32','14');
INSERT INTO `temp_pil` VALUES ('99','32','15');
INSERT INTO `temp_pil` VALUES ('100','32','1');
INSERT INTO `temp_pil` VALUES ('101','32','2');
INSERT INTO `temp_pil` VALUES ('102','32','3');
INSERT INTO `temp_pil` VALUES ('103','32','4');
INSERT INTO `temp_pil` VALUES ('104','32','5');
INSERT INTO `temp_pil` VALUES ('105','32','6');
INSERT INTO `temp_pil` VALUES ('106','32','7');
INSERT INTO `temp_pil` VALUES ('107','32','8');
INSERT INTO `temp_pil` VALUES ('108','32','9');
INSERT INTO `temp_pil` VALUES ('109','32','10');
INSERT INTO `temp_pil` VALUES ('110','32','11');
INSERT INTO `temp_pil` VALUES ('111','32','12');
INSERT INTO `temp_pil` VALUES ('112','32','13');
INSERT INTO `temp_pil` VALUES ('113','32','14');
INSERT INTO `temp_pil` VALUES ('114','32','15');
INSERT INTO `temp_pil` VALUES ('115','33','1');
INSERT INTO `temp_pil` VALUES ('116','33','2');
INSERT INTO `temp_pil` VALUES ('117','33','3');
INSERT INTO `temp_pil` VALUES ('118','33','4');
INSERT INTO `temp_pil` VALUES ('119','33','5');
INSERT INTO `temp_pil` VALUES ('120','33','6');
INSERT INTO `temp_pil` VALUES ('121','33','7');
INSERT INTO `temp_pil` VALUES ('122','33','8');
INSERT INTO `temp_pil` VALUES ('123','33','9');
INSERT INTO `temp_pil` VALUES ('124','33','10');
INSERT INTO `temp_pil` VALUES ('125','33','11');
INSERT INTO `temp_pil` VALUES ('126','33','12');
INSERT INTO `temp_pil` VALUES ('127','33','13');
INSERT INTO `temp_pil` VALUES ('128','33','14');
INSERT INTO `temp_pil` VALUES ('129','33','15');
INSERT INTO `temp_pil` VALUES ('130','34','1');
INSERT INTO `temp_pil` VALUES ('131','34','2');
INSERT INTO `temp_pil` VALUES ('132','34','3');
INSERT INTO `temp_pil` VALUES ('133','34','4');
INSERT INTO `temp_pil` VALUES ('134','34','5');
INSERT INTO `temp_pil` VALUES ('135','34','6');
INSERT INTO `temp_pil` VALUES ('136','34','7');
INSERT INTO `temp_pil` VALUES ('137','34','8');
INSERT INTO `temp_pil` VALUES ('138','34','9');
INSERT INTO `temp_pil` VALUES ('139','34','10');
INSERT INTO `temp_pil` VALUES ('140','34','11');
INSERT INTO `temp_pil` VALUES ('141','34','12');
INSERT INTO `temp_pil` VALUES ('142','34','13');
INSERT INTO `temp_pil` VALUES ('143','34','14');
INSERT INTO `temp_pil` VALUES ('144','34','15');
INSERT INTO `temp_pil` VALUES ('145','34','16');
INSERT INTO `temp_pil` VALUES ('146','34','17');
INSERT INTO `temp_pil` VALUES ('147','34','18');
INSERT INTO `temp_pil` VALUES ('148','34','19');
INSERT INTO `temp_pil` VALUES ('149','34','20');
INSERT INTO `temp_pil` VALUES ('150','34','21');
INSERT INTO `temp_pil` VALUES ('151','34','22');
INSERT INTO `temp_pil` VALUES ('152','34','23');
INSERT INTO `temp_pil` VALUES ('153','34','24');
INSERT INTO `temp_pil` VALUES ('154','34','25');
INSERT INTO `temp_pil` VALUES ('155','34','26');
INSERT INTO `temp_pil` VALUES ('156','34','27');
INSERT INTO `temp_pil` VALUES ('157','34','28');
INSERT INTO `temp_pil` VALUES ('158','34','29');
INSERT INTO `temp_pil` VALUES ('159','34','30');
INSERT INTO `temp_pil` VALUES ('160','34','31');

/*---------------------------------------------------------------
  TABLE: `temp_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_soal`;
CREATE TABLE `temp_soal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  `idfile` int(11) NOT NULL,
  `file` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*---------------------------------------------------------------
  TABLE: `tmpreg`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `tmpreg`;
CREATE TABLE `tmpreg` (
  `nokartu` varchar(100) DEFAULT NULL,
  KEY `nokartu` (`nokartu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `token`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id_token` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `masa_berlaku` time NOT NULL,
  PRIMARY KEY (`id_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `token` VALUES   ('1','VKUNXL','2024-05-17 22:44:21','00:15:00');

/*---------------------------------------------------------------
  TABLE: `tugas`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `tugas`;
CREATE TABLE `tugas` (
  `id_tugas` int(255) NOT NULL AUTO_INCREMENT,
  `id_guru` int(255) DEFAULT NULL,
  `kelas` text DEFAULT NULL,
  `mapel` varchar(255) DEFAULT NULL,
  `judul` varchar(50) DEFAULT NULL,
  `tugas` longtext DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `tgl` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_tugas`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `tugas` VALUES   ('1','3','a:1:{i:0;s:10:\"VIII SAINS\";}','PP','perumusan pancasila','<p>Buatlah rangkuman tentang perumusan pancasila sebagai dasar negara</p>',NULL,'2024-07-13 17:28:00','2024-07-15 17:28:00','2024-07-13 16:30:43',NULL);

/*---------------------------------------------------------------
  TABLE: `tujuan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `tujuan`;
CREATE TABLE `tujuan` (
  `idt` int(11) NOT NULL AUTO_INCREMENT,
  `mapel` varchar(11) DEFAULT NULL,
  `level` varchar(11) DEFAULT NULL,
  `lm` int(11) DEFAULT NULL,
  `tujuan` longtext DEFAULT NULL,
  `tp` int(11) DEFAULT NULL,
  `smt` int(11) DEFAULT NULL,
  `guru` int(11) DEFAULT NULL,
  PRIMARY KEY (`idt`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `tujuan` VALUES   ('1','2','8','1','peserta didik dapat menjjelaskan perumusan pancasila','1','1','3');
INSERT INTO `tujuan` VALUES ('2','2','8','2','memahami hukum bacaan','2','1','3');
INSERT INTO `tujuan` VALUES ('3','2','8','3','Memahami stratifikasi sosial','3','1','3');
INSERT INTO `tujuan` VALUES ('4','2','8','3','sdffggjh','4','1','3');

/*---------------------------------------------------------------
  TABLE: `ujian`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ujian`;
CREATE TABLE `ujian` (
  `id_ujian` int(11) NOT NULL AUTO_INCREMENT,
  `kode_nama` varchar(255) DEFAULT NULL,
  `id_bank` int(11) DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jml_soal` int(11) NOT NULL DEFAULT 0,
  `jml_esai` int(11) NOT NULL DEFAULT 0,
  `jml_multi` int(11) NOT NULL DEFAULT 0,
  `jml_bs` int(11) NOT NULL DEFAULT 0,
  `jml_urut` int(11) NOT NULL DEFAULT 0,
  `tampil_bs` int(11) NOT NULL DEFAULT 0,
  `tampil_urut` int(11) NOT NULL DEFAULT 0,
  `tampil_pg` int(11) NOT NULL DEFAULT 0,
  `tampil_esai` int(11) NOT NULL DEFAULT 0,
  `tampil_multi` int(11) NOT NULL DEFAULT 0,
  `lama_ujian` int(11) NOT NULL DEFAULT 0,
  `tgl_ujian` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `waktu_ujian` time DEFAULT NULL,
  `selesai_ujian` time DEFAULT NULL,
  `level` varchar(5) DEFAULT NULL,
  `pk` text DEFAULT NULL,
  `opsi` int(11) DEFAULT 4,
  `sesi` varchar(1) DEFAULT NULL,
  `acak` int(11) DEFAULT 1,
  `token` int(11) DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `hasil` int(11) DEFAULT 0,
  `kkm` varchar(128) DEFAULT NULL,
  `ulang` int(11) DEFAULT 0,
  `soal_agama` varchar(50) DEFAULT NULL,
  `reset` int(11) DEFAULT 0,
  `pelanggaran` int(11) DEFAULT 0,
  `btnselesai` int(11) DEFAULT 0,
  PRIMARY KEY (`id_ujian`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*---------------------------------------------------------------
  TABLE: `users`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nip` varchar(25) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `walas` varchar(20) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `nowa` varchar(13) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `sts` int(11) NOT NULL DEFAULT 0,
  `idjari` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
INSERT INTO `users` VALUES   ('1','-','Admin',NULL,'admin','$2y$10$MI.UkP3igafD5bXyKWXAXOHLUYOV03F99wopo9CtYh4sTEu57ZYIC','admin',NULL,NULL,'0',NULL);
INSERT INTO `users` VALUES ('3','199405292019031006','Arif Rachman, S.Pd., Gr.','','arifrachman.sukses@gmail.com','123456','guru','082347023536','Foto (Arif Rachman).jpg','0',NULL);
INSERT INTO `users` VALUES ('5','196611071993031005 ','H. Ambo Asse, S.Pd., M.Pd','','196611071993031005 ','196611071993031005 ','guru','082347023536',NULL,'0',NULL);
INSERT INTO `users` VALUES ('6','198312022009011004','Masywir, S.Pd., Gr.','','198312022009011004','198312022009011004','guru','085145482832','MAsywir PGRI.jpg','0',NULL);
INSERT INTO `users` VALUES ('7','197309272003122001','Ramlah, S.Ag','','197309272003122001','197309272003122001','guru','085298709871','NO. 80 RAMLAH.JPG','0',NULL);
INSERT INTO `users` VALUES ('8','197008012006041002','Agustan, S.Pd., M.Pd','','197008012006041002','197008012006041002','guru','08124243506','NO. 9 AGUSTAN.jpg','0',NULL);
INSERT INTO `users` VALUES ('9','197101102014112001','Achdaniar Azis, SP, S. Pd','','197101102014112001','197101102014112001','guru','081342447518','NO. 7 ACHDANIAR.jpeg','0',NULL);
INSERT INTO `users` VALUES ('10','197902012007101004','Surahman, S.Pd.I., M.Pd.I','','197902012007101004','197902012007101004','guru','085299033783','Surahman, S.Pd.I.JPG','0',NULL);
INSERT INTO `users` VALUES ('11','196503221994032001','Hj. Satya, S.Pd.I','','196503221994032001','196503221994032001','guru','081344071099','Hj. Satya.JPG','0',NULL);
INSERT INTO `users` VALUES ('12','198805292023211020','Asmar, SE','','198805292023211020','198805292023211020','guru','085232733677','Asmar.JPG','0',NULL);
INSERT INTO `users` VALUES ('13','198102012022212025','Andi Yusriani, S.Pd','','198102012022212025','198102012022212025','guru','085242271529','A. Yusriani.JPG','0',NULL);
INSERT INTO `users` VALUES ('14','196908132003121002','Muh. Nasri, S.Ag','','196908132003121002','196908132003121002','guru','081355852602','Muh. Nasri.JPG','0',NULL);
INSERT INTO `users` VALUES ('15','197808152007101002','Ridwan, S.Pd.I','','197808152007101002','197808152007101002','guru','000','Ridwan.JPG','0',NULL);
INSERT INTO `users` VALUES ('16','198301012022212074','Hasnidar Ramli, S.Pd','','198301012022212074','198301012022212074','guru','000','Hasnidar Ramli.JPG','0',NULL);
INSERT INTO `users` VALUES ('17','197810072005012005','Sanawati Muin, S.Pt., S.Pd','','197810072005012005','197810072005012005','guru','00','Sanawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('18','198605242020122006','Nurkurniana, S.Pd','','198605242020122006','198605242020122006','guru','00',NULL,'0',NULL);
INSERT INTO `users` VALUES ('19','197812312003122022','Naima, S.Pd','','197812312003122022','197812312003122022','guru','00','Naima.JPG','0',NULL);
INSERT INTO `users` VALUES ('20','196612311994032011','Dra. Darmawati','','196612311994032011','196612311994032011','guru','00','Dra. Darmawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('21','197705072007102008','Amildayani, S.Pd','','197705072007102008','197705072007102008','guru','00','Amildayani.JPG','0',NULL);
INSERT INTO `users` VALUES ('22','197303082007012022','Kasmawati, S.Ag.','','197303082007012022','197303082007012022','guru','00','Kasmawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('23','197507182007102002','A. Ulfawati Akil, S.Pd','','197507182007102002','197507182007102002','guru','00','A. Ulfawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('24','196703252005011013','H. Sabang, S.Pd., MM','','196703252005011013','196703252005011013','guru','00','Sabang.JPG','0',NULL);
INSERT INTO `users` VALUES ('25','196812312005011055','Muh. Nakir, S.Pd., M.Pd','','196812312005011055','196812312005011055','guru','00','Muh. Nakir.JPG','0',NULL);
INSERT INTO `users` VALUES ('26','197311152006042006','Hj. Hamsiah, S.Pd., M.Pd','','197311152006042006','197311152006042006','guru','00','Hj. Hamsiah.JPG','0',NULL);
INSERT INTO `users` VALUES ('27','197207062022212007','Rahmatang, S.Ag., S.Pd.I','','197207062022212007','197207062022212007','guru','00','Rahmatang.JPG','0',NULL);
INSERT INTO `users` VALUES ('28','197202152022212004','St. Haerani, S.Pd','','197202152022212004','197202152022212004','guru','00','St. Haerani.JPG','0',NULL);
INSERT INTO `users` VALUES ('29','197410172022211004','Abdullah, S.Pd.I','','197410172022211004','197410172022211004','guru','00','Abdullah.JPG','0',NULL);
INSERT INTO `users` VALUES ('30','199105082023212044','Melisa, S.Pd','','199105082023212044','199105082023212044','guru','00',NULL,'0',NULL);
INSERT INTO `users` VALUES ('31','197311302000032002','Sitti Suaebah, S.Ag., M.Pd.I','','197311302000032002','197311302000032002','guru','00','St. Suaebah.JPG','0',NULL);
INSERT INTO `users` VALUES ('32','198206062023212036','Nurhariyanti, S.Pd','','198206062023212036','198206062023212036','guru','00',NULL,'0',NULL);
INSERT INTO `users` VALUES ('33','197810282007102002','St. Wardiyah, S.Pd','','197810282007102002','197810282007102002','guru','00','St. Wardiyah.JPG','0',NULL);
INSERT INTO `users` VALUES ('34','197512312005012014','Sitti Muadah, S.Pd','','197512312005012014','197512312005012014','guru','00','St. Muadah.JPG','0',NULL);
INSERT INTO `users` VALUES ('35','196707101992032012','Hj. Rosdianah, S.Pd','','196707101992032012','196707101992032012','guru','00','Hj. Rosdianah.JPG','0',NULL);
INSERT INTO `users` VALUES ('36','197307062022212013','Junari, S.Pd','','197307062022212013','197307062022212013','guru','00','Junari.JPG','0',NULL);
INSERT INTO `users` VALUES ('37','7308215902870004','Sutriani, S.Pd.I','','7308215902870004','7308215902870004','guru','00','Sutriani.JPG','0',NULL);
INSERT INTO `users` VALUES ('38','197012131997032010','Nurjaya, S.Ag','','197012131997032010','197012131997032010','guru','00','Nurjaya.JPG','0',NULL);
INSERT INTO `users` VALUES ('39','198008282005011004','Syamsir, M, S.Pd','','198008282005011004','198008282005011004','guru','00','Syamsir.JPG','0',NULL);
INSERT INTO `users` VALUES ('40','198001152007102001','Nurjannah H, S.Pd.I','','198001152007102001','198001152007102001','guru','00','Nurjannah, H.JPG','0',NULL);
INSERT INTO `users` VALUES ('41','198112122022212029','A. Herianti, S.Pd','','198112122022212029','198112122022212029','guru','00','A. Herianti.JPG','0',NULL);
INSERT INTO `users` VALUES ('42','197810172007102001','A. St. Nurjannah, S.Pd.I','','197810172007102001','197810172007102001','guru','00','A. St. Nurjannah.JPG','0',NULL);
INSERT INTO `users` VALUES ('43','197108151995121001','Jabbar, S.Pd','','197108151995121001','197108151995121001','guru','00','Jabbar.JPG','0',NULL);
INSERT INTO `users` VALUES ('44','197406062007011038','Muliadi, S.Ag., S.Pd.I','','197406062007011038','197406062007011038','guru','00','Muliadi.JPG','0',NULL);
INSERT INTO `users` VALUES ('45','197401072003121001','Muhammad Yunus, S.Pd., MA','','197401072003121001','197401072003121001','guru','00','Muh. Yunus.JPG','0',NULL);
INSERT INTO `users` VALUES ('46','198108272009012012','Masyita, S.Pd','','198108272009012012','198108272009012012','guru','00','Masyita.JPG','0',NULL);
INSERT INTO `users` VALUES ('47','197807282007102001','Intan Yacub, S.Pd.I','','197807282007102001','197807282007102001','guru','00','Intan Yacub.JPG','0',NULL);
INSERT INTO `users` VALUES ('48','197706082007011014','Mustaking, S.Pd.I., M.Pd.I','','197706082007011014','197706082007011014','guru','00','Mustaking.JPG','0',NULL);
INSERT INTO `users` VALUES ('49','197704122007102009','Hasnawati T, S.Pd','','197704122007102009','197704122007102009','guru','00','IMG-20170826-WA0005.jpg','0',NULL);
INSERT INTO `users` VALUES ('50','197003011998032001','Samsiar, S.Ag','','197003011998032001','197003011998032001','guru','00','Samsiar.JPG','0',NULL);
INSERT INTO `users` VALUES ('51','199704272023212020','Marwiah Nurhikmah, S.Pd','','199704272023212020','199704272023212020','guru','00','Marwiah.JPG','0',NULL);
INSERT INTO `users` VALUES ('52','197707082023212007','Andi Marhumah, S.Pd','','197707082023212007','197707082023212007','guru','00','Andi Marhumah.JPG','0',NULL);
INSERT INTO `users` VALUES ('53','198203132009012008','Arni S, S.Pd','','198203132009012008','198203132009012008','guru','00','Arni.JPG','0',NULL);
INSERT INTO `users` VALUES ('54','197810072007101001','Aminuddin, S.Pd., S.Pd.I., MM','','197810072007101001','197810072007101001','guru','00','Aminuddin.JPG','0',NULL);
INSERT INTO `users` VALUES ('55','197007212007012025','Hj. Arnidah, S.Ag., S.Pd.I., MA','','197007212007012025','197007212007012025','guru','00','Arnidah.JPG','0',NULL);
INSERT INTO `users` VALUES ('56','197702242005012002','Nurasiah, S.Pd','','197702242005012002','197702242005012002','guru','00','Nurasia.JPG','0',NULL);
INSERT INTO `users` VALUES ('57','197704112007102001','Aisyah, S.Pd','','197704112007102001','197704112007102001','guru','00','Aisyah.JPG','0',NULL);
INSERT INTO `users` VALUES ('58','198102252007102001','Nurwisyati Darwis, S.Pd','','198102252007102001','198102252007102001','guru','00',NULL,'0',NULL);
INSERT INTO `users` VALUES ('59','197312102007102001','Hasniar, S.Pd., M.Pd','','197312102007102001','197312102007102001','guru','00','Hasniar.JPG','0',NULL);
INSERT INTO `users` VALUES ('60','197910122007102001','Rismawati, S.Pd.I','','197910122007102001','197910122007102001','guru','00','Rismawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('61','198210022022212030','Akhirah, S.Pd','','198210022022212030','198210022022212030','guru','00','Akhirah.JPG','0',NULL);
INSERT INTO `users` VALUES ('62','197401102005012003','Pujiati, S.Pd., M.Pd','','197401102005012003','197401102005012003','guru','00','Pujiati.JPG','0',NULL);
INSERT INTO `users` VALUES ('63','197604072005011006','Mustakim, S.Pd','','197604072005011006','197604072005011006','guru','00','Mustakim (2).JPG','0',NULL);
INSERT INTO `users` VALUES ('64','7308264107870049','Hayati, S.Pd.I','','7308264107870049','7308264107870049','guru','00','Hayati.JPG','0',NULL);
INSERT INTO `users` VALUES ('65','199102162023211017','Muh. Syakir Tahir, S.Pd','','199102162023211017','199102162023211017','guru','00','Muh. Syakir.JPG','0',NULL);
INSERT INTO `users` VALUES ('66','197504112022212004','Jumindar, S.Pd','','197504112022212004','197504112022212004','guru','00','Jumindar.JPG','0',NULL);
INSERT INTO `users` VALUES ('67','197803152022212017','Muliani, S.Pd., M.Pd','','197803152022212017','197803152022212017','guru','00','Muliani.JPG','0',NULL);
INSERT INTO `users` VALUES ('68','197305072023211010','Fahman, S.Pd','','197305072023211010','197305072023211010','guru','00','Fahman.JPG','0',NULL);
INSERT INTO `users` VALUES ('69','197608142007012031','Hamiah, S.Pd.I','','197608142007012031','197608142007012031','guru','00','Hamiah.JPG','0',NULL);
INSERT INTO `users` VALUES ('70','196807272003122002','Dra Hj. Rosmiani','','196807272003122002','196807272003122002','guru','00','Rosmiani.JPG','0',NULL);
INSERT INTO `users` VALUES ('71','197012212006042011','Hj. Rosdianah, S.Ag','','197012212006042011','197012212006042011','guru','00','Rosdianah.JPG','0',NULL);
INSERT INTO `users` VALUES ('72','197507102022212012','Faisah, S.Pd','','197507102022212012','197507102022212012','guru','081342205755','Faisah.JPG','0',NULL);
INSERT INTO `users` VALUES ('73','198003112007102002','Darmawati, S.Pd.I','','198003112007102002','198003112007102002','guru','00','Darmawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('74','197005302014112001','Andi Asnidar, S.Pd','','197005302014112001','197005302014112001','guru','00','A. Asnidar.JPG','0',NULL);
INSERT INTO `users` VALUES ('75','197810262007102002','Kartini, S.Pd','','197810262007102002','197810262007102002','guru','00','Kartini.JPG','0',NULL);
INSERT INTO `users` VALUES ('76','7308212005870003','Munawwir, S.Pd.I','','7308212005870003','7308212005870003','guru','00','Munawwir.jpg','0',NULL);
INSERT INTO `users` VALUES ('77','197612072022212008','Darna, S.Pd','','197612072022212008','197612072022212008','guru','00','Darna.JPG','0',NULL);
INSERT INTO `users` VALUES ('78','7308224107870068','Hj. Rosdiana, S.Pd.I','','7308224107870068','7308224107870068','guru','00','Hj. Rosdiana.JPG','0',NULL);
INSERT INTO `users` VALUES ('79','198005152014121006','Abd. Rahman, S.Pd.I., M.Pd.I','','198005152014121006','198005152014121006','guru','00','Abd. Rahman.JPG','0',NULL);
INSERT INTO `users` VALUES ('80','7308222001680001','Andi Rahmad Syamsu, SP., S.Pd','','7308222001680001','7308222001680001','guru','0','A. Rahmad Syamsu.JPG','0',NULL);
INSERT INTO `users` VALUES ('81','197010302003122002','Hj. Kasmilawati, S.Ag','','197010302003122002','197010302003122002','guru','0','Kasmilawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('82','197001152022212005','St. Maesuri, S.Pd','','197001152022212005','197001152022212005','guru','0','St. Maesuri.JPG','0',NULL);
INSERT INTO `users` VALUES ('83','196812122005012006','Andi Fatmawati, S.Pd','','196812122005012006','196812122005012006','guru','0','A. Fatmawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('84','197210052007102004','Yustina Sari, S.Pd','','197210052007102004','197210052007102004','guru','0','Yustina.JPG','0',NULL);
INSERT INTO `users` VALUES ('85','196806012022212005','Nuraidah, S.Pd','','196806012022212005','196806012022212005','guru','0','Nur Aida.JPG','0',NULL);
INSERT INTO `users` VALUES ('86','197906052007101002','Muh. Alamsyah, S.Pd.I., M.Pd.I','','197906052007101002','197906052007101002','guru','0','Muh. Alamsyah.JPG','0',NULL);
INSERT INTO `users` VALUES ('87','197901112022211007','Arwan , S.Pd.I','','197901112022211007','197901112022211007','guru','0','Arwan.JPG','0',NULL);
INSERT INTO `users` VALUES ('88','197705212007102002','Rosma, S.Pd.I','','197705212007102002','197705212007102002','guru','0','Rosma.JPG','0',NULL);
INSERT INTO `users` VALUES ('89','197603092007012023','Supiana, S.Pd','','197603092007012023','197603092007012023','guru','0','Supiana.JPG','0',NULL);
INSERT INTO `users` VALUES ('90','196805292514111001','Muhammad Taufik, S.Ag S.Pd.I.,Ma','','196805292514111001','196805292514111001','guru','0','Muh. Taufik.JPG','0',NULL);
INSERT INTO `users` VALUES ('91','198411132022212028','Kasnidar, S.Pd.I','','198411132022212028','198411132022212028','guru','0','Kasnidar.JPG','0',NULL);
INSERT INTO `users` VALUES ('92','198111142022212025','Yulianti, S.Pd.I','','198111142022212025','198111142022212025','guru','0','Yulianti.JPG','0',NULL);
INSERT INTO `users` VALUES ('93','197303202022212007','Kasmiati, S.Ag','','197303202022212007','197303202022212007','guru','0','Kasmiati.JPG','0',NULL);
INSERT INTO `users` VALUES ('94','197011042005012004','Andi Nirmala, S.Pd','','197011042005012004','197011042005012004','guru','0','A. Nirmala.JPG','0',NULL);
INSERT INTO `users` VALUES ('95','197608192022212009','Haryani, S.Pd.I','','197608192022212009','197608192022212009','guru','0','Haryani.JPG','0',NULL);
INSERT INTO `users` VALUES ('96','197307062007012019','Ratnawati, S.Pd','','197307062007012019','197307062007012019','guru','0','Ratnawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('97','7308141205900001','Dzulkifli, S.Pd','','7308141205900001','7308141205900001','guru','0','Dzulkifli.JPG','0',NULL);
INSERT INTO `users` VALUES ('98','198209132022212031','A. Asriani Abidin, S.Pd','','198209132022212031','198209132022212031','guru','0','A. Asriani Abidin.JPG','0',NULL);
INSERT INTO `users` VALUES ('99','197808172005011007','H. Alias Haseng, S.Pd','','197808172005011007','197808172005011007','guru','0','Alias Haseng.JPG','0',NULL);
INSERT INTO `users` VALUES ('100','197503042005012005','Astinah, S.Pd','','197503042005012005','197503042005012005','guru','0','Astinah.JPG','0',NULL);
INSERT INTO `users` VALUES ('101','197712312023211029','Iwan, S,Pd','','197712312023211029','197712312023211029','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('102','198607032023211023','Mahmud Ansar, S.Pd','','7308214307860001','198607032023211023','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('103','197907212023212014','Musnaeni, S.Pd','','197907212023212014','197907212023212014','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('104','197908062007102002','Marwah, S.Pd.I','','197908062007102002','197908062007102002','guru','0','Marwah.JPG','0',NULL);
INSERT INTO `users` VALUES ('105','7308231801910002','Wahyuddin, S.Pd','','7308231801910002','7308231801910002','guru','0','NO. 99 WAHYUDDIN.JPG','0',NULL);
INSERT INTO `users` VALUES ('106','198607032023211023','Mahmud Ansr, S.Pd','','198607032023211023','198607032023211023','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('107','199809072023212010','Nurfadhilah Nur, S.Pd.I','','199809072023212010','199809072023212010','guru','0','NO. 126 NURFADHILAH NUR.jpeg','0',NULL);
INSERT INTO `users` VALUES ('108','198609202019032009','Surianty, S.Si., M.Pd','','198609202019032009','198609202019032009','guru','0','NO. 96 SURIANTY.jpg','0',NULL);
INSERT INTO `users` VALUES ('109','199107252019032018','Ririanti, S.Sos','','199107252019032018','199107252019032018','guru','0','NO. 83 RIRIANTI.JPG','0',NULL);
INSERT INTO `users` VALUES ('110','198903282019032021','Sitti Khadijah, S.Pd','','198903282019032021','198903282019032021','guru','0','NO. 90 ST KHADIJAH.jpg','0',NULL);
INSERT INTO `users` VALUES ('111','199301242019031009','Pavin Gunadil, S.Pd','','199301242019031009','199301242019031009','guru','0','foto berdasi.jpg','0',NULL);
INSERT INTO `users` VALUES ('112','199309272019031010','Ichsan Budiarto, S.Com','','199309272019031010','199309272019031010','guru','0','NO. 41 ICHSAN BUDIARTO.jpg','0',NULL);
INSERT INTO `users` VALUES ('113','199406252019032022','Hasrianti, S.Pd','','199406252019032022','199406252019032022','guru','0','NO. 34 HASRIANTI.jpg','0',NULL);
INSERT INTO `users` VALUES ('114','198804092019032016','Nursanti, S.Pd','','198804092019032016','198804092019032016','guru','0','NO. 75 NURSANTI.jpg','0',NULL);
INSERT INTO `users` VALUES ('115','199303232019032026','Magfirah, S.Pd, M.Pd','','199303232019032026','199303232019032026','guru','0','NO. 54 Maghfirah.jpg','0',NULL);
INSERT INTO `users` VALUES ('116','199305152019032029','Epy Khoirunningsih','','199305152019032029','199305152019032029','guru','0','NO. 26 EPY KHOIRUNNINGSIH.jpg','0',NULL);
INSERT INTO `users` VALUES ('117','198404092019032013','Daramatasiah, S.Pd','','198404092019032013','198404092019032013','guru','0','NO. 18 DARAMATASIAH.jpg','0',NULL);
INSERT INTO `users` VALUES ('118','199410242019031014','Reynaldy Ashari Ashal, S.Pd','','199410242019031014','199410242019031014','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('119','7308116809720002','Rahmatiah, S.Pd','','7308116809720002','7308116809720002','guru','0',NULL,'0',NULL);
INSERT INTO `users` VALUES ('120','196912281999031001','Drs. Martang Azis, M.Pd','','196912281999031001','196912281999031001','guru','0','Martang.JPG','0',NULL);
INSERT INTO `users` VALUES ('121','197905302014112001','Hasnawati, S.Pd.I','','197905302014112001','197905302014112001','guru','0','Hasnawati.JPG','0',NULL);
INSERT INTO `users` VALUES ('122','7308267112790004','Rosmiah, S.Pd.I','','7308267112790004','7308267112790004','guru','0','NO. 115 ROSMIA.jpg','0',NULL);
INSERT INTO `users` VALUES ('123',NULL,'Arif Rachman, S.Pd., Gr.',NULL,'admin1','$2y$10$YixD0kaeHKyP9PZ6dEISpuaopQkSBHeszu34PtAB5Lvig3qHSulzq','admin',NULL,NULL,'0',NULL);
INSERT INTO `users` VALUES ('124','198212312023212081','Kasmawati, S.Pd.I','','198212312023212081','198212312023212081','guru','0',NULL,'0',NULL);
