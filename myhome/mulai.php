<?php include "toplog.php"; ?>

<!-- CSS untuk layout responsif dan fullscreen -->
<style>
    html, body {
        height: 100%;
        margin: 0;
    }
    .home-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Set tinggi minimum sesuai tinggi layar */
    }

    /* --- PERUBAHAN CSS DIMULAI DI SINI --- */
    .home-banner {
        flex-grow: 1; /* Membuat banner mengisi sisa ruang */
        position: relative; /* Diperlukan untuk pseudo-element ::before */
        display: flex;
        align-items: center; /* Vertikal center */
        justify-content: center; /* Horizontal center */
        overflow: hidden; /* Pastikan pseudo-element tidak keluar dari banner */
    }

    /* Membuat pseudo-element untuk background agar bisa dianimasikan terpisah */
    .home-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('../botstrap-login/bg1.png'); /* Mengganti background ke bg1.png */
        background-size: cover;
        background-position: center;
        z-index: -1; /* Meletakkan background di belakang konten */
        animation: fadeEffect 7s infinite; /* Terapkan animasi fade in/out */
    }

    /* Definisi animasi Keyframe */
    @keyframes fadeEffect {
        0% { opacity: 0; }   /* Mulai dari transparan */
        20% { opacity: 1; }  /* Fade in selama ~1.4 detik */
        90% { opacity: 1; }  /* Tahan selama 5 detik */
        100% { opacity: 0; } /* Fade out selama ~0.7 detik */
    }
    /* --- PERUBAHAN CSS BERAKHIR DI SINI --- */

    .card-login {
        background-color: rgba(255, 255, 255, 0.95); /* Sedikit transparan agar background terlihat */
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        border: none;
    }

    /* Media query untuk menyembunyikan carousel di layar kecil (HP) */
    @media (max-width: 767.98px) {
        .carousel-container {
            display: none;
        }
        .login-container {
            /* Pastikan kolom login mengambil lebar penuh di mobile */
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 15px; /* Beri sedikit padding */
        }
        .home-banner {
             align-items: flex-start; /* Posisikan form login agak ke atas di mobile */
             padding-top: 2rem;
        }
    }
</style>

