<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/function.php';
require_once __DIR__ . '/../../config/crud.php';
require_once __DIR__ . '/dapodik_helpers.php';
require_once __DIR__ . '/DapodikClientInterface.php';
require_once __DIR__ . '/DapodikClient.php';
require_once __DIR__ . '/MockDapodikClient.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Sesi tidak valid. Silakan login ulang.',
    ]);
    exit;
}

$action = trim((string)($_POST['action'] ?? ''));

try {
    switch ($action) {
        case 'save_config':
            handle_save_config($koneksi, $setting);
            break;

        case 'test_connection':
            handle_test_connection($koneksi);
            break;

        case 'preview_nilai':
            handle_preview_nilai($koneksi, $setting);
            break;

        case 'kirim_nilai':
            handle_kirim_nilai($koneksi, $setting);
            break;

        case 'kirim_matev':
            handle_kirim_matev($koneksi, $setting);
            break;

        default:
            json_response(false, 'Aksi tidak dikenali.', [], 400);
    }
} catch (Throwable $e) {
    json_response(false, $e->getMessage(), [], 500);
}

/**
 * Simpan konfigurasi Dapodik.
 */
function handle_save_config(mysqli $koneksi, array $setting): void
{
    $baseUrl = dapodik_normalize_base_url($_POST['base_url'] ?? '');
    if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
        if (stripos($baseUrl, 'mock://') !== 0) {
            json_response(false, 'URL Dapodik tidak valid. Pastikan diawali http:// atau https://', [], 422);
        }
    }
    $isMock = stripos($baseUrl, 'mock://') === 0;

    $token = trim((string)($_POST['token'] ?? ''));
    if ($token === '' && !$isMock) {
        json_response(false, 'Token / key wajib diisi.', [], 422);
    }

    $npsn = preg_replace('/[^0-9]/', '', (string)($_POST['npsn'] ?? ''));
    if ($npsn === '') {
        if ($isMock) {
            $npsn = '00000000';
        } else {
            json_response(false, 'NPSN wajib diisi (hanya angka).', [], 422);
        }
    }

    $semesterId = trim((string)($_POST['semester_id'] ?? ''));
    if ($semesterId === '') {
        $semesterId = dapodik_semester_id_from_setting($setting);
    }

    $semesterLabel = trim((string)($_POST['semester_label'] ?? ''));
    if ($semesterLabel === '') {
        $semesterLabel = dapodik_semester_label_from_setting($setting);
    }

    $data = [
        'base_url' => $baseUrl,
        'token' => $token,
        'npsn' => $npsn,
        'semester_id' => $semesterId,
        'semester_label' => $semesterLabel,
    ];

    $saved = dapodik_save_config($koneksi, $data);
    if (!$saved) {
        json_response(false, 'Gagal menyimpan pengaturan Dapodik.', [], 500);
    }

    $config = dapodik_get_config($koneksi);
    $config = array_merge($config, dapodik_resolve_semester_info($config, $setting));

    json_response(true, 'Pengaturan berhasil disimpan.', [
        'data' => [
            'semester_id' => $config['semester_id'] ?? '',
            'semester_label' => $config['semester_label'] ?? '',
        ],
    ]);
}

/**
 * Uji koneksi ke webservice Dapodik.
 */
function handle_test_connection(mysqli $koneksi): void
{
    $config = dapodik_get_config($koneksi);
    $client = instantiate_client_from_config($config);
    if (!$client) {
        json_response(false, 'Konfigurasi Dapodik belum lengkap. Simpan pengaturan terlebih dahulu.', [], 422);
    }

    try {
        $sekolahRaw = $client->getSekolah();
        $sekolah = dapodik_extract_sekolah_info($sekolahRaw);
        $namaSekolah = $sekolah['nama'] ?? '(tidak diketahui)';
        $isSimulation = $client instanceof MockDapodikClient;
        $statusLabel = $isSimulation ? '[Simulasi] Sekolah: ' : 'Sekolah: ';
        dapodik_update_test_status($koneksi, 'OK', $statusLabel . $namaSekolah);
        $message = $isSimulation ? 'Koneksi simulasi berhasil.' : 'Koneksi berhasil.';
        json_response(true, $message, [
            'data' => [
                'sekolah' => [
                    'nama' => $namaSekolah,
                    'npsn' => $sekolah['npsn'] ?? ($config['npsn'] ?? ''),
                    'alamat' => $sekolah['alamat_jalan'] ?? ($sekolah['alamat'] ?? ''),
                ],
                'mode' => $isSimulation ? 'simulation' : 'live',
            ],
        ]);
    } catch (Throwable $e) {
        dapodik_update_test_status($koneksi, 'FAILED', $e->getMessage());
        json_response(false, 'Tes koneksi gagal: ' . $e->getMessage(), [], 502);
    }
}

/**
 * Preview data nilai yang siap dikirim.
 */
function handle_preview_nilai(mysqli $koneksi, array $setting): void
{
    $config = dapodik_get_config($koneksi);
    $client = instantiate_client_from_config($config);
    if (!$client) {
        json_response(false, 'Pengaturan Dapodik belum lengkap.', [], 422);
    }

    $prepared = prepare_packages($koneksi, $setting, $config, $client);
    // Cache hasil prepare agar Kirim Nilai tidak perlu memanggil WS Dapodik lagi
    dapodik_store_prepared_cache($prepared, $config, $setting);
    $isSimulation = $client instanceof MockDapodikClient;

    if (empty($prepared['summary'])) {
        json_response(true, 'Tidak ada nilai yang ditemukan untuk semester ini.', [
            'data' => [
                'html' => '<div class="text-muted">Tidak ada data nilai untuk semester / tahun ajaran saat ini.</div>',
                'stats' => $prepared['stats'],
                'data_source' => $prepared['grade_source'] ?? '',
                'mode' => $isSimulation ? 'simulation' : 'live',
            ],
        ]);
        return;
    }

    json_response(true, 'Preview nilai berhasil dihasilkan.', [
        'data' => [
            'html' => $prepared['summary_html'],
            'stats' => $prepared['stats'],
            'unmatched' => $prepared['unmatched'],
            'packages_meta' => extract_packages_meta($prepared['packages']),
            'data_source' => $prepared['grade_source'] ?? '',
            'mode' => $isSimulation ? 'simulation' : 'live',
        ],
    ]);
}

/**
 * Kirim nilai ke Dapodik (percobaan langsung).
 */
