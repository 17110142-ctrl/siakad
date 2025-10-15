<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../../config/koneksi.php');
$que = mysqli_query($koneksi, "SELECT * FROM aplikasi WHERE id_aplikasi='1'");
$hsl = mysqli_fetch_array($que);
$timestamp = strtotime($hsl['tgl_pengumuman']);
// menghapus tags html (mencegah serangan jso pada halaman index)
$sekolah = strip_tags($hsl['sekolah']);
$tahun = strip_tags($hsl['tp']);
$tgl_pengumuman = strip_tags($hsl['tgl_pengumuman']);
//echo $timestamp;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>SIK ONLINE &copy SISTEM INFORMASI KELULUSAN ONLINE</title>
    <link rel="stylesheet" href="../css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="../css/style.css" media="screen">
    <link rel="stylesheet" href="../css/bootswatch.min.css">
    <link rel="stylesheet" href="../css/jasny-bootstrap.min.css">
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <script type="text/javascript" async="" src="../js/ga.js"></script>
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootswatch.js"></script>
</head>

<body style="background: url('../images/bg.png') repeat;">
    <?php
    include "c-header.php";
    ?>
    <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
        <table>
            <tr>
                <td style="padding: 10px;">
                    </br>
                    <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
                        <div style="top: 50%; transform:translate(0, -50%); position: absolute;">
                            <div class="well">
                                <p align="center"><img src="../../images/logo.png" height="75" /></p>
                                <h4 align="center" style="margin: 15px 0 -10px 0;"><b>SISTEM INFORMASI</br>STATUS KELULUSAN SISWA</b></h4>
                                <hr>
                                <script>
                                    function cekNo() {
                                        var cek = document.forms['formcarino']['nisn'].value;
                                        if (cek == null || cek == "") {
                                            alert("SILAHKAN MASUKKAN NOMOR NISN");
                                            return false;
                                        }
                                    }
                                </script>
                                <!-- countdown -->
                                <h4 align=" center">
                                    <div id="clock" class="alert alert-dismissable alert-success" style="padding:10px">
                                    </div>
                                </h4>
                                <div id="xpengumuman">
                                    <div class="alert alert-dismissable alert-success">
                                        <h4 align=" center">
                                            <b>PENGUMUMAN STATUS KELULUSAN</br>TAHUN <?= $tahun ?> DIBUKA
                                            </b>
                                        </h4>
                                    </div>
                                    <hr>
                                    <form class="form-horizontal" form name="formcarino" method="post" action="../index.php?page=carinisn" onSubmit="return cekNo()">
                                        <fieldset id="xpengumuman">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nisn" placeholder="KETIK/MASUKKAN NOMOR NISN" size="auto">
                                            </div>
                                            <div class="form-group" style="margin-bottom: -10px;">
                                                <p align="center"><input type="SUBMIT" name="SUBMIT" id="SUBMIT" value="PERIKSA DATA" class="btn btn-danger"></p>
                                            </div>
                                        </fieldset>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.countdown.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jasny-bootstrap.min.js"></script>
    <script type="text/javascript">
        var skrg = Date.now();
        $('#clock').countdown("<?= $tgl_pengumuman; ?>", {
                elapse: true
            })
            .on('update.countdown', function(event) {
                var $this = $(this);
                if (event.elapsed) {
                    $("#xpengumuman").show();
                    $("#clock").hide();
                } else {
                    $this.html(event.strftime('<strong>WAKTU PENGUMUMAN KELULUSAN </br>%H JAM %M MENIT %S DETIK</strong>'));
                    $("#xpengumuman").hide();
                }
            });
    </script>
    <?php
    include "../../kelulusan/controller/footer.php";
    ?>
</body>

</html>