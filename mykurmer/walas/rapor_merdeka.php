<?php ob_start();
error_reporting(0);
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
session_start();
if (!isset($_SESSION['id_user'])) {
    die('Anda tidak diijinkan mengakses langsung');
}
$tahun=date('Y');
$nis=$_GET['ids'];

$siswa = fetch($koneksi, 'siswa', ['nis' => $nis]);
$klas=$siswa['kelas'];
$tingkat=$siswa['level'];
$walas = fetch($koneksi, 'users', ['walas' => $klas]);

	if($setting['semester']=='1'){
{$smt="(Satu)";}
}elseif($setting['semester']=='2'){
{$smt="(Dua)";}
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Raport_<?= $siswa['nama'] ?></title>
    <style>
        /* Tambahkan style ini untuk kontrol yang lebih baik */
        @page {
        /* Memberi margin atas, kanan, kiri sebesar 30px */
        margin: 30px; 
        /* KHUSUS margin bawah, kita beri ruang 2cm agar footer tidak tumpang tindih */
        margin-bottom: 2cm; 
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
    }

    /* ... (sisa class .table-bordered, .table-info, dll. tetap sama) ... */
    .table-bordered {
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid black;
        padding: 5px;
        vertical-align: top;
    }

    .table-info {
        width: 100%;
        border: none;
    }
    
    .table-info td {
        border: none;
        padding: 2px;
    }

    .text-center {
        text-align: center;
    }

    .text-justify {
        text-align: justify;
    }

    /* PERUBAHAN UTAMA ADA DI SINI */
    .footer {
        position: fixed; 
        text-align: right;
        /* Posisikan footer di area margin bawah halaman */
        left: 0px;
        right: 0px;
        bottom: -1.5cm; /* Mendorong footer ke bawah sejauh 1.5cm dari batas konten */
        height: 1.5cm; /* Memberi tinggi pada area footer */
    }

    .footer .page:after {
        content: counter(page, upper-roman);
    }
    
    .page-break {
        page-break-before: always;
    }
    
    .signature-table {
        width: 100%;
        margin-top: 20px;
    }

    .signature-table td {
        text-align: center;
        width: 33.33%;
        border: none;
        padding: 0;
    }

    </style>
</head>

<body>
    <div class="footer">
        <p class="page"><small><?= strtoupper($siswa['nama']) ?> - <?= strtoupper($siswa['kelas']) ?> &nbsp;&nbsp;&nbsp;&nbsp; <?= strtoupper($setting['sekolah']) ?> - <?= date('Y') ?></small>&nbsp;&nbsp;&nbsp;&nbsp;Halaman </p>
    </div>

    <center>
        <h3>LAPORAN HASIL BELAJAR<br>(RAPOR)</h3>
    </center>
    <br>

    <table class="table-info">
        <tr>
            <td style="width:18%;">Nama Peserta Didik</td>
            <td style="width:1%;">:</td>
            <td style="width:40%;"><?= $siswa['nama'] ?></td>
            <td style="width:5%;"></td>
            <td style="width:18%;">Kelas</td>
            <td style="width:1%;">:</td>
            <td style="width:17%;"><?= $siswa['kelas'] ?></td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td><?= $siswa['nis'] ?></td>
            <td></td>
            <td>Fase</td>
            <td>:</td>
            <td><?= $siswa['fase'] ?></td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td><?= $setting['sekolah'] ?></td>
            <td></td>
            <td>Semester</td>
            <td>:</td>
            <td><?= $setting['semester'] ?> <?= $smt ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><?= $setting['alamat'] ?></td>
            <td></td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td><?= $setting['tp'] ?></td>
        </tr>
    </table>
    <br>

    <p><b>A. Nilai Akademik</b></p>
    <table class="table-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th class="text-center" style="width:25%;">Muatan Pelajaran</th>
                <th class="text-center" style="width:10%;">Nilai Akhir</th>
                <th class="text-center">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE tingkat='$siswa[level]' AND pk='$siswa[jurusan]' ORDER BY urut ASC");
            while ($mapel = mysqli_fetch_array($query)) {
                $pelajaran = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE id='$mapel[mapel]'"));
                $nilai = mysqli_fetch_array(mysqli_query($koneksi,"SELECT AVG(nilai_harian) AS rata FROM nilai_sts WHERE nis='$nis' AND mapel='$mapel[mapel]'"));
                $des = mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM nilai_formatif WHERE nis='$siswa[nis]' AND mapel='$mapel[mapel]' AND kelas='$siswa[kelas]'"));
            ?>
                <tr>
                    <td class="text-center"><?= $mapel['urut'] ?></td>
                    <td><?= $pelajaran['nama_mapel'] ?></td>
                    <td class="text-center">
                        <?php if($nilai['rata'] != 0) echo round($nilai['rata']); ?>
                    </td>
                    <td class="text-justify" style="font-size:11px;">
                        <?php if($nilai['rata'] != 0): ?>
                            Menunjukkan pemahaman yang baik dalam hal: <i><?= strtolower($des['tinggi']) ?></i>.
                            <hr style="border-top: 1px dotted black; margin: 4px 0;">
                            Membutuhkan bimbingan lebih lanjut dalam hal: <i><?= strtolower($des['rendah']) ?></i>.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <div class="page-break"></div>

    <p><b>B. Kegiatan Ekstrakurikuler</b></p>
    <table class="table-bordered">
        <thead>
            <tr>
                <th class="text-center" style="width:5%;">No</th>
                <th class="text-center" style="width:30%;">Kegiatan Ekstrakurikuler</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            $queryx = mysqli_query($koneksi, "select * from m_eskul");
            while ($esk = mysqli_fetch_array($queryx)) {
                $eskuler = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM peskul WHERE nis='$nis' AND eskul='$esk[eskul]'"));
                $no++;
            ?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $esk['eskul'] ?></td>
                <td class="text-justify" style="font-size:11px;">
                <?php 
                    if($eskuler['nilai']) {
                        $grades = "";
                        if($eskuler['nilai']=='A') $grades="Sangat Baik";
                        elseif($eskuler['nilai']=='B') $grades="Baik";
                        elseif($eskuler['nilai']=='C') $grades="Cukup";
                        elseif($eskuler['nilai']=='D') $grades="Kurang";
                        
                        echo "Menunjukkan partisipasi dan kemampuan yang <b>" . $grades . "</b> dalam mengikuti kegiatan " . $esk['eskul'] . ". " . ucfirst($eskuler['ket']);
                    } else {
                        echo "-"; // Tampilkan strip jika tidak ada data
                    }
                ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>

    <p><b>C. Ketidakhadiran</b></p>
    <table class="table-bordered" style="width: 50%;">
        <tr>
            <td style="width: 70%;">Sakit</td>
            <td>: <?= $siswa['sakit'] ?? 0 ?> hari</td>
        </tr>
        <tr>
            <td>Izin</td>
            <td>: <?= $siswa['izin'] ?? 0 ?> hari</td>
        </tr>
        <tr>
            <td>Tanpa Keterangan</td>
            <td>: <?= $siswa['alpha'] ?? 0 ?> hari</td>
        </tr>
    </table>
    <br>

    <?php if($setting['semester'] == '2'): ?>
    <p><b>D. Keterangan Kenaikan Kelas</b></p>
    <table class="table-bordered" style="width: 60%;">
        <tr>
            <td style="padding: 10px;">
                Berdasarkan hasil belajar yang dicapai pada semester 1 dan 2, peserta didik ditetapkan: <br>
                <b>Naik ke kelas VIII (Delapan)</b> / <s style="color:grey;">Tinggal di kelas VII (Tujuh)</s>.
                <br><br>
                <small><i>*) Coret yang tidak perlu.</i></small>
            </td>
        </tr>
    </table>
    <br>
    <?php endif; ?>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui:<br>Orang Tua/Wali,
                <br><br><br><br><br>
                ( ........................... )
            </td>
            <td>
                <br>Kepala Sekolah,
                <br><br><br><br><br>
                <b><u><?= $setting['kepsek'] ?></u></b><br>
                NIP. <?= $setting['nip'] ?>
            </td>
            <td>
                Suruh, <?= $setting['tanggal_rapor'] ?><br>
                Wali Kelas,
                <br><br><br><br><br>
                <b><u><?= $walas['nama'] ?></u></b><br>
                NIP. <?= $walas['nip'] ?>
            </td>
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
$dompdf->stream("Raport_" . $siswa['nama'] . ".pdf", array("Attachment" => false));
exit(0);
?>