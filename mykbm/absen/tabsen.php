<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if (!function_exists('mykbm_normalize_hari')) {
	function mykbm_normalize_hari($value)
	{
		$value = strtolower(trim($value));
		$map = [
			'mon' => 'Mon',
			'monday' => 'Mon',
			'senin' => 'Mon',
			'tue' => 'Tue',
			'tuesday' => 'Tue',
			'selasa' => 'Tue',
			'wed' => 'Wed',
			'wednesday' => 'Wed',
			'rabu' => 'Wed',
			'thu' => 'Thu',
			'thursday' => 'Thu',
			'kamis' => 'Thu',
			'fri' => 'Fri',
			'friday' => 'Fri',
			'jumat' => 'Fri',
			'jum\'at' => 'Fri',
			'sat' => 'Sat',
			'saturday' => 'Sat',
			'sabtu' => 'Sat',
			'sun' => 'Sun',
			'sunday' => 'Sun',
			'minggu' => 'Sun',
			'ahad' => 'Sun'
		];
		if (isset($map[$value])) {
			return $map[$value];
		}
		return ucfirst(substr($value, 0, 3));
	}
}

$createTable = "
CREATE TABLE IF NOT EXISTS `absensi_harian` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `id_jadwal` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `idsiswa` INT(11) NOT NULL,
  `kelas` VARCHAR(50) NOT NULL,
  `mapel` INT(11) NOT NULL,
  `guru` INT(11) NOT NULL,
  `ket` VARCHAR(1) NOT NULL DEFAULT 'H',
  `bulan` VARCHAR(2) NOT NULL,
  `tahun` VARCHAR(4) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jadwal_tanggal` (`id_jadwal`,`tanggal`),
  KEY `idx_idsiswa_tanggal` (`idsiswa`,`tanggal`),
  UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
mysqli_query($koneksi, $createTable);
@mysqli_query($koneksi, "ALTER TABLE absensi_harian ADD UNIQUE KEY `uniq_absensi_harian` (`id_jadwal`,`tanggal`,`idsiswa`)");
 
if ($pg == 'sinkron') {
	$tgl = $_POST['tgl'];
	$mapel = (int) $_POST['mapel'];
	$kelas = $_POST['kelas'];
    $guru = (int) $_POST['guru'];
	$idJadwal = isset($_POST['jadwal']) ? (int) $_POST['jadwal'] : 0;

	if ($idJadwal === 0) {
		$hari = date('D', strtotime($tgl));
		$lookup = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_jadwal FROM jadwal_mapel WHERE kelas='" . mysqli_real_escape_string($koneksi, $kelas) . "' AND mapel='$mapel' AND guru='$guru' AND hari='$hari' LIMIT 1"));
		if ($lookup) {
			$idJadwal = (int) $lookup['id_jadwal'];
		}
	}

	$query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE kelas='" . mysqli_real_escape_string($koneksi, $kelas) . "' AND tanggal='" . mysqli_real_escape_string($koneksi, $tgl) . "'");
	$values = [];
	while ($data = mysqli_fetch_array($query)) {
		$tanggal = mysqli_real_escape_string($koneksi, $data['tanggal']);
		$idsiswa = (int) $data['idsiswa'];
		$kelasRow = mysqli_real_escape_string($koneksi, $data['kelas']);
		$ket = mysqli_real_escape_string($koneksi, $data['ket']);
        if ($ket === '') {
            $ket = 'H';
        }
		$bulan = mysqli_real_escape_string($koneksi, $data['bulan']);
		$tahun = mysqli_real_escape_string($koneksi, $data['tahun']);
		$values[] = "($idJadwal,'$tanggal',$idsiswa,'$kelasRow',$mapel,$guru,'$ket','$bulan','$tahun')";
	}
	if (!empty($values)) {
		$sql = "INSERT INTO absensi_harian(id_jadwal,tanggal,idsiswa,kelas,mapel,guru,ket,bulan,tahun) VALUES " . implode(',', $values) . " ON DUPLICATE KEY UPDATE ket=VALUES(ket), updated_at=NOW()";
		mysqli_query($koneksi, $sql);
	}
}
if ($pg == 'edit') {
	 $id = $_POST['id'];
	 $tgl = $_POST['tgl'];
     $ket = $_POST['ket'];
      $ids = $_POST['ids'];
      mysqli_query($koneksi,"UPDATE absensi_harian SET ket='$ket' WHERE id='$id'");
	  mysqli_query($koneksi,"UPDATE absensi SET ket='$ket' WHERE idsiswa='$ids' AND tanggal='$tgl'");
}
if ($pg == 'mapel') {
    // terima tanggal dari AJAX
    $tgl   = $_POST['tgl'];   
    // hitung nama hari sesuai tanggal itu
    $hari  = date('D', strtotime($tgl));
    $kelas = $_POST['kelas'];
    $guru  = $_POST['guru'];
    $data = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel JOIN mata_pelajaran ON mata_pelajaran.id=jadwal_mapel.mapel where jadwal_mapel.kelas='$kelas' and jadwal_mapel.guru='$guru' and jadwal_mapel.hari='$hari'");           
              echo "<option value=''>Pilih Mapel</option>";
            while ($kelas = mysqli_fetch_array($data)) {
                echo "<option value='$kelas[mapel]'>$kelas[nama_mapel]</option>";
            }
}
if ($pg == 'pertemuan') {
	header('Content-Type: application/json');
	$idParam = isset($_POST['jadwal']) ? trim($_POST['jadwal']) : '';
	$idPieces = array_filter(array_map('intval', explode(',', $idParam)), function ($val) {
		return $val > 0;
	});
	$idPieces = array_values(array_unique($idPieces));
	if (empty($idPieces)) {
		echo json_encode([
			'success' => false,
			'message' => 'Jadwal tidak ditemukan.',
			'dates' => []
		]);
		exit;
	}

	$idListSql = implode(',', $idPieces);
    $jadwalQuery = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE id_jadwal IN ($idListSql)");
    if (!$jadwalQuery || mysqli_num_rows($jadwalQuery) === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Jadwal tidak ditemukan.',
            'dates' => []
        ]);
        exit;
    }

    $jadwalRows = [];
    $allowedDays = [];
    while ($row = mysqli_fetch_assoc($jadwalQuery)) {
        $jadwalRows[] = $row;
        $allowedDays[] = mykbm_normalize_hari($row['hari']);
    }

    if (empty($jadwalRows)) {
        echo json_encode([
            'success' => false,
            'message' => 'Jadwal tidak ditemukan.',
            'dates' => []
        ]);
        exit;
    }

    $allowedDays = array_values(array_unique($allowedDays));
    if (empty($allowedDays)) {
        $allowedDays = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    }
    $baseJadwal = $jadwalRows[0];
    $kelas = mysqli_real_escape_string($koneksi, $baseJadwal['kelas']);
    $kelasCompact = preg_replace('/[^A-Za-z0-9]/', '', $baseJadwal['kelas']);
    $kelasCompactEscaped = mysqli_real_escape_string($koneksi, $kelasCompact);
    $kelasReplaceChars = ['-', ' ', '/', '(', ')', '.', ',', '_'];
    $kelasSanitizeExpr = '%s';
    foreach ($kelasReplaceChars as $char) {
        $escapedChar = mysqli_real_escape_string($koneksi, $char);
        $kelasSanitizeExpr = sprintf("REPLACE(%s,'%s','')", $kelasSanitizeExpr, $escapedChar);
    }
    $kelasSanitizeAbsensi = sprintf($kelasSanitizeExpr, 'a.kelas');
    $kelasSanitizeSiswa = sprintf($kelasSanitizeExpr, 's.kelas');
    $kelasConditions = [
        "a.kelas='{$kelas}'",
        "(s.kelas IS NOT NULL AND s.kelas='{$kelas}')"
    ];
    if ($kelasCompact !== '') {
        $kelasConditions[] = "{$kelasSanitizeAbsensi}='{$kelasCompactEscaped}'";
        $kelasConditions[] = "{$kelasSanitizeSiswa}='{$kelasCompactEscaped}'";
    }
    $kelasConditionSql = implode(' OR ', array_unique($kelasConditions));
    if ($kelasConditionSql === '') {
        $kelasConditionSql = '1=0';
    }

    $tanggalSet = [];

    $resultHarian = mysqli_query($koneksi, "SELECT DISTINCT tanggal FROM absensi_harian WHERE id_jadwal IN ($idListSql) ORDER BY tanggal ASC");
    while ($row = mysqli_fetch_assoc($resultHarian)) {
		$tanggalSet[$row['tanggal']] = true;
	}

    $resultAbsensi = mysqli_query(
        $koneksi,
        "
        SELECT DISTINCT a.tanggal
        FROM absensi a
        LEFT JOIN siswa s ON s.id_siswa=a.idsiswa
        WHERE ({$kelasConditionSql})
        ORDER BY a.tanggal ASC
        "
    );
    while ($row = mysqli_fetch_assoc($resultAbsensi)) {
        $tgl = $row['tanggal'];
        $hariTanggal = date('D', strtotime($tgl));
        if ($hariTanggal && in_array(mykbm_normalize_hari($hariTanggal), $allowedDays, true)) {
            $tanggalSet[$tgl] = true;
        }
    }

	$dates = array_keys($tanggalSet);
	sort($dates);

	if (!empty($dates)) {
        $minDate = min($dates);
        $startTs = strtotime($minDate);
        $todayTs = strtotime(date('Y-m-d'));
        if ($startTs !== false && $todayTs !== false) {
            for ($ts = $startTs; $ts <= $todayTs; $ts = strtotime('+1 day', $ts)) {
                $hariTanggal = date('D', $ts);
                if ($hariTanggal && in_array(mykbm_normalize_hari($hariTanggal), $allowedDays, true)) {
                    $dates[] = date('Y-m-d', $ts);
                }
            }
        }
    }

	$dates = array_values(array_unique($dates));
	sort($dates);

	echo json_encode([
		'success' => true,
		'message' => '',
		'dates' => $dates
	]);
	exit;
}
if ($pg == 'bulk_harian') {
	header('Content-Type: application/json');
	$rows = isset($_POST['rows']) ? $_POST['rows'] : [];
	$idJadwal = isset($_POST['jadwal']) ? (int) $_POST['jadwal'] : 0;
	$kelas = isset($_POST['kelas']) ? mysqli_real_escape_string($koneksi, $_POST['kelas']) : '';
	$mapel = isset($_POST['mapel']) ? (int) $_POST['mapel'] : 0;
	$guru = isset($_POST['guru']) ? (int) $_POST['guru'] : 0;
	$tanggalGlobal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';

	if ($idJadwal === 0 || empty($rows) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalGlobal) || $kelas === '' || $mapel === 0 || $guru === 0) {
		echo json_encode([
			'success' => false,
			'message' => 'Data yang dikirim tidak lengkap.'
		]);
		exit;
	}

	$allowedStatus = ['H', 'A', 'S', 'I'];
	$updated = 0;
	$errors = 0;

	foreach ($rows as $row) {
		$idsiswa = isset($row['idsiswa']) ? (int) $row['idsiswa'] : 0;
		$ket = isset($row['ket']) ? strtoupper(trim($row['ket'])) : '';

		if ($idsiswa === 0 || !in_array($ket, $allowedStatus, true)) {
			$errors++;
			continue;
		}

		$tanggal = $tanggalGlobal;
		$time = strtotime($tanggal);
		if ($time === false) {
			$errors++;
			continue;
		}
		$bulan = date('m', $time);
		$tahun = date('Y', $time);

		$insert = "
		INSERT INTO absensi_harian (id_jadwal,tanggal,idsiswa,kelas,mapel,guru,ket,bulan,tahun)
		VALUES ($idJadwal,'$tanggal',$idsiswa,'$kelas',$mapel,$guru,'$ket','$bulan','$tahun')
		ON DUPLICATE KEY UPDATE ket=VALUES(ket), updated_at=NOW()
		";
		$execInsert = mysqli_query($koneksi, $insert);
		if ($execInsert) {
			$updated += mysqli_affected_rows($koneksi) > 0 ? 1 : 0;
		} else {
			$errors++;
		}

		mysqli_query($koneksi, "UPDATE absensi SET ket='$ket' WHERE idsiswa='$idsiswa' AND tanggal='$tanggal'");
	}

	if ($updated > 0) {
		echo json_encode([
			'success' => true,
			'message' => 'Data presensi berhasil disimpan.',
			'updated' => $updated,
			'errors' => $errors
		]);
	} else {
		echo json_encode([
			'success' => false,
			'message' => 'Tidak ada perubahan yang disimpan.',
			'errors' => $errors
		]);
	}
	exit;
}
