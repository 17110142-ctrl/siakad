<?php
// trx.php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// Get today
$tanggal = date('Y-m-d');

// Retrieve scanned QR code from query string
$kodeScanned = isset($_GET['kode']) ? mysqli_real_escape_string($koneksi, $_GET['kode']) : '';

// Lookup student registration by QR code
$reg = [];
if (!empty($kodeScanned)) {
    $reg = mysqli_fetch_assoc(
        mysqli_query($koneksi,
            "SELECT * FROM datareg WHERE nokartu='$kodeScanned' LIMIT 1"
        )
    );
}

// Determine student info
if (!empty($reg)) {
    $namaSiswa = $reg['nama'];
    // If datareg has a link to idsiswa, use it; otherwise adapt as needed
    $idSiswa = $reg['nokartu'];
} else {
    $namaSiswa = 'Tidak Ditemukan';
    $idSiswa = null;
}

// Count today's transactions
$pinjam = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM transaksi WHERE tanggal='$tanggal' AND ket='Pinjam'"
))[0];
$kembali = mysqli_fetch_row(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) FROM transaksi WHERE tanggal='$tanggal' AND ket='Kembali'"
))[0];

// Load current mode
$dataMode = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT mode FROM statustrx LIMIT 1"));
$mode_perpus = $dataMode['mode'];
switch ($mode_perpus) {
    case 1: $sts = 'PINJAM'; break;
    case 2: $sts = 'KEMBALI'; break;
    case 3: $sts = 'INPUT BUKU'; break;
    default: $sts = '-';
}
?>

<div class="row">
    <div class="col-xl-5">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-primary">
                        <i class="material-icons-outlined">credit_card</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">KARTU PUSTAKA</span>
                        <h4 style="color:blue;font-weight:bold;">
                            <?= htmlspecialchars($kodeScanned ?: '—') ?>
                        </h4>
                        <span>
                            <?= htmlspecialchars($namaSiswa) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2">
        <div class="card-body">
            <?php if (!empty($kodeScanned)): ?>
            <center>
                <img src="../temp/<?= htmlspecialchars($kodeScanned) ?>.png" class="responsive" style="margin-top:-10px;">
            </center>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card widget widget-stats">
            <div class="card-body">
                <div class="widget-stats-container d-flex">
                    <div class="widget-stats-icon widget-stats-icon-success">
                        <i class="material-icons-outlined">shopping_cart</i>
                    </div>
                    <div class="widget-stats-content flex-fill">
                        <span class="widget-stats-title">TRANSAKSI HARI INI</span>
                        <h5 style="color:red;font-weight:bold;">PINJAM: <?= $pinjam ?></h5>
                        <h5 style="color:blue;font-weight:bold;">KEMBALI: <?= $kembali ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Buku Dipinjam oleh <?= htmlspecialchars($namaSiswa) ?></h5>
                <div class="pull-right">
                    <h5><span class="badge badge-primary">MODE MESIN <?= $sts ?></span></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="card-box table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover edis" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JUDUL BUKU</th>
                                <th>TANGGAL PINJAM</th>
                                <th>KEMBALIAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($idSiswa) {
                                $q = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE idsiswa='$idSiswa' AND ket='Pinjam' ORDER BY tanggal DESC LIMIT 10");
                                $no = 0;
                                while ($row = mysqli_fetch_assoc($q)):
                                    $no++;
                                    $buku = fetch($koneksi, 'buku', ['id' => $row['idbuku']]);
                            ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($buku['judul']) ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                <td><?= $row['ket'] ?></td>
                            </tr>
                            <?php endwhile;
                            } else {
                                echo '<tr><td colspan="4" class="text-center">Scan QR untuk melihat riwayat.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $('#datatable1').DataTable({
    pageLength: 5,
    language: {
        emptyTable: "Scan kartu pustaka siswa untuk melihat riwayat pinjaman."
    }
});
</script>
