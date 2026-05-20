<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Menu Dashboard -->
    <li class="nav-item">
      <?php $is_dashboard = url_is('dashboard'); ?>
      <a class="nav-link <?= $is_dashboard ? '' : 'collapsed' ?>" href="<?= base_url('dashboard'); ?>">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

<!-- Menu Riwayat Booking -->

<li class="nav-item">
    <?php $is_history = (url_is('booking-history') || url_is('bookings')); ?>
    <a class="nav-link <?= $is_history ? '' : 'collapsed' ?>" href="<?= base_url('booking-history') ?>">
        <i class="bi bi-calendar-event"></i>
        <span>Booking</span>
    </a>
</li>

<!-- Menu Layanan -->
<li class="nav-item">
    <?php $is_services = (url_is('services-list') || url_is('services') || url_is('booking/*')); ?>
    <a class="nav-link <?= $is_services ? '' : 'collapsed' ?>" href="<?= base_url('services-list'); ?>">
        <i class="bi bi-gem"></i>
        <span>Layanan</span>
    </a>
</li>

  </ul>
</aside>