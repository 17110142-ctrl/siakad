<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
$kode = $_POST['id'];

$query = "SELECT * FROM materi WHERE id_materi='".$kode."'";
		$sql = mysqli_query($koneksi, $query); 
		$data = mysqli_fetch_array($sql);

		if(is_file("../../materi/".$data['file'])) 
			unlink("../../materi/".$data['file']); 
// Hapus materi + data terkait dalam satu transaksi
mysqli_begin_transaction($koneksi);
try {
    // Ambil semua baris nilai_harian yang terkait QUIZ materi ini (untuk perhitungan ulang nilai_sts)
    $affected = [];
    $like = 'QUIZ#' . intval($kode) . '%';
    if ($stmtSel = $koneksi->prepare("SELECT id, idsiswa, mapel, semester, tapel FROM nilai_harian WHERE materi LIKE ?")) {
        $stmtSel->bind_param('s', $like);
        $stmtSel->execute();
        $resSel = $stmtSel->get_result();
        while ($r = $resSel->fetch_assoc()) {
            $key = $r['idsiswa'] . '|' . $r['mapel'] . '|' . $r['semester'] . '|' . $r['tapel'];
            $affected[$key] = [
                'idsiswa' => (int)$r['idsiswa'],
                'mapel' => (int)$r['mapel'],
                'semester' => (string)$r['semester'],
                'tapel' => (string)$r['tapel'],
            ];
        }
        $stmtSel->close();
    }

    // Hapus nilai_harian terkait QUIZ materi ini
    if ($stmtDelNh = $koneksi->prepare("DELETE FROM nilai_harian WHERE materi LIKE ?")) {
        $stmtDelNh->bind_param('s', $like);
        $stmtDelNh->execute();
        $stmtDelNh->close();
    }

    // Hapus quiz dan jawaban_quiz yang terkait materi ini
    // Hapus baris di tabel quiz (FK akan menghapus jawaban_quiz)
    if ($stmtDelQuiz = $koneksi->prepare("DELETE FROM quiz WHERE id_materi=?")) {
        $idmat = (int)$kode;
        $stmtDelQuiz->bind_param('i', $idmat);
        $stmtDelQuiz->execute();
        $stmtDelQuiz->close();
    }

    // Hapus materi, komentar, absen daring terkait (tetap pertahankan kompatibilitas lama)
    mysqli_query($koneksi, "DELETE FROM komentar WHERE id_materi='".$kode."'");
    mysqli_query($koneksi, "DELETE FROM soal_quiz WHERE idmateri='".$kode."'");
    mysqli_query($koneksi, "DELETE FROM absen_daringmapel WHERE idmateri='".$kode."'");
    mysqli_query($koneksi, "DELETE FROM materi WHERE id_materi='".$kode."'");

    // Recalculate nilai_sts for affected students
    foreach ($affected as $a) {
        $total = 0; $jumlah = 0; $rata = 0;
        if ($stmtRR = $koneksi->prepare("SELECT SUM(nilai) AS total, COUNT(*) AS jumlah FROM nilai_harian WHERE idsiswa=? AND mapel=? AND semester=? AND tapel=?")) {
            $stmtRR->bind_param('iiss', $a['idsiswa'], $a['mapel'], $a['semester'], $a['tapel']);
            $stmtRR->execute();
            $resRR = $stmtRR->get_result();
            if ($resRR && $resRR->num_rows) {
                $rowRR = $resRR->fetch_assoc();
                $total = (float)($rowRR['total'] ?? 0);
                $jumlah = (int)($rowRR['jumlah'] ?? 0);
            }
            $stmtRR->close();
            $rata = ($jumlah > 0) ? (int)round($total / $jumlah) : 0;
        }

        // Update atau kosongkan nilai_harian di nilai_sts
        if ($stmtFindSts = $koneksi->prepare("SELECT id FROM nilai_sts WHERE idsiswa=? AND mapel=? AND semester=? AND tp=? LIMIT 1")) {
            $stmtFindSts->bind_param('iiss', $a['idsiswa'], $a['mapel'], $a['semester'], $a['tapel']);
            $stmtFindSts->execute();
            $resFS = $stmtFindSts->get_result();
            if ($resFS && $resFS->num_rows) {
                $rowFS = $resFS->fetch_assoc();
                $idSts = (int)$rowFS['id'];
                $stmtUpdSts = $koneksi->prepare("UPDATE nilai_sts SET nilai_harian=? WHERE id=?");
                if ($stmtUpdSts) { $stmtUpdSts->bind_param('ii', $rata, $idSts); $stmtUpdSts->execute(); $stmtUpdSts->close(); }
            }
            $stmtFindSts->close();
        }
    }

    mysqli_commit($koneksi);
    echo 'ok';
} catch (Throwable $e) {
    mysqli_rollback($koneksi);
    http_response_code(500);
    echo 'error';
}
