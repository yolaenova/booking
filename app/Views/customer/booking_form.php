<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<?php $service = $service ?? []; ?>

<div class="pagetitle">
    <h1>Booking Layanan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('services-list') ?>">Layanan</a></li>
            <li class="breadcrumb-item active">Form Booking</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Pemesanan: <?= $service['name'] ?></h5>

                    <!-- Form Booking -->
                    <form method="post" action="<?= base_url('booking/save'); ?>" class="row g-3">
                        <?= csrf_field(); ?>
                        
                        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                        <input type="hidden" name="price" value="<?= $service['price'] ?>">

                        <div class="col-md-6">
                            <label class="form-label text-bold">Nama Layanan</label>
                            <input type="text" class="form-control bg-light" value="<?= $service['name'] ?>" readonly>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-bold">Harga</label>
                            <input type="text" class="form-control bg-light" value="Rp <?= number_format($service['price']) ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-bold">Tanggal Booking</label>
                            <input type="date" name="booking_date" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-bold">Waktu/Jam</label>
                            <input type="time" name="booking_time" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-bold">Metode Layanan</label>
                            <select name="service_method" id="service_method" class="form-select" required onchange="toggleAddress()">
                                <option value="">-- Pilih Lokasi Makeup --</option>
                                <option value="studio">Datang ke Studio MUA (Jl. Imam Bonjol No. 207, Semarang)</option>
                                <option value="home_service">Home Service (Staff MUA datang ke lokasi kamu)</option>
                            </select>
                        </div>

                        <!-- Bagian Alamat: Muncul secara dinamis -->
                        <div class="col-md-12" id="address_section" style="display:none;">
                            <label class="form-label text-bold">Alamat Lengkap Kamu</label>
                            <textarea class="form-control" name="customer_address" rows="3" placeholder="Masukkan alamat lengkap lokasi makeup..."></textarea>
                            <div class="form-text text-danger">*Biaya transportasi akan dihitung berdasarkan jarak ke lokasi.</div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-bold">Catatan Tambahan</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Contoh: Request look natural, jenis kulit sensitif, dll."></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-5">Konfirmasi Booking Sekarang</button>
                            <a href="<?= base_url('services-list'); ?>" class="btn btn-secondary px-5">Batal</a>
                        </div>

                    </form><!-- End Form Booking -->

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function toggleAddress() {
        var method = document.getElementById("service_method").value;
        var addressSection = document.getElementById("address_section");
        var textarea = addressSection.querySelector('textarea');
        
        if (method === "home_service") {
            addressSection.style.display = "block";
            textarea.required = true;
        } else {
            addressSection.style.display = "none";
            textarea.required = false;
        }
    }
</script>

<?= $this->endSection() ?>