<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');

$kelas = $_POST['kelas'];

?>
<div class='row'>
        <div class='col-md-12'>
            <div class="card">
             <div class="card-header">
                  <h5 class='box-title'><i class="fas fa-upload fa-fw"></i> PKL - <?= $kelas ?></h5>
                </div>
			
                <div class='card-body'>
                <div class="table-responsive">
                    <table style="font-size: 12px" class="table table-hover" id="datatable1">
                        <thead>
                            <tr>
                                 <th class="text-center" width="3%" >
                                    #
                                </th>
                              <th width="10%" >Kelas</th>
                                <th >N I S</th>
								 <th >N I S N</th>
								 <th >Nama Siswa</th>
								<th >Mitra</th>
								<th >Lokasi</th>
								<th >Lama</th>
								<th >Keterangan</th>
							</tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "select * from pkl WHERE kelas='$kelas' ");
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
                                  <td><?= $s['mitra'] ?></td>
                                   <td><?= $s['lokasi'] ?></td>
								    <td><?= $s['lama'] ?></td>
									 <td><?= $s['ket'] ?></td>
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
