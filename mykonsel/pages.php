<?php if ($pg == '') : ?>
     <?php include 'home.php'; ?>

<?php elseif ($pg == enkripsi('kategori')): ?>
    <?php include 'kategori.php'; ?>
<?php elseif ($pg == enkripsi('subkategori')): ?>
    <?php include 'subkategori.php'; ?>
<?php elseif ($pg == enkripsi('pelanggaran')): ?>
    <?php include 'pelanggaran.php'; ?>
<?php elseif ($pg == enkripsi('tindakan')): ?>
    <?php include 'tindakan.php'; ?>
<?php elseif ($pg == enkripsi('inputbk')): ?>
    <?php include 'inputpelanggaran.php'; ?>
<?php elseif ($pg == enkripsi('surat')): ?>
    <?php include 'surat.php'; ?>
<!-- TARIK -->
<?php elseif ($pg == enkripsi('setsinkron')) : ?>
    <?php include 'tarik/setting.php'; ?>
<?php elseif ($pg == enkripsi('sinmas')) : ?>
    <?php include 'tarik/sinmas.php'; ?>

<?php elseif ($pg == enkripsi('pesan')) : ?>
    <?php include 'pesan.php'; ?>

	
<?php else : ?>
    <div class='error-page'>
        <h2 class='headline text-yellow'> 404</h2>
        <div class='error-content'>
            <br />
            <h3><i class='fa fa-warning text-yellow'></i> Upss! Halaman tidak ditemukan.</h3>
            <p>
                Halaman yang anda inginkan saat ini tidak tersedia.<br />
                Silahkan kembali ke <a href='?'><strong>dashboard</strong></a> dan coba lagi.<br />
                Hubungi petugas <strong><i>Developer</i></strong> jika ini adalah sebuah masalah.
            </p>
        </div>
    </div>
<?php endif ?>
 <?php
	$query = mysqli_query($koneksi, "select * from bk_tindakan");
while ($tdk = mysqli_fetch_array($query)):
$min=$tdk['minpoin'];
$max=$tdk['maxpoin'];
$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nis,SUM(poin) AS total FROM bk_siswa "));
$where =[
          'nis' => $siswa['nis'],     
           'ket' => $tdk['tindakan']    
         ];
      $cek = rowcount($koneksi, 'bk_sp', $where);
            if ($cek == 0) {
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP1'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP2'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
if($siswa['total'] >=$min  AND $tdk['tindakan']=='SP3'){
$exec = mysqli_query($koneksi, "INSERT INTO bk_sp(nis,ket,poin,tapel) VALUES('$siswa[nis]','$tdk[tindakan]','$siswa[total]','$setting[tp]')");
}
}
endwhile;
?>