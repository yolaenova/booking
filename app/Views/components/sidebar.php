<?php $role = strtolower(trim(session()->get('role'))); ?>

<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

<?php if ($role == 'admin' || $role == 'administrator'): ?>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin') ?>">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url('bookings') ?>">
            <i class="bi bi-calendar-check"></i>
            <span>Booking</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url('services') ?>">
            <i class="bi bi-brush"></i>
            <span>Layanan</span>
        </a>
    </li>

<?php elseif ($role == 'customer'): ?>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('customer') ?>">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="<?= base_url('services-list') ?>">
            <i class="bi bi-brush"></i>
            <span>Booking Makeup</span>
        </a>
    </li>

<?php elseif ($role == 'staff'): ?>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('staff') ?>">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>

<?php else: ?>

    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-exclamation-circle"></i>
            <span>Role Tidak Ditemukan</span>
        </a>
    </li>

<?php endif; ?>

</ul>

</aside>