<?php
if (!isset($_GET['id']) && !isset($_GET['no']) && !isset($_GET['jenis'])) :
	die("Anda tidak dizinkan mengakses langsung script ini!");
endif;
$nomor = $_GET['no'];
$jenis = $_GET['jenis'];
$id_bank = $_GET['id'];

$nom = mysqli_fetch_array(mysqli_query($koneksi, "SELECT max(nomor) AS nomer FROM soal WHERE id_bank='$id_bank'"));
if($nom['nomer']==''){
$nomor = 1;
}else{
$nomor =$nom['nomer'] + 1;
}
$mapel = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM banksoal WHERE id_bank='$id_bank'"));
$idmap=$mapel['kode'];

$jumsoal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$id_bank' AND  nomor='$nomor' AND jenis='$jenis'"));
$soalQ = mysqli_query($koneksi, "SELECT * FROM soal WHERE id_bank='$id_bank' AND  nomor='$nomor' AND jenis='$jenis'");
$soal = mysqli_fetch_array($soalQ);
($soal['jawaban'] == 'A') ? $jwbA = 'checked' : $jwbA = '';
($soal['jawaban'] == 'B') ? $jwbB = 'checked' : $jwbB = '';
($soal['jawaban'] == 'C') ? $jwbC = 'checked' : $jwbC = '';
($soal['jawaban'] == 'D') ? $jwbD = 'checked' : $jwbD = '';
if ($soal['opsi'] == 5) {
	($soal['jawaban'] == 'E') ? $jwbE = 'checked' : $jwbE = '';
}
?>
<?php include"bank/radio.php"; ?>
 <div class="row"> 
				   <form id='formsoal' action='' method='post' enctype='multipart/form-data'>
                          <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                      <div class='btn-group' style='margin-top:-5px'>
						<label class='btn btn-sm btn-outline-primary'>Mapel </label>
						<label class='btn btn-sm btn-outline-primary'><?= $mapel['nama'] ?> </label>
						<label class='btn btn-sm btn-outline-danger'>No Soal :<b class="sandik"> <?= $nomor ?></b></label>					
					</div>			
                     <button type='submit' name='simpansoal' onclick="tinyMCE.triggerSave(true,true);" class='btn btn-sm btn-outline-primary kanan'> Simpan</button>
						<a href='?pg=<?= enkripsi('banksoal') ?>&ac=lihat&id=<?= $id_bank ?>' class='btn btn-sm btn-outline-danger kanan'> Kembali</a>
					
						</div>
							</div>
								</div>
									</div>										
 
					<input type='hidden' name='mapel' value='<?= $id_bank ?>'>
					<input type='hidden' name='jenis' value='<?= $jenis ?>'>
					<input type='hidden' name='nomor' value='<?= $nomor ?>'>
					
			<div class='row'>
			<div class="col-md-6">
		      <div class="card">
				<div class="card-header">
						  <h5 class="card-title">
                    SOAL MENJODOHKAN
					</h5>
                   
                  </div>
                    <div class="card-body">
					<textarea id='editor2' name='isi_soal' class='editor1' rows='10' cols='80' style='width:100%;' required><?= $soal['soal'] ?></textarea>
							
								<div class="row">
							<div class='col-md-6'>						
								<div class='form-group'>
									<?php
									if ($soal['file'] <> '') {
										$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
										$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
										$ext = explode(".", $soal['file']);
										$ext = end($ext);
										if (in_array($ext, $image)) {
											echo "
										<label>Gambar</label><br />
										<img src='$homeurl/files/$soal[file]' style='max-width:100px;' />
										";
										} elseif (in_array($ext, $audio)) {
											echo "
										<label>Audio</label><br />
										<audio controls>
											<source src='$homeurl/files/$soal[file]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
										";
										} else {
											echo "File tidak didukung!";
										}
										echo "<br /><a href='#'  data-id='$soal[id_soal]'  class='hapus  text-red'><i class='fa fa-times'></i> Hapus</a>";
									} else {
										echo "
										<label>Gambar / Audio</label>
										<input type='file' class='form-control' name='file' type='file'>
										";
									}
									?>
								</div>
							</div>
							<div class='col-md-6'>
								<div class='form-group'>
									<?php
									if ($soal['file1'] <> '') {
										$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
										$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
										$ext = explode(".", $soal['file1']);
										$ext = end($ext);
										if (in_array($ext, $image)) {
											echo "
										<label>Gambar</label><br />
										<img src='$homeurl/files/$soal[file1]' style='max-width:100px;' />
										";
										} elseif (in_array($ext, $audio)) {
											echo "
										<label>Audio</label><br />
										<audio controls>
											<source src='$homeurl/files/$soal[file1]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
										";
										} else {
											echo "File tidak didukung!";
										}
											echo "<br /><a href='#'  data-id='$soal[id_soal]'  class='hapusQ  text-red'><i class='fa fa-times'></i> Hapus</a>";
									} else {
										echo "
										<label>Gambar / Audio</label>
										<input type='file' class='form-control' name='file1' type='file'>
										";
									}
									?>
								</div>
							 </div>
							</div>						
						 </div>	
					</div>	
				</div>	
					 <div class="col-md-6">
						 <div class="card">
						 <div class="card-header">
						  <h5 class="card-title">OPSI DAN KUNCI JAWABAN </h5>
						  </div>
                   <div class="card-body">
				   <div class="row mb-1">
					<label  class="col-md-4 col-form-label bold">Skor Tiap Jawaban Benar</label>
						<div class="col-md-4">
							<input type='number' name='skor' value="1" class='form-control' required="true" />
						</div>
					</div>
						 <div class="example-content bg-white">
                           <p>
                       <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_1" aria-expanded="false" >PERNYATAAN 1</button>
                      <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_11" aria-expanded="false" >A. OPSI PILIHAN</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_12" aria-expanded="false" >KUNCI JAWABAN</button>
                         </p>
                      <div class="row">
						<div class="collapse multi-collapse" id="multi_1">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">PERNYATAAN 1</a> 
						<div class='form-group' >
						<textarea name='perA' class='editor1 pilihan form-control'><?= $soal['perA'] ?></textarea>                                   
									</div>
                                 </div>
                            </div>
							
                          <div class="collapse multi-collapse" id="multi_11">
                              <div class="card card-body">
                               <a class="btn btn-sm btn-secondary">A. OPSI PILIHAN</a> 
						
						              <div class='form-group' >
													<textarea name='pilA' class='editor1 pilihan form-control'><?= $soal['pilA'] ?></textarea>
												</div>
												<?php
												if ($soal['fileA'] <> '') {
													$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
													$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
													$ext = explode(".", $soal['fileA']);
													$ext = end($ext);
													if (in_array($ext, $image)) {
														echo "
														<label>Gambar A</label><br />
														<img src='$homeurl/files/$soal[fileA]' style='max-width:80px;' />
														";
													} elseif (in_array($ext, $audio)) {
														echo "
														<label>Audio</label><br />
														<audio controls>
															<source src='$homeurl/files/$soal[fileA]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
														";
													} else {
														echo "File tidak didukung!";
													}
													echo "<br /><a href='?pg=$pg&ac=hapusfile&id=$soal[id_soal]&file=fileA&jenis=$jenis' class='text-red'><i class='fa fa-times'></i> Hapus</a>";
												} else {
													echo "
														<label>Gambar / Audio Pil A</label>
														<input type='file' name='fileA' class='form-control' />
														";
												}
												?>
												
										</div>                           
									</div>
                                </div>						
						  
						<div class="collapse multi-collapse" id="multi_12">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">KUNCI JAWABAN PERNYATAAN 1</a>
                         <p>							 
						<div class='form-group' >						
					  <input type="button" class="btn btn-danger kanan" onclick="A16()" value="X" data-bs-toggle="tooltip" data-bs-placement="top" title="Batal">								
						<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="A11()" id="A1" name='jawaban[]' value='A'  /> A
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="A12()" id="A2" name='jawaban[]' value='B'  /> B
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="A13()" id="A3" name='jawaban[]' value='C'  /> C
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="A14()" id="A4" name='jawaban[]' value='D'  /> D
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="A15()" id="A5" name='jawaban[]' value='E'  /> E
											<span class="check"></span></label>
                      			                    
									</div>
                                 </div>
                            </div>
						 
						 <div class="example-content bg-white">
                           <p>
                       <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_2" aria-expanded="false">PERNYATAAN 2</button>
                      <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_21" aria-expanded="false">B. OPSI PILIHAN</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_22" aria-expanded="false">KUNCI JAWABAN</button>
                         </p>
						
						<div class="collapse multi-collapse" id="multi_2">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">PERNYATAAN 2</a> 
						<div class='form-group' >
						<textarea name='perB' class='editor1 pilihan form-control'><?= $soal['perB'] ?></textarea>                                   
									</div>
                                 </div>
                            </div>
							
                          <div class="collapse multi-collapse" id="multi_21">
                              <div class="card card-body">
                               <a class="btn btn-sm btn-secondary">B. OPSI PILIHAN</a> 
						
						<div class='form-group' >
													<textarea name='pilB' class='editor1 pilihan form-control'><?= $soal['pilB'] ?></textarea>
												</div>
												<?php
												if ($soal['fileB'] <> '') {
													$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
													$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
													$ext = explode(".", $soal['fileB']);
													$ext = end($ext);
													if (in_array($ext, $image)) {
														echo "
														<label>Gambar B</label><br />
														<img src='$homeurl/files/$soal[fileB]' style='max-width:80px;' />
														";
													} elseif (in_array($ext, $audio)) {
														echo "
														<label>Audio</label><br />
														<audio controls>
															<source src='$homeurl/files/$soal[fileB]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
														";
													} else {
														echo "File tidak didukung!";
													}
													echo "<br /><a href='?pg=$pg&ac=hapusfile&id=$soal[id_soal]&file=fileB&jenis=$jenis' class='text-red'><i class='fa fa-times'></i> Hapus</a>";
												} else {
													echo "
														<label>Gambar / Audio Pil B</label>
														<input type='file' name='fileB' class='form-control' />
														";
												}
												?>
											</div>	            
										</div>
                                     </div>
								
						<div class="collapse multi-collapse" id="multi_22">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">KUNCI JAWABAN PERNYATAAN 2</a>
								<p>
						<div class='form-group' >
					  <input type="button" class="btn  btn-danger kanan" onclick="B16()" value="X" data-bs-toggle="tooltip" data-bs-placement="top" title="Batal">			
						<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="B11()" id="B1" name='jawaban[]' value='A'  /> A
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="B12()" id="B2" name='jawaban[]' value='B'  /> B
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="B13()" id="B3" name='jawaban[]' value='C'  /> C
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="B14()" id="B4" name='jawaban[]' value='D'  /> D
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="B15()" id="B5" name='jawaban[]' value='E'  /> E
											<span class="check"></span></label>	                         
									</div>
                                 </div>
                            </div>
						
						
						
						
						 <div class="example-content bg-white">
                           <p>
                       <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_3" aria-expanded="false" >PERNYATAAN 3</button>
                      <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_31" aria-expanded="false">C. OPSI PILIHAN</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_32" aria-expanded="false">KUNCI JAWABAN</button>
                         </p>
						
						<div class="collapse multi-collapse" id="multi_3">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">PERNYATAAN 3</a> 
						<div class='form-group' >
						<textarea name='perC' class='editor1 pilihan form-control'><?= $soal['perC'] ?></textarea>                                   
									</div>
                                 </div>
                            </div>
							
                          <div class="collapse multi-collapse" id="multi_31">
                              <div class="card card-body">
                               <a class="btn btn-sm btn-secondary">C. OPSI PILIHAN</a> 
						
						<div class='form-group' >
											<textarea name='pilC' class='editor1 pilihan form-control'><?= $soal['pilC'] ?></textarea>
												</div>
												<?php
												if ($soal['fileC'] <> '') {
													$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
													$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
													$ext = explode(".", $soal['fileC']);
													$ext = end($ext);
													if (in_array($ext, $image)) {
														echo "
														<label>Gambar C</label><br />
														<img src='$homeurl/files/$soal[fileC]' style='max-width:80px;' />
														";
													} elseif (in_array($ext, $audio)) {
														echo "
														<label>Audio</label><br />
														<audio controls>
															<source src='$homeurl/files/$soal[fileC]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
														";
													} else {
														echo "File tidak didukung!";
													}
													echo "<br /><a href='?pg=$pg&ac=hapusfile&id=$soal[id_soal]&file=fileC&jenis=$jenis' class='text-red'><i class='fa fa-times'></i> Hapus</a>";
												} else {
													echo "
														<label>Gambar / Audio Pil C</label>
														<input type='file' name='fileC' class='form-control' />
														";
												}
												?>
											</div>	            
										</div>
                                     </div>
								
						<div class="collapse multi-collapse" id="multi_32">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">KUNCI JAWABAN PERNYATAAN 3</a> 
							 <p>
						<div class='form-group' >
							 <input type="button" class="btn btn-danger kanan" onclick="C16()" value="X" data-bs-toggle="tooltip" data-bs-placement="top" title="Batal">			
						<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="C11()" id="C1" name='jawaban[]' value='A'  /> A
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="C12()" id="C2" name='jawaban[]' value='B'  /> B
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="C13()" id="C3" name='jawaban[]' value='C'  /> C
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="C14()" id="C4" name='jawaban[]' value='D'  /> D
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="C15()" id="C5" name='jawaban[]' value='E'  /> E
											<span class="check"></span></label>                       
									</div>
                                 </div>
                            </div>
						
					
                     <div class="example-content bg-white">
                           <p>
                       <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_4" aria-expanded="false" >PERNYATAAN 4</button>
                      <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_41" aria-expanded="false">D. OPSI PILIHAN</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_42" aria-expanded="false">KUNCI JAWABAN</button>
                         </p>
						
						<div class="collapse multi-collapse" id="multi_4">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">PERNYATAAN 4</a> 
						<div class='form-group' >
						<textarea name='perD' class='editor1 pilihan form-control'><?= $soal['perD'] ?></textarea>                                   
									</div>
                                 </div>
                            </div>
							
                          <div class="collapse multi-collapse" id="multi_41">
                              <div class="card card-body">
                               <a class="btn btn-sm btn-secondary">D. OPSI PILIHAN</a> 
						
						       <div class='form-group' >
									<textarea name='pilD' class='editor1 pilihan form-control'><?= $soal['pilD'] ?></textarea>
												</div>
												<?php
												if ($soal['fileD'] <> '') {
													$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
													$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
													$ext = explode(".", $soal['fileD']);
													$ext = end($ext);
													if (in_array($ext, $image)) {
														echo "
														<label>Gambar D</label><br />
														<img src='$homeurl/files/$soal[fileD]' style='max-width:80px;' />
														";
													} elseif (in_array($ext, $audio)) {
														echo "
														<label>Audio</label><br />
														<audio controls>
															<source src='$homeurl/files/$soal[fileD]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
														";
													} else {
														echo "File tidak didukung!";
													}
													echo "<br /><a href='?pg=$pg&ac=hapusfile&id=$soal[id_soal]&file=fileD&jenis=$jenis' class='text-red'><i class='fa fa-times'></i> Hapus</a>";
												} else {
													echo "
														<label>Gambar / Audio Pil D</label>
														<input type='file' name='fileD' class='form-control' />
														";
												}
												?>	
											</div>	            
										</div>
                                     </div>
								
						<div class="collapse multi-collapse" id="multi_42">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">KUNCI JAWABAN PERNYATAAN 4</a> 
							 <p>
						<div class='form-group' >
						          
							 <input type="button" class="btn btn-danger kanan" onclick="D16()" value="X" data-toggle="tooltip" data-placement="top" title="Batal">			
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="D11()" id="D1" name='jawaban[]' value='A'  /> A
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="D12()" id="D2" name='jawaban[]' value='B'  /> B
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="D13()" id="D3" name='jawaban[]' value='C'  /> C
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="D14()" id="D4" name='jawaban[]' value='D'  /> D
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="D15()" id="D5" name='jawaban[]' value='E'  /> E
											<span class="check"></span></label>                   
									</div>
                                 </div>
                            </div>
						

						<div class="example-content bg-white">
                           <p>
                       <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_5" aria-expanded="false" >PERNYATAAN 5</button>
                      <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_51" aria-expanded="false">E. OPSI PILIHAN</button>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multi_52" aria-expanded="false">KUNCI JAWABAN</button>
                         </p>
						
						<div class="collapse multi-collapse" id="multi_5">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">PERNYATAAN 5</a> 
						<div class='form-group' >
						<textarea name='perE' class='editor1 pilihan form-control'><?= $soal['perE'] ?></textarea>                                   
									</div>
                                 </div>
                            </div>
							
                          <div class="collapse multi-collapse" id="multi_51">
                              <div class="card card-body">
                               <a class="btn btn-sm btn-secondary">E. OPSI PILIHAN</a> 
						
						       <div class='form-group' >
									<textarea name='pilE' class='editor1 pilihan form-control'><?= $soal['pilE'] ?></textarea>
												</div>
													<textarea name='pilE' class='editor1 pilihan form-control'><?= $soal['pilE'] ?></textarea>
												</div>
												<?php
												if ($soal['fileE'] <> '') {
													$audio = array('mp3', 'wav', 'ogg', 'MP3', 'WAV', 'OGG');
													$image = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP');
													$ext = explode(".", $soal['fileE']);
													$ext = end($ext);
													if (in_array($ext, $image)) {
														echo "
														<label>Gambar D</label><br />
														<img src='$homeurl/files/$soal[fileE]' style='max-width:80px;' />
														";
													} elseif (in_array($ext, $audio)) {
														echo "
														<label>Audio</label><br />
														<audio controls>
															<source src='$homeurl/files/$soal[fileE]' type='audio/$ext'>Your browser does not support the audio tag.</audio>
														";
													} else {
														echo "File tidak didukung!";
													}
													echo "<br /><a href='?pg=$pg&ac=hapusfile&id=$soal[id_soal]&file=fileE&jenis=$jenis' class='text-red'><i class='fa fa-times'></i> Hapus</a>";
												} else {
													echo "
														<label>Gambar / Audio Pil E</label>
														<input type='file' name='fileE' class='form-control' />
														";
												}
												?>
											</div>	            
										</div>
                                     </div>
								
						<div class="collapse multi-collapse" id="multi_52">
                           <div class="card card-body">
                             <a class="btn btn-sm btn-secondary">KUNCI JAWABAN PERNYATAAN 5</a> 
							 <p>
						<div class='form-group' >
						   <input type="button" class="btn btn-danger kanan" onclick="E16()" value="X" data-toggle="tooltip" data-placement="top" title="Batal">			
						<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="E11()" id="E1" name='jawaban[]' value='A'  /> A
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="E12()" id="E2" name='jawaban[]' value='B'  /> B
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="E13()" id="E3" name='jawaban[]' value='C'  /> C
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="E14()" id="E4" name='jawaban[]' value='D'  /> D
											<span class="check"></span></label>
					<label class="checkbox"><input class='hidden radio-label' type='checkbox' onclick="E15()" id="E5" name='jawaban[]' value='E'  /> E
											<span class="check"></span></label>                    
									</div>
                                 </div>
                            </div>
						 </div>
						
						
					
						</form>
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	tinymce.init({
		selector: '.editor1',
		
		plugins: [
			'advlist autolink lists link image charmap print preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'emoticons template paste textcolor colorpicker textpattern imagetools uploadimage paste formula'
		],

		toolbar: 'bold italic fontselect fontsizeselect | alignleft aligncenter alignright bullist numlist  backcolor forecolor | formula code | imagetools link image paste ',
		fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
		paste_data_images: true,

		images_upload_handler: function(blobInfo, success, failure) {
			success('data:' + blobInfo.blob().type + ';base64,' + blobInfo.base64());
		},
		image_class_list: [{
			title: 'Responsive',
			value: 'img-responsive'
		}],
	});
