<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
require_once("wa_helpers.php");

$today = date('Y-m-d');
$metrics = wa_metrics_get_day($today);
$sum = $metrics['summary'];
$events = is_array($metrics['events']) ? $metrics['events'] : [];

$eventsDesc = array_reverse($events);

$fails = [];
foreach ($eventsDesc as $evt) {
    if (empty($evt['success'])) {
        $fails[] = $evt;
        if (count($fails) >= 50) {
            break;
        }
    }
}

$lasts = array_slice($eventsDesc, 0, 10);
?>

<style>
  .wa-summary{display:flex;justify-content:space-between;gap:8px;margin-bottom:8px}
  .wa-chip{display:inline-block;padding:3px 8px;border-radius:12px;font-size:.75rem}
  .wa-chip.success{background:#e7f5ec;color:#198754}
  .wa-chip.fail{background:#fdecec;color:#b42318}
  .wa-chip.total{background:#eef2ff;color:#3538cd}
  .wa-failed-item{display:flex;align-items:center;justify-content:space-between;border:1px solid #f1f1f1;border-radius:8px;padding:8px 10px;margin-bottom:6px}
  .wa-muted{color:#6b7280;font-size:.8rem}
  .btn-sm{padding:.15rem .4rem;font-size:.75rem}
</style>

<div class="wa-summary">
  <div class="wa-chip total">Total: <?= (int)$sum['total'] ?></div>
  <div class="wa-chip success">Terkirim: <?= (int)$sum['sent'] ?></div>
  <div class="wa-chip fail">Gagal: <?= (int)$sum['failed'] ?></div>
</div>

<?php if (count($fails) > 0): ?>
  <div style="display:flex;justify-content:flex-end;margin-bottom:6px;">
    <button id="wa-resend-all" class="btn btn-sm btn-secondary">Kirim Ulang Semua</button>
  </div>
  <?php foreach($fails as $f):
        $logId = (string)($f['log_id'] ?? '');
        $timeLabel = $f['timestamp'] ?? ($f['waktu'] ?? '');
    ?>
    <div class="wa-failed-item" data-id="<?= htmlspecialchars($logId) ?>">
      <div>
        <div><b><?= htmlspecialchars($f['nama'] ?? '-') ?></b> <span class="wa-muted">(<?= htmlspecialchars($f['number'] ?? '-') ?>)</span></div>
        <div class="wa-muted">Jenis: <?= htmlspecialchars($f['jenis'] ?? 'lain') ?> | <?= htmlspecialchars($timeLabel ?: '-') ?></div>
        <?php if (!empty($f['error'])): ?><div class="wa-muted">Err: <?= htmlspecialchars($f['error']) ?></div><?php endif; ?>
      </div>
      <div>
        <button class="btn btn-sm btn-warning wa-resend-one" <?= $logId !== '' ? '' : 'disabled' ?>>Kirim Ulang</button>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="wa-chip success">Semua notifikasi WA terkirim.</div>
<?php endif; ?>

<hr/>
<div class="wa-muted" style="margin-bottom:4px;">Aktivitas Terakhir</div>
<?php foreach($lasts as $l):
      $timeLabel = $l['timestamp'] ?? ($l['waktu'] ?? '');
  ?>
  <div class="wa-failed-item" style="border-color:#eef2ff;">
    <div>
      <div><b><?= htmlspecialchars($l['nama'] ?? '-') ?></b> <span class="wa-muted">(<?= htmlspecialchars($l['number'] ?? '-') ?>)</span></div>
      <div class="wa-muted">Jenis: <?= htmlspecialchars($l['jenis'] ?? 'lain') ?> | <?= htmlspecialchars($timeLabel ?: '-') ?></div>
    </div>
    <div>
      <?php if (!empty($l['success'])): ?><span class="wa-chip success">OK</span><?php else: ?><span class="wa-chip fail">Gagal</span><?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>

<script>
  $(document)
    .off('click', '.wa-resend-one')
    .on('click', '.wa-resend-one', function(){
      var $row = $(this).closest('.wa-failed-item');
      var id = ($row.data('id') || '').toString();
      if (!id) {
          return;
      }
      var $btn = $(this); $btn.prop('disabled', true).text('Mengirim...');
      $.post('wa_absen_api.php', { action: 'resend_one', id: id }, function(resp){
          $('#logpesan').load('logpesan.php');
      }, 'json').fail(function(){ $btn.prop('disabled', false).text('Kirim Ulang'); });
    })
    .off('click', '#wa-resend-all')
    .on('click', '#wa-resend-all', function(){
      var $btn = $(this); $btn.prop('disabled', true).text('Memproses...');
      $.post('wa_absen_api.php', { action: 'resend_all_failed' }, function(resp){
          $('#logpesan').load('logpesan.php');
      }, 'json').always(function(){ $btn.prop('disabled', false).text('Kirim Ulang Semua'); });
    });
</script>
