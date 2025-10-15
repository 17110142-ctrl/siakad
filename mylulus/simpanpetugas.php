    <?php
    include('../controller/config.php');
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $pass = md5($_POST['pass']);
    $tambah = mysqli_query($koneksi, "INSERT into tbl_user (nama,username,pass) values ('$nama','$username','$pass')");

    if ($tambah) {
        echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <strong> SUKSES!!!</strong> DATA PETUGAS BERHASIL DISIMPAN!.
        </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <strong> Sukses..!</strong> GAGAL DISIMPAN!.
        </div>';
    }

    echo "<meta http-equiv='refresh' content='2; url=index.php?page=petugas'>";
    ?>