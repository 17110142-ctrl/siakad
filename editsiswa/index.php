<?php
session_start();
// Cek login
if (!isset($_SESSION['id_siswa'])) {
  header("Location: ../config/koneksi.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Siswa</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-12">

      <div class="card shadow-lg">
        <div class="card-body">
          <div class="row">

            <!-- Sidebar Tabs -->
            <div class="col-md-3">
              <div class="list-group" id="list-tab" role="tablist">
                <button class="list-group-item list-group-item-action active" id="list-siswa-list" data-bs-toggle="tab" data-bs-target="#list-siswa" type="button" role="tab">Data Siswa</button>
                <button class="list-group-item list-group-item-action" id="list-ortu-list" data-bs-toggle="tab" data-bs-target="#list-ortu" type="button" role="tab">Data Orang Tua</button>
                <button class="list-group-item list-group-item-action" id="list-alamat-list" data-bs-toggle="tab" data-bs-target="#list-alamat" type="button" role="tab">Data Alamat</button>
                <button class="list-group-item list-group-item-action" id="list-aktivitas-list" data-bs-toggle="tab" data-bs-target="#list-aktivitas" type="button" role="tab">Aktivitas Belajar</button>
              </div>
            </div>

            <!-- Form Content -->
            <div class="col-md-9">
              <form id="formDataSiswa">
                <div class="tab-content" id="nav-tabContent">

                  <!-- Form Data Siswa -->
                  <div class="row">
  <div class="col-md-12">
    <h4 class="mb-4">Form Data Siswa</h4>
  </div>

  <!-- Username -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Username</label>
    <input type="text" name="username" class="form-control" required>
  </div>

  <!-- Password -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>

  <!-- NIS -->
  <div class="col-md-6 mb-3">
    <label class="form-label">NIS</label>
    <input type="text" name="nis" class="form-control">
  </div>

  <!-- NISN -->
  <div class="col-md-6 mb-3">
    <label class="form-label">NISN</label>
    <input type="text" name="nisn" class="form-control">
  </div>

  <!-- Nama Lengkap -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Nama Lengkap</label>
    <input type="text" name="nama" class="form-control" required>
  </div>

  <!-- Kelas -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Kelas</label>
    <input type="text" name="kelas" class="form-control">
  </div>

  <!-- Agama -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Agama</label>
    <input type="text" name="agama" class="form-control">
  </div>

  <!-- Foto -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Upload Foto</label>
    <input type="file" name="foto" class="form-control">
  </div>

  <!-- Nomor WhatsApp -->
  <div class="col-md-6 mb-3">
    <label class="form-label">No WhatsApp</label>
    <input type="text" name="nowa" class="form-control">
  </div>

  <!-- Lulusan -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Lulusan</label>
    <input type="text" name="lulusan" class="form-control">
  </div>

  <!-- Prestasi -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Prestasi</label>
    <input type="text" name="prestasi" class="form-control">
  </div>

  <!-- Tingkat -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Tingkat Prestasi</label>
    <input type="text" name="tingkat" class="form-control">
  </div>

  <!-- Juara -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Juara</label>
    <input type="text" name="juara" class="form-control">
  </div>

  <!-- Tempat Lahir -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Tempat Lahir</label>
    <input type="text" name="t_lahir" class="form-control">
  </div>

  <!-- Tanggal Lahir -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Tanggal Lahir</label>
    <input type="date" name="tgl_lahir" class="form-control">
  </div>

  <!-- Alamat -->
  <div class="col-md-12 mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control" rows="2"></textarea>
  </div>

  <!-- Desa -->
  <div class="col-md-4 mb-3">
    <label class="form-label">Desa</label>
    <input type="text" name="desa" class="form-control">
  </div>

  <!-- Kecamatan -->
  <div class="col-md-4 mb-3">
    <label class="form-label">Kecamatan</label>
    <input type="text" name="kecamatan" class="form-control">
  </div>

  <!-- Kabupaten -->
  <div class="col-md-4 mb-3">
    <label class="form-label">Kabupaten</label>
    <input type="text" name="kabupaten" class="form-control">
  </div>

  <!-- Ayah -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Nama Ayah</label>
    <input type="text" name="ayah" class="form-control">
  </div>

  <!-- Pekerjaan Ayah -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Pekerjaan Ayah</label>
    <input type="text" name="pek_ayah" class="form-control">
  </div>

  <!-- Ibu -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Nama Ibu</label>
    <input type="text" name="ibu" class="form-control">
  </div>

  <!-- Pekerjaan Ibu -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Pekerjaan Ibu</label>
    <input type="text" name="pek_ibu" class="form-control">
  </div>

  <!-- Status Keluarga -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Status Keluarga</label>
    <input type="text" name="stskel" class="form-control">
  </div>

  <!-- Anak ke -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Anak ke-</label>
    <input type="number" name="anakke" class="form-control">
  </div>

  <!-- Asal Sekolah -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Asal Sekolah</label>
    <input type="text" name="asal_sek" class="form-control">
  </div>

  <!-- Diterima di Kelas -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Diterima di Kelas</label>
    <input type="text" name="dikelas" class="form-control">
  </div>

  <!-- Tanggal Diterima -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Tanggal Diterima</label>
    <input type="date" name="tgl_terima" class="form-control">
  </div>

  <!-- Keterangan -->
  <div class="col-md-6 mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="2"></textarea>
  </div>
</div>


                  <!-- Form Data Orang Tua -->
                  <div class="tab-pane fade" id="list-ortu" role="tabpanel" aria-labelledby="list-ortu-list">
                    <?php include 'form_data_orang_tua.php'; ?>
                  </div>

                  <!-- Form Data Alamat -->
                  <div class="tab-pane fade" id="list-alamat" role="tabpanel" aria-labelledby="list-alamat-list">
                    <?php include 'form_data_alamat.php'; ?>
                  </div>

                  <!-- Aktivitas Belajar -->
                  <div class="tab-pane fade" id="list-aktivitas" role="tabpanel" aria-labelledby="list-aktivitas-list">
                    <?php include 'form_aktivitas_belajar.php'; ?>
                  </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="mt-4 text-end">
                  <button type="button" class="btn btn-primary" id="btnSimpanSemua">Simpan Semua Data</button>
                </div>

              </form>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (untuk AJAX) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
  $('#btnSimpanSemua').click(function() {
    var formData = $('#formDataSiswa').serialize();
    $.ajax({
      type: 'POST',
      url: 'editsiswa/aksi_simpan.php',
      data: formData,
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          alert(response.message);
          location.reload();
        } else {
          alert(response.message);
        }
      },
      error: function() {
        alert('Terjadi kesalahan saat menyimpan data.');
      }
    });
  });
});
</script>

</body>
</html>
