<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">

        <a href="<?= base_url(session('role')) ?>" class="logo d-flex align-items-center">
            <i class="bi bi-gem me-2"></i>
            <span class="d-none d-lg-block">MUA Booking</span>
        </a>

        <i class="bi bi-list toggle-sidebar-btn"></i>

    </div>

    <nav class="header-nav ms-auto">

        <ul class="d-flex align-items-center">

            <!-- PROFILE -->
            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0"
                   href="#"
                   data-bs-toggle="dropdown">

                    <img src="<?= base_url('niceadmin/assets/img/profile-img.jpg') ?>"
                         class="rounded-circle">

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        <?= session('name') ?>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header text-center">
                        <h6><?= session('name') ?></h6>
                        <span><?= ucfirst(session('role')) ?></span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="ms-2">Logout</span>
                        </a>
                    </li>

                </ul>

            </li>

        </ul>

    </nav>

</header>