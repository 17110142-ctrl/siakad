<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                      E-SAPRAS (NON MESIN SAPRAS)
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				   <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">settings</i>Master Sapras<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('kategori') ?>">Kategori</a></li>
						<li><a href="?pg=<?= enkripsi('lokasi') ?>">Lokasi / Ruang</a></li>					
                        </ul>
                    </li>
					
					<li>
                        <a href="#"><i class="material-icons-two-tone">crisis_alert</i>Input Sapras<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('barang') ?>">Input Sapras</a></li>
						                   						
                        </ul>
                    </li>
					 <li class="sidebar-title">
                      ASET SEKOLAH
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">shopping_cart</i>Input Aset Sekolah<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('aset') ?>">Input Data Aset</a></li>						                   						
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
        Created by : Aji Bagaskoro S.Pd, &copy;2025
    </div>
       </div>
	     
	