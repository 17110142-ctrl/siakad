<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($koneksi)) {
    // Pastikan path ini benar menuju file koneksi Anda
    require_once(__DIR__ . "/../config/koneksi.php");
}

$user_id = null;
$user_type = null;

if (isset($_SESSION['id_user'])) {
    $user_id = $_SESSION['id_user'];
    $user_type = 'users';
} elseif (isset($_SESSION['id_siswa'])) {
    $user_id = $_SESSION['id_siswa'];
    $user_type = 'siswa';
}

if ($user_id !== null && isset($_SESSION['session_token'])) {
    $current_token = $_SESSION['session_token'];
    $table_name = $user_type;
    $id_column = ($user_type === 'users') ? 'id_user' : 'id_siswa';

    $stmt = $koneksi->prepare("SELECT session_token FROM {$table_name} WHERE {$id_column} = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $db_token = $row['session_token'];
        
        if ($db_token !== $current_token) {
            // <<< PERBAIKAN DI SINI: Mengatur online=0 dan session_token=NULL >>>
            $koneksi->query("UPDATE {$table_name} SET online = '0', session_token = NULL WHERE {$id_column} = '{$user_id}'");

            session_unset();
            session_destroy();
            
            header("Location: /myhome/mulai.php?status=autologout");
            exit();
        }
    } else {
        session_unset();
        session_destroy();
        header("Location: /myhome/mulai.php?status=nouser");
        exit();
    }
    $stmt->close();
}
?>
