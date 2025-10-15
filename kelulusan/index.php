
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <title>SIK ONLINE &copy SISTEM INFORMASI KELULUSAN ONLINE</title>
  <link rel="stylesheet" href="css/bootstrap.css" media="screen">
  <link rel="stylesheet" href="css/style.css" media="screen">
  <link rel="stylesheet" href="css/bootswatch.min.css">
  <link rel="stylesheet" href="css/jasny-bootstrap.min.css">
  <link rel="icon" type="image/png" href="../images/logo.png">
  <script type="text/javascript" async="" src="js/ga.js"></script>
  <script src="js/jquery-1.10.2.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootswatch.js"></script>
</head>

<body style="background: url('images/bg.png') repeat;">
  <?php
  include "../kelulusan/controller/header.php";
  ?>
  <div class="container" style="height: 100%; display: flex; justify-content: center; align-items: center;">
    <table>
      <tr>
        <td style="padding: 10px;">
          <?php
          require("../config/koneksi.php");

          error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
          $page = $_GET['page'];
          $filename = "../kelulusan/model/$page.php";
          if (!file_exists($filename)) {
            include "../kelulusan/model/beranda.php";
          } else {
            @include "../kelulusan/model/$page.php";
          }
          ?>
        </td>
      </tr>
    </table>
  </div>
  <?php
  include "../kelulusan/controller/footer.php";
  ?>
</body>

</html>