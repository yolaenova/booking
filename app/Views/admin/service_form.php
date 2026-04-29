<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Layanan</h3>

<form action="/services/store" method="post" enctype="multipart/form-data">

<input type="text" name="name" class="form-control mb-2" placeholder="Nama Layanan">

<textarea name="description" class="form-control mb-2" placeholder="Deskripsi"></textarea>

<input type="number" name="price" class="form-control mb-2" placeholder="Harga">

<input type="number" name="duration" class="form-control mb-2" placeholder="Durasi (menit)">

<input type="file" name="photo" class="form-control mb-2">

<button class="btn btn-success">Simpan</button>

</form>

<?= $this->endSection() ?>