<?php
require "../../config/koneksi.php";
require "../../vendor/autoload.php";
require "../../config/function.php";
require "../../config/crud.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$mode  = strtolower($_GET['mode'] ?? 'mapel');
$kelas = trim($_GET['k'] ?? '');
if ($kelas === '') {
    exit('Parameter kelas wajib diisi.');
}

$mapelIdParam = isset($_GET['m']) ? (int)$_GET['m'] : 0;
$guruParam    = isset($_GET['g']) ? (int)$_GET['g'] : 0;

$setting  = fetch($koneksi, 'setting', ['id_setting' => 1]);
$semester = $_GET['s'] ?? ($setting['semester'] ?? '');
$tapel    = $_GET['tp'] ?? ($setting['tp'] ?? '');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$styleHeader = [
    'font' => ['bold' => true],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical'   => Alignment::VERTICAL_CENTER,
    ],
    'borders' => [
        'top'    => ['borderStyle' => Border::BORDER_THIN],
        'right'  => ['borderStyle' => Border::BORDER_THIN],
        'bottom' => ['borderStyle' => Border::BORDER_THIN],
        'left'   => ['borderStyle' => Border::BORDER_THIN],
    ],
];

$styleRow = [
    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
    'borders' => [
        'top'    => ['borderStyle' => Border::BORDER_THIN],
        'right'  => ['borderStyle' => Border::BORDER_THIN],
        'bottom' => ['borderStyle' => Border::BORDER_THIN],
        'left'   => ['borderStyle' => Border::BORDER_THIN],
    ],
];

$kelas_safe = mysqli_real_escape_string($koneksi, $kelas);
$siswa_res = mysqli_query($koneksi, "SELECT id_siswa, nis, nama, kelas, level, jurusan FROM siswa WHERE kelas='$kelas_safe' ORDER BY nama");
$siswa_list = [];
while ($row = mysqli_fetch_assoc($siswa_res)) {
    $siswa_list[] = $row;
}
if (empty($siswa_list)) {
    exit('Tidak ditemukan siswa pada kelas tersebut.');
}

$mapel_entries = [];
$class_level = $siswa_list[0]['level'] ?? '';
$class_jurusan = $siswa_list[0]['jurusan'] ?? '';
if ($class_jurusan === '' || $class_jurusan === null) {
    $class_jurusan = 'semua';
}

$mapel_rapor_entries = [];
if ($class_level !== '') {
    $levelEsc = mysqli_real_escape_string($koneksi, $class_level);
    $jurEsc   = mysqli_real_escape_string($koneksi, $class_jurusan);
    $sqlRapor = "SELECT mr.mapel, mp.kode, mp.nama_mapel FROM mapel_rapor mr JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.tingkat='$levelEsc' AND (mr.pk='$jurEsc' OR mr.pk='semua') ORDER BY mr.urut, mp.nama_mapel";
    $resRapor = mysqli_query($koneksi, $sqlRapor);
    while ($row = mysqli_fetch_assoc($resRapor)) {
        $mapel_rapor_entries[] = [
            'id'   => (int)$row['mapel'],
            'kode' => $row['kode'],
            'nama' => $row['nama_mapel'],
            'guru_id' => null,
        ];
    }
}

