<?php
defined('APK') or exit('No accsess');

// Pastikan koneksi tersedia: $koneksi

// Helper: cek dan buat tabel guru_wali jika belum ada
function ensure_table_guru_wali(mysqli $db)
{
    $res = $db->query("SHOW TABLES LIKE 'guru_wali'");
    if ($res && $res->num_rows === 0) {
        $db->query(
            "CREATE TABLE IF NOT EXISTS guru_wali (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_guru INT NOT NULL,
                id_siswa INT NOT NULL,
                UNIQUE KEY uq_guru_siswa (id_guru, id_siswa),
                KEY idx_siswa (id_siswa)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }
}

ensure_table_guru_wali($koneksi);

// Ambil parameter
$selected_guru = isset($_GET['id_guru']) ? (int) $_GET['id_guru'] : 0;

// Aksi penugasan / hapus via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = $_POST['aksi'] ?? '';
    if ($aksi === 'simpan') {
        $selected_guru = isset($_POST['id_guru']) ? (int) $_POST['id_guru'] : 0;
        $selected_ids = isset($_POST['selected_ids']) ? trim($_POST['selected_ids']) : '';
        $ids = [];
        if ($selected_ids !== '') {
            foreach (explode(',', $selected_ids) as $id) {
                $v = (int) trim($id);
                if ($v > 0) $ids[$v] = true; // gunakan map untuk unique
            }
        }

        if ($selected_guru > 0) {
            $koneksi->begin_transaction();
            try {
                if (count($ids) > 0) {
                    // Hapus yang tidak terpilih
                    $in = implode(',', array_keys($ids));
                    $koneksi->query("DELETE FROM guru_wali WHERE id_guru=" . $selected_guru . " AND id_siswa NOT IN ($in)");
                } else {
                    // Jika tidak ada pilihan, hapus semua mapping untuk guru ini
                    $koneksi->query("DELETE FROM guru_wali WHERE id_guru=" . $selected_guru);
                }

                // Tambah yang baru (gunakan ON DUPLICATE untuk idempotent)
                if (count($ids) > 0) {
                    $values = [];
                    foreach (array_keys($ids) as $sid) {
                        $values[] = '(' . $selected_guru . ',' . (int)$sid . ')';
                    }
                    $sqlIns = "INSERT INTO guru_wali (id_guru,id_siswa) VALUES " . implode(',', $values) . " ON DUPLICATE KEY UPDATE id_siswa = VALUES(id_siswa)";
                    $koneksi->query($sqlIns);
                }

                $koneksi->commit();
                $msg_sukses = 'Penugasan guru wali berhasil disimpan.';
            } catch (Throwable $e) {
                $koneksi->rollback();
                $msg_error = 'Gagal menyimpan: ' . $e->getMessage();
            }
        } else {
            $msg_error = 'Pilih guru terlebih dahulu.';
        }
    } elseif ($aksi === 'hapus_siswa') {
        $id_guru_req = (int)($_POST['id_guru'] ?? 0);
        $id_siswa_req = (int)($_POST['id_siswa'] ?? 0);
        $response = ['success' => false, 'message' => 'Parameter tidak valid'];
        if ($id_guru_req > 0 && $id_siswa_req > 0) {
            $stmt = $koneksi->prepare('DELETE FROM guru_wali WHERE id_guru=? AND id_siswa=?');
            if ($stmt) {
                $stmt->bind_param('ii', $id_guru_req, $id_siswa_req);
                if ($stmt->execute()) {
                    $response = ['success' => true];
                } else {
                    $response = ['success' => false, 'message' => 'Gagal menghapus di database'];
                }
                $stmt->close();
            } else {
                $response = ['success' => false, 'message' => 'Tidak dapat menyiapkan query'];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Ambil data guru (dropdown)
$guru_list = [];
$qg = $koneksi->query("SELECT id_user, nama, nip FROM users WHERE level='guru' ORDER BY nama ASC");
if ($qg) {
    while ($r = $qg->fetch_assoc()) {
        $guru_list[] = $r;
    }
    $qg->close();
}

// Ambil daftar kelas (horizontal filter)
$kelas_list = [];
$qk = $koneksi->query("SELECT kelas FROM kelas ORDER BY kelas ASC");
if ($qk) {
    while ($r = $qk->fetch_assoc()) {
        $kelas = trim($r['kelas']);
        if ($kelas !== '') $kelas_list[] = $kelas;
    }
    $qk->close();
}

// Ambil mapping siswa yang sudah ditugaskan ke guru terpilih
$assigned = [];
if ($selected_guru > 0) {
    $qa = $koneksi->query("SELECT id_siswa FROM guru_wali WHERE id_guru=" . $selected_guru);
    if ($qa) {
        while ($r = $qa->fetch_assoc()) {
            $assigned[(int)$r['id_siswa']] = true;
        }
        $qa->close();
    }
}
$assigned_ids = array_keys($assigned);

// Load siswa per kelas (sekaligus; akan ditampilkan per tab)
$siswa_per_kelas = [];
if (!empty($kelas_list)) {
    foreach ($kelas_list as $kls) {
        $esc = mysqli_real_escape_string($koneksi, $kls);
        $qs = $koneksi->query("SELECT id_siswa, nis, nama, kelas FROM siswa WHERE kelas='$esc' ORDER BY nama ASC");
        $list = [];
        if ($qs) {
            while ($r = $qs->fetch_assoc()) { $list[] = $r; }
            $qs->close();
        }
        $siswa_per_kelas[$kls] = $list;
    }
}

// Ambil daftar siswa yang sudah punya guru wali (untuk filter tampilan)
$all_assigned_students = [];
$qa_all = $koneksi->query("SELECT DISTINCT id_siswa FROM guru_wali");
if ($qa_all) {
    while ($r = $qa_all->fetch_assoc()) {
        $all_assigned_students[(int)$r['id_siswa']] = true;
    }
    $qa_all->close();
}
?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header warna d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Pengelolaan Guru Wali</h5>
        <div>
            <a href="?pg=<?= enkripsi('guru') ?>" class="btn btn-sm btn-light"><i class="material-icons">arrow_back</i> Kembali</a>
        </div>
      </div>
      <div class="card-body">
        <?php if(!empty($msg_sukses)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg_sukses) ?></div>
        <?php endif; ?>
        <?php if(!empty($msg_error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($msg_error) ?></div>
        <?php endif; ?>

        <form id="formGuruWali" method="post">
            <input type="hidden" name="aksi" value="simpan">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="bold">Pilih Guru</label>
                    <select name="id_guru" id="id_guru" class="form-select" required>
                        <option value="">-- Pilih Guru --</option>
                        <?php foreach($guru_list as $g): ?>
                            <option value="<?= (int)$g['id_user'] ?>" <?= $selected_guru == (int)$g['id_user'] ? 'selected' : '' ?>><?= htmlspecialchars($g['nama']) ?><?= $g['nip']?(' - '.htmlspecialchars($g['nip'])):'' ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary" id="btnResetSelection"><i class="material-icons">history</i> Kembalikan Penugasan</button>
                        <button type="submit" class="btn btn-primary"><i class="material-icons">save</i> Simpan</button>
                    </div>
                </div>
            </div>

            <hr>

            <style>
                .kelas-tabs{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
                .kelas-tab{padding:6px 10px;border:1px solid #e9ecef;border-radius:8px;background:#f8f9fa;cursor:pointer}
                .kelas-tab.active{background:#0d6efd;color:#fff;border-color:#0d6efd}
                .siswa-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:8px}
                .siswa-item{border:1px solid #eef0f2;border-radius:10px;padding:8px 10px;background:#fafbfc;display:flex;align-items:center;justify-content:flex-start;gap:10px}
                .siswa-item label,.siswa-item .info{text-align:left}
                .siswa-item input[type=checkbox]{width:18px;height:18px}
                .muted{color:#6c757d;font-size:.85rem}
                .siswa-item .info{flex:1;min-width:0}
            </style>

            <?php if ($selected_guru <= 0): ?>
                <div class="alert alert-info">Silakan pilih guru terlebih dahulu untuk menampilkan daftar siswa per kelas.</div>
            <?php else: ?>
                <div class="kelas-tabs" id="kelasTabs">
                    <?php $first = true; foreach($kelas_list as $kls): ?>
                        <div class="kelas-tab <?= $first? 'active':'' ?>" data-kelas="<?= htmlspecialchars($kls) ?>">Kelas <?= htmlspecialchars($kls) ?></div>
                    <?php $first=false; endforeach; ?>
                </div>

                <input type="hidden" id="selected_ids" name="selected_ids" value="<?= htmlspecialchars(implode(',', $assigned_ids)) ?>">
                <input type="hidden" id="selected_ids_snapshot" value="<?= htmlspecialchars(implode(',', $assigned_ids)) ?>">

                <?php $first=true; foreach($kelas_list as $kls): $list = $siswa_per_kelas[$kls] ?? []; ?>
                    <div class="kelas-panel" data-panel="<?= htmlspecialchars($kls) ?>" style="display: <?= $first?'block':'none' ?>;">
                        <?php if (empty($list)): ?>
                            <div class="muted">Tidak ada siswa di kelas <?= htmlspecialchars($kls) ?>.</div>
                        <?php else: ?>
                            <?php $rendered_any = false; ?>
                            <div class="siswa-grid">
                                <?php foreach($list as $s):
                                    $sid = (int)$s['id_siswa'];
                                    $checked = isset($assigned[$sid]);
                                    $already_assigned_elsewhere = isset($all_assigned_students[$sid]) && !$checked;
                                    if ($already_assigned_elsewhere) { continue; }
                                    $rendered_any = true;
                                ?>
                                    <label class="siswa-item">
                                        <input type="checkbox" class="cb-siswa" value="<?= $sid ?>" <?= $checked?'checked':'' ?>>
                                        <div class="info">
                                            <div><strong><?= htmlspecialchars($s['nama']) ?></strong></div>
                                            <div class="muted">NIS: <?= htmlspecialchars($s['nis']) ?> • Kelas: <?= htmlspecialchars($s['kelas']) ?></div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <?php if (!$rendered_any): ?>
                                <div class="muted">Seluruh siswa di kelas ini sudah memiliki guru wali.</div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php $first=false; endforeach; ?>

                <script>
                    (function(){
                        const tabs = document.querySelectorAll('.kelas-tab');
                        const panels = document.querySelectorAll('.kelas-panel');
                        const hiddenField = document.getElementById('selected_ids');
                        const snapshotField = document.getElementById('selected_ids_snapshot');
                        const initialValues = (hiddenField.value || '').split(',').filter(Boolean);
                        const savedSnapshot = new Set((snapshotField ? snapshotField.value : '').split(',').filter(Boolean));
                        const selected = new Set(initialValues);

                        const syncHidden = () => { hiddenField.value = Array.from(selected).join(','); };

                        // Init from server-checked checkboxes
                        document.querySelectorAll('.cb-siswa').forEach(cb => {
                            if (cb.checked) { selected.add(cb.value); }
                        });
                        syncHidden();

                        // Toggle tabs
                        tabs.forEach(tab => {
                            tab.addEventListener('click', () => {
                                tabs.forEach(t => t.classList.remove('active'));
                                tab.classList.add('active');
                                const k = tab.getAttribute('data-kelas');
                                panels.forEach(p => {
                                    p.style.display = (p.getAttribute('data-panel') === k) ? 'block' : 'none';
                                });
                            });
                        });

                        // Maintain selection across tabs
                        document.querySelectorAll('.cb-siswa').forEach(cb => {
                            cb.addEventListener('change', () => {
                                if (cb.checked) selected.add(cb.value); else selected.delete(cb.value);
                                syncHidden();
                            });
                        });

                        // Reset ke data tersimpan
                        const resetBtn = document.getElementById('btnResetSelection');
                        if (resetBtn) {
                            resetBtn.addEventListener('click', () => {
                                selected.clear();
                                savedSnapshot.forEach(v => selected.add(v));
                                document.querySelectorAll('.cb-siswa').forEach(cb => {
                                    cb.checked = savedSnapshot.has(cb.value);
                                });
                                syncHidden();
                                if (window.iziToast) {
                                    iziToast.info({title: 'Reset', message: 'Penugasan kembali ke data tersimpan.'});
                                }
                            });
                        }

                        // On submit, serialize selected ids
                        document.getElementById('formGuruWali').addEventListener('submit', function(){
                            syncHidden();
                        });

                        // Auto reload when change guru
                        document.getElementById('id_guru').addEventListener('change', function(){
                            const val = this.value || '';
                            const url = new URL(window.location.href);
                            url.searchParams.set('pg', '<?= enkripsi('guruwali') ?>');
                            if(val) url.searchParams.set('id_guru', val); else url.searchParams.delete('id_guru');
                            window.location.href = url.toString();
                        });

                        const showToast = (type, message) => {
                            if (window.iziToast) {
                                iziToast[type === 'error' ? 'error' : 'success']({title: type === 'error' ? 'Gagal' : 'Sukses', message});
                            } else {
                                alert(message);
                            }
                        };

                        // Hapus siswa dari penugasan via AJAX
                        document.querySelectorAll('.btn-remove-siswa').forEach(btn => {
                            btn.addEventListener('click', function(){
                                const guruId = this.dataset.guru;
                                const siswaId = this.dataset.siswa;
                                if (!guruId || !siswaId) return;
                                if (!confirm('Hapus siswa ini dari daftar guru wali?')) return;

                                const formData = new FormData();
                                formData.append('aksi', 'hapus_siswa');
                                formData.append('id_guru', guruId);
                                formData.append('id_siswa', siswaId);

                                fetch('', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                                })
                                .then(resp => resp.json())
                                .then(res => {
                                    if (res.success) {
                                        selected.delete(siswaId);
                                        savedSnapshot.delete(siswaId);
                                        document.querySelectorAll('.cb-siswa[value="' + siswaId + '"]').forEach(cb => {
                                            cb.checked = false;
                                        });
                                        syncHidden();

                                        const item = this.closest('.siswa-item');
                                        if (item) {
                                            const row = item.closest('tr');
                                            item.remove();
                                            if (row) {
                                                const count = row.querySelectorAll('.siswa-item').length;
                                                const badge = row.querySelector('.badge-count[data-count-for="guru-' + guruId + '"]');
                                                if (badge) badge.textContent = count;
                                            }
                                        }
                                        showToast('success', 'Siswa berhasil dihapus.');
                                    } else {
                                        showToast('error', res.message || 'Gagal menghapus siswa.');
                                    }
                                })
                                .catch(() => showToast('error', 'Terjadi kesalahan koneksi.'));
                            });
                        });
                    })();
                </script>
            <?php endif; ?>
        </form>

        <hr>

        <h6 class="mb-3">Data Guru Wali & Siswa Binaan</h6>
        <?php
            // Tampilkan daftar guru wali beserta jumlah siswa
            $data_wali = [];
            $qw = $koneksi->query("SELECT gw.id_guru, u.nama, u.nip, COUNT(gw.id_siswa) jml FROM guru_wali gw JOIN users u ON gw.id_guru=u.id_user GROUP BY gw.id_guru ORDER BY u.nama ASC");
            if ($qw) { while($r=$qw->fetch_assoc()) $data_wali[]=$r; $qw->close(); }
        ?>
        <?php if (empty($data_wali)): ?>
            <div class="alert alert-light">Belum ada data guru wali.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:40%">Guru</th>
                            <th style="width:15%">Jumlah Siswa</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_wali as $gw): ?>
                            <tr>
                                <td><?= htmlspecialchars($gw['nama']) ?><?= $gw['nip']?(' - '.htmlspecialchars($gw['nip'])):'' ?></td>
                                <td><span class="badge bg-primary badge-count" data-count-for="guru-<?= (int)$gw['id_guru'] ?>"><?= (int)$gw['jml'] ?></span></td>
                                <td>
                                    <?php
                                        $detail = [];
                                        $qd = $koneksi->query("SELECT s.id_siswa, s.nis, s.nama, s.kelas FROM guru_wali gw JOIN siswa s ON gw.id_siswa=s.id_siswa WHERE gw.id_guru=".(int)$gw['id_guru']." ORDER BY s.kelas, s.nama");
                                        if ($qd) { while($r=$qd->fetch_assoc()) $detail[]=$r; $qd->close(); }
                                    ?>
                                    <?php if (empty($detail)): ?>
                                        <span class="text-muted">-</span>
                                    <?php else: ?>
                                        <div class="siswa-grid">
                                            <?php foreach($detail as $s): ?>
                                                <div class="siswa-item" data-siswa="<?= (int)$s['id_siswa'] ?>" data-guru="<?= (int)$gw['id_guru'] ?>" style="cursor:default">
                                                    <div class="info">
                                                        <div><strong><?= htmlspecialchars($s['nama']) ?></strong></div>
                                                        <div class="muted">NIS: <?= htmlspecialchars($s['nis']) ?> • Kelas: <?= htmlspecialchars($s['kelas']) ?></div>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-siswa" data-guru="<?= (int)$gw['id_guru'] ?>" data-siswa="<?= (int)$s['id_siswa'] ?>" title="Hapus"><i class="material-icons" style="font-size:16px">delete</i></button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>
