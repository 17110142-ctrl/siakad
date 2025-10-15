<?php
defined('APK') or exit('No Access');
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">JADWAL KBM</h5>
            </div>
            <div class="card-body">
                <div class="card-box table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>                                               
                                <th>HARI</th>
                                <th>TKT - KELAS</th>
                                <th>MATA PELAJARAN</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            if ($_SESSION['level'] == 'guru') {
                                $id_guru = $_SESSION['id_user'];
                                $query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE guru='$id_guru'"); 
                            } else {
                                $query = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel"); 
                            }

                            while ($data = mysqli_fetch_array($query)) :
                                $harix = fetch($koneksi,'m_hari',['inggris'=>$data['hari']]);
                                $mapelx = fetch($koneksi,'mata_pelajaran',['id'=>$data['mapel']]);
                                $guru = fetch($koneksi,'users',['id_user'=>$data['guru']]);
                                $kuri = fetch($koneksi,'m_kurikulum',['idk'=>$data['kuri']]);
                                $no++;
                            ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $harix['hari'] ?></td>
                                <td><h5><span class="badge badge-dark"><?= $data['tingkat'] ?></span> <span class="badge badge-primary"> <?= $data['kelas'] ?></span></h5></td>
                                <td><?= $mapelx['nama_mapel'] ?><br><span class="badge badge-secondary"><?= $guru['nama'] ?></span> <span class="badge badge-info"><?= $kuri['nama_kurikulum'] ?></span></td>
                                <td>
                                    <button data-id="<?= $data['id_jadwal'] ?>" class="hapus btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"><i class="material-icons">delete</i> </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($ac == '') : ?>
    <div class="col-md-4">
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">INPUT JADWAL KBM</h5>
            </div>
            <div class="card-body">
                <div class="widget-payment-request-container">
                    <div class="widget-payment-request-author">
                        <div class="avatar m-r-sm">
                            <img src="../images/guru.png" alt="">
                        </div>
                        <div class="widget-payment-request-author-info">
                            <span class="widget-payment-request-author-name">Jadwal KBM</span>
                            <span class="widget-payment-request-author-about"><?= $setting['sekolah'] ?></span>
                        </div>
                    </div>

                    <div class="widget-payment-request-info m-t-md">
                        <form id='formguru'>    
                            <label class="bold">Mata Pelajaran</label>
                            <div class="input-group mb-1">
                                <select name='mapel' class='form-select' style='width:100%' required>
                                    <option value=''>Pilih Mata Pelajaran</option>
                                    <?php $que = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran"); ?>
                                    <?php while ($mapel = mysqli_fetch_array($que)) : ?>
                                        <option value="<?= $mapel['id'] ?>"><?= $mapel['nama_mapel'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>

                            <?php if ($_SESSION['level'] == 'guru') : ?>
                                <input type="hidden" name="guru" value="<?= $_SESSION['id_user'] ?>">
                            <?php else : ?>
                                <label class="bold">Guru Pengampu</label>
                                <div class="input-group mb-1">
                                    <select name='guru' class='form-select' style='width:100%' required>
                                        <option value=''>Pilih Guru</option>
                                        <?php $usr = mysqli_query($koneksi, "SELECT * FROM users WHERE level='guru'"); ?>
                                        <?php while ($guru = mysqli_fetch_array($usr)) : ?>
                                            <option value="<?= $guru['id_user'] ?>"><?= $guru['nama'] ?></option>
                                        <?php endwhile ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <label class="bold">Tingkat</label>
                            <div class="input-group mb-1">
                                <select name="level" id="level" class='form-select' style='width:100%' required>
                                    <option value=''>Pilih Tingkat</option>
                                    <?php $lvl = mysqli_query($koneksi, "SELECT level FROM kelas GROUP BY level"); ?>
                                    <?php while ($level = mysqli_fetch_array($lvl)) : ?>
                                        <option value="<?= $level['level'] ?>"><?= $level['level'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>

                            <label class="bold">Kelas / Rombel</label>
                            <div class="input-group mb-1">
                                <select name='kelas' id="kelas" class='form-select' style='width:100%' required></select>
                            </div>

                            <label class="bold">Hari</label>
                            <div class="input-group mb-1">
                                <select name='hari' class='form-select' style='width:100%' required>
                                    <option value=''>Pilih Hari</option>
                                    <?php $hr = mysqli_query($koneksi, "SELECT * FROM m_hari"); ?>
                                    <?php while ($hari = mysqli_fetch_array($hr)) : ?>
                                        <option value="<?= $hari['inggris'] ?>"><?= $hari['hari'] ?></option>
                                    <?php endwhile ?>
                                </select>
                            </div>

                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>

<script>
    $("#level").change(function() {
        var level = $(this).val();
        $.ajax({
            type: "POST",
            url: "jadwal/tjadwal.php?pg=kelas", 
            data: "level=" + level, 
            success: function(response) { 
                $("#kelas").html(response);
            }
        });
    });

    $('#formguru').submit(function(e){
        e.preventDefault();
        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'jadwal/tjadwal.php?pg=tambah',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                $('.progress-bar').animate({ width: "30%" }, 500);
            },
            success: function(data){   		
                setTimeout(function(){
                    window.location.reload();
                }, 2000);
            }
        });
        return false;
    });

    $(document).ready(function() {
    $('#datatable1').DataTable({
        destroy: true,     // <- ini penting untuk mencegah error reinitialise
        paging: false,
        searching: true,
        ordering: true,
        info: false
    });
});


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
                    url: 'jadwal/tjadwal.php?pg=hapus',
                    method: "POST",
                    data: 'id=' + id,
                    beforeSend: function() {
                        $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                        $('.progress-bar').animate({ width: "30%" }, 500);
                    },
                    success: function(data) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                });
            }
            return false;
        });
    });
</script>

