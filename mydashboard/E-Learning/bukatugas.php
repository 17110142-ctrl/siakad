<?php
include '../../config/koneksi.php';
defined('APK') or exit('No Access');
$id = $_GET['id'];
$tugas = mysqli_fetch_array(mysqli_query($koneksi, "select * from tugas where id_tugas='$id'"));
$guru = fetch($koneksi, 'users', ['id_user' => $tugas['id_guru']]);

// Cek absensi siswa untuk tugas ini
$where = array(
    'idsiswa' => $_SESSION['id_siswa'],
    'idtugas' => $id
);
$cek = rowcount($koneksi, 'absen_daringmapel', $where);
if ($cek == 0) {
    $datax = array(
        'idtugas' => $id,
        'mapel'=> $tugas['mapel'],
        'idsiswa' => $_SESSION['id_siswa'],
        'tanggal' => date('Y-m-d'),
        'bulan'=> date('m'),
        'ket' => 'H',
        'guru'=> $tugas['id_guru'],
        'tahun'=> date('Y')
    );
    insert($koneksi, 'absen_daringmapel', $datax);
}
$warna = array('red', 'blue', 'green', 'gray', 'purple', 'black');

// --- LOGIKA BARU: PENGECEKAN BATAS WAKTU ---
$sekarang = date('Y-m-d H:i:s');
$sudah_selesai = $tugas['tgl_selesai'] < $sekarang;

// Fungsi untuk mengubah tanggal ke format Indonesia
function format_tanggal_indonesia($tanggal_waktu) {
    $bulan_indonesia = [
        'January'   => 'Januari', 'February'  => 'Februari', 'March'     => 'Maret',
        'April'     => 'April',   'May'       => 'Mei',      'June'      => 'Juni',
        'July'      => 'Juli',    'August'    => 'Agustus',  'September' => 'September',
        'October'   => 'Oktober', 'November'  => 'November', 'December'  => 'Desember'
    ];
    $timestamp = strtotime($tanggal_waktu);
    $tanggal_inggris = date('d F Y H:i', $timestamp);
    $nama_bulan_inggris = date('F', $timestamp);
    $nama_bulan_indonesia = $bulan_indonesia[$nama_bulan_inggris];
    return str_replace($nama_bulan_inggris, $nama_bulan_indonesia, $tanggal_inggris);
}

$waktu_selesai_formatted = format_tanggal_indonesia($tugas['tgl_selesai']);

// Catatan: $homeurl diasumsikan sudah tersedia dari include global (top.php)

?>

