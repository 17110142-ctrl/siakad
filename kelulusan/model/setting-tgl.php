<?php
include('../config/koneksi.php');
$que = mysqli_query($koneksi, "SELECT * FROM aplikasi WHERE id_aplikasi='1'");
$hsl = mysqli_fetch_array($que);
$timestamp = strtotime($hsl['tgl_pengumuman']);
// menghapus tags html (mencegah serangan jso pada halaman index)
$sekolah = strip_tags($hsl['sekolah']);
$tahun = strip_tags($hsl['tp']);
$tgl_pengumuman = strip_tags($hsl['tgl_pengumuman']);
//echo $timestamp;

?>
<hr>
<div class="alert alert-dismissable alert-success">
    <h4 align=" center"><b>
            <script language="JavaScript">
                TargetDate = "<?= $tgl_pengumuman ?>";
                BackColor = "";
                ForeColor = "green";
                CountActive = true;
                CountStepper = -1;
                LeadingZero = true;
                DisplayFormat = "WAKTU PENGUMUMAN KELULUSAN </br>%%D%% : %%H%% : %%M%% : %%S%%";
                FinishMessage = "PENGUMUMAN STATUS KELULUSAN</br>TAHUN <?= $tahun ?> DIBUKA";
            </script>
            <script language="JavaScript" src="js/countdown.js"></script>
        </b></h4>
</div>
<hr>