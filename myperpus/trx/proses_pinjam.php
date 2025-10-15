<?php
// Set header untuk output JSON
header('Content-Type: application/json');

// Memuat file konfigurasi dan fungsi dasar
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

// Inisialisasi array untuk respons JSON
$response = ['status' => 'error', 'message' => 'Terjadi kesalahan yang tidak diketahui.'];

// 1. Terima dan validasi data POST
if (isset($_POST['kodeSiswa']) && isset($_POST['kodeBuku'])) {
    $kodeSiswa = mysqli_real_escape_string($koneksi, trim($_POST['kodeSiswa']));
    $kodeBuku = mysqli_real_escape_string($koneksi, trim($_POST['kodeBuku'])); // Ini adalah barkode buku

    if (empty($kodeSiswa) || empty($kodeBuku)) {
        $response['message'] = 'Kode siswa atau kode buku tidak boleh kosong.';
        echo json_encode($response);
        exit;
    }

    // 2. Cari ID Siswa dan kelasnya
    $siswaQuery = mysqli_query($koneksi, "SELECT nis, kelas FROM siswa WHERE nis='$kodeSiswa' LIMIT 1");
    if (mysqli_num_rows($siswaQuery) > 0) {
        $dataSiswa = mysqli_fetch_assoc($siswaQuery);
        $idSiswa = $dataSiswa['nis'];
        $kelasSiswa = $dataSiswa['kelas'];

        // 3. Cari ID Buku dan cek ketersediaannya
        $bukuQuery = mysqli_query($koneksi, "SELECT id, judul, jumlah FROM buku WHERE barkode='$kodeBuku' LIMIT 1");
        if (mysqli_num_rows($bukuQuery) > 0) {
            $dataBuku = mysqli_fetch_assoc($bukuQuery);
            $idBuku = $dataBuku['id'];
            $jumlahBuku = $dataBuku['jumlah'];

            if ($jumlahBuku > 0) {
                // 4. Cek apakah buku ini sudah dipinjam oleh siswa yang sama dan belum kembali
                $cekPeminjaman = mysqli_query($koneksi, 
                    "SELECT * FROM transaksi WHERE idsiswa='$idSiswa' AND idbuku='$idBuku' AND ket='Pinjam'"
                );

                if (mysqli_num_rows($cekPeminjaman) == 0) {
                    // 5. Lakukan proses peminjaman
                    $tanggalPinjam = date('Y-m-d');
                    
                    // Kurangi stok buku
                    $stokBaru = $jumlahBuku - 1;
                    $updateStok = update($koneksi, 'buku', ['jumlah' => $stokBaru], ['id' => $idBuku]);

                    if ($updateStok == 'OK') {
                        // Masukkan data ke tabel transaksi
                        $dataTransaksi = [
                            'idsiswa' => $idSiswa,
                            'idbuku'  => $idBuku,
                            'barkode' => $kodeBuku, // Data barkode ditambahkan di sini
                            'tanggal' => $tanggalPinjam,
                            'kelas'   => $kelasSiswa,
                            'ket'     => 'Pinjam'
                        ];
                        
                        $insertTransaksi = insert($koneksi, 'transaksi', $dataTransaksi);

                        if ($insertTransaksi == 'OK') {
                            $response['status'] = 'success';
                            $response['message'] = 'Buku "' . htmlspecialchars($dataBuku['judul']) . '" berhasil dipinjam.';
                        } else {
                            // Kembalikan stok jika gagal mencatat transaksi
                            update($koneksi, 'buku', ['jumlah' => $jumlahBuku], ['id' => $idBuku]);
                            $response['message'] = 'Gagal menyimpan data transaksi.';
                        }
                    } else {
                        $response['message'] = 'Gagal memperbarui stok buku.';
                    }
                } else {
                    $response['message'] = 'Buku ini sudah Anda pinjam dan belum dikembalikan.';
                }
            } else {
                $response['message'] = 'Stok buku "' . htmlspecialchars($dataBuku['judul']) . '" sedang habis.';
            }
        } else {
            $response['message'] = 'Buku dengan kode barcode tersebut tidak ditemukan.';
        }
    } else {
        $response['message'] = 'Siswa dengan kartu tersebut tidak terdaftar.';
    }
} else {
    $response['message'] = 'Data tidak lengkap.';
}

// 6. Kembalikan response dalam format JSON
echo json_encode($response);
?>
