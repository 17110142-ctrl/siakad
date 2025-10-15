<?php
defined('APK') or exit('No Access');

$selectedDate = isset($_GET['tgl']) ? $_GET['tgl'] : '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    $selectedDate = '';
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

if (!function_exists('mykbm_label_hari')) {
    function mykbm_label_hari($norm)
    {
        $map = [
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu',
            'Sun' => 'Minggu',
        ];
        return isset($map[$norm]) ? $map[$norm] : $norm;
    }
}

$whereGuru = '';
if ($user['level'] === 'guru') {
    $guruId = mysqli_real_escape_string($koneksi, $user['id_user']);
    $whereGuru = "WHERE jm.guru='{$guruId}'";
}

$jadwalResult = mysqli_query(
    $koneksi,
    "SELECT jm.*, mp.nama_mapel, mp.kode, u.nama AS nama_guru
    FROM jadwal_mapel jm
    LEFT JOIN mata_pelajaran mp ON mp.id=jm.mapel
    LEFT JOIN users u ON u.id_user=jm.guru
    {$whereGuru}
    ORDER BY jm.hari, jm.kelas, mp.nama_mapel"
);

$jadwalGrouped = [];
while ($row = mysqli_fetch_assoc($jadwalResult)) {
    $normHari = mykbm_normalize_hari($row['hari']);
    $labelHari = mykbm_label_hari($normHari);
    $key = $row['kelas'] . '|' . $row['mapel'] . '|' . $row['guru'];
    $row['id_jadwal'] = (int) $row['id_jadwal'];
    if (!isset($jadwalGrouped[$key])) {
        $row['id_jadwal_list'] = [$row['id_jadwal']];
        $row['hari_list'] = [$normHari];
        $row['hari_display_list'] = [$labelHari];
        $row['day_to_id'] = [$normHari => $row['id_jadwal']];
        $jadwalGrouped[$key] = $row;
    } else {
        $jadwalGrouped[$key]['id_jadwal_list'][] = $row['id_jadwal'];
        $jadwalGrouped[$key]['hari_list'][] = $normHari;
        $jadwalGrouped[$key]['hari_display_list'][] = $labelHari;
        $jadwalGrouped[$key]['day_to_id'][$normHari] = $row['id_jadwal'];
    }
}

if (empty($jadwalGrouped)) {
    echo "<div class='alert alert-warning'>Belum ada jadwal mengajar yang dapat ditampilkan.</div>";
    return;
}

$jadwalList = [];
foreach ($jadwalGrouped as $item) {
    $idList = array_values(array_unique(array_map('intval', $item['id_jadwal_list'])));
    sort($idList);
    $hariList = array_values(array_unique($item['hari_list']));
    sort($hariList);
    $hariDisplay = array_values(array_unique($item['hari_display_list']));
    $dayToId = $item['day_to_id'];
    $jadwalList[] = [
        'id_jadwal' => $idList[0],
        'id_jadwal_list' => $idList,
        'id_combined' => implode(',', $idList),
        'kelas' => $item['kelas'],
        'mapel' => $item['mapel'],
        'nama_mapel' => $item['nama_mapel'],
        'kode' => $item['kode'],
        'guru' => $item['guru'],
        'nama_guru' => $item['nama_guru'],
        'hari_list' => $hariList,
        'hari_display' => implode(', ', $hariDisplay),
        'day_to_id' => $dayToId,
    ];
}

$requestedJadwalParam = isset($_GET['j']) ? trim($_GET['j']) : '';
$selectedJadwal = null;
foreach ($jadwalList as $item) {
    if ($item['id_combined'] === $requestedJadwalParam || (string)$item['id_jadwal'] === $requestedJadwalParam) {
        $selectedJadwal = $item;
        $requestedJadwalParam = $item['id_combined'];
        break;
    }
}

if (!$selectedJadwal && $requestedJadwalParam === '' && !empty($jadwalList)) {
    $selectedJadwal = $jadwalList[0];
    $requestedJadwalParam = $selectedJadwal['id_combined'];
}

$requestedJadwalIds = $selectedJadwal ? $selectedJadwal['id_jadwal_list'] : [];
$primaryJadwalId = $selectedJadwal ? $selectedJadwal['id_jadwal_list'][0] : 0;
$jadwalIdsSql = $selectedJadwal ? implode(',', array_map('intval', $requestedJadwalIds)) : '';

$tanggalPertemuan = [];
$absensiData = [];
$absensiAssoc = [];
$absensiSource = 'absensi_harian';
$targetJadwalId = $primaryJadwalId;

