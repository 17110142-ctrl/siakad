<?php 
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");

date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sesuai
$tanggal = date('Y-m-d');
$waktu_sekarang = date("H:i"); // Format jam:menit sesuai database

// Ambil data jam masuk dan pulang terbaru
$query = mysqli_query($koneksi, "SELECT *, TIME_FORMAT(masuk, '%H:%i') AS masuk_format, TIME_FORMAT(pulang, '%H:%i') AS pulang_format FROM waktu ORDER BY id DESC LIMIT 1");
$waktu = mysqli_fetch_assoc($query);

// Debugging: Cek hasil query
if (!$waktu) {
    die("Data waktu tidak ditemukan!");
}

$masuk = $waktu['masuk_format'];  // Contoh: "23:00"
$pulang = $waktu['pulang_format']; // Contoh: "00:00"

// Ambil mode terbaru dari tabel status
$sql_status = mysqli_query($koneksi, "SELECT id, mode FROM status LIMIT 1");
$sts = mysqli_fetch_assoc($sql_status);

if (!$sts) {
    die("Data status tidak ditemukan!");
}

// Tentukan mode berdasarkan waktu sekarang
if ($waktu_sekarang < $masuk) {
    $mode_baru = 1; // Sebelum jam masuk tetap mode absen masuk
} elseif ($waktu_sekarang >= $masuk && $waktu_sekarang < $pulang) {
    $mode_baru = 1; // Dalam rentang jam masuk hingga sebelum pulang tetap absen masuk
} else {
    $mode_baru = 2; // Setelah jam pulang, masuk ke absen pulang
}

// Debugging: Cek apakah mode berubah
echo "Waktu Sekarang: $waktu_sekarang | Masuk: $masuk | Pulang: $pulang | Mode Baru: $mode_baru <br>";

// Jika mode di database berbeda dengan mode yang seharusnya, update mode
if ($sts['mode'] != $mode_baru) {
    mysqli_query($koneksi, "UPDATE status SET mode='$mode_baru' LIMIT 1");
    echo "Mode telah diperbarui menjadi $mode_baru <br>";
} else {
    echo "Mode tetap $mode_baru (tidak ada perubahan) <br>";
}

// Ambil kembali mode terbaru setelah update
$sql = mysqli_query($koneksi, "SELECT id, mode FROM status LIMIT 1");
$sts = mysqli_fetch_assoc($sql);
?>

<div id="carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div>
                <?php if($sts['mode'] == '1'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN MASUK</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' ORDER BY id DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                            $peg = fetch($koneksi, 'users', ['id_user' => $data['idpeg']]);
                            $info = ($data['ket'] == 'H') ? 'Hadir' : (($data['ket'] == 'S') ? 'Sakit' : (($data['ket'] == 'I') ? 'Izin' : 'Alpha'));
                        ?>
                            <?php if ($data['level'] == 'siswa'): ?>
                                <li data-animation="animated fadeInDownBig" data-delay="1s"><?= $siswa['nama'] ?> <small style="color:yellow">( <?= $info; ?> )</small></li>
                            <?php else: ?>
                                <li data-animation="animated fadeInDownBig" data-delay="1s"><?= $peg['nama'] ?></li>
                            <?php endif; ?>
                        <?php endwhile; ?>               
                    </ul>
                <?php elseif ($sts['mode'] == '2'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN PULANG</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$tanggal' and pulang<>'' ORDER BY pulang DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                            $peg = fetch($koneksi, 'users', ['id_user' => $data['idpeg']]);
                        ?>
                            <li data-animation="animated fadeInDownBig" data-delay="1s"><?= ($data['level'] == 'siswa') ? $siswa['nama'] : $peg['nama'] ?></li>
                        <?php endwhile; ?>  
                    </ul>
                <?php elseif ($sts['mode'] == '3'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN MASUK ESKUL</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE tanggal='$tanggal' ORDER BY id DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                        ?>
                            <li data-animation="animated fadeInDownBig" data-delay="1s"><?= $siswa['nama'] ?></li>
                        <?php endwhile; ?>  
                    </ul>
                <?php elseif ($sts['mode'] == '4'): ?>
                    <h5 data-animation="animated fadeInDownBig">ABSEN PULANG ESKUL</h5>
                    <ul>
                        <?php
                        $query = mysqli_query($koneksi, "SELECT * FROM absensi_les WHERE tanggal='$tanggal' and pulang<>'' ORDER BY pulang DESC LIMIT 8");
                        while ($data = mysqli_fetch_array($query)) :
                            $siswa = fetch($koneksi, 'siswa', ['id_siswa' => $data['idsiswa']]);
                        ?>
                            <li data-animation="animated fadeInDownBig" data-delay="1s"><?= $siswa['nama'] ?></li>
                        <?php endwhile; ?>  
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
