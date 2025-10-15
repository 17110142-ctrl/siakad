<?php
// Rebuild triggers to compute proper average and avoid duplicate rows by update-or-insert logic.
// Usage: php maintenance/fix_triggers.php

header('Content-Type: application/json');
$out = ['ok' => false, 'steps' => []];
require_once __DIR__ . '/../config/koneksi.php';
if (!$koneksi) { echo json_encode(['ok'=>false,'error'=>'DB connect failed']); exit; }

function step($conn,$sql,$label,&$out){ $ok=mysqli_query($conn,$sql); $out['steps'][]=['label'=>$label,'success'=>(bool)$ok,'error'=>$ok?null:mysqli_error($conn)]; return $ok; }

// Drop existing
// Drop existing: tugas triggers
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_delete", 'drop:tugas_delete', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_insert", 'drop:tugas_insert', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_tugas_update", 'drop:tugas_update', $out);
// Drop existing: harian triggers
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_insert", 'drop:harian_insert', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_update", 'drop:harian_update', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_harian_delete", 'drop:harian_delete', $out);
// Drop existing: e-learning triggers
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_insert", 'drop:elearn_insert', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_update", 'drop:elearn_update', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_update_nilai_sts_after_elearning_delete", 'drop:elearn_delete', $out);
// Drop existing: guards on nilai_sts itself
step($koneksi, "DROP TRIGGER IF EXISTS trg_nilai_sts_before_insert_avg", 'drop:sts_bi', $out);
step($koneksi, "DROP TRIGGER IF EXISTS trg_nilai_sts_before_update_avg", 'drop:sts_bu', $out);

// Helper SQL to compute average
$avg_template = <<<SQL
    SELECT ROUND(AVG(v.nil),0) FROM (
        SELECT (nh.nilai+0) AS nil
        FROM nilai_harian nh
        WHERE nh.idsiswa = @IDS AND nh.mapel = @MAPEL AND nh.semester = @SEM AND nh.tapel = @TP AND nh.nilai IS NOT NULL
        UNION ALL
        SELECT (jt.nilai+0) AS nil
        FROM jawaban_tugas jt
        JOIN mata_pelajaran mp ON jt.nama_mapel = mp.kode
        WHERE jt.id_siswa = @IDS AND mp.id = @MAPEL AND jt.semester = @SEM AND jt.tapel = @TP AND jt.nilai IS NOT NULL
        UNION ALL
        SELECT (n.nilai+0) AS nil
        FROM nilai n
        JOIN ujian u ON n.id_ujian = u.id_ujian
        JOIN mata_pelajaran mp2 ON LOCATE(mp2.kode, u.kode_nama) = 1
        WHERE n.id_siswa = @IDS AND mp2.id = @MAPEL AND n.semester = @SEM AND n.tp = @TP AND n.nilai IS NOT NULL
    ) v
SQL;

// After INSERT
$create_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_insert
AFTER INSERT ON jawaban_tugas FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    DECLARE v_id INT; DECLARE v_nis VARCHAR(50); DECLARE v_kelas VARCHAR(50); DECLARE v_guru INT;

    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode=NEW.nama_mapel LIMIT 1;
    IF v_mapel IS NOT NULL AND NEW.nilai IS NOT NULL THEN
        SET @IDS = NEW.id_siswa; SET @MAPEL = v_mapel; SET @SEM = v_sem; SET @TP = v_tp;
        SELECT {$avg_template} INTO v_avg;

        SELECT id, nis, kelas INTO v_id, v_nis, v_kelas FROM siswa WHERE id_siswa=NEW.id_siswa LIMIT 1;
        SELECT id_guru INTO v_guru FROM tugas WHERE id_tugas=NEW.id_tugas LIMIT 1;

        IF EXISTS (SELECT 1 FROM nilai_sts WHERE idsiswa=NEW.id_siswa AND mapel=v_mapel AND semester=v_sem AND tp=v_tp LIMIT 1) THEN
            UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa=NEW.id_siswa AND mapel=v_mapel AND semester=v_sem AND tp=v_tp;
        ELSE
            INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_harian, guru, semester, tp, nilai_raport)
            VALUES (NEW.id_siswa, v_nis, v_kelas, v_mapel, v_avg, v_guru, v_sem, v_tp, 0);
        END IF;
    END IF;
END
SQL;

