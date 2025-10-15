<?php
$hasKurikulumTask = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
?>
<div class="app-menu">
    <ul class="accordion-menu">
        <li class="sidebar-title">
            DASHBOARD UTAMA
        </li>
        <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>

        <?php
        //================================================
        // MENU UNTUK ADMIN
        //================================================
        if ($user['level'] == 'admin') :
        ?>
            <li>
                <a href="#"><i class="material-icons-two-tone">apps</i>Dashboard<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="../mykbm">E-K B M</a></li>
                    <li><a href="../myakm">E-Asesmen</a></li>
                    <li><a href="../mypres">E-Presensi</a></li>
                    <li><a href="../mylearn">E-Learn</a></li>
                    <li><a href="../mykurmer">Rapor Kurmer</a></li>
                    <li><a href="../myproyek">Rapor P5</a></li>
                    <li><a href="../mykurtilas">Rapor K-13</a></li>
                    <li><a href="../myskl">S K L</a></li>
                    <li><a href="../mykonsel">E-Konseling</a></li>
                    <li><a href="../mybayar">E-Payment</a></li>
                    <li><a href="../mypras">E-Sapras</a></li>
                    <li><a href="../myperpus">E-Perpus</a></li>
                    <li><a href="../mykantin">E-Kantin</a></li>
                    <li><a href="../myarsip">E-Arsip</a></li>
                    <li><a href="../mylulus">Kelulusan</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">menu</i>Data Master<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="?pg=<?= enkripsi('pmapel') ?>">Impor Mapel</a></li>
                    <li><a href="?pg=<?= enkripsi('msiswa') ?>">Impor Kelas & Siswa</a></li>
                    <li><a href="?pg=<?= enkripsi('ketua') ?>">Ketua Kelas</a></li>
                    <li><a href="?pg=<?= enkripsi('kuri') ?>">Kurikulum</a></li>
                    <li><a href="?pg=<?= enkripsi('mjadwal') ?>">Jadwal Mengajar</a></li>
                    <li><a href="?pg=<?= enkripsi('eskul') ?>">Ekstrakurikuler</a></li>
                    <li><a href="?pg=<?= enkripsi('api') ?>">API</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">people</i>Data Users<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="?pg=<?= enkripsi('admin') ?>">Administrator</a></li>
                    <li><a href="?pg=<?= enkripsi('kepsek') ?>">Kepala Sekolah</a></li>
                    <li><a href="?pg=<?= enkripsi('guru') ?>">Data Guru</a></li>
                    <li><a href="?pg=<?= enkripsi('staff') ?>">Data Staff</a></li>
                    <li><a href="?pg=<?= enkripsi('tampil') ?>">Data Biodata Siswa</a></li>
                </ul>
            </li>
            <li><a href="?pg=<?= enkripsi('informasi') ?>"><i class="material-icons-two-tone">alarm</i>Informasi</a></li>
            <li><a href="?pg=<?= enkripsi('peserta') ?>"><i class="material-icons-two-tone">school</i>Data Siswa</a></li>
            <li><a href="?pg=<?= enkripsi('mutasi') ?>"><i class="material-icons-two-tone">select_all</i>Kenaikan Kelas</a></li>
            <li><a href="?pg=<?= enkripsi('pdb') ?>"><i class="material-icons-two-tone">upload</i>Peserta Didik Baru</a></li>
            <li>
                <a href="#"><i class="material-icons-two-tone">settings</i>Pengaturan<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="?pg=<?= enkripsi('pengaturan') ?>">Sekolah</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">autorenew</i>Sinkronisasi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="?pg=<?= enkripsi('setsinkron') ?>">Setting URL Sinkron</a></li>
                    <li><a href="?pg=<?= enkripsi('sinmas') ?>">Sinkron Data Master</a></li>
                </ul>
            </li>
            <li class="sidebar-title">
                DATABASE
            </li>
            <li>
                <a href="#"><i class="material-icons-two-tone">storage</i>Database<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="?pg=<?= enkripsi('backupdata') ?>">Backup Database</a></li>
                    <li><a href="?pg=<?= enkripsi('restore') ?>">Restore Database</a></li>
                    <li><a href="?pg=<?= enkripsi('resetdata') ?>">Reset Database</a></li>
                </ul>
            </li>

        <?php
        //================================================
        // MENU UNTUK GURU
        //================================================
        elseif ($user['level'] == 'guru') :
        ?>
            <li>
                <a href="#"><i class="material-icons-two-tone">apps</i>Dashboard<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="../mykbm">E-K B M</a></li>
                    <li><a href="../myakm">E-Asesmen</a></li>
                    <li><a href="../mypres">E-Presensi</a></li>
                    <li><a href="../mylearn">E-Learn</a></li>
                    <li><a href="../mykurmer">Rapor Kurmer</a></li>
                    <li><a href="../myproyek">Rapor P5</a></li>
                    <li><a href="../myskl">S K L</a></li>
                    <li><a href="../mykonsel">E-Konseling</a></li>
                    <li><a href="../myarsip">E-Arsip</a></li>
                    <?php
            // TAMBAHKAN BLOK INI: Tampilkan menu jika guru bertugas di perpustakaan
            if ($user['tugas'] == 'perpustakaan') :
            ?>
                <li><a href="../myperpus">E-Perpus</a></li>
            <?php endif; ?>
                </ul>
            </li>
            <li><a href="?pg=<?= enkripsi('mjadwal') ?>"><i class="material-icons-two-tone">today</i>Jadwal Mengajar</a></li>
            
            <?php
            // Sub-menu jika guru juga sebagai Kurikulum
            if ($user['tugas'] == 'kurikulum' || $user['tugas'] == 'staff') : ?>
                <li class="sidebar-title">DASHBOARD KURIKULUM</li>
                <li>
                    <a href="#"><i class="material-icons-two-tone">menu</i>Data Master<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                    <ul class="sub-menu">
                        <li><a href="?pg=<?= enkripsi('pmapel') ?>">Impor Mapel</a></li>
                        <li><a href="?pg=<?= enkripsi('msiswa') ?>">Impor Kelas & Siswa</a></li>
                        <li><a href="?pg=<?= enkripsi('ketua') ?>">Ketua Kelas</a></li>
                        <li><a href="?pg=<?= enkripsi('kuri') ?>">Kurikulum</a></li>
                        <li><a href="?pg=<?= enkripsi('eskul') ?>">Ekstrakurikuler</a></li>
                    </ul>
                </li>
                <li><a href="?pg=<?= enkripsi('tampil') ?>"><i class="material-icons-two-tone">people</i>Data Biodata Siswa</a></li>
                <li><a href="?pg=<?= enkripsi('mutasi') ?>"><i class="material-icons-two-tone">select_all</i>Kenaikan Kelas</a></li>
                <li><a href="?pg=<?= enkripsi('pdb') ?>"><i class="material-icons-two-tone">upload</i>Peserta Didik Baru</a></li>
                <?php if ($hasKurikulumTask) : ?>
                    <li><a href="?pg=<?= enkripsi('guruwali') ?>"><i class="material-icons-two-tone">supervisor_account</i>Guru Wali</a></li>
                <?php endif; ?>
            <?php endif; ?>

            <?php
            // Sub-menu jika guru juga sebagai Bendahara
            if ($user['tugas'] == 'bendahara') : ?>
                <li><a href="?pg=<?= enkripsi('tampil') ?>"><i class="material-icons-two-tone">people</i>Data Biodata Siswa</a></li>
            <?php endif; ?>

            <?php
            // Sub-menu jika guru juga sebagai Wali Kelas
            if ($user['walas'] <> '' || $hasKurikulumTask) : ?>
                <li class="sidebar-title">DASHBOARD WALI KELAS</li>
                <li><a href="?pg=<?= enkripsi('tampil') ?>"><i class="material-icons-two-tone">people</i>Data Biodata Siswa</a></li>
                <li>
                    <a href="?pg=dashgur"><i class="material-icons-two-tone">menu</i>Wali Kelas<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                    <ul class="sub-menu">
                        <li><a href="?pg=<?= enkripsi('tampil') ?>">Data Biodata Siswa</a></li>
                        <li><a href="?pg=absensiswa">Absensi Siswa</a></li>
                        <li><a href="?pg=prestasi">Prestasi Siswa</a></li>
                        <li><a href="?pg=pelanggaran">Pelanggaran Siswa</a></li>
                    </ul>
                </li>
            <?php endif; ?>


        <?php
        //================================================
        // MENU UNTUK KEPALA SEKOLAH
        //================================================
        elseif ($user['level'] == 'kepala') :
        ?>
            <li>
                <a href="#"><i class="material-icons-two-tone">apps</i>Dashboard<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="../mykbm">E-K B M</a></li>
                    <li><a href="../myakm">E-Asesmen</a></li>
                    <li><a href="../mypres">E-Presensi</a></li>
                    <li><a href="../mylearn">E-Learn</a></li>
                    <li><a href="../mykurmer">Rapor Kurmer</a></li>
                    <li><a href="../myproyek">Rapor P5</a></li>
                    <li><a href="../myskl">S K L</a></li>
                    <li><a href="../mykonsel">E-Konseling</a></li>
                    <li><a href="../myarsip">E-Arsip</a></li>
                </ul>
            </li>
            <li><a href="?pg=kbm">KBM</a></li>
            <li><a href="?pg=jadwalku">JADWALKU</a></li>
            <li><a href="?pg=jadwalmu">JADWAL KBM</a></li>
            <li><a href="?pg=abpeg">ABSEN PEGAWAI</a></li>
            <li><a href="?pg=<?= enkripsi('tampil') ?>"><i class="material-icons-two-tone">people</i>Data Biodata Siswa</a></li>

        <?php
        //================================================
        // MENU UNTUK STAFF
        //================================================
        elseif ($user['level'] == 'staff') :
        ?>
            <li>
                <a href="#"><i class="material-icons-two-tone">apps</i>Dashboard<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                <ul class="sub-menu">
                    <li><a href="../myskl">S K L</a></li>
                    <li><a href="../mybayar">E-Payment</a></li>
                    <li><a href="../mypras">E-Sapras</a></li>
                    <li><a href="../myperpus">E-Perpus</a></li>
                    <li><a href="../mykantin">E-Kantin</a></li>
                    <li><a href="../myarsip">E-Arsip</a></li>
                </ul>
            </li>
            <li><a href="?pg=<?= enkripsi('tampil') ?>"><i class="material-icons-two-tone">people</i>Data Biodata Siswa</a></li>
            <li><a href="?pg=<?= enkripsi('mutasi') ?>"><i class="material-icons-two-tone">select_all</i>Kenaikan dan Mutasi Siswa</a></li>
            <li><a href="?pg=<?= enkripsi('pdb') ?>"><i class="material-icons-two-tone">upload</i>Peserta Didik Baru</a></li>
        <?php endif; ?>

        <li>
            <a href="logout.php"><i class="material-icons-two-tone">logout</i>Logout</a>
        </li>
    </ul>
    
</div>
<div class="sidebar-footer" style="
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        text-align: center;
        font-size: 12px;
        color: #888;
        background-color: #f9f9f9; /* Warna latar yang sedikit berbeda */
        border-top: 1px solid #eee; /* Garis pemisah */
    ">
        Created by : Aji Bagaskoro, S.Pd &copy;2025
    </div>
