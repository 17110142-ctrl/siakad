#!/usr/bin/env php
<?php

/**
 * Simulasi sinkronisasi nilai ke Dapodik via CLI.
 *
 * Script ini meniru alur prepare_packages di mykurmer/dapodik/api.php
 * tanpa men-trigger UI ataupun endpoint AJAX.
 *
 * Cara pakai:
 *   php scripts/dapodik_simulation.php --semester=1 --tp="2025/2026" --limit=2
 *
 * Parameter:
 *   --semester   Semester aktif (default mengikuti setting aplikasi)
 *   --tp         Tahun pelajaran (default mengikuti setting aplikasi)
 *   --limit      Jumlah payload contoh yang akan ditampilkan (default: 2)
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Script ini sebaiknya dijalankan melalui CLI.\n");
}

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/function.php';
require_once __DIR__ . '/../config/crud.php';
require_once __DIR__ . '/../mykurmer/dapodik/dapodik_helpers.php';
require_once __DIR__ . '/../mykurmer/dapodik/DapodikClientInterface.php';
require_once __DIR__ . '/../mykurmer/dapodik/DapodikClient.php';
require_once __DIR__ . '/../mykurmer/dapodik/MockDapodikClient.php';

$options = getopt('', ['semester::', 'tp::', 'limit::']);
$semester = isset($options['semester']) ? (string)$options['semester'] : ($setting['semester'] ?? '1');
$tp = isset($options['tp']) ? (string)$options['tp'] : ($setting['tp'] ?? date('Y') . '/' . (date('Y') + 1));
$limit = isset($options['limit']) ? max(1, (int)$options['limit']) : 2;

$config = dapodik_get_config($koneksi);
$config = array_merge($config, dapodik_resolve_semester_info($config, $setting));

$client = dapodik_sim_instantiate_client_from_config($config);
if (!$client) {
    fwrite(STDERR, "Konfigurasi Dapodik belum lengkap. Simpan pengaturan terlebih dahulu.\n");
    exit(1);
}

[$source, $rows] = dapodik_sim_fetch_grade_rows($koneksi, $semester, $tp);

printf(
    "Config: base=%s, semester_id=%s, npsn=%s\n",
    $config['base_url'] ?? '',
    $config['semester_id'] ?? '',
    $config['npsn'] ?? ''
);
printf("Total baris nilai (%s): %d\n", $source, count($rows));

if (empty($rows)) {
    fwrite(STDOUT, "Tidak ada data nilai yang dapat disimulasikan. Pastikan nilai telah diinput.\n");
    exit(0);
}

try {
    $sekolahRaw = $client->getSekolah();
    $pesertaList = dapodik_sim_ensure_list($client->getPesertaDidik());
    $rombelList = dapodik_sim_ensure_list($client->getRombonganBelajar());
} catch (Throwable $e) {
    fwrite(STDERR, "Gagal mengambil data dari web service: " . $e->getMessage() . "\n");
    exit(1);
}

$sekolah = dapodik_extract_sekolah_info($sekolahRaw);
$namaSekolah = $sekolah['nama'] ?? '(tidak diketahui)';
printf(
    "Sekolah: %s | Peserta Didik: %d | Rombel: %d\n",
    $namaSekolah,
    count($pesertaList),
    count($rombelList)
);

$prepared = dapodik_sim_prepare_packages($rows, $config, $pesertaList, $rombelList);

dapodik_sim_print_summary($prepared['summary']);

fwrite(STDOUT, "\nStatus unmatched:\n");
fwrite(STDOUT, dapodik_sim_format_unmatched($prepared['unmatched']) . "\n");

$packages = $prepared['packages'];
$matchedCount = $prepared['matched_count'];
printf("\nTotal paket siap kirim: %d | Total siswa match: %d\n", count($packages), $matchedCount);

if (empty($packages)) {
    fwrite(STDOUT, "Tidak ada paket nilai yang siap dikirim.\n");
    exit(0);
}

fwrite(STDOUT, sprintf("Tampilkan maksimal %d payload simulasi:\n", $limit));
$count = 0;
foreach ($packages as $package) {
    if ($count >= $limit) {
        break;
    }
    $count++;
    $payload = dapodik_sim_build_payload($config, $package);
    printf(
        "\nPayload #%d (%s / %s) -> %d siswa\n",
        $count,
        $package['kelas'] ?? '',
        $package['mapel'] ?? '',
        isset($payload['nilai']) ? count($payload['nilai']) : 0
    );
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    fwrite(STDOUT, $json . "\n");
}

fwrite(STDOUT, "\nSimulasi selesai. Gunakan payload di atas untuk uji manual endpoint kirimNilai jika diperlukan.\n");
exit(0);

/**
 * Build client dari konfigurasi tersimpan.
 *
 * @param array<string,mixed> $config
 */
