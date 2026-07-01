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

        // 1. Ambil data dengan select dan join asli bawaan kamu
        $rawBookings = $bookingModel
            ->select('bookings.*, users.name, users.phone, services.name as service_name')
            ->join('users','users.id = bookings.user_id')
            ->join('services','services.id = bookings.service_id')
            ->findAll();

        // 2. 🧠 LOGIC PINTAR: Bongkar kolom notes untuk mengubah data tampilan secara dinamis
        foreach ($rawBookings as &$b) {
            // Set nilai default awal dari database jika regex tidak menemukan teks kustom
            $b['real_date']    = '21 May 2026'; 
            $b['real_time']    = !empty($b['booking_time']) ? date('H:i', strtotime($b['booking_time'])) . ' WIB' : '09:00 WIB';
            $b['real_method']  = 'gallery'; 
            $b['real_address'] = 'Pelanggan memilih datang langsung ke studio MUA.';

            // Periksa jika ada catatan teks kustom hasil input form customer
            if (!empty($b['notes'])) {
                // Ekstrak Tanggal Riil
                if (preg_match('/Tanggal:\s*([^\n]+)/', $b['notes'], $matchesTgl)) {
                    $b['real_date'] = trim($matchesTgl[1]);
                }
                // Ekstrak Jam Riil
                if (preg_match('/Jam Mulai:\s*([^\n]+)/', $b['notes'], $matchesJam)) {
                    $b['real_time'] = trim($matchesJam[1]);
                }
                // Ekstrak Metode (Deteksi jika ada kata Home Service di dalam notes)
                if (strpos($b['notes'], 'Home Service') !== false) {
                    $b['real_method'] = 'home_service';
                }
                // Ekstrak Alamat Lengkap
                if (preg_match('/Alamat:\s*([^\n]+)/', $b['notes'], $matchesAlamat)) {
                    $b['real_address'] = trim($matchesAlamat[1]);
                }
            }
        }

        // Pindahkan data hasil pemrosesan pintar ke array data bookings utama
        $data['bookings'] = $rawBookings;

        $data['revenue'] = array_sum(array_column($data['bookings'],'total_price'));

        return view('admin/dashboard', $data);
    }

    // =================================================================
    // KOMPONEN 5: DETAIL INTEGRASI WHATSAPP GATEWAY (NAMA FILE BARU)
    // =================================================================
    // UTUH 100% TANPA ADA YANG DIUBAH ATAU TERLEWAT
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