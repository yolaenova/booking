<?= $this->extend('layouts/template'); // Menggunakan template utama agar sidebar tetap ada ?>

<?= $this->section('content'); ?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Kurs Global</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Home</a></li>
                <li class="breadcrumb-item active">Kurs Global</li>
            </ol>
        </nav>
    </div><section class="section dashboard">
        <div class="row">
            <div class="col-lg-6">
                
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <?= session()->getFlashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card info-card sales-card shadow-sm">
                    <div class="card-body py-4">
                        <h5 class="card-title">Nilai Tukar Mata Uang <span>| Real-time</span></h5>

                        <div class="d-flex align-items-center justify-content-center flex-column py-3">
                            <p class="text-muted mb-1">1 United States Dollar (USD) setara dengan:</p>
                            <h2 class="display-5 fw-bold text-success my-2">
                                Rp <?= number_format($kurs_usd, 0, ',', '.'); ?>
                            </h2>
                            <span class="badge bg-info text-dark mt-2 p-2">
                                <i class="bi bi-cpu me-1"></i> Sistem Cache Aktif (1 Jam)
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 text-center pb-4">
                        <a href="<?= base_url('admin'); ?>" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>
<?= $this->endSection(); ?>