<?php
session_start();
if (empty($_SESSION['namauser']) and empty($_SESSION['kode'])) {
    echo "<p>OPS!!!.</p>";
} else {
?>
    <?php
    include "adm-header.php";
    ?>
    <div class="container" style="height: 100%; display: flex; justify-content: center; border: 0px solid #000;">
        <?php
        include "adm-menu.php";
        ?>
        <table>
            <tr>
                <td>
                    <?php
                    include('../controller/config.php');
                    $no = $_GET['no'];
                    $hapus = mysqli_query($koneksi, "DELETE from tb_student where no='$no'");
                    if ($hapus) {
                        echo '<div class="alert alert-success alert-dismissible fade in" role="alert" style="margin-left: 10px; margin-top: 10px; margin-bottom:0px">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <strong> SUKSES!!!</strong> DATA BERHASIL DIHAPUS!.
                        </div>';
                    } else {
                        echo '<div class="alert alert-danger alert-dismissible fade in" role="alert" style="margin-left: 10px; margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                        <strong> Sukses..!</strong> GAGAL DIHAPUS!.
                        </div>';
                    }
                    echo "<meta http-equiv='refresh' content='2; url=datasiswa.php'>"; ?>
                    <?php include "adm-table.php"; ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
    include "../controller/footer.php";
    ?>
    </body>

    </html>
<?php
}
?>