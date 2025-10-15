<?php
defined('APK') or exit('No Access');
$hari = date('D');
?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="bold">LIHAT ABSENSI PERTEMUAN</h5>
            </div>
            <div class="card-body">
                <p>Pilih kelas dan mata pelajaran untuk melihat daftar kehadiran hari ini.</p>
                
                <label class="bold">Guru Pengampu</label>
                <div class="input-group mb-3">
                    <select id="guru" class='form-select guru' style='width:100%' required>
                        <?php
                        // Logika untuk menampilkan guru sesuai jadwal hari ini
                        if($user['level']=='admin'):
                            $sql=mysqli_query($koneksi,"SELECT hari,guru FROM jadwal_mapel WHERE hari='$hari' GROUP BY guru");
                        elseif($user['level']=='guru'):
                            $sql=mysqli_query($koneksi,"SELECT hari,guru FROM jadwal_mapel WHERE hari='$hari' and guru='$user[id_user]' GROUP BY guru");
                        endif;
                        while ($data=mysqli_fetch_array($sql)) {
                            $peg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$data[guru]'"));
                            echo "<option value='{$data['guru']}'>{$peg['nama']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <label class="bold">Kelas</label>
                <div class="input-group mb-3">
                    <select id="kelas" class='form-select kelas' style='width:100%' required>
                        <option value="">Pilih Kelas</option>
                        <?php
                        // Logika untuk menampilkan kelas sesuai jadwal hari ini
                        if($user['level']=='admin'):
                            $sql=mysqli_query($koneksi,"SELECT hari,kelas FROM jadwal_mapel WHERE hari='$hari' GROUP BY kelas");
                        elseif($user['level']=='guru'):
                            $sql=mysqli_query($koneksi,"SELECT hari,kelas,guru FROM jadwal_mapel WHERE hari='$hari' and guru='$user[id_user]' GROUP BY kelas");
                        endif;
                        while ($data=mysqli_fetch_array($sql)) {
                            echo '<option value="'.$data['kelas'].'">'.$data['kelas'].'</option> ';
                        }
                        ?>
                    </select>
                </div>

                <label class="bold">Mata Pelajaran</label>
                <div class="input-group mb-3">
                    <select id="mapel" class='form-select mapel' style='width:100%' required></select>
                </div>

                <div class="d-grid">
                    <button id="lihat" class="btn btn-primary">Lihat Daftar Hadir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script AJAX untuk mengisi dropdown mapel (sama seperti di manual.php)
    $("#kelas").change(function() {
        var kelas = $(this).val();
        var guru = $("#guru").val();
        $.ajax({
            type: "POST",
            url: "absen/tabsen.php?pg=mapel",
            data: "kelas=" + kelas + '&guru=' + guru,
            success: function(response) {
                $("#mapel").html(response);
            }
        });
    });

    // Script untuk tombol "Lihat"
    $('#lihat').click(function() {
        var k = $('.kelas').val();
        var g = $('.guru').val();
        var m = $('.mapel').val();
        if(k && g && m) {
            // Mengarahkan ke halaman utama dengan parameter untuk memuat lihat_absen.php
            // Sesuaikan 'pg' dengan nama yang Anda gunakan untuk menu ini
            location.href = "?pg=<?= enkripsi('lihat_absen') ?>&k=" + k + "&g=" + g + "&m=" + m;
        } else {
            alert('Harap lengkapi semua pilihan.');
        }
    });
</script>