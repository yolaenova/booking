<?php

namespace App\Controllers;

use App\Models\BookingModel; // Import Model agar bisa dipakai
use App\Models\ServiceModel; // Import ServiceModel jika nanti butuh list layanan

class Booking extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        // Inisialisasi model di constructor
        $this->bookingModel = new BookingModel();
    }

    public function index()
{
    $bookings = $this->bookingModel
        ->select('bookings.*, users.name AS customer_name, services.name AS service_name')
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

    public function create()
    {
        // Fungsi untuk menampilkan form tambah data
        $serviceModel = new ServiceModel();
        $data = [
            'title'    => 'Tambah Booking',
            'menu'     => 'booking',
            'services' => $serviceModel->findAll() // Untuk dropdown pilihan makeup
        ];

        return view('admin/booking_create', $data);
    }

    public function delete($id)
{
    $bookingModel = new \App\Models\BookingModel();

    $bookingModel->delete($id);

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