<?php
require('../config/koneksi.php');

// Ambil semua data siswa + kelasnya
$query = mysqli_query($koneksi, "
    SELECT siswa.id AS id_siswa, siswa.nama, siswa.nis, kelas.level, kelas.kelas, kelas.id AS id_kelas
    FROM siswa
    LEFT JOIN kelas ON siswa.id_kelas = kelas.id
    ORDER BY kelas.level, kelas.kelas, siswa.nama
");

// Ambil daftar kelas untuk opsi kenaikan
$kelas_result = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY level ASC, kelas ASC");
$daftar_kelas = [];
while ($k = mysqli_fetch_assoc($kelas_result)) {
    $daftar_kelas[] = $k;
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title">KENAIKAN KELAS SISWA</h5></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover" style="font-size:12px">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>ROMBEL</th>
                                <th>NAIK KE KELAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($data = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $data['nama'] ?></td>
                                    <td><?= $data['level'] . ' ' . $data['kelas'] ?></td>
                                    <td>
                                        <select class="form-select input-kenaikan" 
                                                data-id="<?= $data['id_siswa'] ?>"
                                                data-nama="<?= $data['nama'] ?>"
                                                data-kelas="<?= $data['level'] . ' ' . $data['kelas'] ?>">
                                            <option value="">-- Pilih Kelas Baru --</option>
                                            <?php foreach ($daftar_kelas as $kelas): ?>
                                                <option value="<?= $kelas['id'] ?>">
                                                    <?= $kelas['level'] . ' ' . $kelas['kelas'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div id="resultKenaikan" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script JS -->
<script>
$(document).ready(function () {
    if ($.fn.dataTable.isDataTable('#datatable1')) {
        $('#datatable1').DataTable().destroy();
    }

    $('#datatable1').DataTable({
        paging: false,
        searching: true,
        ordering: true,
        info: false,
        lengthChange: false,
        order: [[2, 'asc']]
    });

    $('.input-kenaikan').on('change', function () {
        let el = $(this);
        let id_siswa = el.data('id');
        let kelas_baru = el.val();
        let nama = el.data('nama');
        let kelas_lama = el.data('kelas');

        if (kelas_baru === '') return;

        $.ajax({
            type: 'POST',
            url: 'ajax/proses_kenaikan.php',
            data: {
                id_siswa: id_siswa,
                id_kelas_baru: kelas_baru
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
                        message: 'Gagal menaikkan kelas: ' + res,
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
