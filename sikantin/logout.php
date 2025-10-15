<?php
require("config/koneksi.php");
require("config/dis.php");
(isset($_SESSION['id_siswa'])) ? $id_siswa = $_SESSION['id_siswa'] : $id_siswa = 0;

session_destroy();
echo "<script>location.href = 'index.php';</script>";
?>