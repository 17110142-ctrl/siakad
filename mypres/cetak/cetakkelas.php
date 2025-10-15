<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
($id_user == 0) ? header('location:login.php') : null;
$kelas = $_POST['kelas'];
$bl = $_POST['bulan'];
$tahun = date('Y');

$user = fetch($koneksi, 'users', array('walas' => $kelas));
$bulane = fetch($koneksi, 'bulan', ['bln' => $bl]);

// PERUBAHAN: Mengambil level kelas (misal: 'VII' dari 'VII A')
$level_kelas = mysqli_real_escape_string($koneksi, explode(" ", $kelas)[0]);

// PERUBAHAN: Mengambil hari libur yang berlaku untuk 'Semua' atau level kelas yang spesifik
$query_libur = mysqli_query($koneksi, "SELECT tanggal FROM hari_libur WHERE MONTH(tanggal) = '$bl' AND YEAR(tanggal) = '$tahun' AND (kelas IS NULL OR kelas = '$level_kelas')");
$hari_libur_nasional = [];
while ($row = mysqli_fetch_assoc($query_libur)) {
    $hari_libur_nasional[] = date('j', strtotime($row['tanggal']));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Rekap Absen Kelas <?= $kelas ?></title>
    <link rel='stylesheet' href='../../vendor/css/cetak.min.css'>
</head>
<style>
    @page { margin: 80px; }
    body { margin: 20px; }
    .libur { background-color: #ffcccc; color: red; } /* Style untuk sel hari libur */
</style>
<body style="font-size: 13px;">

<div style='background:#fff; width:97%; margin:0 auto; height:90%;'>
    <!-- KOP SURAT ANDA DI SINI -->
    <table width='100%'>
        <tr>
            <td width='100'><img src='../../images/<?= $setting['logo'] ?>' width='70'></td>
            <td style="text-align:center">
                <strong class='f12'>
                    <?= strtoupper($setting['header']) ?><br>
                    <?= strtoupper($setting['sekolah']) ?> </strong><br>
                <small>Alamat : <?= $setting['alamat'] ?> Kec. <?= $setting['kecamatan'] ?> Kab. <?= $setting['kabupaten'] ?> Email : <?= $setting['email'] ?></small>
            </td>
        </tr>
    </table>
    <hr style="margin:1px"><hr style="margin:2px"><br>
    <center><h4>REKAPITULASI ABSENSI KELAS</h4></center><br>
    <table width="100%">
        <tr>
            <td width="10%"></td>
            <td width='100px'>Sekolah</td><td width='10px'>:</td><td><?= $setting['sekolah'] ?></td>
            <td width="70%"></td>
            <td width='100px'>Bulan</td><td width='10px'>:</td><td><?= $bulane['ket'] ?> <?= $tahun ?></td>
        </tr>
        <tr>
            <td width="10%"></td>
            <td width='100px'>Kelas</td><td width='10px'>:</td><td><?= $kelas ?></td>
            <td></td>
            <td width='100px'>Smt - TP</td><td width='10px'>:</td><td><?= $setting['semester'] ?> - <?= $setting['tp'] ?></td>
        </tr>
    </table>
    <br>
    <table class='it-grid it-cetak' width='100%'>
        <tr>
            <th width="2%" height="40px">No</th>
            <th>Nama Siswa</th>
            <?php
            $bulan = $bl;
            $tanggal_per_bulan = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            for ($i = 1; $i <= $tanggal_per_bulan; $i++) {
                $date_obj = new DateTime("$tahun-$bulan-$i");
                $day_of_week = $date_obj->format("D");
                $is_libur_nasional = in_array($i, $hari_libur_nasional);
                
                $is_libur_mingguan = false;
                if (($setting['hari_sekolah'] == 5 && ($day_of_week == 'Sat' || $day_of_week == 'Sun')) || ($setting['hari_sekolah'] == 6 && $day_of_week == 'Sun')) {
                    $is_libur_mingguan = true;
                }
                
                $style = ($is_libur_mingguan || $is_libur_nasional) ? 'style="color:red"' : '';
            ?>
                <th width="2%" <?= $style ?>><?= $i ?></th>
            <?php } ?>
            <th width="1%">H</th><th width="1%">S</th><th width="1%">I</th><th width="1%">A</th>
        </tr>
        <?php
        // PERUBAHAN: Menambahkan ORDER BY nama ASC untuk mengurutkan siswa berdasarkan nama
        $query_siswa = mysqli_query($koneksi, "select id_siswa,kelas,nama from siswa WHERE kelas='$kelas' GROUP BY id_siswa ORDER BY nama ASC");
        $no = 0;
        while ($siswa = mysqli_fetch_array($query_siswa)) {
            $no++;
            $hadir = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$siswa[id_siswa]' AND ket='H' AND bulan='$bulan' AND tahun='$tahun' "));
            $sakit = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$siswa[id_siswa]' AND ket='S' AND bulan='$bulan' AND tahun='$tahun' "));
            $izin = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$siswa[id_siswa]' AND ket='I' AND bulan='$bulan' AND tahun='$tahun' "));
            $alpha = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi WHERE idsiswa='$siswa[id_siswa]' AND ket='A' AND bulan='$bulan' AND tahun='$tahun' "));
        ?>
            <tr>
                <td class="text-center"><?= $no; ?></td>
                <td>&nbsp;&nbsp;<?= ucwords(strtolower($siswa['nama'])) ?></td>
                <?php
                for ($i = 1; $i <= $tanggal_per_bulan; $i++) {
                    $tanggalbaru = date('Y-m-d', mktime(0, 0, 0, $bulan, $i, $tahun));
                    $date_obj = new DateTime($tanggalbaru);
                    $day_of_week = $date_obj->format("D");
                    $is_libur_nasional = in_array($i, $hari_libur_nasional);

                    $is_libur_mingguan = false;
                    if (($setting['hari_sekolah'] == 5 && ($day_of_week == 'Sat' || $day_of_week == 'Sun')) || ($setting['hari_sekolah'] == 6 && $day_of_week == 'Sun')) {
                        $is_libur_mingguan = true;
                    }
                    
                    $is_libur_total = $is_libur_mingguan || $is_libur_nasional;
                    $class_libur = $is_libur_total ? 'libur' : '';
                    $cekabsen = fetch($koneksi, 'absensi', ['tanggal' => $tanggalbaru, 'idsiswa' => $siswa['id_siswa']]);
                    
                    echo "<td class='text-center {$class_libur}'>";
                    if ($cekabsen) {
                        echo "<b>" . $cekabsen['ket'] . "</b>";
                    } elseif ($is_libur_total) {
                        echo "X";
                    }
                    echo "</td>";
                }
                ?>
                <td class="text-center"><?= $hadir; ?></td>
                <td class="text-center"><?= $sakit; ?></td>
                <td class="text-center"><?= $izin; ?></td>
                <td class="text-center"><?= $alpha; ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>
    <p>H : HADIR &nbsp;&nbsp;&nbsp; S : SAKIT &nbsp;&nbsp;&nbsp; I : IZIN &nbsp;&nbsp;&nbsp; A : TANPA KETERANGAN &nbsp;&nbsp;&nbsp; X : LIBUR</p>
    <br>
    <!-- TANDA TANGAN -->
    <table width='100%'>
        <tr>
            <td width="5%"></td><td width='50px'></td>
            <td>
                Mengetahui, <br />Kepala Sekolah<br /><br /><br /><br />
                <u><?= $setting['kepsek'] ?></u><br /><?= $setting['no_guru'] ?> <?= $setting['nip'] ?>
            </td>
            <td width='40%'></td><td width="5%"></td>
            <td>
                <?= ucwords(strtolower($setting['kabupaten'])); ?>, <?php echo date("t", time()); ?> <?= $bulane['ket'] ?> <?= date('Y') ?><br />
                Wali Kelas <?= $kelas ?><br /><br /><br /><br />
                <u><?= $user['nama'] ?></u><br />
                <?= $setting['no_guru'] ?> <?= $user['no_guru'] ?>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
$html = ob_get_clean();
require_once '../../vendor/vendors/autoload.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'Landscape');
$dompdf->render();
$dompdf->stream("Rekap Absen Kelas " . $kelas . " Bulan " . $bl . ".pdf", array("Attachment" => false));
exit(0);
?>
