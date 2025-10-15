<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == enkripsi('lingkup')) : ?>
    <?php include 'proto/lingkup.php'; ?>
<?php elseif ($pg == enkripsi('tujuan')) : ?>
    <?php include 'proto/tujuan.php'; ?>
<?php elseif ($pg == enkripsi('ki3')) : ?>
    <?php include 'kurtilas/ki3.php'; ?>
<?php elseif ($pg == enkripsi('ki4')) : ?>
    <?php include 'kurtilas/ki4.php'; ?>
<?php elseif ($pg == 'upload') : ?>
    <?php include 'upload/upload.php'; ?>
<?php elseif ($pg == enkripsi('agenda')) : ?>
    <?php include 'agenda/agenda.php'; ?>	
<?php elseif ($pg == enkripsi('jurnal')) : ?>
    <?php include 'agenda/jurnal.php'; ?>
<?php elseif ($pg == enkripsi('lihatabsen')) : ?>
    <?php include 'absen/lihat_absen.php'; ?>
<?php elseif ($pg == enkripsi('absensi')) : ?>
    <?php include 'absen/presensi.php'; ?>
<?php elseif ($pg == enkripsi('manual')) : ?>
    <?php include 'absen/manual.php'; ?>
 <?php elseif ($pg == enkripsi('nilai')) : ?>
    <?php include 'nilai/nilph.php'; ?>
<?php elseif ($pg == enkripsi('cnil')) : ?>
    <?php include 'nilai/cnilai.php'; ?>
<?php elseif ($pg == enkripsi('katrol')) : ?>
    <?php include 'nilai/katrol.php'; ?>
<?php elseif ($pg == enkripsi('ckatrol')) : ?>
    <?php include 'nilai/ckatrol.php'; ?>
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
