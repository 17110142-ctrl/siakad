<?php
ini_set('max_execution_time', 600); 
require "../../config/koneksi.php";
require "../../config/function.php";
require "../../config/crud.php";
$waktusinkron = date('D, d-m-Y H:i:s');
if ($koneksi) {
    $token = $_POST['tokenapi'];
	$npsn = $_POST['npsn'];
    $data[]='siswa';
	$data[]='pel';
	$data[]='pegawai';
    if ($token <> '' and $data <> '') {
        foreach ($data as $data) {
            $masuk = $masuk2 = $masuk3 = 0;
            if ($data == 'siswa') {
                $datax = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);
                $r = json_decode($datax, TRUE);
                if ($r <> null) {
                  $sql = mysqli_query($koneksi, "truncate table siswa");
				  $sql = mysqli_query($koneksi, "truncate table kelas");
                    $i = 1;
                    foreach ($r['siswa'] as $r) {
                        $sql = mysqli_query($koneksi, "insert into siswa
                            (id_siswa,kelas,jurusan,nisn,nis,level,fase,no_peserta,nama,agama,username,password,jk,server,sts,nowa) values 			
                            ('$r[id_siswa]','$r[kelas]','$r[jurusan]','$r[nisn]','$r[nis]','$r[level]','$r[fase]','$r[no_peserta]','" . addslashes($r['nama']) . "','$r[agama]','$r[username]','$r[password]','$r[jk]','$r[server]','$r[sts]','$r[nowa]')");

                        $qkelas = mysqli_query($koneksi, "SELECT kelas FROM kelas WHERE kelas='$r[kelas]'");
                        $cekkelas = mysqli_num_rows($qkelas);
                        if (!$cekkelas <> 0) {
                            $exec = mysqli_query($koneksi, "INSERT INTO kelas (kelas,pk,level)VALUES('$r[kelas]','$r[jurusan]','$r[level]')");
                        }
						
                            $masuk++;
                        
                    }
				$exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk',tanggal='$waktusinkron' where kode='SISWA'");
              }
			
            }
			if ($data == 'pel') {
                $syncdata = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);

                $sync = json_decode($syncdata, TRUE);

                if ($sync <> null) {
                
                    $sql = mysqli_query($koneksi, "truncate table mata_pelajaran");
                    foreach ($sync['pel'] as $pel) {
                      
                        $sqlpel = mysqli_query($koneksi, "insert into mata_pelajaran
                                (id,kode,nama_mapel) values 			
                                ('$pel[id]','$pel[kode]','$pel[nama_mapel]')");
                        
                            $masuk2++;
                        
					}
                 
                   $exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk2',tanggal='$waktusinkron' where kode='MAPEL'");
              }
			}
			
			if ($data == 'pegawai') {
                $syncdatax = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);

                $peg = json_decode($syncdatax, TRUE);

                if ($peg <> null) {
                
                    $sql = mysqli_query($koneksi, "truncate table users");
                    foreach ($peg['pegawai'] as $p) {
                      
                        $sqlpel = mysqli_query($koneksi, "insert into users
                                (id_user,username,password,nip,nama,level,sts,jenis,nowa,walas) values 			
                                ('$[id_user]','$p[username]','$p[password]','$p[nip]','$p[nama]','$p[level]','$p[sts]','$p[jenis]','$p[nowa]','$p[level]')");
                        
                            $masuk3++;
                        
					}
                 
                   $exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk3',tanggal='$waktusinkron' where kode='USER'");
              }
			}
		}
	}
}