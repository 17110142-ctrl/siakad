<?php
// Simple one-click trigger restore using existing DB config.
// Usage (CLI):   php maintenance/restore_triggers.php
// Usage (Web):   open maintenance/restore_triggers.php in browser (ensure it's protected)

header('Content-Type: application/json');
$result = ['ok' => false, 'steps' => []];

// Reuse app DB connection
require_once __DIR__ . '/../config/koneksi.php';

if (!$koneksi) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'DB connection failed']);
    exit;
}

// Helper to run a single SQL statement and collect status
function run_sql($conn, $sql, $label, &$steps) {
    $ok = mysqli_query($conn, $sql);
    $steps[] = [
        'label' => $label,
        'success' => (bool)$ok,
        'error' => $ok ? null : mysqli_error($conn)
    ];
    return $ok;
}

// Drop existing triggers (ignore errors)
// Tugas triggers
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_delete", 'drop:tugas_delete', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_insert", 'drop:tugas_insert', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_update", 'drop:tugas_update', $result['steps']);
// Harian triggers
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_insert", 'drop:harian_insert', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_update", 'drop:harian_update', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_delete", 'drop:harian_delete', $result['steps']);
// E-learning triggers
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_insert", 'drop:elearn_insert', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_update", 'drop:elearn_update', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_delete", 'drop:elearn_delete', $result['steps']);
// Guards on nilai_sts
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_nilai_sts_before_insert_avg", 'drop:sts_bi', $result['steps']);
run_sql($koneksi, "DROP TRIGGER IF EXISTS trg_nilai_sts_before_update_avg", 'drop:sts_bu', $result['steps']);

// Re-create triggers per siakad.sql
$create_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_delete
AFTER DELETE ON jawaban_tugas FOR EACH ROW
BEGIN
    DECLARE v_mapel INT;
    DECLARE v_id_siswa INT;
    DECLARE v_tp VARCHAR(10);
    DECLARE v_semester INT;
    DECLARE v_avg_harian DECIMAL(10, 2);

    SET v_id_siswa = OLD.id_siswa;
    SET v_tp = OLD.tapel;
    SET v_semester = OLD.semester;

    SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode = OLD.nama_mapel LIMIT 1;

    IF v_mapel IS NOT NULL THEN
        SELECT ROUND(AVG(gabungan.nilai), 1) INTO v_avg_harian
        FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = v_id_siswa AND mapel = v_mapel AND semester = v_semester AND tapel = v_tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = v_id_siswa AND mp.id = v_mapel AND jt.semester = v_semester AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE n.id_siswa = v_id_siswa AND mp.id = v_mapel AND n.semester = v_semester AND n.tp = v_tp AND n.nilai IS NOT NULL
        ) AS gabungan;

        UPDATE nilai_sts SET nilai_harian = v_avg_harian WHERE idsiswa = v_id_siswa AND mapel = v_mapel AND tp = v_tp AND semester = v_semester;
    END IF;
END
SQL;

$create_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_insert
AFTER INSERT ON jawaban_tugas FOR EACH ROW
BEGIN
    DECLARE v_mapel INT;
    DECLARE v_id_siswa INT;
    DECLARE v_tp VARCHAR(10);
    DECLARE v_semester INT;
    DECLARE v_avg_harian DECIMAL(10, 2);

    SET v_id_siswa = NEW.id_siswa;
    SET v_tp = NEW.tapel;
    SET v_semester = NEW.semester;

    SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode = NEW.nama_mapel LIMIT 1;

    IF v_mapel IS NOT NULL AND NEW.nilai IS NOT NULL THEN
        SELECT ROUND(AVG(gabungan.nilai), 1) INTO v_avg_harian
        FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = v_id_siswa AND mapel = v_mapel AND semester = v_semester AND tapel = v_tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = v_id_siswa AND mp.id = v_mapel AND jt.semester = v_semester AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE n.id_siswa = v_id_siswa AND mp.id = v_mapel AND n.semester = v_semester AND n.tp = v_tp AND n.nilai IS NOT NULL
        ) AS gabungan;

        INSERT INTO nilai_sts (idsiswa, mapel, tp, semester, nilai_harian, nilai_raport)
        VALUES (v_id_siswa, v_mapel, v_tp, v_semester, v_avg_harian, 0)
        ON DUPLICATE KEY UPDATE nilai_harian = VALUES(nilai_harian);
    END IF;
