<?= $this->extend('layouts/template'); // Menggunakan template utama agar sidebar tetap ada ?>

<?= $this->section('content'); ?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Integrasi WhatsApp Gateway</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Home</a></li>
                <li class="breadcrumb-item active">WhatsApp API</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-8">
                
                <div class="card info-card sales-card shadow-sm">
                    <div class="card-body py-4">
                        <h5 class="card-title">Status Koneksi API WAHA <span>| Real-time</span></h5>

                        <div class="alert alert-<?= ($waha_status == 'CONNECTED') ? 'success' : (($waha_status == 'OFFLINE') ? 'danger' : 'warning'); ?> d-flex align-items-center mb-4" role="alert">
                            <i class="bi <?= ($waha_status == 'CONNECTED') ? 'bi-check-circle-fill' : (($waha_status == 'OFFLINE') ? 'bi-exclamation-triangle-fill' : 'bi-exclamation-circle-fill'); ?> me-2 fs-5"></i>
                            <div>
                                Status Server WAHA Saat Ini: <strong class="badge bg-dark"><?= $waha_status; ?></strong>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="fw-bold text-secondary"><i class="bi bi-gear-fill me-1"></i> Spesifikasi Integrasi Webservice:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-2 bg-light">
                                    <tbody>
                                        <tr>
                                            <th width="35%" class="bg-light">Tipe Komponen</th>
                                            <td>Webservice Client & Server (Webhook API)</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Endpoint API Target</th>
                                            <td><code>http://localhost:3000/api/sessions/default</code></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Implementasi Sistem</th>
                                            <td>Kirim Notifikasi Otomatis (Booking Baru, Konfirmasi, & Cancel)</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Error Handling Context</th>
                                            <td><span class="badge bg-success"><i class="bi bi-shield-check"></i> Aktif (Try-Catch Protection)</span></td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Data Caching</th>
                                            <td><span class="badge bg-success"><i class="bi bi-speedometer2"></i> Aktif (CI4 Cache - 5 Menit)</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card border-start border-info border-3 bg-light mt-4 mb-2">
                            <div class="card-body py-3">
                                <h6 class="text-info fw-bold mb-1"><i class="bi bi-info-circle-fill me-1"></i> Catatan Arsitektur Sistem:</h6>
                                <p class="small text-muted mb-0">
                                    Fitur ini dikembangkan sebagai pengganti fungsional modul Kurs Mata Uang agar sistem penyediaan notifikasi berjalan lebih kontekstual dan mendukung proses bisnis utama aplikasi MUA Booking secara langsung.
                                </p>
                            </div>
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