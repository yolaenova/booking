<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'bookings';

    protected $allowedFields = [
        'user_id',
        'service_id',
        'schedule_id',
        'total_price',
        'booking_status',
        'payment_status'
    ];
}