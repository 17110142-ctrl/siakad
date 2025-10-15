<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

            $data = [
			'tingkat'=>$_POST['level'],
			  'jurusan'=>$_POST['jurusan'],
			  'kode'=>$_POST['kodemap'],
			  'namamapel' => $_POST['mapel'],
                'urut' => $_POST['urut'],
                'kelompok' => $_POST['kelompok']   
            ];
			
		$ketika = [
			
			  'jurusan'=>$_POST['jurusan'],
			  'kode'=>$_POST['kodemap']
                  
            ];	
			
	$cekmapel = rowcount($koneksi, 'mapel_ijazah', $ketika);
    if ($cekmapel > 0) {
        echo "Kode sudah ada";
    } else {
            insert($koneksi, 'mapel_ijazah', $data);
            echo 'OK';
	}
        

