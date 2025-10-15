<?php
require("config/koneksi.php");
require("config/function.php");
require("config/crud.php");

session_start();
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';

if(!isset($_SESSION['id_siswa'])){
	if($pg == '' & $ac=='keranjang'){
	   header("location:?pg=login");
	}else if($pg == 'profil'){
		header("location:?pg=login");
	}
}else{
	$id_siswa = $_SESSION['id_siswa'];
	$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
}


$keranjang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM keranjang  WHERE idsiswa='$id_siswa'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sandik All in One">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="sandik">
   
    <title><?= $setting['aplikasi'] ?></title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
     <link href="font/material.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="assets/plugins/pace/pace.css" rel="stylesheet">
     <script src="assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <link href="assets/css/main.min.css" rel="stylesheet">
    <link href="assets/css/horizontal-menu/horizontal-menu.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
   <link rel='stylesheet' href='assets/izitoast/css/iziToast.min.css'>
  <link rel="icon" type="image/png" sizes="16x16" href="<?= $homeurl ?>/images/<?= $setting['logo'] ?>">
<style>
.responsive {
  width: auto;
  height: 100px;
}
.kanan{
    float: right;
    display: block;
	margin-top:5px;
	
  }
  .footer {
   position: fixed;
   
   left: 0;
   bottom: 0;
   height:50px;
   width: 100%;
   background-color: blue;
   color: white;
   text-align: center;
}
</style>
   
</head>
<body>
    <div class="app horizontal-menu align-content-stretch d-flex flex-wrap">
        <div class="app-container">
            <div class="search container">
                <form>
                    <input class="form-control" type="text" placeholder="Type here..." aria-label="Search">
                </form>
                <a href="#" class="toggle-search"><i class="material-icons">close</i></a>
            </div>
            <div class="app-header">
                <nav class="navbar navbar-light navbar-expand-lg container">
                    <div class="container-fluid">
                        <div class="navbar-nav" id="navbarNav">
                            <div class="logo">
                                <a href="."><?= $setting['aplikasi'] ?></a>
                            </div>
                            
                        </div>
						<div id='progressbox'></div> 
                        <div class="d-flex">
                            <ul class="navbar-nav">
                              
                                <li class="nav-item">
                                   <a class="nav-link" href="?pg=mykeranjang"><i class="material-icons">shopping_cart</i> <?= $keranjang ?></a>  
                                </li>
								
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

			
            <div class="app-content" style="margin-top:50px">
               
                       
						 <?php include"pages.php"; ?>
                           
                       
                    </div>
                </div>
				
            </div>
       
   <div class="footer">
   <table style="text-align:center; width:100%">
    <tr>
  <td style="text-align:center; vertical-align:center;"><a href="." class="nav-link" style="color:#FFF;"><i class="material-icons">home</i></a></td>
<td style="text-align:center; vertical-align:center;"><a href="?pg=history" class="nav-link" style="color:#FFF;"><i class="material-icons">history</i></a></td>
<td style="text-align:center; vertical-align:center;"><a href="?pg=saldo" class="nav-link" style="color:#FFF;"><i class="material-icons">payments</i></a></td>
<td style="text-align:center; vertical-align:center;"><a href="?pg=profil" class="nav-link" style="color:#FFF;"><i class="material-icons">manage_accounts</i></a></td>
<td style="text-align:center; vertical-align:center;"><a href="logout.php" class="nav-link" style="color:#FFF;"><i class="material-icons">logout</i></a></td>


</tr>
</table>
</div>
	<script src='assets/izitoast/js/iziToast.min.js'></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="assets/plugins/pace/pace.min.js"></script>
    <script src="assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="assets/js/main.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>
</body>
</html>