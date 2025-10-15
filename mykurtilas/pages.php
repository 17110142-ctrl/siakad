<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == enkripsi('siswa')) : ?>
    <?php include 'siswa/siswa.php'; ?>	
<?php elseif ($pg == enkripsi('model')) : ?>
    <?php include 'jadwal/model.php'; ?>
<?php elseif ($pg == enkripsi('mapel')) : ?>
    <?php include 'jadwal/mapel.php'; ?>
<?php elseif ($pg == enkripsi('setting')) : ?>
    <?php include 'pengaturan/setting.php'; ?>
<!-- MASTER -->
<?php elseif ($pg == enkripsi('kkm')) : ?>
    <?php include 'kkm/kkm.php'; ?>
<?php elseif ($pg == enkripsi('nilai')) : ?>
    <?php include 'nilai/nilai3.php'; ?>
<?php elseif ($pg == enkripsi('deskrip3')) : ?>
    <?php include 'nilai/deskrip3.php'; ?>
<?php elseif ($pg == enkripsi('deskrip4')) : ?>
    <?php include 'nilai/deskrip4.php'; ?>
<?php elseif ($pg == enkripsi('nsikap')) : ?>
    <?php include 'nilai/nsikap.php'; ?>
<?php elseif ($pg == enkripsi('nsikap2')) : ?>
    <?php include 'nilai/nsikap2.php'; ?>
<?php elseif ($pg == enkripsi('nsikap3')) : ?>
    <?php include 'nilai/nsikap3.php'; ?>
	
<?php elseif ($pg == enkripsi('leger')) : ?>
    <?php include 'walas/leger.php'; ?>	
<!-- ESKUL -->
<?php elseif ($pg == enkripsi('peskul')) : ?>
    <?php include 'eskul/peskul.php'; ?>	
<?php elseif ($pg == enkripsi('nileskul')) : ?>
    <?php include 'eskul/nileskul.php'; ?>	
	
<!-- WALAS -->
<?php elseif ($pg == enkripsi('absensi')) : ?>
    <?php include 'walas/absen.php'; ?>	
<?php elseif ($pg == enkripsi('prestasi')) : ?>
    <?php include 'walas/prestasi.php'; ?>	
<?php elseif ($pg == enkripsi('catatan')) : ?>
    <?php include 'walas/catat.php'; ?>	
<?php elseif ($pg == enkripsi('cetak')) : ?>
    <?php include 'walas/cetak.php'; ?>		
	<!-- DATABASE -->
<?php elseif ($pg == enkripsi('resetdata')) : ?>
    <?php include 'pengaturan/resetdata.php'; ?>

<?php else : ?>
 <div class="app app-error align-content-stretch d-flex flex-wrap">
        <div class="app-error-info">
            <h5>Oops!</h5>
            <span>It seems that the page you are looking for no longer exists.<br>
                We will try our best to fix this soon.</span>
            <a href="." class="btn btn-dark">Go to dashboard</a>
        </div>
        <div class="app-error-background"></div>
    </div>

<?php endif ?>
