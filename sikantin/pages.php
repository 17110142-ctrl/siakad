<?php if ($pg == '') : ?>
    <?php include 'home.php'; ?>
<?php elseif ($pg == 'profil') : ?>
    <?php include 'keranjang/profil.php'; ?>
<?php elseif ($pg == 'mykeranjang') : ?>
    <?php include 'keranjang/keranjang.php'; ?>
<?php elseif ($pg == 'history') : ?>
    <?php include 'keranjang/history.php'; ?>
<?php elseif ($pg == 'saldo') : ?>
    <?php include 'keranjang/saldo.php'; ?>
	<?php elseif ($pg == 'login') : ?>
    <?php include 'login.php'; ?>
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
