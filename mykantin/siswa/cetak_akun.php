<style type="text/css">
    .ttd {
        position: absolute;
        z-index: -1;
    }
</style>
<?php
require("../../config/koneksi.php");
require("../../config/function.php");
require("../../config/crud.php");
include "../../vendor/phpqrcode/qrlib.php";
(isset($_SESSION['id_user'])) ? $id_user = $_SESSION['id_user'] : $id_user = 0;
($id_user == 0) ? header('location:index.php') : null;

?>
<style>
    * {
        font-size: x-small;
    }

    .box {
        border: 1px solid #000;
        width: 100%;
        height: 150px;
    }

    .ukuran {
        font-size: 15px;
    }

    .ukuran2 {
        font-size: 12px;
    }

    .user {
        font-size: 15px;
    }
	@page { margin: 20px; }
body { margin: 20px; }
</style>

<table width='100%' align='center' cellpadding='8'>
    <tr>
        <?php $siswaQ = mysqli_query($koneksi, "SELECT * FROM siswa"); ?>
        <?php while ($siswa = mysqli_fetch_array($siswaQ)) : ?>
            <?php
            $nopeserta = $siswa['no_peserta'];
            $no++;
            ?>
			
            <td width='50%'>
                <div style='width:10.4cm;border:1px solid #666;'>
                    
                    <table style="text-align:left; width:100%">
                        <tr>
                            <td class="ukuran" valign='top'>Nama</td>
                            <td class="ukuran2" valign='top'>: <?= $siswa['nama'] ?></td>
                        </tr>
                        <tr>
                            <td class="ukuran" valign='top'>Kelas</td>
                            <td class="ukuran" valign='top'>: <?= $siswa['kelas'] ?></td>
                        </tr>
                        <tr>
                            <td class="ukuran" valign='top'>Username</td>
                            <td class="ukuran" valign='top'>:<b class="user"> <?= $siswa['username'] ?></b></td>
                        </tr>
                        <tr>

                            <td class="ukuran" valign='top'>Password</td>
                            <td class="ukuran" valign='top'>: <b class="user"><?= $siswa['password'] ?></b></td>

                        </tr>
                        
                        
                    </table>
                </div>
                 <?php if (($no % 22) == 0) : ?>
                    <div style='page-break-before:always;'></div>
                <?php endif; ?>
            </td>
            <?php if (($no % 2) == 0) : ?>
    </tr>
    <tr>
    <?php endif; ?>
<?php endwhile; ?>
    </tr>
</table>