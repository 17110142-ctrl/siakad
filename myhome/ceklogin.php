<?php
session_start();
require("../config/koneksi.php");
require("../config/function.php");

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// --- Cek tabel users ---
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_array($query);
    $is_login_valid = false;

    if ($user['level'] == 'admin' && password_verify($password, $user['password'])) {
        $is_login_valid = true;
    } else if (in_array($user['level'], ['guru', 'kepala', 'staff']) && $password == $user['password']) {
        $is_login_valid = true;
    }

    if ($is_login_valid) {
        $session_token = bin2hex(random_bytes(32));
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['level'] = $user['level'];
        $_SESSION['session_token'] = $session_token;

        // <<< PERUBAHAN: Set online=1 dan simpan token baru
        mysqli_query($koneksi, "UPDATE users SET online = '1', session_token = '$session_token' WHERE id_user = '{$user['id_user']}'");
        mysqli_query($koneksi, "INSERT INTO log (id_user, type, text, date, level) VALUES ('{$user['id_user']}', 'login', 'Masuk', NOW(), '{$user['level']}')");
        
        echo "ok";
        exit;
    } else {
        echo "nopass";
        exit;
    }
}

// --- Cek tabel siswa ---
$siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa WHERE username='$username'");
if (mysqli_num_rows($siswaQ) > 0) {
    $usersis = mysqli_fetch_array($siswaQ);
    if ($password == $usersis['password']) {
        $session_token = bin2hex(random_bytes(32));
        $_SESSION['id_siswa'] = $usersis['id_siswa'];
        $_SESSION['nis'] = $usersis['nis'];
        $_SESSION['session_token'] = $session_token;

        // <<< PERUBAHAN: Set online=1 dan simpan token baru
        mysqli_query($koneksi, "UPDATE siswa SET online='1', session_token = '$session_token' WHERE id_siswa='{$usersis['id_siswa']}'");
        
        echo "ok_siswa";
        exit;
    } else {
        echo "nopass";
        exit;
    }
}

echo "td";
exit;
