<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$kode = $_POST['kode'] ?? '';

// Ambil data siswa berdasarkan QR Code
$reg = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM datareg WHERE nokartu='$kode'"));
if (!$reg) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan untuk QR Code: $kode</div>";
    exit;
}

$id_siswa = $reg['idsiswa'];

// Ambil data transaksi siswa (hanya yang belum dikembalikan)
$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE idsiswa='$id_siswa' AND ket='Pinjam' ORDER BY id DESC");

?>

<div class="card widget widget-stats">
    <div class="card-body">
        <div class="widget-stats-container d-flex">
            <div class="widget-stats-icon widget-stats-icon-primary">
                <i class="material-icons-outlined">credit_card</i>
            </div>
            <div class="widget-stats-content flex-fill">
                <span class="widget-stats-title">KARTU PUSTAKA</span>
                <h4 style="color:blue;font-weight:bold;"><?= $kode ?></h4>
                <span><?= $reg['nama'] ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">DAFTAR BUKU DIPINJAM</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" style="font-size:12px;">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Judul Buku</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 0;
                while($data = mysqli_fetch_array($query)):
                    $buku = fetch($koneksi, 'buku', ['id' => $data['idbuku']]);
                    $no++;
                ?>
                <tr>
                    <td><?= $no ?></td>
                    <td><?= $buku['judul'] ?></td>
                    <td><?= $data['jml'] ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
