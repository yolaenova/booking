<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookingModel;
use App\Models\UserModel;

class ApiController extends ResourceController
{
    protected $format = 'json';
    private $secretKey = 'MUA_SECRET_KEY_2026'; // Sebaiknya ambil dari env('API_KEY')

    /**
     * Middleware sederhana untuk validasi API Key
     */
    private function validateApiKey()
    {
        $headerKey = $this->request->getHeaderLine('X-MUA-KEY');
        if ($headerKey !== $this->secretKey) {
            return false;
        }
        return true;
    }

    public function getDashboardSummary()
    {
        if (!$this->validateApiKey()) {
            return $this->failUnauthorized('Akses Ditolak: API Key tidak valid.');
        }

        $bookingModel = new BookingModel();
        $userModel    = new UserModel();

        $payload = [
            'status'       => 200,
            'message'      => 'Data ringkasan MUA Booking berhasil dimuat.',
            'generated_at' => date('Y-m-d H:i:s'),
            'summary'      => [
                'total_booking'  => $bookingModel->countAll(),
                'total_customer' => $userModel->where('role', 'customer')->countAllResults(),
                'total_revenue'  => array_sum(array_column($bookingModel->findAll(), 'total_price'))
            ]
        ];

        return $this->respond($payload);
    }

    public function whatsappCallback()
    {
        if (!$this->validateApiKey()) {
            return $this->respond(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $json = $this->request->getJSON(true);
        
        // Memastikan payload valid
        if (!isset($json['event']) || $json['event'] !== 'message') {
            return $this->respond(['status' => 'ignored', 'message' => 'Event tidak relevan'], 200);
        }

        $pesanTeks = strtolower(trim($json['payload']['body'] ?? ''));

        if ($pesanTeks === 'batal') {
            return $this->handleCancellation();
        }

        return $this->respond(['status' => 'ignored', 'message' => 'Kata kunci tidak dikenali'], 200);
    }

    private function handleCancellation()
    {
        $bookingModel = new BookingModel();
        $booking      = $bookingModel->where('booking_status', 'process')
                                     ->orderBy('id', 'DESC')
                                     ->first();

        if ($booking) {
            $bookingModel->update($booking['id'], ['booking_status' => 'cancel']);
            return $this->respond([
                'status'  => 'success', 
                'message' => "Booking ID {$booking['id']} dibatalkan."
            ], 200);
        }

        return $this->respond(['status' => 'error', 'message' => 'Tidak ada booking aktif.'], 404);
    }
}