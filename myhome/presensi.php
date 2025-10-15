<?php
include "toplog.php";
$tanggal = date('Y-m-d');
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='pegawai'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpegawai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal'"));
?>

<!-- CSS untuk latar belakang animasi dan layout fullscreen -->
<style>
    html,
    body {
        height: 100%;
        margin: 0;
    }

    .home-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .home-banner {
        flex-grow: 1;
        position: relative;
        overflow: hidden;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    /* Pseudo-element untuk background agar bisa dianimasikan terpisah */
    .home-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('../botstrap-login/bg1.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* Membuat background tidak ikut scroll */
        z-index: -1;
        animation: fadeEffect 7s infinite;
    }

    /* Definisi animasi Keyframe */
    @keyframes fadeEffect {
        0% { opacity: 0; }
        20% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }

    /* Memberi sedikit latar pada card agar mudah dibaca */
    .card {
        background-color: rgba(255, 255, 255, 0.9);
    }

    /* CSS untuk panel status di header */
    #mode-status-panel {
        background: rgba(0, 0, 0, 0.3);
        color: #fff;
        padding: 8px 20px;
        border-radius: 50px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1rem;
        font-weight: 500;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        display: none; /* Disembunyikan sampai konten dimuat */
        transition: opacity 0.5s;
    }

    .camera-container {
        position: relative;
        cursor: pointer;
    }

    .camera-container.scanner-active {
        cursor: default;
    }

    #previewKamera {
        display: block;
    }

    .camera-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border-radius: 5px;
        background: rgba(0, 0, 0, 0.45);
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.5px;
        line-height: 1.4;
        text-shadow: 0 1px 2px rgba(0,0,0,0.6);
        pointer-events: none;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .camera-overlay.hidden {
        opacity: 0;
    }
</style>

<div class="home-wrapper" id="home">
    <div class="home-header" style="background:#326698;height:80px;background-image: url('vendor/bg-top.png');background-repeat: no-repeat;">
        <div class="container p-0">
            <nav class="navbar navbar-expand-lg navbar-light" id="navbar-header" style="background:none;">
                <a class="navbar-brand" href="javascript:;">
                    <img src="../images/<?= $setting['logo'] ?>" height="65" />
                    <div class="home-header-text d-none d-sm-block">
                        <h5 style="color:#fff;">SISTEM INFORMASI AKADEMIK</h5>
                        <h6 style="color:#fff;"><?= $setting['sekolah'] ?></h6>
                        <h6 style="color:#fff;">TAHUN PELAJARAN <?= $setting['tp'] ?></h6>
                    </div>
                </a>
                
                <!-- PANEL STATUS BARU DITEMPATKAN DI SINI -->
                <div id="mode-status-panel" class="mx-auto d-none d-lg-flex align-items-center">
                    <!-- Konten akan dimuat oleh AJAX -->
                </div>

                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="menu" style="background:#326698;">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active"><a class="nav-link" href="." style="color:#fff;">Live Presensi</a></li>
                        <li class="nav-item"><a class="nav-link" href="." id="link-home" style="color:#fff;">Login</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Semua konten utama dimasukkan ke dalam .home-banner -->
    <div class="home-banner">
        <div class="container">
            
            <!-- PANEL STATISTIK -->
            <div id="log" class="mb-4"></div>

            <div class="row mb-4">
                <div class="col-lg-8">
                    <!-- Log Absen Utama (ID diperbaiki) -->
                    <div id='logabs-main'></div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>ABSENSI QR CODE</h5>
                        </div>
                        <div class="card-body position-relative">
                            <div id="previewParent" class="camera-container">
                                <video id="previewKamera" playsinline style="width: 100%; border-radius: 5px;"></video>
                                <div id="cameraOverlay" class="camera-overlay text-center">
                                    Menyiapkan kamera untuk pemindaian QR code...
                                </div>
                            </div>
                            <input type="hidden" id="text" name="kode_qr">
                            <p id="result" class="text-center font-weight-bold text-success mt-2"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Log Bawah -->
            <div class="row">
                <div class="col-xl-4">
                    <div class="card widget widget-list">
                        <div class="card-header">
                            <h5 class="card-title">SISWA</h5>
                        </div>
                        <div class="card-body" style="height:470px; overflow-y: auto;">
                            <!-- Log Siswa (ID diperbaiki) -->
                            <div id='logabsen-siswa'></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card widget widget-list">
                        <div class="card-header">
                            <h5 class="card-title">PEGAWAI</h5>
                        </div>
                        <div class="card-body" style="height:470px; overflow-y: auto;">
                            <div id='logabsenpeg'></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card widget widget-payment-request">
                        <div class="card-header">
                            <h5 class="card-title">NOTIFIKASI WA</h5>
                            <button class="hapus btn btn-sm btn-danger pull-right" id="optimal" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                <i class="material-icons">delete</i>
                            </button>
                        </div>
                        <div class="card-body" style="height:470px; overflow-y: auto;">
                            <div id='logpesan'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "botlog.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/@zxing/library@latest"></script>
