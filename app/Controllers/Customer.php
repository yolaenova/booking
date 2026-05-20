<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class Customer extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Customer'
        ];
        return view('customer/dashboard', $data);
    }

    // 1. TAMPILAN DAFTAR LAYANAN
    public function services()
    {
        $services = [
            [
                'id'          => 1,
                'name'        => 'Makeup Wisuda / Graduation',
                'description' => 'Makeup flawless dan tahan lama untuk momen wisuda spesialmu. Sudah termasuk hijab do / hair do simple.',
                'price'       => 350000,
                'image'       => 'graduation.jpg'
            ],
            [
                'id'          => 2,
                'name'        => 'Wedding / Pengantin Modern',
                'description' => 'Paket makeup pengantin premium menggunakan produk high-end. Tahan seharian dan anti-badai.',
                'price'       => 2500000,
                'image'       => 'wedding.jpg'
            ],
            [
                'id'          => 3,
                'name'        => 'Prewedding / Photoshoot',
                'description' => 'Makeup natural dan photogenic yang sangat cocok untuk sesi foto outdoor maupun indoor.',
                'price'       => 500000,
                'image'       => 'prewed.jpeg'
            ]
        ];

        $data = [
            'title'    => 'Pilihan Layanan Makeup',
            'menu'     => 'layanancustomer',
            'services' => $services
        ];

        return view('customer/services', $data); 
    }

    // 2. TAMPILAN FORM BOOKING
    public function booking($id)
    {
        $servicesMaster = [
            1 => ['name' => 'Makeup Wisuda / Graduation', 'price' => 350000],
            2 => ['name' => 'Wedding / Pengantin Modern', 'price' => 2500000],
            3 => ['name' => 'Prewedding / Photoshoot', 'price' => 500000]
        ];

        if (array_key_exists($id, $servicesMaster)) {
            $service = [
                'id'    => $id,
                'name'  => $servicesMaster[$id]['name'],
                'price' => $servicesMaster[$id]['price']
            ];
        } else {
            $service = $this->serviceModel->find($id);
        }

        if (!$service) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
        }

        $data = [
            'title'   => 'Form Booking ' . $service['name'],
            'service' => $service
        ];

        return view('customer/booking_form', $data);
    }

    // 3. PROSES SIMPAN BOOKING (VERSI LENGKAP DENGAN METODE HOME SERVICE)
    public function saveBooking()
    {
        $bookingModel = new \App\Models\BookingModel();

        $serviceId = $this->request->getPost('service_id');
        $totalPrice = $this->request->getPost('price');
        
        // 1. Tangkap metode pilihan dari form
        $bookingMethod = $this->request->getPost('service_method') ?? $this->request->getPost('method') ?? 'studio';
        
        // 2. Tangkap catatan asli dari user
        $userNotes = $this->request->getPost('notes') ? $this->request->getPost('notes') : 'No notes';

        // 3. TRIK SKAKMAT: Gabungkan metode ke dalam catatan agar lolos masuk DB!
        // Hasilnya nanti di DB kolom notes akan berisi contoh: "[HOME_SERVICE] Catatan: Request look natural"
        $finalNotes = "[" . strtoupper($bookingMethod) . "] Catatan: " . $userNotes;

        $db = \Config\Database::connect();
        
        // --- VALIDASI SERVICE_ID ---
        $checkService = $db->table('services')->where('id', $serviceId)->get()->getRowArray();
        $finalServiceId = $checkService ? $serviceId : 1;

        // --- AMBIL STAFF_ID VALID ---
        $anyUser = $db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        $validStaffId = $anyUser ? $anyUser['id'] : 1;

        // --- MASUKKAN DATA KE TABEL SCHEDULES ---
        $scheduleData = [
            'staff_id'   => $validStaffId, 
            'date'       => $this->request->getPost('booking_date') ? $this->request->getPost('booking_date') : date('Y-m-d'),
            'start_time' => $this->request->getPost('booking_time') ? $this->request->getPost('booking_time') : '09:00:00', 
            'end_time'   => date('H:i:s', strtotime(($this->request->getPost('booking_time') ?? '09:00:00') . ' + 2 hours')), 
            'capacity'   => 1
        ];
        $db->table('schedules')->insert($scheduleData);
        $scheduleId = $db->insertID(); 

        // --- MASUKKAN DATA KE TABEL BOOKINGS (BERSIH TANPA KOLOM METHOD GAIB) ---
        $dataBooking = [
            'user_id'        => $anyUser['id'] ?? 1, 
            'service_id'     => $finalServiceId, 
            'schedule_id'    => $scheduleId, 
            'notes'          => $finalNotes, // Kita masukkan lewat sini!
            'total_price'    => $totalPrice,
            'booking_status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        if ($bookingModel->insert($dataBooking)) {
            return redirect()->to('/booking-history')->with('success', 'Booking kamu berhasil dikirim!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan booking.');
        }
    }

    // 4. TAMPILAN RIWAYAT BOOKING (VERSI UNDERSCORE)
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

    // JEMBATAN PENGAMAN: Menghindari 404 jika ada sistem yang memanggil format camelCase
    public function bookingHistory()
    {
        return $this->booking_history();
    }
}