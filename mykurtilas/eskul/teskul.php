<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	
	 $cekuser = rowcount($koneksi, 'peskul', ['nis' => $_POST['nis'],'eskul'=>$_POST['eskul']]);
    if ($cekuser > 0) {
        echo "gagal";
    } else {
  
     $data = [
	    'nis'     => $_POST['nis'],
        'eskul'     => $_POST['eskul'],
        'guru'   => $_POST['guru'],
		 'kelas'   => $_POST['kelas'],
		 'semester'=>$semester,
		 'tp'=>$tapel
		];
			$exec = insert($koneksi, 'peskul', $data);
			echo "OK";                   		
	  }
        
}
  if ($pg == 'nilai') {
	 $nis = $_POST['nis'];
    $eskul = $_POST['eskul'];
	$nilai = $_POST['nilai'];
	$ket = $_POST['ket'];
	
	$exec = mysqli_query($koneksi,"UPDATE peskul SET nilai='$nilai',ket='$ket',semester='$semester',tp='$tapel' WHERE nis='$nis' AND eskul='$eskul'");
			                  		
}
        

?>