<div class="home-wrapper" id="home">
    <div class="home-header" style="background:#326698;background-size: contain;height:80px;background-image: url('../vendor/bg-top.png');background-repeat: no-repeat;">
        <div class="container p-0">
            <nav class="navbar navbar-expand-lg navbar-light" id="navbar-header" style="background:none;">
                <a class="navbar-brand" href="javascript:;">
                    <img src="../images/<?= $setting['logo'] ?>" height="65" />
                    <div class="home-header-text d-none d-sm-block">
                        <h5 style="color:#fff;">SISTEM INFORMASI AKADEMIK (SIAKAD)</h5>
                        <h6 style="color:#fff;"><?= $setting['sekolah'] ?></h6>
                        <h6 style="color:#fff;">TAHUN PELAJARAN <?= $setting['tp'] ?></h6>
                    </div>
                    <span class="logo-mini d-block d-md-none" style="color:#fff;">SIAKAD</span>
                    <span class="logo-mini d-block d-md-none" style="color:#fff;">&nbsp;&nbsp;<?= $setting['tp'] ?></span>
                </a>
                <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="menu" style="background:#326698;">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="." id="link-home" style="color:#fff;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="presensi" style="color:#fff;">Live Presensi</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Konten utama dengan layout yang sudah disesuaikan -->
    <div class="home-banner">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <!-- Kolom login yang lebih responsif -->
                <div class="col-12 col-md-7 col-lg-5 login-container">
                    <div id='progressbox'></div>
                    <div class="card card-login">
                        <div class="card-body p-4">
                            <center><img src="../images/<?= $setting['logo'] ?>" style="max-width:60px;"></center>
                            <h6 class="mb-3 mt-3 text-center bold">LOGIN USERS <?= $setting['sekolah'] ?></h6>
                            <form id="form-login" name="fmLogin">
                                <div class="form-group">
                                    <span class="fa fa-user"></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
                                </div>
                                <p>
                                <div class="form-group">
                                    <span class="fa fa-eye"></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                                </div>
                                <p>
                                <div class="form-group">
                                    <input type="checkbox" onClick="showPassword()" id="btn-eye"> Show Password
                                </div><br>
                                <button type="submit" class="btn btn-primary pull-right">
                                    Masuk
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kolom carousel yang akan disembunyikan di mobile -->
                <div class="col-md-5 col-lg-7 carousel-container">
                    <!-- PERUBAHAN: Menghapus data-interval="5000" -->
                    <div id="carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel" data-slide-to="1"></li>
                            <li data-target="#carousel" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="text-white">
                                    <h5 data-animation="animated fadeInDownBig">
                                        <?= $setting['sekolah'] ?>
                                    </h5>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Apa itu SIAKAD (Sistem Informasi Akademik) ?
                                    </h6>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Merupakan Aplikasi Pendidik yang meliputi :
                                    </h6>
                                    <ul>
                                        <li data-animation="animated fadeInDownBig" data-delay="1s">
                                            Asesmen Berbasis Komputer
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="2s">
                                            Presensi yang Terhubung dengan Whatsapp orangtua
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="3s">
                                            Surat Keterangan Lulus
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="4s">
                                            Kegiatan Belajar Mengajar
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="6s">
                                            Dan lain-lain yang dikemas dalam satu web
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Duplikasi slide ditambahkan di sini -->
                            <div class="carousel-item">
                                <div class="text-white">
                                    <h5 data-animation="animated fadeInDownBig">
                                        <?= $setting['sekolah'] ?>
                                    </h5>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Apa itu SIAKAD (Sistem Informasi Akademik) ?
                                    </h6>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Merupakan Aplikasi Pendidik yang meliputi :
                                    </h6>
                                    <ul>
                                        <li data-animation="animated fadeInDownBig" data-delay="1s">
                                            Asesmen Berbasis Komputer
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="2s">
                                            Presensi yang Terhubung dengan Whatsapp orangtua
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="3s">
                                            Surat Keterangan Lulus
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="4s">
                                            Kegiatan Belajar Mengajar
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="6s">
                                            Dan lain-lain yang dikemas dalam satu web
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="text-white">
                                    <h5 data-animation="animated fadeInDownBig">
                                        <?= $setting['sekolah'] ?>
                                    </h5>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Apa itu SIAKAD (Sistem Informasi Akademik) ?
                                    </h6>
                                    <h6 data-animation="animated fadeInDownBig">
                                        Merupakan Aplikasi Pendidik yang meliputi :
                                    </h6>
                                    <ul>
                                        <li data-animation="animated fadeInDownBig" data-delay="1s">
                                            Asesmen Berbasis Komputer
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="2s">
                                            Presensi yang Terhubung dengan Whatsapp orangtua
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="3s">
                                            Surat Keterangan Lulus
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="4s">
                                            Kegiatan Belajar Mengajar
                                        </li>
                                        <li data-animation="animated flipInX" data-delay="6s">
                                            Dan lain-lain yang dikemas dalam satu web
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Cek parameter URL untuk menampilkan notifikasi SweetAlert
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'autologout') {
            Swal.fire({
                title: 'Sesi Berakhir!',
                text: 'Anda telah keluar secara otomatis karena akun ini digunakan untuk masuk di perangkat lain.',
                icon: 'warning',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#326698'
            });
        }

        // Kode untuk form login
        $('#form-login').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'ceklogin.php',
                data: $(this).serialize(),
                success: function(data) {
                    if (data == "ok") {
                        $('#progressbox').html('<div><label class="sandik" style="color:white;margin-left:80px;">Login berhasil, mengalihkan...</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                        setTimeout(function() {
                            window.location.replace('.'); // Arahkan ke dashboard admin
                        }, 1500);
                    } else if (data == "ok_siswa") {
                        $('#progressbox').html('<div><label class="sandik" style="color:white;margin-left:80px;">Login berhasil, mengalihkan...</label>&nbsp;&nbsp;&nbsp;<img src="../images/animasi.gif" style="width:50px;"></div>');
                        setTimeout(function() {
                            window.location.replace('/mydashboard'); // Arahkan ke dashboard siswa
                        }, 1500);
                    } else if (data == "nopass") {
                        Swal.fire({
                            title: 'Gagal',
                            text: 'Password yang Anda masukkan salah.',
                            icon: 'error'
                        });
                    } else {
                         Swal.fire({
                            title: 'Gagal',
                            text: 'Username tidak ditemukan.',
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                        icon: 'error'
                    });
                }
            });
        });
        
        // --- PENAMBAHAN KODE JAVASCRIPT UNTUK CAROUSEL ---
        
        // Fungsi untuk menjalankan animasi pada elemen di dalam slide
        function doAnimations(elems) {
            var animEndEv = 'webkitAnimationEnd animationend';
            elems.each(function () {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass($animationType).one(animEndEv, function () {
                    $this.removeClass($animationType);
                });
            });
        }

        var $myCarousel = $('#carousel');
        // Inisialisasi carousel
        $myCarousel.carousel();

        // Ambil elemen yang akan dianimasikan pada slide pertama
        var $firstAnimatingElems = $myCarousel.find('.carousel-item:first').find("[data-animation ^= 'animated']");
        
        // Jalankan animasi untuk slide pertama saat halaman dimuat
        doAnimations($firstAnimatingElems);

        // Atur event listener untuk saat slide berpindah
        $myCarousel.on('slide.bs.carousel', function (e) {
            // Ambil elemen yang akan dianimasikan pada slide berikutnya
            var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
            doAnimations($animatingElems);
        });
        // --- AKHIR PENAMBAHAN KODE ---
    });

    // Fungsi untuk show/hide password
    function showPassword() {
        var passwordInput = $('#password');
        var passwordIcon = $('#password').parent().find('.fa');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }
</script>

<!-- Kode untuk chatbot dan footer -->
<?php include "chatbot.php"; ?>
<?php include "footer_login.php"; ?>
<?php include "botlog.php"; ?>
