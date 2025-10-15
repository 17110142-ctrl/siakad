<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'waktu', ['id' => $id]);
	if($exec){
		$query = "SELECT * FROM waktu ORDER BY id";
       $hasil = mysqli_query($query);
 $no = 1;
 
while ($data  = mysqli_fetch_array($hasil))
{
	 $id = $data['id'];
	 $query2 = "UPDATE waktu SET id = $no WHERE id = '$id'";
   mysqli_query($koneksi,$query2);
 
   $no++;   
	}
	$query = "ALTER TABLE waktu  AUTO_INCREMENT = $no";
mysqli_query($koneksi,$query);
	}
}
if ($pg == 'tambah') {
	if($_POST['masuk_eskul'] !=''):
	$jam = date('H',strtotime($_POST['masuk_eskul']));
	$menit = date('i',strtotime($_POST['masuk_eskul']));
	if($menit <= 35){
	$menit = $menit + 20;
	$jeskul = $jam.":".$menit;
	}else{
	$menit = "00";
	$jame = $jam + 1;
	$jeskul = $jame.":".$menit;
	}
	endif;
	$alpha = date('H:i',strtotime($_POST['alpha']));
	$jalpha = $alpha.":00";
	if($_POST['masuk_eskul'] !=''):
      $data = [
	    'hari'     => $_POST['hari'],     
        'masuk'   => $_POST['masuk'],
		'pulang'   => $_POST['pulang'],
		'masuk_eskul'   => $_POST['masuk_eskul'],
		'jam_eskul'   => $jeskul,
		'pulang_eskul'   => $_POST['pulang_eskul'],
		'alpha'   => $jalpha
		
			];
	$exec = insert($koneksi, 'waktu', $data);
	echo "OK";
else:
	 $data = [
       'hari'     => $_POST['hari'],     
        'masuk'   => $_POST['masuk'],
		'pulang'   => $_POST['pulang'],
		'masuk_eskul'   => '23:00:00',
		'jam_eskul'   => '23:00:00',
		'pulang_eskul' =>  '23:00:00',
		'alpha'   => $jalpha
        ];
$exec = insert($koneksi, 'waktu', $data);
	echo "OK";
endif;	
}
if ($pg == 'edit') {
	$id = $_POST['id'];
	
    $jam = date('H',strtotime($_POST['masuk_eskul']));
	$menit = date('i',strtotime($_POST['masuk_eskul']));
	if($menit <= 35){
	$menit = $menit + 20;
	$jeskul = $jam.":".$menit;
	}else{
	$menit = "00";
	$jame = $jam + 1;
	$jeskul = $jame.":".$menit;
	}
	
	$alpha = date('H:i',strtotime($_POST['alpha']));
	$jalpha = $alpha.":00";
	if($_POST['masuk_eskul'] !=''):
         $data = [
       'hari'     => $_POST['hari'],     
        'masuk'   => $_POST['masuk'],
		'pulang'   => $_POST['pulang'],
		'masuk_eskul'   => $_POST['masuk_eskul'],
		'jam_eskul'   => $jeskul,
		'pulang_eskul'   => $_POST['pulang_eskul'],
		'alpha'   => $jalpha
        ];
    $exec = update($koneksi, 'waktu', $data, ['id' => $id]);
    echo $exec;
	else:
	 $data = [
       'hari'     => $_POST['hari'],     
        'masuk'   => $_POST['masuk'],
		'pulang'   => $_POST['pulang'],
		'masuk_eskul'   => '23:00:00',
		'jam_eskul'   => '23:00:00',
		'pulang_eskul' =>  '23:00:00',
		'alpha'   => $jalpha
        ];
    $exec = update($koneksi, 'waktu', $data, ['id' => $id]);
    echo $exec;
	endif;
}
?>