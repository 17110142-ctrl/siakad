<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<div class="row">
    <?php
    $tugasQ = mysqli_query($koneksi, "SELECT * FROM tugas ORDER BY tgl_selesai DESC");
    $adaTugas = false;
    while ($tugas = mysqli_fetch_array($tugasQ)) {
        // Cek apakah tugas ini untuk kelas siswa yang sedang login
        $datakelas = unserialize($tugas['kelas']);
        if (in_array($siswa['kelas'], $datakelas) || in_array('semua', $datakelas)) {
            $guru = fetch($koneksi, 'users', ['id_user' => $tugas['id_guru']]);
            $warna = array('red', 'blue', 'green', 'gray', 'purple', 'black');
            $adaTugas = true;
            
            // Menentukan status tugas
            $sekarang = date('Y-m-d H:i:s');
            $status_tugas = '';
            $tombol_aksi = '';

            if ($tugas['tgl_mulai'] > $sekarang) {
                $status_tugas = '<span class="badge bg-secondary">Belum Dimulai</span>';
                $tombol_aksi = '<button class="btn btn-light flex-grow-1 m-l-xxs" disabled>TUGAS BELUM MULAI</button>';
            } elseif ($tugas['tgl_selesai'] < $sekarang) {
                $status_tugas = '<span class="badge bg-danger">Sudah Selesai</span>';
                $tombol_aksi = '<a href="?pg=bukatugas&id='. $tugas['id_tugas'] .'" class="btn btn-info flex-grow-1 m-l-xxs">LIHAT TUGAS</a>';
            } else {
                $status_tugas = '<span class="badge bg-success">Sedang Berlangsung</span>';
                $tombol_aksi = '<a href="?pg=bukatugas&id='. $tugas['id_tugas'] .'" class="btn btn-primary flex-grow-1 m-l-xxs">BUKA TUGAS</a>';
            }
            ?>
            
            <div class="col-xl-4">
                <div class="card widget widget-payment-request">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">TUGAS BELAJAR</h5>
                        <?= $status_tugas ?>
                    </div>
                    <div class="card-body">
                        <div class="widget-payment-request-container">
                            <div class="widget-payment-request-author">
                                <div class="avatar m-r-sm">
                                    <img src="../images/icon/buku.png" alt="">
                                </div>
                                <div class="widget-payment-request-author-info">
                                    <span class="widget-payment-request-author-name"><?= htmlspecialchars($tugas['mapel']) ?></span>
                                    <span class="widget-payment-request-author-about"><?= htmlspecialchars($tugas['judul']) ?></span>
                                </div>
                            </div>
                            <div class="widget-payment-request-product" style="background-color: <?= $warna[rand(0, count($warna) - 1)] ?>;">
                                <div class="widget-payment-request-product-image m-r-sm">
                                    <?php if(empty($guru['foto'])): ?>
                                        <img src="../images/guru.png" class="mt-auto" alt="">
                                    <?php else: ?>
                                        <img src="../images/<?= htmlspecialchars($guru['foto']) ?>" class="mt-auto" alt="">
                                    <?php endif; ?>
                                </div>
                                <div class="widget-payment-request-product-info d-flex">
                                    <div class="widget-payment-request-product-info-content">
                                        <span class="widget-payment-request-product-name" style="color:#fff;">Guru Pengampu</span>
                                        <span class="widget-payment-request-product-about" style="color:#fff;"><?= htmlspecialchars($guru['nama']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-payment-request-info m-t-md">
                                <div class="widget-payment-request-info-item">
                                    <span class="widget-payment-request-info-title d-block">
                                        MATERI TUGAS
                                    </span>
                                    <span class="text-muted d-block"><?= substr(strip_tags($tugas['tugas']), 0, 30) ?>....</span>
                                </div>
                                <div class="widget-payment-request-info-item">                                    
                                    <span class="text-muted d-block">MULAI &nbsp;&nbsp;&nbsp;&nbsp;<?= date('d/m/Y H:i', strtotime($tugas['tgl_mulai'])) ?></span>                                                    
                                    <span class="text-muted d-block">SELESAI <?= date('d/m/Y H:i', strtotime($tugas['tgl_selesai'])) ?></span>
                                </div>
                            </div>
                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                <?= $tombol_aksi ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    if (!$adaTugas) {
        echo '<div class="alert alert-light" role="alert">Tidak ada Tugas pada hari ini</div>';
    }
    ?>
</div>
