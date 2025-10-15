

<div class='row'>

        <div class='col-md-12'>
             <div class="x_panel">
			<div class="x_title">
				<h2>RESTORE DATABASE</h2>
                <div class="clearfix"></div>
								</div>
				<div class="x_content">
                            <div class='col-md-12 notif'></div>
                            <div class='col-md-6'>
                               
                                    <div class='clearfix'>
                                        <form id='formrestore'>
                                            <p>Klik Tombol dibawah ini untuk merestore database </p>
                                            <div class='col-md-12'>
                                                <input class='form-control' name='datafile' type='file' required />
                                            </div>
											<br><br><br>
                                            <button name='restore' class='btn btn-flat btn-outline-primary pull-right'><i class='fa fa-database'></i> Restore Data</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
           
                      	 
									 
<script>
	
	 $('#formrestore').submit(function(e) {
        e.preventDefault();
        var data = new FormData(this);
        //console.log(data);
        $.ajax({
            type: 'POST',
            url: 'pengaturan/crud_setting.php?pg=setting_restore',
            enctype: 'multipart/form-data',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(data) {
                $('.loader').hide();
                Swal.fire({	
				title: '<a href="#" class="sandik" style="color:blue">Data berhasil direstore</a>',				
				showConfirmButton: false,
				 animation: false,
				  customClass: 'animated tada',				  
				  imageUrl: '../dist/img/sandik_kecil.gif',
                 footer: '<a href="#"><b style="color:red">Sistem Administrasi Pendidik (SANDIK)</b></a>'
                    });
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
            }
        });
        return false;
    });
</script>