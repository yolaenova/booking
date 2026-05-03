<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $service = $service ?? []; ?>

<h3>Booking Layanan</h3>

<div class="card">
<div class="card-body">

<form method="post" action="/booking/save">

<input type="hidden" name="service_id" value="<?= $service['id'] ?>">
<input type="hidden" name="price" value="<?= $service['price'] ?>">

<div class="mb-3">
<label>Nama Layanan</label>
<input type="text" class="form-control" value="<?= $service['name'] ?>" readonly>
</div>

<div class="mb-3">
<label>Harga</label>
<input type="text" class="form-control" value="Rp <?= number_format($service['price']) ?>" readonly>
</div>

<div class="mb-3">
<label>Catatan</label>
<textarea class="form-control" name="notes"></textarea>
</div>

<button class="btn btn-success">Booking Sekarang</button>

</form>

</div>
</div>

<?= $this->endSection() ?>