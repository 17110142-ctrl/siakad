<?php 
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>

<?php include"top.php"; ?>

<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom:10px;">
  <div class=" alert alert-dismissable alert-danger" style="margin-bottom: 20px;">
    <h4 align="left" style="text-transform: uppercase;">
      <script language=JavaScript src="../js/almanak.js"></script>
      <span class="style1">-</span>
      <script language=JavaScript>
        var d = new Date();
        var h = d.getHours();
        if (h < 11) {
          document.write('SELAMAT PAGI');
        } else {
          if (h < 15) {
            document.write('SELAMAT SIANG');
          } else {
            if (h < 19) {
              document.write('SELAMAT SORE');
            } else {
              if (h <= 23) {
                document.write('SELAMAT MALAM');
              }
            }
          }
        }
      </script>
      - <b>ADMINISTRATOR SISTEM INFORMASI KELULUSAN!</b>
    </h4>
  </div>
  <div class=" alert alert-dismissable alert-success" style="margin-bottom: 0px;">
    <h4 align="left" style="text-transform: uppercase;"> SELAMAT DATANG <b><?php echo $_SESSION['namauser']; ?></b> ANDA LOGIN SEBAGAI <strong><?php echo $_SESSION['username']; ?></strong>
      </b>
    </h4>
    </h4>
  </div>
</div>