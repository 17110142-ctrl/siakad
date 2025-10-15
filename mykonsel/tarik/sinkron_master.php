<?php
ini_set('max_execution_time', 600); 
require "../../config/koneksi.php";
require "../../config/function.php";
require "../../config/crud.php";
$waktusinkron = date('D, d-m-Y H:i:s');
if ($koneksi) {
    $token = $_POST['tokenapi'];
	$npsn = $_POST['npsn'];
    $data[]='langgar';
	$data[]='surat';
	
    if ($token <> '' and $data <> '') {
        foreach ($data as $data) {
            $masuk = $masuk2 = 0;
            if ($data == 'langgar') {
                $datax = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);
                $r = json_decode($datax, TRUE);
                if ($r <> null) {
                  $sql = mysqli_query($koneksi, "truncate table bk_siswa");
				 
                    $i = 1;
                    foreach ($r['langgar'] as $r) {
                        $sql = mysqli_query($koneksi, "insert into bk_siswa
                            (nis,kelas,tanggal,idkat,idsub,idpel,idpres,tapel,ket,poin,sts) values 			
                            ('$r[nis]','$r[kelas]','$r[tanggal]','$r[idkat]','$r[idsub]','$r[idpel]','$r[idpres]','$r[tapel]','$r[ket]','$r[poin]','$r[sts]')");			
                            $masuk++;                    
                    }
				$exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk',tanggal='$waktusinkron' where kode='PELANGGARAN'");
              }
			
            }
			if ($data == 'surat') {
                $syncdata = http_request($setting['server'] . "/sinkron/sinmaster.php?token=" . $token);

                $sync = json_decode($syncdata, TRUE);

                if ($sync <> null) {
                
                    $sql = mysqli_query($koneksi, "truncate table bk_surat");
                    foreach ($sync['surat'] as $pel) {
                      
                        $sqlpel = mysqli_query($koneksi, "insert into bk_surat
                                (nosurat,nis,tanggal,sanksi,tapel,sts,idsp) values 			
                                ('$pel[nosurat]','$pel[nis]','$pel[tanggal]','$pel[sanksi]','$pel[tapel]','$pel[sts]','$pel[idsp]')");
                        
                            $masuk2++;
                        
					}
                 
                   $exec = mysqli_query($koneksi, "update sinkron set jumlah='$masuk2',tanggal='$waktusinkron' where kode='SURAT PERINGATAN'");
              }
			}
			
		
		}
	}
}