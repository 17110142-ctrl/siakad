<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';

if (!function_exists('respond_json')) {
    function respond_json(string $status, string $message, array $extra = []): void {
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
        exit;
    }
}

if (!function_exists('mapelrapor_targets')) {
    function mapelrapor_targets(mysqli $conn, string $level, string $kurikulum): array {
        $targets = [];
        if ($level === 'ALL') {
            $res = mysqli_query($conn, "SELECT DISTINCT level, kurikulum FROM kelas WHERE kurikulum='2'");
            while ($row = mysqli_fetch_assoc($res)) {
                $targets[] = ['level' => $row['level'], 'kurikulum' => $row['kurikulum']];
            }
        } else {
            if ($kurikulum === 'ALL' || $kurikulum === '' || $kurikulum === null) {
                $levelEsc = mysqli_real_escape_string($conn, $level);
                $res = mysqli_query($conn, "SELECT kurikulum FROM kelas WHERE level='$levelEsc' LIMIT 1");
                $row = mysqli_fetch_assoc($res);
                $kurikulum = $row['kurikulum'] ?? '2';
            }
            $targets[] = ['level' => $level, 'kurikulum' => $kurikulum];
        }
        return $targets;
    }
}

if ($pg == 'mapel') {
    $kode    = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $tingkat = $_POST['level'];
    $pk      = mysqli_real_escape_string($koneksi, $_POST['pk']);
    $urut    = (int)$_POST['urut'];
    $kuri    = $_POST['kuri'];

    $targets = mapelrapor_targets($koneksi, $tingkat, $kuri);

    $inserted = 0;
    foreach ($targets as $target) {
        $levelVal = mysqli_real_escape_string($koneksi, $target['level']);
        $kurVal   = $target['kurikulum'];

        if ($kurVal === 'ALL' || $kurVal === '' || $kurVal === null) {
            $resKur = mysqli_query($koneksi, "SELECT kurikulum FROM kelas WHERE level='$levelVal' LIMIT 1");
            if ($rowKur = mysqli_fetch_assoc($resKur)) {
                $kurVal = $rowKur['kurikulum'];
            } else {
                $kurVal = '2';
            }
        }
        $kurVal = mysqli_real_escape_string($koneksi, $kurVal);

        $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT idm FROM mapel_rapor WHERE mapel='$kode' AND tingkat='$levelVal' AND pk='$pk' LIMIT 1"));
        if ($cek > 0) {
            continue;
        }

        $cekUrut = mysqli_num_rows(mysqli_query($koneksi, "SELECT idm FROM mapel_rapor WHERE tingkat='$levelVal' AND pk='$pk' AND urut='$urut' LIMIT 1"));
        if ($cekUrut > 0) {
            continue;
        }

        $exec = mysqli_query($koneksi, "INSERT INTO mapel_rapor (urut,mapel,tingkat,pk,kurikulum) VALUES ('$urut','$kode','$levelVal','$pk','$kurVal')");
        if ($exec) {
            $inserted++;
        }
    }

    if ($inserted === 0) {
        echo "GAGAL";
    } else {
        echo "OK";
    }
    exit;
}