</script>

<script>
function A11() {
  document.getElementById("A2").disabled = true;
   document.getElementById("A3").disabled = true;
 document.getElementById("A4").disabled = true;  
 document.getElementById("A5").disabled = true;  
}
function A12() {
  document.getElementById("A1").disabled = true;
  document.getElementById("A3").disabled = true;
 document.getElementById("A4").disabled = true;  
 document.getElementById("A5").disabled = true; 
}
function A13() {
  document.getElementById("A1").disabled = true;
  document.getElementById("A2").disabled = true;
 document.getElementById("A4").disabled = true;  
 document.getElementById("A5").disabled = true; 
}
function A14() {
  document.getElementById("A1").disabled = true;
  document.getElementById("A2").disabled = true;
 document.getElementById("A3").disabled = true;  
 document.getElementById("A5").disabled = true; 
}
function A15() {
  document.getElementById("A1").disabled = true;
  document.getElementById("A3").disabled = true;
 document.getElementById("A4").disabled = true;  
 document.getElementById("A4").disabled = true; 
}
function A16() {
  document.getElementById("A1").disabled = false;
   document.getElementById("A2").disabled = false;
    document.getElementById("A3").disabled = false;
	 document.getElementById("A4").disabled = false;
	  document.getElementById("A5").disabled = false;
   document.getElementById("A1").checked = false;
   document.getElementById("A2").checked = false;
    document.getElementById("A3").checked = false;
   document.getElementById("A4").checked = false;
   document.getElementById("A5").checked = false;
}
</script>
<script>
function B11() {
  document.getElementById("B2").disabled = true;
   document.getElementById("B3").disabled = true;
 document.getElementById("B4").disabled = true;  
 document.getElementById("B5").disabled = true;  
}
function B12() {
  document.getElementById("B1").disabled = true;
  document.getElementById("B3").disabled = true;
 document.getElementById("B4").disabled = true;  
 document.getElementById("B5").disabled = true; 
}
function B13() {
  document.getElementById("B1").disabled = true;
  document.getElementById("B2").disabled = true;
 document.getElementById("B4").disabled = true;  
 document.getElementById("B5").disabled = true; 
}
function B14() {
  document.getElementById("B1").disabled = true;
  document.getElementById("B2").disabled = true;
 document.getElementById("B3").disabled = true;  
 document.getElementById("B5").disabled = true; 
}
function B15() {
  document.getElementById("B1").disabled = true;
  document.getElementById("B3").disabled = true;
 document.getElementById("B4").disabled = true;  
 document.getElementById("B4").disabled = true; 
}
function B16() {
  document.getElementById("B1").disabled = false;
   document.getElementById("B2").disabled = false;
    document.getElementById("B3").disabled = false;
	 document.getElementById("B4").disabled = false;
	  document.getElementById("B5").disabled = false;
   document.getElementById("B1").checked = false;
   document.getElementById("B2").checked = false;
    document.getElementById("B3").checked = false;
   document.getElementById("B4").checked = false;
   document.getElementById("B5").checked = false;
}
</script>
<script>
function C11() {
  document.getElementById("C2").disabled = true;
   document.getElementById("C3").disabled = true;
 document.getElementById("C4").disabled = true;  
 document.getElementById("C5").disabled = true;  
}
function C12() {
  document.getElementById("C1").disabled = true;
  document.getElementById("C3").disabled = true;
 document.getElementById("C4").disabled = true;  
 document.getElementById("C5").disabled = true; 
}
function C13() {
  document.getElementById("C1").disabled = true;
  document.getElementById("C2").disabled = true;
 document.getElementById("C4").disabled = true;  
 document.getElementById("C5").disabled = true; 
}
function C14() {
  document.getElementById("C1").disabled = true;
  document.getElementById("C2").disabled = true;
 document.getElementById("C3").disabled = true;  
 document.getElementById("C5").disabled = true; 
}
function C15() {
  document.getElementById("C1").disabled = true;
  document.getElementById("C3").disabled = true;
 document.getElementById("C4").disabled = true;  
 document.getElementById("C4").disabled = true; 
}
function C16() {
  document.getElementById("C1").disabled = false;
   document.getElementById("C2").disabled = false;
    document.getElementById("C3").disabled = false;
	 document.getElementById("C4").disabled = false;
	  document.getElementById("C5").disabled = false;
   document.getElementById("C1").checked = false;
   document.getElementById("C2").checked = false;
    document.getElementById("C3").checked = false;
   document.getElementById("C4").checked = false;
   document.getElementById("C5").checked = false;
}
</script>
<script>
function D11() {
  document.getElementById("D2").disabled = true;
   document.getElementById("D3").disabled = true;
 document.getElementById("D4").disabled = true;  
 document.getElementById("D5").disabled = true;  
}
function D12() {
  document.getElementById("D1").disabled = true;
  document.getElementById("D3").disabled = true;
 document.getElementById("D4").disabled = true;  
 document.getElementById("D5").disabled = true; 
}
function D13() {
  document.getElementById("D1").disabled = true;
  document.getElementById("D2").disabled = true;
 document.getElementById("D4").disabled = true;  
 document.getElementById("D5").disabled = true; 
}
function D14() {
  document.getElementById("D1").disabled = true;
  document.getElementById("D2").disabled = true;
 document.getElementById("D3").disabled = true;  
 document.getElementById("D5").disabled = true; 
}
function D15() {
  document.getElementById("D1").disabled = true;
  document.getElementById("D3").disabled = true;
 document.getElementById("D4").disabled = true;  
 document.getElementById("D4").disabled = true; 
}
function D16() {
  document.getElementById("D1").disabled = false;
   document.getElementById("D2").disabled = false;
    document.getElementById("D3").disabled = false;
	 document.getElementById("D4").disabled = false;
	  document.getElementById("D5").disabled = false;
   document.getElementById("D1").checked = false;
   document.getElementById("D2").checked = false;
    document.getElementById("D3").checked = false;
   document.getElementById("D4").checked = false;
   document.getElementById("D5").checked = false;
}
</script>
<script>
function E11() {
  document.getElementById("E2").disabled = true;
   document.getElementById("E3").disabled = true;
 document.getElementById("E4").disabled = true;  
 document.getElementById("E5").disabled = true;  
}
function E12() {
  document.getElementById("E1").disabled = true;
  document.getElementById("E3").disabled = true;
 document.getElementById("E4").disabled = true;  
 document.getElementById("E5").disabled = true; 
}
function E13() {
  document.getElementById("E1").disabled = true;
  document.getElementById("E2").disabled = true;
 document.getElementById("E4").disabled = true;  
 document.getElementById("E5").disabled = true; 
}
function E14() {
  document.getElementById("E1").disabled = true;
  document.getElementById("E2").disabled = true;
 document.getElementById("E3").disabled = true;  
 document.getElementById("E5").disabled = true; 
}
function E15() {
  document.getElementById("E1").disabled = true;
  document.getElementById("E3").disabled = true;
 document.getElementById("E4").disabled = true;  
 document.getElementById("E4").disabled = true; 
}
function E16() {
  document.getElementById("E1").disabled = false;
   document.getElementById("E2").disabled = false;
    document.getElementById("E3").disabled = false;
	 document.getElementById("E4").disabled = false;
	  document.getElementById("E5").disabled = false;
   document.getElementById("E1").checked = false;
   document.getElementById("E2").checked = false;
    document.getElementById("E3").checked = false;
   document.getElementById("E4").checked = false;
   document.getElementById("E5").checked = false;
}
</script>