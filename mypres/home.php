<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$tanggal = date('Y-m-d');
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='pegawai'"));
$jsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$jpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));
$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal'"));
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<style>
  #reader {
    width: 100%;
    max-width: 600px;
    margin: auto;
    border: 2px solid red;
  }
</style>



<script>
 function onScanSuccess(decodedText, decodedResult) {
    console.log(`Kode QR Terbaca: ${decodedText}`);
    document.getElementById("text").value = decodedText;

    $.ajax({
        type: "POST",
        url: "proses_absensi.php",
        data: { kode_qr: decodedText },
        success: function(response) {
            console.log("Respons dari server:", response);
            document.getElementById("result").innerHTML = "<b>" + response + "</b>";

          
        },
        error: function(xhr, status, error) {
            console.error("Terjadi kesalahan:", error);
            document.getElementById("result").innerHTML = "Gagal mengirim data!";
        }
    });
}

  function onScanFailure(error) {
      console.warn(`QR Scan Gagal: ${error}`);
  }

  document.addEventListener("DOMContentLoaded", function() {
      console.log("Memulai scanner QR...");
      try {
          let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
          html5QrcodeScanner.render(onScanSuccess, onScanFailure);
      } catch (e) {
          console.error("Kesalahan saat inisialisasi scanner:", e);
          document.getElementById("result").innerHTML = "Scanner gagal dimuat.";
      }
  });
</script>

<div id='logabs'></div>
<div class="row">
    <div class="col-xl-4">
        <div class="card widget widget-list">
            <div class="card-header">
                <h5 class="card-title">SISWA</h5>
            </div>
            <div class="card-body" style="height:470px;">
                <div id='logabsen'></div>
            </div>
        </div>
    </div>    
    <div class="col-xl-4">
        <div class="card widget widget-list">
            <div class="card-header">
                <h5 class="card-title">PEGAWAI</h5>
            </div>
            <div class="card-body" style="height:470px;">
                <div id='logabsenpeg'></div>
            </div>
        </div>
    </div>    
    <div class="col-md-4">                                
        <div class="card widget widget-payment-request">
            <div class="card-header">
                <h5 class="card-title">NOTIFIKASI WA</h5>
                <button class="hapus btn btn-sm btn-danger pull-right" id="optimal" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                    <i class="material-icons">delete</i>
                </button>
            </div>
            <div class="card-body" style="height:470px;">
                <div id='logpesan'></div>
            </div>
        </div>
    </div>
</div>

<script>
    var autoRefresh = setInterval(
        function() {
            $('#logabs').load('logabsen.php');
            $('#logabsen').load('logsis.php');
            $('#logabsenpeg').load('logpeg.php');
            $('#logpesan').load('logpesan.php');
        }, 2000
    );
</script>

<script>
    $("#optimal").click(function(){
        Swal.fire({
            title: 'Hapus Pesan Terkirim',
            text: "Informasi : Pesan terkirim akan terhapus !",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus !'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'pesan/tsetting.php?pg=hps',
                    success: function(data) {
                        Swal.fire(
                            'Success!',
                            'Your file has been Optimize.',
                            'success'
                        )
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                });
            }
            return false;
        })
    });
</script>
