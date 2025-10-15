<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
require("../config/koneksi.php");

$data = mysqli_query($koneksi, "SELECT * FROM arsip ORDER BY tanggal_upload DESC");
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h4>Daftar Arsip</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Kategori</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_file']) ?></td>
                                <td><?= htmlspecialchars(ucwords($row['kategori'])) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_upload'])) ?></td>
                                <td>
                                    <a href="../myarsip/file_arsip/<?= $row['file_path'] ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                    <a href="../myarsip/file_arsip/<?= $row['file_path'] ?>" download class="btn btn-success btn-sm">Download</a>
                                    <a href="hapus_arsip.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus arsip ini?')" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>{}Y
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
