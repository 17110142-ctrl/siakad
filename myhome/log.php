<?php 
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
$tanggal = date('Y-m-d');
$status = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM status")); 
if($status['mode']=='1' OR $status['mode']=='2'):
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal' and level ='pegawai'"));

$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi where tanggal='$tanggal'"));
elseif($status['mode']=='3' OR $status['mode']=='4'):
$jabsis = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les where tanggal='$tanggal' and level ='siswa'"));
$jabpeg = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les where tanggal='$tanggal' and level ='pegawai'"));

$jtot = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi_les where tanggal='$tanggal'"));
endif;
?>

                        <div class="row">
							  <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">SISWA</span>
                                                <span class="widget-stats-amount"><?= $jabsis; ?></span>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">face</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">
												<?php if($status['mode']=='1' OR $status['mode']=='2'): ?>
												PEGAWAI
												<?php elseif($status['mode']=='3' OR $status['mode']=='4'): ?>
												PEMBINA ESKUL
												<?php endif; ?>
												</span>
                                                <span class="widget-stats-amount"><?= $jabpeg; ?></span>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">people</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">TOTAL ABSENSI</span>
                                                <span class="widget-stats-amount"><?= $jtot ?> </span>
                                               
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
						