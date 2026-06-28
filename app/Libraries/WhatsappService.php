<?php

namespace App\Libraries;

use Config\Services;

class WhatsappService
{
    public $client;
    public $baseUrl;
    public $apiKey;
    public $sessionName;

    public function __construct()
    {
        $this->client      = Services::curlrequest();
        
        // ISI LANGSUNG DI SINI SESUAI PANEL PETAPOD KAMU
        $this->baseUrl     = 'https://project000-05a7.id-1.podo.top'; 
        $this->apiKey      = '69c71dbb8d50ed0965b2ed1d75e32bba2768222d9bb547bc';  
        
        $this->sessionName = 'default'; 
    }

    public function sendNotification($to, $message)
    {
        $formattedPhone = $this->formatNumber($to);

        try {
            $response = $this->client->request('POST', rtrim($this->baseUrl, '/') . '/api/sendText', [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'json' => [
                    'chatId'  => $formattedPhone . '@c.us',
                    'text'    => $message,
                    'session' => $this->sessionName 
                ],
                'timeout' => 12 
            ]);

            return ($response->getStatusCode() === 200 || $response->getStatusCode() === 201);
        } catch (\Exception $e) {
            log_message('error', 'Gagal kirim WA: ' . $e->getMessage());
            return false;
        }
    }

    private function formatNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}