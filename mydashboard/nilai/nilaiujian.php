<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$bulan = date('m');
$bln = mysqli_fetch_array(mysqli_query($koneksi, "SELECT bln,ket FROM bulan WHERE bln='$bulan'"));
$hadir = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi where idsiswa='$id_siswa' and ket='H' and bulan='$bulan'"));
$sakit = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi where idsiswa='$id_siswa' and ket='S' and bulan='$bulan'"));
$izin = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi where idsiswa='$id_siswa' and ket='I' and bulan='$bulan'"));
$alpha = mysqli_num_rows(mysqli_query($koneksi, "SELECT idsiswa FROM absensi where idsiswa='$id_siswa' and ket='A' and bulan='$bulan'"));
?>

                       <div class="row">
                            <div class="col">
                                <div class="page">
                                    <h4>PRESENSI & KBM</h4>
                                   
                                </div>
                            </div>
                        </div>
	<div class="row">
		<div class="col-xl-8">
			<div class="card">
				<div class="card-header">
				 <div class="card-title"><h5>NILAI</h5></div>
				</div>
				<div class="card-body">
						<div class="card-box table-responsive">
                                        <table  class="table table-bordered table-hover" style="width:100%;font-size:12px">
                                        <thead>
                                         <tr>
                                          <th width="5%">NO</th>                                               
                                          <th>TANGGAL</th>
                                          <th>MAPEL</th>
										  <th width="5%">NILAI</th>
										  <th>KETERANGAN</th>
                                          </tr>
                                          </thead>
                                          <tbody>
											<?php
										$no = 0;

    // Ambil data dari n_harian
    $query = mysqli_query($koneksi, "SELECT * FROM nilai_harian WHERE idsiswa='$id_siswa' ORDER BY tanggal DESC");
    while ($data = mysqli_fetch_assoc($query)) :
        $mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_mapel FROM mata_pelajaran WHERE id='" . $data['mapel'] . "'"));
        $no++;
    ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= date('d-m-Y', strtotime($data['tanggal'])) ?></td>
            <td><?= $mapel['nama_mapel'] ?? 'Tidak Diketahui'; ?></td>
            <td><?= $data['nilai'] ?></td>
            <td>Nilai Harian</td>
        </tr>
    <?php endwhile; ?>

    <!-- Ambil data dari jawaban_tugas -->
    <?php
    $query1 = mysqli_query($koneksi, "SELECT * FROM jawaban_tugas WHERE id_siswa='$id_siswa' ORDER BY tgl_update DESC");
    while ($data1 = mysqli_fetch_assoc($query1)) :
        $mapel = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_mapel FROM mata_pelajaran WHERE nama_mapel='" . $data1['mapel'] . "'"));
        $no++;
    ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= $data1['tgl_update'] ?></td>
            <td><?= $data1['nama_mapel'] ?? 'Tidak Diketahui'; ?></td>
            <td><?= $data1['nilai'] ?></td>
            <td>Tugas Belajar</td>
        </tr>
    <?php endwhile; ?>
    
    <!-- Ambil data dari nilai -->
    <?php
    $query2 = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' ORDER BY id_bank DESC");
            while ($data2 = mysqli_fetch_assoc($query2)) :
  
        $no++;
    ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= $data2['ujian_selesai'] ?></td>
            <td><?= $data2['kode_ujian'] ?? 'Tidak Diketahui'; ?></td>
            <td><?= $data2['nilai'] ?></td>
            <td>E-Learning</td>
        </tr>
    <?php endwhile; ?>
</tbody>
                                            </table>
											</div>
									</div>
								</div>	
							</div>
							<div class="col-md-4">                                
                             						
                                <div class="card widget widget-list">
								<div class="card-header">
                                    
                                        <h5 class="card-title">BULAN <?= strtoupper($bln['ket']); ?> <?= date('Y'); ?></h5>
                                    </div>
                                    <div class="card-body" style="height:290px;">
									
									<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:blue;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    HADIR
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $hadir; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:grey;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    SAKIT
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $sakit; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:purple;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    IZIN
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $izin; ?></span></h5>
                                            </div>
                                        </div>
										<br>
										<div class="widget-connection-request-container d-flex">
                                            <div class="widget-connection-request-avatar">
                                                <div class="avatar avatar-md m-r-md">
												<?php if($siswa['foto']==''): ?>
                                                    <img src="../images/user.png" alt="">
												<?php else : ?>
												  <img src="../images/fotosiswa/<?= $siswa['foto'] ?>" alt="">
												 <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="widget-connection-request-info flex-grow-1" style="background-color:red;">
                                                <span class="widget-connection-request-info-name" style="color:#fff;margin-left:5px;">
                                                    ALPHA
                                                </span>
                                             <h5><span class="badge badge-success" style="color:#fff;margin-left:5px;"><?= $alpha; ?></span></h5>
                                            </div>
                                        </div>
										<br>
                                    </div>
                                </div>
							</div>
								  
                          </div>	
                            
							
							   