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
		return isset($map[$value]) ? $map[$value] : ucfirst(substr($value, 0, 3));
	}
}

if (!function_exists('mykbm_bulan_indo')) {
	function mykbm_bulan_indo($index)
	{
		$bulan = [
			1 => 'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		];
		return isset($bulan[$index]) ? $bulan[$index] : '';
	}
}

if (!function_exists('mykbm_format_tanggal_indo')) {
	function mykbm_format_tanggal_indo($timestamp)
	{
		return date('d', $timestamp) . ' ' . mykbm_bulan_indo((int) date('n', $timestamp)) . ' ' . date('Y', $timestamp);
	}
}

if (!function_exists('mykbm_logo_src')) {
	function mykbm_logo_src($path, $fallback)
	{
		if (!is_file($path)) {
			return $fallback;
		}

		$info = @getimagesize($path);
		if (!$info || !isset($info['mime'])) {
			return $fallback;
		}

		$mime = strtolower($info['mime']);
		$binary = '';

		if ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
			$png = @imagecreatefrompng($path);
			if ($png) {
				$width = imagesx($png);
				$height = imagesy($png);
				$canvas = imagecreatetruecolor($width, $height);
				$white = imagecolorallocate($canvas, 255, 255, 255);
				imagefill($canvas, 0, 0, $white);
				imagealphablending($canvas, true);
				imagecopy($canvas, $png, 0, 0, 0, 0, $width, $height);
				ob_start();
				imagepng($canvas);
				$binary = ob_get_clean();
				imagedestroy($canvas);
				imagedestroy($png);
			}
		}

		if ($binary === '') {
			$binary = @file_get_contents($path);
		}

		if ($binary === false || $binary === '') {
			return $fallback;
		}

		return 'data:' . $mime . ';base64,' . base64_encode($binary);
	}
}

$jadwalParam = isset($_GET['j']) ? trim($_GET['j']) : '';
$selectedDate = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
	$selectedDate = date('Y-m-d');
}
$selectedTs = strtotime($selectedDate ?: date('Y-m-d'));

$tpString = isset($_GET['tp']) ? trim($_GET['tp']) : (isset($setting['tp']) ? $setting['tp'] : '');
if (preg_match('/(\d{4})\D+(\d{4})/', $tpString, $matchTp)) {
	$tpStartYear = (int) $matchTp[1];
	$tpEndYear = (int) $matchTp[2];
} else {
	$tpStartYear = (int) date('Y', $selectedTs);
	$tpEndYear = $tpStartYear + 1;
	$tpString = $tpStartYear . '/' . $tpEndYear;
}

$semesterSource = isset($_GET['semester']) ? trim($_GET['semester']) : (isset($setting['semester']) ? $setting['semester'] : '');
if ($semesterSource === '' && $selectedDate) {
	$semesterSource = (int) date('n', $selectedTs) >= 7 ? '1' : '2';
}
$semesterLower = strtolower($semesterSource);
$semesterCode = ($semesterLower === '2' || $semesterLower === 'genap') ? '2' : '1';
$semesterLabel = $semesterCode === '2' ? 'Genap' : 'Ganjil';

if ($semesterCode === '2') {
	$rangeStart = sprintf('%04d-01-01', $tpEndYear);
	$rangeEnd = sprintf('%04d-06-30', $tpEndYear);
} else {
	$rangeStart = sprintf('%04d-07-01', $tpStartYear);
	$rangeEnd = sprintf('%04d-12-31', $tpStartYear);
}
$tpDisplay = $tpString;

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
	   AND ah.tanggal BETWEEN '{$rangeStart}' AND '{$rangeEnd}'"
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
	 WHERE a.tanggal BETWEEN '{$rangeStart}' AND '{$rangeEnd}'
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

$pertemuanList = array_keys($pertemuanDates);
sort($pertemuanList);
$pertemuanCount = count($pertemuanList);
$tanggalTtd = $pertemuanCount > 0 ? end($pertemuanList) : $selectedDate;
$tanggalTtd = $tanggalTtd ?: date('Y-m-d');
$tanggalTtdFormattedTs = strtotime($tanggalTtd);
$tanggalTtdFormatted = mykbm_format_tanggal_indo($tanggalTtdFormattedTs);
$statusList = ['H', 'S', 'I', 'A'];