function handle_kirim_nilai(mysqli $koneksi, array $setting): void
{
    $strict = isset($_POST['strict']) && (string)$_POST['strict'] !== '0' && (string)$_POST['strict'] !== '';
    $debug = isset($_POST['debug']) && (string)$_POST['debug'] !== '0' && (string)$_POST['debug'] !== '';
    $config = dapodik_get_config($koneksi);
    $client = instantiate_client_from_config($config);
    if (!$client) {
        json_response(false, 'Pengaturan Dapodik belum lengkap.', [], 422);
    }

    // Gunakan hasil preview terakhir jika masih valid untuk menghindari timeout pemanggilan WS saat sibuk
    $prepared = dapodik_load_prepared_cache($config, $setting);
    if ($prepared === null) {
        $prepared = prepare_packages($koneksi, $setting, $config, $client);
        dapodik_store_prepared_cache($prepared, $config, $setting);
    }
    $isSimulation = $client instanceof MockDapodikClient;
    $packages = $prepared['packages'];
    if (empty($packages)) {
        $mode = $isSimulation ? 'simulation' : 'live';
        $summaryHtml = $prepared['summary_html'] ?? '';
        if ($summaryHtml === '' && !empty($prepared['summary'])) {
            $summaryHtml = build_summary_html($prepared['summary'], $prepared['unmatched'] ?? [
                'kelas' => [],
                'mapel' => [],
                'nisn' => [],
                'anggota_rombel' => [],
            ]);
        }
        if ($summaryHtml === '') {
            $summaryHtml = '<div class="text-muted">Tidak ditemukan data nilai untuk semester ini.</div>';
        }
        json_response(false, 'Tidak ada paket nilai yang siap dikirim.', [
            'data' => [
                'summary_html' => $summaryHtml,
                'stats' => $prepared['stats'] ?? [],
                'unmatched' => $prepared['unmatched'] ?? [],
                'data_source' => $prepared['grade_source'] ?? '',
                'mode' => $mode,
            ],
        ], 422);
    }

    // 0) Pastikan MATEV terbentuk terlebih dahulu agar tampilan Rapor tidak kosong
    //    (akan diabaikan bila endpoint tidak tersedia)
    try {
        // Pass strict+debug agar signature terpenuhi dan konsisten
        seed_matev_for_packages($client, $packages, $config, $strict, $debug);
    } catch (Throwable $_) {}

    // 0.1) Resolusi id_evaluasi untuk setiap paket (ambil dari REST MatevRapor)
    try {
        $evalMap = resolve_evaluasi_id_for_packages($client, $packages, $config);
        foreach ($packages as $idx => $pkg) {
            $key = ($pkg['rombongan_belajar_id'] ?? '') . '|' . ($pkg['mata_pelajaran_id'] ?? '') . '|' . ($pkg['pembelajaran_id'] ?? '');
            if (!empty($evalMap[$key])) { $packages[$idx]['id_evaluasi'] = $evalMap[$key]; }
        }
    } catch (Throwable $_) {}

    $success = 0;
    $failed = 0;
    $responses = [];
    foreach ($packages as $package) {
        if (empty($package['records'])) {
            continue;
        }
        $payload = build_payload_from_package($config, $package);
        if (empty($payload['nilai'])) {
            continue;
        }
        try {
            $result = $client->kirimNilai($payload);
            $messages = dapodik_response_messages($result);
            $messages = dapodik_filter_messages($messages, $debug);
            if (dapodik_response_success($result, $strict)) {
                $success++;
                $responses[] = [
                    'kelas' => $package['kelas'] ?? '',
                    'mapel' => $package['mapel'] ?? '',
                    'jumlah' => count($payload['nilai']),
                    'status' => 'ok',
                    'response' => $debug ? $result : null,
                    'messages' => $messages,
                ];
            } else {
                $failed++;
                $responses[] = [
                    'kelas' => $package['kelas'] ?? '',
                    'mapel' => $package['mapel'] ?? '',
                    'jumlah' => count($payload['nilai']),
                    'status' => 'error',
                    'error' => 'Dapodik tidak mengonfirmasi keberhasilan.',
                    'response' => $debug ? $result : null,
                    'messages' => $messages,
                    'payload' => $debug ? $payload : null,
                ];
            }
        } catch (Throwable $e) {
            $msg = (string)$e->getMessage();
            $msgLower = strtolower($msg);
            $looksTimeout = (strpos($msgLower, 'request timeout') !== false)
                || (strpos($msgLower, 'timed out') !== false)
                || (strpos($msgLower, 'takes too long to process') !== false);
            $looksBusy = (strpos($msgLower, 'service unavailable') !== false)
                || (strpos($msgLower, 'temporarily busy') !== false)
                || (strpos($msgLower, '503') !== false && strpos($msgLower, 'service') !== false);
            if ($looksTimeout || $looksBusy) {
                if ($strict) {
                    $failed++;
                    $responses[] = [
                        'kelas' => $package['kelas'] ?? '',
                        'mapel' => $package['mapel'] ?? '',
                        'jumlah' => count($payload['nilai']),
                        'status' => 'error',
                        'error' => 'Timeout/Busy saat kirim. Server tidak mengonfirmasi keberhasilan.',
                        'payload' => $payload,
                    ];
                } else {
                    // Anggap permintaan diterima oleh server (trigger-only). Hitung sebagai berhasil.
                    $success++;
                    $responses[] = [
                        'kelas' => $package['kelas'] ?? '',
                        'mapel' => $package['mapel'] ?? '',
                        'jumlah' => count($payload['nilai']),
                        'status' => 'ok',
                        'messages' => [
                            'Permintaan dipicu pada server (trigger-only). Server sedang Timeout/Busy.',
                            'Jika ragu, cek Dapodik setelah beberapa menit.',
                        ],
                    ];
                }
            } else {
                $failed++;
                $responses[] = [
                    'kelas' => $package['kelas'] ?? '',
                    'mapel' => $package['mapel'] ?? '',
                    'jumlah' => count($payload['nilai']),
                    'status' => 'error',
                    'error' => $msg,
                    'payload' => $payload,
                ];
            }
        }
    }

    $totalSent = $success + $failed;
    $message = $totalSent === 0
        ? 'Tidak ada data yang terkirim.'
        : "Pengiriman selesai. Berhasil: {$success}, Gagal: {$failed}.";

    $summaryHtml = build_send_summary_html($prepared['summary'], $responses);

    json_response($failed === 0, $message, [
        'data' => [
            'summary_html' => $summaryHtml,
            'responses' => $responses,
            'mode' => $isSimulation ? 'simulation' : 'live',
            'stats' => $prepared['stats'] ?? [],
            'unmatched' => $prepared['unmatched'] ?? [],
            'data_source' => $prepared['grade_source'] ?? '',
        ],
    ], $failed === 0 ? 200 : 207);
}

/**
 * Helper untuk memformat respon JSON.
 *
 * @param array<string,mixed> $extra
 */
function json_response(bool $success, string $message, array $extra = [], int $status = 200): void
{
    http_response_code($status);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $extra));
    exit;
}

/**
 * Kirim MATEV (Mata Evaluasi Rapor) untuk setiap paket rombel/mapel.
 * Menggunakan endpoint pemicu (postMatevRapor/postMatev) tanpa body.
 */
function handle_kirim_matev(mysqli $koneksi, array $setting): void
{
    $strict = isset($_POST['strict']) && (string)$_POST['strict'] !== '0' && (string)$_POST['strict'] !== '';
    $debug = isset($_POST['debug']) && (string)$_POST['debug'] !== '0' && (string)$_POST['debug'] !== '';
    $config = dapodik_get_config($koneksi);
    $client = instantiate_client_from_config($config);
    if (!$client) {
        json_response(false, 'Pengaturan Dapodik belum lengkap.', [], 422);
    }

    $isSimulation = $client instanceof MockDapodikClient;

    // Fast path: picu MATEV secara global terlebih dahulu agar tidak menabrak batas timeout reverse proxy.
    // Hanya butuh npsn+semester_id, tanpa memuat data rombel/pembelajaran.
    try {
        if (method_exists($client, 'getMatevNilai')) {
            $client->getMatevNilai(['a_dari_template' => 1]);
        }
    } catch (Throwable $_) {}

    $globalBase = [
        'npsn' => $config['npsn'] ?? '',
        'semester_id' => $config['semester_id'] ?? '',
    ];
    $globalOk = false; $globalRes = null; $globalMsgs = [];
    try {
        // Coba 2 kali singkat untuk mengatasi fluktuasi beban server
        for ($i=0; $i<2 && !$globalOk; $i++) {
            $res = $client->kirimMatev($globalBase);
            $globalRes = $res;
            $globalOk = dapodik_response_success($res, false);
            $globalMsgs = dapodik_filter_messages(dapodik_response_messages($res), $debug);
            if (!$globalOk) { usleep(80000); }
        }
    } catch (Throwable $e) {
        // Tangani 500/timeout sebagai OK untuk MATEV (trigger-only)
        $msg = strtolower($e->getMessage());
        if (strpos($msg, 'request timeout') !== false || strpos($msg, 'service unavailable') !== false || strpos($msg, 'timed out') !== false) {
            $globalOk = true;
            $globalMsgs = ['Accepted (Timeout/Busy) — proses dipicu di server.'];
        }
    }

    // Siapkan paket (pakai cache jika ada) dan lanjut pemicu per-paket terlepas dari status global,
    // karena beberapa instalasi hanya membentuk MATEV ketika parameter rombel/mapel ikut dikirim.
    $prepared = dapodik_load_prepared_cache($config, $setting);
    if ($prepared === null) {
        $prepared = prepare_packages($koneksi, $setting, $config, $client);
        dapodik_store_prepared_cache($prepared, $config, $setting);
    }
    $packages = $prepared['packages'];
    if (empty($packages)) {
        json_response(false, 'Tidak ada paket rombel/mapel untuk dibentuk MATEV.', [
            'data' => [
                'summary_html' => $prepared['summary_html'] ?? '',
                'stats' => $prepared['stats'] ?? [],
                'unmatched' => $prepared['unmatched'] ?? [],
                'mode' => $isSimulation ? 'simulation' : 'live',
            ],
        ], 422);
    }

    $responses = seed_matev_for_packages($client, $packages, $config, $strict, $debug, false);
    $ok = 0; $err = 0;
    foreach ($responses as $r) { ($r['status'] ?? '') === 'ok' ? $ok++ : $err++; }

    $message = "Pembentukan MATEV selesai. Berhasil: {$ok}, Gagal: {$err}.";
    $html = build_send_summary_html($prepared['summary'], $responses);

    json_response($err === 0, $message, [
        'data' => [
            'summary_html' => $html,
            'responses' => $responses,
            'mode' => $isSimulation ? 'simulation' : 'live',
            'stats' => $prepared['stats'] ?? [],
            'unmatched' => $prepared['unmatched'] ?? [],
        ],
    ], $err === 0 ? 200 : 207);
}

