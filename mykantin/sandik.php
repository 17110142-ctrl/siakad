<?php
require("../config/koneksi.php");
require("../config/apk.php");
require("../config/function.php");
require("../config/crud.php");
(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
($id_user == 0) ? header('location:../myhome/mulai') : null;
$user = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users  WHERE id_user='$id_user'"));
(isset($_GET['pg'])) ? $pg = $_GET['pg'] : $pg = '';
(isset($_GET['ac'])) ? $ac = $_GET['ac'] : $ac = '';
if ($pg == enkripsi('produk')) :
	$sidebar = 'full';
elseif ($pg == enkripsi('tran')) :
	$sidebar = 'full';
else :
	$sidebar = '';
endif;	
?>



 <?php include"top.php"; ?>
<body>
<?php if($sidebar==''): ?>
    <div class="app align-content-stretch d-flex flex-wrap" >
<?php else : ?>
 <div class="app menu-off-canvas align-content-stretch d-flex flex-wrap">	
	<?php endif; ?>
        <div class="app-sidebar">
            <div class="logo" >
			<img src="<?= $homeurl ?>/images/<?= $setting['logo'] ?>" style="no-repeat;max-width:40px">
                <span class="logo-text hidden-on-mobile" style="font-size:12px;font-weight:bold;color:black;"><?= $setting['sekolah'] ?></span>
            
			  <div class="sidebar-user-switcher user-activity-online">
                  
                </div>
				  
            </div>
			
            <?php include"menu.php"; ?>
		 
        <div class="app-container">
            <?php include"nav.php"; ?>
            <div class="app-content">
              <?php include"pages.php"; ?>
                       
					
                </div>
            </div>
        </div>
    </div>
   <?php include"footer.php"; ?>
 
</body>
</html>