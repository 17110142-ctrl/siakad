<?php
require("../config/koneksi.php");
require("../config/function.php");
require("../config/crud.php");
 mysqli_query($koneksi,"TRUNCATE mapel_ijazah");
  mysqli_query($koneksi,"TRUNCATE nilai_skl");
  mysqli_query($koneksi,"UPDATE  siswa SET keterangan=NULL");
  ?>