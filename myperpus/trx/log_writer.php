<?php
// Pastikan file ini tidak dapat diakses secara acak
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    exit('Akses tidak diizinkan.');
}

// Lokasi file log. Pastikan direktori ini ada dan dapat ditulisi oleh server (writable).
define('LOG_FILE', __DIR__ . '/peminjaman.log');

/**
 * Fungsi untuk menulis log ke file.
 * @param string $message Pesan log.
 * @param string $level Level log (INFO, ERROR, WARN, DEBUG).
 */
function write_log($message, $level = 'INFO') {
    // Format entri log: [TIMESTAMP] [LEVEL] - Pesan
    $log_entry = "[" . date('Y-m-d H:i:s') . "] [" . strtoupper($level) . "] - " . $message . PHP_EOL;
    
    // Menulis ke file dengan mode append dan mengunci file untuk mencegah data korup
    file_put_contents(LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}

// Ambil data dari request POST
$level = isset($_POST['level']) ? trim($_POST['level']) : 'INFO';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (!empty($message)) {
    write_log($message, $level);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    header('Content-Type: application/json');
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => 'Pesan log kosong.']);
}
?>