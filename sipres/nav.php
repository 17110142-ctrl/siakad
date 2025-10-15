<div class="app horizontal-menu align-content-stretch d-flex flex-wrap">
        <div class="app-container" >
            
            <div class="app-header" style="background:#326698;background-size: contain;height:60px;background-image: url('../vendor/bg-top.png');background-repeat: no-repeat;">
                <nav class="navbar navbar-light navbar-expand-lg container" >
                    <div class="container-fluid" >
                        <div class="navbar-nav" id="navbarNav">
                            <div class="logo">
                             <img src="../images/<?= $setting['logo'] ?>" style="width:40px;">  
                            </div>
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i></a>
                                </li>
                                
                                    </ul>
                                
                        </div>
						 <div id='progressbox'></div>
                        <div class="d-flex">
						
                           <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown" data-bs-toggle="dropdown" style="color:white;"> <?= substr($siswa['nama'],0,15) ?> </a>
                            
                        </div>
                    </div>
                </nav>
            </div>
