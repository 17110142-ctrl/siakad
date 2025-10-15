<?php
ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
include "../../vendor/phpqrcode/qrlib.php";
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$mesin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mesin_absen WHERE id='$setting[mesin]'"));

// =================================================================
// REVISI: Mengambil rentang siswa berdasarkan NIS yang dipilih
// =================================================================

// 1. Ambil NIS awal dan akhir dari POST
$start_nis = $_POST['id'];
$end_nis = $_POST['ids'];

// 2. Dapatkan id_siswa untuk NIS awal
$start_result = mysqli_query($koneksi, "SELECT id_siswa FROM siswa WHERE nis = '$start_nis'");
$start_row = mysqli_fetch_assoc($start_result);
$start_id = $start_row['id_siswa'];

// 3. Dapatkan id_siswa untuk NIS akhir
$end_result = mysqli_query($koneksi, "SELECT id_siswa FROM siswa WHERE nis = '$end_nis'");
$end_row = mysqli_fetch_assoc($end_result);
$end_id = $end_row['id_siswa'];

// 4. Pastikan ID awal lebih kecil dari ID akhir
if ($start_id > $end_id) {
    // Jika terbalik, tukar nilainya
    list($start_id, $end_id) = array($end_id, $start_id);
}

// 5. Gunakan rentang ID yang sudah benar untuk query
$absQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa BETWEEN '$start_id' AND '$end_id'");
// =================================================================
// Akhir Revisi
// =================================================================


while ($sis = mysqli_fetch_array($absQ)) :
    $tempdir = "../../temp/";
    if (!file_exists($tempdir))
        mkdir($tempdir);
    $codeContents = $sis['nis'];
    QRcode::png($codeContents, $tempdir . $sis['nis'] . '.png', QR_ECLEVEL_M, 6);
endwhile;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>KARPEL</title>
    <style>
        @page {
            /* UBAH DI SINI: Margin halaman diatur menjadi 1cm untuk semua sisi */
            margin: 1cm;
        }
        body {
            /* UBAH DI SINI: Margin body dihapus agar tidak ada margin ganda */
            margin: 0;
            font-family: Calibri, Helvetica, Arial, sans-serif;
        }
        .card-container {
            position: relative;
            width: 208px;
            height: 328px;
            margin: 0 auto;
        }
        .content-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- UBAH DI SINI: margin-top pada tabel dihapus agar mengikuti margin @page */ -->
    <table width='100%' align='center' cellpadding='0px' style="border-spacing: 0;">
        <tr>

            <?php $no = 0; ?>
            <?php $siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa BETWEEN '$start_id' AND '$end_id'"); ?>

            <?php while ($r = mysqli_fetch_array($siswaQ)) : ?>
                <?php
                if (strlen($r['nama']) > 26) {
                    $namamu = substr($r['nama'], 0, 26) . "...";
                } else {
                    $namamu = $r['nama'];
                }
                $no++;
                ?>

                <td width='33%' style="vertical-align: top; padding-bottom: 20px;">
                    <div class="card-container">
                        <!-- Gambar Latar Kartu -->
                        <img src="../../images/kartu/<?= $mesin['depan'] ?>" width="208px" height="328px">

                        <!-- Lapisan Konten (QR Code & Teks) -->
                        <div class="content-overlay">
                            
                            <!-- QR Code diperbesar dan dipindah ke atas -->
                            <img src="../../temp/<?= $r['nis'] ?>.png" style="margin-top: 50px; width:150px; height:150px;">

                            <!-- Posisi Nama Siswa disesuaikan -->
                            <p style="margin-top: 15px; font-size:12px; font-weight:bold; color:#00008B; padding: 0 15px;">
                                <?= strtoupper($namamu) ?>
                            </p>

                            <!-- Posisi Keterangan STUDENT & NIS disesuaikan -->
                            <p style="margin-top: 5px; font-size:10px; color:#00008B;">
                                STUDENT<br>
                                <b style="font-size:14px;color:#008B8B;"><?= $r['nis'] ?></b>
                            </p>
                        </div>
                    </div>
                </td>

                <?php if (($no % 3) == 0) : ?>
        </tr>
        <tr>
        <?php endif; ?>
    <?php endwhile; ?>

        </tr>
    </table>

</body>
</html>
<?php
$html = ob_get_clean();
require_once '../../vendor/vendors/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("KARPEG.pdf", array("Attachment" => false));
exit(0);
?>
