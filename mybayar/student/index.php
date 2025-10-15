<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
require_once __DIR__ . '/../lib/payment.php';

if (!isset($_SESSION['id_siswa'])) {
    header('location: ../../mydashboard');
    exit;
}

$id_siswa = (int)$_SESSION['id_siswa'];
$student = fetch($koneksi, 'siswa', ['id_siswa' => $id_siswa]);
if (!$student) {
    echo 'Data siswa tidak ditemukan';
    exit;
}

mybayar_ensure_payment_infrastructure($koneksi);

$channels = mybayar_fetch_payment_channels($koneksi, true);
$channelPayload = [];
foreach ($channels as $ch) {
    $channelPayload[] = [
        'code' => $ch['code'],
        'name' => $ch['name'],
        'type' => $ch['channel_type'],
        'instructions' => $ch['instructions'] ?? '',
    ];
}

$payments = [];
$result = mysqli_query($koneksi, "SELECT * FROM m_bayar ORDER BY nama");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $summary = mybayar_order_summary($koneksi, $id_siswa, $row);
        $payments[] = [
            'data' => $row,
            'summary' => $summary,
        ];
    }
    mysqli_free_result($result);
}

$orders = mybayar_fetch_orders_by_student($koneksi, $id_siswa);
$orderPayload = array_map('mybayar_order_payload_for_display', $orders);

