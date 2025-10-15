
/*---------------------------------------------------------------
  SQL DB BACKUP 10.05.2024 14:41 
  HOST: localhost
  DATABASE: sas
  TABLES: *
  ---------------------------------------------------------------*/

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
  PRIMARY KEY (`id_aplikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `aplikasi` VALUES   ('1','Server Sekolah','SMP Negeri 2 BANTUR','P01','REGULER','SMP','20517509','2','2023/2024','Desa Wonokerto 297','Wonokerto ','BANTUR','Malang','Jawa Timur','SUGENG GIYANTO, S.Pd., M.Pd.','19671220 200501 1 008','081380774602','','https://','0','-','Asia/Jakarta','http://localhost/multi','WKJ9vUTCuZwDvi','SANDIK','0','logo92.png','KARTU PESERTA UJIAN','MKKS JAWA TIMUR','pusat','PROKTOR',NULL,'stempel86.png','Penilaian Akhir Tahun');

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
  `kelas` text DEFAULT NULL,
  `status` varchar(2) DEFAULT NULL,
  `soal_agama` varchar(50) DEFAULT NULL,
  `model` int(11) DEFAULT 0,
  `groupsoal` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_bank`),
  UNIQUE KEY `kode` (`kode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `banksoal` VALUES   ('1','PPKn-9','5','PPKn','9','a:2:{i:0;s:3:\"9-A\";i:1;s:3:\"9-B\";}','1','','1','AKM');

/*---------------------------------------------------------------
  TABLE: `berita`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `berita`;
CREATE TABLE `berita` (
  `id_berita` int(10) NOT NULL AUTO_INCREMENT,
  `id_bank` int(10) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `bulan`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `bulan`;
CREATE TABLE `bulan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bln` varchar(5) CHARACTER SET utf8mb4 NOT NULL,
  `ket` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
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
  TABLE: `file_pendukung`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `file_pendukung`;
CREATE TABLE `file_pendukung` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT 0,
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
INSERT INTO `file_pendukung` VALUES   ('1','1','1_1_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('2','1','1_2_2.png',NULL);
INSERT INTO `file_pendukung` VALUES ('3','1','1_2_A.png',NULL);
INSERT INTO `file_pendukung` VALUES ('4','1','1_2_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('5','1','1_2_A.png',NULL);
INSERT INTO `file_pendukung` VALUES ('6','1','1_2_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('7','1','1_2_A.png',NULL);
INSERT INTO `file_pendukung` VALUES ('8','1','1_2_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('9','1','1_2_A.png',NULL);
INSERT INTO `file_pendukung` VALUES ('10','1','1_3_1.jpeg',NULL);
INSERT INTO `file_pendukung` VALUES ('11','1','6744.jpeg',NULL);
INSERT INTO `file_pendukung` VALUES ('12','1','7577.jpeg',NULL);
INSERT INTO `file_pendukung` VALUES ('13','1','8092.png',NULL);
INSERT INTO `file_pendukung` VALUES ('14','1','1_1_1.png',NULL);
INSERT INTO `file_pendukung` VALUES ('15','1','9579.jpeg',NULL);
INSERT INTO `file_pendukung` VALUES ('16','1','3278.jpeg',NULL);
INSERT INTO `file_pendukung` VALUES ('17','1','5224.png',NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `jenis` int(1) NOT NULL,
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
  `nilai_esai` int(5) NOT NULL DEFAULT 0,
  `ragu` int(1) NOT NULL DEFAULT 0,
  `status` int(11) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  `skor` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

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
  `id_sp` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) DEFAULT NULL,
  `jenjang` varchar(50) DEFAULT NULL,
  `ket` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_sp`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*---------------------------------------------------------------
  TABLE: `kelas`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `kelas` VALUES   ('1','9','9-A');
INSERT INTO `kelas` VALUES ('2','9','9-B');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `kunci_soal` VALUES   ('1','27','27.1','A','1');
INSERT INTO `kunci_soal` VALUES ('1','28','28.1','10','5');
INSERT INTO `kunci_soal` VALUES ('1','29','29.1','A','1');
INSERT INTO `kunci_soal` VALUES ('1','29','29.2','B','1');
INSERT INTO `kunci_soal` VALUES ('1','29','29.3','D','1');
INSERT INTO `kunci_soal` VALUES ('1','30','30.1','B','1');
INSERT INTO `kunci_soal` VALUES ('1','30','30.2','S','1');
INSERT INTO `kunci_soal` VALUES ('1','30','30.3','S','1');
INSERT INTO `kunci_soal` VALUES ('1','30','30.4','B','1');
INSERT INTO `kunci_soal` VALUES ('1','31','31.1','C','1');
INSERT INTO `kunci_soal` VALUES ('1','31','31.2','A','1');
INSERT INTO `kunci_soal` VALUES ('1','31','31.3','D','1');
INSERT INTO `kunci_soal` VALUES ('1','31','31.4','B','1');

/*---------------------------------------------------------------
  TABLE: `level`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `id_level` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
INSERT INTO `level` VALUES   ('9','9');

/*---------------------------------------------------------------
  TABLE: `log`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `text` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
INSERT INTO `log` VALUES   ('1','22225','logout','keluar','2024-04-22 20:27:02');
INSERT INTO `log` VALUES ('2','1','logout','keluar','2024-04-30 22:48:07');
INSERT INTO `log` VALUES ('3','121','logout','keluar','2024-04-30 23:30:50');
INSERT INTO `log` VALUES ('4','121','logout','keluar','2024-05-01 07:11:41');
INSERT INTO `log` VALUES ('5','121','logout','keluar','2024-05-01 13:24:21');
INSERT INTO `log` VALUES ('6','121','logout','keluar','2024-05-01 14:04:42');
INSERT INTO `log` VALUES ('7','121','logout','keluar','2024-05-01 16:55:43');
INSERT INTO `log` VALUES ('8','1','logout','keluar','2024-05-02 11:10:28');
INSERT INTO `log` VALUES ('9','2','logout','keluar','2024-05-02 11:53:38');
INSERT INTO `log` VALUES ('10','1','logout','keluar','2024-05-02 12:43:54');
INSERT INTO `log` VALUES ('11','3','logout','keluar','2024-05-03 09:02:09');
INSERT INTO `log` VALUES ('12','19084','logout','keluar','2024-05-03 09:15:30');
INSERT INTO `log` VALUES ('13','1','logout','keluar','2024-05-03 18:08:36');
INSERT INTO `log` VALUES ('14','1','logout','keluar','2024-05-07 22:51:30');

/*---------------------------------------------------------------
  TABLE: `mata_pelajaran`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `mata_pelajaran`;
CREATE TABLE `mata_pelajaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
INSERT INTO `mata_pelajaran` VALUES ('8','PJOK','Pendidikan Jasmani Olahraga dan Kesehatan');
INSERT INTO `mata_pelajaran` VALUES ('9','INFO','Informatika');
INSERT INTO `mata_pelajaran` VALUES ('10','PRK','Prakarya');
INSERT INTO `mata_pelajaran` VALUES ('11','BSUND','Bahasa Sunda');
INSERT INTO `mata_pelajaran` VALUES ('12','TIK','Tekhnologi Indormasi dan Komunikasi');

/*---------------------------------------------------------------
  TABLE: `nilai`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL AUTO_INCREMENT,
  `idn` int(11) DEFAULT NULL,
  `id_ujian` int(11) DEFAULT NULL,
  `id_bank` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `ujian_mulai` varchar(20) DEFAULT NULL,
  `ujian_berlangsung` varchar(20) DEFAULT NULL,
  `ujian_selesai` varchar(20) DEFAULT NULL,
  `jml_benar` int(10) DEFAULT NULL,
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
  `status` int(1) DEFAULT NULL,
  `ipaddress` varchar(20) DEFAULT NULL,
  `hasil` int(11) DEFAULT NULL,
  `jawaban` text DEFAULT NULL,
  `jawaban_esai` longtext DEFAULT NULL,
  `jawaban_multi` text DEFAULT NULL,
  `jawaban_bs` text DEFAULT NULL,
  `jawaban_urut` text DEFAULT NULL,
  `nilai_esai` int(11) DEFAULT NULL,
  `nilai_esai2` text DEFAULT NULL,
  `online` int(1) NOT NULL DEFAULT 0,
  `id_soal` longtext DEFAULT NULL,
  `id_opsi` longtext DEFAULT NULL,
  `id_esai` text DEFAULT NULL,
  `blok` int(1) NOT NULL DEFAULT 0,
  `server` varchar(50) DEFAULT NULL,
  `browser` int(11) DEFAULT 0,
  `jenis_browser` varchar(50) DEFAULT NULL,
  `jumjawab` varchar(11) DEFAULT NULL,
  `jumsoal` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
INSERT INTO `nilai` VALUES   ('5',NULL,'1','1','1',NULL,'PAT','2024-05-10 13:48:16','2024-05-10 13:48:16','2024-05-10 13:48:49','1','0','0','0','0','6','0','0','0','0','6',NULL,'::1','0','a:1:{i:27;s:1:\"A\";}','a:1:{i:28;s:11:\"Tidak Diisi\";}','a:1:{i:29;N;}','a:1:{i:30;N;}','a:1:{i:31;N;}',NULL,NULL,'0','27,28,29,30,31,',NULL,NULL,'0','SANDIK','1','Google Chrome','1','5');

/*---------------------------------------------------------------
  TABLE: `pk`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `pk`;
CREATE TABLE `pk` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `id_pk` varchar(100) NOT NULL,
  `pk` varchar(50) NOT NULL,
  `jurusan_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_pk`),
  UNIQUE KEY `no` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `reset` VALUES   ('1','0','1','1');

/*---------------------------------------------------------------
  TABLE: `ruang`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ruang`;
CREATE TABLE `ruang` (
  `kode_ruang` varchar(10) NOT NULL,
  `keterangan` varchar(30) NOT NULL,
  PRIMARY KEY (`kode_ruang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `ruang` VALUES   ('RUANG-1','RUANG-1');

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
INSERT INTO `sesi` VALUES ('4','4');

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
  `nama` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `jk` varchar(50) DEFAULT NULL,
  `npsn` varchar(50) DEFAULT NULL,
  `ruang` varchar(50) DEFAULT NULL,
  `sesi` int(11) DEFAULT 1,
  `online` int(11) NOT NULL DEFAULT 0,
  `foto` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
INSERT INTO `siswa` VALUES   ('1','US-101','sandik-1','sandik-1','123451','ABDUL MALIK','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('2','US-102','sandik-2','sandik-2','123452','ADYA DWI AGUSTIN','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('3','US-103','sandik-3','sandik-3','123453','AGENG RIZKI SURYONGALAM','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('4','US-104','sandik-4','sandik-4','123454','AHMAD ALDY RENALDI','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('5','US-105','sandik-5','sandik-5','123455','ALVITO SIGID SYAWALUDIN','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('6','US-106','sandik-6','sandik-6','123456','AMANDA AMEYLIA PUTRI','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('7','US-107','sandik-7','sandik-7','123457','DANI ZIDAN AFRYAN MAULANA','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('8','US-108','sandik-8','sandik-8','123458','DIMAS PRATAMA','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('9','US-109','sandik-9','sandik-9','123459','EKA WULANDARI','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('10','US-110','sandik-10','sandik-10','123460','ELGAVIA NANDA AGUSTIEN','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('11','US-111','sandik-11','sandik-11','123461','ENDAH SIH ANDARBENI','9','9-A','Kristen','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('12','US-112','sandik-12','sandik-12','123462','FICKY ARYA ANGGARA','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('13','US-113','sandik-13','sandik-13','123463','GALIH PUTRO PRAKOSO','9','9-A','Kristen','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('14','US-114','sandik-14','sandik-14','123464','GILBRAN JIWANDONO','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('15','US-115','sandik-15','sandik-15','123465','JERI BAHTIAR SUWARDANA','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('16','US-116','sandik-16','sandik-16','123466','LAURA CHELSIA OLIVIA','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('17','US-117','sandik-17','sandik-17','123467','M. ROSY ADIANSYAH','9','9-A','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('18','US-118','sandik-18','sandik-18','123468','MA\'RIFATUS ZAHRA','9','9-A','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('19','US-119','sandik-19','sandik-19','123469','Meylisa Regina Anggrainy','9','9-A','Katholik','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('20','US-120','sandik-20','sandik-20','123470','NINDI ALIF CITRA LESTARI','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('21','US-121','sandik-21','sandik-21','123471','PURI CALISTHA SOFHIE MUTIARA','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('22','US-122','sandik-22','sandik-22','123472','RENITA CAHYANI','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('23','US-123','sandik-23','sandik-23','123473','SHEVA CANNAVARO','9','9-B','Kristen','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('24','US-124','sandik-24','sandik-24','123474','SHYKRANA IGATYA','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('25','US-125','sandik-25','sandik-25','123475','SIH WILUJENG KANTHI RAHAYU','9','9-B','Kristen','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('26','US-126','sandik-26','sandik-26','123476','SISWI JAYANTI EDI PENI','9','9-B','Kristen','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('27','US-127','sandik-27','sandik-27','123477','VELISA SUCI RAHMAWATI','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('28','US-128','sandik-28','sandik-28','123478','VICKY INDRA IRAWAN','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('29','US-129','sandik-29','sandik-29','123479','YEHEZKIEL ANINDYA YODHA','9','9-B','Kristen','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('30','US-130','sandik-30','sandik-30','123480','AFRIZAL TINO FERDIANSYAH','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('31','US-131','sandik-31','sandik-31','123481','AHMAD RAHMADANI FERDIANSYAH','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('32','US-132','sandik-32','sandik-32','123482','Aira Salsabila','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('33','US-133','sandik-33','sandik-33','123483','ALISA AUREFA','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('34','US-134','sandik-34','sandik-34','123484','ANIS AGUSTINA','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('35','US-135','sandik-35','sandik-35','123485','ARYO SENO MULYADI','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('36','US-136','sandik-36','sandik-36','123486','BAGAS ROBBY SAMUDRA','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('37','US-137','sandik-37','sandik-37','123487','CICILIA NURFAIZZAH','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('38','US-138','sandik-38','sandik-38','123488','DENI MUJI LESTARI','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('39','US-139','sandik-39','sandik-39','123489','ELAINE KHALIFA PUTRI','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('40','US-140','sandik-40','sandik-40','123490','FERDIAN ALGA SAPUTRA','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('41','US-141','sandik-41','sandik-41','123491','FERDY VIMA JOHAN','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('42','US-142','sandik-42','sandik-42','123492','Gilang Arya Pratama','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('43','US-143','sandik-43','sandik-43','123493','JOKO KURNIAWAN','9','9-B','Islam','Laki-laki','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('44','US-144','sandik-44','sandik-44','123494','KAYLA SEFIA PUTRI','9','9-B','Islam','Perempuan','20517509','RUANG-1','1','0','');
INSERT INTO `siswa` VALUES ('45','US-145','sandik-45','sandik-45','123495','LAURA BABY ELOY','9','9-B','Kristen','Perempuan','20517509','RUANG-1','1','0','');

/*---------------------------------------------------------------
  TABLE: `soal`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `soal`;
CREATE TABLE `soal` (
  `id_soal` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT NULL,
  `nomor` int(5) DEFAULT NULL,
  `soal` longtext DEFAULT NULL,
  `jenis` int(1) DEFAULT NULL,
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
  PRIMARY KEY (`id_soal`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
INSERT INTO `soal` VALUES   ('27','1','1','SPEKTRUM GELOMBANG ELEKTROMAGNETIK\nKenny sedang melihat artikel mengenai Sains dan menemukan gambar sebagai berikutPada gambar, disajikan berbagai macam gelombang elektromagnetik yang disusun berdasarkan frekuensinya dalam satuan Hz.\nWarna yang memiliki frekuensi lebih tinggi daripada warna hijau, tetapi lebih rendah daripada warna ungu adalah .... ','1',NULL,'biru','jingga','merah','kuning','','','','','','','A','9579.jpeg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1');
INSERT INTO `soal` VALUES ('28','1','2','Ayah Lisa adalah seorang petani. Selain menyikapi dampak negatif datangnya musim penghujan, ayah Lisa juga memanfaatkan dampak positif musim penghujan untuk kelangsungan pertaniannya. Menurut ayah Lisa, La Nina memberikan banyak dampak positif pada sektor pertanian. Kemudian, Lisa mencari tahu apa saja dampak positif dari La Nina.  \nDAMPAK POSITIF LA NINA\nDekan Sekolah Vokasi UGM Agus Maryono yang juga merupakan pakar Ekohidrolik dan pelopor restorasi sungai Indonesia mengatakan bahwa seharusnya tahun basah (musim penghujan) bisa dimanfaatkan. Daerah kering dan semi kering juga dapat memanfaatkan air yang berlimpah. Dengan adanya tahun basah, air tanah bisa terisi secara maksimal, begitu pula dengan danau, situ, serta telaga. Alur sungai pun dapat terbentuk dengan sempurna. Masyarakat di sekitar sungai dapat melakukan susur sungai sehingga mereka akan mengetahui sungai yang bisa digunakan untuk mitigasi serta sungai yang memiliki potensi wisata, potensi sumber air, dan potensi perikanan.\nSelain itu, Rizaldi Boer dari Pusat Pengelolaan Risiko dan Peluang Iklim Institut Pertanian Bogor (IPB) mengatakan, La Nina juga mempunyai manfaat bagi pertanian pangan. La Nina memberi peluang untuk percepatan tanam serta perluasan area tanam padi, baik di lahan sawah irigasi, tadah hujan, maupun ladang. Lebih lanjut, La Nina dapat dimanfaatkan untuk meningkatkan areal tanam pada musim hujan, khususnya untuk daerah lahan kering. Petani disarankan untuk memanfaatkan mundurnya akhir musim hujan dengan menanam tanaman umur pendek dan berekonomi tinggi. Tak hanya itu, petani juga dapat melakukan adaptasi teknik budidaya pada daerah endemik banjir dan pertanian lahan kering di lahan gambut.\nDampak positif La Nina yang lain adalah dapat meningkatkan produksi perluasan lahan pasang surut. Lahan pesisir juga akan berkembang lebih baik karena salinitas dapat dikurangi dan perikanan darat bisa dikembangkan lebih awal. Dari segi sumber daya air, menurut Direktur Bina Teknik SDA Kementerian PU-Pera Eko Winar Irianto, kondisi La Nina dapat memenuhi kapasitas energi maksimum pada operasional waduk, sementara dalam kondisi El Nino energi yang dihasilkan akan berkurang.\nBagaimana dampak positif La Nina dari segi sumber daya air?','2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'10',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','5');
INSERT INTO `soal` VALUES ('29','1','3','Kita sering kali melakukan olahraga. Bahkan, orang-orang di sekitar kita juga sering menyarankan kita untuk melakukan aktivitas tersebut. Rupanya, ada keterkaitan antara berolahraga dengan kesehatan fisik dan mental, misalnya terkait dengan perkembangan tubuh dan interaksi sosial.\n     Perlu diketahui bahwa olahraga bermanfaat dalam mencegah risiko berbagai penyakit. Saat tubuh jarang melakukan olahraga, lemak akan menumpuk di dalam tubuh sehingga dapat berujung pada terjadinya obesitas. Namun, dengan berolahraga secara teratur, tumpukan lemak yang ada di dalam tubuh bisa terbakar. Selain itu, saat berolahraga, terjadi kontraksi otot-otot tubuh yang menyebabkan cairan getah bening dapat mengalir dengan lancar. Cairan getah bening merupakan cairan yang mengandung sel-sel darah putih yang berkaitan dengan sistem pertahanan tubuh. Berbeda dengan pembuluh darah, cairan getah bening ini tidak mengalir karena kontraksi jantung, tetapi karena kontraksi otot-otot yang melekat pada rangka tubuh kita.\n     Selain manfaat tersebut, olahraga juga dapat meningkatkan perkembangan tubuh. Aktivitas yang dilakukan selama olahraga akan membantu tubuh untuk lebih cepat berkembang. Ketika berolahraga, terjadi kontraksi otot-otot yang menyebabkan otot lebih terlatih dan akan berkembang dengan baik. Selain itu, aktivitas olahraga yang diiringi gizi seimbang juga dapat membuat metabolisme tubuh menjadi lebih lancar karena hormon pertumbuhan bekerja lebih maksimal.\n     Selain bermanfaat bagi kesehatan fisik, olahraga juga dapat meningkatkan interaksi sosial. Ketika olahraga dilakukan dalam kelompok, misalnya saat bermain sepak bola, basket, dan futsal, terjadi proses perkenalan dengan orang lain, baik dengan orang di dalam tim maupun di luar tim. Selain itu, terjadi proses saling bekerja sama saat bermain atau bertanding. Adanya kompetisi yang sehat dalam permainan olahraga tersebut juga membuat kita menjadi lebih jujur. Akhirnya, kita menjadi terbiasa dalam melakukan \nSelain berolahraga, hal yang perlu kita lakukan untuk menjaga kesehatan fisik dan mental adalah ....  ','3',NULL,'Mengurangi makan makanan penyebab obesitas','Meningkatkan interaksi sosial dan sikap saling bekerja sama','Mengurangi makanan berprotein dan berlemak tinggi','Memakan makanan bergizi seimbang','','','','','','','A, B, D','3278.jpeg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','3');
INSERT INTO `soal` VALUES ('30','1','4','Mina padi adalah suatu bentuk usaha tani gabungan yang memanfaatkan genangan air sawah yang tengah ditanami padi sebagai kolam untuk budidaya ikan. Oleh karena itu, selain mendapat hasil panen yaitu padi, petani yang menerapkan sistem mina padi juga dapat memanen ikan. Pak Made adalah salah satu petani di Bali yang menerapkan sistem mina padi di sawahnya. Pak Made mengatakan bahwa dengan menerapkan sistem mina padi, pendapatan dari hasil panen beliau meningkat. “Akan tetapi, perawatan padi dan ikan pada sistem mina padi memang gampang-gampang susah”, katanya.\nBenih ikan yang ditebar oleh Pak Made di sawah beliau yang seluas 1,5 ha adalah ikan emas dan ikan nila yang masih berukuran 5 cm sampai dengan 8 cm dengan kepadatan 5.000 ekor/ha. Perbandingan benih ikan emas dengan benih ikan nila yang ditebar oleh Pak Made adalah 3 : 2. Harga bibit ikan nila adalah Rp500,00/ekor dan harga bibit ikan emas adalah dua kali lipatnya. Setiap pagi, Pak Made memberi pakan tambahan berupa dedak halus 250 kg/ha untuk ikan yang ada di sawahnya.\nSetelah tujuh puluh hari, Pak Made memanen ikannya tersebut. Total ikan yang dipanen adalah 6.500 kg/ha. Perbandingan hasil panen ikan emas dan ikan nila sama dengan perbandingan benih ikan ketika ditebar. Harga ikan emas dan ikan nila yang dipanen oleh Pak Made berturut-turut adalah Rp30.000,00/kg dan Rp27.000,00/kg. Sekitar 2 bulan kemudian, Pak Made memanen padinya dengan hasil panen 5,7 ton/ha. Pak Made menjualnya dalam bentuk gabah kering panen (GKP) dengan harga Rp5.000,00/kg.\nTentukan benar atau salah pernyataan berikut dengan memberi tanda ? pada kolom yang sesuai!\n','4',NULL,'• Total benih ikan emas yang ditebar di sawah Pak Made adalah 4.500 ekor','• Total benih ikan nila yang ditebar di sawah Pak Made adalah 2.000 ekor','• Total ikan emas yang dipanen Pak Made adalah 3.900 ekor','• Total ikan nila yang dipanen Pak Made adalah 3.900 ekor','','','','','','','B, S, S, B',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','4');
INSERT INTO `soal` VALUES ('31','1','5','Pemerintah melalui Badan Pusat Statistik telah merilis data produktivitas padi dari setiap provinsi di Indonesia. Data tersebut meliputi luas lahan persawahan yang dipanen dan produktivitas lahan panen. Adapun data jumlah produksi per tahun dapat diketahui dengan mengalikan luas lahan panen dan produktivitasnya. Angka produktivitas padi diperoleh melalui survei berupa Gabah Kering Panen (GKP) yang dikonversikan menjadi Gabah Kering Giling (GKG).\n    Pulau Jawa sebagai pulau dengan jumlah penduduk terbanyak masih memerlukan pasokan beras dari daerah lain maupun dari impor. Hal tersebut karena jumlah hasil panen belum dapat mencukupi kebutuhan pangan masyarakat. Berikut data jumlah produksi padi dari perwakilan provinsi di 5 pulau terbesar di Indonesia. \nTentukan urutan provinsi dari yang memiliki jumlah hasil panen tertinggi hingga terendah!','5',NULL,'92.198.050,77 kuintal','46.786.971,19 kuintal','20.763.612,87 kuintal','11.345.248,95 kuintal','','Sumatra Utara','Jawa Barat','Kalimantan Selatan','Sulawesi Selatan','','C, A, D, B','5224.png',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','4');

/*---------------------------------------------------------------
  TABLE: `temp_file`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_file`;
CREATE TABLE `temp_file` (
  `id_file` int(11) NOT NULL AUTO_INCREMENT,
  `id_bank` int(11) DEFAULT 0,
  `nama_file` varchar(50) DEFAULT NULL,
  `status_file` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*---------------------------------------------------------------
  TABLE: `temp_pil`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `temp_pil`;
CREATE TABLE `temp_pil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idbank` int(11) NOT NULL,
  `nomor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
INSERT INTO `token` VALUES   ('1','BZGFYB','2024-02-16 19:42:06','00:15:00');

/*---------------------------------------------------------------
  TABLE: `ujian`
  ---------------------------------------------------------------*/
DROP TABLE IF EXISTS `ujian`;
CREATE TABLE `ujian` (
  `id_ujian` int(5) NOT NULL AUTO_INCREMENT,
  `kode_nama` varchar(255) DEFAULT NULL,
  `id_bank` int(5) DEFAULT NULL,
  `kode_ujian` varchar(30) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jml_soal` int(5) NOT NULL DEFAULT 0,
  `jml_esai` int(5) NOT NULL DEFAULT 0,
  `jml_multi` int(11) NOT NULL DEFAULT 0,
  `jml_bs` int(11) NOT NULL DEFAULT 0,
  `jml_urut` int(11) NOT NULL DEFAULT 0,
  `tampil_bs` int(11) NOT NULL DEFAULT 0,
  `tampil_urut` int(11) NOT NULL DEFAULT 0,
  `tampil_pg` int(5) NOT NULL DEFAULT 0,
  `tampil_esai` int(5) NOT NULL DEFAULT 0,
  `tampil_multi` int(11) NOT NULL DEFAULT 0,
  `lama_ujian` int(5) NOT NULL DEFAULT 0,
  `tgl_ujian` datetime DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `waktu_ujian` time DEFAULT NULL,
  `selesai_ujian` time DEFAULT NULL,
  `level` varchar(5) DEFAULT NULL,
  `kelas` text DEFAULT NULL,
  `opsi` int(11) DEFAULT 4,
  `sesi` varchar(1) DEFAULT NULL,
  `acak` int(1) DEFAULT 1,
  `token` int(1) DEFAULT 0,
  `status` int(1) DEFAULT NULL,
  `hasil` int(1) DEFAULT 0,
  `kkm` varchar(128) DEFAULT NULL,
  `ulang` int(2) DEFAULT 0,
  `soal_agama` varchar(50) DEFAULT NULL,
  `reset` int(1) DEFAULT 0,
  `pelanggaran` int(11) DEFAULT 1,
  `btnselesai` int(11) DEFAULT 0,
  PRIMARY KEY (`id_ujian`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
INSERT INTO `ujian` VALUES   ('2','PPKn-9','1','PAT','PPKn','1','1','1','1','1','1','1','1','1','1','90','2024-05-10 14:21:00','2024-05-10 19:00:00','14:21:00',NULL,'9','a:2:{i:0;s:3:\"9-A\";i:1;s:3:\"9-B\";}','4','1','1','0','1','0',NULL,'0','','0','1','0');

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
  `sts_kode` int(11) NOT NULL DEFAULT 0,
  `sts_jari` int(11) NOT NULL DEFAULT 0,
  `idjari` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
INSERT INTO `users` VALUES   ('1','-','EDI SUKARNA','-','admin','$2y$10$Q4ygEygrlSZpgjmUezEAqeH4oV653VHglhB0pgnWruwx6/hbeEiem','admin','081380774602','ypi rembang.png','0','0','0','');
INSERT INTO `users` VALUES ('5','124','Cikadu','9-A','1','1','guru',NULL,NULL,'0','0','0',NULL);
