<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="pagetitle">
        <h1>Pilihan Layanan Makeup</h1>
        <p>Silakan pilih layanan yang kamu inginkan, Najwa!</p>
    </div>

    <div class="row">
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $s) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Pastikan kolom gambar di database namanya 'image' -->
                        <img src="<?= base_url('assets/img/services/' . $s['photo']); ?>" class="card-img-top" alt="<?= $s['name']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $s['name']; ?></h5>
                            <p class="card-text text-muted"><?= $s['description']; ?></p>
                            <h6 class="text-primary">Harga: Rp <?= number_format($s['price'], 0, ',', '.'); ?></h6>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="<?= base_url('booking/' . $s['id']); ?>" class="btn btn-success w-100">Booking Sekarang</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <p class="alert alert-info">Maaf, saat ini belum ada layanan yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection(); ?>