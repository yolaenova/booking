<?php

namespace App\Controllers;

use App\Models\ServiceModel; // Jangan lupa import model layanan

class Customer extends BaseController
{
    protected $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    // Ini fungsi yang dicari tapi tidak ada tadi
    public function services()
    {
        $data = [
            'title'    => 'Daftar Layanan Makeup',
            'services' => $this->serviceModel->findAll() // Mengambil semua data layanan
        ];

        return view('customer/services_list', $data);
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Customer'
        ];
        return view('customer/dashboard', $data);
    }

    // Tambahkan ini di dalam class Customer di app/Controllers/Customer.php

public function booking($id)
{
    // Cari data layanan berdasarkan ID yang diklik
    $service = $this->serviceModel->find($id);

    if (!$service) {
        return redirect()->back()->with('error', 'Layanan tidak ditemukan.');
    }

    $data = [
        'title'   => 'Form Booking ' . $service['name'],
        'service' => $service
    ];

    return view('customer/booking_form', $data);
}

// app/Controllers/Customer.php

public function saveBooking()
{
    $bookingModel = new \App\Models\BookingModel();

    // Pastikan session ID ada (Najwa harus login)
    $userId = session()->get('id'); 
    
    $data = [
        'user_id'          => $userId,
        'service_id'       => $this->request->getPost('service_id'),
        'total_price'      => $this->request->getPost('price'),
        'booking_date'     => $this->request->getPost('booking_date'),
        'booking_time'     => $this->request->getPost('booking_time'),
        'service_method'   => $this->request->getPost('service_method'),
        'customer_address' => $this->request->getPost('customer_address'),
        'notes'            => $this->request->getPost('notes'),
        'booking_status'   => 'pending',
        'payment_status'   => 'unpaid'
    ];

    if ($bookingModel->insert($data)) {
        return redirect()->to('/booking-history')->with('success', 'Booking kamu berhasil dikirim!');
    } else {
        return redirect()->back()->with('error', 'Waduh, gagal menyimpan booking. Coba lagi ya!');
    }
}

public function bookingHistory()
{
    $bookingModel = new \App\Models\BookingModel();
    
    // Ambil booking hanya milik user yang sedang login
    $data = [
        'title'    => 'Riwayat Booking Saya',
        'bookings' => $bookingModel->where('user_id', session()->get('id'))
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll()
    ];

    return view('customer/booking_history', $data);
}
}