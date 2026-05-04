<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Riwayat Booking</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Daftar Pesanan Kamu</h5>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Pasang</th>
                            <th>Waktu</th>
                            <th>Metode</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b) : ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($b['booking_date'])) ?></td>
                            <td><?= $b['booking_time'] ?></td>
                            <td><?= ($b['service_method'] == 'studio') ? 'Datang ke Studio' : 'Home Service' ?></td>
                            <td>Rp <?= number_format($b['total_price']) ?></td>
                            <td>
                                <?php if ($b['booking_status'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                <?php elseif ($b['booking_status'] == 'confirmed') : ?>
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($bookings)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">Kamu belum memiliki riwayat booking.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>