<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ServiceModel; 
use App\Models\UserModel;
use App\Libraries\WhatsappService; 
use App\Models\ScheduleModel;

class Booking extends BaseController
{
    protected $bookingModel;
    protected $waService;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->waService = new WhatsappService(); 
    }

// Menampilkan semua data booking di panel Admin
   public function index()
    {
        // 1. Jalankan query database asli milikmu
        $bookings = $this->bookingModel
            ->select('bookings.*, users.name AS user_customer_name, users.phone AS user_customer_phone, services.name AS service_name, schedules.date as booking_date, schedules.start_time as booking_time')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->orderBy('bookings.id', 'DESC')
            ->findAll();

        // 2. 🧠 LOGIC EKSTRAKSI PINTAR (Kunci Sinkronisasi Nama & Variabel)
        foreach ($bookings as &$b) {
            // Sinkronisasi nama & phone agar dibaca dengan benar oleh file view bookings.php kamu
            $b['customer_name']      = $b['user_customer_name'] ?? $b['customer_name'] ?? 'Customer';
            $b['user_customer_name'] = $b['customer_name']; 
            $b['name']               = $b['customer_name'];
            
            $b['customer_phone']      = $b['user_customer_phone'] ?? '';
            $b['user_customer_phone'] = $b['customer_phone'];
            $b['phone']               = $b['customer_phone'];

            // Set data default awal dari database bawaan query-mu
            $b['real_date']    = isset($b['booking_date']) ? date('d M Y', strtotime($b['booking_date'])) : '21 May 2026';
            $b['real_time']    = !empty($b['booking_time']) ? date('H:i', strtotime($b['booking_time'])) . ' WIB' : '09:00 WIB';
            $b['real_method']  = 'gallery'; 
            $b['real_address'] = 'Pelanggan memilih datang langsung ke studio MUA.';

            $b['latitude']     = null; // Reset nilai
            $b['longitude']    = null; // Reset nilai

            // Bongkar teks di kolom notes untuk mencari jadwal kustom kiriman form customer
            if (!empty($b['notes'])) {
                if (preg_match('/Tanggal:\s*([^\n]+)/', $b['notes'], $matchesTgl)) {
                    $b['real_date'] = trim($matchesTgl[1]);
                }
                if (preg_match('/Jam Mulai:\s*([^\n]+)/', $b['notes'], $matchesJam)) {
                    $b['real_time'] = trim($matchesJam[1]);
                }
                if (strpos($b['notes'], 'Home Service') !== false) {
                    $b['real_method'] = 'home_service';
                }
                if (preg_match('/Alamat:\s*([^\n]+)/', $b['notes'], $matchesAlamat)) {
                    $b['real_address'] = trim($matchesAlamat[1]);
                }
                if (preg_match('/Koordinat:\s*([-]?\d+\.\d+),\s*([-]?\d+\.\d+)/', $b['notes'], $matchesGeo)) {
                    $b['latitude']  = trim($matchesGeo[1]);
                    $b['longitude'] = trim($matchesGeo[2]);
                }
            }
        }

        // 3. Susun data pembungkus untuk view
        $data = [
            'title'    => 'Data Booking',
            'menu'     => 'booking',
            'bookings' => $bookings
        ];

        return view('admin/bookings', $data);
    }

    public function create()
    {
        $serviceModel = new ServiceModel();
        $userModel = new UserModel();
        $scheduleModel = new \App\Models\ScheduleModel();

        $data = [
            'title'      => 'Tambah Booking',
            'menu'       => 'booking',
            'services'   => $serviceModel->findAll(),
            'customers'  => $userModel->where('role', 'customer')->findAll(),
            'schedules'  => $scheduleModel->findAll()
        ];

        return view('admin/booking_create', $data);
    }

    // Memproses penyimpanan data manual oleh admin
    public function save()
    {
        $this->bookingModel->save([

            'user_id' => $this->request->getPost('user_id'),

            'service_id' => $this->request->getPost('service_id'),

            'schedule_id' => $this->request->getPost('schedule_id'),

            'service_type' => $this->request->getPost('service_type'),

            'customer_address' => $this->request->getPost('customer_address'),

            'latitude' => $this->request->getPost('latitude'),

            'longitude' => $this->request->getPost('longitude'),

            'notes' => $this->request->getPost('notes'),

            'total_price' => $this->request->getPost('total_price'),

            'booking_status' => 'pending',

            'payment_status' => 'unpaid'

        ]);

        return redirect()->to('/admin/bookings')
            ->with('success','Booking berhasil ditambahkan');
    }

    // Aksi Konfirmasi Booking Admin + WA Otomatis PetaPod
    public function confirm($id)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, users.name as user_name, users.phone as user_phone, services.name as service_name, schedules.date as b_date')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->find($id);

        $this->bookingModel->update($id, [
            'booking_status' => 'confirmed' 
        ]);

        if ($booking) {
            $phone = $booking['user_phone'] ?? '';
            $name = $booking['user_name'] ?? $booking['customer_name'] ?? 'Pelanggan';
            $service = $booking['service_name'] ?? 'Layanan Makeup';
            $date = isset($booking['b_date']) ? date('d M Y', strtotime($booking['b_date'])) : date('d M Y');

            if (!empty($phone)) {
                $pesan = "Halo *{$name}*,\n\n"
                       . "Kabar baik! Booking Anda untuk layanan *{$service}* pada tanggal *{$date}* telah **DIKONFIRMASI** oleh Admin.\n\n"
                       . "Silakan datang tepat waktu ya. Terima kasih!";
                
                $this->waService->sendNotification($phone, $pesan);
            }
        }

        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil dikonfirmasi dan notifikasi WA terkirim!');
    }

    // Aksi Tolak/Batalkan Booking Admin
    public function cancel($id)
    {
        $this->bookingModel->update($id, [
            'booking_status' => 'cancelled'
        ]);

        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil ditolak/dibatalkan.');
    }

    public function delete($id)
    {
        $this->bookingModel->delete($id);
        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil dihapus');
    }
}