<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ServiceModel; // Ditambahkan supaya fungsi create() tidak error Class Not Found

class Booking extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    // ==========================================
    //               FITUR ADMIN
    // ==========================================

    // Menampilkan semua data booking di panel Admin
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

    // Menampilkan form tambah data oleh Admin
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


    // ==========================================
    //             FITUR CUSTOMER (Temanmu)
    // ==========================================
    
    // Diubah namanya dari index() menjadi history() agar tidak bentrok dengan milik Admin
    public function history()
    {
        // Mengambil semua data dari tabel bookings tanpa filter rumit dulu
        $bookings = $this->bookingModel->findAll(); 

        // dd($bookings); // Sengaja di-comment dulu agar halaman tidak stuck di fungsi dump ini
        
        $data = [
            'title'    => 'Riwayat Booking Saya',
            'menu'     => 'booking',
            'bookings' => $bookings 
        ];

        // Pastikan ini mengarah ke file view yang benar
        return view('customer/booking_history', $data); 
    }


    // ==========================================
    //           AKSI / PROSES DATA ADMIN
    // ==========================================

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