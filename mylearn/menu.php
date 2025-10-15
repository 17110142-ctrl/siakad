<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD E-LEARN
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				  <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
					  <li><a href="?pg=<?= enkripsi('peserta') ?>"><i class="material-icons-two-tone">school</i>Data Siswa</a></li>
					  <li><a href="?pg=<?= enkripsi('materi') ?>"><i class="material-icons-two-tone">select_all</i>Materi Belajar</a></li>
					   <li><a href="?pg=<?= enkripsi('tugas') ?>"><i class="material-icons-two-tone">wifi</i>Tugas Belajar</a></li>
					  
					
                    <?php if ($user['level']=='admin'): ?>
					 <li>
					 <a href="#"><i class="material-icons-two-tone">storage</i>Database<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?pg=<?= enkripsi('resetdata') ?>">Reset Database</a>
                            </li>
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
        Created by : Aji Bagaskoro S.Pd, &copy;2025
    </div>
       </div>
	     
	