<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if ($pg == 'kelas') {
    $level = $_POST['level'];
    $sql = mysqli_query($koneksi, "SELECT * FROM kelas WHERE level='" . $level . "'");
    echo "<option value=''>Pilih Rombel</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kelas]'>$data[kelas]</option>";
    }
}