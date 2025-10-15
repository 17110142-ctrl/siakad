<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
    <?php
    include('../controller/config.php');
    // menangkap data yang di kirim dari form
    $no = $_POST['no'];
    $nisn = $_POST['nisn'];
    $name = $_POST['name'];
    $addresse = $_POST['addresse'];
    $tgllhr = $_POST['tgllhr'];
    $kelas = $_POST['kelas'];
    $ket = $_POST['ket'];
    // update data ke database
    $simpan = mysqli_query($koneksi, "UPDATE tb_student SET nisn='$nisn', name='$name', addresse='$addresse', tgllhr='$tgllhr', kelas='$kelas', ket='$ket' where no='$no'");
    if ($simpan) {
        echo '<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-left: 10px; margin-top: 10px; margin-bottom:0px">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
    <strong> SUKSES!!!</strong> DATA BERHASIL DISIMPAN!.
    </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-left: 10px; margin-top: 10px;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
    <strong> GAGAL!!!</strong> GAGAL DISIMPAN!.
    </div>';
    }
    // mengalihkan halaman kembali ke index.php
    echo "<meta http-equiv='refresh' content='0; url=datasiswa.php'>";
    ?>
</div>