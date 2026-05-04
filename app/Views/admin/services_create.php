<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Tambah Layanan</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Input Layanan</h5>

                    <form action="/services/save" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Nama Layanan</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Harga (Rp)</label>
                            <div class="col-sm-9">
                                <input type="number" name="price" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Durasi (Menit)</label>
                            <div class="col-sm-9">
                                <input type="number" name="duration" class="form-control" placeholder="Contoh: 60">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select name="status" class="form-select">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non-Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Foto Layanan</label>
                            <div class="col-sm-9">
                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="/services" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>