<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $setting['sekolah'] ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
 <link href="images/<?php echo $setting['logo'] ?>" rel="shortcut icon" />
	<link rel="stylesheet" href="vendor/bootstrap-4/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/all.css">
	 <link rel='stylesheet' href='assets/css/sweetalert2.min.css'>
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap-4/js/popper.min.js"></script>
	<script src="vendor/bootstrap-4/js/bootstrap.min.js"></script>
  
</head>
<style>
.edis {
  background-size: 260px;
  background-image: url("images/tutwuri2.png");
  background-repeat: no-repeat;
  background-position: top right; 
  
}

    </style>

<body>

<div class="container-fluid text-white" style="background:#326698;background-size: contain;background-image: url('vendor/bg-top.png');background-repeat: no-repeat;background-position: left; height:150px;position:fixed;top:0px;left:0px;right:0px">
	  <div class="row">
		<div class="col pl-5 pt-1">
			<table>
				<tr>
					<td>
						<img style="margin:5px;height:70px" src="images/<?php echo $setting['logo'] ?>">
					</td>
					<td>
						<div><b><?= $setting['sekolah'] ?> </b></div>
						<div><small>SISTEM INFORMASI AKADEMIK (SIAKAD)<small></div>
					</td>
				</tr>
			</table>
		</div>
	  </div>
</div>

<div class="wrapper fadeInDown"  style="margin-top:90px;">
  <div id="formContent">
  <div class="edis">
    <div class="fadeIn text-left p-5">
      <div><b>Selamat Datang</b></div>
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
<script type="text/javascript" src="vendor/assets/js/jquery.min.js"></script>
<style>
	

	.wrapper {
	  display: flex;
	  align-items: center;
	  flex-direction: column; 
	  justify-content: center;
	  width: 100%;
	  min-height: 100%;
	  padding: 10px;
	  margin-top:-80px;
	}

	#formContent {
	  -webkit-border-radius: 10px 10px 10px 10px;
	  border-radius: 10px 10px 10px 10px;
	  background: #fff;
	  padding: 5px;
	  width: 100%;
	  max-width: 550px;
	  position: relative;
	  padding: 0px;
	  -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
	  box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
	  text-align: center;
	}

	


	/* TABS */

	h2.inactive {
	  color: #cccccc;
	}

	h2.active {
	  color: #0d0d0d;
	  border-bottom: 2px solid #5fbae9;
	}
	
	input[type=text] {
	  border: none;
	  color: #0d0d0d;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 16px;
	  border-radius:0px;
	  border-bottom:2px solid #eee;
	}
	input[type=password] {
	  border: none;
	  color: #0d0d0d;
	  text-decoration: none;
	  display: inline-block;
	  font-size: 16px;
	  border-radius:0px;
	  border-bottom:2px solid #eee;
	}

	/* ANIMATIONS */

	/* Simple CSS3 Fade-in-down Animation */
	.fadeInDown {
	  -webkit-animation-name: fadeInDown;
	  animation-name: fadeInDown;
	  -webkit-animation-duration: 1s;
	  animation-duration: 1s;
	  -webkit-animation-fill-mode: both;
	  animation-fill-mode: both;
	}

	@-webkit-keyframes fadeInDown {
	  0% {
		opacity: 0;
		-webkit-transform: translate3d(0, -100%, 0);
		transform: translate3d(0, -100%, 0);
	  }
	  100% {
		opacity: 1;
		-webkit-transform: none;
		transform: none;
	  }
	}

	@keyframes fadeInDown {
	  0% {
		opacity: 0;
		-webkit-transform: translate3d(0, -100%, 0);
		transform: translate3d(0, -100%, 0);
	  }
	  100% {
		opacity: 1;
		-webkit-transform: none;
		transform: none;
	  }
	}

	
</style>
<script src='assets/js/sweetalert2.min.js'></script>
<script src="dist/vendor/jquery/jquery-3.2.1.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#formlogin').submit(function(e) {
				var homeurl;
				homeurl = '<?php echo $homeurl; ?>';
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: $(this).attr('action'),
					data: $(this).serialize(),
					success: function(data) {

						if (data == "ok") {
							console.log('sukses');
							window.location = '.';
						}
						if (data == "nopass") {
							swal({
								position: 'top',
								type: 'warning',
								title: 'Password Salah',
								showConfirmButton: false,
								timer: 1500
							});
						}
						if (data == "td") {
							swal({
								position: 'center',
								type: 'warning',
								title: 'Siswa tidak terdaftar',
								showConfirmButton: false,
								timer: 1500
							});
						}
						if (data == "nologin") {
							swal({
								position: 'top',
								type: 'warning',
								title: 'Siswa sudah aktif',
								showConfirmButton: false,
								timer: 1500
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
  

</body>
</html>
