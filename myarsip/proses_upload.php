<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("../config/koneksi.php");

$nama_file = mysqli_real_escape_string($koneksi, $_POST['nama_file']);
$kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);

$allowed_ext = ['pdf', 'docx'];
$file_name = $_FILES['file']['name'];
$tmp = $_FILES['file']['tmp_name'];
$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

if ($_FILES['file']['error'] !== 0) {
    die("Upload error code: " . $_FILES['file']['error']);
}

if (in_array($ext, $allowed_ext)) {
    $new_name = uniqid() . '.' . $ext;
    $upload_dir = '../myarsip/file_arsip/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target = $upload_dir . $new_name;
    if (move_uploaded_file($tmp, $target)) {
        $query = "INSERT INTO arsip (nama_file, kategori, file_path) VALUES ('$nama_file', '$kategori', '$new_name')";
        $exec = mysqli_query($koneksi, $query);

        if (!$exec) {
            die("SQL Error: " . mysqli_error($koneksi));
        }

        echo "<script>alert('Upload berhasil');window.location='../myarsip/';</script>";
        
    } else {
        die("Gagal memindahkan file.");
    }
} else {
    echo "<script>alert('Format file tidak diperbolehkan');window.location='../myarsip/';</script>";
}
?>