if ($pg == 'edit_mapel') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        respond_json('error', 'ID tidak valid');
    }

    $currentRes = mysqli_query($koneksi, "SELECT * FROM mapel_rapor WHERE idm='$id' LIMIT 1");
    if (!$currentRes || mysqli_num_rows($currentRes) === 0) {
        if ($currentRes) {
            mysqli_free_result($currentRes);
        }
        respond_json('error', 'Data mapel tidak ditemukan');
    }
    $current = mysqli_fetch_assoc($currentRes);
    mysqli_free_result($currentRes);

    $mapelId = isset($_POST['mapel']) && $_POST['mapel'] !== '' ? (int)$_POST['mapel'] : (int)$current['mapel'];
    $level    = isset($_POST['level']) && $_POST['level'] !== '' ? trim($_POST['level']) : (string)$current['tingkat'];
    $pk       = isset($_POST['pk']) ? trim($_POST['pk']) : (string)$current['pk'];
    $urut     = isset($_POST['urut']) && $_POST['urut'] !== '' ? (int)$_POST['urut'] : (int)$current['urut'];
    $kurikulum = isset($_POST['kuri']) && $_POST['kuri'] !== '' ? trim($_POST['kuri']) : (string)$current['kurikulum'];

    if ($pk === null) {
        $pk = '';
    }

    if ($mapelId <= 0) {
        respond_json('error', 'Mata pelajaran wajib dipilih');
    }
    if ($level === '') {
        respond_json('error', 'Tingkat wajib diisi');
    }
    if ($kurikulum === '') {
        respond_json('error', 'Kurikulum wajib diisi');
    }

    $mapelInfo = fetch($koneksi, 'mata_pelajaran', ['id' => $mapelId]);
    $mapelLabel = $mapelInfo['nama_mapel'] ?? 'Mapel';

    $levelEsc = mysqli_real_escape_string($koneksi, $level);
    $pkEsc    = mysqli_real_escape_string($koneksi, $pk);

    $pkCondition = $pk === '' ? "(pk='' OR pk IS NULL)" : "pk='" . $pkEsc . "'";
    $dupMapelSql = "SELECT idm FROM mapel_rapor WHERE mapel='$mapelId' AND tingkat='$levelEsc' AND $pkCondition AND idm <> '$id' LIMIT 1";
    $dupMapelRes = mysqli_query($koneksi, $dupMapelSql);
    if ($dupMapelRes && mysqli_num_rows($dupMapelRes) > 0) {
        mysqli_free_result($dupMapelRes);
        respond_json('error', $mapelLabel . ' sudah terdaftar pada tingkat ' . $level . ' jurusan ' . ($pk === '' ? '-' : $pk));
    }
    if ($dupMapelRes) {
        mysqli_free_result($dupMapelRes);
    }

    $pkConditionUrut = $pk === '' ? "(mr.pk='' OR mr.pk IS NULL)" : "mr.pk='" . $pkEsc . "'";
    $dupUrutSql = "SELECT mr.idm, mr.mapel, mp.nama_mapel FROM mapel_rapor mr LEFT JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.tingkat='$levelEsc' AND $pkConditionUrut AND mr.urut='$urut' AND mr.idm <> '$id' LIMIT 1";
    $dupUrutRes = mysqli_query($koneksi, $dupUrutSql);
    if ($dupUrutRes && mysqli_num_rows($dupUrutRes) > 0) {
        $dupRow = mysqli_fetch_assoc($dupUrutRes);
        mysqli_free_result($dupUrutRes);
        $conflictName = $dupRow['nama_mapel'] ?? 'mapel lain';
        $pkLabel = $pk === '' ? '-' : $pk;
        respond_json('error', 'Nomor urut ' . $urut . ' sudah dipakai oleh ' . $conflictName . ' pada tingkat ' . $level . ' jurusan ' . $pkLabel);
    }
    if ($dupUrutRes) {
        mysqli_free_result($dupUrutRes);
    }

    $stmt = $koneksi->prepare("UPDATE mapel_rapor SET mapel=?, tingkat=?, pk=?, kurikulum=?, urut=? WHERE idm=?");
    if (!$stmt) {
        respond_json('error', 'Gagal mempersiapkan pembaruan');
    }

    $stmt->bind_param('isssii', $mapelId, $level, $pk, $kurikulum, $urut, $id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    respond_json('ok', 'Data ' . $mapelLabel . ' berhasil diperbarui', [
        'updated' => $affected,
        'id' => $id,
        'mapel' => [
            'id' => $mapelId,
            'nama' => $mapelLabel,
        ],
        'tingkat' => $level,
        'pk' => $pk,
        'urut' => $urut,
        'kurikulum' => $kurikulum,
    ]);
}
if ($pg == 'hapus') {
    $id = $_POST['id'];
    $exec = delete($koneksi, 'mapel_rapor', ['idm' => $id]);
    echo $exec;
}
if ($pg == 'kuri') {
    $level = $_POST['level'];
    if ($level === 'ALL') {
        echo "<option value='ALL'>Semua</option>";
        exit;
    }
    $sql = mysqli_query($koneksi, "SELECT kelas.level,kelas.kurikulum,m_kurikulum.idk,m_kurikulum.nama_kurikulum FROM kelas JOIN m_kurikulum ON m_kurikulum.idk=kelas.kurikulum WHERE kelas.level='" . $level . "' GROUP BY kelas.level");

    while ($data = mysqli_fetch_array($sql)) {
        echo "<option value='$data[kurikulum]'>$data[nama_kurikulum]</option>";
    }
}

