<?php

require_once __DIR__ . '/DapodikClientInterface.php';

class MockDapodikClient implements DapodikClientInterface
{
    private string $scenario;
    private string $token;
    private string $npsn;
    private array $dataset;
    private string $mockDir;

    public function __construct(string $scenario, string $token, string $npsn)
    {
        $this->scenario = $scenario !== '' ? $scenario : 'default';
        $this->token = $token;
        $this->npsn = $npsn !== '' ? $npsn : '00000000';
        $this->mockDir = __DIR__ . '/mock';
        $this->dataset = $this->loadDataset($this->scenario);
    }

    public function getSekolah(): array
    {
        $sekolah = $this->dataset['sekolah'] ?? [];
        if (!isset($sekolah['npsn'])) {
            $sekolah['npsn'] = $this->npsn;
        }
        if (!isset($sekolah['nama'])) {
            $sekolah['nama'] = 'Simulasi Sekolah';
        }
        return $sekolah;
    }

    public function getPesertaDidik(): array
    {
        return $this->dataset['peserta_didik'] ?? [];
    }

    public function getRombonganBelajar(): array
    {
        return $this->dataset['rombongan_belajar'] ?? [];
    }

    public function kirimNilai(array $payload): array
    {
        $file = $this->logPayload('kirim_nilai', $payload);
        return [
            'status' => 'simulated',
            'message' => 'Payload nilai disimpan dalam log simulasi.',
            'log_file' => $file,
            'timestamp' => date('c'),
            'jumlah_nilai' => isset($payload['nilai']) ? count((array)$payload['nilai']) : 0,
        ];
    }

    public function kirimMatev(array $payload): array
    {
        $file = $this->logPayload('kirim_matev', $payload);
        return [
            'status' => 'simulated',
            'message' => 'Payload matev disimpan dalam log simulasi.',
            'log_file' => $file,
            'timestamp' => date('c'),
        ];
    }

    private function loadDataset(string $scenario): array
    {
        $safeScenario = strtolower(preg_replace('/[^a-z0-9_\-]/i', '_', $scenario));
        $candidates = [];
        $candidates[] = $this->mockDir . '/' . $safeScenario . '.json';
        if ($safeScenario !== 'default') {
            $candidates[] = $this->mockDir . '/default.json';
        }

        foreach ($candidates as $path) {
            if (is_file($path)) {
                $json = file_get_contents($path);
                $decoded = json_decode($json, true);
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        return $this->buildFallbackDataset();
    }

    private function buildFallbackDataset(): array
    {
        $rombel7a = 'ROMBEL-7A';
        $rombel8a = 'ROMBEL-8A';

        return [
            'sekolah' => [
                'nama' => 'Simulasi Sekolah',
                'npsn' => $this->npsn,
                'alamat_jalan' => 'Jl. Simulasi No. 123',
            ],
            'peserta_didik' => [
                [
                    'peserta_didik_id' => 'PD-001',
                    'nama' => 'Adi Simulasi',
                    'nisn' => '1111111111',
                    'anggota_rombel_id' => 'AR-001',
                    'rombongan_belajar_id' => $rombel7a,
                ],
                [
                    'peserta_didik_id' => 'PD-002',
                    'nama' => 'Bela Simulasi',
                    'nisn' => '2222222222',
                    'anggota_rombel_id' => 'AR-002',
                    'rombongan_belajar_id' => $rombel7a,
                ],
                [
                    'peserta_didik_id' => 'PD-003',
                    'nama' => 'Cipto Simulasi',
                    'nisn' => '3333333333',
                    'anggota_rombel_id' => 'AR-003',
                    'rombongan_belajar_id' => $rombel8a,
                ],
            ],
            'rombongan_belajar' => [
                [
                    'rombongan_belajar_id' => $rombel7a,
                    'nama' => 'VII A',
                    'pembelajaran' => $this->defaultPembelajaran('VII A'),
                ],
                [
                    'rombongan_belajar_id' => $rombel8a,
                    'nama' => 'VIII A',
                    'pembelajaran' => $this->defaultPembelajaran('VIII A'),
                ],
            ],
        ];
    }

    private function defaultPembelajaran(string $kelas): array
    {
        return [
            [
                'pembelajaran_id' => 'PB-MAT-' . $kelas,
                'mata_pelajaran_id' => 'MP-MAT',
                'nama_mata_pelajaran' => 'Matematika',
                'ptk_id' => 'PTK-MAT-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-BI-' . $kelas,
                'mata_pelajaran_id' => 'MP-BI',
                'nama_mata_pelajaran' => 'Bahasa Indonesia',
                'ptk_id' => 'PTK-BI-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-BING-' . $kelas,
                'mata_pelajaran_id' => 'MP-BING',
                'nama_mata_pelajaran' => 'Bahasa Inggris',
                'ptk_id' => 'PTK-BING-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-IPA-' . $kelas,
                'mata_pelajaran_id' => 'MP-IPA',
                'nama_mata_pelajaran' => 'Ilmu Pengetahuan Alam (IPA)',
                'ptk_id' => 'PTK-IPA-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-IPAS-' . $kelas,
                'mata_pelajaran_id' => 'MP-IPAS',
                'nama_mata_pelajaran' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)',
                'ptk_id' => 'PTK-IPAS-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-PPKN-' . $kelas,
                'mata_pelajaran_id' => 'MP-PPKN',
                'nama_mata_pelajaran' => 'Pendidikan Pancasila (PPKn)',
                'ptk_id' => 'PTK-PPKN-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-PAI-' . $kelas,
                'mata_pelajaran_id' => 'MP-PAI',
                'nama_mata_pelajaran' => 'Pendidikan Agama Islam',
                'ptk_id' => 'PTK-PAI-' . $kelas,
            ],
            [
                'pembelajaran_id' => 'PB-PJOK-' . $kelas,
                'mata_pelajaran_id' => 'MP-PJOK',
                'nama_mata_pelajaran' => 'Pendidikan Jasmani Olahraga dan Kesehatan (PJOK)',
                'ptk_id' => 'PTK-PJOK-' . $kelas,
            ],
        ];
    }

    private function logPayload(string $type, array $payload): string
    {
        if (!is_dir($this->mockDir)) {
            @mkdir($this->mockDir, 0775, true);
        }
        $safeScenario = strtolower(preg_replace('/[^a-z0-9_\-]/i', '_', $this->scenario));
        $file = $this->mockDir . '/' . $safeScenario . '_' . $type . '_log.jsonl';
        $entry = [
            'timestamp' => date('c'),
            'type' => $type,
            'payload' => $payload,
        ];
        file_put_contents($file, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
        return $file;
    }
}
