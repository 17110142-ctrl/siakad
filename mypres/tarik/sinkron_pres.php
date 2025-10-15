<?php
ini_set('max_execution_time', 600); 
require "../../config/koneksi.php";
require "../../config/function.php";
require "../../config/crud.php";
$waktusinkron = date('D, d-m-Y H:i:s');
if ($koneksi) {
    $token = $_POST['tokenapi'];
	$npsn = $_POST['npsn'];
    $data[]='register';
	$data[]='absensi';
    if ($token <> '' and $data <> '') {
        foreach ($data as $data) {
            $masuk = $masuk2 = 0;
            if ($data == 'register') {
                $datax = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);
                $r = json_decode($datax, TRUE);
                if ($r <> null) {
                  $sql = mysqli_query($koneksi, "truncate table datareg");
				  
                    
                    foreach ($r['register'] as $r) {
                        $sql = mysqli_query($koneksi, "insert into datareg
                            (nokartu,idsiswa,idpeg,level,nama) values 			
                            ('$r[nokartu]','$r[idsiswa]','$r[idpeg]','$r[level]','" . addslashes($r['nama']) . "')");

                            $masuk++;
                        
                    }
				$exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk',tanggal='$waktusinkron' where kode='DATAREG'");
              }
			
            }
			if ($data == 'absensi') {
                $syncdata = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);

                $sync = json_decode($syncdata, TRUE);

                if ($sync <> null) {
                
                     $sql = mysqli_query($koneksi, "truncate table absensi");
                    foreach ($sync['absensi'] as $a) {
                      
                        $sqlpel = mysqli_query($koneksi, "insert into absensi
                                (nokartu,tanggal,idsiswa,kelas,idpeg,level,masuk,pulang,ket,bulan,tahun,keterangan,mesin) values 			
                                ('$a[nokartu]','$a[tanggal]','$a[idsiswa]','$a[kelas]','$a[idpeg]','$a[level]','$a[masuk]','$a[pulang]','$a[ket]','$a[bulan]','$a[tahun]','$a[keterangan]','$a[mesin]')");
                        
                            $masuk2++;
                        
					}
                 
                   $exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk2',tanggal='$waktusinkron' where kode='ABSENSI'");
              }
			}
			
			
		}
	}
}