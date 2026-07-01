<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?? 'Dashboard' ?></title>

    <!-- NICEADMIN CSS -->
    <link href="<?= base_url('niceadmin/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('niceadmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">
    <link href="<?= base_url('niceadmin/assets/css/style.css') ?>" rel="stylesheet">

    <!-- DATATABLE -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        /* 1. Latar Belakang Utama Aplikasi (Ivory) */
        body, #main { 
            background-color: #FCFBF7 !important; 
            font-family: 'Segoe UI', -apple-system, Roboto, sans-serif;
        }

        /* 2. Kustom Header atas NiceAdmin (Burgundy & Gold Border) */
        .header {
            background-color: #4A1525 !important;
            border-bottom: 3px solid #E6D5B8 !important;
            box-shadow: 0px 2px 20px rgba(74, 21, 37, 0.1);
        }

        /* Logo & Teks Header bawaan NiceAdmin */
        .header .logo span {
            color: #E6D5B8 !important; /* Warna Emas */
            font-weight: 700;
        }
        
        .header .toggle-sidebar-btn {
            color: #E6D5B8 !important; /* Tombol menu hamburger warna emas */
        }

        /* Akun Profile & Dropdown di Header */
        .header .nav-profile span {
            color: #F5EBE6 !important;
        }

        /* 3. Kustom Sidebar (Burgundy Gelap & Teks Terang) */
        .sidebar {
            background-color: #3D101E !important; /* Burgundy sedikit lebih gelap untuk dimensi */
            border-right: 1px solid rgba(230, 213, 184, 0.1);
        }

        /* Link menu di dalam sidebar saat tertutup */
        .sidebar-nav .nav-link.collapsed {
            background-color: transparent !important;
            color: #F5EBE6 !important; /* Putih gading */
            opacity: 0.8;
        }

        .sidebar-nav .nav-link.collapsed i {
            color: #E6D5B8 !important; /* Ikon warna emas */
        }

        /* Menu aktif saat ini di sidebar */
        .sidebar-nav .nav-link:not(.collapsed) {
            background-color: #4A1525 !important;
            color: #E6D5B8 !important; /* Teks emas */
            font-weight: 700;
        }

        .sidebar-nav .nav-link:not(.collapsed) i {
            color: #E6D5B8 !important;
        }

        /* Efek hover menu di sidebar */
        .sidebar-nav .nav-link:hover {
            background-color: #4A1525 !important;
            color: #E6D5B8 !important;
            opacity: 1;
        }

        /* 4. Kustom Elemen Umum (Card & Tombol Utama) */
        .card {
            border: none !important;
            background-color: #FFFFFF !important;
            box-shadow: 0 4px 15px rgba(74, 21, 37, 0.04) !important;
            border-radius: 10px !important;
        }

        .card-title {
            color: #4A1525 !important; /* Judul card jadi Burgundy */
            font-weight: 600;
        }

        .btn-primary {
            background-color: #4A1525 !important;
            border-color: #E6D5B8 !important;
            color: #E6D5B8 !important;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #E6D5B8 !important;
            border-color: #4A1525 !important;
            color: #4A1525 !important;
        }

        .text-primary {
            color: #4A1525 !important;
        }

        /* Breadcrumb (Navigasi Halaman Aktif) */
        .breadcrumb-item.active {
            color: #4A1525 !important;
            font-weight: 600;
        }
    </style>

</head>

<body>

<?= $this->include('components/header') ?>
<?php if (session('role') === 'customer') : ?>
    <?= $this->include('components/sidebar_customer') ?>
<?php else : ?>
    <?= $this->include('components/sidebar') ?>
<?php endif; ?>

<main id="main" class="main">
    <?= $this->renderSection('content') ?>
</main>

<?= $this->include('components/footer') ?>

</body>
</html>