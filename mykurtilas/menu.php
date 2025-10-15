<div class="app-menu">
                <ul class="accordion-menu">
				<li class="sidebar-title">
                        DASHBOARD E-RAPOR K-13
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
						<li><a href="?pg=<?= enkripsi('kkm') ?>">3. K K M</a></li>
						  <li><a href="?pg=<?= enkripsi('setting') ?>">4. Tanggal Rapor</a></li> 
                        </ul>
                    </li>
					  <?php endif; ?>
				 <li>
                        <a href="#"><i class="material-icons-two-tone">select_all</i>Input Nilai<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
						<li><a href="?pg=<?= enkripsi('nilai') ?>">Nilai KI-3 & KI-4</a></li>
						<?php $mdr = mysqli_num_rows(mysqli_query($koneksi, "SELECT level,kurikulum,model_rapor FROM kelas where kurikulum='1' and model_rapor='1'")); ?>
						<?php if($mdr !=0): ?>
						<li><a href="?pg=<?= enkripsi('nsikap') ?>">Nilai Sikap Spiritual</a></li>
                        <li><a href="?pg=<?= enkripsi('nsikap2') ?>">Nilai Sikap Sosial</a></li>
                        <?php endif; ?>	
                       <?php $mdr2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT level,kurikulum,model_rapor FROM kelas where kurikulum='1' and model_rapor='2'")); ?>
						<?php if($mdr2 !=0): ?>
						<li><a href="?pg=<?= enkripsi('nsikap3') ?>">Nilai Sikap Model 2023</a></li>      
                        <?php endif; ?>							
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">attach_email</i>Input Deskripsi<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
						<li><a href="?pg=<?= enkripsi('deskrip3') ?>">Deskripsi Pengetahuan</a></li>
						<li><a href="?pg=<?= enkripsi('deskrip4') ?>">Deskripsi Keterampilan</a></li>                      						
                        </ul>
                    </li>
					<?php $jeskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM m_eskul where guru='$user[id_user]'")); ?>
					<?php if($jeskul !=0 OR $user['level']=='admin') : ?>
					<li><a href="?pg=<?= enkripsi('peskul') ?>"><i class="material-icons-two-tone">grade</i>Input Peserta Eskul</a></li>
					<li><a href="?pg=<?= enkripsi('nileskul') ?>"><i class="material-icons-two-tone">output</i>Input Nilai Eskul</a></li>
					<?php endif; ?>
					<?php if($user['walas'] !='' OR $user['level']=='admin'): ?>
					<li>
                        <a href="#"><i class="material-icons-two-tone">menu</i>Wali Kelas<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
						<li><a href="?pg=<?= enkripsi('absensi') ?>">Input Absensi</a></li>
						<?php if($mdr !=0): ?>
						<li><a href="?pg=<?= enkripsi('prestasi') ?>">Input Prestasi</a></li>  
						<li><a href="?pg=<?= enkripsi('catatan') ?>">Input Catatan</a></li> 
						 <?php endif; ?>
                        </ul>
                    </li>
					<li>
                        <a href="#"><i class="material-icons-two-tone">print</i>Cetak Data<i class="material-icons has-sub-menu">keyboard_arrow_down</i></a>
                        <ul class="sub-menu">                      
						<li><a href="?pg=<?= enkripsi('cetak') ?>">Cetak Rapor</a></li>
						<li><a href="?pg=<?= enkripsi('leger') ?>">Cetak Leger</a></li>                      						
                        </ul>
                    </li>
					<?php endif; ?>
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
       </div>
	     
	