if ($pg == 'update_urut') {
    $urut = isset($_POST['urut']) ? (int)$_POST['urut'] : 0;
    $idsRaw = $_POST['ids'] ?? ($_POST['id'] ?? []);

    if (!is_array($idsRaw)) {
        $idsRaw = is_string($idsRaw) ? explode(',', $idsRaw) : [$idsRaw];
    }

    $ids = [];
    foreach ($idsRaw as $value) {
        $id = (int)$value;
        if ($id > 0) {
            $ids[$id] = $id; // dedupe by key
        }
    }

    if (empty($ids)) {
        respond_json('error', 'ID tidak valid');
    }

    $idsList = implode(',', array_map('intval', $ids));
    $result = mysqli_query($koneksi, "SELECT mr.idm, mr.tingkat, mr.pk, mr.mapel, mp.nama_mapel AS mapel_name FROM mapel_rapor mr LEFT JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.idm IN ($idsList)");
    if (!$result) {
        respond_json('error', 'Data mapel tidak ditemukan');
    }

    if (mysqli_num_rows($result) === 0) {
        mysqli_free_result($result);
        respond_json('error', 'Data mapel tidak ditemukan');
    }

    $duplicateInfo = null;

    while ($row = mysqli_fetch_assoc($result)) {
        $levelEscLoop = mysqli_real_escape_string($koneksi, (string)$row['tingkat']);
        $pkValue = $row['pk'];
        $pkCondition = '';
        if ($pkValue === null || $pkValue === '') {
            $pkCondition = "(mr.pk='' OR mr.pk IS NULL)";
        } else {
            $pkEscLoop = mysqli_real_escape_string($koneksi, (string)$pkValue);
            $pkCondition = "mr.pk='" . $pkEscLoop . "'";
        }
        $dupQuery = "SELECT mr.idm, mr.mapel, mp.nama_mapel AS mapel_name FROM mapel_rapor mr LEFT JOIN mata_pelajaran mp ON mp.id = mr.mapel WHERE mr.tingkat='" . $levelEscLoop . "' AND " . $pkCondition . " AND mr.urut='" . $urut . "' AND mr.idm <> " . (int)$row['idm'] . " LIMIT 1";
        $dupRes = mysqli_query($koneksi, $dupQuery);
        if ($dupRes && mysqli_num_rows($dupRes) > 0) {
            $dupRow = mysqli_fetch_assoc($dupRes);
            $duplicateInfo = [
                'tingkat' => $row['tingkat'],
                'pk' => $pkValue,
                'conflict_mapel_name' => $dupRow['mapel_name'] ?? 'Mapel lain',
                'target_mapel_name' => $row['mapel_name'] ?? 'Mapel',
            ];
            mysqli_free_result($dupRes);
            break;
        }
        if ($dupRes) {
            mysqli_free_result($dupRes);
        }
    }

    mysqli_free_result($result);

    if ($duplicateInfo !== null) {
        $pkLabel = $duplicateInfo['pk'];
        if ($pkLabel === null || $pkLabel === '') {
            $pkLabel = '-';
        }
        $targetName = $duplicateInfo['target_mapel_name'];
        $conflictName = $duplicateInfo['conflict_mapel_name'];
        respond_json('error', 'Nomor urut ' . $urut . ' untuk ' . $targetName . ' bentrok dengan ' . $conflictName . ' pada tingkat ' . $duplicateInfo['tingkat'] . ' jurusan ' . $pkLabel);
    }

    $stmt = $koneksi->prepare("UPDATE mapel_rapor SET urut = ? WHERE idm = ?");
    if (!$stmt) {
        respond_json('error', 'Gagal mempersiapkan perintah');
    }

    $idParam = 0;
    $stmt->bind_param('ii', $urut, $idParam);
    $updated = 0;

    foreach ($ids as $idValue) {
        $idParam = $idValue;
        if ($stmt->execute()) {
            $updated += $stmt->affected_rows;
        }
    }

    $stmt->close();

    respond_json('ok', 'Nomor urut diperbarui', [
        'updated' => $updated,
        'ids' => array_values($ids),
    ]);
}