$encodedPayload = json_encode([
    'student'  => [
        'id' => $student['id_siswa'],
        'nama' => $student['nama'],
        'nis' => $student['nis'],
        'kelas' => $student['kelas'],
    ],
    'channels' => $channelPayload,
    'payments' => $payments,
    'orders'   => $orderPayload,
], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

$homeurl = '../..';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($setting['sekolah']) ?> - Pembayaran</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= $homeurl ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $homeurl ?>/assets/plugins/materialdesignicons/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="<?= $homeurl ?>/assets/css/main.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f7fb; font-family: 'Poppins', sans-serif; }
        .payment-card { border-radius: 18px; box-shadow: 0 12px 24px rgba(41, 72, 152, 0.12); overflow: hidden; background: #fff; position: relative; }
        .payment-card::before { content: ""; position: absolute; top: -80px; right: -80px; width: 200px; height: 200px; background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(14,165,233,0.05)); border-radius: 50%; }
        .payment-card-header { padding: 24px 24px 0; position: relative; z-index: 2; }
        .payment-card-body { padding: 24px; position: relative; z-index: 2; }
        .badge-rounded { border-radius: 999px; padding: 6px 12px; font-weight: 600; font-size: 12px; letter-spacing: 0.3px; }
        .progress { height: 10px; border-radius: 20px; background: #edf0fb; }
        .progress-bar { border-radius: 20px; background: linear-gradient(90deg, #2563eb, #22d3ee); }
        .btn-pay { border-radius: 12px; padding: 12px 16px; font-weight: 600; }
        .order-history-card { border-radius: 18px; background: #fff; box-shadow: 0 10px 20px rgba(15,23,42,0.08); }
        .table thead th { font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px; color: #64748b; }
        .order-status { border-radius: 999px; padding: 4px 12px; font-weight: 600; font-size: 11px; }
        .qr-preview { max-width: 220px; border: 12px solid #f1f5f9; border-radius: 16px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="../../mydashboard">
                <img src="<?= $homeurl ?>/images/<?= $setting['logo'] ?>" alt="Logo" style="height:36px;margin-right:8px;"> Pembayaran Siswa
            </a>
            <div class="d-flex align-items-center">
                <div class="text-end me-3">
                    <div class="fw-semibold text-primary"><?= htmlspecialchars($student['nama']) ?></div>
                    <small class="text-muted">Kelas <?= htmlspecialchars($student['kelas']) ?></small>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="../../mydashboard"><i class="mdi mdi-view-dashboard"></i> Dashboard</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row gy-4">
            <div class="col-12">
                <h4 class="fw-semibold mb-3">Tagihan Sekolah</h4>
            </div>
            <?php foreach ($payments as $item):
                $data = $item['data'];
                $summary = $item['summary'];
                $progress = $summary['expected_count'] > 0 ? ($summary['count_paid'] / $summary['expected_count']) * 100 : ($summary['total_expected'] > 0 ? ($summary['total_paid'] / $summary['total_expected']) * 100 : 0);
                $progress = min(100, (int)round($progress));
                $btnDisabled = !$summary['can_create'];
            ?>
            <div class="col-md-6 col-xl-4">
                <div class="payment-card h-100">
                    <div class="payment-card-header">
                        <span class="badge bg-light text-primary badge-rounded">Kode <?= htmlspecialchars($data['kode']) ?></span>
                        <h5 class="mt-3 mb-1 fw-semibold"><?= htmlspecialchars($data['nama']) ?></h5>
                        <small class="text-muted">Model: <?= ((int)$data['model'] === 1) ? 'Sekali bayar' : 'Cicilan ' . (int)$data['jumlah'] . 'x'; ?></small>
                    </div>
                    <div class="payment-card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Progres Pembayaran</small>
                                <small class="fw-semibold text-primary"><?= $summary['count_paid']; ?> / <?= $summary['expected_count'] ?: ($summary['total_expected'] > 0 ? 1 : 0); ?></small>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Tagihan</span>
                                <span class="fw-semibold">Rp <?= number_format((int)$summary['total_expected']); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Terbayar</span>
                                <span class="fw-semibold text-success">Rp <?= number_format((int)$summary['total_paid']); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Sisa</span>
                                <span class="fw-semibold text-danger">Rp <?= number_format((int)$summary['outstanding']); ?></span>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-pay w-100" data-id="<?= (int)$data['id']; ?>" <?= $btnDisabled ? 'disabled' : '' ?>>
                            <?php if ($summary['can_create']) : ?>
                                Bayar Rp <?= number_format((int)$summary['amount_due']); ?>
                            <?php else : ?>
                                <?php if ($summary['outstanding'] <= 0) : ?>Lunas<?php else : ?>Menunggu Verifikasi<?php endif; ?>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="row mt-5" id="orderHistory">
            <div class="col-12">
                <div class="order-history-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">Riwayat Order Pembayaran</h5>
                        <small class="text-muted">Order pending perlu dikonfirmasi bendahara.</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Jenis</th>
                                    <th>Nominal</th>
                                    <th>Periode</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="orderHistoryBody">
                                <?php if (empty($orderPayload)) : ?>
                                    <tr><td colspan="7" class="text-center text-muted">Belum ada order pembayaran.</td></tr>
                                <?php else : ?>
                                    <?php foreach ($orderPayload as $ord) : ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($ord['order_no']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($ord['created_at']) ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($ord['periode_label'] ?: '-') ?></td>
                                            <td>Rp <?= number_format((int)$ord['amount']) ?></td>
                                            <td><?= htmlspecialchars($ord['target_blth'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($ord['method_code']) ?></td>
                                            <td>
                                                <?php
                                                $class = 'bg-secondary';
                                                if ($ord['status'] === 'pending') $class = 'bg-warning';
                                                elseif ($ord['status'] === 'paid') $class = 'bg-success';
                                                elseif ($ord['status'] === 'cancelled') $class = 'bg-danger';
                                                elseif ($ord['status'] === 'expired') $class = 'bg-dark';
                                                ?>
                                                <span class="order-status badge <?= $class ?> text-white"><?= strtoupper($ord['status']) ?></span>
                                            </td>
                                            <td><button class="btn btn-outline-secondary btn-sm btnOrderDetail" data-order='<?= json_encode($ord, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>Detail</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="paymentStepSelect">
                        <div class="mb-3">
                            <h6 id="paymentName" class="fw-semibold mb-1"></h6>
                            <small id="paymentInfo" class="text-muted"></small>
                        </div>
                        <form id="paymentForm">
                            <input type="hidden" name="action" value="create_order">
                            <input type="hidden" name="idbayar" id="formIdBayar">
                            <div class="mb-3">
                                <label class="form-label">Pilih Metode Pembayaran</label>
                                <select class="form-select" name="method_code" id="methodCode" required></select>
                            </div>
                            <div class="alert alert-info" id="channelInfo" style="display:none;"></div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Buat Kode Pembayaran</button>
                            </div>
                        </form>
                    </div>
                    <div id="paymentStepResult" style="display:none;">
                        <div class="alert alert-success">Order berhasil dibuat. Silakan lanjutkan pembayaran dengan detail berikut.</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <h6 class="fw-semibold">Detail Order</h6>
                                    <dl class="row mb-0">
                                        <dt class="col-sm-5">Nomor Order</dt>
                                        <dd class="col-sm-7" id="resultOrderNo"></dd>
                                        <dt class="col-sm-5">Nominal</dt>
                                        <dd class="col-sm-7" id="resultAmount"></dd>
                                        <dt class="col-sm-5">Metode</dt>
                                        <dd class="col-sm-7" id="resultMethod"></dd>
                                        <dt class="col-sm-5">Periode</dt>
                                        <dd class="col-sm-7" id="resultPeriod"></dd>
                                        <dt class="col-sm-5">Kode Bayar</dt>
                                        <dd class="col-sm-7" id="resultCode"></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 text-center" id="resultQrContainer" style="display:none;">
                                    <h6 class="fw-semibold">Scan QR</h6>
                                    <img id="resultQrImage" src="" alt="QR Pembayaran" class="qr-preview">
                                </div>
                                <div class="border rounded-3 p-3" id="resultInstructions" style="display:none;">
                                    <h6 class="fw-semibold">Instruksi</h6>
                                    <pre class="mb-0" style="white-space: pre-wrap; font-size: 0.9rem;" id="resultInstructionText"></pre>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-secondary" id="btnBackToForm">Buat Order Lain</button>
                            <a class="btn btn-primary" href="#orderHistory">Lihat Riwayat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="orderDetailBody"></div>
            </div>
        </div>
    </div>

    <script src="<?= $homeurl ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        window.STUDENT_PAYMENT = <?= $encodedPayload ?>;
    </script>
    <script>
    (function(){
        const data = window.STUDENT_PAYMENT || {};
        const modalElement = document.getElementById('paymentModal');
        const modal = new bootstrap.Modal(modalElement);
        const methodSelect = document.getElementById('methodCode');
        const channelInfo = document.getElementById('channelInfo');
        const stepSelect = document.getElementById('paymentStepSelect');
        const stepResult = document.getElementById('paymentStepResult');
        const paymentName = document.getElementById('paymentName');
        const paymentInfo = document.getElementById('paymentInfo');
        const formIdBayar = document.getElementById('formIdBayar');
        const resultOrderNo = document.getElementById('resultOrderNo');
        const resultAmount = document.getElementById('resultAmount');
        const resultMethod = document.getElementById('resultMethod');
        const resultPeriod = document.getElementById('resultPeriod');
        const resultCode = document.getElementById('resultCode');
        const resultQrContainer = document.getElementById('resultQrContainer');
        const resultQrImage = document.getElementById('resultQrImage');
        const resultInstructions = document.getElementById('resultInstructions');
        const resultInstructionText = document.getElementById('resultInstructionText');
        const btnBackToForm = document.getElementById('btnBackToForm');
        const orderHistoryBody = document.getElementById('orderHistoryBody');
        const orderDetailModal = new bootstrap.Modal(document.getElementById('orderDetailModal'));
        const orderDetailBody = document.getElementById('orderDetailBody');

        const renderChannelOptions = () => {
            methodSelect.innerHTML = '';
            if (!Array.isArray(data.channels) || data.channels.length === 0) {
                methodSelect.innerHTML = '<option value="">Channel belum tersedia</option>';
                methodSelect.disabled = true;
                return;
            }
            methodSelect.disabled = false;
            data.channels.forEach(ch => {
                const opt = document.createElement('option');
                opt.value = ch.code;
                opt.textContent = `${ch.name} (${ch.type})`;
                opt.dataset.instructions = ch.instructions || '';
                methodSelect.appendChild(opt);
            });
        };

        const paymentsById = new Map();
        (data.payments || []).forEach(item => {
            paymentsById.set(parseInt(item.data.id, 10), item);
        });

        document.querySelectorAll('.btn-pay').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id, 10);
                const item = paymentsById.get(id);
                if (!item) return;
                paymentName.textContent = item.data.nama;
                paymentInfo.textContent = `Pembayaran ${item.summary.is_monthly ? 'periode ' + (item.summary.periode_label || '') : 'sekaligus'} - Sisa Rp ${Number(item.summary.outstanding).toLocaleString()}`;
                formIdBayar.value = id;
                stepSelect.style.display = 'block';
                stepResult.style.display = 'none';
                channelInfo.style.display = 'none';
                renderChannelOptions();
                modal.show();
            });
        });

        methodSelect.addEventListener('change', () => {
            const selected = methodSelect.options[methodSelect.selectedIndex];
            const instructions = selected ? selected.dataset.instructions : '';
            if (instructions) {
                channelInfo.style.display = 'block';
                channelInfo.textContent = instructions;
            } else {
                channelInfo.style.display = 'none';
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', function(e){
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../api/payment.php', {
                method: 'POST',
                body: formData
            }).then(r => r.json()).then(resp => {
                if (resp.status !== 'ok') {
                    alert(resp.message || 'Gagal membuat order');
                    return;
                }
                const order = resp.order || {};
                resultOrderNo.textContent = order.order_no || '';
                resultAmount.textContent = 'Rp ' + Number(order.amount || 0).toLocaleString();
                resultMethod.textContent = order.method_code || '';
                resultPeriod.textContent = order.periode_label || '';
                resultCode.textContent = order.payment_code || '-';
                if (order.qr_path) {
                    resultQrImage.src = '../../' + order.qr_path;
                    resultQrContainer.style.display = 'block';
                } else {
                    resultQrContainer.style.display = 'none';
                }
                if (order.instructions) {
                    resultInstructionText.textContent = order.instructions;
                    resultInstructions.style.display = 'block';
                } else {
                    resultInstructions.style.display = 'none';
                }
                stepSelect.style.display = 'none';
                stepResult.style.display = 'block';
                fetch('../api/payment.php?action=list_orders')
                    .then(r => r.json())
                    .then(dataResp => {
                        if (dataResp.status === 'ok' && Array.isArray(dataResp.orders)) {
                            renderOrderHistory(dataResp.orders);
                        }
                    });
            }).catch(() => {
                alert('Terjadi kesalahan koneksi');
            });
        });

        btnBackToForm.addEventListener('click', () => {
            stepSelect.style.display = 'block';
            stepResult.style.display = 'none';
        });

        const renderOrderHistory = (orders) => {
            if (!orders || orders.length === 0) {
                orderHistoryBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada order pembayaran.</td></tr>';
                return;
            }
            const rows = orders.map(ord => {
                const statusClass = ord.status === 'pending' ? 'bg-warning' : ord.status === 'paid' ? 'bg-success' : ord.status === 'cancelled' ? 'bg-danger' : ord.status === 'expired' ? 'bg-dark' : 'bg-secondary';
                return `<tr>
                    <td><strong>${ord.order_no}</strong><br><small class="text-muted">${ord.created_at || ''}</small></td>
                    <td>${ord.periode_label || '-'}</td>
                    <td>Rp ${Number(ord.amount || 0).toLocaleString()}</td>
                    <td>${ord.target_blth || '-'}</td>
                    <td>${ord.method_code || '-'}</td>
                    <td><span class="order-status badge ${statusClass} text-white">${String(ord.status || '').toUpperCase()}</span></td>
                    <td><button class="btn btn-outline-secondary btn-sm btnOrderDetail" data-order='${JSON.stringify(ord).replace(/'/g, '&apos;')}'>Detail</button></td>
                </tr>`;
            }).join('');
            orderHistoryBody.innerHTML = rows;
            attachDetailHandlers();
        };

        const attachDetailHandlers = () => {
            document.querySelectorAll('.btnOrderDetail').forEach(btn => {
                btn.addEventListener('click', () => {
                    const ord = JSON.parse(btn.getAttribute('data-order'));
                    let html = '<dl class="row">';
                    html += `<dt class="col-sm-4">Nomor Order</dt><dd class="col-sm-8"><code>${ord.order_no}</code></dd>`;
                    html += `<dt class="col-sm-4">Status</dt><dd class="col-sm-8">${ord.status}</dd>`;
                    html += `<dt class="col-sm-4">Nominal</dt><dd class="col-sm-8">Rp ${Number(ord.amount || 0).toLocaleString()}</dd>`;
                    html += `<dt class="col-sm-4">Metode</dt><dd class="col-sm-8">${ord.method_code || '-'}</dd>`;
                    html += `<dt class="col-sm-4">Periode</dt><dd class="col-sm-8">${ord.periode_label || '-'} (${ord.target_blth || '-'})</dd>`;
                    if (ord.payment_code) {
                        html += `<dt class="col-sm-4">Kode Bayar</dt><dd class="col-sm-8"><code>${ord.payment_code}</code></dd>`;
                    }
                    if (ord.qr_path) {
                        html += `<dt class="col-sm-4">QR</dt><dd class="col-sm-8"><img src="../../${ord.qr_path}" alt="QR" class="qr-preview"></dd>`;
                    }
                    if (ord.instructions) {
                        html += `<dt class="col-sm-4">Instruksi</dt><dd class="col-sm-8" style="white-space:pre-wrap;">${ord.instructions}</dd>`;
                    }
                    html += '</dl>';
                    orderDetailBody.innerHTML = html;
                    orderDetailModal.show();
                });
            });
        };

        attachDetailHandlers();
    })();
    </script>
</body>
</html>
