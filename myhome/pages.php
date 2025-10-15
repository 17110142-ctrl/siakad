<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == 'info') : ?>
    <?php include 'info.php'; ?>
<?php elseif ($pg == 'profil') : ?>
    <?php include 'user/profil.php'; ?>
<?php elseif ($pg == 'dashmin') : ?>
    <?php include 'dashmin.php'; ?>
<?php elseif ($pg == 'dashgur') : ?>
    <?php include 'dashgur.php'; ?>
<?php elseif ($pg == 'dashtu') : ?>
    <?php include 'dashtu.php'; ?>
 <!-- PENGATURAN -->
<?php elseif ($pg == enkripsi('pengaturan')) : ?>
    <?php include 'pengaturan/pengaturan.php'; ?>
<?php elseif ($pg == enkripsi('msiswa')) : ?>
    <?php include 'siswa/msiswa.php'; ?>
<?php elseif ($pg == enkripsi('ketua')) : ?>
    <?php include 'siswa/ketua.php'; ?>
<!-- MASTER -->
<?php elseif ($pg == enkripsi('pmapel')) : ?>
    <?php include 'master/mapel.php'; ?>
<?php elseif ($pg == enkripsi('peserta')) : ?>
    <?php include 'siswa/siswa.php'; ?>
<?php elseif ($pg == enkripsi('kuri')) : ?>
    <?php include 'kurikulum/kuri.php'; ?>
<?php elseif ($pg == enkripsi('eskul')) : ?>
    <?php include 'eskul/eskul.php'; ?>
<?php elseif ($pg == enkripsi('informasi')) : ?>
    <?php include 'info/info.php'; ?>
<!-- USERS -->
<?php elseif ($pg == enkripsi('admin')) : ?>
    <?php include 'user/admin.php'; ?>
	<?php elseif ($pg == enkripsi('kepsek')) : ?>
    <?php include 'user/kepsek.php'; ?>
<?php elseif ($pg == enkripsi('guru')) : ?>
    <?php include 'user/guru.php'; ?>
<?php elseif ($pg == enkripsi('guruwali')) : ?>
    <?php include 'user/guruwali.php'; ?>
<?php elseif ($pg == enkripsi('staff')) : ?>
    <?php include 'user/staff.php'; ?>
<?php elseif ($pg == 'kontakme') : ?>
    <?php include 'user/kontakme.php'; ?>
<?php elseif ($pg == enkripsi('tampil')) : ?>
    <?php include 'tampilprofilsiswa.php'; ?>
<?php elseif ($pg == enkripsi('siswa')) : ?>
    <?php include 'silearn/'; ?>
<?php elseif ($pg == enkripsi('api')) : ?>
    <?php include 'M_apiext.php'; ?>
<!-- DATABASE -->
<?php elseif ($pg == enkripsi('resetdata')) : ?>
    <?php include 'pengaturan/resetdata.php'; ?>
<?php elseif ($pg == enkripsi('backupdata')) : ?>
    <?php include 'pengaturan/backupdata.php'; ?>
<?php elseif ($pg == enkripsi('restore')) : ?>
    <?php include 'pengaturan/restoredata.php'; ?>	
<!-- JADWAL -->
<?php elseif ($pg == enkripsi('mjadwal')) : ?>
    <?php include 'jadwal/jadwal.php'; ?>	

<!-- MUTASI -->
<?php elseif ($pg == enkripsi('mutasi')) : ?>
    <?php include 'siswa/mutasi.php'; ?>
<?php elseif ($pg == enkripsi('pdb')) : ?>
    <?php include 'pdb/msiswa.php'; ?>
	<!-- KEPSEK -->
<?php elseif ($pg == 'kbm') : ?>
    <?php include 'kepala/kbm.php'; ?>
<?php elseif ($pg == 'jadwalku') : ?>
    <?php include 'kepala/jadwal.php'; ?>
<?php elseif ($pg == 'jadwalmu') : ?>
    <?php include 'kepala/jadwalmu.php'; ?>
<?php elseif ($pg == 'abpeg') : ?>
    <?php include 'kepala/abpeg.php'; ?>
	<?php elseif ($pg == 'detail') : ?>
    <?php include 'kepala/detail.php'; ?>
	<!-- WALAS -->
<?php elseif ($pg == 'absensiswa') : ?>
    <?php include 'siswa/absis.php'; ?>
<?php elseif ($pg == 'prestasi') : ?>
    <?php include 'siswa/prestasi.php'; ?>	
<?php elseif ($pg == 'pelanggaran') : ?>
    <?php include 'siswa/pelanggaran.php'; ?>

	<!-- TARIK -->
<?php elseif ($pg == enkripsi('setsinkron')) : ?>
    <?php include 'tarik/setting.php'; ?>
<?php elseif ($pg == enkripsi('sinmas')) : ?>
    <?php include 'tarik/sinmas.php'; ?>
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
