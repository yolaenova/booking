<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?? 'Dashboard' ?></title>

<!-- Pastikan menggunakan tanda kurung dan tanda petik yang benar -->
<link href="<?= base_url('NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
<link href="<?= base_url('NiceAdmin/assets/css/style.css') ?>" rel="stylesheet">

    <!-- DATATABLE -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .nav-profile img {
            width: 36px;
            height: 36px;
            object-fit: cover;
        }

        .dropdown-menu.profile {
            min-width: 240px;
        }

        .dropdown-header h6 {
            margin-bottom: 0;
            font-weight: 700;
        }

        .dropdown-item {
            padding: 10px 18px;
        }

        .badge-number {
            font-size: 10px;
            padding: 4px 6px;
        }

        .sidebar .nav-link.active {
            background: #f6f9ff;
            color: #4154f1;
        }
    </style>
</head>

<body>

<!-- ======= HEADER ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <!-- Logo -->
    <div class="d-flex align-items-center justify-content-between">
        <a href="/admin" class="logo d-flex align-items-center">
            <i class="bi bi-gem me-2"></i>
            <span class="d-none d-lg-block">MUA Booking</span>
        </a>

        <!-- tombol sidebar mobile -->
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <!-- Right Header -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <!-- Notification -->
            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 4 notifications
                    </li>
                </ul>

            </li>

            <!-- Message -->
            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">3</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                    <li class="dropdown-header">
                        You have 3 messages
                    </li>
                </ul>

            </li>

            <!-- Profile -->
            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0"
                   href="#"
                   data-bs-toggle="dropdown">

                    <img src="<?= base_url('NiceAdmin/assets/img/profile-img.jpg') ?>"
                         alt="Profile"
                         class="rounded-circle">

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        <?= session('name') ?> (admin)
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header text-center">
                        <h6><?= session('name') ?></h6>
                        <span>Administrator</span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/profile">
                            <i class="bi bi-person"></i>
                            <span class="ms-2">My Profile</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/settings">
                            <i class="bi bi-gear"></i>
                            <span class="ms-2">Account Settings</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-question-circle"></i>
                            <span class="ms-2">Need Help?</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="ms-2">Sign Out</span>
                        </a>
                    </li>

                </ul>

            </li>

        </ul>
    </nav>

</header>

<!-- ======= SIDEBAR ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'admin' ? 'active' : '' ?>" href="/admin">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'bookings' ? 'active' : '' ?>" href="/bookings">
                <i class="bi bi-calendar-check"></i>
                <span>Booking</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'services' ? 'active' : '' ?>" href="/services">
                <i class="bi bi-brush"></i>
                <span>Layanan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= uri_string() == 'services-list' ? 'active' : '' ?>" href="/services-list">
                <i class="bi bi-person"></i>
                <span>Booking Makeup</span>
            </a>
        </li>

    </ul>

</aside>

<!-- ======= MAIN ======= -->
<main id="main" class="main">
    <?= $this->renderSection('content') ?>
</main>

<!-- ======= FOOTER KECIL (Opsional) ======= -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="<?= base_url('NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/chart.js/chart.umd.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/echarts/echarts.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/quill/quill.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/php-email-form/validate.js') ?>"></script>

<!-- JQUERY & DATATABLE -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Template Main JS File (INI PALING PENTING) -->
<script src="<?= base_url('NiceAdmin/assets/js/main.js') ?>"></script>
</body>
</html>