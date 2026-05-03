<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $services = $services ?? []; ?>

<h3>Pilih Layanan Makeup</h3>

<div class="row">

<?php foreach($services as $s): ?>
<div class="col-md-4">
  <div class="card mb-3">

    <img src="/uploads/<?= $s['photo'] ?>" class="card-img-top">

    <div class="card-body">
      <h5><?= $s['name'] ?></h5>
      <p>Rp <?= number_format($s['price']) ?></p>

      <a href="/booking/<?= $s['id'] ?>" class="btn btn-primary">Booking</a>
    </div>

  </div>
</div>
<?php endforeach; ?>

</div>

<?= $this->endSection() ?>