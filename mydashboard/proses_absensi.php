<?php
require("../config/koneksi.php");

date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
$waktu = date('H:i');

// Ambil mode absen dari tabel status
$query_status = mysqli_query($koneksi, "SELECT mode FROM status LIMIT 1");
$status = mysqli_fetch_assoc($query_status);
$mode = $status['mode'];

$pesan1 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='1'"));
$pesan2 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='2'"));
$pesan3 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='3'"));
$pesan4 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM m_pesan  WHERE id='4'"));

// Ambil pengaturan jam masuk
$setting = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT masuk, url_api, nowa FROM setting LIMIT 1"));
$row_waktu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT masuk FROM waktu LIMIT 1"));
$jam_masuk = strtotime($row_waktu['masuk']);
$jam_absen = strtotime($waktu);

$selisih = $jam_absen - $jam_masuk;
if ($selisih > 0) {
    $jam = floor($selisih / 3600);
    $menit = floor(($selisih % 3600) / 60);
    $ket = "Terlambat $jam jam, $menit menit";
} else {
    $ket = "Tepat Waktu";
}

if (isset($_POST['kode_qr'])) {
    $nokartu = mysqli_real_escape_string($koneksi, $_POST['kode_qr']);
    if (empty($nokartu)) {
        echo "Error: Data QR code kosong.";
        exit;
    }

    $query_datareg = mysqli_query($koneksi, "SELECT * FROM datareg WHERE nokartu = '$nokartu'");
    if (!$query_datareg || mysqli_num_rows($query_datareg) == 0) {
        echo "Data QR code tidak valid.";
        exit;
    }
    
    $data = mysqli_fetch_assoc($query_datareg);
    $level = mysqli_real_escape_string($koneksi, $data['level']);
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $id_user = ($level === 'siswa') ? $data['idsiswa'] : $data['idpeg'];
    $siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE nis='$data[nokartu]'"));

    $cek_absen = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal = '$tanggal' AND nokartu = '$nokartu'");
    $sudah_absen = mysqli_num_rows($cek_absen) > 0;

    if ($mode == 1) { // Absen Masuk
        if ($sudah_absen) {
            echo "Gagal: Anda sudah absen masuk hari ini.";
        } else {
            $query_insert = "INSERT INTO absensi (tanggal, nokartu, " . ($level === 'siswa' ? "idsiswa" : "idpeg") . ", level, masuk, bulan, tahun, ket, keterangan, mesin) 
                             VALUES ('$tanggal', '$nokartu', '$id_user', '$level', '$waktu', LPAD(MONTH(NOW()), 2, '0'), YEAR(NOW()), 'H', '$ket', 'QR')";
            if (mysqli_query($koneksi, $query_insert)) {
                echo "Absensi masuk berhasil: $level $nama pukul $waktu ($ket).";
                
                // Kirim notifikasi WA
                $notif_masuk_siswa = $pesan3['pesan1']." ".$pesan3['pesan2']." *".$siswa['nama']."* ".$pesan3['pesan3']." ".$tanggal." ".$pesan3['pesan4']." *Keterangan Absen :* ".$ket;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $setting['url_api'] . '/send-message',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => array('message' => $notif_masuk_siswa, 'number' => $setting['nowa'])
                ));
                curl_exec($curl);
                curl_close($curl);
            } else {
                echo "Gagal menyimpan absensi masuk: " . mysqli_error($koneksi);
            }
        }
    } elseif ($mode == 2) { // Absen Pulang
        if ($sudah_absen) {
            $update_pulang = mysqli_query($koneksi, "UPDATE absensi SET pulang = '$waktu' WHERE tanggal = '$tanggal' AND nokartu = '$nokartu'");
            if ($update_pulang) {
                echo "Absensi pulang berhasil: $level $nama pukul $waktu.";
            } else {
                echo "Gagal memperbarui absensi pulang: " . mysqli_error($koneksi);
            }
        } else {
            $query_insert_pulang = "INSERT INTO absensi (tanggal, nokartu, " . ($level === 'siswa' ? "idsiswa" : "idpeg") . ", level, pulang, bulan, tahun, ket, mesin) 
                                     VALUES ('$tanggal', '$nokartu', '$id_user', '$level', '$waktu', LPAD(MONTH(NOW()), 2, '0'), YEAR(NOW()), 'H', 'QR')";
            if (mysqli_query($koneksi, $query_insert_pulang)) {
                echo "Absensi pulang berhasil (tanpa absen masuk): $level $nama pukul $waktu.";
            } else {
                echo "Gagal menyimpan absensi pulang: " . mysqli_error($koneksi);
            }
        }
    } else {
        echo "Mode absensi tidak valid.";
    }
} else {
    echo "Tidak ada data yang dikirim.";
}
