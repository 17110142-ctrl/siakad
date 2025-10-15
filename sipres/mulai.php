<?php include"toplog.php"; ?>
       <div class="home-wrapper" id="home">
            <div class="home-header" style="background:#326698;background-size: contain;height:80px;background-image: url('../vendor/bg-top.png');background-repeat: no-repeat;">
                <div class="container p-0" >
                     <nav class="navbar navbar-expand-lg navbar-light" id="navbar-header" style="background:none;" >
                        <a class="navbar-brand" href="javascript:;" >
                            <img src="../images/<?= $setting['logo'] ?>" height="65" />
                            <div class="home-header-text d-none d-sm-block" >
                                <h5 style="color:#fff;">SISTEM APLIKASI PENDIDIK</h5>
                                <h6 style="color:#fff;"><?= $setting['sekolah'] ?></h6>
                                <h6 style="color:#fff;">TAHUN PELAJARAN <?= $setting['tp'] ?></h6>
                            </div>
                            <span class="logo-mini d-block d-md-none" style="color:#fff;">SANDIK</span>
                            <span class="logo-mini d-block d-md-none" style="color:#fff;">&nbsp;&nbsp;<?= $setting['tp'] ?></span>
                        </a>
                         <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation" >
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="menu" style="background:#326698;">
                           
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item active">
                                    <a class="nav-link" href="../" id="link-home" style="color:#fff;">Home</a>
                                </li>
                                
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
		
            <div class="home-banner">
                <div class="home-banner-bg home-banner-bg-color"></div>
                <div class="home-banner-bg home-banner-bg-img"></div>
                <div class="container mt-5">
                     <div class="row">
                         
						<div class="col-sm-7">
                           
                        </div>
                       <div class="col-sm-5">						
							<div class="card card-login">                             
                                   <div class="edis">
								<div class="fadeIn text-left p-5">
								  <div><b>E-PRESENSI <?= $setting['sekolah'] ?></b></div>
								  <div><small>Silakan login dengan menggunakan username dan password yang anda miliki</small></div>
								 <form id="formlogin" action="ceklogin.php" class="text-center">
									  <div class="input-group mt-4 mb-3">
										<div class="input-group-prepend">
										  <span class="input-group-text" style="border:0px;background:#fff"><i class="fa fa-user-circle"></i></span>
										</div>
										<input type="text" class="form-control" placeholder="Username" name="username" id="username" autocomplete="false" required="true" >
									  </div>
									  <div class="input-group mt-3 mb-3">
										<div class="input-group-prepend">
										  <span class="input-group-text" style="border:0px;background:#fff"><i class="fa fa-lock"></i></span>
										</div>
										<input type="password" class="form-control" placeholder="Password" name="password" id="password"  autocomplete="false" required="true" >
										<div class="input-group-prepend">
										  <span class="input-group-text" style="border:0px;background:#fff;padding-right:0px;padding-left:0px" onCLick="showPassword()" id="btn-eye"><i class="fa fa-eye"></i></span>
										</div>
									  </div>
									  <button type="submit" class="btn btn-primary btn-round form-control" id="submit" style="border-radius:20px" >Login</button>
									</form> 
									</div>
								 </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
          </div>
      </div>
                    
             
		<?php include"botlog.php"; ?>  
           
<script>
		$(document).ready(function() {
			$('#formlogin').submit(function(e) {
				var homeurl;
				homeurl = '<?php echo $homeurl; ?>/sipres/';
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(data) {

						if (data == "ok") {
							console.log('sukses');
							window.location = homeurl;
						}
						if (data == "nopass") {
							iziToast.info(
							{
							title: 'Gagal',
							message: 'Password Tidak Benar',
							titleColor: '#FFFF00',
							messageColor: '#fff',
							backgroundColor: 'rgba(0, 0, 0, 0.5)',
							progressBarColor: '#FFFF00',
							position: 'topRight'				  
							});
						}
						if (data == "td") {
							iziToast.info(
							{
							title: 'Gagal',
							message: 'Akun Tidak Terdaftar',
							titleColor: '#FFFF00',
							messageColor: '#fff',
							backgroundColor: 'rgba(0, 0, 0, 0.5)',
							progressBarColor: '#FFFF00',
							position: 'topRight'				  
							});
						}
						

					}
				})
				return false;
			});

		});
        
		
	</script>
<script>
	function showPassword() {
		var type = $('#password').attr('type');
		if (type ==='password') {
			$('#btn-eye').css('color','#00ff00');
			$('#password').attr('type','text');
		}
		else {
			$('#btn-eye').css('color','#636e72');
			$('#password').attr('type','password');
		}
	}
	
</script>