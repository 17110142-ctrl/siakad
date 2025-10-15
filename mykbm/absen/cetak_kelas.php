<?php
ob_start();

if (!defined('APK')) {
	require_once __DIR__ . "/../../config/koneksi.php";
	require_once __DIR__ . "/../../config/function.php";
	require_once __DIR__ . "/../../config/crud.php";
}

$id_user = isset($_SESSION['id_user']) ? (int) $_SESSION['id_user'] : 0;
if ($id_user === 0) {
	header('Location: ../index.php');
	exit;
}

if (!function_exists('mykbm_normalize_hari')) {
	function mykbm_normalize_hari($value)
	{
		$value = strtolower(trim($value));
		$map = [
			'mon' => 'Mon', 'monday' => 'Mon', 'senin' => 'Mon',
			'tue' => 'Tue', 'tuesday' => 'Tue', 'selasa' => 'Tue',
			'wed' => 'Wed', 'wednesday' => 'Wed', 'rabu' => 'Wed',
			'thu' => 'Thu', 'thursday' => 'Thu', 'kamis' => 'Thu',
			'fri' => 'Fri', 'friday' => 'Fri', 'jumat' => 'Fri', "jum'at" => 'Fri',
			'sat' => 'Sat', 'saturday' => 'Sat', 'sabtu' => 'Sat',
			'sun' => 'Sun', 'sunday' => 'Sun', 'minggu' => 'Sun', 'ahad' => 'Sun'
		];
		if (isset($map[$value])) {
			return $map[$value];
		}
		return ucfirst(substr($value, 0, 3));
	}
}

$jadwalParam = isset($_GET['j']) ? trim($_GET['j']) : '';
$selectedDate = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
	$selectedDate = date('Y-m-d');
}
$selectedTs = strtotime($selectedDate);
$bulan = (int) date('m', $selectedTs);
$tahun = (int) date('Y', $selectedTs);
$bulanKey = sprintf('%02d', $bulan);
$bulanRow = fetch($koneksi, 'bulan', ['bln' => $bulanKey]);
$namaBulan = $bulanRow ? $bulanRow['ket'] : date('F', $selectedTs);

$jadwalIds = array_values(array_filter(array_map('intval', explode(',', $jadwalParam))));
if (empty($jadwalIds)) {
	echo "<script>alert('Jadwal tidak ditemukan.');window.close();</script>";
	exit;
}
$idListSql = implode(',', $jadwalIds);

$jadwalQuery = mysqli_query($koneksi, "SELECT * FROM jadwal_mapel WHERE id_jadwal IN ($idListSql)");
if (!$jadwalQuery || mysqli_num_rows($jadwalQuery) === 0) {
	echo "<script>alert('Jadwal tidak ditemukan.');window.close();</script>";
	exit;
}

$jadwalRows = [];
$allowedDays = [];
while ($row = mysqli_fetch_assoc($jadwalQuery)) {
	$jadwalRows[] = $row;
	$allowedDays[] = mykbm_normalize_hari($row['hari']);
}
$allowedDays = array_values(array_unique(array_filter($allowedDays)));
if (empty($allowedDays)) {
	$allowedDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
}

$baseJadwal = $jadwalRows[0];
$kelasRaw = $baseJadwal['kelas'];
$kelasEscaped = mysqli_real_escape_string($koneksi, $kelasRaw);
$mapelId = (int) $baseJadwal['mapel'];
$guruId = (int) $baseJadwal['guru'];
$mapel = fetch($koneksi, 'mata_pelajaran', ['id' => $mapelId]);
$guru = fetch($koneksi, 'users', ['id_user' => $guruId]);

$kelasCompact = preg_replace('/[^A-Za-z0-9]/', '', $kelasRaw);
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
	"a.kelas='{$kelasEscaped}'",
	"(s.kelas IS NOT NULL AND s.kelas='{$kelasEscaped}')"
];
if ($kelasCompact !== '') {
	$kelasConditions[] = "{$kelasSanitizeAbsensi}='{$kelasCompactEscaped}'";
	$kelasConditions[] = "{$kelasSanitizeSiswa}='{$kelasCompactEscaped}'";
}
$kelasConditionSql = implode(' OR ', array_unique($kelasConditions));
if ($kelasConditionSql === '') {
	$kelasConditionSql = '1=0';
}

$levelTokens = preg_split('/[\s-]+/', $kelasRaw);
$levelKelas = isset($levelTokens[0]) ? mysqli_real_escape_string($koneksi, $levelTokens[0]) : '';
$liburQuery = mysqli_query($koneksi, "SELECT tanggal FROM hari_libur WHERE MONTH(tanggal)='{$bulan}' AND YEAR(tanggal)='{$tahun}' AND (kelas IS NULL OR kelas='{$levelKelas}')");
$hariLiburNasional = [];
while ($row = mysqli_fetch_assoc($liburQuery)) {
	$hariLiburNasional[] = (int) date('j', strtotime($row['tanggal']));
}