// After UPDATE
$create_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_update
AFTER UPDATE ON jawaban_tugas FOR EACH ROW
BEGIN
    IF NEW.nilai <> OLD.nilai THEN
        DECLARE v_mapel INT; DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
        SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode=NEW.nama_mapel LIMIT 1;
        IF v_mapel IS NOT NULL THEN
            SET @IDS = NEW.id_siswa; SET @MAPEL = v_mapel; SET @SEM = v_sem; SET @TP = v_tp;
            SELECT {$avg_template} INTO v_avg;
            UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa=NEW.id_siswa AND mapel=v_mapel AND semester=v_sem AND tp=v_tp;
        END IF;
    END IF;
END
SQL;

// After DELETE
$create_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_tugas_delete
AFTER DELETE ON jawaban_tugas FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(OLD.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(OLD.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SELECT id INTO v_mapel FROM mata_pelajaran WHERE kode=OLD.nama_mapel LIMIT 1;
    IF v_mapel IS NOT NULL THEN
        SET @IDS = OLD.id_siswa; SET @MAPEL = v_mapel; SET @SEM = v_sem; SET @TP = v_tp;
        SELECT {$avg_template} INTO v_avg;
        UPDATE nilai_sts SET nilai_harian = v_avg WHERE idsiswa=OLD.id_siswa AND mapel=v_mapel AND semester=v_sem AND tp=v_tp;
    END IF;
END
SQL;

// --- Triggers for nilai_harian (daily grades) ---
$create_harian_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_insert
AFTER INSERT ON nilai_harian FOR EACH ROW
BEGIN
    DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    IF NEW.nilai IS NOT NULL THEN
        SET @IDS = NEW.idsiswa; SET @MAPEL = NEW.mapel; SET @SEM = v_sem; SET @TP = v_tp;
        SELECT {$avg_template} INTO v_avg;
        IF EXISTS (SELECT 1 FROM nilai_sts WHERE idsiswa=NEW.idsiswa AND mapel=NEW.mapel AND semester=v_sem AND tp=v_tp LIMIT 1) THEN
            UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=NEW.idsiswa AND mapel=NEW.mapel AND semester=v_sem AND tp=v_tp;
        ELSE
            INSERT INTO nilai_sts (idsiswa, nis, kelas, mapel, nilai_harian, guru, semester, tp, nilai_raport)
            SELECT NEW.idsiswa, s.nis, NEW.kelas, NEW.mapel, v_avg, NEW.guru, v_sem, v_tp, 0 FROM siswa s WHERE s.id_siswa=NEW.idsiswa LIMIT 1;
        END IF;
    END IF;
END
SQL;

$create_harian_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_update
AFTER UPDATE ON nilai_harian FOR EACH ROW
BEGIN
    IF NEW.nilai <> OLD.nilai THEN
        DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
        SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SET v_tp  = IFNULL(NULLIF(NEW.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
        SET @IDS = NEW.idsiswa; SET @MAPEL = NEW.mapel; SET @SEM = v_sem; SET @TP = v_tp;
        SELECT {$avg_template} INTO v_avg;
        UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=NEW.idsiswa AND mapel=NEW.mapel AND semester=v_sem AND tp=v_tp;
    END IF;
END
SQL;

$create_harian_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_harian_delete
AFTER DELETE ON nilai_harian FOR EACH ROW
BEGIN
    DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(OLD.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(OLD.tapel,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET @IDS = OLD.idsiswa; SET @MAPEL = OLD.mapel; SET @SEM = v_sem; SET @TP = v_tp;
    SELECT {$avg_template} INTO v_avg;
    UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=OLD.idsiswa AND mapel=OLD.mapel AND semester=v_sem AND tp=v_tp;
END
SQL;

// --- Triggers for e-learning scores table `nilai` ---
$create_elearn_insert = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_insert
AFTER INSERT ON nilai FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg INT;
    SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama)=1 WHERE u.id_ujian=NEW.id_ujian LIMIT 1;
    IF v_mapel IS NOT NULL AND NEW.nilai IS NOT NULL THEN
        SET @IDS = NEW.id_siswa; SET @MAPEL = v_mapel; SET @SEM = NEW.semester; SET @TP = NEW.tp;
        SELECT {$avg_template} INTO v_avg;
        UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=NEW.id_siswa AND mapel=v_mapel AND semester=NEW.semester AND tp=NEW.tp;
    END IF;
END
SQL;

$create_elearn_update = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_update
AFTER UPDATE ON nilai FOR EACH ROW
BEGIN
    IF NEW.nilai <> OLD.nilai THEN
        DECLARE v_mapel INT; DECLARE v_avg INT;
        SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama)=1 WHERE u.id_ujian=NEW.id_ujian LIMIT 1;
        IF v_mapel IS NOT NULL THEN
            SET @IDS = NEW.id_siswa; SET @MAPEL = v_mapel; SET @SEM = NEW.semester; SET @TP = NEW.tp;
            SELECT {$avg_template} INTO v_avg;
            UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=NEW.id_siswa AND mapel=v_mapel AND semester=NEW.semester AND tp=NEW.tp;
        END IF;
    END IF;
END
SQL;

$create_elearn_delete = <<<SQL
CREATE TRIGGER trg_update_nilai_sts_after_elearning_delete
AFTER DELETE ON nilai FOR EACH ROW
BEGIN
    DECLARE v_mapel INT; DECLARE v_avg INT;
    SELECT mp.id INTO v_mapel FROM ujian u JOIN mata_pelajaran mp ON LOCATE(mp.kode, u.kode_nama)=1 WHERE u.id_ujian=OLD.id_ujian LIMIT 1;
    IF v_mapel IS NOT NULL THEN
        SET @IDS = OLD.id_siswa; SET @MAPEL = v_mapel; SET @SEM = OLD.semester; SET @TP = OLD.tp;
        SELECT {$avg_template} INTO v_avg;
        UPDATE nilai_sts SET nilai_harian=v_avg WHERE idsiswa=OLD.id_siswa AND mapel=v_mapel AND semester=OLD.semester AND tp=OLD.tp;
    END IF;
END
SQL;

// --- Guards on nilai_sts so manual writes are auto-averaged ---
$create_sts_bi = <<<SQL
CREATE TRIGGER trg_nilai_sts_before_insert_avg
BEFORE INSERT ON nilai_sts FOR EACH ROW
BEGIN
    DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tp,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET @IDS = NEW.idsiswa; SET @MAPEL = NEW.mapel; SET @SEM = v_sem; SET @TP = v_tp;
    SELECT {$avg_template} INTO v_avg;
    SET NEW.nilai_harian = IFNULL(v_avg, IFNULL(NEW.nilai_harian,0));
    SET NEW.semester = v_sem; SET NEW.tp = v_tp;
END
SQL;

$create_sts_bu = <<<SQL
CREATE TRIGGER trg_nilai_sts_before_update_avg
BEFORE UPDATE ON nilai_sts FOR EACH ROW
BEGIN
    DECLARE v_avg INT; DECLARE v_sem INT; DECLARE v_tp VARCHAR(10);
    SET v_sem = IFNULL(NULLIF(NEW.semester,''),(SELECT semester FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET v_tp  = IFNULL(NULLIF(NEW.tp,''),(SELECT tp FROM aplikasi WHERE id_aplikasi=1 LIMIT 1));
    SET @IDS = NEW.idsiswa; SET @MAPEL = NEW.mapel; SET @SEM = v_sem; SET @TP = v_tp;
    SELECT {$avg_template} INTO v_avg;
    SET NEW.nilai_harian = IFNULL(v_avg, IFNULL(NEW.nilai_harian,0));
    SET NEW.semester = v_sem; SET NEW.tp = v_tp;
END
SQL;

$ok1 = step($koneksi, $create_insert, 'create:tugas_insert', $out);
$ok2 = step($koneksi, $create_update, 'create:tugas_update', $out);
$ok3 = step($koneksi, $create_delete, 'create:tugas_delete', $out);
$ok4 = step($koneksi, $create_harian_insert, 'create:harian_insert', $out);
$ok5 = step($koneksi, $create_harian_update, 'create:harian_update', $out);
$ok6 = step($koneksi, $create_harian_delete, 'create:harian_delete', $out);
$ok7 = step($koneksi, $create_elearn_insert, 'create:elearn_insert', $out);
$ok8 = step($koneksi, $create_elearn_update, 'create:elearn_update', $out);
$ok9 = step($koneksi, $create_elearn_delete, 'create:elearn_delete', $out);
$ok10 = step($koneksi, $create_sts_bi, 'create:sts_before_insert', $out);
$ok11 = step($koneksi, $create_sts_bu, 'create:sts_before_update', $out);

$out['ok'] = ($ok1 && $ok2 && $ok3 && $ok4 && $ok5 && $ok6 && $ok7 && $ok8 && $ok9 && $ok10 && $ok11);
echo json_encode($out);
?>
