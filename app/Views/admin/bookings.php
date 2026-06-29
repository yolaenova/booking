<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<div class="pagetitle">
    <h1>Data Booking</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title">Booking List</h5>
                <a href="<?= base_url('admin/bookings/create'); ?>" class="btn btn-primary btn-sm">+ Tambah Booking</a>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Layanan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data booking. Klik tombol tambah untuk mengisi data booking secara manual.</td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1; foreach ($bookings as $b) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $b['customer_name'] ?? $b['user_customer_name'] ?? 'Customer'; ?></td>
                                <td><?= $b['service_name'] ?? '-'; ?></td>
                                <td><?= date('d M Y', strtotime($b['booking_date'] ?? $b['date'] ?? date('Y-m-d'))); ?></td>
                                
                                <td>
                                    <?php $status = $b['booking_status'] ?? 'pending'; ?>

                                    <?php if ($status == 'pending') : ?>
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    <?php elseif ($status == 'confirmed' || $status == 'process') : ?>
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    <?php elseif ($status == 'cancelled' || $status == 'cancel') : ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php elseif ($status == 'done') : ?>
                                        <span class="badge bg-primary">Selesai</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Status kosong</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (($b['booking_status'] ?? 'pending') == 'pending') : ?>
                                        <a href="<?= base_url('admin/bookings/confirm/' . $b['id']); ?>" 
                                           class="btn btn-success btn-sm"
                                           title="Konfirmasi Booking"
                                           onclick="return confirm('Konfirmasi booking ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </a>

                                        <a href="<?= base_url('admin/bookings/cancel/' . $b['id']); ?>" 
                                           class="btn btn-warning btn-sm"
                                           title="Tolak Booking"
                                           onclick="return confirm('Tolak booking ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= base_url('admin/bookings/delete/' . $b['id']); ?>" 
                                       class="btn btn-danger btn-sm"
                                       title="Hapus Data"
                                       onclick="return confirm('Hapus booking ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>