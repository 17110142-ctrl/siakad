<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                      KEGIATAN BELAJAR MENGAJAR
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				   <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">settings</i>Master KBM<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li class="sidebar-title">KURIKULUM MERDEKA</li>
						<li><a href="?pg=<?= enkripsi('lingkup') ?>">Lingkup Materi</a></li>
						<li><a href="?pg=<?= enkripsi('tujuan') ?>">Tujuan Pembelajaran</a></li>
						<li class="sidebar-title">KURIKULUM 2013</li>
                            <li><a href="?pg=<?= enkripsi('ki3') ?>">KI-3 (Pengetahuan)</a></li>
							<li><a href="?pg=<?= enkripsi('ki4') ?>">KI-4 (Keterampilan)</a></li>
							<li><a href="?pg=upload">Upload Administrasi</a></li>
                        </ul>
                    </li>
					
					<li>
                        <a href="#"><i class="material-icons-two-tone">crisis_alert</i>Absensi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                        <li><a href="?pg=<?= enkripsi('lihatabsen') ?>">Absensi</a></li>
						<li><a href="?pg=<?= enkripsi('manual') ?>">Input Manual</a></li>
						<li><a href="?pg=<?= enkripsi('absensi') ?>">Sinkron Presensi</a></li>                       						
                        </ul>
                    </li>
					  <li>
                        <a href="#"><i class="material-icons-two-tone">workspaces</i>Agenda Guru<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">					
						<li><a href="?pg=<?= enkripsi('agenda') ?>">Agenda Guru</a></li>
						<li><a href="?pg=<?= enkripsi('jurnal') ?>">Jurnal Guru</a></li>				
                        </ul>
                    </li>
					
					<li>
                    <a href="#"><i class="material-icons-two-tone">select_all</i>Penilaian Harian<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                      <ul class="sub-menu">					
						<li><a href="?pg=<?= enkripsi('nilai') ?>">Input PH</a></li>
						<li><a href="?pg=<?= enkripsi('cnil') ?>">Cetak PH</a></li>									
						 </ul>
                    </li>
					<li>
                    <a href="#"><i class="material-icons-two-tone">restart_alt</i>Katrol Nilai<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                      <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('katrol') ?>">Katrol PH</a></li>
						<li><a href="?pg=<?= enkripsi('ckatrol') ?>">Cetak Katrol PH</a></li>									
						 </ul>
                    </li>
					
					 <?php if ($user['level']=='admin'): ?>
					 <li class="sidebar-title">
                      DATABASE
                    </li>
					 <li>
					 <a href="#"><i class="material-icons-two-tone">storage</i>Database<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                        
						 <li><a href="?pg=<?= enkripsi('resetdata') ?>">Reset Database</a></li>
                        </ul>
                    </li>
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
        Created by : Aji Bagaskoro, S.Pd, &copy;2025
    </div>
       </div>
	     
	