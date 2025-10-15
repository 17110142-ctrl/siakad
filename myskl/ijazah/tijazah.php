<?php
// Tingkatkan batas waktu eksekusi
@ini_set('max_execution_time', 300);
@ini_set('max_input_time', 300);

// Sertakan file koneksi
include __DIR__ . "/../../config/koneksi.php";

if (!$koneksi) {
    header("HTTP/1.1 500 Internal Server Error");
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$action = $_POST['action'] ?? '';

// Tentukan kolom database DAN folder tujuan berdasarkan jenis dokumen
$doc_type = $_POST['doc_type'] ?? '';
$column_name = '';
$destFolder = '';
if ($doc_type === 'ijazah') {
    $column_name = 'ijazah';
    $destFolder = 'ijazahsiswa';
} elseif ($doc_type === 'transkrip') {
    $column_name = 'transkrip';
    $destFolder = 'transkripsiswa';
}

// ==========================================================
// --- LOGIKA HAPUS ---
// ==========================================================
if ($action === 'delete') {
    ob_start(); 

    header('Content-Type: application/json');
    $response = ['status' => 'error', 'message' => 'Permintaan tidak valid atau jenis dokumen tidak dikenali.'];

    if (!empty($column_name) && isset($_POST['nis'])) {
        $nis = $_POST['nis'];
        $nisEsc = mysqli_real_escape_string($koneksi, $nis);

        $sql_select = "SELECT $column_name FROM alumni WHERE nis = '$nisEsc'";
        $result_select = mysqli_query($koneksi, $sql_select);

        if (!$result_select) {
            $response['message'] = 'Gagal query SELECT: ' . mysqli_error($koneksi);
        } else if ($row = mysqli_fetch_assoc($result_select)) {
            $file_name = $row[$column_name];
            if (!empty($file_name)) {
                $path_file = '../../images/' . $destFolder . '/' . $file_name;
                if (file_exists($path_file)) {
                    @unlink($path_file);
                }
            }

            $sql_update = "UPDATE alumni SET $column_name = NULL WHERE nis = '$nisEsc'";
            if (mysqli_query($koneksi, $sql_update)) {
                $response['status'] = 'success';
                $response['message'] = 'Dokumen berhasil dihapus.';
            } else {
                $response['message'] = 'Gagal query UPDATE: ' . mysqli_error($koneksi);
            }
        } else {
            $response['message'] = 'NIS tidak ditemukan.';
        }
    }
    
    ob_end_clean(); 
    echo json_encode($response); 
    mysqli_close($koneksi);
    exit();
}

// ==========================================================
// --- LOGIKA UPLOAD ---
// ==========================================================
if (empty($column_name)) {
    die("ERROR: Jenis dokumen tidak valid.");
}

$destPath = realpath(__DIR__ . '/../../images/' . $destFolder);
if ($destPath === false) {
    $destPath = __DIR__ . '/../../images/' . $destFolder;
    if (!mkdir($destPath, 0755, true)) die("ERROR: Gagal membuat direktori tujuan.");
}
if (!is_writable($destPath)) {
    die("ERROR: Direktori tujuan tidak dapat ditulisi. Periksa perizinan folder.");
}

$allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];

if (!empty($_FILES['files']['name'][0])) {
    $file_count = count($_FILES['files']['name']);
    for ($i = 0; $i < $file_count; $i++) {
        $file_name = $_FILES['files']['name'][$i];
        $file_tmp_name = $_FILES['files']['tmp_name'][$i];
        $file_error = $_FILES['files']['error'][$i];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_error === UPLOAD_ERR_OK) {
            if (in_array($file_ext, $allowed_extensions)) {
                $nama_from_file = pathinfo($file_name, PATHINFO_FILENAME);
                $namaEsc = mysqli_real_escape_string($koneksi, $nama_from_file);

                $sql_check = "SELECT nis, $column_name FROM alumni WHERE nama = '$namaEsc'";
                $result_check = mysqli_query($koneksi, $sql_check);

                if (!$result_check) {
                    echo "ERROR: Gagal memeriksa nama '{$nama_from_file}'. Detail: " . mysqli_error($koneksi) . "\n";
                    continue;
                }
                
                $num_rows = mysqli_num_rows($result_check);

                if ($num_rows === 1) {
                    $alumni_data = mysqli_fetch_assoc($result_check);
                    
                    if (!empty($alumni_data[$column_name])) {
                        echo "ERROR: File '{$file_name}' tidak diproses. Dokumen ($doc_type) untuk '{$nama_from_file}' sudah ada.\n";
                        continue;
                    }

                    $nis_to_update = $alumni_data['nis'];
                    $basename = basename($file_name);
                    $target_file = $destPath . DIRECTORY_SEPARATOR . $basename;

                    if (file_exists($target_file)) {
                        $basename = time() . '_' . $basename;
                        $target_file = $destPath . DIRECTORY_SEPARATOR . $basename;
                    }

                    if (move_uploaded_file($file_tmp_name, $target_file)) {
                        $ijazahEsc = mysqli_real_escape_string($koneksi, $basename);
                        $sql_update = "UPDATE alumni SET $column_name = '$ijazahEsc' WHERE nis = '$nis_to_update'";
                        if (mysqli_query($koneksi, $sql_update)) {
                            echo "SUCCESS: File {$basename} berhasil diunggah untuk {$nama_from_file}.\n";
                        } else {
                            echo "ERROR: Gagal update database untuk {$nama_from_file}. Detail: " . mysqli_error($koneksi) . "\n";
                        }
                    } else {
                        echo "ERROR: Gagal memindahkan file {$file_name}.\n";
                    }
                } else if ($num_rows > 1) {
                    echo "ERROR: Ditemukan lebih dari satu alumni dengan nama '{$nama_from_file}'.\n";
                } else {
                    echo "ERROR: Nama '{$nama_from_file}' tidak ditemukan di data alumni.\n";
                }
            } else {
                echo "ERROR: Ekstensi file '{$file_ext}' tidak diizinkan.\n";
            }
        } else {
            echo "ERROR: Terjadi kesalahan saat mengunggah file. Kode: {$file_error}\n";
        }
    }
    echo "Proses upload selesai.";
} else {
    if ($action !== 'delete') {
         echo "ERROR: Tidak ada file yang dipilih atau aksi tidak dikenali.\n";
    }
}

mysqli_close($koneksi);
?>
