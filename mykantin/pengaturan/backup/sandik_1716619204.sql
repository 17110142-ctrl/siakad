
/*---------------------------------------------------------------
  SQL DB BACKUP 25.05.2024 13:40 
  HOST: localhost
  DATABASE: mkkskabmalang_pat78
  TABLES: *
  ---------------------------------------------------------------*/

/*---------------------------------------------------------------
  TABLE: `aplikasi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `aplikasi`;
CREATE TABLE `aplikasi` (
  `id_aplikasi` int NOT NULL AUTO_INCREMENT,
  `aplikasi` varchar(50) DEFAULT NULL,
  `sekolah` varchar(50) DEFAULT NULL,
  `kode_sekolah` varchar(50) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `jenjang` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `semester` int NOT NULL DEFAULT '1',
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
  `token_api` text,
  `id_server` varchar(50) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `logo` text,
  `header_kartu` text,
  `header` text,
  `server` varchar(20) DEFAULT NULL,
  `proktor` varchar(50) DEFAULT NULL,
  `tekhnisi` text,
  `stempel` text,
  `nama_ujian` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `aplikasi` VALUES   ('1','Server Pusat','MKKS KAB. MALANG','P01','REGULER','SMP','20517509','2','2023/2024','Jl. Raya Wonokerto No. 297','Wonokerto ','BANTUR','Cianjur','Jawa Timur','SUGENG GIYANTO, S.Pd., M.Pd.','19671220 200501 1 008','081380774602','','https://','081380774602','-','Asia/Jakarta','https://ujian.mkkskabmalang.com','M4L4N9KJ9vUTCuZwEdis','PUSAT','0','logo77.png','KARTU PESERTA UJIAN','MKKS JAWA TIMUR','pusat','PROKTOR',NULL,'stempel86.png','Penilaian Akhir Tahun');

/*---------------------------------------------------------------
  TABLE: `banksoal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `banksoal`;
CREATE TABLE `banksoal` (
  `id_bank` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) DEFAULT NULL,
  `idguru` varchar(11) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `kelas` text,
  `status` varchar(2) DEFAULT NULL,
  `soal_agama` varchar(50) DEFAULT NULL,
  `model` int DEFAULT '0',
  `groupsoal` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_bank`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
INSERT INTO `banksoal` VALUES   ('3','MTK-8','2','MTK','8',NULL,'1','','1','AKM');
INSERT INTO `banksoal` VALUES ('4','MTK-7','2','MTK','7',NULL,'1','','1','AKM');
INSERT INTO `banksoal` VALUES ('5','BINDO-7','3','BINDO','7',NULL,'1','','1','AKM');
INSERT INTO `banksoal` VALUES ('6','PJOK-8','4','PJOK','8',NULL,'1','','1','AKM');

/*---------------------------------------------------------------
  TABLE: `berita`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id_berita` int NOT NULL AUTO_INCREMENT,
  `id_bank` int NOT NULL,
  `sesi` varchar(10) NOT NULL,
  `ruang` varchar(20) NOT NULL,
  `jenis` varchar(30) NOT NULL,
  `ikut` varchar(10) DEFAULT NULL,
  `susulan` varchar(10) DEFAULT NULL,
  `no_susulan` text,
  `mulai` varchar(10) DEFAULT NULL,
  `selesai` varchar(10) DEFAULT NULL,
  `nama_proktor` varchar(50) DEFAULT NULL,
  `nip_proktor` varchar(50) DEFAULT NULL,
  `nama_pengawas` varchar(50) DEFAULT NULL,
  `nip_pengawas` varchar(50) DEFAULT NULL,
  `catatan` text,
  `tgl_ujian` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_berita`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `bulan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `bulan`;
CREATE TABLE `bulan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bln` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ket` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
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
  TABLE: `download`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `download`;
CREATE TABLE `download` (
  `id` int NOT NULL AUTO_INCREMENT,
  `npsn` varchar(50) DEFAULT NULL,
  `idbank` int DEFAULT NULL,
  `ket` int NOT NULL DEFAULT '1',
  `waktu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `file_pendukung`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `file_pendukung`;
CREATE TABLE `file_pendukung` (
  `id_file` int NOT NULL AUTO_INCREMENT,
  `id_bank` int DEFAULT '0',
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb3;
INSERT INTO `file_pendukung` VALUES   ('1','3','3_1_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('2','3','3_2_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('3','3','3_3_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('4','3','3_4_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('5','3','3_5_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('6','3','3_6_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('7','4','4_1_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('8','4','4_2_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('9','3','3_11_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('10','3','3_12_A.png',NULL);
INSERT INTO `file_pendukung` VALUES ('11','3','3_12_B.png',NULL);
INSERT INTO `file_pendukung` VALUES ('12','3','3_12_C.png',NULL);
INSERT INTO `file_pendukung` VALUES ('13','3','3_12_D.png',NULL);
INSERT INTO `file_pendukung` VALUES ('14','4','4_3_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('15','4','4_4_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('16','3','3_16_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('17','3','3_16_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('18','3','3_16_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('19','4','4_5_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('20','3','3_16_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('21','4','4_6_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('22','4','4_7_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('23','4','4_9_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('24','4','4_10_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('25','4','4_11_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('26','4','4_12_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('27','5','5_3_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('28','4','4_14_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('29','4','4_15_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('30','6','6_1_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('31','4','4_16_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('32','6','6_2_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('33','5','5_5_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('34','5','5_5_2.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('35','4','4_16_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('36','4','4_17_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('37','4','4_18_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('38','4','4_19_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('39','4','4_20_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('40','4','4_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('41','4','4_18_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('42','4','4_18_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('43','4','4_18_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('44','4','4_18_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('45','4','4_18_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('46','3','3_16_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('47','4','4_23_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('48','3','3_20_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('49','3','3_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('50','4','4_25_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('51','3','3_23_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('52','4','4_26_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('53','3','3_25_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('54','4','4_27_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('55','3','3_26_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('56','4','4_28_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('57','3','3_27_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('58','4','4_29_1.jpg',NULL);
INSERT INTO `file_pendukung` VALUES ('59','3','3_28_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('60','4','4_30_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('61','3','3_30_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('62','3','3_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('63','3','3_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('64','3','3_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('65','3','3_21_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('66','3','3_21_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('67','3','3_21_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('68','3','3_21_1.png',NULL);

/*---------------------------------------------------------------
  TABLE: `informasi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `informasi`;
CREATE TABLE `informasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `judul` varchar(50) DEFAULT NULL,
  `untuk` varchar(50) DEFAULT NULL,
  `isi` text,
  `waktu` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `jawaban`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban`;
CREATE TABLE `jawaban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_siswa` int DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `id_bank` int NOT NULL DEFAULT '0',
  `id_soal` int NOT NULL DEFAULT '0',
  `id_ujian` int NOT NULL DEFAULT '0',
  `jawaban` varchar(50) DEFAULT NULL,
  `jawabx` varchar(50) DEFAULT NULL,
  `jenis` int NOT NULL,
  `esai` text,
  `jawabmulti` text,
  `jawabbs` text,
  `jawaburut` text,
  `bs1` varchar(5) DEFAULT NULL,
  `bs2` varchar(5) DEFAULT NULL,
  `bs3` varchar(5) DEFAULT NULL,
  `bs4` varchar(5) DEFAULT NULL,
  `bs5` varchar(5) DEFAULT NULL,
  `urut1` text,
  `urut2` text,
  `urut3` text,
  `urut4` text,
  `urut5` text,
  `nilai_esai` int NOT NULL DEFAULT '0',
  `ragu` int NOT NULL DEFAULT '0',
  `status` int DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `skor` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `jawaban_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jawaban_soal`;
CREATE TABLE `jawaban_soal` (
  `id_jawaban` int NOT NULL AUTO_INCREMENT,
  `id_soal` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `id_bank` int DEFAULT NULL,
  `id_ujian` int DEFAULT NULL,
  `idjawab` varchar(50) DEFAULT NULL,
  `jenis` int DEFAULT NULL,
  `jawab` text,
  `skor` int DEFAULT NULL,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `jenis`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jenis`;
CREATE TABLE `jenis` (
  `id_jenis` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `jenis` VALUES   ('PAT','Penilaian Akhir Tahun','aktif');

/*---------------------------------------------------------------
  TABLE: `jenis_sp`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `jenis_sp`;
CREATE TABLE `jenis_sp` (
  `id_sp` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenjang` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ket` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `id_jawaban` int NOT NULL AUTO_INCREMENT,
  `id_siswa` int DEFAULT NULL,
  `id_bank` int NOT NULL DEFAULT '0',
  `id_soal` int NOT NULL DEFAULT '0',
  `id_ujian` int NOT NULL DEFAULT '0',
  `jenis` varchar(50) DEFAULT NULL,
  `jawaburut` text,
  PRIMARY KEY (`id_jawaban`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `kelas`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `npsn` varchar(50) DEFAULT NULL,
  `level` int DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
INSERT INTO `kelas` VALUES   ('1','20517509','7','VII-A');
INSERT INTO `kelas` VALUES ('2','20517509','7','VII-B');
INSERT INTO `kelas` VALUES ('3','20517509','7','VII-C');
INSERT INTO `kelas` VALUES ('4','20517509','7','VII-D');
INSERT INTO `kelas` VALUES ('5','20517509','7','VII-E');
INSERT INTO `kelas` VALUES ('6','20517509','7','VII-F');
INSERT INTO `kelas` VALUES ('7','20517509','7','VII-G');
INSERT INTO `kelas` VALUES ('8','20517509','8','VIII-A');
INSERT INTO `kelas` VALUES ('9','20517509','8','VIII-B');
INSERT INTO `kelas` VALUES ('10','20517509','8','VIII-C');
INSERT INTO `kelas` VALUES ('11','20517509','8','VIII-D');
INSERT INTO `kelas` VALUES ('12','20517509','8','VIII-E');
INSERT INTO `kelas` VALUES ('13','20517509','8','VIII-F');
INSERT INTO `kelas` VALUES ('14','20517509','8','VIII-G');
INSERT INTO `kelas` VALUES ('15','20517486','7','VII-A');
INSERT INTO `kelas` VALUES ('16','20517486','7','VII-B');
INSERT INTO `kelas` VALUES ('17','20517486','7','VII-C');
INSERT INTO `kelas` VALUES ('18','20517486','7','VII-D');
INSERT INTO `kelas` VALUES ('19','20517486','8','VIII-A');
INSERT INTO `kelas` VALUES ('20','20517486','8','VIII-B');
INSERT INTO `kelas` VALUES ('21','20517486','8','VIII-C');
INSERT INTO `kelas` VALUES ('22','20517486','8','VIII-D');
INSERT INTO `kelas` VALUES ('23','20517517','7','VII-A');
INSERT INTO `kelas` VALUES ('24','20517517','7','VII-B');
INSERT INTO `kelas` VALUES ('25','20517517','7','VII-C');
INSERT INTO `kelas` VALUES ('26','20517517','7','VII-D');
INSERT INTO `kelas` VALUES ('27','20517517','7','VII-E');
INSERT INTO `kelas` VALUES ('28','20517517','7','VII-F');
INSERT INTO `kelas` VALUES ('29','20517517','7','VII-G');
INSERT INTO `kelas` VALUES ('30','20517517','7','VII-H');
INSERT INTO `kelas` VALUES ('31','20517517','7','VII-I');
INSERT INTO `kelas` VALUES ('32','20517517','8','VIII-A');
INSERT INTO `kelas` VALUES ('33','20517517','8','VIII-B');
INSERT INTO `kelas` VALUES ('34','20517517','8','VIII-C');
INSERT INTO `kelas` VALUES ('35','20517517','8','VIII-D');
INSERT INTO `kelas` VALUES ('36','20517517','8','VIII-E');
INSERT INTO `kelas` VALUES ('37','20517517','8','VIII-F');
INSERT INTO `kelas` VALUES ('38','20517517','8','VIII-G');
INSERT INTO `kelas` VALUES ('39','20517517','8','VIII-H');
INSERT INTO `kelas` VALUES ('40','20517470','7','VII-A');
INSERT INTO `kelas` VALUES ('41','20517470','7','VII-B');
INSERT INTO `kelas` VALUES ('42','20517470','7','VII-C');
INSERT INTO `kelas` VALUES ('43','20517470','7','VII-D');
INSERT INTO `kelas` VALUES ('44','20517470','8','VIII-A');
INSERT INTO `kelas` VALUES ('45','20517470','8','VIII-B');
INSERT INTO `kelas` VALUES ('46','20517470','8','VIII-C');
INSERT INTO `kelas` VALUES ('47','20517470','8','VIII-D');

/*---------------------------------------------------------------
  TABLE: `kunci_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kunci_soal`;
CREATE TABLE `kunci_soal` (
  `id_bank` int DEFAULT NULL,
  `id_soal` int DEFAULT NULL,
  `id_jawab` varchar(50) DEFAULT NULL,
  `jawaban` text,
  `skor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `kunci_soal` VALUES   ('3','6','6.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','7','7.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','8','8.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','9','9.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','10','10.1','C','1');
INSERT INTO `kunci_soal` VALUES ('3','11','11.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','12','12.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','13','13.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','15','15.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','17','17.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','18','18.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','19','19.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','20','20.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','22','22.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','24','24.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','16','16.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','16','16.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','16','16.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','16','16.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','17','17.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','17','17.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','17','17.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','17','17.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','18','18.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','18','18.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','18','18.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','18','18.4','S','1');
INSERT INTO `kunci_soal` VALUES ('4','25','25.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','14','14.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','16','16.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','21','21.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','23','23.1','C','1');
INSERT INTO `kunci_soal` VALUES ('3','20','20.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','20','20.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','20','20.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','20','20.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.3','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.4','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.5','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','0','0.4','B','1');
INSERT INTO `kunci_soal` VALUES ('4','27','27.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','28','28.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','29','29.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','30','30.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','31','31.1','D','1');
INSERT INTO `kunci_soal` VALUES ('5','32','32.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','33','33.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','34','34.1','C','1');
INSERT INTO `kunci_soal` VALUES ('5','35','35.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','36','36.1','C','1');
INSERT INTO `kunci_soal` VALUES ('5','37','37.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','38','38.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','39','39.1','D','1');
INSERT INTO `kunci_soal` VALUES ('5','40','40.1','B','1');
INSERT INTO `kunci_soal` VALUES ('6','41','41.1','C','1');
INSERT INTO `kunci_soal` VALUES ('4','31','31.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','31','31.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','31','31.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','31','31.4','S','1');
INSERT INTO `kunci_soal` VALUES ('6','42','42.1','C','1');
INSERT INTO `kunci_soal` VALUES ('6','42','42.2','D','1');
INSERT INTO `kunci_soal` VALUES ('5','43','43.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','45','45.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','45','45.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','45','45.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','45','45.4','S','1');
INSERT INTO `kunci_soal` VALUES ('4','46','46.1','S','1');
INSERT INTO `kunci_soal` VALUES ('4','46','46.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','46','46.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','46','46.4','B','1');
INSERT INTO `kunci_soal` VALUES ('4','49','49.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','49','49.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','49','49.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','50','50.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','50','50.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','50','50.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','50','50.4','S','1');
INSERT INTO `kunci_soal` VALUES ('4','48','48.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','48','48.2','B','1');
INSERT INTO `kunci_soal` VALUES ('4','48','48.3','S','1');
INSERT INTO `kunci_soal` VALUES ('4','48','48.4','S','1');
INSERT INTO `kunci_soal` VALUES ('4','47','47.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','47','47.2','S','1');
INSERT INTO `kunci_soal` VALUES ('4','47','47.3','S','1');
INSERT INTO `kunci_soal` VALUES ('4','47','47.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','44','44.1','S','1');
INSERT INTO `kunci_soal` VALUES ('3','44','44.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','44','44.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','44','44.4','S','1');
INSERT INTO `kunci_soal` VALUES ('4','51','51.1','A','1');
INSERT INTO `kunci_soal` VALUES ('4','51','51.2','B','1');
INSERT INTO `kunci_soal` VALUES ('4','51','51.3','D','1');
INSERT INTO `kunci_soal` VALUES ('3','52','52.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','52','52.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','52','52.3','S','1');
INSERT INTO `kunci_soal` VALUES ('3','52','52.4','B','1');
INSERT INTO `kunci_soal` VALUES ('4','53','53.1','A','1');
INSERT INTO `kunci_soal` VALUES ('4','53','53.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','54','54.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','54','54.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','54','54.3','S','1');
INSERT INTO `kunci_soal` VALUES ('3','54','54.4','S','1');
INSERT INTO `kunci_soal` VALUES ('3','55','55.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','55','55.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','55','55.3','S','1');
INSERT INTO `kunci_soal` VALUES ('3','55','55.4','B','1');
INSERT INTO `kunci_soal` VALUES ('4','56','56.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','56','56.2','C','1');
INSERT INTO `kunci_soal` VALUES ('3','57','57.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','57','57.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','57','57.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','57','57.4','B','1');
INSERT INTO `kunci_soal` VALUES ('4','59','59.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','59','59.2','A','1');
INSERT INTO `kunci_soal` VALUES ('4','59','59.3','D','1');
INSERT INTO `kunci_soal` VALUES ('3','60','60.1','C','1');
INSERT INTO `kunci_soal` VALUES ('3','61','61.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','61','61.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','61','61.3','C','1');
INSERT INTO `kunci_soal` VALUES ('3','62','62.1','A','1');
INSERT INTO `kunci_soal` VALUES ('3','62','62.2','C','1');
INSERT INTO `kunci_soal` VALUES ('4','63','63.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','63','63.2','C','1');
INSERT INTO `kunci_soal` VALUES ('4','63','63.3','A','1');
INSERT INTO `kunci_soal` VALUES ('3','64','64.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','64','64.2','C','1');
INSERT INTO `kunci_soal` VALUES ('3','64','64.3','A','1');
INSERT INTO `kunci_soal` VALUES ('4','65','65.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','65','65.2','A','1');
INSERT INTO `kunci_soal` VALUES ('4','65','65.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','66','66.1','C','1');
INSERT INTO `kunci_soal` VALUES ('3','66','66.2','A','1');
INSERT INTO `kunci_soal` VALUES ('3','66','66.3','B','1');
INSERT INTO `kunci_soal` VALUES ('4','67','67.1','B','1');
INSERT INTO `kunci_soal` VALUES ('4','67','67.2','A','1');
INSERT INTO `kunci_soal` VALUES ('3','68','68.1','C','1');
INSERT INTO `kunci_soal` VALUES ('3','68','68.2','A','1');
INSERT INTO `kunci_soal` VALUES ('3','68','68.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','68','68.4','D','1');
INSERT INTO `kunci_soal` VALUES ('4','69','69.1','E','1');
INSERT INTO `kunci_soal` VALUES ('4','69','69.2','D','1');
INSERT INTO `kunci_soal` VALUES ('4','69','69.3','A','1');
INSERT INTO `kunci_soal` VALUES ('4','69','69.4','C','1');
INSERT INTO `kunci_soal` VALUES ('3','70','70.1','D','1');
INSERT INTO `kunci_soal` VALUES ('3','70','70.2','E','1');
INSERT INTO `kunci_soal` VALUES ('3','70','70.3','C','1');
INSERT INTO `kunci_soal` VALUES ('3','70','70.4','A','1');
INSERT INTO `kunci_soal` VALUES ('4','71','71.1','D','1');
INSERT INTO `kunci_soal` VALUES ('4','71','71.2','C','1');
INSERT INTO `kunci_soal` VALUES ('4','71','71.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','72','72.1','E','1');
INSERT INTO `kunci_soal` VALUES ('3','72','72.2','B','1');
INSERT INTO `kunci_soal` VALUES ('3','72','72.3','D','1');
INSERT INTO `kunci_soal` VALUES ('3','73','73.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','73','73.2','D','1');
INSERT INTO `kunci_soal` VALUES ('3','58','58.1','B','1');
INSERT INTO `kunci_soal` VALUES ('3','58','58.2','S','1');
INSERT INTO `kunci_soal` VALUES ('3','58','58.3','B','1');
INSERT INTO `kunci_soal` VALUES ('3','58','58.4','S','1');

/*---------------------------------------------------------------
  TABLE: `level`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `id_level` int NOT NULL AUTO_INCREMENT,
  `level` int DEFAULT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
INSERT INTO `level` VALUES   ('7','7');
INSERT INTO `level` VALUES ('8','8');

/*---------------------------------------------------------------
  TABLE: `log`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `type` varchar(20) NOT NULL,
  `text` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `level` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
INSERT INTO `log` VALUES   ('1','5','login','Login','2024-05-25 07:26:52','prosek');
INSERT INTO `log` VALUES ('2','1','login','Login','2024-05-25 08:28:58','pusat');
INSERT INTO `log` VALUES ('3','1','login','Login','2024-05-25 08:35:45','pusat');
INSERT INTO `log` VALUES ('4','1','login','Login','2024-05-25 08:48:14','pusat');
INSERT INTO `log` VALUES ('5','1','login','Login','2024-05-25 08:50:24','pusat');
INSERT INTO `log` VALUES ('6','72','login','Login','2024-05-25 08:52:30','prosek');
INSERT INTO `log` VALUES ('7','5','login','Login','2024-05-25 08:57:22','prosek');
INSERT INTO `log` VALUES ('8','1','login','Login','2024-05-25 09:07:51','pusat');
INSERT INTO `log` VALUES ('9','1','login','Login','2024-05-25 09:38:38','pusat');
INSERT INTO `log` VALUES ('10','68','login','Login','2024-05-25 09:39:53','prosek');
INSERT INTO `log` VALUES ('11','1','login','Login','2024-05-25 10:02:52','pusat');
INSERT INTO `log` VALUES ('12','1','login','Login','2024-05-25 10:03:38','pusat');
INSERT INTO `log` VALUES ('13','1','login','Login','2024-05-25 10:15:01','pusat');
INSERT INTO `log` VALUES ('14','1','login','Login','2024-05-25 10:26:33','pusat');
INSERT INTO `log` VALUES ('15','1','login','Login','2024-05-25 11:07:17','pusat');
INSERT INTO `log` VALUES ('16','31','login','Login','2024-05-25 12:02:33','prosek');
INSERT INTO `log` VALUES ('17','49','login','Login','2024-05-25 12:02:52','prosek');
INSERT INTO `log` VALUES ('18','11','login','Login','2024-05-25 12:07:25','prosek');
INSERT INTO `log` VALUES ('19','68','login','Login','2024-05-25 12:08:19','prosek');
INSERT INTO `log` VALUES ('20','55','login','Login','2024-05-25 12:13:32','prosek');
INSERT INTO `log` VALUES ('21','28','login','Login','2024-05-25 12:14:54','prosek');
INSERT INTO `log` VALUES ('22','23','login','Login','2024-05-25 12:21:25','prosek');
INSERT INTO `log` VALUES ('23','1','login','Login','2024-05-25 12:35:34','prosek');
INSERT INTO `log` VALUES ('24','21','login','Login','2024-05-25 12:36:50','prosek');
INSERT INTO `log` VALUES ('25','41','login','Login','2024-05-25 12:37:40','prosek');
INSERT INTO `log` VALUES ('26','27','login','Login','2024-05-25 12:47:01','prosek');
INSERT INTO `log` VALUES ('27','91','login','Login','2024-05-25 12:51:24','prosek');
INSERT INTO `log` VALUES ('28','1','login','Login','2024-05-25 12:51:38','prosek');
INSERT INTO `log` VALUES ('29','37','login','Login','2024-05-25 12:53:19','prosek');
INSERT INTO `log` VALUES ('30','82','login','Login','2024-05-25 12:53:46','prosek');
INSERT INTO `log` VALUES ('31','10','login','Login','2024-05-25 12:57:50','prosek');
INSERT INTO `log` VALUES ('32','10','login','Login','2024-05-25 12:59:55','prosek');
INSERT INTO `log` VALUES ('33','10','login','Login','2024-05-25 13:09:01','prosek');
INSERT INTO `log` VALUES ('34','54','login','Login','2024-05-25 13:11:29','prosek');
INSERT INTO `log` VALUES ('35','10','login','Login','2024-05-25 13:12:38','prosek');
INSERT INTO `log` VALUES ('36','1','login','Login','2024-05-25 13:34:15','pusat');

/*---------------------------------------------------------------
  TABLE: `mata_pelajaran`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `mata_pelajaran`;
CREATE TABLE `mata_pelajaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) DEFAULT NULL,
  `nama_mapel` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
INSERT INTO `mata_pelajaran` VALUES   ('1','PABD','Penddidikan Agama dan Budi Pekerti');
INSERT INTO `mata_pelajaran` VALUES ('2','PPKn','Pendidikan Pancasila dan Kewarganegaraan');
INSERT INTO `mata_pelajaran` VALUES ('3','BINDO','Bahasa Indonesia');
INSERT INTO `mata_pelajaran` VALUES ('4','MTK','Matematika');
INSERT INTO `mata_pelajaran` VALUES ('5','IPA','Ilmu Pengetahuan Alam');
INSERT INTO `mata_pelajaran` VALUES ('6','IPS','Ilmu Pengetahuan Sosial');
INSERT INTO `mata_pelajaran` VALUES ('7','BING','Bahasa Inggris');
INSERT INTO `mata_pelajaran` VALUES ('8','SB','Seni Budaya');
INSERT INTO `mata_pelajaran` VALUES ('9','PJOK','Pendidikan Jasmani Olahraga dan Kesehatan');
INSERT INTO `mata_pelajaran` VALUES ('10','PRK','Prakarya');
INSERT INTO `mata_pelajaran` VALUES ('11','BADER','Bahasa Jawa');
INSERT INTO `mata_pelajaran` VALUES ('12','TIK','Teknologi Informasi dan Komunikasi');

/*---------------------------------------------------------------
  TABLE: `nilai`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL AUTO_INCREMENT,
  `id_ujian` int DEFAULT NULL,
  `id_bank` int DEFAULT NULL,
  `id_siswa` int DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `ujian_mulai` varchar(20) DEFAULT NULL,
  `ujian_berlangsung` varchar(20) DEFAULT NULL,
  `ujian_selesai` varchar(20) DEFAULT NULL,
  `jml_benar` int DEFAULT NULL,
  `benar_esai` int NOT NULL DEFAULT '0',
  `benar_multi` int NOT NULL DEFAULT '0',
  `benar_bs` int NOT NULL DEFAULT '0',
  `benar_urut` int NOT NULL DEFAULT '0',
  `skor` varchar(255) DEFAULT NULL,
  `skor_esai` varchar(255) DEFAULT NULL,
  `skor_multi` varchar(255) DEFAULT NULL,
  `skor_bs` varchar(255) DEFAULT NULL,
  `skor_urut` varchar(255) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `makskor` int DEFAULT NULL,
  `nilai` text,
  `status` int DEFAULT NULL,
  `ipaddress` varchar(20) DEFAULT NULL,
  `hasil` int DEFAULT NULL,
  `jawaban_pg` text,
  `jawaban_esai` longtext,
  `jawaban_multi` text,
  `jawaban_bs` text,
  `jawaban_urut` text,
  `nilai_esai` int DEFAULT NULL,
  `nilai_esai2` text,
  `online` int NOT NULL DEFAULT '0',
  `id_soal` longtext,
  `id_opsi` longtext,
  `id_esai` text,
  `blok` int NOT NULL DEFAULT '0',
  `server` varchar(50) DEFAULT NULL,
  `browser` int DEFAULT '0',
  `jenis_browser` varchar(50) DEFAULT NULL,
  `jumjawab` varchar(11) DEFAULT NULL,
  `jumsoal` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `pk`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `pk`;
CREATE TABLE `pk` (
  `no` int NOT NULL AUTO_INCREMENT,
  `id_pk` varchar(100) NOT NULL,
  `pk` varchar(50) NOT NULL,
  `jurusan_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_pk`),
  UNIQUE KEY `no` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `prosek`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `prosek`;
CREATE TABLE `prosek` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `npsn` varchar(20) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `nowa` varchar(13) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `sts` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`),
  KEY `npsn` (`npsn`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1;
INSERT INTO `prosek` VALUES   ('1','PROKTOR 1','20517477','20517477','20517477','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('2','PROKTOR 2','20549205','20549205','20549205','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('3','PROKTOR 3','20562738','20562738','20562738','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('4','PROKTOR 4','20517478','20517478','20517478','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('5','PROKTOR 5','20517509','20517509','20517509','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('6','PROKTOR 6','20549209','20549209','20549209','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('7','PROKTOR 7','20517479','20517479','20517479','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('8','PROKTOR 8','20517510','20517510','20517510','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('9','PROKTOR 9','20517480','20517480','20517480','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('10','PROKTOR 10','20517511','20517511','20517511','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('11','PROKTOR 11','20517481','20517481','20517481','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('12','PROKTOR 12','20517482','20517482','20517482','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('13','PROKTOR 13','20517512','20517512','20517512','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('14','PROKTOR 14','20517483','20517483','20517483','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('15','PROKTOR 15','20517513','20517513','20517513','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('16','PROKTOR 16','20517484','20517484','20517484','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('17','PROKTOR 17','20517514','20517514','20517514','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('18','PROKTOR 18','20517485','20517485','20517485','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('19','PROKTOR 19','20517515','20517515','20517515','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('20','PROKTOR 20','20517486','20517486','20517486','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('21','PROKTOR 21','20517516','20517516','20517516','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('22','PROKTOR 22','20517487','20517487','20517487','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('23','PROKTOR 23','69936635','69936635','69936635','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('24','PROKTOR 24','20517488','20517488','20517488','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('25','PROKTOR 25','20549206','20549206','20549206','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('26','PROKTOR 26','20517475','20517475','20517475','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('27','MOH. FIQIH DHARMAWAN','20517517','20517517','20517517','sekolah','08993941636',NULL,'0');
INSERT INTO `prosek` VALUES ('28','PROKTOR 28','20517500','20517500','20517500','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('29','PROKTOR 29','20517504','20517504','20517504','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('30','PROKTOR 30','20517520','20517520','20517520','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('31','PROKTOR 31','20517474','20517474','20517474','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('32','PROKTOR 32','20517461','20517461','20517461','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('33','PROKTOR 33','20517518','20517518','20517518','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('34','PROKTOR 34','20517501','20517501','20517501','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('35','PROKTOR 35','20517462','20517462','20517462','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('36','PROKTOR 36','69938826','69938826','69938826','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('37','PROKTOR 37','20517463','20517463','20517463','sekolah','085850721845',NULL,'0');
INSERT INTO `prosek` VALUES ('38','PROKTOR 38','20517519','20517519','20517519','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('39','PROKTOR 39','20517464','20517464','20517464','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('40','PROKTOR 40','20517506','20517506','20517506','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('41','MAHFUDZ ADI SUPRAPTO','20549204','20549204','20549204','sekolah','085107337776',NULL,'0');
INSERT INTO `prosek` VALUES ('42','PROKTOR 42','20517465','20517465','20517465','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('43','PROKTOR 43','20307583','20307583','20307583','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('44','PROKTOR 44','20517466','20517466','20517466','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('45','PROKTOR 45','20549207','20549207','20549207','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('46','PROKTOR 46','20517467','20517467','20517467','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('47','PROKTOR 47','20517492','20517492','20517492','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('48','PROKTOR 48','20517468','20517468','20517468','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('49','PROKTOR 49','20517493','20517493','20517493','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('50','PROKTOR 50','20517469','20517469','20517469','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('51','PROKTOR 51','20517494','20517494','20517494','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('52','PROKTOR 52','20517502','20517502','20517502','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('53','PROKTOR 53','69728235','69728235','69728235','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('54','Mochamad Adam Devara','20517470','20517470','20517470','sekolah','082245535805',NULL,'0');
INSERT INTO `prosek` VALUES ('55','PROKTOR 55','20517495','20517495','20517495','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('56','PROKTOR 56','20517503','20517503','20517503','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('57','PROKTOR 57','20517471','20517471','20517471','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('58','PROKTOR 58','20517496','20517496','20517496','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('59','PROKTOR 59','20517472','20517472','20517472','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('60','PROKTOR 60','20517473','20517473','20517473','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('61','PROKTOR 61','20517497','20517497','20517497','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('62','PROKTOR 62','20517489','20517489','20517489','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('63','PROKTOR 63','20517498','20517498','20517498','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('64','PROKTOR 64','20517490','20517490','20517490','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('65','PROKTOR 65','20517499','20517499','20517499','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('66','PROKTOR 66','20517491','20517491','20517491','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('67','PROKTOR 67','20549208','20549208','20549208','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('68','PROKTOR 68','20517507','20517507','20517507','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('69','PROKTOR 69','20517508','20517508','20517508','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('70','PROKTOR 70','20560082','20560082','20560082','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('71','PROKTOR 71','20561840','20561840','20561840','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('72','PROKTOR 72','20561842','20561842','20561842','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('73','PROKTOR 73','20561846','20561846','20561846','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('74','PROKTOR 74','20561849','20561849','20561849','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('75','PROKTOR 75','20561850','20561850','20561850','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('76','PROKTOR 76','20561854','20561854','20561854','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('77','PROKTOR 77','20561855','20561855','20561855','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('78','PROKTOR 78','20561857','20561857','20561857','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('79','PROKTOR 79','20561859','20561859','20561859','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('80','PROKTOR 80','20561860','20561860','20561860','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('81','PROKTOR 81','20561861','20561861','20561861','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('82','PROKTOR 82','20561864','20561864','20561864','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('83','PROKTOR 83','20561867','20561867','20561867','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('84','PROKTOR 84','20561868','20561868','20561868','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('85','PROKTOR 85','20561872','20561872','20561872','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('86','PROKTOR 86','20561874','20561874','20561874','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('87','PROKTOR 87','20561875','20561875','20561875','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('88','PROKTOR 88','20566334','20566334','20566334','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('89','PROKTOR 89','20566335','20566335','20566335','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('90','PROKTOR 90','20570243','20570243','20570243','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('91','PROKTOR 91','20570271','20570271','20570271','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('92','PROKTOR 92','20570272','20570272','20570272','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('93','PROKTOR 93','20570904','20570904','20570904','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('94','PROKTOR 94','20561858','20561858','20561858','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('95','PROKTOR 95','20561863','20561863','20561863','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('96','PROKTOR 96','20559935','20559935','20559935','sekolah','0',NULL,'0');
INSERT INTO `prosek` VALUES ('97','PROKTOR 97','20561852','20561852','20561852','sekolah','0',NULL,'0');

/*---------------------------------------------------------------
  TABLE: `reset`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `reset`;
CREATE TABLE `reset` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idsiswa` int DEFAULT NULL,
  `idnilai` int DEFAULT NULL,
  `idujian` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `ruang`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ruang`;
CREATE TABLE `ruang` (
  `kode_ruang` varchar(10) NOT NULL,
  `keterangan` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_ruang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `sekolah`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sekolah`;
CREATE TABLE `sekolah` (
  `id_sekolah` int NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `desa` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kepsek` varchar(50) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `id_server` varchar(50) DEFAULT NULL,
  `waktu` varchar(50) DEFAULT 'Asia/Jakarta',
  `token_api` varchar(255) DEFAULT NULL,
  `proktor` varchar(50) DEFAULT NULL,
  `nowa_proktor` varchar(50) DEFAULT NULL,
  `sesi` int DEFAULT '1',
  `url_sinkron` varchar(100) DEFAULT NULL,
  `jenjang` varchar(11) NOT NULL DEFAULT 'SMP',
  `telp` varchar(50) NOT NULL DEFAULT '0',
  `fax` varchar(50) NOT NULL DEFAULT '-',
  `email` varchar(50) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_sekolah`),
  KEY `npsn` (`npsn`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1;
INSERT INTO `sekolah` VALUES   ('1','SMP Negeri 1 AMPELGADING','NEGERI','20517477','Jl. Raya Tirtomarto No 9','Tirtomarto ','AMPELGADING','Malang','Jawa Timur','ARIFIN, S.Pd., M.Pd.','','MKKS-1','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 1',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('2','SMP Negeri 2 AMPELGADING','NEGERI','20549205','Jl. Lapangan 8 - Ds. Sonowangi','Sonowangi','AMPELGADING','Malang','Jawa Timur','NUNUK PUJI RAHAYU, S.Pd','','MKKS-2','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 2',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('3','SMP Negeri 3 AMPELGADING','NEGERI','20562738','Jl. Comdeca - Lebakarjo','Lebakarjo','AMPELGADING','Malang','Jawa Timur','HERMINTO PRABOWO, S.Pd.','','MKKS-3','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 3',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('4','SMP Negeri 1 BANTUR','NEGERI','20517478','Jl. Raya Bantur','Bantur','BANTUR','Malang','Jawa Timur','Drs. EDY SUPRIONO','','MKKS-4','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 4',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('5','SMP Negeri 2 BANTUR','NEGERI','20517509','Desa Wonokerto 297','Wonokerto ','BANTUR','Malang','Jawa Timur','SUGENG GIYANTO, S.Pd., M.Pd.','','MKKS-5','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 5',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('6','SMP Negeri 3 BANTUR','NEGERI','20549209','Jl. Sumber Bening RT 16 RW 03','Sumberbening','BANTUR','Malang','Jawa Timur','RUHDI, S.Pd, M.Pd','','MKKS-6','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 6',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('7','SMP Negeri 1 BULULAWANG','NEGERI','20517479','Jl. Sempalwadak 19','Sempalwadak','Bululawang','Malang','Jawa Timur','SUNTORO, S.Pd., M.Si., M.M.Pd.','','MKKS-7','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 7',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('8','SMP Negeri 2 BULULAWANG','NEGERI','20517510','Jl. Raya Krebet','Krebet','LAWANG','Malang','Jawa Timur','MUSNI YULIASTUTI, M.Pd','','MKKS-8','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 8',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('9','SMP Negeri 1 DAMPIT','NEGERI','20517480','Jl. Gunungjati No.33 Dampit','Dampit','DAMPIT','Malang','Jawa Timur','MOHAMAD UNTUNG, S.Pd','','MKKS-9','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 9',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('10','SMP Negeri 2 DAMPIT','NEGERI','20517511','Jl. Raya Srimulyo No.1','Srimulyo','DAMPIT','Malang','Jawa Timur','SITI ZULAIKAH, S.Pd','','MKKS-10','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 10',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('11','SMP Negeri 1 DAU','NEGERI','20517481','Jl. Raya Tegalwaru 191','Tegalwaru',' DAU','Malang','Jawa Timur','Drs. BINURDIN','','MKKS-11','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 11',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('12','SMP Negeri 1 DONOMULYO','NEGERI','20517482','Jl. Raya Donomulyo 60',' Donomulyo',' DONOMULYO','Malang','Jawa Timur','RIDWAN PURWOKO, S.Pd, M.Si','','MKKS-12','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 12',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('13','SMP Negeri 2 DONOMULYO','NEGERI','20517512','JL. A. Yani 789',' Donomulyo',' DONOMULYO','Malang','Jawa Timur','MATEUS SUBOWO, S.Pd','','MKKS-13','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 13',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('14','SMP NEGERI 1 GEDANGAN','NEGERI','20517483','Jl. DIPONEGORO 244','GEDANGAN',' GEDANGAN','MALANG','JAWA TIMUR','MOHAMMAD ALI, S.Pd.','','MKKS-14','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 14',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('15','SMP Negeri 2 GEDANGAN','NEGERI','20517513','Jl. Pohkecik Desa Tumpakrejo, Gedangan',' Gedangan',' GEDANGAN','Malang','Jawa Timur','HERU NURGIYANTO, S.Pd.Fis','','MKKS-15','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 15',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('16','SMP Negeri 1 GONDANGLEGI','NEGERI','20517484','Jl. Raya Gondanglegi','Gondanglegi','GONDANGLEGI','Malang','Jawa Timur','ERY BASUKI, S.Pd','','MKKS-16','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 16',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('17','SMP Negeri 2 GONDANGLEGI','NEGERI','20517514','Ds. Sukorejo Gondanglegi','Gondanglegi','GONDANGLEGI','Malang','Jawa Timur','UMI KULSUM, S.Pd','','MKKS-17','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 17',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('18','SMP Negeri 1 JABUNG','NEGERI','20517485','Jl. Raden Patah 13',' Sukolilo',' JABUNG','Malang','Jawa Timur','Drs. EDI YUSWANTO','','MKKS-18','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 18',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('19','SMP Negeri 2 JABUNG','NEGERI','20517515','Jl Raya Slamparejo 54','Slamparejo',' JABUNG','Malang','Jawa Timur','Dra. NURUS SOLEHATI','','MKKS-19','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 19',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('20','SMP Negeri 1 KALIPARE','NEGERI','20517486','Jl. Raya Ngembul Kalipare','Kalipare','KALIPARE','Malang','Jawa Timur','SUGENG GIYANTO, S.Pd., M.Pd.','','MKKS-20','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 20',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('21','SMP Negeri 2 KALIPARE','NEGERI','20517516','Sumberpetung','Sumberpetung','KALIPARE','Malang','Jawa Timur','MUHAMMAD JAFAR, S.Pd','','MKKS-21','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 21',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('22','SMP Negeri 1 KARANGPLOSO','NEGERI','20517487','Jl.PB.Sudirman No. 49 Girimoyo','Girimoyo',' KARANGPLOSO','Malang','Jawa Timur',' ARIFIN, S.Pd., M.Pd.','','MKKS-22','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 22',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('23','SMP Negeri 5 KARANGPLOSO','NEGERI','69936635','Jl. Singojoyo - Ngenep Karangploso','Karangploso',' KARANGPLOSO','Malang','Jawa Timur','MAT SALEH, S.Pd','','MKKS-23','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 23',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('24','SMP Negeri 1 KASEMBON','NEGERI','20517488','Jl. Raya 39 Kasembon','Kasembon',' KASEMBON','Malang','Jawa Timur','Dra. TITIK WAHYUNI, M.Pd.','','MKKS-24','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 24',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('25','SMP Negeri 2 KASEMBON','NEGERI','20549206','Jl. Ry No. 98 Pondok Agung','Pondok Agung',' KASEMBON','Malang','Jawa Timur','Dra. TITIK WAHYUNI, M.Pd','','MKKS-25','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 25',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('26','SMP Negeri 1 KEPANJEN','NEGERI','20517475','Jl. Adiwicana 19 Ardirejo','Ardirejo',' KEPANJEN','Malang','Jawa Timur','FARIDA SURTIKANTI, S.Pd, M.Pd','','MKKS-26','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 26',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('27','SMP Negeri 2 KEPANJEN','NEGERI','20517517','Jl. Locari 207   Cepokomulyo','Cepokomulyo',' KEPANJEN','Malang','Jawa Timur','AKHMAD HARNOWO, S.Pd, M.Pd','','MKKS-27','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 27',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('28','SMP Negeri 3 KEPANJEN','NEGERI','20517500','Jl. Raya Sukoharjo No 60 Kepanjen','Sukoharjo',' KEPANJEN','Malang','Jawa Timur','MARGO SUJONO HADI','','MKKS-28','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 28',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('29','SMP Negeri 4 KEPANJEN','NEGERI','20517504','Jl. Kawi No. 3  Cepokomulyo','Cepokomulyo',' KEPANJEN','Malang','Jawa Timur','FARIDA SURTIKANTI, S.Pd., M.Pd','','MKKS-29','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 29',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('30','SMP Negeri 5 KEPANJEN','NEGERI','20517520','Jl. Krajan 144 Sengguruh','Sengguruh',' KEPANJEN','Malang','Jawa Timur','Drs. SINYAMIN, M.Pd','','MKKS-30','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 30',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('31','SMP Negeri 1 KROMENGAN','NEGERI','20517474','Jl. Kramat - Ngadirejo','Ngadirejo','KROMENGAN','Malang','Jawa Timur','AINUL MUTAMAKIN, S.Pd','','MKKS-31','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 31',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('32','SMP Negeri 1 Lawang','NEGERI','20517461','Jl. Sumber Taman No. 50','Kalirejo',' Lawang','Malang','Jawa Timur','EDI SANTOSO, S. Pd., M. Pd ','','MKKS-32','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 32',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('33','SMP Negeri 2 LAWANG','NEGERI','20517518','Jl. Inspol Suwoto 27',' Lawang',' LAWANG','Malang','Jawa Timur','SRI PURWATI, S.Pd','','MKKS-33','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 33',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('34','SMP NEGERI 3 LAWANG','NEGERI','20517501','Jl. Ketindan 185',' Lawang','Lawang','Malang','Jawa Timur','Drs. AHMAD NAJIB BUDAIRI','','MKKS-34','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 34',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('35','SMP Negeri 1 NGAJUM','NEGERI','20517462','Jl. Jatisari 33','Jatisari',' NGAJUM','Malang','Jawa Timur','Drs. KHOLIDUL ADHAR','','MKKS-35','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 35',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('36','SMP Negeri 2 NGAJUM','NEGERI','69938826','Jl. Raya Sukorejo','Sukorejo',' NGAJUM','Malang','Jawa Timur','WORO SULISTYO YEKTI PRIHATININGTYAS, S.Pd, M.Pd','','MKKS-36','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 36',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('37','SMP Negeri 1 NGANTANG','NEGERI','20517463','Jl. Raya - Ngantang','Ngantang','NGANTANG','Malang','Jawa Timur','SAID, S.Pd.','19661219 199001 1 001','MKKS-37','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 37',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-','',NULL);
INSERT INTO `sekolah` VALUES ('38','SMP Negeri 2 NGANTANG','NEGERI','20517519','Ds. Banjarejo','Banjarejo','NGANTANG','Malang','Jawa Timur','NINIT HARTINI, S.Pd','','MKKS-38','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 38',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('39','SMP Negeri 1 PAGAK','NEGERI','20517464','Jl. Gajahmada 90 Sumbermanjingkulon','Sumbermanjingkulon',' PAGAK','Malang','Jawa Timur','MUH SHOLEH MAWARDI, M.Pd','','MKKS-39','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 39',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('40','SMP Negeri 2 PAGAK','NEGERI','20517506','Jl. Batu Putih no 1','Pagak',' PAGAK','Malang','Jawa Timur','SOLIKAN, S.Pd','','MKKS-40','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 40',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('41','SMP Negeri 1 PAGELARAN','NEGERI','20549204','Jl. Keramat No.234 Sidorejo Pagelaran','Pagelaran',' PAGELARAN','Malang','Jawa Timur','LILIK NISWATIN FARIDA, S.Pd, M.Pd','196706281990012001','MKKS-41','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 41',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-','smpn1pagelaran@yahoo.co.id',NULL);
INSERT INTO `sekolah` VALUES ('42','SMP Negeri 1 PAKIS','NEGERI','20517465','Jl.Raya Sumberpasir no 18','Sumberpasir',' PAKIS','Malang','Jawa Timur','MUJI MANGASTUTI, S.Pd','','MKKS-42','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 42',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('43','SMP Negeri 2 PAKIS','NEGERI','20307583','Jl. Gajahmada  Banjarejo','  Banjarejo',' PAKIS','Malang','Jawa Timur','MUSNI YULIASTUTI, M.Pd','','MKKS-43','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 43',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('44','SMP Negeri 1 PAKISAJI','NEGERI','20517466','Kendalpayak','Kendalpayak','PAKISAJI','Malang','Jawa Timur','HANIK LIFDIATI, S.Pd., M.Psi','','MKKS-44','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 44',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('45','SMP Negeri 2 PAKISAJI','NEGERI','20549207','Jl Darungan Desa Glanggang Pakisaji','Pakisaji','PAKISAJI','Malang','Jawa Timur','MAHRUS, S.Ag, M.A.','','MKKS-45','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 45',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('46','SMP Negeri 1 PONCOKUSUMO','NEGERI','20517467','JL. Paras No. 1','Karangnongko','PONCOKUSUMO','Malang','Jawa Timur','MARGO SUJONO HADI, S.Pd, M.Pd','','MKKS-46','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 46',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('47','SMP Negeri 2 PONCOKUSUMO','NEGERI','20517492','Jl. Raya Karangnanyar','Karangnanyar','PONCOKUSUMO','Malang','Jawa Timur','Dra. MARTINA LONA JUSITA, M.Pd','','MKKS-47','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 47',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('48','SMP Negeri 1 PUJON','NEGERI','20517468','Jl. Pondok Asri No. 83 Pandesari','Pandesari',' PUJON','Malang','Jawa Timur','Drs. YUS WAHYU SASMITO','','MKKS-48','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 48',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('49','SMP Negeri 2 PUJON','NEGERI','20517493','Tawangsari','Tawangsari',' PUJON','Malang','Jawa Timur','HARI PURBATIN, S.Pd','','MKKS-49','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 49',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('50','SMP Negeri 1 SINGOSARI','NEGERI','20517469','JL. Raya No. 1 Candirenggo','Candirenggo','SINGOSARI','Malang','Jawa Timur','ANA PURWATI, S.Pd, M.Pd','','MKKS-50','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 50',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('51','SMP Negeri 2 SINGOSARI','NEGERI','20517494','Jl. Klampok Nomor 243','Singosari','SINGOSARI','Malang','Jawa Timur','ENDANG MISWATI, S.Pd, M.Pd','','MKKS-51','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 51',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('52','SMPN 3 SINGOSARI','NEGERI','20517502','Jl. Agarani Pagas','Pagas','SINGOSARI','Malang','Jawa Timur','MEGA ISWANTO W, S.Pd','','MKKS-52','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 52',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('53','SMP Negeri 6 SINGOSARI','NEGERI','69728235','Jl. Perusahaan 20 Tunjung Tirto','Tanjung Tirto','SINGOSARI','Malang','Jawa Timur','ENDANG MISWATI, S.Pd, M.Pd','','MKKS-53','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 53',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('54','SMP Negeri 1 SUMBERMANJING WETAN','NEGERI','20517470','Jl. Raya Harjokuncaran No. 51','Harjokuncaran','SUMBERMANJING WETAN','Malang','Jawa Timur','WIJI INDAYATI, S.PD, M.Pd','197811192006042014','MKKS-54','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 54',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-','wijiindayati91@admin.smp.belajar.id',NULL);
INSERT INTO `sekolah` VALUES ('55','SMP Negeri 2 SUMBERMANJING WETAN','NEGERI','20517495','Jl Raya Sumberagung','Sumberagung','SUMBERMANJING WETAN','Malang','Jawa Timur','AGUS IMAM SYAFII, S.Pd','','MKKS-55','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 55',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('56','SMP Negeri 3 SUMBERMANJING WETAN','NEGERI','20517503','Tambakasri','Tambakasri','SUMBERMANJING WETAN','Malang','Jawa Timur','Drs. ROHMAN WAHYUDI','','MKKS-56','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 56',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('57','SMP NEGERI 1 SUMBERPUCUNG','NEGERI','20517471','Jl. Manggar 28 Karangkates','Karangkates','SUMBERPUCUNG','Malang','Jawa Timur','SUPARIANTO, S.Pd, M.Pd.','','MKKS-57','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 57',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('58','SMP Negeri 2 Sumberpucung','NEGERI','20517496','Jl. TGP No 9 Sumberpucung','Sumberpucung',' SUMBERPUCUNG','Malang','Jawa Timur','DAVIT HARIJONO, S.Pd, M.Pd','','MKKS-58','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 58',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('59','SMP Negeri 1 TAJINAN','NEGERI','20517472','Jl.Raya Gunungsari No.21','Gunungsari',' TAJINAN','Malang','Jawa Timur','Drs. MOHAMAD SULTHON ARIF','','MKKS-59','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 59',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('60','SMP Negeri 1 TIRTOYUDO','NEGERI','20517473','Jl. Raya Gadungsari No. 41','Gadungsari','TIRTOYUDO','Malang','Jawa Timur','Dra. ERNA LUKITAWATI','','MKKS-60','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 60',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('61','SMP Negeri 2 TIRTOYUDO','NEGERI','20517497','Jl. Lapangan - Jogomulyan',' Jogomulyan','Tirtoyudo','Malang','Jawa Timur','Drs. JOKO SUKISWORO','','MKKS-61','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 61',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('62','SMP NEGERI 1 TUMPANG','NEGERI','20517489','Jl. Raya Malangsuko No.22 - Tumpang','Malangsuko','Tumpang','Malang','Jawa Timur','SUNTORO, S.Pd., M.Si., M.M.Pd.','','MKKS-62','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 62',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('63','SMP Negeri 2 TUMPANG','NEGERI','20517498','Jl. Pulungdowo Tumpang','Pulungdowo','TUMPANG','Malang','Jawa Timur','MOHAMMAD WAHIB BASYORI, S.Pd','','MKKS-63','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 63',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('64','SMP Negeri 1 TUREN','NEGERI','20517490','Jl. Panglima Sudirman 1 A',' Turen',' TUREN','Malang','Jawa Timur','SUWARDOYO, S.Pd, M.MPd','','MKKS-64','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 64',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('65','SMP Negeri 2 TUREN','NEGERI','20517499','Jl. Raya Kedok 8 a Turen','Kedok',' TUREN','Malang','Jawa Timur','Drs. TRISNO WIDODO','','MKKS-65','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 65',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('66','SMP Negeri 1 WAGIR','NEGERI','20517491','Jl. Raya Wagir 71 Sitirejo','Sitirejo',' WAGIR','Malang','Jawa Timur','Drs. BUDI PRASETYO','','MKKS-66','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 66',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('67','SMP Negeri 2 WAGIR','NEGERI','20549208','Sukodadi Kec. Wagir','Sukodadi',' WAGIR','Malang','Jawa Timur','MURIADI, S.Pd., M.Pd','','MKKS-67','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 67',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('68','SMP Negeri 1 WAJAK','NEGERI','20517507','Jl. Raya Sukoanyar 504','Sukoanyar',' WAJAK','Malang','Jawa Timur','UMI CHAPSAH, S.Pd, M.M.Pd','','MKKS-68','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 68',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('69','SMP Negeri 1 WONOSARI','NEGERI','20517508','Jl. Raya Bumirejo no 45 Kebobang','Kebobang',' WONOSARI','Malang','Jawa Timur','MOH. MUNIF, S.Pd, M.M.Pd','','MKKS-69','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 69',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('70','SMP Negeri 3 Tirtoyudo Satu Atap','NEGERI','20560082','Jalan Perwira No 5 Desa Tamansatriyan','Tamansatriyan','TIRTOYUDO','Malang','Jawa Timur','SUYATNO','','MKKS-70','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 70',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('71','SMP Negeri 4 Lawang Satu Atap','NEGERI','20561840','Jl. Inspol Suwoto No. 265 SIdoluhur Lawang','Sidoluhur','LAWANG','Malang','Jawa Timur','SUWARTIAH, S.Pd','','MKKS-71','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 71',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('72','SMP Negeri 3 Ngantang Satu Atap','NEGERI','20561842','Dusun Ngembul RT.22 RW. 05 Desa Jombok Kec. Nganta','Jombok','NGANTANG','Malang','Jawa Timur','BUDI RAHAYU','','MKKS-72','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 72',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('73','SMP Negeri 4 Ngantang Satu Atap','NEGERI','20561846','Jl. Raya Sidodadi No 38 Ds. Pagersari Kec. Ngantan','Pagersari','NGANTANG','Malang','Jawa Timur','SULIATI','','MKKS-73','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 73',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('74','SMP Negeri 3 Donomulyo Satu Atap','NEGERI','20561849','Jl. Modangan RT 41 RW 10 Dusun Sumberejo Desa Sumb','Sumberoto','DONOMULYO','Malang','Jawa Timur','Hendri Dwi Siswoyo, S.Pd. SD','','MKKS-74','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 74',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('75','SMP Negeri 4 Ampelgading Satu Atap','NEGERI','20561850','Dusun Argosari RT. 003 RW. 001 Desa Argoyuwono Kec','Argoyuwono','AMPELGADING','Malang','Jawa Timur','JEMADI, S.Pd.SD','','MKKS-75','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 75',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('76','SMP Negeri 3 Tumpang Satu Atap','NEGERI','20561854','Jl.Raya Petungsewu Duwet Tumpang','Duwet','TUMPANG','Malang','Jawa Timur','G KUKUH IKHSANTO, S.Pd','','MKKS-76','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 76',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('77','SMP Negeri 3 Pujon Satu Atap','NEGERI','20561855','JL. RAYA PUJON KIDUL RT. 4 RW. 2, Kec. Pujon - Kab','Pujon','PUJON','Malang','Jawa Timur','ELOK SUKARTI,S.Pd','','MKKS-77','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 77',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('78','SMP Negeri 3 Poncokusumo Satu Atap','NEGERI','20561857','Jl. Raya Ngadas RT.004 RW.001 Desa Ngadas Kecamata','Ngadas','PONCOKUSUMO','Malang','Jawa Timur','ZAKARIA ABDUL HARYS, S.Pd.','','MKKS-78','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 78',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('79','SMP Negeri 4 Singosari Satu Atap','NEGERI','20561859','Jalan Wonorejo Kecamatan Singosari','Wonorejo','SINGOSARI','Malang','Jawa Timur','SUSI ARDIANTINI, S.Pd','','MKKS-79','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 79',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('80','SMP Negeri 4 Bantur Satu Atap','NEGERI','20561860','Dusun Sumberwaluh Ds. Pringgodani Kec. Bantur Kab.','Pringgondani','BANTUR','Malang','Jawa Timur','KAWIT, S.Pd','','MKKS-80','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 80',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('81','SMP NEGERI 5 BANTUR SATU ATAP','NEGERI','20561861','DUSUN JUBEL RT. 50 RW. 11','Bantur','BANTUR','Malang','Jawa Timur','ADI SUHARTO, S.Pd','','MKKS-81','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 81',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('82','SMP Negeri 3 Kalipare Satu Atap','NEGERI','20561864','JL.Soekarno-Hatta 307 Putukrejo,Kec.Kalipare,Kab.M','Putukrejo','KALIPARE','Malang','Jawa Timur','DULATIP,S.Pd','','MKKS-82','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 82',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('83','SMP Negeri 2 Karangploso Satu Atap','NEGERI','20561867','Dusun Borogragal Donowarih Kecamatan Karangploso','Donowarih','KARANGPLOSO','Malang','Jawa Timur','Bayu Winarno, S.Pd','','MKKS-83','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 83',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('84','SMP Negeri 3 Karangplosos Satu Atap','NEGERI','20561868','Dusun Tumpangrejo, Desa Ngenep, Kab. Malang','Ngenep','KARANGPLOSO','Malang','Jawa Timur','WIENNOTO, S.Pd','','MKKS-84','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 84',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('85','SMP NEGERI 3 PAGAK SATU ATAP','NEGERI','20561872','DUSUN SUMBERWADER DESA SUMBERKERTO JALAN SUNAN GIR','Simberkerto','PAGAK','Malang','Jawa Timur','SUTARDI,S.Pd','','MKKS-85','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 85',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('86','SMP NEGERI 4 SUMBERMANJING WETAN SATU ATAP','NEGERI','20561874','Jl. Marto Sugondo RT.06 RW.02 Tambakrejo','Tambakrejo','SUMBERMANJING WETAN','Malang','Jawa Timur','NYUWITO, S.Pd., M.Pd.','','MKKS-86','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 86',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('87','SMP Negeri 5 Sumbermanjing Wetan Satu Atap','NEGERI','20561875','Jalan Raya Tegalrejo Rt 02 Rw 01 Tegalrejo Sumberm','Tegalrejo','SUMBERMANJING WETAN','Malang','Jawa Timur','SUPRIYADI','','MKKS-87','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 87',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('88','SMP Negeri 2 Wajak Satu Atap','NEGERI','20566334','Jalan Semeri No.101 Desa Bambang','Bambang','WAJAK','Malang','Jawa Timur','KUNARYADI,S.Pd','','MKKS-88','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 88',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('89','SMP Negeri 4 Karangploso Satu Atap','NEGERI','20566335','Dusun Supiturang Desa Bocek','Bocek','KARANGPLOSO','Malang','Jawa Timur','HARI PURNOMO, S.Pd, M.Pd','','MKKS-89','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 89',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('90','SMP Negeri 2 Dau Satu Atap','NEGERI','20570243','Jl.Raya Klaseman 16 Desa Kucur Kec Dau Kab Malang','Kucur','DAU','Malang','Jawa Timur','SUTIKNO,S.Pd','','MKKS-90','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 90',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('91','SMP Negeri 5 Singosari Satu Atap','NEGERI','20570271','Dusun Sumbul RT.02 RW.08  Desa Klampok Kecamatan S','Klampok','SINGOSARI','Malang','Jawa Timur','Anas Fachruddin, S.Pd., M.Pd ','','MKKS-91','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 91',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('92','SMP NEGERI 6 LAWANG SATU ATAP','NEGERI','20570272','DUKUH GUNUNG TUMPUK DESA SIDOLUHUR LAWANG, MALANG','Sidoluhur','LAWANG','Malang','Jawa Timur','Drs.Gunarto,M.M.Pd.','','MKKS-92','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 92',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('93','SMP NEGERI 4 PONCOKUSUMO SATU ATAP','NEGERI','20570904','JL. Raya Dusun Sumberejo Desa Sumberejo Kec. Ponco','Sumberejo','PONCOKUSUMO','Malang','Jawa Timur','KUSMIADI, S.Pd.SD.','','MKKS-93','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 93',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('94','SMPN 4 PUJON SATU ATAP','NEGERI','20561858','Jl. Moch. Said No 238 Bendosari','Bendosari','PUJON','Malang','Jawa Timur','Drs. Sulis Setyono','','MKKS-94','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 94',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('95','SMP NEGERI 3 JABUNG SATU ATAP','NEGERI','20561863','Jln Astina No20 Sidomulyo, Kecamatan Jabung','Sidomulyao','JABUNG','Malang','Jawa Timur','KHOIRUL HUDA S.Pd, ','','MKKS-95','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 95',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('96','SMP NEGERI 5 LAWANG','NEGERI','20559935','Jl.Inspol Suwoto Dusun Mendek','Lawang','LAWANG','Malang','Jawa Timur','TUTIK RETNO WAHYUNINGSIH,S.Pd','','MKKS-96','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 96',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);
INSERT INTO `sekolah` VALUES ('97','SMP NEGERI 5 AMPELGADING SATU ATAP','NEGERI','20561852','Jl Lapangan','Tamansari','Ampelgading','Malang','Jawa Timur','SULIADI, S.Pd.SD','','MKKS-97','Asia/Jakarta','M4L4N9KJ9vUTCuZwEdis','PROKTOR 97',NULL,'1','https://ujian.mkkskabmalang.com','SMP','0','-',NULL,NULL);

/*---------------------------------------------------------------
  TABLE: `sesi`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sesi`;
CREATE TABLE `sesi` (
  `kode_sesi` varchar(10) NOT NULL,
  `nama_sesi` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_sesi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `sesi` VALUES   ('1','1');
INSERT INTO `sesi` VALUES ('2','2');
INSERT INTO `sesi` VALUES ('3','3');

/*---------------------------------------------------------------
  TABLE: `sinkron`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `sinkron`;
CREATE TABLE `sinkron` (
  `id` int NOT NULL AUTO_INCREMENT,
  `npsn` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `waktu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sts` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `sinkron` VALUES   ('1','20517509','22 May 2024 22:57:15','1');
INSERT INTO `sinkron` VALUES ('2','20517509','22:57:25','1');
INSERT INTO `sinkron` VALUES ('3','20517509','23 May 2024 10:15:39','1');
INSERT INTO `sinkron` VALUES ('4','20517509','10:15:45','1');
INSERT INTO `sinkron` VALUES ('5','20517509','23 May 2024 12:52:20','1');
INSERT INTO `sinkron` VALUES ('6','20517509','12:52:28','1');
INSERT INTO `sinkron` VALUES ('7','20517509','13:04:49','1');
INSERT INTO `sinkron` VALUES ('8','20517509','13:06:27','1');
INSERT INTO `sinkron` VALUES ('9','20517509','13:07:13','1');
INSERT INTO `sinkron` VALUES ('10','20517509','13:07:50','1');
INSERT INTO `sinkron` VALUES ('11','20517509','13:08:02','1');
INSERT INTO `sinkron` VALUES ('12','20517509','15:32:37','1');
INSERT INTO `sinkron` VALUES ('13','20517509','23 May 2024 19:09:39','1');
INSERT INTO `sinkron` VALUES ('14','20517509','23 May 2024 19:14:15','1');
INSERT INTO `sinkron` VALUES ('15','20517509','20:06:49','1');
INSERT INTO `sinkron` VALUES ('16','20517486','24 May 2024 18:39:47','1');
INSERT INTO `sinkron` VALUES ('17','20517486','18:40:11','1');
INSERT INTO `sinkron` VALUES ('18','20517509','24 May 2024 18:52:59','1');
INSERT INTO `sinkron` VALUES ('19','20517509','18:53:12','1');

/*---------------------------------------------------------------
  TABLE: `siswa`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `siswa`;
CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL AUTO_INCREMENT,
  `no_peserta` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `nis` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `level` int DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `pk` varchar(50) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `jk` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `server` varchar(50) DEFAULT NULL,
  `sesi` int DEFAULT '1',
  `online` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1137 DEFAULT CHARSET=latin1;
INSERT INTO `siswa` VALUES   ('1','P-20517509-1','PAT20517509-1','20517509-1-Rpk','6756','ABIWANTA RIZKY WIDYA AGUNG','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('2','P-20517509-2','PAT20517509-2','20517509-2-HNG','6771','AISYAH TRI CAHYA','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('3','P-20517509-3','PAT20517509-3','20517509-3-1ul','6772','AISYAH VARDA URIFA','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('4','P-20517509-4','PAT20517509-4','20517509-4-ad2','6779','ALVERO DHIKO LEVANO','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('5','P-20517509-5','PAT20517509-5','20517509-5-JBR','6782','ANAVELIA FRANSISCA SIMANJUNTAK','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('6','P-20517509-6','PAT20517509-6','20517509-6-xk1','6786','ANDREAS NOVA ANDRIANO','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('7','P-20517509-7','PAT20517509-7','20517509-7-7CN','6800','AUREL GRESIASEPTIANA','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('8','P-20517509-8','PAT20517509-8','20517509-8-54W','6802','AYESSHA SILVIA AMELLYA','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('9','P-20517509-9','PAT20517509-9','20517509-9-c7T','6805','BELLA AYU INDAH SARI','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('10','P-20517509-10','PAT20517509-10','20517509-10-IGC','6816','DANELA CRISTIANE','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('11','P-20517509-11','PAT20517509-11','20517509-11-BO7','6820','DANY SAFAAD','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('12','P-20517509-12','PAT20517509-12','20517509-12-wFt','6819','DENIS SABRINA','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('13','P-20517509-13','PAT20517509-13','20517509-13-P4e','6835','FADILLAH RAMADHANI','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('14','P-20517509-14','PAT20517509-14','20517509-14-XzF','6836','FAKHRI RAHMAD JULIAN','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('15','P-20517509-15','PAT20517509-15','20517509-15-97D','6846','FIRA RAHAYU DWIYANING P','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('16','P-20517509-16','PAT20517509-16','20517509-16-XeH','6850','Gantari Sastra Paramadiwa','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('17','P-20517509-17','PAT20517509-17','20517509-17-720','6853','GILANG ANDRIANTAMA','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('18','P-20517509-18','PAT20517509-18','20517509-18-Zce','6859','HERI PRASETYO','7','VII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('19','P-20517509-19','PAT20517509-19','20517509-19-Wa1','6867','IRFAN ANTONY FAUZAN IBNI','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('20','P-20517509-20','PAT20517509-20','20517509-20-n2F','6873','KARINA MEGA KASIH','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('21','P-20517509-21','PAT20517509-21','20517509-21-JYC','6884','LUTFI AVRILIA','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('22','P-20517509-22','PAT20517509-22','20517509-22-6Ex','6900','MUHAMMAD ALIF WALID MAULIDDIN','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('23','P-20517509-23','PAT20517509-23','20517509-23-orF','6933','NICO ANDREAN HASAN EFENDI','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('24','P-20517509-24','PAT20517509-24','20517509-24-f1h','6944','PURI AYU CHRISTIANI','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('25','P-20517509-25','PAT20517509-25','20517509-25-LIn','6951','RAHMAT RENDI SANTOSO','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('26','P-20517509-26','PAT20517509-26','20517509-26-hxv','6952','RAIHAN NUR FATHONI','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('27','P-20517509-27','PAT20517509-27','20517509-27-tmb','6965','Riko Yoji Zebrian','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('28','P-20517509-28','PAT20517509-28','20517509-28-KXC','6971','ROHMAN ALFIANSYAH','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('29','P-20517509-29','PAT20517509-29','20517509-29-qb2','6972','RUSTALINO ADE ENDARTO','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('30','P-20517509-30','PAT20517509-30','20517509-30-RJp','6973','RYAN AHMAD AFFANDI','7','VII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('31','P-20517509-31','PAT20517509-31','20517509-31-fyO','6978','SEPTARIA EKA KRISTANTI','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('32','P-20517509-32','PAT20517509-32','20517509-32-gqh','6982','STEVANIA HARVIANING CRISTIANI','7','VII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('33','P-20517509-33','PAT20517509-33','20517509-33-g3R','6992','WIDYA LESTARI','7','VII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('34','P-20517509-34','PAT20517509-34','20517509-34-yIu','6757','ACHMAD ARIFIN','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('35','P-20517509-35','PAT20517509-35','20517509-35-W6d','6769','AISAH CINDY PRATAMA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('36','P-20517509-36','PAT20517509-36','20517509-36-m0i','6770','AISYAH NUR RAHMA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('37','P-20517509-37','PAT20517509-37','20517509-37-DWX','6773','Aji Wahyudi','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('38','P-20517509-38','PAT20517509-38','20517509-38-FDc','6785','ANDRE RIZKY YULIANTO','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('39','P-20517509-39','PAT20517509-39','20517509-39-0q8','6788','ANGGUN RITA AMELIA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('40','P-20517509-40','PAT20517509-40','20517509-40-zkr','6789','ANISA KURNIA ISTIANI','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('41','P-20517509-41','PAT20517509-41','20517509-41-Xbu','6804','BAGAS PRASETYA','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('42','P-20517509-42','PAT20517509-42','20517509-42-yra','6807','BILAL AHMAD','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('43','P-20517509-43','PAT20517509-43','20517509-43-1Uw','6818','DARIL ALIF ZULKARNAEN','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('44','P-20517509-44','PAT20517509-44','20517509-44-8xG','6821','Derian Putra Pratama','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('45','P-20517509-45','PAT20517509-45','20517509-45-b7f','6834','ERISTA VELANICA PUTRI','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('46','P-20517509-46','PAT20517509-46','20517509-46-zQx','6837','FARAH NADIA TAUFIQY','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('47','P-20517509-47','PAT20517509-47','20517509-47-s0N','6845','FERNANDO VICKY ALVIANSAH','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('48','P-20517509-48','PAT20517509-48','20517509-48-sqc','6849','GABRIELLA NATAZHA SALSABILLA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('49','P-20517509-49','PAT20517509-49','20517509-49-xyF','6854','GIOVANO HERNINO SAPUTRA','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('50','P-20517509-50','PAT20517509-50','20517509-50-th0','6866','IQBAL JAUHAR RAVANDA','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('51','P-20517509-51','PAT20517509-51','20517509-51-Qjx','6872','IZZATUL HASANAH RAFIATUZ ZAHRO','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('52','P-20517509-52','PAT20517509-52','20517509-52-KWF','6882','LEITISYA ZEINNARA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('53','P-20517509-53','PAT20517509-53','20517509-53-fG5','6885','M. AJI FIKI RAHMATDANI SOFIULLOH','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('54','P-20517509-54','PAT20517509-54','20517509-54-MIs','6890','MILA PUTRI MIRANDA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('55','P-20517509-55','PAT20517509-55','20517509-55-03E','6895','MOH. AVATAR','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('56','P-20517509-56','PAT20517509-56','20517509-56-WHN','6905','MUHAMMAD HABIBI','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('57','P-20517509-57','PAT20517509-57','20517509-57-zGE','6918','Muhhamad Rudianto','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('58','P-20517509-58','PAT20517509-58','20517509-58-1yo','6921','NABHAN RADINKA KEVAN','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('59','P-20517509-59','PAT20517509-59','20517509-59-omA','6932','NICO ALFIANO PRATAMA','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('60','P-20517509-60','PAT20517509-60','20517509-60-nC7','6950','RAHMAD RIVALDI','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('61','P-20517509-61','PAT20517509-61','20517509-61-uzm','6953','RAISYA SOFIA BUNGA FIRDAUS','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('62','P-20517509-62','PAT20517509-62','20517509-62-1US','6954','RANIKA ARUM PRATIWI','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('63','P-20517509-63','PAT20517509-63','20517509-63-Lr6','6955','RATNA ANIZAH','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('64','P-20517509-64','PAT20517509-64','20517509-64-msW','6969','Riski Maulana','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('65','P-20517509-65','PAT20517509-65','20517509-65-dJv','6974','SABIAN SYAUQI ARATA','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('66','P-20517509-66','PAT20517509-66','20517509-66-CWb','6975','SALSA WULAN DELIMA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('67','P-20517509-67','PAT20517509-67','20517509-67-euy','6988','VILWA SYEIRA EN NADIA','7','VII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('68','P-20517509-68','PAT20517509-68','20517509-68-GI4','6993','WIGNYO ADAM','7','VII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('69','P-20517509-69','PAT20517509-69','20517509-69-BdQ','6758','ADELIA DWI NURFADILA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('70','P-20517509-70','PAT20517509-70','20517509-70-wDp','6765','AHMAT','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('71','P-20517509-71','PAT20517509-71','20517509-71-Xc7','6768','AIRIN KHORIZAH NUR RAHMAH','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('72','P-20517509-72','PAT20517509-72','20517509-72-oT9','6784','ANDIKA NUR FURQON NASRULLAH','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('73','P-20517509-73','PAT20517509-73','20517509-73-OuM','6791','ANITA KURNIAWATI','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('74','P-20517509-74','PAT20517509-74','20517509-74-2Xh','6803','AYNA ROIFFATUL AZIZAH','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('75','P-20517509-75','PAT20517509-75','20517509-75-7st','6808','BILGHIS AZZAHRA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('76','P-20517509-76','PAT20517509-76','20517509-76-vYr','6822','DIAN ILHAM AJI ROHIM','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('77','P-20517509-77','PAT20517509-77','20517509-77-zYL','6833','ELVYN ANDHIKA PUTRA PRATAMA','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('78','P-20517509-78','PAT20517509-78','20517509-78-lC1','6838','FARHAN NUR HUDA','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('79','P-20517509-79','PAT20517509-79','20517509-79-evb','6848','FITRAH HANUM NIMASAYU','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('80','P-20517509-80','PAT20517509-80','20517509-80-5yF','6855','GRIZTA BAYU FEBRIAN FAJAR YUWONO','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('81','P-20517509-81','PAT20517509-81','20517509-81-bMm','6865','INGGAR AYU NISWARI','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('82','P-20517509-82','PAT20517509-82','20517509-82-4au','6870','ISYA DIKRI SUDRAJAD','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('83','P-20517509-83','PAT20517509-83','20517509-83-6Gs','6874','KARUNIA EKA PUTRI','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('84','P-20517509-84','PAT20517509-84','20517509-84-Uq3','6881','LALA PUTRI MAHARANI','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('85','P-20517509-85','PAT20517509-85','20517509-85-W4c','6886','M. FAKHRI ADIS AL-FIKRI','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('86','P-20517509-86','PAT20517509-86','20517509-86-WIZ','6894','MOCHAMAD JUAN RAFANDA YUSWADI','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('87','P-20517509-87','PAT20517509-87','20517509-87-03K','6898','MUHAMMAD DAVIN PRAYOGA','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('88','P-20517509-88','PAT20517509-88','20517509-88-974','6906','MUHAMMAD HARIS ALFARIZI','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('89','P-20517509-89','PAT20517509-89','20517509-89-6ro','6917','MUHAMMAD SYAQIF ALI MAHRUS','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('90','P-20517509-90','PAT20517509-90','20517509-90-38W','6920','MUTIARA EQUILA KHAIRUNNISA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('91','P-20517509-91','PAT20517509-91','20517509-91-ZS6','6922','NABILA AURELIA PUTRI SUSANTO','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('92','P-20517509-92','PAT20517509-92','20517509-92-qDO','6931','NAYLA SABANINA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('93','P-20517509-93','PAT20517509-93','20517509-93-sYv','6937','NOWHA ABDUL AZIZ','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('94','P-20517509-94','PAT20517509-94','20517509-94-Gm9','6948','QIBRAN AHMAD FARIQIN','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('95','P-20517509-95','PAT20517509-95','20517509-95-x0r','6968','RISA DEWI ANDINI','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('96','P-20517509-96','PAT20517509-96','20517509-96-NLI','6976','SANDI IRAWAN','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('97','P-20517509-97','PAT20517509-97','20517509-97-Qbo','6987','VICHO MADA ADHYASTA','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('98','P-20517509-98','PAT20517509-98','20517509-98-uVE','6990','VIVIAN RISKY FASHA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('99','P-20517509-99','PAT20517509-99','20517509-99-5Df','6991','WALIT HASIN AHMAD','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('100','P-20517509-100','PAT20517509-100','20517509-100-CHw','6994','Wildan Al Amin','7','VII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('101','P-20517509-101','PAT20517509-101','20517509-101-4Dh','7003','ZIVANA KEYSA OLIVIA','7','VII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('102','P-20517509-102','PAT20517509-102','20517509-102-r1d','6759','AFGAN ZANUAR PUTRA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('103','P-20517509-103','PAT20517509-103','20517509-103-2Up','6767','AIRA ANAFATHUL KHUSNA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('104','P-20517509-104','PAT20517509-104','20517509-104-kNY','6775','ALEX SAFURA ALZAHRA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('105','P-20517509-105','PAT20517509-105','20517509-105-zqG','6783','ANDHIKA GULANG MAHAREZA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('106','P-20517509-106','PAT20517509-106','20517509-106-hIL','6792','APRILIA EKA NURAINI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('107','P-20517509-107','PAT20517509-107','20517509-107-xsV','6801','AVRILIA VIAGIO','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('108','P-20517509-108','PAT20517509-108','20517509-108-tA1','6809','BRYAN RAMADHANI','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('109','P-20517509-109','PAT20517509-109','20517509-109-3Fg','6823','DIMAS TITONIO PRAKASA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('110','P-20517509-110','PAT20517509-110','20517509-110-aIj','6832','ELSA FEBIOLLA REGINA ANDRIANI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('111','P-20517509-111','PAT20517509-111','20517509-111-kQ1','6839','FARID ADITYA FIGO','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('112','P-20517509-112','PAT20517509-112','20517509-112-JE5','6847','FITRA NUR AKBAR','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('113','P-20517509-113','PAT20517509-113','20517509-113-V5K','6856','Hafiz Juli Ananta','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('114','P-20517509-114','PAT20517509-114','20517509-114-t50','6864','INDIRA SILA FARADILA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('115','P-20517509-115','PAT20517509-115','20517509-115-IEp','6868','IRMA SILVI ANINDYA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('116','P-20517509-116','PAT20517509-116','20517509-116-cbW','6871','ISMA`UL HUSNA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('117','P-20517509-117','PAT20517509-117','20517509-117-BcG','6880','Khusnul Khotimah','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('118','P-20517509-118','PAT20517509-118','20517509-118-kJd','6887','MAGFIROTUL HASANAH','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('119','P-20517509-119','PAT20517509-119','20517509-119-you','6897','MUHAMAD ALDIANU','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('120','P-20517509-120','PAT20517509-120','20517509-120-E82','6901','MUHAMMAD DHANUR SYEEH SYCHAN','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('121','P-20517509-121','PAT20517509-121','20517509-121-6Tv','6907','MUHAMMAD LUTFI RAMADHANI','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('122','P-20517509-122','PAT20517509-122','20517509-122-1nU','6913','MUHAMMAD RIDWAN','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('123','P-20517509-123','PAT20517509-123','20517509-123-VaT','6916','MUHAMMAD SABRI AL GHANI','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('124','P-20517509-124','PAT20517509-124','20517509-124-8bE','6923','NADIA ELISIYA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('125','P-20517509-125','PAT20517509-125','20517509-125-ahx','6930','NAILA FEBRIANA ANDINI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('126','P-20517509-126','PAT20517509-126','20517509-126-2fK','6938','NURUL SYAFIRA','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('127','P-20517509-127','PAT20517509-127','20517509-127-JE1','6939','OKTAVIA MONIX VARIZQI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('128','P-20517509-128','PAT20517509-128','20517509-128-d6R','6941','PRAGA SAPUTRA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('129','P-20517509-129','PAT20517509-129','20517509-129-PV7','6947','PUTRI NAZILLA RAMADHANI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('130','P-20517509-130','PAT20517509-130','20517509-130-yrI','6956','RAIHAN NABIL AFGANY','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('131','P-20517509-131','PAT20517509-131','20517509-131-Ntz','6957','REGITA CAHYANI','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('132','P-20517509-132','PAT20517509-132','20517509-132-NzV','6963','RIDHO CATUR MULYONO','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('133','P-20517509-133','PAT20517509-133','20517509-133-s0F','6967','RIO PUTRA WAHYU PRATAMA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('134','P-20517509-134','PAT20517509-134','20517509-134-Fgc','6977','SENDYN EZA FANTORA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('135','P-20517509-135','PAT20517509-135','20517509-135-E82','6986','SYAHRIAL GUSTIANANDA','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('136','P-20517509-136','PAT20517509-136','20517509-136-5Qm','6995','WIMBA INDAH RAHAYU','7','VII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('137','P-20517509-137','PAT20517509-137','20517509-137-rJc','7002','ZAKARIA FATHURROSI','7','VII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('138','P-20517509-138','PAT20517509-138','20517509-138-tcM','6760','Aginta Rajif Pratama Putra','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('139','P-20517509-139','PAT20517509-139','20517509-139-9He','6766','AIDATUL YUSRONIA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('140','P-20517509-140','PAT20517509-140','20517509-140-jc3','6776','ALFYAN TRI ILHAM RIZQI','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('141','P-20517509-141','PAT20517509-141','20517509-141-Eeo','6787','ANGGI RATU FELISA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('142','P-20517509-142','PAT20517509-142','20517509-142-FKL','6790','ANISTASA SAHRA SAFITRI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('143','P-20517509-143','PAT20517509-143','20517509-143-Gdy','6794','ARISCAWATY NUR ATALYA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('144','P-20517509-144','PAT20517509-144','20517509-144-hW3','6799','AURA CAHYA KIRANA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('145','P-20517509-145','PAT20517509-145','20517509-145-KrP','6810','CAESAR RIAU DHINUARDA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('146','P-20517509-146','PAT20517509-146','20517509-146-95k','6815','CYNTIA NOVITA DEWI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('147','P-20517509-147','PAT20517509-147','20517509-147-ogQ','6817','DANI PUTRA WARDANA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('148','P-20517509-148','PAT20517509-148','20517509-148-Ws3','6824','DINA MULYASARI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('149','P-20517509-149','PAT20517509-149','20517509-149-d0E','6831','EL RUMMY GASTIADIRRIJAL','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('150','P-20517509-150','PAT20517509-150','20517509-150-lJD','6840','FARIS','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('151','P-20517509-151','PAT20517509-151','20517509-151-xDb','6852','GHEFFIRA HYURI ALIYYA YUWONO','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('152','P-20517509-152','PAT20517509-152','20517509-152-P3q','6857','HAFIZHA MUMAYYAZAH','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('153','P-20517509-153','PAT20517509-153','20517509-153-V95','6863','IKHWANUL KIROM','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('154','P-20517509-154','PAT20517509-154','20517509-154-Ixa','6879','KHUSNUL HOTIMAH','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('155','P-20517509-155','PAT20517509-155','20517509-155-xgl','6888','MALVIN SANJAYA APRILIO','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('156','P-20517509-156','PAT20517509-156','20517509-156-1c3','6899','MUHAMMAD ALIF VIAN JUNIAR AKBAR','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('157','P-20517509-157','PAT20517509-157','20517509-157-CzB','6908','MUHAMMAD RADITYA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('158','P-20517509-158','PAT20517509-158','20517509-158-wqv','6915','MUHAMMAD RISKI ADITYA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('159','P-20517509-159','PAT20517509-159','20517509-159-Vr3','6919','MUTIARA DEWI ANDHINI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('160','P-20517509-160','PAT20517509-160','20517509-160-SNf','6924','NASA ANAYA PUTRI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('161','P-20517509-161','PAT20517509-161','20517509-161-KT3','6929','NAUFAL ARIFQI','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('162','P-20517509-162','PAT20517509-162','20517509-162-krd','6949','NAYLA ABER','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('163','P-20517509-163','PAT20517509-163','20517509-163-PEV','6936','Noval Ardian R','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('164','P-20517509-164','PAT20517509-164','20517509-164-oTs','6940','ORYSA SAVITA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('165','P-20517509-165','PAT20517509-165','20517509-165-Zgx','6958','REIHAN AKBAR SYAPUTRA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('166','P-20517509-166','PAT20517509-166','20517509-166-cRD','6966','RIO FITRA EVANDIANSYAH','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('167','P-20517509-167','PAT20517509-167','20517509-167-QNO','6985','SURYA DEWI KARTIKA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('168','P-20517509-168','PAT20517509-168','20517509-168-839','6989','VINO SENO VALENTA','7','VII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('169','P-20517509-169','PAT20517509-169','20517509-169-ZYX','6996','WINDA FITRI RAHMAWATI','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('170','P-20517509-170','PAT20517509-170','20517509-170-v4i','7001','ZAHRA YUAN FADHILLA','7','VII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('171','P-20517509-171','PAT20517509-171','20517509-171-H4m','6761','AHMAD ALFANDI TAJJUDIN AZHAR','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('172','P-20517509-172','PAT20517509-172','20517509-172-dbW','6764','AHMAD MARCO EKY PRATAMA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('173','P-20517509-173','PAT20517509-173','20517509-173-jAd','6777','ALKINDY FATH AVICENA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('174','P-20517509-174','PAT20517509-174','20517509-174-nmu','6781','Amelia Nur Aisah','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('175','P-20517509-175','PAT20517509-175','20517509-175-13d','6795','ARIVA ROSALIA PRATIWI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('176','P-20517509-176','PAT20517509-176','20517509-176-ywl','6798','AUFA NADIA KHANIFAH','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('177','P-20517509-177','PAT20517509-177','20517509-177-KOy','6806','BHEBYANA PUTRI DIAH FAUZI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('178','P-20517509-178','PAT20517509-178','20517509-178-JbX','6811','CAHYA TRIYASBUDI PERMANA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('179','P-20517509-179','PAT20517509-179','20517509-179-MEK','6814','CITRA MELANI PUTRI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('180','P-20517509-180','PAT20517509-180','20517509-180-7J4','6825','DINDA SALSABILA','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('181','P-20517509-181','PAT20517509-181','20517509-181-z4D','6826','DINDA YULIA PUTRI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('182','P-20517509-182','PAT20517509-182','20517509-182-jxE','6830','EKA AGUSTIAN RAMADHANI','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('183','P-20517509-183','PAT20517509-183','20517509-183-WJV','6841','FATAN NOVIANO PUTRA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('184','P-20517509-184','PAT20517509-184','20517509-184-htj','6844','FELICIA PUTRI ARIFIN','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('185','P-20517509-185','PAT20517509-185','20517509-185-m6f','6858','HAYDAN RAMADDANI','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('186','P-20517509-186','PAT20517509-186','20517509-186-RNO','6862','IKA VERA HERAWATI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('187','P-20517509-187','PAT20517509-187','20517509-187-eqR','6878','KEISYA PRATITA AZ ZAHRA','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('188','P-20517509-188','PAT20517509-188','20517509-188-TpJ','6875','KEVIN ADITYA SAPUTRA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('189','P-20517509-189','PAT20517509-189','20517509-189-FNg','6889','MARVIN FIRMANDO','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('190','P-20517509-190','PAT20517509-190','20517509-190-gzo','6893','MOCHAMAD HYZAM','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('191','P-20517509-191','PAT20517509-191','20517509-191-pfs','6914','MOHAMMAD RIZKY ADITYA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('192','P-20517509-192','PAT20517509-192','20517509-192-R1Q','6896','MUHAMAD IRFAN AFANDI','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('193','P-20517509-193','PAT20517509-193','20517509-193-DQk','6903','MUHAMMAD FAREL','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('194','P-20517509-194','PAT20517509-194','20517509-194-SM2','6909','MUHAMMAD RAEVALDO EKA PUTRA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('195','P-20517509-195','PAT20517509-195','20517509-195-sZ8','6925','NASAF PUTRA SAFRIANO','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('196','P-20517509-196','PAT20517509-196','20517509-196-70I','6928','NATASYA FITRIA ENJELITA','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('197','P-20517509-197','PAT20517509-197','20517509-197-mGt','6942','PRICILYA RAMADHANI','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('198','P-20517509-198','PAT20517509-198','20517509-198-jPk','6945','PUTRA ANANDA ADI IRAWAN','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('199','P-20517509-199','PAT20517509-199','20517509-199-9Rd','6946','Putri Auhiyatul Mauliyah','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('200','P-20517509-200','PAT20517509-200','20517509-200-Y3X','6959','REVAN ANDIKA NANDA PUTRA PRATAMA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('201','P-20517509-201','PAT20517509-201','20517509-201-9wP','6962','Rico Aditia Wardana','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('202','P-20517509-202','PAT20517509-202','20517509-202-M6r','6964','RIFKI DENDI PERMANA','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('203','P-20517509-203','PAT20517509-203','20517509-203-Efb','6979','SHABRINA MAZIIDATUL MUTHIAH','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('204','P-20517509-204','PAT20517509-204','20517509-204-2WD','6984','STEVINO ANANDA NIKOLAS','7','VII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('205','P-20517509-205','PAT20517509-205','20517509-205-kf7','6997','WINDA RATNA ANTIKA','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('206','P-20517509-206','PAT20517509-206','20517509-206-3EC','7000','ZAHRA AVRILIANTO','7','VII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('207','P-20517509-207','PAT20517509-207','20517509-207-iLk','6762','AHMAD ALFARIZI','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('208','P-20517509-208','PAT20517509-208','20517509-208-kmL','6763','AHMAD FAIZAL RAMADHANI NURROSYID','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('209','P-20517509-209','PAT20517509-209','20517509-209-MuV','6778','ALMIRA RADIATUL SOLIHA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('210','P-20517509-210','PAT20517509-210','20517509-210-gP1','6780','ALVINO ARDIANSYAH PUTRA SETYAWAN','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('211','P-20517509-211','PAT20517509-211','20517509-211-aiH','6793','APRILIA FIRDAUS','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('212','P-20517509-212','PAT20517509-212','20517509-212-GRo','6796','ARVITA KHOIRUNNISA SALSABILA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('213','P-20517509-213','PAT20517509-213','20517509-213-ZQw','6797','ARYA SAPUTRA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('214','P-20517509-214','PAT20517509-214','20517509-214-jUw','6812','CHEYLA NATASHA ALEA ZAHRA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('215','P-20517509-215','PAT20517509-215','20517509-215-W4O','6813','CHRISTIAN ARVINNO ARDIANSYAH','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('216','P-20517509-216','PAT20517509-216','20517509-216-Aga','6827','DHIVA AZIZATUL MUZAYYANA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('217','P-20517509-217','PAT20517509-217','20517509-217-h9g','6828','EGA DIAZ NOVANDRA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('218','P-20517509-218','PAT20517509-218','20517509-218-EsX','6829','EGO DENDY PRASETYA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('219','P-20517509-219','PAT20517509-219','20517509-219-u5g','6842','FATTAH ABIMANYU','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('220','P-20517509-220','PAT20517509-220','20517509-220-MNZ','6843','FELIA DUWITA SARI','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('221','P-20517509-221','PAT20517509-221','20517509-221-XtM','6860','Hidans Adityo Putra','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('222','P-20517509-222','PAT20517509-222','20517509-222-ex3','6861','HORZETSA RISKI ISYAZARO','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('223','P-20517509-223','PAT20517509-223','20517509-223-MBr','6876','KEVIN RAZEL PRASETYA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('224','P-20517509-224','PAT20517509-224','20517509-224-SCl','6877','KHANAYA SAFA NOR AISHA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('225','P-20517509-225','PAT20517509-225','20517509-225-63I','6892','MOCH.MORENO YOGA PRATAMA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('226','P-20517509-226','PAT20517509-226','20517509-226-0Wr','6891','MOCHAMMAD IZAL AL FIRDAUS','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('227','P-20517509-227','PAT20517509-227','20517509-227-pyN','6911','MUHAMAD RAFIK ISLAM ASRAQI','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('228','P-20517509-228','PAT20517509-228','20517509-228-suL','6902','MUHAMMAD EKA PRATAMA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('229','P-20517509-229','PAT20517509-229','20517509-229-9xY','6910','MUHAMMAD RAFA AZAM RABBANI','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('230','P-20517509-230','PAT20517509-230','20517509-230-ovk','6926','NASYA ADILA RAMADHANI','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('231','P-20517509-231','PAT20517509-231','20517509-231-cKQ','6927','NASYWA ANDINE KURNIA','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('232','P-20517509-232','PAT20517509-232','20517509-232-x3s','6934','Niken Yuliasari','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('233','P-20517509-233','PAT20517509-233','20517509-233-mxQ','6935','NIYAS RAMADHINA PUTRI FADILLAH','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('234','P-20517509-234','PAT20517509-234','20517509-234-2ey','6943','Prisa Ayu Naila Zahra','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('235','P-20517509-235','PAT20517509-235','20517509-235-xRW','6960','REVITA NUR FIANDINI','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('236','P-20517509-236','PAT20517509-236','20517509-236-mfM','6961','RICHO ALDIANO PRATAMA','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('237','P-20517509-237','PAT20517509-237','20517509-237-dpI','6980','Sholawatus Anasya','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('238','P-20517509-238','PAT20517509-238','20517509-238-dVW','6981','SRI MULYANI','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('239','P-20517509-239','PAT20517509-239','20517509-239-dlf','6983','STEVEN RUIEN','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('240','P-20517509-240','PAT20517509-240','20517509-240-H3v','6998','WITTA PARMAWATI','7','VII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('241','P-20517509-241','PAT20517509-241','20517509-241-GYj','6999','YUDHA ADITYA SUPRIANTO','7','VII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('242','P-20517509-242','PAT20517509-242','20517509-242-HQ9','6559','AISYA FATMAWATI','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('243','P-20517509-243','PAT20517509-243','20517509-243-bAx','6568','ANANDA HASBIANSYAH','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('244','P-20517509-244','PAT20517509-244','20517509-244-jHy','6569','ANASTASYA JESSICA OLIVIA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('245','P-20517509-245','PAT20517509-245','20517509-245-s7m','6583','CHARLES REVALYO ANDA SYAPUTRA','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('246','P-20517509-246','PAT20517509-246','20517509-246-Q4B','6585','CHIKA REGINA NAFTALIA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('247','P-20517509-247','PAT20517509-247','20517509-247-KwT','6590','DAVID SETYO YULIANTO','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('248','P-20517509-248','PAT20517509-248','20517509-248-zsG','6608','DWI ASTUTIK','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('249','P-20517509-249','PAT20517509-249','20517509-249-ufN','6613','ERVAN FABIANO PAMUNGKAS','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('250','P-20517509-250','PAT20517509-250','20517509-250-Qeq','6617','FANZA DHERIS HERNANDA','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('251','P-20517509-251','PAT20517509-251','20517509-251-jof','6620','FEBRIAN WAHYU RAKA SYAPUTRA','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('252','P-20517509-252','PAT20517509-252','20517509-252-Vxc','6621','FEBY ALDIANOVA','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('253','P-20517509-253','PAT20517509-253','20517509-253-uta','6632','HAFIZAH ANANDA ALPIN','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('254','P-20517509-254','PAT20517509-254','20517509-254-GBb','6641','JOSHUA HEMASIA PUTRA','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('255','P-20517509-255','PAT20517509-255','20517509-255-8sb','6645','KEVIN BRYAN GIOVANNO YUSTANTO','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('256','P-20517509-256','PAT20517509-256','20517509-256-vEW','6659','MERRYZA AYU CHRISTIANTI','8','VIII-A',NULL,'Kristen','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('257','P-20517509-257','PAT20517509-257','20517509-257-IBw','6667','MOCHAMAD RIFAN FINO APRILIO','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('258','P-20517509-258','PAT20517509-258','20517509-258-0nP','6685','NADISTA PUTRI SINTIA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('259','P-20517509-259','PAT20517509-259','20517509-259-Ndl','6687','NHURUS SAADHA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('260','P-20517509-260','PAT20517509-260','20517509-260-krU','6688','NOVELIA AYU ANJANI','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('261','P-20517509-261','PAT20517509-261','20517509-261-UYn','6691','PUTRA MANGKU LUHUR','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('262','P-20517509-262','PAT20517509-262','20517509-262-Swr','6693','RADITYA ARYA BIMA','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('263','P-20517509-263','PAT20517509-263','20517509-263-WPL','6696','RAIHAN EKA APRIATAMA','8','VIII-A',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('264','P-20517509-264','PAT20517509-264','20517509-264-FLA','6708','REYCHA NUR AISYAH LIANIKA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('265','P-20517509-265','PAT20517509-265','20517509-265-Gdg','6715','RIZKI ADI SAPUTRA','8','VIII-A',NULL,'Kristen','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('266','P-20517509-266','PAT20517509-266','20517509-266-wtK','6716','RIZKY ALIVIA RAMDHANI','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('267','P-20517509-267','PAT20517509-267','20517509-267-GjR','6718','SAFIRA OKTAVIONA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('268','P-20517509-268','PAT20517509-268','20517509-268-6re','6721','SERLYTA NUR ALIFIA WIDYASARI','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('269','P-20517509-269','PAT20517509-269','20517509-269-3Mx','6734','TIARA NELGA SABRIA','8','VIII-A',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('270','P-20517509-270','PAT20517509-270','20517509-270-IH8','6560','AISYAH','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('271','P-20517509-271','PAT20517509-271','20517509-271-CHP','6567','ALVIAN NURI SAPUTRA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('272','P-20517509-272','PAT20517509-272','20517509-272-HFX','6578','AUREL KHEISYAH ZALFA PUTRI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('273','P-20517509-273','PAT20517509-273','20517509-273-Lh9','6580','BAYU AGUNG DANUARTA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('274','P-20517509-274','PAT20517509-274','20517509-274-Lwa','6581','BERLIANA ANDINI VALENTINA','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('275','P-20517509-275','PAT20517509-275','20517509-275-630','6584','CHERIL SEVIRA PUTRI A','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('276','P-20517509-276','PAT20517509-276','20517509-276-K06','6600','DIAH AYU PRATIWI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('277','P-20517509-277','PAT20517509-277','20517509-277-9ER','6601','DIMAS IDRIS SETIAWAN','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('278','P-20517509-278','PAT20517509-278','20517509-278-3hT','6615','FACHRY AZIZI','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('279','P-20517509-279','PAT20517509-279','20517509-279-467','6749','FARDHAN IRZA BAHA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('280','P-20517509-280','PAT20517509-280','20517509-280-QPI','6619','FAUZAN NABIL ALIF ALGHAZY','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('281','P-20517509-281','PAT20517509-281','20517509-281-5cu','6622','FELISA PUTRI KAMILA','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('282','P-20517509-282','PAT20517509-282','20517509-282-fZ0','6630','GUMINTANG ANTALA HADI','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('283','P-20517509-283','PAT20517509-283','20517509-283-Qfj','6634','HERLINDA PUTRI WIJAYANI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('284','P-20517509-284','PAT20517509-284','20517509-284-Dv7','6656','MARCHEL PUTRA PRADANA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('285','P-20517509-285','PAT20517509-285','20517509-285-FkG','6678','MUHAMMAD RIZAL FERNANDO','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('286','P-20517509-286','PAT20517509-286','20517509-286-7Ad','6682','NABILA OKTAVIANA ELYU YAQUTAH','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('287','P-20517509-287','PAT20517509-287','20517509-287-CfS','6683','NADHIRA MAFAZA','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('288','P-20517509-288','PAT20517509-288','20517509-288-SJ4','6689','OCTAVIAN SATRIA WIBAWA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('289','P-20517509-289','PAT20517509-289','20517509-289-4Ce','6695','RAHMA ASYIFA NACA PRATANTI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('290','P-20517509-290','PAT20517509-290','20517509-290-2A6','6698','RANGGA SURYA PRANATA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('291','P-20517509-291','PAT20517509-291','20517509-291-lhG','6699','RANICA APRILIA FEBRIANDITA','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('292','P-20517509-292','PAT20517509-292','20517509-292-XvQ','6701','REFAN ASFA DHINATA PUTRA PRAMENDA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('293','P-20517509-293','PAT20517509-293','20517509-293-B8x','6702','REHAN DEBI REGIAN','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('294','P-20517509-294','PAT20517509-294','20517509-294-Nj0','6703','REKA MAULIDA DWI KARTINI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('295','P-20517509-295','PAT20517509-295','20517509-295-d23','6707','REVANO FENDI SETIAWAN','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('296','P-20517509-296','PAT20517509-296','20517509-296-Rgl','6719','SAHRUL TORIKUL BAHRI','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('297','P-20517509-297','PAT20517509-297','20517509-297-NnS','6731','SURYA WIJAYA','8','VIII-B',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('298','P-20517509-298','PAT20517509-298','20517509-298-6yC','6733','SYIFAAUR RAHMAH LAILATUL FITRI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('299','P-20517509-299','PAT20517509-299','20517509-299-JxS','6748','ZULFA RAMADHANI','8','VIII-B',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('300','P-20517509-300','PAT20517509-300','20517509-300-Ldh','6547','ACHMAD MUHARTO','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('301','P-20517509-301','PAT20517509-301','20517509-301-3ZI','6548','ADHITYA PRATAMA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('302','P-20517509-302','PAT20517509-302','20517509-302-ga9','6565','ALTHAF ALFIAN DAFFA SHAFAREL','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('303','P-20517509-303','PAT20517509-303','20517509-303-Wzn','6594','DEVARA NIKMATUL NADILA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('304','P-20517509-304','PAT20517509-304','20517509-304-Pm6','6596','DEWI AMINATUS SYADIA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('305','P-20517509-305','PAT20517509-305','20517509-305-xeO','6607','DWI ANISSA RETNANINGTYAS','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('306','P-20517509-306','PAT20517509-306','20517509-306-hgE','6609','DWICKY SATRIA WICAKSONO','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('307','P-20517509-307','PAT20517509-307','20517509-307-uI2','6618','FARIZ GALANG WICAKSONO','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('308','P-20517509-308','PAT20517509-308','20517509-308-0bl','6629','GINANJAR CAKRA YUDHA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('309','P-20517509-309','PAT20517509-309','20517509-309-AOm','6631','HAFIDZ MANDALA PUTRA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('310','P-20517509-310','PAT20517509-310','20517509-310-x73','6646','KEVIN DESTAVIANO','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('311','P-20517509-311','PAT20517509-311','20517509-311-dF5','6647','KEYLILA ALIFAH WIJAYATI','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('312','P-20517509-312','PAT20517509-312','20517509-312-YS2','6652','LEDYA AYU FALENTINA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('313','P-20517509-313','PAT20517509-313','20517509-313-iXl','6653','LELITYA PARAMARTHA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('314','P-20517509-314','PAT20517509-314','20517509-314-IBK','6654','M ARDHAN ARIYANTA PUTRA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('315','P-20517509-315','PAT20517509-315','20517509-315-weN','6668','MOH. RAHMAT PAMUJI','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('316','P-20517509-316','PAT20517509-316','20517509-316-Qj4','6670','MUHAMMAD SAHRUL RAMADAN','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('317','P-20517509-317','PAT20517509-317','20517509-317-gKI','6680','MUHAMMAD ZAMZAMI NABIL','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('318','P-20517509-318','PAT20517509-318','20517509-318-B1a','6753','NOHA NUR HANSADA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('319','P-20517509-319','PAT20517509-319','20517509-319-JaN','6694','RAFI ADI KUSUMA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('320','P-20517509-320','PAT20517509-320','20517509-320-NFJ','6704','RENDI ACHMAD HUSAINI','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('321','P-20517509-321','PAT20517509-321','20517509-321-RtE','6710','RICKY PUTRA SLAMET WIJAYA','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('322','P-20517509-322','PAT20517509-322','20517509-322-9UY','6712','RINDU ALGESA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('323','P-20517509-323','PAT20517509-323','20517509-323-5kd','6720','SANGMARITA RAISSA RAHMADANI','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('324','P-20517509-324','PAT20517509-324','20517509-324-Yov','6725','SHINTA NURIYAH FARA DIBA ZAHRA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('325','P-20517509-325','PAT20517509-325','20517509-325-lIW','6732','SUSY EKA PRANATA JATI','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('326','P-20517509-326','PAT20517509-326','20517509-326-mQ4','6736','VENSKA CHELSEA AURELIA AZZAHRA','8','VIII-C',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('327','P-20517509-327','PAT20517509-327','20517509-327-lEA','6743','YANUAR SETO ABIMANYU','8','VIII-C',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('328','P-20517509-328','PAT20517509-328','20517509-328-BzO','6553','Ahmad Bagus Irawan','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('329','P-20517509-329','PAT20517509-329','20517509-329-MDR','6557','AHMAD MAULANA IBRAHIM','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('330','P-20517509-330','PAT20517509-330','20517509-330-380','6558','AIRIS RAHMANIKA KRISTINA PUSPITA SARI DEWI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('331','P-20517509-331','PAT20517509-331','20517509-331-moq','6566','ALVENT RIDHA PAMUNGKAS','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('332','P-20517509-332','PAT20517509-332','20517509-332-wGq','6570','ANGGITA RAMANDANI PUTRI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('333','P-20517509-333','PAT20517509-333','20517509-333-oTP','6575','AROF AZWYL GIBRAN','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('334','P-20517509-334','PAT20517509-334','20517509-334-hzT','6586','CHLARISYA ANINDYTHA PUTRI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('335','P-20517509-335','PAT20517509-335','20517509-335-Sck','6598','DHAFA HAFIDZ ARDIANSYAH','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('336','P-20517509-336','PAT20517509-336','20517509-336-U2w','6602','DIMAS RENDI PRADANA','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('337','P-20517509-337','PAT20517509-337','20517509-337-Kts','6614','EZRYLIA OKTISYA PUTRIADI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('338','P-20517509-338','PAT20517509-338','20517509-338-i5S','6400','FAREL YOGA MAULANA','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('339','P-20517509-339','PAT20517509-339','20517509-339-HOi','6623','FENDI MULYO SADEWO','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('340','P-20517509-340','PAT20517509-340','20517509-340-doa','6635','HERMAWAN','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('341','P-20517509-341','PAT20517509-341','20517509-341-g9p','6649','KHOIS NABILA NOVIANSARI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('342','P-20517509-342','PAT20517509-342','20517509-342-bnq','6752','KHUSNUL KHOTIMAH','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('343','P-20517509-343','PAT20517509-343','20517509-343-6O7','6658','MAULANA SYAH IBRAHIM','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('344','P-20517509-344','PAT20517509-344','20517509-344-SfB','6669','MUHAMAD FAIQ ALFUDHOLI','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('345','P-20517509-345','PAT20517509-345','20517509-345-MHa','6671','MUHAMMAD ABDUL AZIZ','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('346','P-20517509-346','PAT20517509-346','20517509-346-U8D','6672','MUHAMMAD AFANDHI','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('347','P-20517509-347','PAT20517509-347','20517509-347-yQt','6681','NABILA NUR CAHYANI','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('348','P-20517509-348','PAT20517509-348','20517509-348-Bf5','6686','NAZILATUL NURROHMAH','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('349','P-20517509-349','PAT20517509-349','20517509-349-bpl','6690','PRITHA ARTHA DYAH VERONICA','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('350','P-20517509-350','PAT20517509-350','20517509-350-K6B','6697','RAMANDA SURYA DESTIANDRA','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('351','P-20517509-351','PAT20517509-351','20517509-351-lP1','6706','RESTU GALIH WIBOWO','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('352','P-20517509-352','PAT20517509-352','20517509-352-72h','6709','REYHAN NANDA IBRAHIM','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('353','P-20517509-353','PAT20517509-353','20517509-353-6mX','6713','RISLA SALSABILAA','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('354','P-20517509-354','PAT20517509-354','20517509-354-7kO','6737','VIA AULIA FIRDAUS','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('355','P-20517509-355','PAT20517509-355','20517509-355-QKz','6739','VIONEELY QUEEN NINDYATAMA','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('356','P-20517509-356','PAT20517509-356','20517509-356-nhX','6742','WULAN SRI WAHYUNINGSIH','8','VIII-D',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('357','P-20517509-357','PAT20517509-357','20517509-357-56Y','6747','ZIDANE MAULANA AL KHAUFI','8','VIII-D',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('358','P-20517509-358','PAT20517509-358','20517509-358-8x2','6552','AHMAD ADIB PRATAMA DWI ARYADI','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('359','P-20517509-359','PAT20517509-359','20517509-359-ve4','6554','AHMAD FARIL','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('360','P-20517509-360','PAT20517509-360','20517509-360-loT','6555','AHMAD FEBRIANSAH','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('361','P-20517509-361','PAT20517509-361','20517509-361-jHh','6561','AL VIRA REZKYA PUTRI','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('362','P-20517509-362','PAT20517509-362','20517509-362-fQK','6562','ALDICO FITRAH PRATAMA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('363','P-20517509-363','PAT20517509-363','20517509-363-nMi','6571','ANNISA ULKAMILAH','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('364','P-20517509-364','PAT20517509-364','20517509-364-g0y','6579','AYU TRI CAHYANI','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('365','P-20517509-365','PAT20517509-365','20517509-365-EMp','8000','Citra Maulani Mahmudi','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('366','P-20517509-366','PAT20517509-366','20517509-366-r95','6592','DENI CANDRA PRATAMA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('367','P-20517509-367','PAT20517509-367','20517509-367-Da2','6593','DEVANI SATYA RAHMANDA','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('368','P-20517509-368','PAT20517509-368','20517509-368-GWu','7004','DHIFKA SATTYO','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('369','P-20517509-369','PAT20517509-369','20517509-369-5fa','6603','DIMAS SAPUTRA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('370','P-20517509-370','PAT20517509-370','20517509-370-TIU','6610','ELYCIA PUTRI IRWANTI','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('371','P-20517509-371','PAT20517509-371','20517509-371-KIE','6616','FAHRIYAN ALFARIZI SULIKIN PUTRA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('372','P-20517509-372','PAT20517509-372','20517509-372-kb5','6637','IFAL FEBRIAN MAULIDAN','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('373','P-20517509-373','PAT20517509-373','20517509-373-EgM','6650','LAILATUL NIKMA','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('374','P-20517509-374','PAT20517509-374','20517509-374-eg9','6655','M.AFNAN FANANI','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('375','P-20517509-375','PAT20517509-375','20517509-375-Vlw','6657','MARIFATUL ISTIQOMAH','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('376','P-20517509-376','PAT20517509-376','20517509-376-tJ8','6660','MERTHA SHELIYA PUTRI','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('377','P-20517509-377','PAT20517509-377','20517509-377-fYE','6661','MIFTAHUL JANNAH LAILATUL MUBAROKAH','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('378','P-20517509-378','PAT20517509-378','20517509-378-dpi','6665','MOCH. NANDO BACHTIAR','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('379','P-20517509-379','PAT20517509-379','20517509-379-vlW','6666','MOCH. SYAHDAN ALI JAHAR ZAKARIYA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('380','P-20517509-380','PAT20517509-380','20517509-380-74m','6700','RASTINA PUTRI DEARIKA','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('381','P-20517509-381','PAT20517509-381','20517509-381-5Q9','6705','RENDY PUTRA RAMAHDHANY','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('382','P-20517509-382','PAT20517509-382','20517509-382-yYs','6717','RIZQO NEZAR PRAYUGA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('383','P-20517509-383','PAT20517509-383','20517509-383-T9z','6724','SHAFIRA MEYDA AYU PURNOMO','8','VIII-E',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('384','P-20517509-384','PAT20517509-384','20517509-384-md9','6738','VIAN DWI ANDHIKA','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('385','P-20517509-385','PAT20517509-385','20517509-385-qUB','6741','WILDAN DAWAM ICHSANI','8','VIII-E',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('386','P-20517509-386','PAT20517509-386','20517509-386-623','6550','AFRILYA ZUMROTUL FARICHAH','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('387','P-20517509-387','PAT20517509-387','20517509-387-tyU','6563','ALLENDRA RAFAEL ANGGARA PUTRA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('388','P-20517509-388','PAT20517509-388','20517509-388-XSN','6564','ALMAS FARAH AULIA','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('389','P-20517509-389','PAT20517509-389','20517509-389-k5J','6573','ARDIAN ILHAM ALAMSYAH','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('390','P-20517509-390','PAT20517509-390','20517509-390-4z7','6582','BHADRES SYAQQIF MAHESWARA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('391','P-20517509-391','PAT20517509-391','20517509-391-Re5','6587','CINDY PUTRI ADI OKTAVIA','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('392','P-20517509-392','PAT20517509-392','20517509-392-cSM','6588','DANDI TRI WAHYU','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('393','P-20517509-393','PAT20517509-393','20517509-393-hT7','6589','DANY ADI SATRIA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('394','P-20517509-394','PAT20517509-394','20517509-394-mO2','6595','DEVI RAYSSA CAHYANINGRUM','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('395','P-20517509-395','PAT20517509-395','20517509-395-mJM','6597','DEWI MALA AULIA MUKTI','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('396','P-20517509-396','PAT20517509-396','20517509-396-vLl','6599','DHARUS AL AZIZ','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('397','P-20517509-397','PAT20517509-397','20517509-397-0er','6605','DINI AGUSTIN','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('398','P-20517509-398','PAT20517509-398','20517509-398-Jno','6612','ERICK FAJAR PERMANA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('399','P-20517509-399','PAT20517509-399','20517509-399-wZP','6624','FITRIATUL MUFIDA','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('400','P-20517509-400','PAT20517509-400','20517509-400-PVU','6628','GHANI RAFA WAHYU PRATAMA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('401','P-20517509-401','PAT20517509-401','20517509-401-UAv','6639','IMA DEWI RAMADHANI','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('402','P-20517509-402','PAT20517509-402','20517509-402-qrv','6640','INTAN DWI SAIDAH','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('403','P-20517509-403','PAT20517509-403','20517509-403-vqs','6644','KELVIN BAYU PRATAMA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('404','P-20517509-404','PAT20517509-404','20517509-404-F94','6663','MOCH HAFISH FERDIANTO','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('405','P-20517509-405','PAT20517509-405','20517509-405-0yH','6664','MOCH. AFIF PRAMADANI','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('406','P-20517509-406','PAT20517509-406','20517509-406-Kfg','6676','MUHAMAD GEMA YUDIKA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('407','P-20517509-407','PAT20517509-407','20517509-407-AkR','6457','MUHAMMAD AZRIEL AKMAL AKHTANI','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('408','P-20517509-408','PAT20517509-408','20517509-408-O0q','6679','MUHAMMAD RIZKY SAIFULLAH','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('409','P-20517509-409','PAT20517509-409','20517509-409-i7Q','6684','NADIN DWI ARIANI','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('410','P-20517509-410','PAT20517509-410','20517509-410-4d8','6711','RICO ANDIKA YATAMA','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('411','P-20517509-411','PAT20517509-411','20517509-411-yzw','6729','SEPIAN NOOR RAMADHAN','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('412','P-20517509-412','PAT20517509-412','20517509-412-Qzv','6723','SHAFANDA YUNESSA ZAHRA N','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('413','P-20517509-413','PAT20517509-413','20517509-413-Rn0','6735','TUMENGGER ABDANUL OGYAMIGE W','8','VIII-F',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('414','P-20517509-414','PAT20517509-414','20517509-414-mUs','6744','YOHANIA PUTRI ANUGRAHENI','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('415','P-20517509-415','PAT20517509-415','20517509-415-2xD','6746','ZHALWA RAMADHANI ISNANDAR','8','VIII-F',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('416','P-20517509-416','PAT20517509-416','20517509-416-nYv','6546','ACHMAD KURNIAWAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('417','P-20517509-417','PAT20517509-417','20517509-417-YrT','6549','AFRIEDA GABRIELA RAMADHANA','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('418','P-20517509-418','PAT20517509-418','20517509-418-k4l','6572','AQILLA AL FATHIR RAHMAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('419','P-20517509-419','PAT20517509-419','20517509-419-phJ','6574','ARIS WAHYU ADI KURNIAWAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('420','P-20517509-420','PAT20517509-420','20517509-420-Ni4','6751','ARYA BIMA OKTAVIANO','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('421','P-20517509-421','PAT20517509-421','20517509-421-6P5','6576','AUDIA LUTFI ROUFFATUL HUSNA','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('422','P-20517509-422','PAT20517509-422','20517509-422-uLJ','6577','AULIA NAFATUL RIZKI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('423','P-20517509-423','PAT20517509-423','20517509-423-R4v','6591','DAFIN NAUVAL IKHSAN SUSILO','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('424','P-20517509-424','PAT20517509-424','20517509-424-Lre','6604','DINDA DWI WAHYUNI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('425','P-20517509-425','PAT20517509-425','20517509-425-e2n','6606','DUWI FEBRIANTO','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('426','P-20517509-426','PAT20517509-426','20517509-426-MmX','6625','FRISKA HIMAYATUL ROHMA','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('427','P-20517509-427','PAT20517509-427','20517509-427-ML5','6626','GALEH SYAHPUTRA WIJAYA','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('428','P-20517509-428','PAT20517509-428','20517509-428-YJa','6636','IDA DWI SAFITRI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('429','P-20517509-429','PAT20517509-429','20517509-429-qVr','6638','ILHAM PUR FEBRYAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('430','P-20517509-430','PAT20517509-430','20517509-430-RC8','6642','JUNI EKA NURROHMAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('431','P-20517509-431','PAT20517509-431','20517509-431-ksw','6643','KARINA DWI PRASASTI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('432','P-20517509-432','PAT20517509-432','20517509-432-VDE','6648','KHOIRUL ANAM','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('433','P-20517509-433','PAT20517509-433','20517509-433-Bbr','6651','LAVITA VRITI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('434','P-20517509-434','PAT20517509-434','20517509-434-Fd3','6662','MIRNANDA ADJI SETIO WATI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('435','P-20517509-435','PAT20517509-435','20517509-435-5NP','6673','MUHAMMAD DAVID ARDIANSYAH','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('436','P-20517509-436','PAT20517509-436','20517509-436-5o0','6674','MUHAMMAD FARZANA ABDUL REZA','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('437','P-20517509-437','PAT20517509-437','20517509-437-LAF','6675','MUHAMMAD FAUZUL RADHYF NAJASYI','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('438','P-20517509-438','PAT20517509-438','20517509-438-ZAh','6677','MUHAMMAD KEVIN ALFIANSYAH RAMADHAN','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('439','P-20517509-439','PAT20517509-439','20517509-439-YKj','6714','RISTANU AJIANTO','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('440','P-20517509-440','PAT20517509-440','20517509-440-OJg','6726','SHIVA LIFANI RAHMADHANI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('441','P-20517509-441','PAT20517509-441','20517509-441-svp','6728','SILVYA DWI MEYLANI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('442','P-20517509-442','PAT20517509-442','20517509-442-9W8','6730','SOVY PANGESTU','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('443','P-20517509-443','PAT20517509-443','20517509-443-RYm','6740','WAHYU SAPUTRA','8','VIII-G',NULL,'Islam','L','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('444','P-20517509-444','PAT20517509-444','20517509-444-0Vj','6745','ZANUBATUL GEISHA WULANDARI','8','VIII-G',NULL,'Islam','P','20517509','MKKS-5','1','0');
INSERT INTO `siswa` VALUES ('445','P-20517517-1','PAT20517517-1','20517517-1-INx','12226','ABDE FAIZAL AZMI','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('446','P-20517517-2','PAT20517517-2','20517517-2-yVU','12227','ACHMAD DZIDNI AINUL YAQIN','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('447','P-20517517-3','PAT20517517-3','20517517-3-wK6','12228','ADIENIA NUR AFIFSYA','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('448','P-20517517-4','PAT20517517-4','20517517-4-HeL','12229','ADISTYA VIOLA ABHZELLA','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('449','P-20517517-5','PAT20517517-5','20517517-5-MBx','12230','AHMAD HUSAYYIL YABIR','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('450','P-20517517-6','PAT20517517-6','20517517-6-ZkG','12231','AKHLIS SYUHADA\'','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('451','P-20517517-7','PAT20517517-7','20517517-7-EDL','12232','ALISYA YULIANA','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('452','P-20517517-8','PAT20517517-8','20517517-8-KaX','12233','ANISA PUTRI RAHAYU','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('453','P-20517517-9','PAT20517517-9','20517517-9-8og','12234','ANNISA FAHIMATUL ILMIYAH','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('454','P-20517517-10','PAT20517517-10','20517517-10-hem','12235','APRELIA AYU WULANDARI','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('455','P-20517517-11','PAT20517517-11','20517517-11-zWK','12236','AXCEL GHAVYN MAHENDRA','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('456','P-20517517-12','PAT20517517-12','20517517-12-AdJ','12237','BALQIS NUR AQILAH','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('457','P-20517517-13','PAT20517517-13','20517517-13-YVc','12238','BRILIAN MASYITHA SALSABILA','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('458','P-20517517-14','PAT20517517-14','20517517-14-QVo','12239','DAMAR KINANTI','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('459','P-20517517-15','PAT20517517-15','20517517-15-4IL','12240','DWI FEBRIANTO','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('460','P-20517517-16','PAT20517517-16','20517517-16-p0z','12241','FADIL RIZQI PRAYOGA','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('461','P-20517517-17','PAT20517517-17','20517517-17-L6f','12242','FAIS ALFARIZI','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('462','P-20517517-18','PAT20517517-18','20517517-18-5KA','12243','FAZA AKBARUL MUFTI','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('463','P-20517517-19','PAT20517517-19','20517517-19-yEN','12244','GLADISA BRILIAN QODRIYAH','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('464','P-20517517-20','PAT20517517-20','20517517-20-IYo','12245','IRVAN SYARIF FERNANDO','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('465','P-20517517-21','PAT20517517-21','20517517-21-BfA','12246','Jihan Adzra Aqilah','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('466','P-20517517-22','PAT20517517-22','20517517-22-qOX','12247','Kevin Putra Aribowo','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('467','P-20517517-23','PAT20517517-23','20517517-23-TWm','12248','KURNIA WARDOYO','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('468','P-20517517-24','PAT20517517-24','20517517-24-0nM','12249','MAULANA PUTRA ANANDA','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('469','P-20517517-25','PAT20517517-25','20517517-25-WpN','12250','Miqdad Nidhom Fahmi','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('470','P-20517517-26','PAT20517517-26','20517517-26-2Q7','12251','MOCHAMMAD ABDILAH AL JUNAEDI','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('471','P-20517517-27','PAT20517517-27','20517517-27-zV8','12252','RIFKI HABIBI','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('472','P-20517517-28','PAT20517517-28','20517517-28-djZ','12253','RIZQI KURNIYAWAN','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('473','P-20517517-29','PAT20517517-29','20517517-29-7Kh','12254','SELVIANA NAYZHILA CAHYA DWI TIFANI','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('474','P-20517517-30','PAT20517517-30','20517517-30-opJ','12255','SHALIINI MAYSAH MAGENTHARAN','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('475','P-20517517-31','PAT20517517-31','20517517-31-lD1','12256','STEVANNY RICHO ERRYANSYAH','7','VII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('476','P-20517517-32','PAT20517517-32','20517517-32-94o','12257','Zaenab Bidara Zaman','7','VII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('477','P-20517517-33','PAT20517517-33','20517517-33-Nha','12258','Achmad firnando','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('478','P-20517517-34','PAT20517517-34','20517517-34-l4L','12259','Ahmad Rhamadani','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('479','P-20517517-35','PAT20517517-35','20517517-35-3wo','12260','AIDA NURUS ALYA PUTRIANI','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('480','P-20517517-36','PAT20517517-36','20517517-36-ZsD','12261','ALBER PUTRA DWI PURNAMA','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('481','P-20517517-37','PAT20517517-37','20517517-37-kPW','12262','ALIKHA MESYA HARDIANA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('482','P-20517517-38','PAT20517517-38','20517517-38-6xo','12263','ALIYA NAJWA FAUZIYAH','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('483','P-20517517-39','PAT20517517-39','20517517-39-H8v','12264','AMABEL DAMARA ELYSIA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('484','P-20517517-40','PAT20517517-40','20517517-40-YSA','12265','Amanda Rizky Damayanti','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('485','P-20517517-41','PAT20517517-41','20517517-41-KA0','12266','BARA PUTRA ANDRIAN','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('486','P-20517517-42','PAT20517517-42','20517517-42-M71','12267','Dina Nuriati','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('487','P-20517517-43','PAT20517517-43','20517517-43-6kz','12268','DWI KAYATRI PRIDHANA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('488','P-20517517-44','PAT20517517-44','20517517-44-lDc','12269','DWI YUDHA PRASETYA','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('489','P-20517517-45','PAT20517517-45','20517517-45-0lX','12270','ERVINO SHAFARRULLOH','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('490','P-20517517-46','PAT20517517-46','20517517-46-TOP','12271','ERWIN MAULANA ADITYA','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('491','P-20517517-47','PAT20517517-47','20517517-47-ADI','12272','FADHILAH AHMAD MUHARROM','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('492','P-20517517-48','PAT20517517-48','20517517-48-UqH','12273','FITRI OKTAVIA DWI PUSPITA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('493','P-20517517-49','PAT20517517-49','20517517-49-DBP','12274','LIONEL LARIP DEMESSI','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('494','P-20517517-50','PAT20517517-50','20517517-50-Id0','12275','MUHAMAT ILHAM PRATAMA','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('495','P-20517517-51','PAT20517517-51','20517517-51-DIS','12276','MUHAMMAD KHAFID ZUHRI','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('496','P-20517517-52','PAT20517517-52','20517517-52-kUy','12277','NAJWA TANAYA MARTA WIDYA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('497','P-20517517-53','PAT20517517-53','20517517-53-4UA','12278','Narendra Putra Pratama','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('498','P-20517517-54','PAT20517517-54','20517517-54-BFv','12279','Rena syifana','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('499','P-20517517-55','PAT20517517-55','20517517-55-CWM','12280','V RADITHYA DIO FRIESCA AMANDIKA','7','VII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('500','P-20517517-56','PAT20517517-56','20517517-56-Ict','12281','WIDI NUR HIDAYATI','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('501','P-20517517-57','PAT20517517-57','20517517-57-dz4','12282','Yasmin Nurkhalifah','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('502','P-20517517-58','PAT20517517-58','20517517-58-0ad','12283','ZAHROTUL AELSA PUTERI','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('503','P-20517517-59','PAT20517517-59','20517517-59-9qZ','12284','ZAHWA AULYA RAHMA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('504','P-20517517-60','PAT20517517-60','20517517-60-mSr','12285','ZAHWA ERINA MAHABBA SAFA ARISSA SUPRAUN','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('505','P-20517517-61','PAT20517517-61','20517517-61-Pzg','12286','ZASKIA AQUARLA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('506','P-20517517-62','PAT20517517-62','20517517-62-of2','12287','ZHIFANI ARMELIYA PUTRI','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('507','P-20517517-63','PAT20517517-63','20517517-63-T5m','12288','ZIHFARAH RATU ZEKHA','7','VII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('508','P-20517517-64','PAT20517517-64','20517517-64-2xg','12289','ABY RASYA PRATAMA SANTOSO','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('509','P-20517517-65','PAT20517517-65','20517517-65-ePM','12290','ACHMAD MAULANA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('510','P-20517517-66','PAT20517517-66','20517517-66-pml','12291','ACHMAD MUDHOFFAR','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('511','P-20517517-67','PAT20517517-67','20517517-67-7Dn','12293','ADINDA ATHA PUTRI ZAHRA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('512','P-20517517-68','PAT20517517-68','20517517-68-Le7','12294','ADITYA CHAIRUL AZZAMY','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('513','P-20517517-69','PAT20517517-69','20517517-69-ZHn','12295','ADITYA MAULANA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('514','P-20517517-70','PAT20517517-70','20517517-70-gZl','12296','ADITYA NUR SAPUTRA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('515','P-20517517-71','PAT20517517-71','20517517-71-muF','12297','AFDIANO TRICAHYA NUGRAHA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('516','P-20517517-72','PAT20517517-72','20517517-72-AqO','12298','AHMAD NUR ALIMAN','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('517','P-20517517-73','PAT20517517-73','20517517-73-Wy2','12299','AHMAD TEGES PRAKOSO','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('518','P-20517517-74','PAT20517517-74','20517517-74-U9H','12300','AIRLANGGA PRASETYO','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('519','P-20517517-75','PAT20517517-75','20517517-75-PuH','12301','ALFI CASSAVA PUTRI','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('520','P-20517517-76','PAT20517517-76','20517517-76-8Vq','12302','AMELYA PUTRI MAULIDYA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('521','P-20517517-77','PAT20517517-77','20517517-77-pDY','12309','AN-NUM DRAJAT WIBOWO','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('522','P-20517517-78','PAT20517517-78','20517517-78-pV7','12303','ANDINA DWI PRADITA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('523','P-20517517-79','PAT20517517-79','20517517-79-Czv','12304','ANDREA RAFFALLAH PUTRA HARIONO','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('524','P-20517517-80','PAT20517517-80','20517517-80-HL9','12305','ANI DWI MAULINA AL FAHMI','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('525','P-20517517-81','PAT20517517-81','20517517-81-MPc','12306','ANI NUR SETYAWANTRI','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('526','P-20517517-82','PAT20517517-82','20517517-82-XEa','12308','ANNAVI UZAHRA ASYAFANY','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('527','P-20517517-83','PAT20517517-83','20517517-83-CcS','12310','Aq Pandu Dewanata','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('528','P-20517517-84','PAT20517517-84','20517517-84-NhY','12311','AQILA ANJUZIDNA KAMILA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('529','P-20517517-85','PAT20517517-85','20517517-85-czX','12312','ARFINDA MAYGIZTA SALMA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('530','P-20517517-86','PAT20517517-86','20517517-86-4mS','12313','Asmara Aulya Miccayla Shinee','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('531','P-20517517-87','PAT20517517-87','20517517-87-02z','12314','ASYIFA NAURI AZZA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('532','P-20517517-88','PAT20517517-88','20517517-88-4Df','12315','ATIQAH QUROTA AINUN','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('533','P-20517517-89','PAT20517517-89','20517517-89-cOo','12316','Azriel Yoga Pratama','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('534','P-20517517-90','PAT20517517-90','20517517-90-ofL','12317','AZZAM ADE SYAHPUTRA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('535','P-20517517-91','PAT20517517-91','20517517-91-S3c','12318','BAGUS ARYA GANGGA','7','VII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('536','P-20517517-92','PAT20517517-92','20517517-92-UOg','12319','SILVIA MARITA','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('537','P-20517517-93','PAT20517517-93','20517517-93-3XS','12320','yuanita dewi pertiwi','7','VII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('538','P-20517517-94','PAT20517517-94','20517517-94-pIk','12321','ALDI FIRMANSYAH','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('539','P-20517517-95','PAT20517517-95','20517517-95-jYA','12322','ALVINO AGHA BRILLIAN','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('540','P-20517517-96','PAT20517517-96','20517517-96-6tj','12323','ALVINO DWI FIRMANSYAH','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('541','P-20517517-97','PAT20517517-97','20517517-97-8eF','12324','Andika Marthen Hetaria','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('542','P-20517517-98','PAT20517517-98','20517517-98-M1n','12325','ANDIKA RIZKI PRATAMA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('543','P-20517517-99','PAT20517517-99','20517517-99-KIR','12326','ANGGA DWI NUGROHO','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('544','P-20517517-100','PAT20517517-100','20517517-100-7tK','12327','ANISAH PUTRI FADHILAH','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('545','P-20517517-101','PAT20517517-101','20517517-101-dsA','12328','ARAFA DAUD UTAMA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('546','P-20517517-102','PAT20517517-102','20517517-102-lio','12329','Arif Rachmadan','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('547','P-20517517-103','PAT20517517-103','20517517-103-wfQ','12330','ARINI VITA SARI','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('548','P-20517517-104','PAT20517517-104','20517517-104-Zml','12331','ARJUNA SATRIA UTAMA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('549','P-20517517-105','PAT20517517-105','20517517-105-Cnw','12332','ARYA DWI PRASETYO','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('550','P-20517517-106','PAT20517517-106','20517517-106-14H','12333','AULYA NABILAH QURROTA\'AINI','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('551','P-20517517-107','PAT20517517-107','20517517-107-0cX','12334','AZARIA FARRAS ZEROUN','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('552','P-20517517-108','PAT20517517-108','20517517-108-LIU','12335','AZILIA FEBI PUTRI WARDANA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('553','P-20517517-109','PAT20517517-109','20517517-109-cFK','12336','AZKIYA NAHLA KHOIRUNISA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('554','P-20517517-110','PAT20517517-110','20517517-110-mk7','12337','BEAUTY NOVALINA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('555','P-20517517-111','PAT20517517-111','20517517-111-xfe','12338','BELLA RIZKI AMALIA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('556','P-20517517-112','PAT20517517-112','20517517-112-xeF','12339','BENI HERMAWAN','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('557','P-20517517-113','PAT20517517-113','20517517-113-hNM','12340','BIMA PUTRA FACHREZA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('558','P-20517517-114','PAT20517517-114','20517517-114-GFH','12341','BINTANG ADJIEYUVA FADILLA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('559','P-20517517-115','PAT20517517-115','20517517-115-By7','12342','BIRLY AKBAR ZAINUDDIN','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('560','P-20517517-116','PAT20517517-116','20517517-116-5MP','12343','CHRISTIN WIDIARTI NINGSIH','7','VII-D',NULL,'Kristen','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('561','P-20517517-117','PAT20517517-117','20517517-117-uT3','12344','Davin Syahreza','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('562','P-20517517-118','PAT20517517-118','20517517-118-Hiq','12345','DAVINDRA ALDIAS SAPUTRA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('563','P-20517517-119','PAT20517517-119','20517517-119-rnG','12346','DAVIS MOHAMAD AKBAR','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('564','P-20517517-120','PAT20517517-120','20517517-120-eJQ','12347','DESTIANA ANGGGRAINI SAFIRA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('565','P-20517517-121','PAT20517517-121','20517517-121-EIU','12348','DEWI LESTARI','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('566','P-20517517-122','PAT20517517-122','20517517-122-0gu','12349','DHAFA ARISTYA SYAHPUTRA','7','VII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('567','P-20517517-123','PAT20517517-123','20517517-123-DHp','12350','Dhea Ananda Putri','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('568','P-20517517-124','PAT20517517-124','20517517-124-5EY','12351','DHEVINA SHAFA VELLISA','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('569','P-20517517-125','PAT20517517-125','20517517-125-1Sa','12352','DINAR BAL QIS UMM AHATUL NABIL LAH','7','VII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('570','P-20517517-126','PAT20517517-126','20517517-126-Bi2','12353','CHOIRINA PUTRI ANJANI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('571','P-20517517-127','PAT20517517-127','20517517-127-t9J','12354','DAFA MAULANA ARDIANSYAH','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('572','P-20517517-128','PAT20517517-128','20517517-128-5rK','12355','DAFI AURIEL FERNANDA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('573','P-20517517-129','PAT20517517-129','20517517-129-EAL','12356','DARRIUZ ACHMAD ZACKY HAMZAH','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('574','P-20517517-130','PAT20517517-130','20517517-130-0WU','12357','DAVY AGASTA DIKA PUTRA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('575','P-20517517-131','PAT20517517-131','20517517-131-5aL','12358','DIKA SETIAWAN','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('576','P-20517517-132','PAT20517517-132','20517517-132-IKa','12359','DIO RAVANDA MULYA PRIBADI','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('577','P-20517517-133','PAT20517517-133','20517517-133-5P6','12360','DWI ALIF MAULANA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('578','P-20517517-134','PAT20517517-134','20517517-134-Utc','12361','DWI KRISNANDI ARDIANSYAH','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('579','P-20517517-135','PAT20517517-135','20517517-135-ukj','12362','DYAH AYU RAHMAWATI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('580','P-20517517-136','PAT20517517-136','20517517-136-UAN','12363','DZAKWA AGLENSYAH SETIAWAN','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('581','P-20517517-137','PAT20517517-137','20517517-137-sFV','12364','DZANUBA INDIE RAMADHANI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('582','P-20517517-138','PAT20517517-138','20517517-138-SkK','12365','EAGLE BLEDEX WIRANATA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('583','P-20517517-139','PAT20517517-139','20517517-139-LVH','12366','EKA RIZKI PRADITIYA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('584','P-20517517-140','PAT20517517-140','20517517-140-qaA','12368','ELSA DWI ANGGRAENI CAVELERA','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('585','P-20517517-141','PAT20517517-141','20517517-141-zOB','12369','EMBUN MARETA HENDRIANTO','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('586','P-20517517-142','PAT20517517-142','20517517-142-vRY','12370','EMILLI CALANTHA RAMADHANI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('587','P-20517517-143','PAT20517517-143','20517517-143-VSb','12371','ERLAN DEWA SARONI','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('588','P-20517517-144','PAT20517517-144','20517517-144-1MC','12372','EZAR FAADHILAH PRABOWO','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('589','P-20517517-145','PAT20517517-145','20517517-145-pKV','12373','FAJAR OKTAVIANO RISKY PUTRA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('590','P-20517517-146','PAT20517517-146','20517517-146-AvS','12374','FANERION REFINDRA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('591','P-20517517-147','PAT20517517-147','20517517-147-KW2','12375','Fara rahayu putri','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('592','P-20517517-148','PAT20517517-148','20517517-148-dan','12376','FENISHA ADINDA SALSABHILA','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('593','P-20517517-149','PAT20517517-149','20517517-149-s7R','12377','FERDI SETIAWAN','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('594','P-20517517-150','PAT20517517-150','20517517-150-bfQ','12378','FRISKA PUTRI RAMADANI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('595','P-20517517-151','PAT20517517-151','20517517-151-wJE','12379','GABRYELA CAHYA SATRIA','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('596','P-20517517-152','PAT20517517-152','20517517-152-hUn','12380','GAUNG MILDAHUL ATMATAMA','7','VII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('597','P-20517517-153','PAT20517517-153','20517517-153-Mw1','12381','GENDIES LARASATI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('598','P-20517517-154','PAT20517517-154','20517517-154-4AD','12382','GRESYA AULIA HELZIZDA','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('599','P-20517517-155','PAT20517517-155','20517517-155-I71','12383','HAFIZHA YUKI MAHARANI','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('600','P-20517517-156','PAT20517517-156','20517517-156-htM','12384','HANA NUR FAIDAH','7','VII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('601','P-20517517-157','PAT20517517-157','20517517-157-tVU','12385','Fadilla Rahayu','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('602','P-20517517-158','PAT20517517-158','20517517-158-C54','12386','FARDAN FIRMANSYAH','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('603','P-20517517-159','PAT20517517-159','20517517-159-vcH','12387','FAREL ADITTIA','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('604','P-20517517-160','PAT20517517-160','20517517-160-kni','12388','FARID ROBIM TRI LAKSONO','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('605','P-20517517-161','PAT20517517-161','20517517-161-EsA','12389','FASA ADITYA KUSUMA WARDANA','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('606','P-20517517-162','PAT20517517-162','20517517-162-Bs4','12390','Febryan Eka Ramdhani','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('607','P-20517517-163','PAT20517517-163','20517517-163-Dnz','12391','FERDIAN FIRMANSYAH','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('608','P-20517517-164','PAT20517517-164','20517517-164-oS0','12392','FERNINDO PUTRA PRASETYA','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('609','P-20517517-165','PAT20517517-165','20517517-165-3nt','12393','Fikri maulana fanani','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('610','P-20517517-166','PAT20517517-166','20517517-166-t7Q','12394','FINO ALFIANDO','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('611','P-20517517-167','PAT20517517-167','20517517-167-0TE','12395','hafis mardiansyah','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('612','P-20517517-168','PAT20517517-168','20517517-168-3y9','12396','HAIDAR IZZUL JUHAIR','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('613','P-20517517-169','PAT20517517-169','20517517-169-vFH','12397','HANAFI YUDHA MAHENDRA','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('614','P-20517517-170','PAT20517517-170','20517517-170-W2T','12398','HEKSA ELKARIN QUROTA MAQFIROH','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('615','P-20517517-171','PAT20517517-171','20517517-171-2Aw','12399','HERLAMBANG DWI SAPUTRA','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('616','P-20517517-172','PAT20517517-172','20517517-172-FgZ','12400','HULIATUL AWLIYA NURRAHMAH','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('617','P-20517517-173','PAT20517517-173','20517517-173-eWE','12401','ICHA DEWI PURWATI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('618','P-20517517-174','PAT20517517-174','20517517-174-NLw','12402','INDAH AMELIA DEVINA PUTRI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('619','P-20517517-175','PAT20517517-175','20517517-175-gPH','12403','INTAN ADELIA RASTA','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('620','P-20517517-176','PAT20517517-176','20517517-176-Akp','12404','IREINE FAIZZATUNNISA','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('621','P-20517517-177','PAT20517517-177','20517517-177-eW0','12405','IRMA YANUARTI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('622','P-20517517-178','PAT20517517-178','20517517-178-q8e','12406','ISYFA NUR AISYAH','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('623','P-20517517-179','PAT20517517-179','20517517-179-XjY','12407','JANNAH HABIBA ILLA','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('624','P-20517517-180','PAT20517517-180','20517517-180-fOF','12408','JUWANITA OKTAVIANI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('625','P-20517517-181','PAT20517517-181','20517517-181-Uz9','12409','KAFFAH PUTRA ANDRIANSYAH','7','VII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('626','P-20517517-182','PAT20517517-182','20517517-182-ATC','12410','KAILA AOLANI FIDELA','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('627','P-20517517-183','PAT20517517-183','20517517-183-fjl','12411','KARINA PUTRI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('628','P-20517517-184','PAT20517517-184','20517517-184-6xi','12412','Kartika loka pangestu','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('629','P-20517517-185','PAT20517517-185','20517517-185-LEx','12413','KEISA GADISA PUTRI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('630','P-20517517-186','PAT20517517-186','20517517-186-Uoe','12414','KEISHA AOLANI FIDELIA','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('631','P-20517517-187','PAT20517517-187','20517517-187-WyD','12415','MONICA SARI','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('632','P-20517517-188','PAT20517517-188','20517517-188-Dm8','12416','NILAM PUSPITA LEMBAYUNG','7','VII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('633','P-20517517-189','PAT20517517-189','20517517-189-uiv','12417','HIDAYATULLOH AL HABSYI','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('634','P-20517517-190','PAT20517517-190','20517517-190-1Jb','12418','Ilfan Ananta','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('635','P-20517517-191','PAT20517517-191','20517517-191-Vik','12419','ILHAM GANDAR FADHILAH','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('636','P-20517517-192','PAT20517517-192','20517517-192-T04','12420','ILHAM GILANG TRITAMA','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('637','P-20517517-193','PAT20517517-193','20517517-193-I5c','12421','INDRA ZIDHAN REZKY RAMADHANI','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('638','P-20517517-194','PAT20517517-194','20517517-194-JyC','12422','Kenzi Bryden Al Varo','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('639','P-20517517-195','PAT20517517-195','20517517-195-J45','12423','KEVIN ILHAM PRATAMA','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('640','P-20517517-196','PAT20517517-196','20517517-196-zfC','12425','KEYSHA NABILA PUTRI','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('641','P-20517517-197','PAT20517517-197','20517517-197-o5L','12426','KEYZHA LINTANG ADITIA WANDA','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('642','P-20517517-198','PAT20517517-198','20517517-198-7g8','12427','Lailatur rohma Putri riawati','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('643','P-20517517-199','PAT20517517-199','20517517-199-PkJ','12428','LETICIA PUTRI HAIFA\' AMIROH','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('644','P-20517517-200','PAT20517517-200','20517517-200-unA','12429','LEXYA AYUDYA MAYRISKA','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('645','P-20517517-201','PAT20517517-201','20517517-201-Uwb','12430','LINGGA RAIHAN ZACHARY','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('646','P-20517517-202','PAT20517517-202','20517517-202-U3X','12431','M FARIL NEZAR FIRMANSYAH','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('647','P-20517517-203','PAT20517517-203','20517517-203-Gr7','12432','M IRFAN MAULANA','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('648','P-20517517-204','PAT20517517-204','20517517-204-aNR','12433','M.ALTHAF SYAH PUTRA NANDYA','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('649','P-20517517-205','PAT20517517-205','20517517-205-u0P','12434','MARELDA JIHAN SAFIRA','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('650','P-20517517-206','PAT20517517-206','20517517-206-HBd','12435','MARSELINO JUFENTIO FADANDI','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('651','P-20517517-207','PAT20517517-207','20517517-207-ma7','12436','MIRANDA AYU LESTARI','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('652','P-20517517-208','PAT20517517-208','20517517-208-2nb','12437','MOCHAMAD IRFAN SEBASTIAR','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('653','P-20517517-209','PAT20517517-209','20517517-209-Ns9','12438','MOCHAMMAD ALIF GILANG ADRIAN SAU','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('654','P-20517517-210','PAT20517517-210','20517517-210-Jyd','12439','MOHAMMAD FAUZAN HABIBULLOH','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('655','P-20517517-211','PAT20517517-211','20517517-211-ICq','12440','MUHAMAD AKBAR VICKRI AL FURQON','7','VII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('656','P-20517517-212','PAT20517517-212','20517517-212-RWb','12442','NA\'ILA AFIFAH ZAHIRA','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('657','P-20517517-213','PAT20517517-213','20517517-213-H7S','12441','NABILA CITRA OLIVIA','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('658','P-20517517-214','PAT20517517-214','20517517-214-Hw5','12443','Najwa Fifi Jasmine Maulikha','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('659','P-20517517-215','PAT20517517-215','20517517-215-jG6','12444','NASWA OKTA ALTAFUNISA','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('660','P-20517517-216','PAT20517517-216','20517517-216-JBx','12445','NENA DWI HALIMATUZ SADIYAH','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('661','P-20517517-217','PAT20517517-217','20517517-217-9pN','12446','NOVIA TANTRI NURANGGRAENI','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('662','P-20517517-218','PAT20517517-218','20517517-218-8yw','12447','OCTA ISLAMI MULYANING PUTRI','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('663','P-20517517-219','PAT20517517-219','20517517-219-kGP','12448','Pretty Dinda Gayatri Worung','7','VII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('664','P-20517517-220','PAT20517517-220','20517517-220-2Q1','12449','FERNANDO SATRIA YUDHA','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('665','P-20517517-221','PAT20517517-221','20517517-221-1OD','12450','MUCHAMAD MAULANA','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('666','P-20517517-222','PAT20517517-222','20517517-222-P2g','12451','MUCHAMAD RAQEL TRIANSYAH','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('667','P-20517517-223','PAT20517517-223','20517517-223-fek','12453','MUHAMMAD CHARLES HERMAWAN','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('668','P-20517517-224','PAT20517517-224','20517517-224-7at','12454','Muhammad Hafizh Asyam','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('669','P-20517517-225','PAT20517517-225','20517517-225-2wd','12455','MUHAMMAD ILHAM','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('670','P-20517517-226','PAT20517517-226','20517517-226-eJa','12456','MUHAMMAD IRFAN HUSAINI','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('671','P-20517517-227','PAT20517517-227','20517517-227-RbD','12457','MUHAMMAD KEYZA RAFFIO AKBAR','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('672','P-20517517-228','PAT20517517-228','20517517-228-6Ga','12458','MUHAMMAD NIZAM MUZAQI','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('673','P-20517517-229','PAT20517517-229','20517517-229-HZp','12452','MUHAMMAD RAFIANSYAH','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('674','P-20517517-230','PAT20517517-230','20517517-230-DaW','12459','MUHAMMAD RAFLI','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('675','P-20517517-231','PAT20517517-231','20517517-231-Qzg','12460','MUHAMMAD RIZKI SAPUTRA','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('676','P-20517517-232','PAT20517517-232','20517517-232-Ypu','12461','MUKHAMMAD DAFA NUR FERDIANSYAH','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('677','P-20517517-233','PAT20517517-233','20517517-233-Zik','12462','MUKHAMMAD TANWIRUL ANAM ZAHID','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('678','P-20517517-234','PAT20517517-234','20517517-234-yrO','12463','PRICILIA QUROTA A\' YUN','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('679','P-20517517-235','PAT20517517-235','20517517-235-4ud','12464','PUTU KENZIE RADITTYA DEVDAN','7','VII-H',NULL,'Hindu','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('680','P-20517517-236','PAT20517517-236','20517517-236-kBH','12465','QAYRA QHALIFATUL SHAVAHILLA','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('681','P-20517517-237','PAT20517517-237','20517517-237-qOH','12466','Raditya Tito Werdhana','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('682','P-20517517-238','PAT20517517-238','20517517-238-QU0','12467','RAFARIS JOVAN SAPUTRA','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('683','P-20517517-239','PAT20517517-239','20517517-239-Kmo','12468','RAFIKA ADELIA DWI PUTRI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('684','P-20517517-240','PAT20517517-240','20517517-240-95M','12469','RANI ANGGRAENI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('685','P-20517517-241','PAT20517517-241','20517517-241-8a1','12470','RAVAEL ARIFINTINO','7','VII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('686','P-20517517-242','PAT20517517-242','20517517-242-ZLs','12471','RESA SHAFA OKTARINA','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('687','P-20517517-243','PAT20517517-243','20517517-243-PVe','12472','RESTU ANGGE WULANDARI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('688','P-20517517-244','PAT20517517-244','20517517-244-83G','12473','Rohma','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('689','P-20517517-245','PAT20517517-245','20517517-245-cT4','12474','ROSALIA DWI RAHMAWATI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('690','P-20517517-246','PAT20517517-246','20517517-246-1Ze','12475','ROSSA LINDA MEI SAGITA','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('691','P-20517517-247','PAT20517517-247','20517517-247-oFa','12476','SABRINA KAYLA SYAHIRA','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('692','P-20517517-248','PAT20517517-248','20517517-248-3qt','12477','SEPTIA SAVA RAHMADANI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('693','P-20517517-249','PAT20517517-249','20517517-249-nBu','12478','Shavira najatil ula','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('694','P-20517517-250','PAT20517517-250','20517517-250-LEw','12479','SINTA DEWI CHANDRA WINATA','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('695','P-20517517-251','PAT20517517-251','20517517-251-J42','12480','VELLISA PUTRI','7','VII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('696','P-20517517-252','PAT20517517-252','20517517-252-3Jk','12481','AHMAD EZRA DWI PANGESTU','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('697','P-20517517-253','PAT20517517-253','20517517-253-MTq','12482','ANGKY WIJAYA','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('698','P-20517517-254','PAT20517517-254','20517517-254-tJT','12515','Farah Amelia Salsabilla','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('699','P-20517517-255','PAT20517517-255','20517517-255-IvT','12483','FIDELA AMELIA FIDIANI','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('700','P-20517517-256','PAT20517517-256','20517517-256-vGf','12484','MUHAMMAD MUSTAQ FIRRI','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('701','P-20517517-257','PAT20517517-257','20517517-257-7zb','12485','MUHAMMAD NICOLAS SAPUTRA','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('702','P-20517517-258','PAT20517517-258','20517517-258-7Bl','12486','MUHAMMAD UBAIDILLAH','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('703','P-20517517-259','PAT20517517-259','20517517-259-6Xv','12487','RANGGA DEWANDRA PRASONGKO','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('704','P-20517517-260','PAT20517517-260','20517517-260-E8X','12488','REGA RUFIL SATRIA','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('705','P-20517517-261','PAT20517517-261','20517517-261-3O2','12489','Renggar eka ahmad adinata','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('706','P-20517517-262','PAT20517517-262','20517517-262-j10','12490','REYHAN ADITTYA','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('707','P-20517517-263','PAT20517517-263','20517517-263-l09','12491','REZA TRI DIMAS SANJAYA','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('708','P-20517517-264','PAT20517517-264','20517517-264-5sa','12492','Rifky Bastyan Abbyasha','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('709','P-20517517-265','PAT20517517-265','20517517-265-WCU','12493','RYAN FERDYANSYAH','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('710','P-20517517-266','PAT20517517-266','20517517-266-nTi','12494','SAFWANI ZAHRA AMAR','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('711','P-20517517-267','PAT20517517-267','20517517-267-fNq','12495','SAMUEL IBRAHIMOVIC RIZKY ANDREAN','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('712','P-20517517-268','PAT20517517-268','20517517-268-TA6','12496','SATRYA ZHAFIF RAMADHAN','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('713','P-20517517-269','PAT20517517-269','20517517-269-pJm','12497','SETIA HATI ALVINO AUD AFDILAH','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('714','P-20517517-270','PAT20517517-270','20517517-270-aBz','12498','Silsilia Umayroh','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('715','P-20517517-271','PAT20517517-271','20517517-271-HXd','12499','TARISYA ALFIAH NUR','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('716','P-20517517-272','PAT20517517-272','20517517-272-XqC','12500','TIARA DWI AYU ANJELITA','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('717','P-20517517-273','PAT20517517-273','20517517-273-Y5Z','12501','TIto Achsany Ahmed','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('718','P-20517517-274','PAT20517517-274','20517517-274-T1B','12502','ULUMMI FAUZIA','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('719','P-20517517-275','PAT20517517-275','20517517-275-CJt','12503','VERLITA KIRANA PUTRI','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('720','P-20517517-276','PAT20517517-276','20517517-276-f8y','12504','VINA AULIA JANNATI','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('721','P-20517517-277','PAT20517517-277','20517517-277-2ds','12505','VIRNINDIA CAMILATUN NISA','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('722','P-20517517-278','PAT20517517-278','20517517-278-lgz','12506','Vivia Artika Maharani','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('723','P-20517517-279','PAT20517517-279','20517517-279-7ZC','12507','WARDIANSYAH AJI ARUM','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('724','P-20517517-280','PAT20517517-280','20517517-280-UK3','12508','WENDI AGUNG SUSANTO','7','VII-I',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('725','P-20517517-281','PAT20517517-281','20517517-281-JsR','12509','YEZA BRIGIT AULA RAMADANI','7','VII-I',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('726','P-20517517-282','PAT20517517-282','20517517-282-oG4','11983','ABHISTA RAYHAN JADMIKO','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('727','P-20517517-283','PAT20517517-283','20517517-283-7cy','11984','ACHMAD RIZKY SUBACHTIAR','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('728','P-20517517-284','PAT20517517-284','20517517-284-DvV','11953','AIRA ALDILA MOZA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('729','P-20517517-285','PAT20517517-285','20517517-285-6lP','11954','AJENG KARTIKA PUTRI','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('730','P-20517517-286','PAT20517517-286','20517517-286-7Dn','11955','AKBAR SYAM PUTRA','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('731','P-20517517-287','PAT20517517-287','20517517-287-DPo','11956','ALDIRGA AGUSTIAN','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('732','P-20517517-288','PAT20517517-288','20517517-288-5ar','11957','ALDO BINTANG CAHYA MAHERA','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('733','P-20517517-289','PAT20517517-289','20517517-289-2Yu','11958','ALVIN MAULANA ROSYADANI','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('734','P-20517517-290','PAT20517517-290','20517517-290-BZa','11959','AULIA FARADEA MOZA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('735','P-20517517-291','PAT20517517-291','20517517-291-fEH','11961','CAESAR ILHAM RAMADHANI','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('736','P-20517517-292','PAT20517517-292','20517517-292-LOh','11960','CAHYA OCTAFIYANI','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('737','P-20517517-293','PAT20517517-293','20517517-293-aCT','11962','DINDA AUDELIA ROSAA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('738','P-20517517-294','PAT20517517-294','20517517-294-OFx','11964','DISKA NUR CAHYANI PUTRI','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('739','P-20517517-295','PAT20517517-295','20517517-295-WrE','11965','ELSYA FITRIA MELFIANA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('740','P-20517517-296','PAT20517517-296','20517517-296-hYv','11966','FAIRUS BIMA SUMARNO','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('741','P-20517517-297','PAT20517517-297','20517517-297-pAE','11967','M. FADHIL AFDHAL','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('742','P-20517517-298','PAT20517517-298','20517517-298-LS3','11968','MAURINE PUTRI WULANDARI','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('743','P-20517517-299','PAT20517517-299','20517517-299-7oK','11969','MELATI ANANDITA KURNIA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('744','P-20517517-300','PAT20517517-300','20517517-300-yz5','11970','MOCH BEFA ALFIANSYAH','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('745','P-20517517-301','PAT20517517-301','20517517-301-NXe','11971','MUCHAMMAD DANIS ZAYYAN FALIH','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('746','P-20517517-302','PAT20517517-302','20517517-302-EqV','11972','MUHAMAD FARIZQI ADI PUTRA','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('747','P-20517517-303','PAT20517517-303','20517517-303-Rm6','11973','MUHAMMAD DAFFA FIRMANSYAH','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('748','P-20517517-304','PAT20517517-304','20517517-304-c5g','11974','MUHAMMAD DARIEOS PUTRA','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('749','P-20517517-305','PAT20517517-305','20517517-305-xK2','11975','MUHAMMAD NAWAF SYIHABUDDIN YAFIQ','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('750','P-20517517-306','PAT20517517-306','20517517-306-7bB','11976','MUHAMMAD REVALDO','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('751','P-20517517-307','PAT20517517-307','20517517-307-7ro','11977','MUSTHAFA ARIF DWI CAHYO','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('752','P-20517517-308','PAT20517517-308','20517517-308-P5i','11978','NAFIRSA JAMILA SALSABILA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('753','P-20517517-309','PAT20517517-309','20517517-309-irJ','11979','NIZAR RACHMAD SANTOSO PUTRA','8','VIII-A',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('754','P-20517517-310','PAT20517517-310','20517517-310-YOC','11980','PUTRI ADELIA SUTANTO','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('755','P-20517517-311','PAT20517517-311','20517517-311-LxP','11981','RANI RAMADHANIA','8','VIII-A',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('756','P-20517517-312','PAT20517517-312','20517517-312-NY4','12013','ADIT SOBAR SETIAWAN','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('757','P-20517517-313','PAT20517517-313','20517517-313-rTZ','11985','AHMAD QUDSY ROFIQY','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('758','P-20517517-314','PAT20517517-314','20517517-314-pXI','12014','AHMAD RIZAL FATHURRAHMAN','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('759','P-20517517-315','PAT20517517-315','20517517-315-5c9','11986','AILSHA AZARIN FADANTYA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('760','P-20517517-316','PAT20517517-316','20517517-316-VFB','12015','AIRA BILQIS PUTRI AYUNDA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('761','P-20517517-317','PAT20517517-317','20517517-317-UFs','12016','AKBAR ADITYA AGCA FAIRUS','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('762','P-20517517-318','PAT20517517-318','20517517-318-qSI','12017','ALIF AKBAR JULIANSYAH','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('763','P-20517517-319','PAT20517517-319','20517517-319-FCK','11987','ALIYA NUR FADILLAH','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('764','P-20517517-320','PAT20517517-320','20517517-320-UtH','11988','ALUNA KINARA ARIMBI','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('765','P-20517517-321','PAT20517517-321','20517517-321-q9v','11989','ANANDITO IMAN FADILA','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('766','P-20517517-322','PAT20517517-322','20517517-322-7pW','11990','ANDINI EKA PUSPITA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('767','P-20517517-323','PAT20517517-323','20517517-323-ByV','11991','ARDI YUDISTIRA DARMAWAN','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('768','P-20517517-324','PAT20517517-324','20517517-324-8Bf','11992','CHANDRA NUR FAISAL','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('769','P-20517517-325','PAT20517517-325','20517517-325-MLD','11993','CHELSEA MAHARANI HANAFIAH','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('770','P-20517517-326','PAT20517517-326','20517517-326-T2X','11994','DAFFINA ARDYAN SYAFABILLAH','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('771','P-20517517-327','PAT20517517-327','20517517-327-P8A','11995','DEWI FIRDAUS IFTINAH','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('772','P-20517517-328','PAT20517517-328','20517517-328-1GE','11996','DWI AJI SETYO','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('773','P-20517517-329','PAT20517517-329','20517517-329-Y89','11997','ESTU ANA FANIA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('774','P-20517517-330','PAT20517517-330','20517517-330-1Ze','11998','EVAN SYABIL ZAFRAN WIBOWO','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('775','P-20517517-331','PAT20517517-331','20517517-331-swU','11999','FANDRI FIRDAUS JA\'FAR H.','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('776','P-20517517-332','PAT20517517-332','20517517-332-b8Y','12001','LUTFI SULISTIYANINGSIH','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('777','P-20517517-333','PAT20517517-333','20517517-333-Qrp','12002','MARCEL TRI SABILILLAH','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('778','P-20517517-334','PAT20517517-334','20517517-334-oY8','12003','MITA RISQI AULIA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('779','P-20517517-335','PAT20517517-335','20517517-335-dJN','12004','MOCH. FAREL BAHQIST SUBI','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('780','P-20517517-336','PAT20517517-336','20517517-336-2wh','12005','MUHAMMAD FADILAH','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('781','P-20517517-337','PAT20517517-337','20517517-337-7sn','12006','MUHAMMAD YAFI ARYO WIBOWO','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('782','P-20517517-338','PAT20517517-338','20517517-338-UFw','12007','PUTRI AYU WULANDARI','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('783','P-20517517-339','PAT20517517-339','20517517-339-Jwc','12008','RANDY ALFIATINO','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('784','P-20517517-340','PAT20517517-340','20517517-340-LCQ','12009','RASYID REINJIRO ALBEZALEEL','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('785','P-20517517-341','PAT20517517-341','20517517-341-Dfy','12011','SARAH AL HELA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('786','P-20517517-342','PAT20517517-342','20517517-342-UkI','12010','SHANDRA ATHIYAH AZHAHRA','8','VIII-B',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('787','P-20517517-343','PAT20517517-343','20517517-343-L4Q','12012','YUSRIL MAULANA SYAFARI','8','VIII-B',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('788','P-20517517-344','PAT20517517-344','20517517-344-Opr','12044','ABI PRAYOGO','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('789','P-20517517-345','PAT20517517-345','20517517-345-bSL','12045','ADINDA SABHIRAH RAHMAWATI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('790','P-20517517-346','PAT20517517-346','20517517-346-BiD','12018','ALIF AVREL PRADATAMA','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('791','P-20517517-347','PAT20517517-347','20517517-347-ORj','12048','ALIFIYA NUR CAHYANI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('792','P-20517517-348','PAT20517517-348','20517517-348-JgD','12049','ALMIRA NOVI DWI YULIANTI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('793','P-20517517-349','PAT20517517-349','20517517-349-dKa','12019','AMANDA NABILA SYAFINA','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('794','P-20517517-350','PAT20517517-350','20517517-350-BUG','12050','ANINDITA TANAYA ARTANTI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('795','P-20517517-351','PAT20517517-351','20517517-351-Bwz','12051','ANISHA SYAHLA','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('796','P-20517517-352','PAT20517517-352','20517517-352-vZm','12021','aprilicha queensha melody','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('797','P-20517517-353','PAT20517517-353','20517517-353-a5D','12054','BILQIS RISKE YUSIANO PUTRI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('798','P-20517517-354','PAT20517517-354','20517517-354-l2q','12055','CHIKA SINTYA KUSNAEDI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('799','P-20517517-355','PAT20517517-355','20517517-355-YeD','12056','CINTA KASIH NOVIANTI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('800','P-20517517-356','PAT20517517-356','20517517-356-Bft','12022','FERA FERNANDA PUTRI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('801','P-20517517-357','PAT20517517-357','20517517-357-cjh','12023','FIRDA ANIVERSARI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('802','P-20517517-358','PAT20517517-358','20517517-358-Med','12026','GAMMA ALBARA SUEB','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('803','P-20517517-359','PAT20517517-359','20517517-359-mEc','12027','GHIRRID FAURUZ ALBAIS','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('804','P-20517517-360','PAT20517517-360','20517517-360-JR6','12028','IRSYAD NUR TSAQIF','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('805','P-20517517-361','PAT20517517-361','20517517-361-2S6','12029','JOENATHAN PUTRA PURNOMO','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('806','P-20517517-362','PAT20517517-362','20517517-362-YbB','12030','KAKA FAKHRIAL SETYO RAMADHAN PUTRA','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('807','P-20517517-363','PAT20517517-363','20517517-363-T4u','12031','KINANTI AYU WIDYA WATI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('808','P-20517517-364','PAT20517517-364','20517517-364-Zpb','12032','MOCH. FADILLAH RISQI GANESHA','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('809','P-20517517-365','PAT20517517-365','20517517-365-Tjo','12033','MOCH.ILHAM FIRMANSYAH','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('810','P-20517517-366','PAT20517517-366','20517517-366-LNS','12035','MUHAMMAD ILHAM','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('811','P-20517517-367','PAT20517517-367','20517517-367-Z84','12036','MUHAMMAD RAFFY SYAPUTRA','8','VIII-C',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('812','P-20517517-368','PAT20517517-368','20517517-368-Rmv','12037','NANDA PUTRI MAHARANI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('813','P-20517517-369','PAT20517517-369','20517517-369-EyC','12038','NAYLA TSABITA PUTRI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('814','P-20517517-370','PAT20517517-370','20517517-370-cB9','12039','NINA ANJANI','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('815','P-20517517-371','PAT20517517-371','20517517-371-WuM','12040','NUR QOIRIAH','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('816','P-20517517-372','PAT20517517-372','20517517-372-2Dk','12041','SAFARIN INDAH MARTHANIA','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('817','P-20517517-373','PAT20517517-373','20517517-373-ITF','12042','SERLY OLIVIA','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('818','P-20517517-374','PAT20517517-374','20517517-374-7zy','12043','TIFFANY VERONICA ANGGEL','8','VIII-C',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('819','P-20517517-375','PAT20517517-375','20517517-375-ETn','12074','ADINDA TALULA MALIKA','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('820','P-20517517-376','PAT20517517-376','20517517-376-I8E','12075','ADJI SETYA PRAMBUDI','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('821','P-20517517-377','PAT20517517-377','20517517-377-Er1','12076','ALIF SAIFUDIN','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('822','P-20517517-378','PAT20517517-378','20517517-378-vMS','12077','ALVANO HAFID RIDHUANSYAH','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('823','P-20517517-379','PAT20517517-379','20517517-379-klt','12078','ALVINO ARYA KRISTIAWAN','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('824','P-20517517-380','PAT20517517-380','20517517-380-FZG','12079','ANANDA DZAKI FATILAH','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('825','P-20517517-381','PAT20517517-381','20517517-381-Rm4','12080','ANDHIKA YOGI PRATAMA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('826','P-20517517-382','PAT20517517-382','20517517-382-TvY','12057','DENINTIA SINDU PAMBAYUN','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('827','P-20517517-383','PAT20517517-383','20517517-383-ucb','12081','DEVIKA APRILIYA','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('828','P-20517517-384','PAT20517517-384','20517517-384-nds','12082','DIMAS DWI SAPUTRA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('829','P-20517517-385','PAT20517517-385','20517517-385-nvF','12083','DION LEBDA SURYANTO','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('830','P-20517517-386','PAT20517517-386','20517517-386-8VF','12058','EKA GALUH','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('831','P-20517517-387','PAT20517517-387','20517517-387-wvB','12084','EVA SETYAWATI','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('832','P-20517517-388','PAT20517517-388','20517517-388-KmR','12085','FA\'IS FEBRIANTO','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('833','P-20517517-389','PAT20517517-389','20517517-389-prb','12086','FATIHA TSAMROTUL KOLBI','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('834','P-20517517-390','PAT20517517-390','20517517-390-yzr','12087','GALIH PERMANA SUGONDO','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('835','P-20517517-391','PAT20517517-391','20517517-391-Fok','12088','GIOVINCHA APRILYA SARASWATI','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('836','P-20517517-392','PAT20517517-392','20517517-392-WFO','12059','HILMI BINTANG SYAPUTRA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('837','P-20517517-393','PAT20517517-393','20517517-393-I4A','12060','JAENG SATRIYA MAULANA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('838','P-20517517-394','PAT20517517-394','20517517-394-p29','12061','MOCHAMMAD FERDYANS MASTAKILLA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('839','P-20517517-395','PAT20517517-395','20517517-395-8oJ','12062','MOHAMAD RISWAN HAFIS AL\'IZHA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('840','P-20517517-396','PAT20517517-396','20517517-396-xYn','12063','MOHAMMAD FAJAR PUTRA PRATAMA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('841','P-20517517-397','PAT20517517-397','20517517-397-s65','12064','MUHAMAD KURNIA MEGA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('842','P-20517517-398','PAT20517517-398','20517517-398-ATP','12065','MUHAMMAD BIMA LINGGA SAPUTRA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('843','P-20517517-399','PAT20517517-399','20517517-399-CZH','12066','MUHAMMAD ILHAM ALJABAR','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('844','P-20517517-400','PAT20517517-400','20517517-400-cQj','12067','MUHAMMAD PASA FATHURRAHMAN','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('845','P-20517517-401','PAT20517517-401','20517517-401-hH0','12068','NUR AFRINA AFZA','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('846','P-20517517-402','PAT20517517-402','20517517-402-so4','12069','PARAMARTA WIDI ATMAJA','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('847','P-20517517-403','PAT20517517-403','20517517-403-Umi','12070','RADITYA PUTRA ARDIANSYAH','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('848','P-20517517-404','PAT20517517-404','20517517-404-PX2','12071','REALGA ADITYA ALINSKY OWEN','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('849','P-20517517-405','PAT20517517-405','20517517-405-pYs','12072','REVALINA DWI ARISANTY','8','VIII-D',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('850','P-20517517-406','PAT20517517-406','20517517-406-GF1','12073','REVIN ARYO FEBRIANO','8','VIII-D',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('851','P-20517517-407','PAT20517517-407','20517517-407-Lbm','12105','AMIRUL MUKMIN','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('852','P-20517517-408','PAT20517517-408','20517517-408-7Av','12106','ARIEL PUTRA SYAHREZA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('853','P-20517517-409','PAT20517517-409','20517517-409-4br','12107','AULYA VEBY CRISTYANTI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('854','P-20517517-410','PAT20517517-410','20517517-410-saR','12108','AURELLIA NABILA PUTRI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('855','P-20517517-411','PAT20517517-411','20517517-411-bJt','12109','BIAN SOFIA DIANTI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('856','P-20517517-412','PAT20517517-412','20517517-412-RIa','12110','DAVANGGA MARTATAMA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('857','P-20517517-413','PAT20517517-413','20517517-413-faw','12112','DHAVINA AULIA NUR AZZAHRA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('858','P-20517517-414','PAT20517517-414','20517517-414-MaW','12111','DYANDRA AYUADHIA MELINDA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('859','P-20517517-415','PAT20517517-415','20517517-415-zkC','12113','ETGAR DAVID VIATNA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('860','P-20517517-416','PAT20517517-416','20517517-416-2r5','12114','FIA AISYAH','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('861','P-20517517-417','PAT20517517-417','20517517-417-9LE','12115','GHEA EMBUN TIARA ANTIKA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('862','P-20517517-418','PAT20517517-418','20517517-418-zgL','12089','HAAZYMAH HANYYAH ULYA TADZYMAH','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('863','P-20517517-419','PAT20517517-419','20517517-419-SCI','12090','HIKMAH FAJAR TRISANTOSO','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('864','P-20517517-420','PAT20517517-420','20517517-420-eQj','12091','INDAH DEWI AGUSTINA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('865','P-20517517-421','PAT20517517-421','20517517-421-FzE','12116','INTAN NURANI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('866','P-20517517-422','PAT20517517-422','20517517-422-26a','12092','IQLIMATUS SOLIKHA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('867','P-20517517-423','PAT20517517-423','20517517-423-Xcv','12093','ISMI NUR ELITA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('868','P-20517517-424','PAT20517517-424','20517517-424-r8s','12094','JESSICA ANGGUN PUTRI DAMAYANTI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('869','P-20517517-425','PAT20517517-425','20517517-425-I5k','12095','KEYZA SAFIRA DHEFNIA CHRISTANTIA','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('870','P-20517517-426','PAT20517517-426','20517517-426-ktD','12117','KINARYA ABDI NAGARI','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('871','P-20517517-427','PAT20517517-427','20517517-427-QSE','12120','M RIZKI MAULANA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('872','P-20517517-428','PAT20517517-428','20517517-428-3Xb','12096','MISTAKHUL VERY FEBRIANTO','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('873','P-20517517-429','PAT20517517-429','20517517-429-5KX','12119','MOH. RAIHAN ALFIRDAUZ','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('874','P-20517517-430','PAT20517517-430','20517517-430-xnu','12099','MOHAMMAD RAMA WIJAYA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('875','P-20517517-431','PAT20517517-431','20517517-431-mKH','12097','MUHAMMAD AZIZ','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('876','P-20517517-432','PAT20517517-432','20517517-432-lI0','12121','MUHAMMAD FATIH','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('877','P-20517517-433','PAT20517517-433','20517517-433-4dw','12100','MUHAMMAD YAKUB','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('878','P-20517517-434','PAT20517517-434','20517517-434-AC1','12101','NABILA DWI ARIANTI','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('879','P-20517517-435','PAT20517517-435','20517517-435-kzD','12122','NURIL JANNAH','8','VIII-E',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('880','P-20517517-436','PAT20517517-436','20517517-436-Tb7','12102','REZA DWI SYAPUTRA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('881','P-20517517-437','PAT20517517-437','20517517-437-cS3','12103','RIZKY RADITYA','8','VIII-E',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('882','P-20517517-438','PAT20517517-438','20517517-438-3Ey','12131','ADITYA SATRIA PRATAMA','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('883','P-20517517-439','PAT20517517-439','20517517-439-HAP','12132','AHMAD ALIF FUDIN','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('884','P-20517517-440','PAT20517517-440','20517517-440-XGS','12133','ALFIAN RADITYA SUGANDI','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('885','P-20517517-441','PAT20517517-441','20517517-441-cFm','12135','DENDY EKA SETIAWAN','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('886','P-20517517-442','PAT20517517-442','20517517-442-yiH','12136','DEVITA NURDIANTI','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('887','P-20517517-443','PAT20517517-443','20517517-443-i4L','12137','DIRGA EKA ISNANTO','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('888','P-20517517-444','PAT20517517-444','20517517-444-JRk','12138','DZARIYATURRIZKI KHOIRUNNIZAM','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('889','P-20517517-445','PAT20517517-445','20517517-445-0wa','12139','ELVIA LATIVATUL FIRDAUSI','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('890','P-20517517-446','PAT20517517-446','20517517-446-SlZ','12140','Fahri Ardiansyah','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('891','P-20517517-447','PAT20517517-447','20517517-447-KIO','12141','FAREL GITA KENCANA','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('892','P-20517517-448','PAT20517517-448','20517517-448-Ul2','12142','FATIHUL IHSAN','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('893','P-20517517-449','PAT20517517-449','20517517-449-eqa','12143','FIRSA PUTRI ARIFIN','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('894','P-20517517-450','PAT20517517-450','20517517-450-lgp','12144','JERICHO CALVIN WINAPUTRA','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('895','P-20517517-451','PAT20517517-451','20517517-451-Kyj','12146','MAULIDDIA RACHMA','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('896','P-20517517-452','PAT20517517-452','20517517-452-jyF','12147','MAULIDDYA VIVIN NOVARINDA','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('897','P-20517517-453','PAT20517517-453','20517517-453-Ir8','12149','NASYA NABILAH NOVERLITA','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('898','P-20517517-454','PAT20517517-454','20517517-454-mIq','12222','NATASYA FARAH DILLA KUTSIYA','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('899','P-20517517-455','PAT20517517-455','20517517-455-YwL','12150','NURI MARIFATUL LAILI','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('900','P-20517517-456','PAT20517517-456','20517517-456-n3U','12123','OLIVIA DWI CAHYANINGTIAS','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('901','P-20517517-457','PAT20517517-457','20517517-457-JF9','12151','PARAMITHA FIDA KUSUMAWARDANI','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('902','P-20517517-458','PAT20517517-458','20517517-458-5Um','12152','RADITYA ABDILLAH HEULUNG','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('903','P-20517517-459','PAT20517517-459','20517517-459-ZkJ','12124','RAFI FAHMI ISLAMI','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('904','P-20517517-460','PAT20517517-460','20517517-460-9O6','12153','RAHMAT ALDI SETIAWAN','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('905','P-20517517-461','PAT20517517-461','20517517-461-45j','12125','Rangga Putra Adi Wiguna','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('906','P-20517517-462','PAT20517517-462','20517517-462-lVw','12126','RASYA NABILA PUTRI','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('907','P-20517517-463','PAT20517517-463','20517517-463-Y2S','12127','REHAN DWI SUSILO','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('908','P-20517517-464','PAT20517517-464','20517517-464-uEg','12154','REHAN MAULANA ANDYKA YUDHYSTIRA','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('909','P-20517517-465','PAT20517517-465','20517517-465-cpb','12128','REVALDO DWI ANDIKA','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('910','P-20517517-466','PAT20517517-466','20517517-466-MIO','12221','WIVLY ADELIA KRISTINE','8','VIII-F',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('911','P-20517517-467','PAT20517517-467','20517517-467-tEV','12129','ZAINUDIN RISIQ IHSAN','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('912','P-20517517-468','PAT20517517-468','20517517-468-NHG','12130','ZAINULLAH ZARKASI','8','VIII-F',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('913','P-20517517-469','PAT20517517-469','20517517-469-oD8','12159','ARLITA DEWI GAYATRI','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('914','P-20517517-470','PAT20517517-470','20517517-470-RJ5','12160','AURA SYAFA RAMADHANI','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('915','P-20517517-471','PAT20517517-471','20517517-471-Ben','12161','AUSTIAN AGA PRATAMA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('916','P-20517517-472','PAT20517517-472','20517517-472-XT6','12162','CHRISMAS ADI NUGRAHA','8','VIII-G',NULL,'Kristen','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('917','P-20517517-473','PAT20517517-473','20517517-473-wEY','12163','DINA IKRIMA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('918','P-20517517-474','PAT20517517-474','20517517-474-98V','12164','EKA SEPTIAN FITRIYANA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('919','P-20517517-475','PAT20517517-475','20517517-475-z1w','12165','FADIL NIZAR FAZARI HOLANA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('920','P-20517517-476','PAT20517517-476','20517517-476-p8U','12166','FELISYIA SABRINA PUTRI','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('921','P-20517517-477','PAT20517517-477','20517517-477-lho','12167','Fraditya Satria Pratama','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('922','P-20517517-478','PAT20517517-478','20517517-478-xfU','12168','GRAYZILDHA AURA YURIKA OKTAVIA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('923','P-20517517-479','PAT20517517-479','20517517-479-0hY','12169','HASBIAN WISNU PRATAMA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('924','P-20517517-480','PAT20517517-480','20517517-480-3eY','12170','IQBAL TRI ALFARIZI','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('925','P-20517517-481','PAT20517517-481','20517517-481-THl','12171','ISMAIL DANUARTA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('926','P-20517517-482','PAT20517517-482','20517517-482-Whx','12172','KHURIDO ROMADHANI','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('927','P-20517517-483','PAT20517517-483','20517517-483-tOC','12173','LIKEN NOVIAWAN','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('928','P-20517517-484','PAT20517517-484','20517517-484-6kB','12174','M FAJAR GIAT RIFANSYAH','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('929','P-20517517-485','PAT20517517-485','20517517-485-hsN','12176','MAULIDIA NUR KHASANAH','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('930','P-20517517-486','PAT20517517-486','20517517-486-eYt','12177','MEYRA MAGHFIRO','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('931','P-20517517-487','PAT20517517-487','20517517-487-xJd','12175','MOHAMMAD FASHRIQUL AZIZ MAULANA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('932','P-20517517-488','PAT20517517-488','20517517-488-ko3','12178','MUHAMAD AGUNG TRI LAKSONO','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('933','P-20517517-489','PAT20517517-489','20517517-489-Ofm','12179','MUHAMMAD RISKI AKBAR SAPUTRA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('934','P-20517517-490','PAT20517517-490','20517517-490-EY7','12180','NAYAKA ANANDITA AYORA PUTRI NEOTA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('935','P-20517517-491','PAT20517517-491','20517517-491-akX','12181','ORIN ZAQIB AURIGAR','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('936','P-20517517-492','PAT20517517-492','20517517-492-XKb','12182','RADITYA HERNANDA','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('937','P-20517517-493','PAT20517517-493','20517517-493-04X','12183','RAMADHAN PERMATA WONGSO','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('938','P-20517517-494','PAT20517517-494','20517517-494-wPB','12155','RISKY ALBUCHORI','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('939','P-20517517-495','PAT20517517-495','20517517-495-28k','12184','SAFIRA IFNAIFA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('940','P-20517517-496','PAT20517517-496','20517517-496-dFL','12156','SAIFUL','8','VIII-G',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('941','P-20517517-497','PAT20517517-497','20517517-497-VfN','12157','SASYA SEPTIA RAHMA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('942','P-20517517-498','PAT20517517-498','20517517-498-aWg','12158','SELFIA NADZIRHO','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('943','P-20517517-499','PAT20517517-499','20517517-499-w3z','12511','Shela Aurelia','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('944','P-20517517-500','PAT20517517-500','20517517-500-SRT','12185','SHOLIHATUS NUR ANISA','8','VIII-G',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('945','P-20517517-501','PAT20517517-501','20517517-501-VUk','12186','ABDURROHMAN. D','8','VIII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('946','P-20517517-502','PAT20517517-502','20517517-502-dpk','12514','Abied Zandra Alkindi','8','VIII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('947','P-20517517-503','PAT20517517-503','20517517-503-IUY','12187','AGUS CHANDRA SETIAWAN PAMBUDI','8','VIII-H',NULL,'Islam','L','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('948','P-20517517-','PAT20517517-','20517517--R0l','12513','ANINDITA NANDA PERTIWI','8','VIII-H',NULL,'Islam','P','20517517','MKKS-27','1','0');
INSERT INTO `siswa` VALUES ('949','P-20517470-1','PAT20517470-1','20517470-1-W4B','6145','ACHMAD FATHUR RAMADHANI','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('950','P-20517470-2','PAT20517470-2','20517470-2-oZ4','6146','ADINDA MIMI ANGGRAENI SETIAWAN','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('951','P-20517470-3','PAT20517470-3','20517470-3-h9T','6147','AHMAD WILDAN ASSIROJIL FUAD','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('952','P-20517470-4','PAT20517470-4','20517470-4-j45','6148','AIKO VANIA WAHNA BAHARI','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('953','P-20517470-5','PAT20517470-5','20517470-5-Ttw','6149','ASKIA NUR AISYAH','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('954','P-20517470-6','PAT20517470-6','20517470-6-qBW','6150','AZIAN SYAMIL YAHYA ABDILLAH','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('955','P-20517470-7','PAT20517470-7','20517470-7-B4U','6151','BERLIAN MERIANA','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('956','P-20517470-8','PAT20517470-8','20517470-8-VGU','6152','DITA DWI NUR ANGGREANI','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('957','P-20517470-9','PAT20517470-9','20517470-9-XEs','6153','EVA RAHMAWATI','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('958','P-20517470-10','PAT20517470-10','20517470-10-RKW','6154','FARHAN MUTIARA SANDY','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('959','P-20517470-11','PAT20517470-11','20517470-11-yNt','6155','HAMDANI MAULANA NAJIB DZANUR','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('960','P-20517470-12','PAT20517470-12','20517470-12-lBm','6157','JESIKA ANGGRA PUTRIMAULITA','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('961','P-20517470-13','PAT20517470-13','20517470-13-I2Q','6158','LISNDA AULA VINA ROHMA','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('962','P-20517470-14','PAT20517470-14','20517470-14-6bK','6159','MIKO DWI SAPUTRA','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('963','P-20517470-15','PAT20517470-15','20517470-15-keu','6160','MOHAMMAD ANNAS FIRMANSYAH','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('964','P-20517470-16','PAT20517470-16','20517470-16-6Zp','6161','RAFFA ANANDA WIJAYA','7','VII-A',NULL,'Kristen','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('965','P-20517470-17','PAT20517470-17','20517470-17-26Q','6162','RAHMAD AFANDI','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('966','P-20517470-18','PAT20517470-18','20517470-18-n0j','6163','REFA APRIANI','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('967','P-20517470-19','PAT20517470-19','20517470-19-ypB','6164','VINNO ANARGYA AZHILLA','7','VII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('968','P-20517470-20','PAT20517470-20','20517470-20-fzN','6165','YULIA PRATIWI','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('969','P-20517470-21','PAT20517470-21','20517470-21-m3r','6166','ZULFA ZAHIROH','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('970','P-20517470-22','PAT20517470-22','20517470-22-3KN','6235','SOFIA SALSABILA','7','VII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('971','P-20517470-23','PAT20517470-23','20517470-23-56h','6167','ADINDA SILVI HUMAIROH MAHFUD','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('972','P-20517470-24','PAT20517470-24','20517470-24-Brm','6168','AHMAD DHANI ALFARIZI','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('973','P-20517470-25','PAT20517470-25','20517470-25-QMP','6169','ALSYIFA AZZAHRA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('974','P-20517470-26','PAT20517470-26','20517470-26-agq','6170','AZHEA FELOVE RAMADHANI','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('975','P-20517470-27','PAT20517470-27','20517470-27-Yz3','6171','BAKTIAR ILHAM PRATAMA','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('976','P-20517470-28','PAT20517470-28','20517470-28-Pkz','6172','CAMELIA PUTRI MAULIDYA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('977','P-20517470-29','PAT20517470-29','20517470-29-Zoq','6173','FARIDATUL ILMIAH','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('978','P-20517470-30','PAT20517470-30','20517470-30-x2g','6174','HELLENIA TASYA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('979','P-20517470-31','PAT20517470-31','20517470-31-OxE','6175','IRFAN APRILIO','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('980','P-20517470-32','PAT20517470-32','20517470-32-Ncm','6176','KHALISHA APRILIA RAHMA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('981','P-20517470-33','PAT20517470-33','20517470-33-RyE','6177','MAIA PUSPITA ANGGRAENY','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('982','P-20517470-34','PAT20517470-34','20517470-34-9Hl','6178','MOCH BAGAS','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('983','P-20517470-35','PAT20517470-35','20517470-35-Mi3','6179','MOHAMMAD NOVAL AFANDI','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('984','P-20517470-36','PAT20517470-36','20517470-36-8fx','6180','NINDY AISYAH SAPUTRI','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('985','P-20517470-37','PAT20517470-37','20517470-37-UFJ','6181','NIZAM AZIB HAMDANI','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('986','P-20517470-38','PAT20517470-38','20517470-38-nGJ','6182','NURJANA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('987','P-20517470-39','PAT20517470-39','20517470-39-H7t','6183','RAHMAT HIDAYAT','7','VII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('988','P-20517470-40','PAT20517470-40','20517470-40-JCv','6184','REFIKA RIFFI RAMADHANI','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('989','P-20517470-41','PAT20517470-41','20517470-41-JKI','6185','SYAFA RAHMADANIA','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('990','P-20517470-42','PAT20517470-42','20517470-42-hkF','6187','VIVI ULFATUL INAYAH','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('991','P-20517470-43','PAT20517470-43','20517470-43-sAG','6188','ZASKIA EKA WARDANI','7','VII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('992','P-20517470-44','PAT20517470-44','20517470-44-7mA','6189','AFFRILYA ISNAYNI HIKMAH','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('993','P-20517470-45','PAT20517470-45','20517470-45-vuB','6190','AHMAD RIFKY ARDIYANSYAH','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('994','P-20517470-46','PAT20517470-46','20517470-46-QpY','6191','AMANDA BINTANG AISATUL MAGFIROH','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('995','P-20517470-47','PAT20517470-47','20517470-47-ZU1','6192','ARIF WIRUL ANAM','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('996','P-20517470-48','PAT20517470-48','20517470-48-CIq','6193','AZIZAH AL SALSYABILA','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('997','P-20517470-49','PAT20517470-49','20517470-49-Ogv','6194','CHILA CANTIKA RIZKY ARVIANSYAH','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('998','P-20517470-50','PAT20517470-50','20517470-50-WIZ','6195','DAFFA RENANDA','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('999','P-20517470-51','PAT20517470-51','20517470-51-YTJ','6196','ERIKA PUTRI RAMADANI','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1000','P-20517470-52','PAT20517470-52','20517470-52-oPg','6197','GISTA RYAN FAMELINA','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1001','P-20517470-53','PAT20517470-53','20517470-53-GEw','6198','ICHA MARCELLYNA JOHA','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1002','P-20517470-54','PAT20517470-54','20517470-54-jdk','6199','KIKI ADYASTHA AKIRA','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1003','P-20517470-55','PAT20517470-55','20517470-55-tAi','6200','KRISNAWATI','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1004','P-20517470-56','PAT20517470-56','20517470-56-NSq','6201','MELANI WULANDARI','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1005','P-20517470-57','PAT20517470-57','20517470-57-DyH','6203','MUHAMMAD ALVINO','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1006','P-20517470-58','PAT20517470-58','20517470-58-rbP','6204','NIKITA WILI AGUSTIN','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1007','P-20517470-59','PAT20517470-59','20517470-59-d6S','6205','NUR AZIZAH','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1008','P-20517470-60','PAT20517470-60','20517470-60-Y2K','6206','PUTRA HANDIKA PRATAMA','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1009','P-20517470-61','PAT20517470-61','20517470-61-t2C','6207','RENO SEPTA BRAMANTYO','7','VII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1010','P-20517470-62','PAT20517470-62','20517470-62-SUv','6208','SANDRA ASSANIA PUTRI','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1011','P-20517470-63','PAT20517470-63','20517470-63-PL3','6210','ZEYRA ANALISA NAULIA','7','VII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1012','P-20517470-64','PAT20517470-64','20517470-64-Ukt','6212','AIDA FITRI MUBAROK','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1013','P-20517470-65','PAT20517470-65','20517470-65-wuD','6213','ANGELA CHRISTINA ENUAR','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1014','P-20517470-66','PAT20517470-66','20517470-66-MnO','6214','ARDHIKA DWI FATRA GUNAWAN','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1015','P-20517470-67','PAT20517470-67','20517470-67-Vmn','6215','ARSYAFINOW PUTRAMA YANUAR','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1016','P-20517470-68','PAT20517470-68','20517470-68-k1B','6216','AZIZAH AYUDIA QAMARIENA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1017','P-20517470-69','PAT20517470-69','20517470-69-FoN','6217','DIA AYUNITA PUTRI','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1018','P-20517470-70','PAT20517470-70','20517470-70-KwH','6218','DIVA ADELIA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1019','P-20517470-71','PAT20517470-71','20517470-71-reu','6219','EDI FABIYAN PRAYOGA','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1020','P-20517470-72','PAT20517470-72','20517470-72-Sua','6220','ERSA AURELIA MAYYASA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1021','P-20517470-73','PAT20517470-73','20517470-73-lTs','6221','HAFIZAH AZA MAZAYA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1022','P-20517470-74','PAT20517470-74','20517470-74-mEv','6222','ICHA VERANIKA RAMAHDANI','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1023','P-20517470-75','PAT20517470-75','20517470-75-3x6','6223','LIA FITRI WULANDARI','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1024','P-20517470-76','PAT20517470-76','20517470-76-RXZ','6224','MAHENDRA PUTRA AFANDI','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1025','P-20517470-77','PAT20517470-77','20517470-77-kqG','6225','MOCHAMMAD ZAHRA PUTRA MAHENDRA','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1026','P-20517470-78','PAT20517470-78','20517470-78-0zX','6226','MUHAMMAD REFA ADITYAH','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1027','P-20517470-79','PAT20517470-79','20517470-79-WeR','6227','MUHAMMAD ZIDAN','7','VII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1028','P-20517470-80','PAT20517470-80','20517470-80-g4Z','6228','NADIA JENITA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1029','P-20517470-81','PAT20517470-81','20517470-81-Fzi','6229','SHERIS ANGGITA SILVANI','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1030','P-20517470-82','PAT20517470-82','20517470-82-Vke','6230','WIDIA NOVA NUR HIDAYATI','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1031','P-20517470-83','PAT20517470-83','20517470-83-iwm','6231','ZIHAN FAHIRA','7','VII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1032','P-20517470-84','PAT20517470-84','20517470-84-j7Q','6114','A DZARIAN DZAKI ARIFIANTO','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1033','P-20517470-85','PAT20517470-85','20517470-85-LTv','6035','ACHMAD FAUZAN','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1034','P-20517470-86','PAT20517470-86','20517470-86-i67','6116','ADITYA DWI ANDITO','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1035','P-20517470-87','PAT20517470-87','20517470-87-ExJ','6060','AGHIS HULVIATUL VIVI','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1036','P-20517470-88','PAT20517470-88','20517470-88-aLP','6087','AGUNG CAHYONO','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1037','P-20517470-89','PAT20517470-89','20517470-89-UbZ','6062','ALIYATUS ZAHRO','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1038','P-20517470-90','PAT20517470-90','20517470-90-Yrq','6089','ANANDYA FADILATUL ROHMAH','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1039','P-20517470-91','PAT20517470-91','20517470-91-6yu','6118','ANITA NINGSIH','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1040','P-20517470-92','PAT20517470-92','20517470-92-Dwq','6065','AZRIL ALBANI','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1041','P-20517470-93','PAT20517470-93','20517470-93-jEW','6091','BIMA ARYA SENA','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1042','P-20517470-94','PAT20517470-94','20517470-94-EW3','6141','ELI SABET','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1043','P-20517470-95','PAT20517470-95','20517470-95-8cM','6041','ELIZA RIRIS AGUSTIN','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1044','P-20517470-96','PAT20517470-96','20517470-96-SZW','6042','EVAN FABIAN','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1045','P-20517470-97','PAT20517470-97','20517470-97-N78','6124','FELISA JULIA CAHYANI','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1046','P-20517470-98','PAT20517470-98','20517470-98-qsQ','6072','HAMZAH MAULANA','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1047','P-20517470-99','PAT20517470-99','20517470-99-3XE','6045','LODY AULIA PUTRI','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1048','P-20517470-100','PAT20517470-100','20517470-100-4Sh','6129','MIZEL PRADANA SYSTIO PUTRA','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1049','P-20517470-101','PAT20517470-101','20517470-101-7bq','6050','NOVITA ISKIA SAFINA','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1050','P-20517470-102','PAT20517470-102','20517470-102-yZt','6101','NUR LAILATUL RAHMA','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1051','P-20517470-103','PAT20517470-103','20517470-103-SkJ','6052','PANDU AKDI NURFAI','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1052','P-20517470-104','PAT20517470-104','20517470-104-48y','6103','PANJI PRASTYO','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1053','P-20517470-105','PAT20517470-105','20517470-105-qtf','6053','RAEHAN MUHAMAD FARDAN','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1054','P-20517470-106','PAT20517470-106','20517470-106-cdB','6079','RENDI HERMAWAN PUTRA','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1055','P-20517470-107','PAT20517470-107','20517470-107-wQc','6082','SAFITRI YUNITA SAVARA','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1056','P-20517470-108','PAT20517470-108','20517470-108-26k','6137','SATRIA GALANG FIRMANSYAH','8','VIII-A',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1057','P-20517470-109','PAT20517470-109','20517470-109-dBX','6112','SEVY ALBACIA','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1058','P-20517470-110','PAT20517470-110','20517470-110-y0e','6110','SHINTA AYU PRATIWI','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1059','P-20517470-111','PAT20517470-111','20517470-111-E7e','6139','YASMINE AZZAHRA','8','VIII-A',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1060','P-20517470-112','PAT20517470-112','20517470-112-jxg','6088','ALIF SHAHRUL ALAMSHAH','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1061','P-20517470-113','PAT20517470-113','20517470-113-EPa','6061','ALIN DEVITA ARIATI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1062','P-20517470-114','PAT20517470-114','20517470-114-uwQ','6117','ANGGA KURNIAWAN SAPUTRA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1063','P-20517470-115','PAT20517470-115','20517470-115-uEv','6120','AYU CHENDY AULIA','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1064','P-20517470-116','PAT20517470-116','20517470-116-SeW','6121','BRELYAN HEGA HEIFANSYADY LIYANTO','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1065','P-20517470-117','PAT20517470-117','20517470-117-1o4','6039','CITRA EKA YUNIAN MARADONA','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1066','P-20517470-118','PAT20517470-118','20517470-118-jkW','6066','DELLA NOVELINA ANGGRAINI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1067','P-20517470-119','PAT20517470-119','20517470-119-j9M','6040','DEVA SAHADATU RAHMAWATI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1068','P-20517470-120','PAT20517470-120','20517470-120-BGI','6122','DIVA ULVATUS ZAHRANI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1069','P-20517470-121','PAT20517470-121','20517470-121-fcm','6123','DIMAS SULIADI','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1070','P-20517470-122','PAT20517470-122','20517470-122-qRe','6068','DISVA SANTANG MAULANA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1071','P-20517470-123','PAT20517470-123','20517470-123-FaD','6094','DONA SOMANDA TENOMIA','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1072','P-20517470-124','PAT20517470-124','20517470-124-lFD','6096','GEA FELISYA PUTRI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1073','P-20517470-125','PAT20517470-125','20517470-125-GKw','6074','LIGA DWI SAPUTRA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1074','P-20517470-126','PAT20517470-126','20517470-126-Hv1','6099','M ALVIN NUGROHO AGUNG ALFAREZI','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1075','P-20517470-127','PAT20517470-127','20517470-127-HAE','6127','MARCEL ARYA BRAMANTIO','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1076','P-20517470-128','PAT20517470-128','20517470-128-gHR','6075','MARSEL ADITYA SAPUTRA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1077','P-20517470-129','PAT20517470-129','20517470-129-Gpr','6076','MAULIDA FEBRIANTI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1078','P-20517470-130','PAT20517470-130','20517470-130-DiK','6077','MUHAMMAD DARUSHALAM','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1079','P-20517470-131','PAT20517470-131','20517470-131-XoF','6130','NADINE MEY NAYZILLA','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1080','P-20517470-132','PAT20517470-132','20517470-132-FmY','6102','OKTAVIANI DWI SAFITRI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1081','P-20517470-133','PAT20517470-133','20517470-133-bKe','6051','OLIVIA WULANDARI','8','VIII-B',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1082','P-20517470-134','PAT20517470-134','20517470-134-pjO','6105','RANGGA BAYU SAPUTRA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1083','P-20517470-135','PAT20517470-135','20517470-135-vab','6055','SATRIA GILANG FIRMANSYAH','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1084','P-20517470-136','PAT20517470-136','20517470-136-1YP','6084','VALENTA BAGAS PRADANA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1085','P-20517470-137','PAT20517470-137','20517470-137-Gdi','6058','XCEL EKA FAREL FARENO SAPUTRA','8','VIII-B',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1086','P-20517470-138','PAT20517470-138','20517470-138-s1F','6086','ACHMAD WAHYUDHI AMINULLOH','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1087','P-20517470-139','PAT20517470-139','20517470-139-1Pu','6115','ADIT ERLANGGA','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1088','P-20517470-140','PAT20517470-140','20517470-140-w9Q','6064','ALVARO RAFA FIDIANT','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1089','P-20517470-141','PAT20517470-141','20517470-141-k53','6036','AN NISATUL NABILLA AR RIZZQI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1090','P-20517470-142','PAT20517470-142','20517470-142-3CN','6038','ANITA AYU PURNAMA SARI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1091','P-20517470-143','PAT20517470-143','20517470-143-n6a','6093','DEVINA ANJAR PUTRI LISFIANTI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1092','P-20517470-144','PAT20517470-144','20517470-144-XiR','6043','FX BINTANG KRESTIAWAN','8','VIII-C',NULL,'Kristen','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1093','P-20517470-145','PAT20517470-145','20517470-145-lfn','6073','INTAN NUR AINI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1094','P-20517470-146','PAT20517470-146','20517470-146-nqa','6046','MAHENDRA DEWA SASMITA PUTRA','8','VIII-C',NULL,'Kristen','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1095','P-20517470-147','PAT20517470-147','20517470-147-Ouv','6128','MARIAM','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1096','P-20517470-148','PAT20517470-148','20517470-148-7k8','6100','MOHAMMAD KURNIAWAN EKA DEVANO','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1097','P-20517470-149','PAT20517470-149','20517470-149-Pwv','6132','OLIVIA','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1098','P-20517470-150','PAT20517470-150','20517470-150-QZu','6133','RADITIYA ANDIKA PUTRA PRATAMA','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1099','P-20517470-151','PAT20517470-151','20517470-151-XzE','6135','REZA FIKRI FEBRIANDO','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1100','P-20517470-152','PAT20517470-152','20517470-152-ZaE','6080','REZKY RAHMAD HASBULLOH','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1101','P-20517470-153','PAT20517470-153','20517470-153-Pqb','6081','RISCA LAILI PUTRI NUR ALVIANA','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1102','P-20517470-154','PAT20517470-154','20517470-154-Rie','6136','RISMATUL HASANA','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1103','P-20517470-155','PAT20517470-155','20517470-155-tCM','6109','SALSABILLA ARDHANA RESWARI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1104','P-20517470-156','PAT20517470-156','20517470-156-MVp','6113','SEPTRYNO DHILIAN HARAYA','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1105','P-20517470-157','PAT20517470-157','20517470-157-b6Y','6111','SIVANA LETISYA PUTRI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1106','P-20517470-158','PAT20517470-158','20517470-158-yoJ','6057','STIVEN BATIS TINO','8','VIII-C',NULL,'Kristen','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1107','P-20517470-159','PAT20517470-159','20517470-159-7JD','6085','SULAICHA SILVI AYU MARDANY','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1108','P-20517470-160','PAT20517470-160','20517470-160-Df5','6059','WAHYU HIDAYAH','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1109','P-20517470-161','PAT20517470-161','20517470-161-tuN','6140','YUNIAR HARIKE EKA PUTRA','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1110','P-20517470-162','PAT20517470-162','20517470-162-Ohz','6234','SERILIYA ANANDA PUTRI','8','VIII-C',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1111','P-20517470-163','PAT20517470-163','20517470-163-vsa','6236','MOCH RIZKY','8','VIII-C',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1112','P-20517470-164','PAT20517470-164','20517470-164-Cq2','6063','ALLAIKA HIDAYATULLOH','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1113','P-20517470-165','PAT20517470-165','20517470-165-OhM','6037','ANANDA RERE AIRLANGGA SETIAWAN','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1114','P-20517470-166','PAT20517470-166','20517470-166-npW','6090','ANDRIYAS KASTARO','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1115','P-20517470-167','PAT20517470-167','20517470-167-LkO','6119','ARIEL ANSYAH','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1116','P-20517470-168','PAT20517470-168','20517470-168-v3H','6092','CINTA AMANI FATIHAH','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1117','P-20517470-169','PAT20517470-169','20517470-169-J9x','6067','DEVI RAHMATUL HUSNAH','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1118','P-20517470-170','PAT20517470-170','20517470-170-kQI','6095','FERY SETIAWAN','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1119','P-20517470-171','PAT20517470-171','20517470-171-lSu','6069','FRIZMA ACHMAD SHULTON','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1120','P-20517470-172','PAT20517470-172','20517470-172-7Ky','6070','GHIVANCA FERINA PUTRI','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1121','P-20517470-173','PAT20517470-173','20517470-173-BpI','6097','IKBAL RAMADANI','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1122','P-20517470-174','PAT20517470-174','20517470-174-zGB','6125','JUWITA ERNIA NUR ARIFIN','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1123','P-20517470-175','PAT20517470-175','20517470-175-qI3','6098','KHAFIFAH','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1124','P-20517470-176','PAT20517470-176','20517470-176-EIw','6126','LINDA DWI LESTARI','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1125','P-20517470-177','PAT20517470-177','20517470-177-y43','6143','M JAENAL ABIDIN','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1126','P-20517470-178','PAT20517470-178','20517470-178-DgJ','6047','MELINDA PUTRI BUDIMAN','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1127','P-20517470-179','PAT20517470-179','20517470-179-Gne','6048','MUHAMMAD JAMIL JAHO','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1128','P-20517470-180','PAT20517470-180','20517470-180-avx','6131','NUR ROHMAH','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1129','P-20517470-181','PAT20517470-181','20517470-181-S1X','6104','PUTRI ULUMIA SYAFITRI','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1130','P-20517470-182','PAT20517470-182','20517470-182-P7O','6078','RAFA ADITYA','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1131','P-20517470-183','PAT20517470-183','20517470-183-tQN','6106','REZA NOVTYA PUTRA','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1132','P-20517470-184','PAT20517470-184','20517470-184-TkZ','6108','RISA AMELLIA','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1133','P-20517470-185','PAT20517470-185','20517470-185-PnK','6054','ROMAN DEFIN ADRIANO','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1134','P-20517470-186','PAT20517470-186','20517470-186-dWJ','6083','SHINTA ROHMANIA','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1135','P-20517470-187','PAT20517470-187','20517470-187-X9s','6056','SILVINA WAHYUNING TIA','8','VIII-D',NULL,'Islam','P','20517470','MKKS-54','1','0');
INSERT INTO `siswa` VALUES ('1136','P-20517470-188','PAT20517470-188','20517470-188-pJt','6138','TOUHIR ARIE ARDAN JOVANI','8','VIII-D',NULL,'Islam','L','20517470','MKKS-54','1','0');

/*---------------------------------------------------------------
  TABLE: `soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `soal`;
CREATE TABLE `soal` (
  `id_soal` int NOT NULL AUTO_INCREMENT,
  `id_bank` int DEFAULT NULL,
  `nomor` int DEFAULT NULL,
  `soal` longtext,
  `jenis` int DEFAULT NULL,
  `opsi` int DEFAULT NULL,
  `pilA` longtext,
  `pilB` longtext,
  `pilC` longtext,
  `pilD` longtext,
  `pilE` longtext,
  `perA` text,
  `perB` text,
  `perC` text,
  `perD` text,
  `perE` text,
  `jawaban` text,
  `file` longtext,
  `file1` mediumtext,
  `fileA` mediumtext,
  `fileB` mediumtext,
  `fileC` mediumtext,
  `fileD` mediumtext,
  `fileE` mediumtext,
  `ket` text,
  `sts` int DEFAULT '0',
  `max_skor` int DEFAULT '1',
  PRIMARY KEY (`id_soal`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb3;
INSERT INTO `soal` VALUES   ('6','3','1','<p>Pada gambar terlampir, pasangan sudut sehadap adalah &hellip;.</p>\r\n<p>&nbsp;</p>','1',NULL,'<p>P<sub>4</sub> dan Q<sub>2</sub></p>','<p>P<sub>2</sub> dan Q<sub>3</sub></p>','<p>P<sub>1</sub> dan Q<sub>2</sub></p>','<p>P<sub>3</sub> dan Q<sub>3</sub></p>','',NULL,NULL,NULL,NULL,NULL,'D','3_1_1.jpg','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('7','3','2','<p>Dari gambar terlampir, nilai x adalah</p>\r\n<p>&nbsp;</p>','1',NULL,'<p>25&deg;</p>','<p>22&deg;</p>','<p>15&deg;</p>','<p>11&deg;</p>','',NULL,NULL,NULL,NULL,NULL,'A','','3_2_2.png','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('8','3','3','<p>Perhatikan gambar berikut!</p>\r\n<p>Dari gambar tersebut diketahui ABC kongruen CDE . Panjang CD adalah &hellip;. Cm.</p>','1',NULL,'<p>6</p>','<p>8</p>','<p>10</p>','<p>12</p>','',NULL,NULL,NULL,NULL,NULL,'D','3_3_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('9','3','4','<p>Perhatikan gambar di bawah ini!</p>\r\n<p>PQRS adalah jajargenjang dengan panjang QR = 17 cm, PQ = 10 cm, dan TR = 25 cm. Panjang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PT adalah &hellip;</p>','1',NULL,'<p>8</p>','<p>9</p>','<p>10</p>','<p>13</p>','',NULL,NULL,NULL,NULL,NULL,'A','3_4_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('10','3','5','<p>Perhatikan gambar!</p>\r\n<p>Gambar terlampir merupakan sebuah roda putar yang dibagi menjadi 24 bagian.</p>\r\n<p>Pada sebuah acara, seorang tamu memutar panah yang dapat berhenti di sembarang bagian roda. Apabila terdapat <sup>7</sup>&frasl;<sub>24</sub> bagian berwarna biru, <sup>1</sup>&frasl;<sub>8&nbsp;</sub>bagian berwarna ungu, <sup>5</sup>&frasl;<sub>12&nbsp;</sub> bagian kuning, dan sisanya berwarna merah, maka peluang yang paling kecil yang ditunjukkan&nbsp; panah adalah ... .</p>','1',NULL,'<p>Kuning</p>','<p>Merah</p>','<p>Ungu</p>','<p>biru</p>','',NULL,NULL,NULL,NULL,NULL,'C','3_5_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('11','3','6','<p>Gambar sebuah koin mata uang dan sebuah dadu</p>\r\n<p>Sebuah dadu dan sebuah mata uang dilempar bersama &ndash; sama , peluang muncul mata dadu ganjil dan angka pada mata uang adalah &hellip;.</p>','1',NULL,'<p><sup>1</sup>/<sub>2</sub></p>','<p><sup>5</sup>/<sub>12</sub></p>','<p><sup>1</sup>/<sub>3</sub></p>','<p><sup>1</sup>/<sub>4</sub></p>','',NULL,NULL,NULL,NULL,NULL,'D','3_6_1.jpg','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('12','3','7','<p>Di dalam sebuah kantong terdapat 10 kelereng yang diberi nomor 1, 2, 3, 4, . . . , 10. Secara acak di ambil 2 buah kelereng sekaligus. Maka peluang terambilnya dua buah kelereng yang bernomor prima adalah ... .</p>','1',NULL,'<p><sup>2</sup>/<sub>15</sub></p>','<p><sup>3</sup>/<sub>15</sub></p>','<p><sup>4</sup>/<sub>15</sub></p>','<p><sup>16</sup>/<sub>45</sub></p>','',NULL,NULL,NULL,NULL,NULL,'A','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('13','3','8','<p>Diketahui himpunan P adalah himpunan nama bapak-bapak dan himpunan Q adalah himpunan nama anak-anak, serta relasi P ke Q adalah \"<strong>ayah dari\"</strong></p>\r\n<p>P = {Gunawan, Jumakir, Heru, Purnomo}</p>\r\n<p>Q = { Linda, Uut, Danis,Tika, maya }</p>\r\n<p>&nbsp;</p>\r\n<p>Himpunan pasangan berurutannya adalah {(Gunawan, Danis), (Jumakir, Linda), (Heru, Tika), (Purnomo,Uut)}. Maka kodomainnya adalah ...</p>\r\n<p>&nbsp;</p>','1',NULL,'<p>{Linda, Uut, Danis, Tika}</p>','<p>{Linda, Uut, Danis,Tika, Maya}</p>','<p>{Jumakir, Heru, Purnomo}</p>','<p>{Gunawan, Jamakir, Heru, Purnomo}</p>','',NULL,NULL,NULL,NULL,NULL,'B','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('14','4','1','<p>Jika diketahui besar &ang;AOC = 57<sup>0</sup>, maka besar &ang;COB adalah &hellip;</p>\r\n<p>&nbsp;</p>','1',NULL,'<p>33<sup>o</sup></p>','<p>123<sup>o</sup></p>','<p>63<sup>o</sup></p>','<p>143<sup>o</sup></p>','',NULL,NULL,NULL,NULL,NULL,'B','4_1_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('15','3','9','<p>Perusahaan Taksi Kuda dan perusahaan Taksi Zebra sama-sama beroperasi di sebuah kota. Skema tarif kedua taksi tersebut disajikan dalam tabel berikut</p>\r\n<table style=\"height: 320px; width: 469px; border-style: inset; border-color: #000000;\" border=\"1\" width=\"469\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 219.323px;\" colspan=\"2\">\r\n<p><strong>Taksi Kuda</strong></p>\r\n</td>\r\n<td style=\"width: 235.677px;\" colspan=\"2\">\r\n<p><strong>Taksi Zebra</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 99.9479px;\">\r\n<p><strong>Kilometer</strong></p>\r\n</td>\r\n<td style=\"width: 114.042px;\">\r\n<p><strong>Tarif</strong></p>\r\n</td>\r\n<td style=\"width: 113.469px;\">\r\n<p><strong>Kilometer</strong></p>\r\n</td>\r\n<td style=\"width: 116.875px;\">\r\n<p><strong>Tarif</strong></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 99.9479px;\">\r\n<p>Awal (0 km)</p>\r\n<p>1 km</p>\r\n<p>2 km</p>\r\n<p>3 km</p>\r\n<p>...</p>\r\n<p>10 km</p>\r\n</td>\r\n<td style=\"width: 114.042px;\">\r\n<p>Rp.12.000,-</p>\r\n<p>Rp.14.500,-</p>\r\n<p>Rp.17.000,-</p>\r\n<p>Rp.19.500,-</p>\r\n<p>&nbsp;</p>\r\n<p>...</p>\r\n</td>\r\n<td style=\"width: 113.469px;\">\r\n<p>Awal (0 km)</p>\r\n<p>1 km</p>\r\n<p>2 km</p>\r\n<p>3 km</p>\r\n<p>...</p>\r\n<p>10 km</p>\r\n</td>\r\n<td style=\"width: 116.875px;\">\r\n<p>Rp.13.000,-</p>\r\n<p>Rp.15.000,-</p>\r\n<p>Rp.17.000,-</p>\r\n<p>Rp.19.000,-</p>\r\n<p>&nbsp;</p>\r\n<p>...</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Ahmad ingin pergi ke museum kotayang berjarak 10 km. Agar diperoleh biaya yang lebih murah, taksi manakah yang harus di pilih Ahmad?</p>','1',NULL,'<p>Taksi Kuda karena selalu lebih murah</p>','<p>Taksi Zebra karena selalu lebih murah</p>','<p>Taksi Kuda karena lebih murah Rp.4.000,-</p>','<p>Taksi Zebra karena lebih murah Rp.4.000,-</p>','',NULL,NULL,NULL,NULL,NULL,'D','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('16','4','2','<p><strong> Perhatikan gambar tersebut!</strong><br />Langkah yang benar untuk membagi &ang;ABC menjadi dua sama besar adalah&hellip;.</p>','1',NULL,'<p>(1), (4), (3), (2)</p>','<p>(2), (3), (1), (4)</p>','<p>(1), (3), (2), (4)</p>','<p>(4), (3), (2), (1)</p>','',NULL,NULL,NULL,NULL,NULL,'C','4_2_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('17','3','10','<p>Diketahui rumus fungsi f(x)= px + q. Jika f(2)= 11 dan f(-3)= -19, maka nilai dari p &ndash; q adalah ... .</p>','1',NULL,'<p>7</p>','<p>5</p>','<p>-5</p>','<p>-7</p>','',NULL,NULL,NULL,NULL,NULL,'A','','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('18','3','11','<p>Perhatikan gambar berikut!</p>\r\n<p>Persamaan garis yang memenuhi grafik tersebut adalah &hellip;</p>','1',NULL,'<p>2x + 4y = 8</p>','<p>4x + 2y = 8</p>','<p>&ndash; 4x + 2y = -8</p>','<p>&ndash; 2x + 4y = -8</p>','',NULL,NULL,NULL,NULL,NULL,'B','3_11_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('19','3','12','<p>Perhatikan gambar!</p>\r\n<p>Grafik yang menunjukan persamaan garis 3x &ndash; 2y = 12 adalah &hellip; .</p>','1',NULL,'<p>.</p>','<p>.</p>','<p>.</p>','<p>.</p>','',NULL,NULL,NULL,NULL,NULL,'A','','','3_12_A.png','3_12_B.png','3_12_C.png','3_12_D.png','',NULL,'0','1');
INSERT INTO `soal` VALUES ('20','3','13','<p>Nilai ulangan sekelompok siswa adalah sebagai berikut :</p>\r\n<p>7, 6, 6, 9, 8, 9, 6, 8, 7, x</p>\r\n<p>Median dari nilai siswa tersebut 7,5 maka nilai x yang memenuhi adalah ... .</p>','1',NULL,'<p>5</p>','<p>6</p>','<p>7</p>','<p>8</p>','',NULL,NULL,NULL,NULL,NULL,'D','','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('21','4','3','<p><strong>Lihat Gambar tersebut!</strong></p>\r\n<p>Gambar pencerminan yang benar adalah&hellip;.</p>','1',NULL,'<p>Gambar A</p>','<p>Gambar B</p>','<p>Gambar C</p>','<p>Gambar D</p>','',NULL,NULL,NULL,NULL,NULL,'D','4_3_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('22','3','14','<p>Dalam pendataan no sepatu 8 anak di kelas 8 diperolah data sebagai berikut.</p>\r\n<table style=\"border-style: solid; width: 597px; border-color: #000000;\" border=\"1\" width=\"597\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 139.979px;\">\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Anak&nbsp;&nbsp;&nbsp;</p>\r\n</td>\r\n<td style=\"width: 59.9062px;\">\r\n<p>1</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>2</p>\r\n</td>\r\n<td style=\"width: 43.3125px;\">\r\n<p>3</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>4</p>\r\n</td>\r\n<td style=\"width: 43.3125px;\">\r\n<p>5</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>6</p>\r\n</td>\r\n<td style=\"width: 50.9271px;\">\r\n<p>7</p>\r\n</td>\r\n<td style=\"width: 52.1042px;\">\r\n<p>8</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 139.979px;\">\r\n<p>Nomor sepatu</p>\r\n</td>\r\n<td style=\"width: 59.9062px;\">\r\n<p>36</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>40</p>\r\n</td>\r\n<td style=\"width: 43.3125px;\">\r\n<p>37</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>38</p>\r\n</td>\r\n<td style=\"width: 43.3125px;\">\r\n<p>37</p>\r\n</td>\r\n<td style=\"width: 52.0417px;\">\r\n<p>39</p>\r\n</td>\r\n<td style=\"width: 50.9271px;\">\r\n<p>x</p>\r\n</td>\r\n<td style=\"width: 52.1042px;\">\r\n<p>40</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Jika modus dalam data tersenut adalah 37 maka nilai x yang memenuhi adalah &hellip;&hellip;</p>','1',NULL,'<p>37</p>','<p>38</p>','<p>39</p>','<p>40</p>','',NULL,NULL,NULL,NULL,NULL,'A','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('23','4','4','<p>Pak Anto memiliki sebuah kamar tidur berbentuk persegi panjang dengan ukuran Panjang (x+5) m dan lebarnya (x-3) m. <br />Keliling kamar pak Anto adalah&hellip; m</p>','1',NULL,'<p>2x+2</p>','<p>2x-2</p>','<p>4x+4</p>','<p>4x-4</p>','',NULL,NULL,NULL,NULL,NULL,'C','4_4_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('24','3','15','<p>Tabel di bawah ini menunjukkan hasil ulangan matematika dari sekelompok siswa.</p>\r\n<table style=\"border-style: solid; width: 296px; border-color: #000000;\" border=\"1\" width=\"296\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 77.1667px;\">\r\n<p>Nilai</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>4</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>5</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>6</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>7</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>8</p>\r\n</td>\r\n<td style=\"width: 29.7292px;\">\r\n<p>9</p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 77.1667px;\">\r\n<p>Frekuensi</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>3</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>5</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>7</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>x</p>\r\n</td>\r\n<td style=\"width: 29.6875px;\">\r\n<p>7</p>\r\n</td>\r\n<td style=\"width: 29.7292px;\">\r\n<p>3</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Rata-rata dari data dia atas 6,57 maka niali x yang memenuhi adalah &hellip; .&nbsp;</p>','1',NULL,'<p>8</p>','<p>7</p>','<p>6</p>','<p>5</p>','',NULL,NULL,NULL,NULL,NULL,'D','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('25','4','5','<p>Pak Anto memiliki sebuah kamar tidur berbentuk persegi panjang dengan ukuran panjang (x+5) m dan lebarnya (x-3) m.<br />Jika lantai kamar pak Anto akan dipasangi keramik, maka luas keramik yang dibutuhkan adalah... m<sup>2</sup></p>','1',NULL,'<p>x<sup>2</sup>-2x-15</p>','<p>x<sup>2</sup>+2x-15</p>','<p>x<sup>2</sup>+2x+15</p>','<p>x<sup>2</sup>-2x+15</p>','',NULL,NULL,NULL,NULL,NULL,'B','4_5_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('27','4','6','<p><strong>Perhatikan gambar berikut !</strong></p>\r\n<p>Pasangan mana yang tidak memiliki kesamaan bentuk permukaan?</p>','1',NULL,'<p>a dan b</p>','<p>c dan d</p>','<p>a dan d</p>','<p>b dan f</p>','',NULL,NULL,NULL,NULL,NULL,'C','4_6_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('28','4','7','<p><strong>Perhatikan gambar berikut!</strong></p>\r\n<p>Caca mempunyai kotak penyimpanan mainan berbentuk balok, seperti pada gambar. Kotak tersebut berukuran panjang 15 cm, lebar 8 cm dan tinggi 6 cm. Jika Caca ingin melapisi bagian dalam kotak tersebt dengan kain flanel.</p>\r\n<p><em><strong>Maka luas kain flanel yang dibutuhkan Caca adalah..... cm<sup>2</sup></strong></em></p>','1',NULL,'<p>516</p>','<p>425</p>','<p>396</p>','<p>276</p>','',NULL,NULL,NULL,NULL,NULL,'C','4_7_1.jpg','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('29','4','8','<p>Luas permukaan bola yang berjari-jari 7 cm adalah......&nbsp; cm<sup>2</sup></p>','1',NULL,'<p>154</p>','<p>314</p>','<p>616</p>','<p>1.232</p>','',NULL,NULL,NULL,NULL,NULL,'C','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('30','4','9','<p><strong>Perhatikan gambar dibawah ini!</strong></p>\r\n<p>Jika AD//EC dan AD garis bagi &ang;BAC maka pernyataan yang benar adalah ....<br /></p>','1',NULL,'<p>m&ang;DAC = m&ang;ACE</p>','<p>m&ang;ABC = m&ang;ACB</p>','<p>m&ang;ABC = m&ang;AEC</p>','<p>m&ang;BAD = m&ang;DAC</p>','',NULL,NULL,NULL,NULL,NULL,'D','4_9_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('31','4','10','<p><strong>Perhatikan gambar berikut ini!</strong></p>\r\n<p>Jika AG//BE//CD dan &ang;CDE = 65&deg; maka &ang;EAG adalah ....</p>','1',NULL,'<p>kurang dari 35&deg;</p>','<p>35&deg;</p>','<p>kurang dari 65&deg;</p>','<p>65&deg;</p>','',NULL,NULL,NULL,NULL,NULL,'D','4_10_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('32','5','1','<p><em>Bacalah teks berita berikut dengan cermat.</em></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p>Erupsi Gunung Semeru, PVMBG Catat Tinggi Kolom Abu 1.000 meter dari Puncak</p>\r\n<p>&nbsp;</p>\r\n<p>Jakarta, iNews.id &ndash; Gunung Semeru mengalami erupsi pagi ini, Jumat (9/2/2024) pukul 05.39 WIB. Pusat Vulkanologi dan Mitigasi Bencana Geologi (PVMBG) melaporkan tinggi kolom letusan 1.000 meter di atas puncak. Gunung Semeru yang secara administratif terletak dalam dua kabupaten yaitu Lumajang dan Kabupaten Malang, Provinsi Jawa Timur saat ini masih berstatus Siaga Level III.</p>\r\n<p>&ldquo;Terjadi erupasi Gunung Semeru pada hari Jumat, 9 Februari 2024, pukul 05:39 WIB. Tinggi kolom letusan teramati &plusmn; 1000 m di atas puncak (&plusmn; 4676 m di atas permukaan laut),&rdquo; ujar Petugas Pos Pengamatan Gunung Api Ghufron Alwi, Jumat (9/2/2024). Dia mengatakan, kolom abu teramati berwarna putih hingga kelabu dengan intensitas tebal ke arah utara dan barat laut. Erupsi terekam di seismograf dengan amplitudo maksimum 22 mm dan durasi 124 detik.</p>\r\nLebih lanjut, Ghufron mengingatkan Masyarakat agar tidak melakukan aktivitas apa pun di sektor Tenggara di sepanjang Besuk Kobokan sejauh 13 km dari puncak (pusat erupsi). Di luar jarak tersebut, masyarakat tidak melakukan aktivitas pada jarak 500meter dari tepi sungai (sempadan sungai) di sepanjang Besuk Kobokan karena berpotensi terlanda perluasan awan panas dan aliran lahar hingga jarak 17 km dari puncak.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Informasi yang sesuai dengan teks berita di atas yaitu &hellip;</p>','1',NULL,'<p>Kejadian meletusnya Gunung Semeru terjadi di Jakarta pada 9 Februari 2024 pukul 5 dini hari.</p>','<p>Erupsi Gunung Semeru terekam di seismograf dengan amplitudo minimum 22 mm dan durasi 124 detik.</p>','<p>Masyarakat diingatkan agar tidak melakukan aktivitas pada jarak 500 kilometer dari tepi sungai di sepanjang Besuk Kobokan</p>','<p>PVMBG melaporkan tinggi kolom letusan 1.000 meter di atas puncak terjadi pada Jumat, 9 Februari 2024 pukul 05.39 WIB.</p>','',NULL,NULL,NULL,NULL,NULL,'D','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('33','4','11','<p><strong>Perhatikan informasi berikut!</strong><br />\"Pengunjung Perpustakaan\"<br />Suatu hari Ani menemukan sobekan koran yang memuat data pengunjung perpustakaan berupa gambar diagram batang sebagai berikut<em> (lihat gambar di atas)</em>. <strong>Rata-rata pengunjung 41 orang selama lima hari.</strong></p>\r\n<p>Informasi yang ada pada koran tersebut menunjukkan data pengunjung perpustakaan selama 5 hari. Ani penasaran ingin tahu tentang banyak pengunjung pada hari Rabu. Tolong bantu Ani, berapa banyak pengunjung pada hari Rabu?</p>','1',NULL,'<p>55 orang</p>','<p>60 orang</p>','<p>65 orang</p>','<p>70 orang</p>','',NULL,NULL,NULL,NULL,NULL,'D','4_11_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('34','4','12','<p><strong>Perhatikan data tinggi badan siswa berikut!</strong></p>\r\n<p>Median dari data di atas adalah &hellip;</p>','1',NULL,'<p>156,5 cm</p>','<p>157 cm</p>','<p>157,5 cm</p>','<p>158 cm</p>','',NULL,NULL,NULL,NULL,NULL,'C','4_12_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('35','5','2','<p>Bacalah dua teks berita berikut.</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p><strong><em>Berita 1</em></strong></p>\r\n<p>PT KAI Daops 1 Jakarta telah membuka pemesanan tiket kereta api tambahan Lebaran sejak 6 Maret lalu. Saat ini PT KAI telah menambah jumlah kereta tambahan sebanyak 344 jadwal perjalanan.</p>\r\n<p>Kereta tambahan tersebut untuk mengakomodir tingginya animo masyarakat untuk mendapatkan tiket mudik lebaran. Selain tiket kereta tambahan, PT KAI juga terus melakukan perbaikan pelayanan baik di stasiun maupun jalur perlintasan kereta. (Sindonews, 11-3-24)</p>\r\n<p><strong><em>Berita 2</em></strong></p>\r\n<p>Dengan spikenya yang mematikan, opposite hitter asal Indonesia itu mengantarkan Red Sparks ke babak playoff atau semifinal Liga Voli Korea Selatan pada Kamis (07/03) setelah mengalahkan GS Caltex dengan skor 3-0 dalam laga keempat putaran enam. Megawati mengaku tak sabar berlaga di \"momen bersejarah\" semifinal. Sebab, terakhir kali Red Sparks lolos ke babak playoffs adalah tujuh tahun silam.</p>\r\n<p>&ldquo;Saya sempat bilang saat pertama datang ke Korea, saya ingin menunjukkan yang terbaik dan saya ingin mengukir sejarah,&rdquo; kata perempuan yang akrab disapa Mega kepada wartawan BBC News Indonesia, Trisha Husada, Jumat (08/03)</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;Perbedaan isi kedua berita tersebut adalah &hellip;</p>','1',NULL,'<p>Berita 1 : liburan; Berita 2 : olahraga</p>','<p>Berita 1 : politik; Berita 2 : ekonomi</p>','<p>Berita 1 : transportasi; Berita 2 : olahraga</p>','<p>Berita 1 : ekonomi; Berita 2 : Pendidikan</p>','',NULL,NULL,NULL,NULL,NULL,'C','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('36','4','13','<p>Pelemparan dadu sebanyak 25 kali.</p>\r\n<p>Angka yang keluar datanya sebagai berikut:&nbsp;<br /><strong>1 2 3 4 5 5 6 2 3 4 5 6 6 4 3 2 1 4 3 5 6 6 5 4 5</strong></p>\r\n<p>Modus dari data di atas adalah &hellip;</p>','1',NULL,'<p>3</p>','<p>4</p>','<p>5</p>','<p>6</p>','',NULL,NULL,NULL,NULL,NULL,'C','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('37','5','3','<p>Bacalah kutipan teks berita di gambar terlampir.</p>\r\n<p>Arti kata cagar budaya pada teks berita tersebut adalah &hellip;.</p>','1',NULL,'<p>Istilah hukum daerah yang kelestarian hidup tumbuh-tumbuhan dan binatang yang terdapat di dalamnya dilindungi oleh undang- undang dari bahaya kepunahan.</p>','<p>Daerah yang memiliki budaya peninggalan nenek moyang dan terdapat di masing-masing daerah.</p>','<p>Benda hasil akal budi manusia yang perlu diberikan pencagaran&nbsp; karena jika tidak dilindungi dikhawatirkan akan mengalami kerusakan dan kepunahan.</p>','<p>Tempat wisata yang dikelola pemerintah untuk mengenalkan budaya masa lampau agar ramai dikunjungi.</p>','',NULL,NULL,NULL,NULL,NULL,'C','5_3_1.jpg','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('38','4','14','<p>Salah satu bentuk keamanan digital, misalnya untuk akun email atau media sosial, maka kalian diwajibkan membuat password. Panjang password yang disyaratkan paling sedikit terdiri dari 6 angka, meskipun dapat lebih panjang untuk keamanan ekstra. Dari sebuah survei mengenai panjang password yang digunakan untuk akun email siswa, diperoleh hasil sebagai berikut:</p>\r\n<p><strong>(lihat gambar terlampir)</strong></p>\r\n<p>Berdasarkan diagram lingkaran di atas, besar sudut yang mewakili bagian panjang password 9-angka adalah &hellip;</p>','1',NULL,'<p>30 derajat</p>','<p>45 derajat</p>','<p>60 derajat</p>','<p>80 derajat</p>','',NULL,NULL,NULL,NULL,NULL,'B','4_14_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('39','4','15','<p>Berdasarkan diagram batang ganda di bawah ini yang menunjukkan perolehan medali emas, perak, dan perunggu Indonesia dalam ajang Pesta Olahraga Se-Asia Tenggara SEA Games.</p>\r\n<p><strong>(LIHAT GAMBAR TERLAMPIR)</strong></p>\r\n<p>Pada SEA Games tahun berapakah jumlah perolehan medali emas Indonesia hampir dua kali dari perolehan medali emas tahun sebelumnya.</p>','1',NULL,'<p>Tahun 2011</p>','<p>Tahun 2015</p>','<p>Tahun 2017</p>','<p>Tahun 2019</p>','',NULL,NULL,NULL,NULL,NULL,'D','4_15_1.png','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('40','5','4','<p>Bacalah berita <em>online</em> berikut dengan teliti.</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p style=\"text-align: center;\">300 Juta Data Dukcapil Diduga Bocor, Kominfo Lakukan Prosedur Standar</p>\r\n<ol>\r\n<li>Kementerian Komunikasi dan Informatika (Kominfo) menyebut pihaknya akan melakukan prosedur standar untuk menelusuri dugaan kebocoran data Kependudukan dan Pencatatan Sipil (Dukcapil). &ldquo;Kita biasanya akan memanggil atau berkoordinasi dengan yang namanya pengendali data, dalam hal ini kalau Dukcapil itu kan adanya di Kemendagri, nanti kita akan koordinasi termasuk juga dengan BSSN,&rdquo; ujar Usman Kansong , Direktur Jenderal Informasi dan Komunikasi Publik (Dirjen IKP) Kominfo di Kantor Kominfo, Jakarta, Senin (17/7).</li>\r\n<li>Usman menyebut pihaknya akan mendengarkan laporan dari Disdukcapil. Kemudian, BSSN biasanya akan melakukan audit untuk &ldquo;mencari tau data yang mana yang bocor, berapa banyak, baru kemudian dilaporkan ke Kominfo kita akan lihat kalo ada pengendalian data yang tidak baik maka sudah diatur dalam PP 71 tahun 2019 sanksi apa yg bisa kita jatuhkan kepada pengendali data.&rdquo;</li>\r\n<li>Sebelumnya, sebanyak 337 juta data masyarakat di Direktorat Dukcapil Kementerian Dalam Negeri (Kemendagri) diduga mengalami kebocoran dan dijual di forum online hacker BreachForums.</li>\r\n<li>Kebocoran itu diungkap Teguh Aprianto, pendiri Ethical Hacker Indonesia, di media sosial pada Minggu (16/7). Teguh menjelaskan data yang dipastikan bocor adalah nama, Nomor Induk Kependudukan (NIK), nomor Kartu Keluarga (KK), tanggal lahir, alamat, nama ayah, nama ibu, NIK ibu, nomor akta lahir, nomor akta nikah dan lainnya.</li>\r\n<li>Saat ini, lanjut Teguh, pihaknya bersama para pemangku kepentingan terkait, seperti Badan Siber dan Sandi Negara (BSSN) serta Kementerian Komunikasi dan Informatika (Kemenkominfo) melaksanakan dua agenda, yakni audit investigasi dan mitigasi preventif.</li>\r\n</ol>\r\n<p>&nbsp;(Sumber: https://www.cnnindonesia.com/teknologi/20230717173708-192-974565/300-juta-data-dukcapil-diduga-bocor-kominfo-lakukan-prosedur-standar)</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>Struktur berita nomor 1 disebut &hellip;.</p>','1',NULL,'<p>Ekor berita</p>','<p>Teras berita</p>','<p>Judul berita</p>','<p>Isi berita</p>','',NULL,NULL,NULL,NULL,NULL,'B','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('41','6','1','<p><strong>SEJARAH PERKEMBANGAN SEPAK BOLA DUNIA </strong></p>\r\n<p><strong>&nbsp;</strong></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p>Olahraga sepak bola dimulai sejak abad ke-2 dan -3 sebelum Masehi di Cina. Di masa Dinasti Han tersebut, masyarakat menggiring bola kulit dengan menendangnya ke jaring kecil. Permainan serupa juga dimainkan di Jepang dengan sebutan Kemari. Di Italia, permainan menendang dan membawa bola juga digemari terutama mulai abad ke-16.</p>\r\n<p>Sepak bola modern mulai berkembang di Inggris dan menjadi sangat digemari. Di beberapa kompetisi, permainan ini menimbulkan banyak kekerasan selama pertandingan sehingga akhirnya Raja Edward III melarang olahraga ini dimainkan pada tahun 1365. Raja James I dari Skotlandia juga mendukung larangan untuk memainkan sepak bola. Pada tahun 1815, sebuah perkembangan besar menyebabkan sepak bola menjadi terkenal di lingkungan universitas dan sekolah. Kelahiran sepak bola modern terjadi di Freemasons Tavern pada tahun 1863 ketika 11 sekolah dan klub berkumpul dan merumuskan aturan baku untuk permainan tersebut. Bersamaan dengan itu, terjadi pemisahan yang jelas antara olahraga rugby dan sepak bola (soccer). Pada tahun 1869, membawa bola dengan tangan mulai dilarang dalam sepak bola. Selama tahun 1800-an, olahraga tersebut dibawa oleh pelaut, pedagang, dan tentara Inggris ke berbagai belahan dunia. Pada tahun 1904, asosiasi tertinggi sepak bola dunia (FIFA) dibentuk dan pada awal tahun 1900-an, berbagai kompetisi dimainkan di berbagai negara.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p><strong>Berdasarkan teks diatas di benua manakah awal olahraga sepak bola mulai&hellip;.</strong></p>','1',NULL,'<p>Eropa</p>','<p>Amerika</p>','<p>Asia</p>','<p>Australia</p>','',NULL,NULL,NULL,NULL,NULL,'C','6_1_1.jpg','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('42','6','2','<p><strong>SEJARAH PERKEMBANGAN SEPAK BOLA DUNIA </strong></p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p>Olahraga sepak bola dimulai sejak abad ke-2 dan -3 sebelum Masehi di Cina. Di masa Dinasti Han tersebut, masyarakat menggiring bola kulit dengan menendangnya ke jaring kecil. Permainan serupa juga dimainkan di Jepang dengan sebutan Kemari. Di Italia, permainan menendang dan membawa bola juga digemari terutama mulai abad ke-16.</p>\r\n<p>Sepak bola modern mulai berkembang di Inggris dan menjadi sangat digemari. Di beberapa kompetisi, permainan ini menimbulkan banyak kekerasan selama pertandingan sehingga akhirnya Raja Edward III melarang olahraga ini dimainkan pada tahun 1365. Raja James I dari Skotlandia juga mendukung larangan untuk memainkan sepak bola. Pada tahun 1815, sebuah perkembangan besar menyebabkan sepak bola menjadi terkenal di lingkungan universitas dan sekolah. Kelahiran sepak bola modern terjadi di Freemasons Tavern pada tahun 1863 ketika 11 sekolah dan klub berkumpul dan merumuskan aturan baku untuk permainan tersebut. Bersamaan dengan itu, terjadi pemisahan yang jelas antara olahraga rugby dan sepak bola (soccer). Pada tahun 1869, membawa bola dengan tangan mulai dilarang dalam sepak bola. Selama tahun 1800-an, olahraga tersebut dibawa oleh pelaut, pedagang, dan tentara Inggris ke berbagai belahan dunia. Pada tahun 1904, asosiasi tertinggi sepak bola dunia (FIFA) dibentuk dan pada awal tahun 1900-an, berbagai kompetisi dimainkan di berbagai negara.</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><strong>Negara mana saja yang turut mengembangkan sepak bola di dunia ( <em>Pilihlah dua Jawaban benar </em>)</strong></p>\r\n<p>&nbsp;</p>','3',NULL,'<p>Indonesia</p>','<p>Italia</p>','<p>Cina</p>','<p>Inggris</p>','',NULL,NULL,NULL,NULL,NULL,'C, D','6_2_1.jpg','','','','','','',NULL,'0','2');
INSERT INTO `soal` VALUES ('43','5','5','<p>Perhatikan gambar-gambar berikut berikut.</p>\r\n<p>Berdasarkan infografis di atas, perbedaan berita cetak dan digital adalah &hellip;</p>','1',NULL,'<p>Berita cetak</p>\r\n<p>Warna : berwarna</p>\r\n<p>Penempatan iklan : ada halaman khusus</p>\r\n<p>Berita digital</p>\r\n<p>Warna : hitam putih</p>\r\n<p>Penempatan iklan : atas, bawah, kanan, kiri</p>','<p>Berita cetak</p>\r\n<p>Warna : hitam putih</p>\r\n<p>Penempatan iklan : ada halaman khusus</p>\r\n<p>Berita digital</p>\r\n<p>Warna : berwarna</p>\r\n<p>Penempatan iklan : atas, bawah, kanan, kiri</p>','<p>Berita cetak</p>\r\n<p>Warna : hitam putih dan berwarna</p>\r\n<p>Penempatan iklan : tidak ada</p>\r\n<p>Berita digital</p>\r\n<p>Warna : berwarna</p>\r\n<p>Penempatan iklan : ada halaman khusus</p>','<p>Berita cetak</p>\r\n<p>Warna : hitam putih</p>\r\n<p>Penempatan iklan : atas, bawah, kanan, kiri</p>\r\n<p>Berita digital</p>\r\n<p>Warna : hitam putih</p>\r\n<p>Penempatan iklan : ada atas, bawah, kanan, kiri</p>','',NULL,NULL,NULL,NULL,NULL,'B','5_5_1.png','5_5_2.jpg','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('44','3','16','<p>Perhatikan gambar berikut!<br /><br /></p>\r\n<p>Bila diketahui r<sup>0</sup>=30<sup>0</sup>,&nbsp; &nbsp;maka tentukan benar atau salah pernyataan berikut&nbsp; ...</p>\r\n<p>Berilah tanda (<strong>V</strong>) pada pernyataan dibawah ini yang dianggap benar atau salah!</p>','4',NULL,'<p>Besar sudut s adalah 130&deg;</p>','<p>Besar sudut s adalah 150&deg;</p>','<p>Besar sudut t adalah 30&deg;</p>','<p>Besar sudut t adalah 60&deg;</p>','',NULL,NULL,NULL,NULL,NULL,'S, B, B, S','3_16_1.jpg','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('45','4','16','<p><strong>Perhatikan gambar berikut!</strong></p>\r\n<p>Dari gambar lingkaran dan bagian-bagiannya, nyatakanlah pendapatmu tentang pernyataan berikut ini dengan Benar atau Salah!</p>','4',NULL,'<p>OA, OB, OC, OD adalah jari-jari</p>','<p>EF adalah tali busur</p>','<p>Jika AB, CD dan EF tegak lurus PQ maka ketiganya sejajar</p>','<p>CD adalah garis singgung lingkaran</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, B, S','4_16_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('46','4','17','<p><strong>Perhatikan gambar di bawah ini!</strong><br /><br />Nyatakanlah pendapatmu tentang pernyataan berikut ini dengan Benar atau Salah!</p>','4',NULL,'<p>Pada gambar (i) apabila bangun datar digerakkan dengan cara diputar akan membentuk bangun ruang tabung</p>','<p>Pada gambar (ii) bangun prisma segitiga dapat dibentuk dengan cara memutar segitiga</p>','<p>Pada gambar (iii) bangun ruang yang dapat dibentuk adalah tabung</p>','<p>Bangun ruang bola dapat dibentuk seperti yang ada pada gambar (i)</p>','',NULL,NULL,NULL,NULL,NULL,'S, S, B, B','4_17_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('47','4','18','<p><strong>Berikut ini adalah grafik keadaan siswa SMP Negeri 45 selama 5 tahun terakhir.</strong></p>\r\n<p>Berdasarkan grafik di atas, tentukan Benar atau Salah pernyataan-pernyataan berikut.</p>','4',NULL,'<p>Jumlah siswa terbanyak terdapat pada tahun 2020</p>','<p>Jumlah siswa tahun 2020 lebih kecil dari tahun 2019</p>','<p>Setiap tahun jumlah siswa laki-laki lebih sedikit dari jumlah siswa perempuan</p>','<p>Persentase siswa laki-laki selama 3 tahun terakhir sama</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, S, S','','4_18_2.png','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('48','4','19','<p style=\"text-align: left;\"><strong>Perhatikan informasi berikut!</strong></p>\r\n<p style=\"text-align: center;\"><strong>DARURAT SAMPAH DI IBU PERTIWI</strong></p>\r\n<p style=\"text-align: left;\">Julukan sebagai negara nomor dua penghasil sampah plastik di dunia, sudah melekat dalam beberapa tahun ini kepada Indonesia. Julukan yang mulanya diberikan peneliti dari Universitas Georgia, Amerika Serikat, Jenna Jambeck, kini mulai diikuti oleh negara lain dan juga di dalam negeri. Banyak kalangan yang menyebutkan bahwa produksi sampah di Indonesia hanya bisa dikalahkan oleh Tiongkok saja. Indonesia juga darurat sampah dalam cara pengelolaan sampah dimana sampah banyak dibuang kelaut. Berikut adalah grafik batang yang menjelaskan tentang 5 negara penyumbang terbesar sampah plastik ke lautan.</p>\r\n<p><strong>(lihat Grafik terlampir)</strong><br />Berdasarkan grafik di atas, tentukan pernyataan yang benar di bawah ini!</p>','4',NULL,'<p>Indonesia merupakan Negara kedua terbesar penyumbang sampah plastik ke lautan.</p>','<p>Sri langka menyumbangkan 14,6 ton sampah plastik ke lautan.</p>','<p>China yang menempati posisi pertama menyumbangkan lebih dari 10 kali lipat sampah plastik ke lautan dibandingkan banyak sampah plastik yang disumbangkan oleh Negara yang menempati posisi kelima.</p>','<p>Banyak sampah plastik yang disumbangkan oleh Filipina hanya sepertiga banyak sampah plastik yang disumbangkan Indonesia</p>','',NULL,NULL,NULL,NULL,NULL,'B, B, S, S','4_19_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('49','4','20','<p>Berdasarkan gambar di atas, hitunglah bentuk aljabar yang hilang dan nyatakanlah pendapatmu tentang pernyataan berikut ini dengan Ya / Tidak atau Benar / Salah</p>','4',NULL,'<p>Isian dari bangun lingkaran adalah 7x +4</p>','<p>Isian dari bangun trapesium adalah 9x + 3</p>','<p>Isian dari bangun jajargenjang adalah 5x -1</p>','','',NULL,NULL,NULL,NULL,NULL,'B, S, B','4_20_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('50','4','21','<p><strong>Perhatikan gambar berikut ini!</strong><br /><br />Pernyataan dibawah ini jika sesuai jawab Ya, jika tidak sesuai jawab Tidak</p>','4',NULL,'<p>&ang;EAG+&ang;AEF = 180&deg;</p>','<p>&ang;EAG dan &ang;AEF adalah sudut luar sepihak</p>','<p>Sudut dalam berseberangan besarnya sama</p>','<p>Sudut sehadap jumlahnya 180&deg;</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, B, S','4_21_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('51','4','22','<p>Manakah dari pernyataan berikut yang benar! <br />(<em><strong>jawaban lebih dari satu</strong></em>)</p>','3',NULL,'<p>13x - 7x=6x</p>','<p>6(5+3x)-10x= 8x+30</p>','<p>9x-17x= 8x</p>','<p>8-3(3-5x)=-1+15x</p>','',NULL,NULL,NULL,NULL,NULL,'A, B, D','','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('52','3','17','<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 100%;\">\r\n<p>Dalam acara permainan &ldquo;Lets Make a Deal&rdquo;, para peserta disuruh memilih satu dari 24 kotak misteri. Mereka memilih salah satu dan menyisakannya, kemudian menyingkirkan 23 kotak lainnya selama acara berlangsung. 6 kotak misteri berisi hadiah uang Rp10.000,00, Rp20.000,00, Rp25.000,00, Rp50.000,00, Rp75.000,00, dan Rp100.000,00. Ada juga 4 kotak misteri berisi liburan, 4 kotak berisi hiburan di rumah, 2 kotak berisi mobil, dan sisanya berisi hadiah hiburan seperti penjepit kertas, permen karet, dan bola pimpong.</p>\r\n<p>Di empat babak yang berbeda selama acara, para peserta ditawari sebuah deal atau perjanjian dari pembawa acara yaitu menerima sejumlah uang dan pergi, atau tetap bermain dan menerima isi kotak yang semula mereka pilih.</p>\r\n<p>Adi telah memilih sebuah kotak untuk disihkan dan belum ada kotak lain yang disingkirkan. Pernyataan berikut yang benar?</p>\r\n<p>Berilah tanda (<strong>V</strong>) pada pernyataan dibawah ini yang dianggap benar atau salah!</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>','4',NULL,'<p>Peluang Adi memilih satu hadiah uang adalah <sup>1</sup>/<sub>4</sub></p>','<p>Peluang Adi memilih satu hadiah uang yang lebih besar dari Rp40.000,00 adalah <sup>3</sup>/<sub>4</sub></p>','<p>Peluang Adi memilih satu hadiah hiburan lebih kecil dibandingkan peluang memilih hadiah uang</p>','<p>Peluang Adi memilih satu liburan atau seperangkat hiburan di rumah <sup>1</sup>/<sub>3</sub></p>','',NULL,NULL,NULL,NULL,NULL,'B, S, S, B','','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('53','4','23','<p><strong>Perhatikan gambar!</strong><br /><br />P, Q, R adalah bidang, P dan Q sejajar, m adalah garis pada P yang memotong R, n adalah garis pada Q yang memotong R.</p>\r\n<p><br /><em><strong>Kedudukan dua garis pada ruang adalah:</strong></em><br />i. berpotongan<br />ii. berimpit<br />iii. bersilangan<br />iv. sejajar</p>\r\n<p>Kedudukan garis m dan n yang tidak mungkin adalah ...</p>','3',NULL,'<p>i dan ii</p>','<p>ii dan iii</p>','<p>iii dan iv</p>','<p>iv dan i</p>','',NULL,NULL,NULL,NULL,NULL,'A, B','4_23_1.png','','','','','','',NULL,'0','2');
INSERT INTO `soal` VALUES ('54','3','18','<p>Diketahui suatu persamaan garis 2x + 3y = 12. Berilah tanda (V) pada pernyataan berikut ini.</p>','4',NULL,'<p>Gradien garis tersebut adalah -<sup>2</sup>/<sub>3</sub></p>','<p>Persamaan garis yang sejajar adalah 4x + 6y = 12</p>','<p>Gradien garis tersebut adalah <sup>3</sup>/<sub>2</sub></p>','<p>Persamaan garis yang tegak lurus adalah 4x &ndash; 6y = -12</p>','',NULL,NULL,NULL,NULL,NULL,'B, B, S, S','','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('55','3','19','<p>Nyatakanlah ya atau tidak Pernyataan berikut untuk bangun datar yang kongruen !</p>','4',NULL,'<p>sisi yang bersesuaian sama panjang</p>','<p>sisi yang bersesuaian mempunyai perbandingan yang sama</p>','<p>sudut-sudut yang bersesuaian tidak sama besar</p>','<p>sudut-sudut yang bersesuaian sama besar</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, S, B','','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('56','4','24','<p>Data berikut ini merupakan skor yang diperoleh dari 15 siswa peserta ujian menulis yang terdiri atas 10 soal.<br /><strong>4, 7, 6, 5, 8, 9, 5, 7, 8, 9, 8, 10, 9, 10, 9</strong><br />Tentukan pernyatan berikut yang benar terkait dengan data di atas. <em><strong>(jawaban lebih dari satu)</strong></em></p>','3',NULL,'<p>Nilai tengah dari data di atas adalah 7</p>','<p>Rata-rata data di atas adalah 7,6</p>','<p>Modus data di atas adalah 9</p>','<p>Jangkauan dari data di atas adalah 5</p>','',NULL,NULL,NULL,NULL,NULL,'B, C','','','','','','','',NULL,'0','2');
INSERT INTO `soal` VALUES ('57','3','20','<p>Diagram terlampir menunjukkan data pemantauan jumlah kasus Covid-19 di Propinsi DKI Jakarta berdasarka kelompok umur yang berstatus positif per tanggal 31 Mei 2020</p>\r\n<p>Total warga DKI Jakarta berjenis kelamin perempuan yang berstatus positif Covid-19 sebanyak 1.192 jiwa, sedangkan warga berjenis kelamin laki-laki yang berstatus positif sebanyak 1.478 jiwa.</p>\r\n<p>&nbsp;</p>\r\n<p>Berilah tanda centang (V) pada kolom Ya atau Tidak untuk setiap penyataan.</p>','4',NULL,'<p>Terdapat 2.670 warga di Propinsi DKI Jakarta yang berstatus positif&nbsp; per tanggal 31 Mei 2020</p>','<p>Peluang laki-laki umur 30-39 terpapar Covid-19 adalah <sup>251</sup>/<sub>1.192</sub></p>','<p>Peluang perempuan umur 20-29 terpapar Covid-19 adalah <sup>178</sup>/<sub>1.192</sub></p>','<p>Rentang umur laki-laki maupun perempuan yang paling rentan terpapar Covid-19 adalah 50-59 tahun</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, B, B','3_20_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('58','3','21','<p>Grafik berikut menunjukan lama pengisian dan penggunaan baterai.</p>\r\n<p>Berilah tanda (V) pada pernyataan berikut ini jika Yaa tau Tidak !</p>','4',NULL,'<p>Prosentase saat pengisian baterai tersisa 20%</p>','<p>Pengisian baterai sampai penuh memerlukan waktu 1 jam</p>','<p>Jika HP tersebut terisi baterai sampai penuh dan digunakan terus menerus maka akan habis dalam 1 jam 40 menit</p>','<p>Dari grafik tersebut kurang 40 menit untuk baterai sampai penuh</p>','',NULL,NULL,NULL,NULL,NULL,'B, S, B, S','','3_21_2.png','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('59','4','25','<p>Perhatikan gambar!</p>\r\n<p>Dari gambar tersebut pasangkan pernyataan berikut ini dengan jawaban yang tepat!</p>','5',NULL,'<p>&ang; A_3&nbsp; dan &ang; B_1</p>','<p>&ang; A_1&nbsp; dan &ang; B_1</p>','<p>&ang; A_4&nbsp; dan &ang; B_3</p>','<p>&ang; A_2&nbsp; dan &ang; B_3</p>','','<p>Sudut-sudut sehadap</p>','<p>Sudut dalam bersebrangan</p>','<p>Sudut luar sepihak</p>','','','B, A, D','4_25_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('60','3','22','<p>Perhatikan pernyataan berikut!</p>\r\n<ol style=\"list-style-type: lower-roman;\">\r\n<li>Sisi-sisi yang berdekatan sama Panjang</li>\r\n<li>Jumlah sudut-sudut yang berdekatan adalah180<sup>0</sup></li>\r\n<li>Sudut yang berhadapan sama besar&nbsp;&nbsp;&nbsp;</li>\r\n<li>Sisi-sisi yang berhadapan sejajar</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<p>Dari pernyataan di atas yang merupakan sifat jajargenjang adalah &hellip;.</p>','1',NULL,'<p>(i), (ii), (iii)</p>','<p>(i), (iii), (iv)</p>','<p>(ii), (iii), (iv)</p>','<p>Semua benar</p>','',NULL,NULL,NULL,NULL,NULL,'C','','','','','','','',NULL,'0','1');
INSERT INTO `soal` VALUES ('61','3','23','<p>Perhatikan grafik terlampir</p>\r\n<p>Dari diagram cartesius di atas berilah tanda (V) yang merupakan pemetaan adalah ... .</p>\r\n<p>&nbsp;</p>','3',NULL,'<p>Diagram cartesius (I)</p>','<p>Diagram cartesius (II)</p>','<p>Diagram cartesius (III)</p>','<p>Diagram cartesius (VI)</p>','',NULL,NULL,NULL,NULL,NULL,'A, B, C','3_23_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('62','3','24','<p>Hasil Ulangan Matematika kelas 8 dari 10 siswa adalah sebagai berikut :</p>\r\n<table style=\"border-collapse: collapse; width: 100%;\" border=\"1\">\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 16.6667%;\">40</td>\r\n<td style=\"width: 16.6667%;\">60</td>\r\n<td style=\"width: 16.6667%;\">90</td>\r\n<td style=\"width: 16.6667%;\">80</td>\r\n<td style=\"width: 16.6667%;\">70</td>\r\n<td style=\"width: 16.6667%;\">80</td>\r\n</tr>\r\n<tr>\r\n<td style=\"width: 16.6667%;\">50</td>\r\n<td style=\"width: 16.6667%;\">80</td>\r\n<td style=\"width: 16.6667%;\">70</td>\r\n<td style=\"width: 16.6667%;\">90</td>\r\n<td style=\"width: 16.6667%;\">80</td>\r\n<td style=\"width: 16.6667%;\">90</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p><br />Tentukan pernyataan yang tepat dari pernyataan- pernyataan di bawah ini !</p>\r\n<p>&nbsp;</p>','3',NULL,'<p>Nilai modus dari data di atas adalah 80</p>','<p>Nilai median dari data di atas adalah 70</p>','<p>Nilai mean dari data di atas adalah 73</p>','<p>Banyak siswa yang mendapat nilai di atas rata rata adalah 9 anak</p>','',NULL,NULL,NULL,NULL,NULL,'A, C','','','','','','','',NULL,'0','2');
INSERT INTO `soal` VALUES ('63','4','26','<p><strong>Perhatikan gambar di bawah ini!</strong><br /><br />AB adalah lebar sungai, BC perpanjangan dari AB, ED perpanjangan dari AE, BE//CD.<br />Pasangkanlah pernyataan yang sesuai berikut ini!</p>','5',NULL,'<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB8AAAAlCAYAAAC6TzLyAAAAAXNSR0IArs4c6QAAAyJJREFUWEftl0euFTEUROuDGLAJ4oBVkNkGmWWQ2QYZlkFeB1liBUiMSH2ebrVuG79uW2qJybfU+u/b166bXd7Rfxw7ndiHJR2R9HJh3x5Jh+JD9I2k37H3o/f2gHPgq9h4WtKfigLI3JZ0PtaeSTogCaWZZ//eUEQ94FckPZD0dvhq4AA/GgBOSLoq6XVSEHmAn0i67PlWcA5+HFYcDPdlyzPwUVuWPAMObkephz1uZyPAuPD6YB3gGYB1XHpT0rmZfMATgHfFHKBb4S60vpDjFr9/RjjOVKy2oQATFjywGUtuZ51MJU5ofFfSjbDcFjgXrkVONBfQEjgHU1pYTozvhOvPpswnJBcLhZoUmAN3aWUZMplhcNaI5ckFcGf/GO85t3PovTjYtY08MX1RZK1Dkb2RLWcPnrlU9oZtlpNkJNepYgMH0d1QjOxmEJYPUcMA5OESpL6zEdWEAxQtSarPUTocbFeT6Xwk4fNIMAwgIWlAgDyNvXQ1ZKvANbdjBdZ6AOI4AZA9RQJSOgwsBAyl6envQpZEHUurzMKlbG/K2iSEEihV6/v/nLU2eJeyu+Bd7lpL2G7n7/61Dm0850cG/964aQ2xY5K+7SbcGq7sPqPX7Vws9OvyAnGLrc2zxj1Bq550vx5wWuevGfbKvQBLhefR37lqvwYg971Z7Xi7tYIjxyXCjbeNOtt6bkFkSi9AOlBqJJ+t4CYEMNeSveZY+74v+Zyp80TxFnCTSBPFOXDLYF2mTJ6HWuP2za3XAg7/Qu5+8Hdcv2+ILXQ5jxwaQHytkqAojDdQaLxul8BJMmJFIkEKatTZCvAGez+wmi/xwPD88Ug8GI1Z0WZtDpw1wPjrDMVqLKmRxbl4ez/7mh4NlA4kEkrkgfu2gW+LN3upccqNMz/5sG2Wm48T7+wqWzd58IV3XIrjEzhAvIdGM3lE1sAhgrgXIpnfXsiiOXGFOvsVA4bjTSmhmF3LWXgPjzE/oc8lODHhUeCWCIiftLxEAfQaiYVyfpPTQLCOeQaADF63PLPojpNRgvO/GSiCmfay5s9rlA3yHvyuUeUqff4LNbLHJjL6ulgAAAAASUVORK5CYII=\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BAC%7D%7BAB%7D\" /></p>','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB8AAAAlCAYAAAC6TzLyAAAAAXNSR0IArs4c6QAAA2NJREFUWEftmEeOFUEQRGOADZdgcNcABiNOgYdjDGMwt8BzAA6AF9fAiiMgscL1a2W0cupXm99/wWZK+mqmuypNZGRVFGv6j2NtSd/HJJ2V9FjS34G1ByQdjR/T3kn6I+m4pC9eu4xz5r4NhwRQc47T65I2Y97zeJ5pnjtNAFuSzkcgWsb5OUmvJX2NDErnOL4m6bakmylb/JPxpwa194Fcu3aqcwyTNYvWJZ1w9JGZM34g6WQ4ylUxagROgO2Y6vxuRH2pqdlVSQeTc2xg8JGkC4FOjQ7w5EPMm+z8UBjcaILdDVjJ/HPKGkghWA6qDIBagx7Em+TccN2R9EYST2qKIf5m3JD0sKnrs0BlcgONwQ7JgBQGU2+e1DU7Bw1YDMmAfvIYcg6JyC7PORLw2hHfaqUoA4ALlMmlGoX9VmSbswGJV01tISDZMkCBdxmN7Jw2g2ys/V22QA0mL2Az6QiS+jU7Zy79Tw93bZQ4BcmYz5w9o4QdqNmJIBU9CVwwmYGT7Qa6K/HtXhAtk+5pEI+12AYdiEj5FnbE0jl7N/B4ItnYObCywbCG7/yepLm0H+14Ot59j2AzcoOZT2Zqz0SQc2CjtsZabdTAKhP2na+C3uy1hp3n4dlW5i38mZ3/mGdj1qrLkl7sE24Wdqsu6oOd92ynHKG1wbaLBM5bJ2s4iMYG6/j1ajgMIRzYp9Fs7OHs1RwYyCUIQwCoGAfAucAavjGHkyyPU7H3dzpvjHCWSFmzYRADLytnOHs7B9G3LJFTBByv6IT2sBpyzjdEAJkjIrMQQMkgpy5GELaPc+ZxtPps5yi2guHmwqnZojUmoyz0reFYk7PrDIV3I5VlFtnyngAQoOiFUefI4F9N1ogGIuao5NqDmOAahHbLaGSkgBZ+cL5T6+r1aihzQ3s/DPm2AqEgEyTMbCfYj4EAwdEpBFuTV+20oVbrq7fvbKVgrNWbBAgaEcp3EOi0XJ9z15Woc70J2IhkUvG+rLe7Av0GQszn58tGb+aud+0iANx0QL6X5XrXrky0KtnDgU5I1jL3BsMViBtnd5lPFwQQyUx3velv3jNw4v8ksNbfc6MpnbMQ2UyEDJx4QB4YDJm6dgni8Y1dje8E4EEAtrWASOmcv4d4QO1KKUx2ltN5Oy3/vSCh/wF1HtwmAx54KwAAAABJRU5ErkJggg==\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BAC%7D%7BBE%7D\" /></p>','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAlCAYAAAAjt+tHAAAAAXNSR0IArs4c6QAAA2dJREFUWEftmGeOFEEMhd8SzkESxyDDLcjhFuTwixuQ0zHIx4AlnYPY38qv5bGqqweJ4deUNJqdbrvq2c+pdkP9tdF5/bvxriefxUfdnsJeSY8mDvkl6bmkd5I+JZmLkk7OGPVN0llJWyDmEO+Q9FLSruHAfSGP4mFJDyW9GDa6JglA3u9o6NwaHlxPOnskXZbE95FlAWyT9FMSm10tlr0JIIBExuvm8PuKpGOSXhcdnh3Ke815AKs3JR2X9CptBj24nsNuFA8ADI/tHyz+MchgBC5/LAm9M38DwNZgJa7ngwuJDf4+EYcYmz1GbEAFMgDEULxig5cKQjbDhbjsWeLyoKQ7YVF2PSDssa+D1Xx2x6d6cHRmjwJbczsCDVmQY9n9yAKsa/FPNrwNKghWgDlQF8KiB2CKfzZ4Elwi8zllgPn3gRgBXedCBj3+HsH0AJj/nYVn9jKHOQMq/9ViMoD0JRZmKQBYtcZKUEBGPA1rDMaZ0UpZgH6M1LTHxsJRC5cLBdxxCAHIIrUISNKIZ+eDf8AC6lS8g38CEGC8IxApQCxALpTwFgW4fspLX4bD3gfvdjF8A8qLPR2wmS5SM9eSSQ9Uj6z091wlXOnhbL4GsPaAPcD3h5VH3OIBdyXdywDI5/+9NtcxsIwH6HL0B8otEy3lmEbFogcwtOSGlEuyn3MO/aEOMN1CxME0H4/mHsNpqWzEO575PSAZUA5EA6L2AxgQNDKaEpMR3XB2JNsevZuRHCUszv2daQcAeSBxZUWWdxiQOx9tmjvDhTwttyhwe+Vwhk6+6/JMANAMzEMJbZxJOC/e0Q3Zv3sv8GDBJvT81izHBsQE41W2EnnmCKyst6rmkFM9wG/mdzafnGQjKJFdmG7insDIVanBE3iAuwSxMHquArALUWjNgr1Clcf4Sg16jGXf6yg3BaDOe/VgDqvU9PhHH1oeVHoqgMx/DSKDQIZL5+mCqse/veNL7uRYbp7I3zFS00EOJKzJ13JEPMa3+Gckd0ovzIWtIMQSpthLJQWR5WCi3nfDXPVa+Y8OxYnAJKhz1dzSbdUBAoirOC6mmlHtPJIzEdfDAUX1I3Mot+j4Eose/0PgRrRQATPyVmRDBVxT0SixKLOJb8hZBzlkfA9wXYBGAMF36985kx7opdo/f/cHkMrjJsiXGm8AAAAASUVORK5CYII=\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BBE%7D%7BCD%7D\" /></p>','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAmCAYAAAClI5npAAAAAXNSR0IArs4c6QAAA2hJREFUWEftmEmOFDEQRX/Djjswcw1mrsHMCdjTTMdghmswI3EIEAg4AhIrRr9S/JTT5UynVUj0AkutUpXDEd8xfveW/vHa6rSP/AVJryR9nDmL3EFJfO4P+V+Sfks6Jem5z/YC4PAzSadzJQUQDF+UdFbSkzC6S9LP9PtnScfjEqtjPQCQfREKLku6Xxhm/5Ckuwngm6T7Zhi1nQeSzksane0BcCmMn5N0W9J2AeBwZvxG2sfl+bL3kBvCtxQALuT2VyS9S/F9lLsxAdodocEg4cHd5SI0GEYXubA4BBzAnS8jmb7H58lMEd65V7q3QHAgwCE3rCUeIK7EG4PIf4hkMgB+42YYOBL7i4urBcCJh+s/xY2pAkoLYLjSGY7RkXuXoGgBIHFw2ZcwhvyxAEMykWjE/0clLLl9h5HEHeLfKkMnHiCc0QAgHJQThvkdORpL7pXy8rfiEpToaE15gN8pJW4+SppkEGXXUpNxOSFLEqL8TFYNNkRVcHNypizNaiNCGQZIKowBBLdhiGo4miriRMqBx1GO3B4vIEf3o0eQL/yha290xjXjtRBghDbqheGHAQBAhMPn2MOI+zogOEuO7JP0NvbYH8W9twyXJPOUzknDPtCqgl7j3fL/AewYDwBkT3cANz/wzR7g8+vm+ro0XJV0Z8eEoAv63xTu8YBlm82lB2ALgOk1VJzFxHudPiGYnn55q6X3500uBwuNMzUfCU0BZtwydpmGDBgoGdORPs9vzAFot9mxZ0U+yKDhAOUMAwz560s4oY2jDBo9PCQCrUdyScFMzzCcc0aOIfu+HNm1EHgicsvafEeZKbZJib1oelayZvbZg86hfwBXA2DlzHvGa22Oo4DwsJ/HeY4dm18SEryxou4lAAvxfJq6Pefg+CapeQ7BHQA2enyEgMPDV/PJNQA5wy3d26ouzpqy5zzS50xeCc/gudIDUG2U4H5uMrVGL9wQmos/IiQzvHH2bWgAPL8pm9rCOH2BN2K+5uKfP91G3ik90KLYzhGMlf8fmIs/ZBbAayVdqwIynLqn+eRNA3B0QJrJ0+L2tfi7ixLKqvFaFbheTbH5jkG/iGhAJcsFFF2QkFEZNCFK0985X3uur+4wNwvIB8oN5SjmeV7rCdwePX4n5q+otd5fJlVrGLVKb+P9P+pN2ifVId6SAAAAAElFTkSuQmCC\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BAC%7D%7BCD%7D\" /></p>','','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB8AAAAlCAYAAAC6TzLyAAAAAXNSR0IArs4c6QAAA1lJREFUWEft11mSlEUUBeCD8OImVMBtICoEqwAEdRmMKqyC2QW4AJVhHyIYLsEInmSoj8hbkZX9119dFBH0AxnR0VWVmXc4dzp5KB9wHdpS99Ek3yS5m+T1xN1P2m/kjvu+r/y2jXJnHzXhDBiFM+xh2/9iYeSTzpDP2vefkzwto7dR/m2SP5M8T3I8yasJzw+3M58nOdbt+/xHu3uq7u5XOTh5zVuC1yl37mUSHl4ZjHP/6yRH2pnsV/kPSf5JcjbJdwvhPJzynFF/JeFdhYANPAf3T0mub+M5S8EtztcWVl9unv89ATvh9t2pBHMPCoznxP/7jTlk7iW53zwp4aNn5IGctycXnj7oDPsqyc1WIUvF9jfBLskuLKy+2Dzx/9YErKVcvH9pnla5yXxG/dqQW4ZrTnl50p9RMoSB784A+7p4Owa582O45pR/35DplUgcCTWVzX28IVCrKsD3ZabPwU6JJBtLqrKWJ8LR545SGsuQc7qhCmHw1b45jZ6zsjJaMwFvlQzFYn6pNQuxvZ1EXlAKIV2tkk22UyoBKVZiPSJ7Ek6LJKxaJ2HVDmU4JZVIzqgEpSQP1i3GULpnFmzK9hmZu299VL47hu8g4UDAzohP38H4Xa68KM/9/28XSVvePZfktwMB+5aGv5/jB9JzRmmnRujU0nafDVTKHa1203LP31oyQZAhgoUYDnr4v22g6OMShgHGbpEDc8Ede84YJv060Qjk6TYxNzIZwk0uo7XnbAT8PsFoTEWDCF+b4vbG7o81rOZi3s/iFRLQRi06daYZUR4WcejnvVFchj9uU/MtWptoFC/AWxzOnd4747en0IVU0ax65fidAdgOvrBROW6ObSINLDaPEQNcDBkcyUGPFGiREY8EsZ4KwaznrAftjSaoXisSSjJJwt5rxuJ3FuNUCmMh11OuZRKug30u3vVmG7n7VLw5wGgUyz4EcMO3a53yiusYb3cKkZFEjvF2VlXggBByvh4fs8or3lP8HNxqf1mvzYliqVPvOKXKezmw5HJTnleDUd9ftm5UFySZtxhE+kyveKtvv1vuQFDD8TyWJysPjVG5i4Sz0KoHvs+SRwaPzx5I2KPEPgNqMaBk7UFkVO77XB6I3fg05l3R6ZV+OnzZ86R+A/K23iaamhIQAAAAAElFTkSuQmCC\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BAB%7D%7BBE%7D\" /></p>','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB8AAAAmCAYAAAA820BcAAAAAXNSR0IArs4c6QAAA0BJREFUWEftl1lu1kAQhCsgXjgEOxKnYL8GO+IUYecY7HALds7BLnEBJN7Y/FldVnsy9j9WlPCSkaIk9riru3qZmjX9x7W2EPugpEOSXk18t2vi+d947t/9v0vAMfw6jJySNDIk6bCkl/H+gKR38TcY+7r3zyQ9kfTRDi4BvyzpQRitgWMLB3+lPcaBMdjCgVuS/iyJHKNvItr9QX0ZOfZICZHd7QBupBTg2Kf4/4ik363gfPg4vF6XBDgU994Xy+ycSSkqnbrplLXQjqdEcanL3cMugvNdZLsnwO90kZ+TxDd2ju+uS3ovCef6qFsix7m3AQxtGMcQkZtG2yI1UE6xPY1iJjXHJV0LJgbgFnA8JY+m6nZXUFBf0pqpvRdM4TjgOPqiLLZV4G6tnBqiYNXAp/LNflqMdI0Ym8o5z6lYKty9jZHT0ctXIv+53nK+M73ugC9loU6B4yHFdbIYJoDTr1Ot9DUcdLFh/5GkC5JwmL+HFi3BAWUjRYWnZ9NEgmqo44cifB5DB4doP5xlqlFsLEDoEgoQO7A4as8SHIqI1gsQVzUtk/djnEjI9dQiABwaVXlrq83Y3fyrliGzeZQJCzvgW0btnGHTzu+92+zBzwz+YxvBj0n6vlNwW8W4ma1JrkXq1acas/3ijLccxQhGzghUKw5w4jGmGdHM+M+rzvPSPkaZ0czqmnplP2cDBwwHDUoV7cfivEAL4BB2eiZaCy4fjTVwR3u/O2i+RYT5BOO0/BCigugXgXNs4jUR1dQrxgHGSfaWypbn0M1xCzP9aoncItIyqQS33DoRqnW4kRR5AxxBMQjPFnA+YB+RkUMY2BM3Exch6gadtqoQoXtSydSKDI8pMKisSWc/uxrKprlt5yJ3i/DbIpKoabWsXs3G0SiqGjhdQHs131LdNtw0vMh3BscxtDyaj1tKLd8utg2FOCedXSDZoNVrls5zeh2neU8xUg8rI/d0YjBkb3EUNuhXpLNvMW4zVGp5gWQ/ItM1M0pJGTm59AUf5Ukx8TGLezWALN6h0T3pAEUmM8UYQryjBbHP89qNdkOfs3kYf8VHvPMPDmAw08jNlehhjOJCdo9aq6zElj5vbp2lG/8BKW/MJ77/ZXgAAAAASUVORK5CYII=\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BAB%7D%7BAC%7D\" /></p>','<p><img class=\"fm-editor-equation\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAlCAYAAAAjt+tHAAAAAXNSR0IArs4c6QAAA2pJREFUWEftmFdyFEEQRFPAD4dA+Gsg7C3w6AR84+EUeHMNPBwDfwUi+MHOU1RO1Nb07KwIYT7UERvSznRXZ2dWVVftkv7xWFpgf+bslsTfnZKeS/oZn8OSniYbW9L/zGeeh9fMbDkFgI3PSDoh6VGAwMB3SbskvZR0Nywy9373fTne8fhFvAM46x90tt5mBGMAeL5H0htJN7qTXJb0Iy2EhRVJ+4pBGIARNtwfQDn5tm79pTjIsbC7Zm4MwN6YdL2bdKVszrqzku6EYdjwAADfOSnMZQl4x+lhpX/XArBV0reYiMb55N7okKRTjU1gjU0Azokr28+CHQ64ZrcC4PvV7hQXJZ1L+lZfRW8+2QGZcy3WssG7sijLMwrANOFg6JbpnQqY5gZpEfa+BrNHbLsyYA3RCZqzhosAADD047R1bdOvKgBPwolOj+wISEKvvp/SH2kJZyKnZ/Z3GCAECc3HBaD1b0mXTz/DTssJMXRc0tGaNCSh3ck4faYYOwMPDydHSkKWRDSQphWGUEkIkWjQ09nMlNekBCiyH7LADhsxcGRnRewQMQOfGktE6EyyOBBGXqc0Wo2QKwjJ+hzbAPowL5qm7gK/X080TEXLIDuta8FGT55iYKP3G9jbBGAG+Lv9j/M93OBLBvD5LwM4L+nmpg/81wyQjseuZNJrLs/tPtx6FKRTo6+k5jFAfifPX4iLhVvyU+R8LiruCS4Zl+Vsyg3Knc+NCcBXCQnAWPMxFztTEjQr2TDK9cuN19d38dwVc6um5OYEIHOaRWmlziXaatzpfg9wik6kwGiunGlOqJhzzwCb7wMs7/qKeYoBn6ZWuX4O5fmeNzCYATy3KDKyKf50MJ4/yScZcxiM3YvFbOSBjmgMK7XIyEUt/gEQqiuKlOwrvbF5DNCg0JrhNO4LORF07ogeYKbPi17idtfWPYzTYx+wtYVbGAAdUkt/Tt5yQOtvySwJZR4D6WCiL3DmMTCmP4bcnGIY52J4s5kwCycFsCXFFyYBZP2RInu5dSYCcgjmxrQmMOzBDv3GTDs3xoD1pyImng0A/aGQpMJzG8MORSzlN5LhvJwSULwj7Og362Ga7Tko8XQ05pRQ6uEfGsiK+AcDB7sVIWZ5cjFL6DGazW6LgfwzC/+32vP8DBv+WNvWuuZPNL8ANy7jJm9rrScAAAAASUVORK5CYII=\" data-mlang=\"latex\" data-equation=\"%5Cfrac%7BCD%7D%7BBE%7D\" /></p>','','','D, C, A','4_26_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('64','3','25','<p>Perhatikan gambar di bawah ini!</p>\r\n<p>Pada gambar di atas segitiga ABC kongruen dengan segitiga DEF.</p>\r\n<p>Berdasarkan gambar tersebut, pasangkanlah pernyataan berikut dengan pilihan jawaban yang tepat . .</p>','5',NULL,'<p>5</p>','<p>6</p>','<p>7</p>','<p>8</p>','','<p>Panjang DE</p>','<p>Panjang EF</p>','<p>Panjang DF</p>','','','B, C, A','3_25_1.jpg','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('65','4','27','<p>Data bulan November 2022 jumlah wisatawan yang masuk ke Indonesia adalah 1.000.000 orang. Mereka melewati jalur udara, laut dan darat, seperti pada diagram lingkaran di bawah ini.</p>\r\n<p><strong>(Lihat Lampiran Gambar)</strong><br />Pasangkankah pernyataan dan jawaban yang tersedia di bawah ini:</p>','5',NULL,'<p>134.400</p>','<p>72.800</p>','<p>61.600</p>','<p>33.600</p>','','<p>Jumlah wisatawan masuk dari bandara Minang Kabau</p>','<p>Jumlah wisatawan masuk dari bandara Soekarno Hatta</p>','<p>Selisih banyaknya wisatawan masuk dari bandara Soekarno Hatta dengan Ngurah Rai</p>','','','D, A, B','4_27_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('66','3','26','<p>Perhatikan gambar berikut!<br /><br />Diketahui segitiga sama kaki ABC dengan sisi AB sebagai alas. AD dan BE adalah garis tinggi yang tegak lurus sisi BC dan AC, serta perpotongan di titik P, maka jodohkanlah pasangan segitiga yang kongruen &hellip;</p>','5',NULL,'<p>Segitiga BDA</p>','<p>Segitiga BCE</p>','<p>Segitiga BDP</p>','<p>Segitiga APB</p>','','<p>Segitiga AEP</p>','<p>Segitiga AEB</p>','<p>Segitiga ACD</p>','','','C, A, B','3_26_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('67','4','28','<p><strong>OJEK ONLINE</strong><br />Bayu sering menggunakan layanan ojek online. Bayu selalu membandingkan biaya layanan dari berbagai ojek online untuk mencari harga yang paling murah. Terdapat tiga perusahaan yaitu GOGO, GAGA dan GUGU yang sering digunakan Bayu. </p>','5',NULL,'<p>GOGO</p>','<p>GAGA</p>','<p>GUGU</p>','','','<p>Jika Bayu ingin mengeluarkan biaya paling murah, ojek online manakah yang harus dipilih ketika ia berangkat dari rumahnya ke sekolah sejauh 5 km</p>','<p>Jika Bayu ingin mengeluarkan biaya paling murah, ojek online manakah yang harus dipilih ketika ia pergi ke rumah neneknya sejauh 20 km</p>','','','','B, A','4_28_1.png','','','','','','',NULL,'0','2');
INSERT INTO `soal` VALUES ('68','3','27','<p>Pasangkan pernyataan berikut dengan benar!</p>','5',NULL,'<p><sup>3</sup>/<sub>2</sub></p>','<p>-<sup>2</sup>/<sub>3</sub></p>\r\n<p>&nbsp;</p>','<p><sup>2</sup>/<sub>3</sub></p>','<p>-<sup>3</sup>/<sub>2</sub></p>','','<p>Gradien garis m</p>\r\n<p><em>(gambar 1)</em></p>','<p>Gradien garis tersebut</p>\r\n<p><em>(gambar 2)</em></p>','<p>Gradien garis yang melalui titik pusat dan titik ( 3, -2)</p>','<p>Gradien persamaan 6x + 4y = -12</p>','','C, A, B, D','3_27_1.jpg','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('69','4','29','<p>Perhatikan gambar di bawah ini!<br />&nbsp;<br />Sebuah cangkir berbentuk tabung dengan diameter 7 cm dan tinggi 10 cm, diisi penuh dengan minuman kopi panas, setelah 2 jam permukaan kopi turun 0,5 cm.&nbsp;</p>\r\n<p>Pasangkanlah pernyataan yang sesuai dibawah ini!</p>','5',NULL,'<p>19,25 cm<sup>3</sup></p>','<p>182,875 cm<sup>3</sup></p>','<p>192,5 cm<sup>3</sup></p>','<p>365,75 cm<sup>3</sup></p>','','<p>Volume kopi mula-mula</p>','<p>Volume kopi setelah 2 jam</p>','<p>Volume kopi yang menguap setelah 2 jam</p>','<p>Volume setengah cangkir</p>','','E, D, A, C','4_29_1.jpg','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('70','3','28','<p>Perhatikan gambar terlampir!</p>\r\n<p>Sebuah taman berbentuk jajargenjang dengan Panjang alas 20m dan tinggi 12m. Taman tersebut akan ditutupi dengan rumput jepang dengan harga Rp 7.500 per . Selain itu di sekeliling taman akan dipasang lampu dengan jarak antar tiang lampu 3 m.</p>\r\n<p>Pasangkanlah pernyataan berikut dengan jawaban yang sesuai!</p>','5',NULL,'<p>22</p>','<p>45</p>','<p>66</p>','<p>240</p>','<p>1.800.000</p>','<p>Luas taman = &hellip; m<sup>2</sup></p>','<p>Total biaya rumput taman Rp &hellip;</p>','<p>Keliling taman = &hellip; m</p>','<p>Banyak tiang lampu yang dibutuhkan</p>','','D, E, C, A','3_28_1.png','','','','','','',NULL,'0','4');
INSERT INTO `soal` VALUES ('71','4','30','<p>Diagram batang di bawah ini menunjukkan data banyak anak pada tiap-tiap keluarga di lingkungan RT 5 RW 1 Kelurahan Sukajadi. Sumbu horizontal menunjukkan data banyak anak pada tiap-tiap keluarga, sedangkan sumbu vertikal menyatakan banyak keluarga yang memiliki anak dengan jumlah antara 0 sampai dengan 5.</p>\r\n<p><strong>(LIHAT LAMPIRAN GAMBAR)</strong></p>\r\n<p>Pasangkan pernyataan-pernyataan berikut dengan jawaban yang benar.</p>','5',NULL,'<p>2</p>','<p>5</p>','<p>15</p>','<p>24</p>','','<p>Jumlah keluarga yang mempunyai anak kurang dari 3</p>','<p>Jumlah keluarga yang mempunyai anak lebih dari 2</p>','<p>Rata-rata banyak anak tiap keluarga</p>','','','D, C, B','4_30_1.png','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('72','3','29','<p>Diketahui himpunan A = {2, 3, 4, 5} dan himpunan B = {3, 4, 5, 6, 8, 10, 12}. Jika ditentukan himpunan pasangan berurutan {(2, 4), (3, 6), (4, 8), (5, 10)}<br />Pasangkanlah pernyataan berikut dengan jawaban yang sesuai!</p>','5',NULL,'<p>Dua kali dari</p>','<p>{2, 3, 4, 5}</p>','<p>{3, 4, 5, 6, 8, 10, 12}</p>','<p>{4, 6, 8, 10}</p>','','<p>Relasi dari himpunan A ke himpunan B</p>','<p>Domain dari relasi diatas</p>','<p>Range dari relasi di atas</p>','','','E, B, D','','','','','','','',NULL,'0','3');
INSERT INTO `soal` VALUES ('73','3','30','<p>Data siswa dari Kelas A dan B&nbsp; memiliki&nbsp; jumlah siswa masing-masing 10 anak.&nbsp;&nbsp;&nbsp;</p>\r\n<p>Diketahui nilai rata-rata ulangan matematika kelas A dan rata-rata kelas B seperti pada diagram batang. Jika nilai satu siswa kelas A dan nilai satu siswa kelas B ditukarkan, maka nilai rata-rata kelas A dan kelas B&nbsp; <strong><em>menjadi sama</em></strong>. Pasangkan&nbsp; pernyataan di kolom kiri di bawah ini dengan&nbsp; tepat pada jawaban di kolom kanan !</p>','5',NULL,'<p>90</p>','<p>89</p>','<p>80</p>','<p>79</p>','','<p>Jika nilai satu siswa kelas B yang ditukar adalah 79, maka nilai satu siswa kelas A yang dituka adalah &hellip; .</p>','<p>Nilai rata-rata kelas A dan kelas B setelah nilai satu anak <br />dipertukarkan adalah &hellip; </p>','','','','B, D','3_30_1.png','','','','','','',NULL,'0','2');

/*---------------------------------------------------------------
  TABLE: `temp_file`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_file`;
CREATE TABLE `temp_file` (
  `id_file` int NOT NULL AUTO_INCREMENT,
  `id_bank` int DEFAULT '0',
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

/*---------------------------------------------------------------
  TABLE: `temp_pil`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_pil`;
CREATE TABLE `temp_pil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idbank` int NOT NULL,
  `nomor` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*---------------------------------------------------------------
  TABLE: `temp_soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_soal`;
CREATE TABLE `temp_soal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_bank` int NOT NULL,
  `nomor` int NOT NULL,
  `idfile` int NOT NULL,
  `file` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*---------------------------------------------------------------
  TABLE: `token`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `id_token` int NOT NULL AUTO_INCREMENT,
  `token` varchar(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `masa_berlaku` time NOT NULL,
  PRIMARY KEY (`id_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `token` VALUES   ('1','VKUNXL','2024-05-17 22:44:21','00:15:00');

/*---------------------------------------------------------------
  TABLE: `ujian`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ujian`;
CREATE TABLE `ujian` (
  `id_ujian` int NOT NULL AUTO_INCREMENT,
  `kode_nama` varchar(255) DEFAULT NULL,
  `id_bank` int DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jml_soal` int NOT NULL DEFAULT '0',
  `jml_esai` int NOT NULL DEFAULT '0',
  `jml_multi` int NOT NULL DEFAULT '0',
  `jml_bs` int NOT NULL DEFAULT '0',
  `jml_urut` int NOT NULL DEFAULT '0',
  `tampil_bs` int NOT NULL DEFAULT '0',
  `tampil_urut` int NOT NULL DEFAULT '0',
  `tampil_pg` int NOT NULL DEFAULT '0',
  `tampil_esai` int NOT NULL DEFAULT '0',
  `tampil_multi` int NOT NULL DEFAULT '0',
  `lama_ujian` int NOT NULL DEFAULT '0',
  `tgl_ujian` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `waktu_ujian` time DEFAULT NULL,
  `selesai_ujian` time DEFAULT NULL,
  `level` varchar(5) DEFAULT NULL,
  `kelas` text,
  `opsi` int DEFAULT '4',
  `sesi` varchar(1) DEFAULT NULL,
  `acak` int DEFAULT '1',
  `token` int DEFAULT '0',
  `status` int DEFAULT NULL,
  `hasil` int DEFAULT '0',
  `kkm` varchar(128) DEFAULT NULL,
  `ulang` int DEFAULT '0',
  `soal_agama` varchar(50) DEFAULT NULL,
  `reset` int DEFAULT '0',
  `pelanggaran` int DEFAULT '1',
  `btnselesai` int DEFAULT '0',
  PRIMARY KEY (`id_ujian`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `users`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(25) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `mapel` varchar(20) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `nowa` varchar(13) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `sts` int NOT NULL DEFAULT '0',
  `sts_kode` int NOT NULL DEFAULT '0',
  `sts_jari` int NOT NULL DEFAULT '0',
  `idjari` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
INSERT INTO `users` VALUES   ('1','-','ADMIN','-','admin','$2y$10$MI.UkP3igafD5bXyKWXAXOHLUYOV03F99wopo9CtYh4sTEu57ZYIC','admin','081380774602',NULL,'0','0','0','');
INSERT INTO `users` VALUES ('2','001','GURU MTK','MTK','guru_mtk','guru_mtk','guru',NULL,NULL,'0','0','0',NULL);
INSERT INTO `users` VALUES ('3','002','GURU BIN','BINDO','guru_bin','guru_bin','guru',NULL,NULL,'0','0','0',NULL);
INSERT INTO `users` VALUES ('4','003','GURU PJOK (Yusron)','PJOK','guru_pjok','guru_pjok','guru',NULL,NULL,'0','0','0',NULL);
