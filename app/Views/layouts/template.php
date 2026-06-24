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