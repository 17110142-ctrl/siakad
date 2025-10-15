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
                    <div id="rightPan">
                        <div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
                            <div class="cleaner_h5"></div>
                            <div id="hasil"></div>
                            <div class="cleaner_h5"></div>
                            <fieldset>
                                <div class=" alert alert-dismissable alert-success">
                                    <h4 align="center"><b>SISTEM INFORMASI KELULUSAN</br>UPDATE DATA SISWA</b></h4>
                                </div>
                                <?php
                                include "../controller/config.php";
                                $no = $_GET['no'];
                                $q = mysqli_query($koneksi, "SELECT * from tb_student where no='$no'");
                                while ($r = mysqli_fetch_array($q)) {
                                ?>
                                    <form class="form-horizontal" method="post" action="updatesiswa.php">
                                        <input type="hidden" class="form-control" name="no" value="<?php echo $r['no']; ?>" size="50" style="text-align: left;">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="nisn" value="<?php echo $r['nisn']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>NISN</b></span>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" value="<?php echo $r['name']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>NAMA SISWA</b></span>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="addresse" value="<?php echo $r['addresse']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>TEMPAT LAHIR</b></span>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="tgllhr" value="<?php echo $r['tgllhr']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>TANGGAL LAHIR</b></span>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="kelas" value="<?php echo $r['kelas']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>KELAS</b></span>
                                        </div>
                                        </br>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ket" value="<?php echo $r['ket']; ?>" size="50" style="text-align: left;">
                                            <span class="input-group-addon" id="basic-addon1"><b>STATUS KELULUSAN</b></span>
                                        </div>
                                        </br>
                                        <div class="form-group" style="margin-bottom: -10px;">
                                            <p align="left">
                                                <input type="submit" value="UPDATE" name="simpan" class="btn btn-danger" />
                                                <a class="btn btn-success" href="datasiswa.php" value=""><b>KEMBALI</b></a>
                                            </p>
                                        </div>
                                    <?php
                                }
                                    ?>
                                    </form>
                            </fieldset>
                        </div>
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