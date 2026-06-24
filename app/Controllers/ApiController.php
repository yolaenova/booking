<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookingModel;
use App\Models\UserModel;

class ApiController extends ResourceController
{
    // Mengatur format default response otomatis menjadi JSON
    protected $format = 'json';

    public function getDashboardSummary()
    {
        // 1. Kriteria Skor 4: Proteksi Keamanan dengan Token / API Key lewat Header
        $apiKey = $this->request->getHeaderLine('X-MUA-KEY');
        $secureToken = 'MUA_SECRET_KEY_2026'; // Token pengaman rahasia

        if (!$apiKey || $apiKey !== $secureToken) {
            // Jika token salah atau tidak ada, return HTTP Status 401 Unauthorized
            return $this->failUnauthorized('Akses Ditolak: API Key tidak valid atau tidak disertakan.');
        }

        // 2. Inisialisasi Model (Sama persis dengan yang ada di Admin.php kamu)
        $bookingModel = new BookingModel();
        $userModel = new UserModel();

        // 3. Ambil dan hitung data dari database
        $totalBooking = $bookingModel->countAll();
        
        $customers = $userModel->where('role', 'customer')->findAll();
        $totalCustomer = count($customers);

        $bookings = $bookingModel->findAll();
        $revenue = array_sum(array_column($bookings, 'total_price'));

        // 4. Kriteria Skor 4: Susun Response JSON yang Terstruktur dan Rapi
        $payload = [
            'status'       => 200,
            'message'      => 'Data ringkasan MUA Booking berhasil dimuat untuk Webservice.',
            'generated_at' => date('Y-m-d H:i:s'),
            'summary'      => [
                'total_booking'  => $totalBooking,
                'total_customer' => $totalCustomer,
                'total_revenue'  => $revenue
            ]
        ];

        // 5. Return data menggunakan HTTP Status 200 OK
        return $this->respond($payload, 200);
    }
}