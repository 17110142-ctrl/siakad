 <div class="app-header" >
                <nav class="navbar navbar-light navbar-expand-lg">
                    <div class="container-fluid">
                        <div class="navbar-nav" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i></a>
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
			