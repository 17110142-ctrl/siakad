<script type="text/javascript">
	$(document).ready(function() {
		$(".delbutton").click(function() {
			var element = $(this);
			var del_id = element.attr("id");
			var info = 'id=' + del_id;
			if (confirm("ANDA YAKIN MENGHAPUS DATA!")) {
				$.ajax({
					type: "POST",
					url: "hapuspetugas.php",
					data: info,
					success: function() {}
				});
				$(this).parents(".content").animate({
					opacity: "hide"
				}, "slow");
			}
			return false;
		});
	})
</script>

<link href="../css/thickbox.css" rel="stylesheet" type="text/css" />
<script src="../js/thickbox.js" type="text/javascript"></script>
<div class="cleaner_h5"></div>
<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
	<fieldset>
		<table class="table table-striped table-bordered" style="min-width: 600px;">
			<div class=" alert alert-dismissable alert-danger">
				<h4 align="center"><b>SISTEM INFORMASI KELULUSAN </br>DAFTAR PENGELOLA APLIKASI</b></h4>
			</div>
			<thead class="bg-danger text-white">
				<th>NO.</th>
				<th>NAMA PETUGAS</th>
				<th>USERNAME</th>
				<th>HAK AKSES</th>
				<th>AKSI</th>
			</thead>
			<?php
			include('../controller/config.php');
			$q = mysqli_query($koneksi, "SELECT * from tbl_user");
			$n = 1;
			while ($r = mysqli_fetch_array($q)) {
				echo '
					<tr class="content">
						<td style="text-transform: capitalize;">' . $n . '</td>
						<td style="text-transform: capitalize;">' . $r["nama"] . '</td>
						<td style="text-transform: capitalize;">' . $r["username"] . '</td>
						<td style="text-transform: capitalize;">Super Root</td>
						<td align="center">
						<a href="#" class="delbutton" id="' . $r['id_user'] . '" href="#" ><div class="btn btn-danger btn-sm">HAPUS</div></a>
						</td>
					</tr>';
				$n++;
			}
			?>
		</table>
	</fieldset>
	<p align="right"><a href="index.php?page=add-petugas" class="btn btn-info">TAMBAH PENGGUNA</a></p>
</div>
<br />