if (!$selectedJadwal) {
    $selectedDate = '';
} else {
    $kelasRaw = $selectedJadwal['kelas'];
    $kelasEscaped = mysqli_real_escape_string($koneksi, $kelasRaw);
    $hariList = $selectedJadwal['hari_list'];
    if (empty($hariList)) {
        $hariList = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    }
    $mapelId = (int) $selectedJadwal['mapel'];
    $guruId = (int) $selectedJadwal['guru'];
    $dayToId = isset($selectedJadwal['day_to_id']) && is_array($selectedJadwal['day_to_id']) ? $selectedJadwal['day_to_id'] : [];
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

    if ($jadwalIdsSql !== '') {
        $resultPertemuan = mysqli_query(
            $koneksi,
            "SELECT DISTINCT tanggal FROM absensi_harian
             WHERE id_jadwal IN ($jadwalIdsSql)
             ORDER BY tanggal ASC"
        );
        while ($row = mysqli_fetch_assoc($resultPertemuan)) {
            $tanggalPertemuan[] = $row['tanggal'];
        }
    }

    $resultAbsensiTanggal = mysqli_query(
        $koneksi,
        "
        SELECT DISTINCT a.tanggal
        FROM absensi a
        LEFT JOIN siswa s ON s.id_siswa=a.idsiswa
        WHERE ({$kelasConditionSql})
        ORDER BY a.tanggal ASC
        "
    );
    while ($row = mysqli_fetch_assoc($resultAbsensiTanggal)) {
        $tgl = $row['tanggal'];
        if (in_array(mykbm_normalize_hari(date('D', strtotime($tgl))), $hariList, true)) {
            $tanggalPertemuan[] = $tgl;
        }
    }

    sort($tanggalPertemuan);

    if (!empty($tanggalPertemuan)) {
        $minDate = min($tanggalPertemuan);
        $startTs = strtotime($minDate);
        $todayTs = strtotime(date('Y-m-d'));
        if ($startTs !== false && $todayTs !== false) {
            for ($ts = $startTs; $ts <= $todayTs; $ts = strtotime('+1 day', $ts)) {
                if (in_array(mykbm_normalize_hari(date('D', $ts)), $hariList, true)) {
                    $tanggalPertemuan[] = date('Y-m-d', $ts);
                }
            }
        }
        $tanggalPertemuan = array_values(array_unique($tanggalPertemuan));
        sort($tanggalPertemuan);
    }

    if (!empty($tanggalPertemuan)) {
        if ($selectedDate === '' || !in_array($selectedDate, $tanggalPertemuan, true)) {
            $selectedDate = end($tanggalPertemuan);
        }
    } else {
        $selectedDate = '';
    }

    $tanggalEscaped = mysqli_real_escape_string($koneksi, $selectedDate);
    $selectedDay = $selectedDate ? mykbm_normalize_hari(date('D', strtotime($selectedDate))) : '';
    $targetJadwalId = $primaryJadwalId;
    if ($selectedDay && isset($dayToId[$selectedDay])) {
        $targetJadwalId = (int) $dayToId[$selectedDay];
    }
    if ($targetJadwalId === 0 && $primaryJadwalId !== 0) {
        $targetJadwalId = $primaryJadwalId;
    }

    if ($tanggalEscaped !== '') {
        if ($jadwalIdsSql !== '') {
            $absensiResult = mysqli_query(
                $koneksi,
                "SELECT ah.*, s.nama
                 FROM absensi_harian ah
                 LEFT JOIN siswa s ON s.id_siswa=ah.idsiswa
                 WHERE ah.id_jadwal IN ($jadwalIdsSql) AND ah.tanggal='{$tanggalEscaped}'
                 ORDER BY s.nama ASC"
            );

            while ($row = mysqli_fetch_assoc($absensiResult)) {
                $absensiAssoc[$row['idsiswa']] = $row;
            }
        }

        $syncSql = "
        INSERT INTO absensi_harian (id_jadwal,tanggal,idsiswa,kelas,mapel,guru,ket,bulan,tahun)
        SELECT {$targetJadwalId}, '{$tanggalEscaped}', a.idsiswa, '{$kelasEscaped}', {$mapelId}, {$guruId}, a.ket, a.bulan, a.tahun
        FROM absensi a
        LEFT JOIN siswa s ON s.id_siswa=a.idsiswa
        WHERE a.tanggal='{$tanggalEscaped}'
          AND ({$kelasConditionSql})
        AND NOT EXISTS (
            SELECT 1 FROM absensi_harian ah
            WHERE ah.id_jadwal={$targetJadwalId}
              AND ah.tanggal='{$tanggalEscaped}'
              AND ah.idsiswa=a.idsiswa
        )
        ";
        if ($targetJadwalId !== 0 || $jadwalIdsSql !== '') {
            mysqli_query($koneksi, $syncSql);
        }

        if ($jadwalIdsSql !== '') {
            $absensiAssoc = [];
            $absensiResult = mysqli_query(
                $koneksi,
                "SELECT ah.*, s.nama
                 FROM absensi_harian ah
                 LEFT JOIN siswa s ON s.id_siswa=ah.idsiswa
                 WHERE ah.id_jadwal IN ($jadwalIdsSql) AND ah.tanggal='{$tanggalEscaped}'
                 ORDER BY s.nama ASC"
            );

            while ($row = mysqli_fetch_assoc($absensiResult)) {
                $absensiAssoc[$row['idsiswa']] = $row;
            }
        }

        if (empty($absensiAssoc)) {
            $absensiSource = 'absensi';
            $fallbackResult = mysqli_query(
                $koneksi,
                "SELECT a.*, s.nama
                 FROM absensi a
                 LEFT JOIN siswa s ON s.id_siswa=a.idsiswa
                 WHERE a.tanggal='{$tanggalEscaped}'
                   AND ({$kelasConditionSql})
                 ORDER BY s.nama ASC"
            );
            while ($row = mysqli_fetch_assoc($fallbackResult)) {
                $absensiAssoc[$row['idsiswa']] = [
                    'id' => 0,
                    'idsiswa' => $row['idsiswa'],
                    'nama' => $row['nama'],
                    'ket' => $row['ket'],
                    'kelas' => $kelasRaw,
                    'tanggal' => $row['tanggal'],
                    'bulan' => $row['bulan'],
                    'tahun' => $row['tahun'],
                ];
            }
        }
    }
}

