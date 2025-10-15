<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
require_once __DIR__ . '/../lib/payment.php';

mybayar_ensure_payment_infrastructure($koneksi);

$sql = "SELECT o.*, s.nama AS siswa_nama, s.nis, s.kelas, s.nowa, m.nama AS bayar_nama, m.kode AS bayar_kode
        FROM payment_orders o
        LEFT JOIN siswa s ON s.id_siswa = o.idsiswa
        LEFT JOIN m_bayar m ON m.id = o.idbayar
        ORDER BY o.created_at DESC";
$orders = [];
$result = mysqli_query($koneksi, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    mysqli_free_result($result);
}
$channels = mybayar_fetch_payment_channels($koneksi, false);
$channelMap = [];
foreach ($channels as $ch) {
    $channelMap[$ch['code']] = $ch;
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Order Pembayaran Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Siswa</th>
                                <th>Jenis Bayar</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>QR / Kode</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)) : ?>
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada order pembayaran.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($orders as $ord) :
                                    $chan = $channelMap[$ord['method_code']] ?? null;
                                    $status = strtoupper($ord['status']);
                                    $badgeClass = 'bg-secondary';
                                    if ($ord['status'] === 'pending') {
                                        $badgeClass = 'bg-warning';
                                    } elseif ($ord['status'] === 'paid') {
                                        $badgeClass = 'bg-success';
                                    } elseif ($ord['status'] === 'cancelled') {
                                        $badgeClass = 'bg-danger';
                                    } elseif ($ord['status'] === 'expired') {
                                        $badgeClass = 'bg-dark';
                                    }
                                ?>
                                <tr data-order='<?= json_encode($ord, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                                    <td>
                                        <strong><?= htmlspecialchars($ord['order_no']) ?></strong><br>
                                        <small><?= htmlspecialchars($ord['created_at']) ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($ord['siswa_nama'] ?? '-') ?><br>
                                        <small>NIS: <?= htmlspecialchars($ord['nis'] ?? '-') ?> | Kelas: <?= htmlspecialchars($ord['kelas'] ?? '-') ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($ord['bayar_nama'] ?? '-') ?><br>
                                        <small>Kode: <?= htmlspecialchars($ord['bayar_kode'] ?? '-') ?></small>
                                    </td>
                                    <td>Rp <?= number_format((int)$ord['amount']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($ord['method_code']) ?><br>
                                        <small><?= htmlspecialchars($chan['name'] ?? '') ?></small>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($ord['periode_label'] ?? '-') ?><br>
                                        <small><?= $ord['target_blth'] ? htmlspecialchars($ord['target_blth']) : '-' ?></small>
                                    </td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $status ?></span></td>
                                    <td>
                                        <?php if ($ord['qr_path']) : ?>
                                            <img src="../<?= htmlspecialchars($ord['qr_path']) ?>" alt="QR" style="max-width:70px;">
                                        <?php elseif ($ord['payment_code']) : ?>
                                            <code><?= htmlspecialchars($ord['payment_code']) ?></code>
                                        <?php else : ?>
                                            <small>-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info btnDetail">Detail</button>
                                            <?php if ($ord['status'] === 'pending') : ?>
                                                <button class="btn btn-success btnConfirm">Konfirmasi</button>
                                                <button class="btn btn-danger btnCancel">Batalkan</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Order Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const modal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
    const body = document.getElementById('orderDetailBody');

    document.querySelectorAll('#ordersTable .btnDetail').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = JSON.parse(btn.closest('tr').getAttribute('data-order'));
            let html = `<dl class="row">`;
            html += `<dt class="col-sm-4">Order</dt><dd class="col-sm-8"><code>${data.order_no}</code></dd>`;
            html += `<dt class="col-sm-4">Tanggal</dt><dd class="col-sm-8">${data.created_at}</dd>`;
            html += `<dt class="col-sm-4">Status</dt><dd class="col-sm-8">${data.status}</dd>`;
            html += `<dt class="col-sm-4">Nominal</dt><dd class="col-sm-8">Rp ${Number(data.amount).toLocaleString()}</dd>`;
            if (data.periode_label) {
                html += `<dt class="col-sm-4">Periode</dt><dd class="col-sm-8">${data.periode_label} (${data.target_blth || '-'})</dd>`;
            }
            if (data.payment_code) {
                html += `<dt class="col-sm-4">Kode Bayar</dt><dd class="col-sm-8"><code>${data.payment_code}</code></dd>`;
            }
            if (data.qr_path) {
                html += `<dt class="col-sm-4">QR</dt><dd class="col-sm-8"><img src="../${data.qr_path}" alt="QR" style="max-width:240px;"></dd>`;
            }
            if (data.instructions) {
                html += `<dt class="col-sm-4">Instruksi</dt><dd class="col-sm-8" style="white-space:pre-wrap;">${data.instructions}</dd>`;
            }
            html += `</dl>`;
            body.innerHTML = html;
            modal.show();
        });
    });

    const sendAction = (payload) => {
        const formData = new URLSearchParams(payload);
        return fetch('api/payment_admin.php', {
            method: 'POST',
            body: formData
        }).then(r => r.json());
    };

    document.querySelectorAll('#ordersTable .btnConfirm').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('Konfirmasi pembayaran ini?')) return;
            const data = JSON.parse(btn.closest('tr').getAttribute('data-order'));
            sendAction({ action: 'mark_paid', order_no: data.order_no }).then(resp => {
                if (resp.status === 'ok') {
                    window.location.reload();
                } else {
                    alert(resp.message || 'Gagal mengkonfirmasi order');
                }
            });
        });
    });

    document.querySelectorAll('#ordersTable .btnCancel').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('Batalkan order ini?')) return;
            const data = JSON.parse(btn.closest('tr').getAttribute('data-order'));
            sendAction({ action: 'cancel_order', order_no: data.order_no }).then(resp => {
                if (resp.status === 'ok') {
                    window.location.reload();
                } else {
                    alert(resp.message || 'Gagal membatalkan order');
                }
            });
        });
    });
})();
</script>
