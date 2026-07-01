<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="pagetitle mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1>Detail Riwayat Booking</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('booking-history') ?>">Riwayat</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div>
    <a href="<?= base_url('booking-history') ?>" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom" style="border-color: #f1f1f1 !important;">
                        <div>
                            <h5 class="fw-bold text-primary m-0">MUA Elegance Booking</h5>
                            <small class="text-muted">ID Booking: #BKG-<?= str_pad($booking['booking_id'] ?? '000', 5, '0', STR_PAD_LEFT) ?></small>
                        </div>
                        <div>
                            <?php 
                            $status = $booking['booking_status'] ?? 'pending';
                            if ($status === 'confirmed' || $status === 'approved') : ?>
                                <span class="badge bg-success px-3 py-2 text-white"><i class="bi bi-check-circle me-1"></i> Dikonfirmasi</span>
                            <?php elseif ($status === 'completed') : ?>
                                <span class="badge bg-info px-3 py-2 text-white"><i class="bi bi-stars me-1"></i> Selesai</span>
                            <?php elseif ($status === 'canceled') : ?>
                                <span class="badge bg-danger px-3 py-2 text-white"><i class="bi bi-x-circle me-1"></i> Dibatalkan</span>
                            <?php else : ?>
                                <span class="badge bg-warning px-3 py-2 text-dark"><i class="bi bi-clock me-1"></i> Menunggu Konfirmasi</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary text-uppercase small">Detail Riasan / Layanan</h6>
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded" style="background-color: #FDFCF9 !important; border: 1px solid rgba(74, 21, 37, 0.05);">
                        <img src="<?= base_url('assets/img/services/' . ($booking['photo'] ?? 'default.jpg')) ?>" 
                             alt="Foto Layanan" 
                             class="rounded shadow-sm me-3" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <div>
                            <h6 class="fw-bold text-dark m-0 mb-1"><?= $booking['service_name'] ?? 'Custom Makeup' ?></h6>
                            <p class="text-muted small mb-0"><i class="bi bi-clock me-1"></i> Durasi Pengerjaan: <?= $booking['duration'] ?? '90' ?> Menit</p>
                        </div>
                    </div>

<h6 class="fw-bold mb-3 text-secondary text-uppercase small">Jadwal & Lokasi Rias</h6>
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <div class="p-3 border rounded h-100" style="background-color: #ffffff;">
                                <small class="text-muted d-block mb-1"><i class="bi bi-calendar-event me-1 text-primary"></i> Tanggal Acara</small>
                                <span class="fw-bold text-dark">
                                    <?php 
                                        // Cek booking_date hasil join, kalau kosong pakai created_at dari tabel bookings
                                        $tgl = !empty($booking['booking_date']) ? $booking['booking_date'] : ($booking['created_at'] ?? '');
                                        echo !empty($tgl) ? date('d F Y', strtotime($tgl)) : '-';
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 border rounded h-100" style="background-color: #ffffff;">
                                <small class="text-muted d-block mb-1"><i class="bi bi-alarm me-1 text-primary"></i> Waktu Mulai</small>
                                <span class="fw-bold text-dark">
                                    <?php 
                                        $jam = !empty($booking['booking_time']) ? $booking['booking_time'] : ($booking['created_at'] ?? '');
                                        echo !empty($jam) ? date('H:i', strtotime($jam)) . ' WIB' : '00:00 WIB';
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-2 text-secondary text-uppercase small">Catatan Tambahan Anda</h6>
                    <div class="p-3 rounded mb-2 bg-light text-muted small" style="min-height: 60px;">
                        <?= !empty($booking['notes']) ? esc($booking['notes']) : '<i>Tidak ada catatan khusus tambahan.</i>' ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title p-0 mb-3"><i class="bi bi-wallet2 me-2"></i>Rincian Biaya</h5>
                    
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Harga Paket Rias</span>
                        <span>Rp <?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Layanan Tambahan/Transport</span>
                        <span>Rp 0</span>
                    </div>
                    
                    <hr style="border-top: 1px dashed #ccc;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark">Total Bayar</span>
                        <span class="fw-bold text-primary fs-4">Rp <?= number_format($booking['total_price'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <small class="text-muted d-block text-end mb-0">Metode: Cash / Transfer Bank</small>
                </div>
            </div>

            <div class="card bg-success text-white border-0 shadow-sm" style="background: linear-gradient(135deg, #128C7E 0%, #075E54 100%);">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-2 text-white"><i class="bi bi-whatsapp me-2"></i>Butuh Bantuan?</h5>
                    <p class="small opacity-85 mb-3">Ada kendala jadwal atau ingin mengirim bukti transfer? Hubungi tim MUA kami sekarang.</p>
                    <a href="https://wa.me/6285601538952?text=Halo%20Admin%20MUA,%20saya%20ingin%20bertanya%20mengenai%20booking%20ID%20#BKG-<?= str_pad($booking['booking_id'] ?? '000', 5, '0', STR_PAD_LEFT) ?>" 
                       target="_blank" 
                       class="btn btn-light text-success fw-bold w-100 py-2 shadow-sm rounded-pill">
                        <i class="bi bi-chat-left-dots me-2"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>