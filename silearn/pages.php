<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == 'materi') : ?>
    <?php include 'materi.php'; ?>
<?php elseif ($pg == 'bukamateri') : ?>
    <?php include 'bukamateri.php'; ?>
<?php elseif ($pg == 'tugas') : ?>
    <?php include 'tugas.php'; ?>
<?php elseif ($pg == 'bukatugas') : ?>
    <?php include 'bukatugas.php'; ?>
	
<?php elseif ($pg == 'peserta') : ?>
    <?php include 'siswa/siswa.php'; ?>
<!-- DATABASE -->
<?php elseif ($pg == 'resetdata') : ?>
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
