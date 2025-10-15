<?php
session_start();
include "../../config/koneksi.php";

if (isset($_GET['pg']) && $_GET['pg'] == 'profilsiswa') {
    $ids = mysqli_real_escape_string($koneksi, $_POST['ids']);

    // Ambil semua input form
    $data = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'ids') {
            $data[$key] = mysqli_real_escape_string($koneksi, $value);
        }
    }

    // Handle upload foto
    if (!empty($_FILES['file']['name'])) {
        $ext = explode('.', $_FILES['file']['name']);
        $ext = strtolower(end($ext));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $newname = "siswa_" . time() . "." . $ext;
            move_uploaded_file($_FILES['file']['tmp_name'], "../../images/fotosiswa/" . $newname);

            // Update foto
            $data['foto'] = $newname;

            // Hapus foto lama kalau ada
            $query = mysqli_query($koneksi, "SELECT foto FROM siswa WHERE id_siswa='$ids'");
            $old = mysqli_fetch_assoc($query);
            if (!empty($old['foto']) && file_exists("../../images/fotosiswa/" . $old['foto'])) {
                unlink("../../images/fotosiswa/" . $old['foto']);
            }
        }
    }

    // Bangun query update
    $set = [];
    foreach ($data as $key => $value) {
        $set[] = "$key='$value'";
    }
    $set = implode(",", $set);

    $exec = mysqli_query($koneksi, "UPDATE siswa SET $set WHERE id_siswa='$ids'");

    if ($exec) {
        echo "sukses";
    } else {
        echo "gagal";
    }
}
?>
