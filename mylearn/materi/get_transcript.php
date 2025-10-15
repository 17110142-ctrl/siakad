<?php
// Set header ke JSON karena respons akan dalam format ini
header('Content-Type: application/json');

/**
 * Mengambil transkrip dari video YouTube.
 *
 * Catatan: Metode ini mengandalkan scraping struktur halaman YouTube,
 * yang dapat berubah sewaktu-waktu dan menyebabkan skrip ini gagal.
 * Ini adalah pendekatan tanpa pustaka eksternal.
 *
 * @param string $videoId ID video YouTube 11 karakter.
 * @return array Hasil yang berisi status sukses dan data transkrip atau pesan error.
 */
function getYouTubeTranscript($videoId) {
    // 1. Ambil konten halaman video
    $videoUrl = 'https://www.youtube.com/watch?v=' . $videoId;
    $ch = curl_init($videoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'); // User agent penting
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        return ['success' => false, 'error' => 'Gagal mengambil data dari YouTube.'];
    }

    // 2. Cari data JSON yang berisi URL transkrip di dalam HTML
    // Pola regex ini mencari objek JSON 'captions'
    if (!preg_match('/"captions":({.*?"playerCaptionsTracklistRenderer".*?})/', $html, $matches)) {
        return ['success' => false, 'error' => 'Transkrip tidak ditemukan untuk video ini. Pastikan video memiliki subtitle/CC.'];
    }

    $captionsJson = json_decode($matches[1], true);
    $captionTracks = $captionsJson['playerCaptionsTracklistRenderer']['captionTracks'] ?? [];

    if (empty($captionTracks)) {
        return ['success' => false, 'error' => 'Daftar track transkrip kosong.'];
    }

    // 3. Cari URL transkrip. Prioritas: Indonesia (id), Inggris (en), lalu yang pertama tersedia.
    $transcriptUrl = '';
    $langPriority = ['id', 'en'];
    $foundTrack = null;

    foreach ($langPriority as $lang) {
        foreach ($captionTracks as $track) {
            if (isset($track['languageCode']) && strpos($track['languageCode'], $lang) === 0) {
                $foundTrack = $track;
                break 2; // Keluar dari kedua loop
            }
        }
    }
    
    // Jika bahasa prioritas tidak ditemukan, ambil yang pertama
    if (!$foundTrack && !empty($captionTracks)) {
        $foundTrack = $captionTracks[0];
    }

    if (!$foundTrack || !isset($foundTrack['baseUrl'])) {
        return ['success' => false, 'error' => 'URL untuk transkrip tidak dapat ditemukan.'];
    }
    $transcriptUrl = $foundTrack['baseUrl'];

    // 4. Ambil file XML transkrip
    $ch = curl_init($transcriptUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $xmlString = curl_exec($ch);
    curl_close($ch);

    if (!$xmlString) {
        return ['success' => false, 'error' => 'Gagal mengambil file transkrip XML.'];
    }

    // 5. Parse XML dan gabungkan teksnya
    // Menonaktifkan error libxml agar tidak mengganggu output JSON jika XML tidak valid
    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xmlString);
    if ($xml === false) {
        return ['success' => false, 'error' => 'Gagal mem-parsing data transkrip (format XML tidak valid).'];
    }

    $fullTranscript = '';
    foreach ($xml->text as $line) {
        // simplexml_load_string secara otomatis men-decode entitas HTML (&amp;, &quot;, dll.)
        $fullTranscript .= (string)$line . ' ';
    }

    if (empty(trim($fullTranscript))) {
        return ['success' => false, 'error' => 'Transkrip berhasil diambil tetapi isinya kosong.'];
    }

    return ['success' => true, 'transcript' => trim($fullTranscript)];
}

// Ambil Video ID dari request GET
$videoId = $_GET['id'] ?? '';

if (empty($videoId) || !preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId)) {
    echo json_encode(['success' => false, 'error' => 'ID Video YouTube tidak valid.']);
    exit;
}

// Panggil fungsi dan kirim hasilnya
$result = getYouTubeTranscript($videoId);
echo json_encode($result);

?>
