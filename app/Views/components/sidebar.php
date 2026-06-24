<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Menu Dashboard -->
    <li class="nav-item">
      <a class="nav-link <?= (url_is('admin') || url_is('admin/index')) ? '' : 'collapsed' ?>" href="<?= base_url('admin'); ?>">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Menu Booking -->
<!-- Menu Booking -->
<li class="nav-item">
  <a class="nav-link <?= url_is('admin/bookings*') ? '' : 'collapsed' ?>" href="<?= base_url('admin/bookings'); ?>">
    <i class="bi bi-calendar-check"></i>
    <span>Booking</span>
  </a>
</li>

    <!-- Menu Layanan -->
    <li class="nav-item">
      <a class="nav-link <?= url_is('admin/services*') ? '' : 'collapsed' ?>" href="<?= base_url('admin/services'); ?>">
        <i class="bi bi-gem"></i>
        <span>Layanan</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link <?= url_is('admin/kurs*') ? '' : 'collapsed' ?>" href="<?= base_url('admin/kurs'); ?>">
        <i class="bi bi-currency-exchange"></i>
        <span>Kurs Global</span>
      </a>
    </li>

  </ul>
</aside>