END
SQL;

$create_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_update
AFTER UPDATE ON jawaban_tugas FOR EACH ROW
BEGIN
    DECLARE v_mapel INT;
    DECLARE v_id_siswa INT;
    DECLARE v_tp VARCHAR(10);
    DECLARE v_semester INT;
    DECLARE v_avg_harian DECIMAL(10, 2);

    IF NEW.nilai <> OLD.nilai THEN
        SET v_id_siswa = NEW.id_siswa;
        SET v_tp = NEW.tapel;
        SET v_semester = NEW.semester;

        SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode = NEW.nama_mapel LIMIT 1;

        IF v_mapel IS NOT NULL THEN
            SELECT ROUND(AVG(gabungan.nilai), 1) INTO v_avg_harian
            FROM (
                SELECT nilai FROM nilai_harian WHERE idsiswa = v_id_siswa AND mapel = v_mapel AND semester = v_semester AND tapel = v_tp AND nilai IS NOT NULL
                UNION ALL
                SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = v_id_siswa AND mp.id = v_mapel AND jt.semester = v_semester AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
                UNION ALL
                SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE n.id_siswa = v_id_siswa AND mp.id = v_mapel AND n.semester = v_semester AND n.tp = v_tp AND n.nilai IS NOT NULL
            ) AS gabungan;

            UPDATE nilai_sts SET nilai_harian = v_avg_harian WHERE idsiswa = v_id_siswa AND mapel = v_mapel AND tp = v_tp AND semester = v_semester;
        END IF;
    END IF;
END
SQL;

$create_harian_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_insert
AFTER INSERT ON nilai_harian FOR EACH ROW
BEGIN
    DECLARE v_avg DECIMAL(10, 2);
    DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    IF NEW.nilai IS NOT NULL THEN
        SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.idsiswa AND mapel = NEW.mapel AND semester = v_sem AND tapel = v_tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.idsiswa AND mp.id = NEW.mapel AND jt.semester = v_sem AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1 WHERE n.id_siswa = NEW.idsiswa AND mp2.id = NEW.mapel AND n.semester = v_sem AND n.tp = v_tp AND n.nilai IS NOT NULL
        ) AS gab;
        INSERT INTO nilai_sts (idsiswa, mapel, tp, semester, nilai_harian, nilai_raport)
        VALUES (NEW.idsiswa, NEW.mapel, v_tp, v_sem, v_avg, 0)
        ON DUPLICATE KEY UPDATE nilai_harian = VALUES(nilai_harian);
    END IF;
END
SQL;

$create_harian_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_update
AFTER UPDATE ON nilai_harian FOR EACH ROW
BEGIN
    IF NEW.nilai <> OLD.nilai THEN
        DECLARE v_avg DECIMAL(10, 2);
        DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
        SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.idsiswa AND mapel = NEW.mapel AND semester = v_sem AND tapel = v_tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.idsiswa AND mp.id = NEW.mapel AND jt.semester = v_sem AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1 WHERE n.id_siswa = NEW.idsiswa AND mp2.id = NEW.mapel AND n.semester = v_sem AND n.tp = v_tp AND n.nilai IS NOT NULL
        ) AS gab;
        UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa = NEW.idsiswa AND mapel = NEW.mapel AND tp = v_tp AND semester = v_sem;
    END IF;
END
SQL;