if (!empty($absensiAssoc)) {
    $absensiData = array_values($absensiAssoc);
    usort($absensiData, function ($a, $b) {
        return strcasecmp($a['nama'], $b['nama']);
    });
}

$statusLabel = [
    'H' => ['label' => 'Hadir', 'badge' => 'success'],
    'A' => ['label' => 'Alpha', 'badge' => 'danger'],
    'S' => ['label' => 'Sakit', 'badge' => 'warning'],
    'I' => ['label' => 'Izin', 'badge' => 'info'],
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">DAFTAR KEHADIRAN SISWA</h5>
            </div>
            <div class="card-body">
                <form method="get" class="row g-3 mb-3">
                    <input type="hidden" name="pg" value="<?= enkripsi('lihatabsen') ?>">
                    <div class="col-md-5">
                        <label class="form-label bold">Pilih Pertemuan</label>
                        <select name="j" id="selectJadwal" class="form-select" required>
                            <option value="" <?= $requestedJadwalParam === '' ? 'selected' : '' ?>>-- Pilih Jadwal --</option>
                            <?php foreach ($jadwalList as $jadwal) : ?>
                                <?php
                                $label = "{$jadwal['kelas']} - {$jadwal['nama_mapel']} ({$jadwal['nama_guru']})";
                                $value = $jadwal['id_combined'];
                                ?>
                                <option value="<?= $value ?>" <?= $value === $requestedJadwalParam ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label bold">Pertemuan</label>
                        <select name="tgl" id="selectPertemuan" class="form-select" data-selected="<?= $selectedDate ?>" <?= (!$selectedJadwal || empty($tanggalPertemuan)) ? 'disabled' : '' ?>>
                            <?php if (empty($tanggalPertemuan)) : ?>
                                <option value="">Belum ada pertemuan</option>
                            <?php else : ?>
                                <?php
                                $pertemuanKe = 1;
                                foreach ($tanggalPertemuan as $tgl) :
                                    $selected = $tgl === $selectedDate ? 'selected' : '';
                                    ?>
                                    <option value="<?= $tgl ?>" <?= $selected ?>>
                                        Pertemuan ke-<?= $pertemuanKe ?> (<?= date('d-m-Y', strtotime($tgl)) ?>)
                                    </option>
                                    <?php
                                    $pertemuanKe++;
                                endforeach;
                                ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" id="tampilkanBtn" class="btn btn-primary w-100" <?= (!$selectedJadwal || empty($tanggalPertemuan)) ? 'disabled' : '' ?>>Tampilkan</button>
                    </div>
                </form>
                <?php if (!$selectedJadwal) : ?>
                    <div class="alert alert-info" id="alertPilihJadwal">
                        Silakan pilih jadwal terlebih dahulu untuk menampilkan daftar kehadiran.
                    </div>
                <?php else : ?>
                    <?php if (empty($tanggalPertemuan)) : ?>
                        <div class="alert alert-warning" id="alertTidakAdaPertemuan">
                            Belum ada pertemuan yang tercatat untuk jadwal ini. Lakukan sinkron presensi atau input manual pada hari mengajar guru.
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="150px"><strong>Kelas</strong></td>
                                    <td width="10px">:</td>
                                    <td><?= $selectedJadwal['kelas'] ?></td>
                                </tr>
                            <tr>
                                <td><strong>Mata Pelajaran</strong></td>
                                <td>:</td>
                                <td><?= $selectedJadwal['nama_mapel'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Hari Mengajar</strong></td>
                                <td>:</td>
                                <td><?= $selectedJadwal['hari_display'] ?></td>
                            </tr>
                            </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="150px"><strong>Guru Pengampu</strong></td>
                                    <td width="10px">:</td>
                                    <td><?= $selectedJadwal['nama_guru'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Pertemuan</strong></td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        if ($selectedDate) {
                                            $indexPertemuan = array_search($selectedDate, $tanggalPertemuan, true);
                                            $nomorPertemuan = $indexPertemuan === false ? '-' : $indexPertemuan + 1;
                                            echo "Pertemuan ke-{$nomorPertemuan} (" . date('d F Y', strtotime($selectedDate)) . ")";
                                        } else {
                                            echo "-";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if ($selectedDate && $requestedJadwalParam !== '') :
                        $semesterForPrint = '1';
                        if ($selectedDate) {
                            $monthPrint = (int) date('n', strtotime($selectedDate));
                            $semesterForPrint = $monthPrint >= 7 ? '1' : '2';
                        } elseif (isset($setting['semester'])) {
                            $semLower = strtolower($setting['semester']);
                            $semesterForPrint = ($semLower === '2' || $semLower === 'genap') ? '2' : '1';
                        }
                        $tpForPrint = isset($setting['tp']) ? urlencode($setting['tp']) : '';
                        $queryTp = $tpForPrint !== '' ? '&tp=' . $tpForPrint : '';
                        ?>
                        <div class="text-end mb-3">
                            <a href="cetak/cetak_mapel.php?j=<?= urlencode($requestedJadwalParam) ?>&tgl=<?= urlencode($selectedDate) ?>&semester=<?= $semesterForPrint ?><?= $queryTp ?>" target="_blank" class="btn btn-outline-secondary">
                                Cetak Rekap Presensi
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($selectedDate && empty($absensiData)) : ?>
                        <div class="alert alert-info">
                            Data presensi pertemuan ini belum tersedia pada tabel absensi maupun absensi per mapel. Silakan lakukan sinkron atau input manual terlebih dahulu.
                        </div>
<?php elseif ($selectedDate) : ?>
                        <div id="absenAlert"></div>
                        <?php if ($absensiSource === 'absensi') : ?>
                            <div class="alert alert-warning" id="absenSourceInfo">
                                Data diambil dari tabel absensi (mesin presensi). Simpan perubahan untuk menyalin ke absensi per mapel.
                            </div>
                        <?php endif; ?>
                        <form id="formAbsensiHarian">
                            <input type="hidden" name="jadwal" value="<?= $targetJadwalId ?>">
                            <input type="hidden" name="kelas" value="<?= $selectedJadwal['kelas'] ?>">
                            <input type="hidden" name="mapel" value="<?= $selectedJadwal['mapel'] ?>">
                            <input type="hidden" name="guru" value="<?= $selectedJadwal['guru'] ?>">
                            <input type="hidden" name="tanggal" value="<?= $selectedDate ?>">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%" class="text-center">No</th>
                                            <th>Nama Siswa</th>
                                            <th width="20%" class="text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        <?php foreach ($absensiData as $index => $row) : ?>
                                            <?php
                                            $no++;
                                            $ket = strtoupper($row['ket'] ?? 'A');
                                            if (!isset($statusLabel[$ket])) {
                                                $ket = 'A';
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $no; ?></td>
                                                <td><?= ucwords(strtolower($row['nama'])) ?></td>
                                                <td class="text-center">
                                                    <input type="hidden" name="rows[<?= $index ?>][id]" value="<?= isset($row['id']) ? (int)$row['id'] : 0 ?>">
                                                    <input type="hidden" name="rows[<?= $index ?>][idsiswa]" value="<?= (int)$row['idsiswa'] ?>">
                                                    <select name="rows[<?= $index ?>][ket]" class="form-select form-select-sm d-inline-block w-auto">
                                                        <?php foreach ($statusLabel as $kode => $info) : ?>
                                                            <option value="<?= $kode ?>" <?= $kode === $ket ? 'selected' : '' ?>><?= $info['label'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            </div>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
</div>
</div>
</div>
</div>

<script>
    $(function () {
        var $jadwal = $('#selectJadwal');
        var $pertemuan = $('#selectPertemuan');
        var $submit = $('#tampilkanBtn');
        var $alertPilih = $('#alertPilihJadwal');
        var $alertPertemuan = $('#alertTidakAdaPertemuan');
        var initialJadwal = <?= json_encode($requestedJadwalParam) ?>;
        var initialSelected = $pertemuan.data('selected') || '';

        function formatTanggal(dateStr) {
            var parts = dateStr ? dateStr.split('-') : [];
            if (parts.length !== 3) {
                return dateStr;
            }
            return parts[2] + '-' + parts[1] + '-' + parts[0];
        }

        function setEmpty(message) {
            $pertemuan.html('<option value="">' + message + '</option>').prop('disabled', true);
            $submit.prop('disabled', true);
            if ($alertPertemuan.length) {
                if (message === 'Belum ada pertemuan') {
                    $alertPertemuan.show();
                } else {
                    $alertPertemuan.hide();
                }
            }
        }

        function fetchPertemuan(jadwalId, selected) {
            if (!jadwalId) {
                setEmpty('Pilih jadwal terlebih dahulu');
                if ($alertPilih.length) {
                    $alertPilih.show();
                }
                return;
            }

            if ($alertPilih.length) {
                $alertPilih.hide();
            }

            $.ajax({
                type: 'POST',
                url: 'absen/tabsen.php?pg=pertemuan',
                data: { jadwal: jadwalId },
                dataType: 'json',
                success: function (res) {
                    if (res && res.success && Array.isArray(res.dates) && res.dates.length) {
                        var options = '';
                        var picked = selected && res.dates.indexOf(selected) !== -1 ? selected : res.dates[res.dates.length - 1];
                        res.dates.forEach(function (dateStr, idx) {
                            var label = 'Pertemuan ke-' + (idx + 1) + ' (' + formatTanggal(dateStr) + ')';
                            options += '<option value="' + dateStr + '"' + (dateStr === picked ? ' selected' : '') + '>' + label + '</option>';
                        });
                        $pertemuan.html(options).prop('disabled', false);
                        $submit.prop('disabled', false);
                        initialSelected = picked;
                        if ($alertPertemuan.length) {
                            $alertPertemuan.hide();
                        }
                    } else {
                        setEmpty('Belum ada pertemuan');
                    }
                },
                error: function () {
                    setEmpty('Gagal memuat pertemuan');
                    if ($alertPertemuan.length) {
                        $alertPertemuan.hide();
                    }
                }
            });
        }

        $jadwal.on('change', function () {
            initialSelected = '';
            fetchPertemuan($(this).val(), initialSelected);
        });

        if (initialJadwal) {
            fetchPertemuan(initialJadwal, initialSelected);
        } else {
            setEmpty('Pilih jadwal terlebih dahulu');
        }
    });
</script>

<?php if ($selectedJadwal && $selectedDate && !empty($absensiData)) : ?>
<script>
    $(function () {
        $('#formAbsensiHarian').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);
            var $button = $form.find('button[type="submit"]');
            var $alertBox = $('#absenAlert');
            $alertBox.html('');
            $button.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                type: 'POST',
                url: 'absen/tabsen.php?pg=bulk_harian',
                data: $form.serialize(),
                dataType: 'json',
                success: function (response) {
                    var type = response && response.success ? 'success' : 'danger';
                    var message = response && response.message ? response.message : 'Tidak dapat menyimpan data presensi.';
                    $alertBox.html('<div class="alert alert-' + type + '">' + message + '</div>');
                    if (response && response.success) {
                        $('#absenSourceInfo').remove();
                    }
                },
                error: function () {
                    $alertBox.html('<div class="alert alert-danger">Terjadi kesalahan koneksi. Silakan coba lagi.</div>');
                },
                complete: function () {
                    $button.prop('disabled', false).text('Simpan Perubahan');
                }
            });
        });
    });
</script>
<?php endif; ?>