$students = [];
$studentQuery = mysqli_query($koneksi, "SELECT id_siswa, nama FROM siswa WHERE kelas='{$kelasEscaped}' ORDER BY nama ASC");
while ($row = mysqli_fetch_assoc($studentQuery)) {
	$students[] = $row;
}

$attendance = [];
$pertemuanDates = [];
$harianResult = mysqli_query(
	$koneksi,
	"SELECT ah.idsiswa, ah.tanggal, ah.ket
	 FROM absensi_harian ah
	 WHERE ah.id_jadwal IN ($idListSql)
	   AND MONTH(ah.tanggal)='{$bulan}'
	   AND YEAR(ah.tanggal)='{$tahun}'"
);
while ($row = mysqli_fetch_assoc($harianResult)) {
	$dayNorm = mykbm_normalize_hari(date('D', strtotime($row['tanggal'])));
	if (!in_array($dayNorm, $allowedDays, true)) {
		continue;
	}
	$attendance[$row['idsiswa']][$row['tanggal']] = strtoupper($row['ket']);
	$pertemuanDates[$row['tanggal']] = true;
}

$fallbackResult = mysqli_query(
	$koneksi,
	"SELECT a.idsiswa, a.tanggal, a.ket
	 FROM absensi a
	 LEFT JOIN siswa s ON s.id_siswa=a.idsiswa
	 WHERE MONTH(a.tanggal)='{$bulan}'
	   AND YEAR(a.tanggal)='{$tahun}'
	   AND ({$kelasConditionSql})"
);
while ($row = mysqli_fetch_assoc($fallbackResult)) {
	$dayNorm = mykbm_normalize_hari(date('D', strtotime($row['tanggal'])));
	if (!in_array($dayNorm, $allowedDays, true)) {
		continue;
	}
	if (!isset($attendance[$row['idsiswa']][$row['tanggal']])) {
		$attendance[$row['idsiswa']][$row['tanggal']] = strtoupper($row['ket']);
	}
	$pertemuanDates[$row['tanggal']] = true;
}

$pertemuanCount = count($pertemuanDates);
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
$statusList = ['H', 'S', 'I', 'A'];

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Rekap Presensi Mapel <?= htmlspecialchars($mapel['nama_mapel'] ?? '') ?></title>
	<link rel="stylesheet" href="../../vendor/css/cetak.min.css">
	<style>
		@page {
			margin: 80px;
		}

		body {
			margin: 20px;
			font-size: 12px;
		}

		.libur {
			background-color: #ffdddd;
			color: #d32f2f;
		}

		.status-A {
			color: #d32f2f;
			font-weight: bold;
		}

		.status-S,
		.status-I {
			color: #f57c00;
			font-weight: bold;
		}

		.status-H {
			color: #388e3c;
			font-weight: bold;
		}
	</style>
</head>