$create_harian_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_delete
AFTER DELETE ON nilai_harian FOR EACH ROW
BEGIN
    DECLARE v_avg DECIMAL(10, 2);
    DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(OLD.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(OLD.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
        SELECT nilai FROM nilai_harian WHERE idsiswa = OLD.idsiswa AND mapel = OLD.mapel AND semester = v_sem AND tapel = v_tp AND nilai IS NOT NULL
        UNION ALL
        SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = OLD.idsiswa AND mp.id = OLD.mapel AND jt.semester = v_sem AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
        UNION ALL
        SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1 WHERE n.id_siswa = OLD.idsiswa AND mp2.id = OLD.mapel AND n.semester = v_sem AND n.tp = v_tp AND n.nilai IS NOT NULL
    ) AS gab;
    UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa = OLD.idsiswa AND mapel = OLD.mapel AND tp = v_tp AND semester = v_sem;
END
SQL;

$create_elearn_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_insert
AFTER INSERT ON nilai FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg DECIMAL(10, 2);
    SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE u.id_ujian = NEW.id_ujian LIMIT 1;
    IF v_mapel IS NOT NULL AND NEW.nilai IS NOT NULL THEN
        SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.id_siswa AND mapel = v_mapel AND semester = NEW.semester AND tapel = NEW.tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.id_siswa AND mp.id = v_mapel AND jt.semester = NEW.semester AND jt.tapel = NEW.tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u2 ON n.id_ujian = u2.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u2.kode_nama) = 1 WHERE n.id_siswa = NEW.id_siswa AND mp2.id = v_mapel AND n.semester = NEW.semester AND n.tp = NEW.tp AND n.nilai IS NOT NULL
        ) AS gab;
        UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa = NEW.id_siswa AND mapel = v_mapel AND tp = NEW.tp AND semester = NEW.semester;
    END IF;
END
SQL;

$create_elearn_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_update
AFTER UPDATE ON nilai FOR EACH ROW
BEGIN
    IF NEW.nilai <> OLD.nilai THEN
        DECLARE v_mapel INT; DECLARE v_avg DECIMAL(10, 2);
        SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE u.id_ujian = NEW.id_ujian LIMIT 1;
        IF v_mapel IS NOT NULL THEN
            SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
                SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.id_siswa AND mapel = v_mapel AND semester = NEW.semester AND tapel = NEW.tp AND nilai IS NOT NULL
                UNION ALL
                SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.id_siswa AND mp.id = v_mapel AND jt.semester = NEW.semester AND jt.tapel = NEW.tp AND jt.nilai IS NOT NULL
                UNION ALL
                SELECT n.nilai FROM nilai n JOIN ujian u2 ON n.id_ujian = u2.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u2.kode_nama) = 1 WHERE n.id_siswa = NEW.id_siswa AND mp2.id = v_mapel AND n.semester = NEW.semester AND n.tp = NEW.tp AND n.nilai IS NOT NULL
            ) AS gab;
            UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa = NEW.id_siswa AND mapel = v_mapel AND tp = NEW.tp AND semester = NEW.semester;
        END IF;
    END IF;
END
SQL;

$create_elearn_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_delete
AFTER DELETE ON nilai FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg DECIMAL(10, 2);
    SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama) = 1 WHERE u.id_ujian = OLD.id_ujian LIMIT 1;
    IF v_mapel IS NOT NULL THEN
        SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
            SELECT nilai FROM nilai_harian WHERE idsiswa = OLD.id_siswa AND mapel = v_mapel AND semester = OLD.semester AND tapel = OLD.tp AND nilai IS NOT NULL
            UNION ALL
            SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = OLD.id_siswa AND mp.id = v_mapel AND jt.semester = OLD.semester AND jt.tapel = OLD.tp AND jt.nilai IS NOT NULL
            UNION ALL
            SELECT n.nilai FROM nilai n JOIN ujian u2 ON n.id_ujian = u2.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u2.kode_nama) = 1 WHERE n.id_siswa = OLD.id_siswa AND mp2.id = v_mapel AND n.semester = OLD.semester AND n.tp = OLD.tp AND n.nilai IS NOT NULL
        ) AS gab;
        UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa = OLD.id_siswa AND mapel = v_mapel AND tp = OLD.tp AND semester = OLD.semester;
    END IF;
