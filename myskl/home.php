<?php 
$lulus = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE keterangan='1'"));
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl"));
$tlulus = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE keterangan='0' AND level='$skl[tingkat]'"));
?>
<div class="container-fluid">
    <div class="row g-3">
        <!-- Info Widget -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card widget widget-info">
                <div class="card-body">
                    <div class="widget-info-container text-center">
                        <div class="widget-info-image mb-3" style="background: url('../images/<?= $setting['logo'] ?>') no-repeat center; background-size: contain; height: 100px;"></div>
                        <h6 class="widget-info-title">SIAKAD</h6>
                        <p class="widget-info-text"><b>SISTEM INFORMASI AKADEMIK</b></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kepala Sekolah Widget -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex align-items-center">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons">storage</i>
                        </div>
                        <div class="widget-stats-content ms-3">
                            <span class="widget-stats-title">Kepala Sekolah</span>
                            <span class="widget-stats-info"><?= $setting['kepsek']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Telegram Widget -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex align-items-center">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons">home</i>
                        </div>
                        <div class="widget-stats-content ms-3">
                            <span class="widget-stats-title">Telegram</span>
                            <span class="widget-stats-info">
                                <a href="https://www.esandik.my.id" target="_blank" class="btn btn-sm btn-link">
                                    <b>Website</b>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lulus Widget -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex align-items-center">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons">school</i>
                        </div>
                        <div class="widget-stats-content ms-3">
                            <span class="widget-stats-title">LULUS</span>
                            <span class="widget-stats-amount"><?= $lulus; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tidak Lulus Widget -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex align-items-center">
                        <div class="widget-stats-icon widget-stats-icon-danger">
                            <i class="material-icons">school</i>
                        </div>
                        <div class="widget-stats-content ms-3">
                            <span class="widget-stats-title">TIDAK LULUS</span>
                            <span class="widget-stats-amount"><?= $tlulus; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom:10px;">
  <div class=" alert alert-dismissable alert-danger" style="margin-bottom: 20px;">
    <h4 align="left" style="text-transform: uppercase;">
      <script language=JavaScript src="../js/almanak.js"></script>
      <span class="style1">-</span>
      <script language=JavaScript>
        var d = new Date();
        var h = d.getHours();
        if (h < 11) {
          document.write('SELAMAT PAGI');
        } else {
          if (h < 15) {
            document.write('SELAMAT SIANG');
          } else {
            if (h < 19) {
              document.write('SELAMAT SORE');
            } else {
              if (h <= 23) {
                document.write('SELAMAT MALAM');
              }
            }
          }
        }
      </script>
      - <b>ADMINISTRATOR SISTEM INFORMASI KELULUSAN!</b>
    </h4>
  </div>
  </div>

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
        <h4 align="center"><b>UPDATE DATA PROFIL SEKOLAH</b></h4>
    </div>
    <form class="form-horizontal" method="post">
        <?php
        include('../config/koneksi.php');
        if (isset($_REQUEST['submit'])) {
            $cfgSekolah = $_REQUEST['nm_sekolah'];
            $cfgAplikasi = $_REQUEST['nm_aplikasi'];
            $cfgTahun = $_REQUEST['tahun'];
            $cfgTgl = $_REQUEST['cfgTanggal'] . ' ' . $_REQUEST['cfgJam'];

            $qCfg = "UPDATE tbl_profil SET nm_sekolah='$cfgSekolah', nm_aplikasi='$cfgAplikasi', tahun='$cfgTahun',tgl_pengumuman='$cfgTgl' WHERE id_profil='1'";
            $upCfg = mysqli_query($koneksi, $qCfg);
            sleep(2);
        }

        $q = mysqli_query($koneksi, "SELECT * from aplikasi where id_aplikasi='1'");
        while ($r = mysqli_fetch_array($q)) {
        ?>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>NAMA SEKOLAH</b></span>
                <input type="text" class="form-control" name="sekolah" value="<?php echo $r['sekolah']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>NAMA APLIKASI</b></span>
                <input type="text" class="form-control" name="aplikasi" value="<?php echo $r['aplikasi']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>TAHUN PELAJARAN</b></span>
                <input type="text" class="form-control" name="tahun" min="2021" max="2030" value="<?php echo $r['tp']; ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>TANGGAL PENGUMUMAN</b></span>
                <input type="date" class="form-control" name="Tanggal" value="<?= date('Y-m-d', strtotime($r['tgl_pengumuman'])) ?>" readonly size="50" style="text-align: left;">
            </div>
            </br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1" style="min-width: 200px;"><b>JAM PENGUMUMAN</b></span>
                <input type="time" class="form-control" name="Jam" value="<?= date('H:i', strtotime($r['tgl_pengumuman'])) ?>" readonly size="50" style="text-align: left;">
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
