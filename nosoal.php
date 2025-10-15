<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
cek_session_siswa();
$id_bank = $_POST['id_bank'];
$id_siswa = $_POST['id_siswa'];
$id_ujian = $_POST['idu'];
$pengacak = $_POST['pengacak'];
$pengacak = explode(',', $pengacak);
$pengacakpil = $_POST['pengacakpil'];
$pengacakpil = explode(',', $pengacakpil);
$jumsoal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$id_bank' "));

?>
<div class='row' id='nomorsoal'>

    <?php for ($n = 0; $n < $jumsoal; $n++) : ?>
        <?php
        $id_soal = $pengacak[$n];
        $cekjwb = rowcount($koneksi, 'jawaban', array('id_siswa' => $id_siswa, 'id_bank' => $id_bank, 'id_soal' => $id_soal,  'id_ujian' => $id_ujian));
        $ragu = fetch($koneksi, 'jawaban', array('id_siswa' => $id_siswa, 'id_bank' => $id_bank, 'id_soal' => $id_soal,  'id_ujian' => $id_ujian));

        $color1 = ($cekjwb <> 0) ? 'green' : 'gray';
        $color = ($ragu['ragu'] == 1) ? 'yellow' : $color1;
        $nomor = $n + 1;
        $nomor = ($nomor < 10) ? "0" . $nomor : $nomor;

         if($ragu['jenis']=='1' ){
		   $jawabannya=$ragu['jawaban'];
		   
	   }
	   if($ragu['jenis']=='2' ){
		   $jawabannya='Esai';
	   }
	   if($ragu['jenis']=='3' ){
		   $jawabannya=$ragu['jawabmulti'];
	   }
       if($ragu['jenis']=='4' ){
		   $jawabannya=$ragu['jawabbs'];
	   }
	   if($ragu['jenis']=='5' ){
		   $jawabannya=$ragu['jawaburut'];
	   }
	   if($ragu['id_bank']<>$id_bank ){
		    $jawabannya='Blm Jwb';
	   }
	   

        ?>
		<?php if($ragu['jenis']=='1' ){ ?>
        <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-red' style="font-size:medium"><?= $jawabannya ?></span></a>
        <?php } ?>
	   <?php if($ragu['jenis']=='2' ){ ?>
        <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-red' style="font-size:medium"><?= $jawabannya ?></span></a>
        <?php } ?>
	<?php if($ragu['jenis']=='3' ){ ?>
        <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-red' style="font-size:small"><?= $jawabannya ?></span></a>
        <?php } ?>
		<?php if($ragu['jenis']=='4' ){ ?>
        <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-red' style="font-size:small"><?= $jawabannya ?></span></a>
        <?php } ?>
		<?php if($ragu['jenis']=='5' ){ ?>
        <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-red' style="font-size:small"><?= $jawabannya ?></span></a>
       	   <?php } ?>
		   <?php if($ragu['id_bank']<>$id_bank ){ ?>
		    <a style="min-width:50px;height:50px;border-radius:10px;border:solid black;font-size:medium" class='btn btn-app bg-<?= $color ?>' id='badge<?= $id_soal ?>' onclick="loadsoal(<?= $id_bank ?>,<?= $id_siswa ?>,<?= $n ?>)"> <?= $nomor ?> <span id='jawabtemp<?= $id_soal ?>' class='badge bg-black' style="font-size:small"><?= $jawabannya ?></span></a>
       	   <?php } ?>
	<?php endfor; ?>
</div>