<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'tambah') {
	
$kelas=$_POST['kelas'];
 $nis = $_POST['nis'];
  $ket1 = $_POST['ket1'];
   $ket2 = $_POST['ket2'];
    $pred = $_POST['pred'];
               
	  
$data=[
'kelas' => $kelas,
'nis' => $nis,
'ket1' => $ket1,
'ket2' => $ket2,
'mapel' => $_POST['mapel'],
'guru' => $_POST['guru'],
'pred' => $pred,
'smt'=> $semester,
'tp' => $tapel

];	  
    
	if ($ket1 == $ket2) {
                   echo"gagal";
					}else{
					
	$exec=insert($koneksi,'spiritual',$data);
		 echo "OK";		
	 
	   }
}
if ($pg == 'hapussos') {
    $id = $_POST['id'];
    delete($koneksi, 'sosial', ['ids' => $id]);
}
if ($pg == 'hapusspi') {
    $id = $_POST['id'];
    delete($koneksi, 'spiritual', ['ids' => $id]);
}
if ($pg == 'tambah_sos') {
	$kelas=$_POST['kelas'];
                $nis = $_POST['nis'];
                $ket1 = implode(', ',$_POST['ket1']);
				$array= $_POST['ket1'];
				$k1=$array[0];
				$k2=$array[1];
				$k3=$array[2];
				$k4=$array[3];
				$k5=$array[4];
				$k6=$array[5];
				$k7=$array[6];
				
                $ket2 = $_POST['ket2'];
                $pred = $_POST['pred'];
				
				 $data=[
				'kelas' => $kelas,
				'nis' => $nis,
				'ket1' => $ket1,
				'ket2' => $ket2,
				'mapel' => $_POST['mapel'],
				'guru' => $_POST['guru'],
				'pred' => $pred,
				'smt'=> $setting['semester'],
				'tp' => $tapel
				];	
				 if($ket2==$k1 OR $ket2==$k2 OR $ket2==$k3 OR $ket2==$k4 OR $ket2==$k5 OR $ket2==$k6 OR $ket2==$k7){
	  echo"gagal";
					}else{
						
					$exec=insert($koneksi,'sosial',$data);
		 echo "OK";		
						
					}				
}