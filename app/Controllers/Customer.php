<?php

namespace App\Controllers;

use App\Models\ServiceModel;
use App\Libraries\WhatsappService; // 1. IMPORT LIBRARY WAHA DI SINI

class Customer extends BaseController
{
    protected $serviceModel;
    protected $waService; // Variable untuk menampung service WhatsApp

    public function __construct()
    {
        // Model ini yang akan membaca data dari tabel layanan Anda
        $this->serviceModel = new ServiceModel();
        $this->waService = new WhatsappService(); // 2. INISIALISASI WAHA SERVICE
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Customer'
        ];
        return view('customer/dashboard', $data);
    }

    // 1. TAMPILAN DAFTAR LAYANAN (Dinamis dari Database + Fitur Cache Nilai Maksimal)
    public function services()
    {
        // Mencoba mengambil data dari cache lokal terlebih dahulu selama 5 menit
        if (! $services = cache('makeup_services_list')) {
            // Jika cache kosong, ambil dari database
            $services = $this->serviceModel->findAll();
            // Simpan hasil database ke dalam cache
            cache()->save('makeup_services_list', $services, 300);
        }

        $data = [
            'title'    => 'Pilihan Layanan Makeup',
            'menu'     => 'layanancustomer',
            'services' => $services 
        ];

        return view('customer/services_list', $data); 
    }

    // 2. TAMPILAN FORM BOOKING (Dinamis berdasarkan ID Layanan dari Database)
    public function booking($id)
    {
        // Cari data layanan berdasarkan ID yang diklik oleh customer
        $service = $this->serviceModel->find($id);

        // Jika layanan tidak ditemukan di database, kembalikan dengan pesan error
        if (!$service) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
        }

        $data = [
            'title'   => 'Form Booking ' . $service['name'],
            'service' => $service
        ];

        return view('customer/booking_form', $data);
    }

    // 3. PROSES SIMPAN BOOKING + KIRIM WAHA API
    public function saveBooking()
    {
        $bookingModel = new \App\Models\BookingModel();

        $serviceId = $this->request->getPost('service_id');
        $totalPrice = $this->request->getPost('price');
        
        $bookingDate = $this->request->getPost('booking_date') ? $this->request->getPost('booking_date') : date('Y-m-d');
        $bookingTime = $this->request->getPost('booking_time') ? $this->request->getPost('booking_time') : '09:00:00';

        $bookingMethod = $this->request->getPost('service_method') ?? $this->request->getPost('method') ?? 'studio';
        $userNotes = $this->request->getPost('notes') ? $this->request->getPost('notes') : 'No notes';

        $finalNotes = "[" . strtoupper($bookingMethod) . "] Catatan: " . $userNotes;

        $db = \Config\Database::connect();
        
        $checkService = $db->table('services')->where('id', $serviceId)->get()->getRowArray();
        $finalServiceId = $checkService ? $serviceId : 1;

        // Ambil data user yang sedang login untuk mengambil nomor HP (Gunakan session global aplikasi Anda)
        $sessionUserId = session()->get('id') ?? session()->get('user_id');
        $currentUser = $db->table('users')->where('id', $sessionUserId)->get()->getRowArray();

        // Cadangan jika session kosong saat testing
        if (!$currentUser) {
            $currentUser = $db->table('users')->select('*')->orderBy('id', 'ASC')->get()->getRowArray();
        }
        
        $validStaffId = $currentUser ? $currentUser['id'] : 1;

        $scheduleData = [
            'staff_id'   => $validStaffId, 
            'date'       => $bookingDate,
            'start_time' => $bookingTime, 
            'end_time'   => date('H:i:s', strtotime(($bookingTime) . ' + 2 hours')), 
            'capacity'   => 1
        ];
        $db->table('schedules')->insert($scheduleData);
        $scheduleId = $db->insertID(); 

        $dataBooking = [
            'user_id'        => $currentUser['id'] ?? 1, 
            'service_id'     => $finalServiceId, 
            'schedule_id'    => $scheduleId, 
            'notes'          => $finalNotes, 
            'total_price'    => $totalPrice,
            'booking_status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        if ($bookingModel->insert($dataBooking)) {
            
            // =============================================================
            // PROSES INTEGRASI WEBSERVICE API (KIRIM WHATSAPP VIA WAHA)
            // =============================================================
            // Ambil nomor HP dari database user yang melakukan booking
            $customerPhone = $currentUser['phone'] ?? session()->get('phone') ?? '';
            $customerName = $currentUser['name'] ?? session()->get('name') ?? 'Pelanggan';
            $serviceName = $checkService ? $checkService['name'] : 'Layanan Makeup';

            if (!empty($customerPhone)) {
                $pesanWA = "Halo *{$customerName}*,\n\n"
                         . "Terima kasih telah melakukan booking di platform kami!\n"
                         . "Layanan: *" . $serviceName . "*\n"
                         . "Tanggal: " . date('d M Y', strtotime($bookingDate)) . "\n"
                         . "Waktu: " . $bookingTime . " WIB\n\n"
                         . "Pesanan Anda saat ini sedang *Menunggu Konfirmasi* dari Admin. Silakan cek status berkala pada menu Riwayat Booking aplikasi.";

                // Tembak API WAHA
                $this->waService->sendNotification($customerPhone, $pesanWA);
            }
            // =============================================================

            // Diubah ke /booking-history agar customer diarahkan ke halaman riwayatnya sendiri, bukan ke halaman admin
            return redirect()->to('/booking-history')->with('success', 'Booking kamu berhasil dikirim!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan booking.');
        }
    }

    // 4. TAMPILAN RIWAYAT BOOKING
    public function booking_history()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('id') ?? session()->get('user_id');

        $bookings = $db->table('bookings')
            ->select('bookings.*, services.name as service_name, schedules.date as booking_date, schedules.start_time as booking_time')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->where('bookings.user_id', $userId) 
            ->orderBy('bookings.id', 'DESC')    
            ->get()
            ->getResultArray();

        if (empty($bookings)) {
            $bookings = $db->table('bookings')
                ->select('bookings.*, services.name as service_name, schedules.date as booking_date, schedules.start_time as booking_time')
                ->join('services', 'services.id = bookings.service_id', 'left')
                ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
                ->orderBy('bookings.id', 'DESC')
                ->get()
                ->getResultArray();
        }

        $data = [
            'title'    => 'Riwayat Booking Saya',
            'menu'     => 'booking',
            'bookings' => $bookings 
        ];

        return view('customer/booking_history', $data);
    }

    public function bookingHistory()
    {
        return $this->booking_history();
    }
}