/**
 * Panggil endpoint kirimMatev (postMatevRapor/postMatev) untuk semua paket.
 *
 * @param array<int,array<string,mixed>> $packages
 * @param array<string,mixed> $config
 * @return array<int,array<string,mixed>>
 */
function seed_matev_for_packages(DapodikClientInterface $client, array $packages, array $config, bool $strict = false, bool $debug = false, bool $runGlobalFirst = true): array
{
    $responses = [];

    if ($runGlobalFirst) {
        // Warm-up call that mirrors official e-Rapor flow:
        // GET /WebService/getMatevNilai?a_dari_template=1
        try {
            if (method_exists($client, 'getMatevNilai')) {
                $client->getMatevNilai(['a_dari_template' => 1]);
            }
        } catch (Throwable $_) {
            // Abaikan jika tidak tersedia/bermasalah; ini hanya pemanasan cache di sisi Dapodik
        }
    }

    // Helper pemanggil dengan berbagai variasi parameter
    $callMatev = function(array $base) use ($client, $strict, $debug) {
        try {
            $res = $client->kirimMatev($base);
            // Untuk MATEV, perlakukan status 'accepted'/timeout sebagai OK seperti pola postMatevRapor.
            $ok = dapodik_response_success($res, false);
            $msgs = dapodik_filter_messages(dapodik_response_messages($res), $debug);
            return ['ok' => $ok, 'res' => $res, 'msgs' => $msgs, 'err' => null];
        } catch (Throwable $e) {
            $msg = strtolower($e->getMessage());
            $accepted = (strpos($msg, 'request timeout') !== false)
                || (strpos($msg, 'service unavailable') !== false)
                || (strpos($msg, 'timed out') !== false)
                || (strpos($msg, 'takes too long to process') !== false);
            if ($accepted) {
                // Anggap proses dipicu di server; tandai OK agar tidak membingungkan pengguna.
                return ['ok' => true, 'res' => ['message' => 'Accepted (Timeout/Busy) — proses dipicu.'], 'msgs' => ['Accepted (Timeout/Busy) — proses dipicu di server.'], 'err' => null];
            }
            return ['ok' => false, 'res' => null, 'msgs' => [], 'err' => $e->getMessage()];
        }
    };

    if ($runGlobalFirst) {
        // 1) Coba pemicu global yang paling ringan (seperti log resmi):
        // POST /WebService/postMatevRapor?npsn=...&semester_id=...
        $globalBase = [
            'npsn' => $config['npsn'] ?? '',
            'semester_id' => $config['semester_id'] ?? '',
        ];
        $globalOk = false; $globalMsgs = [];
        for ($i = 0; $i < 2 && !$globalOk; $i++) { // panggil singkat 2x
            $out = $callMatev($globalBase);
            $globalOk = $out['ok'];
            $globalMsgs = $out['msgs'];
            if (!$globalOk) { usleep(80000); }
        }
        // Catat satu entri ringkas global
        $responses[] = [
            'kelas' => '',
            'mapel' => '',
            'jumlah' => 0,
            'status' => $globalOk ? 'ok' : 'error',
            'response' => $debug ? ['global' => true] : null,
            'messages' => !empty($globalMsgs) ? $globalMsgs : ($globalOk ? ['MATEV global dipicu.'] : ['MATEV global belum terkonfirmasi.']),
        ];
        // Lanjut ke pemicu per-paket agar MATEV terbentuk untuk tiap rombel/mapel
    }

    foreach ($packages as $package) {
        $base = [
            'npsn' => $config['npsn'] ?? '',
            'semester_id' => $config['semester_id'] ?? '',
            'rombongan_belajar_id' => $package['rombongan_belajar_id'] ?? '',
            'pembelajaran_id' => $package['pembelajaran_id'] ?? '',
            'mata_pelajaran_id' => $package['mata_pelajaran_id'] ?? '',
            'ptk_id' => $package['ptk_id'] ?? null,
        ];

        // Buat beberapa variasi parameter: penuh → hanya rombel → hanya pembelajaran → hanya mapel → minimal
        $variants = [];
        // Prioritaskan pemicu paling ringan lebih dulu agar cepat kembali
        $variants[] = array_intersect_key($base, array_flip(['npsn','semester_id']));
        $variants[] = array_intersect_key($base, array_flip(['npsn','semester_id','rombongan_belajar_id']));
        $variants[] = array_intersect_key($base, array_flip(['npsn','semester_id','pembelajaran_id']));
        $variants[] = array_intersect_key($base, array_flip(['npsn','semester_id','mata_pelajaran_id']));
        $variants[] = $base; // terakhir: paket terlengkap

        $done = false; $final = null; $finalMsgs = [];
        // 2) Fallback per-paket: Coba hingga 1 siklus ringan agar cepat kembali ke UI
        for ($round = 0; $round < 1 && !$done; $round++) {
            foreach ($variants as $vars) {
                $out = $callMatev($vars);
                if ($out['ok']) {
                    $done = true; $final = $out['res']; $finalMsgs = $out['msgs'];
                    break;
                }
                $finalMsgs = $out['msgs'];
                if ($out['err']) { $finalMsgs[] = $out['err']; }
                usleep(80000); // 80ms antar percobaan
            }
            if (!$done) { usleep(120000); } // 120ms antar putaran
        }

        $responses[] = [
            'kelas' => $package['kelas'] ?? '',
            'mapel' => $package['mapel'] ?? '',
            'jumlah' => 0,
            'status' => $done ? 'ok' : 'error',
            'response' => $debug ? $final : null,
            'messages' => $finalMsgs ?: ($done ? ['MATEV dipicu.'] : ['MATEV belum terkonfirmasi.']),
        ];
    }

    return $responses;
}

/**
 * Build client Dapodik (mode asli atau simulasi) dari konfigurasi tersimpan.
 *
 * @param array<string,mixed> $config
 */
function instantiate_client_from_config(array $config): ?DapodikClientInterface
{
    $base = trim((string)($config['base_url'] ?? ''));
    $token = trim((string)($config['token'] ?? ''));
    $npsn = trim((string)($config['npsn'] ?? ''));
    if ($base === '') {
        return null;
    }
    if (stripos($base, 'mock://') === 0) {
        $scenario = substr($base, 7);
        $scenario = trim($scenario) !== '' ? trim($scenario) : 'default';
        if ($npsn === '') {
            $npsn = '00000000';
        }
        return new MockDapodikClient($scenario, $token, $npsn);
    }
    if ($token === '' || $npsn === '') {
        return null;
    }
    $semesterId = trim((string)($config['semester_id'] ?? ''));
    return new DapodikClient(dapodik_normalize_base_url($base), $token, $npsn, 'siakad/dapodik-integration', $semesterId !== '' ? $semesterId : null);
}

/**
 * Dapatkan id_evaluasi (MATEV) per paket dengan memanggil REST MatevRapor untuk tiap rombel.
 * @return array<string,string> key: rombel|mp_id|pembelajaran_id => id_evaluasi
 */
function resolve_evaluasi_id_for_packages(DapodikClientInterface $client, array $packages, array $config): array
{
    // Kelompokkan paket per rombel untuk menghemat panggilan
    $grouped = [];
    foreach ($packages as $pkg) {
        $rombel = (string)($pkg['rombongan_belajar_id'] ?? '');
        if ($rombel === '') { continue; }
        $grouped[$rombel][] = $pkg;
    }

    $result = [];
    foreach ($grouped as $rombelId => $list) {
        try {
            $resp = $client->getMatevRapor([
                'rombongan_belajar_id' => $rombelId,
                'semester_id' => $config['semester_id'] ?? '',
                'a_dari_template' => 1,
                'is_wali_kelas' => 0,
                'page' => 1,
                'start' => 0,
                'limit' => 200,
            ]);
        } catch (Throwable $_) {
            $resp = [];
        }
        $items = ensure_list($resp);
        // Bangun index berdasarkan pembelajaran_id atau mata_pelajaran_id
        $index = [];
        foreach ($items as $it) {
            if (!is_array($it)) { continue; }
            $idEval = (string)($it['id_evaluasi'] ?? ($it['evaluasi_id'] ?? ($it['id'] ?? ($it['matev_rapor_id'] ?? ''))));
            if ($idEval === '') { continue; }
            $pb = (string)($it['pembelajaran_id'] ?? ($it['id_pembelajaran'] ?? ''));
            $mp = (string)($it['mata_pelajaran_id'] ?? ($it['mapel_id'] ?? ''));
            if ($pb !== '') { $index['PB:' . $pb] = $idEval; }
            if ($mp !== '') { $index['MP:' . $mp] = $idEval; }
        }

        foreach ($list as $pkg) {
            $pb = (string)($pkg['pembelajaran_id'] ?? '');
            $mp = (string)($pkg['mata_pelajaran_id'] ?? '');
            $key = $rombelId . '|' . $mp . '|' . $pb;
            $idEval = '';
            if ($pb !== '' && isset($index['PB:' . $pb])) { $idEval = $index['PB:' . $pb]; }
            elseif ($mp !== '' && isset($index['MP:' . $mp])) { $idEval = $index['MP:' . $mp]; }
            if ($idEval !== '') { $result[$key] = $idEval; }
        }
    }

    return $result;
}

