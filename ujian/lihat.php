<?php
                    $ac = $_GET['idu'];
                    $nilai = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' and id_ujian='$ac'"));
                  
                        $mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM banksoal WHERE id_bank='$nilai[id_bank]'"));
                    ?>
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class='box box-solid'>
                                    <div class='box-header with-border'>
                                        <h3 class='box-title'>Data Hasil Ujian</h3>
                                    </div>
                                    <div class='box-body'>
                                        <table  class='table table-bordered'>
                                            <tr>
                                                <th width='150'>No Induk</th>
                                                <td width='10'>:</td>
                                                <td><?= $siswa['nis'] ?></td>
                                                <td style="text-align:center; width:150">Nilai</td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td width='10'>:</td>
                                                <td><?= $siswa['nama'] ?></td>
                                                <td rowspan='4' style='font-size:30px; text-align:center; width:150'><?= $nilai['nilai'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Kelas</th>
                                                <td width='10'>:</td>
                                                <td><?= $siswa['kelas'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mata Pelajaran</th>
                                                <td width='10'>:</td>
                                                <td><?= $mapel['nama'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nama Ujian</th>
                                                <td width='10'>:</td>
                                                <td><?= $nilai['kode_ujian'] ?></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Detail Jawaban</a></li>
                                                
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_1">
												
                                                    <div class='table-responsive'>
                                                        <table  class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th width='5px'>#</th>
                                                                    <th>Soal PG</th>

                                                                    <th style='text-align:center'>Hasil</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $jawaban = unserialize($nilai['jawaban_pg']); ?>
                                                                <?php foreach ($jawaban as $key => $value) : ?>
                                                                    <?php
                                                                    $no++;
                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                                                    if ($value == $soal['jawaban']) :
                                                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                                                    else :
                                                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                                                    endif;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no ?></td>
                                                                        <td><?= substr($soal['soal'],0,20)."...." ?></td>

                                                                        <td style='text-align:center'><?= $status ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
												<div class='table-responsive'>
                                                        <table  class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th width='5px'>#</th>
                                                                    <th>Soal Isian Singkat</th>

                                                                    <th style='text-align:center'>Hasil</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $jawabe = unserialize($nilai['jawaban_esai']); ?>
                                                                <?php foreach ($jawabe as $key => $value) : ?>
                                                                    <?php
                                                                    $no++;
                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                                                    if ($value == $soal['jawaban']) :
                                                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                                                    else :
                                                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                                                    endif;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no ?></td>
                                                                        <td><?= substr($soal['soal'],0,20)."...." ?></td>

                                                                        <td style='text-align:center'><?= $status ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
													
                                               
												 <?php if($mapel['model']==1){ ?>
                                                    <div class='table-responsive'>
                                                        <table  class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th width='5px'>#</th>
                                                                    <th>Soal PG Kompleks</th>

                                                                    <th style='text-align:center'>Hasil</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $jawabm = unserialize($nilai['jawaban_multi']); ?>
                                                                <?php foreach ($jawabm as $key => $value) : ?>
                                                                    <?php
                                                                    $no++;
                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                                                    if ($value == $soal['jawaban']) :
                                                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                                                    else :
                                                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                                                    endif;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no ?></td>
                                                                        <td><?= substr($soal['soal'],0,20)."...." ?></td>


                                                                        <td style='text-align:center'><?= $status ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                
													
												 <div class='table-responsive'>
                                                        <table  class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th width='5px'>#</th>
                                                                    <th>Soal Benar Salah</th>

                                                                    <th style='text-align:center'>Hasil</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $jawabbs = unserialize($nilai['jawaban_bs']); ?>
                                                                <?php foreach ($jawabbs as $key => $value) : ?>
                                                                    <?php
                                                                    $no++;
                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                                                    if ($value == $soal['jawaban']) :
                                                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                                                    else :
                                                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                                                    endif;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no ?></td>
                                                                      <td><?= substr($soal['soal'],0,20)."...." ?></td>


                                                                        <td style='text-align:center'><?= $status ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                
												
												<div class='table-responsive'>
                                                        <table  class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th width='5px'>#</th>
                                                                    <th>Soal Menjodohkan</th>

                                                                    <th style='text-align:center'>Hasil</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $jawabu = unserialize($nilai['jawaban_urut']); ?>
                                                                <?php foreach ($jawabu as $key => $value) : ?>
                                                                    <?php
                                                                    $no++;
                                                                    $soal = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal='$key'"));
                                                                    if ($value == $soal['jawaban']) :
                                                                        $status = "<span class='text-green'><i class='fa fa-check'></i></span>";
                                                                    else :
                                                                        $status = "<span class='text-red'><i class='fa fa-times'></i></span>";
                                                                    endif;
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $no ?></td>
                                                                       <td><?= substr($soal['soal'],0,20)."...." ?></td>


                                                                        <td style='text-align:center'><?= $status ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
												 <?php } ?>
												
											 </div>
											</div>
										</div>
									</div>	
								 </div>	
							</div>		