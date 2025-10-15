<?php
// export.php

require __DIR__ . '/../myhome/library/fpdf.php';
include __DIR__ . '/../config/koneksi.php';

class PDF extends FPDF {
    public function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $sep = -1;
        $i = $j = $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    public function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage();
        }
    }

    public function Header() {
        if ($this->PageNo() == 1) {
            $this->SetFont('Arial','B',14);
            $this->Cell(0,10,'Laporan Status Kelengkapan Biodata Siswa',0,1,'C');
            $this->Ln(4);
        }
        $this->SetFont('Arial','B',11);
        $w = [10,60,30,30,60];
        $this->Cell($w[0],8,'No',1,0,'C');
        $this->Cell($w[1],8,'Nama',1,0,'C');
        $this->Cell($w[2],8,'Kelas',1,0,'C');
        $this->Cell($w[3],8,'NISN',1,0,'C');
        $this->Cell($w[4],8,'Status',1,1,'C');
        $this->SetFont('Arial','',10);
    }
}

// ambil filter kelas
$filterKelas = isset($_GET['filterKelas']) && $_GET['filterKelas']!==''
    ? mysqli_real_escape_string($koneksi, $_GET['filterKelas'])
    : '';
$where = $filterKelas ? "WHERE kelas='$filterKelas'" : '';

// query data
$sql = "
  SELECT nama,kelas,nisn,
         beasiswa,status_ayah,status_ibu,
         t_lahir,tgl_lahir,nik,nokk,agama,email,
         anakke,jumlah_saudara,t_badan,b_badan,l_kepala,
         rt,rw,kelurahan,kecamatan,provinsi,kode_pos,
         hobi,cita_cita,asal_sek,thn_lulus,
         tempat_lahir_ayah,tgl_lahir_ayah,pendidikan_ayah,
         pekerjaan_ayah,penghasilan_ayah,no_hp_ayah,
         tempat_lahir_ibu,tgl_lahir_ibu,pendidikan_ibu,
         pekerjaan_ibu,penghasilan_ibu,no_hp_ibu, kk_ibu
  FROM siswa
  $where
  ORDER BY kelas,nama
";
$res = mysqli_query($koneksi,$sql);

$w = [10,60,30,30,60];
$h_per = 7;

$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

$no = 1;

while ($row = mysqli_fetch_assoc($res)) {
    // Kolom‐kolom dasar yang selalu dicek
    $required = [
      'nama','kelas','nisn','beasiswa',
      't_lahir','tgl_lahir','nik','nokk','agama','email',
      'anakke','jumlah_saudara','t_badan','b_badan','l_kepala',
      'rt','rw','kelurahan','kecamatan','provinsi','kode_pos',
      'hobi','cita_cita','asal_sek','thn_lulus',
    ];

    // Cek status ayah
    $status_ayah = strtolower(trim($row['status_ayah']));
    if (stripos($status_ayah, 'meninggal') === false) {
        $required = array_merge($required, [
            'status_ayah',
            'tempat_lahir_ayah','tgl_lahir_ayah','pendidikan_ayah',
            'pekerjaan_ayah','penghasilan_ayah','no_hp_ayah'
        ]);
    } else {
        $required[] = 'status_ayah';
    }

    // Cek status ibu
    $status_ibu = strtolower(trim($row['status_ibu']));
    if (stripos($status_ibu, 'meninggal') === false) {
        $required = array_merge($required, [
            'status_ibu',
            'tempat_lahir_ibu','tgl_lahir_ibu','pendidikan_ibu',
            'pekerjaan_ibu','penghasilan_ibu','no_hp_ibu'
        ]);
    } else {
        $required[] = 'status_ibu';
    }

    // Tambahkan cek kk_ibu secara eksplisit
    $required[] = 'kk_ibu';

    // Cari field kosong
    $miss = [];
    foreach ($required as $f) {
        $value = trim($row[$f]);

        if ($value === '') {
            if ($f === 'kk_ibu') {
                $miss[] = 'Kartu Keluarga';
            } else {
                $miss[] = ucfirst(str_replace('_',' ', $f));
            }
        }
    }

    $status = empty($miss)
        ? 'Lengkap'
        : 'Belum Lengkap: ' . implode(', ', $miss);

    // hitung baris Nama & Status
    $lnNama   = $pdf->NbLines($w[1], $row['nama']);
    $lnStat   = $pdf->NbLines($w[4], $status);
    $lines    = max($lnNama, $lnStat);
    $h_row    = $h_per * $lines;

    $pdf->CheckPageBreak($h_row);

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Cell($w[0], $h_row, $no++, 1, 0, 'C');

    $pdf->Rect($x+$w[0], $y, $w[1], $h_row);
    $pdf->SetXY($x+$w[0], $y);
    $pdf->MultiCell($w[1], $h_per, $row['nama'], 0, 'L');

    $pdf->SetXY($x + $w[0] + $w[1], $y);
    $pdf->Cell($w[2], $h_row, $row['kelas'], 1, 0, 'C');

    $pdf->Cell($w[3], $h_row, $row['nisn'], 1, 0, 'C');

    $pdf->Rect($x + array_sum(array_slice($w,0,4)), $y, $w[4], $h_row);
    $pdf->SetXY($x + array_sum(array_slice($w,0,4)), $y);
    $pdf->MultiCell($w[4], $h_per, $status, 0, 'L');

    $pdf->SetXY($x, $y + $h_row);
}

$namaKelas = $filterKelas !== '' ? $filterKelas : 'Semua_Kelas';
$namaKelas = str_replace(' ', '_', $namaKelas);
$pdfFileName = 'status kelengkapan ' . $namaKelas . '.pdf';
$pdf->Output('I', $pdfFileName);

exit;
