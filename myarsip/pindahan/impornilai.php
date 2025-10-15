<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<div class='row'>
    <div class='col-md-12'>
        <div class='panel panel-default'>
               <div class="panel-heading" style="height:45px">
			   <div class='box-tools pull-right '>
                   
                </div>	
                  <h4 class='box-title'><i class="fas fa-users-cog"></i> Impor Nilai</h4></div>   
                
			  <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" >Import Data Nilai</a></li>
                       
                    </ul>
					
					<div class="tab-content">
                        <div class="tab-pane active" id="tab_1" >
						<br>
						<div class="panel panel-default">	
            <div class='box-body'>

                <div class='box box-solid'>
                    <div class='box-body'>
                        <div class='form-group-sm'>
                            <div class='row'>
                                <form id='formsiswa' enctype='multipart/form-data'>
                                    <div class='col-md-4'>
                                        <label>Pilih File</label>
                                        <input type='file' name='file' class='form-control' required='true' />
                                    </div>
                                    <div class='col-md-4'>
                                        <label>&nbsp;</label><br>
                                        <button type='submit' name='submit' class='btn-sm btn-success'><i class='fa fa-upload'></i> Import</button>
                                    </div>
                                </form>
                            </div>
                        </div>
						<br>
						<div class='table-responsive'>
                    <table style="font-size: 13px" class='table'>
                        
                        
                            <?php $mapelQ = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC"); ?>
                            <?php while ($mapel = mysqli_fetch_array($mapelQ)) : ?>
                                <?php $no++; ?>
                                <tr>
								<td width="5%"><?= $no ?></td>
                                    <td width="10%"><?= $mapel['kode_mapel'] ?></td>
                                     <td>
									 <a href="sandik_skl/eksporsiswa.php?kode=<?= $mapel['kode_mapel'] ?>&level=<?= $skl['tingkat'] ?>" ><i class='fa fa-download'></i> <b>Download Format</b></a>
									 </td>
									</tr>
									  <?php endwhile ?>
                     
                    </table>
						
					
                        <?= $info ?>
                        
                        <div id='progressbox'></div>
                        <div id='hasilimport'></div>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</div>
<script>
    $('#formsiswa').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'sandik_skl/crud_nilai.php',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $('#progressbox').html('<div><img src="<?= $homeurl ?>/dist/img/animasi1.gif" style="margin-left:100px"></div>');
                $('.progress-bar').animate({
                    width: "30%"
                }, 100);
            },
            success: function(response) {
                setTimeout(function() {
                    $('.progress-bar').css({
                        width: "100%"
                    });
                    setTimeout(function() {
                        $('#hasilimport').html(response);
						});
						setTimeout(function() {
                    window.location.replace('?pg=nilai');
                    }, 2000);
                }, 2000);
            }
        });
    });
</script>
