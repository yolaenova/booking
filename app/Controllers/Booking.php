<?php

namespace App\Controllers;

use App\Models\BookingModel;

class Booking extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    public function index()
    {
        // Mengambil semua data dari tabel bookings tanpa filter rumit dulu
        $bookings = $this->bookingModel->findAll(); 

        dd($bookings);
        
        $data = [
            'title'    => 'Riwayat Booking Saya',
            'menu'     => 'booking',
            'bookings' => $bookings 
        ];

        // Pastikan ini mengarah ke file view yang benar
        return view('customer/booking_history', $data); 
    }
}