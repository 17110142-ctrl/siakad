<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$mapel = $_POST['mapel'];
$kelas = $_POST['kelas'];
$smt = $_POST['semester'];
$pk = $_POST['pk'];
?>
<div class='row'>
        <div class='col-md-12'>
              <div class="card">
             <div class="card-header">	
                    <h5 class='card-title'><i class="fas fa-upload fa-fw"></i> <?= $mapel ?> - SEMESTER <?= $smt ?></h4>
                </div>
				
			 
                <div class='card-body'>
                <div class="table-responsive">
                    <table style="font-size: 12px" class="table table-bordered table-hover" id="datatable1">
                        <thead>
                            <tr>
                                <th class="text-center" width="8%">
                                    #
                                </th>
                              <th>KELAS</th>
                                <th>N I S</th>
								 <th>N I S N</th>
                                 <th>NAMA SISWA</th>
                              
							 <th width="10%">NILAI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from nilai_skl WHERE kelas='$kelas' AND mapel='$mapel' AND semester='$smt'");
                            $no = 0;
                            while ($s = mysqli_fetch_array($query)) {
								$siswa = fetch($koneksi,'siswa',['nis'=>$s['nis']]);
								$no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                   <td><?= $siswa['kelas'] ?></td>
								   <td><?= $siswa['nis'] ?></td>
								   <td><?= $siswa['nisn'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>             
                                     <td><?= $s['nilai'] ?></td>
                                  
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