function dapodik_sim_instantiate_client_from_config(array $config): ?DapodikClientInterface
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
 * Ambil data nilai dari DB dengan prioritas nilai_sumatif.
 *
 * @return array{0:string,1:array<int,array<string,mixed>>}
 */
function dapodik_sim_fetch_grade_rows(mysqli $koneksi, string $semester, string $tp): array
{
    $rows = [];

    $stmt = mysqli_prepare($koneksi, "SELECT COUNT(*) AS jumlah FROM nilai_sumatif WHERE semester=? AND tp=?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $semester, $tp);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $sumatifCount = 0;
        if ($res) {
            $data = mysqli_fetch_assoc($res);
            $sumatifCount = (int)($data['jumlah'] ?? 0);
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
    } else {
        $sumatifCount = 0;
    }

    if ($sumatifCount > 0) {
        $sql = "
            SELECT ns.nis,
                   ns.mapel,
                   ns.kelas,
                   s.nama AS nama_siswa,
                   s.nisn,
                   mp.nama_mapel,
                   mp.kode AS kode_mapel,
                   AVG(CASE WHEN ns.nilai REGEXP '^-?[0-9]+(\\\\.[0-9]+)?$'
                            THEN CAST(ns.nilai AS DECIMAL(7,2))
                            ELSE NULL END) AS nilai_akhir
            FROM nilai_sumatif ns
            INNER JOIN siswa s ON s.nis = ns.nis
            INNER JOIN mata_pelajaran mp ON mp.id = ns.mapel
            WHERE ns.semester = ? AND ns.tp = ?
            GROUP BY ns.nis, ns.mapel
            HAVING nilai_akhir IS NOT NULL
        ";
        $stmt = mysqli_prepare($koneksi, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $semester, $tp);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = [
                        'nis' => (string)$row['nis'],
                        'mapel' => (string)$row['mapel'],
                        'kelas' => (string)$row['kelas'],
                        'nama_siswa' => (string)$row['nama_siswa'],
                        'nisn' => (string)$row['nisn'],
                        'nama_mapel' => (string)$row['nama_mapel'],
                        'nilai_akhir' => round((float)$row['nilai_akhir'], 2),
                    ];
                }
                mysqli_free_result($result);
            }
            mysqli_stmt_close($stmt);
        }
        return ['nilai_sumatif', $rows];
    }

    // fallback ke nilai_sts
    $sql = "
        SELECT ns.nis,
               ns.mapel,
               ns.kelas,
               s.nama AS nama_siswa,
               s.nisn,
               mp.nama_mapel,
               mp.kode AS kode_mapel,
               ns.nilai_raport,
               ns.nilai_sts,
               ns.nilai_harian
        FROM nilai_sts ns
        INNER JOIN siswa s ON s.nis = ns.nis
        INNER JOIN mata_pelajaran mp ON mp.id = ns.mapel
        WHERE ns.semester = ? AND ns.tp = ?
    ";
    $stmt = mysqli_prepare($koneksi, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $semester, $tp);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $nilai = dapodik_sim_pick_numeric(
                    $row['nilai_raport'] ?? null,
                    $row['nilai_sts'] ?? null,
                    $row['nilai_harian'] ?? null
                );
                if ($nilai === null) {
                    continue;
                }
                $rows[] = [
                    'nis' => (string)$row['nis'],
                    'mapel' => (string)$row['mapel'],
                    'kelas' => (string)$row['kelas'],
                    'nama_siswa' => (string)$row['nama_siswa'],
                    'nisn' => (string)($row['nisn'] ?? ''),
                    'nama_mapel' => (string)$row['nama_mapel'],
                    'nilai_akhir' => round($nilai, 2),
                ];
            }
            mysqli_free_result($result);
        }
        mysqli_stmt_close($stmt);
    }

    return ['nilai_sts', $rows];
}

