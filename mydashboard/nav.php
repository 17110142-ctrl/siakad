<div class="app-header">
    <nav class="navbar navbar-light navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-nav" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link hide-sidebar-toggle-button" href="#" style="background-color: #0d6efd; color: white; border-radius: .25rem; padding: 0.375rem 0.75rem; text-decoration: none;">MENU</a>
                    </li>
                </ul>
            </div>
            <div id='progressbox'></div>
            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item hidden-on-mobile">
                        <a class="nav-link language-dropdown-toggle" href="#" id="languageDropDown" data-bs-toggle="dropdown"> <?= ucfirst($siswa['nama']) ?><i class="material-icons has-sub-menu">keyboard_arrow_down</i><?php if ($siswa['foto'] == '') { ?><img src="<?= $homeurl ?>/images/guru.png" style="width: 32px; height: 32px; object-fit: cover; border-radius: 50%;" <?php } else { ?><img src="<?= $homeurl ?>/images/fotosiswa/<?= $siswa['foto'] ?>" style="width: 32px; height: 32px; object-fit: cover; border-radius: 50%;" <?php } ?> alt="Foto Profil"></a>
                        <ul class="dropdown-menu dropdown-menu-end language-dropdown" aria-labelledby="languageDropDown">
                            <li><a class="dropdown-item" href="../myhome/logout.php"><img src="<?= $homeurl ?>/images/logout.png" alt="">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
