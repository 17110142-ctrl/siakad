<?php
require("../config/koneksi.php");
require("../config/function.php");
function youtube($url)
{
$link = str_replace('http://www.youtube.com/watch?v=', '', $url);
$link = str_replace('https://www.youtube.com/watch?v=', '', $link);
$data = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $link . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
return $data;
}
?>

<!DOCTYPE html>
<html lang="en" translate="no">
    <head>
        <meta charset="utf-8" />
		<meta name="google" content="notranslate">
         <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
         <title><?= $setting['sekolah'] ?></title>
		
		<meta name="description" content="Sandik All in One">
		<meta name="keywords" content="sandik"/>
		<meta name="msapplication-navbutton-color" content="#4285f4">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">
		<link rel='stylesheet' href='../vendor/fontawesome/css/all.css' />
        <link href="../botstrap-login/css/front.min.css" rel="stylesheet" />
		 <link rel="stylesheet" href="../botstrap-login/css/1.css">
		 <link rel="stylesheet" href="../botstrap-login/css/2.css">
		 <link rel="stylesheet" href="../botstrap-login/css/3.css">
		 <link rel="stylesheet" href="../botstrap-login/css/components2.css">
		 <link href="../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
		<link href="../assets/plugins/pace/pace.css" rel="stylesheet">
		<link href="../assets/plugins/datatables/datatables.min.css" rel="stylesheet">
		<link href="../assets/plugins/highlight/styles/github-gist.css" rel="stylesheet">
		<link rel='stylesheet' href="../assets/izitoast/css/iziToast.min.css">
		<script src="../assets/plugins/jquery/jquery-3.5.1.min.js"></script>
		<link href="../assets/css/main.css" rel="stylesheet">    
		<link rel="icon" type="image/png" sizes="32x32" href="../images/<?= $setting['logo'] ?>" />
       <link rel="icon" type="image/png" sizes="16x16" href="../images/<?= $setting['logo'] ?>" />
	  <style type="text/css">
   .bold { font-weight: bold; }
   .pull-right{
    float: right;
    display: block;
	margin-top:-30px;
  }
</style> 	
		
<style>
        @media screen and (max-width: 360px){
            #rc-imageselect, .g-recaptcha {transform:scale(0.66);-webkit-transform:scale(0.66);transform-origin:0 0;-webkit-transform-origin:0 0;}
        }
        @media screen and (min-width: 361px,max-width: 720px){
            #rc-imageselect, .g-recaptcha {transform:scale(0.88);-webkit-transform:scale(0.88);transform-origin:0 0;-webkit-transform-origin:0 0;}
        }
    </style>
    <style >
.pre-loader {
    background: #fff;
    background-position: center center;
    background-size: 13%;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 12345;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center
}

.pre-loader .loader-logo {
    padding-bottom: 15px;
}

.pre-loader .loader-progress {
    height: 8px;
    border-radius: 15px;
    max-width: 200px;
    margin: 0 auto;
    display: block;
    background: #ecf0f4;
    overflow: hidden
}

.pre-loader .bar {
    width: 0%;
    height: 8px;
    display: block;
    background: #1b00ff
}

.pre-loader .percent {
    text-align: center;
    font-size: 24px;
}

.pre-loader .loading-text {
    text-align: center;
    font-size: 18px;
    font-weight: 500;
    padding-top: 15px
}

		</style>
</head>
<body>

<div class="pre-loader">
<div class="pre-loader-box">
  <div class="loader-logo"><img src="../images/loading.gif" width="160px" alt=""></div>
  <div class="loader-progress" id="progress_div">
    <div class="bar" id="bar1" style="width: 100%;"></div>
  </div>
  <div class="percent" id="percent1">100%</div>
  <div class="loading-text">
    Loading...
  </div>
</div>
</div>
   