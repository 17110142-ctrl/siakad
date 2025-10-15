<?php

require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
require("../config/apk.php");
(isset($_SESSION['id_siswa'])) ? $id_siswa = $_SESSION['id_siswa'] : $id_siswa = 0;

($id_siswa == 0) ?  header("Location:mulai") : null;
$siswa = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id_siswa'"));
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';

?>

 <?php include"top.php"; ?>
    <?php include"nav.php"; ?>	
			<?php include"menu.php"; ?>	
            <div class="app-content">
                <div class="content-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col">
							
                                <div class="mailbox-container">
                                    <?php include"pages.php"; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
   <?php include"footer.php"; ?>
 
</body>
</html>