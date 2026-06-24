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
        $bookings = $this->bookingModel
            ->select('bookings.*, users.name AS user_customer_name, users.phone AS user_customer_phone, services.name AS service_name, schedules.date as booking_date, schedules.start_time as booking_time')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->orderBy('bookings.id', 'DESC')
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
            'services' => $serviceModel->findAll()
        ];

        return view('admin/booking_create', $data);
    }

    // Memproses penyimpanan data manual oleh admin
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

        $this->bookingModel->save([
            'user_id'        => null, 
            'customer_name'  => $this->request->getPost('customer_name'), 
            'service_id'     => $this->request->getPost('service_id'),
            'booking_date'   => $this->request->getPost('booking_date'),
            'total_price'    => $this->request->getPost('total_price'),
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