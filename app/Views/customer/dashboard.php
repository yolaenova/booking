<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle mb-4">
    <h1>Dashboard Customer</h1>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8">
            
            <div class="card p-4 text-white" style="background: linear-gradient(135deg, #4A1525 0%, #2A0812 100%); border-left: 5px solid #E6D5B8 !important;">
                <h3 class="fw-bold mb-1" style="color: #E6D5B8;">Halo, <?= session('name') ?> ! </h3>
                <p class="mb-0 opacity-75">Selamat datang di Booking Makeup Artist. Temukan dan atur jadwal riasan terbaik untuk momen spesialmu bersama kami.</p>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title p-0 mb-3">
                        <i class="bi bi-calendar-heart me-2"></i>Booking Makeup Sekarang
                    </h5>
                    <p class="text-muted">Jelajahi berbagai macam pilihan tipe riasan, mulai dari Graduation, Engagement, hingga Traditional Wedding Look.</p>
                    
                    <a href="<?= base_url('services-list'); ?>" class="btn btn-primary px-4 py-2 mt-2">
                        <i class="bi bi-eye me-2"></i>Lihat Katalog Layanan
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>