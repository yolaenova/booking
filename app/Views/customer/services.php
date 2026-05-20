<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $services = $services ?? []; ?>

<div class="alert alert-info py-3 mb-4">
    <h5><strong>Pilihan Layanan Makeup</strong></h5>
    <p class="mb-0">Silakan pilih layanan yang kamu inginkan, <?= session('name') ?>!</p>
</div>

<div class="row">

<?php if (empty($services)): ?>
    <div class="col-12 text-center">
        <div class="alert alert-warning">
            Maaf, saat ini belum ada layanan yang tersedia.
        </div>
    </div>
<?php else: ?>
    <?php foreach($services as $s): ?>
    <div class="col-md-4">
      <div class="card mb-3 shadow-sm">

        <img src="<?= base_url('public/assets/img/services/' . $s['photo']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">

        <div class="card-body">
          <h5 class="fw-bold"><?= $s['name'] ?></h5>
          <p class="text-success fw-bold">Rp <?= number_format($s['price'], 0, ',', '.') ?></p>

          <a href="/booking/<?= $s['id'] ?>" class="btn btn-primary w-100">Booking</a>
        </div>

      </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

</div>

<?= $this->endSection() ?>