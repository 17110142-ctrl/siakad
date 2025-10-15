<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'mapel') {
	 $kode = $_POST['mapel'];
     $tingkat = $_POST['level'];
     $pk = $_POST['pk'];
	$urut = $_POST['urut'];
	$kuri = $_POST['kuri'];
	$sikap = $_POST['sikap'];
	$kelompok = $_POST['kelompok'];	
    $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE  mapel='$kode' AND tingkat='$tingkat' AND pk='$pk'"));
        if ($cek > 0) :
            echo "GAGAL";
        else :
		
        $exec = mysqli_query($koneksi, "INSERT INTO mapel_rapor (urut,mapel,tingkat,pk,kurikulum,kelompok,sikap) VALUES ('$urut','$kode','$tingkat','$pk','$kuri','$kelompok','$sikap')");
		$exec = mysqli_query($koneksi, "UPDATE mata_pelajaran set sikap='$sikap' WHERE id='$kode'");
endif;
}
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'mapel_rapor', ['idm' => $id]);
    echo $exec;
}
if ($pg == 'kuri') {
    $level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT kelas.level,kelas.kurikulum,m_kurikulum.idk,m_kurikulum.nama_kurikulum FROM kelas JOIN m_kurikulum ON m_kurikulum.idk=kelas.kurikulum WHERE kelas.level='" . $level . "' GROUP BY kelas.level");
 echo "<option value=''>Pilih Kurikulum</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kurikulum]'>$data[nama_kurikulum]</option>";
    }
}