<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
require_once __DIR__ . '/../lib/payment.php';

mybayar_ensure_payment_infrastructure($koneksi);
$channels = mybayar_fetch_payment_channels($koneksi, false);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Channel Pembayaran</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#channelModal" id="btnAddChannel">
                    <i class="material-icons" style="font-size:16px">add</i> Tambah Channel
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="channelTable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Instruksi</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($channels)) : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada channel pembayaran.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($channels as $row) : ?>
                                    <tr data-code="<?= htmlspecialchars($row['code']) ?>">
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><code><?= htmlspecialchars($row['code']) ?></code></td>
                                        <td><?= htmlspecialchars($row['channel_type']) ?></td>
                                        <td>
                                            <?php if ((int)$row['is_active'] === 1) : ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="max-width:260px; white-space: pre-wrap;"><?= nl2br(htmlspecialchars((string)$row['instructions'])) ?></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-info btnEdit" data-channel='<?= json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>Edit</button>
                                                <button class="btn btn-warning btnToggle" data-active="<?= (int)$row['is_active'] ?>">Toggle</button>
                                                <button class="btn btn-danger btnDelete">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <small class="text-muted">Gunakan tipe <strong>QRIS</strong> untuk menampilkan QR dinamis, dan <strong>PAYCODE</strong> untuk kode bayar/virtual account.</small>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="channelModal" tabindex="-1" aria-labelledby="channelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="channelModalLabel">Channel Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="channelForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="save_channel">
                    <input type="hidden" name="original_code" id="original_code">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Channel</label>
                            <input type="text" class="form-control" name="name" id="channel_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" name="code" id="channel_code" maxlength="32" required>
                            <small class="text-muted">Gunakan huruf/angka tanpa spasi, contoh: QRIS_UTAMA</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tipe Channel</label>
                            <select class="form-select" name="channel_type" id="channel_type" required>
                                <option value="QRIS">QRIS</option>
                                <option value="PAYCODE">PAYCODE</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prefix Kode Bayar (PAYCODE)</label>
                            <input type="text" class="form-control" name="payment_code_prefix" id="payment_code_prefix" maxlength="16" placeholder="CTH: 8899">
                            <small class="text-muted">Opsional, akan digabungkan dengan NIS dan ID order.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Template Payload (QRIS)</label>
                            <textarea class="form-control" name="payload_template" id="payload_template" rows="3" placeholder="Masukkan payload QRIS atau URL pembayaran."></textarea>
                            <small class="text-muted">Placeholder yang dapat digunakan: {amount}, {order_no}, {student_id}, {student_nis}, {student_name}, {kelas}, {kode_bayar}, {payment_code}.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Instruksi Pembayaran</label>
                            <textarea class="form-control" name="instructions" id="instructions" rows="4" placeholder="Langkah pembayaran atau informasi rekening"></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Channel aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    const modal = new bootstrap.Modal(document.getElementById('channelModal'));
    const form = document.getElementById('channelForm');
    const btnAdd = document.getElementById('btnAddChannel');
    const originalCode = document.getElementById('original_code');
    const channelType = document.getElementById('channel_type');
    const payloadTemplate = document.getElementById('payload_template');
    const prefixInput = document.getElementById('payment_code_prefix');

    const resetForm = () => {
        form.reset();
        originalCode.value = '';
        document.getElementById('channel_code').readOnly = false;
    };

    const fillForm = (data) => {
        document.getElementById('channel_name').value = data.name || '';
        document.getElementById('channel_code').value = data.code || '';
        document.getElementById('channel_code').readOnly = !!data.code;
        document.getElementById('channel_type').value = data.channel_type || 'QRIS';
        document.getElementById('payment_code_prefix').value = data.payment_code_prefix || '';
        document.getElementById('payload_template').value = data.payload_template || '';
        document.getElementById('instructions').value = data.instructions || '';
        document.getElementById('is_active').checked = (parseInt(data.is_active, 10) === 1);
        originalCode.value = data.code || '';
    };

    channelType.addEventListener('change', () => {
        const type = channelType.value;
        if (type === 'QRIS') {
            payloadTemplate.removeAttribute('disabled');
        } else {
            payloadTemplate.setAttribute('disabled', 'disabled');
        }
    });

    btnAdd.addEventListener('click', () => {
        resetForm();
        channelType.dispatchEvent(new Event('change'));
    });

    document.querySelectorAll('#channelTable .btnEdit').forEach(btn => {
        btn.addEventListener('click', () => {
            const data = JSON.parse(btn.getAttribute('data-channel'));
            resetForm();
            fillForm(data);
            channelType.dispatchEvent(new Event('change'));
            modal.show();
        });
    });

    document.querySelectorAll('#channelTable .btnToggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('tr');
            const code = row.getAttribute('data-code');
            const active = btn.getAttribute('data-active') === '1';
            fetch('api/payment_admin.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'toggle_channel',
                    code: code,
                    to_active: active ? '0' : '1'
                })
            }).then(r => r.json()).then(resp => {
                if (resp.status === 'ok') {
                    window.location.reload();
                } else {
                    alert(resp.message || 'Gagal memperbarui channel');
                }
            });
        });
    });

    document.querySelectorAll('#channelTable .btnDelete').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!confirm('Hapus channel ini?')) return;
            const row = btn.closest('tr');
            const code = row.getAttribute('data-code');
            fetch('api/payment_admin.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'delete_channel', code })
            }).then(r => r.json()).then(resp => {
                if (resp.status === 'ok') {
                    row.remove();
                } else {
                    alert(resp.message || 'Gagal menghapus channel');
                }
            });
        });
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('api/payment_admin.php', {
            method: 'POST',
            body: formData
        }).then(r => r.json()).then(resp => {
            if (resp.status === 'ok') {
                modal.hide();
                window.location.reload();
            } else {
                alert(resp.message || 'Gagal menyimpan channel');
            }
        });
    });
})();
</script>
