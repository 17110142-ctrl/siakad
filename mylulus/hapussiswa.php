<div class="well" style="margin-left: 10px; margin-top: 10px;">
    <?php
    include "../controller/config.php";
    $hapus = mysqli_query($koneksi, "DELETE FROM tb_student");
    if ($hapus) {
        echo '
        <div class=" alert alert-dismissable alert-danger">
            <h4 align="left" style="text-transform: uppercase;">PROSES DELETE DATA SISWA BERHASIL! </h4>
        </div>';
    } else {
        echo '
        <div class=" alert alert-dismissable alert-danger">
            <h4 align="left" style="text-transform: uppercase;">PROSES GAGAL HAPUS DATA SISWA! </h4>
        </div>';
    }
    echo "<meta http-equiv='refresh' content='0; url=datasiswa.php'>";
    ?>
</div>