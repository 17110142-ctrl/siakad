<?php
require '../../vendor/autoload.php';
require '../../koneksi.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Image;

// Fungsi bantu untuk ekstrak teks/gambar dari sel
function extractTextOrImage($cell, $uploadDir = '../../gambar/') {
    $text = '';
    foreach ($cell->getElements() as $element) {
        if (method_exists($element, 'getText')) {
            $text .= $element->getText();
        } elseif ($element instanceof Image) {
            $imagePath = tempnam(sys_get_temp_dir(), 'img_');
            file_put_contents($imagePath, $element->getImageStringData());

            $ext = pathinfo($element->getSource(), PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '.' . $ext;
            $destPath = $uploadDir . $filename;
            copy($imagePath, $destPath);
            unlink($imagePath);

            $text .= '<img src="gambar/' . $filename . '" width="200">';
        }
    }
    return $text;
}

// Proses upload dan baca file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $file = $_FILES['word_file']['tmp_name'];
    $phpWord = IOFactory::load($file);
    $tables = $phpWord->getSections()[0]->getElements();

    foreach ($tables as $element) {
        if (get_class($element) === 'PhpOffice\PhpWord\Element\Table') {
            foreach ($element->getRows() as $index => $row) {
                if ($index == 0) continue; // skip header

                $cells = $row->getCells();
                if (count($cells) < 10) continue;

                $jenis = trim($cells[1]->getText());
                $soal = extractTextOrImage($cells[2]);
                $pilA = extractTextOrImage($cells[3]);
                $pilB = extractTextOrImage($cells[4]);
                $pilC = extractTextOrImage($cells[5]);
                $pilD = extractTextOrImage($cells[6]);
                $pilE = extractTextOrImage($cells[7]);
                $kunci = trim($cells[8]->getText());
                $skor  = trim($cells[9]->getText());

                // Simpan ke database (ubah sesuai skema)
                $query = "INSERT INTO soal (jenis, soal, pilA, pilB, pilC, pilD, pilE, kunci, skor)
                          VALUES ('$jenis', '$soal', '$pilA', '$pilB', '$pilC', '$pilD', '$pilE', '$kunci', '$skor')";
                mysqli_query($conn, $query);
            }
            break;
        }
    }

    echo "<script>alert('Import berhasil'); window.location='../?pg=banksoal';</script>";
}
?>