/**
 * Ambil nilai sumatif dan bangun paket sinkronisasi.
 *
 * @param array<string,mixed> $config
 * @param DapodikClientInterface $client
 * @return array{
 *   packages: array<int,array<string,mixed>>,
 *   summary: array<int,array<string,mixed>>,
 *   summary_html: string,
 *   stats: array<string,int>,
 *   unmatched: array<string,array<int,array<string,string>>>
 * }
 */
function prepare_packages(mysqli $koneksi, array $setting, array $config, DapodikClientInterface $client): array
{
    $semester = trim((string)($setting['semester'] ?? '1'));
    $tp = trim((string)($setting['tp'] ?? ''));
    $gradeData = fetch_local_grade_rows($koneksi, $semester, $tp);
    $rows = $gradeData['rows'];
    $gradeSource = $gradeData['source'];

    if (empty($rows)) {
        return [
            'packages' => [],
            'summary' => [],
            'summary_html' => '',
            'stats' => [
                'total_rows' => 0,
                'matched' => 0,
                'unmatched' => 0,
            ],
            'grade_source' => $gradeSource,
            'unmatched' => [
                'kelas' => [],
                'mapel' => [],
                'nisn' => [],
                'anggota_rombel' => [],
            ],
        ];
    }

    // Ambil PD dan Rombel dari WS Dapodik dengan fallback cache agar Preview tidak gagal ketika WS sibuk
    $pesertaList = [];
    $rombelList = [];
    try {
        $pesertaList = ensure_list($client->getPesertaDidik());
        $rombelList = ensure_list($client->getRombonganBelajar());
        dapodik_store_pd_rombel_cache($pesertaList, $rombelList, $config);
    } catch (Throwable $_) {
        $cached = dapodik_load_pd_rombel_cache($config);
        if ($cached !== null) {
            $pesertaList = $cached['pd'];
            $rombelList = $cached['rombel'];
        } else {
            // Biarkan kosong; ringkasan akan tetap terbentuk sebagai unmatched (tanpa rombel/mapel Dapodik)
            $pesertaList = [];
            $rombelList = [];
        }
    }

    $pesertaByNisn = [];
    $pesertaByNisLocal = [];
    foreach ($pesertaList as $pd) {
        $nisn = trim((string)($pd['nisn'] ?? ''));
        if ($nisn === '') {
            continue;
        }
        $pesertaByNisn[$nisn] = $pd;
        $nisLocal = trim((string)($pd['nis'] ?? ($pd['nipd'] ?? '')));
        if ($nisLocal !== '') {
            $pesertaByNisLocal[$nisLocal] = $pd;
        }
    }

    $rombelMap = build_rombel_index($rombelList);

    $packages = [];
    $summaryIndex = [];
    $unmatched = [
        'kelas' => [],
        'mapel' => [],
        'nisn' => [],
        'anggota_rombel' => [],
    ];

    $matchedCount = 0;

    foreach ($rows as $row) {
        $kelas = trim((string)$row['kelas']);
        $mapelName = trim((string)$row['nama_mapel']);
        $mapelIdLocal = (string)$row['mapel'];
        $nilaiAkhir = round((float)$row['nilai_akhir'], 2);

        $summaryKey = $kelas . '|' . $mapelName;
        if (!isset($summaryIndex[$summaryKey])) {
            $summaryIndex[$summaryKey] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'total' => 0,
                'matched' => 0,
                'no_rombel' => 0,
                'no_mapel' => 0,
                'no_nisn' => 0,
                'no_anggotarombel' => 0,
            ];
        }
        $summaryIndex[$summaryKey]['total']++;

        $rombel = find_rombel_by_kelas($rombelMap, $kelas);
        if ($rombel === null) {
            $summaryIndex[$summaryKey]['no_rombel']++;
            $unmatched['kelas'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'],
                'nama' => $row['nama_siswa'],
            ];
            continue;
        }

        $pembelajaran = find_matching_pembelajaran($rombel, $mapelName, $mapelIdLocal);
        if ($pembelajaran === null) {
            $summaryIndex[$summaryKey]['no_mapel']++;
            $unmatched['mapel'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'],
                'nama' => $row['nama_siswa'],
            ];
            continue;
        }

        $nisn = trim((string)$row['nisn']);
        $pd = null;
        if ($nisn !== '' && isset($pesertaByNisn[$nisn])) {
            $pd = $pesertaByNisn[$nisn];
        } else {
            $nisLocal = trim((string)$row['nis']);
            if ($nisLocal !== '' && isset($pesertaByNisLocal[$nisLocal])) {
                $pd = $pesertaByNisLocal[$nisLocal];
            }
        }
        if ($pd === null) {
            $summaryIndex[$summaryKey]['no_nisn']++;
            $unmatched['nisn'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'],
                'nisn' => $nisn,
                'nama' => $row['nama_siswa'],
            ];
            continue;
        }

        $anggotaRombelId = $pd['anggota_rombel_id'] ?? '';
        if ($anggotaRombelId === '') {
            $summaryIndex[$summaryKey]['no_anggotarombel']++;
            $unmatched['anggota_rombel'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'],
                'nisn' => $nisn,
                'nama' => $row['nama_siswa'],
            ];
            continue;
        }

        $packageKey = ($rombel['rombongan_belajar_id'] ?? '') . '::' . ($pembelajaran['pembelajaran_id'] ?? normalize_key($mapelName));
        if (!isset($packages[$packageKey])) {
            $packages[$packageKey] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'rombongan_belajar_id' => $rombel['rombongan_belajar_id'] ?? '',
                'rombongan_nama' => $rombel['nama'] ?? $kelas,
                'pembelajaran_id' => $pembelajaran['pembelajaran_id'] ?? null,
                'mata_pelajaran_id' => $pembelajaran['mata_pelajaran_id'] ?? null,
                'mata_pelajaran_nama' => $pembelajaran['nama_mata_pelajaran'] ?? $mapelName,
                'ptk_id' => $pembelajaran['ptk_id'] ?? ($pembelajaran['ptk_id_str'] ?? null),
                'records' => [],
            ];
        }

        $nisnFinal = trim((string)($pd['nisn'] ?? $nisn));

        $packages[$packageKey]['records'][] = [
            'nis' => $row['nis'],
            'nisn' => $nisnFinal,
            'nama' => $row['nama_siswa'],
            'nilai' => $nilaiAkhir,
            'anggota_rombel_id' => $anggotaRombelId,
            'peserta_didik_id' => $pd['peserta_didik_id'] ?? null,
            'pd' => $pd,
        ];

        $summaryIndex[$summaryKey]['matched']++;
        $matchedCount++;
    }

    $summary = array_values($summaryIndex);
    usort($summary, function ($a, $b) {
        return [$a['kelas'], $a['mapel']] <=> [$b['kelas'], $b['mapel']];
    });

    $summaryHtml = build_summary_html($summary, $unmatched);

    $stats = [
        'total_rows' => count($rows),
        'matched' => $matchedCount,
        'unmatched' => count($rows) - $matchedCount,
    ];

    return [
        'packages' => array_values($packages),
        'summary' => $summary,
        'summary_html' => $summaryHtml,
        'stats' => $stats,
        'grade_source' => $gradeSource,
        'unmatched' => $unmatched,
    ];
}

/**
 * Ambil data nilai lokal dari berbagai sumber (prioritas nilai_formatif).
 *
 * @return array{rows:array<int,array<string,mixed>>,source:string}
 */
function fetch_local_grade_rows(mysqli $koneksi, string $semester, string $tp): array
{
    $fromFormatif = fetch_rows_from_nilai_formatif($koneksi, $semester, $tp);
    if (!empty($fromFormatif['rows'])) {
        return $fromFormatif;
    }

    $candidates = detect_grade_sources($koneksi, ['nilai_formatif']);
    $lastLabel = $fromFormatif['source'] ?? '';
    foreach ($candidates as $candidate) {
        $lastLabel = $candidate['label'];
        $rows = fetch_rows_for_candidate($koneksi, $candidate, $semester, $tp);
        if (!empty($rows)) {
            return [
                'rows' => $rows,
                'source' => $candidate['label'],
            ];
        }
    }

    return [
        'rows' => [],
        'source' => $lastLabel,
    ];
}

/**
 * Ambil nilai berdasarkan entri deskriptif di nilai_formatif.
 * Menggunakan nilai numerik dari nilai_rapor / nilai_sts bila tersedia.
 */
