<?php
// Pastikan definisi APK dan fungsi enkripsi/dekripsi, fetch sudah ada
defined('APK') or exit('No Access');

// Pastikan koneksi database ($koneksi) dan variabel $user, $setting sudah tersedia
// Contoh placeholder jika belum didefinisikan (HANYA UNTUK UJI COBA, sesuaikan dengan lingkungan Anda)
/*
if (!isset($koneksi)) {
    // Sesuaikan dengan detail koneksi database Anda
    $koneksi = mysqli_connect("localhost", "username", "password", "database_name");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
    }
}
if (!function_exists('enkripsi')) {
    function enkripsi($str) { return base64_encode($str); }
    function dekripsi($str) { return base64_decode($str); }
}
if (!function_exists('fetch')) {
    function fetch($koneksi, $table, $conditions) {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = '" . mysqli_real_escape_string($koneksi, $value) . "'";
        }
        $query = "SELECT * FROM $table WHERE " . implode(' AND ', $where) . " LIMIT 1";
        $result = mysqli_query($koneksi, $query);
        return mysqli_fetch_assoc($result);
    }
}
$user = ['level' => 'admin', 'id_user' => '1']; // Contoh user admin
$setting = ['semester' => 'Ganjil']; // Contoh setting semester
*/

?>
            
<?php if ($ac == '') : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">INPUT TUJUAN PEMBELAJARAN </h5>
                </div>
                <div class="card-body">
                    <div class="card-box table-responsive">
                        <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
                            <thead>
                                <tr>
                                    <th width="5%">NO</th> 
                                    <th>SMT</th>
                                    <th>TKT</th>                                                                                     
                                    <th>MATA PELAJARAN</th>
                                    <th>GURU PENGAMPU</th>
                                    <th>JML LM</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 0;
                                if ($user['level'] == 'admin') {
                                    $query = mysqli_query($koneksi, "SELECT tingkat, mapel FROM jadwal_mapel WHERE kuri='2' GROUP BY tingkat, mapel");
                                } elseif ($user['level'] == 'guru') {
                                    $query = mysqli_query($koneksi, "SELECT tingkat, mapel FROM jadwal_mapel WHERE guru='$user[id_user]' AND kuri='2' GROUP BY tingkat, mapel");
                                }

                                while ($data = mysqli_fetch_array($query)) :
                                    $tingkat = $data['tingkat'];
                                    $mapel = $data['mapel'];

                                    $mapel_data = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel]);

                                    // Ambil semua guru yang mengajar kombinasi tingkat + mapel
                                    $guruQuery = mysqli_query($koneksi, "SELECT DISTINCT guru FROM jadwal_mapel WHERE tingkat='$tingkat' AND mapel='$mapel' AND kuri='2'");
                                    $guru_nama = [];
                                    while ($g = mysqli_fetch_array($guruQuery)) {
                                        $guruData = fetch($koneksi, 'users', ['id_user' => $g['guru']]);
                                        $guru_nama[] = $guruData['nama'];
                                    }

                                    $list_guru = implode(', ', $guru_nama);

                                    // Hitung jumlah LM
                                    $jumdes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tujuan WHERE mapel='$mapel' AND level='$tingkat' AND smt='$setting[semester]'"));

                                    $no++;
                                ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><h5><span class="badge badge-dark"><?= $setting['semester']; ?></span></h5></td>
                                        <td><?= $tingkat ?></td>
                                        <td><?= $mapel_data['nama_mapel'] ?></td>
                                        <td><?= $list_guru ?></td>
                                        <td><h5><span class="badge badge-success"><?= $jumdes; ?></span></h5></td>
                                        <td class="text-center">
                                            <?php
                                            // Tampilkan tombol hanya jika user adalah guru dan dia salah satu pengampu, atau jika user adalah admin
                                            $can_input = false;
                                            $guru_id = null; // Inisialisasi guru_id

                                            if ($user['level'] == 'admin') {
                                                $guru_input = mysqli_fetch_array(mysqli_query($koneksi, "SELECT guru FROM jadwal_mapel WHERE tingkat='$tingkat' AND mapel='$mapel' LIMIT 1"));
                                                $guru_id = $guru_input['guru'];
                                                $can_input = true;
                                            } else {
                                                $guru_check = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE tingkat='$tingkat' AND mapel='$mapel' AND guru='$user[id_user]' AND kuri='2'");
                                                if (mysqli_num_rows($guru_check) > 0) {
                                                    $guru_id = $user['id_user'];
                                                    $can_input = true;
                                                }
                                            }

                                            if ($can_input && $guru_id): // Pastikan guru_id tidak null
                                            ?>
                                            <a href="?pg=<?= enkripsi('tujuan') ?>&ac=<?= enkripsi('input') ?>&l=<?= enkripsi($tingkat) ?>&m=<?= enkripsi($mapel) ?>&g=<?= enkripsi($guru_id) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Materi"><i class="material-icons">select_all</i></a>
                                            
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
                     <?php elseif ($ac == enkripsi('input')): ?>
