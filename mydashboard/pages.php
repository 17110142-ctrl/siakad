<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == 'profil_siswa') : ?>
    <?php include 'profil_siswa.php'; ?>
<?php elseif ($pg == 'edit_profil') : ?>
    <?php include 'edit_profil.php'; ?>
<?php elseif ($pg == 'prestasi') : ?>
    <?php include 'prestasi.php'; ?>
<?php elseif ($pg == 'perpus') : ?>
    <?php include 'perpus.php'; ?>

 <!-- E-Learning -->
<?php elseif ($pg == enkripsi('materi')) : ?>
    <?php include 'E-Learning/materi.php'; ?>
<?php elseif ($pg == 'bukamateri') : ?>
    <?php include 'E-Learning/bukamateri.php'; ?>
<?php elseif ($pg == enkripsi('tugas')) : ?>
    <?php include 'E-Learning/tugas.php'; ?>
<?php elseif ($pg == 'bukatugas') : ?>
    <?php include 'E-Learning/bukatugas.php'; ?>
<?php elseif ($pg == enkripsi('quiz')) : ?>
    <?php include 'E-Learning/quiz.php'; ?>
<!-- K B M -->
<?php elseif ($pg == enkripsi('nilai')) : ?>
    <?php include 'nilai/nilai.php'; ?>
<?php elseif ($pg == absensi) : ?>
    <?php include 'nilai/absensi.php'; ?>
<?php elseif ($pg == enkripsi('konsel')) : ?>
    <?php include 'pelanggaran.php'; ?>
<?php elseif ($pg == enkripsi('khs')) : ?>
    <?php include 'khs.php'; ?>
<?php elseif ($pg == enkripsi('informasi')) : ?>
    <?php include 'info/info.php'; ?>
<!-- USERS -->

<?php elseif ($pg == enkripsi('siswa')) : ?>
    <?php include 'silearn/'; ?>

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
