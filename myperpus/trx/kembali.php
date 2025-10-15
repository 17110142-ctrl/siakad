<?php
// Pastikan file ini tidak diakses langsung dan set mode transaksi ke KEMBALI
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
mysqli_query($koneksi, "UPDATE statustrx SET mode='2'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container-fluid">

    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="scan-siswa"><strong>Scan Kartu Pustaka</strong></label>
                        <input type="text" id="scan-siswa" class="form-control form-control-lg" placeholder="Scan kartu di sini..." autofocus>
                        <input type="hidden" id="active-student-code" value="">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="scan-buku"><strong>Scan Barcode Buku yang Dikembalikan</strong></label>
                        <input type="text" id="scan-buku" class="form-control form-control-lg" placeholder="Scan buku setelah pilih peminjam..." disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button id="refresh-btn" class="btn btn-info btn-lg w-90">Peminjam Lainnya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="detail-transaksi">
        <div class="alert alert-primary" role="alert">
            Silakan scan kartu pustaka untuk memulai transaksi pengembalian.
        </div>
    </div>

</div>

<script type="text/javascript">
$(document).ready(function() {

    // 1. Logika untuk Scan Kartu Siswa
    $('#scan-siswa').on('keypress', function(e) {
        if (e.which === 13) { // Tombol Enter
            e.preventDefault();
            var kodeSiswa = $(this).val().trim();
            if (kodeSiswa) {
                $('#active-student-code').val(kodeSiswa);
                $("#detail-transaksi").html('<div class="alert alert-info">Memuat data pinjaman ...</div>');
                // Memuat tampilan dari trk.php
                $("#detail-transaksi").load('trx/trk.php?kode=' + encodeURIComponent(kodeSiswa));
                $(this).val('');
                $('#scan-buku').prop('disabled', false).focus();
            }
        }
    });

    // 2. Logika untuk Scan Barcode Buku (Pengembalian)
    $('#scan-buku').on('keypress', function(e) {
        if (e.which === 13) { // Tombol Enter
            e.preventDefault();
            var kodeBuku = $(this).val().trim();
            var kodeSiswaAktif = $('#active-student-code').val();
            if (!kodeSiswaAktif) {
                alert('Silakan scan kartu pustaka terlebih dahulu!');
                $('#scan-siswa').focus();
                return;
            }
            if (kodeBuku) {
                $.ajax({
                    // Mengarah ke proses_kembali.php
                    url: 'trx/proses_kembali.php',
                    type: 'POST',
                    data: { kodeSiswa: kodeSiswaAktif, kodeBuku: kodeBuku },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Muat ulang daftar buku yang dipinjam
                            $("#detail-transaksi").load('trx/trk.php?kode=' + encodeURIComponent(kodeSiswaAktif));
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR) {
                        alert('Gagal memproses permintaan. Cek console (F12) untuk detail.');
                        console.log(jqXHR.responseText);
                    },
                    complete: function() {
                        $('#scan-buku').val('').focus();
                    }
                });
            }
        }
    });

    // 3. Fungsi untuk Tombol Refresh
    $('#refresh-btn').on('click', function(e) {
        e.preventDefault();
        $('#detail-transaksi').html('<div class="alert alert-primary" role="alert">Silakan scan kartu pustaka untuk memulai transaksi pengembalian.</div>');
        $('#scan-buku').val('').prop('disabled', true);
        $('#active-student-code').val('');
        $('#scan-siswa').val('').focus();
    });

});
</script>

</body>
</html>