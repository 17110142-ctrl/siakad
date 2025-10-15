<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD KANTIN
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				   <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
					
					
					 <li>
                        <a href="#"><i class="material-icons-two-tone">menu</i>Data Master<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                          <?php if($setting['kantin']==1): ?>						
                        <li><a href="?pg=msiswa">Impor Data Konsumen</a></li>
						<?php endif; ?>
                        <li><a href="?pg=toko">Data Kantin</a></li>						
                        <li><a href="?pg=kategori">Kategori Produk</a></li>
						<li><a href="?pg=<?= enkripsi('produk') ?>">Data Produk</a></li>  
                        </ul>
                    </li>
					<li class="sidebar-title">
                        MODEL MANUAL
                    </li>
					 <li><a href="?pg=<?= enkripsi('tran') ?>"><i class="material-icons-two-tone">shopping_cart</i>Manual Transaksi </a></li>
					<li class="sidebar-title">
                        MODEL MESIN KANTIN
                    </li>
					 <li><a href="?pg=pelanggan"><i class="material-icons-two-tone">school</i>Data Konsumen</a></li>
					 <li><a href="?pg=register"><i class="material-icons-two-tone">workspaces</i>Registrasi Kartu</a></li>
					 <li><a href="?pg=transaksi"><i class="material-icons-two-tone">shopping_cart</i>Transaksi</a></li>
					  <li><a href="?pg=topup"><i class="material-icons-two-tone">computer</i>Top Up Saldo</a></li>
                     
					<li>
                        <a href="#"><i class="material-icons-two-tone">print</i>Cetak Data<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						    <li><a href="?pg=trxtoko">Transaksi</a></li>
                           
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">storage</i>Database<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						
                            <li>
                                <a href="?pg=resetdata">Reset Database</a>
                            </li>
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
        Created by : Aji Bagaskoro, S.Pd, &copy;2025
    </div>
       </div>
	     
	