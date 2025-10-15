<?php
defined('APK') or exit('No Access');
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpesL = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L'"));
$jpesP = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P'"));
?>

<?php if ($ac == '') : ?>
    <div class="row">
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body edis">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">face</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">SISWA LAKI-LAKI</span>
                            <span class="widget-stats-amount"><?= $jpesL; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body edis">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-warning">
                            <i class="material-icons-outlined">face</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">SISWA PEREMPUAN</span>
                            <span class="widget-stats-amount"><?= $jpesP; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body edis">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-success">
                            <i class="material-icons-outlined">people</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">TOTAL SISWA</span>
                            <span class="widget-stats-amount"><?= $jsiswa ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">DATA SISWA</h5>
                    <?php if ($user['level'] == 'admin') : ?>
                        <div class="pull-right">
                            <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('tambah') ?>" class='btn btn-primary' data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Siswa"><i class="material-icons">add</i>Tambah</a>
                            <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('upload') ?>" class='btn btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Upload Foto"><i class="material-icons">upload</i>Foto</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="card-box table-responsive">
                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NIS</th>
                                    <th>NAMA SISWA</th>
                                    <th>ROMBEL</th>
                                    <th>USERNAME</th>
                                    <th>PASSWORD</th>
                                    <th>JK</th>
                                    <th>AGAMA</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username<>''");
                                while ($data = mysqli_fetch_assoc($query)) :
                                    $no++;
                                ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $data['nis'] ?></td>
                                        <td><?= $data['nama'] ?></td>
                                        <td><?= $data['kelas'] ?></td>
                                        <td><?= $data['username'] ?></td>
                                        <td><?= $data['password'] ?></td>
                                        <td><?= $data['jk'] ?></td>
                                        <td><?= $data['agama'] ?></td>
                                        <td>
                                            <a href="?pg=<?= enkripsi('peserta') ?>&ac=<?= enkripsi('edit') ?>&ids=<?= enkripsi($data['id_siswa']) ?>"> <button class='btn btn-sm btn-success' data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="material-icons">edit</i></button></a>
                                            <?php if ($user['level'] == 'admin') : ?>
                                                <button data-id="<?= $data['id_siswa'] ?>" class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#datatable1').on('click', '.hapus', function() {
            var id = $(this).data('id');
            swal({
                title: 'Yakin hapus data?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'siswa/edit.php?pg=hapus',
                        method: "POST",
                        data: 'id=' + id,
                        success: function(data) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    });
                }
            })
        });
    </script>

