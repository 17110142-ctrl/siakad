
    <script src="<?= $homeurl ?>/assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?= $homeurl ?>/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= $homeurl ?>/assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="<?= $homeurl ?>/assets/plugins/pace/pace.min.js"></script>
    <script src="<?= $homeurl ?>/assets/plugins/highlight/highlight.pack.js"></script>
    <script src="<?= $homeurl ?>/assets/plugins/blockUI/jquery.blockUI.min.js"></script>	
	<script src="<?= $homeurl ?>/assets/plugins/select2/js/select2.full.min.js"></script>
	<script src="<?= $homeurl ?>/assets/plugins/datatables/datatables.js"></script>
    <script src="<?= $homeurl ?>/assets/js/main.min.js"></script>
    <script src="<?= $homeurl ?>/assets/js/custom.js"></script>
	<script src="<?= $homeurl ?>/assets/js/pages/datatables.js"></script>
	<script src='<?= $homeurl ?>/assets/izitoast/js/iziToast.min.js'></script>
    <script src="<?= $homeurl ?>/assets/js/pages/blockui.js"></script>
	<script src="<?= $homeurl ?>/assets/js/sweetalert2.min.js"></script>
	<script src='<?= $homeurl ?>/assets/datetimepicker/build/jquery.datetimepicker.full.min.js'></script>
	
	 <script>
		    var autoRefresh = setInterval(
			function() {
				$('#waktu').load('waktu.php?pg=waktu');
			}, 1000
		);
      </script>
	  
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
		$('.jam3').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});		

        $('.jam4').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});	
		$('.jam5').datetimepicker({
			datepicker: false,
			format: 'H:i'
		});		
			$(function() {
			$('#textarea').wysihtml5()
		});
		
		
		</script>