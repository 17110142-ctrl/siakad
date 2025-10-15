<div class="navbar navbar-inverse navbar-fixed-bottom">
    <?php
    require "../config/koneksi.php";
    $p = mysqli_query($koneksi, "SELECT * FROM aplikasi WHERE  id_aplikasi='1'");
    while ($data = mysqli_fetch_array($p)) {
        echo '<h5 align="center" style="color:#ffff;">' . $data['sekolah'] . '</br>' . $data['aplikasi'] . ' &copy ' . $data['tp'] . '</h5>';
    }
    ?>
</div>