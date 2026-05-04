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
        $data = [
            'title'    => 'Data Booking',
            'menu'     => 'booking',
            // Ambil semua data dari database lewat model
            'bookings' => $this->bookingModel->findAll() 
        ];

        // Pastikan variabel $data dimasukkan ke dalam view
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
}