 <?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
(isset($_SESSION['id_siswa'])) ? $id_siswa = $_SESSION['id_siswa'] : $id_siswa = 0;
($id_siswa == 0) ?  header("Location:$homeurl/myhome") : null;
$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
$idsesi = $siswa['sesi'];
$idpk = $siswa['pk'];
$level = $siswa['level'];
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl"));
$pk = fetch($koneksi, 'pk', array('id_pk' => $idpk));

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == '') :
	$sidebar = 'sidebar-collapse';
elseif ($pg == 'jadwal') :
	$sidebar = 'sidebar-collapse';
elseif ($pg == 'testongoing') :
	$sidebar = 'sidebar-collapse';

else :
	$sidebar = '';
endif;

($pg == 'testongoing') ? $disa = '' : $disa = 'offcanvas';
$token = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM token"));
$nilsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' AND browser='0'"));
$tglsekarang = time();
?>
<!DOCTYPE html>
<html lang="en" translate="no">
<head>
    <meta charset="utf-8">
	<meta name="google" content="notranslate">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sandik All in One">
    <meta name="keywords" content="Sandik All in One">
    <meta name="author" content="sandik">
   
    <title><?= $setting['sekolah'] ?></title>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
    <link rel='shortcut icon' href='images/<?= $setting['logo'] ?>' />
	<link href="font/material.css" rel="stylesheet">
    <link rel='stylesheet' href='vendor/css/bootstrap.min.css' />
    <link rel='stylesheet' href='vendor/fontawesome/css/all.css' />
    <link rel='stylesheet' href='vendor/css/AdminLTE.min.css' />
    <link rel='stylesheet' href='vendor/css/skins/skin-green-light.min.css' />
    <link rel='stylesheet' href='vendor/iCheck/square/green.css' />   
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css">	
    <link rel='stylesheet' href='assets/toastr/toastr.min.css'>
    <link rel='stylesheet' href='assets/radio/css/style.css'>
    <link rel='stylesheet' href='assets/izitoast/css/iziToast.min.css'>
    <script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <link rel='stylesheet' href='vendor/css/costum.css' />
   <link rel='stylesheet' href='vendor/css/kostum.css'>
   
<style>
  .edis {
  background-size: 260px;
  background-image: url('images/tutwuri2.png');
  background-repeat: no-repeat;
  background-position: top right; 
}
</style>

</head>