<!-- SweetAlert untuk notifikasi yang lebih baik -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // (rollback) Tidak ada popup resend WA di halaman ini

    // Inisialisasi QR Code Scanner
    let selectedDeviceId = null;
    const codeReader = new ZXing.BrowserMultiFormatReader();
    let deviceControls = null;
    let lastScanText = '';
    let lastScanTime = 0;
    const scanCooldown = 2000; // ms
    let cooldownActive = false;
    let scanningActive = false;
    let $previewParent = null;
    let $cameraOverlay = null;

    function setOverlay(message, hide = false) {
        if ($cameraOverlay) {
            if (typeof message === 'string') {
                $cameraOverlay.text(message);
            }
            $cameraOverlay.toggleClass('hidden', hide);
        }

        if ($previewParent) {
            $previewParent.toggleClass('scanner-active', hide);
        }
    }

    function startScanCooldown() {
        cooldownActive = true;
        setTimeout(() => {
            cooldownActive = false;
        }, scanCooldown);
    }

    function stopScanner(message) {
        if (!scanningActive && !deviceControls) {
            if (message) {
                setOverlay(message, false);
            }
            return;
        }

        if (deviceControls && typeof deviceControls.stop === 'function') {
            deviceControls.stop();
        }
        codeReader.reset();
        deviceControls = null;
        scanningActive = false;
        cooldownActive = false;
        lastScanText = '';
        lastScanTime = 0;

        const preview = document.getElementById('previewKamera');
        if (preview && preview.srcObject) {
            preview.srcObject.getTracks().forEach(track => track.stop());
            preview.srcObject = null;
        }

        if (message) {
            setOverlay(message, false);
        } else {
            setOverlay('Pemindaian dihentikan sementara. Sistem akan mencoba memulai ulang.', false);
        }
    }

    function startScanner() {
        if (scanningActive) {
            return;
        }

        setOverlay('Mengaktifkan kamera...', false);

        return codeReader.listVideoInputDevices()
            .then(videoInputDevices => {
                if (videoInputDevices.length < 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Kamera tidak ditemukan!',
                    });
                    throw new Error('Kamera tidak ditemukan');
                }

                if (!selectedDeviceId) {
                    const backCamera = videoInputDevices.find(device => /back|rear|environment/i.test(device.label));
                    selectedDeviceId = (backCamera || videoInputDevices[0]).deviceId;
                }

                scanningActive = true;
                cooldownActive = false;
                setOverlay(null, true);
                $('#result').text('Scanner aktif. Arahkan QR code ke kamera.');

                return codeReader.decodeFromVideoDevice(
                    selectedDeviceId,
                    'previewKamera',
                    (result, err) => {
                        if (!scanningActive || cooldownActive) {
                            return;
                        }

                        if (result) {
                            handleScanResult(result.text);
                            startScanCooldown();
                            return;
                        }

                        if (err && !(err instanceof ZXing.NotFoundException)) {
                            console.error(err);
                        }
                    }
                );
            })
            .then(controls => {
                deviceControls = controls || null;
            })
            .catch(err => {
                console.error(err);
                stopScanner('Tidak dapat mengakses kamera: ' + (err.message || err));
            });
    }

    function handleScanResult(text) {
        const now = Date.now();

        if (text === lastScanText && (now - lastScanTime) < scanCooldown) {
            return;
        }

        lastScanText = text;
        lastScanTime = now;

        document.getElementById("text").value = text;

        $.ajax({
            type: "POST",
            url: "proses_absensi.php",
            data: {
                kode_qr: text
            },
            success: function(response) {
                console.log("Respons dari server:", response);
                document.getElementById("result").innerHTML = "<b>" + response + "</b>";

                const audio = new Audio('assets/audio/beep.mp3');
                audio.play().catch(error => {
                    console.error("Error playing beep:", error);
                });

                // Notifikasi WA kini dikirim langsung oleh proses_absensi.php untuk menghindari pesan ganda
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan:", error);
                document.getElementById("result").innerHTML = "Gagal mengirim data!";
            },
            complete: function() {
                setTimeout(() => {
                    document.getElementById("result").innerHTML = "";
                }, 5000);
            }
        });
    }

    // Memulai scanner dan auto-refresh setelah halaman dimuat
    $(document).ready(function() {
        $previewParent = $('#previewParent');
        $cameraOverlay = $('#cameraOverlay');

        const attemptStart = () => {
            if (!scanningActive) {
                startScanner();
            }
        };

        $previewParent.on('click touchstart', function(e) {
            if (!scanningActive) {
                e.preventDefault();
                attemptStart();
            }
        });

        if (navigator.mediaDevices) {
            startScanner();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.',
            });
            setOverlay('Akses kamera gagal. Sentuh area ini untuk mencoba lagi setelah izin diberikan.', false);
        }

        // Fungsi untuk memuat ulang log secara berkala (SUDAH DIPERBAIKI)
        setInterval(function() {
            // Memuat statistik total
            $('#log').load('log.php');
            
            // Memuat log utama dan status mode dari logabsen.php
            $.get('logabsen.php', function(data) {
                // Pisahkan konten berdasarkan delimiter
                const parts = data.split('<--SPLIT-->');
                if (parts.length === 2) {
                    // Masukkan bagian pertama ke panel status di header
                    $('#mode-status-panel').html(parts[0]).show();
                    // Masukkan bagian kedua ke panel log utama
                    $('#logabs-main').html(parts[1]);
                } else {
                    // Fallback jika pemisahan gagal
                    $('#logabs-main').html(data);
                }
            }).fail(function() {
                $('#logabs-main').html('<p class="text-danger">Gagal memuat log absensi.</p>');
            });

            // Memuat log spesifik siswa dan pegawai
            $('#logabsen-siswa').load('logsis.php');
            $('#logabsenpeg').load('logpeg.php');
            
            // Memuat log notifikasi WA
            $('#logpesan').load('logpesan.php');
        }, 2000);

        // Fungsi untuk tombol hapus log pesan
        $("#optimal").click(function() {
            Swal.fire({
                title: 'Hapus Pesan Terkirim?',
                text: "Semua pesan terkirim di log akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../pesan/tsetting.php?pg=hps',
                        success: function(data) {
                            Swal.fire(
                                'Berhasil!',
                                'Log pesan telah dihapus.',
                                'success'
                            )
                            // Tidak perlu reload halaman, karena log akan refresh otomatis
                        },
                        error: function() {
                             Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus log.',
                                'error'
                            )
                        }
                    });
                }
            })
        });
    });
</script>
