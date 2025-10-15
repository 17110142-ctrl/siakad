

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $setting['sekolah'] ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
    <link href="images/<?php echo $setting['logo'] ?>" rel="shortcut icon" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/all.min.css" rel="stylesheet" />
    <link href="assets/wow/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/front.min.css" rel="stylesheet" />
	<link href="assets/css/main.min.css" rel="stylesheet">
	<link rel='stylesheet' href='assets/izitoast/css/iziToast.min.css'>
  <link rel="icon" type="image/png" sizes="32x32" href="images/<?php echo $setting['logo'] ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="images/<?php echo $setting['logo'] ?>" />
	  <style type="text/css">
   .bold { font-weight: bold; }
   .pull-right{
    float: right;
    display: block;
	margin-top:-30px;
  }
</style> 	
</head>

<body>

<div class="container-fluid text-white" >
	  <div class="row">
		<div class="col pl-5 pt-3">
			<table>
				<tr>
					<td>
						<img style="margin:5px;height:80px" src="images/<?= $setting['logo'] ?>">
					</td>
					<td>
						<div><h2 style="color:black;"><?php echo $setting['aplikasi'] ?></h2></div>
						<div><small style="color:black;"><?php echo $setting['sekolah'] ?><small></div>
					</td>
				</tr>
			</table>
		</div>
	  </div>
</div>

<div class="wrapper fadeInDown"  style="margin-top:10px;">
  <div id="formContent"> 
    <div class="fadeIn text-left p-5">
	<div id='progressbox'></div>
      <div><h4>LOGIN COSTUMER</h4></div>
     
	  <form id="form-login" name="fmLogin" >
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
		 <button type="submit" class="btn btn-primary btn-round form-control" id="blockui-1" style="border-radius:20px" >LOGIN</button>
		</form> 
    </div>
	 
  </div> 
</div>

</div>

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
<script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/wow/js/wow.min.js"></script>
<script src="assets/js/front.min.js"></script>
<script src="assets/js/main.min.js"></script>
<script src='assets/izitoast/js/iziToast.min.js'></script>
	<script type="text/javascript">
        $(document).ready(function() {
			$('#form-login').submit(function(e) {
				var homeurl;
				homeurl = '<?php echo $homeurl; ?>';
				e.preventDefault();
				$.ajax({
					type: 'POST',
					url: 'ceklogin.php',
					data: $(this).serialize(),
					beforeSend: function() {
			$('#progressbox').html('<div><img src="images/animasi1.gif" ></div>');
			$('.progress-bar').animate({
			width: "30%"
			}, 500);
			},
					success: function(data) {
						console.log(data);
						if (data == "ok") {
							
							setTimeout(function() {
								location.href = '.';
							}, 2000);
						}
						if (data == "nopass") {
							
                        iziToast.info(
							{
							title: 'GAGAL!',
							message: 'Password Salah',
							titleColor: '#FFFF00',
							messageColor: '#fff',
							backgroundColor: 'rgba(0, 0, 0, 0.5)',
							progressBarColor: '#FFFF00',
							position: 'topRight'				  
								});
						setTimeout(function() {
						window.location.reload();
						}, 2000);
						}
						if (data == "td") {
							iziToast.info(
							{
							title: 'GAGAL!',
							message: 'Akun Tidak Terdaftar',
							titleColor: '#FFFF00',
							messageColor: '#fff',
							backgroundColor: 'rgba(0, 0, 0, 0.5)',
							progressBarColor: '#FFFF00',
							position: 'topRight'				  
								});
						setTimeout(function() {
						window.location.reload();
						}, 2000);
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