function fetch_rows_from_nilai_formatif(mysqli $koneksi, string $semester, string $tp): array
{
    $sql = "
        SELECT
            nf.nis,
            nf.mapel,
            MAX(COALESCE(NULLIF(nr.kelas, ''), NULLIF(ns.kelas, ''), NULLIF(nf.kelas, ''), '')) AS kelas,
            MAX(s.nama) AS nama_siswa,
            MAX(s.nisn) AS nisn,
            MAX(COALESCE(mp_nf.nama_mapel, mp_ns.nama_mapel, mp_nr.nama_mapel, mp_kode.nama_mapel, nf.mapel)) AS nama_mapel,
            MAX(COALESCE(mp_nf.kode, mp_ns.kode, mp_nr.kode, mp_kode.kode, nf.mapel)) AS kode_mapel,
            AVG(
                CASE
                    WHEN nr.nilai REGEXP '^-?[0-9]+(\\.[0-9]+)?$' THEN CAST(nr.nilai AS DECIMAL(7,2))
                    WHEN ns.nilai_rapor REGEXP '^-?[0-9]+(\\.[0-9]+)?$' THEN CAST(ns.nilai_rapor AS DECIMAL(7,2))
                    WHEN ns.nilai_sts REGEXP '^-?[0-9]+(\\.[0-9]+)?$' THEN CAST(ns.nilai_sts AS DECIMAL(7,2))
                    ELSE NULL
                END
            ) AS nilai_akhir
        FROM nilai_formatif nf
        INNER JOIN siswa s ON s.nis = nf.nis
        LEFT JOIN nilai_rapor nr
            ON nr.nis = nf.nis
           AND CAST(nr.mapel AS CHAR) = CAST(nf.mapel AS CHAR)
        LEFT JOIN nilai_sts ns
            ON ns.nis = nf.nis
           AND CAST(ns.mapel AS CHAR) = CAST(nf.mapel AS CHAR)
           AND (ns.khp IS NULL OR ns.khp IN ('STS','SAM','SAS','SAS1','SAS2','LMAS'))
        LEFT JOIN mata_pelajaran mp_nf ON mp_nf.id = nf.mapel
        LEFT JOIN mata_pelajaran mp_ns ON mp_ns.id = ns.mapel
        LEFT JOIN mata_pelajaran mp_nr ON mp_nr.id = nr.mapel
        LEFT JOIN mata_pelajaran mp_kode ON mp_kode.kode = nf.mapel
        WHERE (
            (nr.semester = ? AND nr.tp = ?)
            OR (ns.semester = ? AND ns.tp = ?)
        )
        GROUP BY nf.nis, nf.mapel
        HAVING nilai_akhir IS NOT NULL
    ";

    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        return ['rows' => [], 'source' => ''];
    }

    mysqli_stmt_bind_param($stmt, 'ssss', $semester, $tp, $semester, $tp);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_stmt_close($stmt);

    if (empty($rows)) {
        return ['rows' => [], 'source' => ''];
    }

    return [
        'rows' => $rows,
        'source' => 'nilai_formatif.nilai_rapor',
    ];
}

/**
 * Identifikasi tabel sumber nilai yang tersedia di database lokal.
 *
 * @return array<int,array<string,mixed>>
 */
function detect_grade_sources(mysqli $koneksi, array $excludeTables = []): array
{
    $priorities = [
        ['table' => 'nilai_formatif', 'label' => 'nilai_formatif'],
        ['table' => 'nilai_rapor', 'label' => 'nilai_rapor'],
        ['table' => 'nilai_sumatif', 'label' => 'nilai_sumatif'],
        ['table' => 'nilai_sts', 'label' => 'nilai_sts'],
    ];

    $detected = [];
    foreach ($priorities as $candidate) {
        if (in_array($candidate['table'], $excludeTables, true)) {
            continue;
        }
        $structure = inspect_grade_table_structure($koneksi, $candidate['table']);
        if ($structure !== null) {
            $entry = array_merge($candidate, $structure);
            $entry['label'] = $candidate['table'] . '.' . $structure['grade_column'];
            $detected[] = $entry;
        }
    }
    return $detected;
}

/**
 * Ambil metadata kolom penting dari sebuah tabel nilai.
 *
 * @return array<string,string|null>|null
 */
function inspect_grade_table_structure(mysqli $koneksi, string $table): ?array
{
    $pattern = addcslashes($table, '\\_%');
    $tableEsc = mysqli_real_escape_string($koneksi, $pattern);
    $exists = mysqli_query($koneksi, "SHOW TABLES LIKE '{$tableEsc}'");
    if (!$exists || mysqli_num_rows($exists) === 0) {
        if ($exists) {
            mysqli_free_result($exists);
        }
        return null;
    }
    mysqli_free_result($exists);

    $tableSafe = str_replace('`', '``', $table);
    $columnsRes = mysqli_query($koneksi, "SHOW COLUMNS FROM `{$tableSafe}`");
    if (!$columnsRes) {
        return null;
    }
    $columns = [];
    while ($col = mysqli_fetch_assoc($columnsRes)) {
        $columns[strtolower((string)($col['Field'] ?? ''))] = $col;
    }
    mysqli_free_result($columnsRes);

    $find = function (array $candidates) use ($columns): ?string {
        foreach ($candidates as $name) {
            $key = strtolower($name);
            if (isset($columns[$key])) {
                return $columns[$key]['Field'] ?? $name;
            }
        }
        return null;
    };

    $nisCol = $find(['nis', 'nis_siswa']);
    $mapelCol = $find(['mapel', 'id_mapel', 'mapel_id', 'mata_pelajaran', 'kode_mapel']);
    $kelasCol = $find(['kelas', 'kelas_id', 'rombel', 'rombongan_belajar', 'rombongan_belajar_id']);
    $gradeCol = $find(['nilai', 'nilai_akhir', 'nilai_formatif', 'nilai_sumatif', 'nilai_rapor', 'nilai_raport', 'nilai_kurmer', 'nilai_sts']);
    if ($nisCol === null || $mapelCol === null || $kelasCol === null || $gradeCol === null) {
        return null;
    }

    $semesterCol = $find(['semester', 'smt', 'semester_id']);
    $tpCol = $find(['tp', 'tapel', 'tahun_pelajaran', 'tahun']);

    return [
        'nis_column' => $nisCol,
        'mapel_column' => $mapelCol,
        'kelas_column' => $kelasCol,
        'grade_column' => $gradeCol,
        'semester_column' => $semesterCol,
        'tp_column' => $tpCol,
    ];
}

/**
 * Ambil baris nilai dari sumber tertentu.
 *
 * @param array<string,mixed> $candidate
 * @return array<int,array<string,mixed>>
 */
function fetch_rows_for_candidate(mysqli $koneksi, array $candidate, string $semester, string $tp): array
{
    $table = $candidate['table'];
    $alias = 'nf';
    $nisCol = $candidate['nis_column'];
    $mapelCol = $candidate['mapel_column'];
    $kelasCol = $candidate['kelas_column'];
    $gradeCol = $candidate['grade_column'];
    $semesterCol = $candidate['semester_column'] ?? null;
    $tpCol = $candidate['tp_column'] ?? null;

    $conditions = [];
    $tableSafe = str_replace('`', '``', $table);
    if ($semesterCol !== null && $semester !== '') {
        $conditions[] = "{$alias}.`{$semesterCol}` = '" . mysqli_real_escape_string($koneksi, $semester) . "'";
    }
    if ($tpCol !== null && $tp !== '') {
        $conditions[] = "{$alias}.`{$tpCol}` = '" . mysqli_real_escape_string($koneksi, $tp) . "'";
    }
    $whereSql = $conditions ? implode(' AND ', $conditions) : '1=1';

    $query = "
        SELECT {$alias}.`{$nisCol}` AS nis,
               {$alias}.`{$mapelCol}` AS mapel,
               {$alias}.`{$kelasCol}` AS kelas,
               s.nama AS nama_siswa,
               s.nisn,
               COALESCE(mp.nama_mapel, {$alias}.`{$mapelCol}`) AS nama_mapel,
               COALESCE(mp.kode, {$alias}.`{$mapelCol}`) AS kode_mapel,
               AVG(
                   CASE
                       WHEN {$alias}.`{$gradeCol}` REGEXP '^-?[0-9]+(\\\\.[0-9]+)?$'
                           THEN CAST({$alias}.`{$gradeCol}` AS DECIMAL(7,2))
                       ELSE NULL
                   END
               ) AS nilai_akhir
        FROM `{$tableSafe}` {$alias}
        INNER JOIN siswa s ON s.nis = {$alias}.`{$nisCol}`
        LEFT JOIN mata_pelajaran mp ON (
            mp.id = {$alias}.`{$mapelCol}` OR mp.kode = {$alias}.`{$mapelCol}`
        )
        WHERE {$whereSql}
        GROUP BY {$alias}.`{$nisCol}`, {$alias}.`{$mapelCol}`
        HAVING nilai_akhir IS NOT NULL
    ";

    $rows = [];
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }

    // Fallback: jika dengan filter semester/tp kosong, coba tanpa filter
    if (empty($rows) && $whereSql !== '1=1') {
        $queryNoFilter = str_replace('WHERE ' . $whereSql, 'WHERE 1=1', $query);
        $result2 = mysqli_query($koneksi, $queryNoFilter);
        if ($result2) {
            while ($row = mysqli_fetch_assoc($result2)) {
                $rows[] = $row;
            }
            mysqli_free_result($result2);
        }
    }
    return $rows;
}

