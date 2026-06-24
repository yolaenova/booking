<?php

namespace App\Controllers;

use App\Models\ServiceModel;

class Customer extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        // Model ini yang akan membaca data dari tabel layanan Anda
        $this->serviceModel = new ServiceModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Customer'
        ];
        return view('customer/dashboard', $data);
    }

    // 1. TAMPILAN DAFTAR LAYANAN (Dinamis dari Database)
    public function services()
    {
        // Mengambil semua data layanan yang ada di database secara otomatis
        $services = $this->serviceModel->findAll();

        $data = [
            'title'    => 'Pilihan Layanan Makeup',
            'menu'     => 'layanancustomer',
            'services' => $services // Data ini akan otomatis bertambah jika Anda input di DB
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

    // 3. PROSES SIMPAN BOOKING
    public function saveBooking()
    {
        $bookingModel = new \App\Models\BookingModel();

        $serviceId = $this->request->getPost('service_id');
        $totalPrice = $this->request->getPost('price');
        
        $bookingMethod = $this->request->getPost('service_method') ?? $this->request->getPost('method') ?? 'studio';
        $userNotes = $this->request->getPost('notes') ? $this->request->getPost('notes') : 'No notes';

        $finalNotes = "[" . strtoupper($bookingMethod) . "] Catatan: " . $userNotes;

        $db = \Config\Database::connect();
        
        $checkService = $db->table('services')->where('id', $serviceId)->get()->getRowArray();
        $finalServiceId = $checkService ? $serviceId : 1;

        $anyUser = $db->table('users')->select('id')->orderBy('id', 'ASC')->get()->getRowArray();
        $validStaffId = $anyUser ? $anyUser['id'] : 1;

        $scheduleData = [
            'staff_id'   => $validStaffId, 
            'date'       => $this->request->getPost('booking_date') ? $this->request->getPost('booking_date') : date('Y-m-d'),
            'start_time' => $this->request->getPost('booking_time') ? $this->request->getPost('booking_time') : '09:00:00', 
            'end_time'   => date('H:i:s', strtotime(($this->request->getPost('booking_time') ?? '09:00:00') . ' + 2 hours')), 
            'capacity'   => 1
        ];
        $db->table('schedules')->insert($scheduleData);
        $scheduleId = $db->insertID(); 

        $dataBooking = [
            'user_id'        => $anyUser['id'] ?? 1, 
            'service_id'     => $finalServiceId, 
            'schedule_id'    => $scheduleId, 
            'notes'          => $finalNotes, 
            'total_price'    => $totalPrice,
            'booking_status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        if ($bookingModel->insert($dataBooking)) {
            return redirect()->to('/admin/bookings')->with('success', 'Booking kamu berhasil dikirim!');
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