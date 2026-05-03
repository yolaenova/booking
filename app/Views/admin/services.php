<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
  <h1>Layanan Makeup</h1>
</div>

<a href="/services/create" class="btn btn-primary mb-3">+ Tambah Layanan</a>

<?php $services = $services ?? []; ?>
<div class="card">
  <div class="card-body">

    <table class="table table-striped" id="tableService">
      <thead>
        <tr>
          <th>#</th>
          <th>Foto</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Durasi</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody>
      <?php foreach($services as $i => $s): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td>
            <img src="/uploads/<?= $s['photo'] ?>" width="80">
          </td>
          <td><?= $s['name'] ?></td>
          <td>Rp <?= number_format($s['price']) ?></td>
          <td><?= $s['duration'] ?> menit</td>
          <td>
            <a href="/services/delete/<?= $s['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>

    </table>

  </div>
</div>

<script>
$(document).ready(function(){
    $('#tableService').DataTable();
});
</script>

<?= $this->endSection() ?>