/**
 * Normalisasi array agar pasti berbentuk numerik.
 *
 * @param mixed $value
 * @return array<int,mixed>
 */
function ensure_list($value): array
{
    if (!is_array($value)) {
        return [];
    }
    $keys = array_keys($value);
    $isAssoc = array_keys($keys) !== $keys;
    if ($isAssoc) {
        foreach (['rows', 'data', 'result', 'items'] as $key) {
            if (isset($value[$key]) && is_array($value[$key])) {
                return array_values($value[$key]);
            }
        }
        return [$value];
    }
    return $value;
}

/**
 * Normalisasi string untuk keperluan pencocokan.
 */
function normalize_key(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }
    if (function_exists('mb_strtolower')) {
        $value = mb_strtolower($value, 'UTF-8');
    } else {
        $value = strtolower($value);
    }
    $value = str_replace(['bahasa ', 'mata pelajaran '], '', $value);
    $value = preg_replace('/\bkelas\b/i', '', $value ?? '');
    $value = str_ireplace('kelas', '', $value);
    $value = preg_replace('/[^a-z0-9]/', '', $value);
    return (string)$value;
}

/**
 * Hasilkan alias untuk nama kelas/rombel (normalisasi, roman <-> angka, dll.).
 *
 * @return array<int,string>
 */
function generate_class_aliases(string $name): array
{
    $aliases = [];
    $base = normalize_key($name);
    if ($base === '') {
        return [];
    }
    $aliases[$base] = true;

    if (preg_match('/^([ivxlcdm]+)([a-z0-9]*)$/i', $base, $match)) {
        $roman = strtolower($match[1]);
        $rest = strtolower($match[2] ?? '');
        $arabic = roman_to_int($roman);
        if ($arabic !== null) {
            $aliases[strtolower($arabic . $rest)] = true;
        }
    }

    if (preg_match('/^([0-9]+)([a-z]*)$/i', $base, $match)) {
        $number = (int)$match[1];
        if ($number > 0 && $number <= 50) {
            $roman = strtolower(int_to_roman($number));
            $aliases[$roman . strtolower($match[2] ?? '')] = true;
        }
    }

    return array_keys($aliases);
}

function roman_to_int(string $roman): ?int
{
    $roman = strtoupper($roman);
    $map = ['M' => 1000, 'D' => 500, 'C' => 100, 'L' => 50, 'X' => 10, 'V' => 5, 'I' => 1];
    $value = 0;
    $prev = 0;
    for ($i = strlen($roman) - 1; $i >= 0; $i--) {
        $char = $roman[$i];
        if (!isset($map[$char])) {
            return null;
        }
        $curr = $map[$char];
        if ($curr < $prev) {
            $value -= $curr;
        } else {
            $value += $curr;
            $prev = $curr;
        }
    }
    return $value > 0 ? $value : null;
}

function int_to_roman(int $number): string
{
    $map = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];
    $result = '';
    foreach ($map as $roman => $value) {
        while ($number >= $value) {
            $result .= $roman;
            $number -= $value;
        }
    }
    return $result;
}

function find_rombel_by_kelas(array $rombelMap, string $kelasName): ?array
{
    foreach (generate_class_aliases($kelasName) as $alias) {
        if (isset($rombelMap[$alias])) {
            return $rombelMap[$alias];
        }
    }
    return null;
}

/**
 * Index rombel beserta pembelajaran yang tersedia.
 *
 * @param array<int,mixed> $rombelList
 * @return array<string,array<string,mixed>>
 */
function build_rombel_index(array $rombelList): array
{
    $rombelMap = [];
    foreach ($rombelList as $rombel) {
        if (!is_array($rombel)) {
            continue;
        }
        $name = (string)($rombel['nama'] ?? ($rombel['nama_rombel'] ?? ''));
        $aliases = generate_class_aliases($name);
        if (empty($aliases)) {
            continue;
        }

        $entry = $rombel;
        $entry['__mapel_index'] = [];

        if (!empty($rombel['pembelajaran']) && is_array($rombel['pembelajaran'])) {
            foreach ($rombel['pembelajaran'] as $pembelajaran) {
                if (!is_array($pembelajaran)) {
                    continue;
                }
                $mpName = $pembelajaran['nama_mata_pelajaran'] ?? '';
                $mpKey = normalize_key((string)$mpName);
                if ($mpKey !== '') {
                    $entry['__mapel_index'][$mpKey][] = $pembelajaran;
                }
                $mpId = (string)($pembelajaran['mata_pelajaran_id'] ?? '');
                if ($mpId !== '') {
                    $entry['__mapel_index'][$mpId][] = $pembelajaran;
                }
            }
        }

        foreach ($aliases as $alias) {
            if (!isset($rombelMap[$alias])) {
                $rombelMap[$alias] = $entry;
            }
        }
    }
    return $rombelMap;
}

/**
 * Cari pembelajaran yang cocok berdasarkan nama atau kode mapel.
 *
 * @param array<string,mixed> $rombel
 */
function find_matching_pembelajaran(array $rombel, string $mapelName, string $mapelIdLocal): ?array
{
    $index = $rombel['__mapel_index'] ?? [];
    $mpKey = normalize_key($mapelName);
    if ($mpKey !== '' && isset($index[$mpKey]) && !empty($index[$mpKey])) {
        return $index[$mpKey][0];
    }

    if ($mapelIdLocal !== '' && isset($index[$mapelIdLocal]) && !empty($index[$mapelIdLocal])) {
        return $index[$mapelIdLocal][0];
    }

    // coba cocokkan parsial
    foreach ($index as $key => $items) {
        if (strpos($key, $mpKey) !== false || strpos($mpKey, $key) !== false) {
            return $items[0];
        }
    }

    return null;
}

/**
 * Bangun HTML ringkasan untuk ditampilkan pada UI.
 *
 * @param array<int,array<string,mixed>> $summary
 * @param array<string,array<int,array<string,string>>> $unmatched
 */
