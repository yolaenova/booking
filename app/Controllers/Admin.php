<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function index()
    {
        // SESSION SEMENTARA
        session()->set([
            'username' => 'Admin',
            'role'     => 'Administrator'
        ]);

        $bookingModel = new BookingModel();
        $userModel = new UserModel();

        $data['totalBooking'] = $bookingModel->countAll();
        
        $data['customers'] = $userModel
    ->where('role', 'customer')
    ->findAll();

$data['totalCustomer'] = count($data['customers']);

        $data['bookings'] = $bookingModel
            ->select('bookings.*, users.name, services.name as service_name')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->findAll();

        $data['revenue'] = array_sum(array_column($data['bookings'],'total_price'));

        return view('admin/dashboard', $data);
    }

// =================================================================
    // KOMPONEN 5: DETAIL INTEGRASI WHATSAPP GATEWAY (NAMA FILE BARU)
    // =================================================================
    public function whatsapp()
    {
        $client = \Config\Services::curlrequest();
        
        try {
            // Tembak server WAHA untuk mengambil status real-time terbarunya
            $response = $client->request('GET', 'http://localhost:3000/api/sessions/default', [
                'timeout' => 3
            ]);
            $result = json_decode($response->getBody(), true);
            $wahaStatus = $result['status'] ?? 'DISCONNECTED';
        } catch (\Exception $e) {
            $wahaStatus = 'OFFLINE';
        }

        // 🛠️ SEKARANG DIALIKHAN KE VIEW admin/whatsapp
        return view('admin/whatsapp', [
            'title'       => 'Integrasi WhatsApp Gateway',
            'waha_status' => $wahaStatus
        ]);
    }
}