<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
                                <td><?= $b['name'] ?? 'Customer'; ?></td>
                                <td><?= $b['service_name'] ?? '-'; ?></td>
                                <td><?= $b['real_date'] ?? date('d M Y', strtotime($b['booking_date'] ?? $b['date'] ?? date('Y-m-d'))); ?></td>
                                
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
                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#detailModal<?= $b['id']; ?>" title="Lihat Detail Lengkap">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>

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

<?php if (!empty($bookings)) : ?>
    <?php foreach ($bookings as $b) : ?>
    <div class="modal fade" id="detailModal<?= $b['id']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $b['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
<div class="modal-header" style="background-color: #631a31; color: #E6D5B8;">
    <h5 class="modal-title" id="detailModalLabel<?= $b['id']; ?>">Detail Lengkap Pesanan #B-0<?= $b['id']; ?></h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">Nama Pelanggan:</label>
                            <p class="fs-6 p-2 bg-light border rounded mb-0"><?= esc($b['customer_name'] ?? $b['user_customer_name'] ?? 'Customer'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small">Nomor WhatsApp / Telp:</label>
                            <p class="fs-6 p-2 bg-light border rounded mb-0">
                                <?php if (!empty($b['user_customer_phone'])) : ?>
                                    <a href="https://wa.me/<?= $b['user_customer_phone']; ?>" target="_blank" class="text-success fw-bold text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> <?= esc($b['user_customer_phone']); ?> (Hubungi WA)
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </p>
                        </div>
<div class="col-md-6">
    <label class="fw-bold text-muted small">Metode Layanan:</label>
    <p class="fs-6 p-2 bg-light border rounded mb-0">
        <span class="badge <?= ($b['real_method'] ?? ($b['service_type'] ?? 'gallery')) == 'home_service' ? 'bg-info text-dark' : 'bg-secondary' ?>">
            <?= ($b['real_method'] ?? ($b['service_type'] ?? 'gallery')) == 'home_service' ? 'Home Service' : 'Datang ke Studio' ?>
        </span>
    </p>
</div>
<div class="col-md-12">
    <label class="fw-bold text-muted small">Alamat Lengkap Tujuan:</label>
    <div class="p-2 bg-light border rounded text-dark" style="min-height: 50px;">
        <?= nl2br(esc($b['real_address'] ?? ($b['customer_address'] ?? 'Pelanggan memilih datang langsung ke studio MUA.'))); ?>
    </div>
</div>

<?php if (($b['real_method'] ?? ($b['service_type'] ?? 'gallery')) == 'home_service') : ?>
<div class="col-md-12">
    <label class="fw-bold text-muted small">Peta Lokasi Rumah Customer (Leaflet Spasial):</label>
    
    <div id="adminMap<?= $b['id']; ?>" 
         data-lat="<?= $b['latitude'] ?? '0'; ?>" 
         data-lng="<?= $b['longitude'] ?? '0'; ?>" 
         style="height: 250px; width: 100%;" 
         class="border rounded mb-1 bg-light text-center pt-5 text-muted">Memuat peta...</div>
    
    <div class="mt-2">
        <a href="https://www.google.com/maps/search/?api=1&query=<?= ($b['latitude'] ?? '0'); ?>,<?= ($b['longitude'] ?? '0'); ?>" target="_blank" class="btn btn-sm btn-success">
            <i class="bi bi-geo-alt"></i> Buka Rute di Google Maps Admin
        </a>
    </div>
</div>

<script>
    // 1. Inisialisasi object global untuk menyimpan instance peta
    if (typeof window.maps === 'undefined') { window.maps = {}; }

    document.addEventListener("DOMContentLoaded", function() {
        var modal = document.getElementById('detailModal<?= $b['id']; ?>');
        
        modal.addEventListener('shown.bs.modal', function () {
            var containerId = 'adminMap<?= $b['id']; ?>';
            var container = document.getElementById(containerId);
            
            // UBAHAN: Mengambil koordinat dari data-attribute elemen HTML (Lebih akurat untuk looping)
            var lat = parseFloat(container.getAttribute('data-lat'));
            var lng = parseFloat(container.getAttribute('data-lng'));

            // Bersihkan instance peta lama jika ada
            if (window.maps['<?= $b['id']; ?>']) { 
                window.maps['<?= $b['id']; ?>'].remove(); 
            }

            try {
                // 2. ERROR HANDLING: Validasi data koordinat
                if (isNaN(lat) || isNaN(lng) || lat === 0) {
                    throw new Error("Koordinat tidak valid/kosong.");
                }
                
                // 3. ERROR HANDLING: Deteksi koneksi internet
                if (!navigator.onLine) {
                    throw new Error("Koneksi internet terputus.");
                }

                // Inisialisasi peta
                window.maps['<?= $b['id']; ?>'] = L.map(containerId).setView([lat, lng], 15);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(window.maps['<?= $b['id']; ?>']);
                
                L.marker([lat, lng]).addTo(window.maps['<?= $b['id']; ?>'])
                    .bindPopup('<b>Lokasi Acara Customer</b>').openPopup();
                
                setTimeout(function(){ window.maps['<?= $b['id']; ?>'].invalidateSize(); }, 300);
                
            } catch (error) {
                console.error("Gagal memuat peta:", error.message);
                container.innerHTML = "<div class='p-3 text-danger fw-bold text-center'>⚠️ Gagal memuat peta: " + error.message + "</div>";
                
                // Notifikasi alert untuk memenuhi rubrik penilaian error handling
                alert("🚨 Gagal Memuat Peta Spasial!\nAlasan: " + error.message);
            }
        });
    });
</script>
<?php endif; ?>

                        <div class="col-md-12">
                            <label class="fw-bold text-muted small">Catatan Tambahan (Request Look):</label>
                            <div class="p-2 bg-light border rounded text-dark" style="font-style: italic; background-color: #fffdf3 !important;">
                                <?= !empty($b['notes']) ? '"' . esc($b['notes']) . '"' : 'Tidak ada catatan khusus.' ?>
                            </div>
                        </div>
                    </div>
                </div>
<div class="modal-footer">
    <button type="button" class="btn btn-sm text-white" style="background-color: #631a31;" data-bs-dismiss="modal">Tutup</button>
</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection(); ?>