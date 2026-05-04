<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Menu Dashboard -->
    <li class="nav-item">
      <?php $is_dashboard = (url_is('admin') || url_is('admin/index')); ?>
      <a class="nav-link <?= $is_dashboard ? '' : 'collapsed' ?>" href="<?= base_url('admin'); ?>">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Menu Booking -->
    <li class="nav-item">
      <?php $is_booking = url_is('bookings*'); ?>
      <a class="nav-link <?= $is_booking ? '' : 'collapsed' ?>" href="<?= base_url('bookings'); ?>">
        <i class="bi bi-calendar-check"></i>
        <span>Booking</span>
      </a>
    </li>

    <!-- Menu Layanan -->
    <li class="nav-item">
      <?php $is_services = url_is('services*'); ?>
      <a class="nav-link <?= $is_services ? '' : 'collapsed' ?>" href="<?= base_url('services'); ?>">
        <i class="bi bi-gem"></i>
        <span>Layanan</span>
      </a>
    </li>

  </ul>

</aside>