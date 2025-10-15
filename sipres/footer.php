
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="../assets/plugins/pace/pace.min.js"></script>
    <script src="../assets/plugins/summernote/summernote-lite.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script src="../assets/js/pages/mailbox.js"></script>
	<script src='<?= $homeurl ?>/assets/izitoast/js/iziToast.min.js'></script>
	<script src="<?= $homeurl ?>/assets/plugins/datatables/datatables.js"></script>
	<script src="<?= $homeurl ?>/assets/js/pages/datatables.js"></script>
	<script>
			
			$('#datatable1').DataTable({
				pageLength: 10
			});
			$('.select2').select2();
			function kelapKelip() {
			$('.sandik').fadeOut(); 
			$('.sandik').fadeIn(); 
			}
			setInterval(kelapKelip, 500);
          $('.datepicker').datetimepicker({
			timepicker: false,
			format: 'Y-m-d'
		});
		$('.tgl').datetimepicker();
		$('.timer').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});	
		$('.jam1').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});	
		$('.jam2').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});			
			$(function() {
			$('#textarea').wysihtml5()
		});
		
		
		</script>