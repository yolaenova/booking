<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        // SESSION SEMENTARA
        session()->set(['username' => 'Admin', 'role' => 'Administrator']);

        $bookingModel = new BookingModel();
        $userModel    = new UserModel();

        // Mengambil data dashboard utama
        $data['totalBooking']  = $bookingModel->countAll();
        $data['customers']     = $userModel->where('role', 'customer')->findAll();
        $data['totalCustomer'] = count($data['customers']);

        // Ambil dan proses data booking
        $rawBookings = $bookingModel
            ->select('bookings.*, users.name, users.phone, services.name as service_name')
            ->join('users', 'users.id = bookings.user_id')
            ->join('services', 'services.id = bookings.service_id')
            ->findAll();

        $data['bookings'] = array_map([$this, 'processBookingNotes'], $rawBookings);
        $data['revenue']  = array_sum(array_column($data['bookings'], 'total_price'));

        return view('admin/dashboard', $data);
    }

    /**
     * Helper untuk memproses isi notes menjadi data yang mudah dibaca
     */
    private function processBookingNotes(array $booking): array
    {
        // Nilai Default
        $booking['real_date']    = '21 May 2026';
        $booking['real_time']    = !empty($booking['booking_time']) ? date('H:i', strtotime($booking['booking_time'])) . ' WIB' : '09:00 WIB';
        $booking['real_method']  = 'gallery';
        $booking['real_address'] = 'Pelanggan memilih datang langsung ke studio MUA.';

        if (!empty($booking['notes'])) {
            // Regex untuk Tanggal
            if (preg_match('/Tanggal:\s*([^\n]+)/', $booking['notes'], $m)) {
                $booking['real_date'] = trim($m[1]);
            }
            // Regex untuk Jam
            if (preg_match('/Jam Mulai:\s*([^\n]+)/', $booking['notes'], $m)) {
                $booking['real_time'] = trim($m[1]);
            }
            // Deteksi Home Service
            if (strpos($booking['notes'], 'Home Service') !== false) {
                $booking['real_method'] = 'home_service';
            }
            // Regex untuk Alamat
            if (preg_match('/Alamat:\s*([^\n]+)/', $booking['notes'], $m)) {
                $booking['real_address'] = trim($m[1]);
            }
        }

        return $booking;
    }

    public function whatsapp()
    {
        $client     = \Config\Services::curlrequest();
        $wahaStatus = 'OFFLINE';

        try {
            $response = $client->request('GET', 'http://localhost:3000/api/sessions/default', ['timeout' => 3]);
            $result   = json_decode($response->getBody(), true);
            $wahaStatus = $result['status'] ?? 'DISCONNECTED';
        } catch (\Exception $e) {
            // Log error jika diperlukan: log_message('error', $e->getMessage());
        }

        return view('admin/whatsapp', [
            'title'       => 'Integrasi WhatsApp Gateway',
            'waha_status' => $wahaStatus
        ]);
    }
}