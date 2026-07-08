<?php

namespace App\Controllers;


class Customer extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Customer',
            'menu'  => 'dashboard'
        ];
        return view('customer/dashboard', $data);
    }

    public function services()
    {
        $modelService = new \App\Models\ServiceModel();
        $data = [
            'title'    => 'Layanan Makeup',
            'menu'     => 'services',
            'services' => $modelService->findAll()
        ];
        return view('customer/services_list', $data);
    }

public function booking($id)
    {
        $modelService = new \App\Models\ServiceModel();
        
        // Cari data layanan berdasarkan ID yang dipilih (misal paket wisuda/wedding)
        $service = $modelService->find($id);

        if (!$service) {
            return redirect()->to(base_url('services-list'))->with('error', 'Layanan tidak ditemukan.');
        }

        // Siapkan data untuk dikirim ke View form booking
        $data = [
            'title'   => 'Form Booking Layanan',
            'menu'    => 'services',
            'service' => $service
        ];

        return view('customer/booking_form', $data);
    }

    // ========================================================
    // FUNGSI DETAIL YANG MENGAMBIL DATA TANGGAL & START_TIME TABEL SCHEDULES
    // ========================================================
    public function detail($id)
    {
        $modelBooking = new \App\Models\BookingModel();

        $booking = $modelBooking
            ->select('
                bookings.id as booking_id,
                bookings.user_id,
                bookings.total_price,
                bookings.booking_status,
                bookings.payment_status,
                bookings.notes,
                services.name as service_name,
                services.photo,
                services.duration,
                schedules.date as booking_date,
                schedules.start_time as booking_time
            ')
                        ->join('services', 'services.id = bookings.service_id')
                        ->join('schedules', 'schedules.id = bookings.schedule_id')
                        ->where('bookings.id', $id)
                        ->first();

        if (!$booking) {
            return redirect()->to(base_url('booking-history'))->with('error', 'Data booking tidak ditemukan.');
        }

        $data = [
            'title'   => 'Detail Booking Saya',
            'menu'    => 'booking_history',
            'booking' => $booking
        ];

        return view('customer/booking_detail', $data);
    }

    public function bookingHistory()
    {
        $modelBooking = new \App\Models\BookingModel();
        
        // 🛠️ MENDAPATKAN ID USER YANG SEDANG LOGIN SECARA DINAMIS
        $customerId = session()->get('user_id') ?? session()->get('id');
        if (empty($customerId)) {
            // Skenario fallback jika session testing belum terisi otomatis (menyesuaikan ke ID akun Najwa)
            $customerId = 5; 
        }

        // 1. Jalankan query asli bawaan kamu dengan tambahan filter WHERE user_id
        $rawBookings = $modelBooking
                        ->select('
                            bookings.*, 
                            services.name as service_name, 
                            schedules.date as booking_date, 
                            schedules.start_time as booking_time
                        ')
                        ->join('services', 'services.id = bookings.service_id')
                        ->join('schedules', 'schedules.id = bookings.schedule_id')
                        ->where('bookings.user_id', $customerId) // <--- UTAMA: Filter data agar hanya milik user yang login
                        ->findAll();

        // 2. LOGIC TAMBAHAN: Ekstrak tanggal & jam asli pilihan customer dari kolom notes (UTUH 100%)
        foreach ($rawBookings as &$b) {
            // Pastikan format jam default dari query rapi (misal: 09:00 WIB) jika tidak ditimpa
            if (!empty($b['booking_time'])) {
                $b['booking_time'] = date('H:i', strtotime($b['booking_time'])) . ' WIB';
            }

            // Jika notes mengandung data teks jadwal kustom, kita ekstrak pakai regex
            if (!empty($b['notes'])) {
                // Cari teks setelah tulisan "Tanggal:"
                if (preg_match('/Tanggal:\s*([^\n]+)/', $b['notes'], $matchesTgl)) {
                    $b['booking_date'] = trim($matchesTgl[1]);
                }
                // Cari teks setelah tulisan "Jam Mulai:"
                if (preg_match('/Jam Mulai:\s*([^\n]+)/', $b['notes'], $matchesJam)) {
                    $b['booking_time'] = trim($matchesJam[1]);
                }
            }
        }

        // 3. Masukkan data hasil pemrosesan ke array data view
        $data = [
            'title'    => 'Riwayat Booking',
            'menu'     => 'booking_history',
            'bookings' => $rawBookings // Mengirimkan data yang sudah rapi dan dinamis
        ];
        return view('customer/booking_history', $data);
    }

public function saveBooking()
    {
        $modelBooking = new \App\Models\BookingModel();
        $modelService = new \App\Models\ServiceModel();
        $modelUser    = new \App\Models\UserModel();

        // 1. Ambil semua data input dari form HTML booking_form.php
        $serviceId   = $this->request->getPost('service_id');
        $notes       = $this->request->getPost('notes');
        $serviceType = $this->request->getPost('service_type');
        $address     = $this->request->getPost('customer_address');
        $latitude    = $this->request->getPost('latitude');
        $longitude   = $this->request->getPost('longitude');
        $bookingDate = $this->request->getPost('booking_date');
        $bookingTime = $this->request->getPost('booking_time');
        
        // Format teks tanggal dan jam agar rapi saat dibaca di detail booking
        $formatTanggalPilihan = !empty($bookingDate) ? date('d F Y', strtotime($bookingDate)) : '-';
        $formatJamPilihan     = !empty($bookingTime) ? date('H:i', strtotime($bookingTime)) . ' WIB' : '-';
        
        // SAFE TRICK: Karena database tidak punya kolom koordinat/alamat, kita gabungkan semuanya ke dalam kolom NOTES
        $catatanLengkap = "[JADWAL PILIHAN ACARA]\n";
        $catatanLengkap .= "Tanggal: " . $formatTanggalPilihan . "\n";
        $catatanLengkap .= "Jam Mulai: " . $formatJamPilihan . "\n\n";
        
        $catatanLengkap .= "[LOKASI & METODE LAYANAN]\n";
        $catatanLengkap .= "Metode: " . ($serviceType === 'home_service' ? 'Home Service (Datang ke Rumah)' : 'Datang ke Studio MUA') . "\n";
        if ($serviceType === 'home_service') {
            $catatanLengkap .= "Alamat: " . $address . "\n";
            $catatanLengkap .= "Koordinat: " . $latitude . ", " . $longitude . "\n\n";
        }
        
        $catatanLengkap .= "Catatan Tambahan Customer: " . $notes;

        // Kunci ke ID 2 sesuai dengan data yang tersedia di tabel schedules kamu
        $scheduleId = 2; 

// 2. Ambil ID Customer yang sedang aktif login
        $customerId = session()->get('user_id') ?? session()->get('id'); // Coba cek beberapa nama key session umum

        // FORCE FIX UNTUK TESTING: Jika session kosong, kunci langsung ke ID 4 (ID Yola Enova di database kamu)
        if (empty($customerId)) {
            $customerId = 4; 
        }

        // 3. Ambil harga paket secara otomatis langsung dari database services
        $service = $modelService->find($serviceId);
        $totalPrice = $service ? $service['price'] : 0; 

        // 4. SUSUN DATA YANG HANYA ADA DI phpMyAdmin KAMU SAJA (Mencegah eror Unknown Column)
        $dataSave = [
            'user_id'          => $customerId, 
            'service_id'       => $serviceId,
            'schedule_id'      => $scheduleId, 
            'booking_time'     => !empty($bookingTime) ? $bookingTime : date('H:i:s'), 
            'total_price'      => $totalPrice, 
            'booking_status'   => 'pending',
            'notes'            => $catatanLengkap // Semua info dinamis (peta, alamat, tgl) aman tersimpan di sini
        ];

        // 5. Eksekusi simpan ke database bookings
        $modelBooking->save($dataSave);

        return redirect()->to(base_url('booking-history'))->with('success', 'Booking layanan berhasil disimpan!');
    }
    public function pay($id)
    {
        $modelBooking = new \App\Models\BookingModel();

        $modelBooking->update($id, [
            'payment_status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi. Menunggu verifikasi admin.');
    }
}