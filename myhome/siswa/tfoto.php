<?php
require("../../config/koneksi.php");

header('Content-Type: application/json');

// Fungsi untuk menghapus direktori dan isinya secara rekursif
function deleteDir($dirPath) {
    if (!is_dir($dirPath)) return;
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') $dirPath .= '/';
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) deleteDir($file);
        else unlink($file);
    }
    rmdir($dirPath);
}

// Fungsi untuk memproses file gambar yang diunggah langsung (bukan dari ZIP)
function processDirectUploadedImage($file_name, $file_tmp_path, &$response, $koneksi) {
    $destPath = '../../images/fotosiswa/';
    $nama_siswa_from_file = basename($file_name, '.' . pathinfo($file_name, PATHINFO_EXTENSION));
    $nama_siswa_db = mysqli_real_escape_string($koneksi, $nama_siswa_from_file);

    $sql_check = "SELECT id_siswa FROM siswa WHERE nama = '$nama_siswa_db'";
    $result = mysqli_query($koneksi, $sql_check);

    if (mysqli_num_rows($result) > 0) {
        $siswa = mysqli_fetch_assoc($result);
        $id_siswa = $siswa['id_siswa'];
        $target = $destPath . $file_name;

        // Menggunakan move_uploaded_file karena ini adalah file asli dari unggahan
        if (move_uploaded_file($file_tmp_path, $target)) {
            $sql_update = "UPDATE siswa SET foto = '" . mysqli_real_escape_string($koneksi, $file_name) . "' WHERE id_siswa = '$id_siswa'";
            if (mysqli_query($koneksi, $sql_update)) {
                $response['sukses'][] = "✔ Foto '$file_name' berhasil diunggah.";
            } else {
                $response['gagal'][] = "❌ Gagal update database untuk '$file_name'.";
            }
        } else {
            $response['gagal'][] = "❌ Gagal memindahkan file '$file_name'.";
        }
    } else {
        $response['gagal'][] = "❌ Siswa dengan nama '$nama_siswa_from_file' tidak ditemukan.";
    }
}


// ACTION HANDLER: Memeriksa apakah ini permintaan HAPUS atau UPLOAD
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    // --- LOGIKA HAPUS FOTO (Tidak Berubah) ---
    $response = ['status' => 'error', 'message' => 'Permintaan tidak valid untuk menghapus.'];
    if (isset($_POST['id_siswa'])) {
        $id_siswa = mysqli_real_escape_string($koneksi, $_POST['id_siswa']);
        $sql_get = "SELECT foto FROM siswa WHERE id_siswa = '$id_siswa'";
        $result_get = mysqli_query($koneksi, $sql_get);
        if ($row = mysqli_fetch_assoc($result_get)) {
            $nama_foto_lengkap = $row['foto'];
            if (!empty($nama_foto_lengkap)) {
                $file_path = '../../images/fotosiswa/' . $nama_foto_lengkap;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                $sql_update = "UPDATE siswa SET foto = '' WHERE id_siswa = '$id_siswa'";
                if (mysqli_query($koneksi, $sql_update)) {
                    $response = ['status' => 'success', 'message' => 'Foto berhasil dihapus.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Gagal memperbarui database.'];
                }
            } else {
                $response = ['status' => 'info', 'message' => 'Siswa ini tidak memiliki foto untuk dihapus.'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Siswa tidak ditemukan.'];
        }
    }
    echo json_encode($response);

} else {
    // --- LOGIKA UPLOAD (Aksi Default) ---
    $response = ['sukses' => [], 'gagal' => []];
    if (!empty($_FILES['file']['name'][0])) {
        
        // Cek apakah ini unggahan file ZIP tunggal
        if (count($_FILES['file']['name']) == 1 && strtolower(pathinfo($_FILES['file']['name'][0], PATHINFO_EXTENSION)) === 'zip') {
            $zip = new ZipArchive;
            if ($zip->open($_FILES['file']['tmp_name'][0]) === TRUE) {
                $extractDir = sys_get_temp_dir() . '/' . uniqid('extract_');
                if (mkdir($extractDir, 0777, true)) {
                    $zip->extractTo($extractDir);
                    $zip->close();
                    
                    $destPath = '../../images/fotosiswa/';
                    $filesInZip = new DirectoryIterator($extractDir);

                    foreach ($filesInZip as $file) {
                        if ($file->isDot() || $file->isDir()) continue;

                        $file_name = $file->getFilename();
                        $file_tmp_path = $file->getPathname(); // Path file yang sudah diekstrak
                        $ext = strtolower($file->getExtension());

                        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                            $nama_siswa_from_file = $file->getBasename('.' . $ext);
                            $nama_siswa_db = mysqli_real_escape_string($koneksi, $nama_siswa_from_file);
                            $sql_check = "SELECT id_siswa FROM siswa WHERE nama = '$nama_siswa_db'";
                            $result = mysqli_query($koneksi, $sql_check);

                            if (mysqli_num_rows($result) > 0) {
                                $siswa = mysqli_fetch_assoc($result);
                                $id_siswa = $siswa['id_siswa'];
                                $target = $destPath . $file_name;

                                // PERBAIKAN: Menggunakan rename() untuk file dari ZIP
                                if (rename($file_tmp_path, $target)) {
                                    $sql_update = "UPDATE siswa SET foto = '" . mysqli_real_escape_string($koneksi, $file_name) . "' WHERE id_siswa = '$id_siswa'";
                                    if (mysqli_query($koneksi, $sql_update)) {
                                        $response['sukses'][] = "✔ Foto '$file_name' dari ZIP berhasil diunggah.";
                                    } else {
                                        $response['gagal'][] = "❌ Gagal update database untuk '$file_name' dari ZIP.";
                                    }
                                } else {
                                    $response['gagal'][] = "❌ Gagal memindahkan file '$file_name' dari ZIP.";
                                }
                            } else {
                                $response['gagal'][] = "❌ Siswa dengan nama '$nama_siswa_from_file' tidak ditemukan.";
                            }
                        }
                    }
                    deleteDir($extractDir);
                } else {
                    $response['gagal'][] = "Gagal membuat folder sementara untuk ekstraksi.";
                }
            } else {
                $response['gagal'][] = "Gagal membuka file ZIP.";
            }
        } else {
            // Proses sebagai unggahan file gambar ganda (atau tunggal)
            $file_count = count($_FILES['file']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['file']['error'][$i] === UPLOAD_ERR_OK) {
                    $file_name = $_FILES['file']['name'][$i];
                    $file_tmp_path = $_FILES['file']['tmp_name'][$i];
                    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        processDirectUploadedImage($file_name, $file_tmp_path, $response, $koneksi);
                    } else {
                        $response['gagal'][] = "❌ Format file '$file_name' tidak didukung.";
                    }
                }
            }
        }
    } else {
        $response['gagal'][] = "Tidak ada file yang diunggah atau terjadi error.";
    }

    echo json_encode($response);
}
?>