<!-- CSS untuk Countdown Timer Modern -->
<style>
    .card-countdown {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        margin-bottom: 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
        text-align: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card-header-countdown {
        padding: 15px;
        font-size: 1.2em;
        font-weight: bold;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .completion-date {
        font-size: 0.7em;
        font-weight: normal;
        opacity: 0.9;
        margin-top: 5px;
        display: block; /* Agar menjadi baris baru */
    }
    .card-body-countdown {
        padding: 20px;
    }
    .countdown-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .time-segment {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 80px;
    }
    .time-segment .time-value {
        font-size: 3em;
        font-weight: bold;
        line-height: 1;
    }
    .time-label {
        font-size: 0.9em;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 5px;
    }
    .expired-message {
        font-size: 2em;
        font-weight: bold;
        padding: 20px;
        animation: pulse 1.5s infinite;
        display: none; /* Sembunyikan secara default */
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>

<!-- Panel Countdown Timer Modern -->
<div class="card-countdown">
    <div class="card-header-countdown">
        Batas Waktu Pengerjaan
        <span class="completion-date">Selesai pada: <?= $waktu_selesai_formatted ?> WIB</span>
    </div>
    <div class="card-body-countdown">
        <div class="countdown-container">
            <div class="time-segment">
                <span id="days" class="time-value">00</span>
                <span class="time-label">Hari</span>
            </div>
            <div class="time-segment">
                <span id="hours" class="time-value">00</span>
                <span class="time-label">Jam</span>
            </div>
            <div class="time-segment">
                <span id="minutes" class="time-value">00</span>
                <span class="time-label">Menit</span>
            </div>
            <div class="time-segment">
                <span id="seconds" class="time-value">00</span>
                <span class="time-label">Detik</span>
            </div>
        </div>
        <div id="countdown-expired" class="expired-message">
            WAKTU PENGERJAAN SUDAH HABIS
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-6">
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">TUGAS BELAJAR</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($tugas['id_materi']) && (int)$tugas['id_materi'] > 0): ?>
                <div class="mb-3">
                    <a href="?pg=bukamateri&id=<?= (int)$tugas['id_materi'] ?>" class="btn btn-warning">
                        <i class="fas fa-book"></i> Lihat Materi Terkait
                    </a>
                </div>
                <?php endif; ?>
                <div class="widget-payment-request-container">
                    <div class="widget-payment-request-author">
                        <div class="avatar m-r-sm">
                            <img src="../images/icon/buku.png" alt="">
                        </div>
                        <div class="widget-payment-request-author-info">
                            <span class="widget-payment-request-author-name"><?= $tugas['mapel'] ?></span>
                            <span class="widget-payment-request-author-about"><?= $tugas['judul'] ?></span>
                        </div>
                    </div>
                    <div class="widget-payment-request-product" style="background-color: <?= $warna[rand(0, count($warna) - 1)] ?>;">
                        <div class="widget-payment-request-product-image m-r-sm">
                            <?php if($guru['foto']==''): ?>
                                <img src="../images/guru.png" class="mt-auto" alt="">
                            <?php endif; ?>
                        </div>
                        <div class="widget-payment-request-product-info d-flex">
                            <div class="widget-payment-request-product-info-content">
                                <span class="widget-payment-request-product-name" style="color:#fff;">Guru Pengampu</span>
                                <span class="widget-payment-request-product-about" style="color:#fff;"><?= $guru['nama'] ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="widget-payment-request-info">
                        <?php if($tugas['file']==''): ?>
                            <font style="color:red;">Tidak ada File Download</font>
                        <?php else: ?>
                            <br>
                            <a href="../tugas/<?= $tugas['file'] ?>" target="_blank" class="btn btn-sm btn-link kanan">Download</a>
                        <?php endif; ?>
                    </div>
                    <br>
                    <center>
                        <h3><?= $tugas['judul'] ?></h3>
                    </center>
                    <p><?= $tugas['tugas'] ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">KIRIM JAWABAN</h5>
            </div>
            <div class="card-body">
                <?php 
                if (!empty($tugas['file'])) {
                    // Menggunakan pathinfo untuk cara yang lebih aman mendapatkan ekstensi
                    $path_info = pathinfo($tugas['file']);
                    $ekstensi = strtolower($path_info['extension']);
                    
                    // Gunakan path relatif agar konsisten dengan file lain
                    $file_rel = '../tugas/' . rawurlencode($tugas['file']);
                    
                    // Daftar ekstensi yang didukung oleh Microsoft Office Viewer
                    $office_extensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
                ?>
                    <?php if ($ekstensi == 'mp4'): ?>
                        <video src="<?= $file_rel ?>" controls autoplay width="100%" height="315"></video>
                    <?php elseif (in_array($ekstensi, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $file_rel ?>" style="max-width: 100%; height: auto;">
                    <?php elseif ($ekstensi == 'pdf'): ?>
                        <iframe src="<?= $file_rel ?>" width="100%" height="500px"></iframe>
                    <?php elseif (in_array($ekstensi, $office_extensions)): ?>
                        <div class="alert alert-info">
                            Pratinjau dokumen Office tidak tersedia. <a href="<?= $file_rel ?>" target="_blank">Unduh file</a> untuk melihat.
                        </div>
                    <?php endif; ?>
                <?php } ?>
                
                <?php
                $kondisi = array(
                    'id_siswa' => $_SESSION['id_siswa'],
                    'id_tugas' => $tugas['id_tugas']
                );
                $jawab_tugas = fetch($koneksi, 'jawaban_tugas', $kondisi);
                if ($jawab_tugas) {
                    $jawaban = $jawab_tugas['jawaban'];
                } else {
                    $jawaban = "";
                }
                ?>
                <?php 
                // Tampilkan nilai hanya jika sudah dinilai guru.
                $sudah_dinilai = false;
                if ($jawab_tugas) {
                    $nilai_str = isset($jawab_tugas['nilai']) ? trim((string)$jawab_tugas['nilai']) : '';
                    $sts = isset($jawab_tugas['status']) ? (int)$jawab_tugas['status'] : 0;
                    // Anggap "sudah dinilai" jika status=1 atau nilai bukan kosong dan bukan "0"
                    $sudah_dinilai = ($sts === 1) || ($nilai_str !== '' && $nilai_str !== '0');
                }
                ?>
                <?php if ($sudah_dinilai) { ?>
                    <h4>Nilai Kamu : <?= htmlspecialchars($jawab_tugas['nilai']) ?></h4>
                <?php } else { ?>
                    
                    <!-- KONDISI JIKA TUGAS SUDAH SELESAI -->
                    <?php if ($sudah_selesai): ?>
                        <div class="alert alert-danger mt-3">
                            Anda sudah tidak bisa untuk mengirimkan tugas, karena sudah ditutup pada <strong><?= $waktu_selesai_formatted ?></strong>.
                        </div>
                        <form>
                            <div class="form-group">
                                <label class="bold">Lembar Jawaban</label>
                                <textarea class="form-control" rows="10" disabled><?= $jawaban ?></textarea>
                            </div><p>
                            <div class="kanan">
                                <button type="button" class="btn btn-primary" disabled>Simpan Jawaban</button>
                            </div>
                        </form>
                    
                    <!-- KONDISI JIKA TUGAS MASIH AKTIF -->
                    <?php else: ?>
                        <form id='formjawaban'>
                            <input type="hidden" name="id_tugas" value="<?= $tugas['id_tugas'] ?>">
                            <input type="hidden" name="nama_mapel" value="<?= $tugas['mapel'] ?>">
                            <div class="form-group">
                                <label class="bold">Lembar Jawaban</label>
                                <textarea class="form-control" name="jawaban" id="txtjawaban" rows="10"><?= $jawaban ?></textarea>
                            </div><p>
                            <?php if ($jawab_tugas['file'] == '') { ?>
                                <div class="form-group">
                                    <p class="bold">Jika jawaban diupload</p>
                                    <label class="bold">Upload</label>
                                    <input type="file" class="form-control-file" name="file" aria-describedby="fileHelpId">
                                    <p></p>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-success" role="alert">
                                    <strong>File jawaban berhasil dikirim</strong>
                                    <a href='../tugas/<?= $jawab_tugas['file'] ?>' target="_blank">Lihat file</a>
                                </div>
                            <?php  } ?>
                            <div class="kanan">
                                <button type="submit" class="btn btn-primary">Simpan Jawaban</button>
                            </div>
                        </form>
                    <?php endif; ?>

                <?php  } ?>
            </div>
        </div>
    </div>
</div>
<script>
// --- SCRIPT UNTUK COUNTDOWN TIMER ---
// Mengatur tanggal akhir hitungan mundur dari data PHP
var countDownDate = new Date("<?= date('M d, Y H:i:s', strtotime($tugas['tgl_selesai'])) ?>").getTime();

// Memperbarui hitungan mundur setiap 1 detik
var x = setInterval(function() {
    var now = new Date().getTime();
    var distance = countDownDate - now;

    // Kalkulasi waktu untuk hari, jam, menit, dan detik
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Fungsi untuk menambahkan nol di depan jika angka < 10
    function pad(n) {
        return (n < 10 ? '0' : '') + n;
    }

    // Menampilkan hasil di elemen masing-masing
    document.getElementById("days").innerHTML = pad(days);
    document.getElementById("hours").innerHTML = pad(hours);
    document.getElementById("minutes").innerHTML = pad(minutes);
    document.getElementById("seconds").innerHTML = pad(seconds);

    // Jika hitungan mundur selesai
    if (distance < 0) {
        clearInterval(x);
        document.querySelector(".countdown-container").style.display = 'none';
        document.getElementById("countdown-expired").style.display = 'block';
        // Menonaktifkan form jika waktu sudah habis
        $('#formjawaban :input').prop('disabled', true);
    }
}, 1000);


// --- SCRIPT UNTUK SUBMIT FORM JAWABAN ---
$('#formjawaban').submit(function(e){
    e.preventDefault();
    var data = new FormData(this);
    console.log([...data.entries()]); 
    $.ajax(
    {
        type: 'POST',
        url: 'simpantugas.php',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
        $('#progressbox').html('<div><label class="sandik" style="color:blue;margin-left:80px;">Data sedang di proses</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi1.gif" ></div>');
        $('.progress-bar').animate({
        width: "30%"
        }, 500);
        },          
        success: function(data){               
        setTimeout(function()
            {
            window.location.reload();
                    }, 2000);
                            
                    }
                });
            return false;
        });
    </script>
