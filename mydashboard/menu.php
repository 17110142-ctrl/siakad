
<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD UTAMA SISWA
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
					<li><a href="../"><i class="material-icons-two-tone">apps</i>E-Asessmen</a></li>
					<li><a href="?pg=profil_siswa"><i class="material-icons-two-tone">person</i>Profil Siswa</a></li>
                        <li>
                        <a href="#"><i class="material-icons-two-tone">menu</i>K B M<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('nilai') ?>">Nilai Harian</a></li>
						<li><a href="?pg=absensi">Absensi</a></li>
                        </ul>
                        </li>
                    <li><a href='?pg=perpus'><i class="material-icons-two-tone">auto_stories</i>Data Peminjaman Buku</a></li>
                    <li><a href='?pg=prestasi'><i class="material-icons-two-tone">emoji_events</i>Prestasi</a></li>
                    <li><a href="../mybayar/student/"><i class="material-icons-two-tone">credit_card</i>Pembayaran</a></li>
                    <li><a href="?pg=<?= enkripsi('refleksi') ?>"><i class="material-icons-two-tone">favorite</i>Refleksi</a></li>
                    <li><a href="?pg=<?= enkripsi('khs') ?>"><i class="material-icons-two-tone">edit</i>Kartu Hasil Studi</a></li>
                    <li><a href="?pg=<?= enkripsi('konsel') ?>"><i class="material-icons-two-tone">star</i>Pelanggaran</a></li>
                    <li>
                        <a href="#"><i class="material-icons-two-tone">select_all</i>E-Learning<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('materi') ?>">Materi Belajar</a></li>
						<li><a href="?pg=<?= enkripsi('tugas') ?>">Tugas Belajar</a></li>
                        </ul>
                        </li>
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
