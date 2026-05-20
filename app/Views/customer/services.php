<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Pilihan Layanan Makeup</h1>
</div>

<section class="section">
    <div class="row">
        <?php if (!empty($services) && count($services) > 0) : ?>
            <?php foreach ($services as $s) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body mt-3">
                            <h5 class="card-title text-primary p-0 m-0 mb-2"><?= $s['name'] ?></h5>
                            <p class="card-text text-muted" style="font-size: 14px; min-height: 60px;">
                                <?= $s['description'] ?>
                            </p>
                            <h6 class="font-weight-bold text-success mb-3" style="font-size: 18px;">
                                Rp <?= number_format($s['price'], 0, ',', '.') ?>
                            </h6>
                            
                            <a href="<?= base_url('booking/' . $s['id']) ?>" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-check"></i> Pilih Layanan
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Maaf, saat ini belum ada layanan yang tersedia.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>