<?php
$mapel = dekripsi($_GET['m']);
$tingkat = dekripsi($_GET['l']);
$guru = dekripsi($_GET['g']);
$jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tujuan where mapel='$mapel' and level ='$tingkat' and guru='$guru' and smt='$setting[semester]'"));
$jumlah = $jml + 1;
$mpl = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel]);
$peg = fetch($koneksi, 'users', ['id_user' => $guru]);
?>

<!-- Input Form -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="bold"><?= $mpl['kode'] ?></h5>
            </div>
            <div class="card-body">
                <!-- Tombol Import Excel Baru -->
                                            <a href="?pg=<?= enkripsi('tujuan') ?>&ac=<?= enkripsi('import_excel') ?>&l=<?= enkripsi($tingkat) ?>&m=<?= enkripsi($mapel) ?>&g=<?= enkripsi($guru_id) ?>" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Import TP dari Excel"><i class="material-icons">file_upload</i>Import TP</a>
                <div class="card-box table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>TKT</th>
                                <th>SMT</th>
                                <th>LM</th>
                                <th>TP</th>
                                <th>TUJUAN PEMBELAJARAN</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $query = mysqli_query($koneksi, "SELECT * FROM tujuan WHERE guru='$guru' AND mapel='$mapel' AND level='$tingkat' AND smt='$setting[semester]'");
                        while ($data = mysqli_fetch_array($query)) :
                            $no++;
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $data['level'] ?></td>
                            <td><?= $data['smt'] ?></td>
                            <td><?= $data['lm'] ?></td>
                            <td><?= $data['tp'] ?></td>
                            <td><?= $data['tujuan'] ?></td>
                            <td>
                                <a href="?pg=<?= enkripsi('tujuan') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($data['idt']) ?>" class="btn btn-sm btn-primary"><i class="material-icons">edit</i></a>
                                <button data-id="<?= $data['idt'] ?>" class="hapus btn btn-sm btn-danger"><i class="material-icons">delete</i></button>
                            </td>
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
                <h5 class="bold">INPUT TUJUAN PEMBELAJARAN</h5>
            </div>
            <div class="card-body">
                <form id="formdeskrip">
                    <input type="hidden" name="mapel" value="<?= $mapel ?>">
                    <input type="hidden" name="guru" value="<?= $guru ?>">
                    <input type="hidden" name="level" value="<?= $tingkat ?>">
                    <input type="hidden" name="tp" value="<?= $jumlah ?>">
                    <label class="bold">Semester</label>
                    <div class="input-group mb-1">
                        <select name="smt" class="form-select" required>
                            <option value="1" <?= $setting['semester'] == '1' ? 'selected' : '' ?>>Semester 1</option>
                            <option value="2" <?= $setting['semester'] == '2' ? 'selected' : '' ?>>Semester 2</option>
                        </select>
                    </div>

                    <label class="bold">LM</label>
                    <select name="lm" class="form-select" onchange="changeValue(this.value)" required>
                        <option value="">Pilih LM</option>
                        <?php
                        $sql = mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$mapel' AND level='$tingkat' AND smt='$setting[semester]'");
                        $jsArray = "var prdName = new Array();\n";
                        while ($data = mysqli_fetch_array($sql)) {
                            echo '<option value="' . $data['id'] . '">' . $data['lm'] . '</option>';
                            $jsArray .= "prdName['" . $data['id'] . "'] = {materi:'" . addslashes($data['materi']) . "'};\n";
                        }
                        ?>
                    </select>

                    <label class="bold">Materi</label>
                    <input type="text" name="materi" id="materi" class="form-control" readonly>

                    <label class="bold">Tujuan (maximal 200 karakter)</label>
                    <textarea name="tujuan" class="form-control" rows="5" maxlength="200" required></textarea>

                    <div id="count">
                        <span id="current_count">0</span>
                        <span id="maximum_count">/ 200</span>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
<?= $jsArray; ?>
function changeValue(x) {
    document.getElementById('materi').value = prdName[x].materi;
}

$('textarea[name="tujuan"]').keyup(function () {
    var characterCount = $(this).val().length;
    $('#current_count').text(characterCount);
});

$('#formdeskrip').submit(function (e) {
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
        type: 'POST',
        url: 'proto/tdeskrip.php?pg=tuju',
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
            // Menggunakan SweetAlert sebagai pengganti alert/confirm
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data tujuan pembelajaran berhasil disimpan.',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menyimpan data.',
            });
        }
    });
});

$('#datatable1').on('click', '.hapus', function () {
    var id = $(this).data('id');
    Swal.fire({ // Menggunakan Swal.fire sebagai pengganti swal
        title: 'Yakin hapus data?',
        text: "Data tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('proto/tdeskrip.php?pg=hapustp', { id: id }, function (data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Data tujuan pembelajaran berhasil dihapus.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menghapus data.',
                });
            });
        }
    });
});
</script>
<?php elseif ($ac == enkripsi('import_excel')): ?>
<?php
$mapel = dekripsi($_GET['m']);
$tingkat = dekripsi($_GET['l']);
$guru_param_id = dekripsi($_GET['g']); // Mengubah nama variabel agar tidak ambigu

