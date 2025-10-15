<?php
header('Content-Type: application/json');

// Menggunakan path absolut dari document root untuk keandalan yang lebih baik.
// Ini mengasumsikan folder 'data' Anda berada di dalam 'public_html/data'.
$dataDir = $_SERVER['DOCUMENT_ROOT'] . '/data/';

// Fungsi untuk mengubah nama file (e.g., "jawa-barat.csv") menjadi nama provinsi ("JAWA BARAT")
function filenameToProvince($filename) {
    $name = str_replace('.csv', '', $filename);
    $name = str_replace('-', ' ', $name);
    return strtoupper($name);
}

// Fungsi untuk mengubah nama provinsi (e.g., "JAWA BARAT") menjadi nama file ("jawa-barat.csv")
function provinceToFilename($provinceName) {
    $name = strtolower($provinceName);
    return str_replace(' ', '-', $name) . '.csv';
}

// Fungsi untuk menstandarkan header dari CSV, termasuk menghapus BOM
function standardizeHeader($header) {
    // Peta untuk menstandarkan berbagai kemungkinan nama kolom
    $map = [
        'propinsi' => 'provinsi',
        'provinsi' => 'provinsi',
        'kota/kab.' => 'kabupaten', // Ditambahkan untuk menangani header dengan titik
        'kota/kab' => 'kabupaten',
        'kabupaten' => 'kabupaten',
        'kecamatan' => 'kecamatan',
        'kelurahan' => 'kelurahan',
        'desa' => 'kelurahan',
        'kode pos' => 'kodepos',
        'kodepos' => 'kodepos',
        'lintang' => 'lintang',
        'latitude' => 'lintang',
        'bujur' => 'bujur',
        'longitude' => 'bujur',
    ];

    $standardized = [];
    $isFirst = true;
    foreach ($header as $col) {
        // Hapus karakter aneh (BOM) dari kolom pertama jika ada
        if ($isFirst) {
            $col = preg_replace('/^\x{FEFF}|\x{FFFE}|\x{EF}\x{BB}\x{BF}/', '', $col);
            $isFirst = false;
        }
        $cleanCol = strtolower(trim($col));
        $standardized[] = $map[$cleanCol] ?? $cleanCol;
    }
    return $standardized;
}


// Fungsi untuk membaca CSV dan mengembalikan data unik berdasarkan filter
function getDataFromCSV($filePath, $columnName, $filters = []) {
    if (!file_exists($filePath)) {
        return ['error' => 'File data untuk provinsi ini tidak ditemukan. Path: ' . $filePath];
    }

    $data = [];
    $header = [];
    $rawHeader = [];
    $columnIndex = -1;

    if (($handle = fopen($filePath, 'r')) !== FALSE) {
        if (($rawHeader = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $header = standardizeHeader($rawHeader);
            $columnIndex = array_search($columnName, $header);
        }

        if ($columnIndex === false) {
            fclose($handle);
            $rawHeaderString = implode(' | ', $rawHeader);
            $standardizedHeaderString = implode(' | ', $header);
            return ['error' => "Kolom standar '{$columnName}' tidak dapat ditemukan. Header mentah: [{$rawHeaderString}]. Header terstandar: [{$standardizedHeaderString}]."];
        }

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $match = true;
            foreach ($filters as $filterColumn => $filterValue) {
                $filterColumnIndex = array_search($filterColumn, $header);
                if ($filterColumnIndex === false || !isset($row[$filterColumnIndex]) || strtolower(trim($row[$filterColumnIndex])) != strtolower(trim($filterValue))) {
                    $match = false;
                    break;
                }
            }
            if ($match && isset($row[$columnIndex])) {
                $data[] = trim($row[$columnIndex]);
            }
        }
        fclose($handle);
    }
    $uniqueData = array_values(array_unique($data));
    sort($uniqueData);
    return $uniqueData;
}

// Fungsi untuk mendapatkan detail lengkap (kodepos, dll.)
function getDetailFromCSV($filePath, $filters = []) {
     if (!file_exists($filePath)) {
        return ['error' => 'File data untuk provinsi ini tidak ditemukan.'];
    }

    $result = null;
    $header = [];

    if (($handle = fopen($filePath, 'r')) !== FALSE) {
        if (($rawHeader = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $header = standardizeHeader($rawHeader);
        }

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if(count($header) == count($row)){
                $row_assoc = array_combine($header, $row);
                $match = true;
                foreach ($filters as $filterColumn => $filterValue) {
                    if (!isset($row_assoc[$filterColumn]) || strtolower(trim($row_assoc[$filterColumn])) != strtolower(trim($filterValue))) {
                        $match = false;
                        break;
                    }
                }
                if ($match) {
                    $result = [
                        'kodepos' => $row_assoc['kodepos'] ?? '',
                        'lintang' => $row_assoc['lintang'] ?? '',
                        'bujur' => $row_assoc['bujur'] ?? ''
                    ];
                    break;
                }
            }
        }
        fclose($handle);
    }
    return $result;
}

$action = $_GET['action'] ?? '';
$filters = [];
$response = [];

switch ($action) {
    case 'provinsi':
        if (!is_dir($dataDir)) {
            $response = ['error' => 'Direktori data tidak ditemukan di path: ' . $dataDir];
            break;
        }
        $files = scandir($dataDir);
        if ($files === false) {
            $response = ['error' => 'Gagal membaca isi direktori data. Periksa izin akses (permissions).'];
            break;
        }
        $provinces = [];
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'csv') {
                $provinces[] = filenameToProvince($file);
            }
        }
        if (empty($provinces)) {
            $response = ['error' => 'Tidak ada file .csv yang ditemukan di dalam direktori data.'];
            break;
        }
        sort($provinces);
        $response = $provinces;
        break;

    case 'kabupaten':
    case 'kecamatan':
    case 'kelurahan':
    case 'detail':
        if (!isset($_GET['provinsi'])) {
            $response = ['error' => 'Nama provinsi diperlukan.'];
            break;
        }
        $provinsi = $_GET['provinsi'];
        $csvFile = $dataDir . provinceToFilename($provinsi);
        
        if ($action === 'kabupaten') {
            $response = getDataFromCSV($csvFile, 'kabupaten');
        }
        if ($action === 'kecamatan') {
            if (isset($_GET['kabupaten'])) {
                $filters['kabupaten'] = $_GET['kabupaten'];
                $response = getDataFromCSV($csvFile, 'kecamatan', $filters);
            } else {
                $response = ['error' => 'Nama kabupaten diperlukan.'];
            }
        }
        if ($action === 'kelurahan') {
            if (isset($_GET['kabupaten']) && isset($_GET['kecamatan'])) {
                $filters['kabupaten'] = $_GET['kabupaten'];
                $filters['kecamatan'] = $_GET['kecamatan'];
                $response = getDataFromCSV($csvFile, 'kelurahan', $filters);
            } else {
                $response = ['error' => 'Nama kabupaten dan kecamatan diperlukan.'];
            }
        }
        if ($action === 'detail') {
            if (isset($_GET['kabupaten']) && isset($_GET['kecamatan']) && isset($_GET['kelurahan'])) {
                $filters['kabupaten'] = $_GET['kabupaten'];
                $filters['kecamatan'] = $_GET['kecamatan'];
                $filters['kelurahan'] = $_GET['kelurahan'];
                $response = getDetailFromCSV($csvFile, $filters);
            } else {
                $response = ['error' => 'Semua detail wilayah diperlukan.'];
            }
        }
        break;

    default:
        $response = ['error' => 'Aksi tidak valid.'];
        break;
}

echo json_encode($response);
?>
