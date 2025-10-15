<?php
session_start();
if (empty($_SESSION['namauser']) and empty($_SESSION['kode'])) {
	echo "<p>OPS!!!.</p>";
} else {
?>
	<?php
	include "adm-header.php";
	?>
	<div class="container" style="height: 100%; display: flex; justify-content: center; border: 0px solid #000;">
		<?php
		include "adm-menu.php";
		?>
		<table>
			<tr>
				<td>
					<?php include "adm-table.php"; ?>
				</td>
			</tr>
		</table>
	</div>
	<?php
	include "../controller/footer.php";
	?>
	</body>

	</html>
<?php
}
?>