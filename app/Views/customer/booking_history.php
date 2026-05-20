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
                            <th>Layanan</th>
                            <th>Waktu</th>
                            <th>Metode</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)) : ?>
                            <?php foreach ($bookings as $b) : ?>
                            <tr>
                                <td><?= !empty($b['booking_date']) ? date('d M Y', strtotime($b['booking_date'])) : '-' ?></td>
                                
                                <td><strong><?= esc($b['service_name'] ?? 'Layanan Makeup') ?></strong></td>
                                
                                <td><?= !empty($b['booking_time']) ? date('H:i', strtotime($b['booking_time'])) : '00:00' ?> WIB</td>
                                
                                <td>
                                    <?php 
                                        // Kita intip kolom notes untuk mendeteksi metodenya
                                        $notesCheck = isset($b['notes']) ? strtolower($b['notes']) : '';

                                        // Jika di dalam catatan mengandung kata 'home_service' atau 'home service'
                                        if (strpos($notesCheck, 'home') !== false) {
                                            echo '<span class="badge bg-info text-dark">Home Service</span>';
                                        } else {
                                            echo 'Datang ke Studio';
                                        }
                                    ?>
                                </td>

                                <td>Rp <?= number_format($b['total_price'] ?? 0, 0, ',', '.') ?></td>
                                
                                <td>
                                    <?php $status = $b['booking_status'] ?? 'pending'; ?>
                                    <?php if ($status == 'pending') : ?>
                                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                    <?php elseif ($status == 'confirmed') : ?>
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Kamu belum memiliki riwayat booking.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>