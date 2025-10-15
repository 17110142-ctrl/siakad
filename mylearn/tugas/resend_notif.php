<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$failed_list = [];
$return_to = $_POST['return_to'] ?? ($_SERVER['HTTP_REFERER'] ?? 'index.php');
$max_rounds = 1;

if (!empty($_POST['failed_base64'])) {
    $json = base64_decode($_POST['failed_base64'], true);
    if ($json !== false) {
        $decoded = json_decode($json, true);
        if (is_array($decoded)) $failed_list = $decoded;
    }
}
if (empty($failed_list)) {
    $arr_nama = isset($_POST['nama_siswa']) ? (array)$_POST['nama_siswa'] : [];
    $arr_nowa = isset($_POST['nowa_siswa']) ? (array)$_POST['nowa_siswa'] : [];
    $id_tugas = $_POST['id_tugas'] ?? '';
    $type     = $_POST['type'] ?? 'perubahan';
    foreach ($arr_nama as $i => $nm) {
        $failed_list[] = [
            'nama'     => $nm,
            'nohp'     => $arr_nowa[$i] ?? '',
            'id_tugas' => $id_tugas,
            'type'     => $type
        ];
    }
}
if (empty($failed_list) && !empty($_SESSION['notif_failed'])) {
    $failed_list = $_SESSION['notif_failed'];
}

function build_url($file) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base   = rtrim(dirname($_SERVER['REQUEST_URI'] ?? '/'), '/');
    return $scheme.'://'.$host.$base.'/'.$file;
}

function resend_one($nama, $nohp, $id_tugas, $type) {
    $url = build_url('kirim_notif_satu.php');
    $payload = [
        'id_tugas'   => $id_tugas,
        'nama_siswa' => $nama,
        'nowa_siswa' => preg_replace('/\D+/', '', $nohp),
        'type'       => $type ?: 'perubahan'
    ];
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_POSTFIELDS => http_build_query($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($resp === false || $code >= 400) return false;
    $data = json_decode($resp, true);
    return is_array($data) && ($data['status'] ?? '') === 'ok';
}

$remaining = $failed_list;
$success = 0;
for ($r=1; $r <= $max_rounds; $r++) {
    $next = [];
    foreach ($remaining as $row) {
        $nama = trim((string)($row['nama'] ?? ''));
        $nohp = trim((string)($row['nohp'] ?? ($row['nowa'] ?? '')));
        $id_tugas = trim((string)($row['id_tugas'] ?? ($_POST['id_tugas'] ?? '')));
        $type = trim((string)($row['type'] ?? ($_POST['type'] ?? 'perubahan')));
        if ($nama === '' || $nohp === '' || $id_tugas === '') {
            $row['alasan'] = 'Data tidak lengkap';
            $next[] = $row;
            continue;
        }
        $ok = resend_one($nama, $nohp, $id_tugas, $type);
        if ($ok) {
            $success++;
        } else {
            $row['alasan'] = 'Gagal saat kirim ulang';
            $next[] = $row;
        }
    }
    $remaining = $next;
    if (empty($remaining)) break;
}

if (!empty($remaining)) {
    $_SESSION['notif_failed'] = $remaining;
} else {
    unset($_SESSION['notif_failed']);
}

$is_ajax = (isset($_POST['ajax']) && $_POST['ajax'] === '1') || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
if ($is_ajax) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'succeeded' => $success,
        'failed' => count($remaining),
        'remaining' => $remaining
    ]);
    exit;
}

header('Location: '.$return_to);
exit;
?>

<script>
$(document).on('submit', '#resend-form', function(e){
  e.preventDefault();
  var form = $(this);
  var btn  = form.find('button[type="submit"]');
  var data = form.serializeArray();
  data.push({name: 'ajax', value: '1'});
  btn.prop('disabled', true).text('Memproses...');
  $.ajax({
    url: form.attr('action'),
    type: 'POST',
    data: $.param(data),
    dataType: 'json',
    success: function(resp){
      if (resp && resp.status === 'ok') {
        if (resp.failed > 0) {
          $('#failed-section').show();
          $('#failed-notif-list').empty();
          resp.remaining.forEach(function(s){
            var nama = s.nama || '-';
            var nohp = s.nohp || s.nowa || '-';
            $('#failed-notif-list').append('<li class="list-group-item list-group-item-danger">'+nama+' ('+nohp+')</li>');
          });
          var json = JSON.stringify(resp.remaining);
          var b64  = btoa(unescape(encodeURIComponent(json)));
          $('#failed_base64').val(b64);
          $('#resend-form .dyn').remove();
          resp.remaining.forEach(function(s){
            $('<input>',{type:'hidden',name:'nama_siswa[]',value:s.nama,class:'dyn'}).appendTo('#resend-form');
            $('<input>',{type:'hidden',name:'nowa_siswa[]',value:(s.nohp||s.nowa||''),class:'dyn'}).appendTo('#resend-form');
          });
          swal({ title: 'Sebagian gagal', text: 'Berhasil: '+resp.succeeded+'\nGagal: '+resp.failed, type: 'warning' });
        } else {
          swal({ title: 'Selesai', text: 'Semua pesan terkirim.', type: 'success' }).then(function(){ window.location.replace('?pg=<?= enkripsi('tugas') ?>'); });
        }
      } else {
        swal('Gagal', 'Resend gagal diproses.', 'error');
      }
    },
    error: function(){ swal('Error', 'Koneksi gagal.', 'error'); },
    complete: function(){ btn.prop('disabled', false).text('Kirimkan Ulang'); }
  });
});
</script>
