<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">LAPORAN DATA PEMINJAM BUKU</h5>
            </div>
            <div class="card-body">
                <div class="card-box table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover edis" style="width:100%;font-size:12px">
                        <thead>
                            <tr>
                                <th width="5%">NO</th>
                                <th>TANGGAL</th>
                                <th>NAMA SISWA</th>
                                <th>KELAS</th>
                                <th>JUDUL BUKU</th>
                                <th>JML</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 0;
                            // Mengambil data 'Pinjam' dan 'Kembali', diurutkan berdasarkan status lalu tanggal terbaru
                            // Pastikan nama status 'Pinjam' dan 'Kembali' sesuai dengan yang ada di database Anda
                            $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE ket IN ('Pinjam', 'Kembali') ORDER BY FIELD(ket, 'Pinjam', 'Kembali'), tanggal DESC");
                            
                            while ($data = mysqli_fetch_array($query)) :
                                $siswa = fetch($koneksi, 'siswa', ['nis' => $data['idsiswa']]);
                                $buku = fetch($koneksi, 'buku', ['id' => $data['idbuku']]);
                                $no++;

                                // Menentukan warna badge berdasarkan status
                                $status_badge = ($data['ket'] == 'Pinjam') ? 'badge-danger' : 'badge-success';
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                    <td><?= $siswa['nama'] ?? 'Data Siswa Hilang'; ?></td>
                                    <td>
                                        <h5><span class="badge badge-primary"><?= $data['kelas']; ?></span></h5>
                                    </td>
                                    <td><?= $buku['judul'] ?? 'Data Buku Hilang'; ?></td>
                                    <td>
                                        <h5><span class="badge badge-secondary"><?= $data['jml']; ?></span></h5>
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