<body class='hold-transition skin-green-light  fixed <?= $sidebar ?>'>
    <span id='livetime'></span>

    <div class='wrapper'>
        <header class='main-header'>
		
            <nav class='navbar navbar-static-top' style='background-color:#2c94de;box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.1)' role='navigation'>
                <a href='#' class='sidebar-baru' data-toggle='<?= $disa ?>' role='button'>
                    <i class="fa fa-bars fa-lg fa-fw"></i>
                </a>

                <div class='navbar-custom-menu'>
				
                    <ul class='nav navbar-nav'>
                        <li class="visible-xs"><a><?= $siswa['nama'] ?></a></li>
                        <li class='dropdown user user-menu'>
                            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                <?php
                                if ($siswa['foto'] <> '') :
                                    if (!file_exists("images/fotosiswa/$siswa[foto]")) :
                                        echo "<img src='images/user.png' class='user-image'   alt='+'>";
                                    else :
                                        echo "<img src='images/fotosiswa/$siswa[foto]' class='user-image'   alt='+'>";
                                    endif;
                                else :
                                    echo "<img src='images/user.png' class='user-image'   alt='+'>";
                                endif;
                                ?>
                                <span class='hidden-xs' style="color:#fff;"><?= $siswa['nama'] ?> &nbsp; <i class='fa fa-caret-down'></i></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <li class='user-header bg-blue'>
                                    <?php
                                    if ($siswa['foto'] <> '') :
                                        if (!file_exists("images/fotosiswa/$siswa[foto]")) :
                                            echo "<img src='images/user.png' class='img-circle' alt='User Image'>";
                                        else :
                                            echo "<img src='images/fotosiswa/$siswa[foto]' class='img-circle' alt='User Image'>";
                                        endif;
                                    else :
                                        echo "<img src='images/user.png' class='img-circle' alt='User Image'>";
                                    endif;
                                    ?>
                                    <p style="color:#fff">
                                        <?= $siswa['nama'] ?>
                                    </p>
                                </li>
                                <li class='user-footer'>
                                    
									<?php if($pg !='testongoing'): ?>
									<div class='pull-left'>
									<a href='?pg=profil' class='btn btn-sm btn-default btn-flat'><i class='fas  fa-user-plus'></i> <span> Profil</span></a>
                                       
									   </div>
									   <div class='pull-right'>
									   <a href='logout.php' class='btn btn-sm btn-default btn-flat'><i class='fa fa-sign-out'></i> Keluar</a>
										</div>
                                   <?php endif; ?>
								   
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
<aside class='main-sidebar'>
            <section class='sidebar'>
                <hr style="margin:0px">
                <div class='user-panel'>
                    <div class='pull-left image'>
                        <?php
                        if ($siswa['foto'] <> '') :
                            if (!file_exists("images/fotosiswa/$siswa[foto]")) :
                                echo "<img src='images/user.png' class='img'  style='max-width:60px' alt='+'>";
                            else :
                                echo "<img src='images/fotosiswa/$siswa[foto]' class='img'  style='max-width:60px' alt='+'>";
                            endif;
                        else :
                            echo "<img src='images/user.png' class='img'  style='max-width:60px' alt='+'>";
                        endif;
                        ?>
                    </div>
                    <div class='pull-left info' style='left:65px'>
                        <?php
                        if (strlen($siswa['nama']) > 15) {
                            $nama = substr($siswa['nama'], 0, 15) . "...";
                        } else {
                            $nama = $siswa['nama'];
                        }
                        ?>
                        <p title="<?= $siswa['nama'] ?>"><?= $nama ?></p>
						
                        <p><a href='#'><i class='fa fa-circle text-green'></i> online</a>
                        <p><span class="badge bg-blue"><?=strtoupper($siswa['jurusan']); ?></span> <span class="badge bg-green"><?=$siswa['kelas']?></span></p>
                   </div>
                </div><br>
                <hr style="margin:0px">
                <ul class='sidebar-menu tree' data-widget='tree' style="font-size:14px;">
				<br>
                    <li>&nbsp;&nbsp;<b>Menu Utama</b></li>
					<br>
					<li><a href='mydashboard'><span>Dashboard Utama</span></a></li>
					<li><a href='.'><span> UJIAN</span></a></li>
					<li><a href='?pg=hasil'><span> Nilai Ujian</span></a></li>
					<li><a href='silearn'><span> E-Learning</span></a></li>
					<li><a href='sipres'><span> E-Presensi</span></a></li>
					<br>
					<?php if($level==$skl['tingkat']): ?>
					<li>&nbsp;&nbsp;<b>Menu Kelulusan</b></li>
					<br>
					<?php if($tanggal >= $skl['dibuka'] AND $tanggal <= $skl['ditutup'] ){ ?>
					<li><a href="myskl/skl_siswa.php?nis=<?= $siswa['nis'] ?>" target="_blank"><span> Download S K L</span></a></li>
					<li><a href="myskl/skkb_siswa.php?nis=<?= $siswa['nis'] ?>" target="_blank"><span> Download S K K B</span></a></li>
					<?php } ?>
					<?php endif; ?>
					<li><a href='logout.php'><span> Log Out</span></a></li>
                </ul>
            </section>
        </aside>
        <div class='content-wrapper'>
		
            <section class='content-header' style="background:#326698;background-size: contain;height:100px;background-image: url('vendor/bg-top.png');background-repeat: no-repeat;">
			
            </section>
			
            <section class='content' style="margin-top:-95px">
                
          <?php if ($pg == '') : ?>
			<?php include 'home.php'; ?>
		<?php elseif ($pg == 'jadwal') : ?>
			<?php include 'jadwal.php'; ?>
		<?php elseif ($pg == 'profilsiswa') : ?>
			<?php include 'editsiswa/index.php'; ?>
		<?php elseif ($pg == 'profilsiswa') : ?>
			<?php include 'editsiswa/index.php'; ?>
		<?php elseif ($pg == 'testongoing') : ?>
			<?php include 'tes.php'; ?>
		<?php elseif ($pg == 'hasil') : ?>
			<?php include 'ujian/hasil.php'; ?>	
		<?php elseif ($pg == 'lihathasil') : ?>
			<?php include 'ujian/lihat.php'; ?>		
		<?php elseif ($pg == 'mintareset') : ?>
			<?php include 'ujian/mintareset.php'; ?>			
		<?php elseif ($pg == 'cekreset') : ?>
			<?php include 'ujian/cekreset.php'; ?>
		<?php elseif ($pg == 'profil') : ?>
			<?php include 'ujian/profil.php'; ?>
		<?php else : ?>
		<?php jump($homeurl); ?>
		<?php endif ?>
            </section>
        </div>
       
    </div>
     <script src='assets/zoom-master/jquery.zoom.js'></script>
    <script src='vendor/js/bootstrap.min.js'></script>
    <script src='vendor/iCheck/icheck.min.js'></script>
    <script src='vendor/js/app.min.js'></script>	
   <script src="assets/js/sweetalert2.min.js"></script>
	 <script src='assets/toastr/toastr.min.js'></script>	 
   <script src='assets/mousetrap/mousetrap.min.js'></script>
   <script src='assets/izitoast/js/iziToast.min.js'></script>
   <script>
        $('.loader').fadeOut('fast');
        var url = window.location;
        $('ul.sidebar-menu a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');
      
        $('ul.treeview-menu a').filter(function() {
            return this.href == url;
        }).closest('.treeview').addClass('active');
       
    </script>
   
        <script>
            var homeurl;
            homeurl = '.';
            var examActive = <?= ($pg == 'testongoing') ? 'true' : 'false' ?>;
            var violationCountdown = <?= ($pg == 'testongoing' && isset($mapel['pelanggaran'])) ? (int)$mapel['pelanggaran'] : 0 ?>;
            // Default mode ketat: 3 detik jika belum diset di jadwal
            if (!violationCountdown) { violationCountdown = 3; }
            var violationLock = false;
            var focusTimeoutHandle = null;

            function blockShortcutEvent(e) {
                if (!examActive) {
                    return;
                }
                if (e && typeof e.preventDefault === 'function') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }

            jQuery('body').on('contextmenu', function(e) {
                if (!examActive) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                return false;
            });

            jQuery(document).on('selectstart dragstart', function(e) {
                if (!examActive) {
                    return;
                }
                e.preventDefault();
                return false;
            });

            jQuery(document).on('keydown', function(e) {
                if (!examActive) {
                    return;
                }
                // Intercept Windows/Meta key, ContextMenu key, and Ctrl+Esc
                var kc = e.keyCode || e.which;
                var isMeta = (e.key === 'Meta') || kc === 91 || kc === 92; // Windows/Command key
                var isContextMenu = (e.key === 'ContextMenu') || kc === 93; // Menu key (often near right Ctrl)
                var isCtrlEsc = (e.ctrlKey || e.metaKey) && (e.key === 'Escape' || kc === 27);
                if (isMeta || isContextMenu || isCtrlEsc) {
                    blockShortcutEvent(e);
                    triggerViolation('Pintasan sistem diblokir saat ujian.');
                    return false;
                }
                if (e.key === 'Escape' || e.keyCode === 27) {
                    blockShortcutEvent(e);
                    triggerViolation('Tombol Escape diblokir saat ujian berlangsung.');
                }
            });

            // Extra guard on keyup for Meta/Windows key
            jQuery(document).on('keyup', function(e) {
                if (!examActive) return;
                var kc = e.keyCode || e.which;
                if ((e.key === 'Meta') || kc === 91 || kc === 92) {
                    blockShortcutEvent(e);
                    triggerViolation('Tombol Windows/Command tidak diperbolehkan.');
                    return false;
                }
            });

            if (typeof Mousetrap !== 'undefined') {
                var shortcutMessages = {
                    'mod+s': 'Pintasan simpan dinonaktifkan.',
                    'mod+shift+s': 'Pintasan simpan dinonaktifkan.',
                    'mod+p': 'Pintasan cetak dinonaktifkan.',
                    'mod+shift+p': 'Pintasan cetak dinonaktifkan.',
                    'mod+o': 'Pintasan membuka berkas dinonaktifkan.',
                    'mod+shift+o': 'Pintasan membuka berkas dinonaktifkan.',
                    'mod+u': 'Pintasan melihat sumber halaman dinonaktifkan.',
                    'mod+shift+i': 'Pintasan membuka alat pengembang dinonaktifkan.',
                    'mod+shift+j': 'Pintasan membuka alat pengembang dinonaktifkan.',
                    'mod+shift+c': 'Pintasan membuka alat pengembang dinonaktifkan.',
                    'mod+shift+del': 'Pintasan hapus data peramban dinonaktifkan.',
                    'mod+shift+esc': 'Pintasan pengelola tugas dinonaktifkan.',
                    'ctrl+shift+del': 'Pintasan hapus data peramban dinonaktifkan.',
                    'ctrl+shift+esc': 'Pintasan pengelola tugas dinonaktifkan.',
                    'ctrl+alt+del': 'Pintasan sistem tidak diperbolehkan.',
                    'mod+w': 'Menutup tab ujian tidak diperbolehkan.',
                    'mod+shift+w': 'Menutup tab ujian tidak diperbolehkan.',
                    'mod+n': 'Membuka jendela baru tidak diperbolehkan.',
                    'mod+shift+n': 'Membuka jendela baru tidak diperbolehkan.',
                    'mod+l': 'Mengakses bilah alamat tidak diperbolehkan.',
                    'mod+r': 'Memuat ulang halaman ujian tidak diperbolehkan.',
                    'alt+f4': 'Menutup jendela ujian tidak diperbolehkan.',
                    'alt+tab': 'Berpindah aplikasi tidak diperbolehkan.',
                    'meta+tab': 'Berpindah aplikasi tidak diperbolehkan.',
                    'mod+tab': 'Berpindah aplikasi tidak diperbolehkan.',
                    'f5': 'Memuat ulang halaman ujian tidak diperbolehkan.',
                    'ctrl+shift+r': 'Memuat ulang halaman ujian tidak diperbolehkan.',
                    'f11': 'Mengubah mode layar tidak diperbolehkan.',
                    'f12': 'Pintasan membuka alat pengembang dinonaktifkan.'
                };
                Object.keys(shortcutMessages).forEach(function(combo) {
                    Mousetrap.bind(combo, function(e) {
                        blockShortcutEvent(e);
                        triggerViolation(shortcutMessages[combo]);
                        return false;
                    });
                });
            }

            document.addEventListener('keydown', function(e) {
                if (!examActive) {
                    return;
                }
                var forbiddenKeys = [112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123];
                if (forbiddenKeys.indexOf(e.keyCode) !== -1) {
                    blockShortcutEvent(e);
                    triggerViolation('Fungsi tombol F tidak diperbolehkan saat ujian.');
                }
                if ((e.altKey || e.metaKey) && e.keyCode === 9) {
                    blockShortcutEvent(e);
                    triggerViolation('Berpindah dari tampilan ujian tidak diperbolehkan.');
                }
            }, true);

            if (examActive) {
                window.addEventListener('blur', function() {
                    focusTimeoutHandle = setTimeout(function() {
                        if (!document.hasFocus()) {
                            triggerViolation('Fokus ujian hilang.');
                        }
                    }, 300);
                });

                window.addEventListener('focus', function() {
                    if (focusTimeoutHandle) {
                        clearTimeout(focusTimeoutHandle);
                        focusTimeoutHandle = null;
                    }
                    if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.mozFullScreenElement && !document.msFullscreenElement) {
                        openFullscreen();
                    }
                });

                document.addEventListener('visibilitychange', function() {
                    if (document.hidden) {
                        triggerViolation('Tampilan ujian ditinggalkan.');
                    }
                });
            }

                function selesai() {
                    var idmapel = '<?= $id_bank  ?>';
                    var idsiswa = '<?= $id_siswa  ?>';
                    $.ajax({
                        type: 'POST',
                       url: homeurl + '/selesai.php',
                        data: {
                            
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            id_ujian: <?= $ac ?>
                        },
                        beforeSend: function() {
                            $('.loader').css('display', 'block');
                        },
                        success: function(response) {
                           
                            $('.loader').css('display', 'none');
                            location.href=homeurl;
                           
                           
                        }
                    });
                }    
        var elem = document.documentElement;

        async function openFullscreen() {
            try {
                if (elem.requestFullscreen) {
                    await elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) {
                    await elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) {
                    await elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) {
                    elem = window.top.document.body;
                    await elem.msRequestFullscreen();
                }
                if (examActive && navigator.keyboard && navigator.keyboard.lock) {
                    try {
                        await navigator.keyboard.lock([
                            'Escape','F1','F2','F3','F4','F5','F6','F7','F8','F9','F10','F11','F12'
                        ]);
                    } catch (e) {
                        // Keyboard Lock API might be unavailable or require user gesture
                    }
                }
            } catch (e) {
                // ignore
            }
        }
        if (examActive) {
            swal({
                title: 'Info Ujian',
                html: 'Selamat Mengerjakan',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    openFullscreen();
                }
            });
        }

        if (examActive && document.addEventListener) {
            document.addEventListener('fullscreenchange', exitHandler, false);
            document.addEventListener('mozfullscreenchange', exitHandler, false);
            document.addEventListener('MSFullscreenChange', exitHandler, false);
            document.addEventListener('webkitfullscreenchange', exitHandler, false);
            // try re-lock keyboard upon re-entering fullscreen
            document.addEventListener('fullscreenchange', function(){
                var isFullScreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
                if (isFullScreen && navigator.keyboard && navigator.keyboard.lock) {
                    navigator.keyboard.lock(['Escape','F1','F2','F3','F4','F5','F6','F7','F8','F9','F10','F11','F12']).catch(function(){});
                } else if (!isFullScreen && navigator.keyboard && navigator.keyboard.unlock) {
                    navigator.keyboard.unlock();
                }
            }, false);
        }

        function exitHandler() {
            if (!examActive) {
                return;
            }
            var isFullScreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || document.webkitIsFullScreen || document.mozFullScreen;
            if (!isFullScreen) {
                triggerViolation('Mode layar penuh dimatikan.');
            }
        }

        // Extra guard to warn on close/refresh while ujian aktif
        if (examActive) {
            window.addEventListener('beforeunload', function (e) {
                e.preventDefault();
                e.returnValue = '';
            });
        }

        function triggerViolation(reason) {
            if (!examActive) {
                return;
            }
            if (focusTimeoutHandle) {
                clearTimeout(focusTimeoutHandle);
                focusTimeoutHandle = null;
            }
            if (violationLock) {
                return;
            }
            violationLock = true;
            var message = reason || 'Dilarang meninggalkan tampilan ujian.';
            if (violationCountdown > 0 && typeof selesai === 'function') {
                var closeInSeconds = violationCountdown;
                var template = message + ' — Ujian terpaksa selesai dalam #1 detik lagi';
                var timer = setInterval(function() {
                    closeInSeconds--;
                    if (closeInSeconds < 0) {
                        clearInterval(timer);
                        violationLock = false;
                        selesai();
                        return;
                    }
                    $('.swal2-content, .swal2-html-container').text(template.replace(/#1/, closeInSeconds));
                }, 1000);
                swal({
                    title: 'Pelanggaran!',
                    html: template.replace(/#1/, closeInSeconds),
                    confirmButtonText: 'Kembali ke ujian',
                    allowOutsideClick: false
                }).then((result) => {
                    clearInterval(timer);
                    violationLock = false;
                    if (result.value) {
                        openFullscreen();
                    }
                }).catch(() => {
                    clearInterval(timer);
                    violationLock = false;
                });
            } else {
                swal({
                    title: 'Peringatan',
                    text: message,
                    confirmButtonText: 'Kembali ke ujian',
                    allowOutsideClick: false
                }).then((result) => {
                    violationLock = false;
                    if (result.value) {
                        openFullscreen();
                    }
                }).catch(() => {
                    violationLock = false;
                });
            }
        }
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };
            
            var homeurl;
            homeurl = '<?= $homeurl ?>';
            $(document).ready(function() {
                $("#modalnosoal").on('shown.bs.modal', function() {
                    var idmapel = '<?= $id_bank  ?>';
                    var idsiswa = '<?= $id_siswa  ?>';
                    var pengacak = JSON.parse(localStorage.getItem('pengacakpg'));
                    var pengacakpil = JSON.parse(localStorage.getItem('pengacakpil'));
                    $.ajax({
                        type: 'POST',
                        url: homeurl + '/nosoal.php',
                        data: {
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            pengacak: pengacak,
                            pengacakpil: pengacakpil,
                            idu: <?= $ac ?>
                        },
                        success: function(response) {
                            
                            $('#loadnosoal').html(response);

                        }
                    });
                });
            });


            function soalpertama() {
                var idmapel = '<?= $id_bank  ?>';
                var idsiswa = '<?= $id_siswa  ?>';
                var soalsoal = JSON.parse(localStorage.getItem('soallokal'));
                var ujianya = JSON.parse(localStorage.getItem('ujianya'));
                var pengacak = JSON.parse(localStorage.getItem('pengacakpg'));
                var pengacakpil = JSON.parse(localStorage.getItem('pengacakpil'));
                $.ajax({
                    type: 'POST',
                    url: homeurl + '/soal.php',
                    data: {
                        pg: 'soal',
                        id_bank: idmapel,
                        id_siswa: idsiswa,
                        no_soal: 0,
                        ujian: ujianya,
                        soal: soalsoal,
                        pengacak: pengacak,
                        pengacakpil: pengacakpil,
                        idu: <?= $ac ?>
                    },
                    beforeSend: function() {
						$('#loading-image').show();
					},
                    success: function(response) {
                        num = 1;
                        $('#loading-image').hide();
                        $('#displaynum').html(num);
                        $('#loadsoal').html(response);
                        $('.fa-spin').hide();
                        
                        soalFont(fontSize);
                        
                    }
                });
            }
            soalpertama();
            /* Font Adjusments */
            let defaultFontSize = 10;
            let fontSize = 0;
            fontSize = localStorage.getItem('fontSize');
            if (!fontSize) {
                fontSize = defaultFontSize;
                localStorage.setItem('fontSize', fontSize);
            }
            soalFont(fontSize);

            function soalFont(fontSize) {
                $('div.soal > p > span').css({
                    fontSize: fontSize + 'pt'
                });
                $('span.soal > p > span').css({
                    fontSize: fontSize + 'pt'
                });
                $('.soal').css({
                    fontSize: fontSize + 'pt'
                })
                $('.callout soal').css({
                    fontSize: fontSize + 'pt'
                })
            }

            $(document).ready(function() {
                $('#smaller_font').on('click', function() {
                    fontSize = localStorage.getItem('fontSize')
                    fontSize--;
                    localStorage.setItem('fontSize', fontSize)
                    soalFont(fontSize)
                });

                $('#bigger_font').on('click', function() {
                    fontSize = localStorage.getItem('fontSize')
                    fontSize++;
                    localStorage.setItem('fontSize', fontSize)
                    soalFont(fontSize)
                });

                $('#reset_font').on('click', function() {
                    fontSize = defaultFontSize
                    localStorage.setItem('fontSize', fontSize)
                    soalFont(fontSize)
                });
                function selesai() {
                    var idmapel = '<?= $id_bank  ?>';
                    var idsiswa = '<?= $id_siswa  ?>';
                    $.ajax({
                        type: 'POST',
                        url: homeurl + '/selesai.php',
                        data: {
                            
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            id_ujian: <?= $ac ?>
                        },
                        beforeSend: function() {
                            $('.loader').css('display', 'block');
                        },
                        success: function(response) {
                           
                            $('.loader').css('display', 'none');
                            location.href=homeurl;
                           
                           
                        }
                    });
                }
                $(document).on('click', '.done-btn', function() {
                    var idmapel = '<?= $id_bank  ?>';
                    var idsiswa = '<?= $id_siswa  ?>';
                    $.ajax({
                        type: 'POST',
                        url: homeurl + '/cekselesai.php',
                        data: {
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            id_ujian: <?= $ac ?>
                        },
                        success: function(response) {
                            if (response == 'ok') {
                                swal({
                                    title: 'Apa kamu yakin telah selesai?',
                                    html: 'Pastikan telah menyelesaikan semua dengan benar!',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Iya'
                                }).then((result) => {
                                    if (result.value) {
                                        
                                       selesai();
                                    }
                                })
                            } else if (response == 'ragu') {
                                swal({
                                    type: 'warning',
                                    title: 'Peringatan',
                                    html: 'Masih ada soal yang masih ragu!!',
                                })
                            } else {
                                swal({
                                    type: 'warning',
                                    title: 'Peringatan',
                                    html: 'Masih ada soal yang belum dikerjakan!!',
                                })
                            }

                        }
                    });

                });
                
                var result = '';
                $('.jawabesai').change(function() {
                    result = $(this).val();
                    $('#result').html(result);
                });

                var jam = $('#htmljam').html();
                var menit = $('#htmlmnt').html();
                var detik = $('#htmldtk').html();

                function hitung() {
                    setTimeout(hitung, 1000);
                    $('#countdown').html(jam + ':' + menit + ':' + detik);
                    detik--;
                    if (detik < 0) {
                        detik = 59;
                        menit--;
                        if (menit < 0) {
                            menit = 59;
                            jam--;
                            if (jam < 0) {
                                jam = 0;
                                menit = 0;
                                detik = 0;
                                selesai();
                            }
                        }
                    }
                }
                hitung();

            });

            function waktuhabis() {
                swal({
                    title: 'Peringatan!',
                    text: 'Waktu Ujian Telah Habis',
                    timer: 1000,
                    onOpen: () => {
                        swal.showLoading()
                    }
                }).then((result) => {
                    selesai();
                });
            }

            function loadsoal(idmapel, idsiswa, nosoal) {

                if (nosoal >= 0 && nosoal<<?= $jumsoal ?>) {
                    curnum = $('#displaynum').html();
                    if (nosoal == curnum) {
                        $('#spin-next').show();
                    }
                    if (nosoal > curnum) {
                        $('#spin-next').show();
                    }
                    if (nosoal < curnum) {
                        $('#spin-prev').show();
                    }
                    var ujianya = JSON.parse(localStorage.getItem('ujianya'));
                    var soalsoal = JSON.parse(localStorage.getItem('soallokal'));
                    var pengacak = JSON.parse(localStorage.getItem('pengacakpg'));
                    var pengacakpil = JSON.parse(localStorage.getItem('pengacakpil'));
                    $.ajax({
                        type: 'POST',
                        url: homeurl + '/soal.php',
                        data: {
                            pg: 'soal',
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            no_soal: nosoal,
                            soal: soalsoal,
                            pengacak: pengacak,
                            pengacakpil: pengacakpil,
                            ujian: ujianya

                        },
                        success: function(response) {
                            num = nosoal + 1;
                            $('#displaynum').html(num);
                            $('#loadsoal').html(response);
                            $('.fa-spin').hide();
                            $("#modalnosoal").modal('hide');
                            soalFont(fontSize);
                            
                        }
                    });
                }
            }

            function jawabsoal(idmapel, idsiswa, idsoal, jawab, jawabQ, jenis, idu) {

                
                $.ajax({
                    type: 'POST',
                    url: homeurl + '/soal.php',
                    data: {
                        pg: 'jawab',
                        id_bank: idmapel,
                        id_siswa: idsiswa,
                        id_soal: idsoal,
                        jawaban: jawab,
                        jenis: jenis,
                        idu: idu,
                        jawabx: jawabQ
                    },
                    success: function(response) {
                      
                        if (response == 'OK') {
                            $('#nomorsoal #badge' + idsoal).removeClass('bg-gray');
                            $('#nomorsoal #badge' + idsoal).removeClass('bg-yellow');
                            $('#nomorsoal #badge' + idsoal).addClass('bg-green');
                            $('#nomorsoal #jawabtemp' + idsoal).html(jawabQ);
                            $('#ketjawab').load(window.location.href + ' #ketjawab');
                        }
                    }
                });
            }

			
			  function jawabbs(idmapel, idsiswa, idsoal, jawabbs, jawabbs2, jawabQ, jenis, idu) {

                
                $.ajax({
                    type: 'POST',
                    url: homeurl + '/soal.php',
                    data: {
                        pg: 'jawabbs',
                        id_bank: idmapel,
                        id_siswa: idsiswa,
                        id_soal: idsoal,
                        jawabbs: jawabbs,
						jawabbs2: jawabbs2,
                        jenis: jenis,
                        idu: idu,
                        jawabx: jawabQ
                    },
                    success: function(response) {
                        
                        if (response == 'OK') {
                            $('#nomorsoal #badge' + idsoal).removeClass('bg-gray');
                            $('#nomorsoal #badge' + idsoal).removeClass('bg-yellow');
                            $('#nomorsoal #badge' + idsoal).addClass('bg-green');
                            $('#nomorsoal #jawabtemp' + idsoal).html(jawabQ);
                            $('#ketjawab').load(window.location.href + ' #ketjawab');
                        }
                    }
                });
            }

			
            function jawabesai(idmapel, idsiswa, idsoal, jenis) {
                var jawab = $('#jawabesai').val();
                $.ajax({
                    type: 'POST',
                    url: homeurl + '/soal.php',
                    data: {
                        pg: 'jawab',
                        id_bank: idmapel,
                        id_siswa: idsiswa,
                        id_soal: idsoal,
                        jawaban: jawab,
                        jenis: jenis,
                        idu: <?= $ac ?>
                    },
                    success: function(response) {
                        if (response == 'OK') {
                            toastr.success("jawaban berhasil disimpan");
                            $('#badge' + idsoal).removeClass('bg-gray');
                            $('#badge' + idsoal).removeClass('bg-yellow');
                            $('#badge' + idsoal).addClass('bg-green');
                            $('#ketjawab').load(window.location.href + ' #ketjawab');
                        }

                    }
                });
            }

            function radaragu(idmapel, idsiswa, idsoal) {
                cekclass = $('#nomorsoal #badge' + idsoal).attr('class');
                if (cekclass != 'btn btn-app bg-gray') {
                    $.ajax({
                        type: 'POST',
                        url: homeurl + '/soal.php',
                        data: {
                            pg: 'ragu',
                            id_bank: idmapel,
                            id_siswa: idsiswa,
                            id_soal: idsoal
                           
							
                        },
                        success: function(response) {
                            console.log(response);
                            if (response == 'OK') {
                                if (cekclass == 'btn btn-app bg-green') {
                                    $('#nomorsoal #badge' + idsoal).removeClass('bg-gray');
                                    $('#nomorsoal #badge' + idsoal).removeClass('bg-green');
                                    $('#nomorsoal #badge' + idsoal).addClass('bg-yellow');
                                    console.log('kuning');
                                }
                                if (cekclass == 'btn btn-app bg-yellow') {
                                    $('#nomorsoal #badge' + idsoal).removeClass('bg-gray');
                                    $('#nomorsoal #badge' + idsoal).removeClass('bg-yellow');
                                    $('#nomorsoal #badge' + idsoal).addClass('bg-green');
                                    console.log('hijau');
                                }
                            }
                        }
                    });
                } else {
                    $('#load-ragu input').removeAttr('checked');
                }
            }
        </script>
		
	<script>
	function kelapKelip() {
			$('.kedip').fadeOut(); 
			$('.kedip').fadeIn(); 
			}
			setInterval(kelapKelip, 500);
	</script>
	
   <script type="text/javascript">
						$(document).ready(function(){
							setInterval(function(){
								$("#waktu").load('waktu.php');
							}, 1000);  
						});
					</script> 
	
</body>

</html>
 
