<?php
// Redirect ke endpoint gabungan agar tidak 404 bila URL lama digunakan
$q = [];
foreach (['kelas','semester','tp'] as $k) { if (isset($_GET[$k])) $q[$k] = $_GET[$k]; }
$q['format'] = 'pts_pdf';
$qs = http_build_query($q);
header('Location: export_nilai_sts.php?'.$qs);
exit;
