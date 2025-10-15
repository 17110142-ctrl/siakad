                   <div class='row'>
                        <div class='col-md-12'>
                            <div class='box box-solid'>
                                <div class='box-header with-border'>
                                    <h3 class='box-title'>HASIL ASESSMEN</h3>
                                </div>
                                <div class='box-body'>
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Hasil Asessmen</a></li>
                                            
									   </ul>
                                        <div class="tab-content">
                                            
                                                <table id='example1' class='table table-bordered'>
                                                    <thead>
                                                        <tr>
                                                            <th width='5px'>#</th>
                                                            <th>Kode Tes</th>
                                                            <th class='hidden-xs'>Ujian Selesai</th>
                                                            <th class='hidden-xs'>Status</th>
															<th>Jenis Soal</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $nilaix = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' AND ujian_selesai <>'' ORDER BY ujian_selesai ASC "); ?>
                                                        <?php while ($nilai = mysqli_fetch_array($nilaix)) : ?>
                                                            <?php
                                                            $no++;
                                                            $mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM banksoal WHERE id_bank='$nilai[id_bank]'"));
                                                            $namamapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE kode='$mapel[nama]'"));
                                                            ?>
                                                            <tr>
                                                                <td><?= $no ?></td>
                                                                <td><?= $mapel['nama'] . '-' . $namamapel['nama_mapel'] ?></td>
                                                                <td class='hidden-xs'><?= $nilai['ujian_selesai'] ?></td>
                                                                <td class='hidden-xs'><label class='label label-primary'>Selesai</label></td>
																<td><?= $mapel['groupsoal'] ?></td>
                                                                <td>
                                                                    <a href="?pg=lihathasil&idu=<?= $nilai['id_ujian'] ?>"><button class='btn btn-sm btn-success'><i class='fa fa-search'></i> Lihat Hasil</button></a>
																	 
																</td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>