<?php  
include "toplog.php";
$tanggal = date('Y-m-d');
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='pegawai'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpegawai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal'"));
?>

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

    <div class="home-banner">
        <div class="home-banner-bg home-banner-bg-color"></div>
        <div class="home-banner-bg home-banner-bg-img"></div>
        <div class="container mt-5" id="log"></div>
        <div class="container mt-5">
            <div class="row">
                <div class="col-sm-8">
                    <div id='logabs'></div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header text-center"><h5>ABSENSI QR CODE</h5></div>
                        <div class="card-body">
                            <div id="previewParent">
                                <video id="previewKamera" style="width: 100%;"></video>
                            </div>
                            <input type="hidden" id="text" name="kode_qr">
                            <p id="result" class="text-center font-weight-bold text-success"></p>
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
 

<script>
    // (rollback) Tidak ada popup resend WA di halaman ini

    let selectedDeviceId = null;
    
    const codeReader = new ZXing.BrowserMultiFormatReader();

    function initScanner() {
        codeReader.listVideoInputDevices()
            .then(videoInputDevices => {
                if (videoInputDevices.length < 1) {
                    alert("Camera not found!");
                    return;
                }

                selectedDeviceId = videoInputDevices[0].deviceId;

                codeReader.decodeOnceFromVideoDevice(selectedDeviceId, 'previewKamera')
                    .then(result => {
                        console.log(`Kode QR Terbaca: ${result.text}`);
                        document.getElementById("text").value = result.text;

                        $.ajax({
                            type: "POST",
                            url: "proses_absensi.php",
                            data: { kode_qr: result.text },
                            success: function(response) {
                                console.log("Respons dari server:", response);
                                document.getElementById("result").innerHTML = "<b>" + response + "</b>";
                                var audio = new Audio('assets/audio/beep.mp3');
                                audio.play().then(() => {
                                    console.log("Beep played successfully");
                                }).catch(error => {
                                    console.error("Error playing beep:", error);
                                });

                                // Determine if it's a check-in or check-out based on the response
                                let isCheckIn = response.includes("check-in"); // Adjust this condition based on your actual response
                                let mode = isCheckIn ? 1 : 2;

                                // Send WhatsApp message
                                $.ajax({
                                    type: "GET",
                                    url: "../pesan.php",
                                    data: { nokartu: result.text, mode: mode },
                                    success: function(waResponse) {
                                        console.log("WhatsApp response:", waResponse);
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("WhatsApp error:", error);
                                        // Log the error to the console
                                        console.log("WhatsApp error details:", xhr.responseText);
                                    }
                                });
                            },
                            
                            error: function(xhr, status, error) {
                                console.error("Terjadi kesalahan:", error);
                                document.getElementById("result").innerHTML = "Gagal mengirim data!";
                            }
                        });

                        setTimeout(() => {
                            initScanner();
                        }, 5);
                    })
                    .catch(err => console.error(err));
            })
            .catch(err => console.error(err));
    }

    if (navigator.mediaDevices) {
        initScanner();
    } else {
        alert('Cannot access camera.');
    }

    var autoRefresh = setInterval(
        function() {
            $('#log').load('log.php');
            $('#logabs').load('logabsen.php');
            $('#logabsen').load('logsis.php');
            $('#logpesan').load('logpesan.php');
        }, 1000
    );
</script>

<div id='logabs'></div>
<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-list">
            <div class="card-header">
                <h5 class="card-title">SISWA</h5>
            </div>
            <div class="card-body" style="height:470px;">
                <div id='logabsen'></div>
            </div>
        </div>
    </div>    
    <div class="col-xl-4">
        <div class="card widget widget-list">
            <div class="card-header">
                <h5 class="card-title">PEGAWAI</h5>
            </div>
            <div class="card-body" style="height:470px;">
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
            <div class="card-body" style="height:470px;">
                <div id='logpesan'></div>
            </div>
        </div>
    </div>
</div>

<script>
    var autoRefresh = setInterval(
        function() {
            $('#logabs').load('logabsen.php');
            $('#logabsen').load('logsis.php');
            $('#logabsenpeg').load('logpeg.php');
            $('#logpesan').load('logpesan.php');
        }, 2000
    );
</script>

<script>
    $("#optimal").click(function(){
        Swal.fire({
            title: 'Hapus Pesan Terkirim',
            text: "Informasi : Pesan terkirim akan terhapus !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'pesan/tsetting.php?pg=hps',
                    success: function(data) {
                        Swal.fire(
                            'Success!',
                            'Your file has been Optimize.',
                            'success'
                        )
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                });
            }
            return false;
        })
    });
</script>
