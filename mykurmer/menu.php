<?php
$hasKurikulumTask = strtolower(trim($user['tugas'] ?? '')) === 'kurikulum';
?>
<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD E-RAPOR K-MERDEKA
                    </li>
				 <li><a href="."><i class="material-icons-two-tone">home</i>Beranda</a></li>
				 <li><a href="../myhome"><i class="material-icons-two-tone">apps</i>Home</a></li>
				 <?php if($user['level']=='admin'): ?>
				  <li> <a href="?pg=<?= enkripsi('siswa') ?>"><i class="material-icons-two-tone">people</i>Update Data Siswa</a></li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">settings</i>Master E-Rapor<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
						<li><a href="?pg=<?= enkripsi('model') ?>">1. Model Rapor</a></li>
						<li><a href="?pg=<?= enkripsi('mapel') ?>">2. Mapel Rapor</a></li>
						  <li><a href="?pg=<?= enkripsi('setting') ?>">3. Tanggal Rapor</a></li> 
                        </ul>
                    </li>
					  <?php endif; ?>
				 <li>
                        <a href="#"><i class="material-icons-two-tone">select_all</i>Input Nilai<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
								
						<li><a href="?pg=<?= enkripsi('sumlm') ?>">Sumatif Akhir Materi</a></li>
						<li><a href="?pg=<?= enkripsi('sts') ?>">Sumatif Tengah Semester</a></li>
                        <li><a href="?pg=<?= enkripsi('sas') ?>">Sumatif Akhir Semester</a></li>
                       	<li><a href="?pg=<?= enkripsi('formatif') ?>">Nilai Formatif</a></li>			
                        </ul>
                    </li>
					<li><a href="?pg=<?= enkripsi('khs') ?>"><i class="material-icons-two-tone">article</i>KHS Siswa</a></li>

					<?php $jeskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM m_eskul where guru='$user[id_user]'")); ?>
					<?php if($jeskul !=0 OR $user['level']=='admin') : ?>
					<li><a href="?pg=<?= enkripsi('peskul') ?>"><i class="material-icons-two-tone">grade</i>Input Peserta Eskul</a></li>
					<li><a href="?pg=<?= enkripsi('nileskul') ?>"><i class="material-icons-two-tone">output</i>Input Nilai Eskul</a></li>
					<?php endif; ?>
					<?php if($user['walas'] !='' || $user['level']=='admin' || $hasKurikulumTask): ?>
					<li>
                        <a href="#"><i class="material-icons-two-tone">menu</i>Wali Kelas<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
						<li><a href="?pg=<?= enkripsi('absensi') ?>">Input Absensi</a></li>
						
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">print</i>Cetak Data<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
                            <li><a href="?pg=<?= enkripsi('cetak') ?>">Cetak Rapor</a></li>
                            <li><a href="?pg=<?= enkripsi('leger') ?>">Cetak Leger</a></li>
                            <li><a href="?pg=<?= enkripsi('cetak_sts') ?>">Cetak Nilai STS</a></li>                                   
                        </ul>
                    </li>
					
					<?php endif; ?>
					<?php if ($user['level']=='admin'): ?>
					 <li class="sidebar-title">
                      INTEGRASI
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">cloud_sync</i>Integrasi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">
                            <li><a href="?pg=<?= enkripsi('dapodik') ?>">Kirim Nilai ke Dapodik</a></li>
                        </ul>
                    </li>
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
	     
	
