<style>
    * {
        font-size: x-small;
    }

    .box {
        border: 1px solid #000;
        width: 100%;
        height: 150px;
    }

    .ukuran {
        font-size: 15px;
    }

    .ukuran2 {
        font-size: 12px;
    }

    .user {
        font-size: 15px;
    }

    .card-background {
        background-image: url('<?= $homeurl ?>/images/latarkartu.png');
        background-size: cover;
        background-position: center;
        width: 10.5cm;
        border: 1px solid #666;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    @media print {
        .card-background {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    }
</style>

<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
include "../../vendor/phpqrcode/qrlib.php";

(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
($id_user == 0) ? header('location:index.php') : null;

$kelas = @$_GET['kelas'];

if (date('m') >= 7 and date('m') <= 12) {
    $ajaran = date('Y') . "/" . (date('Y') + 1);
} elseif (date('m') >= 1 and date('m') <= 6) {
    $ajaran = (date('Y') - 1) . "/" . date('Y');
}

$kelasX = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM kelas WHERE kelas='$kelas'"));

$absQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas'");		
while ($sis = mysqli_fetch_array($absQ)) : 		  
    $tempdir = "../../temp/"; 
    if (!file_exists($tempdir)) 
        mkdir($tempdir);
    $codeContents = $sis['nis'] . '-' . $sis['nama'];
    QRcode::png($codeContents, $tempdir . $sis['nis'] . '.png', QR_ECLEVEL_M, 4);
endwhile;

$siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='$kelas' ORDER BY nama ASC");
$no = 0;
echo "<table width='100%' align='center' cellpadding='10'><tr>";

while ($siswa = mysqli_fetch_array($siswaQ)) :
    $no++;
?>
    <td width='50%' style="page-break-inside: avoid; vertical-align: top;">
        <div class="card-background">
            <table style="text-align:center; width:100%">
                <tr>
                    <td style="text-align:left; vertical-align:top">
                        <img src="<?= $homeurl; ?>/images/<?= $setting['logo'] ?>" height='60px'>
                    </td>
                    <td style="text-align:center">
                        <b class="ukuran">
                            <?= strtoupper($setting['header_kartu']) ?><br>
                            <?= strtoupper($setting['sekolah']) ?><br>
                            TAHUN PELAJARAN <?= $ajaran ?>
                        </b>
                    </td>
                    <td style="text-align:right; vertical-align:top">
                        <img src="<?= $homeurl; ?>/images/<?= $setting['logokanan'] ?>" height='60px' />
                    </td>
                </tr>
            </table>
            <hr>
            <table style="text-align:left; width:100%">
                <tr>
                    <td style="text-align:center; vertical-align:top; width:100px" rowspan="8">
                        <?php
                        if ($siswa['foto'] != '') {
                            if (file_exists("../../images/fotosiswa/$siswa[foto]")) {
                                echo "<img src='$homeurl/images/fotosiswa/$siswa[foto]' class='img' style='max-width:60px'>";
                            } else {
                                echo "<img src='$homeurl/images/user.png' class='img' style='max-width:60px'>";
                            }
                        } else {
                            echo "<img src='$homeurl/images/user.png' class='img' style='max-width:60px'>";
                        }
                        ?>
                        <br>
                        <img src="<?= $homeurl ?>/temp/<?= $siswa['nis'] ?>.png" width="90px">
                    </td>
                </tr>
                <tr>
                    <td class="ukuran" valign='top' width="30%">No Peserta</td>
                    <td class="ukuran" valign='top'>: <?= $siswa['no_peserta'] ?></td>
                </tr>
                <tr>
                    <td class="ukuran" valign='top'>Nama</td>
                    <td class="ukuran2" valign='top'>: <?= substr($siswa['nama'], 0, 21) ?></td>
                </tr>
                <tr>
                    <td class="ukuran" valign='top'>Kelas / Sesi Ujian</td>
                    <td class="ukuran" valign='top'>: <?= $kelasX['kelas'] ?> / Sesi <?= $siswa['sesi'] ?></td>
                </tr>
                <tr>
                    <td class="ukuran" valign='top'>Username</td>
                    <td class="ukuran" valign='top'>: <b class="user"><?= $siswa['username'] ?></b></td>
                </tr>
                <tr>
                    <td class="ukuran" valign='top'>Password</td>
                    <td class="ukuran" valign='top'>: <b class="user"><?= $siswa['password'] ?></b></td>
                </tr>
                <tr>
                    <td valign='top'></td>
                    <td class="ukuran2" valign='top' align='center'>
                        <div style="line-height: 1; margin-bottom: 0;">
                            <strong>Kepala Sekolah</strong>
                        </div>
                        <div style="display: flex; justify-content: center; align-items: center; line-height: 1; margin: 0;">
                            <img src="<?= $homeurl ?>/images/<?= $setting['stempel'] ?>" width="90px" style="margin-right: -30px; z-index: 1;">
                            <img src="<?= $homeurl ?>/images/<?= $setting['ttd'] ?>" width="90px" style="z-index: 2;">
                        </div>
                        <div style="line-height: 1; margin-top: 0;">
                            <b><?= $setting['kepsek'] ?></b><br>
                            <b>NIP. <?= $setting['nip'] ?></b>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </td>

<?php
    if ($no % 2 == 0) echo "</tr><tr>";
    if ($no % 6 == 0) echo "</tr></table><div style='page-break-after: always;'></div><table width='100%' align='center' cellpadding='10'><tr>";
endwhile;

echo "</tr></table>";
?>
