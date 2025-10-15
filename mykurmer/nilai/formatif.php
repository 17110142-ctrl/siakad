<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

if ($ac == '') :
    // Ambil parameter GET
    $tingkat = $_GET['level']  ?? '';
    $kelas   = $_GET['kelas']  ?? '';
    $mapel   = $_GET['mapel']  ?? '';
    $guru    = $_GET['guru']   ?? '';

    // Ambil semester dan tahun
    $smt = $setting['semester'];
    $tp  = $setting['tahun'];

    // Ambil semua TP untuk mapel, level, dan semester
    $TPList = [];
    $tpQuery = mysqli_query(
        $koneksi,
        "SELECT tujuan FROM tujuan WHERE mapel='" . mysqli_real_escape_string($koneksi, $mapel) . "'"
        . " AND level='" . mysqli_real_escape_string($koneksi, $tingkat) . "'"
        . " AND smt='" . mysqli_real_escape_string($koneksi, $smt) . "'"
    );
    while ($row = mysqli_fetch_assoc($tpQuery)) {
        $TPList[] = $row['tujuan'];
    }
?>
<div class="alert alert-dark" role="alert">
    Asesmen Formatif bertujuan untuk memperbaiki proses pembelajaran baik bagi guru maupun siswa. Hasil asesmen formatif tidak
    digunakan dalam pengolahan rapor namun sebagai bahan pertimbangan <b>untuk membuat deskripsi</b> pada laporan hasil belajar siswa.
    Isikan TP sesuai jumlah TP pada menu Tujuan Pembelajaran
    <?php include "nilai/radio.php"; ?>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="bold">NILAI FORMATIF <span class="badge badge-secondary"><?= $mapel ?> <?= $kelas ?></span></h5>
        <a href="." class="btn btn-light pull-right">Back</a>
      </div>
      <div class="card-body">
        <div class="card-box table-responsive">
          <form id="formNilaiRaport" method="POST">
            <table class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
              <thead>
                <tr>
                  <th>NO</th>
                  <th>NIS</th>
                  <th>NAMA SISWA</th>
                  <th>NILAI</th>
                  <th>TP TERCAPAI</th>
                  <th>TP KURANG TERCAPAI</th>
                </tr>
              </thead>
              <tbody>
<?php
$no = 0;
$qSiswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE kelas='" . mysqli_real_escape_string($koneksi, $kelas) . "'");
while ($data = mysqli_fetch_array($qSiswa)):
    $no++;
    // Ambil existing data formatif
    $formatif = fetch($koneksi, 'nilai_sts', [
        'nis'      => $data['nis'],
        'semester' => $smt,
        'tp'       => $tp,
        'guru'     => $guru,
        'mapel'    => $mapel,
    ]);
    // Pecah TP yang sudah disimpan
    $tinggiExist = isset($formatif['tinggi']) ? explode('||', $formatif['tinggi']) : [];
    $rendahExist = isset($formatif['rendah']) ? explode('||', $formatif['rendah']) : [];
?>
<tr>
  <td><?= $no ?></td>
  <td><?= $data['nis'] ?></td>
  <td><?= $data['nama'] ?></td>
  <td>
    <input type="number"
           name="nilai_raport[<?= $data['id_siswa'] ?>]"
           class="form-control form-control-sm"
           value="<?= $formatif['nilai_raport'] ?? '' ?>"
           min="0" max="100">
  </td>
  <td>
    <?php foreach ($TPList as $tujuan): ?>
      <label class="checkbox-inline">
        <input type="checkbox"
               name="tinggi[<?= $data['id_siswa'] ?>][]"
               value="<?= htmlspecialchars($tujuan) ?>"
               <?= in_array($tujuan, $tinggiExist) ? 'checked' : '' ?>>
        <?= htmlspecialchars($tujuan) ?>
      </label><br>
    <?php endforeach; ?>
  </td>
  <td>
    <?php foreach ($TPList as $tujuan): ?>
      <label class="checkbox-inline">
        <input type="checkbox"
               name="rendah[<?= $data['id_siswa'] ?>][]"
               value="<?= htmlspecialchars($tujuan) ?>"
               <?= in_array($tujuan, $rendahExist) ? 'checked' : '' ?>>
        <?= htmlspecialchars($tujuan) ?>
      </label><br>
    <?php endforeach; ?>
  </td>
</tr>
<?php endwhile; ?>
              </tbody>
            </table>
            <!-- hidden fields -->
            <input type="hidden" name="kelas" value="<?= $kelas ?>">
            <input type="hidden" name="mapel" value="<?= $mapel ?>">
            <input type="hidden" name="guru" value="<?= $guru ?>">
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-success">SIMPAN NILAI FORMATIF</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card widget-payment-request">
      <div class="card-body">
        <div class="widget-payment-request-author-info">
          <span class="widget-payment-request-author-name"><?= strtoupper($user['nama']) ?></span><br>
          <span><?= $setting['sekolah'] ?></span>
        </div>
        <div class="mt-3">
          <div class="mb-2">
            <label class="form-label bold">Tingkat</label>
            <select name="level" id="level" class="form-select level" required style="width:100%">
              <option value="">Pilih Tingkat</option>
              <?php
              $q = mysqli_query($koneksi, "SELECT level FROM kelas WHERE kurikulum='2' GROUP BY level");
              while ($r = mysqli_fetch_array($q)) {
                  echo "<option value='" . $r['level'] . "'>" . $r['level'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">Pilihan Rombel</label>
            <select name="kelas" id="kelas" class="form-select kelas" required style="width:100%"></select>
          </div>
          <div class="mb-2">
            <label class="form-label bold">Mata Pelajaran</label>
            <select name="mapel" id="mapel" class="form-select mapel" required style="width:100%">
              <option value="">Pilih Mapel</option>
              <?php
              if ($user['level']=='admin') {
                  $qry = mysqli_query($koneksi, "SELECT id,nama_mapel FROM mata_pelajaran");
              } else {
                  $qry = mysqli_query(
                      $koneksi,
                      "SELECT DISTINCT mp.id, mp.nama_mapel FROM jadwal_mapel jm "
                      . "JOIN mata_pelajaran mp ON mp.id=jm.mapel "
                      . "WHERE jm.guru='" . $user['id_user'] . "'"
                  );
              }
              while ($m = mysqli_fetch_array($qry)) {
                  echo "<option value='" . $m['id'] . "'>" . $m['nama_mapel'] . "</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <button id="cari" class="btn btn-primary w-100">PILIH</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$('#formNilaiRaport').submit(function(e) {
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: 'nilai/tnilaiformatif.php',
        data: $(this).serialize(),
        success: function(response) {
            if (response.trim() === 'OK') {
                iziToast.success({ title: 'Berhasil!', message: 'Nilai formatif berhasil disimpan', position: 'topRight' });
            } else {
                iziToast.error({ title: 'Gagal!', message: response, position: 'topRight' });
            }
        }
    });
});

// AJAX load rombel
$('#level').change(function() {
    var level = $(this).val();
    $.post('nilai/tnilai.php?pg=kelas', { level: level }, function(resp) {
        $('#kelas').html(resp);
    });
});

// Tombol cari
$('#cari').click(function(e) {
    e.preventDefault();
    var lvl = $('#level').val();
    var kel = $('#kelas').val();
    var mpl = $('#mapel').val();
    location.replace('?pg=' + '<?= enkripsi('formatif') ?>' + '&level=' + lvl + '&kelas=' + kel + '&mapel=' + mpl + '&guru=' + $('select.guru').val());
});
</script>
<?php endif; ?>
