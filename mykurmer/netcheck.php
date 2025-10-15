<?php
// Simple connectivity checker for Dapodik WS via Portmap
header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(60);

$host = isset($_GET['host']) ? $_GET['host'] : '193.161.193.99';
$port = isset($_GET['port']) ? (int)$_GET['port'] : 62906;
$path = isset($_GET['path']) ? $_GET['path'] : '/';

printf("Target: %s:%d%s\n", $host, $port, $path);

// 1) Raw TCP connect test
$errno = 0;
$errstr = '';
$t0 = microtime(true);
$fp = @fsockopen($host, $port, $errno, $errstr, 8);
$dt = number_format((microtime(true) - $t0) * 1000, 1);
if ($fp) {
    echo "fsockopen: OK ({$dt} ms connect)\n";
    fclose($fp);
} else {
    echo "fsockopen: FAIL ($errno) $errstr ({$dt} ms)\n";
}

// 2) HTTP GET test (show headers + first bytes)
$url = "http://{$host}:{$port}{$path}";
$ch = curl_init($url);
$opts = [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_CONNECTTIMEOUT => 8,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTPHEADER => ['Expect:'],
    CURLOPT_USERAGENT => 'netcheck/1.0',
];
if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
    $opts[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
}
curl_setopt_array($ch, $opts);
$resp = curl_exec($ch);
$info = curl_getinfo($ch);
$err = curl_error($ch);
$eno = curl_errno($ch);
curl_close($ch);

echo "cURL: http_code={$info['http_code']}, connect={$info['connect_time']}s, total={$info['total_time']}s, ip={$info['primary_ip']}\n";
if ($err) {
    echo "cURL error ({$eno}): {$err}\n";
}

if ($resp !== false) {
    $hs = (int)($info['header_size'] ?? 0);
    $hdr = substr($resp, 0, $hs);
    $body = substr($resp, $hs);
    echo "\n--- Response headers ---\n";
    echo trim($hdr) . "\n";
    echo "\n--- Body (first 400 bytes) ---\n";
    echo substr($body, 0, 400) . "\n";
}