<body>
	<div style="background:#fff; width:97%; margin:0 auto;">
		<table width="100%">
			<tr>
				<td width="100">
					<img src="../../images/<?= $setting['logo'] ?>" width="70">
				</td>
				<td style="text-align:center">
					<strong><?= strtoupper($setting['header']) ?><br><?= strtoupper($setting['sekolah']) ?></strong><br>
					<small>Alamat : <?= $setting['alamat'] ?> Kec. <?= $setting['kecamatan'] ?> Kab. <?= $setting['kabupaten'] ?> Email : <?= $setting['email'] ?></small>
				</td>
			</tr>
		</table>
		<hr style="margin:1px">
		<hr style="margin:2px"><br>
		<center>
			<h4>REKAPITULASI PRESENSI GURU MAPEL</h4>
		</center>
		<br>
		<table width="100%">
			<tr>
				<td width="8%"></td>
				<td width="120">Sekolah</td>
				<td width="10">:</td>
				<td><?= $setting['sekolah'] ?></td>
				<td width="40%"></td>
				<td width="120">Bulan</td>
				<td width="10">:</td>
				<td><?= htmlspecialchars($namaBulan) ?> <?= $tahun ?></td>
			</tr>
			<tr>
				<td></td>
				<td>Kelas</td>
				<td>:</td>
				<td><?= htmlspecialchars($kelasRaw) ?></td>
				<td></td>
				<td>Mata Pelajaran</td>
				<td>:</td>
				<td><?= htmlspecialchars($mapel['nama_mapel'] ?? '-') ?></td>
			</tr>
			<tr>
				<td></td>
				<td>Guru Pengampu</td>
				<td>:</td>
				<td><?= htmlspecialchars($guru['nama'] ?? '-') ?></td>
				<td></td>
				<td>Pertemuan</td>
				<td>:</td>
				<td><?= $pertemuanCount ?> Pertemuan</td>
			</tr>
		</table>
		<br>
		<table class="it-grid it-cetak" width="100%">
			<tr>
				<th width="3%" height="40">No</th>
				<th width="27%">Nama Siswa</th>
				<?php
				$today = strtotime(date('Y-m-d'));
				for ($day = 1; $day <= $daysInMonth; $day++) {
					$dateObj = mktime(0, 0, 0, $bulan, $day, $tahun);
					$dayOfWeek = date('D', $dateObj);
					$normDay = mykbm_normalize_hari($dayOfWeek);
					$isHoliday = in_array($day, $hariLiburNasional, true);
					$isWeekend = ($setting['hari_sekolah'] == 5 && (date('N', $dateObj) >= 6)) || ($setting['hari_sekolah'] == 6 && date('N', $dateObj) == 7);
					$isLibur = $isHoliday || $isWeekend;
					$isPertemuan = in_array($normDay, $allowedDays, true);
					$style = '';
					if ($isLibur) {
						$style = 'class="libur"';
					} elseif (!$isPertemuan) {
						$style = 'style="color:#aaa"';
					}
					echo "<th width=\"2%\" {$style}>{$day}</th>";
				}
				?>
				<th width="2%">H</th>
				<th width="2%">S</th>
				<th width="2%">I</th>
				<th width="2%">A</th>
			</tr>
			<?php
			if (empty($students)) :
			?>
				<tr>
					<td colspan="<?= 4 + $daysInMonth ?>" class="text-center">Tidak ada data siswa untuk kelas ini.</td>
				</tr>
			<?php
			else :
				$no = 0;
				foreach ($students as $student) :
					$no++;
					$idSiswa = (int) $student['id_siswa'];
					$statusCount = array_fill_keys($statusList, 0);
			?>
					<tr>
						<td class="text-center"><?= $no ?></td>
						<td>&nbsp;<?= ucwords(strtolower($student['nama'])) ?></td>
						<?php
						for ($day = 1; $day <= $daysInMonth; $day++) {
							$dateCurrent = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
							$dateTs = strtotime($dateCurrent);
							$dayOfWeek = date('D', $dateTs);
							$normDay = mykbm_normalize_hari($dayOfWeek);
							$isHoliday = in_array($day, $hariLiburNasional, true);
							$isWeekend = ($setting['hari_sekolah'] == 5 && (date('N', $dateTs) >= 6)) || ($setting['hari_sekolah'] == 6 && date('N', $dateTs) == 7);
							$isLibur = $isHoliday || $isWeekend;
							$isPertemuan = in_array($normDay, $allowedDays, true);
							$ket = isset($attendance[$idSiswa][$dateCurrent]) ? strtoupper(trim($attendance[$idSiswa][$dateCurrent])) : '';
							$cellClass = '';
							$cellValue = '';

							if ($ket !== '' && in_array($ket, $statusList, true)) {
								$statusCount[$ket]++;
								$cellClass = 'status-' . $ket;
								$cellValue = $ket;
							} elseif ($isLibur && !$isPertemuan) {
								$cellClass = 'libur';
								$cellValue = 'X';
							} elseif ($isLibur) {
								$cellClass = 'libur';
								$cellValue = 'X';
							} else {
								$cellValue = $isPertemuan && $dateTs <= $today ? '-' : '';
							}

							echo "<td class=\"text-center {$cellClass}\">{$cellValue}</td>";
						}
						?>
						<td class="text-center"><?= $statusCount['H'] ?></td>
						<td class="text-center"><?= $statusCount['S'] ?></td>
						<td class="text-center"><?= $statusCount['I'] ?></td>
						<td class="text-center"><?= $statusCount['A'] ?></td>
					</tr>
			<?php
				endforeach;
			endif;
			?>
		</table>
		<br>
		<p>H : HADIR &nbsp;&nbsp; S : SAKIT &nbsp;&nbsp; I : IZIN &nbsp;&nbsp; A : TANPA KETERANGAN &nbsp;&nbsp; X : LIBUR / TIDAK ADA PERTEMUAN</p>
		<br>
		<table width="100%">
			<tr>
				<td width="5%"></td>
				<td>
					Mengetahui,<br>
					Kepala Sekolah<br><br><br><br>
					<u><?= $setting['kepsek'] ?></u><br>
					<?= $setting['no_guru'] ?> <?= $setting['nip'] ?>
				</td>
				<td width="40%"></td>
				<td>
					<?= ucwords(strtolower($setting['kabupaten'])) ?>, <?= str_pad($daysInMonth, 2, '0', STR_PAD_LEFT) ?> <?= htmlspecialchars($namaBulan) ?> <?= $tahun ?><br>
					Guru Mapel<br><br><br><br>
					<u><?= htmlspecialchars($guru['nama'] ?? '-') ?></u><br>
					<?= $setting['no_guru'] ?> <?= htmlspecialchars($guru['no_guru'] ?? '') ?>
				</td>
			</tr>
		</table>
	</div>
</body>

</html>
<?php
$html = ob_get_clean();
require_once __DIR__ . '/../../vendor/vendors/autoload.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'Landscape');
$dompdf->render();
$fileName = "Rekap Presensi Mapel " . preg_replace('/[^A-Za-z0-9_\- ]/', '', $mapel['nama_mapel'] ?? 'mapel') . " {$kelasRaw} {$bulan}-{$tahun}.pdf";
$dompdf->stream($fileName, ['Attachment' => false]);
exit(0);
?>
