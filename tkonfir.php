<?php
// --- Start of Original Script Logic ---

// Get the User-Agent from the server variables.
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Not provided';

// Log every incoming User-Agent for admin reference (to useragent.log)
// Helps to update the allowlist below when needed.
try {
    $ip  = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '-';
    $uri = $_SERVER['REQUEST_URI'] ?? '-';
    $now = date('Y-m-d H:i:s');
    $line = "[$now] $ip $uri UA= " . $userAgent . PHP_EOL;
    // Write at project root useragent.log
    $logPath = __DIR__ . '/useragent.log';
    @file_put_contents($logPath, $line, FILE_APPEND | LOCK_EX);
} catch (\Throwable $e) {
    // ignore logging errors
}

// Define a list of allowed User-Agent strings.
$allowedUserAgents = [
    'Chrome/98.0.4758.101',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) QtWebEngine/6.5.2 Chrome/108.0.5359.220 Safari/537.36'
];

// Check if the current User-Agent is in the allowed list.
$isAllowed = false;
foreach ($allowedUserAgents as $allowedAgent) {
    // Use strpos to see if the current User-Agent string contains an allowed string.
    if (strpos($userAgent, $allowedAgent) !== false) {
        $isAllowed = true;
        break; // Exit the loop once a match is found.
    }
}

// If the User-Agent is not allowed, block access.
if (!$isAllowed) {
    // If the User-Agent doesn't match, send a 403 Forbidden response.
    http_response_code(403);
    // Display a forbidden page.
    // Ensure 'forbidden.php' exists in the same directory.
    if (file_exists(__DIR__ . '/forbidden.php')) {
        readfile(__DIR__ . '/forbidden.php');
    } else {
        echo '<h1>403 Forbidden</h1><p>You are not allowed to access this page.</p>';
    }
    // Stop script execution.
    exit;
}

// If User-Agent is valid, proceed to check the token.
require "config/koneksi.php";
require "config/function.php";
require "config/crud.php";

// Check if the token is set in the POST request.
if (isset($_POST['token'])) {
    // Check if the provided token exists in the 'token' table.
    $cek = rowcount($koneksi, 'token', ['token' => $_POST['token']]);
    // Respond with 'OK' if the token is valid, otherwise 'gagal'.
    echo $cek > 0 ? 'OK' : 'gagal';
} else {
    // Respond with 'gagal' if no token was provided.
    echo 'gagal';
}

?>
