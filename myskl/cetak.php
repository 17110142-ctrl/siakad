<?php
defined('APK') or exit('Anda tidak dizinkan mengakses langsung script ini!');
$skl = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM skl  WHERE id_skl='1'"));
?>
<?php if ($ac == '') : ?>
    <div class='row'>
        <div class='col-md-12'>
            <div class='panel panel-default'>
               <div class="panel-heading" style="height:45px">
                  
                    <div class='box-tools pull-right'>
                        <?php if ($user['level'] == 'admin') : ?>
                            
                            <a href='?pg=updatesiswa' class='btn btn-sm btn-success'><i class='fa fa-upload'></i>  Update Siswa</a>
                           
                        <?php endif ?>
                    </div>
					 <h4 class='box-title'><i class="fas fa-user-friends fa-fw"></i> Data Kelulusan Siswa</h4>
                </div>
			  
			  <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true" >Data Siswa</a></li>
                        
                    </ul>
					
					<div class="tab-content">
                        <div class="tab-pane active" id="tab_1" >
						<br>
						<div class="panel panel-default">	
                <div class='box-body'>
                   <div id='cetakskl'>
				   
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
	

<?php endif ?>
<script type="text/javascript">
		$(document).ready(function() {
			setInterval(function(){
				$("#cetakskl").load('sandik_skl/print_skl.php?nis=<?= $_GET['nis'] ?>')
			}, 2000);
		});	
	</script>
<script>
   
 
    $('#example1').on('click', '.hapus', function() {
        var id = $(this).data('id');
        console.log(id);
        Swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'	
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: 'sandik_ujian/crud_siswa.php?pg=hapus',
                    method: "POST",
                    data: 'id_siswa=' + id,
                    success: function(data) {
                  iziToast.info(
            {
                title: 'Sukses!',
                message: 'Data berasil dihapus',
				titleColor: '#FFFF00',
				messageColor: '#fff',
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				 progressBarColor: '#FFFF00',
                  position: 'topRight'			  
                });
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                });
            }
            return false;
        })

    });
</script>