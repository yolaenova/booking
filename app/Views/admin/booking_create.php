<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tambah Booking Manual</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin'); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('admin/bookings'); ?>">Data Booking</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Form Input Booking Baru</h5>

                        <form action="<?= base_url('admin/bookings/save'); ?>" method="post">
                            <?= csrf_field(); ?>

                            <div class="row mb-3">
                                <label for="customer_name" class="col-sm-3 col-form-label">Nama Pelanggan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Ketik nama pelanggan secara manual..." required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="service_id" class="col-sm-3 col-form-label">Layanan MUA</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="service_id" id="service_id" required>
                                        <option value="">-- Pilih Layanan --</option>
                                        <?php foreach ($services as $s) : ?>
                                            <option value="<?= $s['id']; ?>" data-price="<?= $s['price']; ?>">
                                                <?= $s['name']; ?> - Rp <?= number_format($s['price'], 0, ',', '.'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="booking_date" class="col-sm-3 col-form-label">Tanggal & Waktu</label>
                                <div class="col-sm-9">
                                    <input type="datetime-local" class="form-control" name="booking_date" id="booking_date" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="total_price" class="col-sm-3 col-form-label">Harga Total (Rp)</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" name="total_price" id="total_price" readonly required>
                                    <small class="text-muted">*Harga terisi otomatis berdasarkan layanan yang dipilih</small>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <a href="<?= base_url('admin/bookings'); ?>" class="btn btn-secondary btn-sm">Batal</a>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan Booking</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
    document.getElementById('service_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        
        if (price) {
            document.getElementById('total_price').value = price;
        } else {
            document.getElementById('total_price').value = '';
        }
    });
</script>

<?= $this->endSection(); ?>