$mpl = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel]);

$nama_guru_tampil = ''; // Variabel untuk menyimpan nama guru yang akan ditampilkan

// Logika untuk menentukan nama guru pengampu yang akan ditampilkan
if ($user['level'] == 'admin') {
    // Jika admin, ambil guru pertama yang mengajar mapel/tingkat ini
    $guru_input = mysqli_fetch_array(mysqli_query($koneksi, "SELECT guru FROM jadwal_mapel WHERE tingkat='$tingkat' AND mapel='$mapel' LIMIT 1"));
    if ($guru_input) {
        $peg = fetch($koneksi, 'users', ['id_user' => $guru_input['guru']]);
        $nama_guru_tampil = $peg['nama'] ?? 'Guru Tidak Ditemukan';
    } else {
        $nama_guru_tampil = 'Tidak Ada Guru Terdaftar';
    }
} elseif ($user['level'] == 'guru') {
    // Jika guru, ambil nama guru yang sedang login
    $peg = fetch($koneksi, 'users', ['id_user' => $user['id_user']]);
    $nama_guru_tampil = $peg['nama'] ?? 'Nama Guru Tidak Ditemukan';
}

// Pastikan $guru (ID guru yang akan disimpan/diproses) juga konsisten
// Jika Anda ingin ID guru yang akan digunakan untuk import adalah yang dikirim melalui URL
// maka variabel $guru tetap menggunakan $guru_param_id
$guru_for_form_submission = $guru_param_id; 

// Jika $guru_param_id kosong atau tidak valid, dan user adalah guru, gunakan id_user guru tersebut
if (empty($guru_for_form_submission) && $user['level'] == 'guru') {
    $guru_for_form_submission = $user['id_user'];
}

?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5 class="bold">IMPORT TUJUAN PEMBELAJARAN DARI EXCEL</h5>
            </div>
            <div class="card-body">
                <p>Mata Pelajaran: <strong><?= $mpl['nama_mapel'] ?></strong></p>
                <p>Tingkat: <strong><?= $tingkat ?></strong></p>
                <p>Guru Pengampu: <strong><?= $nama_guru_tampil ?></strong></p>
                <hr>
                <form id="formImportExcel" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="mapel" value="<?= $mapel ?>">
                    <input type="hidden" name="guru" value="<?= $guru_for_form_submission ?>"> 
                    <input type="hidden" name="level" value="<?= $tingkat ?>">
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="smt" class="form-select" required>
                            <option value="1" <?= $setting['semester'] == '1' ? 'selected' : '' ?>>Semester 1</option>
                            <option value="2" <?= $setting['semester'] == '2' ? 'selected' : '' ?>>Semester 2</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fileExcel" class="form-label">Pilih File Excel (.xlsx atau .xls)</label>
                        <input class="form-control" type="file" id="fileExcel" name="fileExcel" accept=".xlsx, .xls" required>
                    </div>
                    <p class="text-muted">Pastikan format file Excel sesuai dengan contoh:
                        <a href="../../mykbm/proto/generate_template.php?m=<?= enkripsi($mapel) ?>&l=<?= enkripsi($tingkat) ?>" download>Download Template</a>
                    </p>
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="?pg=<?= enkripsi('tujuan') ?>" class="btn btn-secondary">Kembali</a>
                </form>
                <div id="import_status" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
// ... (script JavaScript Anda tidak perlu diubah, kecuali jika Anda ingin menyesuaikan redirect setelah import)
$(document).ready(function() {
    $('#formImportExcel').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'proto/tdeskrip.php?pg=import_tp', // Arahkan ke file proses import
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#import_status').html('<div class="alert alert-info">Mengimport data, mohon tunggu...</div>');
            },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            // Redirect kembali ke halaman input TP yang sudah ada
                            // Penting: pastikan parameter URL ini benar setelah import
                            // Gunakan guru_for_form_submission yang sudah dipastikan nilainya valid
                            window.location.href = '?pg=<?= enkripsi('tujuan') ?>&ac=<?= enkripsi('input') ?>&l=<?= enkripsi($tingkat) ?>&m=<?= enkripsi($mapel) ?>&g=<?= enkripsi($guru_for_form_submission) ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message,
                        });
                        $('#import_status').html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server!',
                        text: 'Respon dari server tidak valid. Silakan coba lagi.',
                    });
                    console.error("JSON Parse Error:", e, "Response:", response);
                    $('#import_status').html('<div class="alert alert-danger">Respon tidak valid dari server.</div>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengunggah file.',
                });
                $('#import_status').html('<div class="alert alert-danger">Terjadi kesalahan saat mengunggah file.</div>');
            }
        });
    });
});
</script>
<?php endif; ?>
