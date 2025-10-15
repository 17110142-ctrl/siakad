<?php
require("../config/koneksi.php");
require("../config/function.php");
$token = isset($_GET['token']) ? $_GET['token'] : 'false';

$querys = mysqli_query($koneksi, "select token_api from aplikasi where token_api='$token'");
$cektoken = mysqli_num_rows($querys);

if ($cektoken <> 0) {

    $query = mysqli_query($koneksi, "select * from siswa");
    $array_data = array();
    while ($baris = mysqli_fetch_assoc($query)) {
        $array_data[] = $baris;
    }

    echo json_encode([
        'siswa' => $array_data
    ]);
} else {
    echo "<script>location.href='.'</script>";
}
