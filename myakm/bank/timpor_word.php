<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require_once '../../vendor/autoload.php'; // pastikan sudah install PHPWord dengan Composer

use PhpOffice\PhpWord\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idmapel = $_POST['idmapel'];
    $nomer = $_POST['nomer'] ?? 0;

    if (isset($_FILES['file']['name']) && pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) === 'docx') {
        $uploadFilePath = '../../files/' . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);

        $phpWord = IOFactory::load($uploadFilePath);
        $tables = $phpWord->getSections()[0]->getElements();

        foreach ($tables as $element) {
            if (method_exists($element, 'getRows')) {
                $rows = $element->getRows();
                foreach ($rows as $index => $row) {
                    if ($index < 1) continue; // Lewati header tabel

                    $cells = $row->getCells();
                    $no = trim($cells[0]->getElements()[0]->getText());
                    $soal = addslashes(trim($cells[1]->getElements()[0]->getText()));
                    $jenis = trim($cells[2]->getElements()[0]->getText());
                    $pilA = addslashes(trim($cells[3]->getElements()[0]->getText()));
                    $pilB = addslashes(trim($cells[4]->getElements()[0]->getText()));
                    $pilC = addslashes(trim($cells[5]->getElements()[0]->getText()));
                    $pilD = addslashes(trim($cells[6]->getElements()[0]->getText()));
                    $pilE = addslashes(trim($cells[7]->getElements()[0]->getText()));
                    $jawaban = trim($cells[8]->getElements()[0]->getText());
                    $max_skor = trim($cells[9]->getElements()[0]->getText());

                    $nomor = $nomer + $no;

                    $sql = "INSERT INTO soal (id_bank, nomor, soal, jenis, pilA, pilB, pilC, pilD, pilE, jawaban, max_skor)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $koneksi->prepare($sql);
                    $stmt->bind_param("iissssssssi", $idmapel, $nomor, $soal, $jenis, $pilA, $pilB, $pilC, $pilD, $pilE, $jawaban, $max_skor);
                    $stmt->execute();
                }
                break; // hanya ambil tabel pertama
            }
        }

        echo json_encode(["status" => "success", "message" => "Impor soal dari Word berhasil."]);
    } else {
        echo json_encode(["status" => "error", "message" => "File tidak valid. Harus .docx"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diperbolehkan"]);
}
?>
