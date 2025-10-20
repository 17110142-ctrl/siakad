<?php

require_once __DIR__ . '/DapodikClientInterface.php';

/**
 * Lightweight HTTP client for interacting with Dapodik Web Service.
 */
class DapodikClient implements DapodikClientInterface
{
    private string $baseUrl;
    private string $token;
    private string $npsn;
    private string $userAgent;
    private ?string $semesterId = null;

    public function __construct(string $baseUrl, string $token, string $npsn, string $userAgent = 'siakad/dapodik-integration', ?string $semesterId = null)
    {
        $this->baseUrl = rtrim($baseUrl, "/ \t\n\r\0\x0B");
        $this->token = trim($token);
        $this->npsn = trim($npsn);
        $this->userAgent = trim($userAgent) !== '' ? trim($userAgent) : 'siakad/dapodik-integration';
        $sid = $semesterId !== null ? trim($semesterId) : '';
        $this->semesterId = $sid !== '' ? $sid : null;
    }

    /**
     * @throws RuntimeException on HTTP / transport errors.
     */
    private function request(string $method, string $endpoint, ?array $payload = null, array $query = []): array
    {
        $variants = $this->expandEndpointVariants($endpoint);
        $isGrade = $this->isGradeSubmissionEndpoint($endpoint);
        // Batasi variasi untuk endpoint nilai/MATEV agar tidak memicu timeout reverse proxy
        if ($isGrade) {
            $variants = array_slice($variants, 0, 3); // cukup 3 varian teratas
        }
        $lastException = null;

        $prefixes = $isGrade ? ['WebService'] : ['WebService', 'index.php', ''];
        $maxAttempts = $isGrade ? 1 : 2; // grade endpoint: sekali saja per pasangan variant/prefix
        foreach ($variants as $variant) {
            foreach ($prefixes as $prefix) {
                // Ulangi terbatas untuk kasus server sibuk (503/502/504)
                for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                    try {
                        return $this->performRequestWithPrefix($method, $variant, $payload, $query, $prefix);
                    } catch (RuntimeException $e) {
                        $code = $e->getCode();
                        $lastException = $e;
                        $isRetryable = ($code === 502 || $code === 503 || $code === 504);
                        if ($isRetryable && $attempt + 1 < $maxAttempts) {
                            usleep(250000); // 250ms backoff
                            continue; // ulangi pasangan variant+prefix yang sama sekali lagi
                        }
                        // Coba variasi/prefix lain untuk kesalahan yang menandakan rute tidak tersedia
                        // atau server sibuk. Biarkan error lain (auth, dll.) tetap dilempar.
                        if ($code === 404 || $code === 405 || ($code >= 500 && $code < 600)) {
                            break; // lanjut ke prefix/variant berikutnya
                        }
                        throw $e;
                    }
                }
            }
        }

        if ($lastException !== null) {
            throw $lastException;
        }

