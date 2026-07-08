<?php
$customers = $customers ?? [];
$services = $services ?? [];
$schedules = $schedules ?? [];
?>

<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Tambah Booking Manual</h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('admin'); ?>">Home</a>
                </li>

                <li class="breadcrumb-item">
                    <a href="<?= base_url('admin/bookings'); ?>">
                        Data Booking
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Tambah Booking
                </li>

            </ol>
        </nav>
    </div>

    <section class="section">

        <div class="row">

            <div class="col-lg-8">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <h5 class="card-title">
                            Form Booking Manual
                        </h5>

                        <form action="<?= base_url('admin/bookings/save'); ?>" method="post">

                            <?= csrf_field(); ?>

                            <!-- CUSTOMER -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">
                                    Customer
                                </label>

                                <div class="col-sm-9">

                                    <select class="form-select"
                                            name="user_id"
                                            required>

                                        <option value="">
                                            -- Pilih Customer --
                                        </option>

                                        <?php foreach($customers as $c): ?>

                                            <option value="<?= $c['id'] ?>">

                                                <?= $c['name'] ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <!-- LAYANAN -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">
                                    Layanan
                                </label>

                                <div class="col-sm-9">

                                    <select class="form-select"
                                            id="service_id"
                                            name="service_id"
                                            required>

                                        <option value="">
                                            -- Pilih Layanan --
                                        </option>

                                        <?php foreach($services as $s): ?>

                                            <option
                                                value="<?= $s['id']; ?>"
                                                data-price="<?= $s['price']; ?>">

                                                <?= $s['name']; ?>

                                                -

                                                Rp <?= number_format($s['price'],0,',','.'); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <!-- JADWAL -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">

                                    Jadwal

                                </label>

                                <div class="col-sm-9">

                                    <select class="form-select"
                                            name="schedule_id"
                                            required>

                                        <option value="">
                                            -- Pilih Jadwal --
                                        </option>

                                        <?php foreach($schedules as $j): ?>

                                            <option value="<?= $j['id']; ?>">

                                                <?= date('d M Y',strtotime($j['date'])) ?>

                                                -

                                                <?= substr($j['start_time'],0,5); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <!-- METODE -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">

                                    Metode

                                </label>

                                <div class="col-sm-9">

                                    <select class="form-select"
                                            id="service_type"
                                            name="service_type">

                                        <option value="gallery">

                                            Datang ke Studio

                                        </option>

                                        <option value="home_service">

                                            Home Service

                                        </option>

                                    </select>

                                </div>

                            </div>

                            <!-- ALAMAT -->

                            <div class="row mb-3 alamat-box"
                                 style="display:none;">

                                <label class="col-sm-3 col-form-label">

                                    Alamat

                                </label>

                                <div class="col-sm-9">

                                    <textarea
                                            class="form-control"
                                            name="customer_address"
                                            rows="3"></textarea>

                                </div>

                            </div>

                            <!-- LAT -->

                            <div class="row mb-3 koordinat-box"
                                 style="display:none;">

                                <label class="col-sm-3 col-form-label">

                                    Latitude

                                </label>

                                <div class="col-sm-9">

                                    <input
                                            type="text"
                                            class="form-control"
                                            name="latitude">

                                </div>

                            </div>

                            <!-- LNG -->

                            <div class="row mb-3 koordinat-box"
                                 style="display:none;">

                                <label class="col-sm-3 col-form-label">

                                    Longitude

                                </label>

                                <div class="col-sm-9">

                                    <input
                                            type="text"
                                            class="form-control"
                                            name="longitude">

                                </div>

                            </div>

                            <!-- HARGA -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">

                                    Total Harga

                                </label>

                                <div class="col-sm-9">

                                    <input
                                            type="number"
                                            class="form-control"
                                            id="total_price"
                                            name="total_price"
                                            readonly
                                            required>

                                    <small class="text-muted">

                                        Harga otomatis mengikuti layanan

                                    </small>

                                </div>

                            </div>

                            <!-- CATATAN -->

                            <div class="row mb-3">

                                <label class="col-sm-3 col-form-label">

                                    Catatan

                                </label>

                                <div class="col-sm-9">

                                    <textarea
                                            class="form-control"
                                            name="notes"
                                            rows="4"></textarea>

                                </div>

                            </div>

                            <div class="text-end">

                                <a href="<?= base_url('admin/bookings'); ?>"
                                   class="btn btn-secondary">

                                    Batal

                                </a>

                                <button
                                        type="submit"
                                        class="btn btn-primary">

                                    Simpan Booking

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<script>

document.getElementById('service_id').addEventListener('change',function(){

    let option=this.options[this.selectedIndex];

    let price=option.getAttribute('data-price');

    document.getElementById('total_price').value=price??'';

});

document.getElementById('service_type').addEventListener('change',function(){

    let home=this.value==="home_service";

    document.querySelector('.alamat-box').style.display=
        home?"flex":"none";

    document.querySelectorAll('.koordinat-box').forEach(function(item){

        item.style.display=home?"flex":"none";

    });

});

</script>

<?= $this->endSection(); ?>