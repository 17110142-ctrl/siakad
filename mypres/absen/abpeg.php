<?php
defined('APK') or exit('No Access');
$tanggal = date('Y-m-d');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">INPUT DATA TIDAK HADIR PEGAWAI</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover" style="font-size:12px">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>NAMA LENGKAP</th>
                                <th>JABATAN</th>
                                <th>STATUS</th>
                                <th>INPUT</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 0;
                        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE level<>'admin' AND NOT EXISTS (
                            SELECT * FROM absensi WHERE users.id_user=absensi.idpeg AND absensi.tanggal='$tanggal')");
                        while ($data = mysqli_fetch_array($query)) :
                            $no++;
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= strtoupper($data['level']) ?></td>
                                <td><span class="badge bg-secondary">Belum Absen</span></td>
                                <td>
                                    <select class="form-select input-absen-pegawai" data-id="<?= $data['id_user'] ?>" data-nama="<?= $data['nama'] ?>" data-jabatan="<?= $data['level'] ?>">
                                        <option value="">Pilih</option>
                                        <option value="S">Sakit</option>
                                        <option value="I">Izin</option>
                                        <option value="A">Alpha</option>
                                        <option value="H">Hadir</option>
                                    </select>
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
$(document).ready(function () {
    $('.input-absen-pegawai').on('change', function () {
        var el = $(this);
        var ket = el.val();
        if (ket === '') return;

        var idpeg = el.data('id');
        var nama = el.data('nama');
        var kelas = el.data('jabatan'); // kelas = jabatan pegawai

        $.ajax({
            type: 'POST',
            url: 'absen/tabsen.php?pg=pegawai',
            data: {
                idpeg: idpeg,
                ket: ket
            },
            beforeSend: function () {
                el.prop('disabled', true);
                el.after('<span class="spinner-border spinner-border-sm text-primary ms-2" role="status"></span>');
            },
            success: function (res) {
                if (res.trim() === 'OK') {
                    el.closest('tr').fadeOut(500);
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: 'Gagal menyimpan absen: ' + res,
                        position: 'topRight'
                    });
                    el.prop('disabled', false);
                    el.next('.spinner-border').remove();
                }
            },
            error: function () {
                iziToast.error({
                    title: 'Error',
                    message: 'Koneksi ke server gagal',
                    position: 'topRight'
                });
                el.prop('disabled', false);
                el.next('.spinner-border').remove();
            }
        });
    });
});
</script>
