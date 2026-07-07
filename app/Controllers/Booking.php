<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ServiceModel; 
use App\Models\UserModel;
use App\Libraries\WhatsappService; 

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

    // Memproses penyimpanan data manual oleh admin
public function save()
    {
        // 1. Validasi tetap sama agar tidak merusak fungsi sebelumnya
        if (!$this->validate([
            'customer_name' => 'required',
            'service_id'    => 'required',
            'booking_date'  => 'required',
            'total_price'   => 'required|numeric',
        ])) {
            return redirect()->back()->withInput();
        }

        // 2. Tangkap data koordinat dan notes dari input form
        $lat   = $this->request->getPost('latitude');
        $lng   = $this->request->getPost('longitude');
        $notes = $this->request->getPost('notes') ?? '';

        // 3. Gabungkan koordinat ke dalam notes jika ada
        // Logika ini memastikan database menyimpan koordinat unik untuk setiap booking
        if (!empty($lat) && !empty($lng)) {
            $notes .= "\nKoordinat: " . $lat . ", " . $lng;
        }

        // 4. Simpan ke model (Menambahkan field 'notes' ke dalam array)
        $this->bookingModel->save([
            'user_id'        => null, 
            'customer_name'  => $this->request->getPost('customer_name'), 
            'service_id'     => $this->request->getPost('service_id'),
            'booking_date'   => $this->request->getPost('booking_date'),
            'total_price'    => $this->request->getPost('total_price'),
            'notes'          => $notes, // Field baru ditambahkan di sini
            'booking_status' => 'pending' 
        ]);

        return redirect()->to('/admin/bookings')->with('success', 'Booking manual berhasil disimpan.');
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