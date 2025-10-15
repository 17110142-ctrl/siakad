<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
include "../config/koneksi.php";

$mutasi = $_GET['mutasi'] ?? '';
$kelas  = $_GET['kelas']  ?? '';
?>
<div class="row">
  <div class="col-md-4">
    <div class="card widget-payment-request">
      <div class="card-header">
        <h5 class="bold">Mutasi Siswa</h5>
      </div>
      <div class="card-body">
        <label class="form-label bold">Alasan Mutasi</label>
        <select class="form-select" id="mutasi">
          <option value="">Pilih</option>
          <option value="keluar" <?= $mutasi=='keluar'?'selected':'' ?>>Keluar</option>
          <option value="tamat"  <?= $mutasi=='tamat'?'selected':''  ?>>Tamat</option>
          <option value="naik"   <?= $mutasi=='naik'?'selected':''   ?>>Naik Kelas</option>
        </select>

        <div class="mt-3">
          <label class="form-label bold">Kelas Asal</label>
          <select class="form-select" id="kelas">
            <option value="">Pilih Kelas</option>
            <?php
            $res = mysqli_query($koneksi, "SELECT * FROM kelas");
            while($r = mysqli_fetch_assoc($res)):
            ?>
              <option value="<?= $r['kelas'] ?>" <?= $kelas==$r['kelas']?'selected':'' ?>>
                <?= $r['kelas'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="mt-3 text-end">
          <button id="cari" class="btn btn-primary">Tampilkan</button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <?php if($mutasi=='naik' && $kelas!=''): ?>
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">MUTASI NAIK KE KELAS <?= strtoupper($kelas) ?></h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="datatable-mutu" class="table table-bordered table-hover" style="font-size:12px">
            <thead>
              <tr>
                <th>NO</th>
                <th>NIS</th>
                <th>NAMA</th>
                <th>KELAS ASAL</th>
                <th>KELAS BARU</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              $q = mysqli_query($koneksi,
                "SELECT * FROM siswa 
                 WHERE kelas='$kelas' 
                   AND (stskel IS NULL OR stskel!='Tamat')"
              );
              while($d = mysqli_fetch_assoc($q)):
                $no++;
              ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $d['nis'] ?></td>
                <td><?= $d['nama'] ?></td>
                <td><?= $d['kelas'] ?></td>
                <td>
                  <select class="form-select select-naik"
                          data-id="<?= $d['id_siswa'] ?>"
                          data-nis="<?= $d['nis'] ?>"
                          data-nama="<?= htmlspecialchars($d['nama'], ENT_QUOTES) ?>"
                          data-kelas="<?= $d['kelas'] ?>">
                    <option value="">Pilih</option>
                    <?php
                    $r2 = mysqli_query($koneksi, "SELECT * FROM kelas");
                    while($k2 = mysqli_fetch_assoc($r2)):
                    ?>
                      <option value="<?= $k2['kelas'] ?>">
                        <?= $k2['kelas'] ?>
                      </option>
                    <?php endwhile; ?>
                  </select>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php elseif(in_array($mutasi, ['tamat','keluar']) && $kelas!=''): ?>
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
          MUTASI <?= strtoupper($mutasi) ?> KELAS <?= strtoupper($kelas) ?>
        </h5>
        <button id="btn-simpan" class="btn btn-success">Simpan</button>
      </div>
      <div class="card-body">
        <form id="form-mutasi" method="POST" action="siswa/tmutasi.php?pg=siswa">
          <input type="hidden" name="aksi" value="<?= $mutasi ?>">
          <input type="hidden" name="kelas_asal" value="<?= $kelas ?>">
          <div class="table-responsive">
            <table id="datatable-mutu-simple" class="table table-bordered table-hover" style="font-size:12px">
              <thead>
                <tr>
                  <th><input type="checkbox" id="check-all"></th>
                  <th>NO</th>
                  <th>NIS</th>
                  <th>NAMA</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 0;
                $q2 = mysqli_query($koneksi,
                  "SELECT * FROM siswa 
                   WHERE kelas='$kelas' 
                     AND (stskel IS NULL OR stskel!='Tamat')"
                );
                while($d2 = mysqli_fetch_assoc($q2)):
                  $no++;
                ?>
                <tr>
                  <td>
                    <input type="checkbox" class="chk-siswa"
                           name="selected[]" 
                           value="<?= $d2['id_siswa'] ?>">
                  </td>
                  <td><?= $no ?></td>
                  <td><?= $d2['nis'] ?></td>
                  <td><?= htmlspecialchars($d2['nama'], ENT_QUOTES) ?></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </form>
      </div>
    </div>
    <?php endif ?>
  </div>
</div>

<script>
$(function(){
  $('#cari').on('click', function(){
    let m = $('#mutasi').val(),
        k = $('#kelas').val();
    if(!m || !k){ alert('Pilih mutasi & kelas asal!'); return }
    location.href = "?pg=<?= enkripsi('mutasi') ?>&mutasi="+m+"&kelas="+k;
  });

  if($.fn.DataTable && $('#datatable-mutu').length){
    $('#datatable-mutu').DataTable({
      paging: false,
      searching: true,
      ordering: true,
      info: false,
      lengthChange: false,
      order: [[3, 'asc']]
    });
  }

  if($.fn.DataTable && $('#datatable-mutu-simple').length){
    $('#datatable-mutu-simple').DataTable({
      paging: false,
      searching: true,
      ordering: true,
      info: false,
      lengthChange: false
    });
  }

  $('#check-all').on('change', function(){
    $('.chk-siswa').prop('checked', $(this).is(':checked'));
  });

  $('.chk-siswa').on('change', function(){
    let total = $('.chk-siswa').length;
    let checked = $('.chk-siswa:checked').length;
    $('#check-all').prop('checked', total === checked);
  });

  $('#btn-simpan').on('click', function(){
    if($('.chk-siswa:checked').length === 0){
      alert('Pilih minimal satu siswa untuk dimutasi.');
      return;
    }
    $('#form-mutasi').submit();
  });

  $('.select-naik').on('change', function(){
    let sel = $(this),
        ket = sel.val();
    if(!ket) return;

    let payload = {
      id_siswa: sel.data('id'),
      nis:      sel.data('nis'),
      nama:     sel.data('nama'),
      kelas_asal: sel.data('kelas'),
      kelas_baru:  ket
    };

    $.ajax({
      type: 'POST',
      url: 'siswa/tmutasi.php?pg=siswa',
      data: payload,
      beforeSend: function(){
        sel.prop('disabled', true)
           .after('<span class="spinner-border spinner-border-sm text-primary ms-2"></span>');
      },
      success: function(res){
        if($.trim(res)==='OK'){
          sel.closest('tr').fadeOut(500);
        } else {
          iziToast.error({
            title: 'Error',
            message: 'Gagal mutasi: '+res,
            position: 'topRight'
          });
          sel.prop('disabled', false);
          sel.next('.spinner-border').remove();
        }
      },
      error: function(){
        iziToast.error({
          title: 'Error',
          message: 'Koneksi server gagal',
          position: 'topRight'
        });
        sel.prop('disabled', false);
        sel.next('.spinner-border').remove();
      }
    });
  });
});
</script>
