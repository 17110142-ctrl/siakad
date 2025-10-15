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
<?php elseif ($pg == enkripsi('khs')) : ?>
    <?php include 'khs.php'; ?>
<?php elseif ($pg == enkripsi('formatif')) : ?>
    <?php include 'nilai/formatif.php'; ?>
<?php elseif ($pg == enkripsi('sumlm')) : ?>
    <?php include 'nilai/sumlm.php'; ?>
<?php elseif ($pg == enkripsi('sts')) : ?>
    <?php include 'nilai/sts.php'; ?>
<?php elseif ($pg == enkripsi('sas')) : ?>
    <?php include 'nilai/sas.php'; ?>
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

<?php elseif ($pg == enkripsi('cetak')) : ?>
    <?php include 'walas/cetak.php'; ?>        
<?php elseif ($pg == enkripsi('cetak_sts')) : ?>
    <?php include 'walas/cetak_nilai_sts.php'; ?>        
	<!-- DATABASE -->
<?php elseif ($pg == enkripsi('resetdata')) : ?>
    <?php include 'pengaturan/resetdata.php'; ?>

<?php elseif ($pg == enkripsi('dapodik')) : ?>
    <?php include 'dapodik/index.php'; ?>

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
