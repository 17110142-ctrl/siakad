<?php if ($pg == '') : ?>
    <?php include 'sandik_skl/home.php'; ?>
<?php elseif ($pg == 'pengaturan'): ?>
    <?php cek_session_admin(); ?>
    <?php include 'pengaturan/pengaturan.php'; ?>		
    <!-- /master -->
<?php elseif ($pg == 'settingskl') : ?>
    <?php cek_session_admin(); ?>
    <?php include 'sandik_skl/settingskl.php'; ?>
<?php elseif ($pg == 'siswa') : ?>
    <?php cek_session_admin(); ?>
    <?php include 'sandik_skl/siswa.php'; ?>
<?php elseif ($pg == 'updatesiswa') : ?>
    <?php cek_session_admin(); ?>
    <?php include 'sandik_skl/updatesiswa.php'; ?>
<?php elseif ($pg == 'mapel') : ?>
    <?php cek_session_admin(); ?>
    <?php include 'sandik_skl/mapel.php'; ?>	
	<?php elseif ($pg == 'nilai') : ?>
    <?php include 'sandik_skl/nilai.php'; ?>	

	<?php elseif ($pg == 'skkb') : ?>
    <?php include 'sandik_skl/skkb.php'; ?>
	<?php elseif ($pg == 'cetak') : ?>
    <?php include 'sandik_skl/cetak.php'; ?>
	<?php elseif ($pg == 'viewsemester') : ?>
    <?php include 'sandik_skl/viewsemester.php'; ?>
	<?php elseif ($pg == 'nijazah') : ?>
    <?php include 'sandik_skl/nijazah.php'; ?>
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
        </div><!-- /.error-content -->
    </div><!-- /.error-page -->
<?php endif ?>
