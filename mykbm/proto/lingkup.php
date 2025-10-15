<?php
defined('APK') or exit('No Access');
?>

<!-- =================================================================
BAGIAN 1: TAMPILAN DAFTAR MATA PELAJARAN (TIDAK ADA PERUBAHAN)
================================================================== -->
<?php if ($ac == '') : ?>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">INPUT LINGKUP MATERI</h5>
      </div>
      <div class="card-body">
        <div class="card-box table-responsive">
          <table id="datatable1" class="table table-bordered table-hover edis2" style="width:100%;font-size:12px">
            <thead>
              <tr>
                <th width="5%">NO</th>
                <th>SMT</th>
                <th>TKT</th>
                <th>MATA PELAJARAN</th>
                <th>GURU PENGAMPU</th>
                <th>JML LM</th>
                <th>INPUT</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              if ($user['level'] == 'admin'):
                  $query = mysqli_query($koneksi, "SELECT tingkat, mapel FROM jadwal_mapel WHERE kuri='2' GROUP BY tingkat, mapel");
              elseif ($user['level'] == 'guru'):
                  $query = mysqli_query($koneksi, "
                    SELECT tingkat, mapel 
                    FROM jadwal_mapel 
                    WHERE kuri='2' 
                      AND mapel IN (
                        SELECT mapel 
                        FROM jadwal_mapel 
                        WHERE guru = '$user[id_user]'
                      )
                    GROUP BY tingkat, mapel
                ");
              endif;
              while ($data = mysqli_fetch_array($query)):
                  $mapel = fetch($koneksi, 'mata_pelajaran', ['id' => $data['mapel']]);
                  $guru_list = mysqli_query($koneksi, "SELECT DISTINCT guru FROM jadwal_mapel WHERE tingkat='$data[tingkat]' AND mapel='$data[mapel]'");
                  $guru_nama = [];
                  while ($g = mysqli_fetch_assoc($guru_list)) {
                      $g_nama = fetch($koneksi, 'users', ['id_user' => $g['guru']]);
                      $guru_nama[] = $g_nama['nama'];
                  }
                  $guru_display = implode(', ', $guru_nama);

                  $jumdes = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$data[mapel]' AND level='$data[tingkat]' AND smt='$setting[semester]'"));
                  $no++;
              ?>
              <tr>
                <td><?= $no; ?></td>
                <td><h5><span class="badge badge-dark">Semester <?= $setting['semester']; ?></span></h5></td>
                <td><?= $data['tingkat'] ?></td>
                <td><?= $mapel['nama_mapel'] ?></td>
                <td><?= $guru_display ?></td>
                <td><h5><span class="badge badge-success"><?= $jumdes; ?></span></h5></td>
                <td>
                  <a href="?pg=<?= enkripsi('lingkup') ?>&ac=<?= enkripsi('input') ?>&l=<?= enkripsi($data['tingkat']) ?>&m=<?= enkripsi($data['mapel']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Tambah Materi">
                    <i class="material-icons">select_all</i>
                  </a>
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

<!-- =================================================================
BAGIAN 2: FORMULIR INPUT MATERI (PERUBAHAN PADA SCRIPT)
================================================================== -->
<?php elseif ($ac == enkripsi('input')): ?>
<?php
  $mapel = dekripsi($_GET['m']);
  $tingkat = dekripsi($_GET['l']);
  $jml = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$mapel' AND level='$tingkat' AND smt='$setting[semester]'"));
  $jumlah = $jml + 1;
  $mpl = fetch($koneksi, 'mata_pelajaran', ['id' => $mapel]);
?>
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h5 class="bold">DAFTAR LINGKUP MATERI: <?= $mpl['nama_mapel'] ?> - KELAS <?= $tingkat ?></h5>
      </div>
      <div class="card-body">
        <div class="card-box table-responsive">
          <table id="datatable1" class="table table-bordered table-hover" style="width:100%;font-size:12px">
            <thead>
              <tr>
                <th width="10%">NO</th>
                <th width="10%">TKT</th>
                <th width="10%">SMT</th>
                <th>MATERI</th>
                <th width="20%">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              $query = mysqli_query($koneksi, "SELECT * FROM lingkup WHERE mapel='$mapel' AND level='$tingkat' AND smt='$setting[semester]' ORDER BY materi ASC");
              while ($data = mysqli_fetch_array($query)):
                $no++;
              ?>
              <tr>
                <td><?= $no; ?></td>
                <td><?= $data['level'] ?></td>
                <td><?= $data['smt'] ?></td>
                <td><?= $data['materi'] ?></td>
                <td>
                  <a href="?pg=<?= enkripsi('lingkup') ?>&ac=<?= enkripsi('edit') ?>&id=<?= enkripsi($data['id']) ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Edit LM">
                    <i class="material-icons">edit</i>
                  </a>
                  <button type="button" class="hapus btn btn-sm btn-danger" data-id="<?= $data['id'] ?>" data-bs-toggle="tooltip" title="Hapus">
                    <i class="material-icons">delete</i>
                  </button>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card widget widget-payment-request">
      <div class="card-header">
        <h5 class="bold">INPUT LINGKUP MATERI BARU</h5>
      </div>
      <div class="card-body">
        <form id="formdeskrip">
          <label class="bold">Semester</label>
          <div class="input-group mb-2">
            <select name="smt" class="form-select" required>
              <option value="1" <?= $setting['semester'] == '1' ? 'selected' : '' ?>>Semester 1</option>
              <option value="2" <?= $setting['semester'] == '2' ? 'selected' : '' ?>>Semester 2</option>
            </select>
          </div>
          <input type="hidden" name="tp" value="<?= $setting['tp'] ?>">
          <input type="hidden" name="level" value="<?= $tingkat ?>">
          <input type="hidden" name="mapel" value="<?= $mapel ?>">
          
          <label class="bold">Materi</label>
          <div id="materi-container">
            <div class="input-group mb-2 materi-item">
              <input type="text" name="materi[]" class="form-control" placeholder="Masukkan Materi" required>
              <button type="button" class="btn btn-danger btn-sm ms-2 remove-materi">Hapus</button>
            </div>
          </div>
          <button type="button" class="btn btn-success btn-sm mb-3" id="tambah-materi">+ Tambah Materi</button>
          
          <!-- Tempat untuk notifikasi loading -->
          <div id="progressbox" class="mb-2"></div>

          <div class="widget-payment-request-actions m-t-lg d-flex">
            <button type="submit" class="btn btn-primary flex-grow-1 m-l-xxs">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- =================================================================
SCRIPT YANG TELAH DIPERBAIKI
================================================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // Fungsi untuk menambah baris input materi
  document.getElementById('tambah-materi').addEventListener('click', function() {
    const container = document.getElementById('materi-container');
    const item = document.createElement('div');
    item.classList.add('input-group', 'mb-2', 'materi-item');
    item.innerHTML = `
      <input type="text" name="materi[]" class="form-control" placeholder="Masukkan Materi" required>
      <button type="button" class="btn btn-danger btn-sm ms-2 remove-materi">Hapus</button>
    `;
    container.appendChild(item);
  });

  // Fungsi untuk menghapus baris input materi
  document.getElementById('materi-container').addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-materi')) {
      // Hanya hapus jika ada lebih dari satu item
      if (document.querySelectorAll('.materi-item').length > 1) {
        e.target.closest('.materi-item').remove();
      } else {
        alert('Minimal harus ada satu materi yang diinput.');
      }
    }
  });

  // Fungsi untuk submit form
  document.getElementById('formdeskrip').addEventListener('submit', function(e) {
    e.preventDefault();

    const materiInputs = document.querySelectorAll('input[name="materi[]"]');
    let materiSet = new Set();
    let duplicateFound = false;
    let emptyFound = false;

    materiInputs.forEach(input => {
      const val = input.value.trim();
      if (val === '') {
        emptyFound = true;
      }
      
      const lowerVal = val.toLowerCase();
      if (materiSet.has(lowerVal)) {
        duplicateFound = true;
      } else {
        materiSet.add(lowerVal);
      }
    });

    if (emptyFound) {
      alert('Ada isian materi yang masih kosong. Mohon periksa kembali.');
      return;
    }
    if (duplicateFound) {
      alert('Ada materi yang sama diinput lebih dari sekali dalam form ini.');
      return;
    }

    var data = new FormData(this);
    
    // Tampilkan loading
    const progressbox = document.getElementById('progressbox');
    progressbox.innerHTML = '<div class="text-center"><label class="text-primary">Data sedang diproses...</label>&nbsp;<img src="../images/animasi.gif" style="width:30px;"></div>';

    fetch('proto/tdeskrip.php?pg=lingkup', {
      method: 'POST',
      body: data
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json(); // Langsung minta respons sebagai JSON
    })
    .then(res => {
      if (res.status === 'sukses') {
        progressbox.innerHTML = '<div class="alert alert-success">Data berhasil disimpan!</div>';
        setTimeout(() => location.reload(), 1500);
      } else {
        // Menangani error spesifik dari server (duplikat, dll)
        const errorMessage = res.materi ? `Materi "${res.materi}" sudah ada di database.` : res.message;
        alert('Gagal: ' + errorMessage);
        progressbox.innerHTML = ''; // Hapus loading
      }
    })
    .catch(error => {
      // Menangkap error jaringan atau jika respons dari server bukan JSON yang valid
      console.error('Fetch Error:', error);
      alert('Terjadi kesalahan fatal. Periksa konsol browser (F12) untuk detail. Kemungkinan ada error pada file PHP.');
      progressbox.innerHTML = ''; // Hapus loading
    });
  });

  // Fungsi untuk hapus data dari tabel
  $('#datatable1').on('click', '.hapus', function() {
    var id = $(this).data('id');
    swal({
      title: 'Yakin hapus data ini?',
      text: "Data yang sudah dihapus tidak dapat dikembalikan!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: "Batal"
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: 'proto/tdeskrip.php?pg=hapus',
          method: "POST",
          data: { id: id }, // Kirim data sebagai objek
          success: function(data) {
            if (data.trim() === 'success') {
              swal('Berhasil!', 'Data telah dihapus.', 'success').then(() => {
                location.reload();
              });
            } else {
              swal('Gagal!', 'Data tidak dapat dihapus.', 'error');
            }
          },
          error: function() {
            swal('Error!', 'Gagal menghubungi server.', 'error');
          }
        });
      }
    });
  });
});
</script>
<?php endif; ?>
