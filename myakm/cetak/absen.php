<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$jsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE no_peserta<>''"));
$jlaki = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='L' AND no_peserta<>''"));
$jper = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa WHERE jk='P' AND no_peserta<>''"));
?>
<div class="row"> 
        <div class="col-md-8">
            <div class="card">
              <div class="card-body">    				
                <form>
                    <div class="row mb-2">
                       <label  class="col-sm-3 control-label bold">Pilih Mapel</label>
                        <div class="col-sm-9">
						<select id='absenmapel' class='form-select' required='true' onchange="printabsen();" >
                            <?php $sql_mapel = mysqli_query($koneksi, "SELECT * FROM ujian"); ?>
                            <option value=''>Pilih Jadwal Ujian</option>
                            <?php while ($mapel = mysqli_fetch_array($sql_mapel)) : ?>
                                <option value="<?= $mapel['id_bank'] ?>">
								<?php echo "$mapel[nama] $mapel[level]";
                                   $dataArray = unserialize($mapel['kelas']);
                                    foreach ($dataArray as $key => $value) {
                                       echo " $value ";
                                       }
                                ?></option>
                            <?php endwhile ?>
                        </select>
                    </div>
                   </div>
				 
                  <div class="row mb-2">
                       <label  class="col-sm-3 control-label bold">Pilih Ruang</label>
						<div class="col-sm-9">
                        <select id='absenruang' class='form-select' onchange="printabsen();" >

                        </select>
                    </div>
					</div>
					 <div class="row mb-2">
                       <label  class="col-sm-3 control-label bold">Pilih Sesi</label>
						<div class="col-sm-9">
                        <select id='absensesi' class='form-select' onchange="printabsen();" >
                        </select>
                    </div>
					</div>
					 <div class="row mb-2">
                       <label  class="col-sm-3 control-label bold">Pilih Kelas</label>
						<div class="col-sm-9">
                        <select id='absenkelas' class='form-select' onchange="printabsen();" >
                        </select>
                    </div>
					</div>
					<div class="row mb-2">
                        <label  class="col-sm-2 control-label bold"></label>
				         <div class="col-md-10">	
					  <button id='btnabsen' class='btn btn-primary kanan' onclick="frames['frameresult'].print()"><i class='material-icons'>print</i>Print</button>
					 </div>
					</div>
					</form>
               </div>
			</div>
		</div>

	      
		 <div class="col-xl-4">
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">Peserta Ujian</h5>
                                    </div>
                                    <div class="card-body">
                                       
                                        <ul class="widget-list-content list-unstyled">
                                            <li class="widget-list-item widget-list-item-green">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">face</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Laki-laki</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= $jlaki ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="widget-list-item widget-list-item-blue">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">verified_user</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Perempuan</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= $jper ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                           
                                            <li class="widget-list-item widget-list-item-yellow">
                                                <span class="widget-list-item-icon"><i class="material-icons-outlined">extension</i></span>
                                                <span class="widget-list-item-description">
                                                    <a href="#" class="widget-list-item-description-title">
                                                        <b>Total Peserta</b>
                                                    </a>
                                                    <span class="widget-list-item-description-subtitle">
                                                        <?= number_format($jsis); ?> Siswa
                                                    </span>
                                                </span>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
			 </div>
	<iframe id='loadabsen' name='frameresult' src='cetak/print_absen.php' style='border:none;width:0px;height:0px;'></iframe>		 
<script>
    function printabsen() {
        var idsesi = $('#absensesi option:selected').val();
        var idmapel = $('#absenmapel option:selected').val();
        var idruang = $('#absenruang option:selected').val();
        var idkelas = $('#absenkelas option:selected').val();
        if (!idkelas) {
            idkelas = '';
        }
        if (!idsesi) {
            idsesi = '';
        }
        $('#loadabsen').attr('src', 'cetak/print_absen.php?id_sesi=' + idsesi + '&id_ruang=' + idruang + '&id_bank=' + idmapel + '&id_kelas=' + idkelas);
    }
    $("#absenmapel").change(function() {
        var mapel_id = $(this).val();
        console.log(mapel_id);
        $.ajax({
            type: "POST", 
            url: "cetak/ambildata.php?pg=ambil_ruang", 
            data: "mapel_id=" + mapel_id, 
            success: function(response) { 
                $("#absenruang").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    $("#absensesi").change(function() {
        var sesi = $(this).val();
        var mapel_id = $("#absenmapel").val();
        var ruang = $("#absenruang").val();
        console.log(sesi + ruang + mapel_id);
        $.ajax({
            type: "POST",
            url: "cetak/ambildata.php?pg=ambilkelas", 
            data: "mapel_id=" + mapel_id + '&sesi=' + sesi + '&ruang=' + ruang, 
            success: function(response) { 
                $("#absenkelas").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    $("#absenruang").change(function() {

        var ruang = $(this).val();
        console.log(ruang);
        $.ajax({
            type: "POST", 
            url: "cetak/ambildata.php?pg=ambil_sesi", 
            data: "ruang=" + ruang, 
            success: function(response) { 
                $("#absensesi").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });
</script>