function build_summary_html(array $summary, array $unmatched): string
{
    if (empty($summary)) {
        return '<div class="text-muted">Tidak ada data nilai ditemukan.</div>';
    }

    $rows = '';
    foreach ($summary as $row) {
        $rows .= '<tr>'
            . '<td>' . htmlspecialchars($row['kelas'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td>' . htmlspecialchars($row['mapel'], ENT_QUOTES, 'UTF-8') . '</td>'
            . '<td class="text-end">' . (int)$row['total'] . '</td>'
            . '<td class="text-end text-success">' . (int)$row['matched'] . '</td>'
            . '<td class="text-end text-warning">' . ((int)$row['no_nisn'] + (int)$row['no_anggotarombel']) . '</td>'
            . '<td class="text-end text-danger">' . ((int)$row['no_rombel'] + (int)$row['no_mapel']) . '</td>'
            . '</tr>';
    }

    $html = '<div class="table-responsive">'
        . '<table class="table table-sm table-striped">'
        . '<thead><tr>'
        . '<th>Kelas</th>'
        . '<th>Mapel</th>'
        . '<th class="text-end">Total</th>'
        . '<th class="text-end">Match</th>'
        . '<th class="text-end">Siswa unmatched</th>'
        . '<th class="text-end">Rombel/Mapel bermasalah</th>'
        . '</tr></thead><tbody>' . $rows . '</tbody></table></div>';

    $issueHtml = '';
    foreach (['kelas' => 'Rombel tidak ditemukan', 'mapel' => 'Mapel belum terpadan', 'nisn' => 'NISN tidak terdaftar', 'anggota_rombel' => 'Anggota rombel tidak ditemukan'] as $key => $title) {
        if (empty($unmatched[$key])) {
            continue;
        }
        $issueHtml .= '<div class="mt-3"><strong>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</strong><ul class="small mb-0">';
        $count = 0;
        foreach ($unmatched[$key] as $item) {
            $issueHtml .= '<li>' . htmlspecialchars(($item['nama'] ?? '') . ' (' . ($item['kelas'] ?? '-') . ' - ' . ($item['mapel'] ?? '-') . ')', ENT_QUOTES, 'UTF-8') . '</li>';
            $count++;
            if ($count >= 25) {
                $issueHtml .= '<li>... dan lainnya</li>';
                break;
            }
        }
        $issueHtml .= '</ul></div>';
    }

    return $html . $issueHtml;
}

/**
 * Ambil metadata ringkas dari paket untuk preview (tanpa data siswa detail).
 *
 * @param array<int,array<string,mixed>> $packages
 */
function extract_packages_meta(array $packages): array
{
    $meta = [];
    foreach ($packages as $package) {
        $meta[] = [
            'kelas' => $package['kelas'] ?? '',
            'mapel' => $package['mapel'] ?? '',
            'jumlah_siswa' => isset($package['records']) ? count($package['records']) : 0,
            'rombongan_belajar_id' => $package['rombongan_belajar_id'] ?? '',
            'pembelajaran_id' => $package['pembelajaran_id'] ?? '',
            'mata_pelajaran_id' => $package['mata_pelajaran_id'] ?? '',
        ];
    }
    return $meta;
}

/**
 * Susun payload untuk dikirim ke Dapodik dari satu paket.
 *
 * @param array<string,mixed> $config
 * @param array<string,mixed> $package
 */
function build_payload_from_package(array $config, array $package): array
{
    $records = [];
    foreach ($package['records'] as $row) {
        // Jangan paksa harus ada anggota_rombel_id.
        // Banyak instalasi Dapodik menerima peseta_didik_id + konteks rombel/pembelajaran.
        if (empty($row['anggota_rombel_id']) && empty($row['peserta_didik_id'])) {
            continue; // minimal salah satu harus ada
        }
        $score = round((float)$row['nilai'], 2);
        $records[] = [
            'anggota_rombel_id' => $row['anggota_rombel_id'],
            'peserta_didik_id' => $row['peserta_didik_id'] ?? null,
            // Kompatibilitas untuk berbagai varian webservice
            'nilai_kurmer' => $score,     // dipakai oleh kurikulum merdeka
            'nilai_rapor'  => $score,     // dipakai oleh beberapa modul rapor
            'nilai'        => $score,     // fallback generik
            'jenis_nilai' => 'SUMATIF',
            'tanggal_nilai' => date('Y-m-d'),
            'deskripsi' => '',
        ];
    }

    $payload = [
        'npsn' => $config['npsn'] ?? '',
        'semester_id' => $config['semester_id'] ?? '',
        'rombongan_belajar_id' => $package['rombongan_belajar_id'] ?? '',
        'pembelajaran_id' => $package['pembelajaran_id'] ?? '',
        'mata_pelajaran_id' => $package['mata_pelajaran_id'] ?? '',
        'ptk_id' => $package['ptk_id'] ?? null,
        'nilai' => $records,
    ];
    if (!empty($package['id_evaluasi'])) { $payload['id_evaluasi'] = $package['id_evaluasi']; }
    return $payload;
}

/**
 * Ambil koleksi pesan dari respon Dapodik (baik JSON maupun teks log).
 *
 * @param mixed $response
 * @return array<int,string>
 */
function dapodik_response_messages($response): array
{
    $candidates = [];
    if (is_array($response)) {
        if (isset($response['messages']) && is_array($response['messages'])) {
            $candidates = array_merge($candidates, $response['messages']);
        }
        foreach (['raw_body', 'raw', 'body', 'message', 'request_url'] as $key) {
            if (isset($response[$key]) && is_string($response[$key])) {
                $candidates[] = $response[$key];
            }
        }
    } elseif (is_string($response)) {
        $candidates[] = $response;
    }

    $messages = [];
    foreach ($candidates as $candidate) {
        if (!is_string($candidate)) {
            continue;
        }
        foreach (dapodik_split_progress_messages($candidate) as $message) {
            $messages[] = $message;
        }
    }

    if (empty($messages)) {
        return [];
    }

    $unique = [];
    $seen = [];
    foreach ($messages as $message) {
        $key = strtolower($message);
        if (isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $unique[] = $message;
    }

    return $unique;
}

/**
 * Filter pesan agar tidak membanjiri UI kecuali saat debug diaktifkan.
 * - Saat non-debug: sembunyikan raw_body, request_url, dan pesan yang sangat panjang (>300 chars)
 * - Saat debug: tampilkan apa adanya
 *
 * @param array<int,string> $messages
 * @return array<int,string>
 */
function dapodik_filter_messages(array $messages, bool $debug): array
{
    if ($debug) {
        return $messages;
    }
    $out = [];
    foreach ($messages as $m) {
        if (!is_string($m)) { continue; }
        $t = trim($m);
        if ($t === '') { continue; }
        if (strlen($t) > 300) { continue; }
        if (preg_match('/request_url|raw_body/i', $t)) { continue; }
        if (preg_match('/^https?:\/\//i', $t)) { continue; }
        $out[] = $t;
    }
    if (empty($out)) { return $messages; }
    return $out;
}

/**
 * Evaluasi apakah respon Dapodik menandakan keberhasilan.
 *
 * @param mixed $response
 */
function dapodik_response_success($response, bool $strict = false): bool
{
    if (is_array($response)) {
        $allMessages = dapodik_response_messages($response);
        if (!$strict) {
            // Heuristik non-ketat: terima Timeout/Busy sebagai OK (trigger-only)
            $blob = strtolower(implode(' | ', array_map(function ($m) {
                return is_string($m) ? $m : '';
            }, $allMessages)));
            if ($blob !== '') {
                $looksTimeout = (strpos($blob, 'request timeout') !== false)
                    || (strpos($blob, 'timed out') !== false)
                    || (strpos($blob, 'takes too long to process') !== false)
                    || (strpos($blob, 'connection timeout') !== false)
                    || (strpos($blob, '504') !== false && (strpos($blob, 'gateway') !== false || strpos($blob, 'time') !== false))
                    || (strpos($blob, '503') !== false && (strpos($blob, 'service') !== false || strpos($blob, 'unavailable') !== false));
                if ($looksTimeout) {
                    return true;
                }
            }
        }
        // 1) Jika respons memiliki flag sukses eksplisit, hormati itu lebih dulu
        if (isset($response['success'])) {
            $value = $response['success'];
            if (is_bool($value)) {
                if ($strict) {
                    // Dalam mode ketat, abaikan 'success=true' jika status hanya 'accepted'
                    if (isset($response['status']) && strtolower((string)$response['status']) === 'accepted') {
                        return false;
                    }
                }
                return $value;
            }
            $normalized = strtolower((string)$value);
            if ($strict && isset($response['status']) && strtolower((string)$response['status']) === 'accepted') {
                // Jangan anggap sukses jika hanya accepted
            } elseif (in_array($normalized, ['1', 'true', 'ok', 'success', 'berhasil', 'selesai'], true)) {
                return true;
            }
        }
        if (isset($response['status'])) {
            $status = strtolower((string)$response['status']);
            if ($strict && $status === 'accepted') {
                // Mode ketat: 'accepted' bukan keberhasilan final
            } elseif (in_array($status, ['ok', 'success', 'berhasil', 'simulated', 'true'], true)) {
                return true;
            }
        }
        if (isset($response['message']) && preg_match('/\b(success|berhasil|selesai|ok|tersimpan|disimpan)\b/i', (string)$response['message'])) {
            return true;
        }
        // 2) Bila tidak ada indikator sukses yang jelas, cek pesan kegagalan umum
        foreach ($allMessages as $m) {
            if (preg_match('/harap\s+masuk[ak]n?\s+paramet?er\s+table/i', $m)) {
                return false;
            }
        }
        foreach ($allMessages as $message) {
            if (preg_match('/\b(success|berhasil|selesai|ok|tersimpan|disimpan)\b/i', $message)) {
                return true;
            }
        }
        return false;
    }

    if (is_string($response)) {
        $lower = strtolower($response);
        if (preg_match('/\b(success|berhasil|selesai|ok|tersimpan|disimpan)\b/i', $response) === 1) {
            return true;
        }
        if (!$strict) {
            // Heuristik timeout/busy pada respon string mentah (HTML/text)
            if (strpos($lower, 'request timeout') !== false
                || strpos($lower, 'timed out') !== false
                || strpos($lower, 'takes too long to process') !== false
                || strpos($lower, 'connection timeout') !== false
                || (strpos($lower, '504') !== false && (strpos($lower, 'gateway') !== false || strpos($lower, 'time') !== false))
                || (strpos($lower, '503') !== false && (strpos($lower, 'service') !== false || strpos($lower, 'unavailable') !== false))
            ) {
                return true;
            }
        }
        return false;
    }

    return false;
}

/**
 * Pecah string log bergaya progress menjadi array pesan.
 *
 * @return array<int,string>
 */
function dapodik_split_progress_messages(string $text): array
{
    $normalized = trim(str_replace(["\r\n", "\r"], "\n", $text));
    if ($normalized === '') {
        return [];
    }
    $parts = preg_split('/\|+|\n+/', $normalized);
    if ($parts === false) {
        return [$normalized];
    }
    $messages = [];
    foreach ($parts as $part) {
        $message = trim($part);
        if ($message !== '') {
            $messages[] = $message;
        }
    }
    if (empty($messages)) {
        $messages[] = $normalized;
    }
    return $messages;
}

/**
 * Ambil pesan terakhir untuk ringkasan singkat.
 *
 * @param array<int,string> $messages
 */
function dapodik_last_message(array $messages): string
{
    if (empty($messages)) {
        return '';
    }
    $messages = array_values($messages);
    return (string)($messages[count($messages) - 1] ?? '');
}

/**
 * Ambil pesan terakhir yang tidak terlihat seperti URL/debug.
 *
 * @param array<int,string> $messages
 */
function dapodik_last_human_message(array $messages): string
{
    if (empty($messages)) {
        return '';
    }
    $lastHuman = '';
    $lastNonUrl = '';
    foreach ($messages as $msg) {
        if (!is_string($msg)) { continue; }
        $trim = trim($msg);
        // Skip URL panjang atau entri debug murni
        if (preg_match('#^https?://#i', $trim)) { continue; }
        if (strlen($trim) > 300) { continue; }
        if (preg_match('/request_url|raw_body/i', $trim)) { continue; }
        $lastNonUrl = $trim; // simpan kandidat non-URL terakhir
        if (preg_match('/(success|berhasil|gagal|harap|error|pesan|message)/i', $trim)) {
            $lastHuman = $trim; // prefer pesan dengan kata kunci
        }
    }
    if ($lastHuman !== '') { return $lastHuman; }
    if ($lastNonUrl !== '') { return $lastNonUrl; }
    return '';
}

/**
 * Simpan/muat cache hasil prepare untuk sesi saat ini.
 */
const DAPODIK_PREPARED_TTL = 3600; // detik (1 jam)

function dapodik_prepared_cache_key(array $config, array $setting): string
{
    $npsn = trim((string)($config['npsn'] ?? ''));
    $sid = trim((string)($config['semester_id'] ?? ''));
    $tp = trim((string)($setting['tp'] ?? ''));
    $smt = trim((string)($setting['semester'] ?? ''));
    return implode('|', [$npsn, $sid, $tp, $smt]);
}

function dapodik_store_prepared_cache(array $prepared, array $config, array $setting): void
{
    if (!isset($_SESSION)) { return; }
    $key = dapodik_prepared_cache_key($config, $setting);
    $_SESSION['dapodik_prepared_cache'] = $_SESSION['dapodik_prepared_cache'] ?? [];
    $_SESSION['dapodik_prepared_cache'][$key] = [
        'time' => time(),
        'prepared' => $prepared,
    ];
}

function dapodik_load_prepared_cache(array $config, array $setting): ?array
{
    if (!isset($_SESSION['dapodik_prepared_cache'])) { return null; }
    $key = dapodik_prepared_cache_key($config, $setting);
    $entry = $_SESSION['dapodik_prepared_cache'][$key] ?? null;
    if (!is_array($entry)) { return null; }
    $age = time() - (int)($entry['time'] ?? 0);
    if ($age > DAPODIK_PREPARED_TTL) { return null; }
    $prepared = $entry['prepared'] ?? null;
    if (!is_array($prepared)) { return null; }
    // Validasi minimal
    if (!isset($prepared['packages']) || !is_array($prepared['packages'])) { return null; }
    return $prepared;
}

// Cache PD & Rombel agar preview tidak gagal saat WS sibuk
const DAPODIK_PDROMBEL_TTL = 900; // 15 menit

function dapodik_pd_rombel_cache_key(array $config): string
{
    $npsn = trim((string)($config['npsn'] ?? ''));
    $sid = trim((string)($config['semester_id'] ?? ''));
    return 'pdrombel|' . $npsn . '|' . $sid;
}

/**
 * @param array<int,mixed> $pd
 * @param array<int,mixed> $rombel
 */
function dapodik_store_pd_rombel_cache(array $pd, array $rombel, array $config): void
{
    if (!isset($_SESSION)) { return; }
    $key = dapodik_pd_rombel_cache_key($config);
    $_SESSION['dapodik_pdrombel_cache'] = $_SESSION['dapodik_pdrombel_cache'] ?? [];
    $_SESSION['dapodik_pdrombel_cache'][$key] = [
        'time' => time(),
        'pd' => $pd,
        'rombel' => $rombel,
    ];
}

/**
 * @return array{pd:array<int,mixed>,rombel:array<int,mixed>}|null
 */
function dapodik_load_pd_rombel_cache(array $config): ?array
{
    if (!isset($_SESSION['dapodik_pdrombel_cache'])) { return null; }
    $key = dapodik_pd_rombel_cache_key($config);
    $entry = $_SESSION['dapodik_pdrombel_cache'][$key] ?? null;
    if (!is_array($entry)) { return null; }
    $age = time() - (int)($entry['time'] ?? 0);
    if ($age > DAPODIK_PDROMBEL_TTL) { return null; }
    $pd = $entry['pd'] ?? null; $rombel = $entry['rombel'] ?? null;
    if (!is_array($pd) || !is_array($rombel)) { return null; }
    return ['pd' => $pd, 'rombel' => $rombel];
}

/**
 * Tampilkan ringkasan hasil kirim.
 *
 * @param array<int,array<string,mixed>> $summary
 * @param array<int,array<string,mixed>> $responses
 */
function build_send_summary_html(array $summary, array $responses): string
{
    $html = build_summary_html($summary, ['kelas' => [], 'mapel' => [], 'nisn' => [], 'anggota_rombel' => []]);
    if (empty($responses)) {
        return $html;
    }

    $log = '<div class="mt-3"><strong>Detail Pengiriman</strong><ul class="small mb-0">';
    foreach ($responses as $resp) {
        $last = '';
        $messageSuffix = '';
        if (!empty($resp['messages']) && is_array($resp['messages'])) {
            $last = dapodik_last_human_message($resp['messages']);
            if ($last !== '') {
                $messageSuffix = ' <span class="text-muted">(' . htmlspecialchars($last, ENT_QUOTES, 'UTF-8') . ')</span>';
            }
        }
        if (($resp['status'] ?? '') === 'ok') {
            $log .= '<li class="text-success">' . htmlspecialchars(($resp['kelas'] ?? '') . ' / ' . ($resp['mapel'] ?? '') . ' - ' . ($resp['jumlah'] ?? 0) . ' siswa', ENT_QUOTES, 'UTF-8') . $messageSuffix . '</li>';
        } else {
            $errorText = (string)($resp['error'] ?? '');
            if ($errorText === '') {
                $errorText = $last !== '' ? $last : 'Dapodik tidak memberikan pesan kesalahan.';
            }
            $log .= '<li class="text-danger">' . htmlspecialchars(($resp['kelas'] ?? '') . ' / ' . ($resp['mapel'] ?? '') . ' gagal: ' . $errorText, ENT_QUOTES, 'UTF-8');
            if ($messageSuffix !== '') {
                $log .= $messageSuffix;
            }
            $log .= '</li>';
        }
    }
    $log .= '</ul></div>';
    return $html . $log;
}

/**
 * Ekstrak informasi sekolah dari berbagai struktur respon Dapodik.
 *
 * @param mixed $value
 * @return array<string,mixed>
 */
function dapodik_extract_sekolah_info($value): array
{
    if (!is_array($value)) {
        return [];
    }

    if (isset($value['nama'])) {
        return $value;
    }

    foreach (['sekolah', 'data', 'result'] as $key) {
        if (isset($value[$key])) {
            $found = dapodik_extract_sekolah_info($value[$key]);
            if (!empty($found)) {
                return $found;
            }
        }
    }

    if (isset($value['rows']) && is_array($value['rows'])) {
        $found = dapodik_extract_sekolah_info($value['rows']);
        if (!empty($found)) {
            return $found;
        }
    }

    if (dapodik_is_list($value)) {
        foreach ($value as $item) {
            $found = dapodik_extract_sekolah_info($item);
            if (!empty($found)) {
                return $found;
            }
        }
    }

    return [];
}

/**
 * Cek apakah array berindeks numerik berurutan.
 */
function dapodik_is_list(array $array): bool
{
    if (function_exists('array_is_list')) {
        return array_is_list($array);
    }
    if ($array === []) {
        return true;
    }
    $expected = range(0, count($array) - 1);
    return array_keys($array) === $expected;
}
