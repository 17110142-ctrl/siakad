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
    $isSimulation = $client instanceof MockDapodikClient;

    if (empty($prepared['summary'])) {
        json_response(true, 'Tidak ada nilai yang ditemukan untuk semester ini.', [
            'data' => [
                'html' => '<div class="text-muted">Tidak ada data nilai untuk semester / tahun ajaran saat ini.</div>',
                'stats' => $prepared['stats'],
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
            'mode' => $isSimulation ? 'simulation' : 'live',
        ],
    ]);
}

/**
 * Kirim nilai ke Dapodik (percobaan langsung).
 */
function handle_kirim_nilai(mysqli $koneksi, array $setting): void
{
    $config = dapodik_get_config($koneksi);
    $client = instantiate_client_from_config($config);
    if (!$client) {
        json_response(false, 'Pengaturan Dapodik belum lengkap.', [], 422);
    }

    $prepared = prepare_packages($koneksi, $setting, $config, $client);
    $packages = $prepared['packages'];
    if (empty($packages)) {
        json_response(false, 'Tidak ada paket nilai yang siap dikirim.', [], 422);
    }

    $isSimulation = $client instanceof MockDapodikClient;

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
            $success++;
            $responses[] = [
                'kelas' => $package['kelas'] ?? '',
                'mapel' => $package['mapel'] ?? '',
                'jumlah' => count($payload['nilai']),
                'status' => 'ok',
                'response' => $result,
            ];
        } catch (Throwable $e) {
            $failed++;
            $responses[] = [
                'kelas' => $package['kelas'] ?? '',
                'mapel' => $package['mapel'] ?? '',
                'jumlah' => count($payload['nilai']),
                'status' => 'error',
                'error' => $e->getMessage(),
                'payload' => $payload,
            ];
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
    return new DapodikClient(dapodik_normalize_base_url($base), $token, $npsn);
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
    $rows = fetch_local_grade_rows($koneksi, $semester, $tp);

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
            'unmatched' => [
                'kelas' => [],
                'mapel' => [],
                'nisn' => [],
                'anggota_rombel' => [],
            ],
        ];
    }

    $pesertaList = ensure_list($client->getPesertaDidik());
    $rombelList = ensure_list($client->getRombonganBelajar());

    $pesertaByNisn = [];
    foreach ($pesertaList as $pd) {
        $nisn = trim((string)($pd['nisn'] ?? ''));
        if ($nisn === '') {
            continue;
        }
        $pesertaByNisn[$nisn] = $pd;
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

        $kelasKey = normalize_key($kelas);
        if ($kelasKey === '' || !isset($rombelMap[$kelasKey])) {
            $summaryIndex[$summaryKey]['no_rombel']++;
            $unmatched['kelas'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'],
                'nama' => $row['nama_siswa'],
            ];
            continue;
        }
        $rombel = $rombelMap[$kelasKey];

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
        if ($nisn === '' || !isset($pesertaByNisn[$nisn])) {
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

        $pd = $pesertaByNisn[$nisn];
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

        $packages[$packageKey]['records'][] = [
            'nis' => $row['nis'],
            'nisn' => $nisn,
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
        'unmatched' => $unmatched,
    ];
}

/**
 * Jalankan query nilai sumatif dan gabungkan dengan siswa + mapel.
 *
 * @return array<int,array<string,mixed>>
 */
function fetch_local_grade_rows(mysqli $koneksi, string $semester, string $tp): array
{
    $sql = "
        SELECT ns.nis,
               ns.mapel,
               ns.kelas,
               s.nama AS nama_siswa,
               s.nisn,
               mp.nama_mapel,
               mp.kode AS kode_mapel,
               AVG(CASE WHEN ns.nilai REGEXP '^-?[0-9]+(\\\\.[0-9]+)?$' THEN CAST(ns.nilai AS DECIMAL(7,2)) ELSE NULL END) AS nilai_akhir
        FROM nilai_sumatif ns
        INNER JOIN siswa s ON s.nis = ns.nis
        INNER JOIN mata_pelajaran mp ON mp.id = ns.mapel
        WHERE ns.semester = ? AND ns.tp = ?
        GROUP BY ns.nis, ns.mapel
        HAVING nilai_akhir IS NOT NULL
    ";

    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        return [];
    }
    mysqli_stmt_bind_param($stmt, 'ss', $semester, $tp);
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
    $value = preg_replace('/[^a-z0-9]/', '', $value);
    return (string)$value;
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
        $name = $rombel['nama'] ?? ($rombel['nama_rombel'] ?? '');
        $key = normalize_key((string)$name);
        if ($key === '') {
            continue;
        }
        if (!isset($rombelMap[$key])) {
            $rombelMap[$key] = $rombel;
            $rombelMap[$key]['__mapel_index'] = [];
        }

        if (!empty($rombel['pembelajaran']) && is_array($rombel['pembelajaran'])) {
            foreach ($rombel['pembelajaran'] as $pembelajaran) {
                if (!is_array($pembelajaran)) {
                    continue;
                }
                $mpName = $pembelajaran['nama_mata_pelajaran'] ?? '';
                $mpKey = normalize_key((string)$mpName);
                if ($mpKey !== '') {
                    $rombelMap[$key]['__mapel_index'][$mpKey][] = $pembelajaran;
                }
                $mpId = (string)($pembelajaran['mata_pelajaran_id'] ?? '');
                if ($mpId !== '') {
                    $rombelMap[$key]['__mapel_index'][$mpId][] = $pembelajaran;
                }
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
        if (empty($row['anggota_rombel_id'])) {
            continue;
        }
        $records[] = [
            'anggota_rombel_id' => $row['anggota_rombel_id'],
            'peserta_didik_id' => $row['peserta_didik_id'] ?? null,
            'nilai_kurmer' => round((float)$row['nilai'], 2),
            'jenis_nilai' => 'SUMATIF',
            'tanggal_nilai' => date('Y-m-d'),
            'deskripsi' => '',
        ];
    }

    return [
        'npsn' => $config['npsn'] ?? '',
        'semester_id' => $config['semester_id'] ?? '',
        'rombongan_belajar_id' => $package['rombongan_belajar_id'] ?? '',
        'pembelajaran_id' => $package['pembelajaran_id'] ?? '',
        'mata_pelajaran_id' => $package['mata_pelajaran_id'] ?? '',
        'ptk_id' => $package['ptk_id'] ?? null,
        'nilai' => $records,
    ];
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
        if (($resp['status'] ?? '') === 'ok') {
            $log .= '<li class="text-success">' . htmlspecialchars(($resp['kelas'] ?? '') . ' / ' . ($resp['mapel'] ?? '') . ' - ' . ($resp['jumlah'] ?? 0) . ' siswa', ENT_QUOTES, 'UTF-8') . '</li>';
        } else {
            $log .= '<li class="text-danger">' . htmlspecialchars(($resp['kelas'] ?? '') . ' / ' . ($resp['mapel'] ?? '') . ' gagal: ' . ($resp['error'] ?? ''), ENT_QUOTES, 'UTF-8') . '</li>';
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