END
SQL;

$create_sts_bi = <<<SQL
CREATE TRIGGER trg_nilai_sts_before_insert_avg
BEFORE INSERT ON nilai_sts FOR EACH ROW
BEGIN
    DECLARE v_avg DECIMAL(10, 2);
    DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tp,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
        SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.idsiswa AND mapel = NEW.mapel AND semester = v_sem AND tapel = v_tp AND nilai IS NOT NULL
        UNION ALL
        SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.idsiswa AND mp.id = NEW.mapel AND jt.semester = v_sem AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
        UNION ALL
        SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1 WHERE n.id_siswa = NEW.idsiswa AND mp2.id = NEW.mapel AND n.semester = v_sem AND n.tp = v_tp AND n.nilai IS NOT NULL
    ) AS gab;
    SET NEW.nilai_harian = IFNULL(v_avg, IFNULL(NEW.nilai_harian,0));
    SET NEW.semester = v_sem; SET NEW.tp = v_tp;
END
SQL;

$create_sts_bu = <<<SQL
CREATE TRIGGER trg_nilai_sts_before_update_avg
BEFORE UPDATE ON nilai_sts FOR EACH ROW
BEGIN
    DECLARE v_avg DECIMAL(10, 2);
    DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tp,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SELECT ROUND(AVG(gab.nilai), 1) INTO v_avg FROM (
        SELECT nilai FROM nilai_harian WHERE idsiswa = NEW.idsiswa AND mapel = NEW.mapel AND semester = v_sem AND tapel = v_tp AND nilai IS NOT NULL
        UNION ALL
        SELECT jt.nilai FROM jawaban_tugas jt JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode WHERE jt.id_siswa = NEW.idsiswa AND mp.id = NEW.mapel AND jt.semester = v_sem AND jt.tapel = v_tp AND jt.nilai IS NOT NULL
        UNION ALL
        SELECT n.nilai FROM nilai n JOIN ujian u ON n.id_ujian = u.id_ujian JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1 WHERE n.id_siswa = NEW.idsiswa AND mp2.id = NEW.mapel AND n.semester = v_sem AND n.tp = v_tp AND n.nilai IS NOT NULL
    ) AS gab;
    SET NEW.nilai_harian = IFNULL(v_avg, IFNULL(NEW.nilai_harian,0));
    SET NEW.semester = v_sem; SET NEW.tp = v_tp;
END
SQL;

$ok1 = run_sql($koneksi, $create_delete, 'create:tugas_delete', $result['steps']);
$ok2 = run_sql($koneksi, $create_insert, 'create:tugas_insert', $result['steps']);
$ok3 = run_sql($koneksi, $create_update, 'create:tugas_update', $result['steps']);
$ok4 = run_sql($koneksi, $create_harian_insert, 'create:harian_insert', $result['steps']);
$ok5 = run_sql($koneksi, $create_harian_update, 'create:harian_update', $result['steps']);
$ok6 = run_sql($koneksi, $create_harian_delete, 'create:harian_delete', $result['steps']);
$ok7 = run_sql($koneksi, $create_elearn_insert, 'create:elearn_insert', $result['steps']);
$ok8 = run_sql($koneksi, $create_elearn_update, 'create:elearn_update', $result['steps']);
$ok9 = run_sql($koneksi, $create_elearn_delete, 'create:elearn_delete', $result['steps']);
$ok10 = run_sql($koneksi, $create_sts_bi, 'create:sts_before_insert', $result['steps']);
$ok11 = run_sql($koneksi, $create_sts_bu, 'create:sts_before_update', $result['steps']);

$result['ok'] = ($ok1 && $ok2 && $ok3 && $ok4 && $ok5 && $ok6 && $ok7 && $ok8 && $ok9 && $ok10 && $ok11);
echo json_encode($result);
?>
