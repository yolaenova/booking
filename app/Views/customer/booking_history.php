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
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal Pasang</th>
                            <th>Layanan</th>
                            <th>Waktu</th>
                            <th>Metode</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th> <!-- Tambah kolom aksi sesuai spesifikasi Poin 8 -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bookings)) : ?>
                            <?php foreach ($bookings as $b) : ?>
                            <tr>
                                <td><?= !empty($b['booking_date']) ? date('d M Y', strtotime($b['booking_date'])) : '-' ?></td>
                                
                                <td><strong><?= esc($b['service_name'] ?? 'Layanan Makeup') ?></strong></td>
                                
                                <td><?= $b['booking_time'] ?? '09:00 WIB' ?></td>
                                
                                <td>
                                    <?php 
                                        // Mempertahankan logika intip kolom notes bawaan untuk mendeteksi metode
                                        $notesCheck = isset($b['notes']) ? strtolower($b['notes']) : '';

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

                                <!-- Tombol Aksi Detail Premium -->
                                <td class="text-center">
                                    <a href="<?= base_url('booking/detail/' . $b['id']) ?>" class="btn btn-primary btn-sm px-3">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Kamu belum memiliki riwayat booking.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>