/**
 * Konversi nilai string/angka ke float pertama yang valid.
 *
 * @param mixed ...$values
 */
function dapodik_sim_pick_numeric(...$values): ?float
{
    foreach ($values as $value) {
        if ($value === null || $value === '') {
            continue;
        }
        if (is_numeric($value)) {
            return (float)$value;
        }
        $normalized = str_replace(',', '.', (string)$value);
        if (is_numeric($normalized)) {
            return (float)$normalized;
        }
    }
    return null;
}

/**
 * Siapkan paket nilai berdasarkan data lokal & dataset Dapodik.
 *
 * @param array<int,array<string,mixed>> $rows
 * @param array<int,mixed> $pesertaList
 * @param array<int,mixed> $rombelList
 * @return array{
 *   packages: array<int,array<string,mixed>>,
 *   summary: array<int,array<string,mixed>>,
 *   unmatched: array<string,array<int,array<string,mixed>>>,
 *   matched_count: int
 * }
 */
function dapodik_sim_prepare_packages(
    array $rows,
    array $config,
    array $pesertaList,
    array $rombelList
): array {
    if (empty($rows)) {
        return [
            'packages' => [],
            'summary' => [],
            'unmatched' => [
                'kelas' => [],
                'mapel' => [],
                'nisn' => [],
                'anggota_rombel' => [],
            ],
            'matched_count' => 0,
        ];
    }

    $pesertaByNisn = [];
    foreach ($pesertaList as $pd) {
        if (!is_array($pd)) {
            continue;
        }
        $nisn = trim((string)($pd['nisn'] ?? ''));
        if ($nisn === '') {
            continue;
        }
        $pesertaByNisn[$nisn] = $pd;
    }

    $rombelMap = dapodik_sim_build_rombel_index($rombelList);

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
        $kelas = trim((string)($row['kelas'] ?? ''));
        $mapelName = trim((string)($row['nama_mapel'] ?? ''));
        $mapelIdLocal = (string)($row['mapel'] ?? '');
        $nilaiAkhir = round((float)($row['nilai_akhir'] ?? 0), 2);

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

        $kelasKey = dapodik_sim_normalize_key($kelas);
        if ($kelasKey === '' || !isset($rombelMap[$kelasKey])) {
            $summaryIndex[$summaryKey]['no_rombel']++;
            $unmatched['kelas'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'] ?? '',
                'nama' => $row['nama_siswa'] ?? '',
            ];
            continue;
        }
        $rombel = $rombelMap[$kelasKey];

        $pembelajaran = dapodik_sim_find_matching_pembelajaran($rombel, $mapelName, $mapelIdLocal);
        if ($pembelajaran === null) {
            $summaryIndex[$summaryKey]['no_mapel']++;
            $unmatched['mapel'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'] ?? '',
                'nama' => $row['nama_siswa'] ?? '',
            ];
            continue;
        }

        $nisn = trim((string)($row['nisn'] ?? ''));
        if ($nisn === '' || !isset($pesertaByNisn[$nisn])) {
            $summaryIndex[$summaryKey]['no_nisn']++;
            $unmatched['nisn'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'] ?? '',
                'nisn' => $nisn,
                'nama' => $row['nama_siswa'] ?? '',
            ];
            continue;
        }

        $pd = $pesertaByNisn[$nisn];
        $anggotaRombelId = (string)($pd['anggota_rombel_id'] ?? '');
        if ($anggotaRombelId === '') {
            $summaryIndex[$summaryKey]['no_anggotarombel']++;
            $unmatched['anggota_rombel'][] = [
                'kelas' => $kelas,
                'mapel' => $mapelName,
                'nis' => $row['nis'] ?? '',
                'nisn' => $nisn,
                'nama' => $row['nama_siswa'] ?? '',
            ];
            continue;
        }

        $packageKey = ($rombel['rombongan_belajar_id'] ?? '') . '::' . ($pembelajaran['pembelajaran_id'] ?? dapodik_sim_normalize_key($mapelName));
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
            'nis' => $row['nis'] ?? '',
            'nisn' => $nisn,
            'nama' => $row['nama_siswa'] ?? '',
            'nilai' => $nilaiAkhir,
            'anggota_rombel_id' => $anggotaRombelId,
            'peserta_didik_id' => $pd['peserta_didik_id'] ?? null,
        ];

        $summaryIndex[$summaryKey]['matched']++;
        $matchedCount++;
    }

    $summary = array_values($summaryIndex);
    usort($summary, function ($a, $b) {
        return [$a['kelas'], $a['mapel']] <=> [$b['kelas'], $b['mapel']];
    });

    return [
        'packages' => array_values($packages),
        'summary' => $summary,
        'unmatched' => $unmatched,
        'matched_count' => $matchedCount,
    ];
}