<?php elseif ($ac == enkripsi('upload')) : ?>
    <div class="row">
        <div class='col-md-8'>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">STATUS FOTO SISWA</h5>
                    <a href="?pg=<?= enkripsi('peserta') ?>" class="btn btn-sm btn-secondary"><i class="material-icons me-1" style="font-size: 16px;">arrow_back</i>Kembali</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="datatable1">
                            <thead>
                                <tr>
                                    <th width="5%">NO</th>
                                    <th>NAMA SISWA</th>
                                    <th width="25%">FOTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                $query_siswa = mysqli_query($koneksi, "SELECT id_siswa, nama, foto FROM siswa ORDER BY nama ASC");
                                while ($siswa = mysqli_fetch_assoc($query_siswa)) :
                                    $no++;
                                    $foto_display = '<span class="badge bg-danger">Masih Kosong</span>';
                                    $foto_path = '';

                                    // PERUBAHAN: Logika menampilkan foto disederhanakan
                                    if (!empty($siswa['foto'])) {
                                        $path_check = '../images/fotosiswa/' . $siswa['foto'];
                                        if (file_exists($path_check)) {
                                            $foto_path = $path_check;
                                        }
                                    }

                                    if ($foto_path != '') {
                                        $foto_display = '
                                            <div class="d-flex align-items-center">
                                                <img src="' . $foto_path . '?' . time() . '" alt="' . $siswa['nama'] . '" width="80" class="me-2 rounded">
                                                <button class="btn btn-sm btn-outline-danger hapus-foto" data-id="' . $siswa['id_siswa'] . '" data-nama="' . htmlspecialchars($siswa['nama'], ENT_QUOTES) . '">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>';
                                    }
                                ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $siswa['nama']; ?></td>
                                        <td><?= $foto_display; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">UPLOAD FOTO</h5>
                </div>
                <div class="card-body">
                    <form id='formfoto'>
                        <label class="form-label">Pilih File</label>
                        <p class="form-text text-muted" style="margin-top: -5px; margin-bottom: 10px;">
                            Bisa pilih banyak foto (.jpg/.png) atau satu file .zip.
                            <br><em>Nama file gambar harus sesuai nama lengkap siswa.</em>
                        </p>
                        <div class="input-group mb-3">
                            <input type='file' name='file[]' class='form-control' required='true' accept=".zip,.jpg,.jpeg,.png" multiple />
                        </div>
                        <button type="submit" class="btn btn-success w-100"><i class="material-icons">upload</i> UPLOAD SEKARANG</button>
                    </form>
                    <hr>
                    <div id="upload-progress" style="display:none;">
                        <label id="progress-label">Mengunggah...</label>
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Fungsi Upload
        $('#formfoto').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var progressBar = $('#progress-bar');
            var progressLabel = $('#progress-label');
            var uploadProgress = $('#upload-progress');
            
            uploadProgress.show();
            progressBar.width('0%').text('0%');
            progressLabel.text('Mengunggah...');

            $.ajax({
                url: 'siswa/tfoto.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;
                            progressBar.width(percentComplete.toFixed(0) + '%');
                            progressBar.text(percentComplete.toFixed(0) + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    uploadProgress.hide();

                    if (response.gagal && response.gagal.length > 0) {
                        var errorMessages = response.gagal.join('<br>');
                        swal({
                            title: 'Beberapa Foto Gagal Diunggah!',
                            html: '<div style="text-align: left; max-height: 400px; overflow-y: auto;">' + errorMessages + '</div>',
                            type: 'error',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else if (response.sukses && response.sukses.length > 0) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Semua foto berhasil diunggah.',
                            type: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        swal({
                            title: 'Informasi',
                            text: 'Tidak ada file yang diproses atau diunggah.',
                            type: 'info',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                },
                error: function() {
                    uploadProgress.hide();
                    swal('Error!', 'Upload Gagal! Terjadi kesalahan pada server.', 'error');
                }
            });
        });

        // Fungsi Hapus Foto
        $('#datatable1').on('click', '.hapus-foto', function() {
            var id_siswa = $(this).data('id');
            var nama_siswa = $(this).data('nama');
            
            swal({
                title: 'Yakin hapus foto?',
                text: "Anda akan menghapus foto milik " + nama_siswa,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'siswa/tfoto.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id_siswa: id_siswa
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'success') {
                                swal('Berhasil!', response.message, 'success').then(function() {
                                    window.location.reload();
                                });
                            } else {
                                swal('Gagal!', response.message, 'error');
                            }
                        },
                        error: function() {
                            swal('Error!', 'Gagal menghubungi server.', 'error');
                        }
                    });
                }
            });
        });
    });
    </script>

