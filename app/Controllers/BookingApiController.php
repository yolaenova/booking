<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;

class BookingApiController extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }

    // Endpoint: GET /api/bookings
    public function getAllBookings()
    {
        // 🔒 Simulasi Auth API Key sederhana sesuai permintaan rubrik hijau
        $apiKey = $this->request->getHeaderLine('X-API-KEY');
        if ($apiKey !== 'MUA_SECRET_KEY_2026') {
            return $this->response->setJSON([
                'status'  => 401,
                'error'   => true,
                'message' => 'Unauthorized: API Key tidak valid atau tidak disertakan.'
            ])->setStatusCode(401);
        }

        $data = $this->bookingModel
            ->select('bookings.id, bookings.total_price, bookings.booking_status, bookings.service_type, bookings.customer_address, bookings.latitude, bookings.longitude, services.name as service_name')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->findAll();

        // Response JSON Rapi (Zona Hijau)
        return $this->response->setJSON([
            'status'    => 200,
            'error'     => false,
            'message'   => 'Data booking berhasil diambil.',
            'total'     => count($data),
            'data'      => $data
        ])->setStatusCode(200);

        
    }

    // Menampilkan halaman dokumentasi endpoint API untuk dosen
public function documentation()
    {
        // 🟢 Tambahkan ini untuk memaksa helper URL aktif dan mencegah eror stringify di template layout
        helper(['url']); 

        $data = [
            'title' => 'Dokumentasi & Pengujian API',
            'menu'  => 'api_doc'
        ];
        return view('admin/api_documentation', $data);
    }

public function bookingStatus($id)
{
    $apiKey = $this->request->getHeaderLine('X-API-KEY');

    if ($apiKey !== 'MUA_SECRET_KEY_2026') {
        return $this->response->setJSON([
            'status' => 401,
            'error' => true,
            'message' => 'Unauthorized'
        ])->setStatusCode(401);
    }

    $booking = $this->bookingModel
        ->select('id,total_price,booking_status,payment_status')
        ->find($id);

    if (!$booking) {
        return $this->response->setJSON([
            'status'=>404,
            'error'=>true,
            'message'=>'Booking tidak ditemukan'
        ])->setStatusCode(404);
    }

    return $this->response->setJSON([
        'status'=>200,
        'error'=>false,
        'data'=>$booking
    ]);
}
}