<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD E-PRESENSI
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				   <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
					 <?php if($user['level']=='admin'): ?>
					<li>
                        <a href="#"><i class="material-icons-two-tone">settings</i>Pengaturan<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('mesin') ?>">Setting Mesin</a></li>
						<li><a href="?pg=<?= enkripsi('waktu') ?>">Setting Waktu</a></li>
                            <li><a href="?pg=<?= enkripsi('psis') ?>">Pesan Siswa</a></li>
							<li><a href="?pg=<?= enkripsi('ppeg') ?>">Pesan Pegawai</a></li>
							<li><a href="?pg=<?= enkripsi('esis') ?>">Pesan Eskul Siswa</a></li>
							<li><a href="?pg=<?= enkripsi('epeg') ?>">Pesan Eskul Pembina</a></li>
                        </ul>
                    </li>
					  <li>
                        <a href="#"><i class="material-icons-two-tone">workspaces</i>Registrasi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<?php if($setting['mesin']=='1'): ?>
						<li><a href="?pg=<?= enkripsi('rfid') ?>">Registrasi RFID</a></li>
						<?php endif; ?>
                       	<?php if($setting['mesin']=='2'): ?>
						<li><a href="?pg=barkode">Registrasi Barcode</a></li>
						<?php endif; ?>	
	                   <?php if($setting['mesin']=='3'): ?>
						<li><a href="?pg=finger">Registrasi Finger Print</a></li>
						<?php endif; ?>	
                      <?php if($setting['mesin']=='4'): ?>
						<li><a href="?pg=face">Registrasi Face Pegawai</a></li>
						<li><a href="?pg=faces">Registrasi Face Siswa</a></li>
						<?php endif; ?>								
                        </ul>
                    </li>
					<?php endif; ?>
					<li>
                        <a href="?pg=<?= enkripsi('status') ?>"><i class="material-icons-two-tone">sync</i>Data Absensi</a>
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">crisis_alert</i>Input Tidak Hadir<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('insis') ?>">Siswa</a></li>
						<?php if($user['level']=='admin'): ?>
					    <li><a href="?pg=<?= enkripsi('inpeg') ?>">Pegawai</a></li>	
						<?php endif; ?>
                        </ul>
                    </li>
										<li>
                    <a href="#"><i class="material-icons-two-tone">select_all</i>Cetak Kartu<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                      <ul class="sub-menu">					
					<li><a href="?pg=<?= enkripsi('cetak') ?>">Kartu Siswa</a></li>
                   	<li><a href="?pg=<?= enkripsi('cetakpegawai') ?>">Kartu Pegawai</a></li>										
                        </ul>
                    </li>
					<li>
                    <?php if($user['level']=='admin' || $user['level']=='staff'): ?>
                    <a href="#"><i class="material-icons-two-tone">print</i>Cetak Data<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<?php if($setting['mesin']=='BARKODE'): ?>
						<li><a href="?pg=cetak">Barkode Siswa</a></li>
                        <li><a href="?pg=cetakpeg">Barkode Pegawai</a></li>
						<?php endif; ?>	
					</li>
					<?php if($user['level']=='admin' || $user['level']=='staff'): ?>
					<li> 
						<li><a href="?pg=<?= enkripsi('abpeg') ?>">Rekapitulasi Pegawai</a></li>
                        <li><a href="?pg=<?= enkripsi('absis') ?>">Rekapitulasi Siswa</a></li>
						<li><a href="?pg=<?= enkripsi('abpeg2') ?>">Rekap Pembina Eskul</a></li>
                        <li><a href="?pg=<?= enkripsi('absis2') ?>">Rekap Eskul Siswa</a></li> 						
                        </ul>
                    </li>
					<?php endif; ?>
					 <li>
                        <a href="#"><i class="material-icons-two-tone">autorenew</i>Sinkronisasi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('setsinkron') ?>">Setting URL Sinkron</a></li>
						<li><a href="?pg=<?= enkripsi('sinmas') ?>">Sinkron Data Presensi</a></li>												
                        </ul>
                    </li>	
					 <li class="sidebar-title">
                        DAATABASE
                    </li>
					 <li>
					 <a href="#"><i class="material-icons-two-tone">storage</i>Database<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                         <li><a href="?pg=<?= enkripsi('resetpres') ?>">Reset Data Presensi</a></li>
						
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
        Created by : Aji Bagaskoro S.Pd &copy;2025
    </div>
       </div>
	     
	