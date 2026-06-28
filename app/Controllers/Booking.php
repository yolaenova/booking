<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\ServiceModel; 
use App\Models\UserModel;
use App\Libraries\WhatsappService; 

class Booking extends BaseController
{
    protected $bookingModel;
    protected $waService;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->waService    = new WhatsappService(); 
    }

    public function index()
    {
        $bookings = $this->bookingModel
            ->select('bookings.*, users.name AS user_customer_name, users.phone AS user_customer_phone, services.name AS service_name, schedules.date as booking_date, schedules.start_time as booking_time')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->orderBy('bookings.id', 'DESC')
            ->findAll();

        $data = [
            'title'    => 'Data Booking',
            'menu'     => 'booking',
            'bookings' => $bookings
        ];

        return view('admin/bookings', $data);
    }

    public function create()
    {
        $serviceModel = new ServiceModel();
        $data = [
            'title'    => 'Tambah Booking',
            'menu'     => 'booking',
            'services' => $serviceModel->findAll()
        ];
        return view('admin/booking_create', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'customer_name' => 'required',
            'service_id'    => 'required',
            'booking_date'  => 'required',
            'total_price'   => 'required|numeric',
        ])) {
            return redirect()->back()->withInput();
        }

        $this->bookingModel->save([
            'user_id'        => null, 
            'customer_name'  => $this->request->getPost('customer_name'), 
            'service_id'     => $this->request->getPost('service_id'),
            'booking_date'   => $this->request->getPost('booking_date'),
            'total_price'    => $this->request->getPost('total_price'),
            'booking_status' => 'pending' 
        ]);

        return redirect()->to('/admin/bookings')->with('success', 'Booking manual berhasil disimpan.');
    }

    public function confirm($id)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, users.name as user_name, users.phone as user_phone, services.name as service_name, schedules.date as b_date')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->join('services', 'services.id = bookings.service_id', 'left')
            ->join('schedules', 'schedules.id = bookings.schedule_id', 'left')
            ->find($id);

        $this->bookingModel->update($id, [
            'booking_status' => 'confirmed' 
        ]);

        if ($booking) {
            $phone = $booking['user_phone'] ?? '';
            $name = $booking['user_name'] ?? $booking['customer_name'] ?? 'Pelanggan';
            $service = $booking['service_name'] ?? 'Layanan Makeup';
            $date = isset($booking['b_date']) ? date('d M Y', strtotime($booking['b_date'])) : date('d M Y');

            if (!empty($phone)) {
                $pesan = "Halo *{$name}*,\n\n"
                       . "Kabar baik! Booking Anda untuk layanan *{$service}* pada tanggal *{$date}* telah **DIKONFIRMASI** oleh Admin.\n\n"
                       . "Silakan datang tepat waktu ya. Terima kasih!";
                
                try {
                    $this->waService->sendNotification($phone, $pesan);
                } catch (\Exception $e) {
                    log_message('error', 'Gagal kirim WA: ' . $e->getMessage());
                }
            }
        }

        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil dikonfirmasi!');
    }

    public function cancel($id)
    {
        $this->bookingModel->update($id, [
            'booking_status' => 'cancelled'
        ]);
        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil ditolak/dibatalkan.');
    }

    public function delete($id)
    {
        $this->bookingModel->delete($id);
        return redirect()->to('/admin/bookings')->with('success', 'Booking berhasil dihapus');
    }

    public function show_qr()
    {
        $client = \Config\Services::curlrequest();
        
        // RUTE PALING BENAR UNTUK STRUKTUR WAHA PETAPOD KAMU
        $baseUrl = 'https://project000-05a7.id-1.podo.top';
        $apiKey  = '';

        try {
            $client->request('POST', $baseUrl . '/sessions/start', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json'
                ],
                'json' => ['name' => 'default'],
                'timeout' => 5
            ]);
            sleep(1);
        } catch (\Exception $e) {}

        try {
            $response = $client->request('GET', $baseUrl . '/default/auth/qr', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Accept'        => 'image/png'
                ],
                'http_errors' => false,
                'timeout' => 20 
            ]);
            
            $statusCode = $response->getStatusCode();
            $imageBody = $response->getBody();

            if ($statusCode === 401) {
                throw new \Exception("Akses Ditolak (401). Silakan periksa kembali kecocokan token di panel PetaPod.");
            }
            
            return $this->response->setHeader('Content-Type', 'image/png')->setBody($imageBody);

        } catch (\Exception $e) {
            return $this->response->setHeader('Content-Type', 'text/html')->setBody("
                <div style='text-align:center; margin-top:80px; font-family:sans-serif; background:#f8f9fa; padding:30px; border-radius:10px; max-width:600px; margin-left:auto; margin-right:auto; border:1px solid #ddd;'>
                    <h2 style='color:#0275d8; margin-bottom:10px;'>Menghubungkan ke WhatsApp</h2>
                    <p style='color:#666; font-size:14px;'>Status Sesi: <code style='background:#fff; padding:3px 6px; border:1px solid #ccc; color:#c7254e;'> " . htmlspecialchars($e->getMessage()) . " </code></p>
                    <p style='margin-top:20px; font-size:15px;'>Silakan klik RESET SESI lalu REFRESH HALAMAN.</p>
                    <br><br>
                    <a href='".base_url('index.php/admin/reset-wa')."' style='padding:12px 25px; background:#d9534f; color:white; text-decoration:none; border-radius:5px; font-weight:bold; margin-right:10px;'>⚠️ RESET SESI</a>
                    <a href='".base_url('index.php/admin/scan-wa')."' style='padding:12px 25px; background:#0275d8; color:white; text-decoration:none; border-radius:5px; font-weight:bold;'>🔄 REFRESH HALAMAN</a>
                </div>
            ");
        }
    }

    public function reset_wa()
    {
        $client = \Config\Services::curlrequest();
        $baseUrl = 'https://project000-05a7.id-1.podo.top';
        $apiKey  = '';

        try {
            $client->request('POST', $baseUrl . '/sessions/stop', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json'
                ],
                'json' => ['name' => 'default'],
                'timeout' => 10
            ]);
        } catch (\Exception $e) {}

        return redirect()->to(base_url('index.php/admin/scan-wa'));
    }
}