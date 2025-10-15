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

    public function __construct(string $baseUrl, string $token, string $npsn, string $userAgent = 'siakad/dapodik-integration')
    {
        $this->baseUrl = rtrim($baseUrl, "/ \t\n\r\0\x0B");
        $this->token = trim($token);
        $this->npsn = trim($npsn);
        $this->userAgent = trim($userAgent) !== '' ? trim($userAgent) : 'siakad/dapodik-integration';
    }

    /**
     * @throws RuntimeException on HTTP / transport errors.
     */
    private function request(string $method, string $endpoint, ?array $payload = null, array $query = []): array
    {
        $url = $this->baseUrl . '/WebService/' . ltrim($endpoint, '/');

        if (strtoupper($method) === 'GET') {
            $query = array_merge(['npsn' => $this->npsn], $query);
        }
        if (!empty($query)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($query);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $this->buildHeaders($method),
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_ENCODING => '',
        ]);

        if ($payload !== null) {
            $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }

        $body = curl_exec($ch);
        if ($body === false) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);
            throw new RuntimeException('Koneksi Dapodik gagal: ' . $error, $errno);
        }

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status >= 400) {
            throw new RuntimeException("Dapodik mengembalikan HTTP {$status}: {$body}", $status);
        }

        $decoded = json_decode($body, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            $snippet = substr(preg_replace('/\s+/', ' ', (string)$body), 0, 400);
            throw new RuntimeException(
                'Gagal membaca respon Dapodik sebagai JSON: ' . json_last_error_msg()
                . ($snippet !== '' ? " | Cuplikan: {$snippet}" : '')
            );
        }

        return [
            'status' => $status,
            'body' => $body,
            'data' => $decoded,
        ];
    }

    private function buildHeaders(string $method): array
    {
        $headers = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'Cache-Control: no-cache',
            'Connection: keep-alive',
        ];
        if (strtoupper($method) !== 'GET') {
            $headers[] = 'Content-Type: application/json';
        }
        return $headers;
    }

    public function getSekolah(): array
    {
        return $this->request('GET', 'getSekolah')['data'];
    }

    public function getPesertaDidik(): array
    {
        return $this->request('GET', 'getPesertaDidik')['data'];
    }

    public function getRombonganBelajar(): array
    {
        return $this->request('GET', 'getRombonganBelajar')['data'];
    }

    public function kirimNilai(array $payload): array
    {
        if (!isset($payload['npsn'])) {
            $payload['npsn'] = $this->npsn;
        }
        return $this->request('POST', 'kirimNilai', $payload)['data'];
    }

    public function kirimMatev(array $payload): array
    {
        if (!isset($payload['npsn'])) {
            $payload['npsn'] = $this->npsn;
        }
        return $this->request('POST', 'kirimMatev', $payload)['data'];
    }
}
