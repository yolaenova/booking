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
        'booking_date',      // Field baru dari form
        'booking_time',      // Field baru dari form
        'service_method',    // Field baru dari form
        'customer_address',  // Field baru dari form
        'notes'              // Field baru dari form
    ];

    // Aktifkan otomatisasi waktu (created_at & updated_at)
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}