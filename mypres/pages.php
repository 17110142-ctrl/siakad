<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>

 <!-- PENGATURAN -->
<?php elseif ($pg == enkripsi('waktu')) : ?>
    <?php include 'pengaturan/waktu.php'; ?>
<?php elseif ($pg == enkripsi('mesin')) : ?>
    <?php include 'pengaturan/mesin.php'; ?>
<?php elseif ($pg == enkripsi('psis')) : ?>
    <?php include 'pesan/pesansis.php'; ?>	
<?php elseif ($pg == enkripsi('ppeg')) : ?>
    <?php include 'pesan/pesanpeg.php'; ?>	
<?php elseif ($pg == enkripsi('esis')) : ?>
    <?php include 'pesan/esis.php'; ?>	
<?php elseif ($pg == enkripsi('epeg')) : ?>
    <?php include 'pesan/epeg.php'; ?>	
 <!-- REGISTER -->
<?php elseif ($pg == enkripsi('rfid')) : ?>
    <?php include 'rfid/rfid.php'; ?>
<?php elseif ($pg == 'barkode') : ?>
    <?php include 'barkode/barkode.php'; ?>
<?php elseif ($pg == 'finger') : ?>
    <?php include 'finger/finger.php'; ?>
<?php elseif ($pg == 'face') : ?>
    <?php include 'face/face.php'; ?>
	<?php elseif ($pg == 'faces') : ?>
    <?php include 'face/faces.php'; ?>
<!-- ABSENSI -->
<?php elseif ($pg == enkripsi('status')) : ?>
    <?php include 'status.php'; ?>
<?php elseif ($pg == enkripsi('absis')) : ?>
    <?php include 'cetak/absis.php'; ?>	
<?php elseif ($pg == enkripsi('abpeg')) : ?>
    <?php include 'cetak/abpeg.php'; ?>
<?php elseif ($pg == enkripsi('absis2')) : ?>
    <?php include 'cetak/absis2.php'; ?>	
<?php elseif ($pg == enkripsi('abpeg2')) : ?>
    <?php include 'cetak/abpeg2.php'; ?>		
<?php elseif ($pg == enkripsi('detail')) : ?>
    <?php include 'cetak/detail.php'; ?>	
<!-- TIDAK HADIR -->
<?php elseif ($pg == enkripsi('insis')) : ?>
    <?php include 'absen/absiswa.php'; ?>		
<?php elseif ($pg == enkripsi('inpeg')) : ?>
    <?php include 'absen/abpeg.php'; ?>	
<!-- DATABASE -->

<?php elseif ($pg == enkripsi('resetpres')) : ?>
    <?php include 'pengaturan/resetdata.php'; ?>	
	
	
<?php elseif ($pg == enkripsi('cetak')) : ?>
    <?php include 'kartu/cetak.php'; ?>
<?php elseif ($pg == enkripsi('cetakpegawai')) : ?>
    <?php include 'kartu/cetak_pegawai.php'; ?>
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
