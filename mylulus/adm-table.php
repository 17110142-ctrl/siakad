<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
    <table class="table table-striped table-bordered">
        <div class=" alert alert-dismissable alert-danger">
            <h4 align="center"><b>SIK - DAFTAR NAMA SISWA</b></h4>
        </div>
        <thead class="bg-danger text-white">
            <th>NO</th>
            <th>NISN</th>
            <th>NAMA SISWA</th>
            <th>TEMPAT TANGGAL LAHIR</th>
            <th>KELAS</th>
            <th>KELULUSAN</th>
            <th>AKSI</th>
        </thead>

        <?php
        $batas = 10;
        $halaman = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

        $previous = $halaman - 1;
        $next = $halaman + 1;
        include "../controller/config.php";
        $data = mysqli_query($koneksi, "select * from tb_student");
        $jumlah_data = mysqli_num_rows($data);
        $total_halaman = ceil($jumlah_data / $batas);
        $data_siswa = mysqli_query($koneksi, "select * from tb_student limit $halaman_awal, $batas");
        $nomor = $halaman_awal + 1;
        while ($r = mysqli_fetch_array($data_siswa)) {
        ?>
            <tr class="success">
                <td><?php echo $nomor++; ?></td>
                <td><?php echo $r['nisn'] ?></td>
                <td><?php echo $r['name'] ?></td>
                <td><?php echo $r['addresse'];
                    echo ", ";
                    echo $r['tgllhr']; ?></td>
                <td><?php echo $r['kelas'] ?></td>
                <td><?php echo $r['ket'] ?></td>
                <td>
                    <a href="editsiswa.php?no=<?php echo $r['no'] ?>" class=" btn btn-info btn-sm" title="Edit">EDIT</a>
                    <a href="#" onClick="confirm_modal('adm-delete.php?no=<?php echo $r['no'] ?>');">
                        <button class="btn btn-danger btn-sm" title="Hapus">HAPUS</button>
                    </a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php include "adm-pag.php"; ?>
</div>