if ($mode === 'all') {
    if (!empty($mapel_rapor_entries)) {
        $mapel_entries = $mapel_rapor_entries;
    } else {
        $mapel_res = mysqli_query($koneksi, "SELECT jm.mapel, mp.kode, mp.nama_mapel, MIN(jm.guru) AS guru_id FROM jadwal_mapel jm JOIN mata_pelajaran mp ON mp.id = jm.mapel WHERE jm.kelas = '$kelas_safe' GROUP BY jm.mapel, mp.kode, mp.nama_mapel ORDER BY mp.nama_mapel");
        while ($row = mysqli_fetch_assoc($mapel_res)) {
            $mapel_entries[] = [
                'id'       => (int)$row['mapel'],
                'kode'     => $row['kode'],
                'nama'     => $row['nama_mapel'],
                'guru_id'  => $row['guru_id'] ? (int)$row['guru_id'] : null,
            ];
        }

        if (empty($mapel_entries) && $class_level !== '') {
            $level_safe = mysqli_real_escape_string($koneksi, $class_level);
            $mapel_res = mysqli_query($koneksi, "SELECT DISTINCT mr.mapel, mp.kode, mp.nama_mapel FROM mapel_rapor mr JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.tingkat = '$level_safe' ORDER BY mr.urut");
            while ($row = mysqli_fetch_assoc($mapel_res)) {
                $mapel_entries[] = [
                    'id'      => (int)$row['mapel'],
                    'kode'    => $row['kode'],
                    'nama'    => $row['nama_mapel'],
                    'guru_id' => null,
                ];
            }
        }

        if (empty($mapel_entries)) {
            $mapel_res = mysqli_query($koneksi, "SELECT id, kode, nama_mapel FROM mata_pelajaran ORDER BY nama_mapel");
            while ($row = mysqli_fetch_assoc($mapel_res)) {
                $mapel_entries[] = [
                    'id'      => (int)$row['id'],
                    'kode'    => $row['kode'],
                    'nama'    => $row['nama_mapel'],
                    'guru_id' => null,
                ];
            }
        }
    }

    if (!empty($mapel_entries)) {
        $stmtGuruLookup = $koneksi->prepare("SELECT guru FROM jadwal_mapel WHERE kelas = ? AND mapel = ? LIMIT 1");
        foreach ($mapel_entries as $idx => $entry) {
            if (!empty($entry['guru_id'])) {
                continue;
            }
            $mapelIdLookup = $entry['id'];
            $stmtGuruLookup->bind_param('si', $kelas_safe, $mapelIdLookup);
            $stmtGuruLookup->execute();
            $resGuru = $stmtGuruLookup->get_result();
            if ($resGuru) {
                $rowGuru = $resGuru->fetch_assoc();
                if ($rowGuru && !empty($rowGuru['guru'])) {
                    $mapel_entries[$idx]['guru_id'] = (int)$rowGuru['guru'];
                }
                $resGuru->free();
            }
        }
        $stmtGuruLookup->close();
    }
} else {
    if ($mapelIdParam === 0) {
        exit('Parameter mapel wajib diisi.');
    }
    $mapel_info = fetch($koneksi, 'mata_pelajaran', ['id' => $mapelIdParam]);
    if (!$mapel_info) {
        exit('Data mata pelajaran tidak ditemukan.');
    }

    $guru_id = $guruParam ?: null;
    if ($guru_id === null) {
        $mapelIdSafe = mysqli_real_escape_string($koneksi, (string)$mapelIdParam);
        $jadwal = mysqli_query($koneksi, "SELECT guru FROM jadwal_mapel WHERE kelas='$kelas_safe' AND mapel='$mapelIdSafe' LIMIT 1");
        if ($jadwal_row = mysqli_fetch_assoc($jadwal)) {
            $guru_id = (int)$jadwal_row['guru'];
        }
    }

    $mapel_entries[] = [
        'id'      => $mapelIdParam,
        'kode'    => $mapel_info['kode'] ?? '',
        'nama'    => $mapel_info['nama_mapel'] ?? '',
        'guru_id' => $guru_id,
    ];
}

if (empty($mapel_entries)) {
    exit('Daftar mata pelajaran tidak ditemukan.');
}

$guru_ids = array_filter(array_unique(array_map(function ($entry) {
    return $entry['guru_id'];
}, $mapel_entries)));

