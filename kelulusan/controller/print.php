<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIK ONLINE &copy SISTEM INFORMASI KELULUSAN ONLINE</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="../../images/logo.png">
    <style type="text/css">
        @import "style.css";
    </style>
</head>

<body style="background: url('../images/bg.png') repeat;">
    <div class="container" style="height: 100%; display: flex; justify-content: center;">
        <table>
            <tr>
                <td style="padding: 10px;">
                    <?php
                    function tgl_indo($tanggal)
                    {
                        $bulan = array(
                            1 =>   'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        );
                        $pecahkan = explode('/', $tanggal);

                        // variabel pecahkan 0 = tanggal
                        // variabel pecahkan 1 = bulan
                        // variabel pecahkan 2 = tahun

                        return $pecahkan[0] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[2];
                    }

                    include('../../config/koneksi.php');
                    if (!isset($_GET['nisn']) || empty($_GET['nisn'])) {
    die("NISN tidak ditemukan.");
}
                    $nisn = $_GET['nisn'];
                    $q = mysqli_query($koneksi, "SELECT * FROM siswa where nisn = '$nisn'");
                    if (!$q || mysqli_num_rows($q) == 0) {
    echo "<div align='center'><h3>Data tidak ditemukan untuk NISN <strong>$nisn</strong></h3></div>";
    exit;
}
                    while ($data = mysqli_fetch_array($q)) {
                        echo "<div class='well' style='margin-top: 15px;' >
                            <p align='center'><img src='../../images/logo.png' height='75' /></p>
                            <h4 align='center' style='margin: 15px 0 -10px 0;'><b>SISTEM INFORMASI</br>STATUS KELULUSAN SISWA</b></h4><hr>
                            <div align='center' class='alert alert-dismissable alert-success'><h4><b>DETAIL STATUS KELULUSAN</b></h4></div>";
                        echo "<table min-width='100' class='table table-striped table-bordered'>";
                        echo "<tr class='success'>
                            <td colspan='4' align='center'><font color='#000000' size='4' style='font-weight: bold;';><b>IDENTITAS PESERTA DIDIK</b></td>
                            </tr>
                            
                            <tr><td>Nama Lengkap</td>
                            <td colspan='3'><font style='text-transform: capitalize;'><strong>:  " . $data['nama'] . "</strong></font></td>
                            </tr>

                            <tr class='secondary'><td width='250'>NISN </td>
                            <td width='480'><strong>:  " . $data['nisn'] . "</strong></td>
                            </tr>

                            <tr><td>Kelas</td>
                            <td colspan='3'><strong>:  " . $data['kelas'] . "</strong></td>
                            </tr>

                            <tr class='secondary'><td>Tempat/ Tgl. Lahir</td>
                            <td colspan='3'>
                            <font style='text-transform: uppercase;'><strong>:  " . $data['t_lahir'] . ", " . tgl_indo($data['tgl_lahir']) . "</strong></font>
                            </td>
                            </tr>

                            <tr><td>Asal Sekolah</td>
                            <td colspan='3'><font style='text-transform: capitalize;'><strong>: " . $data['asal_sek'] . "</strong></font></td>
                            </tr>

                            <tr class='success'>
      <td colspan='4' align='center'><font color='#000000' size='4' style='font-weight: bold;'>STATUS KELULUSAN DINYATAKAN</font></td>
    </tr>";

    // ✅ Bagian yang diminta: status kelulusan
    echo "
    <tr class='warning'>
      <td colspan='4' align='center'>";
    if ($data['stskel'] == 1) {
      echo "<a data-id='" . $data['id_siswa'] . "'><b style='color:blue; text-transform: uppercase; font-size: 1.5em;'>LULUS</b></a>";
    } elseif (isset($data['keterangan']) && $data['keterangan'] == 2) {
      echo "<a data-id='" . $data['id_siswa'] . "' style='text-transform: uppercase; font-size: 1.5em;'>LULUS BERSYARAT</a>";
    } else {
      echo "<a data-id='" . $data['id_siswa'] . "'><b style='color:red; text-transform: uppercase; font-size: 1.5em;'>TIDAK LULUS</b></a>";
    }
    echo "</td>
    </tr>";

    echo "
                            <tr class='success'>
                            <td colspan='4' align='center'><b>Apapun hasil yang didapat, semoga ini adalah yang terbaik, tetap semangat dan optimis.</b></td>
                            </tr>

                            <tr>
                            <td colspan='4'></td>
                            </tr>

                            <tr class='danger'>
                            <td colspan='4' align='center'>
                            <b>Catatan:</b> Jika ada perbedaan data pengumuman online dan manual,</br>maka yang menjadi acuan 
                            adalah dokumen asli Kelulusan yang telah disahkan, <i></br>ditandatangani oleh Kepala Sekolah <b>" . $data['sekolah'] . "</b> dan 
                            diberi cap basah sekolah</i>.
                            </td>
                            </tr>

                            </table>";

                        echo "<div class='form-group' style='margin-bottom: -10px;'>
                            <h3 align='center' style='padding-bottom:-50px; '><button class='no-print' onclick='window.print();'><b>CETAK KELULUSAN</b></buttom></h3>
                            </div>
                            </div>";
                    }
                    ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>