$pertemuanPerPage = 25;
$tableChunks = !empty($pertemuanList) ? array_chunk($pertemuanList, $pertemuanPerPage) : [];
$studentTotals = [];
foreach ($students as $studentData) {
	$idSiswa = (int) $studentData['id_siswa'];
	$studentTotals[$idSiswa] = array_fill_keys($statusList, 0);
	if (isset($attendance[$idSiswa])) {
		foreach ($attendance[$idSiswa] as $ketValue) {
			$ketValue = strtoupper(trim($ketValue));
			if (isset($studentTotals[$idSiswa][$ketValue])) {
				$studentTotals[$idSiswa][$ketValue]++;
			}
		}
	}
}

$logoPath = __DIR__ . '/../../images/' . $setting['logo'];
$logoSrc = mykbm_logo_src($logoPath, '../../images/' . $setting['logo']);

$renderHeader = function () use ($logoSrc, $setting, $semesterLabel, $kelasRaw, $tpDisplay, $mapel, $guru) {
?>
	<table width="100%">
		<tr>
			<td width="130">
				<img src="<?= htmlspecialchars($logoSrc, ENT_QUOTES, 'UTF-8') ?>" width="110" style="display:block;">
			</td>
			<td style="text-align:center">
				<strong><?= strtoupper($setting['header']) ?><br><?= strtoupper($setting['sekolah']) ?></strong><br>
				<small>Alamat : <?= $setting['alamat'] ?>  Email : <?= $setting['email'] ?></small>
			</td>
		</tr>
	</table>
	<hr style="margin:1px">
	<hr style="margin:2px"><br>

	<center><h4 class="title-presensi">REKAPITULASI PRESENSI GURU MAPEL</h4></center>
	<br>

	<table width="100%">
		<tr>
			<td width="8%"></td>
			<td width="120">Sekolah</td>
			<td width="10">:</td>
			<td><?= $setting['sekolah'] ?></td>
			<td width="40%"></td>
			<td width="120">Semester</td>
			<td width="10">:</td>
			<td><?= $semesterLabel ?></td>
		</tr>
		<tr>
			<td></td>
			<td>Kelas</td>
			<td>:</td>
			<td><?= htmlspecialchars($kelasRaw) ?></td>
			<td></td>
			<td>Tahun Pelajaran</td>
			<td>:</td>
			<td><?= htmlspecialchars($tpDisplay) ?></td>
		</tr>
		<tr>
			<td></td>
			<td>Mata Pelajaran</td>
			<td>:</td>
			<td><?= htmlspecialchars($mapel['nama_mapel'] ?? '-') ?></td>
			<td></td>
			<td>Guru Pengampu</td>
			<td>:</td>
			<td><?= htmlspecialchars($guru['nama'] ?? '-') ?></td>
		</tr>
	</table>
	<br>
<?php
};
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
		font-family: 'DejaVu Sans', sans-serif;
		margin: 20px;
		font-size: 12px;
	}

	.page-break {
		page-break-before: always;
	}

	.presensi-table {
		table-layout: fixed;
		width: 100%;
	}

	.presensi-table th,
	.presensi-table td {
		text-align: center;
		padding: 6px 4px;
	}

	.presensi-table td.nama-cell {
		text-align: left;
		padding-left: 8px;
		word-break: break-word;
	}

	.header-cell {
		vertical-align: middle;
	}

	.pertemuan-number {
		display: block;
		font-weight: bold;
	}

	.pertemuan-date {
		display: block;
		font-size: 11px;
		margin-top: 2px;
	}

	.title-presensi {
		font-weight: bold;
	}
	</style>
</head>

<body>
<?php if (empty($students) || empty($pertemuanList)) : ?>
	<div style="background:#fff;width:97%;margin:0 auto;">
		<?php $renderHeader(); ?>
		<table class="it-grid it-cetak" width="100%">
			<tr>
				<td class="text-center">Tidak ada data presensi pada semester ini.</td>
			</tr>
		</table>
	</div>
