 <?php 
$kls13 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas WHERE kurikulum='K-2013'"));
$kls23 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kelas WHERE kurikulum='K-Merdeka'"));
$jumsiswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa"));
$deskrip = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM deskrip"));
$jadwal = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM jadwal_rapor"));
$mapel = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mapel_rapor"));
$nilai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_rapor"));
$lingkup = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM lingkup"));
$tujuan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tujuan"));
$formatif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_formatif"));
$sumatif = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai_sumatif"));
$tujuan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tujuan"));
$eskul = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa_eskul"));
?>


<div id="wrapperdigital">
  <div id="clock">
            <div class="text"><b> DASHBOARD PEMBELAJARAN PROJEK </b></div>
      <div class="text"><i> "Lakukan Absensi RIFD Masuk & Pulang" </i></div>
    <div class="date"><i> Hari {{ date }} Semangat Bekerja </i></div>
    <div class="time"> {{ time }} </div>
    <br/>
    <div class="text"> <b><i>SMK BISA!   SMK HEBAT! <b><i></div>
  </div>
</div>

<style>
#clock {background:#fffdfc;height:100%;width:100%;color:#3c4043;text-align:center;margin:0;padding:10px;border-radius:22px;line-height:1.6;border:1px solid rgba(155,155,155,0.15)}
#clock .time {letter-spacing:0.05em;font-size:35px;padding:0px;background:rgba(255,255,255,.9);border-radius:10px;border:1px solid rgba(0,0,0,0.05);z-index:1;position:relative;}
#clock .date {letter-spacing:0.1em;font-size:25px;padding:5px;}
#clock .text {letter-spacing:0.1em;font-size:20px;padding:5px;z-index:1;position:relative;}
#wrapperdigital {position:relative;overflow:hidden}
#wrapperdigital:before {content:'';display:block;position:absolute;bottom:0;right:0;width:120px;height:120px;background-image:linear-gradient(230deg,#ff4169,#8b41f6);background-repeat:no-repeat;border-radius:120px 0 22px;transition:opacity .3s;opacity:1}
</style>

<!--[ Vue.Js Clock ]-->
<script src='https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.4/vue.min.js'></script>
<script type='text/javascript'>
var clock=new Vue({el:"#clock",data:{time:"",date:""}}),week=["Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu"],timerID=setInterval(updateTime,1e3);function updateTime(){var e=new Date;clock.time=zeroPadding(e.getHours(),2)+":"+zeroPadding(e.getMinutes(),2)+":"+zeroPadding(e.getSeconds(),2),clock.date=week[e.getDay()]+", "+zeroPadding(e.getDate(),2)+"-"+zeroPadding(e.getMonth()+1,2)+"-"+zeroPadding(e.getFullYear(),4)}function zeroPadding(e,t){for(var a="",d=0;d<t;d++)a+="0";return(a+e).slice(-t)}updateTime();
</script>

<br>

                       <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">RAPOR PROJEK</span>
                                                <h2 class="bold"><span class="badge badge-primary"><?= $kls23 ?></span> <small class="badge badge-secondary">KELAS</small></h2>
                                               
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            <div class="col-xl-4">
                                 <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                           <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">KURIKULUM MERDEKA</span>
                                                <h2 class="bold"><span class="badge badge-success"><?= $kls23 ?></span> <small class="badge badge-secondary">KELAS</small></h2>
                                               
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body edis">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">school</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">TOTAL PESERTA DIDIK</span>
                                                <h2 class="bold"><span class="badge badge-secondary"><?= $jumsiswa ?></span> <small class="badge badge-secondary">PD</small></h2>
                                               
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-xl-7">
                                <div class="card widget widget-payment-request">
                                    <div class="card-header">
                                        <h5 class="card-title">PROGRES INPUT NILAI</h5>
                                    </div>
                                    <div class="card-body edis2">
                                        <div id='logabsen' ></div> 
                                            
                                            
                                           
                                        </div>
                                    </div>
                                </div>
                          
                            <div class="col-xl-5">
                                <div class="card widget widget-list">
                                    <div class="card-header">
									
                                        <h5 class="card-title">PROGRES NILAI</h5>
										
                                    </div>
                                    <div class="card-body">
									  <div id='loglive' ></div> 
										
                                </div>
                               </div>
                             	<?php
							
							$jwb = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi"));
							
							?> 
													
                                <div class="card widget widget-list">
                                    <div class="card-header">
                                        <h5 class="card-title">HAPUS DATA TABEL RAPOR PROJEK</h5>
                                    </div>
                                    <div class="card-body">
                                    <div class="alert alert-custom" role="alert">
									<span>Jika Server Terasa Lamban Silahkan Hapus Data dibawah ini, Namun sebelumnya Anda <b>Telah mecetak Data Nilai Atau Backup Data</b></span>
								</div>
									<form id='formhapusdata' action='' method='post'>
                                        
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="m_proyek" name="data[]">
                                                     <span class="widget-list-item-description"> Master Proyek </span>
													</div><p>
                                              <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="proyek" name="data[]">
                                                     <span class="widget-list-item-description"> Proyek</span>
													</div><p>
                                             <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="nilai_proyek" name="data[]">
                                                     <span class="widget-list-item-description"> Nilai Proyek</span>
													</div><p>
													<div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" value="nilai_proses" name="data[]">
                                                     <span class="widget-list-item-description">Nilai Proses</span>
													</div>
													
                                            <div class="widget-payment-request-actions m-t-lg d-flex">
                                              
                                                <button type="submit" id="blockui-2" class="btn btn-danger flex-grow-1 m-l-xxs">Reset Rapor Proyek</button>
                                            </div>
                                        </ul>
										</form>
                                    </div>
                                </div>
                            </div>
						</div>
                       </div>
					  
	
	<script>						
$('#formhapusdata').submit(function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'asesmen/tsetting.php?pg=clear',
            data: $(this).serialize(),
            success: function(data) {
                console.log(data);
                if (data == "ok") {
                   setTimeout(function()
				{
				window.location.reload();
						}, 2000);
                } else {
                    iziToast.warning(
            {
                title: 'Gagal!',
                message: 'Belum ada yang dipilih',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: '#8b0000',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'
            });
                }

            }
        });
        return false;
    });
</script>
								<script>

							var autoRefresh = setInterval(
								function() {
									
									$('#logabsen').load('sandik_projek/loginput.php');
									$('#loglive').load('sandik_projek/logstatus.php');
									
								}, 1000
							);
						</script>