/**
 * Normalisasi array agar berbentuk numerik.
 *
 * @param mixed $value
 * @return array<int,mixed>
 */
function dapodik_sim_ensure_list($value): array
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

function dapodik_sim_normalize_key(string $value): string
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
 * @param array<int,mixed> $rombelList
 * @return array<string,array<string,mixed>>
 */
function dapodik_sim_build_rombel_index(array $rombelList): array
{
    $rombelMap = [];
    foreach ($rombelList as $rombel) {
        if (!is_array($rombel)) {
            continue;
        }
        $name = $rombel['nama'] ?? ($rombel['nama_rombel'] ?? '');
        $key = dapodik_sim_normalize_key((string)$name);
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
                $mpKey = dapodik_sim_normalize_key((string)$mpName);
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
 * @param array<string,mixed> $rombel
 */
function dapodik_sim_find_matching_pembelajaran(array $rombel, string $mapelName, string $mapelIdLocal): ?array
{
    $index = $rombel['__mapel_index'] ?? [];
    $mpKey = dapodik_sim_normalize_key($mapelName);
    if ($mpKey !== '' && isset($index[$mpKey]) && !empty($index[$mpKey])) {
        return $index[$mpKey][0];
    }
    if ($mapelIdLocal !== '' && isset($index[$mapelIdLocal]) && !empty($index[$mapelIdLocal])) {
        return $index[$mapelIdLocal][0];
    }
    foreach ($index as $key => $items) {
        if (strpos($key, $mpKey) !== false || strpos($mpKey, $key) !== false) {
            return $items[0];
        }
    }
    return null;
}

/**
 * Bangun payload contoh untuk endpoint kirimNilai.
 *
 * @param array<string,mixed> $config
 * @param array<string,mixed> $package
 * @return array<string,mixed>
 */
function dapodik_sim_build_payload(array $config, array $package): array
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
 * Cetak ringkasan per kelas/mapel ke STDOUT.
 *
 * @param array<int,array<string,mixed>> $summary
 */
function dapodik_sim_print_summary(array $summary): void
{
    if (empty($summary)) {
        fwrite(STDOUT, "Tidak ada ringkasan yang bisa ditampilkan.\n");
        return;
    }
    fwrite(STDOUT, "Ringkasan per kelas/mapel:\n");
    foreach ($summary as $row) {
        printf(
            "  - %-8s %-40s total=%3d match=%3d no_rombel=%2d no_mapel=%2d no_nisn=%2d no_anggota=%2d\n",
            $row['kelas'] ?? '',
            $row['mapel'] ?? '',
            (int)($row['total'] ?? 0),
            (int)($row['matched'] ?? 0),
            (int)($row['no_rombel'] ?? 0),
            (int)($row['no_mapel'] ?? 0),
            (int)($row['no_nisn'] ?? 0),
            (int)($row['no_anggotarombel'] ?? 0)
        );
    }
}

/**
 * Format ringkasan unmatched untuk CLI.
 *
 * @param array<string,array<int,array<string,mixed>>> $unmatched
 */
function dapodik_sim_format_unmatched(array $unmatched): string
{
    $lines = [];
    $mapping = [
        'kelas' => 'Rombel tidak ditemukan',
        'mapel' => 'Mapel belum terpadan',
        'nisn' => 'NISN tidak terdaftar di Dapodik',
        'anggota_rombel' => 'Anggota rombel tidak ditemukan',
    ];
    foreach ($mapping as $key => $label) {
        if (empty($unmatched[$key])) {
            continue;
        }
        $sample = $unmatched[$key][0] ?? [];
        $lines[] = sprintf(
            "- %s: %d entri (contoh: %s / %s / %s)",
            $label,
            count($unmatched[$key]),
            $sample['nama'] ?? '-',
            $sample['kelas'] ?? '-',
            $sample['mapel'] ?? '-'
        );
    }
    return empty($lines) ? "Tidak ada data unmatched." : implode("\n", $lines);
}