<?php elseif ($ac == enkripsi('edit')) : ?>
    <?php
    $ids = dekripsi($_GET['ids']);
    $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $ids]);
    if ($siswa['jk'] == 'L') {
        $kel = 'Laki-laki';
    } else {
        $kel = 'Perempuan';
    }
    ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">EDIT DATA</h5>
                    <a href="?pg=<?= enkripsi('peserta') ?>" class="btn btn-sm btn-secondary"><i class="material-icons me-1" style="font-size: 16px;">arrow_back</i>Kembali</a>
                </div>
                <div class="card-body">
                    <form id="formedit" action='' method='post' class="row g-3" enctype='multipart/form-data'>
                        <input type='hidden' name='ids' value="<?= $siswa['id_siswa'] ?>" class='form-control' />
                        <div class="col-md-12">
                            <label class="form-label bold">NAMA LENGKAP</label>
                            <input type='text' name='nama' value="<?= $siswa['nama'] ?>" class='form-control' required="true" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">NIS</label>
                            <input type='text' name='nis' value="<?= $siswa['nis'] ?>" class='form-control' required="true" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">NISN</label>
                            <input type='text' name='nisn' value="<?= $siswa['nisn'] ?>" class='form-control' required="true" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">TINGKAT</label>
                            <select class="form-select" name="level" required>
                                <option value="<?= $siswa['level'] ?>"><?= $siswa['level'] ?></option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">ROMBEL</label>
                            <select class="form-select" name="kelas" required>
                                <option value="<?= $siswa['kelas'] ?>"><?= $siswa['kelas'] ?></option>
                                <?php
                                $kls = mysqli_query($koneksi, "SELECT kelas FROM siswa GROUP BY kelas");
                                while ($kelas = mysqli_fetch_array($kls)) {
                                    echo "<option value='$kelas[kelas]'>$kelas[kelas]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">AGAMA</label>
                            <select class="form-select" name="agama" required="true">
                                <option value="<?= $siswa['agama'] ?>"><?= $siswa['agama'] ?></option>
                                <option value='' disabled>-- Pilih Agama --</option>
                                <option value='Islam'>Islam</option>
                                <option value='Kristen'>Kristen</option>
                                <option value='Katholik'>Katholik</option>
                                <option value='Hindu'>Hindu</option>
                                <option value='Budha'>Budha</option>
                                <option value='Konghucu'>Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">JK</label>
                            <select class="form-select" name="jk" required="true">
                                <option value="<?= $siswa['jk'] ?>"><?= $kel ?></option>
                                <option value='' disabled>-- Pilih JK --</option>
                                <option value='L'>Laki-laki</option>
                                <option value='P'>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">JURUSAN</label>
                            <select class="form-select" name="pk" required="true">
                                <option value="<?= $siswa['jurusan'] ?>"><?= $siswa['jurusan'] ?></option>
                                <?php
                                $lev = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
                                while ($level = mysqli_fetch_array($lev)) {
                                    echo "<option value='$level[jurusan]'>$level[jurusan]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">USERNAME</label>
                            <input type='text' name='username' value="<?= $siswa['username'] ?>" class='form-control' readonly />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">PASSWORD</label>
                            <input type='text' name='password' value="<?= $siswa['password'] ?>" class='form-control' required="true" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">NO WHATSAPP ( Jika Ada )</label>
                            <input type='number' name='nowa' value="<?= $siswa['nowa'] ?>" class='form-control' />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">Tempat Lahir</label>
                            <input type='text' name='tlahir' value="<?= $siswa['t_lahir'] ?>" class='form-control' />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">Tgl Lahir ( contoh: 21 Agustus 2007 )</label>
                            <input type='text' name='tgllahir' value="<?= $siswa['tgl_lahir'] ?>" class='form-control' />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">FOTO ( Jika Ada )</label>
                            <input type='file' name='file' class='form-control' />
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card widget widget-payment-request">
                <div class="card-header">
                    <h5 class="card-title text-center"><?= strtoupper($siswa['nama']); ?></h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <?php
                            $foto_path = '../images/user.png'; // Default
                            // PERUBAHAN: Logika menampilkan foto disederhanakan
                            if(!empty($siswa['foto'])){
                                $path_check = '../images/fotosiswa/' . $siswa['foto'];
                                if (file_exists($path_check)) {
                                    $foto_path = $path_check;
                                }
                            }
                        ?>
                        <img src="<?= $foto_path ?>?t=<?= time() ?>" alt="Foto Siswa" class="img-fluid rounded" style="max-height: 250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#formedit').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'siswa/edit.php?pg=edit',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
            return false;
        });
    </script>

