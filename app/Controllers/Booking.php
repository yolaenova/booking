<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ServiceModel; 
use App\Models\UserModel;

class Booking extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    // ==========================================
    //                 FITUR ADMIN
    // ==========================================

    // Menampilkan semua data booking di panel Admin
    public function index()
    {
        // Dioptimalkan menggunakan left join agar booking tanpa akun (input manual) tetap muncul nama pelanggannya
        $bookings = $this->bookingModel
            ->select('bookings.*, users.name AS user_customer_name, services.name AS service_name')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->findAll();

        $data = [
            'title'    => 'Data Booking',
            'menu'     => 'booking',
            'bookings' => $bookings
        ];

        return view('admin/bookings', $data);
    }

    // Menampilkan form tambah data oleh Admin
    public function create()
    {
        $serviceModel = new ServiceModel();

        $data = [
            'title'    => 'Tambah Booking',
            'menu'     => 'booking',
            'services' => $serviceModel->findAll() // Hanya butuh data layanan karena nama customer sekarang diketik bebas
        ];

        return view('admin/booking_create', $data);
    }

    // 🛠️ PERBAIKAN FUNGSI SAVE: Memproses penyimpanan data dengan input nama bebas (customer_name) & Proteksi cURL
    public function save()
    {
        if (!$this->validate([
            'customer_name' => 'required',
            'service_id'    => 'required',
            'booking_date'  => 'required',
            'total_price'   => 'required|numeric',
        ])) {
            return redirect()->back()->withInput();
        }

        // 1. Simpan Data ke Database Utama (Ini tetap berjalan mendahului API)
        $this->bookingModel->save([
            'user_id'        => null, 
            'customer_name'  => $this->request->getPost('customer_name'), 
            'service_id'     => $this->request->getPost('service_id'),
            'booking_date'   => $this->request->getPost('booking_date'),
            'total_price'    => $this->request->getPost('total_price'),
            'booking_status' => 'process' 
        ]);

        // --- ⚙️ IMPLEMENTASI POIN 5: WEBSERVICE CLIENT YANG DI-PROTEKSI TOTAL ---
        $nomorTujuan = "6289603083502"; 
        $pesanTeks   = "Halo " . $this->request->getPost('customer_name') . ",\n\nBooking MUA Anda untuk tanggal " . $this->request->getPost('booking_date') . " BERHASIL DISIMPAN oleh Admin dan sedang diproses.";

        try {
            // 💡 PENTING: Inisialisasi ditaruh di dalam try-catch. 
            // Jika ekstensi cURL Laragon mati, error langsung ditangkap di sini dan web TIDAK AKAN CRASH ORANYE.
            $client = \Config\Services::curlrequest();
            
            $client->request('POST', 'http://localhost:3000/api/sendText', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'chatId'  => $nomorTujuan . '@c.us',
                    'text'    => $pesanTeks,
                    'session' => 'default'
                ],
                'timeout' => 4 
            ]);
            
            $waMessage = ' dan Notifikasi WA berhasil dikirim!';
        } catch (\Exception $e) {
            // Poin 5: Menangkap error cURL laptop mati atau server WAHA offline
            $waMessage = ', namun Notifikasi WA gagal dikirim (Fitur WA offline / cURL lokal belum aktif).';
        }
        // --- ⚙️ AKHIR IMPLEMENTASI POIN 5 ---

        return redirect()->to('/admin/bookings')->with('success', 'Booking manual berhasil disimpan' . $waMessage);
    }


    // ==========================================
    //             FITUR CUSTOMER (Temanmu)
    // ==========================================
    
    public function history()
    {
        $bookings = $this->bookingModel->findAll(); 
        
        $data = [
            'title'    => 'Riwayat Booking Saya',
            'menu'     => 'booking',
            'bookings' => $bookings 
        ];

        return view('customer/booking_history', $data); 
    }


    // ==========================================
    //           AKSI / PROSES DATA ADMIN
    // ==========================================

    public function delete($id)
    {
        $this->bookingModel->delete($id);

        return redirect()->to('/admin/bookings')
                         ->with('success', 'Booking berhasil dihapus');
    }

    public function confirm($id)
    {
        $this->bookingModel->update($id, [
            'booking_status' => 'process'
        ]);

        return redirect()->to('/admin/bookings')
                         ->with('success', 'Booking berhasil dikonfirmasi');
    }

    public function cancel($id)
    {
        $this->bookingModel->update($id, [
            'booking_status' => 'cancel'
        ]);

        return redirect()->to('/admin/bookings')
                         ->with('success', 'Booking berhasil ditolak');
    }
}