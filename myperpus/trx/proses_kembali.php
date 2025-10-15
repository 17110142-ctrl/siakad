<?php
// proses_kembali.php
header('Content-Type: application/json');

require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan tidak diketahui.'];

if (isset($_POST['kodeSiswa']) && isset($_POST['kodeBuku'])) {
    $kodeSiswa = mysqli_real_escape_string($koneksi, $_POST['kodeSiswa']);
    $kodeBuku = mysqli_real_escape_string($koneksi, $_POST['kodeBuku']);

    // 1. Cari ID buku berdasarkan barcode
    $buku = fetch($koneksi, 'buku', ['barkode' => $kodeBuku]);
    if ($buku) {
        $idBuku = $buku['id'];

        // 2. Cari transaksi peminjaman yang aktif untuk buku dan siswa ini
        $where = [
            'idsiswa' => $kodeSiswa,
            'idbuku'  => $idBuku,
            'ket'     => 'Pinjam'
        ];
        $transaksi = fetch($koneksi, 'transaksi', $where);

        if ($transaksi) {
            // 3. Jika transaksi ditemukan, proses pengembalian
            $idTransaksi = $transaksi['id'];
            $tglKembali = date('Y-m-d');
            
            // Update status transaksi menjadi 'Kembali'
            $updateTrx = update($koneksi, 'transaksi', 
                ['ket' => 'Kembali', 'tgl_kembali' => $tglKembali], 
                ['id' => $idTransaksi]
            );

            if ($updateTrx == 'OK') {
                // Tambah stok buku kembali
                mysqli_query($koneksi, "UPDATE buku SET jumlah = jumlah + 1 WHERE id='$idBuku'");
                
                $response['status'] = 'success';
                $response['message'] = 'Buku berhasil dikembalikan.';
            } else {
                $response['message'] = 'Gagal mengupdate status transaksi.';
            }
        } else {
            $response['message'] = 'Buku ini tidak tercatat sedang dipinjam oleh siswa tersebut.';
        }
    } else {
        $response['message'] = 'Buku dengan barcode tersebut tidak ditemukan.';
    }
} else {
    $response['message'] = 'Data tidak lengkap.';
}

echo json_encode($response);
?>