<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
  <?php
  include "excel_reader2.php";
  include "../controller/config.php";

  // file yang tadinya di upload, di simpan di temporary file PHP, file tersebut yang kita ambil
  // dan baca dengan PHP Excel Class
  $data = new Spreadsheet_Excel_Reader($_FILES['fileexcel']['tmp_name']);
  $hasildata = $data->rowcount($sheet_index = 0);
  // default nilai 
  $sukses = 0;
  $gagal = 0;

  for ($i = 1; $i <= $hasildata; $i++) {
    $data1 = $data->val($i, 1);
    $data2 = $data->val($i, 2);
    $data3 = $data->val($i, 3);
    $data4 = $data->val($i, 4);
    $data5 = $data->val($i, 5);
    $data6 = $data->val($i, 6);
    $data7 = $data->val($i, 7);
    $data8 = $data->val($i, 8);
    $data9 = $data->val($i, 9);
    $data10 = $data->val($i, 10);
    $data11 = $data->val($i, 11);
    $data12 = $data->val($i, 12);
    $data13 = $data->val($i, 13);
    $data14 = $data->val($i, 14);
    $data15 = $data->val($i, 15);
    $data16 = $data->val($i, 16);
    $data17 = $data->val($i, 17);
    $data18 = $data->val($i, 18);
    $data19 = $data->val($i, 19);
    $data20 = $data->val($i, 20);
    $data21 = $data->val($i, 21);
    $data22 = $data->val($i, 22);
    $data23 = $data->val($i, 23);
    $data24 = $data->val($i, 24);
    $data25 = $data->val($i, 25);
    $data26 = $data->val($i, 26);
    $data27 = $data->val($i, 27);
    $data28 = $data->val($i, 28);
    $data29 = $data->val($i, 29);
    $data30 = $data->val($i, 30);
    $data31 = $data->val($i, 31);
    $data32 = $data->val($i, 32);
    $data33 = $data->val($i, 33);
    $data34 = $data->val($i, 34);
    $data35 = $data->val($i, 35);

    $query = "INSERT INTO tb_student VALUES ('$data1','$data2','$data3','$data4','$data5',
'$data6','$data7','$data8','$data9','$data10','$data11','$data12','$data13','$data14',
'$data15','$data16','$data17','$data18','$data19','$data20','$data21','$data22','$data23',
'$data24','$data25','$data26','$data27','$data28','$data29','$data30','$data31','$data32',
'$data33','$data34','$data35')";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasildata) $sukses++;
    else $gagal++;

    echo "<pre>";
    print_r($query);
    echo "</pre>";
  }
  echo "<meta http-equiv='refresh' content='1; url=datasiswa.php'>";
  ?>
</div>