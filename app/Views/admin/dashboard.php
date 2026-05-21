<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
  <h1>Dashboard</h1>
</div>

<section class="section">
  <div class="row">

    <!-- TOTAL BOOKING -->
    <div class="col-lg-4">
      <div class="card info-card">
        <div class="card-body">
          <h5 class="card-title">Total Booking</h5>
          <h3><?= $totalBooking ?? 0 ?></h3>
        </div>
      </div>
    </div>

    <!-- CUSTOMER -->
    <div class="col-lg-4">
      <div class="card info-card">
        <div class="card-body">
          <h5 class="card-title">Customer</h5>
          <h3><?= $totalCustomer ?? 0 ?></h3>
        </div>
      </div>
    </div>

    <!-- REVENUE -->
    <div class="col-lg-4">
      <div class="card info-card">
        <div class="card-body">
          <h5 class="card-title">Revenue</h5>
          <h3>Rp <?= number_format($revenue ?? 0) ?></h3>
        </div>
      </div>
    </div>

  </div>

  <div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Daftar Customer</h5>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($customers as $c) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $c['name']; ?></td>
                        <td><?= $c['email']; ?></td>
                        <td><?= $c['phone']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</section>

<?= $this->endSection() ?>