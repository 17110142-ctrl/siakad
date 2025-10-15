<?php
include('../controller/config.php');
$hapus = mysqli_query($koneksi, "DELETE from tbl_user where id_user='$_POST[id]'");
if ($hapus) {
    echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
    <strong> SUKSES!!!</strong> DATA BERHASIL DIHAPUS!.
    </div>';
} else {
    echo '<div class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
    <strong> Sukses..!</strong> GAGAL DIHAPUS!.
    </div>';
}
echo "<meta http-equiv='refresh' content='10; url=index.php?page=petugas'>";
