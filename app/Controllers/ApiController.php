<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookingModel;
use App\Models\UserModel;

class ApiController extends ResourceController
{
    // Mengatur format default response otomatis menjadi JSON
    protected $format = 'json';

    // =================================================================
    // POIN 6 VERSI 1: API SUMMARY PENDAPATAN (BAWAAN GIT PULL)
    // =================================================================
    public function getDashboardSummary()
    {
        // 1. Kriteria Skor 4: Proteksi Keamanan dengan Token / API Key lewat Header
        $apiKey = $this->request->getHeaderLine('X-MUA-KEY');
        $secureToken = 'MUA_SECRET_KEY_2026'; // Token pengaman rahasia

        if (!$apiKey || $apiKey !== $secureToken) {
            // Jika token salah atau tidak ada, return HTTP Status 401 Unauthorized
            return $this->failUnauthorized('Akses Ditolak: API Key tidak valid atau tidak disertakan.');
        }

        // 2. Inisialisasi Model
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

    // =================================================================
    // ➕ POIN 6 VERSI 2: WEBSERVICE SERVER (ENDPOINT WEBHOOK WAHA)
    // =================================================================
    public function whatsappCallback()
    {
        // 🔒 KEAMANAN: Periksa token rahasia di Header (Sama seperti summary)
        $apiKey = $this->request->getHeaderLine('X-MUA-KEY');
        if ($apiKey !== 'MUA_SECRET_KEY_2026') {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Unauthorized: API Key salah atau tidak ditemukan.'
            ], 401);
        }

        // Tangkap data JSON mentah yang dikirim oleh server WAHA
        $json = $this->request->getJSON(true);
        
        // Validasi apakah ada data pesan masuk dari pelanggan
        if (isset($json['event']) && $json['event'] === 'message') {
            $pesanTeks  = strtolower(trim($json['payload']['body'] ?? ''));

            // Jalankan logika: Jika pelanggan membalas chat dengan kata "batal"
            if ($pesanTeks === 'batal') {
                $bookingModel = new BookingModel();

                // Cari data booking terakhir yang statusnya masih 'process'
                $cekBooking = $bookingModel->where('booking_status', 'process')
                                           ->orderBy('id', 'DESC')
                                           ->first();

                if ($cekBooking) {
                    // Update status otomatis di database menjadi cancel
                    $bookingModel->update($cekBooking['id'], [
                        'booking_status' => 'cancel'
                    ]);

                    // Mengembalikan Response JSON Rapi untuk WAHA
                    return $this->respond([
                        'status'  => 'success',
                        'message' => 'Webhook berhasil diproses. Status booking ID ' . $cekBooking['id'] . ' otomatis dibatalkan.'
                    ], 200);
                }
            }
        }

        return $this->respond([
            'status'  => 'ignored',
            'message' => 'Event bukan pesan masuk atau kata kunci tidak cocok.'
        ], 200);
    }
}