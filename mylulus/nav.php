 <div class="app-header" >
                <nav class="navbar navbar-light navbar-expand-lg">
                    <div class="container-fluid">
                        <div class="navbar-nav" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i><small>SEMESTER</small> <small class="badge badge-primary"><?= $setting['semester'] ?></small></a>
                                </li>
								     
                                        </ul>
                                </li>
                               
                            </ul>
            
                        </div>
						<div id='progressbox'></div>
                        <div class="d-flex">
                            <ul class="navbar-nav">
                               
									 <li class="nav-item hidden-on-mobile">
                                    <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown" data-bs-toggle="dropdown"> <?= ucfirst($user['nama']) ?><i class="material-icons has-sub-menu">keyboard_arrow_down</i><?php if($user['foto']==''){ ?><img src="<?= $homeurl ?>/images/guru.png"<?php }else{ ?><img src="<?= $homeurl ?>/images/fotoguru/<?= $user['foto'] ?>"<?php } ?> alt=""></a>
                                        <ul class="dropdown-menu dropdown-menu-end language-dropdown" aria-labelledby="languageDropDown">
                                            <li><a class="dropdown-item" href="logout.php"><img src="<?= $homeurl ?>/images/logout.png" alt="">Log Out</a></li>
                                           
                                        </ul>
                                </li>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
			<script>
			$('#server').on('click', '.ganti', function() {
			var id = $(this).data('id');
			console.log(id);
				Swal.fire({
					title: 'Ganti Mode Server?',
					text: "Informasi : Mengganti mode Server",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, Ganti !',
					cancelButtonText: "Batal"				  
					}).then((result) => {
					if (result.value) {
						$.ajax({
						url: 'gantiserver.php',
						method: "POST",
						data: 'id=' + id,
						success: function(data) {
						Swal.fire(
						'Update!',
						'Mode Server telah diganti',
						'success'
						) 
						setTimeout(function() {
						window.location.reload();
								}, 1000);
							}
						});
					}
					return false;
					})
			});
</script>