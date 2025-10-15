<?php include"toplog.php"; ?>

       <div class="home-wrapper" id="home">
            <div class="home-header" style="background:#326698;background-size: contain;height:80px;background-image: url('vendor/bg-top.png');background-repeat: no-repeat;">
                <div class="container p-0" >
                     <nav class="navbar navbar-expand-lg navbar-light" id="navbar-header" style="background:none;" >
                        <a class="navbar-brand" href="javascript:;" >
                            <img src="images/<?= $setting['logo'] ?>" height="65" />
                            <div class="home-header-text d-none d-sm-block" >
                                <h5 style="color:#fff;">SISTEM INFORMASI AKADEMIK (SIAKAD)K</h5>
                                <h6 style="color:#fff;"><?= $setting['sekolah'] ?></h6>
                                <h6 style="color:#fff;">TAHUN PELAJARAN <?= $setting['tp'] ?></h6>
                            </div>
                            <span class="logo-mini d-block d-md-none" style="color:#fff;">SIAKAD</span>
                            <span class="logo-mini d-block d-md-none" style="color:#fff;">&nbsp;&nbsp;<?= $setting['tp'] ?></span>
                        </a>
                         <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation" >
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="menu" style="background:#326698;">
                            <ul class="navbar-nav ml-auto">
							<li class="nav-item active">
                                    <a class="nav-link" href="."  style="color:#fff;">Live Presensi</a>
                                </li>
                                <li class="nav-item ">
                                    <a class="nav-link" href="."  id="link-home" style="color:#fff;">Login</a>
                                </li>
                                
								
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
		 
            <div class="home-banner">
                <div class="home-banner-bg home-banner-bg-color"></div>
                <div class="home-banner-bg home-banner-bg-img"></div>
			
				 <div class="container mt-5" id="log"></div>
				
                <div class="container mt-5">
                     <div class="row">
						<div class="col-sm-8">
                            <div id='logabs' ></div> 
                            </div>
							
                        
                       <div class="col-sm-4">
							<div class="card" >
                              <div id='logabsen' ></div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
           
		<?php include"botlog.php"; ?>
		<script>

							var autoRefresh = setInterval(
								function() {
									$('#log').load('log.php');
									$('#logabs').load('logabsen.php');
									$('#logabsen').load('logsis.php');
									
								}, 1000
							);
						</script>