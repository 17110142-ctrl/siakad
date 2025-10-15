<script src="../js/jquery.validate.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#frm").validate({
			debug: false,
			rules: {
				nama: "required",
				username: "required",
				pass: "required",
			},
			messages: {
				nama: "* Nama Tidak Boleh Kosong",
				username: "* Username Tidak Boleh Kosong",
				pass: "* Password Tidak Boleh Kosong"
			},
			submitHandler: function(form) {
				// do other stuff for a valid form
				$.post('simpanpetugas.php', $("#frm").serialize(), function(data) {
					$('#hasil').html(data);
					document.frm.nama.value = "";
					document.frm.username.value = "";
					document.frm.pass.value = "";
				});
			}
		});
	});
</script>

<div id="rightPan">
	<div class="well" style="margin-left: 10px; margin-top: 10px; margin-bottom: 60px;">
		<div class="cleaner_h5"></div>
		<div id="hasil"></div>
		<div class="cleaner_h5"></div>
		<fieldset>
			<div class=" alert alert-dismissable alert-danger">
				<h4 align="center"><b>SIK - TAMBAH PENGELOLA APLIKASI</b></h4>
			</div>
			<form class="form-horizontal" method="post" action="" name="frm" id="frm">
				<div class="input-group">
					<input type="text" class="form-control" name="nama" placeholder="NAMA LENGKAP" size="50" style="text-align: left;">
					<span class="input-group-addon" id="basic-addon1"><b>NAMA</b></span>
				</div>
				</br>
				<div class="input-group">
					<input type="text" class="form-control" name="username" placeholder="USERNAME" size="50" style="text-align: left;">
					<span class="input-group-addon" id="basic-addon1"><b>USER</b></span>
				</div>
				</br>
				<div class="input-group">
					<input type="text" class="form-control" name="pass" placeholder="PASSWORD" size="50" style="text-align: left;">
					<span class="input-group-addon" id="basic-addon1"><b>PASS</b></span>
				</div>
				</br>
				<div class="form-group">
					<p align="right">
						<input type="submit" name="submit" id="submit" value="SIMPAN" class="btn btn-danger" onclick="tb_remove()">
					</p>
				</div>
			</form>
		</fieldset>
	</div>