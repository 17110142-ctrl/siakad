<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>

<?php elseif ($pg == enkripsi('arsip')) : ?>
    <?php include 'list_arsip.php'; ?>
<?php elseif ($pg == 'settingskl') : ?>
    <?php include 'settingskl.php'; ?>
<?php elseif ($pg == enkripsi('siswa')) : ?>
    <?php include 'siswa.php'; ?>
<?php elseif ($pg == enkripsi('updatesiswa')) : ?>
    <?php include 'updatesiswa.php'; ?>
<?php elseif ($pg == enkripsi('mapel')) : ?>
    <?php include 'mapel.php'; ?>	
<?php elseif ($pg == enkripsi('nilai')) : ?>
    <?php include 'nilai.php'; ?>	
<?php elseif ($pg == 'nilaiujian') : ?>
    <?php include 'nilaiujian.php'; ?>
	<?php elseif ($pg == enkripsi('skkb')) : ?>
    <?php include 'skkb.php'; ?>
	<?php elseif ($pg == 'cetak') : ?>
    <?php include 'cetak.php'; ?>
	<?php elseif ($pg == 'viewsemester') : ?>
    <?php include 'viewsemester.php'; ?>
	<?php elseif ($pg == 'nijazah') : ?>
    <?php include 'nijazah.php'; ?>
	<?php elseif ($pg == 'transkip') : ?>
    <?php include 'transkip.php'; ?>
	<?php elseif ($pg == 'viewujian') : ?>
    <?php include 'viewujian.php'; ?>
	<?php elseif ($pg == 'pk'): ?>
    <?php include 'pk.php'; ?>	
	<?php elseif ($pg == 'prod'): ?>
    <?php include 'prod.php'; ?>
	<?php elseif ($pg == 'viewsku'): ?>
    <?php include 'viewsku.php'; ?>
	<?php elseif ($pg == 'pkl'): ?>
    <?php include 'pkl.php'; ?>
	<?php elseif ($pg == 'viewpkl'): ?>
    <?php include 'viewpkl.php'; ?>
	<?php elseif ($pg == enkripsi('resetdata')): ?>
    <?php include 'reset.php'; ?>
	
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
