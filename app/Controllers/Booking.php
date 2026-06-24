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
        $serviceModel = new ServiceModel();
        $userModel = new UserModel();

        $data = [
            'title'     => 'Tambah Booking',
            'menu'      => 'booking',
            'services'  => $serviceModel->findAll(),
            'customers' => $userModel->where('role', 'customer')->findAll() // ➕ Ditambahkan agar dropdown pelanggan terisi data asli
        ];

        return view('admin/booking_create', $data);
    }

    // ➕ FUNGSI BARU: Memproses penyimpanan data form booking manual ke database
    public function save()
    {
        if (!$this->validate([
            'user_id'      => 'required',
            'service_id'   => 'required',
            'booking_date' => 'required',
            'total_price'  => 'required|numeric',
        ])) {
            return redirect()->back()->withInput();
        }

        $this->bookingModel->save([
            'user_id'        => $this->request->getPost('user_id'),
            'service_id'     => $this->request->getPost('service_id'),
            'booking_date'   => $this->request->getPost('booking_date'),
            'total_price'    => $this->request->getPost('total_price'),
            'booking_status' => 'process' // Langsung disetujui karena diinput langsung oleh admin
        ]);

        return redirect()->to('/admin/bookings')->with('success', 'Booking manual berhasil disimpan!');
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