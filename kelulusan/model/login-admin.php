<?php
include('config.php');
$pass = md5($_POST['password']);
$user = mysqli_real_escape_string($koneksi, $_POST['username']);
$login = sprintf("SELECT * FROM tbl_user WHERE username='$user' AND pass='$pass'");
$cek_lagi = mysqli_query($koneksi, $login);
$ketemu = mysqli_num_rows($cek_lagi);
$r = mysqli_fetch_array($cek_lagi);
if ($ketemu > 0) {
  session_start();
  $_SESSION['namauser'] = $r['nama'];
  $_SESSION['kode'] = $r['id_user'];
  $_SESSION['username'] = $r['username'];
  echo "<meta http-equiv='refresh' content='0; url=admin/index.php?page=home'>";
} else {
?><script>
    alert("NAMA PENGUNA & KATA SANDI TIDAK COCOK!");
  </script>
<?php
  echo "<meta http-equiv='refresh' content='0; url=index.php?page=login'>";
}
?>