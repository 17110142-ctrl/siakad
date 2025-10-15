<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == 'kategori') : ?>
    <?php include 'produk/kategori.php'; ?>
<?php elseif ($pg == enkripsi('produk')) : ?>
    <?php include 'produk/produk.php'; ?>
	
<?php elseif ($pg == 'produktoko') : ?>
    <?php include 'toko/produk.php'; ?>
<?php elseif ($pg == 'ctrx') : ?>
    <?php include 'toko/ctrx.php'; ?>	
<?php elseif ($pg == 'cstk') : ?>
    <?php include 'toko/cstk.php'; ?>	
<?php elseif ($pg == 'trxtoko') : ?>
    <?php include 'toko/trxtoko.php'; ?>	
<?php elseif ($pg == 'transaksi') : ?>
    <?php include 'transaksi.php'; ?>	
<?php elseif ($pg == 'pengaturan') : ?>
    <?php include 'pengaturan/pengaturan.php'; ?>
<?php elseif ($pg == 'msiswa') : ?>
    <?php include 'siswa/msiswa.php'; ?>
<?php elseif ($pg == 'register') : ?>
    <?php include 'rfid/rfid.php'; ?>
<?php elseif ($pg == 'pelanggan') : ?>
    <?php include 'siswa/siswa.php'; ?>
<?php elseif ($pg == 'topup') : ?>
    <?php include 'siswa/topup.php'; ?>
<?php elseif ($pg == 'admin') : ?>
    <?php include 'user/admin.php'; ?>
<?php elseif ($pg == 'guru') : ?>
    <?php include 'user/guru.php'; ?>
<?php elseif ($pg == 'toko') : ?>
    <?php include 'toko/toko.php'; ?>
<!-- DATABASE -->
<?php elseif ($pg == 'resetdata') : ?>
    <?php include 'pengaturan/resetdata.php'; ?>
<?php elseif ($pg == 'backupdata') : ?>
    <?php include 'pengaturan/backupdata.php'; ?>
<?php elseif ($pg == 'restore') : ?>
    <?php include 'pengaturan/restoredata.php'; ?>	

<?php elseif ($pg == enkripsi('tran')) : ?>
    <?php include 'manual/tran.php'; ?>
<?php elseif ($pg == enkripsi('struk')) : ?>
    <?php include 'manual/struk.php'; ?>
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
