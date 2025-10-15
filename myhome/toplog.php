<?php
require("../config/koneksi.php");
require("../config/function.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
         <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />
         <title><?= $setting['sekolah'] ?></title>
		
		<meta name="description" content="Sandik All in One">
		<meta name="keywords" content="sandik"/>
		<meta name="msapplication-navbutton-color" content="#4285f4">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">
		 <link href="../font/material.css" rel="stylesheet">
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
    background: black;
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
    display: flex;
    justify-content: center;
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

@keyframes spinner {
    100% {
        transform: rotate(3600deg);
    }
}

.loading-container {
    margin: 0 auto;
    text-align: center;
    position: relative;
    width: 100px;
    height: 100px;
}

.spinner {
    display: inline-block;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 12px solid rgba(52, 152, 219, 0.2);
    border-top-color: #3498db;
    border-right-color: #2779bd;
    transform-origin: 50% 50%;
    animation: spinner 2.4s linear infinite;
}

.spinner-center {
    display: inline-block;
    position: absolute;
    top: 50%;
    left: 50%;
    width: 48px;
    height: 48px;
    margin: 0;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    background: radial-gradient(circle, #5dade2 0%, #2e86c1 65%, #1f618d 100%);
    box-shadow: 0 0 16px rgba(52, 152, 219, 0.6);
    content: '';
}

		</style>
</head>
<body>

<div class="pre-loader">
<div class="pre-loader-box">
  <div class="loader-logo">
    <div class="loading-container">
      <span class="spinner"></span>
      <span class="spinner-center"></span>
    </div>
  </div>
  <div class="loader-progress" id="progress_div">
    <div class="bar" id="bar1" style="width: 100%;"></div>
  </div>
  <div class="percent" id="percent1" style="color:gold">100%</div>
  <div class="loading-text" style="color:white">
    Sistem Informasi Akademik
  </div>
</div>
</div>
   
