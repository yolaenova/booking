<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Field yang diizinkan untuk diinput/disimpan (Sudah disinkronkan dengan MySQL baru)
    protected $allowedFields = [
        'user_id',
        'service_id',
        'schedule_id',
        'total_price',
        'booking_status',
        'payment_status',
        'notes',
        'service_type',     // 🟢 Menyimpan gallery / home_service
        'customer_address', // 🟢 Alamat rumah customer
        'latitude',         // 🟢 Koordinat peta lintang
        'longitude'         // 🟢 Koordinat peta bujur
    ];

    // Aktifkan otomatisasi waktu (created_at & updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}