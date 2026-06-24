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
    // KOMPONEN 5: KONSUMSI WEBSERVICE CLIENT + ERROR HANDLING + CACHE
    // =================================================================
    public function kurs()
    {
        // 1. Inisialisasi Service Cache CI4
        $cache = \Config\Services::cache();
        
        // Cek apakah data kurs USD ke IDR sudah tersimpan di memori cache server
        $dataKurs = $cache->get('kurs_usd_idr');

        // 2. Jika cache kosong, kita ambil data baru
        if (!$dataKurs) {
            try {
                // Menggunakan native PHP stream context sebagai pengganti cURL agar bebas dari error server
                $opts = [
                    "http" => [
                        "method" => "GET",
                        "header" => "Accept: application/json\r\n",
                        "timeout" => 5
                    ]
                ];
                $context = stream_context_create($opts);

                // Membaca data API secara aman
                $response = @file_get_contents('https://open.er-api.com/v6/latest/USD', false, $context);

                if ($response !== false) {
                    $result = json_decode($response, true);
                    $dataKurs = $result['rates']['IDR'] ?? null;

                    // Skor 4: Simpan data hasil API ke cache selama 1 jam
                    if ($dataKurs) {
                        $cache->save('kurs_usd_idr', $dataKurs, 3600);
                    }
                } else {
                    $dataKurs = null;
                    session()->setFlashdata('error', 'Gagal memuat data dari server API eksternal.');
                }
            } catch (\Exception $e) {
                // Skor 4: Error Handling ketat agar aplikasi tidak crash/blank
                $dataKurs = null;
                log_message('error', 'Gagal memanggil API Kurs via Stream: ' . $e->getMessage());
                session()->setFlashdata('error', 'Koneksi ke API Kurs terputus. Menampilkan estimasi data lokal.');
            }
        }

        // 3. Kirim data ke View Admin
        return view('admin/kurs', [
            'title'    => 'Konversi Kurs Terkini MUA',
            'kurs_usd' => $dataKurs ?? 16200 // Nilai cadangan (fallback) jika internet mati
        ]);
    }
}