<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">RIWAYAT PEMINJAMAN SAYA</h5>
            </div>
            <div class="card-body">
                <div class="card-box table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover edis" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>NAMA PEMINJAM</th>
                                <th>KODE</th>
                                <th>JUDUL BUKU</th>
                                <th>TANGGAL PINJAM</th>
                                <th>TANGGAL KEMBALI</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            // QUERY DIUBAH: Mengurutkan berdasarkan status 'Pinjam' dulu, baru 'Kembali'
                            $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE idsiswa = '$siswa[nis]' AND ket IN ('Pinjam', 'Kembali') ORDER BY FIELD(ket, 'Pinjam', 'Kembali'), tanggal DESC");

                            while ($data = mysqli_fetch_array($query)) :
                                $siswa_data = fetch($koneksi, 'siswa', ['nis' => $data['idsiswa']]);
                                $buku = fetch($koneksi, 'buku', ['id' => $data['idbuku']]);
                                $no++;

                                // Warna badge berubah sesuai status
                                $status_badge = ($data['ket'] == 'Kembali') ? 'badge-success' : 'badge-danger';
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $siswa_data['nama'] ?? 'Siswa tidak ditemukan'; ?></td>
                                    <td><?= $data['barkode']; ?></td>
                                    <td><?= $buku['judul'] ?? 'Buku tidak ditemukan'; ?></td>
                                    <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                    <td>
                                        <?= ($data['tgl_kembali'] == NULL) ? '-' : date('d-m-Y', strtotime($data['tgl_kembali'])); ?>
                                    </td>
                                    <td>
                                        <h5><span class="badge <?= $status_badge; ?>"><?= $data['ket']; ?></span></h5>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>