<?php else : ?>
	<?php $totalChunks = count($tableChunks); ?>
	<?php foreach ($tableChunks as $chunkIndex => $chunkDates) : ?>
		<?php if ($chunkIndex > 0) : ?>
			<div class="page-break"></div>
		<?php endif; ?>

		<div style="background:#fff;width:97%;margin:0 auto;">
			<?php $renderHeader(); ?>

			<table class="it-grid it-cetak presensi-table">
				<thead>
					<tr>
						<th width="3%" rowspan="2" class="text-center header-cell">No</th>
						<th width="27%" rowspan="2" class="text-center header-cell">Nama Siswa</th>
						<th colspan="<?= $pertemuanPerPage ?>" class="text-center">Pertemuan</th>
						<th width="2%" rowspan="2" class="text-center header-cell">H</th>
						<th width="2%" rowspan="2" class="text-center header-cell">S</th>
						<th width="2%" rowspan="2" class="text-center header-cell">I</th>
						<th width="2%" rowspan="2" class="text-center header-cell">A</th>
					</tr>
					<tr>
						<?php
						for ($slot = 0; $slot < $pertemuanPerPage; $slot++) {
							if ($slot < count($chunkDates)) {
								$number = ($chunkIndex * $pertemuanPerPage) + $slot + 1;
								$dateTs = strtotime($chunkDates[$slot]);
								$dateLabel = date('d-m', $dateTs);
								echo '<th width="3%" class="text-center"><span class="pertemuan-number">' . $number . '</span><span class="pertemuan-date">' . $dateLabel . '</span></th>';
							} else {
								echo '<th width="3%">&nbsp;</th>';
							}
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($students as $indexSiswa => $student) : ?>
						<?php
						$nomorSiswa = $indexSiswa + 1;
						$idSiswa = (int) $student['id_siswa'];
						$totals = isset($studentTotals[$idSiswa]) ? $studentTotals[$idSiswa] : array_fill_keys(['H','S','I','A'], 0);
						?>
						<tr>
							<td class="text-center"><?= $nomorSiswa ?></td>
							<td class="nama-cell"><?= ucwords(strtolower($student['nama'])) ?></td>
							<?php
							for ($slot = 0; $slot < $pertemuanPerPage; $slot++) {
								if ($slot < count($chunkDates)) {
									$tanggalPertemuan = $chunkDates[$slot];
									$ket = isset($attendance[$idSiswa][$tanggalPertemuan]) ? strtoupper(trim($attendance[$idSiswa][$tanggalPertemuan])) : '';
									if ($ket === 'H') {
										$cellValue = '✓';
									} elseif ($ket !== '') {
										$cellValue = $ket;
									} else {
										$cellValue = '';
									}
									echo '<td class="text-center">' . $cellValue . '</td>';
								} else {
									echo '<td>&nbsp;</td>';
								}
							}
							?>
							<td class="text-center"><?= $totals['H'] ?></td>
							<td class="text-center"><?= $totals['S'] ?></td>
							<td class="text-center"><?= $totals['I'] ?></td>
							<td class="text-center"><?= $totals['A'] ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<br>
			<p>✓ : HADIR &nbsp;&nbsp; S : SAKIT &nbsp;&nbsp; I : IZIN &nbsp;&nbsp; A : TANPA KETERANGAN</p>
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
						<?= ucwords(strtolower($setting['kabupaten'])) ?>, <?= $tanggalTtdFormatted ?><br>
						Guru Mapel<br><br><br><br>
						<u><?= htmlspecialchars($guru['nama'] ?? '-') ?></u><br>
						<?= $setting['no_guru'] ?> <?= htmlspecialchars($guru['nip'] ?? '') ?>
					</td>
				</tr>
			</table>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
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

$mapelSafe = preg_replace('/[^A-Za-z0-9_\- ]/', '', $mapel['nama_mapel'] ?? 'mapel');
$kelasSafe = preg_replace('/[^A-Za-z0-9_\- ]/', '', $kelasRaw);
$tpSafe = preg_replace('/[^A-Za-z0-9_\- ]/', '-', $tpDisplay);
$fileName = "Rekap Presensi Mapel {$mapelSafe} {$kelasSafe} Semester {$semesterLabel} {$tpSafe}.pdf";
$dompdf->stream($fileName, ['Attachment' => false]);
exit(0);
?>
