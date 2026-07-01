<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<style>
    #map-wrapper {
        margin-top: 15px;
        display: none;
    }
    #map { 
        height: 320px; 
        min-height: 320px;
        width: 100%; 
        border-radius: 8px; 
        border: 2px solid #dee2e6;
    }
</style>

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
</div><section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Pemesanan: <?= $service['name'] ?></h5>

                    <form method="post" action="<?= base_url('booking/save'); ?>" class="row g-3">
                        <?= csrf_field(); ?>
                        
                        <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                        <input type="hidden" name="price" value="<?= $service['price'] ?>">

                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">

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
                            <select name="service_type" id="service_method" class="form-select" required onchange="toggleAddress()">
                                <option value="">-- Pilih Lokasi Makeup --</option>
                                <option value="gallery">Datang ke Studio MUA (Jl. Imam Bonjol No. 207, Semarang)</option>
                                <option value="home_service">Home Service (Staff MUA datang ke lokasi kamu)</option>
                            </select>
                        </div>

                        <div class="col-md-12" id="address_section" style="display:none;">
                            <label class="form-label text-bold">Alamat Lengkap Kamu</label>
                            <textarea class="form-control" name="customer_address" rows="3" placeholder="Masukkan alamat lengkap lokasi makeup..."></textarea>
                            <div class="form-text text-danger mb-2">*Biaya transportasi akan dihitung berdasarkan jarak ke lokasi.</div>
                            
                            <div id="map-wrapper">
                                <div class="alert alert-info py-2 mb-2" style="font-size: 0.9rem;">
                                    📍 <strong>Petunjuk:</strong> Geser pin merah di peta tepat ke titik lokasi rumah/acara kamu.
                                </div>
                                <div id="map"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label text-bold">Catatan Tambahan</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Contoh: Request look natural, jenis kulit sensitif, dll."></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success px-5">Konfirmasi Booking Sekarang</button>
                            <a href="<?= base_url('services-list'); ?>" class="btn btn-secondary px-5">Batal</a>
                        </div>

                    </form></div>
            </div>
        </div>
    </div>
</section>

<script>
    var map;
    var marker;
    var defaultLat = -6.9826; // Titik tengah Semarang (Simpang Lima)
    var defaultLng = 110.4093;

function toggleAddress() {
    var method = document.getElementById("service_method").value;
    var addressSection = document.getElementById("address_section");
    var mapWrapper = document.getElementById("map-wrapper");
    var textarea = addressSection.querySelector('textarea');
    
    if (method === "home_service") {
        addressSection.style.display = "block";
        mapWrapper.style.display = "block"; 
        textarea.required = true;

        if (!map) {
            map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            document.getElementById('latitude').value = defaultLat;
            document.getElementById('longitude').value = defaultLng;

            marker.on('dragend', function (e) {
                var coords = e.target.getLatLng();
                document.getElementById('latitude').value = coords.lat;
                document.getElementById('longitude').value = coords.lng;
            });

            // 🟢 BARU: Tambahkan Tombol Pencarian Alamat di Pojok Kanan Atas Peta
            var geocoder = L.Control.geocoder({
                defaultMarkGeocode: false, // Kita matikan marker bawaan geocoder biar tidak double
                placeholder: "Cari alamat/nama jalan di sini...",
                errorMessage: "Alamat tidak ditemukan."
            })
            .on('markgeocode', function(e) {
                var bbox = e.geocode.bbox;
                var center = e.geocode.center;

                // 1. Pindahkan pin merah ke lokasi hasil pencarian secara otomatis
                marker.setLatLng(center);

                // 2. Arahkan kamera peta fokus ke lokasi baru tersebut
                map.setView(center, 16); // Zoom lebih dekat skala 16 biar detail

                // 3. Masukkan koordinat baru ke input hidden MySQL
                document.getElementById('latitude').value = center.lat;
                document.getElementById('longitude').value = center.lng;
            })
            .addTo(map);

            setTimeout(function () { 
                map.invalidateSize(); 
            }, 300);

        } else {
            setTimeout(function () { 
                map.invalidateSize(); 
            }, 100);
        }

    } else {
        addressSection.style.display = "none";
        mapWrapper.style.display = "none"; 
        textarea.required = false;

        document.getElementById('latitude').value = '';
        document.getElementById('longitude').value = '';
    }
}

    // Error Handling saat Browser Offline (Syarat Poin 5 Hijau)
    window.addEventListener('offline', function() {
        alert('Koneksi internet Anda terputus. Pencarian alamat peta mungkin tidak berfungsi dengan maksimal.');
    });
</script>

<?= $this->endSection() ?>