<?php elseif ($ac == enkripsi('tambah')) : ?>
    <?php
    $username = '';
    $password = '';
    ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">TAMBAH SISWA</h5>
                    <a href="?pg=<?= enkripsi('peserta') ?>" class="btn btn-sm btn-secondary"><i class="material-icons me-1" style="font-size: 16px;">arrow_back</i>Kembali</a>
                </div>
                <div class="card-body">
                    <form id="formsiswa" action='' method='post' class="row g-3" enctype='multipart/form-data'>
                        <div class="col-md-12">
                            <label class="form-label bold">NAMA LENGKAP</label>
                            <input type='text' name='nama' class='form-control' required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">NIS</label>
                            <input type='text' name='nis' class='form-control' required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">NISN</label>
                            <input type='text' name='nisn' class='form-control' required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">TINGKAT</label>
                            <select class="form-select" name="level" required>
                                <option value='' selected>-- Pilih Tingkat --</option>
                                <?php
                                $lev = mysqli_query($koneksi, "SELECT level FROM siswa GROUP BY level");
                                while ($level = mysqli_fetch_array($lev)) {
                                    echo "<option value='$level[level]'>$level[level]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">ROMBEL</label>
                            <select class="form-select" name="kelas" required>
                                <option value='' selected>-- Pilih Rombel --</option>
                                <?php
                                $kls = mysqli_query($koneksi, "SELECT kelas FROM kelas");
                                while ($k = mysqli_fetch_array($kls)) {
                                    echo "<option value='$k[kelas]'>$k[kelas]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">AGAMA</label>
                            <select class="form-select" name="agama" required>
                                <option value='' selected>-- Pilih Agama --</option>
                                <option value='Islam'>Islam</option>
                                <option value='Kristen'>Kristen</option>
                                <option value='Katholik'>Katholik</option>
                                <option value='Hindu'>Hindu</option>
                                <option value='Budha'>Budha</option>
                                <option value='Konghucu'>Konghucu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">JK</label>
                            <select class="form-select" name="jk" required>
                                <option value='' selected>-- Pilih JK --</option>
                                <option value='L'>Laki-laki</option>
                                <option value='P'>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">JURUSAN</label>
                            <select class="form-select" name="pk" required>
                                <option value='' selected>-- Pilih Jurusan --</option>
                                <?php
                                $jur = mysqli_query($koneksi, "SELECT jurusan FROM siswa GROUP BY jurusan");
                                while ($pk = mysqli_fetch_array($jur)) {
                                    echo "<option value='$pk[jurusan]'>$pk[jurusan]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">USERNAME</label>
                            <input type='text' name='username' value="<?= $username; ?>" class='form-control' readonly />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label bold">PASSWORD</label>
                            <input type='text' name='password' value="<?= $password; ?>" class='form-control' readonly />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">NO WHATSAPP (Jika Ada)</label>
                            <input type='number' name='nowa' class='form-control' />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label bold">FOTO (Jika Ada)</label>
                            <input type='file' name='file' class='form-control' />
                        </div>
                        <div class="col-md-12">
                            <button type="submit" id="btnSubmit" class="btn btn-primary" disabled>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card widget widget-payment-request">
                <div class="card-header">
                    <h5 class="card-title text-center">FOTO SISWA</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="../images/user.png" alt="Foto Siswa" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Submit Form
        $('#formsiswa').submit(function(e) {
            e.preventDefault();
            var data = new FormData(this);
            $.ajax({
                type: 'POST',
                url: 'siswa/tambah.php',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
            return false;
        });

        // Fungsi untuk generate serial random
        function generateSerial() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var serial = '';
            for (var i = 0; i < 3; i++) {
                serial += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return serial;
        }

        // Update Username dan Password saat NIS diisi
        $('input[name="nis"]').on('input', function() {
            var nis = $(this).val();
            if (nis !== '') {
                var serial = generateSerial();
                $('input[name="username"]').val(nis);
                $('input[name="password"]').val(nis + '-' + serial);
                $('#btnSubmit').prop('disabled', false); // Aktifkan tombol
            } else {
                $('input[name="username"]').val('');
                $('input[name="password"]').val('');
                $('#btnSubmit').prop('disabled', true); // Disable tombol
            }
        });
    </script>
<?php endif; ?>