if ($pg == 'list_jadwal') {
    $level = $_POST['level'] ?? '';
    $pk    = $_POST['pk'] ?? '';
    $kuri  = $_POST['kuri'] ?? '';
    if ($level === '' || $pk === '') {
        respond_json('error', 'Tingkat dan jurusan wajib dipilih');
    }

    $conditions = ["k.kurikulum='2'"];
    if ($level !== 'ALL') {
        $levelEsc = mysqli_real_escape_string($koneksi, $level);
        $conditions[] = "k.level='$levelEsc'";
    }
    if ($pk !== 'semua') {
        $pkEsc = mysqli_real_escape_string($koneksi, $pk);
        $conditions[] = "(k.jurusan='$pkEsc' OR k.jurusan='' OR k.jurusan IS NULL)";
    }

    $whereSql = 'WHERE ' . implode(' AND ', $conditions);
    $sql = "SELECT DISTINCT mp.id, mp.kode, mp.nama_mapel FROM jadwal_mapel jm JOIN kelas k ON k.kelas = jm.kelas JOIN mata_pelajaran mp ON mp.id = jm.mapel $whereSql ORDER BY mp.nama_mapel";
    $res = mysqli_query($koneksi, $sql);

    $data = [];
    $targets = mapelrapor_targets($koneksi, $level, $kuri);
    while ($row = mysqli_fetch_assoc($res)) {
        $mapelId = (int)$row['id'];
        $exists = false;
        foreach ($targets as $target) {
            $levelTarget = mysqli_real_escape_string($koneksi, $target['level']);
            $pkEsc       = mysqli_real_escape_string($koneksi, $pk);
            $cek = mysqli_query($koneksi, "SELECT idm FROM mapel_rapor WHERE mapel='$mapelId' AND tingkat='$levelTarget' AND pk='$pkEsc' LIMIT 1");
            if (mysqli_num_rows($cek) > 0) {
                $exists = true;
                break;
            }
        }
        $data[] = [
            'id'     => $mapelId,
            'kode'   => $row['kode'],
            'nama'   => $row['nama_mapel'],
            'exists' => $exists,
        ];
    }

    $startOrder = 1;
    if ($level !== 'ALL') {
        $levelEsc = mysqli_real_escape_string($koneksi, $level);
        $pkEsc = mysqli_real_escape_string($koneksi, $pk);
        $qMax = mysqli_query($koneksi, "SELECT COALESCE(MAX(urut),0) AS max_urut FROM mapel_rapor WHERE tingkat='$levelEsc' AND pk='$pkEsc'");
        if ($row = mysqli_fetch_assoc($qMax)) {
            $startOrder = (int)$row['max_urut'] + 1;
        }
    } else {
        $qMaxAll = mysqli_query($koneksi, "SELECT COALESCE(MAX(urut),0) AS max_urut FROM mapel_rapor");
        if ($rowAll = mysqli_fetch_assoc($qMaxAll)) {
            $startOrder = (int)$rowAll['max_urut'] + 1;
        }
    }

    respond_json('ok', 'Data dimuat', ['data' => $data, 'start' => $startOrder]);
}

if ($pg == 'generate_bulk') {
    $payloadJson = $_POST['payload'] ?? '';
    $payload = json_decode($payloadJson, true);
    if (!is_array($payload)) {
        respond_json('error', 'Data tidak valid');
    }

    $level = $payload['level'] ?? '';
    $pk    = $payload['pk'] ?? '';
    $kuri  = $payload['kuri'] ?? '';
    $items = $payload['mapels'] ?? [];

    if ($level === '' || $pk === '' || empty($items)) {
        respond_json('error', 'Lengkapi pilihan level, jurusan, dan mapel.');
    }

    $targets = mapelrapor_targets($koneksi, $level, $kuri);
    if (empty($targets)) {
        respond_json('error', 'Target tingkat tidak ditemukan.');
    }

    $inserted = 0;
    $skipped  = [];

    foreach ($items as $item) {
        if (!isset($item['id'])) {
            continue;
        }
        $mapelId = (int)$item['id'];
        $urutVal = isset($item['urut']) ? (int)$item['urut'] : 0;

        foreach ($targets as $target) {
            $levelTarget = mysqli_real_escape_string($koneksi, $target['level']);
            $kurTarget   = mysqli_real_escape_string($koneksi, $target['kurikulum']);
            $pkEsc       = mysqli_real_escape_string($koneksi, $pk);
            $cek = mysqli_query($koneksi, "SELECT idm FROM mapel_rapor WHERE mapel='$mapelId' AND tingkat='$levelTarget' AND pk='$pkEsc' LIMIT 1");
            if ($cek && mysqli_num_rows($cek) > 0) {
                mysqli_free_result($cek);
                $skipped[] = "$levelTarget-$pkEsc-$mapelId";
                continue;
            }
            if ($cek) {
                mysqli_free_result($cek);
            }

            $cekUrut = mysqli_query($koneksi, "SELECT idm FROM mapel_rapor WHERE tingkat='$levelTarget' AND pk='$pkEsc' AND urut='$urutVal' LIMIT 1");
            if ($cekUrut && mysqli_num_rows($cekUrut) > 0) {
                mysqli_free_result($cekUrut);
                $skipped[] = "$levelTarget-$pkEsc-urut-$urutVal";
                continue;
            }
            if ($cekUrut) {
                mysqli_free_result($cekUrut);
            }
            mysqli_query($koneksi, "INSERT INTO mapel_rapor (urut,mapel,tingkat,pk,kurikulum) VALUES ('$urutVal','$mapelId','$levelTarget','$pkEsc','$kurTarget')");
            if (mysqli_affected_rows($koneksi) > 0) {
                $inserted++;
            }
        }
    }

    respond_json('ok', 'Generate mapel selesai', ['inserted' => $inserted, 'skipped' => $skipped]);
}
