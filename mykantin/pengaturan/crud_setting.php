<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");

(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
if ($pg == 'setting_app') {
    $alamat = nl2br($_POST['alamat']);
	$smt = $_POST['semester'];
    $data = [
        'aplikasi' => $_POST['aplikasi'],
        'sekolah' => $_POST['sekolah'],
        'id_server' => $_POST['kode'],
        'jenjang' => $_POST['jenjang'],
		 'npsn' => $_POST['npsn'],
        'kepsek' => $_POST['kepsek'],
        'nip' => $_POST['nip'],
		'nowa' => $_POST['nowa'],
        'alamat' => $_POST['alamat'],
		'desa' => $_POST['desa'],
        'kecamatan' => $_POST['kecamatan'],
        'kabupaten' => $_POST['kabupaten'],
		 'propinsi' => $_POST['propinsi'],
        'telp' => $_POST['telp'],
        'fax' => $_POST['fax'],
        'web' => $_POST['web'],
        'email' => $_POST['email'],
		
        'waktu' => $_POST['waktu'],
		 'semester' => $_POST['semester'],
		  'tp' => $_POST['tp'],
		    'jenis' => $_POST['jenis']
    ];
    $exec = update($koneksi, 'aplikasi', $data, ['id_aplikasi' => 1]);
	
    if ($exec) {
        $ektensi = ['jpg', 'png','svg','PNG', 'JPG', 'JPEG'];
        if ($_FILES['logo']['name'] <> '') {
            $logo = $_FILES['logo']['name'];
            $temp = $_FILES['logo']['tmp_name'];
            $ext = explode('.', $logo);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
                $dest = 'logo' . rand(0,100). '.' . $ext;
                $upload = move_uploaded_file($temp, '../../images/' . $dest);
                if ($upload) {
                    $exec = update($koneksi, 'aplikasi', ['logo' => $dest], ['id_aplikasi' => 1]);
                } else {
                    echo "gagal";
                }
            }
        }
          if ($_FILES['stempel']['name'] <> '') {
            $logo = $_FILES['stempel']['name'];
            $temp = $_FILES['stempel']['tmp_name'];
            $ext = explode('.', $logo);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
                $dest = 'stempel' . rand(0,100). '.' . $ext;
                 $upload = move_uploaded_file($temp, '../../images/' . $dest);
				  if ($upload) {
                    $exec = update($koneksi, 'aplikasi', ['stempel' => $dest], ['id_aplikasi' => 1]);
                }
            }
        }
		 if ($_FILES['ttd']['name'] <> '') {
            $logo = $_FILES['ttd']['name'];
            $temp = $_FILES['ttd']['tmp_name'];
            $ext = explode('.', $logo);
            $ext = end($ext);
            if (in_array($ext, $ektensi)) {
               $dest = 'ttd' . rand(0,100). '.' . $ext;
                $upload = move_uploaded_file($temp, '../../images/' . $dest);
								  if ($upload) {
                    $exec = update($koneksi, 'aplikasi', ['ttd' => $dest], ['id_aplikasi' => 1]);
                }
            }
        }
    } else {
        echo "Gagal menyimpan";
    }
}


if ($pg == 'setting_restore') {
    function restore($file)
    {
        require("../../config/koneksi.php");
        global $rest_dir;
        $nama_file    = $file['name'];
        $ukrn_file    = $file['size'];
        $tmp_file    = $file['tmp_name'];

        if ($nama_file == "") {
            echo "Fatal Error";
        } else {
            $alamatfile    = $rest_dir . $nama_file;
            $templine    = array();

            if (move_uploaded_file($tmp_file, $alamatfile)) {

                $templine    = '';

                $lines    = file($alamatfile);

                foreach ($lines as $line) {
                    if (substr($line, 0, 2) == '--' || $line == '')
                        continue;

                    $templine .= $line;

                    if (substr(trim($line), -1, 1) == ';') {
                        mysqli_query($koneksi, $templine);
                        $templine = '';
                    }
                }
            } else {
                echo "Proses upload gagal, kode error = " . $file['error'];
            }
        }
    }
    restore($_FILES['datafile']);
    if (isset($_FILES['datafile'])) {
        echo "data berhasil di restore";
    }
}


if ($pg == 'ambil_jenjang') {
    $jenis = $_POST['jenis'];
    $sql = mysqli_query($koneksi, "SELECT * FROM jenis_sp WHERE jenis='" . $jenis . "'");
    echo "<option value='semua'>Pilih Jenjang</option>";
    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[jenjang]'>$data[ket]</option>";
    }
}
if ($pg == 'reset_pesan') {
	$exec = mysqli_query($koneksi, "truncate pesan_terkirim");
}
if ($pg == 'apiwa') {
	$data=[
	'url_api'=>$_POST['urlapi']
	];
   $exec = update($koneksi, 'aplikasi', $data, ['id_aplikasi' => 1]);
}


if ($pg == 'reset') {

$exec = mysqli_query($koneksi, "truncate siswa");
$exec = mysqli_query($koneksi, "truncate keranjang");
$exec = mysqli_query($koneksi, "truncate transaksi");
$exec = mysqli_query($koneksi, "truncate invoice");
$exec = mysqli_query($koneksi, "truncate kategori");
$exec = mysqli_query($koneksi, "truncate produk");
$exec = mysqli_query($koneksi, "truncate saldo");
$exec = mysqli_query($koneksi, "truncate tmpreg");

$gambar = glob('../../gambar/produk/*'); 
foreach ($gambar as $filex) {
    if (is_file($filex))
        unlink($filex); 
}
$foto = glob('../../images/foto/*'); 
foreach ($foto as $file) {
    if (is_file($file))
        unlink($file); 
}
    
}