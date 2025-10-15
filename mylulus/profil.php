<!--script src="../js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#frm").validate({
            debug: false,
            rules: {
                nm_sekolah: "required",
                nm_aplikasi: "required",
                tahun: "required",
            },
            messages: {
                nm_sekolah: "* Nama Tidak Boleh Kosong",
                nm_aplikasi: "* Username Tidak Boleh Kosong",
                tahun: "* Password Tidak Boleh Kosong"
            },
            submitHandler: function(form) {
                // do other stuff for a valid form
                $.post('index.php?page=updateprofil', $("#frm").serialize(), function(data) {
                    $('#hasil').html(data);
                    document.frm.nm_sekolah.value = "";
                    document.frm.nm_aplikasi.value = "";
                    document.frm.tahun.value = "";
                });
            }
        });
    });
</script-->

<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
    <div class=" alert alert-dismissable alert-success">
        <h4 align="center"><b>SISTEM INFORMASI KELULUSAN</br>UPDATE DATA PROFIL SEKOLAH</b></h4>
    </div>
    <form class="form-horizontal" method="post">
        <?php
        include('../controller/config.php');
        if (isset($_REQUEST['submit'])) {
            $cfgSekolah = $_REQUEST['nm_sekolah'];
            $cfgAplikasi = $_REQUEST['nm_aplikasi'];
            $cfgTahun = $_REQUEST['tahun'];
            $cfgTgl = $_REQUEST['cfgTanggal'] . ' ' . $_REQUEST['cfgJam'];

            $qCfg = "UPDATE tbl_profil SET nm_sekolah='$cfgSekolah', nm_aplikasi='$cfgAplikasi', tahun='$cfgTahun',tgl_pengumuman='$cfgTgl' WHERE id_profil='1'";
            $upCfg = mysqli_query($koneksi, $qCfg);
            sleep(2);
        }

        $q = mysqli_query($koneksi, "SELECT * from tbl_profil where id_profil='1'");
        while ($r = mysqli_fetch_array($q)) {
        ?>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>NAMA SEKOLAH</b></span>
                <input type="text" class="form-control" name="nm_sekolah" value="<?php echo $r['nm_sekolah']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>NAMA APLIKASI</b></span>
                <input type="text" class="form-control" name="nm_aplikasi" value="<?php echo $r['nm_aplikasi']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>TAHUN PELAJARAN</b></span>
                <input type="number" class="form-control" name="tahun" min="2021" max="2030" value="<?php echo $r['tahun']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>TANGGAL PENGUMUMAN</b></span>
                <input type="date" class="form-control" name="cfgTanggal" value="<?= date('Y-m-d', strtotime($r['tgl_pengumuman'])) ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>JAM PENGUMUMAN</b></span>
                <input type="time" class="form-control" name="cfgJam" value="<?= date('H:i', strtotime($r['tgl_pengumuman'])) ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="form-group">
                <p align="left">
                    <button type="button" id="btEnable" class="btn btn-danger">EDIT</button>
                    <button type="submit" name="submit" class="btn btn-info" disabled="disabled">UPDATE</button>
                </p>
            </div>
            <script>
                $('button[name="submit"]').prop('disabled', true);
                $('#btEnable').click(function() {
                    $("input").removeAttr('readonly');
                    $('button[name="submit"]').removeAttr('disabled');
                });
            </script>

        <?php
        }
        ?>
    </form>
</div>