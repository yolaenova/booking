<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Field yang diizinkan untuk diinput/disimpan
    protected $allowedFields = [
        'user_id',
        'service_id',
        'schedule_id',
        'total_price',
        'booking_status',
        'payment_status',
        'booking_date',      
        'booking_time',      
        'service_method',    // <--- PASTIKAN TETAP INI YANG ADA DI SINI
        'customer_address',  
        'notes'              
    ];

    // Aktifkan otomatisasi waktu (created_at & updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}