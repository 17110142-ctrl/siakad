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

        <form action="proses_upload.php" method="post" enctype="multipart/form-data">
    <label>Nama File:</label>
    <input type="text" name="nama_file" required class="form-control"><br>

    <label>Kategori:</label>
    <select name="kategori" required class="form-control">
        <option value="Surat Masuk">Surat Masuk</option>
        <option value="Surat Keluar">Surat Keluar</option>
        <option value="Surat Tugas">Surat Tugas</option>
    </select><br>

    <label>Upload File (PDF/DOCX):</label>
    <input type="file" name="file" required class="form-control"><br>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>
