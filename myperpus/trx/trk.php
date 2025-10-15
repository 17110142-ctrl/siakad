<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// --- Bagian Pengambilan Data ---
// Sama persis seperti trx.php

$tanggal = date('Y-m-d');

// Ambil kode siswa dari parameter URL
$kodeScanned = isset($_GET['kode']) ? mysqli_real_escape_string($koneksi, $_GET['kode']) : '';

// Ambil data siswa dari tabel datareg
$reg = [];
if (!empty($kodeScanned)) {
    $reg = fetch($koneksi, 'datareg', ['nokartu' => $kodeScanned]);
}

// Tentukan info siswa
if ($reg) {
    $namaSiswa = $reg['nama'];
    $idSiswa = $reg['nokartu']; // Menggunakan nokartu sebagai ID untuk query transaksi
} else {
    $namaSiswa = 'Tidak Ditemukan';
    $idSiswa = null;
}

// Hitung total transaksi hari ini
$pinjam = rowcount($koneksi, 'transaksi', ['tanggal' => $tanggal, 'ket' => 'Pinjam']);
$kembali = rowcount($koneksi, 'transaksi', ['tanggal' => $tanggal, 'ket' => 'Kembali']);

// Ambil mode mesin saat ini
$dataMode = fetch($koneksi, 'statustrx');
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
                <img src="../temp/<?= htmlspecialchars($kodeScanned) ?>.png" class="responsive" style="margin-top:-10px;" onerror="this.style.display='none'">
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
                <h5 class="card-title">Buku yang Sedang Dipinjam oleh <?= htmlspecialchars($namaSiswa) ?></h5>
                <div class="pull-right">
                    <h5><span class="badge badge-danger">MODE MESIN <?= $sts ?></span></h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>JUDUL BUKU</th>
                                <th>TANGGAL PINJAM</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($idSiswa) {
                                // Query tetap sama: mencari buku yang statusnya 'Pinjam'
                                $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE idsiswa='$idSiswa' AND ket='Pinjam' ORDER BY tanggal DESC LIMIT 10");
                                $no = 0;
                                if (mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)):
                                        $no++;
                                        $buku = fetch($koneksi, 'buku', ['id' => $row['idbuku']]);
                            ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($buku['judul'] ?? 'Buku Dihapus') ?></td>
                                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                <td><span class="badge badge-warning"><?= $row['ket'] ?></span></td>
                            </tr>
                            <?php 
                                    endwhile;
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">Tidak ada buku yang sedang dipinjam oleh siswa ini.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">Scan kartu pustaka siswa untuk melihat daftar pinjaman.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>