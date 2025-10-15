<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

$kelas = $_POST['kelas'];
$sku = $_POST['sku'];
?>
<div class='row'>
        <div class='col-md-12'>
           <div class="card">
             <div class="card-header">
                  <h5 class='box-title'><i class="fas fa-upload fa-fw"></i> S K U - <?= $sku ?></h5>
                </div>
			
                <div class='card-body'>
                <div class="table-responsive">
                    <table style="font-size: 12px" class="table table-hover" id="datatable">
                        <thead>
                            <tr>
                                 <th class="text-center" width="3%" rowspan="3">
                                    #
                                </th>
                              <th width="10%" rowspan="3">Kelas</th>
                                <th rowspan="3">N I S</th>
								 <th rowspan="3">N I S N</th>
								 <th rowspan="3">Nama Siswa</th>
								</tr><tr>
                                <th style="text-align:center"><?= $sku ?></th>
							
								</tr>
								<tr>
                              <th width="10%" style="text-align:center"><?= $kelas ?></th>
							</tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from nilai_sku WHERE kelas='$kelas'  AND kode='$sku' ");
                            $no = 0;
                            while ($s = mysqli_fetch_array($query)) {
								$siswa = fetch($koneksi,'siswa',['nis'=>$s['nis']]);
								
                                $no++;
                            ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                   <td><?= $siswa['id_kelas'] ?></td>
								   <td><?= $siswa['nis'] ?></td>
								   <td><?= $siswa['nisn'] ?></td>
                                    <td><?= $siswa['nama'] ?></td>
                                  <td style="text-align:center"><?= $s['nilai'] ?></td>
                                  
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