$guru_names = [];
if (!empty($guru_ids)) {
    $in = implode(',', array_map('intval', $guru_ids));
    $guru_res = mysqli_query($koneksi, "SELECT id_user, nama FROM users WHERE id_user IN ($in)");
    while ($row = mysqli_fetch_assoc($guru_res)) {
        $guru_names[(int)$row['id_user']] = $row['nama'];
    }
}

if ($mode === 'all') {
    $sheet->setCellValue('A1', "FORMAT UPLOAD NILAI SAS SEMUA MAPEL KELAS $kelas");
} else {
    $kode = $mapel_entries[0]['kode'] ?? 'MAPEL';
    $sheet->setCellValue('A1', "FORMAT UPLOAD NILAI SAS $kode $kelas");
}

$mapelStartColIndex = 4; // column D
$mapelCount = count($mapel_entries);
$mapelCount = max(0, $mapelCount);
if ($mapelCount === 0) {
    exit('Daftar mata pelajaran belum diatur untuk kelas ini.');
}
$lastColIndex = $mapelStartColIndex + $mapelCount - 1;
$lastColLetter = Coordinate::stringFromColumnIndex($lastColIndex);

$sheet->mergeCells('A1:' . $lastColLetter . '1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', '');
$sheet->setCellValue('B2', '');
$sheet->setCellValue('C2', '');
$sheet->mergeCells('D2:' . $lastColLetter . '2');
$sheet->setCellValue('D2', 'Nilai SAS');
$sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A3', 'No');
$sheet->setCellValue('B3', 'NIS');
$sheet->setCellValue('C3', 'Nama');
$sheet->getStyle('A3')->applyFromArray($styleHeader);
$sheet->getStyle('B3')->applyFromArray($styleHeader);
$sheet->getStyle('C3')->applyFromArray($styleHeader);

$sheet->setCellValue('A4', '');
$sheet->setCellValue('B4', '');
$sheet->setCellValue('C4', '');

for ($i = 0; $i < $mapelCount; $i++) {
    $colIndex = $mapelStartColIndex + $i;
    $colLetter = Coordinate::stringFromColumnIndex($colIndex);
    $mapelEntry = $mapel_entries[$i];
    $headerText = $mapelEntry['kode'] ?: $mapelEntry['nama'];
    $sheet->setCellValue($colLetter . '3', $headerText);
    $sheet->setCellValue($colLetter . '4', $mapelEntry['id']);
    $sheet->getStyle($colLetter . '3')->applyFromArray($styleHeader);
}

$sheet->getRowDimension(4)->setVisible(false);

$rowIndex = 5;
$counter = 1;
foreach ($siswa_list as $siswa) {
    $sheet->setCellValue('A' . $rowIndex, $counter++);
    $sheet->setCellValue('B' . $rowIndex, $siswa['nis']);
    $sheet->setCellValue('C' . $rowIndex, strtoupper($siswa['nama']));
    $sheet->getStyle('A' . $rowIndex)->applyFromArray($styleRow);
    $sheet->getStyle('B' . $rowIndex)->applyFromArray($styleRow);
    $sheet->getStyle('C' . $rowIndex)->applyFromArray($styleRow);

    for ($i = 0; $i < $mapelCount; $i++) {
        $colLetter = Coordinate::stringFromColumnIndex($mapelStartColIndex + $i);
        $sheet->setCellValue($colLetter . $rowIndex, 0);
        $sheet->getStyle($colLetter . $rowIndex)->applyFromArray($styleRow);
    }
    $rowIndex++;
}

foreach (range(1, $lastColIndex) as $colIdx) {
    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($colIdx))->setAutoSize(true);
}

$sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$sheet->setTitle('Nilai SAS');

if ($mode === 'all') {
    $filename = 'Format_Nilai_SAS_Semua_Mapel_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $kelas) . '.xlsx';
} else {
    $kode = $mapel_entries[0]['kode'] ?? 'MAPEL';
    $filename = 'Format_Nilai_SAS_' . $kode . '_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $kelas) . '.xlsx';
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
