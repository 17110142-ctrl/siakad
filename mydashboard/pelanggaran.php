<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

// Total poin siswa
$totalPoin = mysqli_fetch_array(mysqli_query($koneksi, "SELECT SUM(poin) as total FROM bk_siswa WHERE nis='$siswa[nis]' AND tapel='$setting[tp]'"));

// Ambil tindakan yang sesuai
$tindakan = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM bk_tindakan WHERE {$totalPoin['total']} BETWEEN minpoin AND maxpoin LIMIT 1"));

// Tentukan warna badge berdasarkan tindakan
function warnaBadge($tindakan) {
    if (!$tindakan) return 'secondary';
    $nama = strtolower($tindakan['tindakan']);
    if (strpos($nama, 'sp1') !== false) return 'orange';  // oranye custom
    if (strpos($nama, 'sp2') !== false) return 'danger';  // merah
    if (strpos($nama, 'sp3') !== false) return 'dark';    // hitam
    return 'info';
}

$warna = warnaBadge($tindakan);
?>

<!-- Custom warna badge orange -->
<style>
  .badge-orange {
    background-color: orange;
    color: white;
  }
</style>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">DATA PELANGGARAN SAYA</h5>
      </div>
      <div class="card-body">
        <div class="card-box table-responsive">
          <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
            <thead>
              <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>KETERANGAN</th> 
                <th>POIN</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = mysqli_query($koneksi, "SELECT * FROM bk_siswa WHERE nis='$siswa[nis]' AND tapel='$setting[tp]' ORDER BY tanggal DESC");
              $no = 0;
              if (mysqli_num_rows($query) > 0):
                while ($bk = mysqli_fetch_array($query)):
                    $no++;
              ?>
              <tr>
                <td><?= $no; ?></td>
                <td><?= date('d-m-Y',strtotime($bk['tanggal'])) ?></td>
                <td><?= $bk['ket'] ?></td>
                <td><h5><span class="badge badge-danger"><?= $bk['poin'] ?></span></h5></td>
              </tr>
              <?php endwhile; else: ?>
              <tr>
                <td colspan="4" class="text-center">Data tidak ditemukan</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <!-- Total Poin -->
          <div class="mt-3">
            <strong>Total Poin Pelanggaran:</strong> 
            <span class="badge <?= $warna == 'orange' ? 'badge-orange' : 'badge-' . $warna ?>">
              <?= $totalPoin['total'] ?? 0 ?>
            </span>
          </div>

          <!-- Tindakan Sesuai Poin -->
          <!-- Tindakan Sesuai Poin -->
<?php
$alertClass = 'alert-info';
if ($tindakan) {
    $nama = strtolower($tindakan['tindakan']);
    if (strpos($nama, 'sp1') !== false) {
        $alertClass = 'alert-warning'; // oranye
    } elseif (strpos($nama, 'sp2') !== false) {
        $alertClass = 'alert-danger';  // merah
    } elseif (strpos($nama, 'sp3') !== false) {
        $alertClass = 'alert-dark';    // hitam
    }
}
?>

<?php if ($tindakan): ?>
  <div class="alert <?= $alertClass ?> mt-3" role="alert">
    <strong>Tindakan Disarankan:</strong> <?= $tindakan['tindakan']; ?>  
    <br>
    <small><i><?= $tindakan['ketentuan']; ?></i></small>
  </div>
<?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
