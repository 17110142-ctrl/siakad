<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>SIK ONLINE &copy SISTEM INFORMASI KELULUSAN ONLINE</title>
    <link rel="stylesheet" href="../css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="../css/bootswatch.min.css">
    <link rel="stylesheet" href="../css/jasny-bootstrap.min.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <script type="text/javascript" async="" src="./js/ga.js"></script>
    <script src="../js/jquery-1.10.2.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/bootswatch.js"></script>
</head>
<?php
include "../controller/box-message.php";
?>

<body style="background: url('../images/bg.png') repeat;">
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <?php
                require "../controller/config.php";
                $p = mysqli_query($koneksi, "SELECT * FROM tbl_profil WHERE  id_profil='1'");
                while ($data = mysqli_fetch_array($p)) {
                    echo '<a href="index.php?page=home" class="navbar-brand"><img src="../images/home.png" height="100%"><b>&nbsp&nbsp ' . $data['nm_aplikasi'] . '</b></a>';
                }
                ?>
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-main">
                <ul class="nav navbar-nav navbar-right">
                    <!--li><a href="#" onClick="keluar_modal('keluar.php');"> <b>KELUAR</b></a></li-->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                            <span><img src="../images/ico.png" height="20"></span> <?php echo $_SESSION['username']; ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#" onClick="keluar_modal('keluar.php');"> <b>KELUAR</b></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>