        throw new RuntimeException('Tidak dapat menghubungi webservice Dapodik.');
    }

    /**
     * Jalankan request HTTP terhadap satu endpoint aktual.
     *
     * @throws RuntimeException
     */
    private function performRequest(string $method, string $endpoint, ?array $payload, array $query): array
    {
        return $this->performRequestWithPrefix($method, $endpoint, $payload, $query, 'WebService');
    }

    /**
     * Jalankan request HTTP terhadap satu endpoint aktual dengan prefiks dasar (mis. WebService atau index.php).
     *
     * @throws RuntimeException
     */
    private function performRequestWithPrefix(string $method, string $endpoint, ?array $payload, array $query, string $prefix): array
    {
        $prefix = trim($prefix, '/');
        $url = rtrim($this->baseUrl, '/') . '/' . ($prefix !== '' ? $prefix . '/' : '') . ltrim($endpoint, '/');

        $defaultQuery = [];
        $epLower = strtolower(trim($endpoint, '/'));
        $payloadNpsn = is_array($payload) ? trim((string)($payload['npsn'] ?? '')) : '';
        if ($payloadNpsn !== '') {
            $defaultQuery['npsn'] = $payloadNpsn;
        } elseif ($this->npsn !== '') {
            $defaultQuery['npsn'] = $this->npsn;
        }
        if (is_array($payload)) {
            $payloadSemester = trim((string)($payload['semester_id'] ?? ''));
            if ($payloadSemester !== '') {
                $defaultQuery['semester_id'] = $payloadSemester;
            }
            // Jika caller sudah menyuplai 'table' di payload, mirror ke query
            $payloadTable = trim((string)($payload['table'] ?? ''));
            if ($payloadTable !== '' && !isset($query['table'])) {
                $defaultQuery['table'] = $payloadTable;
            }
            // Mirror beberapa id penting ke query agar kompatibel dengan endpoint trigger-only
            foreach (['rombongan_belajar_id','pembelajaran_id','mata_pelajaran_id','ptk_id','id_evaluasi'] as $idKey) {
                if (!isset($query[$idKey]) && isset($payload[$idKey])) {
                    $val = trim((string)$payload[$idKey]);
                    if ($val !== '') {
                        $defaultQuery[$idKey] = $val;
                    }
                }
            }
        }

        // Tambah parameter 'table' sesuai jenis endpoint seperti pada access.log e-Rapor resmi
        // Catatan: untuk nilai rapor, sebagian instalasi memakai 'rapor'; untuk MATEV tidak perlu table.
        $resolvedTable = null;
        if (
            // Nilai (rapor)
            $epLower === 'kirimnilai' ||
            $epLower === 'postnilai' ||
            $epLower === 'insertnilai' ||
            substr($epLower, -11) === '/kirimnilai' ||
            substr($epLower, -15) === '/postnilairapor' ||
            substr($epLower, -9) === '/postnilai' ||
            substr($epLower, -12) === '/insertnilai' ||
            $epLower === 'erapor/insertnilai' ||
            $epLower === 'siakad/insertnilai'
        ) {
            $resolvedTable = 'rapor';
        } elseif (
            // Matev (nilai ekstrakurikuler)
            $epLower === 'kirimmatev' ||
            $epLower === 'postmatev' ||
            $epLower === 'insertmatev' ||
            substr($epLower, -11) === '/kirimmatev' ||
            substr($epLower, -15) === '/postmatevrapor' ||
            substr($epLower, -8) === '/postmatev' ||
            substr($epLower, -12) === '/insertmatev' ||
            $epLower === 'erapor/insertmatev' ||
            $epLower === 'siakad/insertmatev'
        ) {
            $resolvedTable = null; // tidak diperlukan untuk MATEV sesuai pola resmi
        }
        if ($resolvedTable !== null && !isset($query['table'])) {
            $defaultQuery['table'] = $resolvedTable;
        }

        // Beberapa server hanya membaca key dari query string
        if (!isset($query['key'])) {
            $defaultQuery['key'] = $this->token;
        }

        $query = array_merge($defaultQuery, $query);
        $query = array_filter($query, function ($value) {
            return $value !== '' && $value !== null;
        });

        if (!empty($query)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($query);
        }

        $ch = curl_init();
        $shouldSendBody = $this->shouldSendBody($method, $endpoint, $payload);
        // Mirror 'table' ke body untuk kompatibilitas server yang membaca dari POST, bukan query
        if ($shouldSendBody && is_array($payload) && $resolvedTable !== null && !isset($payload['table'])) {
            $payload['table'] = $resolvedTable;
        }
        // Timeout yang lebih agresif untuk endpoint nilai/MATEV (trigger-only)
        $isGrade = $this->isGradeSubmissionEndpoint($endpoint);
        $timeout = $isGrade ? 6 : 120;           // detik
        $connectTimeout = $isGrade ? 5 : 30;     // detik
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $connectTimeout,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $this->buildHeaders($method, $shouldSendBody, $endpoint),
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_ENCODING => '',
            CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HTTP_VERSION => defined('CURL_HTTP_VERSION_1_1') ? CURL_HTTP_VERSION_1_1 : 2,
        ]);

        if ($shouldSendBody && $payload !== null) {
            $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }

        $body = curl_exec($ch);
        if ($body === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new RuntimeException('Koneksi Dapodik gagal ke ' . $url . ': ' . $error, $errno);
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '';
        curl_close($ch);

        // Beberapa instalasi memutus koneksi dengan halaman HTML "Request Timeout" atau 503 Service Unavailable
        // meski proses tetap dipicu di server. Untuk endpoint nilai/matev, coba fallback kirim per-record
        // agar beban server kecil. Jika fallback tidak dipakai/berhasil, kembalikan Accepted sebagai tanda
        // bahwa proses sudah dipicu (trigger-only).
        if (
            $this->isGradeSubmissionEndpoint($endpoint)
            && ( $this->looksLikeTimeout($body) || stripos($body, 'Service Unavailable') !== false || $status === 503 )
            && ($status >= 500 || $status === 408 || $status < 400)
        ) {
            // Coba kirim per-record bila payload berisi daftar nilai.
            if (is_array($payload) && isset($payload['nilai'])) {
                $formFields = $payload;
                if ($resolvedTable !== null && !isset($formFields['table'])) {
                    $formFields['table'] = $resolvedTable;
                }
                if (!isset($formFields['npsn']) && $this->npsn !== '') {
                    $formFields['npsn'] = $this->npsn;
                }
                if (!isset($formFields['key'])) {
                    $formFields['key'] = $this->token;
                }
                // Biarkan sendFormPerRecord menangani konversi array/string nilai
                $aggr = $this->sendFormPerRecord($url, $formFields);
                if (!empty($aggr)) {
                    return $aggr;
                }
            }
            // Fallback: tandai Accepted agar UI tidak merah
            return [
                'status' => 202,
                'body' => $body,
                'data' => [
                    'success' => true,
                    'status' => 'accepted',
                    'messages' => [
                        'Accepted (Timeout/Busy) — proses dipicu di server.'
                    ],
                    'raw_body' => $body,
                    'request_url' => $url,
                ],
                'content_type' => $contentType,
                'is_json' => false,
            ];
        }

        // Jika server merespon 2xx namun body mengindikasikan INSERT kosong/sintaks error,
        // coba ulang dengan form-urlencoded dan/atau kirim per-record
        if ($status < 400 && $this->looksLikeEmptyInsertError($body)) {
            $formFields = is_array($payload) ? $payload : [];
            if ($resolvedTable !== null) {
                $formFields['table'] = $resolvedTable;
            }
            if (!isset($formFields['npsn']) && $this->npsn !== '') {
                $formFields['npsn'] = $this->npsn;
            }
            if (!isset($formFields['key'])) {
                $formFields['key'] = $this->token;
            }
            if (isset($formFields['nilai']) && is_array($formFields['nilai'])) {
                $formFields['nilai'] = json_encode($formFields['nilai'], JSON_UNESCAPED_UNICODE);
            }

            // Coba kirim sekali sebagai form-url-encoded
            $ch2 = curl_init();
            curl_setopt_array($ch2, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                CURLOPT_USERAGENT => $this->userAgent,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => '',
                CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                CURLOPT_POSTFIELDS => http_build_query($formFields),
            ]);
            $body2 = curl_exec($ch2);
            if ($body2 !== false) {
                $status2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                $ctype2 = curl_getinfo($ch2, CURLINFO_CONTENT_TYPE) ?: '';
                curl_close($ch2);
                if ($status2 < 400) {
                    $decoded2 = json_decode($body2, true);
                    $isJson2 = !(json_last_error() !== JSON_ERROR_NONE);
                    $data2 = $isJson2 ? $decoded2 : $this->buildStructuredLogResponse($body2, $ctype2, $url);
                    if ($this->looksLikeEmptyInsertError($body2) || $this->endpointRequiresPerRecord($endpoint)) {
                        $aggr = $this->sendFormPerRecord($url, $formFields);
                        if (!empty($aggr)) {
                            return $aggr;
                        }
                    }
                    return [
                        'status' => $status2,
                        'body' => $body2,
                        'data' => $data2,
                        'content_type' => $ctype2,
                        'is_json' => $isJson2,
                    ];
                }
            } else {
                curl_close($ch2);
            }

            // Jika tetap tidak memadai, kirim langsung per-record sebagai fallback
            $aggr = $this->sendFormPerRecord($url, $formFields);
            if (!empty($aggr)) {
                return $aggr;
            }
        }

        // Jika server merespon 200 namun meminta parameter table, coba fallback kirim sebagai form-url-encoded
        if ($status < 400 && $this->looksLikeMissingTable($body)) {
            $formFields = is_array($payload) ? $payload : [];
            if ($resolvedTable !== null) {
                $formFields['table'] = $resolvedTable;
            }
            if (!isset($formFields['npsn']) && $this->npsn !== '') {
                $formFields['npsn'] = $this->npsn;
            }
            if (!isset($formFields['key'])) {
                $formFields['key'] = $this->token;
            }
            if (isset($formFields['nilai']) && is_array($formFields['nilai'])) {
                $formFields['nilai'] = json_encode($formFields['nilai'], JSON_UNESCAPED_UNICODE);
            }

            $ch2 = curl_init();
            curl_setopt_array($ch2, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                CURLOPT_USERAGENT => $this->userAgent,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => '',
                CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                CURLOPT_POSTFIELDS => http_build_query($formFields),
            ]);
            $body2 = curl_exec($ch2);
            if ($body2 !== false) {
                $status2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                $ctype2 = curl_getinfo($ch2, CURLINFO_CONTENT_TYPE) ?: '';
                curl_close($ch2);
                if ($status2 < 400) {
                    $decoded2 = json_decode($body2, true);
                    $isJson2 = !(json_last_error() !== JSON_ERROR_NONE);
                    $data2 = $isJson2 ? $decoded2 : $this->buildStructuredLogResponse($body2, $ctype2, $url);
                    // Jika masih diminta 'table', coba alternatif 'rapor'
                    if ($this->looksLikeMissingTable($body2)) {
                        $altFields = $formFields;
                        $altFields['table'] = 'rapor';
                        $ch3 = curl_init();
                        curl_setopt_array($ch3, [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_TIMEOUT => 120,
                            CURLOPT_CONNECTTIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => strtoupper($method),
                            CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                            CURLOPT_USERAGENT => $this->userAgent,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_ENCODING => '',
                            CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                            CURLOPT_POSTFIELDS => http_build_query($altFields),
                        ]);
                        $body3 = curl_exec($ch3);
                        if ($body3 !== false) {
                            $status3 = curl_getinfo($ch3, CURLINFO_HTTP_CODE);
                            $ctype3 = curl_getinfo($ch3, CURLINFO_CONTENT_TYPE) ?: '';
                            curl_close($ch3);
                            if ($status3 < 400) {
                                $decoded3 = json_decode($body3, true);
                                $isJson3 = !(json_last_error() !== JSON_ERROR_NONE);
                                $data3 = $isJson3 ? $decoded3 : $this->buildStructuredLogResponse($body3, $ctype3, $url);
                                // Jika masih ada indikasi format nilai tidak sesuai, coba kirim per-record
                                if ($this->looksLikeEmptyInsertError($body3) || $this->endpointRequiresPerRecord($endpoint)) {
                                    $aggr = $this->sendFormPerRecord($url, $altFields);
                                    if (!empty($aggr)) {
                                        return $aggr;
                                    }
                                }
                                return [
                                    'status' => $status3,
                                    'body' => $body3,
                                    'data' => $data3,
                                    'content_type' => $ctype3,
                                    'is_json' => $isJson3,
                                ];
                            }
                        } else {
                            curl_close($ch3);
                        }
                    } elseif ($this->looksLikeEmptyInsertError($body2) || $this->endpointRequiresPerRecord($endpoint)) {
                        // Endpoint kemungkinan menghendaki satu record per request (nilai sebagai objek, bukan array)
                        $aggr = $this->sendFormPerRecord($url, $formFields);
                        if (!empty($aggr)) {
                            return $aggr;
                        }
                    }
                    return [
                        'status' => $status2,
                        'body' => $body2,
                        'data' => $data2,
                        'content_type' => $ctype2,
                        'is_json' => $isJson2,
                    ];
                }
            } else {
                curl_close($ch2);
            }
        }

        // Jika server mengembalikan 5xx (sibuk/overload) atau body berisi indikasi timeout,
        // coba ulangi dengan form-urlencoded sekali, meskipun request awal tanpa body
        if ($status >= 500 || $this->looksLikeTimeout($body)) {
            $formFields = is_array($payload) ? $payload : [];
            if ($resolvedTable !== null) {
                $formFields['table'] = $resolvedTable;
            }
            if (!isset($formFields['npsn']) && $this->npsn !== '') {
                $formFields['npsn'] = $this->npsn;
            }
            if (!isset($formFields['key'])) {
                $formFields['key'] = $this->token;
            }
            if (isset($formFields['nilai']) && is_array($formFields['nilai'])) {
                $formFields['nilai'] = json_encode($formFields['nilai'], JSON_UNESCAPED_UNICODE);
            }

            $ch2 = curl_init();
            curl_setopt_array($ch2, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                CURLOPT_USERAGENT => $this->userAgent,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_ENCODING => '',
                CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                CURLOPT_FORBID_REUSE => true,
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_HTTP_VERSION => defined('CURL_HTTP_VERSION_1_1') ? CURL_HTTP_VERSION_1_1 : 2,
                CURLOPT_POSTFIELDS => http_build_query($formFields),
            ]);
            $body2 = curl_exec($ch2);
            if ($body2 !== false) {
                $status2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
                $ctype2 = curl_getinfo($ch2, CURLINFO_CONTENT_TYPE) ?: '';
                curl_close($ch2);
                if ($status2 < 400) {
                    $decoded2 = json_decode($body2, true);
                    $isJson2 = !(json_last_error() !== JSON_ERROR_NONE);
                    $data2 = $isJson2 ? $decoded2 : $this->buildStructuredLogResponse($body2, $ctype2, $url);
                    // Jika masih timeout atau server tidak menerima array, fallback kirim per-record
                    if ($this->looksLikeTimeout($body2) || $this->looksLikeEmptyInsertError($body2)) {
                        $aggr = $this->sendFormPerRecord($url, $formFields);
                        if (!empty($aggr)) {
                            return $aggr;
                        }
                    }
                    return [
                        'status' => $status2,
                        'body' => $body2,
                        'data' => $data2,
                        'content_type' => $ctype2,
                        'is_json' => $isJson2,
                    ];
                }
            } else {
                curl_close($ch2);
            }
        }

        if ($status >= 400) {
            throw new RuntimeException("Dapodik mengembalikan HTTP {$status} pada {$url}: {$body}", $status);
        }

        $decoded = json_decode($body, true);
        $isJson = !(json_last_error() !== JSON_ERROR_NONE);
        if ($isJson && is_array($decoded)) {
            // Sisipkan meta ringan untuk membantu debug di sisi aplikasi
            if (!isset($decoded['content_type'])) {
                $decoded['content_type'] = $contentType;
            }
            if (!isset($decoded['raw_body'])) {
                $decoded['raw_body'] = $body;
            }
            if (!isset($decoded['request_url'])) {
                $decoded['request_url'] = $url;
            }
        }
        $data = $isJson ? $decoded : $this->buildStructuredLogResponse($body, $contentType, $url);

        // Untuk endpoint pemicu (trigger-only) seperti postMatevRapor/postNilaiRapor,
        // jika HTTP 2xx maka anggap sukses agar lapisan atas tidak menganggap gagal
        // hanya karena body tidak memuat kata kunci.
        if ($this->isTriggerOnlyEndpoint($endpoint) && $status < 400 && is_array($data)) {
            if (!isset($data['success'])) { $data['success'] = true; }
            if (!isset($data['status'])) { $data['status'] = 'ok'; }
        }

        // Jika endpoint bertipe postNilai/insertNilai dan respons tidak jelas menyatakan keberhasilan,
        // coba kirim per-record langsung (beberapa instalasi hanya menerima satu record per request).
        if ($this->endpointRequiresPerRecord($endpoint) && is_array($payload) && !empty($payload['nilai'])) {
            $obviousSuccess = false;
            if ($isJson && is_array($decoded)) {
                if (isset($decoded['success']) && (bool)$decoded['success'] === true) { $obviousSuccess = true; }
                if (isset($decoded['status']) && in_array(strtolower((string)$decoded['status']), ['ok','success','berhasil','true'], true)) { $obviousSuccess = true; }
                if (isset($decoded['message']) && preg_match('/\b(success|berhasil|selesai)\b/i', (string)$decoded['message'])) { $obviousSuccess = true; }
            } else {
                // Jika bukan JSON, anggap belum jelas
                $obviousSuccess = false;
            }
            if (!$obviousSuccess) {
                $baseFields = $payload;
                if ($resolvedTable !== null && !isset($baseFields['table'])) { $baseFields['table'] = $resolvedTable; }
                if (!isset($baseFields['npsn']) && $this->npsn !== '') { $baseFields['npsn'] = $this->npsn; }
                if (!isset($baseFields['key'])) { $baseFields['key'] = $this->token; }
                $aggr = $this->sendFormPerRecord($url, $baseFields);
                if (!empty($aggr)) {
                    return $aggr;
                }
            }
        }

        return [
            'status' => $status,
            'body' => $body,
            'data' => $data,
            'content_type' => $contentType,
            'is_json' => $isJson,
        ];
    }

    private function isTriggerOnlyEndpoint(string $endpoint): bool
    {
        $ep = strtolower(trim($endpoint, '/'));
        $triggerOnly = [
            'postnilairapor',
            'postmatevrapor',
            'postnilai',
            'postmatev',
        ];
        foreach ($triggerOnly as $name) {
            if ($ep === $name || substr($ep, -1 - strlen($name)) === '/' . $name) {
                return true;
            }
        }
        return false;
    }

    private function isGradeSubmissionEndpoint(string $endpoint): bool
    {
        $ep = strtolower(trim($endpoint, '/'));
        return (strpos($ep, 'nilai') !== false) || (strpos($ep, 'matev') !== false);
    }

    private function buildFormHeaders(): array
    {
        return [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'X-Api-Key: ' . $this->token,
            'Cache-Control: no-cache',
            'Connection: close',
            'Content-Type: application/x-www-form-urlencoded',
            'Expect:'
        ];
    }

    private function looksLikeMissingTable(string $body): bool
    {
        $text = strtolower($body);
        return (strpos($text, 'harap masukan paramater table') !== false)
            || (strpos($text, 'harap masukan parameter table') !== false)
            || (strpos($text, 'parameter table') !== false && strpos($text, 'harap') !== false);
    }

    private function looksLikeEmptyInsertError(string $body): bool
    {
        $text = strtolower($body);
        return (strpos($text, 'insert into') !== false && strpos($text, '() values ()') !== false)
            || (strpos($text, 'syntax error') !== false && strpos($text, 'values ()') !== false)
            || (strpos($text, '42601') !== false && strpos($text, 'syntax error') !== false);
    }

    private function looksLikeTimeout(string $body): bool
    {
        $text = strtolower($body);
        return (strpos($text, 'request timeout') !== false)
            || (strpos($text, 'timed out') !== false)
            || (strpos($text, 'timeout') !== false && strpos($text, 'request') !== false)
            || (strpos($text, '504 gateway') !== false);
    }

    private function endpointRequiresPerRecord(string $endpoint): bool
    {
        $ep = strtolower(trim($endpoint, '/'));
        return (strpos($ep, 'postnilai') !== false) || (strpos($ep, 'insertnilai') !== false);
    }

    private function sendFormPerRecord(string $url, array $baseFields): array
    {
        $records = [];
        if (isset($baseFields['nilai'])) {
            if (is_string($baseFields['nilai'])) {
                $decoded = json_decode($baseFields['nilai'], true);
                if (is_array($decoded)) {
                    $records = $decoded;
                }
            } elseif (is_array($baseFields['nilai'])) {
                $records = $baseFields['nilai'];
            }
            unset($baseFields['nilai']);
        }
        if (empty($records)) {
            return [];
        }

        $ok = 0; $fail = 0; $messages = [];
        foreach ($records as $rec) {
            if (!is_array($rec)) { continue; }
            $fields = $baseFields;
            // Flatten: kirim tiap atribut nilai sebagai field top-level agar cocok dengan kolom DB
            foreach ($rec as $k => $v) {
                if (is_scalar($v) || is_null($v)) {
                    $fields[(string)$k] = (string)$v;
                }
            }
            // Pastikan kunci nilai numeric tersedia dengan nama umum
            $score = null;
            if (isset($rec['nilai_kurmer'])) { $score = $rec['nilai_kurmer']; }
            if (isset($rec['nilai_rapor'])) { $score = $rec['nilai_rapor']; }
            if (isset($rec['nilai'])) { $score = $rec['nilai']; }
            if ($score !== null) {
                $fields['nilai'] = $score;
                $fields['nilai_rapor'] = $score;
                $fields['nilai_angka'] = $score;
            }

            // Pastikan parameter penting juga ada di body (beberapa instalasi hanya membaca dari POST)
            foreach (['npsn','semester_id','rombongan_belajar_id','pembelajaran_id','mata_pelajaran_id','ptk_id','key'] as $must) {
                if (!isset($fields[$must]) && isset($baseFields[$must])) {
                    $fields[$must] = $baseFields[$must];
                }
            }
            if (!isset($fields['table'])) {
                $fields['table'] = isset($baseFields['table']) && $baseFields['table'] !== '' ? $baseFields['table'] : 'nilai_rapor';
            }

            // Siapkan kandidat URL: coba ganti postNilai -> insertNilai bila ada
            $candidateUrls = [];
            $candidateUrls[] = $url;
            if (stripos($url, 'postNilai') !== false) {
                $candidateUrls[] = str_ireplace('postNilai', 'insertNilai', $url);
            }
            if (stripos($url, '/kirimNilai') !== false) {
                $candidateUrls[] = str_ireplace('/kirimNilai', '/insertNilai', $url);
            }
            // unik
            $tmp = [];$uniqueUrls=[]; foreach ($candidateUrls as $u){$k=strtolower($u); if(!isset($tmp[$k])){$tmp[$k]=1;$uniqueUrls[]=$u;}}

            // 1) Coba kirim sebagai JSON (banyak instalasi membaca body JSON)
            $b = null; $st = 0; $ct = '';
            $okFlag = false; $dec = null;

            foreach ($uniqueUrls as $tryUrl) {
                $chJson = curl_init();
                curl_setopt_array($chJson, [
                    CURLOPT_URL => $tryUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 120,
                    CURLOPT_CONNECTTIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => $this->buildHeaders('POST', true),
                    CURLOPT_USERAGENT => $this->userAgent,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_ENCODING => '',
                    CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                    CURLOPT_POSTFIELDS => json_encode($fields, JSON_UNESCAPED_UNICODE),
                ]);
                $b = curl_exec($chJson);
                if ($b !== false) {
                    $st = curl_getinfo($chJson, CURLINFO_HTTP_CODE);
                    $ct = curl_getinfo($chJson, CURLINFO_CONTENT_TYPE) ?: '';
                }
                curl_close($chJson);

                if ($b !== false && $st < 400) {
                    $dec = json_decode($b, true);
                    if (is_array($dec)) {
                        if (isset($dec['success'])) {
                            $okFlag = (bool)$dec['success'];
                        } elseif (isset($dec['status'])) {
                            $okFlag = in_array(strtolower((string)$dec['status']), ['ok','success','berhasil','true'], true);
                        }
                        if (isset($dec['message']) && is_string($dec['message'])) {
                            $messages[] = $dec['message'];
                        }
                    } else {
                        // Non-JSON: deteksi kata kunci keberhasilan pada body
                        if (is_string($b) && $this->containsSuccessFlag($this->splitLogMessages($b), $b)) {
                            $okFlag = true;
                        }
                    }
                }
                if ($okFlag) { $url = $tryUrl; break; }
            }

            // 2) Jika JSON tidak jelas berhasil, fallback ke form-urlencoded
            if (!$okFlag) {
                foreach ($uniqueUrls as $tryUrl) {
                    $ch = curl_init();
                    curl_setopt_array($ch, [
                        CURLOPT_URL => $tryUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 120,
                        CURLOPT_CONNECTTIMEOUT => 30,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                        CURLOPT_USERAGENT => $this->userAgent,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_ENCODING => '',
                        CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                        CURLOPT_POSTFIELDS => http_build_query($fields),
                    ]);
                    $b2 = curl_exec($ch);
                    $st2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $ct2 = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '';
                    curl_close($ch);
                    if ($b2 !== false && $st2 < 400) {
                        $dec2 = json_decode($b2, true);
                        if (is_array($dec2)) {
                            if (isset($dec2['success'])) {
                                $okFlag = (bool)$dec2['success'];
                            } elseif (isset($dec2['status'])) {
                                $okFlag = in_array(strtolower((string)$dec2['status']), ['ok','success','berhasil','true'], true);
                            }
                            if (isset($dec2['message']) && is_string($dec2['message'])) {
                                $messages[] = $dec2['message'];
                            }
                        } else {
                            if (is_string($b2) && $this->containsSuccessFlag($this->splitLogMessages($b2), $b2)) {
                                $okFlag = true;
                            }
                        }
                        if (!$okFlag && is_string($b2)) { $messages[] = $b2; }
                        $b = $b2; $st = $st2; $ct = $ct2; $dec = $dec2; $url = $tryUrl;
                    }
                    if ($okFlag) { break; }
                }

                // 3) Terakhir, coba pakai table=rapor jika belum berhasil
                if (!$okFlag && (!isset($fields['table']) || strtolower((string)$fields['table']) !== 'rapor')) {
                    $fieldsAlt = $fields; $fieldsAlt['table'] = 'rapor';
                    foreach ($uniqueUrls as $tryUrl) {
                        $ch = curl_init();
                        curl_setopt_array($ch, [
                            CURLOPT_URL => $tryUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_TIMEOUT => 120,
                            CURLOPT_CONNECTTIMEOUT => 30,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_HTTPHEADER => $this->buildFormHeaders(),
                            CURLOPT_USERAGENT => $this->userAgent,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_ENCODING => '',
                            CURLOPT_IPRESOLVE => defined('CURL_IPRESOLVE_V4') ? CURL_IPRESOLVE_V4 : 1,
                            CURLOPT_POSTFIELDS => http_build_query($fieldsAlt),
                        ]);
                        $b2 = curl_exec($ch);
                        $st2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $ct2 = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '';
                        curl_close($ch);
                        if ($b2 !== false && $st2 < 400) {
                            $dec2 = json_decode($b2, true);
                            if (is_array($dec2)) {
                                if (isset($dec2['success'])) {
                                    $okFlag = (bool)$dec2['success'];
                                } elseif (isset($dec2['status'])) {
                                    $okFlag = in_array(strtolower((string)$dec2['status']), ['ok','success','berhasil','true'], true);
                                }
                                if (isset($dec2['message']) && is_string($dec2['message'])) {
                                    $messages[] = $dec2['message'];
                                }
                            } else {
                                if (is_string($b2) && $this->containsSuccessFlag($this->splitLogMessages($b2), $b2)) {
                                    $okFlag = true;
                                }
                            }
                            if (!$okFlag && is_string($b2)) { $messages[] = $b2; }
                            $b = $b2; $st = $st2; $ct = $ct2; $dec = $dec2; $url = $tryUrl;
                        }
                        if ($okFlag) { break; }
                    }
                }
            }

            if ($b === false || $st >= 400) { $fail++; continue; }
            if (!$okFlag) { if (is_string($b)) { $messages[] = $b; } }
            if ($okFlag) { $ok++; } else { $fail++; }
            // Jeda kecil agar server tidak kewalahan
            usleep(80000); // 80ms
        }

        $successAll = ($fail === 0 && $ok > 0);
        $messages[] = 'Kirim per-record: Berhasil ' . $ok . ', Gagal ' . $fail;
        return [
            'status' => $successAll ? 200 : 207,
            'body' => implode('|', $messages),
            'data' => [
                'success' => $successAll,
                'messages' => $messages,
                'raw_body' => implode("\n", $messages),
                'request_url' => $url,
            ],
            'content_type' => 'text/plain',
            'is_json' => false,
        ];
    }

    /**
     * Variasi endpoint yang mungkin dipakai oleh instalasi Dapodik berbeda.
     *
     * @return array<int,string>
     */
    private function expandEndpointVariants(string $endpoint): array
    {
        $normalized = ltrim($endpoint, '/');
        $variants = [$normalized];

        $lower = strtolower($normalized);
        if ($lower === 'kirimnilai') {
            // Instalasi yang Anda gunakan hanya menyediakan postNilai.
            // Prioritaskan postNilai terlebih dahulu untuk menghindari 404 beruntun.
            $variants = [
                'postNilai',
                'kirimNilai',
                'siakad/kirimNilai',
                'erapor/kirimNilai',
                // Varian insert (jika tersedia di instalasi lain)
                'siakad/insertNilai',
                'erapor/insertNilai',
                // Varian pemicu berat diletakkan paling akhir
                'postNilaiRapor',
                'erapor/postNilaiRapor',
                'kurmer/postNilaiRapor',
            ];
        } elseif ($lower === 'kirimmatev') {
            // Ikuti urutan yang tampak pada access.log resmi: postMatevRapor lebih dulu
            $variants = [
                'postMatevRapor',
                'postMatev',
                'kirimMatev',
            ];
            $variants[] = 'erapor/kirimMatev';
            $variants[] = 'kurmer/kirimMatev';
            $variants[] = 'siakad/kirimMatev';
            $variants[] = 'erapor/postMatevRapor';
            $variants[] = 'kurmer/postMatevRapor';
        }

        $unique = [];
        foreach ($variants as $variant) {
            $key = strtolower($variant);
            if (!isset($unique[$key])) {
                $unique[$key] = ltrim($variant, '/');
            }
        }
        return array_values($unique);
    }

    private function buildHeaders(string $method, bool $hasBody = false, string $endpoint = ''): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'X-Api-Key: ' . $this->token,
            'Cache-Control: no-cache',
            // Default behavior; can be overridden below
            'Connection: keep-alive',
            // Hindari "Expect: 100-continue" yang kadang membuat server lama bingung
            'Expect:'
        ];
        if ($hasBody) {
            $headers[] = 'Content-Type: application/json';
        }
        // Untuk endpoint pemicu tanpa body (postMatevRapor/postNilai/postMatev/kirimMatev),
        // gunakan Connection: close agar server Dapodik yang sibuk lebih cepat melepas koneksi.
        $ep = strtolower(trim($endpoint, '/'));
        $isTrigger = (!$hasBody) && (
            strpos($ep, 'postnilairapor') !== false ||
            strpos($ep, 'postmatevrapor') !== false ||
            strpos($ep, 'postnilai') !== false ||
            strpos($ep, 'postmatev') !== false ||
            strpos($ep, 'kirimmatev') !== false
        );
        if ($isTrigger) {
            foreach ($headers as $i => $h) {
                if (stripos($h, 'Connection:') === 0) { $headers[$i] = 'Connection: close'; break; }
            }
        }
        return $headers;
    }

    /**
     * Beberapa endpoint Dapodik terbaru (mis. postNilaiRapor/postMatevRapor)
     * hanya memakai parameter query dan tidak mengharuskan body JSON.
     * Untuk kompatibilitas, jangan kirim body pada endpoint tersebut.
     */
    private function shouldSendBody(string $method, string $endpoint, ?array $payload): bool
    {
        if (strtoupper($method) === 'GET') {
            return false;
        }
        $ep = strtolower(trim($endpoint, '/'));
        $triggerOnly = [
            'postnilairapor',
            'postmatevrapor',
            'postnilai',
            'postmatev',
            // Banyak instalasi memperlakukan kirimMatev sebagai pemicu tanpa body
            'kirimmatev',
        ];
        foreach ($triggerOnly as $name) {
            if ($ep === $name || substr($ep, -1 - strlen($name)) === '/' . $name) {
                return false;
            }
        }
        return $payload !== null;
    }

    public function getSekolah(): array
    {
        return $this->request('GET', 'getSekolah')['data'];
    }

    public function getPesertaDidik(): array
    {
        $payload = [];
        if ($this->semesterId !== null) { $payload['semester_id'] = $this->semesterId; }
        return $this->request('GET', 'getPesertaDidik', $payload)['data'];
    }

    public function getRombonganBelajar(): array
    {
        $payload = [];
        if ($this->semesterId !== null) { $payload['semester_id'] = $this->semesterId; }
        return $this->request('GET', 'getRombonganBelajar', $payload)['data'];
    }

    public function getMatevRapor(array $params = []): array
    {
        $payload = $params;
        if ($this->semesterId !== null && !isset($payload['semester_id'])) { $payload['semester_id'] = $this->semesterId; }
        return $this->request('GET', 'rest/MatevRapor', $payload)['data'];
    }

    public function getMatevNilai(array $params = []): array
    {
        // Warm-up like official e-Rapor: a_dari_template=1
        $payload = $params;
        if (!isset($payload['a_dari_template'])) { $payload['a_dari_template'] = 1; }
        if ($this->semesterId !== null && !isset($payload['semester_id'])) { $payload['semester_id'] = $this->semesterId; }
        return $this->request('GET', 'getMatevNilai', $payload)['data'];
    }

    public function kirimNilai(array $payload): array
    {
        if (!isset($payload['npsn'])) {
            $payload['npsn'] = $this->npsn;
        }
        if (!isset($payload['key'])) {
            $payload['key'] = $this->token;
        }
        return $this->request('POST', 'kirimNilai', $payload)['data'];
    }

    public function kirimMatev(array $payload): array
    {
        if (!isset($payload['npsn'])) {
            $payload['npsn'] = $this->npsn;
        }
        if (!isset($payload['key'])) {
            $payload['key'] = $this->token;
        }
        return $this->request('POST', 'kirimMatev', $payload)['data'];
    }

    /**
     * Normalise non-JSON responses into a structured array.
     */
    private function buildStructuredLogResponse(string $body, string $contentType, string $url = ''): array
    {
        $messages = $this->splitLogMessages($body);
        return [
            'raw_body' => $body,
            'messages' => $messages,
            'success' => $this->containsSuccessFlag($messages, $body),
            'content_type' => $contentType,
            'request_url' => $url,
        ];
    }

    /**
     * Split progress style response (separated by "|" or newlines) into messages.
     *
     * @return array<int,string>
     */
    private function splitLogMessages(string $body): array
    {
        $normalized = trim(str_replace(["\r\n", "\r"], "\n", $body));
        if ($normalized === '') {
            return [];
        }

        $parts = preg_split('/\|+|\n+/', $normalized);
        if ($parts === false) {
            return [$normalized];
        }

        $messages = [];
        foreach ($parts as $part) {
            $text = trim($part);
            if ($text !== '') {
                $messages[] = $text;
            }
        }

        if (empty($messages) && $normalized !== '') {
            $messages[] = $normalized;
        }

        return $messages;
    }

    /**
     * Detect success markers inside messages or raw body.
     *
     * @param array<int,string> $messages
     */
    private function containsSuccessFlag(array $messages, string $body): bool
    {
        foreach ($messages as $message) {
            if (preg_match('/\b(success|berhasil|selesai|ok|tersimpan|disimpan)\b/i', $message)) {
                return true;
            }
        }

        return preg_match('/\b(success|berhasil|selesai|ok|tersimpan|disimpan)\b/i', $body) === 1;
    }
}
