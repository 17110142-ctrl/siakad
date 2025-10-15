<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$mapel = $_POST['mapel'];
$kelas = $_POST['kelas'];
$smt = $_POST['jenis'];
?>
<div class='row'>
        <div class='col-md-12'>
            <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'>
                  <i class="fas fa-upload fa-fw"></i> <?= $mapel ?> - <?= $kelas ?> </h5>
                </div>
				
			 
                <div class='card-body'>
                <div class="table-responsive">
                    <table style="font-size: 12px" class="table table-bordered table-hover" id="datatable">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%" rowspan="2">
                                    #
                                </th>
                              <th width="10%" rowspan="2">Kelas</th>
                                <th rowspan="2">N I S</th>
								 <th rowspan="2">N I S N</th>
                                 <th rowspan="2">Nama Siswa</th>
                              <th style="text-align:center" colspan="2"><?= $mapel ?></th>
                             </tr>
							 <tr>
							 <th width="10%"> TEORI</th>
							 <th width="10%">PRAKTEK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from nilai_skl WHERE kelas='$kelas' AND mapel='$mapel' GROUP BY nis");
                            $no = 0;
                            while ($s = mysqli_fetch_array($query)) {
								$siswa = fetch($koneksi,'siswa',['nis'=>$s['nis']]);
								$ki3 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE kelas='$kelas' AND mapel='$mapel' AND jenis='TEORI' AND nis='$siswa[nis]'"));
                                $ki4 = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai_skl  WHERE kelas='$kelas' AND mapel='$mapel' AND jenis='PRAKTEK' AND nis='$siswa[nis]'"));
                                
								$no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                   <td><?= $siswa['id_kelas'] ?></td>
								   <td><?= $siswa['nis'] ?></td>
								   <td><?= $siswa['nisn'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>             
                                     <td><?= $ki3['nilai'] ?></td>
                                   <td><?= $ki4['nilai'] ?></td>
                                </tr>

                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
