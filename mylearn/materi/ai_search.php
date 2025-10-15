<?php
// Atur header agar output berupa JSON
header('Content-Type: application/json');

// --- PENTING: GANTI DENGAN API KEY ANDA ---
$apiKey = 'AIzaSyBcdUaogodlZCXxlitTPJSe62N1dOAfMgc';
// -----------------------------------------

$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $apiKey;

// Ambil data JSON yang dikirim dari client-side (JavaScript)
$jsonInput = file_get_contents('php://input');
$data = json_decode($jsonInput);

// Validasi input
if (!isset($data->message) || empty(trim($data->message))) {
    echo json_encode(['reply' => 'Pesan tidak boleh kosong.']);
    http_response_code(400); // Bad Request
    exit;
}

$userMessage = $data->message;

// Struktur data (payload) yang akan dikirim ke Gemini API
$postData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $userMessage]
            ]
        ]
    ]
];

// Inisialisasi cURL untuk melakukan request ke API
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Timeout 60 detik

// Eksekusi cURL
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Penanganan error cURL
if ($error) {
    echo json_encode(['reply' => 'Maaf, terjadi kesalahan saat menghubungi server AI: ' . $error]);
    http_response_code(500); // Internal Server Error
    exit;
}

// Penanganan error dari API (misal: API key salah, dll)
if ($httpcode != 200) {
    $errorResponse = json_decode($response, true);
    $errorMessage = isset($errorResponse['error']['message']) ? $errorResponse['error']['message'] : 'Terjadi kesalahan pada API.';
    echo json_encode(['reply' => 'Gagal mendapatkan balasan dari AI: ' . $errorMessage]);
    http_response_code($httpcode);
    exit;
}

// Decode respons JSON dari Gemini
$result = json_decode($response, true);

// Ekstrak dan kirim balasan teks ke client
if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $result['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode(['reply' => $reply]);
} else {
    // Jika format balasan tidak sesuai harapan
    echo json_encode(['reply' => 'Maaf, saya tidak dapat memproses balasan saat ini.']);
    http_response_code(500);
}
?>
